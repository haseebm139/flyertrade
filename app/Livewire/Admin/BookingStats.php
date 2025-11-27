<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class BookingStats extends Component
{
    public string $mode = 'dashboard';  
    public array $stats = [];

    public function mount(string $mode = 'dashboard')
    {
        $this->mode = $mode;
        $this->generateStats();
    }

    public function generateStats()
    {
        switch ($this->mode) {
            case 'booking':
                $counts = Booking::select(
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as active"),
                    DB::raw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled"),
                    DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"),
                    DB::raw("SUM(CASE WHEN status = 'awaiting_provider' THEN 1 ELSE 0 END) as pending")
                )->first();

                $this->stats = [
                    [
                        'label' => 'Total Bookings', 
                        'value' => $counts->total ?? 0,
                        'icon' => 'assets/images/icons/active_booking.svg',
                        'onclick' => 'showAllBookings()'
                    ],
                    [
                        'label' => 'Active Bookings', 
                        'value' => $counts->active ?? 0,
                        'icon' => 'assets/images/icons/active_booking.svg',
                        'onclick' => 'filterByStatus("confirmed", "Active Bookings")'
                    ],
                    [
                        'label' => 'Cancelled Bookings',
                        'value' => $counts->cancelled ?? 0,
                        'icon' => 'assets/images/icons/active_booking.svg',
                        'onclick' => 'filterByStatus("cancelled", "Cancelled Bookings")'
                    ],
                ];
                break;
            case 'detailed':
                $counts = Booking::select(
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as active"),
                    DB::raw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled"),
                    DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"),
                    DB::raw("SUM(CASE WHEN status = 'awaiting_provider' THEN 1 ELSE 0 END) as pending"),
                    DB::raw("SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress")
                )->first();

                $this->stats = [
                    [
                        'label' => 'Total Bookings', 
                        'value' => $counts->total ?? 0,
                        'icon' => 'assets/images/icons/active_booking.svg',
                        'onclick' => 'showAllBookings()'
                    ],
                    [
                        'label' => 'Active Bookings', 
                        'value' => $counts->active ?? 0,
                        'icon' => 'assets/images/icons/active_booking.svg',
                        'onclick' => 'filterByStatus("confirmed", "Active Bookings")'
                    ],
                    [
                        'label' => 'Completed Bookings',
                        'value' => $counts->completed ?? 0,
                        'icon' => 'assets/images/icons/active_booking.svg',
                        'onclick' => 'filterByStatus("completed", "Completed Bookings")'
                    ],
                    [
                        'label' => 'Cancelled Bookings',
                        'value' => $counts->cancelled ?? 0,
                        'icon' => 'assets/images/icons/active_booking.svg',
                        'onclick' => 'filterByStatus("cancelled", "Cancelled Bookings")'
                    ],
                    [
                        'label' => 'Pending Bookings',
                        'value' => $counts->pending ?? 0,
                        'icon' => 'assets/images/icons/active_booking.svg',
                        'onclick' => 'filterByStatus("awaiting_provider", "Pending Bookings")'
                    ],
                ];
                break;
            default:
                $this->stats = [];
                break;
        }
    }

    public function render()
    {
        return view('livewire.admin.booking-stats');
    }
}
