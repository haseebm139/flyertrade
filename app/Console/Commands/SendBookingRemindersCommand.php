<?php

namespace App\Console\Commands;

use App\Services\Booking\BookingReminderDispatchService;
use App\Services\Notification\NotificationService;
use Illuminate\Console\Command;

class SendBookingRemindersCommand extends Command
{
    protected $signature = 'bookings:send-reminders';

    protected $description = 'Hourly safety net: send any missed booking reminders (primary delivery is delayed queue jobs)';

    public function handle(
        BookingReminderDispatchService $reminderDispatch,
        NotificationService $notifications
    ): int {
        $reminderDispatch->dispatchDueReminders($notifications);

        return self::SUCCESS;
    }
}
