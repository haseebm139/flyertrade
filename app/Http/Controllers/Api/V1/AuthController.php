<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\SocialRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\BaseController;

use App\Models\User;
class AuthController extends BaseController
{
    public function register(RegisterRequest $request)
    {
        $checkUser = User::where('email', $request->email)->first();
        if ($checkUser) {
            $hasRole = $checkUser->roles()->where('name', $request->role)->exists();
            if ($hasRole) {
                // Same email + same role → Error
                return $this->sendError('User already registered with this role.', [], 409);
            }
            if (!$hasRole) {
            // Assign the new role
            $checkUser->assignRole($request->role);

                // If now has more than one role, mark as multi
                if ($checkUser->roles()->count() > 1) {
                    $checkUser->update([
                        'role_id'   => 'multi',
                        'user_type' => 'multi',
                    ]);
                }

                    $message = $checkUser->roles()->count() > 1
                        ? 'Now registered with both roles'
                        : 'Registration successful';
            } else {
                // Already has this role
                $message = $checkUser->roles()->count() > 1
                    ? 'Already registered with both roles'
                    : 'Already registered with this role';
            }
            return $this->sendResponse([], $message);
        }
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'role_id'          => $request->role,
            'user_type'          => $request->role,
            'password'      => Hash::make($request->password),
        ]);
        $user->assignRole($request->role);
        return $this->sendResponse([], 'Registration successful');
    }

    public function login(LoginRequest $request)
    {

        $user = User::where('email', $request->email)->first();


        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Invalid credentials');
        }


        if ($request->filled('role')) {
            $hasRole = $user->roles()->where('name', $request->role)->exists();

            if (!$hasRole) {
                return $this->sendError('Role mismatch');
            }
        }
        return $this->sendResponse([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user'  => $user->load('roles'), // Load roles for response
        ], 'Login successful');
    }

    public  function socialLogin(SocialRequest $request, $provider)
    {
        $providerField = $provider . '_id';

        $user = User::where('email', $request->email)->first();
        $data = [
            $providerField      => $request->social_id,
            'name'              => $request->name,
            'password'          =>Hash::make($request->social_id),
            'role_id'           => $request->role,
            'user_type'         => $request->role,
            'latitude'          => $user->latitude ?? null,
            'longitude'         => $user->longitude  ?? null,
            'country'           => $user->country  ?? null,
            'city'              => $user->city  ?? null,
            'state'             => $user->state  ?? null,
            'zip'               => $user->zip  ?? null,
            'address'           => $user->address  ?? null,

        ];
        if ($user) {
            $user->update($data);
             return $this->sendResponse([
            'token' => $user->createToken('guest_token')->plainTextToken,
            'user'  => $user
        ], 'Login successful');
        }
        $data['email'] = $request->email;
        $user = User::create($data);

        $user->assignRole($request->role); // roles should be 'customer' or 'provider'

        return $this->sendResponse([
            'token' => $user->createToken('guest_token')->plainTextToken,
            'user'  => $user
        ], 'Login successful');

    }

    public function guestLogin()
    {
        $user = User::create([
            'name'        => 'Guest',
            'role'        => 'customer',
            'is_verified' => false
        ]);

        return $this->sendResponse([
            'token' => $user->createToken('guest_token')->plainTextToken,
            'user'  => $user
        ], 'Guest mode enabled');
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'address' => 'nullable|string',
            'lat'     => 'nullable|numeric',
            'lng'     => 'nullable|numeric',
        ]);

        $user = auth()->user();
        $user->update($request->only('address', 'lat', 'lng'));

        return $this->sendResponse($user, 'Location updated successfully');
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->sendResponse([], 'Logged out successfully');
    }


}
