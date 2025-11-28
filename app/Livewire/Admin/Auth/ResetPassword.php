<?php

namespace App\Livewire\Admin\Auth;

use Livewire\Component;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
class ResetPassword extends Component
{
    public $token, $email, $password, $password_confirmation;

    public function mount($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function resetPassword()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed',
        ], [], [
            'password' => 'password',
            'password_confirmation' => 'password confirmation',
        ]);
         
        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->forceFill([
                    'password' => bcrypt($this->password),
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $this->dispatch('swal-success', message: 'Password has been reset successfully!', redirect: route('login')); 
        } else {
            $this->dispatch('swal-error', message: __($status));
        }
    }
    public function render()
    {
        return view('livewire.admin.auth.reset-password')->layout('admin.layouts.auth', [
            'title' => 'Reset Password'
        ]);
    }
}
