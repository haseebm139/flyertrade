<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Dispute;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $customerCount = User::customers()->count();
        $providerCount = User::providers()->count();
        $activeUsersCount = User::where('status', 'active')->count();
        $activeBookingsCount = Booking::whereIn('status', [
            'confirmed',
            'in_progress',
            'awaiting_provider',
        ])->count();

        $stats = [
            [
                'label' => 'Total service users',
                'value' => $customerCount,
                'icon' => 'assets/images/icons/service_providers.svg',
            ],
            [
                'label' => 'Total service providers',
                'value' => $providerCount,
                'icon' => 'assets/images/icons/new-providers.svg',
            ],
            [
                'label' => 'Active bookings',
                'value' => $activeBookingsCount,
                'icon' => 'assets/images/icons/active_booking.svg',
            ],
            [
                'label' => 'Total active users',
                'value' => $activeUsersCount,
                'icon' => 'assets/images/icons/active_members.svg',
            ],
        ];

        $recentDisputes = Dispute::with(['user', 'booking'])
            ->latest()
            ->take(2)
            ->get();

        $recentUsers = User::customers()
            ->latest()
            ->take(5)
            ->get();

        $recentProviders = User::providers()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'stats',
            'recentDisputes',
            'recentUsers',
            'recentProviders'
        ));
    }
}
