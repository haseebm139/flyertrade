<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\BookingReminderDispatch;
use App\Models\Setting;
use App\Services\Notification\NotificationService;
use Carbon\Carbon;

class BookingReminderDispatchService
{
    private const INTERVAL_MINUTES = [
        '15m' => 15,
        '30m' => 30,
        '45m' => 45,
    ];

    public function syncForBooking(Booking $booking): void
    {
        if ($booking->status !== 'confirmed') {
            return;
        }

        $firstSlot = $booking->slots
            ->sortBy(fn ($s) => $s->service_date . ' ' . $s->start_time)
            ->first();

        if (! $firstSlot) {
            return;
        }

        $slotStart = Carbon::parse($firstSlot->service_date . ' ' . $firstSlot->start_time);

        $settings = $this->resolveReminderSettings();
        if (! $settings['will_send_user'] && ! $settings['will_send_provider']) {
            return;
        }

        foreach (self::INTERVAL_MINUTES as $key => $minutes) {
            $fireAt = $slotStart->copy()->subMinutes($minutes);

            if ($settings['user_enabled'] && in_array($key, $settings['user_times'], true)) {
                $this->upsertDispatch(
                    $booking,
                    (int) $booking->customer_id,
                    'booking_reminder',
                    $key,
                    $slotStart,
                    $fireAt
                );
            }

            if ($settings['provider_enabled'] && in_array($key, $settings['effective_provider_times'], true)) {
                $this->upsertDispatch(
                    $booking,
                    (int) $booking->provider_id,
                    'provider_booking_reminder',
                    $key,
                    $slotStart,
                    $fireAt
                );
            }
        }
    }

    public function syncQueuedForAllConfirmedBookings(): void
    {
        $settings = $this->resolveReminderSettings();
        if (! $settings['will_send_user'] && ! $settings['will_send_provider']) {
            return;
        }

        Booking::query()
            ->where('status', 'confirmed')
            ->with(['slots'])
            ->chunkById(100, function ($bookings) {
                foreach ($bookings as $booking) {
                    $this->syncForBooking($booking);
                }
            });
    }

    public function processDue(NotificationService $notifications): int
    {
        $processed = 0;

        BookingReminderDispatch::query()
            ->where('status', 'pending')
            ->where('fire_at', '<=', now())
            ->orderBy('id')
            ->chunkById(100, function ($dispatches) use ($notifications, &$processed) {
                foreach ($dispatches as $dispatch) {
                    $processed += $this->processOneDispatch($dispatch, $notifications) ? 1 : 0;
                }
            });

        return $processed;
    }

    public function removeAllForBooking(int $bookingId): void
    {
        BookingReminderDispatch::query()->where('booking_id', $bookingId)->delete();
    }

    public function resyncAfterSlotChange(Booking $booking): void
    {
        $this->removeAllForBooking($booking->id);
        $booking->load('slots');
        $this->syncForBooking($booking);
    }

    private function upsertDispatch(
        Booking $booking,
        int $recipientUserId,
        string $notificationType,
        string $intervalKey,
        Carbon $slotStart,
        Carbon $fireAt
    ): void {
        $pending = [
            'fire_at' => $fireAt,
            'status' => 'pending',
            'failure_reason' => null,
            'processed_at' => null,
        ];

        if ($fireAt->lte(now())) {
            $pending['status'] = 'failed';
            $pending['failure_reason'] = 'fire_time_already_passed_before_queue_could_send';
            $pending['processed_at'] = now();
        }

        BookingReminderDispatch::query()->firstOrCreate(
            [
                'booking_id' => $booking->id,
                'recipient_user_id' => $recipientUserId,
                'notification_type' => $notificationType,
                'interval_key' => $intervalKey,
                'slot_starts_at' => $slotStart,
            ],
            $pending
        );
    }

    private function processOneDispatch(BookingReminderDispatch $dispatch, NotificationService $notifications): bool
    {
        $booking = Booking::query()
            ->with(['slots', 'customer', 'provider'])
            ->find($dispatch->booking_id);

        if (! $booking || $booking->status !== 'confirmed') {
            $this->failDispatch($dispatch, 'booking_not_confirmed_or_deleted');

            return true;
        }

        if (! $this->dispatchStillAllowedBySettings($dispatch)) {
            $this->failDispatch($dispatch, 'reminder_disabled_in_settings');

            return true;
        }

        $slotStart = Carbon::parse($dispatch->slot_starts_at);
        if ($slotStart->lte(now())) {
            $this->failDispatch($dispatch, 'slot_start_time_already_passed');

            return true;
        }

        try {
            $outcome = $notifications->sendBookingReminderOutcome(
                $booking,
                $dispatch->notification_type,
                $dispatch->interval_key,
                $slotStart
            );
        } catch (\Throwable $e) {
            $this->failDispatch($dispatch, 'exception: ' . $e->getMessage());

            return true;
        }

        if (($outcome['ok'] ?? false) === true) {
            $dispatch->delete();

            return true;
        }

        $this->failDispatch($dispatch, $outcome['reason'] ?? 'unknown_failure');

        return true;
    }

    private function failDispatch(BookingReminderDispatch $dispatch, string $reason): void
    {
        $dispatch->update([
            'status' => 'failed',
            'failure_reason' => mb_substr($reason, 0, 2000),
            'processed_at' => now(),
        ]);
    }

    private function dispatchStillAllowedBySettings(BookingReminderDispatch $dispatch): bool
    {
        $settings = $this->resolveReminderSettings();
        $key = $dispatch->interval_key;

        if ($dispatch->notification_type === 'booking_reminder') {
            return $settings['user_enabled'] && in_array($key, $settings['user_times'], true);
        }

        if ($dispatch->notification_type === 'provider_booking_reminder') {
            return $settings['provider_enabled'] && in_array($key, $settings['effective_provider_times'], true);
        }

        return false;
    }

    /**
     * @return array{
     *   user_enabled: bool,
     *   provider_enabled: bool,
     *   user_times: list<string>,
     *   effective_provider_times: list<string>,
     *   will_send_user: bool,
     *   will_send_provider: bool
     * }
     */
    private function resolveReminderSettings(): array
    {
        $userEnabled = (bool) Setting::get('user_reminder_enabled', false);
        $providerEnabled = (bool) Setting::get('provider_reminder_enabled', false);

        $userTimes = $this->normalizeTimes(Setting::get('user_reminder_times', '[]'));
        $providerTimes = $this->normalizeTimes(Setting::get('provider_reminder_times', '[]'));

        $effectiveProviderTimes = $providerTimes;
        if ($providerEnabled && $effectiveProviderTimes === [] && $userEnabled && $userTimes !== []) {
            $effectiveProviderTimes = $userTimes;
        }
        if ($providerEnabled && $effectiveProviderTimes === []) {
            $effectiveProviderTimes = ['15m', '30m', '45m'];
        }

        return [
            'user_enabled' => $userEnabled,
            'provider_enabled' => $providerEnabled,
            'user_times' => $userTimes,
            'effective_provider_times' => $effectiveProviderTimes,
            'will_send_user' => $userEnabled && $userTimes !== [],
            'will_send_provider' => $providerEnabled && $effectiveProviderTimes !== [],
        ];
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

        return array_values(array_unique(array_intersect($allowed, array_map('strval', array_values($raw)))));
    }
}
