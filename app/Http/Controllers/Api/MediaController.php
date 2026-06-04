<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Support\MediaStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaController extends BaseController
{
    /**
     * Upload image for chat
     * Returns file path that can be uploaded to Firebase
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        try {
            $file = $request->file('image');
            $userId = auth()->id();
            $directory = 'chat/images/'.$userId;
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

            $stored = $this->storeMediaFile($file, $directory, $filename);

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);

            return $this->sendResponse([
                'path' => $stored['database'],
                'url' => $stored['url'],
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'width' => $image->width(),
                'height' => $image->height(),
                'type' => 'image',
            ], 'Image uploaded successfully. Use the path to read file and upload to Firebase.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to upload image: '.$e->getMessage(), 500);
        }
    }

    /**
     * Upload video for chat
     * Returns file path that can be uploaded to Firebase
     */
    public function uploadVideo(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'video' => 'required|mimes:mp4,avi,mov,wmv,flv,webm|max:51200', // 50MB max
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        try {
            $file = $request->file('video');
            $userId = auth()->id();
            $directory = 'chat/videos/'.$userId;
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

            $stored = $this->storeMediaFile($file, $directory, $filename);

            return $this->sendResponse([
                'path' => $stored['database'],
                'url' => $stored['url'],
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'type' => 'video',
            ], 'Video uploaded successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to upload video: '.$e->getMessage(), 500);
        }
    }

    /**
     * Upload any media file (image or video)
     * Automatically detects file type
     */
    public function uploadMedia(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,webp,mp4,avi,mov,wmv,flv,webm|max:51200', // 50MB max
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        try {
            $file = $request->file('file');
            $userId = auth()->id();
            $mimeType = $file->getMimeType();

            $isImage = str_starts_with($mimeType, 'image/');
            $isVideo = str_starts_with($mimeType, 'video/');

            if (! $isImage && ! $isVideo) {
                return $this->sendError('File must be an image or video.', 422);
            }

            $folder = $isImage ? 'chat/images' : 'chat/videos';
            $directory = $folder.'/'.$userId;
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();

            $stored = $this->storeMediaFile($file, $directory, $filename);

            $response = [
                'path' => $stored['database'],
                'url' => $stored['url'],
                'mime_type' => $mimeType,
                'size' => $file->getSize(),
                'type' => $isImage ? 'image' : 'video',
            ];

            if ($isImage) {
                try {
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($file);
                    $response['width'] = $image->width();
                    $response['height'] = $image->height();
                } catch (\Exception $e) {
                    // continue without dimensions
                }
            }

            if ($isVideo) {
                $response['duration_ms'] = null;
            }

            return $this->sendResponse($response, 'File uploaded successfully. Upload this file to Firebase using the path.');
        } catch (\Exception $e) {
            return $this->sendError('Failed to upload file: '.$e->getMessage(), 500);
        }
    }

    /**
     * @return array{relative: string, database: string, url: string|null}
     */
    private function storeMediaFile(UploadedFile $file, string $directory, string $filename): array
    {
        $stored = MediaStorage::storeUploaded($file, $directory, $filename);

        if (! MediaStorage::exists($stored['database'])) {
            throw new \RuntimeException('Failed to store file. Please check storage permissions.');
        }

        return $stored;
    }
}
