<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaStorage
{
    public static function usesS3(): bool
    {
        return filter_var(config('filesystems.use_s3', false), FILTER_VALIDATE_BOOLEAN);
    }

    public static function diskName(): string
    {
        return self::usesS3() ? 's3' : 'public';
    }

    public static function disk()
    {
        return Storage::disk(self::diskName());
    }

    /**
     * Relative path inside the disk (no storage/ prefix).
     */
    public static function toRelativePath(?string $storedPath): string
    {
        if ($storedPath === null || $storedPath === '') {
            return '';
        }

        if (str_starts_with($storedPath, 'http://') || str_starts_with($storedPath, 'https://')) {
            $path = parse_url($storedPath, PHP_URL_PATH) ?: '';

            return ltrim($path, '/');
        }

        return ltrim(str_replace('storage/', '', $storedPath), '/');
    }

    /**
     * Value saved in DB: storage/... locally, full URL on S3.
     */
    public static function pathForDatabase(string $relativePath): string
    {
        $relativePath = ltrim($relativePath, '/');

        if (self::usesS3()) {
            return self::disk()->url($relativePath);
        }

        return 'storage/'.$relativePath;
    }

    /**
     * Public URL for API / views.
     */
    public static function url(?string $storedPath): ?string
    {
        if ($storedPath === null || $storedPath === '') {
            return null;
        }

        if (str_starts_with($storedPath, 'http://') || str_starts_with($storedPath, 'https://')) {
            return $storedPath;
        }

        $relative = self::toRelativePath($storedPath);

        if ($relative === '') {
            return null;
        }

        if (self::usesS3()) {
            return self::disk()->url($relative);
        }

        return asset('storage/'.$relative);
    }

    public static function exists(?string $storedPath): bool
    {
        $relative = self::toRelativePath($storedPath);

        if ($relative === '') {
            return false;
        }

        if (str_starts_with($storedPath, 'http://') || str_starts_with($storedPath, 'https://')) {
            return Storage::disk('public')->exists($relative) || Storage::disk('s3')->exists($relative);
        }

        return self::disk()->exists($relative);
    }

    /**
     * Delete from S3 or local public disk (handles legacy paths).
     */
    public static function delete(?string $storedPath): void
    {
        if ($storedPath === null || $storedPath === '') {
            return;
        }

        $relative = self::toRelativePath($storedPath);

        if ($relative === '') {
            return;
        }

        foreach (['public', 's3'] as $diskName) {
            $disk = Storage::disk($diskName);
            if ($disk->exists($relative)) {
                $disk->delete($relative);
            }
        }
    }

    public static function makeDirectory(string $directory): void
    {
        if (! self::usesS3()) {
            self::disk()->makeDirectory($directory);
        }
    }

    public static function store(UploadedFile $file, string $directory): string
    {
        self::makeDirectory($directory);

        $relativePath = $file->store($directory, self::diskName());

        return self::pathForDatabase($relativePath);
    }

    public static function storeAs(string $directory, string $filename, UploadedFile $file): string
    {
        self::makeDirectory($directory);

        $relativePath = $file->storeAs($directory, $filename, self::diskName());

        return self::pathForDatabase($relativePath);
    }

    /**
     * @return array{relative: string, database: string, url: string}
     */
    public static function storeUploaded(UploadedFile $file, string $directory, ?string $filename = null): array
    {
        self::makeDirectory($directory);

        $relativePath = $filename !== null
            ? $file->storeAs($directory, $filename, self::diskName())
            : $file->store($directory, self::diskName());

        return [
            'relative' => $relativePath,
            'database' => self::pathForDatabase($relativePath),
            'url' => self::url(self::pathForDatabase($relativePath)),
        ];
    }
}
