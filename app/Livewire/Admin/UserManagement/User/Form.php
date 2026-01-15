<?php

namespace App\Livewire\Admin\UserManagement\User;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\UserCredentialsMail;
class Form extends Component
{

    public $showModal = false;
    public $userId;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    protected $listeners = [        
        'addItemRequested' => 'open',
    ];

    public function open($id = null)
    {
         
        $this->resetValidation();
        $this->resetForm();

        if ($id) {
            $data = User::find($id);
            if ($data) {
                $this->userId   = $data->id;
                $this->name     = $data->name;
                $this->email    = $data->email;
                $this->phone    = $data->phone;
                $this->address  = $data->address;
            }
        }

        $this->showModal = true;
    }
    public function close()
    {
        $this->showModal = false;
    }

    public function save()
    {
        // Validate first - validation errors will show under fields automatically
        $this->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'required|string|regex:/^[0-9+\-() ]+$/|min:10|max:20',
            'address'   => 'required',
        ], [
            'phone.regex' => 'The phone number format is invalid. Only digits, +, -, spaces, and parentheses are allowed.',
            'phone.min' => 'The phone number must be at least 10 characters.',
            'phone.max' => 'The phone number must not exceed 20 characters.',
        ]);

        try {
            // Generate random password (8-12 characters with letters and numbers)
            $password = Str::random(10);
            $hashedPassword = Hash::make($password);

            $user = User::create([
                'name'      => $this->name,
                'email'     => $this->email,
                'phone'     => $this->phone,
                'address'   => $this->address,
                'password'  => $hashedPassword,
                'role_id'   => 'customer',
                'user_type' => 'customer',
            ]);

            $user->assignRole('customer');

            // Send email with credentials in background (non-blocking)
            // Dispatch email sending to avoid delay in user response
            dispatch(function () use ($user, $password) {
                try {
                    Mail::to($user->email)->send(new UserCredentialsMail($user, $password));
                } catch (\Exception $e) {
                    \Log::error('Failed to send credentials email to user: ' . $user->email . ' - ' . $e->getMessage());
                }
            })->afterResponse();

            $this->dispatch('showSweetAlert', 'success', 'Service User Created Successfully. Credentials sent to email.', 'Success');
            $this->dispatch('categoryUpdated');
            $this->close();
        } catch (\Exception $e) {
            // Only show toastr for non-validation errors (database errors, etc.)
            $this->dispatch('showSweetAlert', 'error', 'Error creating user: ' . $e->getMessage(), 'Error');
        }
    } 

    public function update()
    {
        
        $this->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users,email,' . $this->userId,
            'phone'     => 'required|string|regex:/^[0-9+\-() ]+$/|min:10|max:20',
            'address'   => 'required',
        ], [
            'phone.regex' => 'The phone number format is invalid. Only digits, +, -, spaces, and parentheses are allowed.',
            'phone.min' => 'The phone number must be at least 10 characters.',
            'phone.max' => 'The phone number must not exceed 20 characters.',
        ]);

        try {
            $user = User::where('id',$this->userId)
            ->where('user_type','customer')->first();
            if(!$user) {
                $this->dispatch('showSweetAlert', 'error', 'Service User not found.', 'Error');
                $this->close();
                return;
            }
            $user->update([
                'name'    => $this->name,
                'email'   => $this->email,
                'phone'   => $this->phone,
                'address' => $this->address,
            ]);         

            $this->dispatch('showSweetAlert', 'success', 'Service User updated successfully.', 'Success');
            $this->dispatch('categoryUpdated');
            $this->close();
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', 'error', 'Error updating user: ' . $e->getMessage(), 'Error');
        }
    }
    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = ''; 
    }
    public function render()
    {
         
        return view('livewire.admin.user-management.user.form');
    }
}
