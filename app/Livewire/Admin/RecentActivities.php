<?php

namespace App\Livewire\Admin;

use App\Models\Notification;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;

class RecentActivities extends Component
{
    public $activities = [];

    public function mount(): void
    {
        $this->loadActivities();
    }

    public function loadActivities(): void
    {
        // Get latest 5 notifications for admin
        $notifications = Notification::where(function($query) {
                $query->where('recipient_type', 'admin')
                    ->orWhere('recipient_type', 'all');
            })
            ->orderBy('created_at', 'desc')
            ->take(9)
            ->get();

        // Collect provider IDs for batch loading
        $providerIds = [];
        foreach ($notifications as $notification) {
            if ($notification->type === 'provider_registered') {
                $data = $notification->data ?? [];
                if (isset($data['provider_id'])) {
                    $providerIds[] = $data['provider_id'];
                }
            }
        }

        // Batch load all providers at once
        $providers = [];
        if (!empty($providerIds)) {
            $providers = User::whereIn('id', array_unique($providerIds))
                ->pluck('avatar', 'id')
                ->toArray();
        }

        // Format activities
        $this->activities = $notifications->map(function($notification) use ($providers) {
            $iconUrl = $this->getNotificationIcon($notification, $providers);
            $bgColor = $this->getBackgroundColor($notification->type);
            
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'message' => $notification->message,
                'icon_url' => $iconUrl,
                'bg_color' => $bgColor,
                'time_ago' => $this->getTimeAgo($notification->created_at),
                'created_at' => $notification->created_at,
            ];
        })->toArray();
    }

    private function getNotificationIcon($notification, array $providers = []): string
    {
        // For provider_registered notifications, use provider's avatar
        if ($notification->type === 'provider_registered') {
            $data = $notification->data ?? [];
            if (isset($data['provider_id'])) {
                $providerId = $data['provider_id'];
                // Use batch loaded providers if available
                if (!empty($providers) && isset($providers[$providerId]) && $providers[$providerId]) {
                    return asset($providers[$providerId]);
                }
                // Fallback to single query if not in batch
                $provider = User::find($providerId);
                if ($provider && $provider->avatar) {
                    return asset($provider->avatar);
                }
            }
        }
        
        // Default to notification icon_url
        return $notification->icon_url ?? asset('assets/images/icons/manage.svg');
    }

    private function getBackgroundColor($type): string
    {
        $colorMap = [
            'booking_created' => 'bg-beige',
            'provider_registered' => 'bg-green',
            'dispute_created' => 'bg-blue',
            'review_received' => 'bg-light-brown',
            'document_verification' => 'bg-beige',
            'document_pending' => 'bg-beige',
        ];

        return $colorMap[$type] ?? 'bg-beige';
    }

    private function getTimeAgo($datetime): string
    {
        $carbon = Carbon::parse($datetime);
        return $carbon->diffForHumans();
    }

    public function render()
    {
        return view('livewire.admin.recent-activities');
    }
}
