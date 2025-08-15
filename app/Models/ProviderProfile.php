<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(ProviderService::class);
    }

    public function certificates()
    {
        return $this->hasMany(ProviderCertificate::class);
    }
}
