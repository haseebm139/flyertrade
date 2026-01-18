<div>
    <livewire:admin.components.toolbar label="users" button_label="User" search_label="Search user" :active-filters="$activeFilters" />

    <div class="table-responsive">
        <table class="theme-table roles">
            <thead>
                <tr>
                    <th><input type="checkbox" wire:model.live="selectAll"></th>
                    <th class="sortable" wire:click="sortBy('user_type')" style="cursor: pointer;">
                        User type
                        <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon {{ $sortField === 'user_type' ? $sortDirection : '-' }}">
                    </th>
                    <th class="sortable" wire:click="sortBy('name')" style="cursor: pointer;">
                        User name
                        <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon {{ $sortField === 'name' ? $sortDirection : '-' }}">
                    </th>
                    <th class="sortable" wire:click="sortBy('address')" style="cursor: pointer;">
                        Home address
                        <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon {{ $sortField === 'address' ? $sortDirection : '-' }}">
                    </th>
                    <th class="sortable" wire:click="sortBy('phone')" style="cursor: pointer;">
                        Phone number
                        <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon {{ $sortField === 'phone' ? $sortDirection : '-' }}">
                    </th>
                    <th class="sortable" wire:click="sortBy('last_login_at')" style="cursor: pointer;">
                        Last login
                        <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon {{ $sortField === 'last_login_at' ? $sortDirection : '-' }}">
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr wire:key="user-row-{{ $user->id }}">
                        <td><input type="checkbox" value="{{ $user->id }}" wire:model.live="selected"></td>
                        <td style='font-weight:500; cursor: pointer;' wire:click="viewUser({{ $user->id }})">
                            {{ ucfirst($user->user_type ?? 'N/A') }}
                        </td>
                        <td style="cursor: pointer;" wire:click="viewUser({{ $user->id }})">
                            <div class="user-info">
                                <img src="{{ asset($user->avatar ?? 'assets/images/icons/person-one.svg') }}" alt="User">
                                <div>
                                    <p class="user-name" style='font-weight:500;'>{{ $user->name }}</p>
                                    <p class="user-email">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td style='font-weight:500; cursor: pointer;' wire:click="viewUser({{ $user->id }})">{{ Str::limit($user->address ?? 'N/A', 30) }}</td>
                        <td style='font-weight:500; cursor: pointer;' wire:click="viewUser({{ $user->id }})">{{ $user->phone ?? 'N/A' }}</td>
                        <td>
                            <span class="status last-seen" style="font-weight:400">
                                @if ($user->last_login_at)
                                    @php
                                        $lastLogin = $user->last_login_at;
                                        $now = now();
                                        
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
                                                $lastLoginText = str_replace([' minutes ago', ' minute ago'], ' min ago', $lastLoginText);
                                            }
                                        }
                                    @endphp
                                    {{ $lastLoginText }}
                                @else
                                    Never
                                @endif
                            </span>
                        </td>
                        <td class="viw-parent">
                            <div class="d-flex align-items-center gap-3">
                                <a href="javascript:void(0);" class="view-btn" wire:click="viewUser({{ $user->id }})">
                                    <img src="{{ asset('assets/images/icons/eye_icon.svg') }}" alt="View" class="eye-icon">
                                    View
                                </a>
                                {{-- <a href="javascript:void(0);" class="view-btn" wire:click="editUser({{ $user->id }})">
                                    <img src="{{ asset('assets/images/icons/edit.svg') }}" alt="Edit" class="eye-icon">
                                    Edit
                                </a> --}}
                                
                                <div style="position: relative;">
                                    <!-- ✅ Delete Modal -->
                                    <div id="deleteUserModal{{ $user->id }}" class="deleteModal"
                                        style="display: none; position: absolute; top: 2vw; right: 6vw; z-index: 1000;">
                                        <div class="delete-card">
                                            <div class="delete-card-header">
                                                <h3 class="delete-title">Delete User</h3>
                                                <span class="delete-close closeDeleteModal" data-id="{{ $user->id }}">&times;</span>
                                            </div>
                                            <p class="delete-text">Are you sure you want to delete user <strong>{{ $user->name }}</strong>?</p>
                                            <div class="delete-actions justify-content-start">
                                                <button class="confirm-delete-btn" wire:click="deleteUser({{ $user->id }})">Delete</button>
                                                <button class="cancel-delete-btn" data-id="{{ $user->id }}">Cancel</button>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="delete-btn showUserDeleteModal" data-id="{{ $user->id }}" style="border: 0 !important; background: none; padding: 0;">
                                        <img src="{{ asset('assets/images/icons/delete-icon-active.svg') }}" alt="Delete" class="eye-icon">
                                        <span style="font-size: 0.9vw; color: #064f3c; cursor: pointer; font-weight: 400;"> Delete </span>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links('vendor.pagination.custom') }}

    

    @if ($showFilterModal)
        <div class="modal filter-theme-modal" style="display: flex;">
            <div class="modal-content filter-modal">
                <div class="modal_heaader">
                    <span class="close-modal" wire:click="closeFilterModal">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75"
                                stroke="#717171" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    <h3 class="mt-0">Filter</h3>
                </div>

                <label style='color:#717171;font-weight:500;'>Select Date</label>
                <div class=" row mt-3">
                    <div class='col-6'>
                        <span style="font-weight:500">From:</span>
                        <div class="date_field_wraper">
                            <input type="date" class="form-input mt-2 date-input" wire:model="tempFromDate">
                        </div>
                    </div>
                    <div class='col-6'>
                        <span style="font-weight:500"> To:</span>
                        <div class="date_field_wraper">
                            <input type="date" class="form-input mt-2 date-input" wire:model="tempToDate">
                        </div>
                    </div>
                </div>

                <label style="color:#717171;font-weight:500;margin: 12px 0px 12px 0px;">Role</label>
                <x-custom-select-livewire name="tempRole" :options="[
                    ['value' => '', 'label' => 'Select role'],
                    ...$roles->map(fn($r) => ['value' => $r->name, 'label' => ucfirst($r->name)])->toArray()
                ]" placeholder="Select role" wireModel="tempRole" :value="$tempRole" class="form-input mt-2" />

                <div class="form-actions">
                    <button type="button" class="reset-btn filter_modal_reset" wire:click="resetFilters">Reset</button>
                    <button type="button" class="submit-btn" wire:click="applyFilters">Apply Now</button>
                </div>
            </div>
        </div>
    @endif

    <style>
        .modal_heaader {
            display: flex;
            position: relative;
            border-bottom: 1.50px solid #f1f1f1;
            margin-bottom: 1.2vw;
        }
        .modal_heaader .close-modal {
            top: 0px;
            right: 0px;
            line-height: 1;
        }
        .filter_modal_reset {
            border: 1px solid #f1f1f1;
            border-radius: 10px;
            padding: 12px 24px;
        }
        .date_field_wraper {
            position: relative;
        }
        .date-input {
            position: relative;
            padding-right: 35px;
            font-family: Clash Display;
            color: #555;
            font-style: Medium;
            font-weight: 500;
        }
        .date-input::-webkit-calendar-picker-indicator {
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            position: absolute;
        }
        .date-input {
            background-image: url('data:image/svg+xml;utf8,<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.66406 1.66602V4.16602" stroke="%23717171" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.3359 1.66602V4.16602" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.91406 7.57422H17.0807" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.5 7.08268V14.166C17.5 16.666 16.25 18.3327 13.3333 18.3327H6.66667C3.75 18.3327 2.5 16.666 2.5 14.166V7.08268C2.5 4.58268 3.75 2.91602 6.66667 2.91602H13.3333C16.25 2.91602 17.5 4.58268 17.5 7.08268Z" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.99803 11.4167H10.0055" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.91209 11.4167H6.91957" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.91209 13.9167H6.91957" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
        }

         
    </style>

     
</div>

@push('scripts')
    <script>
        $(document).on('click', '.showUserDeleteModal', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let id = $(this).data('id');
            $('.deleteModal').hide();
            $('#deleteUserModal' + id).show();
        })

        $(document).on('click', '.closeDeleteModal, .cancel-delete-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let id = $(this).data('id');
            $('#deleteUserModal' + id).hide();
        })

        $(document).on('click', function(e) {
            if (!$(e.target).closest('.deleteModal').length && !$(e.target).closest('.showUserDeleteModal').length) {
                $('.deleteModal').hide();
            }
        });
    </script>
@endpush
