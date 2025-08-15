<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderService extends Model
{
     protected $guarded = [];

    public function media()
    {
        return $this->hasMany(ProviderServiceMedia::class, 'provider_service_id');
    }

    public function certificates()
    {
        return $this->hasMany(ProviderCertificate::class, 'provider_service_id');
    }
}
