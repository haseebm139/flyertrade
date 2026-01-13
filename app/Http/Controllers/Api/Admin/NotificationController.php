<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationController extends BaseController
{
    /**
     * Get all notifications for admin with filtering and grouping
     * 
     * Supports:
     * - Filter by category: all, reviews, bookings, transactions, admin_actions
     * - Group by date (Today, Yesterday, etc.)
     * - Pagination
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        // Verify user is admin
        if (!$user->hasRole('admin') && $user->user_type !== 'admin') {
            return $this->sendError('Unauthorized. Admin access required.', 403);
        }

        $query = Notification::where('recipient_type', 'admin')
            ->orWhere('recipient_type', 'all')
            ->orderBy('created_at', 'desc');

        // Filter by category
        $category = $request->get('category', 'all');
        $allowedCategories = ['reviews', 'bookings', 'transactions', 'admin_actions', 'promotions', 'services', 'messages'];
        if ($category !== 'all' && in_array($category, $allowedCategories)) {
            $query->where('category', $category);
        }

        // Get all notifications (we'll group them by date)
        $notifications = $query->get();

        // Group notifications by date
        $grouped = $this->groupNotificationsByDate($notifications);

        // Pagination (optional - can be implemented if needed)
        $perPage = $request->get('per_page', 50);
        $page = $request->get('page', 1);
        
        // For now, return all grouped notifications
        // You can implement pagination on grouped data if needed

        return $this->sendResponse([
            'notifications' => $grouped,
            'total' => $notifications->count(),
            'unread_count' => $notifications->whereNull('read_at')->count(),
        ], 'Notifications retrieved successfully.');
    }

    /**
     * Group notifications by date (Today, Yesterday, etc.)
     */
    private function groupNotificationsByDate($notifications)
    {
        $grouped = [];
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

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

            $grouped[$groupKey][] = [
                'id' => $notification->id,
                'type' => $notification->type,
                'icon' => $notification->icon,
                'icon_url' => $notification->icon_url, // Full URL to icon file
                'category' => $notification->category,
                'title' => $notification->title,
                'message' => $notification->message,
                'data' => $notification->data,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
                'time_ago' => $this->getTimeAgo($notification->created_at),
                'action_url' => $notification->data['action_url'] ?? null,
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

    /**
     * Get human-readable time ago
     */
    private function getTimeAgo($datetime)
    {
        $carbon = Carbon::parse($datetime);
        $diff = $carbon->diffForHumans();
        
        // Format: "30 min ago", "2 hours ago", etc.
        return str_replace(['before', 'after'], ['ago', ''], $diff);
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount(): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin') && $user->user_type !== 'admin') {
            return $this->sendError('Unauthorized. Admin access required.', 403);
        }

        $count = Notification::where(function($query) {
                $query->where('recipient_type', 'admin')
                    ->orWhere('recipient_type', 'all');
            })
            ->whereNull('read_at')
            ->count();
        
        return $this->sendResponse(['unread_count' => $count], 'Unread notifications count.');
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin') && $user->user_type !== 'admin') {
            return $this->sendError('Unauthorized. Admin access required.', 403);
        }

        $notification = Notification::where(function($query) {
                $query->where('recipient_type', 'admin')
                    ->orWhere('recipient_type', 'all');
            })
            ->findOrFail($id);
        
        $notification->markAsRead();
        
        return $this->sendResponse($notification, 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin') && $user->user_type !== 'admin') {
            return $this->sendError('Unauthorized. Admin access required.', 403);
        }

        $updated = Notification::where(function($query) {
                $query->where('recipient_type', 'admin')
                    ->orWhere('recipient_type', 'all');
            })
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return $this->sendResponse(['updated' => $updated], 'All notifications marked as read.');
    }

    /**
     * Delete notification
     */
    public function destroy($id): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin') && $user->user_type !== 'admin') {
            return $this->sendError('Unauthorized. Admin access required.', 403);
        }

        $notification = Notification::where(function($query) {
                $query->where('recipient_type', 'admin')
                    ->orWhere('recipient_type', 'all');
            })
            ->findOrFail($id);
        
        $notification->delete();
        
        return $this->sendResponse([], 'Notification deleted successfully.');
    }

    /**
     * Get providers awaiting document verification
     * This is a helper endpoint for the document verification notification
     */
    public function pendingDocumentVerification(): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user->hasRole('admin') && $user->user_type !== 'admin') {
            return $this->sendError('Unauthorized. Admin access required.', 403);
        }

        // Get providers with pending document verification
        // Adjust this query based on your actual provider/document verification logic
        $providers = \App\Models\User::where('user_type', 'provider')
            ->whereHas('providerProfile', function($query) {
                // Assuming there's a document verification status field
                // Adjust based on your actual schema
                $query->where('document_verified', false)
                    ->orWhereNull('document_verified');
            })
            ->with('providerProfile')
            ->get();

        return $this->sendResponse([
            'providers' => $providers,
            'count' => $providers->count(),
        ], 'Providers awaiting document verification retrieved successfully.');
    }
}
