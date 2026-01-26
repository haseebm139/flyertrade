<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\HasOne;
class ProviderService extends Model
{
     protected $guarded = [];
     protected $casts = [
         'is_primary' => 'boolean',
         'show_certificate' => 'boolean',
     ];





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

    /**
     * Get reviews for this provider service
     * Reviews are linked by service_id and receiver_id (provider user_id)
     */
    public function reviews()
    {
        return Review::where('service_id', $this->service_id)
            ->where('receiver_id', $this->user_id)
            ->where('status', 'published');
    }

    /**
     * Get reviews count for this provider service
     */
    public function getReviewsCountAttribute()
    {
        if (isset($this->attributes['reviews_count'])) {
            return (int) $this->attributes['reviews_count'];
        }
        return $this->reviews()->count();
    }

    /**
     * Get average rating for this provider service
     */
    public function getRatingAttribute()
    {
        if (isset($this->attributes['service_rating'])) {
            return round((float) $this->attributes['service_rating'], 2);
        }
        $rating = $this->reviews()->avg('rating') ?? 0;
        return round((float) $rating, 2);
    }

     public function providers()
    {
        return $this->belongsToMany(User::class);
    }
}
