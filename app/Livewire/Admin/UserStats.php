<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class UserStats extends Component
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
                        'value' => $counts->total,
                        'icon' => 'assets/images/icons/service-providers.png',
                    ],
                    [
                        'label' => 'Active Users', 
                        'value' => $counts->active,
                        'icon' => 'assets/images/icons/new-provides.png'
                    ],
                    [
                        'label' => 'Inactive Users',
                        'value' => $counts->inactive,
                        'icon' => 'assets/images/icons/active-booking.png'                    
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
                        'value' => $counts->total,
                        'icon' => 'assets/images/icons/service-providers.png',
                    ],
                    [
                        'label' => 'Active Users', 
                        'value' => $counts->active,
                        'icon' => 'assets/images/icons/new-provides.png'
                    ],
                    [
                        'label' => 'Inactive Users',
                        'value' => $counts->inactive,
                        'icon' => 'assets/images/icons/active-booking.png'                    
                    ],
                ];
                break;
            case 'users':
                $counts = User::select(
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN user_type = 'provider' THEN 1 ELSE 0 END) as providers"),
                    DB::raw("SUM(CASE WHEN user_type = 'customer' THEN 1 ELSE 0 END) as customers"),
                    DB::raw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active"),
                ) 
                ->first();
                $this->stats = [
                    [
                        'label' => 'Total service users', 
                        'value' => $counts->customers,
                        'icon' => 'assets/images/icons/service-providers.png',
                    ],
                    [
                        'label' => 'Total service providers', 
                        'value' => $counts->providers,
                        'icon' => 'assets/images/icons/new-provides.png'
                    ],
                    [
                        'label' => 'Total active users', 
                        'value' => $counts->active,
                        'icon' => 'assets/images/icons/active-members.png'
                    ],
                    // assets/images/icons/active-members.png
                     
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
