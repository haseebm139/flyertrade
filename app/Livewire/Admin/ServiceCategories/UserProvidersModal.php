<?php

namespace App\Livewire\Admin\ServiceCategories;

use Livewire\Component;
use App\Models\User;
use App\Models\Service;
class UserProvidersModal extends Component
{
    public $show = false;
    public $providers = [];
    public $title;
    
    protected $listeners = ['open-user-providers-modal' => 'openModal'];
    public function openModal($serviceId)
    {
        if ($serviceId) {
            $service = Service::with('providers')->find($serviceId);
            $this->providers = $service?->providers ?? [];
            $this->title = $service?->name;
            $this->show = true;
        }
    }

    public function closeModal()
    {
        $this->show = false;
        $this->providers = [];
        $this->title = '';
    }
    public function render()
    {
        return view('livewire.admin.service-categories.user-providers-modal');
    }
}
