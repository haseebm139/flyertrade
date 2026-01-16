<div>
<style>

#addUserModal label{
        margin-top: 1vw;
    margin-bottom: 0.2vw;
}
</style>

    @if ($showModal)
        <div id="addUserModal" class="modal" style="display: flex;">
            <div class="modal-content add-user-modal">
                <span class="close-modal" wire:click="closeUserModal"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg></span>
                <h3>{{ $isEdit ? 'Edit User' : 'Add User' }}</h3>
                <form wire:submit.prevent="save">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-input @error('name') error-input @enderror " wire:model="name" placeholder="Enter name" wire:loading.attr="disabled" wire:target="save">
                        @error('name')
                            <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-input @error('email') error-input @enderror" wire:model="email" placeholder="Enter email" wire:loading.attr="disabled" wire:target="save">
                        @error('email')
                            <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Home Address</label>
                        <input type="text" class="form-input  @error('address') error-input @enderror" wire:model="address" placeholder="Enter home address" wire:loading.attr="disabled" wire:target="save">
                        @error('address')
                            <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="number" class="form-input  @error('phone') error-input @enderror" wire:model="phone" placeholder="Enter phone number" wire:loading.attr="disabled" wire:target="save">
                        @error('phone')
                            <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="userType" class="form-label">User Type</label>
                        <x-custom-select
                            name="user_type"
                            id="userType"
                            :options="array_merge(
                                [['value' => '', 'label' => 'Select user type']],
                                $availableRoles->map(function($role) {
                                    return ['value' => $role->name, 'label' => ucfirst($role->name)];
                                })->toArray()
                            )"
                            placeholder="Select user type"
                            wireModel="user_type"
                            class="form-select"
                        />
                        @error('user_type')
                           <div class="error-message">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="button" class="cancel-btn" wire:click="closeUserModal" wire:loading.attr="disabled" wire:target="save">Cancel</button>
                        <button type="submit" class="submit-btn" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">
                                <i class="fa-solid fa-plus mr-3"></i>
                                {{ $isEdit ? 'Update User' : 'Add User' }}
                            </span>
                            <span wire:loading wire:target="save">
                                <i class="fa-solid fa-spinner fa-spin mr-3"></i>
                                {{ $isEdit ? 'Updating...' : 'Adding...' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

     
</div>
