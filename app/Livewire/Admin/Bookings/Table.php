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
    public $showBookingModal = false;
    public $selectedBooking = null;

    // Temporary filter values
    public $tempStatus = '';
    public $tempFromDate = '';
    public $tempToDate = '';
    public $tempServiceFilter = '';
    public $tempCustomerFilter = '';
    public $tempProviderFilter = '';

    public $serviceFilter = '';
    public $customerFilter = '';
    public $providerFilter = '';
    protected $listeners = [
        'categoryUpdated' => '$refresh',
        'exportCsvRequested-all-bookings' => 'exportCsv',
        'openFilterModal-all-bookings'    => 'openFilterModal',
        'searchUpdated-all-bookings'      => 'updatingSearch',
        'removeFilter-all-bookings'       => 'removeFilter',
        'filterByStatus'                  => 'filterByStatus',
    ];
    # -------------------- SEARCH + FILTER --------------------
    public function openFilterModal()
    {
        $this->tempStatus = $this->status;
        $this->tempFromDate = $this->fromDate;
        $this->tempToDate = $this->toDate;
        $this->tempServiceFilter = $this->serviceFilter;
        $this->tempCustomerFilter = $this->customerFilter;
        $this->tempProviderFilter = $this->providerFilter;
        $this->showFilterModal = true;
    }

    public function closeFilterModal()
    {
        $this->showFilterModal = false;
    }

    public function updatingSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function applyFilters()
    {
        $this->status = $this->tempStatus;
        $this->fromDate = $this->tempFromDate;
        $this->toDate = $this->tempToDate;
        $this->serviceFilter = $this->tempServiceFilter;
        $this->customerFilter = $this->tempCustomerFilter;
        $this->providerFilter = $this->tempProviderFilter;

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
            $this->status = '';
        } elseif ($key === 'service') {
            $this->serviceFilter = '';
        } elseif ($key === 'customer') {
            $this->customerFilter = '';
        } elseif ($key === 'provider') {
            $this->providerFilter = '';
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

        if ($this->status) {
            $statusLabels = [
                'awaiting_provider' => 'Pending',
                'confirmed' => 'Active',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
                'rejected' => 'Rejected',
                'reschedule_pending_customer' => 'Reschedule Pending',
            ];
            $filters[] = [
                'type' => 'status',
                'label' => ($statusLabels[$this->status] ?? ucfirst($this->status)) . ' bookings',
                'key' => 'status'
            ];
        }

        if ($this->serviceFilter) {
            $filters[] = [
                'type' => 'service',
                'label' => 'Service: ' . $this->serviceFilter,
                'key' => 'service'
            ];
        }

        if ($this->customerFilter) {
            $filters[] = [
                'type' => 'customer',
                'label' => 'User: ' . $this->customerFilter,
                'key' => 'customer'
            ];
        }

        if ($this->providerFilter) {
            $filters[] = [
                'type' => 'provider',
                'label' => 'Provider: ' . $this->providerFilter,
                'key' => 'provider'
            ];
        }

        return $filters;
    }

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
    public function filterByStatus($status = null)
    {
        if (is_array($status) && isset($status['status'])) {
            $this->status = $status['status'];
        } else {
            $this->status = $status;
        }
        $this->resetPage();
        $this->dispatch('filtersUpdated', $this->getActiveFilters());
    }

    public function clearFilters()
    {
        $this->reset(['status', 'fromDate', 'toDate', 'serviceFilter', 'customerFilter', 'providerFilter']);
        $this->dispatch('filtersUpdated', $this->getActiveFilters());
    }

    public function resetFilters()
    {
        $this->reset(['status', 'fromDate', 'toDate', 'serviceFilter', 'customerFilter', 'providerFilter', 'tempStatus', 'tempFromDate', 'tempToDate', 'tempServiceFilter', 'tempCustomerFilter', 'tempProviderFilter']);
        $this->resetPage();
        $this->closeFilterModal();
        $this->dispatch('filtersUpdated', $this->getActiveFilters());
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
        $activeFilters = $this->getActiveFilters();

        return view('livewire.admin.bookings.table', compact('data', 'activeFilters'));
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
        if (!auth()->user()->can('Delete Bookings')) {
            $this->dispatch('showSweetAlert', 'error', 'Unauthorized action.', 'Error');
            return;
        }
        try {
            $booking = Booking::find($id);
            if ($booking) {
                $booking->delete();
                $this->dispatch('showSweetAlert', 'success', 'Booking deleted successfully.', 'Success');
            }
            $this->confirmingId = null;
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', 'error', 'Error deleting booking: ' . $e->getMessage(), 'Error');
        }
    }

    public function viewBooking($id)
    {
        if (!auth()->user()->can('Read Bookings')) {
            $this->dispatch('showSweetAlert', 'error', 'Unauthorized access.', 'Error');
            return;
        }
        $this->selectedBooking = Booking::with(['customer', 'provider', 'service'])->find($id);
        if ($this->selectedBooking) {
            $this->showBookingModal = true;
        }
    }

    public function closeBookingModal()
    {
        $this->showBookingModal = false;
        $this->selectedBooking = null;
    }

    public function downloadBookingDetails($id)
    {
        if (!auth()->user()->can('Read Bookings')) {
            $this->dispatch('showSweetAlert', 'error', 'Unauthorized action.', 'Error');
            return;
        }
        $booking = Booking::with(['customer', 'provider', 'service'])->find($id);
        if (!$booking) {
            $this->dispatch('showSweetAlert', 'error', 'Booking not found.', 'Error');
            return;
        }

        $fileName = "booking-{$booking->booking_ref}.csv";
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () use ($booking) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Field', 'Details']);
            fputcsv($handle, ['Booking ID', $booking->booking_ref]);
            fputcsv($handle, ['Date', $booking->created_at->format('d M, Y')]);
            fputcsv($handle, ['Time', $booking->created_at->format('h:i A')]);
            fputcsv($handle, ['Duration', $booking->working_hours . ' Hours']);
            fputcsv($handle, ['Location', $booking->booking_address ?? '-']);
            fputcsv($handle, ['Service Type', $booking->service->name ?? '-']);
            fputcsv($handle, ['Service Cost', '$' . number_format($booking->total_price, 2)]);
            fputcsv($handle, ['Status', ucfirst(str_replace('_', ' ', $booking->status))]);
            fputcsv($handle, ['Service Provider', $booking->provider->name ?? '-']);
            fputcsv($handle, ['Service User', $booking->customer->name ?? '-']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
