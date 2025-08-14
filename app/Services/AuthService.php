<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Create a new user or assign a role to an existing one.
     */
    public function createUserWithRole($request, string $role): User
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // If already has this role → just return
            if ($user->hasRole($role)) {
                return $user;
            }

            // Assign missing role
            $user->assignRole($role);

            // If now has multiple roles → set multi
            if ($user->roles()->count() > 1) {
                $user->update([
                    'role_id'   => 'multi',
                    'user_type' => 'multi',
                ]);
            }

            return $user;
        }

        // Create new user
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password ?? Str::random(16)),
            'role_id'   => $role,
            'user_type' => $role,
        ]);

        $user->assignRole($role);

        return $user;
    }

    /**
     * Create or update a user from social login data.
     */
    public function findOrCreateSocialUser($request, string $role): User
    {
        $providerField = $request->provider . '_id';
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->update([
                $providerField => $request->social_id,
                'name'         => $request->name,
                'password'     => Hash::make($request->social_id),
                'latitude'     => $request->latitude ?? $user->latitude,
                'longitude'    => $request->longitude ?? $user->longitude,
                'country'      => $request->country ?? $user->country,
                'city'         => $request->city ?? $user->city,
                'state'        => $request->state ?? $user->state,
                'zip'          => $request->zip ?? $user->zip,
                'address'      => $request->address ?? $user->address,
            ]);

            if (!$user->hasRole($role)) {
                $user->assignRole($role);

                if ($user->roles()->count() > 1) {
                    $user->update([
                        'role_id'   => 'multi',
                        'user_type' => 'multi',
                    ]);
                }
            }

            return $user;
        }

        // New social user
        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            $providerField => $request->social_id,
            'password'     => Hash::make($request->social_id),
            'role_id'      => $role,
            'user_type'    => $role,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'country'      => $request->country,
            'city'         => $request->city,
            'state'        => $request->state,
            'zip'          => $request->zip,
            'address'      => $request->address,
        ]);

        $user->assignRole($role);

        return $user;
    }
}
