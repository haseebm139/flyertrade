<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\UserNotification;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notifications.index');
    }

    public function send(Request $request)
    {
        event(new UserNotification("🚀 A user clicked the button!"));
        return response()->json(['status' => 'Notification sent']);
        return response()->json(['success' => true]);
    }
}
