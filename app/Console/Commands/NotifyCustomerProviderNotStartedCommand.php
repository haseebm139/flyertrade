<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Dispute;
use App\Services\Booking\BookingService;
use App\Services\Notification\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyCustomerProviderNotStartedCommand extends Command
{
    protected $signature = 'bookings:notify-customer-provider-not-started';

    protected $description = 'Alert customers when a paid booking is past start time but the provider has not started the job (dispute / action flow)';

    public function handle(BookingService $bookings, NotificationService $notifications): int
    {
        Booking::query()
            ->where('status', 'confirmed')
            ->whereNotNull('paid_at')
            ->with(['slots', 'customer', 'provider'])
            ->chunkById(100, function ($chunk) use ($bookings, $notifications): void {
                foreach ($chunk as $booking) {
                    if ($booking->slots->isEmpty()) {
                        continue;
                    }

                    $firstSlot = $booking->slots
                        ->sortBy(fn ($s) => $s->service_date.' '.$s->start_time)
                        ->first();
                    $slotStart = Carbon::parse($firstSlot->service_date.' '.$firstSlot->start_time);
                    // Paid on time: captured before the scheduled service start (15m buffer for webhooks).
                    if ($booking->paid_at && $booking->paid_at->gt($slotStart->copy()->addMinutes(15))) {
                        continue;
                    }

                    $late = $bookings->isProviderLate($booking);
                    if (! ($late['is_late'] ?? false)) {
                        continue;
                    }

                    if (Dispute::query()
                        ->where('booking_id', $booking->id)
                        ->where('user_id', $booking->customer_id)
                        ->exists()) {
                        continue;
                    }

                    $notifications->notifyCustomerProviderNotStartedDisputePrompt($booking);
                }
            });

        return self::SUCCESS;
    }
}
