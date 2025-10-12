<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\HasOne;
class ProviderService extends Model
{
     protected $guarded = [];





    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(ProviderProfile::class, 'provider_profile_id');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(ProviderCertificate::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProviderServiceMedia::class, 'provider_service_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

     public function providers()
    {
        return $this->belongsToMany(User::class);
    }
}
