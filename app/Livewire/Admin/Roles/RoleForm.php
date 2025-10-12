<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RoleForm extends Component
{
    public $roleId;
    public $name = '';
    public $permissions = [];
    public $isEdit = false;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'permissions' => 'array',
    ];

    protected $messages = [
        'name.required' => 'Role name is required.',
        'name.unique' => 'A role with this name already exists.',
    ];

    protected $listeners = [
        'openRoleModal' => 'openModal'
    ];

    public function mount($roleId = null, $isEdit = false)
    {
        $this->roleId = $roleId;
        $this->isEdit = $isEdit;
        
        if ($isEdit && $roleId) {
            $this->loadRole();
        }
    }

    public function openModal($roleId = null, $mode = 'create')
    {
        $this->dispatch('showToastr', 'info', 'RoleForm openModal called', 'Debug');
        $this->roleId = $roleId;
        $this->isEdit = ($mode === 'edit');
        $this->showModal = true;
        
        if ($this->isEdit && $roleId) {
            $this->loadRole();
        } else {
            $this->resetForm();
        }
    }

    public function loadRole()
    {
        $role = Role::findOrFail($this->roleId);
        $this->name = $role->name;
        $this->permissions = $role->permissions->pluck('id')->toArray();
    }

    public function updatedName()
    {
        $this->name = Str::slug($this->name, '_');
    }

    public function save()
    {
        if ($this->isEdit) {
            $this->rules['name'] = 'required|string|max:255|unique:roles,name,' . $this->roleId;
        }

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

            $this->dispatch('showToastr', 'success', $message, 'Success');
            $this->dispatch('roleSaved');
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('showToastr', 'error', 'Error saving role: ' . $e->getMessage(), 'Error');
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->permissions = [];
        $this->roleId = null;
        $this->isEdit = false;
        $this->showModal = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $permissionGroups = [
            'Dashboard' => Permission::where('name', 'like', '%dashboard%')->get(),
            'Users' => Permission::where('name', 'like', '%user%')->get(),
            'Bookings' => Permission::where('name', 'like', '%booking%')->get(),
            'Transactions' => Permission::where('name', 'like', '%transaction%')->orWhere('name', 'like', '%payment%')->get(),
            'Reports' => Permission::where('name', 'like', '%report%')->orWhere('name', 'like', '%analytics%')->get(),
            'Roles' => Permission::where('name', 'like', '%role%')->orWhere('name', 'like', '%permission%')->get(),
        ];

        return view('livewire.admin.roles.role-form', compact('permissionGroups'));
    }
}