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
                'show_certificate'    => $data['services']['show_certificate'] ?? true,
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
                        'provider_profile_id' => $profile_id,
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
                        'provider_profile_id' => $profile_id,
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
                        'provider_profile_id' => $profile_id,
                        'file_path'           => 'storage/' . $path ?? null,
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
            'show_certificate'  => $data['services']['show_certificate'] ?? $service->show_certificate,
            'title'          => $data['services']['title'] ?? $service->title,
            'description'    => $data['services']['description'] ?? $service->description,
            'staff_count'    => $data['services']['staff_count'] ?? $service->staff_count,
            'rate_min'       => $data['services']['rate_min'] ?? $service->rate_min,
            'rate_mid'       => $data['services']['rate_mid'] ?? $service->rate_mid,
            'rate_max'       => $data['services']['rate_max'] ?? $service->rate_max,
        ]);

        // Handle delete photos (by IDs)
        if (!empty($data['services']['delete_photos']) && is_array($data['services']['delete_photos'])) {
            $photosToDelete = $service->media()
                ->where('type', 'photo')
                ->whereIn('id', $data['services']['delete_photos'])
                ->get();
            
            foreach ($photosToDelete as $photo) {
                // Delete file from storage
                $filePath = str_replace('storage/', '', $photo->file_path);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                $photo->delete();
            }
        }

        // Handle delete videos (by IDs)
        if (!empty($data['services']['delete_videos']) && is_array($data['services']['delete_videos'])) {
            $videosToDelete = $service->media()
                ->where('type', 'video')
                ->whereIn('id', $data['services']['delete_videos'])
                ->get();
            
            foreach ($videosToDelete as $video) {
                // Delete file from storage
                $filePath = str_replace('storage/', '', $video->file_path);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                $video->delete();
            }
        }

        // Handle delete certificates (by IDs)
        if (!empty($data['services']['delete_certificates']) && is_array($data['services']['delete_certificates'])) {
            $certsToDelete = $service->certificates()
                ->whereIn('id', $data['services']['delete_certificates'])
                ->get();
            
            foreach ($certsToDelete as $cert) {
                // Delete file from storage
                $filePath = str_replace('storage/', '', $cert->file_path);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                $cert->delete();
            }
        }

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

        return $service->load('service', 'certificates', 'media');
    }

    public function delete($user, $id)
    {
        // Handle multiple IDs (comma-separated or array)
        if (is_string($id) && strpos($id, ',') !== false) {
            $ids = array_map('trim', explode(',', $id));
        } elseif (is_array($id)) {
            $ids = $id;
        } else {
            $ids = [$id];
        }

        // Get all services owned by user
        $services = ProviderService::whereIn('id', $ids)
            ->where('user_id', $user->id)
            ->get();

        if ($services->isEmpty()) {
            return [
                'error'   => true,
                'message' => 'No services found or not owned by this user.'
            ];
        }

        $deletedCount = 0;
        foreach ($services as $service) {
            // Delete media + certificates
            $service->media()->delete();
            $service->certificates()->delete();
            $service->delete();
            $deletedCount++;
        }

        $message = $deletedCount === 1 
            ? 'Service deleted successfully.' 
            : 'Services deleted successfully.';

        return [
            'error' => false,
            'message' => $message,
            'deleted_count' => $deletedCount
        ];
    }


}
