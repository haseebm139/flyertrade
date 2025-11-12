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
                    <textarea style=""
                    id="myTextarea"
                    rows="2"
                        type="text"
                        class="form-input"
                        placeholder="Add description"
                        wire:model="description" rows="1"></textarea>
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

<style>
#myTextarea {
  width: 100%;
  min-height: 40px;
  resize: none; /* optional: user can't resize manually */
  overflow: hidden; /* optional: hide scrollbar */
}
</style>
</div>
<script>
const textarea = document.getElementById("myTextarea");

textarea.addEventListener("input", () => {
  textarea.style.height = "auto"; // reset height
  textarea.style.height = textarea.scrollHeight + "px"; // set new height
});
</script>



