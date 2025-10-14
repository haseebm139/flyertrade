<div>
    <!-- Toolbar -->
    <div class="users-toolbar">
        <div class="toolbar-left">
            <button class="export-btn" wire:click="exportCsv">
                <span class="download-icon">
                    <img class="btn-icons" src="{{ asset('assets/images/icons/download.png') }}" alt="">
                </span> Export CSV
            </button>
            <button class="add-user-btn" wire:click="addRole">
                + Add Role
            </button>
        </div>
        <div class="toolbar-right">
            <input type="text" class="search-user" placeholder="Search roles" wire:model.live="search">
            <button class="filter-btn" wire:click="openFilterModal">
                <span class="download-icon">
                    <img class="btn-icons" src="{{ asset('assets/images/icons/button-icon.png') }}" alt="">
                </span>Filter
            </button>
        </div>
    </div>

    <!-- Table -->
    <table class="theme-table roles">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th wire:click="sortBy('name')" class="sortable" style="cursor: pointer;">
                    Role
                    <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>
                <th wire:click="sortBy('users_count')" class="sortable" style="cursor: pointer;">
                    Assignees
                    <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>

                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                <tr>
                    <td><input type="checkbox" value="{{ $role->id }}" wire:model.live="selected"></td>
                    <td>
                        <div class="role-info">
                            <span class="role-name">{{ ucfirst($role->name) ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="users-info">
                            @if ($role->users->count() > 0)
                                <div class="user-avatar">
                                    <img src="{{ asset($role->users->first()->avatar) ?? asset('assets/images/icons/person-one.png') }}"
                                        alt="User" class="avatar-small">
                                    <span class="more">+{{ $role->users_count - 1 }} users</span>
                                </div>
                            @else
                                <span class="users-count more">{{ $role->users_count }} users</span>
                            @endif
                        </div>
                    </td>

                    <td class="viw-parent theme-parent-class">
                        <a href="javascript:void(0);" class="view-btn" wire:click="viewRole({{ $role->id }})">
                            <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                            View
                        </a>
                        <a href="javascript:void(0);" class="view-btn" wire:click="editRole({{ $role->id }})">
                            <img src="{{ asset('assets/images/icons/edit.png') }}" alt="Edit" class="eye-icon">
                            Edit
                        </a>
                        <a href="javascript:void(0);" class="view-btn"
                            wire:click="openDeleteModal({{ $role->id }})">
                            <img src="{{ asset('assets/images/icons/trash_trash.png') }}" alt="Delete"
                                class="eye-icon">
                            Delete
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No roles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $roles->links('vendor.pagination.custom') }}

    <!-- Filter Modal -->
    @if ($showFilterModal)
        <div class="modal filter-theme-modal" style="display: flex;">
            <div class="modal-content filter-modal">
                <span class="close-modal" wire:click="closeFilterModal">&times;</span>
                <h3>Filter</h3>
                <label>Select Date</label>
                <div class="date-range">
                    <div>
                        <span>From:</span>
                        <input type="date" class="form-input" wire:model="fromDate">
                    </div>
                    <div>
                        <span>To:</span>
                        <input type="date" class="form-input" wire:model="toDate">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="reset-btn" wire:click="resetFilters">Reset</button>
                    <button type="button" class="submit-btn" wire:click="applyFilters">Apply Now</button>
                </div>
            </div>
        </div>
    @endif

    <style>
        .users-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-avatar {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e0e0;
        }

        .users-count {
            font-size: 14px;
            color: #666;
        }
    </style>

    <script>
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

    <!-- Global Delete Modal -->
    @if ($showDeleteModal)
        <div class="deleteModal delete-card" id="global-delete-modal">
            <div class="delete-card-header">
                <h3 class="delete-title">Delete Role</h3>
                <span class="delete-close" wire:click="closeDeleteModal">&times;</span>
            </div>
            <p class="delete-text">Are you sure you want to delete this role?
            </p>
             
            <div class="delete-actions">
                <button class="confirm-delete-btn" wire:click="deleteRole">Delete</button>
                <button class="cancel-delete-btn" wire:click="closeDeleteModal">Cancel</button>
            </div>
        </div>
    @endif
</div>
