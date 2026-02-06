<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\NotificationController;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    try {
        // dd( $serviceAccountPath = storage_path('firebase/firebase_credentials.json'));
        $data = ['message' => 'Hello, this is a test email from Flyertrade!'];
        Mail::to('flyertradead@gmail.com')->send(new TestEmail($data));
        return "✅ Email sent successfully to flyertradead@gmail.com!";
    } catch (\Exception $e) {
        return "❌ Failed to send email. Error: " . $e->getMessage();
    }
});

Route::get('/testing', function () {
    dd('Hello world');
});
Route::get('/', function () {
    return view('welcome');
});

Route::get('/notifications', [NotificationController::class, 'index']);
Route::post('/notifications/send', [NotificationController::class, 'send'])->name('notifications.send');

Route::view('/notifications/debug', 'notifications.debug');
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');

    return "✅ All caches cleared successfully!";
})->name('cache.clear');
Route::view('/privacy-policy', 'pages.privacy-policy')->name('privacy-policy');
Route::view('/terms-conditions', 'pages.terms-conditions')->name('terms-conditions');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
