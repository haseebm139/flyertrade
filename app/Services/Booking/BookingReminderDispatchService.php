<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\Setting;
use App\Services\Notification\NotificationService;
use Carbon\Carbon;

/**
 * Polls confirmed bookings and sends tiered reminders (15 / 30 / 45 minutes before first slot).
 *
 * Design:
 * - Uses mutually exclusive time windows in seconds until the first slot (see {@see self::isInReminderWindow()})
 *   so each tier can fire on any scheduler tick inside that window (avoids exact-minute cron races).
 * - NotificationService deduplicates by user, booking, interval key, and slot start (DB guard).
 * - Command should use {@see \Illuminate\Console\Scheduling\ManagesAttributes::withoutOverlapping()}
 *   so concurrent runs cannot double-send before DB rows exist.
 */
final class BookingReminderDispatchService
{
    private const ALLOWED_INTERVAL_KEYS = ['15m', '30m', '45m'];

    public function dispatchDueReminders(NotificationService $notifications): void
    {
        $ctx = $this->resolveRuntimeContext();
        if ($ctx === null) {
            return;
        }

        Booking::query()
            ->where('status', 'confirmed')
            ->with(['slots', 'customer', 'provider'])
            ->chunkById(100, function ($bookings) use ($notifications, $ctx): void {
                foreach ($bookings as $booking) {
                    $this->processBooking($booking, $notifications, $ctx);
                }
            });
    }

    /**
     * @return array{
     *     user_enabled: bool,
     *     provider_enabled: bool,
     *     user_times: list<string>,
     *     provider_times: list<string>,
     * }|null
     */
    private function resolveRuntimeContext(): ?array
    {
        $userEnabled = (bool) Setting::get('user_reminder_enabled', false);
        $providerEnabled = (bool) Setting::get('provider_reminder_enabled', false);

        if (! $userEnabled && ! $providerEnabled) {
            return null;
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

        if ((! $userEnabled || $userTimes === []) && (! $providerEnabled || $effectiveProviderTimes === [])) {
            return null;
        }

        return [
            'user_enabled' => $userEnabled,
            'provider_enabled' => $providerEnabled,
            'user_times' => $userTimes,
            'provider_times' => $effectiveProviderTimes,
        ];
    }

    /**
     * @param  array{user_enabled: bool, provider_enabled: bool, user_times: list<string>, provider_times: list<string>}  $ctx
     */
    private function processBooking(Booking $booking, NotificationService $notifications, array $ctx): void
    {
        $now = Carbon::now();

        $firstSlot = $booking->slots
            ->sortBy(fn ($s) => $s->service_date.' '.$s->start_time)
            ->first();

        if (! $firstSlot) {
            return;
        }

        $slotStart = Carbon::parse($firstSlot->service_date.' '.$firstSlot->start_time);
        if ($slotStart->lte($now)) {
            return;
        }

        $secondsUntil = self::secondsUntilFirstSlot($slotStart, $now);

        foreach (self::intervalKeysInChronologicalOrder() as $key) {
            if (! self::isInReminderWindow($secondsUntil, $key)) {
                continue;
            }

            if ($ctx['user_enabled'] && in_array($key, $ctx['user_times'], true)) {
                $notifications->notifyBookingReminder($booking, $key, $slotStart);
            }

            if ($ctx['provider_enabled'] && in_array($key, $ctx['provider_times'], true)) {
                $notifications->notifyProviderBookingReminder($booking, $key, $slotStart);
            }
        }
    }

    /**
     * @return list<string>
     */
    private static function intervalKeysInChronologicalOrder(): array
    {
        return ['45m', '30m', '15m'];
    }

    /**
     * Mutually exclusive windows (seconds until first slot start):
     * - 45m: (30 min, 45 min]
     * - 30m: (15 min, 30 min]
     * - 15m: (0, 15 min]
     */
    public static function isInReminderWindow(int $secondsUntil, string $intervalKey): bool
    {
        return match ($intervalKey) {
            '45m' => $secondsUntil > 30 * 60 && $secondsUntil <= 45 * 60,
            '30m' => $secondsUntil > 15 * 60 && $secondsUntil <= 30 * 60,
            '15m' => $secondsUntil > 0 && $secondsUntil <= 15 * 60,
            default => false,
        };
    }

    public static function secondsUntilFirstSlot(Carbon $slotStart, Carbon $now): int
    {
        return max(0, (int) ($slotStart->getTimestamp() - $now->getTimestamp()));
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

        return array_values(array_unique(array_intersect(
            self::ALLOWED_INTERVAL_KEYS,
            array_map('strval', array_values($raw))
        )));
    }
}
