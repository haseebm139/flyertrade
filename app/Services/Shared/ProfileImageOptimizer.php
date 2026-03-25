<?php

namespace App\Services\Shared;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;

class ProfileImageOptimizer
{
    /**
     * Built-in / static paths that must never be deleted from disk.
     */
    public function isDefaultOrStaticAssetPath(?string $urlPath): bool
    {
        if ($urlPath === null || $urlPath === '') {
            return true;
        }
        $lower = strtolower($urlPath);
        if (str_contains($lower, 'default.png') || str_contains($lower, 'default.jpg') || str_contains($lower, 'default.jpeg')) {
            return true;
        }
        if (str_starts_with($lower, 'assets/') || str_contains($lower, '/assets/images/')) {
            return true;
        }
        if (str_starts_with($urlPath, 'http://') || str_starts_with($urlPath, 'https://')) {
            return true;
        }
        // Only uploaded files live under storage/ — never delete anything else
        if (! str_starts_with($urlPath, 'storage/')) {
            return true;
        }

        return false;
    }

    /**
     * Remove a file from the public disk given a URL-style path like `storage/foo/bar.jpg`.
     */
    public function deletePublicStoragePath(?string $urlPath): void
    {
        if ($this->isDefaultOrStaticAssetPath($urlPath)) {
            return;
        }
        $relative = str_replace('storage/', '', $urlPath);
        $relative = ltrim($relative, '/');
        if ($relative !== '' && Storage::disk('public')->exists($relative)) {
            Storage::disk('public')->delete($relative);
        }
    }

    /**
     * Store an optimized (resized + compressed) JPEG. Same pipeline as customer profile.
     *
     * @return string|null Path like `storage/<dir>/<file>.jpg`
     */
    public function storeOptimizedJpeg(UploadedFile $file, string $directory, int $maxWidth, int $maxHeight, int $quality = 80): ?string
    {
        $disk = Storage::disk('public');

        $storeRaw = function () use ($disk, $file, $directory): ?string {
            try {
                $disk->makeDirectory($directory);
                $rawPath = rtrim($directory, '/') . '/' . $file->hashName();
                $bytes = $file->getRealPath() && is_readable($file->getRealPath())
                    ? file_get_contents($file->getRealPath())
                    : $file->get();
                $disk->put($rawPath, $bytes);

                return 'storage/' . $rawPath;
            } catch (\Throwable $e) {
                \Log::error('Failed to store raw file fallback: ' . $e->getMessage());

                return null;
            }
        };

        $driver = null;
        if (extension_loaded('gd')) {
            $driver = new Driver();
        } elseif (extension_loaded('imagick') && class_exists(\Intervention\Image\Drivers\Imagick\Driver::class)) {
            $driver = new \Intervention\Image\Drivers\Imagick\Driver();
        } else {
            return $storeRaw();
        }

        $manager = new ImageManager($driver);
        try {
            $image = $manager->read($file);
        } catch (\Throwable $e) {
            \Log::warning('Profile image read failed, using raw store: ' . $e->getMessage());

            return $storeRaw();
        }
        $image->scaleDown($maxWidth, $maxHeight);

        $encoded = (string) $image->encode(new JpegEncoder(quality: $quality));

        if ($encoded === '') {
            return $storeRaw();
        }

        try {
            $disk->makeDirectory($directory);
            $filename = Str::uuid()->toString() . '.jpg';
            $path = rtrim($directory, '/') . '/' . $filename;
            $disk->put($path, $encoded);

            return 'storage/' . $path;
        } catch (\Throwable $e) {
            \Log::error('Failed to store optimized image: ' . $e->getMessage());

            return $storeRaw();
        }
    }
}
