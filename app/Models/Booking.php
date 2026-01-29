<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Booking extends Model
{
    protected $fillable = [
        'booking_ref','customer_id','provider_id','service_id','provider_service_id','booking_address','booking_description',
        'status','booking_working_minutes','total_price','service_charges',
        'stripe_payment_intent_id','stripe_payment_method_id','paid_at','expires_at','booking_type','cancelled_reason','cancelled_at',
        'late_action_taken','late_action_type','late_action_at',
        'reschedule_initiated_by','reschedule_response'
    ];

    protected $casts = [
        'paid_at'    => 'datetime',
        'expires_at' => 'datetime',
        'late_action_at' => 'datetime',
        'late_action_taken' => 'boolean',
    ];


    public function slots(): HasMany { return $this->hasMany(BookingSlot::class); }
    public function customer(): BelongsTo { return $this->belongsTo(User::class,'customer_id'); }
    public function provider(): BelongsTo { return $this->belongsTo(User::class,'provider_id'); }
    public function service(): BelongsTo { return $this->belongsTo(Service::class); }
    public function providerService(): BelongsTo { return $this->belongsTo(ProviderService::class); }
    public function review(): HasOne { return $this->hasOne(Review::class); }

    public function getWorkingHoursAttribute(): float
    {
        return round(($this->booking_working_minutes ?? 0) / 60, 2);
    }

    public function getFormattedDurationAttribute(): string
    {
        $minutes = $this->booking_working_minutes ?? 0;
        if ($minutes < 60) {
            return $minutes . 'm';
        }
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        
        if ($remainingMinutes === 0) {
            return $hours . 'h';
        }
        
        return $hours . 'h ' . $remainingMinutes . 'm';
    }

    /**
     * Check if review has been given for this booking
     */
    public function getIsReviewGivenAttribute(): bool
    {
        return $this->review()->exists();
    }

    public function reschedules()
    {
        return $this->hasMany(BookingReschedule::class);
    }

    public function latestPendingReschedule()
    {
        return $this->hasOne(BookingReschedule::class)
            ->where('status', 'pending')
            ->latest();
    }
}
