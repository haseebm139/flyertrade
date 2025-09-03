<?php

namespace App\Livewire\Admin\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ForgotPassword extends Component
{

    public $email = 'admin@admin.com';

    public function sendResetLink()
    {
        $this->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->dispatch('swal-success',
            message: 'Password reset link sent to your email.', 
            redirect: route('login'),
            showConfirmButton : true); 
            // session()->flash('success', 'Password reset link sent to your email.');
        } else {
            $this->addError('email', __($status));
        }
    }
    public function render()
    {
        return view('livewire.admin.auth.forgot-password')->layout('admin.layouts.auth', [
            'title' => 'Forgot Password'
        ]);
    }
}
