<div>
    <style>
        #addUserModal label {
            margin-top: 1vw;
            margin-bottom: 0.2vw;
        }

        .video-container {
            position: relative;
            /* width: 404px !important;
            height: 147px !important; */
            border-radius: 4px;
            overflow: hidden;
            background: #000;
        }

        /* Override style.css static grid for swiper */
        #photos-swiper,
        #videos-swiper {
            display: block !important;
            width: 100%;
            overflow: hidden;
            position: relative;
            padding-bottom: 20px !important;
        }

        #photos-swiper .swiper-wrapper,
        #videos-swiper .swiper-wrapper {
            display: flex !important;
            flex-wrap: nowrap !important;
        }

        /* Figma Image Styles */
        #photos-swiper .swiper-slide {
            width: 194px !important;
            height: 147px !important;
            margin-right: 15px;
        }

        #photos-swiper .swiper-slide img {
            width: 194px !important;
            height: 147px !important;
            object-fit: cover;
            border-radius: 4px !important;
            box-shadow: 0px 16px 48px 3px rgba(181, 181, 181, 0.24);
        }

        .videos-grid-layout {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 15px;
            width: 100%;
            margin-top: 0.5vw;
            padding-bottom: 10px;
        }

        .videos-grid-layout::-webkit-scrollbar {
            height: 4px;
        }

        .videos-grid-layout::-webkit-scrollbar-thumb {
            background: #004e42;
            border-radius: 10px;
        }

        /* .videos-grid-layout .video-container {
            flex: 0 0 404px;
            width: 404px !important;
            height: 147px !important;
            border-radius: 4px;
            overflow: hidden;
            background: #000;
            position: relative;
        } */

        /* Figma Video Styles */
        /* #videos-swiper .swiper-slide {
            width: 404px !important;
            height: 147px !important;
            margin-right: 15px;
        } */

        #videos-swiper .custom-video {
            width: 100% !important;
            height: 7.813vw !important;
            object-fit: cover;
            cursor: pointer;
        }

        .video-container.playing .custom-controls {
            opacity: 0.7;
        }

        .video-container:hover .custom-controls {
            opacity: 1;
        }

        /* Swiper Navigation Customization */
        .service-details-theme .swiper-button-next,
        .service-details-theme .swiper-button-prev {
            background: white;
            width: 30px !important;
            height: 30px !important;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            color: #004e42 !important;
        }

        .service-details-theme .swiper-button-next::after,
        .service-details-theme .swiper-button-prev::after {
            font-size: 14px !important;
            font-weight: bold;
        }

        .custom-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0, 0, 0, 0.5);
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 5px 10px;
        }

        .custom-btn {
            width: 25px;
            height: 25px;
            background: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            color: #004e42;
            font-size: 12px;
        }

        .custom-progress {
            flex-grow: 1;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            margin: 0 10px;
            cursor: pointer;
            border-radius: 2px;
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

        .swiper {
            width: 100%;
            min-height: 8vw;
        }

        .deleteModal {
            position: absolute;
            top: -5px;
            right: -5px;
        }

        /* Complete Scroll for Service Details Modal */
        #service-details-modal-container {
            overflow-y: auto !important;
            background: rgba(0, 0, 0, 0.5);
        }

        .service-details-theme {
            display: flex;
            justify-content: center;
            align-items: flex-start !important;
            /* Align to top for scrolling */
            padding: 2vw 0;
            /* Space at top and bottom */
            min-height: 100%;
            position: relative !important;
            /* Allow container to handle fixed positioning */
            background: transparent !important;
        }

        .service-details-theme .modal-content {
            max-height: none !important;
            /* Remove inner scroll */
            overflow-y: visible !important;
            margin-bottom: 2vw;
        }

        body.modal-open {
            overflow: hidden !important;
        }


        .video-container {
    position: relative;
    width: 100%;
    height: 100%;
}

.custom-video {
    width: 100%;
       height: 7.813vw;
    display: none; /* start me hidden */
}

.video-placeholder {
    position: relative;
    width: 100%;
    cursor: pointer;
}

.video-placeholder img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
}

.play-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 60px;
    color: #fff;
    background: rgba(0, 0, 0, 0.2);
    width: 100%;
    height: 100%;
    border-radius: 0px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.play-icon svg{
    width:2vw;
    height: 2vw;
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
                    <span class="stars">
                        @php
                            $rating = $user->overall_rating ?? 0;
                            $filledStars = floor($rating);
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $filledStars)
                                <img class="icons-btn" src="{{ asset('assets/images/icons/star.svg') }}" alt="Star"
                                    style="width: 1.2vw; height: 1.2vw;">
                            @else
                                <img class="icons-btn" src="{{ asset('assets/images/icons/empty_star.svg') }}"
                                    alt="Empty Star" style="width: 1.2vw; height: 1.2vw;">
                            @endif
                        @endfor
                        ({{ number_format($rating, 1) }})
                    </span>
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
                            <th class="sortable">Service User <img src="{{ asset('assets/images/icons/sort.svg') }}"
                                    class="sort-icon"></th>
                            <th class="sortable">Service Category <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable" wire:click="sortBy('booking_address', 'booking')">Location <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable" wire:click="sortBy('total_price', 'booking')">Amount Paid <img
                                    src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                            <th class="sortable" wire:click="sortBy('booking_working_minutes', 'booking')">Duration
                                <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                            </th>

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
                                <td>
                                    <a href="{{ $booking->customer_id ? route('user-management.service.users.view', $booking->customer_id) : '#' }}"
                                        style="text-decoration: none; color: inherit; font-weight: 500;">
                                        {{ $booking->customer->name ?? '-' }}
                                    </a>
                                </td>
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
                <div class="toolbar-actions" {{ empty($selectedDocs) ? 'hidden' : '' }}>
                    <button class="btn btn-verified" style="font-weight:500"
                        wire:click="bulkUpdateDocumentStatus('verified')"><i class="fa-solid fa-trash-can"></i>&nbsp;
                        Mark as verified</button>
                    <button class="btn btn-declined" style="font-weight:500"
                        wire:click="bulkUpdateDocumentStatus('declined')"><i class="fa-solid fa-xmark"></i>&nbsp;
                        Mark as decline</button>
                    <button class="btn btn-pending" style="font-weight:500"
                        wire:click="bulkUpdateDocumentStatus('pending')"><i class="fa-solid fa-minus"></i>&nbsp;
                        Mark as pending</button>
                </div>
            </div>

            <div class="documents-list" style="padding:0vw 1.3vw 1.3vw;">
                @php
                    $profile = $user->providerProfile;
                    $docs = [
                        [
                            'id' => 'id_photo',
                            'title' => 'Valid Emirate ID card',
                            'file' => $profile->id_photo ?? null,
                            'status' => $profile->id_photo_status ?? 'pending',
                        ],
                        [
                            'id' => 'passport',
                            'title' => 'Passport',
                            'file' => $profile->passport ?? null,
                            'status' => $profile->passport_status ?? 'pending',
                        ],
                        [
                            'id' => 'work_permit',
                            'title' => 'Work Permit',
                            'file' => $profile->work_permit ?? null,
                            'status' => $profile->work_permit_status ?? 'pending',
                        ],
                    ];
                @endphp
                @foreach ($docs as $doc)
                    @php
                        $uiStatus = $doc['status'];
                        if ($doc['status'] === 'approved') {
                            $uiStatus = 'verified';
                        }
                        if ($doc['status'] === 'rejected') {
                            $uiStatus = 'declined';
                        }
                    @endphp
                    <div class="doc-row" data-id="{{ $doc['id'] }}">
                        <div class="d-flex align-items-center">
                            <label class="check-wrap check-wrap-checkbox">
                                <input type="checkbox" class="row-check" value="{{ $doc['id'] }}"
                                    wire:model.live="selectedDocs">
                                <span class="checkmark"></span>
                            </label>
                            <span class="doc-title">{{ $doc['title'] }}</span>
                        </div>

                        @if ($doc['file'])
                            <a href="#" class="doc-link view-doc-btn" style="color:#004e42;"
                                data-src="{{ asset($doc['file']) }}" data-title="{{ $doc['title'] }}">
                                View document
                            </a>
                        @else
                            <span class="text-muted" style="font-size: 0.9vw;">No document uploaded</span>
                        @endif

                        <span
                            class="badge badge-{{ $uiStatus }} badge-pill actions-btn-verified {{ $uiStatus === 'declined' ? 'text-danger border-danger' : '' }}"
                            style="position:relative;padding:0.677vw; {{ $uiStatus === 'declined' ? 'background:rgba(251, 55, 72, 0.1);' : '' }}"
                            data-block='{{ $doc['id'] }}' data-badge>
                            {{ ucfirst($uiStatus) }} &nbsp; <i class="fa-solid fa-chevron-down"></i>
                            <div class="actions-menu" id="actions-menu-verified-{{ $doc['id'] }}"
                                style="display: none;left:0px;right:unset;top:2.5vw;min-width: 6.5vw;">
                                @if ($uiStatus !== 'pending')
                                    <a href="#"
                                        wire:click.prevent="updateDocumentStatus('{{ $doc['id'] }}', 'pending')">Pending</a>
                                @endif
                                @if ($uiStatus !== 'verified')
                                    <a href="#"
                                        wire:click.prevent="updateDocumentStatus('{{ $doc['id'] }}', 'approved')">Verified</a>
                                @endif
                                @if ($uiStatus !== 'declined')
                                    <a href="#"
                                        wire:click.prevent="updateDocumentStatus('{{ $doc['id'] }}', 'rejected')">Decline</a>
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
                        <div class="text-end">
                            <a href="{{ route('user-management.service.providers.view', $user->id) }}"
                                style="text-decoration: none; color: #004e42; font-weight: 600;">
                                {{ $user->name }}
                            </a>
                        </div>
                        <div>Service user</div>
                        <div class="text-end">
                            <a href="{{ $selectedBooking->customer_id ? route('user-management.service.users.view', $selectedBooking->customer_id) : '#' }}"
                                style="text-decoration: none; color: #004e42; font-weight: 600;">
                                {{ $selectedBooking->customer->name ?? '-' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Service Details Modal Container -->
    <div id="service-details-modal-container" style="display: none; position: fixed; inset: 0; z-index: 1050;">
        <div id="service-details-modal" class="service-details-theme" style="display: flex;">
            <div class="modal-content" @click.stop>
                <span class="close-btn" style="line-height: 1;" onclick="closeServiceModalJS()">
                    <svg style="width:0.625vw;height:0.625vw;" width="12" height="12" viewBox="0 0 12 12"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75"
                            stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </span>
                <h3>Service details</h3>

                <label>Name</label>
                <input type="text" value="{{ $selectedService->service->name ?? '' }}" readonly>

                <label>Description</label>
                <textarea readonly>{{ $selectedService->description ?? '' }}</textarea>

                <div class="price-boxes">
                    <div>
                        <label for="">Maximum price/hr</label>
                        <input type="text"
                            value="{{ $selectedService ? '$' . number_format($selectedService->rate_max ?? 0, 2) : '' }}"
                            readonly>
                    </div>
                    <div>
                        <label for="">Mid price/hr</label>
                        <input type="text"
                            value="{{ $selectedService ? '$' . number_format($selectedService->rate_mid ?? 0, 2) : '' }}"
                            readonly>
                    </div>
                    <div>
                        <label for="">Minimum price/hr</label>
                        <input type="text"
                            value="{{ $selectedService ? '$' . number_format($selectedService->rate_min ?? 0, 2) : '' }}"
                            readonly>
                    </div>
                </div>

                @if ($selectedService)
                    @php
                        $allMedia = $selectedService->media;
                        if ($allMedia->isEmpty()) {
                            $allMedia = \App\Models\ProviderServiceMedia::where('user_id', $user->id)->get();
                        }
                    @endphp

                    <h4 style="font-size:0.938vw">Photos</h4>
                    <div id="photos-swiper" class="swiper w-100" wire:ignore>
                        <div class="swiper-wrapper">
                            @php
                                $photos = $allMedia->filter(
                                    fn($m) => in_array(strtolower(trim($m->type)), ['photo', 'image']),
                                );
                            @endphp
                            @forelse($photos as $photo)
                                <div class="swiper-slide">
                                    <img src="{{ asset($photo->file_path) }}" alt="Service Photo">
                                </div>
                            @empty
                                <div class="swiper-slide">
                                    <img src="{{ asset('assets/images/icons/service_one.svg') }}" alt="Placeholder">
                                </div>
                                 <div class="swiper-slide">
                                    <img src="{{ asset('assets/images/icons/service_one.svg') }}" alt="Placeholder">
                                </div>
                                 <div class="swiper-slide">
                                    <img src="{{ asset('assets/images/icons/service_one.svg') }}" alt="Placeholder">
                                </div>
                                 <div class="swiper-slide">
                                    <img src="{{ asset('assets/images/icons/service_one.svg') }}" alt="Placeholder">
                                </div>
                            @endforelse
                        </div>
                        <div class="swiper-button-next photos-next"></div>
                        <div class="swiper-button-prev photos-prev"></div>
                    </div>

                    <h4 style="font-size:0.938vw;margin-top:0.9vw">Videos</h4>
                    <div class="videos-grid-layout  swiper "  id="videos-swiper" wire:ignore>
                        <!-- Navigation Arrows -->
                        <div class="swiper-button-next video-swiper-button-next"></div>
                        <div class="swiper-button-prev video-swiper-button-prev"></div>
                        <div class="swiper-wrapper">
                            @php
                                $videos = $allMedia->filter(
                                    fn($m) => strtolower(trim($m->type)) === 'video' ||
                                        str_ends_with(strtolower($m->file_path), '.mp4'),
                                );
                            @endphp
                            @forelse($videos as $video)
                           
                                                          <div class="video-container swiper-slide">
    <!-- Placeholder -->
    <div class="video-placeholder">
        <img src="{{ asset('assets/images/icons/service_one.svg') }}" alt="Video Placeholder">
        <div class="play-icon" onclick="window.toggleVideo(this)"><svg  viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.2734 17.4999V15.3416C13.2734 12.5562 15.2422 11.4333 17.6484 12.8187L19.5151 13.8978L21.3818 14.977C23.788 16.3624 23.788 18.6374 21.3818 20.0228L19.5151 21.102L17.6484 22.1812C15.2422 23.5666 13.2734 22.4291 13.2734 19.6583V17.4999Z" stroke="white" stroke-width="1.52" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M17.4974 32.0832C25.5515 32.0832 32.0807 25.554 32.0807 17.4998C32.0807 9.44568 25.5515 2.9165 17.4974 2.9165C9.44324 2.9165 2.91406 9.44568 2.91406 17.4998C2.91406 25.554 9.44324 32.0832 17.4974 32.0832Z" stroke="white" stroke-width="1.52" stroke-linecap="round" stroke-linejoin="round"/>
</svg></div>
    </div>

    <!-- Video -->
    <video class="custom-video" preload="metadata" playsinline controls>
        <source src="{{ asset($video->file_path) }}" type="video/mp4">
    </video>
</div>
                            @empty
                              
                                                                                          <div class="video-container swiper-slide">
    <!-- Placeholder -->
    <div class="video-placeholder">
        <img src="{{ asset('assets/images/icons/service_one.svg') }}" alt="Video Placeholder">
        <div class="play-icon" onclick="window.toggleVideo(this)"><svg  viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.2734 17.4999V15.3416C13.2734 12.5562 15.2422 11.4333 17.6484 12.8187L19.5151 13.8978L21.3818 14.977C23.788 16.3624 23.788 18.6374 21.3818 20.0228L19.5151 21.102L17.6484 22.1812C15.2422 23.5666 13.2734 22.4291 13.2734 19.6583V17.4999Z" stroke="white" stroke-width="1.52" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M17.4974 32.0832C25.5515 32.0832 32.0807 25.554 32.0807 17.4998C32.0807 9.44568 25.5515 2.9165 17.4974 2.9165C9.44324 2.9165 2.91406 9.44568 2.91406 17.4998C2.91406 25.554 9.44324 32.0832 17.4974 32.0832Z" stroke="white" stroke-width="1.52" stroke-linecap="round" stroke-linejoin="round"/>
</svg></div>
    </div>

    <!-- Video -->
    <video class="custom-video" preload="metadata" playsinline controls>
        <source src="{{ asset('assets/videos/video1.mp4') }}" type="video/mp4">
    </video>
</div>




                            @endforelse
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Document Preview Modal -->
    <div id="check-modal" class="cm-modal" aria-hidden="true">
        <div class="cm-backdrop"></div>
        <div class="cm-dialog" role="dialog" aria-modal="true" aria-labelledby="cm-title">
            <div class="cm-head">
                <h4 id="cm-title" class="cm-title">Document</h4>
                <button type="button" class="cm-close" aria-label="Close">×</button>
            </div>
            <div class="cm-body">
                <img id="cm-img" class="cm-img" alt="Preview" />
                <div id="cm-ph" class="cm-placeholder" hidden>
                    <svg viewBox="0 0 24 24" class="cm-ph-icon">
                        <circle cx="8" cy="8" r="3"></circle>
                        <path d="M2 20l6-7 4 4 3-3 7 6" fill="none" stroke="currentColor" stroke-width="2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <script>
console.log("Service details script loading...");
function toggleVideo(el) {
    const container = el.closest('.video-container');
    const video = container.querySelector('.custom-video');
    const placeholder = container.querySelector('.video-placeholder');

    // Pause all other videos
    document.querySelectorAll('.custom-video').forEach(v => {
        if (v !== video) {
            v.pause();
            v.style.display = 'none';
            v.closest('.video-container')
                .querySelector('.video-placeholder').style.display = 'block';
        }
    });

    // Play current
    placeholder.style.display = 'none';
    video.style.display = 'block';
    video.play();

    // Jab video pause/end ho → placeholder wapas
    video.onpause = video.onended = () => {
        video.style.display = 'none';
        placeholder.style.display = 'block';
    };
}

/* =====================================
   PLAY / PAUSE (MULTIPLE VIDEOS SAFE)
===================================== */
// function toggleVideo(btn) {
//     const container = btn.closest('.video-container');
//     const video = container.querySelector('.custom-video');

//     // Pause all other videos
//     document.querySelectorAll('.custom-video').forEach(v => {
//         if (v !== video) {
//             v.pause();
//             const c = v.closest('.video-container');
//             c.querySelector('.play-btn').textContent = '▶';
//             c.classList.remove('playing');
//         }
//     });

//     if (video.paused) {
//         video.play();
//         btn.textContent = '⏸';
//         container.classList.add('playing');
//     } else {
//         video.pause();
//         btn.textContent = '▶';
//         container.classList.remove('playing');
//     }
// }

/* =====================================
   PROGRESS BAR UPDATE (ONE TIME ONLY)
===================================== */
$(document)
.off('timeupdate', '.custom-video')
.on('timeupdate', '.custom-video', function () {
    const container = $(this).closest('.video-container');
    const progressBar = container.find('.progress-bar');

    if (this.duration && !isNaN(this.duration)) {
        progressBar.val((this.currentTime / this.duration) * 100);
    }
});

/* =====================================
   SEEK BAR (PER VIDEO)
===================================== */
$(document)
.off('input', '.progress-bar')
.on('input', '.progress-bar', function () {
    const container = $(this).closest('.video-container');
    const video = container.find('.custom-video')[0];

    if (video && video.duration && !isNaN(video.duration)) {
        video.currentTime = (this.value / 100) * video.duration;
    }
});

/* =====================================
   VIDEO END RESET
===================================== */
$(document)
.off('ended', '.custom-video')
.on('ended', '.custom-video', function () {
    const container = $(this).closest('.video-container');
    container.find('.play-btn').text('▶');
    container.removeClass('playing');
    this.currentTime = 0;
});

/* =====================================
   MODAL CLOSE
===================================== */
function closeServiceModalJS() {
    $('video').each(function () {
        this.pause();
        const container = $(this).closest('.video-container');
        container.find('.play-btn').text('▶');
        container.removeClass('playing');
    });

    $('#service-details-modal-container').fadeOut(200);
    $('body').removeClass('modal-open');

    @this.closeServiceModal();
}
</script>


    <!-- Modals (Scripts to trigger them) -->
    <script>
        // console.log("Service details script loading...");

        // // Global functions for modal - Move outside to ensure they load immediately

        // function toggleVideo(btn) {
        //     const container = btn.closest('.video-container');
        //     const video = container.querySelector('.custom-video');
        //     const progressBar = container.querySelector('.progress-bar');

        //     if (video.paused) {
        //         video.play();
        //         btn.textContent = '⏸';
        //     } else {
        //         video.pause();
        //         btn.textContent = '▶';
        //     }

        //     // Update progress while playing
        //     video.addEventListener('timeupdate', () => {
        //         const percent = (video.currentTime / video.duration) * 100;
        //         progressBar.value = percent || 0;
        //     });

        //     // Seek on progress change
        //     progressBar.addEventListener('input', () => {
        //         video.currentTime = (progressBar.value / 100) * video.duration;
        //     });

        //     // Reset on end
        //     video.addEventListener('ended', () => {
        //         btn.textContent = '▶';
        //         progressBar.value = 0;
        //     });
        // }



        // function closeServiceModalJS() {
        //     // Pause all videos when closing
        //     $('video').each(function() {
        //         if (!this.paused) {
        //             this.pause();
        //             const container = $(this).closest('.video-container');
        //             container.find('.play-btn').text('▶');
        //             container.removeClass('playing');
        //         }
        //     });
        //     $('#service-details-modal-container').fadeOut(200);
        //     $('body').removeClass('modal-open');
        //     @this.closeServiceModal();
        // }
        // // Progress bar and time updates - Keep these as they are data-bound
        //     $(document).off('timeupdate', '.custom-video').on('timeupdate', '.custom-video', function() {
        //         const video = this;
        //         const container = $(this).closest('.video-container');
        //         const progressBar = container.find('.progress-bar');
        //         if (video.duration) {
        //             const value = (video.currentTime / video.duration) * 100;
        //             progressBar.val(value);
        //         }
        //     });

        //     $(document).off('input', '.progress-bar').on('input', '.progress-bar', function() {
        //         const container = $(this).closest('.video-container');
        //         const video = container.find('.custom-video')[0];
        //         const value = $(this).val();
        //         if (video && video.duration && !isNaN(video.duration)) {
        //             video.currentTime = (value * video.duration) / 100;
        //         }
        //     });

        //     // Reset UI when video ends
        //     $(document).off('ended', '.custom-video').on('ended', '.custom-video', function() {
        //         const container = $(this).closest('.video-container');
        //         container.find('.play-btn').text('▶');
        //         container.removeClass('playing');
        //         this.dataset.processing = 'false';
        //     });

        document.addEventListener('livewire:initialized', () => {
            // Function to initialize Swipers
            let photosSwiper;
            const initSwipers = () => {
                const photosEl = document.querySelector('#photos-swiper');
                if (photosEl) {
                    if (photosSwiper) photosSwiper.destroy(true, true);
                    photosSwiper = new Swiper('#photos-swiper', {
                        slidesPerView: '4',
                        spaceBetween: 15,
                        observer: true,
                        observeParents: true,
                        navigation: {
                            nextEl: '.photos-next',
                            prevEl: '.photos-prev',
                        },
                    });
                }

     let videoSwiper;
                 const videosEl = document.querySelector('#videos-swiper');
                if (videosEl) {
                    if (videoSwiper) videoSwiper.destroy(true, true);
                    videoSwiper = new Swiper('#videos-swiper', {
                        slidesPerView: '4',
                        spaceBetween: 15,
                        observer: true,
                        observeParents: true,
                        navigation: {
                            nextEl: '.video-swiper-button-next',
                            prevEl: '.video-swiper-button-prev',
                        },
                    });
                }
            };

            // Listen for open modal event
            Livewire.on('open-service-modal', () => {
                $('#service-details-modal-container').fadeIn(200);
                $('body').addClass('modal-open');
                setTimeout(initSwipers, 300);
            });

    
            // Document status badge dropdown logic
            $(document).on('click', '.actions-btn-verified', function(e) {
                e.stopPropagation();
                const blockId = $(this).attr('data-block');
                const menu = $(`#actions-menu-verified-${blockId}`);

                $('.actions-menu').not(menu).hide();
                menu.toggle();
            });

            $(document).on('click', '.view-doc-btn', function(e) {
                e.preventDefault();
                const src = $(this).data('src');
                const title = $(this).data('title');
                $('#cm-img').attr('src', src);
                $('#cm-title').text(title);
                $('#check-modal').addClass('is-open');
                $('body').addClass('cm-lock');
            });

            $(document).on('click', '.cm-close, .cm-backdrop', function() {
                $('#check-modal').removeClass('is-open');
                $('body').removeClass('cm-lock');
            });

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('cm-modal')) {
                    $('#check-modal').removeClass('is-open');
                    $('body').removeClass('cm-lock');
                }
                $('.actions-menu').hide();
            });

            // Toolbar checkboxes logic
            $(document).on('change', '.row-check', function() {
                // Handled by Livewire but keeping for UI interaction if needed
            });
        });
    </script>
</div>
