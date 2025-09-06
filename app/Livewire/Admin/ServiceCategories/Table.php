<?php

namespace App\Livewire\Admin\ServiceCategories;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;
use Livewire\Attributes\On;
class Table extends Component
{
     use WithPagination;

    public $search = '';
    public $perPage = 10;  
    public $status = '';   // active/inactive
    public $fromDate = '';
    public $toDate = ''; 
    public $showFilterModal = false;
    protected $listeners = [
        'categoryUpdated' => '$refresh', // refresh table when category is saved
    ];
    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }
    public function updatingFromDate() { $this->resetPage(); }
    public function updatingToDate() { $this->resetPage(); } 
     

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
    public function render()
    {
        
        $categories = Service::query()
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
            ->with(['providers' => fn($q) => $q->select('users.id','users.name','users.avatar')->limit(1)])
            ->latest()
            ->paginate($this->perPage); 

        return view('livewire.admin.service-categories.table', compact('categories'));
    } 
}
