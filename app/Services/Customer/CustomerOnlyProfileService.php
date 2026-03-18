<?php

namespace App\Services\Customer;

use App\Models\User;
use App\Models\Review;
 
use App\Models\Bookmark; 
use App\Models\ProviderService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
class CustomerOnlyProfileService
{
    /**
     * Store an optimized (resized + compressed) JPEG image.
     *
     * @return string|null Storage URL path like `storage/<dir>/<file>.jpg`
     */
    private function storeOptimizedJpeg($file, string $directory, int $maxWidth, int $maxHeight, int $quality = 80): ?string
    {
        $disk = Storage::disk('public');
        
        
        // Fallback: store raw file if neither GD nor Imagick is available.
        $storeRaw = function () use ($disk, $file, $directory): ?string {
            try {
                $disk->makeDirectory($directory);
                $rawPath = rtrim($directory, '/') . '/' . $file->hashName();
                $disk->put($rawPath, file_get_contents($file->getRealPath()));
                return 'storage/' . $rawPath;
            } catch (\Throwable $e) {
                \Log::error('Failed to store raw file fallback: ' . $e->getMessage());
                return null;
            }
        };
 
        $driver = null;
        if (extension_loaded('gd')) {
            
            $driver = new Driver();
        } elseif (extension_loaded('imagick') && class_exists(\Intervention\Image\Drivers\Imagick\Driver::class)) {
              
            $driver = new \Intervention\Image\Drivers\Imagick\Driver();
        } else {
              
            return $storeRaw();
        }
        
        $manager = new ImageManager($driver);
        $image = $manager->read($file);
        $image->scaleDown($maxWidth, $maxHeight);

         
        $encoded = (string) $image->encode(new JpegEncoder(quality: $quality));
         
        if ($encoded === '') {
            return $storeRaw();
        }

        $disk->makeDirectory($directory);
        $filename = Str::uuid()->toString() . '.jpg';
        $path = rtrim($directory, '/') . '/' . $filename;
        $disk->put($path, $encoded);
        dd($path);
        return 'storage/' . $path;
        try {
        } catch (\Throwable $e) {
            \Log::error('Failed to store optimized image: ' . $e->getMessage());
            return $storeRaw();
        }
    }

    /**
     * Update customer profile (only basic fields)
     *
     * @param array $data
     * @param User $user
     * @return User
     */
    public function updateProfile(array $data, User $user)
    {
         
        $updateData = [];

        // Handle avatar upload
        // if (isset($data['avatar']) && $data['avatar']) {
        //     // Delete old avatar if exists and not default
        //     if ($user->avatar && $user->avatar !== 'assets/images/avatar/default.png') {
        //         $oldPath = str_replace('storage/', '', $user->avatar);
        //         if (Storage::disk('public')->exists($oldPath)) {
        //             Storage::disk('public')->delete($oldPath);
        //         }
        //     }

        //     $path = $data['avatar']->store('customer/profile', 'public');
        //     $updateData['avatar'] = 'storage/' . $path;
        // }
        
        if (isset($data['avatar']) && $data['avatar']) {
            $file = $data['avatar'];
              
            $optimizedPath = $this->storeOptimizedJpeg(
                $file,
                'customer/profile',
                256,
                256,
                80
            );

            if ($optimizedPath) {
                $updateData['avatar'] = $optimizedPath;
            }
        }
         
        if (isset($data['cover_photo']) && $data['cover_photo']) {
            $file = $data['cover_photo'];
            $optimizedPath = $this->storeOptimizedJpeg(
                $file,
                'customer/profile',
                1024,
                512,
                80
            );

            if ($optimizedPath) {
                $updateData['cover_photo'] = $optimizedPath;
            }
        }
        
        // Update other fields (only allowed fields for customer)
        if (isset($data['name'])) {
            $updateData['name'] = $data['name'];
        }

        if (isset($data['email'])) {
            $updateData['email'] = $data['email'];
            // Reset email verification if email changed
            if ($user->email !== $data['email']) {
                $updateData['email_verified_at'] = null;
            }
        }

        if (isset($data['phone'])) {
            $updateData['phone'] = $data['phone'];
        }

        if (isset($data['address'])) {
            $updateData['address'] = $data['address'];
        }

        if(isset($data['country'])) {
            $updateData['country'] = $data['country'];
        }
        if(isset($data['city'])) {
            $updateData['city'] = $data['city'];
        }
        if(isset($data['state'])) {
            $updateData['state'] = $data['state'];
        }
        if(isset($data['zip'])) {
            $updateData['zip'] = $data['zip'];
        }
        if(isset($data['latitude'])) {
            $updateData['latitude'] = $data['latitude'];
        }
        if(isset($data['longitude'])) {
            $updateData['longitude'] = $data['longitude'];
        }

        if(isset($data['fcm_token'])) {
            $updateData['fcm_token'] = $data['fcm_token'];
        }
        $user->update($updateData); 
        
        return $user->load('providerProfile');
    }

    /**
     * Get customer profile
     *
     * @param User $user
     * @return User
     */
    public function getProfile(User $user)
    {
         
        $user->load([
            'providerProfile',
            'workingHours',  
            
        ])
        ->loadCount([
                'providerBookings as provider_bookings_count' => function ($query) {
                    $query->where('status', 'completed');
                },
                'providerServices as provider_services_count',
                'publishedReviews as published_reviews_count'
            ])
            ->loadAvg('publishedReviews as published_reviews_avg_rating', 'rating');
         
        return $user;
    }

    /**
     * Get customer profile by ID
     *
     * @param int $id
     * @return User|null
     */
    public function getProfileById(int $id)
    {
        return User::with('providerProfile')->find($id);
    }
}

