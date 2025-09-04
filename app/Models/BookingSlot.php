<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class BookingSlot extends Model
{
    protected $fillable = ['booking_id','service_date','start_time','end_time','duration_minutes'];
    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
}
