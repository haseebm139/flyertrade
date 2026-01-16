<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;

use Livewire\Attributes\Reactive;

class Toolbar extends Component
{

    public $search = '';
    public string $label = 'Item'; // default label
    public string $button_label = ''; // default label
    public string $search_label = ''; // default label
    public bool $showAddButton = true; // Control button visibility
    
    #[Reactive]
    public array $activeFilters = []; // Active filters to display in toolbar
    
    public function mount($activeFilters = [])
    {
        $this->activeFilters = $activeFilters;
    }
    
    private function getNamespace()
    {
        return str_replace(' ', '-', strtolower($this->label));
    }

    public function addItem()
    {
        $this->dispatch('addItemRequested-' . $this->getNamespace());
    }

    public function exportCsv()
    {
        $this->dispatch('exportCsvRequested-' . $this->getNamespace());
    }

    public function openFilterModal()
    {
        $this->dispatch('openFilterModal-' . $this->getNamespace());
    }

    public function removeFilter($key)
    {
        $this->dispatch('removeFilter-' . $this->getNamespace(), key: $key);
    }

    public function updatedSearch()
    {
        $this->dispatch('searchUpdated-' . $this->getNamespace(), $this->search);
    }


     
    public function render()
    {
        return view('livewire.admin.components.toolbar');
    }
}
