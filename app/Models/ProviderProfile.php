<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProviderProfile extends Model
{
    protected $guarded = [];
    // Belongs to a user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Has many services
    public function services(): HasMany
    {
        return $this->hasMany(ProviderService::class)
            ->orderByDesc('is_primary'); // primary services come first
    }

    public function workingHours(): HasMany
    {
        return $this->hasMany(ProviderWorkingHour::class) 
            ->orderBy('day');
    }

    // Has many certificates
    public function certificates(): HasMany
    {
        return $this->hasMany(ProviderCertificate::class);
    }

    // Has many media
    public function media(): HasMany
    {
        return $this->hasMany(ProviderServiceMedia::class);
    }
}
