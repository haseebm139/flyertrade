<?php

namespace App\Livewire\Admin\ServiceCategories;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service; 
use Symfony\Component\HttpFoundation\StreamedResponse;
class Table extends Component
{
    use WithPagination;
     
    public $confirmingId ;
    public $search = '';
    public $perPage = 10;  
    public $status = '';    
    public $fromDate = '';
    public $toDate = ''; 

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
        'removeFilter'       => 'removeFilter',
    ];

     
    # -------------------- SEARCH + FILTER --------------------
    public function updatingSearch($value) {  
        $this->search = $value;
        $this->resetPage(); 
    }
    // Remove individual updating handlers to only apply on "Apply Now"
    // public function updatingStatus() { $this->resetPage(); }
    // public function updatingFromDate() { $this->resetPage(); }
    // public function updatingToDate() { $this->resetPage(); }  

    # -------------------- SELECT ALL --------------------
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = Service::pluck('id')->toArray();  
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
            $service = Service::find($id);
            if ($service) {
                $service->delete();
                $this->dispatch('showSweetAlert', 'success', 'Service category deleted successfully.', 'Success');
            } else {
                $this->dispatch('showSweetAlert', 'error', 'Service category not found.', 'Error');
            }
            $this->dispatch('categoryUpdated'); // Refresh the table
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', 'error', 'Error deleting service category: ' . $e->getMessage(), 'Error');
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
                'label' => ($statusLabels[$this->status] ?? ucfirst($this->status)) . ' categories',
                'key' => 'status'
            ];
        }
        
        return $filters;
    }


    # -------------------- QUERY --------------------
    private function getDataQuery()
    {
        return Service::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->fromDate && $this->toDate, fn($q) =>
                $q->whereBetween('created_at', [
                    $this->fromDate . ' 00:00:00',
                    $this->toDate . ' 23:59:59'
                ])
            )
             
            ->when($this->status !== '', fn($q) =>
                $q->where('status', $this->status)
            )
            ->withCount('providers')
            ->orderBy($this->sortField, $this->sortDirection)
            ->with(['providers' => fn($q) => $q->select('users.id','users.name','users.avatar')->limit(1)]);
            
    }

    public function render()
    {
        $data = $this->getDataQuery()
        ->latest()
        ->paginate($this->perPage);
         
        $activeFilters = $this->getActiveFilters();
        
        // Dispatch event to update toolbar with current active filters
        $this->dispatch('filtersUpdated', $activeFilters);
        
        return view('livewire.admin.service-categories.table', [
            'data' => $data,
            'activeFilters' => $activeFilters,
        ]);
    } 

    public function exportCsv(): StreamedResponse
    {
        $fileName = 'services.csv';
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // CSV Header row
            fputcsv($handle, ['ID', 'Name', 'Description', 'Status', 'Providers Count','ImageUrl', 'Created At']);

            // Fetch your data
            $data = $this->getDataQuery()->get();
            
            foreach ($data as $item) {
                fputcsv($handle, [
                    $item->id??'',
                    $item->name??'',
                    $item->description??'',
                    $item->status??'',
                    $item->providers_count??'',
                    env('APP_URL').'/'.$item->icon??'',
                    $item->created_at?->format('Y-m-d H:i:s') ?? ''
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
