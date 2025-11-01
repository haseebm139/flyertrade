<div>



    @if ($showModal)
        <div id="addUserModal" class="modal" style="display: flex;">
            <div class="modal-content add-user-modal">
                <span class="close-modal" wire:click="closeUserModal">&times;</span>
                <h3>{{ $isEdit ? 'Edit User' : 'Add User' }}</h3>
                <form wire:submit.prevent="save">
                    <label>Name</label>
                    <input type="text" class="form-input" wire:model="name" placeholder="Enter name" required>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <label>Email</label>
                    <input type="email" class="form-input" wire:model="email" placeholder="Enter email" required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <label>Home Address</label>
                    <input type="text" class="form-input" wire:model="address" placeholder="Enter home address">
                    @error('address')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <label>Phone Number</label>
                    <input type="text" class="form-input" wire:model="phone" placeholder="Enter phone number">
                    @error('phone')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <div class="mb-3">
                        <label for="userType" class="form-label">User Type</label>
                        <select class="form-select" id="userType" wire:model="user_type" required>
                            <option value="" selected>Select user type</option>
                            <option value="customer">Customer</option>
                            <option value="provider">Provider</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('user_type')
                            <span class="error">{{ $message }}</span>
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
