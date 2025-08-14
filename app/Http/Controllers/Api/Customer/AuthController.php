<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\SocialRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\OtpCodeMail;
use Illuminate\Support\Str;
use App\Services\AuthService;
class AuthController extends BaseController
{
    public function __construct(private AuthService $authService) {}
    public function register(RegisterRequest $request)
    {
        $user = $this->authService->createUserWithRole($request, 'customer');

        return $this->sendResponse([], 'Registration successful');
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Invalid email or password');
        }

        if (!$user->hasRole('customer')) {
            return $this->sendError('Role mismatch');
        }

        $token = $user->createToken('customer_token', ['customer'])->plainTextToken;

        return $this->sendResponse([
            'token' => $token,
            'user'  => $user->load('roles')
        ], 'Login successful');
    }

    public function socialLogin(SocialRequest $request)
    {
        $user = $this->authService->findOrCreateSocialUser($request, 'customer');

        return $this->sendResponse([
            'token' => $user->createToken('customer_token', ['customer'])->plainTextToken,
            'user'  => $user
        ], 'Login successful');
    }

    public function guestLogin()
    {
        $username = 'Guest_' . Str::upper(Str::random(5));
        $email    = strtolower($username) . '@flyertrade.com';

        $user = User::create([
            'name'      => $username,
            'email'     => $email,
            'password'  => Hash::make(Str::random(16)),
            'is_guest'  => true,
        ]);

        $user->assignRole('customer');

        return $this->sendResponse([
            'token' => $user->createToken('guest_token', ['customer'])->plainTextToken,
            'user'  => $user
        ], 'Guest mode enabled');
    }

    public function sendCodeToEmail(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email']);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->sendError('Invalid Email Address');
        }

        $otp = rand(10000, 99999);
        $this->sendResetPasswordMail($otp, $user->email);

        return $this->sendResponse(['otp' => $otp], 'Code sent successfully');
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return $this->sendResponse([], 'Logged out successfully');
    }

    // ------------------------
    // Helpers
    // ------------------------
    private function createUserWithRole($request, string $role): User
    {
        // Check if user already exists
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // If user already has this role → return as-is
            if ($user->hasRole($role)) {
                return $user;
            }

            // Assign new role
            $user->assignRole($role);

            // Mark as multi-role if now has more than one
            if ($user->roles()->count() > 1) {
                $user->update([
                    'role_id'   => 'multi',
                    'user_type' => 'multi',
                ]);
            }

            return $user;
        }

        // If no user exists → create new
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => \Hash::make($request->password ?? \Str::random(16)),
            'role_id'   => $role,
            'user_type' => $role,
        ]);

        $user->assignRole($role);

        return $user;
    }

    private function findOrCreateSocialUser($request, string $role): User
    {
        $providerField = $request->provider . '_id';

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Update user with latest social info
            $user->update([
                $providerField => $request->social_id,
                'name'         => $request->name,
                'password'     => \Hash::make($request->social_id),
                'latitude'     => $request->latitude ?? $user->latitude,
                'longitude'    => $request->longitude ?? $user->longitude,
                'country'      => $request->country ?? $user->country,
                'city'         => $request->city ?? $user->city,
                'state'        => $request->state ?? $user->state,
                'zip'          => $request->zip ?? $user->zip,
                'address'      => $request->address ?? $user->address,
            ]);

            // If user already has role, return
            if ($user->hasRole($role)) {
                return $user;
            }

            // Assign missing role
            $user->assignRole($role);

            // If user now has multiple roles → set as multi
            if ($user->roles()->count() > 1) {
                $user->update([
                    'role_id'   => 'multi',
                    'user_type' => 'multi',
                ]);
            }

            return $user;
        }

        // New user creation
        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            $providerField => $request->social_id,
            'password'     => \Hash::make($request->social_id),
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

    private function sendResetPasswordMail($otp, $email)
    {
        $mailData = [
            'title' => 'Reset Password OTP',
            'body'  => 'Use the OTP below to reset your password.',
            'email' => $email,
            'otp'   => $otp,
            'logo'  => asset('assets/logos/email_logo.png')
        ];

        Mail::to($email)->queue(new OtpCodeMail($mailData));
    }
}
