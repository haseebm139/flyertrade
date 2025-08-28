<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProviderCertificate extends Model
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

    public function service(): BelongsTo
    {
        return $this->belongsTo(ProviderService::class, 'provider_service_id');
    }
}
