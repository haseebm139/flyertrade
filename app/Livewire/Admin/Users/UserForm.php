<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsMail;

class UserForm extends Component
{
    public $userId;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $user_type = '';
    public $roles = [];
    public $isEdit = false;
    public $showModal = false;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'phone' => 'required|string|regex:/^[0-9+\-() ]+$/|min:10|max:20',
            'address' => 'required|string|max:500',
            'user_type' => 'required|exists:roles,name',
        ];
    }

    protected $messages = [
        'name.required' => 'Name is required.',
        'email.required' => 'Email is required.',
        'email.unique' => 'A user with this email already exists.',
        'user_type.required' => 'Please select a user type.',
        'phone.regex' => 'The phone number format is invalid.',
        'phone.min' => 'The phone number must be at least 10 characters.',
    ];

    protected $listeners = [
        'openUserModal' => 'openUserModal'
    ];

    public function mount($userId = null, $isEdit = false)
    {
        $this->userId = $userId;
        $this->isEdit = $isEdit;
        
        if ($isEdit && $userId) {
            $this->loadUser();
        }
    }

    public function loadUser()
    {
        $user = User::findOrFail($this->userId);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->address = $user->address ?? '';
        $this->user_type = $user->user_type ?? '';
        $this->roles = $user->roles->pluck('name')->toArray();
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEdit) {
                $user = User::findOrFail($this->userId);
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'user_type' => $this->user_type,
                    'role_id'   => $this->user_type,
                ]);
                $user->syncRoles([$this->user_type]);
                $message = 'User updated successfully.';
            } else {
                // Generate random password for new user
                $password = Str::random(10);
                
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'user_type' => $this->user_type,
                    'role_id'   => $this->user_type,
                    'password' => Hash::make($password),
                ]);
                 
                $user->assignRole($this->user_type);
                $message = 'User created successfully. Credentials sent via email.';

                // Send email with credentials
                try {
                    Mail::to($user->email)->send(new UserCredentialsMail($user, $password));
                } catch (\Exception $mailEx) {
                    \Log::error('Failed to send credentials email: ' . $mailEx->getMessage());
                    $message .= ' (Note: Email delivery failed)';
                }
            }

            $this->dispatch('showSweetAlert', 'success', $message, 'Success');
            $this->dispatch('userSaved');
            $this->dispatch('refreshUsersTable');
            
            if ($this->isEdit && $this->userId) {
                $this->dispatch('userUpdated', $this->userId);
            }
            
            $this->closeUserModal();
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', 'error', 'Error saving user: ' . $e->getMessage(), 'Error');
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->user_type = '';
        $this->roles = [];
        $this->userId = null;
        $this->isEdit = false;
    }

    public function openUserModal($userId = null, $mode = 'create')
    {
        $this->resetValidation();
        $this->resetForm();
        
        $this->userId = $userId;
        $this->isEdit = ($mode === 'edit');
        $this->showModal = true;
        
        if ($this->isEdit && $userId) {
            $this->loadUser();
        }
    }

    public function closeUserModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $availableRoles = Role::all();
        return view('livewire.admin.users.user-form', compact('availableRoles'));
    }
}