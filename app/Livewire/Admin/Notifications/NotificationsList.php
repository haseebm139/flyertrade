<?php

namespace App\Livewire\Admin\Notifications;

use App\Models\Notification;
use Livewire\Component;
use Carbon\Carbon;

class NotificationsList extends Component
{
    public $category = 'all';
    public $groupedNotifications = [];
    public $unreadCount = 0;
    public $categoryUnreadCounts = [];
    public $perPage = 20;
    public $loadedCount = 0;
    public $totalCount = 0;
    public $hasMore = true;
    public $loading = false;

    protected $queryString = [
        'category' => ['except' => 'all'],
    ];

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function updatedCategory(): void
    {
        $this->loadNotifications();
    }

    public function switchCategory($category): void
    {
        $this->category = $category;
        $this->loadedCount = 0;
        $this->hasMore = true;
        $this->loadNotifications();
    }

    public function markAsRead($id): void
    {
        $notification = Notification::where(function($query) {
                $query->where('recipient_type', 'admin')
                    ->orWhere('recipient_type', 'all');
            })
            ->find($id);

        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }


    public function loadNotifications($loadMore = false): void
    {
        if ($this->loading) {
            return;
        }

        $this->loading = true;

        $query = Notification::where(function($query) {
                $query->where('recipient_type', 'admin')
                    ->orWhere('recipient_type', 'all');
            })
            ->orderBy('created_at', 'desc');

        // Filter by category
        $allowedCategories = ['reviews', 'bookings', 'transactions', 'admin_actions'];
        if ($this->category !== 'all' && in_array($this->category, $allowedCategories)) {
            $query->where('category', $this->category);
        }

        // Get total count for this category
        $this->totalCount = (clone $query)->count();

        // Load notifications in chunks
        if ($loadMore && $this->loadedCount > 0) {
            // Load next batch
            $newNotifications = $query->skip($this->loadedCount)->take($this->perPage)->get();
            
            if ($newNotifications->count() > 0) {
                // Merge with existing grouped notifications
                $newGrouped = $this->groupNotificationsByDate($newNotifications);
                $this->groupedNotifications = $this->mergeGroupedNotifications($this->groupedNotifications, $newGrouped);
                $this->loadedCount += $newNotifications->count();
            } else {
                $this->hasMore = false;
            }
        } else {
            // Initial load
            $notifications = $query->take($this->perPage)->get();
            $this->loadedCount = $notifications->count();
            $this->groupedNotifications = $this->groupNotificationsByDate($notifications);
            $this->hasMore = $this->loadedCount < $this->totalCount;
        }

        // Calculate unread count (from all notifications, not just loaded)
        $unreadQuery = Notification::where(function($query) {
                $query->where('recipient_type', 'admin')
                    ->orWhere('recipient_type', 'all');
            })
            ->whereNull('read_at');

        if ($this->category !== 'all' && in_array($this->category, $allowedCategories)) {
            $unreadQuery->where('category', $this->category);
        }

        $this->unreadCount = $unreadQuery->count();

        // Calculate unread counts per category
        $this->calculateCategoryUnreadCounts();

        $this->loading = false;
    }

    public function loadMore(): void
    {
        if ($this->hasMore && !$this->loading) {
            $this->loadNotifications(true);
        }
    }

    private function mergeGroupedNotifications($existing, $new): array
    {
        $merged = [];
        $existingGroups = [];

        // Convert existing to associative array
        foreach ($existing as $group) {
            $existingGroups[$group['group']] = $group['notifications'];
        }

        // Merge new groups
        foreach ($new as $group) {
            if (isset($existingGroups[$group['group']])) {
                // Merge notifications in same group
                $existingGroups[$group['group']] = array_merge($existingGroups[$group['group']], $group['notifications']);
            } else {
                // New group
                $existingGroups[$group['group']] = $group['notifications'];
            }
        }

        // Convert back to array format
        foreach ($existingGroups as $groupName => $notifications) {
            $merged[] = [
                'group' => $groupName,
                'notifications' => $notifications,
                'count' => count($notifications),
            ];
        }

        return $merged;
    }

    private function calculateCategoryUnreadCounts(): void
    {
        $categories = ['all', 'reviews', 'bookings', 'transactions', 'admin_actions'];
        $this->categoryUnreadCounts = [];

        foreach ($categories as $cat) {
            $query = Notification::where(function($query) {
                    $query->where('recipient_type', 'admin')
                        ->orWhere('recipient_type', 'all');
                })
                ->whereNull('read_at');

            if ($cat !== 'all') {
                $query->where('category', $cat);
            }

            $this->categoryUnreadCounts[$cat] = $query->count();
        }
    }

    private function groupNotificationsByDate($notifications): array
    {
        $grouped = [];

        foreach ($notifications as $notification) {
            $createdAt = Carbon::parse($notification->created_at);
            
            if ($createdAt->isToday()) {
                $groupKey = 'Today';
            } elseif ($createdAt->isYesterday()) {
                $groupKey = 'Yesterday';
            } elseif ($createdAt->isCurrentWeek()) {
                $groupKey = $createdAt->format('l'); // Day name (Monday, Tuesday, etc.)
            } elseif ($createdAt->isCurrentMonth()) {
                $groupKey = $createdAt->format('F j'); // Month Day (January 5)
            } else {
                $groupKey = $createdAt->format('F Y'); // Month Year (January 2025)
            }

            if (!isset($grouped[$groupKey])) {
                $grouped[$groupKey] = [];
            }

            // Always generate action_url from notification data (booking_id, review_id, transaction_id)
            $actionUrl = $this->generateActionUrl($notification);
            
            // Fix the URL to use correct routes
            $actionUrl = $this->fixActionUrl($actionUrl);

            $grouped[$groupKey][] = [
                'id' => $notification->id,
                'type' => $notification->type,
                'icon' => $notification->icon,
                'icon_url' => $notification->icon_url,
                'category' => $notification->category,
                'title' => $notification->title,
                'message' => $notification->message,
                'data' => $notification->data,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
                'time_ago' => $this->getTimeAgo($notification->created_at),
                'action_url' => $actionUrl,
            ];
        }

        // Convert to array format with keys as group names
        $result = [];
        foreach ($grouped as $groupName => $items) {
            $result[] = [
                'group' => $groupName,
                'notifications' => $items,
                'count' => count($items),
            ];
        }
        return $result;
    }

    private function getTimeAgo($datetime): string
    {
        $carbon = Carbon::parse($datetime);
        $diff = $carbon->diffForHumans();
        
        // Format: "30 min ago", "2 hours ago", etc.
        return str_replace(['before', 'after'], ['ago', ''], $diff);
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

        // No action URL available
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
        return view('livewire.admin.notifications.notifications-list');
    }
}
