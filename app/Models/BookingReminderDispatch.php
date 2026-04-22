<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingReminderDispatch extends Model
{
    protected $fillable = [
        'booking_id',
        'recipient_user_id',
        'notification_type',
        'interval_key',
        'slot_starts_at',
        'fire_at',
        'status',
        'failure_reason',
        'processed_at',
    ];

    protected $casts = [
        'slot_starts_at' => 'datetime',
        'fire_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }
}
