<div>
    <div class="container" bis_skin_checked="1">
        <h1 class="page-title">All Reviews</h1>
    </div>
    <livewire:admin.components.toolbar label="reviews" button_label="" search_label="" :active-filters="$activeFilters" />

    <div class="tabs-section">
        <div class="tab {{ $activeTab === 'users' ? 'active' : '' }} roles-tab" wire:click="switchTab('users')">
            &nbsp; User Reviews&nbsp;
        </div>
        <div class="tab {{ $activeTab === 'providers' ? 'active' : '' }} roles-tab" wire:click="switchTab('providers')">
            &nbsp; Providers Reviews&nbsp;
        </div>
    </div>

    <div class="tab-content active">
        <div class="table-responsive">
            <table class="theme-table">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th class="sortable" wire:click="sortBy('booking_id')">Booking ID
                            <img src="{{ asset('assets/images/icons/sort.svg') }}"
                                class="sort-icon {{ $sortField === 'booking_id' ? ($sortDirection === 'asc' ? '' : 'desc') : '' }}">
                        </th>
                        {{-- <th class="sortable">Booking Ref 
                            <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                        </th> --}}

                        <th class="sortable" wire:click="sortBy('created_at')">Date and time
                            <img src="{{ asset('assets/images/icons/sort.svg') }}"
                                class="sort-icon {{ $sortField === 'created_at' ? ($sortDirection === 'asc' ? '' : 'desc') : '' }}">
                        </th>
                        <th class="sortable" wire:click="sortBy('sender_id')">Reviewer
                            <img src="{{ asset('assets/images/icons/sort.svg') }}"
                                class="sort-icon {{ $sortField === 'sender_id' ? ($sortDirection === 'asc' ? '' : 'desc') : '' }}">
                        </th>
                        <th class="sortable" wire:click="sortBy('receiver_id')">Reviewed
                            {{ $activeTab === 'users' ? 'Provider' : 'User' }}
                            <img src="{{ asset('assets/images/icons/sort.svg') }}"
                                class="sort-icon {{ $sortField === 'receiver_id' ? ($sortDirection === 'asc' ? '' : 'desc') : '' }}">
                        </th>
                        <th class="sortable" wire:click="sortBy('review')"> Review
                            <img src="{{ asset('assets/images/icons/sort.svg') }}"
                                class="sort-icon {{ $sortField === 'review' ? ($sortDirection === 'asc' ? '' : 'desc') : '' }}">
                        </th>
                        <th class="sortable" wire:click="sortBy('status')"> Status
                            <img src="{{ asset('assets/images/icons/sort.svg') }}"
                                class="sort-icon {{ $sortField === 'status' ? ($sortDirection === 'asc' ? '' : 'desc') : '' }}">
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr wire:key="review-{{ $review->id }}">
                            <td><input type="checkbox" value="{{ $review->id }}"></td>
                            <td>{{ $review->booking_id ?? 'N/A' }}</td>
                            {{-- <td>{{ $review->booking->booking_ref ?? 'N/A' }}</td> --}}
                            <td>
                                <span class="date">{{ $review->created_at->format('d M, Y') }}</span>
                                <br>
                                <small class="time">{{ $review->created_at->format('h:i a') }}</small>
                            </td>
                            <td>
                                <a href="{{ $activeTab === 'users' ? route('user-management.service.users.view', $review->sender_id) : route('user-management.service.providers.view', $review->sender_id) }}"
                                    class="user-info" style="text-decoration: none;">
                                    <img src="{{ asset($review->reviewer->avatar ?? 'assets/images/icons/person-one.svg') }}"
                                        alt="User">
                                    <div>
                                        <span class="user-theme-name">{{ $review->reviewer->name ?? 'N/A' }}</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <a href="{{ $activeTab === 'users' ? route('user-management.service.providers.view', $review->receiver_id) : route('user-management.service.users.view', $review->receiver_id) }}"
                                    class="user-info" style="text-decoration: none;">
                                    <img src="{{ asset($review->reviewedProvider->avatar ?? 'assets/images/icons/person-one.svg') }}"
                                        alt="User">
                                    <div>
                                        <span
                                            class="user-theme-name">{{ $review->reviewedProvider->name ?? 'N/A' }}</span>
                                    </div>
                                </a>
                            </td>
                            <td class="min-width-200">
                                <div class="stars-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <img src="{{ asset('assets/images/icons/star.svg') }}" alt="star"
                                                style="margin-right: 0.1vw;">
                                        @else
                                            <img src="{{ asset('assets/images/icons/empty_star.svg') }}"
                                                alt="empty star" style="margin-right: 0.1vw;">
                                        @endif
                                    @endfor
                                </div>
                                {{ Str::limit($review->review, 100) }}
                            </td>
                            <td>
                                <div class="status-dropdown position-relative">
                                    @php
                                        $statusClass =
                                            strtolower($review->status) === 'published'
                                                ? 'publish'
                                                : (strtolower($review->status) === 'unpublished'
                                                    ? 'unpublished'
                                                    : 'pending');
                                    @endphp
                                    @can('Write Reviews')
                                        <span class="status {{ $statusClass }}" onclick="toggleReviewDropdown(this)">
                                            {{ ucfirst($review->status) }}
                                            <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="14"
                                                height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </span>
                                        <ul class="dropdown-menu" style="display:none; position: absolute; z-index: 100;">
                                            <li wire:click="setStatus({{ $review->id }}, 'pending')">Pending</li>
                                            <li wire:click="setStatus({{ $review->id }}, 'published')">Publish</li>
                                            <li wire:click="setStatus({{ $review->id }}, 'unpublished')">Unpublished</li>
                                        </ul>
                                    @else
                                        <span class="status {{ $statusClass }}">
                                            {{ ucfirst($review->status) }}
                                        </span>
                                    @endcan
                                </div>
                            </td>
                            <td style="position:relative;">
                                <div class="actions-dropdown review-actions-{{ $review->id }}">
                                    @if(auth()->user()->can('Read Reviews') || auth()->user()->can('Delete Reviews'))
                                        <button class="actions-btn act"
                                            onclick="toggleReviewActions(this, {{ $review->id }})">
                                            <img src="{{ asset('assets/images/icons/three_dots.svg') }}" class="dots-img"
                                                alt="">
                                        </button>
                                        <div class="actions-menu" style="position: absolute; right: 0; z-index: 100;">
                                            <a href="{{ route('reviews.show', ['id' => $review->id]) }}">
                                                <img src="{{ asset('assets/images/icons/eye.svg') }}" alt=""> View
                                                Details
                                            </a>
                                            @can('Delete Reviews')
                                                <a href="javascript:void(0);" class="showReviewDeleteModal"
                                                    data-id="{{ $review->id }}">
                                                    <img src="{{ asset('assets/images/icons/delete-icon.svg') }}"
                                                        alt=""> Delete
                                                </a>
                                            @endcan
                                        </div>
                                    @endif
                                </div>

                                <!-- ✅ Delete Modal -->
                                @can('Delete Reviews')
                                    <div id="deleteReviewModal{{ $review->id }}" class="deleteModal"
                                        style="display: none; position: absolute; top: 2vw; right: 6vw; z-index: 1000;">
                                        <div class="delete-card">
                                            <div class="delete-card-header">
                                                <h3 class="delete-title">Delete review</h3>
                                                <span class="delete-close closeReviewDeleteModal"
                                                    data-id="{{ $review->id }}">&times;</span>
                                            </div>
                                            <p class="delete-text">Are you sure you want to delete this review?</p>
                                            <div class="delete-actions justify-content-start">
                                                <button class="confirm-delete-btn"
                                                    wire:click="delete({{ $review->id }})">Delete</button>
                                                <button class="cancel-delete-btn closeReviewDeleteModal"
                                                    data-id="{{ $review->id }}">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No reviews found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $reviews->links('vendor.pagination.custom') }}
        </div>
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
                    ['value' => 'pending', 'label' => 'Pending'],
                    ['value' => 'published', 'label' => 'Published'],
                    ['value' => 'unpublished', 'label' => 'Unpublished'],
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

    <style>
        .desc {
            transform: rotate(180deg);
        }

        .sort-icon {
            transition: transform 0.3s ease;
        }

        .theme-table tbody tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.2s ease;
        }

        .unpublished {
            color: #D00416;
            border: 1px solid #D00416;
            background-color: #fb374741 !important;
        }

        .publish {
            background-color: #e0f5e9 !important;
            color: #28a745 !important;
            border: 1px solid #28a745 !important;
        }

      

        .status-dropdown .dropdown-menu {
            width: 100%;
            min-width: 120px;
        }

        .status-dropdown .dropdown-menu li {
            padding: 8px 12px;
            cursor: pointer;
            list-style: none;
        }

        .status-dropdown .dropdown-menu li:hover {
            background-color: #f8f9fa;
        }
    </style>

    <script>
        function toggleReviewDropdown(el) {
            const parent = el.closest('.status-dropdown');
            const dropdown = parent.querySelector('.dropdown-menu');
            const isOpen = dropdown.style.display === 'block';

            event.stopPropagation();

            document.querySelectorAll('.dropdown-menu').forEach(d => d.style.display = 'none');
            document.querySelectorAll('.status').forEach(s => s.classList.remove('open'));

            if (!isOpen) {
                dropdown.style.display = 'block';
                el.classList.add('open');
            }
        }

        function toggleReviewActions(el, id) {
            const dropdown = document.querySelector('.review-actions-' + id);
            const allDropdowns = document.querySelectorAll('.actions-dropdown');

            event.stopPropagation();

            allDropdowns.forEach(d => {
                if (d !== dropdown) d.classList.remove('active');
            });

            dropdown.classList.toggle('active');
        }

        $(document).on('click', '.showReviewDeleteModal', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let id = $(this).data('id');
            $('.deleteModal').hide();
            $('.actions-dropdown').removeClass('active'); // Close dropdown when opening modal
            $('#deleteReviewModal' + id).show();
        })

        $(document).on('click', '.closeReviewDeleteModal, .cancel-delete-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let id = $(this).data('id');
            $('#deleteReviewModal' + id).hide();
        })

        document.addEventListener('click', function(e) {
            // Close status dropdowns
            if (!e.target.closest('.status-dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
            }

            // Close actions dropdowns
            if (!e.target.closest('.actions-dropdown')) {
                document.querySelectorAll('.actions-dropdown').forEach(d => d.classList.remove('active'));
            }

            // Close delete modals
            if (!$(e.target).closest('.deleteModal').length && !$(e.target).closest('.showReviewDeleteModal')
                .length) {
                $('.deleteModal').hide();
            }
        });
    </script>
</div>
