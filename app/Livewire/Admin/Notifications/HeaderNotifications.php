<?php

namespace App\Livewire\Admin\Notifications;

use App\Models\Notification;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;

class HeaderNotifications extends Component
{
    public $groupedNotifications = [];
    public $unreadCount = 0;
    public $selectedGroup = null;
    public $selectedGroupNotifications = [];

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $userId = auth()->id();

        // Latest notifications first; group header order = most recent activity in that type
        $notifications = Notification::query()
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->where('recipient_type', 'admin')
                    ->orWhere('recipient_type', 'all');
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(20)
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
        
        // Group by type
        $grouped = [];
        foreach ($notifications as $notification) {
            $type = $notification->type;
            $category = $notification->category ?? 'admin_actions';
            
            // Create a group key based on type
            $groupKey = $type;
            
            if (!isset($grouped[$groupKey])) {
                // Get icon URL - use provider avatar for provider_registered (from batch loaded data)
                $iconUrl = $this->getNotificationIcon($notification, $providers);
                
                $grouped[$groupKey] = [
                    'type' => $type,
                    'category' => $category,
                    'title' => $this->getGroupTitle($type, $category),
                    'icon_url' => $iconUrl,
                    'count' => 0,
                    'unread_count' => 0,
                    'has_unread' => false,
                    'notifications' => [],
                ];
            }
            
            $grouped[$groupKey]['count']++;
            $grouped[$groupKey]['notifications'][] = $notification;
            
            // Check if notification is unread
            if (is_null($notification->read_at)) {
                $grouped[$groupKey]['unread_count']++;
                $grouped[$groupKey]['has_unread'] = true;
            }
        }

        foreach (array_keys($grouped) as $key) {
            usort($grouped[$key]['notifications'], function ($a, $b): int {
                return Carbon::parse($b->created_at)->timestamp <=> Carbon::parse($a->created_at)->timestamp;
            });
        }

        $this->groupedNotifications = collect($grouped)
            ->sortByDesc(function (array $group): int {
                $latest = collect($group['notifications'])->max(
                    fn ($n): int => Carbon::parse($n->created_at)->timestamp
                );

                return (int) $latest;
            })
            ->values()
            ->toArray();

        // Calculate total unread count
        $this->unreadCount = Notification::query()
            ->where('user_id', $userId)
            ->where(function ($query) {
                $query->where('recipient_type', 'admin')
                    ->orWhere('recipient_type', 'all');
            })
            ->whereNull('read_at')
            ->count();

        // Dispatch event to update badge
        $this->dispatch('notificationUpdated', ['unreadCount' => $this->unreadCount]);
    }

    public function viewGroup($groupKey): void
    {
        // Find the group
        foreach ($this->groupedNotifications as $group) {
            if ($group['type'] === $groupKey) {
                $this->selectedGroup = $group;
                
                // Collect all provider IDs for batch loading
                $providerIds = [];
                foreach ($group['notifications'] as $notification) {
                    $data = is_array($notification) ? ($notification['data'] ?? []) : ($notification->data ?? []);
                    $type = is_array($notification) ? ($notification['type'] ?? '') : ($notification->type ?? '');
                    if ($type === 'provider_registered' && isset($data['provider_id'])) {
                        $providerIds[] = $data['provider_id'];
                    }
                }

                // Batch load all providers at once
                $providers = [];
                if (! empty($providerIds)) {
                    $providers = User::whereIn('id', array_unique($providerIds))
                        ->pluck('avatar', 'id')
                        ->toArray();
                }

                $ids = collect($group['notifications'])
                    ->map(fn ($n) => is_array($n) ? ($n['id'] ?? null) : $n->id)
                    ->filter()
                    ->unique()
                    ->values();

                $rows = $ids->isEmpty()
                    ? collect()
                    : Notification::query()
                        ->where('user_id', auth()->id())
                        ->whereIn('id', $ids)
                        ->orderByDesc('created_at')
                        ->orderByDesc('id')
                        ->get();

                $this->selectedGroupNotifications = $rows->map(function ($notification) use ($providers) {
                    $actionUrl = $this->generateActionUrl($notification);
                    $actionUrl = $this->fixActionUrl($actionUrl);
                    
                    // Get icon URL - use provider avatar for provider_registered (from batch loaded data)
                    $iconUrl = $this->getNotificationIcon($notification, $providers);

                    $data = $notification->data ?? [];
                    $hasProfile = isset($data['provider_id']) || isset($data['customer_id']);

                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'icon_url' => $iconUrl,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'read_at' => $notification->read_at,
                        'created_at' => $notification->created_at,
                        'time_ago' => $this->getTimeAgo($notification->created_at),
                        'action_url' => $actionUrl,
                        'data' => $data,
                        'has_profile' => $hasProfile,
                    ];
                })->values()->toArray();
                
                // Dispatch event to notify that group is loaded
                // Convert selectedGroup to array if it's not already
                $groupData = is_array($this->selectedGroup) ? $this->selectedGroup : [
                    'type' => $this->selectedGroup['type'] ?? $groupKey,
                    'title' => $this->selectedGroup['title'] ?? '',
                    'count' => $this->selectedGroup['count'] ?? 0,
                ];
                
                $this->dispatch('groupLoaded', [
                    'group' => $groupData,
                    'notifications' => $this->selectedGroupNotifications
                ]);
                
                break;
            }
        }
    }

    public function closeGroupView(): void
    {
        $this->selectedGroup = null;
        $this->selectedGroupNotifications = [];
    }

    private function getGroupTitle($type, $category): string
    {
        // Map notification types to titles
        $titles = [
            'provider_registered' => 'New Service Provider Registered',
            'booking_created' => 'New Booking Created',
            'review_received' => 'New Review Posted',
            'dispute_created' => 'New Dispute',
            'refund_processed' => 'Refund Processed',
            'refund_failed' => 'Refund Failed',
            'provider_late_escalation' => 'Provider Late Escalation',
        ];

        return $titles[$type] ?? ucfirst(str_replace('_', ' ', $type));
    }

    public function markAsRead($id): void
    {
        $notification = Notification::query()
            ->where('user_id', auth()->id())
            ->where(function ($query) {
                $query->where('recipient_type', 'admin')
                    ->orWhere('recipient_type', 'all');
            })
            ->find($id);

        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    private function getTimeAgo($datetime): string
    {
        $carbon = Carbon::parse($datetime);
        $diff = $carbon->diffForHumans();
        
        return str_replace(['before', 'after'], ['ago', ''], $diff);
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
        return $notification->icon_url ?? asset('assets/images/icons/person.svg');
    }

    private function generateActionUrl($notification): ?string
    {
        $data = $notification->data ?? [];
        $type = $notification->type ?? '';
        $category = $notification->category ?? '';
        $rawActionUrl = is_string($notification->action_url ?? null) ? trim($notification->action_url) : null;

        if ($rawActionUrl !== null && $rawActionUrl !== '') {
            $normalized = strtolower($rawActionUrl);

            if (in_array($normalized, ['viewprofile', 'view_profile', 'view-profile'], true)) {
                if (isset($data['provider_id'])) {
                    return "/admin/providers/{$data['provider_id']}";
                }
                // Fall back to data-based routing if provider_id is missing
                $rawActionUrl = null;
            } elseif ($normalized !== 'view') {
                return $rawActionUrl;
            } else {
                $rawActionUrl = null;
            }
        }

        // Priority 1: Check category first
        if ($category === 'bookings' && isset($data['booking_id'])) {
            return "/admin/bookings/{$data['booking_id']}";
        }

        if ($category === 'reviews' && isset($data['review_id'])) {
            return "/admin/reviews/{$data['review_id']}";
        }

        if ($category === 'transactions' && isset($data['transaction_id'])) {
            return "/admin/transactions/{$data['transaction_id']}";
        }

        if ($category === 'admin_actions' && isset($data['provider_id'])) {
            return "/admin/providers/{$data['provider_id']}";
        }

        // Priority 2: Check notification type
        if (str_contains($type, 'booking') && isset($data['booking_id'])) {
            return "/admin/bookings/{$data['booking_id']}";
        }

        if (str_contains($type, 'review') && isset($data['review_id'])) {
            return "/admin/reviews/{$data['review_id']}";
        }

        if ((str_contains($type, 'transaction') || str_contains($type, 'refund') || str_contains($type, 'payment')) && isset($data['transaction_id'])) {
            return "/admin/transactions/{$data['transaction_id']}";
        }

        // Check for provider-related notifications
        if (($type === 'provider_registered' || str_contains($type, 'provider') || $category === 'admin_actions') && isset($data['provider_id'])) {
            return "/admin/providers/{$data['provider_id']}";
        }

        // Priority 3: Check for any ID in data (fallback)
        if (isset($data['booking_id'])) {
            return "/admin/bookings/{$data['booking_id']}";
        }
        if (isset($data['review_id'])) {
            return "/admin/reviews/{$data['review_id']}";
        }
        if (isset($data['transaction_id'])) {
            return "/admin/transactions/{$data['transaction_id']}";
        }
        if (isset($data['provider_id'])) {
            return "/admin/providers/{$data['provider_id']}";
        }

        return null;
    }

    private function fixActionUrl($actionUrl): ?string
    {
        if (!$actionUrl) {
            return null;
        }

        // If it's already an absolute URL, return as is
        if (str_starts_with($actionUrl, 'http://') || str_starts_with($actionUrl, 'https://')) {
            return $actionUrl;
        }

        // Fix review URLs: /admin/reviews/{id} -> /admin/reviews-details/{id}
        if (preg_match('#^/admin/reviews/(\d+)$#', $actionUrl, $matches)) {
            return route('reviews.show', ['id' => $matches[1]]);
        }

        // Fix dispute URLs: /admin/disputes/{id} -> /admin/dispute (no detail route exists)
        if (preg_match('#^/admin/disputes/(\d+)$#', $actionUrl)) {
            return route('dispute.index');
        }

        // Fix booking URLs: /admin/bookings/{id} -> /admin/booking (no detail route exists)
        if (preg_match('#^/admin/bookings/(\d+)$#', $actionUrl)) {
            return route('booking.index');
        }

        // Fix transaction URLs: /admin/transactions/{id} -> /admin/transactions (no detail route exists)
        if (preg_match('#^/admin/transactions/(\d+)$#', $actionUrl)) {
            return route('transaction.index');
        }

        // Fix provider URLs: /admin/providers/{id} -> /admin/user-management/service-provider/{id}
        if (preg_match('#^/admin/providers/(\d+)$#', $actionUrl, $matches)) {
            return route('user-management.service.providers.view', ['id' => $matches[1]]);
        }

        // Fix provider pending verification: /admin/providers/pending-verification -> /admin/user-management/service-provider
        if ($actionUrl === '/admin/providers/pending-verification') {
            return route('user-management.service.providers.index');
        }

        // For relative URLs starting with /, convert to full URL
        if (str_starts_with($actionUrl, '/')) {
            return url($actionUrl);
        }

        // For any other URL (relative or route name), try to convert to full URL
        return url($actionUrl);
    }

    public function render()
    {
        return view('livewire.admin.notifications.header-notifications');
    }
}
