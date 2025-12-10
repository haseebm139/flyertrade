<div>

    <livewire:admin.components.toolbar label="All Bookings" search_label="user" :show-add-button="false" />


    <table class="theme-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th class="sortable" data-column="0">Booking ID <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon">
                </th>
                <th class="sortable" data-column="4">Service category <img
                        src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                <th class="sortable">Date created
                    <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                </th>
                <th class="sortable" data-column="1">Service User<img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon"></th>


                <th class="sortable" data-column="1">Provider<img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon"></th>
                <th class="sortable" data-column="2">Location <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon"></th>
                <th class="sortable" data-column="3">Amount Paid <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon"></th>


                <th class="sortable" data-column="6"> Status <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon"></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $booking)
                <tr>
                    <td><input type="checkbox" value="{{ $booking->id }}" wire:model.live="selected"></td>
                    <td style="cursor: pointer" onclick="openBookingModal({{ $booking->id }})">{{ $booking->booking_ref }}</td>
                    <td>{{ $booking->service->name ?? 'N/A' }}</td>
                    <td><span class="date">{{ $booking->created_at->format('M d, Y') }}</span>
                        <br>
                        <small class="time">{{ $booking->created_at->format('h:i A') }}</small>
                    </td>
                    <td>
                        <div class="user-info">
                            <img src="{{ $booking->customer->avatar1 ?? asset('assets/images/icons/person-one.svg') }}"
                                alt="User" class="avatar">
                            <span class="user-theme-name">{{ $booking->customer->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="user-info">
                            <img src="{{ $booking->provider->avatar1 ?? asset('assets/images/icons/person-one.svg') }}"
                                alt="User" class="avatar">
                            <span class="user-theme-name">{{ $booking->provider->name ?? 'N/A' }}</span>
                        </div>
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
                            ];
                            $statusClass = $statusClasses[$booking->status] ?? 'inactive';
                        @endphp
                        <span class="status {{ $statusClass }}">
                            {{ ucfirst($statusClass) ?? '' }}
                        </span>
                    </td>
                    <td>
                        <button class="view-btn" onclick="openBookingModal({{ $booking->id }})">
                            <img src="{{ asset('assets/images/icons/eye_icon.svg') }}" alt="View"
                                class="action-icon">
                        </button>


                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No bookings found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <!-- End::Table -->

    {{ $data->links('vendor.pagination.custom') }}
    <style>
        .modal_heaader{
            display: flex;
            position: relative;
            border-bottom: 1.50px solid #f1f1f1;
            margin-bottom: 1.2vw;

        }
         .modal_heaader .close-modal{
            top:0px;
            right:0px;
            line-height: 1;
         }
         .filter_modal_reset{
            border: 1px solid #f1f1f1;
            border-radius: 10px;
            padding: 12px 24px;
         }
         .date_field_wraper{
            position: relative;
         }
         .date-input {
            position: relative;
            padding-right: 35px; /* space for icon */
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
            background-position: right 10px center;
            background-size: 20px;
            }

    </style>
    @if ($showFilterModal)
   <div class="modal filter-theme-modal" style="display: flex;">
            <div class="modal-content filter-modal">
                <div class="modal_heaader">
                <span class="close-modal"  wire:click="closeFilterModal" >
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
                    <button type="button" class="reset-btn filter_modal_reset" wire:click="resetFilters">Reset</button>
                    <button type="button" class="submit-btn" wire:click="applyFilters">Apply Now</button>
                </div>
            </div>
        </div>
    @endif

</div>
