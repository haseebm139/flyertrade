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

                        <div class="stars-rating"></div> Lorem ipsum dolor sit amet consectetur adipisicing
                        elit. Non, voluptates? Lorem
                        ipsum
                        dolor sit amet.

                    </td>


              <td>
  <div class="status-dropdown">
    <span class="status publish" onclick="toggleDropdown(this)">
      Publish
      <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0a8754" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="6 9 12 15 18 9"></polyline>
      </svg>
    </span>
    <ul class="dropdown-menu">
      <li onclick="setStatus(this, 'Pending')">Pending</li>
      <li onclick="setStatus(this, 'Publish')">Publish</li>
     
    </ul>
  </div>
</td>
                    <td>
                        <div class="actions-dropdown" bis_skin_checked="1">
                            <button class="actions-btn"><img src="{{ asset('assets/images/icons/three-dots.png') }}"
                                    class="dots-img" alt=""></button>
                            <div class="actions-menu" bis_skin_checked="1">
                                <a href=""><img src="{{ asset('assets/images/icons/eye.png') }}" alt="">
                                    View
                                    Details</a>
                                <a href=""><img src="{{ asset('assets/images/icons/delete-icon.png') }}"
                                        alt="">
                                    Deleted
                                </a>

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

                        <div class="stars-rating"></div> Lorem ipsum dolor sit amet consectetur adipisicing
                        elit. Non, voluptates? Lorem
                        ipsum
                        dolor sit amet.

                    </td>


            <td>
  <div class="status-dropdown">
    <span class="status publish" onclick="toggleDropdown(this)">
      Publish
      <svg class="arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0a8754" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="6 9 12 15 18 9"></polyline>
      </svg>
    </span>
    <ul class="dropdown-menu">
      <li onclick="setStatus(this, 'Pending')">Pending</li>
      <li onclick="setStatus(this, 'Publish')">Publish</li>

    </ul>
  </div>
</td>

                    <td>
                        <div class="actions-dropdown" bis_skin_checked="1">
                            <button class="actions-btn"><img src="{{ asset('assets/images/icons/three-dots.png') }}"
                                    class="dots-img" alt=""></button>
                            <div class="actions-menu" bis_skin_checked="1">
                                <a href=""><img src="{{ asset('assets/images/icons/eye.png') }}" alt="">
                                    View
                                    Details</a>
                                <a href=""><img src="{{ asset('assets/images/icons/delete-icon.png') }}"
                                        alt="">
                                    Deleted
                                </a>

                            </div>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>

    <script>
        let rating = 4;
        let container = document.querySelector(".stars-rating");

        for (let i = 0; i < rating; i++) {
            let img = document.createElement("img");
            img.src = "{{ asset('assets/images/icons/star.png') }}";
            img.alt = "star";
            img.style.width = "1vw";
            img.style.height = "1vw";
            img.style.marginRight = "0.1vw";
            container.appendChild(img);
        }
    </script>

@endsection
