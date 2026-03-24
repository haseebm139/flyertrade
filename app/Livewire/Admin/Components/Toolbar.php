<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;

class Toolbar extends Component
{
    #[Modelable]
    public $search = '';
    public string $label = 'Item'; // default label
    public string $button_label = ''; // default label
    public string $search_label = ''; // default label
    public bool $showAddButton = true; // Control button visibility
    
    #[Reactive]
    public array $activeFilters = []; // Active filters to display in toolbar

    /** When set, server events target this parent Livewire component (nested toolbar → table). */
    public ?string $dispatchParent = null;

    public function mount($activeFilters = [])
    {
        $this->activeFilters = $activeFilters;
    }

    private function routeDispatchToParent($event): void
    {
        if ($this->dispatchParent) {
            $event->to($this->dispatchParent);
        }
    }
    
    private function getNamespace()
    {
        return str_replace(' ', '-', strtolower($this->label));
    }

    public function addItem()
    {
        $this->routeDispatchToParent($this->dispatch('addItemRequested-' . $this->getNamespace()));
    }

    public function exportCsv()
    {
        $this->routeDispatchToParent($this->dispatch('exportCsvRequested-' . $this->getNamespace()));
    }

    public function openFilterModal()
    {
        $this->routeDispatchToParent($this->dispatch('openFilterModal-' . $this->getNamespace()));
    }

    public function removeFilter($key)
    {
        $this->routeDispatchToParent($this->dispatch('removeFilter-' . $this->getNamespace(), key: $key));
    }

    public function getListeners()
    {
        return [
            'search-reset-' . $this->getNamespace() => 'resetSearch',
        ];
    }

    public function resetSearch()
    {
        $this->search = '';
    }

    public function render()
    {
        return view('livewire.admin.components.toolbar');
    }
}
