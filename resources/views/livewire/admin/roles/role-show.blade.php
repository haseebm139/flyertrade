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
            <div class="deleteModal delete-card" id="global-delete-modal"
                style="
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

                <div class="delete-actions justify-content-start">
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
                    data-target="{{ Str::slug($groupName) }}_show_tab"
                    onclick="switchTab('{{ Str::slug($groupName) }}_show_tab')">
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
        <div id="{{ Str::slug($groupName) }}_show_tab" class="tab-content {{ $loop->first ? 'active' : '' }}"
            style="margin-left: 1vw">
            @foreach ($groupPermissions as $permission)
                <div class="permission-item">
                    <span>{{ ucwords(str_replace(['-', '_'], ' ', $permission->name)) }}</span>
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
                <th><input type="checkbox" wire:model.live="selectAllUsers"></th>
                <th class="sortable" wire:click="sortBy('user_type')">User Type
                    <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon {{ $sortColumn === 'user_type' ? ($sortDirection === 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                </th>
                <th class="sortable" wire:click="sortBy('name')">User name
                    <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon {{ $sortColumn === 'name' ? ($sortDirection === 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                </th>
                <th class="sortable" wire:click="sortBy('last_login_at')">Last login
                    <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon {{ $sortColumn === 'last_login_at' ? ($sortDirection === 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                </th>
                <th class="sortable" wire:click="sortBy('created_at')">Date added
                    <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon {{ $sortColumn === 'created_at' ? ($sortDirection === 'asc' ? 'sort-asc' : 'sort-desc') : '' }}">
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if ($assignedUsers && $assignedUsers->count() > 0)
                @foreach ($assignedUsers as $user)
                    <tr wire:key="assigned-user-{{ $user->id }}">
                        <td><input type="checkbox" wire:model.live="selectedUsers" value="{{ $user->id }}"></td>
                        <td style="cursor: pointer;"
                            onclick="window.location.href='{{ route('roles-and-permissions.users.show', ['id' => $user->id]) }}'">
                            {{ ucfirst($user->user_type) }}</td>
                        <td style="cursor: pointer;"
                            onclick="window.location.href='{{ route('roles-and-permissions.users.show', ['id' => $user->id]) }}'">
                            <div class="user-info">
                                <img src="{{ asset($user->avatar ?? 'assets/images/icons/person-one.svg') }}"
                                    alt="User">
                                <div>
                                    <p class="user-name">{{ $user->name }}</p>
                                    <p class="user-email">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status last-seen" style="font-weight:400">
                                @if ($user->last_login_at)
                                    @php
                                        $lastLogin = $user->last_login_at;
                                        $now = now();

                                        // Handle potential future time (clock skew) as "Just now"
                                        if ($lastLogin->gt($now)) {
                                            $lastLoginText = 'Just now';
                                        } else {
                                            $diffInDays = $lastLogin->diffInDays($now);

                                            if ($diffInDays >= 30) {
                                                $lastLoginText = 'Last month';
                                            } elseif ($diffInDays >= 7) {
                                                $lastLoginText = 'Last week';
                                            } else {
                                                $lastLoginText = $lastLogin->diffForHumans($now);
                                                // Shorten "minutes" to "min" as per user image
                                                $lastLoginText = str_replace(
                                                    [' minutes ago', ' minute ago'],
                                                    ' min ago',
                                                    $lastLoginText,
                                                );
                                            }
                                        }
                                    @endphp
                                    {{ $lastLoginText }}
                                @else
                                    Never
                                @endif
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

    <div class="mt-3">
        {{ $assignedUsers->links('vendor.pagination.custom') }}
    </div>








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
