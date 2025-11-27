@extends('admin.layouts.app')

@section('title', 'Reviews & Ratings')
@section('header', 'Reviews & Ratings')
@section('content')

<div class="container" bis_skin_checked="1">
    <h1 class="page-title">All Reviews</h1>
</div>
<!-- Top Stat Cards -->
<div class="tabs-section">
    <div class="tab active roles-tab" data-target="users">&nbsp; User Reviews&nbsp; </div>
    <div class="tab roles-tab" data-target="providers">&nbsp; Providers Reviews&nbsp; </div>

</div>


<div class="users-toolbar">
    <div class="toolbar-left">
        <button class="export-btn d-flex align-items-center gap-1" style="color:#004E42; line-height:1">
            <span class="download-icon"><img src="{{ asset('assets/images/icons/download.svg') }}"
                    alt=""></span> Export
            CSV
        </button>

    </div>
    <div class="toolbar-right">
        <input type="text" class="search-user" placeholder="Search user">
        <button class="filter-btn" id="openFilterModal"> Filter <span class="download-icon"><img
                    src="{{ asset('assets/images/icons/button-icon.svg') }}" alt=""></span></button>
    </div>

</div>

<div id="users" class="tab-content active">

        <table class="theme-table">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th class="sortable" data-column="0">Service type <img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon">
                    </th>

                    <th class="sortable">Date and time
                        <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                    </th>



                    <th class="sortable" data-column="1">Reviewer<img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon">
                    </th>
                    <th class="sortable" data-column="1">Reviewed Provider<img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                    </th>



                <th class="sortable" data-column="6"> Review <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon">
                </th>
                <th class="sortable" data-column="6"> Status <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon">
                </th>
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
                    <div class="user-info" bis_skin_checked="1">
                        <img src="{{ asset('assets/images/icons/person-one.svg') }}" alt="User">
                        <div bis_skin_checked="1">
                            <span class="user-theme-name ">Johnbosco Davies</span>

                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-info" bis_skin_checked="1">
                        <img src="{{ asset('assets/images/icons/person-one.svg') }}" alt="User">
                        <div bis_skin_checked="1">
                            <span class="user-theme-name ">Johnbosco Davies</span>

                        </div>
                    </div>
                </td>


                <td>

                   <div class="stars-rating">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.svg" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.svg" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.svg" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.svg" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                    </div>
                     Lorem ipsum dolor sit amet consectetur adipisicing
                    elit. Non, voluptates? Lorem
                    ipsum
                    dolor sit amet.

                </td>

                <style>
                    .unpublished{
                        color:#D00416;
                        border: 2px solid #D00416;
                        background-color: #fb374741!important;
                    }
                </style>
                <td>
                    <div class="status-dropdown">
                        <span class="status publish" onclick="toggleDropdown(this)">
                            Publish
                            <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="#0a8754" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </span>
                        <ul class="dropdown-menu">
                            <li onclick="setStatus(this, 'Pending')">Pending</li>
                            <li onclick="setStatus(this, 'Publish')">Publish</li>
                            <li onclick="setStatus(this, 'Unpublished')">Unpublished</li>
                        </ul>
                    </div>
                </td>
                <td style="position:relative;">
                    <div class="actions-dropdown" bis_skin_checked="1">
                        <button class="actions-btn act"><img src="{{ asset('assets/images/icons/three_dots.svg') }}"
                                class="dots-img" alt=""></button>
                        <div class="actions-menu" bis_skin_checked="1">
                            <a href="{{ route('reviews.show') }}"><img src="{{ asset('assets/images/icons/eye.svg') }}" alt="">
                                View
                                Details</a>
                            <a href="" class="showDeleteModal"  data-id="1"><img src="{{ asset('assets/images/icons/delete-icon.svg') }}"
                                    alt="">
                                Deleted
                            </a>

                        </div>
                    </div>
                                <div id="globalDeleteModal1" class="deleteModal"
                                style="display: none;position:absolute;    top: 1vw; right: 3vw;">
                                <div class="delete-card">
                                    <div class="delete-card-header">
                                        <h3 class="delete-title">Delete review</h3>
                                        <span class="delete-close closeDeleteModal"
                                            data-id="1">&times;</span>
                                    </div>
                                    <p class="delete-text">Are you sure you want to delete this review?</p>
                                    <div class="delete-actions justify-content-start">
                                        <button class="confirm-delete-btn" wire:click="delete(1)"
                                            data-id="1">Delete</button>
                                        <button class="cancel-delete-btn" data-id="1">Cancel</button>
                                    </div>
                                </div>
                            </div>
                </td>
            </tr>

        </tbody>
    </table>

</div>

<div id="providers" class="tab-content ">

        <table class="theme-table">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th class="sortable" data-column="0">Service type <img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon">
                    </th>

                    <th class="sortable">Date and time
                        <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                    </th>



                    <th class="sortable" data-column="1">Reviewer<img src="{{ asset('assets/images/icons/sort.svg') }}"
                            class="sort-icon">
                    </th>
                    <th class="sortable" data-column="1">Reviewed Provider<img
                            src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                    </th>



                <th class="sortable" data-column="6"> Review <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon">
                </th>
                <th class="sortable" data-column="6"> Status <img src="{{ asset('assets/images/icons/sort.svg') }}"
                        class="sort-icon">
                </th>
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
                    <div class="user-info" bis_skin_checked="1">
                        <img src="{{ asset('assets/images/icons/person-one.svg') }}" alt="User">
                        <div bis_skin_checked="1">
                            <span class="user-theme-name ">Johnbosco Davies</span>

                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-info" bis_skin_checked="1">
                        <img src="{{ asset('assets/images/icons/person-one.svg') }}" alt="User">
                        <div bis_skin_checked="1">
                            <span class="user-theme-name ">Johnbosco Davies</span>

                        </div>
                    </div>
                </td>


                <td>

                    <div class="stars-rating">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.svg" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.svg" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.svg" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.svg" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                    </div>
                    Lorem ipsum dolor sit amet consectetur adipisicing
                    elit. Non, voluptates? Lorem
                    ipsum
                    dolor sit amet.

                </td>


                <td>
                    <div class="status-dropdown">
                        <span class="status publish" onclick="toggleDropdown(this)">
                            Publish
                            <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                viewBox="0 0 24 24" fill="none" stroke="#0a8754" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </span>
                        <ul class="dropdown-menu">
                            <li onclick="setStatus(this, 'Pending')">Pending</li>
                            <li onclick="setStatus(this, 'Publish')">Publish</li>

                        </ul>
                    </div>
                </td>

                <td  style="position:relative;">
                    <div class="actions-dropdown" bis_skin_checked="1">
                        <button class="actions-btn"><img src="{{ asset('assets/images/icons/three_dots.svg') }}"
                                class="dots-img" alt=""></button>
                        <div class="actions-menu" bis_skin_checked="1">
                            <a href="{{ route('reviews.show') }}"><img src="{{ asset('assets/images/icons/eye.svg') }}" alt="">
                                View
                                Details</a>
                            <a href="" class="showDeleteModal"  data-id="1"><img src="{{ asset('assets/images/icons/delete-icon.svg') }}"
                                    alt="">
                                Deleted
                            </a>

                        </div>
                    </div>
                                   <div id="globalDeleteModal1" class="deleteModal"
                                style="display: none;position:absolute;    top: 1vw; right: 3vw;">
                                <div class="delete-card">
                                    <div class="delete-card-header">
                                        <h3 class="delete-title">Delete review</h3>
                                        <span class="delete-close closeDeleteModal"
                                            data-id="1">&times;</span>
                                    </div>
                                    <p class="delete-text">Are you sure you want to delete this review?</p>
                                    <div class="delete-actions justify-content-start">
                                        <button class="confirm-delete-btn" wire:click="delete(1)"
                                            data-id="1">Delete</button>
                                        <button class="cancel-delete-btn" data-id="1">Cancel</button>
                                    </div>
                                </div>
                            </div>
                </td>
            </tr>

        </tbody>
    </table>

</div>
   <style>
        .modal_heaader{
            display: flex;
            position: relative;
            border-bottom: 1.50px solid #f1f1f1;
            margin-bottom: 1.2vw;

        }
         .modal_heaader .close-modal{
            top:0px;
            right:0px;
            line-height: 1;
         }
         .filter_modal_reset{
            border: 1px solid #f1f1f1;
            border-radius: 10px;
            padding: 12px 24px;
         }
         .date_field_wraper{
            position: relative;
         }
         .date-input {
            position: relative;
            padding-right: 35px; /* space for icon */
            font-family: Clash Display;
            color:#555;
            font-style: Medium;
           font-weight: 500;
            }

            /* Hide default icon */
            .date-input::-webkit-calendar-picker-indicator {
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            position: absolute;
            }

            /* Add your SVG as custom icon */
            .date-input {
            background-image: url('data:image/svg+xml;utf8,<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.66406 1.66602V4.16602" stroke="%23717171" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.3359 1.66602V4.16602" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.91406 7.57422H17.0807" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.5 7.08268V14.166C17.5 16.666 16.25 18.3327 13.3333 18.3327H6.66667C3.75 18.3327 2.5 16.666 2.5 14.166V7.08268C2.5 4.58268 3.75 2.91602 6.66667 2.91602H13.3333C16.25 2.91602 17.5 4.58268 17.5 7.08268Z" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.99803 11.4167H10.0055" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.91209 11.4167H6.91957" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.91209 13.9167H6.91957" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
            }

    </style>

<!-- Filter Modal -->
<div id="filterModal" class="modal filter-theme-modal" style="display:none">

    <div class="modal-content filter-modal" id="filter-theme-modal-content">
          <div class="modal_heaader">
                <span class="close-modal" id="closeFilterModal"  wire:click="closeFilterModal" >
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                </span>
                <h3 class="mt-0">Filter</h3>
                </div>
              
                <label style='color:#717171;font-weight:500;'>Select Date</label>
                <div class=" row mt-3">
                    <div class='col-6'>
                        <span style="font-weight:500">From:</span>
                        <div class="date_field_wraper">
                            <input type="date" class="form-input mt-2 date-input" wire:model="fromDate">
                        </div>
                       
                    </div>
                    <div class='col-6'>
                        <span style="font-weight:500"> To:</span>
                        <div class="date_field_wraper">
                            <input type="date" class="form-input mt-2 date-input" wire:model="toDate">
                        </div>
                       
                    </div>
                </div>



        <div class="form-actions">
            <button type="button" class="reset-btn">Reset</button>
            <button type="button" class="submit-btn">Apply Now</button>
        </div>
    </div>
</div>
<script>

    $('#openFilterModal').on('click', function(e) {
        e.preventDefault();
        $('#filterModal').css('display', 'flex');
        $("#openFilterModal").removeClass('tab-active');
    })
    $('#closeFilterModal').on('click', function(e) {
        e.preventDefault();
        $('#filterModal').css('display', 'none');
        $("#openFilterModal").removeClass('tab-active');
    })
    $('#filterModal').on('click', function(e) {
        e.preventDefault();
        // $('#filterModal').css('display', 'none');
        $("#openFilterModal").removeClass('tab-active');
    })
        $(document).on('click', '.showDeleteModal', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let id = $(this).data('id');
            $('.deleteModal').hide(); // Close all other modals first
            setTimeout(function() {
                $('#globalDeleteModal' + id).css('display', 'block');
            }, 10);
            return false;
        })
        $(document).on('click', '.closeDeleteModal', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let id = $(this).data('id');
            $('#globalDeleteModal' + id).css('display', 'none');
        })
        $(document).on('click', '.cancel-delete-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let id = $(this).data('id');
            $('#globalDeleteModal' + id).css('display', 'none');
        })
        $(document).on('click', '.confirm-delete-btn', function(e) {
            e.stopPropagation();
            let id = $(this).data('id');
            $('#globalDeleteModal' + id).css('display', 'none');
        })
        $(document).on('click', function(e) {
            // Don't close if clicking on delete button or inside modal
            if ($(e.target).closest('.showDeleteModal').length ||
                $(e.target).closest('.deleteModal').length ||
                $(e.target).hasClass('showDeleteModal')) {
                return;
            }
            $('.deleteModal').hide();
        });
    $('.act').on('click', function(e) {
        $('.dropdown-menu').hide();
    })
    // let rating = 4;
    // let container = document.querySelector(".stars-rating");

    // for (let i = 0; i < rating; i++) {
    //     let img = document.createElement("img");
    //     img.src = "{{ asset('assets/images/icons/star.svg') }}";
    //     img.alt = "star";
    //     img.style.width = "1vw";
    //     img.style.height = "1vw";
    //     img.style.marginRight = "0.1vw";
    //     container.appendChild(img);
    // }
</script>

@endsection