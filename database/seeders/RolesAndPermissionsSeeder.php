<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Abilities
        $abilities = ['read', 'write', 'create', 'delete'];

        // Permissions by role
        $permissions_by_role = [
            'admin' => [
                'user management',
                'content management',
                'financial management',
                'reporting',
            ],
            'customer' => [
                'view products',
                'place orders',
                'manage profile',
            ],
            'provider' => [
                'manage services',
                'view bookings',
                'manage profile',
            ],
        ];

        // Create all unique permissions from all roles
        foreach ($permissions_by_role as $permissions) {
            foreach ($permissions as $permission) {
                foreach ($abilities as $ability) {
                    Permission::firstOrCreate(['name' => "$ability $permission"]);
                }
            }
        }

        // Create roles and assign relevant permissions
        foreach ($permissions_by_role as $role => $permissions) {
            $full_permissions_list = [];

            foreach ($permissions as $permission) {
                foreach ($abilities as $ability) {
                    $full_permissions_list[] = "$ability $permission";
                }
            }

            Role::firstOrCreate(['name' => $role])
                ->syncPermissions($full_permissions_list);
        }

        // // Assign roles to existing users
        // if ($admin = User::find(1)) {
        //     $admin->assignRole('admin');
        // }
        // if ($customer = User::find(2)) {
        //     $customer->assignRole('customer');
        // }
        // if ($provider = User::find(3)) {
        //     $provider->assignRole('provider');
        // }


    }
}
