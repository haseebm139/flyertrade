@extends('admin.layouts.app')

@section('title', 'Disputes & Complaints')
@section('header', 'Disputes & Complaints')
@section('content')




    <!-- Top Stat Cards -->
    <div class=" combo-class">
        <div class="dashboard-card">
            <div>
                <h6>Total Booking</h6>
                <h2>1200</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/active-booking.png') }}" alt="User Icon">
            </div>
        </div>
        <div class="dashboard-card">
            <div>
                <h6>Active Booking</h6>
                <h2>1200</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/active-booking.png') }}" alt="User Icon">
            </div>
        </div>
        <div class="dashboard-card">
            <div>
                <h6>Inactive Booking</h6>
                <h2>1200</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/active-booking.png') }}" alt="User Icon">
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
                <span class="download-icon"><img src="{{ asset('assets/images/icons/download.png') }}" alt="" class="btn-icons"></span> Export CSV
            </button>
            <button class="d-none add-user-btn" id="openAddUserModal">+ Add User</button>
        </div>
        <div class="toolbar-right">
            <input type="text" class="search-user" placeholder="Search user">
            <button class="filter-btn" id="openFilterModal"> <span class="download-icon"><img
                        src="{{ asset('assets/images/icons/button-icon.png') }}" alt=""  class="btn-icons"></span>Filter</button>
        </div>
    </div>

    <!-- booking -table-->
    <table class="theme-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th class="sortable" data-column="0">Booking ID <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>

                <th class="sortable">Date created
                    <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>



                <th class="sortable" data-column="1">Affected user<img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>
                <th class="sortable" data-column="2">Service Typer <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>
                <th class="sortable" data-column="3">Dispute issue <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>


                <th class="sortable" data-column="6"> Status <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>

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
                            <!-- Default: Publish -->
                            <span class="status active" onclick="toggleDropdown(this)">Resolved</span>
                            <ul class="dropdown-menu" style="display: none;">
                                <li class="active" onclick="setStatus(this, 'Resolved')">Resolved</li>
                                <li class="inactive" onclick="setStatus(this, 'Unresolved')">Unresolved</li>
                            </ul>
                        </div>
                    </td>
                <td>
                        <div class="actions-dropdown">
                            <button class="actions-btn">⋮</button>
                            <div class="actions-menu">
                                <a href="http://127.0.0.1:8000/admin/user-management/service-users/8"><i class="fa fa-eye"></i> View user</a>
                               
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
                <h5> <img src="{{ asset('assets/images/icons/download.png') }}" alt="Download" class="download-icon"> <small
                        style="color:grey;">Download </small></h5>
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
        </div>
    </div>



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

    <!-- Filter Modal -->
    <div id="filterModal" class="modal filter-theme-modal">
        <div class="modal-content filter-modal">
            <span class="close-modal" id="closeFilterModal">&times;</span>
            <h3>Filter</h3>
            <label>Select Date</label>
            <div class="date-range">
                <div>
                    <span>From:</span>
                    <input type="date" class="form-input">
                </div>
                <div>
                    <span>To:</span>
                    <input type="date" class="form-input">
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="reset-btn">Reset</button>
                <button type="submit" class="submit-btn">Apply Now</button>
            </div>
        </div>
    </div>
<script>
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

</script>
@endsection
