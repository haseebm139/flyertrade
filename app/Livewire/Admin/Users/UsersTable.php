<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
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
    public $selectAll = false;
    public $showFilterModal = false;
    public $fromDate = '';
    public $toDate = '';
    public $statusFilter = '';
    public $roleFilter = '';
    public $confirmingId = null;
    
    // Temporary filter values (only used in modal, not applied until Apply button clicked)
    public $tempStatus = '';
    public $tempFromDate = '';
    public $tempToDate = '';
    public $tempRole = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $listeners = [
        'userSaved' => 'refreshUsers',
        'refreshUsersTable' => 'refreshUsersTable',
        'userUpdated' => 'refreshUser',
        'userDeleted' => 'refreshAfterDelete',
        'openFilterModal-users' => 'openFilterModal',
        'searchUpdated-users' => 'updatingSearch',
        'removeFilter-users' => 'removeFilter',
        'exportCsvRequested-users' => 'exportCsv',
        'addItemRequested-users' => 'addUser',
    ];

    public function mount()
    {
        // Ensure sortField is always valid for users table
        $validFields = ['name', 'email', 'user_type', 'address', 'phone', 'created_at', 'updated_at'];
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
        $this->tempStatus = $this->statusFilter;
        $this->tempFromDate = $this->fromDate;
        $this->tempToDate = $this->toDate;
        $this->tempRole = $this->roleFilter;
        $this->showFilterModal = true;
    }

    public function closeFilterModal()
    {
        $this->showFilterModal = false;
    }

    public function resetFilters()
    {
        $this->tempStatus = '';
        $this->tempFromDate = '';
        $this->tempToDate = '';
        $this->tempRole = '';
        
        $this->statusFilter = '';
        $this->fromDate = '';
        $this->toDate = '';
        $this->roleFilter = '';
        
        $this->resetPage();
        $this->closeFilterModal();
        $this->dispatch('filtersUpdated', $this->getActiveFilters());
    }

    public function applyFilters()
    {
        $this->statusFilter = $this->tempStatus;
        $this->fromDate = $this->tempFromDate;
        $this->toDate = $this->tempToDate;
        $this->roleFilter = $this->tempRole;
        
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
        } elseif ($key === 'status') {
            $this->statusFilter = '';
        } elseif ($key === 'role') {
            $this->roleFilter = '';
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
        
        if ($this->statusFilter) {
            $filters[] = [
                'type' => 'status',
                'label' => ucfirst($this->statusFilter) . ' users',
                'key' => 'status'
            ];
        }

        if ($this->roleFilter) {
            $filters[] = [
                'type' => 'role',
                'label' => 'Role: ' . ucfirst($this->roleFilter),
                'key' => 'role'
            ];
        }
        
        return $filters;
    }

    public function refreshTable()
    {
        $this->resetPage();
    }

    #[On('userSaved')]
    public function refreshUsers()
    {
        $this->refreshTable();
    }

    #[On('refreshUsersTable')]
    public function refreshUsersTable()
    {
        $this->refreshTable();
    }

    #[On('userUpdated')]
    public function refreshUser($userId)
    {
        $this->refreshTable();
    }

    #[On('userDeleted')]
    public function refreshAfterDelete()
    {
        $this->refreshTable();
    }

    public function render()
    {
        $users = $this->getDataQuery()->paginate($this->perPage);
        $roles = Role::all();
        $activeFilters = $this->getActiveFilters();
        
        return view('livewire.admin.users.users-table', [
            'users' => $users,
            'roles' => $roles,
            'activeFilters' => $activeFilters
        ]);
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

    public function exportCsv()
    {
        $users = $this->getDataQuery()->get();
        
        $filename = 'users_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['Name', 'Email', 'User Type', 'Roles', 'Phone', 'Address', 'Created At']);
            
            // Fetch fresh data for the callback
            $users = $this->getDataQuery()->get();

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

    public function confirmDelete($userId)
    {
        $this->confirmingId = $userId;
    }

    public function deleteUser($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->delete();
            $this->confirmingId = null;
            $this->dispatch('showSweetAlert', 'success', 'User deleted successfully.', 'Success');
            $this->dispatch('userDeleted');
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', 'error', 'Error deleting user: ' . $e->getMessage(), 'Error');
        }
    }

    public function addItemRequested()
    {
        if (method_exists($this, 'addUser')) {
            $this->addUser();
        } elseif (method_exists($this, 'addRole')) {
            $this->addRole();
        } else {
            // Default behavior for other tables (like Service Categories)
            $this->dispatch('addItemRequested');
        }
    }

    public function viewUser($userId)
    {
        return redirect()->route('roles-and-permissions.users.show', ['id' => $userId]);
    }

    public function editUser($userId)
    {
        $this->dispatch('openUserModal', $userId, 'edit');
    }

    public function addUser()
    {
        $this->dispatch('openUserModal', null, 'create');
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
