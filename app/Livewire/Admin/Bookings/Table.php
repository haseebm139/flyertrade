<?php

namespace App\Livewire\Admin\Bookings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;
use Symfony\Component\HttpFoundation\StreamedRespons;
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
    ];
     # -------------------- SEARCH + FILTER --------------------
    public function openFilterModal() {  $this->showFilterModal = true;   }

    public function closeFilterModal() { $this->showFilterModal = false;  }

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
            $this->selected = Booking::pluck('id')->toArray();  
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
        Booking::findOrFail($id)->delete();
        $this->confirmingId = null;
        $this->dispatch('showToastr', 'success', 'Booking category deleted successfully.', 'Success');
        
    }

    
    
    public function updatedPerPage($value)
    {
          
        $this->perPage = (int) $value;  
        $this->resetPage();  
    }
    
    # -------------------- FILTER MODAL --------------------
     

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
        return Booking::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->fromDate && $this->toDate, fn($q) =>
                $q->whereBetween('created_at', [
                    $this->fromDate.'00:00:00',
                    $this->toDate.'23:59:59'
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
        // $data = $this->getDataQuery()
        // ->latest()
        // ->paginate($this->perPage);
         
        $data =[];
        return view('livewire.admin.bookings.table', compact('data'));
    } 

    public function exportCsv(): StreamedResponse
    {
        $fileName = 'booking.csv';
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
