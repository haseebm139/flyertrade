<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\SocialRequest;

use App\Models\User;
use App\Mail\OtpCodeMail;

class AuthController extends BaseController
{
    public function register(RegisterRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            return $this->sendError('Email already registered');
        }

        try {
            $this->createOrUpdateUserWithRole($user, $request, $request->role);
        } catch (\Exception $e) {
            return $this->sendError('Error creating user: ' . $e->getMessage());
        }

        return $this->sendResponse([], 'Registration successful');
    }

    public function login(LoginRequest $request)
    {

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendError('User not found');
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->sendError('Invalid password');
        }

        if ($request->filled('role')) {
            $hasRole = $user->roles()->where('name', $request->role)->exists();

            if (!$hasRole) {
                return $this->sendError('Role mismatch');
            }
        }

        try {
            $token = $user->createToken('auth_token')->plainTextToken;
        } catch (\Exception $e) {
            return $this->sendError('Error generating token: ' . $e->getMessage());
        }

        return $this->sendResponse([
            'token' => $token,
            'user'  => $user, // Load roles for response
        ], 'Login successful');
    }

    public  function socialLogin(SocialRequest $request, $provider)
    {
        $providerField = $provider . '_id';

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update([
                $providerField => $request->social_id,
                'name'         => $request->name,
                'password'     => Hash::make($request->social_id),
                'latitude'     => $request->latitude ?? $user->latitude ?? null,
                'longitude'    => $request->longitude ?? $user->longitude ?? null,
                'country'      => $request->country ?? $user->country ?? null,
                'city'         => $request->city ?? $user->city ?? null,
                'state'        => $request->state  ?? $user->state ?? null,
                'zip'          => $request->zip     ?? $user->zip ?? null,
                'address'      => $request->address ?? $user->address ?? null,

            ]);
        }
        // Create or update user with role
        $user = $this->createOrUpdateUserWithRole($user, $request, $request->role);

        return $this->sendResponse([
            'token' => $user->createToken('guest_token')->plainTextToken,
            'user'  => $user
        ], 'Login successful');

    }

    /**
 * Create a new user or assign a new role to an existing user.
 */
    private function createOrUpdateUserWithRole($user, $request, $role)
    {
        if ($user) {
            // Already has this role → just return user
            if ($user->roles()->where('name', $role)->exists()) {
                return $user;
            }

            // Assign new role
            $user->assignRole($role);

            // If user now has multiple roles, mark as multi
            if ($user->roles()->count() > 1) {
                $user->update([
                    'role_id'   => 'multi',
                    'user_type' => 'multi',
                ]);
            }

            return $user;
        }

        // New user creation
        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role_id'   => $role,
            'user_type' => $role,
            'password'  => Hash::make($request->password ?? $request->social_id),
        ];

        $user = User::create($data);
        $user->assignRole($role);

        return $user;
    }
    public function guestLogin()
    {
        $username = 'Guest_' . Str::upper(Str::random(5));
        $email    = strtolower($username) . '@flyertrade.com';
        $user = User::create([
            'name'      => $username,
            'email'     => $email,
            'password'  => Hash::make(Str::random(16)), // Random password
            'is_guest'  => true,
        ]);

        return $this->sendResponse([
            'token' => $user->createToken('guest_token')->plainTextToken,
            'user'  => $user
        ], 'Guest mode enabled');
    }


    public function sendCodeToEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendError('Invalid Email Address');
        }

        $otp = rand(10000, 99999);


        // Send email (assuming helper function works)
        $this->sendResetPasswordMail($otp, $user['email']);

        return $this->sendResponse(['otp' => $otp], 'Code sent successfully');
    }
    public function updateLocation(Request $request)
    {
        $request->validate([
            'country' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip'   => 'nullable|string',
            'address' => 'nullable|string',
            'lat'     => 'nullable|numeric',
            'lng'     => 'nullable|numeric',
        ]);

        $user = auth()->user();
        $user->update($request->only('address', 'latitude', 'longitude', 'country', 'city', 'state', 'zip'));

        return $this->sendResponse($user, 'Location updated successfully');
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->sendResponse([], 'Logged out successfully');
    }

    protected function sendResetPasswordMail($otp, $email)
    {
        $mailData = [
            'title' => 'Reset Password OTP',
            'body' => 'Use the OTP below to reset your password.',
            'email' => $email,
            'otp' => $otp,
            'logo' => asset('assets/logos/email_logo.png') // this will make it absolute
        ];


        Mail::to($email)->queue(new OtpCodeMail($mailData));
        return true;

    }
    // protected function sendVerificationMail($otp, $email)
    // {
    //     // Send Email
    //     Mail::send('emails.reset-password-email', ['otp' => $otp], function ($message) use ($email) {
    //         $message->to($email, 'Verification Code From FlyerTrade');
    //         $message->subject('You have received Verification Code');
    //     });
    // }


}
