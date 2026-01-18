<div>
    <!-- Breadcrumb -->
    <div class="users-toolbar">
        <nav class="breadcrumb">
            <a href="{{ route('roles-and-permissions.index') }}">Roles</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">{{ ucfirst($role->name) ?? 'Role Details' }}</span>
        </nav>
    </div>

    <!-- Toolbar -->
    <div class="users-toolbar" style="position: relative">
        <div class="toolbar-left">
            @if ($role)
                <button class="edit-btn" wire:click="editRole">
                    <span class="download-icon">
                        <img src="{{ asset('assets/images/icons/edit.svg') }}" alt="" class="icons-btn">
                    </span> Edit Role
                </button>

                <button class="delete-btn" wire:click="openDeleteModal">
                  
                    Delete Role&nbsp;
                      <span class="download-icon">
                        <img src="{{ asset('assets/images/icons/trash.svg') }}" alt="" class="icons-btn">
                    </span>
                </button>
            @endif
        </div>

        <div class="toolbar-right">
            <h2 class="page-titles">{{ ucfirst($role->name) ?? 'Role Details' }}</h2>
        </div>
          <!-- Global Delete Modal -->
    @if ($showDeleteModal)
            <div class="deleteModal delete-card" id="global-delete-modal" style="
    position: absolute;
    right: 12vw;
    top: 1vw;
">
            <div class="delete-card-header">
                <h3 class="delete-title">Delete Role</h3>
                <span class="delete-close" wire:click="closeDeleteModal">&times;</span>
            </div>
            <p class="delete-text">Are you sure you want to delete this role?
            </p>
             
            <div class="delete-actions justify-content-start" >
                <button class="confirm-delete-btn" wire:click="deleteRole">Delete</button>
                <button class="cancel-delete-btn" wire:click="closeDeleteModal">Cancel</button>
            </div>
        </div>
    @endif
    </div>

    <div class="users-toolbars">
        <h2 class="page-titles text-end">Permissions</h2>
    </div>

    <!-- Tabs Wrapper -->
    <div class="tabs-wrapper">
        <!-- Left Control -->
        <button class="tab-control left" onclick="scrollTabs('left')">
            <img src="{{ asset('assets/images/icons/left_control.svg') }}" alt="Left">
        </button>

        <!-- Tabs Navigation -->
        <div class="tabs-nav theme-btn-class-roles-module" id="tabsNav">
            @foreach ($permissionGroups as $groupName => $groupPermissions)
                <div class="tab roles-permission-theme-tabs {{ $loop->first ? 'active' : '' }}"
                    data-target="{{ Str::slug($groupName) }}" onclick="switchTab('{{ Str::slug($groupName) }}')">
                    {{ $groupName }}
                </div>
            @endforeach
        </div>

        <!-- Right Control -->
        <button class="tab-control right" onclick="scrollTabs('right')">
            <img src="{{ asset('assets/images/icons/right-control.svg') }}" alt="Right">
        </button>
    </div>

    <!-- Tab Content -->
    @foreach ($permissionGroups as $groupName => $groupPermissions)
        <div id="{{ Str::slug($groupName) }}" class="tab-content {{ $loop->first ? 'active' : '' }}">
            @foreach ($groupPermissions as $permission)
                <div class="permission-item">
                    <span >{{ ucwords(str_replace(['-', '_'], ' ', $permission->name)) }}</span>
                    <input type="checkbox" wire:model="permissions" value="{{ $permission->name }}"
                        id="permission_{{ $permission->id }}">
                </div>
            @endforeach
            <hr>
            <div class="form-actions d-flex justify-content-end theme-btn-class-roles-module">
                <button type="button" class="cancel-btn" wire:click="resetPermissions">Cancel</button>
                <button type="button" class="submit-btn" wire:click="updatePermissions">Submit</button>
            </div>
        </div>
    @endforeach
    <div class="users-toolbars">
        <h2 class="page-titles text-end">Assigned users</h2>
    </div>
    <!-- Users Table -->
    <table class="theme-table">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAllUsers"></th>
                <th class="sortable" data-column="0">User Type
                    <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                </th>
                <th class="sortable" data-column="4">User name
                    <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                </th>
                <th class="sortable">Last login
                    <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                </th>
                <th class="sortable" data-column="6">Date added
                    <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if ($role && $role->users->count() > 0)
                @foreach ($role->users as $user)
                    <tr>
                        <td><input type="checkbox" class="user-checkbox" value="{{ $user->id }}"></td>
                        <td>{{ ucfirst($user->user_type) }}</td>
                        <td>
                            <div class="user-info">
                                <img src="{{ asset('assets/images/icons/person-one.svg') }}" alt="User">
                                <div>
                                    <p class="user-name">{{ $user->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status last-seen py-2" style="font-weight:400">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '2 weeks ago' }}
                            </span>
                        </td>
                        <td><span class="date">{{ $user->created_at->format('Y-m-d') }}</span></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">No users assigned to this role</td>
                </tr>
            @endif
        </tbody>
    </table>

  

    <script>
        function scrollTabs(direction) {
            const tabsNav = document.getElementById('tabsNav');
            const scrollAmount = 200;

            if (direction === 'left') {
                tabsNav.scrollLeft -= scrollAmount;
            } else {
                tabsNav.scrollLeft += scrollAmount;
            }
        }

        function switchTab(tabId) {
            const wrapper = document.querySelector('.tabs-wrapper');
            if (!wrapper) return;

            // Hide all tab contents (scoped to the current component if possible, but at least use classes correctly)
            document.querySelectorAll('.tab-content').forEach(content => {
                // Only affect contents that are NOT main-tab-content
                if (!content.classList.contains('main-tab-content')) {
                    content.classList.remove('active');
                }
            });

            // Remove active class from all tabs within this wrapper
            wrapper.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            const targetContent = document.getElementById(tabId);
            if (targetContent) {
                targetContent.classList.add('active');
            }

            // Add active class to clicked tab
            const clickedTab = wrapper.querySelector(`[data-target="${tabId}"]`);
            if (clickedTab) {
                clickedTab.classList.add('active');
            }
        }


        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.querySelector('.deleteModal');
            if (event.target === modal) {
                @this.call('closeDeleteModal');
            }
        });

        // Close modal with escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                @this.call('closeDeleteModal');
            }
        });
    </script>
</div>
