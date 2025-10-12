<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Booking extends Model
{
    protected $fillable = [
        'booking_ref','customer_id','provider_id','service_id','provider_service_id','booking_address','booking_description',
        'status','booking_working_minutes','total_price','service_charges',
        'stripe_payment_intent_id','stripe_payment_method_id','paid_at','expires_at','booking_type'
    ];

    protected $casts = [
        'paid_at'    => 'datetime',
        'expires_at' => 'datetime',
    ];


    public function slots(): HasMany { return $this->hasMany(BookingSlot::class); }
    public function customer(): BelongsTo { return $this->belongsTo(User::class,'customer_id'); }
    public function provider(): BelongsTo { return $this->belongsTo(User::class,'provider_id'); }
    public function service(): BelongsTo { return $this->belongsTo(Service::class); }
    public function providerService(): BelongsTo { return $this->belongsTo(ProviderService::class); }

    public function getWorkingHoursAttribute(): float
    {
        return round(($this->booking_working_minutes ?? 0) / 60, 2);
    }
}
