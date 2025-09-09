<div>    
    @if ($showModal)
        <div style="display: flex;" class="modal">
            <div class="modal-content add-user-modal">
                <span class="close-modal" id="closeAddUserModal" wire:click="close">&times;</span>
                <h3>{{ $userId ? 'Edit User' : 'Add User' }}</h3>
                <form wire:submit.prevent="{{ $userId ? 'update' : 'save' }}" >
                    <label>Name</label>
                    <input type="text" class="form-input" placeholder="Enter name" wire:model="name">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <label>Email</label>
                    <input type="email" class="form-input" placeholder="Enter email" wire:model="email">
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <label>Home Address</label>
                    <input type="text" class="form-input" placeholder="Enter home address" wire:model="address">
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <label>Phone Number</label>
                    <input type="text" class="form-input" placeholder="Enter phone number" wire:model="phone">
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="form-actions">
                        <button type="button" class="cancel-btn " wire:click="close">Cancel</button>
                        <button type="submit" class="submit-btn"> + {{ $userId ? 'Save Changes' : 'Add User' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
