<?php

namespace App\Livewire\Admin\Components;

use Livewire\Component;

class Toolbar extends Component
{

    public $search = '';
    public string $label = 'Item'; // default label
    public bool $showAddButton = true; // Control button visibility
    
    public function addItem()
    {
         
        $this->dispatch('addItemRequested');
    }
    public function exportCsv()
    {
        // Tell parent to handle exporting
        $this->dispatch('exportCsvRequested');
    }

    public function openFilterModal()
    {
        // Tell parent table to show modal
        $this->dispatch('openFilterModal');
    }

    public function updatedSearch()
    {

        
        // Send search updates to parent
        $this->dispatch('searchUpdated', $this->search);
    }


     
    public function render()
    {
        return view('livewire.admin.components.toolbar');
    }
}
