<?php

namespace App\Services\Provider;

use App\Models\ProviderProfile;
use App\Models\ProviderService;
use App\Models\ProviderCertificate;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Shared\UserResource;
use App\Services\Notification\NotificationService;
use DB;
class ProviderProfileService
{
    public function __construct(private NotificationService $notificationService) {}
    public function createOrUpdateProfile(array $data, $user)
    {
        $user = $user->load('providerProfile');
        
        // Check if user is actually a provider
        $isProvider = $user->hasRole('provider') || 
                     $user->user_type === 'provider' || 
                     $user->user_type === 'multi';
        
         
        
        // Ensure provider profile exists (only for providers)
        if ($isProvider && !$user->providerProfile) {
            ProviderProfile::create([
                'user_id' => $user->id,
            ]);
            // Reload the relationship after creating
            $user->refresh();
            $user->load('providerProfile');
        }
        
        // Check if services data exists and has service_id
        if (!empty($data['services']) && isset($data['services']['service_id'])) {
            $existingService = ProviderService::where('user_id', $user->id)
                ->where('service_id', $data['services']['service_id'])
                ->first();
            if ($existingService) {
                return [
                    'error'   => true,
                    'message' => 'This service is already assigned to your profile.'
                ];
            }
        }

        // ✅ Save profile
        $profileData = collect($data)->only([
            'country', 'city', 'state', 'zip',
            'office_address', 'latitude', 'longitude'
        ])->toArray();
        
        $profileImg = null;
        if (isset($data['avatar'])) {
            $path = $data['avatar']->store('provider/profile', 'public');
            $profileImg = 'storage/' . $path;

            $profileData['profile_photo'] = $profileImg;
        }

        $coverPhotoPath = null;
        if (isset($data['cover_photo'])) {
            // Delete old cover photo if exists
            if ($user->cover_photo) {
                $oldPath = str_replace('storage/', '', $user->cover_photo);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $data['cover_photo']->store('provider/profile', 'public');
            $coverPhotoPath = 'storage/' . $path;
        }
        
        $profileData['about_me'] = isset($data['services']['about']) ? $data['services']['about'] : null;
        if (isset($data['office_address'])) {
            $data['address'] = $data['office_address'];
        }
        
        $updateData = collect($data)->only([
            'country', 'city', 'state', 'zip',
            'address', 'latitude', 'longitude','phone','is_booking_notification','is_promo_option_notification'
        ])->toArray();
         
         
        if ($profileImg !== null) {
            $updateData['avatar'] = $profileImg;
        }

        if ($coverPhotoPath !== null) {
            $updateData['cover_photo'] = $coverPhotoPath;
        }

        // Save FCM token if provided
        if (isset($data['fcm_token'])) {
            $updateData['fcm_token'] = $data['fcm_token'];
        }

        if (!empty($updateData)) {
            $user->update($updateData);
             
        }

        $idPhotoPath = null;
        
        if (isset($data['id_photo'])) {
            // Delete old cover photo if exists
            if ($user->providerProfile && $user->providerProfile->id_photo) {
                $oldPath = str_replace('storage/', '', $user->providerProfile->id_photo);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $data['id_photo']->store('provider/profile', 'public');
            $idPhotoPath = 'storage/' . $path;
        }
        $passportPath = null;
        
        if (isset($data['passport'])) {
            // Delete old passport if exists
            if ($user->providerProfile && $user->providerProfile->passport) {
                $oldPath = str_replace('storage/', '', $user->providerProfile->passport);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $data['passport']->store('provider/profile', 'public');
            $passportPath = 'storage/' . $path;
        }

        $workPermitPath = null;
         
        if (isset($data['work_permit'])) {
            // Delete old work permit if exists
            if ($user->providerProfile && $user->providerProfile->work_permit) {
                $oldPath = str_replace('storage/', '', $user->providerProfile->work_permit);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $data['work_permit']->store('provider/profile', 'public');
            $workPermitPath = 'storage/' . $path;
        }
        if ($idPhotoPath !== null) {
            $profileData['id_photo'] = $idPhotoPath;
            $profileData['id_photo_status'] = 'pending'; // Set status to pending when uploaded
        }

        if ($passportPath !== null) {
            $profileData['passport'] = $passportPath;
            $profileData['passport_status'] = 'pending'; // Set status to pending when uploaded
        }
        if ($workPermitPath !== null) {
            $profileData['work_permit'] = $workPermitPath;
            $profileData['work_permit_status'] = 'pending'; // Set status to pending when uploaded
        }
         
        // Track if any document was uploaded
        $documentUploaded = $idPhotoPath !== null || $passportPath !== null || $workPermitPath !== null;
         
        // Final check: Only allow provider profile creation/update for providers
        if ($isProvider) {
             
            $profile = ProviderProfile::updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
            // Send notification to admin if any document was uploaded
            if ($documentUploaded) {
                try {
                    $result = $this->notificationService->notifyDocumentVerificationPending(0, $user->id);
                     
                    \Log::info('Document verification notification sent. Count: ' . $result);
                } catch (\Exception $e) {
                    // Log error but don't break profile creation
                    \Log::error('Failed to send document verification notification: ' . $e->getMessage());
                    \Log::error('Stack trace: ' . $e->getTraceAsString());
                }
            }
            // ✅ Save services
            if (!empty($data['services']) && isset($data['services']['service_id'])) {
                $service = ProviderService::create([
                    'user_id'             => $user->id,
                    'service_id'          => $data['services']['service_id'],
                    'provider_profile_id' => $profile->id,
                    'is_primary'          => $data['services']['is_primary'] ?? false,
                    'show_certificate'    => $data['services']['show_certificate'] ?? true,
                    'title'               => $data['services']['title'] ?? null,
                    'description'         => $data['services']['description'] ?? null,
                    'staff_count'         => $data['services']['staff_count'] ?? null,
                    'rate_min'            => $data['services']['rate_min'] ?? null,
                    'rate_max'            => $data['services']['rate_max'] ?? null,
                ]);
    
                // Photos
                if (!empty($data['services']['photos'])) {
                    foreach ($data['services']['photos'] as $photo) {
                        $path = $photo->store('provider/services/photos', 'public');
    
                        $service->media()->create([
                            'provider_service_id' => $service->id,
                            'provider_profile_id' => $profile->id,
                            'user_id'    => $user->id,
                            'file_path'  => 'storage/' . $path,
                            // 'file_path'  => Storage::disk('s3')->url($path),
                            'type'       => 'photo',
                        ]);
                    }
                }
    
                // Videos
                if (!empty($data['services']['videos'])) {
    
                    foreach ($data['services']['videos'] as $video) {
                        $path = $video->store('provider/services/videos', 'public');
                        $service->media()->create([
                            'provider_service_id' => $service->id,
                            'provider_profile_id' => $profile->id,
                            'user_id'    => $user->id,
                            'file_path'  => 'storage/' . $path,
                            // 'file_path'  => Storage::disk('s3')->url($path),
                            'type'       => 'video',
                        ]);
                    }
                }
                if (!empty($data['services']['certificates'])) {
                    foreach ($data['services']['certificates'] as $certData) {
    
                        $path = $certData->store('provider/certificates', 'public');
                        ProviderCertificate::create([
                            'provider_service_id' => $service->id,
                            'user_id'             => $user->id,
                            'provider_profile_id' => $profile->id,
                            'file_path'           => 'storage/' . $path?? null,
                            // 'file_path'           => Storage::disk('s3')->url($path),
                            'status'              => 'pending',
                        ]);
    
                    }
                }
            }
        }
         
        return $this->getProfile($user->id);
        // return $user->load(
        //     'providerProfile',
        //     // 'providerProfile.services',
        //     // 'providerProfile.services.media',
        //     // 'providerProfile.services.certificates'
        // );
    }

    public function getProfile($user)
    {
        // Handle both User model instance and user ID
        if (!$user instanceof User) {
            $user = User::find($user);
        }
        
        if (!$user) {
            throw new \Exception('User not found');
        }
        
        // Check if user is actually a provider
        $isProvider = $user->hasRole('provider') || 
                     $user->user_type === 'provider' || 
                     $user->user_type === 'multi';
        
        // Only create provider profile if user is a provider
        if ($isProvider && !$user->providerProfile) {
            ProviderProfile::create([
                'user_id' => $user->id,
            ]);
            // Reload the relationship after creating
            $user->refresh();
            $user->load('providerProfile');
        }
        
        return $user->load(
            'providerProfile',
            // 'providerProfile.services',
            // 'providerProfile.services.service',
            // 'providerProfile.services.media',
            // 'providerProfile.services.certificates'
        ); 
    }

    public function changeAvailabilityStatus($data, $user)
    {

        $provider = ProviderProfile::where('user_id', $user->id)->first();

        if (!$provider) {
            return false;
        }

        $provider->update([
            'availability_status' => $data['availability_status']
        ]);

        return $provider; // return the updated model instead of just true/false

    }

    public function workingHours($user)
    {
        $workingHours = $user->providerProfile->workingHours;
        return $workingHours;
    }

    public function createOrUpdateWorkingHours($data, $user)
    {
        DB::beginTransaction();

        try {

            foreach ($data['working_hours'] as $dayData) {
                $user->providerProfile->workingHours()->updateOrCreate(
                    ['user_id' => $user->id, 'day' => $dayData['day']],
                    [
                        'start_time' => $dayData['start_time'] ?? null,
                        'end_time' => $dayData['end_time'] ?? null,
                        'is_active' => $dayData['is_active'],
                    ]
                );
            }
            DB::commit();
                return [
                'error' => false,
                'message' => 'Working hours saved successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }


    }
}
