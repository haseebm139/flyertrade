<?php

namespace App\Livewire\Admin\Reviews;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReviewsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $activeTab = 'users'; // 'users' or 'providers'
    public $selected = [];
    public $selectAll = false;
    
    public $showFilterModal = false;
    public $fromDate = '';
    public $toDate = '';
    public $statusFilter = '';
    
    // Temporary filter values
    public $tempFromDate = '';
    public $tempToDate = '';
    public $tempStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'activeTab' => ['except' => 'users'],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $listeners = [
        'filtersUpdated' => 'updateFilters',
        'searchUpdated-reviews' => 'updatingSearch',
        'removeFilter-reviews' => 'removeFilter',
        'exportCsvRequested-reviews' => 'exportCsv',
    ];

    public function updatingSearch($value)
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->selected = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function openFilterModal()
    {
        $this->tempFromDate = $this->fromDate;
        $this->tempToDate = $this->toDate;
        $this->tempStatus = $this->statusFilter;
        $this->showFilterModal = true;
    }

    public function closeFilterModal()
    {
        $this->showFilterModal = false;
    }

    public function applyFilters()
    {
        $this->fromDate = $this->tempFromDate;
        $this->toDate = $this->tempToDate;
        $this->statusFilter = $this->tempStatus;
        $this->resetPage();
        $this->closeFilterModal();
        $this->dispatch('filtersUpdated', $this->getActiveFilters());
    }

    public function resetFilters()
    {
        $this->fromDate = '';
        $this->toDate = '';
        $this->statusFilter = '';
        $this->tempFromDate = '';
        $this->tempToDate = '';
        $this->tempStatus = '';
        $this->resetPage();
        $this->closeFilterModal();
        $this->dispatch('filtersUpdated', $this->getActiveFilters());
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
            $this->statusFilter = '';
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
                'label' => ucfirst($this->statusFilter),
                'key' => 'status'
            ];
        }
        return $filters;
    }

    public function setStatus($reviewId, $status)
    {
        if (!auth()->user()->can('Write Reviews')) {
            $this->dispatch('showSweetAlert', type: 'error', message: 'Unauthorized action');
            return;
        }
        $review = Review::findOrFail($reviewId);
        $review->update(['status' => strtolower($status)]);
        $this->dispatch('showSweetAlert', type: 'success', message: 'Status updated to ' . $status);
    }

    public function delete($reviewId)
    {
        if (!auth()->user()->can('Delete Reviews')) {
            $this->dispatch('showSweetAlert', type: 'error', message: 'Unauthorized action');
            return;
        }
        Review::findOrFail($reviewId)->delete();
        $this->dispatch('showSweetAlert', type: 'success', message: 'Review deleted successfully');
    }

    public function getDataQuery()
    {
        $query = Review::query()
            ->with(['reviewer', 'reviewedProvider', 'booking', 'service']);

        if ($this->activeTab === 'users') {
            // Reviews written BY customers ABOUT providers
            // sender_id should be customer
            $query->whereHas('reviewer', function($q) {
                $q->whereIn('user_type', ['customer', 'multi']);
            });
        } else {
            // Reviews written BY providers ABOUT customers
            // sender_id should be provider
            $query->whereHas('reviewer', function($q) {
                $q->whereIn('user_type', ['provider', 'multi']);
            });
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('review', 'like', '%' . $this->search . '%')
                  ->orWhereHas('reviewer', function($sq) {
                      $sq->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('reviewedProvider', function($sq) {
                      $sq->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->fromDate && $this->toDate) {
            $query->whereBetween('created_at', [$this->fromDate . ' 00:00:00', $this->toDate . ' 23:59:59']);
        }

        if ($this->statusFilter) {
            $query->where('status', strtolower($this->statusFilter));
        }

        return $query->orderBy($this->sortField, $this->sortDirection);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->getDataQuery()
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        $this->selectAll = false;
    }

    public function exportCsv()
    {
        $reviews = $this->getDataQuery()->get();
        $filename = 'reviews_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reviews) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Booking ID', 'Booking Ref', 'Date', 'Reviewer', 'Reviewed', 'Rating', 'Review', 'Status']);

            foreach ($reviews as $review) {
                fputcsv($file, [
                    $review->id,
                    $review->booking_id,
                    $review->booking->booking_ref ?? 'N/A',
                    $review->created_at->format('d M, Y'),
                    $review->reviewer->name ?? 'N/A',
                    $review->reviewedProvider->name ?? 'N/A',
                    $review->rating,
                    $review->review,
                    ucfirst($review->status)
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('livewire.admin.reviews.reviews-table', [
            'reviews' => $this->getDataQuery()->paginate($this->perPage),
            'activeFilters' => $this->getActiveFilters()
        ]);
    }
}
