<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Setting;
use App\Services\Notification\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBookingRemindersCommand extends Command
{
    protected $signature = 'bookings:send-reminders';

    protected $description = 'Send customer/provider booking reminders based on admin notification settings';

    public function handle(NotificationService $notifications): int
    {
        $intervalMinutes = [
            '15m' => 15,
            '30m' => 30,
            '45m' => 45,
        ];

        $userEnabled = (bool) Setting::get('user_reminder_enabled', false);
        $providerEnabled = (bool) Setting::get('provider_reminder_enabled', false);

        if (! $userEnabled && ! $providerEnabled) {
            return self::SUCCESS;
        }

        $userTimes = $this->normalizeTimes(Setting::get('user_reminder_times', '[]'));
        $providerTimes = $this->normalizeTimes(Setting::get('provider_reminder_times', '[]'));

        // Provider toggle on but no intervals saved (common QA/admin mistake) — mirror customer times, else all allowed slots.
        $effectiveProviderTimes = $providerTimes;
        if ($providerEnabled && $effectiveProviderTimes === [] && $userEnabled && $userTimes !== []) {
            $effectiveProviderTimes = $userTimes;
        }
        if ($providerEnabled && $effectiveProviderTimes === []) {
            $effectiveProviderTimes = ['15m', '30m', '45m'];
        }

        $willSendUser = $userEnabled && $userTimes !== [];
        $willSendProvider = $providerEnabled && $effectiveProviderTimes !== [];
        if (! $willSendUser && ! $willSendProvider) {
            return self::SUCCESS;
        }

        $now = Carbon::now();

        Booking::query()
            ->where('status', 'confirmed')
            ->with(['slots', 'customer', 'provider'])
            ->chunkById(100, function ($bookings) use (
                $notifications,
                $now,
                $intervalMinutes,
                $userEnabled,
                $providerEnabled,
                $userTimes,
                $effectiveProviderTimes
            ) {
                foreach ($bookings as $booking) {
                    $firstSlot = $booking->slots
                        ->sortBy(fn ($s) => $s->service_date . ' ' . $s->start_time)
                        ->first();

                    if (! $firstSlot) {
                        continue;
                    }

                    $slotStart = Carbon::parse($firstSlot->service_date . ' ' . $firstSlot->start_time);
                    if ($slotStart->lte($now)) {
                        continue;
                    }

                    $minutesUntil = (int) floor($now->floatDiffInMinutes($slotStart, false));
                    $slotIso = $slotStart->toIso8601String();

                    foreach ($intervalMinutes as $key => $target) {
                        if ($minutesUntil !== $target) {
                            continue;
                        }

                        if ($userEnabled && in_array($key, $userTimes, true)) {
                            $notifications->notifyBookingReminder($booking, $key, $slotStart);
                        }

                        if ($providerEnabled && in_array($key, $effectiveProviderTimes, true)) {
                            $notifications->notifyProviderBookingReminder($booking, $key, $slotStart);
                        }
                    }
                }
            });

        return self::SUCCESS;
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
