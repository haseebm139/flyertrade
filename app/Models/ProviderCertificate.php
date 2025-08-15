<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderCertificate extends Model
{
    protected $guarded = [];

    public function profile()
    {
        return $this->belongsTo(ProviderProfile::class, 'provider_profile_id');
    }
}
