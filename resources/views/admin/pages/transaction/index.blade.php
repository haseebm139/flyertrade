@extends('admin.layouts.app')

@section('title', 'Transactions')
@section('header', 'Transactions & Payments')
@section('content')

    <div class=" combo-class">
        <div class="dashboard-card">
            <div>
                <h6>Total ravenue</h6>
                <h2>$82460</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/payout-icon.png') }}" alt="pay-icon">
            </div>
        </div>
        <div class="dashboard-card">
            <div>
                <h6>Total payout</h6>
                <h2>$32000</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/payout-icon.png') }}" alt="pay-icon">
            </div>
        </div>
        <div class="dashboard-card">
            <div>
                <h6>Pending payout</h6>
                <h2>$1000</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/payout-icon.png') }}" alt="pay-icon">
            </div>
        </div>

    </div>
    <br>
    <div class="container">
        <h1 class="page-title">All transactions</h1>
    </div>
    <div class="users-toolbar">
        <div class="toolbar-left">
            <button class="export-btn">
                <span class="download-icon"><img src="{{ asset('assets/images/icons/download.png') }}" alt=""></span> Export CSV
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
                <th class="sortable" data-column="0">Transaction ID <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>
                <th class="sortable" data-column="4">Transaction type <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>
                <th class="sortable">Date and time
                    <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>



                <th class="sortable" data-column="1">Associated user/provider<img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>
                <th class="sortable" data-column="2">Payment method <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>
                <th class="sortable" data-column="3">Amount Paid <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>


                <th class="sortable" data-column="6"> Status <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>
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
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}" alt=""> View
                                user</a>
                            <a href="#"><img src="{{ asset('assets/images/icons/init.png') }}" alt=""> Initiate payout</a>

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
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}" alt=""> View
                                user</a>
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
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}" alt=""> View
                                user</a>
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
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}" alt=""> View
                                user</a>
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
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}" alt=""> View
                                user</a>
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
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}" alt=""> View
                                user</a>
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
                            <a onclick="openBookingModal()"><img src="{{ asset('assets/images/icons/eye.png') }}" alt=""> View
                                user</a>
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
                    <div class>12345</div>
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
    <div id="filterModal" class="modal">
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




@endsection
