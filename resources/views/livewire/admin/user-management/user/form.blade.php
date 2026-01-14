<div>
    @if ($showModal)
        <div style="display: flex;" class="modal">
            <div class="modal-content add-user-modal">
                <span class="close-modal" id="closeAddUserModal" wire:click="close"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg></span>
                <h3>{{ $userId ? 'Edit User' : 'Add User' }}</h3>
                <form wire:submit.prevent="{{ $userId ? 'update' : 'save' }}">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-input @error('name') error-input @enderror"
                            placeholder="Enter name" wire:model="name">
                        @error('name')
                            <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-input @error('email') error-input @enderror"
                            placeholder="Enter email" wire:model="email">
                        @error('email')
                            <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Home Address</label>
                        <input type="text" class="form-input @error('address') error-input @enderror"
                            placeholder="Enter home address" wire:model="address">
                        @error('address')
                            <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" class="form-input @error('phone') error-input @enderror"
                            placeholder="Enter phone number (e.g., +1234567890)" wire:model="phone">
                        @error('phone')
                            <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    <div class="form-actions">
                        <button type="button" class="cancel-btn " wire:click="close">Cancel</button>
                        <button type="submit" class="submit-btn"> <i class="fa-solid fa-plus mr-3"></i>
                            {{ $userId ? 'Save Changes' : 'Add User' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
