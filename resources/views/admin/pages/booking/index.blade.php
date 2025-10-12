@extends('admin.layouts.app')

@section('title', 'Booking Management')
@section('header', 'Booking Management')
@section('content')

    <!-- Top Stat Cards -->
    <div class=" combo-class">
        <div class="dashboard-card" wire:click="filterByStatus(null)">
            <div>
                <h6>Total Booking</h6>
                <h2>1200</h2>
            </div>
            <div class="icon-box">
                <img
                    src="{{ asset('assets/images/icons/active-booking.png') }}"
                    alt="User Icon"
                >
            </div>
        </div>
        <div class="dashboard-card" wire:click="filterByStatus('active')" >
            <div>
                <h6>Active Booking</h6>
                <h2>1200</h2>
            </div>
            <div class="icon-box">
                <img
                    src="{{ asset('assets/images/icons/active-booking.png') }}"
                    alt="User Icon"
                >
            </div>
        </div>
        <div class="dashboard-card" wire:click="filterByStatus('inactive')">
            <div>
                <h6>Inactive Booking</h6>
                <h2>1200</h2>
            </div>
            <div class="icon-box">
                <img
                    src="{{ asset('assets/images/icons/active-booking.png') }}"
                    alt="User Icon"
                >
            </div>
        </div>

    </div>
    <br>
    <div class="container">
        <h1 class="page-title">All Booking</h1>
    </div>
     
     
     <livewire:admin.bookings.table />


    


    <div
        id="view-booking"
        class="view-booking-modal"
    >
        <div class="view-booking-content">
            <div class="modal-header">
                <h2>Booking details</h2>
                <div class="header-actions">

                    <span
                        class="close-btn"
                        onclick="closeBookingModal()"
                    >&times;</span>
                </div>
            </div>
            <div class="service-header-icons">
                <h4>Service details</h4>
                <h5> <img
                        src="{{ asset('assets/images/icons/download.png') }}"
                        alt="Download"
                        class="download-icon"
                    > <small style="color:grey;">Download </small></h5>
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



     

    <!-- Filter Modal -->
    <div
        id="filterModal"
        class="modal filter-theme-modal"
    >
        <div class="modal-content filter-modal">
            <span
                class="close-modal"
                id="closeFilterModal"
            >&times;</span>
            <h3>Filter</h3>
            <label>Select Date</label>
            <div class="date-range">
                <div>
                    <span>From:</span>
                    <input
                        type="date"
                        class="form-input"
                    >
                </div>
                <div>
                    <span>To:</span>
                    <input
                        type="date"
                        class="form-input"
                    >
                </div>
            </div>

            <div class="form-actions">
                <button
                    type="button"
                    class="reset-btn"
                >Reset</button>
                <button
                    type="submit"
                    class="submit-btn"
                >Apply Now</button>
            </div>
        </div>
    </div>


@endsection
