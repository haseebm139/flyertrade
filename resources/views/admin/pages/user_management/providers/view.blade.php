@extends('admin.layouts.app')

@section('title', 'Service Providers Details')
@section('header', 'User Management')
@section('content')

    <!-- Top Stat Cards -->


    <div class="users-toolbar">
        <nav class="breadcrumb">
            <a href="#">Service Provider</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">Johnbosco Davies</span>
        </nav>
    </div>

    <!-- Toolbar -->
    <div class="users-toolbar">
        <div class="toolbar-left">
            <button class="reset-btn">Reset Password</button>

            <button class="edit-btn" id="openAddUserModal">
                Edit User

                <span class="download-icon"><img src="{{ asset('assets/images/icons/edit.png') }}" alt="" class="icons-btn"></span> 
            </button>

            <button class="delete-btn showDeleteModal">
                
                Delete user
                <span class="download-icon"><img src="{{ asset('assets/images/icons/trash.png') }}" alt="" class="icons-btn"></span>
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
                    <button class="status-btn">Active <i class="fa-solid fa-chevron-down"></i></button>
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
        <div class="tab" data-target="services">Services</div>
        <div class="tab" data-target="documents-verifications">Documents & verification</div>
        <div class="tab" data-target="charges-fees">Charges & fees</div>

    </div>
    <!-- personal details -->
    <div id="details" class="tab-content active" style="border: 0.1vw solid #ddd;border-radius: 2vw;">
        <h3 style="font-size:1.4vw;" class="profile-heading">Profile details</h3>
        <div class="profile-details">
            <p><span>Name</span> Johnbosco Davies</p>
            <p><span>Email address</span> Johnboscodaviess@gmail.com</p>
            <p><span>Phone number</span> 081 4596 58598</p>
            <p><span>State of residence</span> Dubai</p>
            <p><span>Home address</span> 123, ABC road, Dubai</p>
            <p><span>Overall rating</span> <img class="icons-btn" src="{{ asset('assets/images/icons/star.png') }}" alt=""> (4.9)
            </p>
            <p><span>Referrals</span> 2</p>
        </div>
    </div>
    <!-- personal details-end -->


    <!-- history -->
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



                    <td class="viw-parents">
                        <button class="view-btn" onclick="openBookingModal()">
                            <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                            View
                        </button>
                    </td>
                </tr>
                <tr>

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



                    <td class="viw-parents">
                        <button class="view-btn" onclick="openBookingModal()">
                            <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                            View
                        </button>
                    </td>
                </tr>
                <tr>

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



                    <td class="viw-parents">
                        <button class="view-btn" onclick="openBookingModal()">
                            <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                            View
                        </button>
                    </td>
                </tr>
                <tr>

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



                    <td class="viw-parents">
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
    <!-- histrory end-->


    <!-- services-->


    <div id="services" class="tab-content">


        <table class="theme-table">
            <thead>
                <tr>

                    <th class="sortable" data-column="0">Service Catogery <img src="{{ asset('assets/images/icons/sort.png') }}"
                            class="sort-icon">
                    </th>

                    <th class="sortable">Description
                        <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                    </th>



                    <th class="sortable" data-column="1">Job Id<img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"></th>
                    <th class="sortable" data-column="2">Ratings <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                    </th>
                    <th class="sortable" data-column="2">Min price/hr <img src="{{ asset('assets/images/icons/sort.png') }}"
                            class="sort-icon"></th>

                    <th class="sortable" data-column="3">Mid price/hr <img src="{{ asset('assets/images/icons/sort.png') }}"
                            class="sort-icon"></th>


                    <th class="sortable" data-column="6">Max price/hr<img src="{{ asset('assets/images/icons/sort.png') }}"
                            class="sort-icon"></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Home Cleaning</td>

                    <td>Reliable and affordable plumbing solutions for your
                        ...

                    </td>
                    <td>

                        <p class="user-name">123455</p>


                    </td>


                    <td>
                        <div class="stars-rating"></div>
                    </td>
                    <td>
                        $20


                    </td>
                    <td>$3</td>
                    <td>4$</td>



                    <td class="viw-parent">
                        <button class="view-btn" id="openServiceDetails">
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
    <!-- services end-->

    <!-- documents -->
    <div id="documents-verifications" class="tab-content" style="border:0.1vw solid #ddd;border-radius:1vw;padding:1vw;">

        <!-- Top toolbar -->
        <div class="toolbar">
            <h3 class="toolbar-title">Documents</h3>
            <div class="toolbar-actions" hidden>
                <button class="btn btn-verified" data-action="verified">✔ Mark as verified</button>
                <button class="btn btn-declined" data-action="declined">✖ Mark as decline</button>
                <button class="btn btn-pending" data-action="pending">— Mark as pending</button>
            </div>
        </div>

        <div class="documents-list">
            <div class="doc-row" data-id="1">
                <div>
                    <label class="check-wrap check-wrap-checkbox">
                        <input type="checkbox" class="row-check">
                        <span class="checkmark"></span>
                    </label>
                    <span class="doc-title">Valid Emirate ID card</span>
                </div>
              
                <a href="#" class="doc-link" data-check-modal data-title="ID card"
                    data-src="{{ asset('assets/images/icons/id-sample.png') }}">
                    View document
                </a>
                
                     <span class="badge badge-verified badge-pill actions-btn-verified" data-block='1' data-badge>Verified &nbsp;<i class="fa-solid fa-chevron-down"></i></span>
                <div class="actions-menu" id="actions-menu-verified-1" style="display: none;">
                            <a href="#">Pend</a>
                                <a href="#" class="showDeleteModal">Decline</a>
                            </div>
            </div>

            <div class="doc-row" data-id="2">
                <div>
                    <label class="check-wrap check-wrap-checkbox">
                        <input type="checkbox" class="row-check">
                        <span class="checkmark"></span>
                    </label>
                    <span class="doc-title">Valid Emirate ID card</span>
                </div>
              
                <a href="#" class="doc-link" data-check-modal data-title="ID card"
                    data-src="{{ asset('assets/images/icons/id-sample.png') }}">
                    View document
                </a>
                     <span class="badge badge-verified badge-pill actions-btn-verified" data-block='2' data-badge>Verified &nbsp;<i class="fa-solid fa-chevron-down"></i></span>
                <div class="actions-menu" id="actions-menu-verified-2" style="display: none;">
                             <a href="#">Pend</a>
                                <a href="#" class="showDeleteModal">Decline</a>
                            </div>
            </div>

            <div class="doc-row" data-id="3">
                <div>
                    <label class="check-wrap check-wrap-checkbox">
                        <input type="checkbox" class="row-check">
                        <span class="checkmark"></span>
                    </label>
                    <span class="doc-title">Valid Emirate ID card</span>
                </div>
              
                <a href="#" class="doc-link" data-check-modal data-title="ID card"
                    data-src="{{ asset('assets/images/icons/id-sample.png') }}">
                    View document
                </a>
                <span class="badge badge-verified badge-pill actions-btn-verified" data-block='3' data-badge>Verified &nbsp;<i class="fa-solid fa-chevron-down"></i></span>
                <div class="actions-menu" id="actions-menu-verified-3" style="display: none;">
                                 <a href="#">Pend</a>
                                <a href="#" class="showDeleteModal">Decline</a>
                            </div>
            </div>

            <div class="doc-row" data-id="4">
                <div>
                    <label class="check-wrap check-wrap-checkbox">
                        <input type="checkbox" class="row-check">
                        <span class="checkmark"></span>
                    </label>
                    <span class="doc-title">Valid Emirate ID card</span>
                </div>
              
                <a href="#" class="doc-link" data-check-modal data-title="ID card"
                    data-src="{{ asset('assets/images/icons/id-sample.png') }}">
                    View document
                </a>
                  <span class="badge badge-verified badge-pill actions-btn-verified" data-block='4' data-badge>Verified &nbsp;<i class="fa-solid fa-chevron-down"></i></span>
                <div class="actions-menu" id="actions-menu-verified-4" style="display: none;">
                                <a href="#">Pend</a>
                                <a href="#" class="showDeleteModal">Decline</a>
                            </div>
            </div>
            <div class="doc-row" data-id="5">
                <div>
                    <label class="check-wrap check-wrap-checkbox">
                        <input type="checkbox" class="row-check">
                        <span class="checkmark"></span>
                    </label>
                    <span class="doc-title">Valid Emirate ID card</span>
                </div>
              
                <a href="#" class="doc-link" data-check-modal data-title="ID card"
                    data-src="{{ asset('assets/images/icons/id-sample.png') }}">
                    View document
                </a>
                <span class="badge badge-verified badge-pill actions-btn-verified" data-block='5' data-badge>Verified &nbsp;<i class="fa-solid fa-chevron-down"></i></span>
                <div class="actions-menu" id="actions-menu-verified-5" style="display: none;">
                                  <a href="#">Pend</a>
                                <a href="#" class="showDeleteModal">Decline</a>
                            </div>
            </div>
        </div>


    </div>

    <!-- documents end -->

    <div id="charges-fees" class="tab-content" style="border:0.1vw solid #ddd;border-radius:1vw;padding:1vw;">

        <h3 style="font-size:1.4vw;margin:0 0 1vw;" class="profile-heading">
            Charges and fees
        </h3>

        <div class="charges-row">
            <div class="charge-col">
                <label class="charge-label">Service fee</label>
                <input type="text" class="charge-input" placeholder="$10">
            </div>

            <div class="charge-col">
                <label class="charge-label">Commission</label>
                <input type="text" class="charge-input" placeholder="5%">
            </div>
        </div>
    </div>




    <!-- Modal: id = check-modal -->
    <div id="check-modal" class="cm-modal" aria-hidden="true">
        <div class="cm-backdrop"></div>

        <div class="cm-dialog" role="dialog" aria-modal="true" aria-labelledby="cm-title">
            <div class="cm-head">
                <h4 id="cm-title" class="cm-title">Document</h4>
                <button type="button" class="cm-close" aria-label="Close">×</button>
            </div>

            <div class="cm-body">
                <img id="cm-img" class="cm-img" alt="Preview" />
                <div id="cm-ph" class="cm-placeholder" hidden>
                    <!-- simple placeholder -->
                    <svg viewBox="0 0 24 24" class="cm-ph-icon">
                        <circle cx="8" cy="8" r="3"></circle>
                        <path d="M2 20l6-7 4 4 3-3 7 6" fill="none" stroke="currentColor" stroke-width="2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>


    <!-- tabs section end -->

    <div id="service-details-modal" class="service-details-theme">
        <div class="modal-content">
            <span class="close-btn" id="closeServiceDetails">&times;</span>
            <h3>Service details</h3>

            <label>Name</label>
            <input type="text" value="Plumbing" readonly>

            <label>Description</label>
            <textarea readonly> Reliable and affordable plumbing solutions for your home or office. From fixing leaks and unclogging drains to full bathroom installations, I deliver fast and professional services. </textarea>

            <div class="price-boxes">
                <div>Maximum price/hr <input type="text" value="$80" readonly></div>
                <div>Mid price/hr <input type="text" value="$30" readonly></div>
                <div>Minimum price/hr <input type="text" value="$40" readonly></div>
            </div>

            <h4>Photos</h4>
            <div class="photos">
                <img src="{{ asset('assets/images/icons/service-one.png') }}" alt="">
                <img src="{{ asset('assets/images/icons/service-four.png') }}" alt="">
                <img src="{{ asset('assets/images/icons/service-three.png') }}" alt="">
                <img src="{{ asset('assets/images/icons/service-four.png') }}" alt="">
            </div>

            <h4>Videos</h4>
            <div class="videos">
                <video controls src="assets/videos/video1.mp4"></video>
                <video controls src="assets/videos/video2.mp4"></video>
            </div>
        </div>
    </div>



    <div id="view-booking" class="view-booking-modal add-user-modal">
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
                    <button type="submit" class="submit-btn"> Update</button>
                </div>
            </form>
        </div>
    </div>
    <!-- ✅ Global Delete Modal -->
    <div id="globalDeleteModal" class="deleteModal" style="display: none;">
        <div class="delete-card">
            <div class="delete-card-header">
                <h3 class="delete-title">Delete Service</h3>
                <span class="delete-close" id="closeDeleteModal">&times;</span>
            </div>
            <p class="delete-text">Are you sure you want to delete this service?</p>
            <div class="delete-actions justify-content-start">
                <button class="confirm-delete-btn">Delete</button>
                <button class="cancel-delete-btn">Cancel</button>
            </div>
        </div>
    </div>
    <script>
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
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.4);
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.delete-card {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  min-width: 300px;
}

</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const actionButtons = document.querySelectorAll(".actions-btn-verified");

  actionButtons.forEach(button => {
    button.addEventListener("click", function(e) {
      e.stopPropagation(); // prevent bubbling

      const blockId = this.getAttribute("data-block");
      const menu = document.getElementById(`actions-menu-verified-${blockId}`);

      // Pehle sab menus hide kar do
      document.querySelectorAll(".actions-menu").forEach(m => {
        if (m !== menu) m.style.display = "none";
      });

      // Ab sirf current wale ko toggle karo
      if (menu.style.display === "none" || menu.style.display === "") {
        menu.style.display = "block";
      } else {
        menu.style.display = "none";
      }
    });
  });

  // Page ke kisi aur area pe click hone par dropdown close ho jaye
  document.addEventListener("click", function() {
    document.querySelectorAll(".actions-menu").forEach(m => {
      m.style.display = "none";
    });
  });
});
</script>

@endsection
