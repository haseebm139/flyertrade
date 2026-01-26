<div>
    @if ($showModal)
        <div class="modal" style="display: flex;">
            <div class="modal-content add-user-modal">
                <span class="close-modal" wire:click="close"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg></span>
                <h3 class="adfa">{{ $categoryId ? 'Edit Service Category' : 'Add Service Category' }}</h3>
                <form wire:submit.prevent="save">
                    <div class="form-group">
                        <label>Service Name</label>
                        <input type="text" class="form-input @error('name') error-input @enderror" wire:model="name"
                            placeholder="Enter name" wire:loading.attr="disabled" wire:target="save">
                        @error('name')
                            <div class="error-message" style="margin-top: 0.5rem;">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea id="myTextarea" rows="2" class="form-input @error('description') error-input @enderror"
                            placeholder="Add description" wire:model="description" wire:loading.attr="disabled"
                            wire:target="save"></textarea>
                        @error('description')
                            <div class="error-message" style="margin-top: 0.5rem;">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" class="form-input @error('icon') error-input @enderror" wire:model="icon"
                            accept="image/*" wire:loading.attr="disabled" wire:target="save,icon">
                        @error('icon')
                            <div class="error-message" style="margin-top: 0.5rem;">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                        @if ($icon)
                            <div style="margin-top: 0.8vw;">
                                <img src="{{ $icon->temporaryUrl() }}" alt="Preview"
                                    style="width:3vw;height:3vw;object-fit:cover;border-radius:0.5vw;">
                            </div>
                        @elseif ($existingIcon)
                            <div style="margin-top: 0.8vw;">
                                <img src="{{ asset($existingIcon) }}" alt="Current"
                                    style="width:3vw;height:3vw;object-fit:cover;border-radius:0.5vw;">
                            </div>
                        @endif
                    </div>
                    <div class="form-actions">
                        <button type="button" wire:click="close" class="cancel-btn" wire:loading.attr="disabled"
                            wire:target="save">Cancel</button>
                        <button type="submit" class="submit-btn" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">
                                <i class="fa-solid fa-plus mr-3"></i>
                                {{ $categoryId ? 'Save Changes' : 'Add Service' }}
                            </span>
                            <span wire:loading wire:target="save">
                                <i class="fa-solid fa-spinner fa-spin mr-3"></i>
                                {{ $categoryId ? 'Saving...' : 'Adding...' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById("myTextarea");
        if (textarea) {
            textarea.addEventListener("input", () => {
                textarea.style.height = "auto"; // reset height
                textarea.style.height = textarea.scrollHeight + "px"; // set new height
            });
        }
    });
</script>
