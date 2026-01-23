<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Api\BaseController;
use App\Models\Notification;
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
        
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        // Add icon_url to each notification
        $notifications->getCollection()->transform(function ($notification) {
            $notification->icon_url = $notification->icon_url;
            return $notification;
        });
        
        return $this->sendResponse($notifications, 'Notifications retrieved successfully.');
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
