@extends('admin.layouts.app')

@section('title', 'Disputes & Complaints')
@section('header', 'Disputes & Complaints')
@section('content')




    <!-- Top Stat Cards -->
    <div class=" combo-class">
        <div class="dashboard-card">
            <div>
                <h6>Total dispute</h6>
                <h2>200</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/dispute_icon.png') }}" alt="User Icon">
            </div>
        </div>
        <div class="dashboard-card">
            <div>
                <h6>Resolved dispute</h6>
                <h2>198</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/dispute_icon.png') }}" alt="User Icon">
            </div>
        </div>
        <div class="dashboard-card">
            <div>
                <h6>Pending dispute</h6>
                <h2>2</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/dispute_icon.png') }}" alt="User Icon">
            </div>
        </div>

    </div>
    <br>
    <div class="container">
        <h1 class="page-title">All dispute</h1>
    </div>
    <div class="users-toolbar">
        <div class="toolbar-left">
            <button class="export-btn">
                <span class="download-icon"><img src="{{ asset('assets/images/icons/download.png') }}" alt=""
                        class="btn-icons"></span> Export CSV
            </button>

        </div>
        <div class="toolbar-right">
            <input type="text" class="search-user" placeholder="Search user">
            <button class="filter-btn" id="openFilterModal"> <span class="download-icon"><img
                        src="{{ asset('assets/images/icons/button-icon.png') }}" alt=""
                        class="btn-icons"></span>Filter</button>
        </div>
    </div>

    <!-- booking -table-->
    <table class="theme-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th class="sortable" data-column="0">Booking ID <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon">
                </th>

                <th class="sortable">Date created
                    <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>



                <th class="sortable" data-column="1">Affected user<img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>
                <th class="sortable" data-column="2">Service Typer <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>
                <th class="sortable" data-column="3">Dispute issue <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>


                <th class="sortable" data-column="6"> Status <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox"></td>
                <td onclick="openBookingModal()">12345</td>

                <td><span class="date">Jan,2025-01-31</span>
                    <br>
                    <small class="time">12:00pm</small>

                </td>
                <td>
                    <div class="user-info">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div>
                            <span class="user-theme-name ">Johnbosco Davies</span>

                        </div>
                    </div>
                </td>

                <td>Plubming</td>
                <td> Lorem ipsum dolor sit amet consectetur adipisicing elit. Non, voluptates? Lorem ipsum dolor sit amet.

                </td>


           <td>
 <div class="status-dropdown">
  <span class="status active" onclick="toggleDropdown(this)">
    Unresolved
    <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" 
      viewBox="0 0 24 24" fill="none" stroke="#d4aa00" stroke-width="2" 
      stroke-linecap="round" stroke-linejoin="round">
      <polyline points="6 9 12 15 18 9"></polyline>
    </svg>
  </span>
  <ul class="dropdown-menu" style="display: none;">
    <li onclick="setStatus(this, 'Resolved')">Resolved</li>
    <li onclick="setStatus(this, 'Unresolved')">Unresolved</li>
  </ul>
</div>
</td>

                  <td>
                <div class="actions-dropdown">
                    <button class="actions-btn" fdprocessedid="3p4nw"> <img src="http://127.0.0.1:8000/assets/images/icons/three-dots.png" class="dots-img "></button>
                    <div class="actions-menu" style="display: none;">
                        <a onclick="openBookingModal()"><img src="http://127.0.0.1:8000/assets/images/icons/eye.png" alt="">
                            View
                            details</a>
                                    <a href="#" class="initiateBtn" data-user="Mike Brown">
  <img src="http://127.0.0.1:8000/assets/images/icons/init.png" alt=""> Initiate payout
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





    <div id="view-booking" class="view-booking-modal">
        <div class="view-booking-content">
            <div class="modal-header">
                <h2>Booking details</h2>
                <div class="header-actions">

                    <span class="close-btn" onclick="closeBookingModal()">&times;</span>
                </div>
            </div>
            <div class="service-header-icons">
                <h4>Service details</h4>
                <h5> <img src="{{ asset('assets/images/icons/download.png') }}" alt="Download" class="download-icon">
                    <small style="color:grey;">Download </small>
                </h5>
            </div>

            <div class="modal-section">

                <div class="details-grid">
                    <div>Booking ID</div>
                    <div>12345</div>
                    <div>Date</div>
                    <div>12 Jan, 2025</div>
                    <div>Time</div>
                    <div>12:00 PM</div>
                    <div>Duration</div>
                    <div>2 hours</div>
                    <div>Location</div>
                    <div>Villa 27, Street 12, Al Barsha 2, Dubai</div>
                    <div>Service type</div>
                    <div>AC repair</div>
                    <div>Service cost</div>
                    <div>$40</div>
                    <div>Status</div>
                    <div class="status completed">Completed</div>
                </div>
            </div>

            <div class="modal-section">
                <br>
                <h4>Users details</h4>

                <div class="details-grid">
                    <div>Service provider</div>
                    <div class="text-end">Johnbosco Davies</div>
                    <div>Service user</div>
                    <div class="text-end">Johnbosco Davies</div>
                </div>
            </div>

            <div class="modal-section">
                <br>
                <h4>Dispute issue</h4>

                <div class="dispute-text">
                    <div>Service provider did not show up at the scheduled time and did not provide prior notice.</div>

                </div>
            </div>
        </div>
    </div>





    <!-- Filter Modal -->
    <div id="filterModal" class="modal filter-theme-modal">

        <div class="modal-content filter-modal">
            <span class="close-modal" id="closeFilterModal">&times;</span>
            <h3>Filter</h3>
            <label>Select Date</label>
            <div class="date-range">
                <div>
                    <span>From:</span>
                    <input type="date" class="form-input" id="fromDate">
                </div>
                <div>
                    <span>To:</span>
                    <input type="date" class="form-input" id="toDate">
                </div>
            </div>

            <label>Status</label>
            <select class="form-input" id="statusFilter">
                <option value="">Select status</option>
                <option value="resolved">Resolved</option>
                <option value="unresolved">Unresolved</option>
            </select>

            <div class="form-actions">
                <button type="button" class="reset-btn" onclick="resetFilters()">Reset</button>
                <button type="button" class="submit-btn" onclick="applyFilters()">Apply Now</button>
            </div>
        </div>
    </div>
    <script>
        // Filter modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const openFilterBtn = document.getElementById("openFilterModal");
            const closeFilterBtn = document.getElementById("closeFilterModal");
            const filterModal = document.getElementById("filterModal");

            if (openFilterBtn && filterModal) {
                openFilterBtn.addEventListener("click", function() {
                    filterModal.style.display = "flex";
                });
            }

            if (closeFilterBtn && filterModal) {
                closeFilterBtn.addEventListener("click", function() {
                    filterModal.style.display = "none";
                });
            }

            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === filterModal) {
                    filterModal.style.display = "none";
                }
            });
        });

        // Booking modal functionality
        function openBookingModal() {
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

        // Status dropdown functionality
        function toggleDropdown(trigger) {
            const menu = trigger.nextElementSibling;
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        }

        function setStatus(option, status) {
            const dropdown = option.closest(".status-dropdown");
            const statusEl = dropdown.querySelector(".status");

            const statusClassMap = {
                active: "active",
                inactive: "inactive",
                resolved: "resolved",
                unresolved: "inactive"
            };

            statusEl.classList.remove(...Object.values(statusClassMap));
            statusEl.textContent = status;

            const className = statusClassMap[status.toLowerCase()];
            if (className) {
                statusEl.classList.add(className);
            }
            dropdown.querySelector(".dropdown-menu").style.display = "none";
        }

        // Actions dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const actionBtns = document.querySelectorAll('.actions-btn');
            actionBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;
                    document.querySelectorAll('.actions-menu').forEach(m => {
                        if (m !== menu) m.style.display = 'none';
                    });
                    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.actions-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            });
        });

        // Export functionality
        function exportToCSV() {
            const table = document.querySelector('.theme-table');
            if (!table) return;

            const rows = Array.from(table.querySelectorAll('tr'));
            let csv = [];

            rows.forEach(row => {
                const cells = Array.from(row.querySelectorAll('td, th'));
                const rowData = cells.map(cell => {
                    if (cell.querySelector('.actions-dropdown') || cell.querySelector(
                            'input[type="checkbox"]')) {
                        return '';
                    }
                    return '"' + cell.textContent.trim().replace(/"/g, '""') + '"';
                }).filter(cell => cell !== '""');

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
            a.download = 'disputes.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        // Search functionality
        function filterTable(searchTerm) {
            const table = document.querySelector('.theme-table tbody');
            if (!table) return;

            const rows = Array.from(table.querySelectorAll('tr'));
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matches = text.includes(searchTerm.toLowerCase());
                row.style.display = matches ? '' : 'none';
            });
        }

        // Filter functions
        function resetFilters() {
            document.getElementById('fromDate').value = '';
            document.getElementById('toDate').value = '';
            document.getElementById('statusFilter').value = '';

            // Show all rows
            const table = document.querySelector('.theme-table tbody');
            if (table) {
                const rows = table.querySelectorAll('tr');
                rows.forEach(row => {
                    row.style.display = '';
                });
            }

            // Close modal
            const filterModal = document.getElementById('filterModal');
            if (filterModal) {
                filterModal.style.display = 'none';
            }
        }

        function applyFilters() {
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;
            const status = document.getElementById('statusFilter').value;

            const table = document.querySelector('.theme-table tbody');
            if (!table) return;

            const rows = table.querySelectorAll('tr');
            rows.forEach(row => {
                let showRow = true;

                // Date filter (simplified - you can enhance this)
                if (fromDate || toDate) {
                    const dateCell = row.cells[2]; // Date column
                    if (dateCell) {
                        const cellText = dateCell.textContent.toLowerCase();
                        // Basic date filtering - you can enhance this
                        if (fromDate && !cellText.includes(fromDate.substring(0, 4))) {
                            showRow = false;
                        }
                    }
                }

                // Status filter
                if (status) {
                    const statusCell = row.querySelector('.status');
                    if (statusCell) {
                        const statusText = statusCell.textContent.toLowerCase();
                        if (statusText !== status) {
                            showRow = false;
                        }
                    }
                }

                row.style.display = showRow ? '' : 'none';
            });

            // Close modal
            const filterModal = document.getElementById('filterModal');
            if (filterModal) {
                filterModal.style.display = 'none';
            }
        }

        // Initialize search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-user');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    filterTable(this.value);
                });
            }

            const exportBtn = document.querySelector('.export-btn');
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    exportToCSV();
                });
            }
        });
    </script>


@endsection
