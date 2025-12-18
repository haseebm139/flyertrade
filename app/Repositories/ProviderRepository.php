<?php

namespace App\Repositories;


use App\Models\User;
use App\Models\Bookmark;
use App\Models\Review;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\Shared\UserResource;
class ProviderRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    /**
     * Attach bookmark flag to provider query
     */
    protected function attachBookmarkFlag($query, $userId)
    {
        $query->withExists(['bookmarkedBy as is_bookmarked' => function ($q) use ($userId) {
            $q->where('user_id', $userId);
        }]);
    }
    /**
     * Get top providers with filters
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getProviders(array $filters = [], $userId = null, $perPage = 15)
    {
         $query = User::query()
            ->where('role_id', 'provider')
            ->with(['providerProfile', 
                    'providerProfile.services' => function($q) {
                        $q->with(['service', 'media', 'certificates']);
                    },
                    'providerProfile.workingHours']) // eager load
            ->withCount([
                'providerBookings as provider_bookings_count',
                'providerServices as provider_services_count',
                'publishedReviews as published_reviews_count'
            ])
            ->withAvg('publishedReviews as published_reviews_avg_rating', 'rating');

            // , 'ratings'
        // 🔹 Filter by Provider Name
        // if (!empty($filters['provider_name'])) {
        //     $query->where('name', 'like', '%' . $filters['provider_name'] . '%');
        // }

        // 🔹 Filter by Service Name
        if (!empty($filters['service_name'])) {

            $query->whereHas('providerServices.service', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['service_name'] . '%');
            });
        }

        if (!empty($filters['service_id'])) {

            $query->whereHas('providerServices.service', function ($q) use ($filters) {
                $q->where('id', $filters['service_id'] );
            });
        }

        // 🔹 Filter by Price Range
        // if (!empty($filters['min_price']) && !empty($filters['max_price'])) {
        //     $query->whereHas('providerServices', function ($q) use ($filters) {
        //         $q->where(function ($qq) use ($filters) {
        //             $qq->whereBetween('rate_min', [$filters['min_price'], $filters['max_price']])
        //                ->orWhereBetween('rate_max', [$filters['min_price'], $filters['max_price']]);
        //         });
        //     });
        // }

        // 🔹 Filter by Rating (withAvg is cleaner than havingRaw inside whereHas)
        // if (!empty($filters['min_rating'])) {
        //     $query->withAvg('ratings', 'rating')
        //         ->having('ratings_avg_rating', '>=', $filters['min_rating']);
        // }

        // 🔹 Filter by Distance (Haversine Formula)
        // if (!empty($filters['latitude']) && !empty($filters['longitude']) && !empty($filters['distance'])) {
        //     $lat = $filters['latitude'];
        //     $lng = $filters['longitude'];
        //     $distance = $filters['distance'];

        //     $query->select('users.*', DB::raw("(
        //         6371 * acos(
        //             cos(radians($lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($lng))
        //             + sin(radians($lat)) * sin(radians(latitude))
        //         )
        //     ) AS distance"))
        //     ->having('distance', '<=', $distance)
        //     ->orderBy('distance');
        // }

        // 🔹 Sort by Top Rating (if no min_rating given but sorting requested)
        // if (!empty($filters['sort_by']) && $filters['sort_by'] === 'rating') {
        //     $query->withAvg('ratings', 'rating')->orderByDesc('ratings_avg_rating');
        // }

        // 🔹 Attach Bookmark Flag
        if ($userId) {
            $this->attachBookmarkFlag($query, $userId);
        }

        // Default order (if nothing else applied)
        if (empty($filters['sort_by'])) {
            $query->latest();
        }
        $perPage = $filters['per_page'] ?? 10; // default 10 per page
        $providers = $query->paginate($perPage);
        
        // Optimize: Batch load all reviews data in single queries (eliminates N+1)
        $providerIds = $providers->getCollection()->pluck('id')->toArray();
        
        if (!empty($providerIds)) {
            // Get all provider services mapping
            $providerServicesMap = ProviderService::whereIn('user_id', $providerIds)
                ->get(['id', 'user_id', 'service_id'])
                ->groupBy('user_id');
            
            // Get all service_ids for these providers
            $serviceIds = ProviderService::whereIn('user_id', $providerIds)
                ->pluck('service_id')
                ->unique()
                ->toArray();
            
            if (!empty($serviceIds)) {
                // Single query: Get all reviews stats (count + avg rating) for all provider-service combinations
                $reviewsStats = Review::where('status', 'published')
                    ->whereIn('receiver_id', $providerIds)
                    ->whereIn('service_id', $serviceIds)
                    ->select(
                        'service_id',
                        'receiver_id',
                        DB::raw('COUNT(*) as reviews_count'),
                        DB::raw('AVG(rating) as rating')
                    )
                    ->groupBy('service_id', 'receiver_id')
                    ->get()
                    ->keyBy(function($item) {
                        return $item->service_id . '_' . $item->receiver_id;
                    });
                
                // Single query: Get all reviews with reviewer info, then group in memory
                $allReviews = Review::where('status', 'published')
                    ->whereIn('receiver_id', $providerIds)
                    ->whereIn('service_id', $serviceIds)
                    ->with(['reviewer:id,name,avatar'])
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy(function($review) {
                        return $review->service_id . '_' . $review->receiver_id;
                    })
                    ->map(function($reviews) {
                        // Take only latest 5 reviews per service
                        return $reviews->take(3)->values();
                    });
                
                // Attach data to services (in-memory operation, very fast)
                $providers->getCollection()->transform(function ($provider) use ($reviewsStats, $allReviews) {
                    if ($provider->providerProfile && $provider->providerProfile->services) {
                        foreach ($provider->providerProfile->services as $service) {
                            $key = $service->service_id . '_' . $provider->id;
                            
                            // Get stats from pre-loaded data
                            $stats = $reviewsStats->get($key);
                            $reviewsCount = $stats ? (int) $stats->reviews_count : 0;
                            $rating = $stats ? round((float) $stats->rating, 2) : 0;
                            
                            // Get reviews from pre-loaded data
                            $reviews = $allReviews->get($key) ?? collect();
                            
                            $service->setAttribute('reviews_count', $reviewsCount);
                            $service->setAttribute('rating', $rating);
                            $service->setAttribute('reviews', $reviews);
                        }
                    }
                    return $provider;
                });
            }
        }
        
        return $providers;
    }


    public function providerProfile($id)
    {
        $userId = auth()->id(); // current logged in user (customer)

        $user = User::with([
            'providerProfile',
            'workingHours',
            'providerProfile.services' => function($q) {
                $q->with(['service', 'media', 'certificates']);
            }
        ])
        ->withCount([
            'providerBookings as provider_bookings_count',
            'providerServices as provider_services_count',
            'publishedReviews as published_reviews_count'
        ])
        ->withAvg('publishedReviews as published_reviews_avg_rating', 'rating')
        ->when($userId, function ($q) use ($userId) {
            $q->withExists([
                'bookmarkedBy as is_bookmarked' => function ($q2) use ($userId) {
                    $q2->where('user_id', $userId);
                }
            ]);
        })
        ->findOrFail($id);

        // Add complete details to provider services (reviews count, rating, reviews list)
        if ($user->providerProfile && $user->providerProfile->services) {
            $serviceIds = $user->providerProfile->services->pluck('service_id')->toArray();
            
            if (!empty($serviceIds)) {
                // Single query: Get all reviews stats (count + avg rating) for all services
                $reviewsStats = Review::where('status', 'published')
                    ->where('receiver_id', $user->id)
                    ->whereIn('service_id', $serviceIds)
                    ->select(
                        'service_id',
                        DB::raw('COUNT(*) as reviews_count'),
                        DB::raw('AVG(rating) as rating')
                    )
                    ->groupBy('service_id')
                    ->get()
                    ->keyBy('service_id');
                
                // Single query: Get all reviews with reviewer info, then group in memory
                $allReviews = Review::where('status', 'published')
                    ->where('receiver_id', $user->id)
                    ->whereIn('service_id', $serviceIds)
                    ->with(['reviewer:id,name,avatar'])
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->groupBy('service_id')
                    ->map(function($reviews) {
                        // Take only latest 3 reviews per service
                        return $reviews->take(3)->values();
                    });
                
                // Attach data to services
                foreach ($user->providerProfile->services as $service) {
                    // Get stats from pre-loaded data
                    $stats = $reviewsStats->get($service->service_id);
                    $reviewsCount = $stats ? (int) $stats->reviews_count : 0;
                    $rating = $stats ? round((float) $stats->rating, 2) : 0;
                    
                    // Get reviews from pre-loaded data
                    $reviews = $allReviews->get($service->service_id) ?? collect();
                    
                    $service->setAttribute('reviews_count', $reviewsCount);
                    $service->setAttribute('rating', $rating);
                    $service->setAttribute('reviews', $reviews);
                }
            }
        }

        return $user;
    }

    /**
     * Toggle bookmark for a provider
     */
    public function toggleBookmark(int $userId, int $providerId)
    {
        $bookmark = Bookmark::where('user_id', $userId)
            ->where('provider_id', $providerId)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return [
                'status'  => false,
                'message' => 'Provider removed from bookmarks',
            ];
        } else {
            $newBookmark = Bookmark::create([
                'user_id'     => $userId,
                'provider_id' => $providerId,
            ]);

            return [
                'status'  => true,
                'message' => 'Provider bookmarked successfully',
                'data'    => $newBookmark
            ];
        }
    }

    /**
     * Get all bookmarks for a user
     */
    public function getBookmarks(int $userId)
    {  
        return Bookmark::with('provider.providerProfile','provider.providerServices.service','provider.providerServices.media','provider.providerServices.certificates','provider.providerProfile.workingHours') // eager load provider info
            ->where('user_id', $userId)
            ->get();
    }
}
