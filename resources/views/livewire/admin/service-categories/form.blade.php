<div>
    @if ($showModal)
        <div
            class="modal"
            style="display: flex;"
        >
            <div class="modal-content add-user-modal">
                <span
                    class="close-modal"
                    wire:click="close"
                >&times;</span>
                <h3 class="adfa">{{ $categoryId ? 'Edit Service Category' : 'Add Service Category' }}</h3>
                <form wire:submit.prevent="save">
                    <label>Service Name</label>
                    <input
                        type="text"
                        class="form-input"
                        wire:model="name"
                        placeholder="Enter name"
                    >
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <label>Description</label>
                    <input
                        type="text"
                        class="form-input"
                        placeholder="Add description"
                        wire:model="description"
                    >
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="form-actions">
                        <button
                            type="button"
                            wire:click="close"
                            class="cancel-btn"
                        >Cancel</button>
                        <button
                            type="submit"
                            class="submit-btn"
                        >+ {{ $categoryId ? 'Save Changes' : 'Add Service' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
