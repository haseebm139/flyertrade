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
            
            // Check if user has any bookings or transactions
            if ($user->bookings()->count() > 0 || $user->transactions()->count() > 0) {
                $this->dispatch('showSweetAlert', type: 'error', message: 'Cannot delete user. User has associated bookings or transactions.', title: 'Error');
                $this->closeDeleteModal();
                return;
            }
            
            $user->delete();
            $this->dispatch('showSweetAlert', type: 'success', message: 'User deleted successfully.', title: 'Success');
            $this->closeDeleteModal();
            
            // Redirect to users index
            return redirect()->route('user-management.service.users.index');
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', type: 'error', message: 'Error deleting user: ' . $e->getMessage(), title: 'Error');
        }
    }

    public function editUser()
    {
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
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
                'user.name' => 'required|string|max:255',
                'user.email' => 'required|email|unique:users,email,' . $this->userId,
                'user.phone' => 'nullable|string|max:20',
                'user.address' => 'nullable|string|max:500',
                'user.state' => 'nullable|string|max:100',
                'user.city' => 'nullable|string|max:100',
                'user.country' => 'nullable|string|max:100',
            ]);

            $this->user->save();
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
