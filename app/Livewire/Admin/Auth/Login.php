<?php

namespace App\Livewire\Admin\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    // public $email = 'admin@admin.com';
    // public $password = 'password';
    public $email = '';
    public $password = '';

    public function login()
    {
         
        $validated = $this->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);
 
        if (Auth::attempt($validated)) {
            session()->regenerate();

             
            $this->dispatch('swal-success',message: 'Login successfully!', redirect: route('dashboard'));  
        } else {
             
            $this->dispatch('swal-error', message: 'Invalid email or password.');
        }
    }

    
     

    public function render()
    {
        return view('livewire.admin.auth.login')->layout('admin.layouts.auth', [
            'title' => 'Login'
        ]);
    }
}
