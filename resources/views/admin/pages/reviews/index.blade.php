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
        <button class="export-btn">
            <span class="download-icon"><img src="{{ asset('assets/images/icons/download.png') }}"
                    alt=""></span> Export
            CSV
        </button>

    </div>
    <div class="toolbar-right">
        <input type="text" class="search-user" placeholder="Search user">
        <button class="filter-btn" id="openFilterModal"> <span class="download-icon"><img
                    src="{{ asset('assets/images/icons/button-icon.png') }}" alt=""></span>Filter</button>
    </div>

</div>

<div id="users" class="tab-content active">

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



                <th class="sortable" data-column="1">Reviewer<img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon">
                </th>
                <th class="sortable" data-column="1">Reviewer Provider<img
                        src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>



                <th class="sortable" data-column="6"> Review <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon">
                </th>
                <th class="sortable" data-column="6"> Status <img src="{{ asset('assets/images/icons/sort.png') }}"
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
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div bis_skin_checked="1">
                            <span class="user-theme-name ">Johnbosco Davies</span>

                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-info" bis_skin_checked="1">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div bis_skin_checked="1">
                            <span class="user-theme-name ">Johnbosco Davies</span>

                        </div>
                    </div>
                </td>


                <td>

                   <div class="stars-rating">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.png" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.png" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.png" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.png" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
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
                <td style="position:relative;">
                    <div class="actions-dropdown" bis_skin_checked="1">
                        <button class="actions-btn act"><img src="{{ asset('assets/images/icons/three-dots.png') }}"
                                class="dots-img" alt=""></button>
                        <div class="actions-menu" bis_skin_checked="1">
                            <a href="{{ route('reviews.show') }}"><img src="{{ asset('assets/images/icons/eye.png') }}" alt="">
                                View
                                Details</a>
                            <a href="" class="showDeleteModal"  data-id="1"><img src="{{ asset('assets/images/icons/delete-icon.png') }}"
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
                <th class="sortable" data-column="0">Booking ID <img
                        src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>

                <th class="sortable">Date created
                    <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>



                <th class="sortable" data-column="1">Reviewer<img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon">
                </th>
                <th class="sortable" data-column="1">Reviewer Provider<img
                        src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>



                <th class="sortable" data-column="6"> Review <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon">
                </th>
                <th class="sortable" data-column="6"> Status <img src="{{ asset('assets/images/icons/sort.png') }}"
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
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div bis_skin_checked="1">
                            <span class="user-theme-name ">Johnbosco Davies</span>

                        </div>
                    </div>
                </td>
                <td>
                    <div class="user-info" bis_skin_checked="1">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div bis_skin_checked="1">
                            <span class="user-theme-name ">Johnbosco Davies</span>

                        </div>
                    </div>
                </td>


                <td>

                    <div class="stars-rating">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.png" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.png" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.png" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
                        <img src="http://127.0.0.1:8000/assets/images/icons/star.png" alt="star" style="width: 1vw; height: 1vw; margin-right: 0.1vw;">
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
                        <button class="actions-btn"><img src="{{ asset('assets/images/icons/three-dots.png') }}"
                                class="dots-img" alt=""></button>
                        <div class="actions-menu" bis_skin_checked="1">
                            <a href="{{ route('reviews.show') }}"><img src="{{ asset('assets/images/icons/eye.png') }}" alt="">
                                View
                                Details</a>
                            <a href="" class="showDeleteModal"  data-id="1"><img src="{{ asset('assets/images/icons/delete-icon.png') }}"
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

<script>
   
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
    //     img.src = "{{ asset('assets/images/icons/star.png') }}";
    //     img.alt = "star";
    //     img.style.width = "1vw";
    //     img.style.height = "1vw";
    //     img.style.marginRight = "0.1vw";
    //     container.appendChild(img);
    // }
</script>

@endsection