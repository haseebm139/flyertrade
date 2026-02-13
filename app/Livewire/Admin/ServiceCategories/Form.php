<?php

namespace App\Livewire\Admin\ServiceCategories;

use Livewire\Component;
use App\Models\Service;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
class Form extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $categoryId;
    public $name = '';
    public $description = '';
    public $icon;
    public $existingIcon = '';
     
    protected $listeners = [        
        'addItemRequested' => 'open',
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
                $this->existingIcon = $cat->icon ?? '';
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
            'description' => 'nullable|string',
            'icon' => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:51200',
        ]);

        try {
            $iconPath = $this->existingIcon;
            if ($this->icon) {
                $iconPath = $this->icon->storePublicly('service-categories', 'public');
                $iconPath = 'storage/' . ltrim($iconPath, '/');
            }

            Service::updateOrCreate(
                ['id' => $this->categoryId],
                ['name' => $this->name, 'description' => $this->description, 'icon' => $iconPath]
            );
            $this->dispatch('showSweetAlert', 'success', 'Service ' . ($this->categoryId ? 'updated' : 'created') . ' successfully.', 'Success');
            
            // notify the table to refresh
            $this->dispatch('categoryUpdated');

            $this->close();
        } catch (\Exception $e) {
            $this->dispatch('showSweetAlert', 'error', 'Error saving service: ' . $e->getMessage(), 'Error');
        }
    } 
    private function resetForm()
    {
        $this->categoryId = null;
        $this->name = '';
        $this->description = '';
        $this->icon = null;
        $this->existingIcon = '';
    }
    public function render()
    {
        return view('livewire.admin.service-categories.form');
    }
}
