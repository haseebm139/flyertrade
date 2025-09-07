<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
Route::get('/', function () {
    return view('welcome');
});


Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');

    return "✅ All caches cleared successfully!";
})->name('cache.clear');
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
