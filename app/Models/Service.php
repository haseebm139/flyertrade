<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = ['name','slug', 'description', 'icon', 'status'];



    // Automatically generate slug on create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = static::generateUniqueSlug($service->name);
            }
        });
    }

    // Function to generate unique slug
    protected static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
     
    public function providerServices()
    {
        return $this->hasMany(ProviderService::class);
    }

    public function providers()
    {
        return $this->belongsToMany(User::class, 'provider_services')
                    ->withPivot(['id','is_primary','title','description','staff_count','rate_min','rate_max'])
                    // ->withPivotCount()
                    ->withTimestamps();
    }
}
