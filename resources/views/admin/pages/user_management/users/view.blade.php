@extends('admin.layouts.app')

@section('title', 'Service Users Details')
@section('header', 'User Management')

@section('content')



<!-- Top Stat Cards -->


<div class="users-toolbar">
    <nav class="breadcrumb">
        <a href="#">Service User</a>
        <span class="breadcrumb-separator"><i class="fa-solid fa-chevron-right"></i></span>
        <span class="breadcrumb-current">Johnbosco Davies</span>
    </nav>
</div>

<!-- Toolbar -->
<div class="users-toolbar">
    <div class="toolbar-left" style="position:relative">
        <button class="reset-btn" id="reset_moda">Reset Password</button>
        <div id="globalresetModal" class="deleteModal" style="display: none;">
            <div class="delete-card">
                <div class="delete-card-header">
                    <h3 class="delete-title">Reset password</h3>
                    <span class="delete-close close_resset" >&times;</span>
                </div>
                <p class="delete-text">Are you sure you want to reset this user password?</p>
                <div class="delete-actions justify-content-start">
                    <button class="confirm-delete-btn">Reset</button>
                    <button class="cancel-delete-btn close_resset">Cancel</button>
                </div>
            </div>
        </div>
        <button class="edit-btn" id="openAddUserModal">
            Edit User&nbsp;
            <span class="download-icon"><img src="{{ asset('assets/images/icons/edit.svg') }}" alt="" class="icons-btn"></span>
        </button>

        <button class="delete-btn showDeleteModal">

            Delete user
            &nbsp;
            <span class="download-icon"><img src="{{ asset('assets/images/icons/trash.svg') }}" alt="" class="icons-btn"></span>
        </button>
        <div id="globalDeleteModal" class="deleteModal" style="display: none;">
            <div class="delete-card">
                <div class="delete-card-header">
                    <h3 class="delete-title">Delete Service User?</h3>
                    <span class="delete-close " >&times;</span>
                </div>
                <p class="delete-text">Are you sure you want to delete this service user?</p>
                <div class="delete-actions justify-content-start">
                    <button class="confirm-delete-btn">Delete</button>
                    <button class="cancel-delete-btn ">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toolbar-right">
        <!-- ✅ User Profile -->
        <div class="user-profile">
            <img src="{{ asset('assets/images/icons/user_profile_img.svg') }}" alt="User" class="user-profile-img">
            <div class="user-infos">
                <h4 class="user-name-user">Johnbosco Davies</h4>
                <p class="user-role">Service user</p>
            </div>

            <!-- ✅ Status Dropdown -->
            <div class="status-dropdown">
                <button class="status-btn">Active <i class="fa-solid fa-chevron-down"></i></button>
                <div class="status-menu">
                    <div class="status-option active">Active</div>
                    <div class="status-option">Suspend</div>
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
        <p><span>Overall rating</span> <span class="stars" style="color:#393939;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.78313 13.3332C4.8748 12.9248 4.70813 12.3415 4.41647 12.0498L2.39147 10.0248C1.75813 9.3915 1.50813 8.7165 1.69147 8.13317C1.88313 7.54984 2.4748 7.14984 3.35813 6.99984L5.95813 6.5665C6.33313 6.49984 6.79147 6.1665 6.96647 5.82484L8.3998 2.94984C8.81647 2.12484 9.38313 1.6665 9.9998 1.6665C10.6165 1.6665 11.1831 2.12484 11.5998 2.94984L13.0331 5.82484C13.1415 6.0415 13.3665 6.24984 13.6081 6.3915L4.63313 15.3665C4.51647 15.4832 4.31647 15.3748 4.3498 15.2082L4.78313 13.3332Z" fill="#EFC100" />
                    <path d="M15.5859 12.0501C15.2859 12.3501 15.1193 12.9251 15.2193 13.3334L15.7943 15.8417C16.0359 16.8834 15.8859 17.6667 15.3693 18.0417C15.1609 18.1917 14.9109 18.2667 14.6193 18.2667C14.1943 18.2667 13.6943 18.1084 13.1443 17.7834L10.7026 16.3334C10.3193 16.1084 9.68594 16.1084 9.3026 16.3334L6.86094 17.7834C5.93594 18.3251 5.14427 18.4167 4.63594 18.0417C4.44427 17.9001 4.3026 17.7084 4.21094 17.4584L14.3443 7.32508C14.7276 6.94174 15.2693 6.76674 15.7943 6.85841L16.6359 7.00008C17.5193 7.15008 18.1109 7.55008 18.3026 8.13341C18.4859 8.71674 18.2359 9.39174 17.6026 10.0251L15.5859 12.0501Z" fill="#EFC100" />
                </svg>


                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.78313 13.3332C4.8748 12.9248 4.70813 12.3415 4.41647 12.0498L2.39147 10.0248C1.75813 9.3915 1.50813 8.7165 1.69147 8.13317C1.88313 7.54984 2.4748 7.14984 3.35813 6.99984L5.95813 6.5665C6.33313 6.49984 6.79147 6.1665 6.96647 5.82484L8.3998 2.94984C8.81647 2.12484 9.38313 1.6665 9.9998 1.6665C10.6165 1.6665 11.1831 2.12484 11.5998 2.94984L13.0331 5.82484C13.1415 6.0415 13.3665 6.24984 13.6081 6.3915L4.63313 15.3665C4.51647 15.4832 4.31647 15.3748 4.3498 15.2082L4.78313 13.3332Z" fill="#EFC100" />
                    <path d="M15.5859 12.0501C15.2859 12.3501 15.1193 12.9251 15.2193 13.3334L15.7943 15.8417C16.0359 16.8834 15.8859 17.6667 15.3693 18.0417C15.1609 18.1917 14.9109 18.2667 14.6193 18.2667C14.1943 18.2667 13.6943 18.1084 13.1443 17.7834L10.7026 16.3334C10.3193 16.1084 9.68594 16.1084 9.3026 16.3334L6.86094 17.7834C5.93594 18.3251 5.14427 18.4167 4.63594 18.0417C4.44427 17.9001 4.3026 17.7084 4.21094 17.4584L14.3443 7.32508C14.7276 6.94174 15.2693 6.76674 15.7943 6.85841L16.6359 7.00008C17.5193 7.15008 18.1109 7.55008 18.3026 8.13341C18.4859 8.71674 18.2359 9.39174 17.6026 10.0251L15.5859 12.0501Z" fill="#EFC100" />
                </svg>


                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.78313 13.3332C4.8748 12.9248 4.70813 12.3415 4.41647 12.0498L2.39147 10.0248C1.75813 9.3915 1.50813 8.7165 1.69147 8.13317C1.88313 7.54984 2.4748 7.14984 3.35813 6.99984L5.95813 6.5665C6.33313 6.49984 6.79147 6.1665 6.96647 5.82484L8.3998 2.94984C8.81647 2.12484 9.38313 1.6665 9.9998 1.6665C10.6165 1.6665 11.1831 2.12484 11.5998 2.94984L13.0331 5.82484C13.1415 6.0415 13.3665 6.24984 13.6081 6.3915L4.63313 15.3665C4.51647 15.4832 4.31647 15.3748 4.3498 15.2082L4.78313 13.3332Z" fill="#EFC100" />
                    <path d="M15.5859 12.0501C15.2859 12.3501 15.1193 12.9251 15.2193 13.3334L15.7943 15.8417C16.0359 16.8834 15.8859 17.6667 15.3693 18.0417C15.1609 18.1917 14.9109 18.2667 14.6193 18.2667C14.1943 18.2667 13.6943 18.1084 13.1443 17.7834L10.7026 16.3334C10.3193 16.1084 9.68594 16.1084 9.3026 16.3334L6.86094 17.7834C5.93594 18.3251 5.14427 18.4167 4.63594 18.0417C4.44427 17.9001 4.3026 17.7084 4.21094 17.4584L14.3443 7.32508C14.7276 6.94174 15.2693 6.76674 15.7943 6.85841L16.6359 7.00008C17.5193 7.15008 18.1109 7.55008 18.3026 8.13341C18.4859 8.71674 18.2359 9.39174 17.6026 10.0251L15.5859 12.0501Z" fill="#EFC100" />
                </svg>


                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.78313 13.3332C4.8748 12.9248 4.70813 12.3415 4.41647 12.0498L2.39147 10.0248C1.75813 9.3915 1.50813 8.7165 1.69147 8.13317C1.88313 7.54984 2.4748 7.14984 3.35813 6.99984L5.95813 6.5665C6.33313 6.49984 6.79147 6.1665 6.96647 5.82484L8.3998 2.94984C8.81647 2.12484 9.38313 1.6665 9.9998 1.6665C10.6165 1.6665 11.1831 2.12484 11.5998 2.94984L13.0331 5.82484C13.1415 6.0415 13.3665 6.24984 13.6081 6.3915L4.63313 15.3665C4.51647 15.4832 4.31647 15.3748 4.3498 15.2082L4.78313 13.3332Z" fill="#EFC100" />
                    <path d="M15.5859 12.0501C15.2859 12.3501 15.1193 12.9251 15.2193 13.3334L15.7943 15.8417C16.0359 16.8834 15.8859 17.6667 15.3693 18.0417C15.1609 18.1917 14.9109 18.2667 14.6193 18.2667C14.1943 18.2667 13.6943 18.1084 13.1443 17.7834L10.7026 16.3334C10.3193 16.1084 9.68594 16.1084 9.3026 16.3334L6.86094 17.7834C5.93594 18.3251 5.14427 18.4167 4.63594 18.0417C4.44427 17.9001 4.3026 17.7084 4.21094 17.4584L14.3443 7.32508C14.7276 6.94174 15.2693 6.76674 15.7943 6.85841L16.6359 7.00008C17.5193 7.15008 18.1109 7.55008 18.3026 8.13341C18.4859 8.71674 18.2359 9.39174 17.6026 10.0251L15.5859 12.0501Z" fill="#EFC100" />
                </svg>


                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.78313 13.3332C4.8748 12.9248 4.70813 12.3415 4.41647 12.0498L2.39147 10.0248C1.75813 9.3915 1.50813 8.7165 1.69147 8.13317C1.88313 7.54984 2.4748 7.14984 3.35813 6.99984L5.95813 6.5665C6.33313 6.49984 6.79147 6.1665 6.96647 5.82484L8.3998 2.94984C8.81647 2.12484 9.38313 1.6665 9.9998 1.6665C10.6165 1.6665 11.1831 2.12484 11.5998 2.94984L13.0331 5.82484C13.1415 6.0415 13.3665 6.24984 13.6081 6.3915L4.63313 15.3665C4.51647 15.4832 4.31647 15.3748 4.3498 15.2082L4.78313 13.3332Z" fill="#EFC100" />
                    <path d="M15.5859 12.0501C15.2859 12.3501 15.1193 12.9251 15.2193 13.3334L15.7943 15.8417C16.0359 16.8834 15.8859 17.6667 15.3693 18.0417C15.1609 18.1917 14.9109 18.2667 14.6193 18.2667C14.1943 18.2667 13.6943 18.1084 13.1443 17.7834L10.7026 16.3334C10.3193 16.1084 9.68594 16.1084 9.3026 16.3334L6.86094 17.7834C5.93594 18.3251 5.14427 18.4167 4.63594 18.0417C4.44427 17.9001 4.3026 17.7084 4.21094 17.4584L14.3443 7.32508C14.7276 6.94174 15.2693 6.76674 15.7943 6.85841L16.6359 7.00008C17.5193 7.15008 18.1109 7.55008 18.3026 8.13341C18.4859 8.71674 18.2359 9.39174 17.6026 10.0251L15.5859 12.0501Z" fill="#EFC100" />
                </svg>
                (4.9)
            </span> </p>
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
                <img src="{{ asset('assets/images/icons/payout-icon.svg') }}" alt="User Icon">
            </div>
        </div>
        <div class="dashboard-card">
            <div>
                <h6>Total Booking</h6>
                <h2>1200</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/active_booking.svg') }}" alt="User Icon">
            </div>
        </div>
        <div class="dashboard-card">
            <div>
                <h6>Completed Booking</h6>
                <h2>1200</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/active_booking.svg') }}" alt="User Icon">
            </div>
        </div>
        <div class="dashboard-card">
            <div>
                <h6>Cancled Booking</h6>
                <h2>1200</h2>
            </div>
            <div class="icon-box">
                <img src="{{ asset('assets/images/icons/active_booking.svg') }}" alt="User Icon">
            </div>
        </div>

    </div>
    <br>

    <table class="theme-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th class="sortable" data-column="0">Booking ID <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                </th>

                <th class="sortable">Date created
                    <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                </th>



                <th class="sortable" data-column="1">Provider<img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                <th class="sortable" data-column="2">Location <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
                <th class="sortable" data-column="2">Service Catogery <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon"></th>

                <th class="sortable" data-column="3">Amount Paid <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                </th>


                <th class="sortable" data-column="6">Duration<img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon"></th>
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
                        <img src="{{ asset('assets/images/icons/eye_icon.svg') }}" alt="View" class="eye-icon">
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
                        <img src="{{ asset('assets/images/icons/eye_icon.svg') }}" alt="View" class="eye-icon">
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
                        <img src="{{ asset('assets/images/icons/eye_icon.svg') }}" alt="View" class="eye-icon">
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
                        <img src="{{ asset('assets/images/icons/eye_icon.svg') }}" alt="View" class="eye-icon">
                        View
                    </button>
                </td>
            </tr>

        </tbody>
    </table>


    <!-- Pagination -->
    <div class="pagination">
        <button class="page-btn prev" disabled><i class="fa-solid fa-chevron-left"></i></button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <button class="page-btn">4</button>
        <button class="page-btn">5</button>
        <span class="dots">...</span>
        <button class="page-btn">14</button>
        <button class="page-btn next"><i class="fa-solid fa-chevron-right"></i></button>

        <div class="rows-control">
            <label for="rows" style="
    color: #555555; font-weight: 400 !important;">Show</label>
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
            <h5> <img src="{{ asset('assets/images/icons/download.svg') }}" alt="Download" class="download-icon"> <small
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
                <button type="submit" class="submit-btn"> Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    $('#reset_moda').on('click',function(e){
        e.preventDefault();
        $('#globalresetModal').toggle();
    })
    $('.close_resset').on('click',function(e){
        e.preventDefault();
       $('#globalresetModal').toggle();
    })
    document.addEventListener("DOMContentLoaded", function() {
        const deleteModal = document.getElementById("globalDeleteModal");
        const showButtons = document.querySelectorAll(".showDeleteModal");
        const closeButton = document.getElementById("closeDeleteModal");
        const cancelButton = document.querySelector(".cancel-delete-btn");

        // Jab kisi showDeleteModal button pr click ho
        showButtons.forEach(btn => {
            btn.addEventListener("click", () => {
                deleteModal.style.display = "flex"; // modal show karo
            });
        });

        // Close button ya cancel button pr click hone pr modal hide karo
        [closeButton, cancelButton].forEach(btn => {
            btn.addEventListener("click", () => {
                deleteModal.style.display = "none";
            });
        });

        // Optional: backdrop click se bhi band ho
        deleteModal.addEventListener("click", (e) => {
            if (e.target === deleteModal) {
                deleteModal.style.display = "none";
            }
        });
    });
</script>
<style>
    .deleteModal {
        /* display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        justify-content: center;
        align-items: center;
        z-index: 999; */
        position: absolute;
        top: -5px;
        right: -5px;
    }

    .delete-card {
        /* background: #fff;
        padding: 20px;
        border-radius: 10px;
        min-width: 300px; */
    }
</style>

@endsection