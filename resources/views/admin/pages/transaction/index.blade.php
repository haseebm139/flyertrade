@extends('admin.layouts.app')

@section('title', 'Transactions')
@section('header', 'Transactions & Payments')
@section('content')
    <livewire:admin.user-stats mode="transactions"/>
     
    <br>
    <div class="container">
        <h1 class="page-title">All transactions</h1>
    </div>
    <div class="users-toolbar">
        <div class="toolbar-left">
            <button class="export-btn">
                <span class="download-icon"><img src="{{ asset('assets/images/icons/download.png') }}"
                        alt=""></span> Export CSV
            </button>
            <button class="d-none add-user-btn" id="openAddUserModal">+ Add User</button>
        </div>
        <div class="toolbar-right">
            <input type="text" class="search-user" placeholder="Search user">
            <button class="filter-btn" id="openFilterModal"> <span class="download-icon"><img
                        src="{{ asset('assets/images/icons/button-icon.png') }}" alt=""></span>Filter</button>
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
                <td>12345</td>
                <td>Payout</td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>


                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>Mobile money</td>
                <td>$1200</td>


                <td><span class="status pending">Pending</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                    alt=""> View
                                details</a>
                            <a href="#"><img src="{{ asset('assets/images/icons/init.png') }}" alt="">
                                Initiate payout</a>

                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Booking Payment</td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>

                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>Card</td>
                <td>$1200</td>



                <td><span class="status active">Completed</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                    alt=""> View
                                details</a>
                            <a href="#" class="initiateBtn" data-user="Mike Brown">
                                <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                            </a>

                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Service Charge</td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>

                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>Pay Stock</td>
                <td>$1200</td>



                <td><span class="status active">Completed</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                    alt=""> View
                                details</a>
                            <a href="#" class="initiateBtn" data-user="Mike Brown">
                                <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                            </a>

                        </div>
                    </div>
                </td>
            </tr>


            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Refund</td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>

                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>Card</td>
                <td>$1200</td>



                <td><span class="status active">Completed</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                    alt=""> View
                                details</a>
                            <a href="#" class="initiateBtn" data-user="Mike Brown">
                                <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                            </a>

                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Service Charge</td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>

                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>Pay Stock</td>
                <td>$1200</td>



                <td><span class="status active">Completed</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                    alt=""> View
                                details</a>
                            <a href="#" class="initiateBtn" data-user="Mike Brown">
                                <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                            </a>

                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Service Charge</td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>

                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>Pay Stock</td>
                <td>$1200</td>



                <td><span class="status active">Completed</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                    alt=""> View
                                details</a>
                            <a href="#" class="initiateBtn" data-user="Mike Brown">
                                <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                            </a>

                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>Payout</td>
                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>


                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>
                <td>Mobile money</td>
                <td>$1200</td>


                <td><span class="status pending">Pending</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}"
                                    alt=""> View
                                details</a>
                            <a href="#" class="initiateBtn" data-user="Mike Brown">
                                <img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout
                            </a>

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


    <!-- initiate moda -->
    <div id="initiateModal" class="initiate-modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h3 class="heading-int">Initiate payout</h3>
            <p id="modalMessage">Are you sure you want to initiate payout to this user?</p>
            <div class="modal-actions">
                <button class="cancel-btn">Cancel</button>
                <button class="init-btn">Initiate</button>
            </div>
        </div>
    </div>

    <!-- end -->



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
                    <button type="submit" class="submit-btn"> + Add User</button>
                </div>
            </form>
        </div>
    </div>
    <!-- in this page its not working -->



    <!-- Filter Modal -->
    <div id="filterModal" class="modal filter-theme-modal">




        <div class="modal-content filter-modal">
            <span class="close-modal" id="closeFilterModal">&times;</span>
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
            <label>Transaction type</label>
            <select class="form-input" wire:model="status">
                <option value="">Select transaction</option>
                <option value="0">Payout</option>
                <option value="0">Booking Payment</option>
                <option value="0">Service Charges</option>

            </select>


            <label>Payment method</label>
            <select class="form-input" wire:model="status">
                <option value="">Select payment method</option>
                <option value="0">Mobile Money</option>
                <option value="1">paystack</option>
                <option value="2">Card</option>
            </select>


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
        // Actions dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Handle actions dropdown
            const actionBtns = document.querySelectorAll('.actions-btn');
            actionBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;

                    // Close all other menus
                    document.querySelectorAll('.actions-menu').forEach(m => {
                        if (m !== menu) m.style.display = 'none';
                    });

                    // Toggle current menu
                    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.actions-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            });

            // Handle initiate payout buttons
            const initiateBtns = document.querySelectorAll('.initiateBtn');
            initiateBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const userName = this.getAttribute('data-user') || 'this user';
                    document.getElementById('modalMessage').textContent =
                        `Are you sure you want to initiate payout to ${userName}?`;
                    document.getElementById('initiateModal').style.display = 'block';
                });
            });

            // Handle filter modal
            const filterBtn = document.getElementById('openFilterModal');
            const filterModal = document.getElementById('filterModal');
            const closeFilterModal = document.getElementById('closeFilterModal');

            if (filterBtn && filterModal) {
                filterBtn.addEventListener('click', function() {
                    filterModal.style.display = 'block';
                });
            }

            if (closeFilterModal && filterModal) {
                closeFilterModal.addEventListener('click', function() {
                    filterModal.style.display = 'none';
                });
            }

            // Handle export functionality
            const exportBtn = document.querySelector('.export-btn');
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    exportToCSV();
                });
            }

            // Handle search functionality
            const searchInput = document.querySelector('.search-user');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    filterTable(this.value);
                });
            }

            // Handle pagination
            const pageBtns = document.querySelectorAll('.page-btn:not(.prev):not(.next)');
            pageBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    pageBtns.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');

                    // Here you would typically load new page data
                    console.log('Page changed to:', this.textContent);
                });
            });

            // Handle rows per page
            const rowsSelect = document.getElementById('rows');
            if (rowsSelect) {
                rowsSelect.addEventListener('change', function() {
                    console.log('Rows per page changed to:', this.value);
                    // Here you would typically reload the table with new row count
                });
            }
        });

        // View transaction modal
        function openBookingModal(transactionId = null) {
            const modal = document.getElementById('view-booking');
            if (modal) {
                modal.style.display = 'flex';
            }
        }

        function closeBookingModal() {
            const modal = document.getElementById('view-booking');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const bookingModal = document.getElementById('view-booking');
            const filterModal = document.getElementById('filterModal');
            const initiateModal = document.getElementById('initiateModal');

            if (event.target === bookingModal) {
                bookingModal.style.display = 'none';
            }
            if (event.target === filterModal) {
                filterModal.style.display = 'none';
            }
            if (event.target === initiateModal) {
                initiateModal.style.display = 'none';
            }
        }

        // Export to CSV functionality
        function exportToCSV() {
            const table = document.querySelector('.theme-table');
            const rows = Array.from(table.querySelectorAll('tr'));

            let csv = [];
            rows.forEach(row => {
                const cells = Array.from(row.querySelectorAll('td, th'));
                const rowData = cells.map(cell => {
                    // Skip action cells and checkboxes
                    if (cell.querySelector('.actions-dropdown') || cell.querySelector(
                            'input[type="checkbox"]')) {
                        return '';
                    }
                    return '"' + cell.textContent.trim().replace(/"/g, '""') + '"';
                }).filter(cell => cell !== '""'); // Remove empty cells

                if (rowData.length > 0) {
                    csv.push(rowData.join(','));
                }
            });

            const csvContent = csv.join('\n');
            const blob = new Blob([csvContent], {
                type: 'text/csv'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'transactions.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Filter table functionality
        function filterTable(searchTerm) {
            const table = document.querySelector('.theme-table tbody');
            const rows = Array.from(table.querySelectorAll('tr'));

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matches = text.includes(searchTerm.toLowerCase());
                row.style.display = matches ? '' : 'none';
            });
        }

        // Initiate payout functionality
        function initiatePayout() {
            // Here you would typically make an API call
            console.log('Initiating payout...');
            alert('Payout initiated successfully!');
            document.getElementById('initiateModal').style.display = 'none';
        }

        // Cancel payout functionality
        function cancelPayout() {
            document.getElementById('initiateModal').style.display = 'none';
        }

        // Add event listeners for modal buttons
        document.addEventListener('DOMContentLoaded', function() {
            const initBtn = document.querySelector('.init-btn');
            const cancelBtn = document.querySelector('.cancel-btn');

            if (initBtn) {
                initBtn.addEventListener('click', initiatePayout);
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', cancelPayout);
            }
        });
    </script>
@endpush
