<?php

namespace App\Livewire\Admin\ServiceCategories;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;
use League\Uri\BaseUri;
use Livewire\Attributes\On;
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
    ];
 
    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingFromDate() { $this->resetPage(); }
    public function updatingToDate() { $this->resetPage(); } 

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

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
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
        Service::findOrFail($id)->delete();
         $this->confirmingId = null;
        $this->dispatch('showToastr', 'success', 'Service category deleted successfully.', 'Success');
        
    }

    public function edit($id)
    {
        $this->dispatch('openModal', id: $id); // open modal with category
    }
    
    public function updatedPerPage($value)
    {
          
        $this->perPage = (int) $value; // force integer
        $this->resetPage(); // reset to first page when rows change
    }
    
    public function openFilterModal()
    {
        $this->showFilterModal = true;
    }

    public function closeFilterModal()
    {
        $this->showFilterModal = false;
    }

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

    private function getCategoriesQuery()
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
        $categories = $this->getCategoriesQuery()
        ->latest()
        ->paginate($this->perPage);
         
        
        return view('livewire.admin.service-categories.table', compact('categories'));
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
            $services = $this->getCategoriesQuery()->get();

            foreach ($services as $service) {
                fputcsv($handle, [
                    $service->id,
                    $service->name,
                    $service->description,
                    $service->status,
                    $service->providers_count,
                    env('APP_URL').'/'.$service->icon,
                    $service->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
