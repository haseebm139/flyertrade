<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Api\BaseController;
use App\Models\Notification;
use App\Models\User;
use App\Services\Notification\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    /**
     * Get all notifications for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $perPage = $request->get('per_page', 15);
        $query = Notification::where('user_id', $user->id);
        $unreadCount = (clone $query)
        ->whereNull('read_at')
        ->count();
        $notifications = $query
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);
        // $notifications = Notification::where('user_id', $user->id)
        //     ->orderBy('created_at', 'desc')
        //     ->paginate($perPage);
        
        // Add icon_url to each notification
        $notifications->getCollection()->transform(function ($notification) {
            $notification->icon_url = $notification->icon_url;
            return $notification;
        });
        
        return $this->sendResponse([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ], 'Notifications retrieved successfully.');
    }

    /**
     * Test push notification
     */
    public function testPush(Request $request): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'fcm_token' => 'required|string',
            'title' => 'nullable|string',
            'body' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $token = $request->fcm_token;
        $title = $request->title ?? 'Test Notification';
        $body = $request->body ?? 'This is a test push from FlyerTrade API';
        
        $data = $request->data ?? ['test' => 'true'];

        $firebaseService = app(\App\Services\FirebaseService::class);
        $result = $firebaseService->sendToToken($token, $title, $body, $data);

        if ($result) {
            return $this->sendResponse($result, 'Push notification request sent to Google.');
        }

        return $this->sendError('Failed to send push notification. Check laravel.log for details.', 500);
    }

    public function pushNotification(Request $request): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'fcm_token' => 'required|string',
            'title' => 'nullable|string',
            'body' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $token = $request->fcm_token;
        $title = $request->title ?? 'Notification';
        $body = $request->body ?? 'You have a new message from FlyerTrade';
        
        // Use provided data or default to empty array
        $data = $request->data ?? [];
        
        // Ensure click_action is set if not provided, common for Flutter apps
        if (!isset($data['click_action'])) {
            $data['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
        }

        $firebaseService = app(\App\Services\FirebaseService::class);
        $result = $firebaseService->sendToToken($token, $title, $body, $data);

        if ($result) {
            return $this->sendResponse($result, 'Push notification request sent to Google.');
        }

        return $this->sendError('Failed to send push notification. Check laravel.log for details.', 500);
    }

    /**
     * Send a custom notification to one or many users (admin only).
     */
    public function custom(Request $request, NotificationService $notificationService): JsonResponse
    {
        $user = auth()->user();
          
        // if (!$user || (!($user->role_id === 'admin') && !($user->user_type === 'admin'))) {
        //     return $this->sendError('Unauthorized.', 403);
        // }

        $validator = \Validator::make($request->all(), [
            'user_id' => 'nullable|integer|exists:users,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'integer|exists:users,id',
            'type' => 'nullable|string',
            'title' => 'required|string',
            'message' => 'required|string',
            'recipient_type' => 'nullable|string',
            'data' => 'nullable|array',
            'icon' => 'nullable|string',
            'category' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $userIds = [];
        if (!empty($request->user_id)) {
            $userIds[] = (int) $request->user_id;
        }
        if (!empty($request->user_ids)) {
            $userIds = array_merge($userIds, array_map('intval', $request->user_ids));
        }
        $userIds = array_values(array_unique($userIds));

        if (empty($userIds)) {
            return $this->sendError('user_id or user_ids is required.', 422);
        }

        $type = $request->input('type', 'custom');
        $recipientType = $request->input('recipient_type', 'customer');
        $title = $request->input('title');
        $message = $request->input('message');
        $data = $request->input('data', []);
        $icon = $request->input('icon');
        $category = $request->input('category');

        if (count($userIds) === 1) {
            $targetUser = User::find($userIds[0]);
            if (!$targetUser) {
                return $this->sendError('User not found.', 404);
            }

            $notification = $notificationService->send(
                $targetUser,
                $type,
                $title,
                $message,
                $recipientType,
                null,
                $data,
                $icon,
                $category
            );

            return $this->sendResponse($notification, 'Notification sent.');
        }

        $count = $notificationService->sendToMany(
            $userIds,
            $type,
            $title,
            $message,
            $recipientType,
            null,
            $data,
            $icon,
            $category
        );

        return $this->sendResponse(['count' => $count], 'Notifications sent.');
    }

    /**
     * Create "new_message" notification for a user.
     */
    public function messageReceived(Request $request, NotificationService $notificationService): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'title' => 'nullable|string',
            'message' => 'nullable|string',
            'recipient_type' => 'nullable|string',
            'data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $targetUser = User::find($request->user_id);
        if (!$targetUser) {
            return $this->sendError('User not found.', 404);
        }

        $title = $request->input('title', 'New message');
        $message = $request->input('message', 'You have received a new message.');
        $recipientType = $request->input('recipient_type');
        if (!$recipientType) {
            $userType = $targetUser->user_type ?? null;
            if ($userType === 'provider' || $userType === 'multi') {
                $recipientType = 'provider';
            } elseif ($userType === 'admin') {
                $recipientType = 'admin';
            } else {
                $recipientType = 'customer';
            }
        }
        $data = $request->input('data', []);

        $notification = $notificationService->sendOnlyPushNotification(
            $targetUser,
            'new_message',
            $title,
            $message,
            $recipientType,
            null,
            $data
        );

        return $this->sendResponse([], 'Message notification sent.');
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount(): JsonResponse
    {
        $user = auth()->user();
        $count = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
        
        return $this->sendResponse(['count' => $count], 'Unread notifications count.');
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id): JsonResponse
    {
        $user = auth()->user();
        $notification = Notification::where('user_id', $user->id)
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
        $updated = Notification::where('user_id', $user->id)
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
        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);
        
        $notification->delete();
        
        return $this->sendResponse([], 'Notification deleted successfully.');
    }
}
