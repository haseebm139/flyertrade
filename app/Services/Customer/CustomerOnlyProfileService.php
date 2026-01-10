<?php

namespace App\Services\Customer;

use App\Models\User;
use App\Models\Review;
 
use App\Models\Bookmark; 
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerOnlyProfileService
{
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
            $path = 'customer/profile/' . $file->hashName();  

            // File content ko seedhe disk mein likhein
            try {
                Storage::disk('public')->put(
                    $path, 
                    file_get_contents($file->getRealPath())
                );
                $updateData['avatar'] = 'storage/' . $path;
                \Log::info('File saved successfully using put method: ' . $path);
            } catch (\Exception $e) {
                \Log::error('File saving failed with PUT method: ' . $e->getMessage());
            }
        }
         
        if (isset($data['cover_photo']) && $data['cover_photo']) {
            $file = $data['cover_photo'];  
            $path = 'customer/profile/' . $file->hashName();  

             
            try {
                Storage::disk('public')->put(
                    $path, 
                    file_get_contents($file->getRealPath())
                );
                $updateData['cover_photo'] = 'storage/' . $path;
                \Log::info('File saved successfully using put method: ' . $path);
            } catch (\Exception $e) {
                \Log::error('File saving failed with PUT method: ' . $e->getMessage());
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
                'providerBookings as provider_bookings_count',
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

