<?php

namespace App\Console\Commands;

use App\Services\Booking\BookingService;
use Illuminate\Console\Command;

class CancelUnpaidPastBookingsCommand extends Command
{
    protected $signature = 'bookings:cancel-unpaid-past';

    protected $description = 'Cancel unpaid bookings whose first scheduled slot is already in the past';

    public function handle(BookingService $bookings): int
    {
        $n = $bookings->autoCancelUnpaidPastBookings();
        $this->info("Auto-cancelled {$n} unpaid past booking(s).");

        return self::SUCCESS;
    }
}
