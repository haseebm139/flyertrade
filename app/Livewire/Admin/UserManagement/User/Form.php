<?php

namespace App\Livewire\Admin\UserManagement\User;

use Livewire\Component;

use App\Models\User;
class Form extends Component
{

    public $showModal = false;
    public $userId;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    protected $listeners = [        
        'addItemRequested' => 'open',
    ];

    public function open($id = null)
    {
         
        $this->resetValidation();
        $this->resetForm();

        if ($id) {
            $data = User::find($id);
            if ($data) {
                $this->userId   = $data->id;
                $this->name     = $data->name;
                $this->email    = $data->email;
                $this->phone    = $data->phone;
                $this->address  = $data->address;
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
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'required|unique:users,phone',
            'address'   => 'required',
        ]);

        $user = User::create([
            'name'      => $this->name,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'address'   => $this->address,
            'role_id'   => 'customer',
            'user_type' => 'customer',
        ]);

        $user->assignRole('customer');
        $this->dispatch('showToastr', 'success', 'Service User Created Successfully.', 'Success' );
         
        
        $this->dispatch('categoryUpdated');

        $this->close(); 
    } 

    public function update()
    {
        
        $this->validate([
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users,email,' . $this->userId,
            'phone'     => 'required|unique:users,phone,' . $this->userId,
            'address'   => 'required',
        ]);

        $user = User::where('id',$this->userId)
        ->where('user_type','customer')->first();
        if(!$user) {
            $this->dispatch('showToastr', 'error', 'Service User not found.', 'Error');
            $this->close();
        }
        $user->update([
            'name'    => $this->name,
            'email'   => $this->email,
            'phone'   => $this->phone,
            'address' => $this->address,
        ]);         

        $this->dispatch('showToastr', 'success', 'Service User updated successfully.', 'Success');
        $this->dispatch('categoryUpdated');
        $this->close();
    }
    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = ''; 
    }
    public function render()
    {
         
        return view('livewire.admin.user-management.user.form');
    }
}
