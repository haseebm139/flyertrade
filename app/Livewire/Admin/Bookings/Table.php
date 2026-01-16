<?php

namespace App\Livewire\Admin\Bookings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;
use Symfony\Component\HttpFoundation\StreamedResponse;
class Table extends Component
{

    use WithPagination;
     
    public $confirmingId = null;
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
    public $serviceFilter = '';
    public $customerFilter = '';
    public $providerFilter = ''; 
    protected $listeners = [
        'categoryUpdated' => '$refresh',  
        'exportCsvRequested-all-bookings' => 'exportCsv',
        'openFilterModal-all-bookings'    => 'openFilterModal',
        'searchUpdated-all-bookings'      => 'updatingSearch',
        'filterByStatus'     => 'filterByStatus',
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
    public function testToastr()
    {
        $this->dispatch('toastrNotification', [
        'type' => 'success',
        'message' => 'Direct event — no middleman!',
        'title' => 'Success'
    ]);
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
    public function filterByStatus($status = null)
    {
        if (is_array($status) && isset($status['status'])) {
            $this->status = $status['status'];
        } else {
            $this->status = $status;
        }
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['status', 'fromDate', 'toDate', 'serviceFilter', 'customerFilter', 'providerFilter']);
        
    }
    public function resetFilters()
    {
        $this->reset(['status', 'fromDate', 'toDate', 'serviceFilter', 'customerFilter', 'providerFilter']);
        $this->resetPage();
    }


    # -------------------- QUERY --------------------
    private function getDataQuery()
    {
        return Booking::query()
            ->when($this->search, fn($q) => $q->where('booking_ref', 'like', "%{$this->search}%")
                ->orWhere('booking_address', 'like', "%{$this->search}%")
                ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->orWhereHas('provider', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            )
            ->when($this->fromDate && $this->toDate, fn($q) =>
                $q->whereBetween('created_at', [
                    $this->fromDate.' 00:00:00',
                    $this->toDate.' 23:59:59'
                ])
            )
            ->when($this->status !== '', fn($q) =>
                $q->where('status', $this->status)
            )
            ->when($this->serviceFilter, fn($q) =>
                $q->whereHas('service', fn($q) => $q->where('name', 'like', "%{$this->serviceFilter}%"))
            )
            ->when($this->customerFilter, fn($q) =>
                $q->whereHas('customer', fn($q) => $q->where('name', 'like', "%{$this->customerFilter}%"))
            )
            ->when($this->providerFilter, fn($q) =>
                $q->whereHas('provider', fn($q) => $q->where('name', 'like', "%{$this->providerFilter}%"))
            )
            ->with(['customer:id,name,avatar,email,phone', 'provider:id,name,avatar,email,phone', 'service:id,name'])
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function render()
    {
        $data = $this->getDataQuery()
            ->paginate($this->perPage);
         
        return view('livewire.admin.bookings.table', compact('data'));
    } 

    public function exportCsv()
    {
        $fileName = 'bookings.csv';
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // CSV Header row
            fputcsv($handle, ['Booking ID', 'Customer', 'Provider', 'Service', 'Address', 'Status', 'Total Price', 'Created At']);

            // Fetch your data
            $data = $this->getDataQuery()->get();
            foreach ($data as $item) {
                fputcsv($handle, [
                    $item->booking_ref ?? '',
                    $item->customer->name ?? '',
                    $item->provider->name ?? '',
                    $item->service->name ?? '',
                    $item->booking_address ?? '',
                    $item->status ?? '',
                    $item->total_price ?? '',
                    $item->created_at?->format('Y-m-d H:i:s') ?? ''
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function confirmDelete($id)
    {
        $this->confirmingId = $id;
    }

    public function delete($id)
    {
        $booking = Booking::find($id);
        if ($booking) {
            $booking->delete();
            $this->dispatch('showToastr', 'success', 'Booking deleted successfully.', 'Success');
        }
        $this->confirmingId = null;
    }
}
