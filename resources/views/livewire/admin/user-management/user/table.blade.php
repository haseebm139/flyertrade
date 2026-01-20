<div>
    <livewire:admin.components.toolbar label="service users" :button_label="auth()->user()->can('Create Users') ? 'Users' : ''" search_label="user" :active-filters="$activeFilters" />

    <!-- Users Table -->
    <div class="table-responsive">
        <table class="theme-table">
            <thead>
                <tr>
                    <th><input type="checkbox" wire:model.live="selectAll"></th>
                    <th class="sortable" data-column="0">Customer ID <img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon" wire:click="sortBy('id')"
                            {{ $sortField === 'id' ? $sortDirection : '-' }}>
                    </th>
                    <th class="sortable" data-column="1">Customer Name <img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"
                            wire:click="sortBy('name')" {{ $sortField === 'name' ? $sortDirection : '-' }}>
                    </th>
                    <th class="sortable" data-column="2">Home Address <img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"
                            wire:click="sortBy('address')" {{ $sortField === 'address' ? $sortDirection : '-' }}></th>
                    <th class="sortable" data-column="3">Phone Number <img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"
                            wire:click="sortBy('phone')" {{ $sortField === 'phone' ? $sortDirection : '-' }}></th>
                    <th class="sortable" data-column="4">Status <img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon" wire:click="sortBy('status')"
                            {{ $sortField === 'status' ? $sortDirection : '-' }}></th>

                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td><input type="checkbox" value="{{ $item->id }}" wire:model.live="selected"></td>
                    <td>{{ $item->id }}</td>
                    <td style="padding: 0;">
                        <a href="{{ route('user-management.service.users.view', ['id' => $item->id]) }}" class="user-info-link" style="padding: 0.75rem 1rem;">
                            <div class="user-info">
                                <img src="{{ asset($item->avatar ?? 'assets/images/icons/person-one.svg') }}"
                                    alt="avatar">
                                <div>
                                    <p class="user-name">{{ $item->name ?? '-' }}</p>
                                    <p class="user-email">{{ $item->email ?? '-' }}</p>
                                </div>
                            </div>
                        </a>
                    </td>
                        <td>
                            @php
                                $addressParts = array_filter([
                                    $item->address ?? null,
                                    $item->city ?? null,
                                    $item->state ?? null,
                                    $item->country ?? null,
                                ]);
                                $fullAddress = !empty($addressParts) ? implode(', ', $addressParts) : '-';
                            @endphp
                            {{ $fullAddress }}
                    </td>
                    <td>{{ $item->phone ?? '-' }}</td>
                    <td><span
                            class="status {{ $item->status == 'active' ? 'active' : 'inactive' }}">{{ ucfirst($item->status) ?? '' }}</span>
                    </td>
                    <td style="position:relative">
                        <div class="actions-dropdown">
                            @if(auth()->user()->can('Read Users') || auth()->user()->can('Write Users') || auth()->user()->can('Delete Users'))
                                <button class="actions-btn"> <img
                                        src="{{ asset('assets/images/icons/three_dots.svg') }}"
                                    class="dots-img "></button>
                                <div class="actions-menu" style="display: none;">
                                    <a href="{{ route('user-management.service.users.view', ['id' => $item->id]) }}"><img
                                            src="{{ asset('assets/images/icons/eye.svg') }}" alt="View User"
                                            class="w-5 h-5"> View user</a>
                                    @can('Write Users')
                                        <a href="#" wire:click="edit({{ $item->id }}) "><img
                                                src="{{ asset('assets/images/icons/edit-icon.svg') }}" alt="Edit User"
                                                class="w-5 h-5"> Edit user</a>
                                    @endcan
                                    @can('Delete Users')
                                        <a href="#" class="showDeleteModal___" data-id="{{ $item->id }}"><img
                                                src="{{ asset('assets/images/icons/delete-icon.svg') }}" alt="Delete User"
                                                class="w-5 h-5"> Delete user</a>
                                    @endcan
                                </div>
                            @endif
                        </div>
                        
                        @can('Delete Users')
                            <div id="globalDeleteModal__{{ $item->id }}" class="deleteModal"
                                style="display: none;position:absolute;    top: 2.5vw; right: 1vw;">
                                <div class="delete-card">
                                    <div class="delete-card-header">
                                        <h3 class="delete-title">Delete Service User?</h3>
                                        <span class="delete-close closeDeleteModal"
                                            data-id="{{ $item->id }}">&times;</span>
                                    </div>
                                    <p class="delete-text">Are you sure you want to delete this service user?</p>
                                    <div class="delete-actions justify-content-start">
                                        <button class="confirm-delete-btn" wire:click="delete({{ $item->id }})" data-id="{{ $item->id }}">Delete</button>
                                        <button class="cancel-delete-btn" data-id="{{ $item->id }}">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">No Service User found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


    {{ $data->links('vendor.pagination.custom') }}

    <style>
        .user-info-link {
            text-decoration: none;
            color: inherit;
            display: block;
            transition: background-color 0.2s;
        }
         
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
            /* space for icon */
            font-family: Clash Display;
            color: #555;
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
            background-position: right 10px center;
            background-size: 20px;
        }
    </style>

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
            <label style="color:#717171;font-weight:500">Status</label>
                <x-custom-select-livewire name="tempStatus" :options="[
                    ['value' => '', 'label' => 'Select status'],
                    ['value' => 'active', 'label' => 'Active'],
                    ['value' => 'inactive', 'label' => 'Inactive'],
                ]" placeholder="Select status"
                    wireModel="tempStatus" :value="$tempStatus" class="form-input mt-2" />
            <div class="form-actions">
                    <button type="button" class="reset-btn filter_modal_reset"
                        wire:click="resetFilters">Reset</button>
                <button type="button" class="submit-btn" wire:click="applyFilters">Apply Now</button>
            </div>
        </div>
    </div>
    @endif
</div>
<script>
    // Actions dropdown - Works with Livewire updates
    (function() {
        // Single event delegation handler (works for all buttons, even after Livewire updates)
        document.addEventListener('click', function(e) {
            // Check if clicked on actions button
            const btn = e.target.closest('.actions-btn');
            if (btn) {
                e.stopPropagation();
                e.preventDefault();
                
                const dropdown = btn.closest('.actions-dropdown');
                if (!dropdown) return false;
                
                const menu = dropdown.querySelector('.actions-menu');
                if (!menu) return false;
                
                const isActive = dropdown.classList.contains('active');
                
                // Close all other menus
                document.querySelectorAll('.actions-dropdown').forEach(d => {
                    if (d !== dropdown) {
                        d.classList.remove('active');
                        const otherMenu = d.querySelector('.actions-menu');
                        if (otherMenu) otherMenu.style.display = 'none';
                    }
                });
                
                // Toggle current menu
                if (isActive) {
                    dropdown.classList.remove('active');
                    menu.style.display = 'none';
                } else {
                    dropdown.classList.add('active');
                    menu.style.display = 'block';
                }
                
                return false;
            }
            
            // Close dropdowns when clicking outside
            if (!e.target.closest('.actions-dropdown') && !e.target.closest('.actions-menu')) {
                document.querySelectorAll('.actions-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('active');
                    const menu = dropdown.querySelector('.actions-menu');
                    if (menu) menu.style.display = 'none';
                });
            }
        }, true); // Use capture phase to run before main.js
        
        // Ensure menus stay hidden after Livewire updates
        if (typeof Livewire !== 'undefined') {
            document.addEventListener('livewire:init', function() {
                Livewire.hook('morph.updated', () => {
                    // Close all menus after Livewire update
                    setTimeout(() => {
                        document.querySelectorAll('.actions-dropdown').forEach(dropdown => {
                            dropdown.classList.remove('active');
                            const menu = dropdown.querySelector('.actions-menu');
                            if (menu) menu.style.display = 'none';
                        });
                    }, 10);
                });
            });
        }
    })();

    // Delete modal handlers
    $(document).on('click', '.showDeleteModal___', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const id = $(this).attr('data-id');
        if (!id) return;
        
        // Close actions dropdown
        $(this).closest('.actions-dropdown').removeClass('active');
        $(this).closest('.actions-dropdown').find('.actions-menu').hide();
        
        // Hide any other open modals first
        $('.deleteModal').hide();
        
        // Show the specific modal
        const modal = $('#globalDeleteModal__' + id);
        if (modal.length) {
            modal.css('display', 'flex'); // Use flex to match original design if needed, or just show()
        }
    });
    
    $(document).on('click', '.closeDeleteModal, .cancel-delete-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const id = $(this).attr('data-id');
        $('#globalDeleteModal__' + id).hide();
    });
    
    $(document).on('click', '.confirm-delete-btn', function(e) {
        // We don't call preventDefault here to allow wire:click to work
        // but we can hide the modal
        const id = $(this).attr('data-id');
        $('#globalDeleteModal__' + id).hide();
    });
    
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.showDeleteModal___, .deleteModal').length) {
            $('.deleteModal').hide();
        }
    });
</script>
