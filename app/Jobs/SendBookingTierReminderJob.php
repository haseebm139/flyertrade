<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\Setting;
use App\Services\Notification\NotificationService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
/**
 * Fires at a fixed offset before the first slot (15 / 30 / 45 minutes).
 * Idempotency: {@see NotificationService} dedupes; this job also aborts if slots or status changed.
 */
final class SendBookingTierReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(
        public int $bookingId,
        public string $intervalKey,
        public string $expectedSlotStartIso,
    ) {}

    public function handle(NotificationService $notifications): void
    {
        if (! in_array($this->intervalKey, ['15m', '30m', '45m'], true)) {
            return;
        }

        $booking = Booking::query()
            ->with(['slots', 'customer', 'provider'])
            ->find($this->bookingId);

        if (! $booking || $booking->status !== 'confirmed') {
            return;
        }

        $firstSlot = $booking->slots
            ->sortBy(fn ($s) => $s->service_date.' '.$s->start_time)
            ->first();

        if (! $firstSlot) {
            return;
        }

        $slotStart = Carbon::parse($firstSlot->service_date.' '.$firstSlot->start_time);

        if ($slotStart->toIso8601String() !== $this->expectedSlotStartIso) {
            return;
        }

        if ($slotStart->lte(Carbon::now())) {
            return;
        }

        $userEnabled = (bool) Setting::get('user_reminder_enabled', false);
        $providerEnabled = (bool) Setting::get('provider_reminder_enabled', false);

        if (! $userEnabled && ! $providerEnabled) {
            return;
        }

        $userTimes = $this->normalizeTimes(Setting::get('user_reminder_times', '[]'));
        $providerTimes = $this->normalizeTimes(Setting::get('provider_reminder_times', '[]'));

        $effectiveProviderTimes = $providerTimes;
        if ($providerEnabled && $effectiveProviderTimes === [] && $userEnabled && $userTimes !== []) {
            $effectiveProviderTimes = $userTimes;
        }
        if ($providerEnabled && $effectiveProviderTimes === []) {
            $effectiveProviderTimes = ['15m', '30m', '45m'];
        }

        if ($userEnabled && in_array($this->intervalKey, $userTimes, true)) {
            $notifications->notifyBookingReminder($booking, $this->intervalKey, $slotStart);
        }

        if ($providerEnabled && in_array($this->intervalKey, $effectiveProviderTimes, true)) {
            $notifications->notifyProviderBookingReminder($booking, $this->intervalKey, $slotStart);
        }
    }

    /**
     * @return list<string>
     */
    private function normalizeTimes(string $json): array
    {
        $raw = json_decode($json, true);
        if (! is_array($raw)) {
            return [];
        }

        $allowed = ['15m', '30m', '45m'];

        return array_values(array_unique(array_intersect(
            $allowed,
            array_map('strval', array_values($raw))
        )));
    }
}
