<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class ToastrNotifier extends Component
{

    #[On('showToastr')]
    public function showToastr($payload)
    {
         
        // Fix the nested array issue
        if (isset($payload[0]) && is_array($payload[0])) {
            $payload = $payload[0];
        }
         
        $this->dispatch('toastrNotification', [
            'type'    => $payload['type'] ?? 'info',
            'message' => $payload['message'] ?? '',
            'title'   => $payload['title'] ?? '',
        ]);
    }


    public function render()
    {
        return view('livewire.components.toastr-notifier');
    }
}
