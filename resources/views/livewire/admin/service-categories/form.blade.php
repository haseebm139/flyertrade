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
                <form>
                    <label>Service Name</label>
                    <input
                        type="text"
                        class="form-input"
                        wire:model.defer="name"
                        placeholder="Enter name"
                    >
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <label>Descrption</label>
                    <input
                        type="text"
                        class="form-input"
                        placeholder="add descrption"
                        wire:model.defer="description"
                    >
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="form-actions">
                        <button
                            wire:click="close"
                            type="button"
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
