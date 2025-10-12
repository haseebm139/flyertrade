<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selected = [];
    public $showFilterModal = false;
    public $fromDate = '';
    public $toDate = '';
    public $statusFilter = '';
    public $roleFilter = '';
    public $showModal = false;
    
    // Form properties
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $user_type = 'customer';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount()
    {
        // Ensure sortField is always valid for users table
        $validFields = ['name', 'email', 'user_type', 'address', 'phone', 'created_at', 'updated_at'];
        if (!in_array($this->sortField, $validFields)) {
            $this->sortField = 'name';
        }
    }

    public function render()
    {
        $users = $this->getDataQuery()->paginate($this->perPage);
        $roles = Role::all();
        return view('livewire.admin.users.users-table', compact('users', 'roles'));
    }

    private function getDataQuery()
    {
        // Ensure sortField is valid before using it
        $validFields = ['name', 'email', 'user_type', 'address', 'phone', 'created_at', 'updated_at'];
        $sortField = in_array($this->sortField, $validFields) ? $this->sortField : 'name';
        
        return User::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
            )
            ->when($this->fromDate && $this->toDate, fn($q) =>
                $q->whereBetween('created_at', [
                    $this->fromDate.' 00:00:00',
                    $this->toDate.' 23:59:59'
                ])
            )
            ->when($this->roleFilter, fn($q) => $q->whereHas('roles', fn($q) => $q->where('name', $this->roleFilter)))
            ->with('roles')
            ->orderBy($sortField, $this->sortDirection);
    }

    public function sortBy($field)
    {
        // Only allow sorting by valid fields for users table
        $validFields = ['name', 'email', 'user_type', 'address', 'phone', 'created_at', 'updated_at'];
        
        if (!in_array($field, $validFields)) {
            return;
        }
        
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openFilterModal()
    {
        $this->showFilterModal = true;
    }

    public function closeFilterModal()
    {
        $this->showFilterModal = false;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->fromDate = '';
        $this->toDate = '';
        $this->statusFilter = '';
        $this->roleFilter = '';
        $this->resetPage();
    }

    public function applyFilters()
    {
        $this->resetPage();
        $this->closeFilterModal();
    }

    public function exportCsv()
    {
        $users = $this->getDataQuery()->get();
        
        $filename = 'users_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['Name', 'Email', 'User Type', 'Roles', 'Phone', 'Address', 'Created At']);
            
            // CSV Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $user->user_type ?? 'N/A',
                    $user->roles->pluck('name')->join(', '),
                    $user->phone ?? 'N/A',
                    $user->address ?? 'N/A',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function deleteUser($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->delete();
            $this->dispatch('showToastr', 'success', 'User deleted successfully.', 'Success');
        } catch (\Exception $e) {
            $this->dispatch('showToastr', 'error', 'Error deleting user: ' . $e->getMessage(), 'Error');
        }
    }

    public function viewUser($userId)
    {
        return redirect()->route('roles-and-permissions.show', ['id' => $userId]);
    }

    public function editUser($userId)
    {
        $this->dispatch('openUserModal', $userId, 'edit');
    }

    public function assignRole($userId, $roleId)
    {
        try {
            $user = User::findOrFail($userId);
            $role = Role::findOrFail($roleId);
            
            if (!$user->hasRole($role->name)) {
                $user->assignRole($role);
                $this->dispatch('showToastr', 'success', 'Role assigned successfully.', 'Success');
            } else {
                $this->dispatch('showToastr', 'info', 'User already has this role.', 'Info');
            }
        } catch (\Exception $e) {
            $this->dispatch('showToastr', 'error', 'Error assigning role: ' . $e->getMessage(), 'Error');
        }
    }

    public function removeRole($userId, $roleId)
    {
        try {
            $user = User::findOrFail($userId);
            $role = Role::findOrFail($roleId);
            
            $user->removeRole($role);
            $this->dispatch('showToastr', 'success', 'Role removed successfully.', 'Success');
        } catch (\Exception $e) {
            $this->dispatch('showToastr', 'error', 'Error removing role: ' . $e->getMessage(), 'Error');
        }
    }

    public function openUserModal()
    {
        $this->showModal = true;
    }

    public function closeUserModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function saveUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'user_type' => 'required|in:admin,customer,provider',
        ]);

        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'user_type' => $this->user_type,
                'password' => Hash::make('password123'), // Default password
            ]);

            $this->dispatch('showToastr', 'success', 'User created successfully.', 'Success');
            $this->closeUserModal();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('showToastr', 'error', 'Error creating user: ' . $e->getMessage(), 'Error');
        }
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->user_type = 'customer';
    }

    public function addUser()
    {
        $this->openUserModal();
    }
}