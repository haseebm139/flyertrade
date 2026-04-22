<?php

namespace App\Console\Commands;

use App\Services\Booking\BookingReminderDispatchService;
use App\Services\Notification\NotificationService;
use Illuminate\Console\Command;

class SendBookingRemindersCommand extends Command
{
    protected $signature = 'bookings:send-reminders';

    protected $description = 'Queue booking reminder rows, send due reminders, persist failures with reasons';

    public function handle(
        BookingReminderDispatchService $dispatchService,
        NotificationService $notifications
    ): int {
        $dispatchService->syncQueuedForAllConfirmedBookings();
        $dispatchService->processDue($notifications);

        return self::SUCCESS;
    }
}
