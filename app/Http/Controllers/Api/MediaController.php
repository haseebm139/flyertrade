<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
        ]);
        
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        try {
            $file = $request->file('image');
            $userId = auth()->id();
            
            // Generate unique filename
            $directory = 'chat/images/' . $userId;
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory($directory);
            
            // Store file in public storage
            $path = $file->storeAs($directory, $filename, 'public');
            
            // Verify file was stored successfully
            if (!Storage::disk('public')->exists($path)) {
                return $this->sendError('Failed to store file. Please check storage permissions.', 500);
            }
            
            // Get full URL path - use Storage::url() which handles the storage link
            $fullPath = Storage::disk('public')->url($path);
            
            // Get image dimensions
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $width = $image->width();
            $height = $image->height();
            $size = $file->getSize();
            $mimeType = $file->getMimeType();
            
            return $this->sendResponse([
                'path' => $path, // Relative path for Firebase upload (use this path to read file from storage/app/public/{path})
                'url' => $fullPath, // Full URL (accessible via storage link: https://flyertrade.com/storage/{path})
                'storage_path' => 'storage/app/public/' . $path, // Full storage path for reading file
                'absolute_path' => storage_path('app/public/' . $path), // Absolute path on server
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
        
        $validator = Validator::make($request->all(), [
            'video' => 'required|mimes:mp4,avi,mov,wmv,flv,webm|max:51200', // 50MB max 
        ]);
        
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }
        try {
            $file = $request->file('video');
            $userId = auth()->id();
            
            // Generate unique filename
            $directory = 'chat/videos/' . $userId;
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory($directory);
            
            // Store file in public storage
            $path = $file->storeAs($directory, $filename, 'public');
            
            // Verify file was stored successfully
            if (!Storage::disk('public')->exists($path)) {
                return $this->sendError('Failed to store file. Please check storage permissions.', 500);
            }
            
            // Get full URL path - use Storage::url() which handles the storage link
            $fullPath = Storage::disk('public')->url($path);
            
            $size = $file->getSize();
            $mimeType = $file->getMimeType();
            
            // Get video duration if possible (requires ffmpeg or similar)
            $duration = null;
            // You can add video duration extraction here if needed
            
            return $this->sendResponse([
                'path' => $path, // Relative path for Firebase upload (use this path to read file from storage/app/public/{path})
                'url' => $fullPath, // Full URL (accessible via storage link: https://flyertrade.com/storage/{path})
                'storage_path' => 'storage/app/public/' . $path, // Full storage path for reading file
                'absolute_path' => storage_path('app/public/' . $path), // Absolute path on server
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
            
            // Determine file type
            $isImage = str_starts_with($mimeType, 'image/');
            $isVideo = str_starts_with($mimeType, 'video/');
            
            if (!$isImage && !$isVideo) {
                return $this->sendError('File must be an image or video.', 422);
            }
            
            $folder = $isImage ? 'chat/images' : 'chat/videos';
            $directory = $folder . '/' . $userId;
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Ensure directory exists
            Storage::disk('public')->makeDirectory($directory);
            
            // Store file in public storage
            $path = $file->storeAs($directory, $filename, 'public');
            
            // Verify file was stored successfully
            if (!Storage::disk('public')->exists($path)) {
                return $this->sendError('Failed to store file. Please check storage permissions.', 500);
            }
            
            // Get full URL path - use Storage::url() which handles the storage link
            $fullPath = Storage::disk('public')->url($path);
            
            $size = $file->getSize();
            $response = [
                'path' => $path, // Relative path for Firebase upload
                'url' => $fullPath, // Full URL (accessible via storage link: https://flyertrade.com/storage/{path})
                'storage_path' => 'storage/app/public/' . $path, // Full storage path for reading file
                'absolute_path' => storage_path('app/public/' . $path), // Absolute path on server
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
