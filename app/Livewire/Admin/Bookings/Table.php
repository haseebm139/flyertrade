<?php

namespace App\Livewire\Admin\Bookings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Booking;
class Table extends Component
{

    use WithPagination;

    public $filterStatus = null; // null = all, active, inactive
    public $search = '';
    public $perPage = 10;
    public $pageTitle = 'All Booking';


    public function filterByStatus($status)
    {
        dd($status);
        $this->filterStatus = $status;
        $this->pageTitle = $status === 'active' ? 'Active Booking'
                          : ($status === 'inactive' ? 'Inactive Booking'
                          : 'All Booking');
        $this->resetPage(); // Reset pagination when filtering
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        $query = Booking::query()
            ->with(['user', 'provider', 'service'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->search, fn($q) =>
                $q->whereHas('user', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"))
                  ->orWhereHas('provider', fn($q2) => $q2->where('name', 'like', "%{$this->search}%"))
            );

        $bookings = $query->latest()->paginate($this->perPage);
        return view('livewire.admin.bookings.table', compact('bookings'));
    }
}
