<div>
    <div class="row">
        <div class="col-6">
            <!-- Review Card -->
            <div class="card border-0 p-3">
                <h5 class="mb-3 page-title">Review details</h5>

                <!-- Reviewer info -->
                <div class="d-flex align-items-center justify-content-between border rounded p-3"
                    style="margin-bottom: 2vw;">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset($review->reviewer->avatar ?? 'assets/images/icons/person.svg') }}"
                            alt="Reviewer" class="me-3 profile"
                            style="width: 2.65vw; height: 2.65vw; border-radius: 50%;">
                        <div>
                            <h6 class="mb-0" style="font-weight: 500; font-size: 0.8vw;">
                                <a href="{{ $review->reviewer->user_type === 'provider' ? route('user-management.service.providers.view', ['id' => $review->reviewer->id]) : route('user-management.service.users.view', ['id' => $review->reviewer->id]) }}"
                                    style="text-decoration: none; color: inherit;">
                                    {{ $review->reviewer->name ?? 'N/A' }}
                                </a>
                            </h6>
                            <small class="text-muted" style="font-size: 0.7vw;">Reviewer</small>
                        </div>
                    </div>
                    <a href="{{ $review->reviewer->user_type === 'provider' ? route('user-management.service.providers.view', ['id' => $review->reviewer->id]) : route('user-management.service.users.view', ['id' => $review->reviewer->id]) }}"
                        class="btn btn-outline-secondary btn-sm view-profile-btn">View profile</a>
                </div>

                <div class="d-flex align-items-center justify-content-between p-3 mb-3">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0" style="font-weight: 500; font-size: 0.8vw;">
                                <a href="{{ $review->reviewedProvider->user_type === 'provider' ? route('user-management.service.providers.view', ['id' => $review->reviewedProvider->id]) : route('user-management.service.users.view', ['id' => $review->reviewedProvider->id]) }}"
                                    style="text-decoration: none; color: inherit;">
                                    {{ $review->reviewedProvider->name ?? 'N/A' }}
                                </a>
                            </h6>
                            <small class="text-muted" style="font-size: 0.7vw;">Reviewed
                                {{ $review->reviewer->user_type === 'customer' ? 'Provider' : 'User' }}</small>
                        </div>
                    </div>

                    <div class="status-dropdown position-relative">
                        @php
                            $statusClass =
                                strtolower($review->status) === 'published'
                                    ? 'active'
                                    : (strtolower($review->status) === 'unpublished'
                                        ? 'inactive'
                                        : 'pending');
                        @endphp
                        @can('Write Reviews')
                            <span class="status-btn {{ $statusClass }}" onclick="toggleShowStatusDropdown()">
                                {{ ucfirst($review->status) }}
                                <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </span>
                            <ul class="dropdown-menu show-status-menu"
                                style="display: none; position: absolute; right: 0; z-index: 100;">
                                <li wire:click="setStatus('pending')">Pending</li>
                                <li wire:click="setStatus('published')">Publish</li>
                                <li wire:click="setStatus('unpublished')">Unpublish</li>
                            </ul>
                        @else
                            <span class="status-btn {{ $statusClass }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        @endcan
                    </div>
                </div>

                <!-- Review section -->
                <div class="p-3 border rounded reviewbox" style="display: grid; row-gap: 22px;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <!-- Stars -->
                        <div class="stars-rating d-flex">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <img src="{{ asset('assets/images/icons/star.svg') }}" alt="star"
                                        style="width:1.2vw; height:1.2vw; margin-right:0.3vw;">
                                @else
                                    <img src="{{ asset('assets/images/icons/empty_star.svg') }}" alt="empty star"
                                        style="width:1.2vw; height:1.2vw; margin-right:0.3vw;">
                                @endif
                            @endfor
                        </div>
                        <small class=""
                            style="font-size: 0.9vw;color:#8E8E8E;">{{ $review->created_at->diffForHumans() }}</small>
                    </div>

                    <!-- Review Text -->
                    @if (!$isEditing)
                        <p class="mb-3" style="font-size: 1vw;">
                            {{ $review->review }}
                        </p>
                    @else
                        <!-- Edit Area -->
                        <textarea wire:model="reviewText"
                            style="width:100%; font-size:1vw; padding: 10px; border: 1px solid #555555; border-radius: 5px;" rows="4"></textarea>
                    @endif

                    <!-- Buttons -->
                    <div class="action-buttons d-flex justify-content-between align-items-center">
                        <div class="edit-delete-buttons d-flex gap-3">
                            @if (!$isEditing)
                                <!-- EDIT -->
                                @can('Write Reviews')
                                    <a href="javascript:void(0);" wire:click="toggleEdit" class="view-btn"
                                        style="color: grey; display: flex; align-items: center; gap: 5px; font-size: 0.8vw; text-decoration: none;">
                                        <img src="{{ asset('assets/images/icons/edit-2.svg') }}"
                                            style="width: 1vw; height: 1vw;">
                                        Edit
                                    </a>
                                @endcan

                                <!-- DELETE -->
                                @can('Delete Reviews')
                                    <a href="javascript:void(0);" onclick="confirmDeleteReview()" class="view-btn"
                                        style="color: grey; display: flex; align-items: center; gap: 5px; font-size: 0.8vw; text-decoration: none;">
                                        <img src="{{ asset('assets/images/icons/trash-theme.svg') }}"
                                            style="width: 1vw; height: 1vw;">
                                        Delete
                                    </a>
                                @endcan
                            @else
                                <button wire:click="saveReview" class="btn btn-primary btn-sm"
                                    style="background-color: #004e42; border: none; padding: 0.4vw 1vw; font-size: 0.8vw;">
                                    Save changes
                                </button>
                                <button wire:click="toggleEdit" class="btn btn-secondary btn-sm"
                                    style="padding: 0.4vw 1vw; font-size: 0.8vw;">
                                    Cancel
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    @can('Delete Reviews')
        <div id="deleteReviewModal" class="deleteModal"
            style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); justify-content: center; align-items: center; z-index: 1000;">
            <div class="delete-card" style="background: #fff; padding: 20px; border-radius: 10px; min-width: 300px;">
                <div class="delete-card-header d-flex justify-content-between align-items-center mb-3">
                    <h3 class="delete-title mb-0">Delete review</h3>
                    <span class="delete-close" style="cursor:pointer; font-size: 1.5rem;"
                        onclick="closeDeleteModal()">&times;</span>
                </div>
                <p class="delete-text">Are you sure you want to delete this review?</p>
                <div class="delete-actions d-flex justify-content-start gap-2">
                    <button class="btn btn-danger" wire:click="delete">Delete</button>
                    <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                </div>
            </div>
        </div>
    @endcan

    <style>
        .view-profile-btn {
            border: 1px solid #004E424D !important;
            color: #004E42 !important;
            font-weight: 500 !important;
        }

        .view-profile-btn:hover {
            background-color: #004d40 !important;
            color: #fff !important;
            border-color: #004d40 !important;
        }

        .status-btn.active {
            background-color: #e0f5e9;
            color: #28a745;
        }

        .status-btn.inactive {
            background-color: #fdecea;
            color: #dc3545;
        }

        .status-btn.pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .dropdown-menu li {
            padding: 8px 12px;
            cursor: pointer;
            list-style: none;
        }

        .dropdown-menu li:hover {
            background-color: #f8f9fa;
        }
    </style>

    <script>
        function toggleShowStatusDropdown() {
            const menu = document.querySelector('.show-status-menu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        }

        function confirmDeleteReview() {
            document.getElementById('deleteReviewModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteReviewModal').style.display = 'none';
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.status-dropdown')) {
                const menu = document.querySelector('.show-status-menu');
                if (menu) menu.style.display = 'none';
            }
        });
    </script>
</div>
