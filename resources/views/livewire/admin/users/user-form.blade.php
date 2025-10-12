<div>



    @if ($showModal)
        <div id="addUserModal" class="modal" style="display: flex;">
            <div class="modal-content add-user-modal">
                <span class="close-modal" wire:click="closeUserModal">&times;</span>
                <h3>Add User</h3>
                <form wire:submit.prevent="saveUser">
                    <label>Name</label>
                    <input type="text" class="form-input" wire:model="name" placeholder="Enter name">

                    <label>Email</label>
                    <input type="email" class="form-input" wire:model="email" placeholder="Enter email">

                    <label>Home Address</label>
                    <input type="text" class="form-input" wire:model="address" placeholder="Enter home address">

                    <label>Phone Number</label>
                    <input type="text" class="form-input" wire:model="phone" placeholder="Enter phone number">

                    <div class="mb-3">
                        <label for="userType" class="form-label">Role</label>
                        <select class="form-select" id="userType" wire:model="user_type">
                            <option value="" selected>Select role</option>
                            <option value="customer">Customer</option>
                            <option value="provider">Provider</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="cancel-btn" wire:click="closeUserModal">Cancel</button>
                        <button type="submit" class="submit-btn">+ Add User</button>
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
    </style>
</div>
