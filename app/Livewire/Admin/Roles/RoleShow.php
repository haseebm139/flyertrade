<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleShow extends Component
{
    public $roleId;
    public $role;
    public $permissions = [];
    public $permissionGroups = [];
    public $showDeleteModal = false;
    public $deleteRoleId;
    public $deleteRoleName;

    protected $listeners = [
        'openDeleteModal' => 'openDeleteModal',
        'closeDeleteModal' => 'closeDeleteModal',
        'roleSaved' => 'refreshRoleData',
        'roleUpdated' => 'handleRoleUpdated'
    ];

    public function mount($roleId)
    {
        $this->roleId = $roleId;
        $this->loadRole();
        $this->loadPermissions();
        
         
    }

    public function loadRole()
    {
        $this->role = Role::with(['permissions', 'users'])->findOrFail($this->roleId);
        $this->permissions = $this->role->permissions->pluck('name')->toArray();
    }

    public function loadPermissions()
    {
        $allPermissions = Permission::all();
        
        $this->permissionGroups = [
            'Dashboard' => $allPermissions->filter(function ($permission) {
                return str_contains(strtolower($permission->name), 'dashboard');
            }),
            'Users' => $allPermissions->filter(function ($permission) {
                return str_contains(strtolower($permission->name), 'user') || 
                       str_contains(strtolower($permission->name), 'customer') ||
                       str_contains(strtolower($permission->name), 'provider');
            }),
            'Bookings' => $allPermissions->filter(function ($permission) {
                return str_contains(strtolower($permission->name), 'booking');
            }),
            'Transactions' => $allPermissions->filter(function ($permission) {
                return str_contains(strtolower($permission->name), 'transaction') || 
                       str_contains(strtolower($permission->name), 'payment');
            }),
            'Reports' => $allPermissions->filter(function ($permission) {
                return str_contains(strtolower($permission->name), 'report') || 
                       str_contains(strtolower($permission->name), 'analytics');
            }),
            'Roles' => $allPermissions->filter(function ($permission) {
                return str_contains(strtolower($permission->name), 'role') || 
                       str_contains(strtolower($permission->name), 'permission');
            }),
            'Content' => $allPermissions->filter(function ($permission) {
                return str_contains(strtolower($permission->name), 'content') || 
                       str_contains(strtolower($permission->name), 'category') ||
                       str_contains(strtolower($permission->name), 'service');
            }),
            'Financial' => $allPermissions->filter(function ($permission) {
                return str_contains(strtolower($permission->name), 'financial') || 
                       str_contains(strtolower($permission->name), 'payout');
            }),
        ];

        // Remove empty groups
        $this->permissionGroups = array_filter($this->permissionGroups, function ($group) {
            return $group->count() > 0;
        });
    }

    public function updatePermissions()
    {
        try {
            $role = Role::findOrFail($this->roleId);
            $role->syncPermissions($this->permissions);
            
            $this->dispatch('showSweetAlert', type: 'success', message: 'Permissions updated successfully.', title: 'Success');
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', type: 'error', message: 'Error updating permissions: ' . $e->getMessage(), title: 'Error');
        }
    }

    public function openDeleteModal()
    {
        $this->deleteRoleId = $this->roleId;
        $this->deleteRoleName = $this->role->name;
        $this->showDeleteModal = true;
         
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteRoleId = null;
        $this->deleteRoleName = null;
         
    }

    public function deleteRole()
    {
        try {
            $role = Role::findOrFail($this->deleteRoleId);
            
            // Check if role has users
            if ($role->users()->count() > 0) {
                $this->dispatch('showSweetAlert', type: 'error', message: 'Cannot delete role. It has assigned users.', title: 'Error');
                $this->closeDeleteModal();
                return;
            }
            
            $role->delete();
            $this->dispatch('showSweetAlert', type: 'success', message: 'Role deleted successfully.', title: 'Success');
            $this->closeDeleteModal();
            
            // Redirect to roles index with tab parameter
            return redirect()->route('roles-and-permissions.index', ['tab' => 'roles']);
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', type: 'error', message: 'Error deleting role: ' . $e->getMessage(), title: 'Error');
        }
    }

    public function editRole()
    {
        $this->dispatch('openRoleModal', roleId: $this->roleId, mode: 'edit');
         
    }

    public function resetPermissions()
    {
        try {
            $this->loadRole();
            $this->dispatch('showSweetAlert', type: 'success', message: 'Permissions reset to original state.', title: 'Success');
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', type: 'error', message: 'Error resetting permissions: ' . $e->getMessage(), title: 'Error');
        }
    }

    public function refreshRoleData()
    {
        try {
            $this->loadRole();
            $this->loadPermissions();
             
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', type: 'error', message: 'Error refreshing role data: ' . $e->getMessage(), title: 'Error');
        }
    }

    public function handleRoleUpdated($updatedRoleId)
    {
        // Only refresh if the updated role is the current role
        if ($updatedRoleId == $this->roleId) {
            try {
                $this->loadRole();
                $this->loadPermissions();
                // Silent refresh - no toastr notification as user already got success message from edit
            } catch (\Exception $e) {
                $this->dispatch('showSweetAlert', type: 'error', message: 'Error refreshing role data: ' . $e->getMessage(), title: 'Error');
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.roles.role-show');
    }
}
