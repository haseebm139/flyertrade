<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleForm extends Component
{
    public $roleId;
    public $name = '';
    public $permissions = [];
    public $isEdit = false;
    public $showModal = false;
    public $step = 1; // 1: Role Name, 2: Permissions
    public $activeTab = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . ($this->roleId ?? 'NULL'),
            'permissions' => 'required|array|min:1',
        ];
    }

    protected $messages = [
        'name.required' => 'Role name is required.',
        'name.unique' => 'A role with this name already exists.',
        'permissions.required' => 'Please select at least one permission.',
        'permissions.min' => 'Please select at least one permission.',
    ];

    public function mount($roleId = null, $isEdit = false)
    {
        $this->roleId = $roleId;
        $this->isEdit = $isEdit;
        
        if ($isEdit && $roleId) {
            $this->loadRole();
        }
    }

    #[On('openRoleModal')]
    public function openModal($roleId = null, $mode = 'create')
    {
        $this->resetValidation();
        $this->roleId = $roleId;
        $this->isEdit = ($mode === 'edit');
        $this->showModal = true;
        $this->step = 1;
        
        if ($this->isEdit && $roleId) {
            $this->loadRole();
        } else {
            $this->resetForm();
        }
    }

    public function goToPermissions()
    {
        $this->validateOnly('name');
        $this->step = 2;
        
        // Set first tab as active if not set
        if (empty($this->activeTab)) {
            $allPermissions = \DB::table('permissions')->get();
            $permissionGroups = $allPermissions->groupBy(function($permission) {
                $name = strtolower(trim($permission->name));
                $parts = explode(' ', $name);
                $actions = ['create', 'read', 'write', 'delete', 'update', 'view', 'manage', 'can'];
                while (!empty($parts) && in_array($parts[0], $actions)) {
                    array_shift($parts);
                }
                return !empty($parts) ? ucwords($parts[0]) : 'General';
            })->sortKeys();
            
            if ($permissionGroups->count() > 0) {
                $this->activeTab = Str::slug($permissionGroups->keys()->first()) . '_tab';
            }
        }
    }

    public function backToName()
    {
        $this->step = 1;
    }

    public function loadRole()
    {
        $role = Role::findOrFail($this->roleId);
        $this->name = $role->name;
        $this->permissions = $role->permissions->pluck('name')->toArray();
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEdit) {
                $role = Role::findOrFail($this->roleId);
                $role->update(['name' => $this->name]);
                $role->syncPermissions($this->permissions);
                $message = 'Role updated successfully.';
            } else {
                $role = Role::create(['name' => $this->name]);
                $role->givePermissionTo($this->permissions);
                $message = 'Role created successfully.';
            }

            $this->dispatch('showSweetAlert', 'success', $message, 'Success');
            $this->dispatch('roleSaved');
            $this->dispatch('refreshRolesTable');
            
            // If editing a role, dispatch a specific event to refresh that role's data
            if ($this->isEdit && $this->roleId) {
                $this->dispatch('roleUpdated', $this->roleId);
            }
            
            // Close the modal after successful save
            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', 'error', 'Error saving role: ' . $e->getMessage(), 'Error');
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->permissions = [];
        $this->roleId = null;
        $this->isEdit = false;
        $this->step = 1;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->step = 1;
        $this->resetForm();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $allPermissions = \DB::table('permissions')->get();
        
        $permissionGroups = $allPermissions->groupBy(function($permission) {
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

        return view('livewire.admin.roles.role-form', compact('permissionGroups'));
    }
}
