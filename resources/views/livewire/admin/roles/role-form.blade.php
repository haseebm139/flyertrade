<div>
    {{-- Debug info removed for production --}}
    <style>
        .tabs-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5vw;
            margin-bottom: 1.5vw;
            padding: 0 0.5vw;
        }
        .tabs-nav {
            display: flex;
            gap: 1vw;
            overflow-x: auto;
            scroll-behavior: smooth;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none;  /* IE and Edge */
            padding: 0.5vw 0;
        }
        .tabs-nav::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }
        .roles-permission-theme-tab {
            white-space: nowrap;
            padding: 0.6vw 1.2vw;
            border-radius: 0.5vw;
            font-size: 0.85vw;
            color: #717171;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            background: transparent;
        }
        .roles-permission-theme-tab.active {
            background: #e6f0ed;
            color: #064f3c;
        }
        .tab-control {
            flex-shrink: 0;
            width: 2vw;
            height: 2vw;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid #f1f1f1;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .tab-control:hover {
            background: #f8f9fa;
        }
        .tab-control img {
            width: 0.6vw;
            height: 0.6vw;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>

    @if ($showModal)
        <div class="modal role-form-modal" style="display: flex;" wire:click.self="closeModal" x-on:keydown.escape.window="closeModal">



            <div class="modal-content role-add add-user-modal">
                <span class="close-modal" id="closeAddUserModal" wire:click="closeModal"><svg width="12" height="12"
                        viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75"
                            stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg></span>
                <h3 id="change___Modal_title">{{ $step == 2 ? 'Add Permission' : ($isEdit ? 'Edit Role' : 'Add Role') }} </h3>
                <form wire:submit.prevent="save">
                    @if ($step == 1)
                        <div id="first_btns____wrapper">
                            <!-- Role input -->
                            <div class="form-group">
                                <label>Role</label>
                                <input type="text" class="form-input @error('name') error-input @enderror"
                                    wire:model="name" placeholder="Enter name" wire:loading.attr="disabled" wire:target="save">
                                @error('name')
                                    <div class="error-message" style="margin-top: 0.5rem;">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>
                            <div class="form-actions justify-content-center" id="first_btns____">
                                <button type="button" class="cancel-btn" wire:click="closeModal" wire:loading.attr="disabled" wire:target="save">Cancel</button>
                                <button type="button" class="submit-btn"
                                    wire:click="goToPermissions" wire:loading.attr="disabled" wire:target="save"><i class="fa-solid fa-plus mr-2"></i>
                                    {{ $isEdit ? 'Edit Role' : 'Add Role' }}
                                </button>
                            </div>
                        </div>
                    @endif


                    @if ($step == 2)
                        <div id="second_btns____wrapper" class="permission-wrapper-visible">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="mb-0">Permission</label>
                                <a href="javascript:void(0)" wire:click="backToName" style="color: #064f3c; font-size: 0.8vw; text-decoration: underline;">Edit Name</a>
                            </div>

                            <div class="form-group">
                                <input type="text" class="form-input" value="{{ $name }}" disabled readonly 
                                    style="background-color: #E0E0E0 !important; color: #555 !important; cursor: not-allowed; border: none !important;">
                            </div>

                            <!-- Permission Section -->
                            <div class="permission-section" id="permissionSection">
                                <!-- Tabs navigation -->
                                <div class="tabs-wrapper">
                                    <!-- Left Control -->
                                    <button type="button" class="tab-control left" onclick="scrollTabs(this, -1)">
                                        <img src="{{ asset('assets/images/icons/left_control.svg') }}" alt="Left">
                                    </button>
                                    
                                    <div class="tabs-nav">
                                        @foreach ($permissionGroups as $groupName => $groupPermissions)
                                            @php $tabId = Str::slug($groupName) . '_tab'; @endphp
                                            @if ($groupPermissions->count() > 0)
                                                <div class="tab {{ $activeTab == $tabId ? 'active' : '' }} roles-permission-theme-tab"
                                                    data-target="{{ $tabId }}"
                                                    wire:click="setActiveTab('{{ $tabId }}')">
                                                    {{ $groupName }}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- Right Control -->
                                    <button type="button" class="tab-control right" onclick="scrollTabs(this, 1)">
                                        <img src="{{ asset('assets/images/icons/right-control.svg') }}" alt="Right">
                                    </button>
                                </div>
                            <!-- Tab content -->
                            @foreach ($permissionGroups as $groupName => $groupPermissions)
                                @php $tabId = Str::slug($groupName) . '_tab'; @endphp
                                @if ($groupPermissions->count() > 0)
                                    <div id="{{ $tabId }}" wire:key="content-{{ $tabId }}"
                                        class="tab-content {{ $activeTab == $tabId ? 'active' : '' }}"
                                        style="display: {{ $activeTab == $tabId ? 'block' : 'none' }}">
                                        @foreach ($groupPermissions as $permission)
                                            <div class="permission-item" wire:key="permission-{{ $permission->id }}">
                                                <span>{{ ucwords(str_replace(['-', '_'], ' ', $permission->name)) }}</span>
                                                <input type="checkbox" wire:model.live="permissions"
                                                    value="{{ $permission->name }}"
                                                    id="permission_{{ $permission->id }}">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                            
                            @error('permissions')
                                <div class="error-message" style="margin-top: 1rem; color: #ff0000;">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror

                            <!-- Form actions -->
                            <div class="form-actions justify-content-center">
                                <button type="button" class="cancel-btn" wire:click="closeModal" wire:loading.attr="disabled" wire:target="save">Cancel</button>
                                <button type="submit" class="submit-btn" wire:loading.attr="disabled" wire:target="save">
                                    <span wire:loading.remove wire:target="save">
                                        <i class="fa-solid fa-plus mr-2"></i>
                                        {{ $isEdit ? 'Update permission' : 'Add permission' }}
                                    </span>
                                    <span wire:loading wire:target="save">
                                        <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                                        Saving...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                </form>
            </div>

        </div>
    @endif

    <script>
        function switchTab(tabId) {
            const section = document.getElementById('permissionSection');
            if (!section) return;

            // Hide all tab contents within this section
            section.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
                content.style.display = 'none';
            });

            // Remove active class from all tabs within this section
            section.querySelectorAll('.roles-permission-theme-tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            const targetContent = document.getElementById(tabId);
            if (targetContent) {
                targetContent.classList.add('active');
                targetContent.style.display = 'block';
            }

            // Add active class to clicked tab
            const clickedTab = section.querySelector(`[data-target="${tabId}"]`);
            if (clickedTab) {
                clickedTab.classList.add('active');
            }
        }

        function scrollTabs(btn, direction) {
            const tabsNav = btn.parentElement.querySelector('.tabs-nav');
            if (tabsNav) {
                tabsNav.scrollLeft += direction * 150;
            }
        }
    </script>
</div>
