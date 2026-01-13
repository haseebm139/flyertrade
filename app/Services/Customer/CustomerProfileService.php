<?php

namespace App\Services\Customer;

use App\Models\User;
use App\Models\ProviderProfile;
use Illuminate\Support\Facades\Storage;

class CustomerProfileService
{
    /**
     * Update user profile (works for both customer and provider)
     *
     * @param array $data
     * @param User $user
     * @return User
     */
    public function updateProfile(array $data, User $user)
    {
        $updateData = [];
        $avatarPath = null;

        // Handle avatar upload
        if (isset($data['avatar']) && $data['avatar']) {
            // Delete old avatar if exists and not default
            if ($user->avatar && $user->avatar !== 'assets/images/avatar/default.png') {
                $oldPath = str_replace('storage/', '', $user->avatar);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Store in appropriate directory based on user type
            $directory = ($user->user_type === 'provider' || $user->user_type === 'multi') 
                ? 'provider/profile' 
                : 'customer/profile';
            
            $path = $data['avatar']->store($directory, 'public');
            $avatarPath = 'storage/' . $path;
            $updateData['avatar'] = $avatarPath;
        }
       
        // Handle cover photo upload
        if (isset($data['cover_photo']) && $data['cover_photo']) {
            // Delete old cover photo if exists
            if ($user->cover_photo) {
                $oldPath = str_replace('storage/', '', $user->cover_photo);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Store in appropriate directory based on user type
            $directory = ($user->user_type === 'provider' || $user->user_type === 'multi') 
                ? 'provider/profile' 
                : 'customer/profile';
            
            $path = $data['cover_photo']->store($directory, 'public');
            $coverPhotoPath = 'storage/' . $path;
            $updateData['cover_photo'] = $coverPhotoPath;
        }

        // Update other fields
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

        // Update location fields if provided
        if (isset($data['country'])) {
            $updateData['country'] = $data['country'];
        }

        if (isset($data['city'])) {
            $updateData['city'] = $data['city'];
        }

        if (isset($data['state'])) {
            $updateData['state'] = $data['state'];
        }

        if (isset($data['zip'])) {
            $updateData['zip'] = $data['zip'];
        }

        if (isset($data['latitude'])) {
            $updateData['latitude'] = $data['latitude'];
        }

        if (isset($data['longitude'])) {
            $updateData['longitude'] = $data['longitude'];
        }

        if(isset($data['fcm_token'])) {
            $updateData['fcm_token'] = $data['fcm_token'];
        }
        $user->update($updateData);

        // If user is a provider, also update ProviderProfile
        $isProvider = $user->user_type === 'provider' || $user->user_type === 'multi' || $user->hasRole('provider');
        
        if ($isProvider) {
            $providerProfileData = [];
            
            // Sync location fields to ProviderProfile
            if (isset($data['country'])) {
                $providerProfileData['country'] = $data['country'];
            }
            if (isset($data['city'])) {
                $providerProfileData['city'] = $data['city'];
            }
            if (isset($data['state'])) {
                $providerProfileData['state'] = $data['state'];
            }
            if (isset($data['zip'])) {
                $providerProfileData['zip'] = $data['zip'];
            }
            if (isset($data['latitude'])) {
                $providerProfileData['latitude'] = $data['latitude'];
            }
            if (isset($data['longitude'])) {
                $providerProfileData['longitude'] = $data['longitude'];
            }
            
            // Handle office_address (provider-specific field)
            if (isset($data['office_address'])) {
                $providerProfileData['office_address'] = $data['office_address'];
            }
            
            // Handle avatar - also update profile_photo in ProviderProfile
            if ($avatarPath) {
                $providerProfileData['profile_photo'] = $avatarPath;
            }
            
            // Update or create ProviderProfile
            if (!empty($providerProfileData)) {
                ProviderProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    $providerProfileData
                );
            }
        }

        return $user->fresh();
    }

    /**
     * Get customer profile
     *
     * @param User $user
     * @return User
     */
    public function getProfile(User $user)
    {
        return $user;
    }

    /**
     * Get user profile by ID (works for both customer and provider)
     *
     * @param int $id
     * @return User|null
     */
    public function getProfileById(int $id)
    {
        return User::find($id);
    }
}

