<?php

namespace App\Livewire\Admin\ServiceCategories;

use Livewire\Component;
use App\Models\Service;
use Livewire\Attributes\On;
class Form extends Component
{
    public $showModal = false;
    public $categoryId;
    public $name = '';
    public $description = '';

    protected $listeners = [
        'openModal' => 'open',
    ];
    public function open($id = null)
    {
        $this->resetValidation();
        $this->resetForm();

        if ($id) {
            $cat = Service::find($id);
            if ($cat) {
                $this->categoryId   = $cat->id;
                $this->name         = $cat->name;
                $this->description  = $cat->description;
            }
        }

        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $this->categoryId,
            'description' => 'nullable|string|max:500',
        ]);

        Service::updateOrCreate(
            ['id' => $this->categoryId],
            ['name' => $this->name, 'description' => $this->description]
        );
        $this->dispatch('showToastr', 'success', 'Service ' . ($this->categoryId ? 'updated' : 'created') . ' successfully.', 'Success');
         
        // notify the table to refresh
        $this->dispatch('categoryUpdated');

        $this->close();
        session()->flash('success', 'Service saved successfully.');
    } 
    private function resetForm()
    {
        $this->categoryId = null;
        $this->name = '';
        $this->description = '';
    }
    public function render()
    {
        return view('livewire.admin.service-categories.form');
    }
}
