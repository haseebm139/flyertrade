<div>

    <livewire:admin.components.toolbar label="All Bookings" search_label="Booking ID, Customer, Provider"
        :show-add-button="false" :activeFilters="$activeFilters" />
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
    </style>
    <div class="table-responsive">
        <table class="theme-table">
            <thead>
                <tr>
                    <th><input type="checkbox" wire:model.live="selectAll"></th>
                    <th class="sortable booking_id" wire:click="sortBy('booking_ref')">Booking ID <img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                    </th>
                    <th class="sortable" wire:click="sortBy('service_id')">Service category <img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                    <th class="sortable" wire:click="sortBy('created_at')">Date created
                        <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                    </th>
                    <th class="sortable">Service User<img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon"></th>


                    <th class="sortable">Provider<img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon"></th>
                    <th class="sortable" wire:click="sortBy('booking_address')">Location <img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                    <th class="sortable" wire:click="sortBy('total_price')">Amount Paid <img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>


                    <th class="sortable" wire:click="sortBy('status')"> Status <img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $booking)
                    <tr>
                        <td><input type="checkbox" value="{{ $booking->id }}" wire:model.live="selected"></td>
                        <td style="cursor: pointer" wire:click="viewBooking({{ $booking->id }})">
                            {{ $booking->booking_ref }}</td>
                        <td>{{ $booking->service->name ?? 'N/A' }}</td>
                        <td><span class="date">{{ $booking->created_at->format('M d, Y') }}</span>
                            <br>
                            <small class="time">{{ $booking->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <a href="{{ $booking->customer_id ? route('user-management.service.users.view', $booking->customer_id) : '#' }}"
                                class="user-info" style="text-decoration: none; color: inherit; display: flex;">
                                <img src="{{ $booking->customer->avatar ? asset($booking->customer->avatar) : asset('assets/images/icons/person-one.svg') }}"
                                    alt="User" class="avatar">
                                <span class="user-theme-name">{{ $booking->customer->name ?? 'N/A' }}</span>
                            </a>
                        </td>
                        <td>
                            <a href="{{ $booking->provider_id ? route('user-management.service.providers.view', $booking->provider_id) : '#' }}"
                                class="user-info" style="text-decoration: none; color: inherit; display: flex;">
                                <img src="{{ $booking->provider->avatar ? asset($booking->provider->avatar) : asset('assets/images/icons/person-one.svg') }}"
                                    alt="User" class="avatar">
                                <span class="user-theme-name">{{ $booking->provider->name ?? 'N/A' }}</span>
                            </a>
                        </td>
                        <td>
                            <span class="desf">
                                {{ Str::limit($booking->booking_address, 30) }}
                            </span>
                        </td>
                        <td>${{ number_format($booking->total_price, 2) }}</td>
                        <td>
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
                                $statusClass = $statusClasses[$booking->status] ?? 'inactive';
                            @endphp
                            <span class="status {{ $statusClass }}">
                                {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                            </span>
                        </td>
                        <td>
                            <button class="view-btn" wire:click="viewBooking({{ $booking->id }})">
                                View <img src="{{ asset('assets/images/icons/eye_icon.svg') }}" alt="View"
                                    class="action-icon">
                            </button>


                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">No bookings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- End::Table -->

    {{ $data->links('vendor.pagination.custom') }}

    @if ($showBookingModal && $selectedBooking)
        <div id="view-booking" class="view-booking-modal add-user-modal" style="display: flex;"
            wire:click.self="closeBookingModal">
            <div class="view-booking-content">
                <div class="modal-header" style="margin-bottom:1.563vw">
                    <h2 style="font-size:1.146vw;font-weight: 600;line-height:1;">Booking details</h2>
                    <div class="header-actions">
                        <span class="close-btn" style="line-height:1;" wire:click="closeBookingModal">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75"
                                    stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="service-header-icons">
                    <h4 style="font-size:0.938vw;font-weight: 500; letter-spacing: -0.04em;">Service details</h4>
                    <h5 style="cursor: pointer;" wire:click="downloadBookingDetails({{ $selectedBooking->id }})">
                        <img src="{{ asset('assets/images/icons/download.svg') }}" alt="Download"
                            class="download-icon">
                        <small style="color:grey;font-size:0.938vw;">Download </small>
                    </h5>
                </div>

                <div class="modal-section">
                    <div class="details-grid">
                        <div>Booking ID</div>
                        <div>{{ $selectedBooking->booking_ref }}</div>
                        <div>Date</div>
                        <div>{{ $selectedBooking->created_at->format('d M, Y') }}</div>
                        <div>Time</div>
                        <div>{{ $selectedBooking->created_at->format('h:i A') }}</div>
                        <div>Duration</div>
                        <div>{{ $selectedBooking->working_hours }} Hours</div>
                        <div>Location</div>
                        <div>{{ $selectedBooking->booking_address ?? '-' }}</div>
                        <div>Service type</div>
                        <div>{{ $selectedBooking->service->name ?? '-' }}</div>
                        <div>Service cost</div>
                        <div>${{ number_format($selectedBooking->total_price, 2) }}</div>
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
                                $statusClass = $statusClasses[$selectedBooking->status] ?? 'inactive';
                            @endphp
                            <span class="status {{ $statusClass }}"
                                style="border-radius: 1.042vw; font-weight: 500; padding: 0.5vw 1vw; display: inline-block;">
                                {{ str_replace('_', ' ', ucfirst($selectedBooking->status)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="modal-section">
                    <br>
                    <h4 style="font-size:0.938vw;font-weight: 500; letter-spacing: -0.04em;">Users details</h4>
                    <div class="details-grid">
                        <div>Service provider</div>
                        <div class="text-end">
                            <a
                                href="{{ $selectedBooking->provider_id ? route('user-management.service.providers.view', $selectedBooking->provider_id) : '#' }}">
                                {{ $selectedBooking->provider->name ?? '-' }}
                            </a>
                        </div>
                        <div>Service user</div>
                        <div class="text-end">
                            <a
                                href="{{ $selectedBooking->customer_id ? route('user-management.service.users.view', $selectedBooking->customer_id) : '#' }}">
                                {{ $selectedBooking->customer->name ?? '-' }}
                            </a>
                        </div>
                    </div>
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
                {{-- <label style="color:#717171;font-weight:500;margin: 12px 0px 12px 0px;">Status</label>
                <x-custom-select-livewire name="tempStatus" :options="[
                    ['value' => '', 'label' => 'Select status'],
                    ['value' => 'awaiting_provider', 'label' => 'Pending'],
                    ['value' => 'confirmed', 'label' => 'Active'],
                    ['value' => 'in_progress', 'label' => 'In Progress'],
                    ['value' => 'completed', 'label' => 'Completed'],
                    ['value' => 'cancelled', 'label' => 'Cancelled'],
                    ['value' => 'rejected', 'label' => 'Rejected'],
                    ['value' => 'reschedule_pending_customer', 'label' => 'Reschedule Pending'],
                ]" placeholder="Select status" wireModel="tempStatus" :value="$tempStatus"
                    class="form-input mt-2" />

                <label style="color:#717171;font-weight:500;margin: 12px 0px 12px 0px;">Service Category</label>
                <input type="text" class="form-input mt-2" placeholder="Search service..." wire:model="tempServiceFilter">

                <label style="color:#717171;font-weight:500;margin: 12px 0px 12px 0px;">Service User</label>
                <input type="text" class="form-input mt-2" placeholder="Search user..." wire:model="tempCustomerFilter">

                <label style="color:#717171;font-weight:500;margin: 12px 0px 12px 0px;">Service Provider</label>
                <input type="text" class="form-input mt-2" placeholder="Search provider..." wire:model="tempProviderFilter"> --}}

                <div class="form-actions">
                    <button type="button" class="reset-btn filter_modal_reset"
                        wire:click="resetFilters">Reset</button>
                    <button type="button" class="submit-btn" wire:click="applyFilters">Apply Now</button>
                </div>
            </div>
        </div>
    @endif

</div>
