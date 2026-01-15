<div>
    <style>
        #addUserModal label {
            margin-top: 1vw;
            margin-bottom: 0.2vw;
        }


        .deleteModal {
            /* display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        justify-content: center;
        align-items: center;
        z-index: 999; */
            position: absolute;
            top: -5px;
            right: -5px;
        }

        .delete-card {
            /* background: #fff;
        padding: 20px;
        border-radius: 10px;
        min-width: 300px; */
        }

        /* Dynamic Status Styles */
        .status {
            padding: 0.3vw 1vw;
            border-radius: 1.0416vw;
            font-size: 0.9vw;
            text-align: center;
            width: fit-content;
            font-weight: 500;
            border: 1px solid transparent;
            text-transform: capitalize;
        }

        .status.completed,
        .status.active {
            color: #17A55A;
            border-color: #17A55A;
            background-color: rgba(23, 165, 90, 0.1);
        }

        .status.pending,
        .status.awaiting_provider,
        .status.reschedule_pending_provider,
        .status.reschedule_pending_customer {
            color: #EFC100;
            border-color: #EFC100;
            background-color: rgba(239, 193, 0, 0.1);
        }

        .status.cancelled,
        .status.rejected,
        .status.refunded,
        .status.inactive {
            color: #dc3545;
            border-color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
        }

        .status.confirmed,
        .status.in_progress {
            color: #007bff;
            border-color: #007bff;
            background-color: rgba(0, 123, 255, 0.1);
        }
    </style>

    <div class="users-toolbar">
        <nav class="breadcrumb">
            <a href="{{ route('user-management.service.users.index') }}">Service User</a>
            <span class="breadcrumb-separator"><i class="fa-solid fa-chevron-right"></i></span>
            <span class="breadcrumb-current">{{ ucwords($user->name) }}</span>
        </nav>
    </div>

    <!-- Toolbar -->
    <div class="users-toolbar">
        <div class="toolbar-left" style="position:relative">
            <button class="reset-btn" wire:click="$set('showResetModal', true)" style="background-color: #fff;">Reset
                password</button>

            @if ($showResetModal)
                <div class="deleteModal" style="display: flex;">
                    <div class="delete-card">
                        <div class="delete-card-header">
                            <h3 class="delete-title">Reset password</h3>
                            <span class="delete-close" wire:click="$set('showResetModal', false)">&times;</span>
                        </div>
                        <p class="delete-text">Are you sure you want to reset this user password?</p>
                        <div class="delete-actions justify-content-start">
                            <button class="confirm-delete-btn" wire:click="resetPassword"
                                onclick="this.closest('.deleteModal').style.display='none'">Reset</button>
                            <button class="cancel-delete-btn" wire:click="$set('showResetModal', false)">Cancel</button>
                        </div>
                    </div>
                </div>
            @endif


            <button class="edit-btn" wire:click="$dispatch('addItemRequested', { id: {{ $user->id }} })">
                Edit user
                &nbsp;
                &nbsp;
                <span class="download-icon"><img src="{{ asset('assets/images/icons/edit.svg') }}" alt=""
                        class="icons-btn"></span>
            </button>

            <button class="delete-btn" wire:click="$set('showDeleteModal', true)">
                Delete user
                &nbsp;
                <span class="download-icon"><img src="{{ asset('assets/images/icons/trash.svg') }}" alt=""
                        class="icons-btn"></span>
            </button>

            @if ($showDeleteModal)
                <div id="globalDeleteModal" class="deleteModal" style="display: flex;">
                    <div class="delete-card">
                        <div class="delete-card-header">
                            <h3 class="delete-title">Delete Service User?</h3>
                            <span class="delete-close" wire:click="$set('showDeleteModal', false)">&times;</span>
                        </div>
                        <p class="delete-text">Are you sure you want to delete this service user?</p>
                        <div class="delete-actions justify-content-start">
                            <button class="confirm-delete-btn" wire:click="deleteUser"
                                onclick="this.closest('.deleteModal').style.display='none'">Delete</button>
                            <button class="cancel-delete-btn" wire:click="$set('showDeleteModal', false)">Cancel</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="toolbar-right">
            <!-- ✅ User Profile -->
            <div class="user-profile">
                <img src="{{ $user->avatar ? asset($user->avatar) : asset('assets/images/avatar/default.png') }}" alt="User"
                    class="user-profile-img">
                <div class="user-infos">
                    <h4 class="user-name-user">{{ ucwords($user->name) }}</h4>
                    <p class="user-role">{{ $user->user_type === 'customer' ? 'Service user' : ucfirst($user->user_type ?? 'Service user') }}</p>
                </div>

                <!-- ✅ Status Dropdown -->
                <div class="status-dropdown">
                    <button class="status-btn {{ ($user->status ?? 'active') === 'active' ? 'active' : 'inactive' }}">
                        {{ ucfirst($user->status ?? 'Active') }} <i class="fa-solid fa-chevron-down"></i>
                    </button>
                    <div class="status-menu">
                        <div class="status-option {{ ($user->status ?? 'active') === 'active' ? 'active' : '' }}"
                            wire:click="toggleStatus">
                            {{ ($user->status ?? 'active') === 'active' ? 'Suspend' : 'Activate' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- tabs-section -->
    <div class="tabs-section">
        <div class="tab {{ $activeTab === 'details' ? 'active' : '' }}" wire:click="setTab('details')">Personal details
        </div>
        <div class="tab {{ $activeTab === 'history' ? 'active' : '' }}" wire:click="setTab('history')">Booking history
        </div>
    </div>

    @if ($activeTab === 'details')
        <div id="details" class="tab-content active" style="border: 0.1vw solid #ddd; border-radius: 2vw;">
            <h3 style="font-size:1.4vw;" class="profile-heading">Profile details</h3>
            <div class="profile-details">
                <p><span>Name</span> {{ ucwords($user->name) }}</p>
                <p><span>Email address</span> {{ $user->email }}</p>
                <p><span>Phone number</span> {{ $user->phone ?? '-' }}</p>
                <p><span>State of residence</span> {{ $user->state ?? '-' }}</p>
                <p><span>Home address</span> {{ $user->address ?? '-' }}</p>
                <p><span>Overall rating</span>
                    <span class="stars" style="color:#393939;">
                        @php $rating = $user->overall_rating; @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.78313 13.3332C4.8748 12.9248 4.70813 12.3415 4.41647 12.0498L2.39147 10.0248C1.75813 9.3915 1.50813 8.7165 1.69147 8.13317C1.88313 7.54984 2.4748 7.14984 3.35813 6.99984L5.95813 6.5665C6.33313 6.49984 6.79147 6.1665 6.96647 5.82484L8.3998 2.94984C8.81647 2.12484 9.38313 1.6665 9.9998 1.6665C10.6165 1.6665 11.1831 2.12484 11.5998 2.94984L13.0331 5.82484C13.1415 6.0415 13.3665 6.24984 13.6081 6.3915L4.63313 15.3665C4.51647 15.4832 4.31647 15.3748 4.3498 15.2082L4.78313 13.3332Z"
                                    fill="{{ $i <= $rating ? '#EFC100' : '#D1D1D1' }}" />
                                <path
                                    d="M15.5859 12.0501C15.2859 12.3501 15.1193 12.9251 15.2193 13.3334L15.7943 15.8417C16.0359 16.8834 15.8859 17.6667 15.3693 18.0417C15.1609 18.1917 14.9109 18.2667 14.6193 18.2667C14.1943 18.2667 13.6943 18.1084 13.1443 17.7834L10.7026 16.3334C10.3193 16.1084 9.68594 16.1084 9.3026 16.3334L6.86094 17.7834C5.93594 18.3251 5.14427 18.4167 4.63594 18.0417C4.44427 17.9001 4.3026 17.7084 4.21094 17.4584L14.3443 7.32508C14.7276 6.94174 15.2693 6.76674 15.7943 6.85841L16.6359 7.00008C17.5193 7.15008 18.1109 7.55008 18.3026 8.13341C18.4859 8.71674 18.2359 9.39174 17.6026 10.0251L15.5859 12.0501Z"
                                    fill="{{ $i <= $rating ? '#EFC100' : '#D1D1D1' }}" />
                            </svg>
                        @endfor
                        ({{ $rating }})
                    </span>
                </p>
                <p><span>Referrals</span> {{ $user->referrals_count ?? 0 }}</p>
            </div>
        </div>
    @endif

    @if ($activeTab === 'history')
        <div id="history" class="tab-content active">
            <div class=" combo-class">
                <div class="dashboard-card">
                    <div>
                        <h6>Amount Spent</h6>
                        <h2>{{ number_format($user->total_spent_sum ?? 0, 2) }}</h2>
                    </div>
                    <div class="icon-box">
                        <img src="{{ asset('assets/images/icons/payout-icon.svg') }}" alt="User Icon">
                    </div>
                </div>
                <div class="dashboard-card">
                    <div>
                        <h6>Total Booking</h6>
                        <h2>{{ $user->total_bookings_count }}</h2>
                    </div>
                    <div class="icon-box">
                        <img src="{{ asset('assets/images/icons/active_booking.svg') }}" alt="User Icon">
                    </div>
                </div>
                <div class="dashboard-card">
                    <div>
                        <h6>Completed Booking</h6>
                        <h2>{{ $user->completed_bookings_count }}</h2>
                    </div>
                    <div class="icon-box">
                        <img src="{{ asset('assets/images/icons/active_booking.svg') }}" alt="User Icon">
                    </div>
                </div>
                <div class="dashboard-card">
                    <div>
                        <h6>Cancelled Booking</h6>
                        <h2>{{ $user->cancelled_bookings_count }}</h2>
                    </div>
                    <div class="icon-box">
                        <img src="{{ asset('assets/images/icons/active_booking.svg') }}" alt="User Icon">
                    </div>
                </div>
            </div>
            <br>

            <table class="theme-table">
                <thead>
                    <tr>
                         
                        <th>Booking ID</th>
                        <th>Date created</th>
                        <th>Provider</th>
                        <th>Location</th>
                        <th>Service Category</th>
                        <th>Amount Paid</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                             
                            <td>{{ $booking->booking_ref }}</td>
                            <td><span class="date">{{ $booking->created_at->format('d M, Y') }}</span>
                                <br>
                                <small class="time">{{ $booking->created_at->format('h:ia') }}</small>
                            </td>
                            <td>
                                <p class="user-name">{{ $booking->provider->name ?? '-' }}</p>
                            </td>
                            <td>{{ $booking->booking_address ?? '-' }}</td>
                            <td>
                                <p class="user-name">{{ $booking->service->name ?? '-' }}</p>
                            </td>
                            <td>${{ number_format($booking->total_price, 2) }}</td>
                            <td>{{ $booking->duration ?? '-' }}</td>
                            <td>
                                <div class="status {{ strtolower($booking->status) }}">
                                    {{ str_replace('_', ' ', $booking->status) }}
                                </div>
                            </td>
                            <td class="viw-parent">
                                <button class="view-btn"
                                    wire:click="viewBooking({{ $booking->id }})">
                                    <img src="{{ asset('assets/images/icons/eye_icon.svg') }}" alt="View"
                                        class="eye-icon">
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $bookings->links('vendor.pagination.custom') }}
        </div>
    @endif

    @if ($showBookingModal && $selectedBooking)
        <div id="view-booking" class="view-booking-modal" style="display: flex;">
            <div class="view-booking-content">
                <div class="modal-header" style="margin-bottom:1.563vw">
                    <h2 style="font-size:1.146vw;font-weight: 600;line-height:1;">Booking details</h2>
                    <div class="header-actions">
                        <span class="close-btn" style="line-height:1;" wire:click="closeBookingModal">
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
                    <h5> <img src="{{ asset('assets/images/icons/download.svg') }}" alt="Download"
                            class="download-icon"> <small style="color:grey;font-size:0.938vw;">Download </small></h5>
                </div>

                <div class="modal-section">
                    <div class="details-grid">
                        <div>Booking ID</div>
                        <div style="cursor:pointer">{{ $selectedBooking->booking_ref }}</div>
                        <div>Date</div>
                        <div>{{ $selectedBooking->created_at->format('d M, Y') }}</div>
                        <div>Time</div>
                        <div>{{ $selectedBooking->created_at->format('h:i A') }}</div>
                        <div>Duration</div>
                        <div>{{ $selectedBooking->duration ?? '-' }}</div>
                        <div>Location</div>
                        <div>{{ $selectedBooking->booking_address ?? '-' }}</div>
                        <div>Service type</div>
                        <div>{{ $selectedBooking->service->name ?? '-' }}</div>
                        <div>Service cost</div>
                        <div>${{ number_format($selectedBooking->total_price, 2) }}</div>
                        <div>Status</div>
                        <div class="status {{ strtolower($selectedBooking->status) }}">
                            {{ str_replace('_', ' ', $selectedBooking->status) }}
                        </div>
                    </div>
                </div>

                <div class="modal-section">
                    <br>
                    <h4 style="font-size:0.938vw;font-weight: 500; letter-spacing: -0.04em;">Users details</h4>
                    <div class="details-grid">
                        <div>Service provider</div>
                        <div class="text-end">{{ $selectedBooking->provider->name ?? '-' }}</div>
                        <div>Service user</div>
                        <div class="text-end">{{ $user->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modals (Scripts to trigger them) -->
    <script>
    </script>
</div>
