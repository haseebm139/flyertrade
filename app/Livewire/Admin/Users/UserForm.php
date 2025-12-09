<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:500',
        'user_type' => 'required|exists:roles,name',
        'roles' => 'array',
    ];

    protected $messages = [
        'name.required' => 'Name is required.',
        'email.required' => 'Email is required.',
        'email.unique' => 'A user with this email already exists.',
        'user_type.required' => 'User type is required.',
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
        $this->user_type = $user->user_type ?? 'customer';
        $this->roles = $user->roles->pluck('name')->toArray();
    }

    public function save()
    {
        if ($this->isEdit) {
            $this->rules['email'] = 'required|email|max:255|unique:users,email,' . $this->userId;
        }

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
                ]);
                $user->syncRoles($this->roles);
                $message = 'User updated successfully.';
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'user_type' => $this->user_type,
                    'password' => Hash::make('password123'), // Default password
                ]);
                 
                $user->assignRole($this->user_type);
                $message = 'User created successfully.';
            }

            $this->dispatch('showToastr', 'success', $message, 'Success');
            $this->dispatch('userSaved');
            $this->dispatch('refreshUsersTable');
            
            // If editing a user, dispatch a specific event to refresh that user's data
            if ($this->isEdit && $this->userId) {
                $this->dispatch('userUpdated', $this->userId);
            }
            
            $this->closeUserModal();
        } catch (\Exception $e) {
            $this->dispatch('showToastr', 'error', 'Error saving user: ' . $e->getMessage(), 'Error');
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->user_type = 'customer';
        $this->roles = [];
        $this->userId = null;
        $this->isEdit = false;
    }

    public function openUserModal($userId = null, $mode = 'create')
    {
        $this->userId = $userId;
        $this->isEdit = ($mode === 'edit');
        $this->showModal = true;
        
        if ($this->isEdit && $userId) {
            $this->loadUser();
        } else {
            // Reset form data but keep modal open
            $this->name = '';
            $this->email = '';
            $this->phone = '';
            $this->address = '';
            $this->user_type = 'customer';
            $this->roles = [];
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