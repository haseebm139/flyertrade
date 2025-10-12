<div>
    <button wire:click="openModal" style="background: red; color: white; padding: 10px;">Test Open Modal</button>
    @if ($showModal)
        <div class="modal" style="display: flex;">
            <div class="modal-content role-add add-user-modal">
                <span class="close-modal" wire:click="closeModal">&times;</span>
                <h3>{{ $isEdit ? 'Edit Role' : 'Add Role' }}</h3>
                <form wire:submit.prevent="save">
                    <!-- Role input -->
                    <label>Role Name</label>
                    <input type="text" class="form-input" wire:model="name" placeholder="Enter role name" required>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <!-- Permission Section -->
                    <div class="permission-section">
                        <h4>Permissions</h4>

                        @foreach ($permissionGroups as $groupName => $groupPermissions)
                            @if ($groupPermissions->count() > 0)
                                <div class="permission-group">
                                    <h5>{{ $groupName }}</h5>
                                    <div class="permission-items">
                                        @foreach ($groupPermissions as $permission)
                                            <div class="permission-item">
                                                <input type="checkbox" id="permission_{{ $permission->id }}"
                                                    value="{{ $permission->id }}" wire:model="permissions">
                                                <label for="permission_{{ $permission->id }}">
                                                    {{ ucwords(str_replace(['_', '-'], ' ', $permission->name)) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Form actions -->
                    <div class="form-actions">
                        <button type="button" class="cancel-btn" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="submit-btn">
                            {{ $isEdit ? 'Update Role' : 'Create Role' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif


</div>
