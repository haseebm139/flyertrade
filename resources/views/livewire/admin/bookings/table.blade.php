<div>

    <livewire:admin.components.toolbar label="Booking" :show-add-button="false" />


    <table class="theme-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th class="sortable" data-column="0">Booking ID <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon">
                </th>
                <th class="sortable" data-column="4">Service category <img
                        src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>
                <th class="sortable">Date created
                    <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>
                <th class="sortable" data-column="1">Service User<img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>


                <th class="sortable" data-column="1">Provider<img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>
                <th class="sortable" data-column="2">Location <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>
                <th class="sortable" data-column="3">Amount Paid <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>


                <th class="sortable" data-column="6"> Status <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $booking)
                <tr>
                    <td><input type="checkbox" value="{{ $booking->id }}" wire:model.live="selected"></td>
                    <td>{{ $booking->booking_ref }}</td>
                    <td>{{ $booking->service->name ?? 'N/A' }}</td>
                    <td><span class="date">{{ $booking->created_at->format('M d, Y') }}</span>
                        <br>
                        <small class="time">{{ $booking->created_at->format('h:i A') }}</small>
                    </td>
                    <td>
                        <div class="user-info">
                            <img src="{{ $booking->customer->avatar1 ?? asset('assets/images/icons/person-one.png') }}"
                                alt="User" class="avatar">
                            <span class="user-theme-name">{{ $booking->customer->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="user-info">
                            <img src="{{ $booking->provider->avatar1 ?? asset('assets/images/icons/person-one.png') }}"
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
                            <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View"
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

    @if ($showFilterModal)
        <div class="modal filter-theme-modals" style="display: flex;">
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
                <label>Status</label>
                <select class="form-input" wire:model="status">
                    <option value="">Select status</option>
                    <option value="confirmed">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="awaiting_provider">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <div class="form-actions">
                    <button type="button" class="reset-btn" wire:click="resetFilters">Reset</button>
                    <button type="button" class="submit-btn" wire:click="applyFilters">Apply Now</button>
                </div>
            </div>
        </div>
    @endif

</div>
