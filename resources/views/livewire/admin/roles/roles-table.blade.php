<div>
    <!-- Toolbar -->
    <div class="users-toolbar">
        <div class="toolbar-left">
            <button class="export-btn" wire:click="exportCsv">
                <span class="download-icon">
                    <img class="btn-icons" src="{{ asset('assets/images/icons/download.svg') }}" alt="">
                </span> Export CSV
            </button>
            <button class="add-user-btn" wire:click="addRole">
                <i class="fa-solid fa-plus mr-3"></i> Add Role
            </button>
        </div>
        <div class="toolbar-right">
            <input type="text" class="search-user" placeholder="Search roles" wire:model.live="search">
            <button class="filter-btn" wire:click="openFilterModal">
                Filter <span class="download-icon">
                    <img class="btn-icons" src="{{ asset('assets/images/icons/button-icon.svg') }}" alt="">
                </span>
            </button>
        </div>
    </div>

    <!-- Table -->
    <table class="theme-table roles" style="position: relative">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th wire:click="sortBy('name')" class="sortable" style="cursor: pointer;">
                    Role
                    <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                </th>
                <th wire:click="sortBy('users_count')" class="sortable" style="cursor: pointer;">
                    Assignees
                    <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
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
                            <span class="role-name" style='font-weight:500;'>{{ ucfirst($role->name) ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="users-info">
                            @if ($role->users->count() > 0)
                                <div class="user-avatar">
                                    <img src="{{ asset($role->users->first()->avatar) ?? asset('assets/images/icons/person-one.svg') }}"
                                        alt="User" class="avatar-small">
                                    <span class="more">+{{ $role->users_count - 1 }} users</span>
                                </div>
                            @else
                            <img src="{{  asset('assets/images/icons/person-one.svg') }}"
                                        alt="User" class="avatar-small">
                                <span class="users-count more">{{ $role->users_count }} users</span>
                            @endif
                        </div>
                    </td>

                    <td class="viw-parent theme-parent-class">
                        <a href="javascript:void(0);" class="view-btn" wire:click="viewRole({{ $role->id }})">
                            <img src="{{ asset('assets/images/icons/eye_icon.svg') }}" alt="View" class="eye-icon">
                            View
                        </a>
                        <a href="javascript:void(0);" class="view-btn" wire:click="editRole({{ $role->id }})">
                            <img src="{{ asset('assets/images/icons/edit.svg') }}" alt="Edit" class="eye-icon">
                            Edit
                        </a>
                        <button class="delete-btn showDeleteModal" wire:click="confirmDelete({{ $role->id }})">
                            <img src="{{ asset('assets/images/icons/delete-icon-active.svg') }}" alt="Delete"
                                class="eye-icon">
                            <span
                                style="font-size: 0.9vw; color: #064f3c; cursor: pointer; font-weight: 400;">
                                Delete </span>
                        </button>
                        @if ($confirmingId === $role->id)
                            <div class="deleteModal delete-card" id="global-delete-modal"
                                style=" position: absolute; right: 12vw; top: 1vw; z-index: 99; ">
                                <div class="delete-card-header">
                                    <h3 class="delete-title">Delete Role</h3>
                                    <span class="delete-close" wire:click="$set('confirmingId', null)">&times;</span>
                                </div>
                                <p class="delete-text">Are you sure you want to delete role
                                    <strong>{{ $role->name }}</strong>?
                                </p>
                                <div class="delete-actions  justify-content-start">
                                    <button class="confirm-delete-btn"
                                        wire:click="deleteRole({{ $role->id }})">Delete</button>
                                    <button class="cancel-delete-btn"
                                        wire:click="$set('confirmingId', null)">Cancel</button>
                                </div>
                            </div>
                        @endif
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
   <style>
        .modal_heaader{
            display: flex;
            position: relative;
            border-bottom: 0.078vw solid #f1f1f1;
            margin-bottom: 1.2vw;

        }
         .modal_heaader .close-modal{
            top:0px;
            right:0px;
            line-height: 1;
         }
         .filter_modal_reset{
            border: 1px solid #f1f1f1;
            border-radius: 0.521vw;
            padding: 0.625vw 1.25vw;
         }
         .date_field_wraper{
            position: relative;
         }
         .date-input {
            position: relative;
            padding-right: 1.667vw; /* space for icon */
            font-family: Clash Display;
            color:#555;
            font-style: Medium;
           font-weight: 500;
            }

            /* Hide default icon */
            .date-input::-webkit-calendar-picker-indicator {
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            position: absolute;
            }

            /* Add your SVG as custom icon */
            .date-input {
            background-image: url('data:image/svg+xml;utf8,<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.66406 1.66602V4.16602" stroke="%23717171" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.3359 1.66602V4.16602" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.91406 7.57422H17.0807" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.5 7.08268V14.166C17.5 16.666 16.25 18.3327 13.3333 18.3327H6.66667C3.75 18.3327 2.5 16.666 2.5 14.166V7.08268C2.5 4.58268 3.75 2.91602 6.66667 2.91602H13.3333C16.25 2.91602 17.5 4.58268 17.5 7.08268Z" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.99803 11.4167H10.0055" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.91209 11.4167H6.91957" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.91209 13.9167H6.91957" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>');
            background-repeat: no-repeat;
            background-position: right 0.521vw center;
            background-size: 1.042vw;
            }

    </style>

    <!-- Filter Modal -->
    @if ($showFilterModal)
        <div class="modal filter-theme-modal" style="display: flex;">
            <div class="modal-content filter-modal">
                <div class="modal_heaader">
                <span class="close-modal" id="closeFilterModal"  wire:click="closeFilterModal" >
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                </span>
                <h3 class="mt-0">Filter</h3>
                </div>
              
                <label style='color:#717171;font-weight:500;'>Select Date</label>
                <div class=" row mt-3">
                    <div class='col-6'>
                        <span style="font-weight:500">From:</span>
                        <div class="date_field_wraper">
                            <input type="date" class="form-input mt-2 date-input" wire:model="fromDate">
                        </div>
                       
                    </div>
                    <div class='col-6'>
                        <span style="font-weight:500"> To:</span>
                        <div class="date_field_wraper">
                            <input type="date" class="form-input mt-2 date-input" wire:model="toDate">
                        </div>
                       
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
            gap: 0.417vw;
        }

        .user-avatar {
            display: flex;
            align-items: center;
            gap: 0.417vw;
        }

        .avatar-small {
            width: 1.667vw;
            height: 1.667vw;
            border-radius: 50%;
            object-fit: cover;
            border: 0.104vw solid #e0e0e0;
        }

        .users-count {
            font-size: 0.729vw;
            color: #666;
        }
    </style>


</div>
