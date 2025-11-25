@extends('admin.layouts.app')

@section('title', 'Transactions')
@section('header', 'Transactions & Payments')
@section('content')
<style>
    .deleteModal {
        position: absolute;
        right: 50px;
        z-index: 99999;
    }
</style>
<livewire:admin.user-stats mode="transactions" />



<br>
<div class="container">
    <h1 class="page-title">All transactions</h1>
</div>
<div class="users-toolbar">
    <div class="toolbar-left">
        <button class="export-btn">
            <span class="download-icon"><img src="{{ asset('assets/images/icons/download.png') }}" alt=""></span>&nbsp;
            Export
            CSV
        </button>
        <!-- <button class="d-none add-user-btn" id="openAddUserModal">+ Add User</button> -->
    </div>
    <div class="toolbar-right">
        <input type="text" class="search-user" placeholder="Search user">
        <button class="filter-btn" id="openFilterModal"> Filter&nbsp;&nbsp;<span class="download-icon"><img
                    src="{{ asset('assets/images/icons/button-icon.png') }}" alt=""></span></button>
    </div>
</div>

<!-- booking -table-->
<table class="theme-table">
    <thead>
        <tr>
            <th><input type="checkbox"></th>
            <th class="sortable" data-column="0">Transaction ID <img src="{{ asset('assets/images/icons/sort.png') }}"
                    class="sort-icon">
            </th>
            <th class="sortable" data-column="4">Transaction type <img src="{{ asset('assets/images/icons/sort.png') }}"
                    class="sort-icon">
            </th>
            <th class="sortable">Date and time
                <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
            </th>



            <th class="sortable" data-column="1">Associated user/provider<img
                    src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>
            <th class="sortable" data-column="2">Payment method <img src="{{ asset('assets/images/icons/sort.png') }}"
                    class="sort-icon">
            </th>
            <th class="sortable" data-column="3">Amount Paid <img src="{{ asset('assets/images/icons/sort.png') }}"
                    class="sort-icon"></th>


            <th class="sortable" data-column="6"> Status <img src="{{ asset('assets/images/icons/sort.png') }}"
                    class="sort-icon"></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><input type="checkbox"></td>
            <td style="font-weight:500;">12345</td>
            <td style="font-weight:500;">Payout</td>
            <td><span class="date" style="font-weight:500;">Jan,2025-01-31</span>
                <br>
                <small class="time m-0">12:00pm</small>

            </td>


            <td>
                <div class="user-info">
                    <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                    <div>
                        <p class="user-name" style="font-weight:500;">Johnbosco Davies</p>

                    </div>
                </div>
            </td>
            <td style="font-weight:500;">Mobile money</td>
            <td style="font-weight:500;">$1200</td>


            <td><span class="status pending">Pending</span></td>
            <td style="position:relative;">
                <div class="actions-dropdown">
                    <button class="actions-btn"> <img src="{{ asset('assets/images/icons/three-dots.png') }}"
                            class="dots-img "></button>
                    <div class="actions-menu">
                        <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                alt="">
                            View
                            details</a>
                        <a href="#" class="initiateBtn showDeleteModal___" data-id="1" data-user="Mike Brown">
                            <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                        </a>

                    </div>

                </div>
                <div id="globalDeleteModal__1" class="deleteModal" style="display: none;position:absolute;    top: 2.5vw; right: 3vw;">
                    <div class="delete-card">
                        <div class="delete-card-header">
                            <h3 class="delete-title">Initiate payout</h3>
                            <span class="delete-close closeDeleteModal" data-id="1">&times;</span>
                        </div>
                        <p class="delete-text">Are you sure you want to initiate payout to this user?</p>
                        <div class="delete-actions justify-content-start">

                            <button class="cancel-delete-btn" data-id="1">Cancel</button>
                            <button class="confirm-delete-btn" style="background-color:#004E42 !important;color:#fff;">Initiate</button>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="font-weight:500;"><input type="checkbox"></td>
            <td style="font-weight:500;">12345</td>
            <td style="font-weight:500;">Booking Payment</td>
            <td><span class="date" style="font-weight:500;">Jan,2025-01-31</span>
                <br>
                <small class="time m-0">12:00pm</small>

            </td>

            <td>
                <div class="user-info">
                    <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                    <div>
                        <p class="user-name" style="font-weight:500;">Johnbosco Davies</p>

                    </div>
                </div>
            </td>
            <td style="font-weight:500;">Card</td>
            <td style="font-weight:500;">$1200</td>



            <td><span class="status active">Completed</span></td>
            <td style="position:relative;">
                <div class="actions-dropdown">
                    <button class="actions-btn"> <img src="{{ asset('assets/images/icons/three-dots.png') }}"
                            class="dots-img "></button>
                    <div class="actions-menu">
                        <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                alt="">
                            View
                            details</a>
                        <a href="#" class="initiateBtn showDeleteModal___" data-id="2" data-user="Mike Brown">
                            <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                        </a>

                    </div>

                </div>
                <div id="globalDeleteModal__2" class="deleteModal" style="display: none;position:absolute;    top: 2.5vw; right: 3vw;">
                    <div class="delete-card">
                        <div class="delete-card-header">
                            <h3 class="delete-title">Initiate payout</h3>
                            <span class="delete-close closeDeleteModal" data-id="2">&times;</span>
                        </div>
                        <p class="delete-text">Are you sure you want to initiate payout to this user?</p>
                        <div class="delete-actions justify-content-start">

                            <button class="cancel-delete-btn" data-id="2">Cancel</button>
                            <button class="confirm-delete-btn" style="background-color:#004E42 !important;color:#fff;">Initiate</button>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <td><input type="checkbox"></td>
            <td style="font-weight:500;">12345</td>
            <td style="font-weight:500;">Service Charge</td>
            <td><span class="date" style="font-weight:500;">Jan,2025-01-31</span>
                <br>
                <small class="time m-0">12:00pm</small>

            </td>

            <td>
                <div class="user-info">
                    <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                    <div>
                        <p class="user-name" style="font-weight:500;">Johnbosco Davies</p>

                    </div>
                </div>
            </td>
            <td style="font-weight:500;">Pay Stock</td>
            <td style="font-weight:500;">$1200</td>



            <td><span class="status active">Completed</span></td>
            <td style="position:relative;">
                <div class="actions-dropdown">
                    <button class="actions-btn"> <img src="{{ asset('assets/images/icons/three-dots.png') }}"
                            class="dots-img "></button>
                    <div class="actions-menu">
                        <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                alt="">
                            View
                            details</a>
                        <a href="#" class="initiateBtn showDeleteModal___" data-id="3" data-user="Mike Brown">
                            <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                        </a>

                    </div>

                </div>
                <div id="globalDeleteModal__3" class="deleteModal" style="display: none;position:absolute;    top: 2.5vw; right: 3vw;">
                    <div class="delete-card">
                        <div class="delete-card-header">
                            <h3 class="delete-title">Initiate payout</h3>
                            <span class="delete-close closeDeleteModal" data-id="3">&times;</span>
                        </div>
                        <p class="delete-text">Are you sure you want to initiate payout to this user?</p>
                        <div class="delete-actions justify-content-start">

                            <button class="cancel-delete-btn" data-id="3">Cancel</button>
                            <button class="confirm-delete-btn" style="background-color:#004E42 !important;color:#fff;">Initiate</button>
                        </div>
                    </div>
                </div>
            </td>
        </tr>


        <tr>
            <td><input type="checkbox"></td>
            <td style="font-weight:500;">12345</td>
            <td style="font-weight:500;">Refund</td>
            <td><span class="date" style="font-weight:500;">Jan,2025-01-31</span>
                <br>
                <small class="time m-0">12:00pm</small>

            </td>

            <td>
                <div class="user-info">
                    <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                    <div>
                        <p class="user-name" style="font-weight:500;">Johnbosco Davies</p>

                    </div>
                </div>
            </td>
            <td style="font-weight:500;">Card</td>
            <td style="font-weight:500;">$1200</td>



            <td><span class="status active">Completed</span></td>
            <td style="position:relative;">
                <div class="actions-dropdown">
                    <button class="actions-btn"> <img src="{{ asset('assets/images/icons/three-dots.png') }}"
                            class="dots-img "></button>
                    <div class="actions-menu">
                        <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                alt="">
                            View
                            details</a>
                        <a href="#" class="initiateBtn showDeleteModal___" data-id="4" data-user="Mike Brown">
                            <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                        </a>

                    </div>

                </div>
                <div id="globalDeleteModal__4" class="deleteModal" style="display: none;position:absolute;    top: 2.5vw; right: 3vw;">
                    <div class="delete-card">
                        <div class="delete-card-header">
                            <h3 class="delete-title">Initiate payout</h3>
                            <span class="delete-close closeDeleteModal" data-id="4">&times;</span>
                        </div>
                        <p class="delete-text">Are you sure you want to initiate payout to this user?</p>
                        <div class="delete-actions justify-content-start">

                            <button class="cancel-delete-btn" data-id="4">Cancel</button>
                            <button class="confirm-delete-btn" style="background-color:#004E42 !important;color:#fff;">Initiate</button>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox"></td>
            <td style="font-weight:500;">12345</td>
            <td style="font-weight:500;">Service Charge</td>
            <td><span class="date" style="font-weight:500;">Jan,2025-01-31</span>
                <br>
                <small class="time m-0">12:00pm</small>

            </td>

            <td>
                <div class="user-info">
                    <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                    <div>
                        <p class="user-name" style="font-weight:500;">Johnbosco Davies</p>

                    </div>
                </div>
            </td>
            <td style="font-weight:500;">Pay Stock</td>
            <td style="font-weight:500;">$1200</td>



            <td><span class="status active">Completed</span></td>
            <td style="position:relative;">
                <div class="actions-dropdown">
                    <button class="actions-btn"> <img src="{{ asset('assets/images/icons/three-dots.png') }}"
                            class="dots-img "></button>
                    <div class="actions-menu">
                        <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                alt="">
                            View
                            details</a>
                        <a href="#" class="initiateBtn showDeleteModal___" data-id="5" data-user="Mike Brown">
                            <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                        </a>

                    </div>

                </div>
                <div id="globalDeleteModal__5" class="deleteModal" style="display: none;position:absolute;    top: 2.5vw; right: 3vw;">
                    <div class="delete-card">
                        <div class="delete-card-header">
                            <h3 class="delete-title">Initiate payout</h3>
                            <span class="delete-close closeDeleteModal" data-id="5">&times;</span>
                        </div>
                        <p class="delete-text">Are you sure you want to initiate payout to this user?</p>
                        <div class="delete-actions justify-content-start">

                            <button class="cancel-delete-btn" data-id="5">Cancel</button>
                            <button class="confirm-delete-btn" style="background-color:#004E42 !important;color:#fff;">Initiate</button>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox"></td>
            <td style="font-weight:500;">12345</td>
            <td style="font-weight:500;">Service Charge</td>
            <td><span class="date" style="font-weight:500;">Jan,2025-01-31</span>
                <br>
                <small class="time m-0">12:00pm</small>

            </td>

            <td>
                <div class="user-info">
                    <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                    <div>
                        <p class="user-name" style="font-weight:500;">Johnbosco Davies</p>

                    </div>
                </div>
            </td>
            <td style="font-weight:500;">Pay Stock</td>
            <td style="font-weight:500;">$1200</td>



            <td><span class="status active">Completed</span></td>
            <td style="position:relative;">
                <div class="actions-dropdown">
                    <button class="actions-btn"> <img src="{{ asset('assets/images/icons/three-dots.png') }}"
                            class="dots-img "></button>
                    <div class="actions-menu">
                        <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                alt="">
                            View
                            details</a>
                        <a href="#" class="initiateBtn showDeleteModal___" data-id="6" data-user="Mike Brown">
                            <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                        </a>

                    </div>

                </div>
                <div id="globalDeleteModal__6" class="deleteModal" style="display: none;position:absolute;    top: 2.5vw; right: 3vw;">
                    <div class="delete-card">
                        <div class="delete-card-header">
                            <h3 class="delete-title">Initiate payout</h3>
                            <span class="delete-close closeDeleteModal" data-id="6">&times;</span>
                        </div>
                        <p class="delete-text">Are you sure you want to initiate payout to this user?</p>
                        <div class="delete-actions justify-content-start">

                            <button class="cancel-delete-btn" data-id="6">Cancel</button>
                            <button class="confirm-delete-btn" style="background-color:#004E42 !important;color:#fff;">Initiate</button>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td><input type="checkbox"></td>
            <td style="font-weight:500;">12345</td>
            <td style="font-weight:500;">Payout</td>
            <td><span class="date" style="font-weight:500;">Jan,2025-01-31</span>
                <br>
                <small class="time m-0">12:00pm</small>

            </td>


            <td>
                <div class="user-info">
                    <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                    <div>
                        <p class="user-name" style="font-weight:500;">Johnbosco Davies</p>

                    </div>
                </div>
            </td>
            <td style="font-weight:500;">Mobile money</td>
            <td style="font-weight:500;">$1200</td>


            <td><span class="status pending">Pending</span></td>
            <td style="position:relative;">
                <div class="actions-dropdown">
                    <button class="actions-btn"> <img src="{{ asset('assets/images/icons/three-dots.png') }}"
                            class="dots-img "></button>
                    <div class="actions-menu">
                        <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                alt="">
                            View
                            details</a>
                        <a href="#" class="initiateBtn showDeleteModal___" data-id="7" data-user="Mike Brown">
                            <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                        </a>

                    </div>

                </div>
                <div id="globalDeleteModal__7" class="deleteModal" style="display: none;position:absolute;    top: 2.5vw; right: 3vw;">
                    <div class="delete-card">
                        <div class="delete-card-header">
                            <h3 class="delete-title">Initiate payout</h3>
                            <span class="delete-close closeDeleteModal" data-id="7">&times;</span>
                        </div>
                        <p class="delete-text">Are you sure you want to initiate payout to this user?</p>
                        <div class="delete-actions justify-content-start">

                            <button class="cancel-delete-btn" data-id="7">Cancel</button>
                            <button class="confirm-delete-btn" style="background-color:#004E42 !important;color:#fff;">Initiate</button>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>


<!-- Pagination -->
<div class="pagination">
    <button class="page-btn prev" disabled>‹</button>
    <button class="page-btn active">1</button>
    <button class="page-btn">2</button>
    <button class="page-btn">3</button>
    <button class="page-btn">4</button>
    <button class="page-btn">5</button>
    <span class="dots">...</span>
    <button class="page-btn">14</button>
    <button class="page-btn next">›</button>

    <div class="rows-control">
        <label for="rows">Show</label>
        <select id="rows">
            <option>10 rows</option>
            <option>25 rows</option>
            <option>50 rows</option>
            <option>100 rows</option>
        </select>
    </div>
</div>





<!-- transction -->


<div id="view-booking" class="view-booking-modal">
    <div class="view-booking-content">
        <div class="modal-header">
            <h2 class="page-title">Transaction details</h2>
            <div class="header-actions">

                <span class="close-btn" onclick="closeBookingModal()">&times;</span>
            </div>
        </div>
        <div class="service-header-icons">
            <h4 style="font-size: 1vw">Transaction info</h4>
            <h5> <img src="{{ asset('assets/images/icons/download.png') }}" alt="Download" class="download-icon">
                <small style="color:grey;">Download </small>
            </h5>
        </div>

        <div class="modal-section">

            <div class="details-grid">
                <div>Transaction ID</div>
                <div>12345</div>
                <div>Date</div>
                <div>12 Jan, 2025</div>
                <div>Time</div>
                <div>12:00 PM</div>
                <div>Transaction Type</div>
                <div>Booking payment</div>
                <div>Payment Method</div>
                <div>Google pay</div>
                <div>Transaction amount</div>
                <div>$40</div>
                <div>Associated user</div>
                <div>Johnbosco Davies</div>
                <div>Status</div>
                <div class="status " style="color: goldenrod; border: 1px solid goldenrod; width:6vw;">Pending</div>
            </div>
        </div>


    </div>
</div>
<!-- this modal is end here -->






<!-- in this page its not working -->
<div id="addUserModal" class="modal">
    <div class="modal-content add-user-modal">
        <span class="close-modal" id="closeAddUserModal">&times;</span>
        <h3>Add User</h3>
        <form>
            <label>Name</label>
            <input type="text" class="form-input" placeholder="Enter name">
            <label>Email</label>
            <input type="email" class="form-input" placeholder="Enter email">
            <label>Home Address</label>
            <input type="text" class="form-input" placeholder="Enter home address">
            <label>Phone Number</label>
            <input type="text" class="form-input" placeholder="Enter phone number">
            <div class="form-actions">
                <button type="button" class="cancel-btn">Cancel</button>
                <button type="submit" class="submit-btn"> <i class="fa-solid fa-plus mr-3"></i> Add User</button>
            </div>
        </form>
    </div>
</div>
<!-- in this page its not working -->



<!-- Filter Modal -->
<div id="filterModal" class="modal filter-theme-modal">

    <div class="modal-content filter-modal" id="filter-theme-modal-content">
        <span class="close-modal" id="closeFilterModal">&times;</span>
        <h3>Filter</h3>
        <label style='color:#717171'>Select Date</label>
        <div class=" row mt-3">
            <div class='col-6'>
                <span>From:</span>
                <input type="date" class="form-input mt-2" wire:model="fromDate">
            </div>
            <div class="col-6">
                <span>To:</span>
                <input type="date" class="form-input mt-2" wire:model="toDate">
            </div>
        </div>
        <label>Transaction type</label>
        <x-custom-select name="transaction_type" :options="[
                ['value' => '', 'label' => 'Select transaction'],
                ['value' => 'payout', 'label' => 'Payout'],
                ['value' => 'booking_payment', 'label' => 'Booking Payment'],
                ['value' => 'service_charges', 'label' => 'Service Charges'],
            ]" placeholder="Select transaction"
            wireModel="transaction_type"
            class="form-input" />

        <label>Payment method</label>
        <x-custom-select name="payment_method" :options="[
                ['value' => '', 'label' => 'Select payment method'],
                ['value' => '0', 'label' => 'Mobile Money'],
                ['value' => '1', 'label' => 'Paystack'],
                ['value' => '2', 'label' => 'Card'],
            ]" placeholder="Select payment method"
            wireModel="payment_method" class="form-input" />


        Payment method
        <div class="form-actions">
            <button type="button" class="reset-btn">Reset</button>
            <button type="button" class="submit-btn">Apply Now</button>
        </div>
    </div>
</div>




@endsection

@push('styles')
@endpush
@push('scripts')
<script>
    $('#openFilterModal').on('click', function(e) {
        e.preventDefault();
        $('#filterModal').css('display', 'flex');
        $("#openFilterModal").removeClass('tab-active');
    })
    $('#closeFilterModal').on('click', function(e) {
        e.preventDefault();
        $('#filterModal').css('display', 'none');
        $("#openFilterModal").removeClass('tab-active');
    })
    // $('#filterModal').on('click', function(e) {
    //     e.preventDefault();
    //     $('#filterModal').css('display', 'none');
    //     $("#openFilterModal").removeClass('tab-active');
    // })
    // $("#filter-theme-modal-content").on('click',function(e){
    //      e.preventDefault();
    //        $('#filterModal').css('display','flex');

    // })
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


    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("globalDeleteModal");
        const confirmBtn = document.getElementById("confirmDeleteBtn");
        const cancelBtn = document.getElementById("cancelDeleteBtn");
        const closeBtn = document.getElementById("closeDeleteModal");

        document.addEventListener("click", function(e) {
            if (e.target.closest(".showDeleteModal")) {
                const btn = e.target.closest(".showDeleteModal");
                const id = btn.getAttribute("data-id");
                const rect = btn.getBoundingClientRect();

                const modalWidth = window.innerWidth * 0.40;

                // ✅ Aur zyada left shift (360 → 450)
                let topPos = window.scrollY + rect.top + 40;
                let leftPos = rect.left - 620;

                // ✅ Prevent overflow (right side)
                if (leftPos + modalWidth > window.innerWidth - 20) {
                    leftPos = window.innerWidth - modalWidth - 20;
                }

                // ✅ Prevent overflow (left side)
                if (leftPos < 20) {
                    leftPos = 20;
                }

                modal.style.display = "block";
                modal.style.position = "absolute";
                modal.style.top = `${topPos}px`;
                modal.style.left = `${leftPos}px`;

                modal.dataset.id = id;
            }
        });

        // ✅ Close modal
        [cancelBtn, closeBtn].forEach(btn => {
            btn.addEventListener("click", () => {
                modal.style.display = "none";
            });
        });

        // ✅ Close on outside click
        document.addEventListener("click", function(e) {
            if (!e.target.closest(".deleteModal") && !e.target.closest(".showDeleteModal")) {
                modal.style.display = "none";
            }
        });

        // ✅ Confirm delete
        confirmBtn.addEventListener("click", function() {
            const id = modal.dataset.id;
            if (window.Livewire) {
                Livewire.dispatch('delete', {
                    id: id
                });
            }
            modal.style.display = "none";
        });
    });
</script>
@endpush