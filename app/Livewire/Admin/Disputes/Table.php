<?php

namespace App\Livewire\Admin\Disputes;

use App\Models\Dispute;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Table extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $status = '';
    public $fromDate = '';
    public $toDate = '';

    public $tempStatus = '';
    public $tempFromDate = '';
    public $tempToDate = '';

    public $showFilterModal = false;
    public $activeFilters = [];

    protected $listeners = [
        'searchUpdated-disputes' => 'updatingSearch',
        'openFilterModal-disputes' => 'openFilterModal',
        'removeFilter-disputes' => 'removeFilter',
        'exportCsvRequested-disputes' => 'exportCsv',
    ];

    public function updatingSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
        $this->activeFilters = $this->getActiveFilters();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openFilterModal()
    {
        $this->tempStatus = $this->status;
        $this->tempFromDate = $this->fromDate;
        $this->tempToDate = $this->toDate;
        $this->showFilterModal = true;
    }

    public function closeFilterModal()
    {
        $this->showFilterModal = false;
    }

    public function applyFilters()
    {
        $this->status = $this->tempStatus;
        $this->fromDate = $this->tempFromDate;
        $this->toDate = $this->tempToDate;
        $this->resetPage();
        $this->closeFilterModal();
        $this->activeFilters = $this->getActiveFilters();
    }

    public function resetFilters()
    {
        $this->reset(['status', 'fromDate', 'toDate', 'tempStatus', 'tempFromDate', 'tempToDate']);
        $this->resetPage();
        $this->closeFilterModal();
        $this->activeFilters = $this->getActiveFilters();
    }

    public function removeFilter($key)
    {
        if (is_array($key) && isset($key['key'])) {
            $key = $key['key'];
        }

        if ($key === 'date') {
            $this->fromDate = '';
            $this->toDate = '';
        } elseif ($key === 'status') {
            $this->status = '';
        } elseif ($key === 'search') {
            $this->search = '';
            $this->dispatch('search-reset-disputes'); // Optional: for frontend cleanup
        }
        $this->resetPage();
        $this->activeFilters = $this->getActiveFilters();
    }

    public function getActiveFilters()
    {
        $filters = [];
        if ($this->search) {
            $filters[] = [
                'type' => 'search',
                'label' => 'Search: ' . $this->search,
                'key' => 'search'
            ];
        }
        if ($this->fromDate && $this->toDate) {
            $filters[] = [
                'type' => 'date',
                'label' => date('d M, Y', strtotime($this->fromDate)) . ' - ' . date('d M, Y', strtotime($this->toDate)),
                'key' => 'date'
            ];
        }
        if ($this->status) {
            $statusLabels = [
                'resolved' => 'Resolved',
                'unresolved' => 'Unresolved',
            ];
            $filters[] = [
                'type' => 'status',
                'label' => ($statusLabels[$this->status] ?? ucfirst($this->status)),
                'key' => 'status'
            ];
        }
        return $filters;
    }

    public function setStatus($disputeId, $status)
    {
        if (!auth()->user()->can('Write Disputes')) {
            $this->dispatch('showSweetAlert', 'error', 'Unauthorized action.', 'Error');
            return;
        }
        $dispute = Dispute::findOrFail($disputeId);
        $dispute->update(['status' => strtolower($status)]);
        $this->dispatch('showSweetAlert', 'success', 'Status updated successfully.', 'Success');
    }

    public function getDataQuery()
    {
        return Dispute::query()
            ->with(['user', 'booking.service'])
            ->when($this->search, function ($q) {
                $q->where('message', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($sq) {
                        $sq->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('booking', function ($sq) {
                        $sq->where('booking_ref', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->status, function ($q) {
                $q->where('status', strtolower($this->status));
            })
            ->when($this->fromDate && $this->toDate, function ($q) {
                $q->whereBetween('created_at', [$this->fromDate . ' 00:00:00', $this->toDate . ' 23:59:59']);
            })
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function render()
    {
        $disputes = $this->getDataQuery()->paginate($this->perPage);
        
        $stats = [
            'total' => Dispute::count(),
            'resolved' => Dispute::where('status', 'resolved')->count(),
            'unresolved' => Dispute::where('status', 'unresolved')->count(),
        ];

        return view('livewire.admin.disputes.table', [
            'disputes' => $disputes,
            'stats' => $stats,
        ]);
    }

    public function exportCsv()
    {
        $fileName = 'disputes.csv';
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Booking ID', 'User', 'Message', 'Status', 'Date']);

            $data = $this->getDataQuery()->get();
            foreach ($data as $item) {
                fputcsv($handle, [
                    $item->id,
                    $item->booking->booking_ref ?? 'N/A',
                    $item->user->name ?? 'N/A',
                    $item->message,
                    ucfirst($item->status),
                    $item->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
