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
    protected $listeners = [
        'categoryUpdated' => '$refresh', // refresh table when category is saved
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        Service::findOrFail($id)->delete();
        session()->flash('success', 'Category deleted successfully.');
    }

    public function edit($id)
    {
        $this->dispatch('openModal', id: $id); // open modal with category
    }
    public function updatedPerPage()
    {
        $allowed = [10, 25, 50, 100];
        $this->perPage = in_array((int)$value, $allowed) ? (int)$value : 10;
        $this->resetPage();
    }
    public function render()
    {
         
        $categories = Service::query()
        ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
        ->withCount('providers')
        ->with(['providers' => fn($q) => $q->select('users.id','users.name','users.avatar')->limit(1)])
        ->latest()
        ->paginate($this->perPage); 
        return view('livewire.admin.service-categories.table', compact('categories'));
    } 
}
