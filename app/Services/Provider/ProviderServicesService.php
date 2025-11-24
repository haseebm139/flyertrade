<?php

namespace App\Services\Provider;
use App\Models\ProviderProfile;
use App\Models\ProviderService;
use App\Models\ProviderCertificate;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Shared\UserResource;
class ProviderServicesService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function get($id){
        return ProviderService::where('provider_profile_id', $id)
        ->with('service', 'certificates', 'media')
        ->orderBy('is_primary', 'desc')
        ->get();
    }
    public function create(array $data, $user)
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
        if (!empty($data['services'])) {

            $profile_id = $user->providerProfile->id;
            $service = ProviderService::create([
                'user_id'             => $user->id,
                'service_id'          => $data['services']['service_id'],
                'about'          => $data['services']['about'],
                'provider_profile_id' => $profile_id,
                'is_primary'          => $data['services']['is_primary'] ?? false,
                'title'       => $data['services']['title'],
                'description' => $data['services']['description'] ?? null,
                'staff_count'     => $data['services']['staff_count'] ?? null,
                'rate_min'            => $data['services']['rate_min'] ?? null,
                'rate_mid'            => $data['services']['rate_mid'] ?? null,
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

        return $service;
    }


    public function update(array $data, $user, $id)
    {

        $service = ProviderService::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$service) {
            return [
                'error'   => true,
                'message' => 'Service not found or not owned by this user.'
            ];
        }

        // Update main fields
        $service->update([
            'service_id'     => $data['services']['service_id'] ?? $service->service_id,
            'about'          => $data['services']['about'] ?? $service->about,
            'is_primary'     => $data['services']['is_primary'] ?? $service->is_primary,
            'title'          => $data['services']['title'] ?? $service->title,
            'description'    => $data['services']['description'] ?? $service->description,
            'staff_count'    => $data['services']['staff_count'] ?? $service->staff_count,
            'rate_min'       => $data['services']['rate_min'] ?? $service->rate_min,
            'rate_max'       => $data['services']['rate_max'] ?? $service->rate_max,
        ]);

        // Handle new photos
        if (!empty($data['services']['photos'])) {
            foreach ($data['services']['photos'] as $photo) {
                $path = $photo->store('provider/services/photos', 'public');
                $service->media()->create([
                    'provider_service_id' => $service->id,
                    'provider_profile_id' => $user->providerProfile->id,
                    'user_id'    => $user->id,
                    'file_path'  => 'storage/' . $path,
                    'type'       => 'photo',
                ]);
            }
        }

        // Handle new videos
        if (!empty($data['services']['videos'])) {
            foreach ($data['services']['videos'] as $video) {
                $path = $video->store('provider/services/videos', 'public');
                $service->media()->create([
                    'provider_service_id' => $service->id,
                    'provider_profile_id' => $user->providerProfile->id,
                    'user_id'    => $user->id,
                    'file_path'  => 'storage/' . $path,
                    'type'       => 'video',
                ]);
            }
        }

        // Handle new certificates
        if (!empty($data['services']['certificates'])) {
            foreach ($data['services']['certificates'] as $certData) {
                $path = $certData->store('provider/certificates', 'public');
                ProviderCertificate::create([
                    'provider_service_id' => $service->id,
                    'user_id'             => $user->id,
                    'provider_profile_id' => $user->providerProfile->id,
                    'file_path'           => 'storage/' . $path,
                    'status'              => 'pending',
                ]);
            }
        }

        return $service->fresh(['service', 'certificates', 'media']);
    }

    public function delete($user, $id)
    {
        $service = ProviderService::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$service) {
            return [
                'error'   => true,
                'message' => 'Service not found or not owned by this user.'
            ];
        }

        // Delete media + certificates (optional)
        $service->media()->delete();
        $service->certificates()->delete();

        $service->delete();

        return true;
    }


}
