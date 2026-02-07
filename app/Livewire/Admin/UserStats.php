<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
class UserStats extends Component
{

     
    public string $mode = 'dashboard';  
    public array $stats = [];

    protected $listeners = [
        'categoryUpdated' => 'generateStats',
        'bookingUpdated'  => 'generateStats',
    ];

    public function mount(string $mode = 'dashboard')
    {
        $this->mode = $mode;
        $this->generateStats();
    }

    public function generateStats()
    {
 
        switch ($this->mode) {
            case 'customers':
                $counts = User::select(
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active"),
                    DB::raw("SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive")
                )
                ->where('user_type', 'customer')
                ->first();
                $this->stats = [
                    [
                        'label' => 'Total Users', 
                        'value' => $counts->total ?? 0,
                        'icon' => 'assets/images/icons/service_providers.svg',
                    ],
                    [
                        'label' => 'Active Users', 
                        'value' => $counts->active ?? 0,
                        'icon' => 'assets/images/icons/new-providers.svg'
                    ],
                    [
                        'label' => 'Inactive Users',
                        'value' => $counts->inactive ?? 0,
                        'icon' => 'assets/images/icons/active_booking.svg'                    
                    ],
                ];
                break;
            case 'providers':
                $counts = User::select(
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active"),
                    DB::raw("SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive")
                )
                ->where('user_type', 'provider')
                ->first();
                $this->stats = [
                    [
                        'label' => 'Total Users', 
                        'value' => $counts->total ?? 0,
                        'icon' => 'assets/images/icons/service_providers.svg',
                    ],
                    [
                        'label' => 'Active Users', 
                        'value' => $counts->active ?? 0,
                        'icon' => 'assets/images/icons/new-providers.svg'
                    ],
                    [
                        'label' => 'Inactive Users',
                        'value' => $counts->inactive ?? 0,
                        'icon' => 'assets/images/icons/active_booking.svg'                    
                    ],
                ];
                break;
            case 'users':
                $userCounts = User::select(
                    DB::raw("SUM(CASE WHEN user_type = 'provider' THEN 1 ELSE 0 END) as providers"),
                    DB::raw("SUM(CASE WHEN user_type = 'customer' THEN 1 ELSE 0 END) as customers"),
                    DB::raw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_users"),
                ) 
                ->first();

                $activeBookingsCount = Booking::whereIn('status', ['confirmed', 'in_progress', 'awaiting_provider'])->count();

                $this->stats = [
                    [
                        'label' => 'Total service users', 
                        'value' => $userCounts->customers ?? 0,
                        'icon' => 'assets/images/icons/service_providers.svg',
                    ],
                    [
                        'label' => 'Total service providers', 
                        'value' => $userCounts->providers ?? 0,
                        'icon' => 'assets/images/icons/new-providers.svg'
                    ],
                    [
                        'label' => 'Active bookings', 
                        'value' => $activeBookingsCount,
                        'icon' => 'assets/images/icons/active_booking.svg'
                    ],
                    [
                        'label' => 'Total active users', 
                        'value' => $userCounts->active_users ?? 0,
                        'icon' => 'assets/images/icons/active_members.svg'
                    ],
                ];
                break;
            case 'transactions':
                $totalRevenue = Transaction::where('status', 'succeeded')
                    ->where('type', 'payment')
                    ->sum('service_charges');
                $totalPayout = Transaction::where('status', 'succeeded')
                    ->where('type', 'payout')
                    ->sum('net_amount');
                $pendingPayout = Transaction::whereIn('status', ['pending', 'processing'])
                    ->where('type', 'payout')
                    ->sum('net_amount');

                $this->stats = [
                    [
                        'label' => 'Total revenue',
                        'value' => '$' . number_format((float) $totalRevenue, 0),
                        'icon' => 'assets/images/icons/payout-icon.svg',
                    ],
                    [
                        'label' => 'Total payout',
                        'value' => '$' . number_format((float) $totalPayout, 0),
                        'icon' => 'assets/images/icons/payout-icon.svg'
                    ],
                    [
                        'label' => 'Pending payout',
                        'value' => '$' . number_format((float) $pendingPayout, 0),
                        'icon' => 'assets/images/icons/payout-icon.svg'
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
         
        return view('livewire.admin.user-stats');
    }
}
