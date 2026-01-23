@extends('admin.layouts.app')

@section('title', 'Booking Management')
@section('header', 'Booking Management')
@section('content')

    <div class="col-lg-9 " id="container-booking">
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
            document.getElementById('container-booking').style.display = 'none';
            // Show back button
            document.getElementById('backButtonContainer').style.display = 'block';
            // Update page title
            document.getElementById('pageTitle').textContent = title;

            // Dispatch Livewire event to filter the table
            Livewire.dispatch('filterByStatus', {
                status: status,
                isCard: true
            });
        }

        function showAllBookings() {
            // Show stats container
            document.getElementById('container-booking').style.display = 'block';
            // Hide back button
            document.getElementById('backButtonContainer').style.display = 'none';
            // Reset page title
            document.getElementById('pageTitle').textContent = 'All Bookings';

            // Dispatch Livewire event to show all bookings
            Livewire.dispatch('filterByStatus', {
                status: '',
                isCard: false
            });
        }
    </script>
@endpush
