<?php

namespace App\Livewire\Admin\UserManagement\Provider;

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
    public $status = '';    
    public $fromDate = '';
    public $toDate = ''; 
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
    ];

    # -------------------- SEARCH + FILTER --------------------
    public function updatingSearch($value) {  
        $this->search = $value;
        $this->resetPage(); 
    }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingFromDate() { $this->resetPage(); }
    public function updatingToDate() { $this->resetPage(); }  


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
        User::findOrFail($id)->delete();
         $this->confirmingId = null;
        $this->dispatch('showToastr', 'success', 'Service category deleted successfully.', 'Success');
        
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
    public function openFilterModal() {  $this->showFilterModal = true;   }

    public function closeFilterModal() { $this->showFilterModal = false;  }

    public function applyFilters()
    {
    
        $this->resetPage();
        $this->closeFilterModal();
    }
    public function clearFilters()
    {
        $this->reset(['status', 'fromDate', 'toDate']);
        
    }
    public function resetFilters()
    {
        $this->reset(['status', 'fromDate', 'toDate']);
        $this->resetPage();
    }
    

    # -------------------- QUERY --------------------
    private function getDataQuery()
    {
        return User::query()
            ->where('user_type', 'provider')
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
            ->with(['providerServices.provider'])
            ->withCount('providerServices')
            ->orderBy($this->sortField, $this->sortDirection);
            
    }
    public function render()
    {
        $data = $this->getDataQuery()->paginate($this->perPage);
         
        return view('livewire.admin.user-management.provider.table',compact('data'));
    }  

    # -------------------- Export -------------------- 
     
    public function exportCsv(): StreamedResponse
    {
        $fileName = 'service_providers.csv';
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // CSV Header row
            fputcsv($handle, ['USER ID', 'PROVIDER Name','PROVIDER Email', 'Home Address', 'Phone Number','Verification Status','Status', 'Created At']);

            // Fetch your data
            $data = $this->getDataQuery()->get();

            foreach ($data as $item) {
                fputcsv($handle, [
                    $item->id,
                    $item->name, 
                    $item->email,
                    $item->address,
                    $item->phone,
                    $item->is_verified,
                    $item->status,                   
                    $item->created_at?->format('Y-m-d H:i:s') ?? ''Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
    
}
