<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'booking_id',
        'customer_id',
        'provider_id',
        'transaction_ref',
        'type',
        'status',
        'amount',
        'service_charges',
        'net_amount',
        'currency',
        'stripe_payment_intent_id',
        'stripe_payment_method_id',
        'stripe_charge_id',
        'stripe_customer_id',
        'processed_at',
        'completed_at',
        'failed_at',
        'failure_reason',
        'metadata',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'service_charges' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the booking that owns the transaction
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the customer that made the transaction
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the provider for the transaction
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    /**
     * Generate unique transaction reference
     */
    public static function generateRef(): string
    {
        return 'TXN-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
    }
}
