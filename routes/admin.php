<?php
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('user-management/service-users', function () {
        return view('admin.pages.user_management.users.index');
    })->name('user-management.service.users.index');

    Route::get('user-management/service-users/{id}', function () {
        return view('admin.pages.user_management.users.view');
    })->name('user-management.service.users.view');
    Route::get('user-management/service-provider', function () {
        return view('admin.pages.user_management.providers.index');
    })->name('user-management.service.providers.index');

    Route::get('user-management/service-provider/{id}', function () {
        return view('admin.pages.user_management.providers.view');
    })->name('user-management.service.providers.view');
    Route::get('service-category',function(){
        return view('admin.pages.service_category.index');
    })->name('service-category.index');


    Route::get('booking',function(){
        return view('admin.pages.booking.index');
    })->name('booking.index');

    Route::get('transactions',function(){
        return view('admin.pages.transaction.index');
    })->name('transaction.index');

    Route::get('dispute',function(){
        return view('admin.pages.dispute.index');
    })->name('dispute.index');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
