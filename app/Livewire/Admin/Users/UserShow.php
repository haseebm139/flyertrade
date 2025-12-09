<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserShow extends Component
{
    public $userId;
    public $user;
    public $roles = [];
    public $userRoles = [];
    public $showDeleteModal = false;
    public $deleteUserId;
    public $deleteUserName;
    public $showEditModal = false;
    public $editUser = [];

    protected $listeners = [
        'openDeleteModal' => 'openDeleteModal',
        'closeDeleteModal' => 'closeDeleteModal',
        'userSaved' => 'refreshUserData',
        'userUpdated' => 'handleUserUpdated'
    ];

    public function mount($userId)
    {
        
        $this->userId = $userId;
        $this->loadUser();
        $this->loadRoles();
    }

    public function loadUser()
    {
        $this->user = User::with(['roles', 'providerProfile'])->findOrFail($this->userId);
        $this->userRoles = $this->user->roles->pluck('name')->toArray();
    }

    public function loadRoles()
    {
        $this->roles = Role::all();
    }

    public function openDeleteModal()
    {
        $this->deleteUserId = $this->userId;
        $this->deleteUserName = $this->user->name;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteUserId = null;
        $this->deleteUserName = null;
    }

    public function deleteUser()
    {
        try {
            $user = User::findOrFail($this->deleteUserId);
            
            // Check if user has any related data that might prevent deletion
            // Note: Add specific checks based on your application's requirements
            // For now, we'll allow deletion but you can add checks for:
            // - Provider services
            // - Reviews
            // - Other related data
            
            $user->delete();
            $this->dispatch('showSweetAlert', type: 'success', message: 'User deleted successfully.', title: 'Success');
            $this->closeDeleteModal();
            
            // Redirect to users index
            return redirect()->route('roles-and-permissions.index');
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', type: 'error', message: 'Error deleting user: ' . $e->getMessage(), title: 'Error');
        }
    }


    public function openEditModal()
    {
        // Populate edit form with current user data
        $this->editUser = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'address' => $this->user->address,
            'state' => $this->user->state,
            'city' => $this->user->city,
            'country' => $this->user->country,
            'user_type' => $this->user->roles->first() ? $this->user->roles->first()->name : 'customer',
        ];
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editUser = [];
    }

    public function updateUserRoles()
    {
        try {
            $user = User::findOrFail($this->userId);
            $user->syncRoles($this->userRoles);
            
            $this->dispatch('showSweetAlert', type: 'success', message: 'User roles updated successfully.', title: 'Success');
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', type: 'error', message: 'Error updating user roles: ' . $e->getMessage(), title: 'Error');
        }
    }

    public function updateUser()
    {
        try {
            $this->validate([
                'editUser.name' => 'required|string|max:255',
                'editUser.email' => 'required|email|unique:users,email,' . $this->userId,
                'editUser.phone' => 'nullable|string|max:20',
                'editUser.address' => 'nullable|string|max:500',
                'editUser.state' => 'nullable|string|max:100',
                'editUser.city' => 'nullable|string|max:100',
                'editUser.country' => 'nullable|string|max:100',
                'editUser.user_type' => 'required|exists:roles,name',
            ]);

            $user = User::findOrFail($this->userId);
            
            // Remove user_type from editUser array before updating user
            $userData = $this->editUser;
            unset($userData['user_type']);
            $user->update($userData);
            
            // Update user role - remove all existing roles and assign new one
            $user->syncRoles([$this->editUser['user_type']]);
            
            $this->loadUser(); // Refresh user data
            $this->closeEditModal();
            $this->dispatch('showSweetAlert', type: 'success', message: 'User updated successfully.', title: 'Success');
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', type: 'error', message: 'Error updating user: ' . $e->getMessage(), title: 'Error');
        }
    }

    public function refreshUserData()
    {
        try {
            $this->loadUser();
            $this->loadRoles();
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', type: 'error', message: 'Error refreshing user data: ' . $e->getMessage(), title: 'Error');
        }
    }

    public function handleUserUpdated($updatedUserId)
    {
        // Only refresh if the updated user is the current user
        if ($updatedUserId == $this->userId) {
            try {
                $this->loadUser();
                $this->loadRoles();
            } catch (\Exception $e) {
                $this->dispatch('showSweetAlert', type: 'error', message: 'Error refreshing user data: ' . $e->getMessage(), title: 'Error');
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.users.user-show');
    }
}
