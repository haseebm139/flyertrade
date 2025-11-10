<?php

namespace App\Services\Customer;

use App\Models\User;
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
        if (isset($data['avatar']) && $data['avatar']) {
            // Delete old avatar if exists and not default
            if ($user->avatar && $user->avatar !== 'assets/images/avatar/default.png') {
                $oldPath = str_replace('storage/', '', $user->avatar);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $data['avatar']->store('customer/profile', 'public');
            $updateData['avatar'] = 'storage/' . $path;
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
     * Get customer profile by ID
     *
     * @param int $id
     * @return User|null
     */
    public function getProfileById(int $id)
    {
        return User::customers()->find($id);
    }
}

