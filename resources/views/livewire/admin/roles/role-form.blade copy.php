<div>
    {{-- <button wire:click="openModal" style="background: red; color: white; padding: 10px;">Test Open Modal</button> --}}
    @if ($showModal)
        <div class="modal" style="display: flex;">



            <div class="modal-content role-add add-user-modal">
                <span class="close-modal" id="closeAddUserModal" wire:click="closeModal">&times;</span>
                <h3>{{ $isEdit ? 'Edit Permission' : 'Add Permission' }} </h3>
                <form wire:submit.prevent="save">
                    <!-- Role input -->
                    <label>Role</label>
                    <input type="text" class="form-input" wire:model="name" placeholder="Enter name">
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror

                    <label>Permission</label>
                    <!-- Permission Section (hidden by default) -->
                    <div class="permission-section" id="permissionSection" style="display: block;">
                        <!-- Tabs navigation -->
                        <div class="tabs-wrapper">
                            <!-- Left Control -->
                            <button type="button" class="tab-control left" onclick="scrollTabs(-1)">
                                <img src="{{ asset('assets/images/icons/left_control.svg') }}" alt="Left">
                            </button>

                            @foreach ($permissionGroups as $groupName => $groupPermissions)
                                @if ($groupPermissions->count() > 0)
                                    <div class="tabs-nav">
                                        <div class="tab {{ $loop->first ? 'active' : '' }} roles-permission-theme-tab"
                                            data-target="{{ Str::slug($groupName) . '_tab' }}"
                                            onclick="switchTab('{{ Str::slug($groupName) . '_tab' }}')">
                                            {{ $groupName }}</div>

                                    </div>
                                @endif
                            @endforeach



                            <!-- Tabs Navigation -->

                            <!-- Right Control -->
                            <button type="button" class="tab-control right" onclick="scrollTabs(1)">
                                <img src="{{ asset('assets/images/icons/right-control.svg') }} " alt="Right">
                            </button>
                        </div>


                        <!-- Tab content -->
                        @foreach ($permissionGroups as $groupName => $groupPermissions)
                            @if ($groupPermissions->count() > 0)
                                <div id="{{ Str::slug($groupName) }}_tab"
                                    class="tab-content {{ $loop->first ? 'active' : '' }}">



                                    @foreach ($groupPermissions as $permission)
                                        <div class="permission-item">
                                            <span
                                                class="user-name">{{ ucwords(str_replace(['-', '_'], ' ', $permission->name)) }}</span>
                                            <input type="checkbox" wire:model="permissions"
                                                value="{{ $permission->name }}" id="permission_{{ $permission->id }}">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach





                    </div>

                    <!-- Form actions -->
                    <div class="form-actions justify-content-center">
                        <button type="button" class="cancel-btn" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="submit-btn">+
                            {{ $isEdit ? 'Update Permission' : 'Add Permission' }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    @endif

    <script>
        function switchTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            const targetContent = document.getElementById(tabId);
            if (targetContent) {
                targetContent.classList.add('active');
            }

            // Add active class to clicked tab
            const clickedTab = document.querySelector(`[data-target="${tabId}"]`);
            if (clickedTab) {
                clickedTab.classList.add('active');
            }
        }

        function scrollTabs(direction) {
            const tabsContainer = document.querySelector('.tabs-wrapper');
            if (tabsContainer) {
                tabsContainer.scrollLeft += direction * 200;
            }
        }









        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.querySelector('.modal');
            if (event.target === modal) {
                @this.call('closeModal');
            }
        });

        // Close modal with escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                @this.call('closeModal');
            }
        });
    </script>
</div>
