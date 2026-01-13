<?php

namespace App\Observers;

use App\Models\User;
use App\Models\ProviderProfile;
use App\Models\ProviderWorkingHour;
use App\Services\Notification\NotificationService;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Check if user is provider by user_type or role_id
        // Note: hasRole() might not work here because role is assigned AFTER user creation
        $isProvider = false;
        
        // Check user_type first (set during creation)
        if (isset($user->user_type) && in_array($user->user_type, ['provider', 'multi'])) {
            $isProvider = true;
        }
        
        // Also check role_id if exists (set during creation)
        if (!$isProvider && isset($user->role_id) && in_array($user->role_id, ['provider', 'multi'])) {
            $isProvider = true;
        }
        
        // Fallback: check hasRole if available (might work if role was assigned before save)
        if (!$isProvider && method_exists($user, 'hasRole')) {
            try {
                if ($user->hasRole('provider')) {
                    $isProvider = true;
                }
            } catch (\Exception $e) {
                // hasRole might fail if roles table not ready, ignore
            }
        }
        
        if ($isProvider) {
            $profile = ProviderProfile::create([
                'user_id' => $user->id,
            ]);

            ProviderWorkingHour::seedDefaultHours($user->id, $profile->id);
            
            // Send notification to admin about new provider registration
            // Use try-catch to prevent observer from breaking user creation
            try {
                $notificationService = app(NotificationService::class);
                $notificationService->notifyNewProviderRegistered($user);
            } catch (\Exception $e) {
                // Log error but don't break user creation
                \Log::error('Failed to send provider registration notification: ' . $e->getMessage());
            }
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
