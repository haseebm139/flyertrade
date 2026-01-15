<div>
    <style>
        .video-container {
            position: relative;
            width: 25%;


        }

        .custom-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.5);
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .custom-btn {
            width: 1.823vw;
            height: 1.823vw;
            background: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }

        .custom-progress {
            flex-grow: 1;
            height: 0.313vw;
            background: #fff;
            cursor: pointer;
        }

        .custom-progress input {
            width: 100%;
            height: 100%;
            -webkit-appearance: none;
            appearance: none;
            background: transparent;
            cursor: pointer;
            padding: 0px;
            overflow: visible;
        }

        .custom-progress input::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 0.625vw;
            height: 0.625vw;
            background: #fff;
            border-radius: 50%;
            cursor: pointer;
        }

        .custom-progress input::-moz-range-thumb {
            width: 0.625vw;
            height: 0.625vw;
            background: #fff;
            border-radius: 50%;
            cursor: pointer;
        }

        #addUserModal label {
            margin-top: 1vw;
            margin-bottom: 0.2vw;
        }

        .deleteModal {
            position: absolute;
            top: -5px;
            right: -5px;
            z-index: 999;
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
        .status.active,
        .status.verified {
            color: #17A55A;
            border-color: #17A55A;
            background-color: rgba(23, 165, 90, 0.1);
        }

        .status.pending,
        .status.awaiting_provider {
            color: #EFC100;
            border-color: #EFC100;
            background-color: rgba(239, 193, 0, 0.1);
        }

        .status.cancelled,
        .status.rejected,
        .status.inactive,
        .status.declined {
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
            <a href="{{ route('user-management.service.providers.index') }}">Service Provider</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">{{ ucwords($user->name) }}</span>
        </nav>
    </div>

    <!-- Toolbar -->
    <div class="users-toolbar">
        <div class="toolbar-left" style="position:relative">
            <button class="reset-btn" wire:click="$set('showResetModal', true)" style="background-color: #fff;">Reset
                Password</button>

            @if ($showResetModal)
                <div class="deleteModal" style="display: flex;">
                    <div class="delete-card">
                        <div class="delete-card-header">
                            <h3 class="delete-title">Reset password</h3>
                            <span class="delete-close" wire:click="$set('showResetModal', false)">&times;</span>
                        </div>
                        <p class="delete-text">Are you sure you want to reset this provider's password?</p>
                        <div class="delete-actions justify-content-start">
                            <button class="confirm-delete-btn" wire:click="resetPassword">Reset</button>
                            <button class="cancel-delete-btn" wire:click="$set('showResetModal', false)">Cancel</button>
                        </div>
                    </div>
                </div>
            @endif

            <button class="edit-btn" wire:click="$dispatch('addItemRequested', { id: {{ $user->id }} })">
                Edit User
                &nbsp;
                <span class="download-icon"><img src="{{ asset('assets/images/icons/edit.svg') }}" alt=""
                        class="icons-btn"></span>
            </button>

            <button class="delete-btn showDeleteModal" wire:click="$set('showDeleteModal', true)">
                Delete user
                &nbsp;
                <span class="download-icon"><img src="{{ asset('assets/images/icons/trash.svg') }}" alt=""
                        class="icons-btn"></span>
            </button>

            @if ($showDeleteModal)
                <div id="globalDeleteModal" class="deleteModal" style="display: flex;">
                    <div class="delete-card">
                        <div class="delete-card-header">
                            <h3 class="delete-title">Delete Service Provider?</h3>
                            <span class="delete-close" wire:click="$set('showDeleteModal', false)">&times;</span>
                        </div>
                        <p class="delete-text">Are you sure you want to delete this service provider?</p>
                        <div class="delete-actions justify-content-start">
                            <button class="confirm-delete-btn" wire:click="deleteUser">Delete</button>
                            <button class="cancel-delete-btn"
                                wire:click="$set('showDeleteModal', false)">Cancel</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="toolbar-right">
            <!-- ✅ User Profile -->
            <div class="user-profile">
                <img src="{{ $user->avatar ? asset($user->avatar) : asset('assets/images/icons/user_profile_img.svg') }}"
                    alt="User" class="user-profile-img">
                <div class="user-infos">
                    <h4 class="user-name-user">{{ ucwords($user->name) }}</h4>
                    <p class="user-role">Service provider</p>
                </div>

                <!-- ✅ Status Dropdown -->
                <div class="status-dropdown" style="position:relative;">
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

    <!-- Tabs Section -->
    <div class="tabs-section">
        <div class="tab {{ $activeTab === 'details' ? 'active' : '' }}" wire:click="setTab('details')">Personal details
        </div>
        <div class="tab {{ $activeTab === 'history' ? 'active' : '' }}" wire:click="setTab('history')">Job history
        </div>
        <div class="tab {{ $activeTab === 'services' ? 'active' : '' }}" wire:click="setTab('services')">Services</div>
        <div class="tab {{ $activeTab === 'documents' ? 'active' : '' }}" wire:click="setTab('documents')">Documents &
            verification</div>
        <div class="tab {{ $activeTab === 'charges' ? 'active' : '' }}" wire:click="setTab('charges')">Charges & fees
        </div>
    </div>

    <!-- Tab Contents -->
    @if ($activeTab === 'details')
        <div id="details" class="tab-content active" style="border: 0.1vw solid #ddd;border-radius: 2vw;">
            <h3 style="font-weight:500;font-size:1.4vw;color:#1b1b1b;" class="profile-heading">Profile details</h3>
            <div class="profile-details">
                <p><span>Name</span> {{ ucwords($user->name) }}</p>
                <p><span>Email address</span> {{ $user->email }}</p>
                <p><span>Phone number</span> {{ $user->phone ?? '-' }}</p>
                <p><span>State of residence</span> {{ $user->state ?? '-' }}</p>
                <p><span>Home address</span> {{ $user->address ?? '-' }}</p>
                <p><span>Overall rating</span>
                    <img class="icons-btn" src="{{ asset('assets/images/icons/star.svg') }}" alt=""
                        style="width: 1.2vw; height: 1.2vw;">
                    ({{ number_format($user->overall_rating ?? 0, 1) }})
                </p>
                <p><span>Availability status</span>
                    {{ $user->providerProfile ? str_replace('_', ' ', ucfirst($user->providerProfile->availability_status)) : 'N/A' }}
                </p>
                <p><span>About</span> <span>{{ $user->providerProfile->about_me ?? '-' }}</span></p>
            </div>
        </div>
    @elseif ($activeTab === 'history')
        <div id="history" class="tab-content active">
            <div class="combo-class">
                <div class="dashboard-card">
                    <div>
                        <h6>Total Payout</h6>
                        <h2>${{ number_format($user->total_payout_sum ?? 0, 2) }}</h2>
                    </div>
                    <div class="icon-box">
                        <img src="{{ asset('assets/images/icons/payout-icon.svg') }}" alt="Icon">
                    </div>
                </div>
                <div class="dashboard-card">
                    <div>
                        <h6>Total Booking</h6>
                        <h2>{{ $user->total_bookings_count }}</h2>
                    </div>
                    <div class="icon-box">
                        <img src="{{ asset('assets/images/icons/active_booking.svg') }}" alt="Icon">
                    </div>
                </div>

            </div>
            <br>
            <div class="table-responsive">
                <table class="theme-table">
                    <thead>
                        <tr>
                            <th class="sortable" wire:click="sortBy('booking_ref', 'booking')">Booking ID <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable" wire:click="sortBy('created_at', 'booking')">Date created <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable">Service User <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable">Service Category <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable" wire:click="sortBy('booking_address', 'booking')">Location <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable" wire:click="sortBy('total_price', 'booking')">Amount Paid <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable" wire:click="sortBy('booking_working_minutes', 'booking')">Duration <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $booking->booking_ref }}</td>
                                <td>
                                    <span class="date">{{ $booking->created_at->format('d M, Y') }}</span>
                                    <br>
                                    <small class="time">{{ $booking->created_at->format('h:i A') }}</small>
                                </td>
                                <td>{{ $booking->customer->name ?? '-' }}</td>
                                <td>{{ $booking->service->name ?? '-' }}</td>
                                <td>{{ Str::limit($booking->booking_address ?? '-', 30) }}</td>
                                <td>${{ number_format($booking->total_price, 2) }}</td>
                                <td>{{ $booking->duration ?? '-' }}</td>

                                <td class="viw-parents">
                                    <button class="view-btn" wire:click="viewBooking({{ $booking->id }})">
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
            </div>
            {{ $bookings->links('vendor.pagination.custom') }}
        </div>
    @elseif ($activeTab === 'services')
        <div id="services" class="tab-content active">
            <div class="table-responsive">
                <table class="theme-table">
                    <thead>
                        <tr>
                            <th class="sortable" wire:click="sortBy('service_name', 'service')">Service Category <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable" wire:click="sortBy('description', 'service')">Description <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable" wire:click="sortBy('id', 'service')">Job ID <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable">Ratings <img src="{{ asset('assets/images/icons/sort.svg') }}"
                                    class="sort-icon"></th>
                            <th class="sortable" wire:click="sortBy('rate_min', 'service')">Min price/hr <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable" wire:click="sortBy('rate_mid', 'service')">Mid price/hr <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable" wire:click="sortBy('rate_max', 'service')">Max price/hr <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($providerServices as $ps)
                            <tr>
                                <td>{{ $ps->service->name ?? '-' }}</td>
                                <td>{{ Str::limit($ps->description ?? '-', 50) }}</td>
                                <td>{{ $ps->id }}</td>
                                <td>
                                    <div class="stars-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $ps->rating)
                                                <img src="{{ asset('assets/images/icons/star.svg') }}" alt="star"
                                                    style="width: 0.8vw; height: 0.8vw; margin-right: 0.05vw;">
                                            @else
                                                <img src="{{ asset('assets/images/icons/empty_star.svg') }}"
                                                    alt="star"
                                                    style="width: 0.8vw; height: 0.8vw; margin-right: 0.05vw;">
                                            @endif
                                        @endfor
                                    </div>
                                </td>

                                <td>${{ number_format($ps->rate_min ?? 0, 2) }}</td>
                                <td>${{ number_format($ps->rate_mid ?? 0, 2) }}</td>
                                <td>${{ number_format($ps->rate_max ?? 0, 2) }}</td>
                                <td class="viw-parent">
                                    <button class="view-btn" wire:click="viewService({{ $ps->id }})">
                                        <img src="{{ asset('assets/images/icons/eye_icon.svg') }}" alt="View"
                                            class="eye-icon">
                                        View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No services offered.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $providerServices->links('vendor.pagination.custom') }}
        </div>
    @elseif ($activeTab === 'documents')
        <div id="documents-verifications" class="tab-content active"
            style="border:0.1vw solid #ddd;border-radius:1vw;">
            <!-- Top toolbar -->
            <div class="toolbar" style="padding:1.3vw;">
                <h3 class="toolbar-title" style="font-weight:500;font-size:1.4vw;color:#1b1b1b;">Documents</h3>
                <div class="toolbar-actions" hidden>
                    <button class="btn btn-verified" style="font-weight:500" data-action="verified"><i
                            class="fa-solid fa-trash-can"></i>&nbsp; Mark as verified</button>
                    <button class="btn btn-declined" style="font-weight:500" data-action="declined"><i
                            class="fa-solid fa-xmark"></i>&nbsp; Mark as decline</button>
                    <button class="btn btn-pending" style="font-weight:500" data-action="pending"><i
                            class="fa-solid fa-minus"></i>&nbsp; Mark as pending</button>
                </div>
            </div>

            <div class="documents-list" style="padding:0vw 1.3vw 1.3vw;">
                @php
                    $docs = [
                        ['id' => 1, 'title' => 'Valid Emirate ID card', 'status' => 'verified'],
                        ['id' => 2, 'title' => 'Trade License', 'status' => 'pending'],
                        ['id' => 3, 'title' => 'Insurance Document', 'status' => 'declined'],
                    ];
                @endphp
                @foreach ($docs as $doc)
                    <div class="doc-row" data-id="{{ $doc['id'] }}">
                        <div class="d-flex align-items-center">
                            <label class="check-wrap check-wrap-checkbox">
                                <input type="checkbox" class="row-check">
                                <span class="checkmark"></span>
                            </label>
                            <span class="doc-title">{{ $doc['title'] }}</span>
                        </div>

                        <a href="#" class="doc-link" style="color:#004e42;">
                            View document
                        </a>

                        <span class="badge badge-{{ $doc['status'] }} badge-pill actions-btn-verified"
                            style="position:relative;padding:0.677vw;" data-block='{{ $doc['id'] }}' data-badge>
                            {{ ucfirst($doc['status']) }} &nbsp; <i class="fa-solid fa-chevron-down"></i>
                            <div class="actions-menu" id="actions-menu-verified-{{ $doc['id'] }}"
                                style="display: none;left:0px;right:unset;top:2.5vw;min-width: 6.5vw;">
                                @if ($doc['status'] !== 'pending')
                                    <a href="#">Pending</a>
                                @endif
                                @if ($doc['status'] !== 'verified')
                                    <a href="#">Verified</a>
                                @endif
                                @if ($doc['status'] !== 'declined')
                                    <a href="#">Decline</a>
                                @endif
                            </div>
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif ($activeTab === 'charges')
        <div id="charges-fees" class="tab-content active" style="border:0.1vw solid #ddd;border-radius:1vw;">
            <h3 style="font-weight:500;font-size:1.4vw;color:#1b1b1b;" class="profile-heading">
                Charges and fees
            </h3>

            <div class="charges-row" style="padding:0vw 1.3vw 1.3vw">
                <div class="charge-col">
                    <label class="charge-label">Service fee</label>
                    <input type="text" class="charge-input" placeholder="$10" readonly>
                </div>

                <div class="charge-col">
                    <label class="charge-label">Commission</label>
                    <input type="text" class="charge-input" placeholder="5%" readonly>
                </div>
            </div>
        </div>
    @endif

    <!-- Booking Modal -->
    @if ($showBookingModal && $selectedBooking)
        <div id="view-booking" class="view-booking-modal add-user-modal" style="display: flex;">
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
                        <div>{{ $selectedBooking->duration ?? '-' }}</div>
                        <div>Location</div>
                        <div>{{ $selectedBooking->booking_address ?? '-' }}</div>
                        <div>Service type</div>
                        <div>{{ $selectedBooking->service->name ?? '-' }}</div>
                        <div>Service cost</div>
                        <div>${{ number_format($selectedBooking->total_price, 2) }}</div>
                        <div>Status</div>
                        <div>
                            <span class="status {{ strtolower($selectedBooking->status) }}">
                                {{ str_replace('_', ' ', $selectedBooking->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="modal-section">
                    <br>
                    <h4 style="font-size:0.938vw;font-weight: 500; letter-spacing: -0.04em;">Users details</h4>
                    <div class="details-grid">
                        <div>Service provider</div>
                        <div class="text-end">{{ $user->name }}</div>
                        <div>Service user</div>
                        <div class="text-end">{{ $selectedBooking->customer->name ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showServiceModal && $selectedService)
        <div id="service-details-modal" class="service-details-theme" style="display: flex;">
            <div class="modal-content">
                <span class="close-btn" id="closeServiceDetails" style="line-height: 1;"
                    wire:click="closeServiceModal">
                    <svg style="width:0.625vw;height:0.625vw;" width="12" height="12" viewBox="0 0 12 12"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75"
                            stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </span>
                <h3>Service details</h3>

                <label>Name</label>
                <input type="text" value="{{ $selectedService->service->name ?? '-' }}" readonly>

                <label>Description</label>
                <textarea readonly>{{ $selectedService->description ?? '-' }}</textarea>

                <div class="price-boxes">
                    <div>
                        <label for="">Maximum price/hr</label>
                        <input type="text" value="${{ number_format($selectedService->rate_max ?? 0, 2) }}"
                            readonly>
                    </div>
                    <div>
                        <label for="">Mid price/hr</label>
                        <input type="text" value="${{ number_format($selectedService->rate_mid ?? 0, 2) }}"
                            readonly>
                    </div>
                    <div>
                        <label for="">Minimum price/hr</label>
                        <input type="text" value="${{ number_format($selectedService->rate_min ?? 0, 2) }}"
                            readonly>
                    </div>
                </div>

                @if ($selectedService->media->where('type', 'photo')->count() > 0)
                    <h4 style="font-size:0.938vw">Photos</h4>
                    <div class="photos">
                        @foreach ($selectedService->media->where('type', 'photo') as $media)
                            <img src="{{ asset($media->file_path) }}" alt="service photo">
                        @endforeach
                    </div>
                @endif

                @if ($selectedService->media->where('type', 'video')->count() > 0)
                    <h4 style="font-size:0.938vw;margin-top:0.9vw">Videos</h4>
                    <div class="videos">
                        @foreach ($selectedService->media->where('type', 'video') as $media)
                            <div class="video-container">
                                <video class="custom-video" style="width: 100%;">
                                    <source src="{{ asset($media->file_path) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <div class="custom-controls">
                                    <div class="custom-btn play-btn">▶</div>
                                    <div class="custom-progress">
                                        <input type="range" class="progress-bar" value="0" max="100">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
    {{-- <div id="service-details-modal" class="service-details-theme">
        <div class="modal-content">
            <span class="close-btn" id="closeServiceDetails" style="line-height: 1;"><svg
                    style="width:0.625vw;height:0.625vw;" width="12" height="12" viewBox="0 0 12 12"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg></span>
            <h3>Service details</h3>

            <label>Name</label>
            <input type="text" value="Plumbing" readonly>

            <label>Description</label>
            <textarea readonly> Reliable and affordable plumbing solutions for your home or office. From fixing leaks and unclogging drains to full bathroom installations, I deliver fast and professional services. </textarea>

            <div class="price-boxes">
                <div><label for="">Maximum price/hr</label><input type="text" value="$80" readonly>
                </div>
                <div><label for="">Mid price/hr</label> <input type="text" value="$30" readonly></div>
                <div><label for="">Minimum price/hr</label><input type="text" value="$40" readonly>
                </div>
            </div>

            <h4 style="font-size:0.938vw">Photos</h4>
            <div class="photos">
                <img src="{{ asset('assets/images/icons/service_one.svg') }}" alt="">
                <img src="{{ asset('assets/images/icons/service_four.svg') }}" alt="">
                <img src="{{ asset('assets/images/icons/service_three.svg') }}" alt="">
                <img src="{{ asset('assets/images/icons/service_four.svg') }}" alt="">
            </div>

            <h4 style="font-size:0.938vw;margin-top:0.9vw">Videos</h4>
            <div class="videos">
                <div class="video-container">
                    <video class="custom-video" style="width: 100%;">
                        <source src="{{ asset('assets/videos/video1.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>

                    <div class="custom-controls">
                        <div class="custom-btn play-btn">▶</div>
                        <div class="custom-progress">
                            <input type="range" class="progress-bar" value="0" max="100">
                        </div>
                    </div>
                </div>

                <div class="video-container">
                    <video class="custom-video" style="width: 100%;">
                        <source src="{{ asset('assets/videos/video1.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>

                    <div class="custom-controls">
                        <div class="custom-btn play-btn">▶</div>
                        <div class="custom-progress">
                            <input type="range" class="progress-bar" value="0" max="100">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> --}}

    <!-- Modals (Scripts to trigger them) -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Video controls logic
            $(document).on('click', '.play-btn', function() {
                const container = $(this).closest('.video-container');
                const video = container.find('.custom-video')[0];
                const btn = $(this);

                if (video.paused) {
                    video.play();
                    btn.text('⏸');
                } else {
                    video.pause();
                    btn.text('▶');
                }
            });

            $(document).on('timeupdate', '.custom-video', function() {
                const video = this;
                const container = $(this).closest('.video-container');
                const progressBar = container.find('.progress-bar');
                if (video.duration) {
                    const value = (video.currentTime / video.duration) * 100;
                    progressBar.val(value);
                }
            });

            $(document).on('input', '.progress-bar', function() {
                const container = $(this).closest('.video-container');
                const video = container.find('.custom-video')[0];
                const value = $(this).val();
                if (video.duration) {
                    video.currentTime = (value * video.duration) / 100;
                }
            });

            // Document status badge dropdown logic
            $(document).on('click', '.actions-btn-verified', function(e) {
                e.stopPropagation();
                const blockId = $(this).attr('data-block');
                const menu = $(`#actions-menu-verified-${blockId}`);

                $('.actions-menu').not(menu).hide();
                menu.toggle();
            });

            $(document).on('click', function() {
                $('.actions-menu').hide();
            });

            // Toolbar checkboxes logic
            $(document).on('change', '.row-check', function() {
                const checkedCount = $('.row-check:checked').length;
                if (checkedCount > 0) {
                    $('.toolbar-actions').removeAttr('hidden');
                } else {
                    $('.toolbar-actions').attr('hidden', true);
                }
            });
        });
    </script>
</div>
