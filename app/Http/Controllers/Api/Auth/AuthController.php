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
use Illuminate\Support\Facades\Http;  
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

    public  function socialLogin(SocialRequest $request, $provider = 'google')
    {
        $providerField = $provider . '_id';
         
        if ($providerField !== 'google_id') {
            return $this->sendError('Invalid provider');
        }  
        $user = User::where('email', $request->email)->first();
        $role = $request->role ?? null;
        if (!$role && $user) {
            $role = $user->role_id ?? null;
        }
        if (!$role) {
            $role = 'customer';
        }
        // Create or update user with role (role mismatch handled inside)
        $user = $this->createOrUpdateUserWithRole($user, $request, $role);
        if ($user) {
            $user->update([
                $providerField => $request->social_id,
                'name'         => $request->name, 
                'latitude'     => $request->latitude ?? $user->latitude ?? null,
                'longitude'    => $request->longitude ?? $user->longitude ?? null,
                'country'      => $request->country ?? $user->country ?? null,
                'city'         => $request->city ?? $user->city ?? null,
                'state'        => $request->state  ?? $user->state ?? null,
                'zip'          => $request->zip     ?? $user->zip ?? null,
                'address'      => $request->address ?? $user->address ?? null,
            ]);
        }

        return $this->sendResponse([
            'token' => $user->createToken('guest_token')->plainTextToken,
            'user'  => $user->load('providerProfile')
        ], 'Login successful');

    }

    /**
 * Create a new user or assign a new role to an existing user.
 */
    private function createOrUpdateUserWithRole($user, $request, $role)
    {
        if ($user) {

            $existingRole = $user->user_type; // or role_id
            if ($existingRole !== $role) {
                abort(response()->json([
                    'status' => false,
                    'message' => "This email already exists as {$existingRole}. Please login using that role."
                ], 409));
            }
            // Already has this role → just return user
            if ($user->roles()->where('name', $role)->exists()) {
                return $user;
            }

            // Assign new role
            $user->assignRole($role);

            // If user now has multiple roles, mark as multi
            // if ($user->roles()->count() > 1) {
            //     $user->update([
            //         'role_id'   => 'multi',
            //         'user_type' => 'multi',
            //     ]);
            // }

            return $user;
        }

        // New user creation
        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role_id'   => $role,
            'user_type' => $role,
            'password'  => Hash::make($request->password ?? $request->social_id),
            'phone'     => $request->phone ?? null,
            'latitude'  => $request->latitude ?? null,
            'longitude' => $request->longitude ?? null,
            'country'   => $request->country ?? null,
            'city'      => $request->city ?? null,
            'state'     => $request->state ?? null,
            'zip'       => $request->zip ?? null,
            'address'   => $request->address ?? null,
            
        ];

        $user = User::create($data);
        $user->assignRole($role);
        if ($role == 'provider') {
            $profile = $user->providerProfile;
            // $profile = $user->providerProfile()->create([]);
            ProviderWorkingHour::seedDefaultHours($user->id, $profile->id);
        }
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

        $otp = rand(100000, 999999);
        // $otp = 123456;


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

    public function googleLogin(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'id_token' => 'required',
            'role' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        // 🔐 verify token from Google
        $response = Http::get(
            'https://oauth2.googleapis.com/tokeninfo',
            ['id_token' => $request->id_token]
        );

        if (!$response->ok()) {
            return $this->sendError('Invalid Google token');
            
        }

        $googleUser = $response->json();

        // 🔥 verify this token is for YOUR app
        if ($googleUser['aud'] !== config('services.google.client_id')) {
            return $this->sendError('Token does not belong to this application');             
        }

        if ($googleUser['email_verified'] !== 'true') {
            return $this->sendError('Google email not verified');
            
        }

        $email    = $googleUser['email'] ??'Googleuser@gmail.com';
        $googleId = $googleUser['sub'] ?? null;
        $name     = $googleUser['name'] ?? 'Google User';

        // 🔎 find user
        $user = User::where('email', $email)->first();

        // ❌ email exists with other role
        if ($user && $user->user_type !== $request->role) {
            return $this->sendError("This email already exists as {$user->user_type}. Please login using that role.");
             
        }

        // ✅ create user if not exists
        if (!$user) {
            $user = User::create([
                'name'      => $name,
                'email'     => $email,
                'google_id' => $googleId,
                'role_id'   => $request->role,
                'user_type' => $request->role,
                'password'  => Hash::make(Str::random(30)),
            ]);

            $user->assignRole($request->role);

            // provider setup
            if ($request->role === 'provider') { 
                ProviderWorkingHour::seedDefaultHours($user->id, $profile->id);
            }
        } else {
            // update google id if missing
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleId
                ]);
            }
        }
        $token = $user->createToken('guest_token')->plainTextToken;
        return $this->sendResponse([
            'token' => $token,
            'user'  => $user->load('providerProfile'), // Load roles for response
        ], 'Login With Google successfully');
         
    }
}
