@extends('admin.layouts.app')

@section('title', 'Service Users Details')
@section('header', 'User Management')

@section('content')



    <!-- Top Stat Cards -->


    <div class="users-toolbar">
        <nav class="breadcrumb">
            <a href="#">Service User</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">Johnbosco Davies</span>
        </nav>
    </div>

    <!-- Toolbar -->
    <div class="users-toolbar">
        <div class="toolbar-left">
            <button class="reset-btn">Reset Password</button>

            <button class="edit-btn" id="openAddUserModal">
                <span class="download-icon"><img src="{{ asset('assets/images/icons/edit.png') }}" alt="" class="icons-btn"></span> Edit
                User
            </button>

            <button class="delete-btn">
                <span class="download-icon"><img src="{{ asset('assets/images/icons/trash.png') }}" alt="" class="icons-btn"></span>
                Delete user
            </button>
        </div>

        <div class="toolbar-right">
            <!-- ✅ User Profile -->
            <div class="user-profile">
                <img src="{{ asset('assets/images/icons/user-profile-img.png') }}" alt="User" class="user-profile-img">
                <div class="user-infos">
                    <h4 class="user-name-user">Johnbosco Davies</h4>
                    <p class="user-role">Service user</p>
                </div>

                <!-- ✅ Status Dropdown -->
                <div class="status-dropdown">
                    <button class="status-btn">Active ▼</button>
                    <div class="status-menu">
                        <div class="status-option active">Active</div>
                        <div class="status-option">Inactive</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- tabs-section -->
    <div class="tabs-section">
        <div class="tab active" data-target="details">Personal details</div>
        <div class="tab" data-target="history">Booking history</div>

    </div>

    <div id="details" class="tab-content active"
        style="border: 0.1vw solid #ddd;
                        border-radius: 2vw;">
        <h3 style="font-size:1.4vw;" class="profile-heading">Profile details</h3>
        <div class="profile-details">
            <p><span>Name</span> Johnbosco Davies</p>
            <p><span>Email address</span> Johnboscodaviess@gmail.com</p>
            <p><span>Phone number</span> 081 4596 58598</p>
            <p><span>State of residence</span> Dubai</p>
            <p><span>Home address</span> 123, ABC road, Dubai</p>
            <p><span>Overall rating</span> <span class="stars">⭐⭐⭐⭐⭐</span> (4.9)</p>
            <p><span>Referrals</span> 2</p>
        </div>
    </div>

    <div id="history" class="tab-content">
        <div class=" combo-class">
            <div class="dashboard-card">
                <div>
                    <h6>Amount Paid</h6>
                    <h2>1200</h2>
                </div>
                <div class="icon-box">
                    <img src="{{ asset('assets/images/icons/payout-icon.png') }}" alt="User Icon">
                </div>
            </div>
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
                    <h6>Completed Booking</h6>
                    <h2>1200</h2>
                </div>
                <div class="icon-box">
                    <img src="{{ asset('assets/images/icons/active-booking.png') }}" alt="User Icon">
                </div>
            </div>
            <div class="dashboard-card">
                <div>
                    <h6>Cancled Booking</h6>
                    <h2>1200</h2>
                </div>
                <div class="icon-box">
                    <img src="{{ asset('assets/images/icons/active-booking.png') }}" alt="User Icon">
                </div>
            </div>

        </div>
        <br>

        <table class="theme-table">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th class="sortable" data-column="0">Booking ID <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                    </th>

                    <th class="sortable">Date created
                        <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                    </th>



                    <th class="sortable" data-column="1">Provider<img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>
                    <th class="sortable" data-column="2">Location <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>
                    <th class="sortable" data-column="2">Service Catogery <img src="{{ asset('assets/images/icons/sort.png') }}"
                            class="sort-icon"></th>

                    <th class="sortable" data-column="3">Amount Paid <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                    </th>


                    <th class="sortable" data-column="6">Duration<img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>
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

                        <p class="user-name">Johnbosco Davies</p>


                    </td>


                    <td>123, Abc Road, Dubai</td>
                    <td>

                        <p class="user-name">Home Cleaning</p>


                    </td>
                    <td>$1200</td>
                    <td>2 Hours</td>



                    <td class="viw-parent">
                        <button class="view-btn" onclick="openBookingModal()">
                            <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                            View
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>12345</td>

                    <td><span class="date">Jan,2025-01-31</span>
                        <br>
                        <small class="time">12:00pm</small>

                    </td>
                    <td>

                        <p class="user-name">Johnbosco Davies</p>


                    </td>


                    <td>123, Abc Road, Dubai</td>
                    <td>

                        <p class="user-name">Home Cleaning</p>


                    </td>
                    <td>$1200</td>
                    <td>2 Hours</td>



                    <td class="viw-parent">
                        <button class="view-btn" onclick="openBookingModal()">
                            <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                            View
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>12345</td>

                    <td><span class="date">Jan,2025-01-31</span>
                        <br>
                        <small class="time">12:00pm</small>

                    </td>
                    <td>

                        <p class="user-name">Johnbosco Davies</p>


                    </td>


                    <td>123, Abc Road, Dubai</td>
                    <td>

                        <p class="user-name">Home Cleaning</p>


                    </td>
                    <td>$1200</td>
                    <td>2 Hours</td>



                    <td class="viw-parent">
                        <button class="view-btn" onclick="openBookingModal()">
                            <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                            View
                        </button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>12345</td>

                    <td><span class="date">Jan,2025-01-31</span>
                        <br>
                        <small class="time">12:00pm</small>

                    </td>
                    <td>

                        <p class="user-name">Johnbosco Davies</p>


                    </td>


                    <td>123, Abc Road, Dubai</td>
                    <td>

                        <p class="user-name">Home Cleaning</p>


                    </td>
                    <td>$1200</td>
                    <td>2 Hours</td>



                    <td class="viw-parent">
                        <button class="view-btn" onclick="openBookingModal()">
                            <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                            View
                        </button>
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
    </div>



    <!-- tabs section end -->
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
                            <div class="status active">Completed</div>
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




            <!-- Add User Modal -->
            <div id="addUserModal" class="modal">
                <div class="modal-content add-user-modal">
                    <span class="close-modal" id="closeAddUserModal">&times;</span>
                    <h3>Edit User</h3>
                    <form>
                        <label>Name</label>
                        <input type="text" class="form-input" placeholder="Enter name">
                        <label>Email</label>
                        <input type="email" class="form-input" placeholder="Enter email">
                        <label>Home Address</label>
                        <input type="text" class="form-input" placeholder="Enter home address">
                        <label>Phone Number</label>
                        <input type="text" class="form-input" placeholder="Enter phone number">
                        <div class="form-actions justify-content-center">
                            <button type="button" class="cancel-btn">Cancel</button>
                            <button type="submit" class="submit-btn"> + Add User</button>
                        </div>
                    </form>
                </div>
            </div>


@endsection
