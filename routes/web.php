<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['web', 'auth', 'role:admin'])->get('/admin/dashboard', [DashboardController::class, 'index']);
require __DIR__.'/admin.php';
