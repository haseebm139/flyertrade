<div>
    <style>
        .deleteModal {
            position: absolute;
            right: 50px;
            z-index: 99999;
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
            font-family: Clash Display;
            color: #555;
            font-style: Medium;
            font-weight: 500;
        }

        .date-input::-webkit-calendar-picker-indicator {
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            position: absolute;
        }

        .date-input {
            background-image: url('data:image/svg+xml;utf8,<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.66406 1.66602V4.16602" stroke="%23717171" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.3359 1.66602V4.16602" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.91406 7.57422H17.0807" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.5 7.08268V14.166C17.5 16.666 16.25 18.3327 13.3333 18.3327H6.66667C3.75 18.3327 2.5 16.666 2.5 14.166V7.08268C2.5 4.58268 3.75 2.91602 6.66667 2.91602H13.3333C16.25 2.91602 17.5 4.58268 17.5 7.08268Z" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.99803 11.4167H10.0055" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.91209 11.4167H6.91957" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.91209 13.9167H6.91957" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
        }

        input[type="date"] {
            pointer-events: auto;
            position: relative;
            z-index: 1;
        }

        .date_field_wraper input[type="date"] {
            pointer-events: auto !important;
            z-index: 10;
        }
    </style>

    <div class="container" style="
    margin-top: 1vw;
    margin-bottom: 1vw;">
        <h1 class="page-title">All transactions</h1>
    </div>

    <div class="users-toolbar">
        <div class="toolbar-left">
            @can('Read Transactions')
                <button class="export-btn d-flex align-items-center gap-1" wire:click="exportCsv"
                    style="color:#004E42; line-height:1">
                    <span class="download-icon"><img class="btn-icons" src="{{ asset('assets/images/icons/download.svg') }}"
                            alt=""></span>&nbsp;
                    Export
                    CSV
                </button>
            @endcan
        </div>
        <div class="toolbar-right">
            <input type="text" class="search-user" placeholder="Search user"
                wire:model.live.debounce.500ms="search">
            <button class="filter-btn" wire:click="openFilterModal">
                Filter&nbsp;&nbsp;<span class="download-icon"><img
                        src="{{ asset('assets/images/icons/button-icon.svg') }}" class="btn-icons"
                        alt=""></span></button>
            @if (count($activeFilters) > 0)
                @foreach ($activeFilters as $filter)
                    <a href="#" class="filter_active_btna___" wire:click.prevent="removeFilter('{{ $filter['key'] }}')">
                        <span>{{ $filter['label'] }}</span>
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endforeach
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="theme-table">
            <thead>
                <tr>
                    <th><input type="checkbox" wire:model.live="selectAll"></th>
                    <th class="sortable" wire:click="sortBy('transaction_ref')">Transaction ID <img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                    </th>
                    <th class="sortable" wire:click="sortBy('type')">Transaction type <img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                    </th>
                    <th class="sortable" wire:click="sortBy('created_at')">Date and time
                        <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                    </th>
                    <th class="sortable">Associated user/provider<img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon"></th>
                    <th class="sortable">Payment method <img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon">
                    </th>
                    <th class="sortable" wire:click="sortBy('amount')">Amount Paid <img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                    <th class="sortable" wire:click="sortBy('status')"> Status <img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $transaction)
                    @php
                        $transactionDate = $transaction->processed_at ?? $transaction->created_at;
                        $associatedUser = $this->resolveAssociatedUser($transaction);
                        $isProvider = $transaction->type === 'payout';
                        $userRoute = $associatedUser
                            ? ($isProvider
                                ? route('user-management.service.providers.view', $associatedUser->id)
                                : route('user-management.service.users.view', $associatedUser->id))
                            : '#';
                    @endphp
                    <tr wire:key="transaction-{{ $transaction->id }}">
                        <td><input type="checkbox" value="{{ $transaction->id }}" wire:model.live="selected"></td>
                        <td style="font-weight:500; cursor: pointer;"
                            wire:click="viewTransaction({{ $transaction->id }})">
                            {{ $transaction->transaction_ref ?? $transaction->id }}
                        </td>
                        <td style="font-weight:500;">{{ $this->getTransactionTypeLabel($transaction) }}</td>
                        <td>
                            <span class="date" style="font-weight:500;">
                                {{ $transactionDate?->format('d M, Y') ?? '-' }}
                            </span>
                            <br>
                            <small class="time m-0">{{ $transactionDate?->format('h:i A') ?? '-' }}</small>
                        </td>
                        <td>
                            <a href="{{ $userRoute }}" class="user-info" style="text-decoration: none; color: inherit;">
                                <img src="{{ $associatedUser?->avatar ? asset($associatedUser->avatar) : asset('assets/images/icons/person-one.svg') }}"
                                    alt="User">
                                <div>
                                    <p class="user-name" style="font-weight:500;">
                                        {{ $associatedUser?->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </a>
                        </td>
                        <td style="font-weight:500;">{{ $this->getPaymentMethodLabel($transaction) }}</td>
                        <td style="font-weight:500;">{{ $this->formatAmount($transaction->amount, $transaction->currency) }}</td>
                        <td>
                            <span class="status {{ $this->getStatusClass($transaction) }}">
                                {{ $this->getStatusLabel($transaction) }}
                            </span>
                        </td>
                        <td style="position:relative;">
                            <div class="actions-dropdown">
                                <button class="actions-btn"> <img src="{{ asset('assets/images/icons/three_dots.svg') }}"
                                        class="dots-img "></button>
                                <div class="actions-menu">
                                    @can('Read Transactions')
                                        <a href="#" wire:click.prevent="viewTransaction({{ $transaction->id }})">
                                            <img src="{{ asset('assets/images/icons/eye.svg') }}" alt="">
                                            View
                                            details
                                        </a>
                                    @endcan
                                    @can('Write Transactions')
                                    <a href="#" class="initiateBtn showDeleteModal___"
                                        data-id="{{ $transaction->id }}" data-user="{{ $associatedUser?->name }}">
                                        <img style="height:0.7vw;width:0.7vw" src="{{ asset('assets/images/icons/init.svg') }}"
                                            alt=""> Initiate payout
                                    </a>
                                        @if ($transaction->type === 'payout' && in_array($transaction->status, ['pending', 'processing']))
                                        @endif
                                    @endcan
                                </div>
                            </div>
                            <div id="globalDeleteModal__{{ $transaction->id }}" class="deleteModal"
                                style="display: none;position:absolute;    top: 2.5vw; right: 3vw;">
                                <div class="delete-card">
                                    <div class="delete-card-header">
                                        <h3 class="delete-title">Initiate payout</h3>
                                        <span class="delete-close closeDeleteModal"
                                            data-id="{{ $transaction->id }}">&times;</span>
                                    </div>
                                    <p class="delete-text">Are you sure you want to initiate payout to this user?</p>
                                    <div class="delete-actions justify-content-start">
                                        <button class="cancel-delete-btn" data-id="{{ $transaction->id }}">Cancel</button>
                                        <button class="confirm-delete-btn"
                                            style="background-color:#004E42 !important;color:#fff;"
                                            wire:click="initiatePayout({{ $transaction->id }})">Initiate</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">No transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $data->links('vendor.pagination.custom') }}

    @if ($showTransactionModal && $selectedTransaction)
        @php
            $modalDate = $selectedTransaction->processed_at ?? $selectedTransaction->created_at;
            $modalUser = $this->resolveAssociatedUser($selectedTransaction);
        @endphp
        <div id="view-booking" class="view-booking-modal add-user-modal" style="display: flex;"
            wire:click.self="closeTransactionModal">
            <div class="view-booking-content">
                <div class="modal-header" style="margin-bottom:1.563vw">
                    <h2 class="page-title" style="font-size:1.146vw;font-weight: 600;line-height:1;">Transaction details
                    </h2>
                    <div class="header-actions">
                        <span class="close-btn" style="line-height:1;" wire:click="closeTransactionModal">
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
                    <h4 style="font-size:0.938vw;font-weight: 500; letter-spacing: -0.04em;">Transaction info</h4>
                    @can('Read Transactions')
                        <h5 style="cursor: pointer;"
                            wire:click="downloadTransactionDetails({{ $selectedTransaction->id }})">
                            <img src="{{ asset('assets/images/icons/download.svg') }}" alt="Download"
                                class="download-icon">
                            <small style="color:grey;font-size:0.938vw;">Download </small>
                        </h5>
                    @endcan
                </div>

                <div class="modal-section">
                    <div class="details-grid">
                        <div>Transaction ID</div>
                        <div style="cursor:pointer">{{ $selectedTransaction->transaction_ref ?? $selectedTransaction->id }}</div>
                        <div>Date</div>
                        <div>{{ $modalDate?->format('d M, Y') ?? '-' }}</div>
                        <div>Time</div>
                        <div>{{ $modalDate?->format('h:i A') ?? '-' }}</div>
                        <div>Transaction Type</div>
                        <div>{{ $this->getTransactionTypeLabel($selectedTransaction) }}</div>
                        <div>Payment Method</div>
                        <div>{{ $this->getPaymentMethodLabel($selectedTransaction) }}</div>
                        <div>Transaction amount</div>
                        <div>{{ $this->formatAmount($selectedTransaction->amount, $selectedTransaction->currency) }}</div>
                        <div>Associated user</div>
                        <div>{{ $modalUser?->name ?? '-' }}</div>
                        <div>Status</div>
                        <div class="status"
                            style="color:#17a55a;border-color:#17a55a;font-weight:500;background:rgba(23, 165, 90, 0.1)">
                            {{ $this->getStatusLabel($selectedTransaction) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($showFilterModal)
        <div class="modal filter-theme-modal" style="display:flex" wire:click.self="closeFilterModal">
            <div class="modal-content filter-modal" id="filter-theme-modal-content">
                <div class="modal_heaader">
                    <span class="close-modal" wire:click="closeFilterModal">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75"
                                stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
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
                <label style="color:#717171;font-weight:500;margin: 12px 0px 12px 0px;">Transaction type</label>
                <x-custom-select-livewire name="transaction_type" :options="[
                    ['value' => '', 'label' => 'Select transaction'],
                    ['value' => 'payout', 'label' => 'Payout'],
                    ['value' => 'booking_payment', 'label' => 'Booking Payment'],
                    ['value' => 'service_charges', 'label' => 'Service Charges'],
                ]" placeholder="Select transaction"
                    wireModel="tempTransactionType" class="form-input" />

                <label style="color:#717171; font-weight:500;margin: 12px 0px 12px 0px;">Payment method</label>
                <x-custom-select-livewire name="payment_method" :options="[
                    ['value' => '', 'label' => 'Select payment method'],
                    ['value' => 'mobile_money', 'label' => 'Mobile Money'],
                    ['value' => 'paystack', 'label' => 'Paystack'],
                    ['value' => 'card', 'label' => 'Card'],
                ]" placeholder="Select payment method"
                    wireModel="tempPaymentMethod" class="form-input" />

                <div class="form-actions">
                    <button type="button" class="reset-btn" wire:click="resetFilters">Reset</button>
                    <button type="button" class="submit-btn" wire:click="applyFilters">Apply Now</button>
                </div>
            </div>
        </div>
    @endif

    <script>
        $(document).on('click', '.showDeleteModal___', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $('#globalDeleteModal__' + id).css('display', 'block');
        })
        $(document).on('click', '.closeDeleteModal', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $('#globalDeleteModal__' + id).css('display', 'none');
        })
        $(document).on('click', '.cancel-delete-btn', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $('#globalDeleteModal__' + id).css('display', 'none');
        })
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.showDeleteModal___, .deleteModal').length) {
                $('.deleteModal').hide();
            }
        });
    </script>
</div>
