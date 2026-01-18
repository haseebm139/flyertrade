<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleShow extends Component
{
    use WithPagination;

    public $roleId;
    public $role;
    public $permissions = [];
    public $selectedUsers = [];
    public $selectAllUsers = false;
    public $showDeleteModal = false;
    public $perPage = 10;
    public $sortColumn = 'created_at';
    public $sortDirection = 'desc';
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
        $this->sortColumn = 'created_at';
    }

    public function loadRole()
    {
        $this->role = Role::with(['permissions'])->findOrFail($this->roleId);
        $this->permissions = $this->role->permissions->pluck('name')->toArray();
    }

    public function getPermissionGroupsProperty()
    {
        $allPermissions = Permission::all();

        return $allPermissions->groupBy(function ($permission) {
            $name = strtolower(trim($permission->name));
            $parts = explode(' ', $name);
            $actions = ['create', 'read', 'write', 'delete', 'update', 'view', 'manage', 'can'];

            // Shuru ke tamam action words ko hatayein
            while (!empty($parts) && in_array($parts[0], $actions)) {
                array_shift($parts);
            }

            // Ab jo pehla lafz bacha hai, sirf usey return karein
            return !empty($parts) ? ucwords($parts[0]) : 'General';
        })->sortKeys();
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
                // Silent refresh - no toastr notification as user already got success message from edit
            } catch (\Exception $e) {
                $this->dispatch('showSweetAlert', type: 'error', message: 'Error refreshing role data: ' . $e->getMessage(), title: 'Error');
            }
        }
    }

    public function updatedSelectAllUsers($value)
    {
        if ($value) {
            $this->selectedUsers = $this->role->users->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function updatedSelectedUsers()
    {
        $this->selectAllUsers = false;
    }

    public function sortBy($column)
    {
        $validFields = ['user_type', 'name', 'created_at', 'last_login_at'];
        if (!in_array($column, $validFields)) {
            return;
        }

        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $assignedUsers = $this->role->users()
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.roles.role-show', [
            'permissionGroups' => $this->permissionGroups,
            'assignedUsers' => $assignedUsers
        ]);
    }
}
