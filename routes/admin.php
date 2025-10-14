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

    Route::get('reviews',function(){
        return view('admin.pages.reviews.index');
    })->name('reviews.index');


    Route::get('roles-and-permissions',function(){
        return view('admin.pages.roles_and_permissions.index');
    })->name('roles-and-permissions.index');

    Route::get('roles-and-permissions/roles/show/{id}', function($id) {
        $data = \Spatie\Permission\Models\Role::with('permissions')->findOrFail($id);
        $title = $data->name ?? '';
        $type = 'role';
        $tab_type = "Roles";
        
        return view('admin.pages.roles_and_permissions.show', compact('data', 'title', 'type', 'tab_type'));
    })->name('roles-and-permissions.roles.show');

    Route::get('roles-and-permissions/users/show/{id}', function($id) {
        $data = \App\Models\User::with('roles')->findOrFail($id);
        $title = $data->name ?? '';
        $type = 'user';
        $tab_type = "Users";
        
        return view('admin.pages.roles_and_permissions.show', compact('data', 'title', 'type', 'tab_type'));
    })->name('roles-and-permissions.users.show');

    Route::get('messages',function(){
        return view('admin.pages.messages.index');
    })->name('messages.index');

    Route::get('settings',function(){
        return view('admin.pages.settings.index');
    })->name('settings.index');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
