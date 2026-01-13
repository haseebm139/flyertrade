<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;


class UserProvidersModal extends Component
{

    public $showModal = false;
    public $providers = [];
    public $title = "Providers";

    public function openModal($itemId)
    {
 
        $item = User::with('providers')->findOrFail($itemId);

        $this->providers = $item->providers->map(function ($provider) {
            return [
                'name'   => $provider->name,
                'email'  => $provider->email,
                'avatar' => asset($provider->avatar ?? 'assets/images/icons/person-one.svg'),
            ];
        })->toArray();

        $this->title = $item->name; // or any label
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.admin.user-providers-modal');
    }
}
