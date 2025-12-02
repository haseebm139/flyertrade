<?php

namespace App\Repositories;


use App\Models\User;
use App\Models\Bookmark;

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
            ->with(['providerProfile', 'providerServices.service', 'providerServices.media', 'providerServices.certificates','providerProfile.workingHours']); // eager load

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
        return $query->paginate($perPage);
    }


    public function providerProfile($id)
    {
        $userId = auth()->id(); // current logged in user (customer)

        $user = User::with([
            'providerProfile',
            'workingHours',
            'providerProfile.services',
            'providerProfile.services.service',
            'providerProfile.services.media',
            'providerProfile.services.certificates'
        ])
        ->when($userId, function ($q) use ($userId) {
            $q->withExists([
                'bookmarkedBy as is_bookmarked' => function ($q2) use ($userId) {
                    $q2->where('user_id', $userId);
                }
            ]);
        })
        ->findOrFail($id);

        return new UserResource($user);


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
