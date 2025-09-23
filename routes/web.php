<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\NotificationController;
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
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
