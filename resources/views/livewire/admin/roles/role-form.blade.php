<div>
    {{-- Debug info removed for production --}}
    @if ($showModal)
        <div class="modal role-form-modal" style="display: flex;">



            <div class="modal-content role-add add-user-modal">
                <span class="close-modal" id="closeAddUserModal" wire:click="closeModal"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg></span>
                <h3 id="change___Modal_title">{{ $isEdit ? 'Edit Role' : 'Add Role' }} </h3>
                <form wire:submit.prevent="save">
                    <div id="first_btns____wrapper">
                        <!-- Role input -->
                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" class="form-input @error('name') error-input @enderror" wire:model="name" placeholder="Enter name">
                            @error('name')
                                <div class="error-message">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                           <div class="form-actions justify-content-center" id="first_btns____">
                            <button type="button" class="cancel-btn" wire:click="closeModal">Cancel</button>
                            <button type="button" class="submit-btn add_permission________" id="add_permission________"><i class="fa-solid fa-plus"></i>
                                {{ $isEdit ? 'Edit Role' : 'Add Role' }}
                            </button>
                        </div>
                    </div>
              

                    <div id="second_btns____wrapper" class="permission-wrapper-hidden">

                    <label>Permission</label>
                    <!-- Permission Section (hidden by default) -->
                    <div class="permission-section" id="permissionSection">
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
                            <!-- Right Control -->
                            <button type="button" class="tab-control right" onclick="scrollTabs(1)">
                                <img src="{{ asset('assets/images/icons/right-control.svg') }} " alt="Right">
                            </button>
                               <!-- Form actions -->
                 
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
                        <!-- Form actions -->
                        <div class="form-actions justify-content-center">
                            <button type="button" class="cancel-btn" wire:click="closeModal">Cancel</button>
                            <button type="submit" class="submit-btn"><i class="fa-solid fa-plus"></i> 
                                {{ $isEdit ? 'Add Permission' : 'Add Permission' }}
                            </button>
                        </div>
                    </div>
                    </div>

                </form>
            </div>

        </div>
    @endif

    <script>
        
        $(document).on('click', '.add_permission________', function(e){
             e.preventDefault();
            // $("#first_btns____wrapper").css("display","none");
             $("#second_btns____wrapper").css("display","block");
             $("#first_btns____").css("display","none");
             $("#change___Modal_title").html('Add Permission');
        
        })
        function switchTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
                $("#ROlesss_tabb").addClass("active");
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
                 $("#ROlesss_tabb").addClass("active");
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
