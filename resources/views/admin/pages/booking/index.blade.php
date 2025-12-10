@extends('admin.layouts.app')

@section('title', 'Booking Management')
@section('header', 'Booking Management')
@section('content')

    <!-- Top Stat Cards -->
    <div id="statsContainer">
        <livewire:admin.booking-stats mode="booking" />
    </div>


     

    <!-- Back Button (Hidden by default) -->

    <div class="">
        <div class="back-button-container" id="backButtonContainer" style="display: none;">
            <button class="back-button" onclick="showAllBookings()">
                <div class="back-icon">
                    <img src="{{ asset('assets/images/icons/back_icon.svg') }}" alt="Back">
                </div>

                <span class="page-title" id="pageTitle">All Bookings</span>
            </button>
        </div>

    </div>


    <livewire:admin.bookings.table />





    <div id="view-booking" class="view-booking-modal">
        <div class="view-booking-content">
        <div class="modal-header" style="margin-bottom:1.563vw">
                <h2 style="font-size:1.146vw;font-weight: 600;line-height:1;">Booking details</h2>
                <div class="header-actions">

                    <span class="close-btn" style="line-height:1;" onclick="closeBookingModal()"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg></span>
                </div>
            </div>
            <div class="service-header-icons">
                <h4 style="font-size:0.938vw;font-weight: 500; letter-spacing: -0.04em;">Service details</h4>
                <h5> <img src="{{ asset('assets/images/icons/download.svg') }}" alt="Download" class="download-icon"> <small
                        style="color:grey;">Download </small></h5>
            </div>

            <div class="modal-section">

                <div class="details-grid">
                    <div>Booking ID</div>
                    <div style="cursor:pointer">12345</div>
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
                    <div class="status completed" style="color:#17a55a;border-color:#17a55a;font-weight:500;background:rgba(23, 165, 90, 0.1)">Completed</div>
                </div>
            </div>

            <div class="modal-section">
                <br>
                <h4 style="font-size:0.938vw;font-weight: 500; letter-spacing: -0.04em;">Users details</h4>

                <div class="details-grid">
                    <div>Service provider</div>
                    <div class="text-end">Johnbosco Davies</div>
                    <div>Service user</div>
                    <div class="text-end">Johnbosco Davies</div>
                </div>
            </div>
        </div>
    </div>





    <!-- Filter Modal -->
    <div id="filterModal" class="modal filter-theme-modal" style="display:none">
        <div class="modal-content filter-modal">
            <span class="close-modal" id="closeFilterModal">&times;</span>
            <h3>Filter</h3>
            <label style="color:#717171;">Select Date</label>
            <div class="row  mt-3">
                <div class="col-6">
                    <span>From:</span>
                    <input type="date" class="form-input mt-2">
                </div>
                <div  class="col-6">
                    <span>To:</span>
                    <input type="date" class="form-input mt-2">
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="reset-btn">Reset</button>
                <button type="submit" class="submit-btn">Apply Now</button>
            </div>
        </div>
    </div>


@endsection

@push('styles')
    <style>
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            background: #ffffff;
            border: 1px solid #ffffff;
            border-radius: 12px;
            color: #495057;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;

        }



        .back-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }

        .back-icon img {
            width: 20px;
            height: 20px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .back-button:hover .back-icon img {
            transform: translateX(-2px);
        }

        .back-text {
            font-weight: 600;
            letter-spacing: 0.5px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function filterByStatus(status, title) {
            // Hide stats container
            document.getElementById('statsContainer').style.display = 'none';

            // Show back button
            document.getElementById('backButtonContainer').style.display = 'flex';

            // Update page title
            document.getElementById('pageTitle').textContent = title;
            document.getElementById('backButtonText').textContent = '← ' + title;

            // Dispatch Livewire event to filter the table
            Livewire.dispatch('filterByStatus', {
                status: status
            });
        }

        function showAllBookings() {
            // Show stats container
            document.getElementById('statsContainer').style.display = 'flex';

            // Hide back button
            document.getElementById('backButtonContainer').style.display = 'none';

            // Reset page title and back button text
            document.getElementById('pageTitle').textContent = 'All Bookings';
            document.getElementById('backButtonText').textContent = 'All Bookings';

            // Dispatch Livewire event to show all bookings
            Livewire.dispatch('filterByStatus', {
                status: ''
            });
        }

        function openBookingModal(bookingId = null) {
            if (bookingId) {
                // Fetch booking data and populate modal
                fetch(`/admin/bookings/${bookingId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate modal with booking data
                        console.log('Booking data:', data);
                    })
                    .catch(error => {
                        console.error('Error fetching booking data:', error);
                    });
            }
            document.getElementById('view-booking').style.display = 'flex';
        }

        function closeBookingModal() {
            document.getElementById('view-booking').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('view-booking');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
@endpush
