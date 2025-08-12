<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderServices extends Model
{
    protected $table = 'provider_services';

    protected $fillable = [
        'user_id','service_id','is_primary','title','description',
        'staff_count','service_photos','service_video','rate_min','rate_max'
    ];

    protected $casts = [
        'service_photos' => 'array',
        'is_primary' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
