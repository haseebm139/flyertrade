<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Modules/Categories
        $modules = [
            'Dashboard',
            'Users',
            'Providers',
            'Service Categories',
            'Bookings',
            'Transactions',
            'Disputes',
            'Reviews',
            'Roles',
            'Messages',
            'Settings',
            'Notifications'
        ];

        // 2. Define Abilities
        $abilities = ['Read', 'Write', 'Create', 'Delete'];

        // 3. Create Permissions
        foreach ($modules as $module) {
            foreach ($abilities as $ability) {
                $permissionName = "{$ability} {$module}";
                Permission::firstOrCreate(['name' => $permissionName]);
            }
        }

        // 4. Setup Roles and Assign Permissions

        // --- SUPER ADMIN ---
        $superAdminRole = Role::firstOrCreate(['name' => 'admin']);
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);

        // --- CUSTOMER ---
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $customerPermissions = [
             
        ];
        $customerRole->syncPermissions($customerPermissions);

        // --- PROVIDER ---
        $providerRole = Role::firstOrCreate(['name' => 'provider']);
        $providerPermissions = [
             
        ];
        $providerRole->syncPermissions($providerPermissions);

        // --- MULTI (Both Provider & Customer) ---
        $multiRole = Role::firstOrCreate(['name' => 'multi']);
        $multiPermissions = array_unique(array_merge($customerPermissions, $providerPermissions));
        $multiRole->syncPermissions($multiPermissions);

        $this->command->info('Permissions and Roles seeded successfully!');
    }
}
