<div>



    @if ($showModal)
        <div id="addUserModal" class="modal" style="display: flex;">
            <div class="modal-content add-user-modal">
                <span class="close-modal" wire:click="closeUserModal">&times;</span>
                <h3>{{ $isEdit ? 'Edit User' : 'Add User' }}</h3>
                <form wire:submit.prevent="save">
                    <label>Name</label>
                    <input type="text" class="form-input @error('name') error-input @enderror" wire:model="name" placeholder="Enter name"  >
                    @error('name')
                        <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                    @enderror

                    <label>Email</label>
                    <input type="email" class="form-input @error('email') error-input @enderror" wire:model="email" placeholder="Enter email"  >
                    @error('email')
                        <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                    @enderror

                    <label>Home Address</label>
                    <input type="text" class="form-input @error('address') error-input @enderror" wire:model="address" placeholder="Enter home address">
                    @error('address')
                        <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                    @enderror

                    <label>Phone Number</label>
                    <input type="text" class="form-input  " wire:model="phone" placeholder="Enter phone number">
                    @error('phone')
                        <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                    @enderror

                    <div class="mb-3">
                        <label for="userType" class="form-label">User Type</label>
                        <x-custom-select
                            name="user_type"
                            id="userType"
                            :options="[
                                ['value' => '', 'label' => 'Select user type'],
                                ['value' => 'customer', 'label' => 'Customer'],
                                ['value' => 'provider', 'label' => 'Provider'],
                                ['value' => 'admin', 'label' => 'Admin']
                            ]"
                            placeholder="Select user type"
                            wireModel="user_type"
                            class="form-select @error('user_type') error-input @enderror"
                        />
                        @error('user_type')
                           <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                     

                    <div class="form-actions">
                        <button type="button" class="cancel-btn" wire:click="closeUserModal">Cancel</button>
                        <button type="submit" class="submit-btn">
                            <i class="fa-solid fa-plus mr-3"></i>
                            {{ $isEdit ? 'Update User' : 'Add User' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <style>
        .error {
            color: #d32f2f;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }

        .role-checkboxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .role-item {
            display: flex;
            align-items: center;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f8f9fa;
        }

        .role-item input[type="checkbox"] {
            margin-right: 8px;
        }

        .role-item label {
            margin: 0;
            cursor: pointer;
            flex: 1;
        }
    </style>
</div>
