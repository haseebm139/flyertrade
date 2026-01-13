<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('image');
            $userId = auth()->id();
            
            // Generate unique filename
            $filename = 'chat/images/' . $userId . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Store file in public storage
            $path = $file->storeAs('public', $filename);
            
            // Get full URL path
            $url = Storage::url($filename);
            $fullPath = asset('storage/' . $filename);
            
            // Get image dimensions
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $width = $image->width();
            $height = $image->height();
            $size = $file->getSize();
            $mimeType = $file->getMimeType();
            
            return $this->sendResponse([
                'path' => $filename, // Relative path for Firebase upload (use this path to read file and upload to Firebase)
                'url' => $fullPath, // Full URL (temporary, until uploaded to Firebase)
                'storage_path' => $path, // Storage path
                'mime_type' => $mimeType,
                'size' => $size,
                'width' => $width,
                'height' => $height,
                'type' => 'image',
            ], 'Image uploaded successfully. Use the path to read file and upload to Firebase.');
            
        } catch (\Exception $e) {
            return $this->sendError('Failed to upload image: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Upload video for chat
     * Returns file path that can be uploaded to Firebase
     */
    public function uploadVideo(Request $request): JsonResponse
    {
        $request->validate([
            'video' => 'required|mimes:mp4,avi,mov,wmv,flv,webm|max:51200', // 50MB max
        ]);

        try {
            $file = $request->file('video');
            $userId = auth()->id();
            
            // Generate unique filename
            $filename = 'chat/videos/' . $userId . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Store file in public storage
            $path = $file->storeAs('public', $filename);
            
            // Get full URL path
            $url = Storage::url($filename);
            $fullPath = asset('storage/' . $filename);
            
            $size = $file->getSize();
            $mimeType = $file->getMimeType();
            
            // Get video duration if possible (requires ffmpeg or similar)
            $duration = null;
            // You can add video duration extraction here if needed
            
            return $this->sendResponse([
                'path' => $filename, // Relative path for Firebase upload
                'url' => $fullPath, // Full URL (temporary, until uploaded to Firebase)
                'storage_path' => $path, // Storage path
                'mime_type' => $mimeType,
                'size' => $size,
                'duration_ms' => $duration,
                'type' => 'video',
            ], 'Video uploaded successfully. Upload this file to Firebase using the path.');
            
        } catch (\Exception $e) {
            return $this->sendError('Failed to upload video: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Upload any media file (image or video)
     * Automatically detects file type
     */
    public function uploadMedia(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,webp,mp4,avi,mov,wmv,flv,webm|max:51200', // 50MB max
        ]);

        try {
            $file = $request->file('file');
            $userId = auth()->id();
            $mimeType = $file->getMimeType();
            
            // Determine file type
            $isImage = str_starts_with($mimeType, 'image/');
            $isVideo = str_starts_with($mimeType, 'video/');
            
            if (!$isImage && !$isVideo) {
                return $this->sendError('File must be an image or video.', 422);
            }
            
            $folder = $isImage ? 'chat/images' : 'chat/videos';
            $filename = $folder . '/' . $userId . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Store file
            $path = $file->storeAs('public', $filename);
            $fullPath = asset('storage/' . $filename);
            
            $size = $file->getSize();
            $response = [
                'path' => $filename,
                'url' => $fullPath,
                'storage_path' => $path,
                'mime_type' => $mimeType,
                'size' => $size,
                'type' => $isImage ? 'image' : 'video',
            ];
            
            // Add image dimensions if image
            if ($isImage) {
                try {
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($file);
                    $response['width'] = $image->width();
                    $response['height'] = $image->height();
                } catch (\Exception $e) {
                    // If image processing fails, continue without dimensions
                }
            }
            
            // Add video duration if video (optional, requires ffmpeg)
            if ($isVideo) {
                $response['duration_ms'] = null; // Add duration extraction if needed
            }
            
            return $this->sendResponse($response, 'File uploaded successfully. Upload this file to Firebase using the path.');
            
        } catch (\Exception $e) {
            return $this->sendError('Failed to upload file: ' . $e->getMessage(), 500);
        }
    }
}
