<?php

namespace App\Services\Booking;

use App\Jobs\SendBookingTierReminderJob;
use App\Models\Booking;
use Carbon\Carbon;

/**
 * Dispatches delayed {@see SendBookingTierReminderJob} rows at fixed offsets before the first slot.
 * Uses the database queue: run `php artisan queue:work database` (or Horizon) on the server.
 */
final class BookingReminderJobScheduler
{
    /**
     * @param  list<string>  $tiers  e.g. ['45m','30m','15m']
     */
    public function scheduleForBooking(Booking $booking, array $tiers = ['45m', '30m', '15m']): void
    {
        $booking->loadMissing('slots');

        $firstSlot = $booking->slots
            ->sortBy(fn ($s) => $s->service_date.' '.$s->start_time)
            ->first();

        if (! $firstSlot) {
            return;
        }

        $slotStart = Carbon::parse($firstSlot->service_date.' '.$firstSlot->start_time);
        if ($slotStart->lte(Carbon::now())) {
            return;
        }

        $slotIso = $slotStart->toIso8601String();

        $minutesByKey = [
            '45m' => 45,
            '30m' => 30,
            '15m' => 15,
        ];

        foreach ($tiers as $key) {
            if (! isset($minutesByKey[$key])) {
                continue;
            }

            $runAt = $slotStart->copy()->subMinutes($minutesByKey[$key]);
            if ($runAt->isFuture()) {
                SendBookingTierReminderJob::dispatch($booking->id, $key, $slotIso)
                    ->delay($runAt);
            }
        }
    }
}
