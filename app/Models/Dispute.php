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
     * Payload for mobile/web: whether the user can still open a new dispute for this booking,
     * and whether admin has marked the existing dispute resolved.
     *
     * Per auth user: dispute UI only for their own row (`booking_id` + `user_id`).
     * `can_report` is true until this user has filed; the other party disputing does not block them.
     *
     * @param  Collection<int, self>|null  $disputesForBooking  Optional pre-loaded disputes for this booking (batch).
     */
    public static function incidentReportUi(?Booking $booking, ?int $viewerUserId = null, ?Collection $disputesForBooking = null): array
    {
        if (! $booking) {
            return [
                'can_report' => true,
                'dispute_id' => null,
                'dispute_resolved' => false,
            ];
        }

        $viewerUserId ??= auth()->id();

        $rows = $disputesForBooking ?? self::query()->where('booking_id', $booking->id)->get();
        $rows = collect($rows);

        if ($rows->isEmpty()) {
            return [
                'can_report' => true,
                'dispute_id' => null,
                'dispute_resolved' => false,
            ];
        }

        $mine = $viewerUserId
            ? $rows->first(fn (self $d) => (int) $d->user_id === (int) $viewerUserId)
            : null;

        $canReport = $mine === null;

        $resolved = $mine && strtolower((string) $mine->status) === 'resolved';

        return [
            'can_report' => $canReport,
            'dispute_id' => $mine?->id,
            'dispute_resolved' => (bool) $resolved,
        ];
    }
}
