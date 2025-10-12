<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolesTable extends Component
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

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount()
    {
        // Ensure sortField is always valid for roles table
        $validFields = ['name', 'created_at', 'updated_at', 'users_count'];
        if (!in_array($this->sortField, $validFields)) {
            $this->sortField = 'name';
        }
    }

    public function render()
    {
        $roles = $this->getDataQuery()->paginate($this->perPage);
        return view('livewire.admin.roles.roles-table', compact('roles'));
    }

    private function getDataQuery()
    {
        // Ensure sortField is valid before using it
        $validFields = ['name', 'created_at', 'updated_at', 'users_count'];
        $sortField = in_array($this->sortField, $validFields) ? $this->sortField : 'name';
        
        return Role::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->fromDate && $this->toDate, fn($q) =>
                $q->whereBetween('created_at', [
                    $this->fromDate.' 00:00:00',
                    $this->toDate.' 23:59:59'
                ])
            )
            ->withCount('users')
            ->with(['users' => function($query) {
                $query->select('id', 'name', 'avatar', 'email')->limit(1);
            }])
            ->orderBy($sortField, $this->sortDirection);
    }

    public function sortBy($field)
    {
        // Only allow sorting by valid fields for roles table
        $validFields = ['name', 'created_at', 'updated_at', 'users_count'];
        
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
        $this->resetPage();
    }

    public function applyFilters()
    {
        $this->resetPage();
        $this->closeFilterModal();
    }

    public function exportCsv()
    {
        $roles = $this->getDataQuery()->get();
        
        $filename = 'roles_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($roles) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['Role Name', 'Users Count', 'Created At', 'Updated At']);
            
            // CSV Data
            foreach ($roles as $role) {
                fputcsv($file, [
                    $role->name,
                    $role->users_count,
                    $role->created_at->format('Y-m-d H:i:s'),
                    $role->updated_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function deleteRole($roleId)
    {
        try {
            $role = Role::findOrFail($roleId);
            
            // Check if role has users
            if ($role->users()->count() > 0) {
                $this->dispatch('showToastr', 'error', 'Cannot delete role. It has assigned users.', 'Error');
                return;
            }
            
            $role->delete();
            $this->dispatch('showToastr', 'success', 'Role deleted successfully.', 'Success');
        } catch (\Exception $e) {
            $this->dispatch('showToastr', 'error', 'Error deleting role: ' . $e->getMessage(), 'Error');
        }
    }

    public function viewRole($roleId)
    {
        return redirect()->route('roles-and-permissions.show', ['id' => $roleId, 'type' => 'role']);
    }

    public function editRole($roleId)
    {
        $this->dispatch('openRoleModal', $roleId, 'edit');
    }

    public function addRole()
    {
         
        $this->dispatch('openRoleModal', null, 'create'); 
    }

    public function openRoleModal($roleId = null, $mode = 'create')
    {
        $this->dispatch('openRoleModal', $roleId, $mode);
    }
}