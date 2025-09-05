<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingReschedule extends Model
{
     

    protected $fillable = [
        'booking_id',
        'requested_by',
        'old_slots',
        'new_slots',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'old_slots' => 'array',
        'new_slots' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
