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
use App\Models\ProviderWorkingHour;
use App\Models\User;
use App\Mail\OtpCodeMail;
use Str;
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
            return $this->sendError('Invalid email or password');
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->sendError('Invalid email or password');
        }

        if ($request->filled('role')) {
            $hasRole = $user->roles()->where('name', $request->role)->exists();

            if (!$hasRole) {
                return $this->sendError('Role mismatch');
            }
        }

        try {
            $token = $user->createToken('guest_token')->plainTextToken;
        } catch (\Exception $e) {
            return $this->sendError('Error generating token: ' . $e->getMessage());
        }

        return $this->sendResponse([
            'token' => $token,
            'user'  => $user->load('providerProfile'), // Load roles for response
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
        if ($role == 'provider') {
            $profile = $user->providerProfile()->create([]);
            ProviderWorkingHour::seedDefaultHours($user->id, $profile->id);
        }
        return $user->load('providerProfile');
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
        $user->assignRole('customer');
        $user = $user->fresh();
        return $this->sendResponse([
            'token' => $user->createToken('guest_token')->plainTextToken,
            'user'  => $user->load('providerProfile'),
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

        // $otp = rand(100000, 999999);
        $otp = 123456;


        // Send email (assuming helper function works)
        $this->sendResetPasswordMail($otp, $user['email']);
        $user->update(['otp' => $otp]);
        return $this->sendResponse(['otp' => $otp], 'Code sent successfully');
    }
    public function updatePassword(Request $request){
         $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'code' => 'required|numeric',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $checkOtp = User::where('email', $request->email)->where('otp', $request->code)->first();

        if (!$checkOtp) {
            return $this->sendError('Invalid Code');
        }
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password), 'otp' => null]);
        return $this->sendResponse([], 'Password updated successfully');


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


        Mail::to($email)->send(new OtpCodeMail($mailData));
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

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password'      => 'required',
            'new_password'          => 'required|min:8|confirmed', // confirmation required
            'new_password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => true,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = auth()->user();

        // ✅ Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError("Your current password is incorrect.",400);
        }

        // ✅ Prevent same password reuse
        if (Hash::check($request->new_password, $user->password)) {
            return $this->sendError("New password cannot be the same as the current password.",400);

        }

        // ✅ Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        return $this->sendResponse([], 'Password changed successfully.',200);

    }


}
