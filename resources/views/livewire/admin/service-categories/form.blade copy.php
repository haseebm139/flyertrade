<div>
    @if ($showModal)
        <div
            class="modal-overlay"
            id="addUserModal"
        >
            <div class="modal-content add-user-modal">
                <button
                    id="closeAddUserModal"
                    type="button"
                    class="close-modal"
                    wire:click="close"
                >&times;</button>

                <h3 class="adfa">{{ $categoryId ? 'Edit Service Category' : 'Add Service Category' }}</h3>

                <form wire:submit.prevent="save">
                    <label>Service Name</label>
                    <input
                        type="text"
                        wire:model.defer="name"
                        class="form-input"
                        placeholder="Enter name"
                    >
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <label>Description</label>
                    <input
                        type="text"
                        wire:model.defer="description"
                        class="form-input"
                        placeholder="Enter description"
                    >
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <div class="form-actions">
                        <button
                            type="button"
                            class="cancel-btn"
                            wire:click="close"
                        >Cancel</button>
                        <button
                            type="submit"
                            class="submit-btn"
                        >
                            + {{ $categoryId ? 'Save Changes' : 'Add Service' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
