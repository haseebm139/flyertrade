<div>
    <!-- Top Stat Cards -->
    <div class="combo-class">
        <div class="dashboard-card" wire:click="$set('status', '')" style="cursor: pointer">
            <div>
                <h6>Total dispute</h6>
                <h2>{{ $stats['total'] }}</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/dispute_icon.svg') }}" alt="User Icon">
            </div>
        </div>
        <div class="dashboard-card" wire:click="$set('status', 'resolved')" style="cursor: pointer">
            <div>
                <h6>Resolved dispute</h6>
                <h2>{{ $stats['resolved'] }}</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/dispute_icon.svg') }}" alt="User Icon">
            </div>
        </div>
        <div class="dashboard-card" wire:click="$set('status', 'unresolved')" style="cursor: pointer">
            <div>
                <h6>Unresolved dispute</h6>
                <h2>{{ $stats['unresolved'] }}</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/dispute_icon.svg') }}" alt="User Icon">
            </div>
        </div>
    </div>
    <br>

    <div class="container">
        <h1 class="page-title">All dispute</h1>
    </div>

    <livewire:admin.components.toolbar label="disputes" button_label="" search_label="user or booking ref"
        :active-filters="$activeFilters" :show-add-button="false" />

    <div class="table-responsive">
        <table class="theme-table">
            <thead>
                <tr>
                    <th><input type="checkbox" wire:model.live="selectAll"></th>
                    <th class="sortable" wire:click="sortBy('booking_id')" >Booking ID
                        <img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon {{ $sortField === 'booking_id' ? ($sortDirection === 'asc' ? '' : 'desc') : '' }}">
                    </th>
                    <th class="sortable" wire:click="sortBy('created_at')">Date created
                        <img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon {{ $sortField === 'created_at' ? ($sortDirection === 'asc' ? '' : 'desc') : '' }}">
                    </th>
                    <th class="sortable">Affected user<img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon"></th>
                    <th class="sortable">Service Type <img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon"></th>
                    <th class="sortable">Dispute issue <img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon"></th>
                    <th class="sortable" wire:click="sortBy('status')"> Status
                        <img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon {{ $sortField === 'status' ? ($sortDirection === 'asc' ? '' : 'desc') : '' }}">
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($disputes as $dispute)
                    <tr wire:key="dispute-{{ $dispute->id }}">
                        <td><input type="checkbox" value="{{ $dispute->id }}" wire:model.live="selected"></td>
                        <td wire:click="viewDispute({{ $dispute->id }})" style="cursor: pointer">


                            {{ $dispute->booking->booking_ref ?? 'N/A' }}

                        </td>
                        <td>
                            <span class="date">{{ $dispute->created_at->format('d M, Y') }}</span>
                            <br>
                            <small class="time">{{ $dispute->created_at->format('h:i a') }}</small>
                        </td>
                        <td>
                            <div class="user-info">
                                <img src="{{ asset($dispute->user->avatar ?? 'assets/images/icons/person-one.svg') }}"
                                    alt="User">
                                <div>
                                    <span class="user-theme-name ">{{ $dispute->user->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $dispute->booking->service->name ?? 'N/A' }}</td>
                        <td class="min-width-200">{{ Str::limit($dispute->message, 100) }}</td>
                        <td>
                            <div class="status-dropdown status-dropdown-resolve">
                                @can('Write Disputes')
                                    <span class="status active {{ $dispute->status }}" onclick="toggleDropdown(this)">
                                        {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                                        <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </span>
                                    <ul class="dropdown-menu" style="display: none;">
                                        @if ($dispute->status == 'resolved')
                                            <li style="font-weight: 400;" wire:click="setStatus({{ $dispute->id }}, 'unresolved')">Unresolved</li>
                                        @else
                                            <li style="font-weight: 400;" wire:click="setStatus({{ $dispute->id }}, 'resolved')">Resolved</li>
                                        @endif
                                    </ul>
                                @else
                                    <span class="status active {{ $dispute->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                                    </span>
                                @endcan
                            </div>
                        </td>
                        <td>
                            <div class="actions-dropdown">
                                <button class="actions-btn" onclick="toggleActions(this)"> <img
                                        src="{{ asset('assets/images/icons/three_dots.svg') }}"
                                        class="dots-img "></button>
                                <div class="actions-menu" style="display: none; right: 0px !important;">
                                    {{-- <a wire:click="viewDispute({{ $dispute->id }})">View details</a> --}}
                                    @can('Write Disputes')
                                        @if ($dispute->status === 'resolved')
                                            <a style="cursor: pointer" wire:click="setStatus({{ $dispute->id }}, 'unresolved')">Mark as
                                                unresolved</a>
                                        @else
                                            <a style="cursor: pointer" wire:click="setStatus({{ $dispute->id }}, 'resolved')">Resolve Dispute</a>
                                        @endif
                                    @endcan

                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">No disputes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $disputes->links('vendor.pagination.custom') }}
    </div>

    <!-- Filter Modal -->
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
                <div class="row mt-3">
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

                <label style="color:#717171;font-weight:500;margin: 12px 0px 12px 0px;">Status</label>
                <x-custom-select-livewire name="tempStatus" :options="[
                    ['value' => '', 'label' => 'Select status'],
                    ['value' => 'resolved', 'label' => 'Resolved'],
                    ['value' => 'unresolved', 'label' => 'Unresolved'],
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

    @if ($showDisputeModal && $selectedDispute)
        <div id="view-booking" class="view-booking-modal" style="display: flex;">
            <div class="view-booking-content">
                <div class="modal-header" style="margin-bottom:1.563vw">
                    <h2 style="font-size:1.146vw;font-weight: 600;line-height:1;">Booking details</h2>
                    <div class="header-actions">
                        <span class="close-btn" style="line-height:1;" wire:click="closeDisputeModal">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75"
                                    stroke="#717171" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="service-header-icons">
                    <h4 style="font-size:0.938vw;font-weight: 500; letter-spacing: -0.04em;">Service details</h4>
                    <h5 wire:click="downloadDisputeDetails" style="cursor: pointer;">
                        <img src="{{ asset('assets/images/icons/download.svg') }}" alt="Download"
                            class="download-icon">
                        <small style="color:grey;font-size:0.938vw;">Download </small>
                    </h5>
                </div>

                <div class="modal-section">
                    <div class="details-grid">
                        <div>Booking ID</div>
                        <div style="cursor:pointer">{{ $selectedDispute->booking->booking_ref ?? 'N/A' }}</div>
                        <div>Date</div>
                        <div>{{ optional($selectedDispute->booking?->created_at)->format('d M, Y') ?? 'N/A' }}</div>
                        <div>Time</div>
                        <div>{{ optional($selectedDispute->booking?->created_at)->format('h:i A') ?? 'N/A' }}</div>
                        <div>Duration</div>
                        <div>{{ $selectedDispute->booking->duration ?? '-' }}</div>
                        <div>Location</div>
                        <div>{{ $selectedDispute->booking->booking_address ?? '-' }}</div>
                        <div>Service type</div>
                        <div>{{ $selectedDispute->booking->service->name ?? '-' }}</div>
                        <div>Service cost</div>
                        <div>${{ number_format($selectedDispute->booking->total_price ?? 0, 2) }}</div>
                        <div>Status</div>
                        <div>
                            @php
                                $statusClasses = [
                                    'awaiting_provider' => 'pending',
                                    'confirmed' => 'active',
                                    'in_progress' => 'active',
                                    'completed' => 'completed',
                                    'cancelled' => 'cancelled',
                                    'rejected' => 'cancelled',
                                    'reschedule_pending_customer' => 'pending',
                                ];
                                $statusClass = $statusClasses[$selectedDispute->booking->status] ?? 'inactive';
                            @endphp
                            <span class="status {{ $statusClass }}"
                                style="border-radius: 1.042vw; font-weight: 500;  display: inline-block;">
                                {{ str_replace('_', ' ', ucfirst($selectedDispute->booking->status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="modal-section">
                    <br>
                    <h4 style="font-size:0.938vw;font-weight: 500; letter-spacing: -0.04em;">Users details</h4>
                    <div class="details-grid">
                        <div>Service provider</div>
                        <div class="text-end">{{ $selectedDispute->booking->provider->name ?? '-' }}</div>
                        <div>Service user</div>
                        <div class="text-end">{{ $selectedDispute->booking->customer->name ?? '-' }}</div>
                    </div>
                </div>

                <div class="modal-section">
                    <br>
                    <h4 style="font-size:0.938vw;font-weight: 500; letter-spacing: -0.04em;">Dispute issue</h4>
                    <div class="dispute-text">
                        <div style="color:#717171;">
                            {{ $selectedDispute->message ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        
        .theme-table td,
        .date,
        span.desf {
            font-weight: 500;
        }

        @media (max-width:600px) {
            .booking_id {
                min-width: 100px;
            }
        }
   
        .desc {
            transform: rotate(180deg);
        }

        .sort-icon {
            transition: transform 0.3s ease;
        }

        .resolved {
            color: #17A55A;
            border-color: #17A55A;
            background-color: rgba(23, 165, 90, 0.1);
        }

        .unresolved {

            color: #dc3545;
            border-color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
        }
    </style>

    <script>
        function toggleDropdown(el) {
            const menu = el.nextElementSibling;
            const isOpen = menu.style.display === 'block';
            document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
            document.querySelectorAll('.actions-menu').forEach(m => m.style.display = 'none');
            menu.style.display = isOpen ? 'none' : 'block';
            event.stopPropagation();
        }

        function toggleActions(el) {
            const menu = el.nextElementSibling;
            const isOpen = menu.style.display === 'block';
            document.querySelectorAll('.actions-menu').forEach(m => m.style.display = 'none');
            document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
            menu.style.display = isOpen ? 'none' : 'block';
            event.stopPropagation();
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.status-dropdown') && !e.target.closest('.actions-dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
                document.querySelectorAll('.actions-menu').forEach(m => m.style.display = 'none');
            }
        });
    </script>
</div>
