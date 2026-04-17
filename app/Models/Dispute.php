<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Dispute extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'message',
        'attachment',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Latest dispute row per booking id (for list UIs).
     *
     * @param  list<int|string>  $bookingIds
     * @return Collection<string, self> keyed by booking_id
     */
    public static function latestPerBookingForIds(array $bookingIds): Collection
    {
        if ($bookingIds === []) {
            return collect();
        }

        return static::query()
            ->whereIn('booking_id', $bookingIds)
            ->orderByDesc('id')
            ->get()
            ->unique('booking_id')
            ->keyBy('booking_id');
    }

    /**
     * Payload for mobile/web: whether the user can still open a new dispute for this booking.
     * The app should choose button labels locally from the can_report flag.
     */
    public static function incidentReportUi(?self $dispute): array
    {
        if (! $dispute) {
            return [
                'can_report' => true,
                'dispute_id' => null,
            ];
        }

        return [
            'can_report' => false,
            'dispute_id' => $dispute->id,
        ];
    }
}
