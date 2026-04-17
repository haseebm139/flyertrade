<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     */
    public static function incidentReportUi(?self $dispute): array
    {
        if (! $dispute) {
            return [
                'can_report' => true,
                'dispute_id' => null,
                'dispute_resolved' => false,
            ];
        }

        $resolved = strtolower((string) $dispute->status) === 'resolved';

        return [
            'can_report' => false,
            'dispute_id' => $dispute->id,
            'dispute_resolved' => $resolved,
        ];
    }
}
