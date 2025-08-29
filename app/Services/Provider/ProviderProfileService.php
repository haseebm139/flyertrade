<?php

namespace App\Services\Provider;

use App\Models\ProviderProfile;
use App\Models\ProviderService;
use App\Models\ProviderCertificate;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Shared\UserResource;
use DB;
class ProviderProfileService
{
    public function createOrUpdateProfile(array $data, $user)
    {

        $existingService = ProviderService::where('user_id', $user->id)
        ->where('service_id', $data['services']['service_id'])
        ->first();
        if ($existingService) {
            return [
                'error'   => true,
                'message' => 'This service is already assigned to your profile.'
            ];
        }

        // ✅ Save profile
        $profileData = collect($data)->only([
            'country', 'city', 'state', 'zip',
            'office_address', 'latitude', 'longitude'
        ])->toArray();
        if (isset($data['avatar'])) {
            $path = $data['avatar']->store('provider/profile', 'public');
            $profileImg = 'storage/' . $path;

            $profileData['profile_photo'] = $profileImg;
        }
        $profileData['about_me'] = $data['services']['about'] ?? null;

        $user->update([
            'avatar' => $profileImg
        ]);
        $profile = ProviderProfile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );
        // ✅ Save services
        if (!empty($data['services'])) {


            $service = ProviderService::create([
                'user_id'             => $user->id,
                'service_id'          => $data['services']['service_id'],
                'provider_profile_id' => $profile->id,
                'is_primary'          => $data['services']['is_primary'] ?? false,
                'title'       => $data['services']['title'],
                'description' => $data['services']['description'] ?? null,
                'staff_count'     => $data['services']['staff_count'] ?? null,
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

        return $user->load(
            'providerProfile',
            'providerProfile.services',
            'providerProfile.services.media',
            'providerProfile.services.certificates'
        );
    }

    public function getProfile($user)
    {

        $user->load(
            'providerProfile',
            'providerProfile.services',
            'providerProfile.services.service',
            'providerProfile.services.media',
            'providerProfile.services.certificates'
        );
        return new UserResource($user);
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
