<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Booking\BookingService;
class AutoRejectBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:auto-reject';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto reject awaiting_provider bookings past their expiry and refund';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $service->autoRejectExpired();
        $this->info("Auto-rejected {$count} bookings.");
        return self::SUCCESS;
    }
}
