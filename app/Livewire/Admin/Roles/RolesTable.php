<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
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
    public $selectAll = false;
    public $showFilterModal = false;
    public $fromDate = '';
    public $toDate = '';
    public $statusFilter = '';
    public $confirmingId = null;

    // Temporary filter values (only used in modal, not applied until Apply button clicked)
    public $tempFromDate = '';
    public $tempToDate = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $listeners = [
        'roleSaved' => 'refreshRoles',
        'refreshRolesTable' => 'refreshRolesTable',
        'roleUpdated' => 'refreshRole',
        'roleDeleted' => 'refreshAfterDelete',
        'openFilterModal-roles' => 'openFilterModal',
        'searchUpdated-roles' => 'updatingSearch',
        'removeFilter-roles' => 'removeFilter',
        'exportCsvRequested-roles' => 'exportCsv',
        'addItemRequested-roles' => 'addRole',
    ];

    public function mount()
    {
        // Ensure sortField is always valid for roles table
        $validFields = ['name', 'created_at', 'updated_at', 'users_count'];
        if (!in_array($this->sortField, $validFields)) {
            $this->sortField = 'name';
        }
    }

    # -------------------- SEARCH + FILTER --------------------
    public function updatingSearch($value) {  
        $this->search = $value;
        $this->resetPage(); 
    }

    public function openFilterModal()
    {
        $this->tempFromDate = $this->fromDate;
        $this->tempToDate = $this->toDate;
        $this->showFilterModal = true;
    }

    public function closeFilterModal()
    {
        $this->showFilterModal = false;
    }

    public function resetFilters()
    {
        $this->tempFromDate = '';
        $this->tempToDate = '';
        $this->fromDate = '';
        $this->toDate = '';
        
        $this->resetPage();
        $this->closeFilterModal();
        $this->dispatch('filtersUpdated', $this->getActiveFilters());
    }

    public function applyFilters()
    {
        $this->fromDate = $this->tempFromDate;
        $this->toDate = $this->tempToDate;
        
        $this->resetPage();
        $this->closeFilterModal();
        $this->dispatch('filtersUpdated', $this->getActiveFilters());
    }

    public function removeFilter($key = null)
    {
        if (is_array($key) && isset($key['key'])) {
            $key = $key['key'];
        }
        
        if ($key === 'date') {
            $this->fromDate = '';
            $this->toDate = '';
        }
        
        $this->resetPage();
        $this->dispatch('filtersUpdated', $this->getActiveFilters());
    }

    public function getActiveFilters()
    {
        $filters = [];
        
        if ($this->fromDate && $this->toDate) {
            $filters[] = [
                'type' => 'date',
                'label' => date('d M, Y', strtotime($this->fromDate)) . ' - ' . date('d M, Y', strtotime($this->toDate)),
                'key' => 'date'
            ];
        }
        
        return $filters;
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $roles = $this->getDataQuery()->paginate($this->perPage);
        $activeFilters = $this->getActiveFilters();
        
        return view('livewire.admin.roles.roles-table', [
            'roles' => $roles,
            'activeFilters' => $activeFilters
        ]);
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
                $query->limit(1);
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
        $this->resetPage();
    }

    public function exportCsv()
    {
        $roles = $this->getDataQuery()->get();
        
        $filename = 'roles_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['Role Name', 'Users Count', 'Created At', 'Updated At']);
            
            // Fetch fresh data for callback
            $roles = $this->getDataQuery()->get();

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

    public function refreshTable()
    {
        $this->resetPage();
    }

    #[On('roleSaved')]
    public function refreshRoles()
    {
        $this->refreshTable();
    }

    #[On('refreshRolesTable')]
    public function refreshRolesTable()
    {
        $this->refreshTable();
    }

    #[On('roleUpdated')]
    public function refreshRole($roleId)
    {
        $this->refreshTable();
    }

    #[On('roleDeleted')]
    public function refreshAfterDelete()
    {
        $this->refreshTable();
    }

    public function confirmDelete($roleId)
    {
        $this->confirmingId = $roleId;
    }

    public function deleteRole($roleId)
    {
        try {
            $role = Role::findOrFail($roleId);
            
            // Check if role has users
            if ($role->users()->count() > 0) {
                $this->dispatch('showSweetAlert', 'error', 'Cannot delete role. It has assigned users.', 'Error');
                $this->confirmingId = null;
                return;
            }
            
            $role->delete();
            $this->dispatch('showSweetAlert', 'success', 'Role deleted successfully.', 'Success');
            $this->dispatch('roleDeleted');
            $this->confirmingId = null;
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', 'error', 'Error deleting role: ' . $e->getMessage(), 'Error');
        }
    }

    public function addItemRequested()
    {
        if (method_exists($this, 'addRole')) {
            $this->addRole();
        } else {
            $this->dispatch('addItemRequested');
        }
    }

    public function viewRole($roleId)
    {
        return redirect()->route('roles-and-permissions.roles.show', ['id' => $roleId]);
    }

    public function editRole($roleId)
    {
        $this->dispatch('openRoleModal', $roleId, 'edit');
    }

    public function addRole()
    {
        $this->dispatch('openRoleModal', null, 'create');
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getDataQuery()->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        $this->selectAll = false;
    }
}
