<?php

namespace App\Livewire\Admin\UserManagement\User;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination; 
use Symfony\Component\HttpFoundation\StreamedResponse;
class Table extends Component
{
    use WithPagination;
     
    public $confirmingId ;
    public $search = '';
    public $perPage = 10;  
    public $status = '';    // Applied filter status
    public $fromDate = '';  // Applied filter fromDate
    public $toDate = '';    // Applied filter toDate
    
    // Temporary filter values (only used in modal, not applied until Apply button clicked)
    public $tempStatus = '';
    public $tempFromDate = '';
    public $tempToDate = '';
    
    public $sortField = 'created_at';  
    public $sortDirection = 'desc';
    public $selected = [];  
    public $selectAll = false;    
    public $showFilterModal = false; 

    protected $listeners = [
        'categoryUpdated' => '$refresh',  
        'exportCsvRequested' => 'exportCsv',
        'openFilterModal'    => 'openFilterModal',
        'searchUpdated'      => 'updatingSearch',
        'addItemRequested'   => 'openAddModal',
        'removeFilter'       => 'removeFilter',
    ];

    # -------------------- SEARCH + FILTER --------------------
    public function updatingSearch($value) {  
        $this->search = $value;
        $this->resetPage(); 
    }
    // Remove these - filters should only apply on Apply button click
    // public function updatingStatus() { $this->resetPage(); }
    // public function updatingFromDate() { $this->resetPage(); }
    // public function updatingToDate() { $this->resetPage(); }  


    # -------------------- SELECT ALL --------------------
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = User::pluck('id')->toArray();  
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        $this->selectAll = false;  
    }

    # -------------------- SORTING --------------------
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }


    # -------------------- CRUD --------------------
    public function confirmDelete($id)
    {
          $this->confirmingId = $id;
    }
    public function testToastr()
    {
        $this->dispatch('toastrNotification', [
        'type' => 'success',
        'message' => 'Direct event — no middleman!',
        'title' => 'Success'
    ]);
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            $this->confirmingId = null;
            $this->dispatch('showSweetAlert', 'success', 'Service User deleted successfully.', 'Success');
            $this->dispatch('categoryUpdated');
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', 'error', 'Error deleting user: ' . $e->getMessage(), 'Error');
        }
    }

    public function edit($id)
    {
        $this->dispatch('addItemRequested', id: $id); // open modal with category
    }
    
    public function updatedPerPage($value)
    {
          
        $this->perPage = (int) $value;  
        $this->resetPage();  
    }


    # -------------------- FILTER MODAL --------------------
    public function openFilterModal() {  
        // Load current applied filters into temporary variables
        $this->tempStatus = $this->status;
        $this->tempFromDate = $this->fromDate;
        $this->tempToDate = $this->toDate;
        $this->showFilterModal = true;   
    }

    public function closeFilterModal() { 
        $this->showFilterModal = false;  
    }

    public function applyFilters()
    {
        // Apply temporary filters to actual filters
        $this->status = $this->tempStatus;
        $this->fromDate = $this->tempFromDate;
        $this->toDate = $this->tempToDate;
        
        $this->resetPage();
        $this->closeFilterModal();
        
        // Dispatch event to update toolbar with new active filters
        $this->dispatch('filtersUpdated', $this->getActiveFilters());
    }
    
    public function resetFilters()
    {
        // Reset both temporary and applied filters
        $this->tempStatus = '';
        $this->tempFromDate = '';
        $this->tempToDate = '';
        $this->status = '';
        $this->fromDate = '';
        $this->toDate = '';
        $this->resetPage();
        $this->closeFilterModal();
        
        // Dispatch event to update toolbar (filters cleared)
        $this->dispatch('filtersUpdated', $this->getActiveFilters());
    }

    /**
     * Remove a specific filter by key
     */
    public function removeFilter($key = null)
    {
        // Handle array parameter from Livewire event
        if (is_array($key) && isset($key['key'])) {
            $key = $key['key'];
        }
        
        if ($key === 'date') {
            $this->fromDate = '';
            $this->toDate = '';
        } elseif ($key === 'status') {
            $this->status = '';
        }
        
        $this->resetPage();
        
        // Dispatch event to update toolbar with new active filters
        $this->dispatch('filtersUpdated', $this->getActiveFilters());
    }

    /**
     * Get active filters for display in toolbar
     */
    public function getActiveFilters()
    {
        $filters = [];
        
        // Date range filter
        if ($this->fromDate && $this->toDate) {
            $filters[] = [
                'type' => 'date',
                'label' => date('d M, Y', strtotime($this->fromDate)) . ' - ' . date('d M, Y', strtotime($this->toDate)),
                'key' => 'date'
            ];
        }
        
        // Status filter
        if ($this->status) {
            $statusLabels = [
                'active' => 'Active',
                'inactive' => 'Inactive',
            ];
            $filters[] = [
                'type' => 'status',
                'label' => ($statusLabels[$this->status] ?? ucfirst($this->status)) . ' users',
                'key' => 'status'
            ];
        }
        
        return $filters;
    }
    

    # -------------------- QUERY --------------------
    private function getDataQuery()
    {   
         
        return User::query()
            ->where('user_type', 'customer')
            ->when($this->search, fn($q) =>
                $q->where(function($sub) {
                    $sub->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                })
            )->when($this->fromDate && $this->toDate, fn($q) =>
                $q->whereBetween('created_at', [
                    $this->fromDate.'00:00:00',
                    $this->toDate.'23:59:59'
                ])
            )
             
            ->when($this->status !== '', fn($q) =>
                $q->where('status', $this->status)
            ) 
            ->orderBy($this->sortField, $this->sortDirection);
            
    }
    public function render()
    {
        $data = $this->getDataQuery()->paginate($this->perPage);
        $activeFilters = $this->getActiveFilters();
        
        // Dispatch event to update toolbar with current active filters
        $this->dispatch('filtersUpdated', $activeFilters);
         
        return view('livewire.admin.user-management.user.table', [
            'data' => $data,
            'activeFilters' => $activeFilters,
        ]);
    }  

    # -------------------- Export -------------------- 
     
    public function exportCsv(): StreamedResponse
    {
        $fileName = 'service_users.csv';
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // CSV Header row
            fputcsv($handle, ['CUSTOMER ID', 'CUSTOMER Name','Customer Email', 'Home Address', 'Phone Number','Status', 'Created At']);

            // Fetch your data
            $data = $this->getDataQuery()->get();

            foreach ($data as $item) {
                fputcsv($handle, [
                    $item->id,
                    $item->name, 
                    $item->email,
                    $item->address,
                    $item->phone,
                    $item->status,                   
                    $item->created_at?->format('Y-m-d H:i:s') ?? ('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        
        return response()->stream($callback, 200, $headers);
    }
}
