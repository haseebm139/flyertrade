@extends('admin.layouts.app')

@section('title', 'Reviews & Ratings')
@section('header', 'Reviews & Ratings')
 
    <style>
        .profile {
            width: 2.083vw;
            height: 2.083vw;
        }

        .view-profile-btn {
            border: 1px solid #C4C4C4;
        }

        .view-profile-btn:hover {
            background-color: #004d40;
            color: #fff;
            border-color: #004d40;
        }

        .view-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8vw;
            cursor: pointer;
            text-decoration: none;
        }

        .eye-icon {
            width: 1vw;
            height: 1vw;
        }

        #edit-review {
            width: 100%;
            padding: 10px;
            resize: vertical;
            border: 1px solid #ccc;
            font-size: 0.8vw;
            display: none;
        }

        .reviewbox {
            display: grid;
            row-gap: 22px;
        }

        .save-btn-custom {
            background-color: #004d40;
            color: white;
            border: none;
            padding: 0.4vw 1vw;
            border-radius: 5px;
            font-size: 0.8vw;
            cursor: pointer;
            display: none;
        }

        .save-btn-custom:hover {
            background-color: #00695c;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .edit-delete-buttons {
            display: flex;
            gap: 1vw;
        }
    </style>
 
@section('content')

    <div class="row g-3">
        <div class="col-lg-8">
            <!-- Review Card -->
            <div class="card border-0 p-3">
                <h5 class="mb-3 page-title">Review details</h5>

                <!-- Reviewer info -->
                <div class="d-flex align-items-center justify-content-between border rounded p-3 mb-3">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/images/icons/person.png') }}" alt="Reviewer" class="me-3 profile">
                        <div>
                            <h6 class="mb-0" style="font-weight: 500; font-size: 0.8vw;">Johnbosco Davies</h6>
                            <small class="text-muted" style="font-size: 0.7vw;">Reviewer</small>
                        </div>
                    </div>
                    <a href="#" class="btn btn-outline-secondary btn-sm view-profile-btn">View profile</a>
                </div>

                <!-- Status -->
                <div class="d-flex align-items-center justify-content-between border rounded p-3 mb-3">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0" style="font-weight: 500; font-size: 0.8vw;">Review Davies</h6>
                        </div>
                    </div>

                    <div class="status-dropdown">
                        <!-- Default: Publish -->
                        <span class="status active" onclick="toggleDropdown(this)">Publish</span>
                        <ul class="dropdown-menu" style="display: none;">
                            <li class="active" onclick="setStatus(this, 'Publish')">Publish</li>
                            <li class="inactive" onclick="setStatus(this, 'UnPublish')">UnPublish</li>
                        </ul>
                    </div>

                </div>

                <!-- Review section -->
                <div class="p-3 border rounded reviewbox">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <!-- Stars -->
                        <div class="stars-rating d-flex">
                            <img src="{{ asset('assets/images/icons/star.png') }}" alt="star"
                                style="width:1.2vw; height:1.2vw; margin-right:0.3vw;">
                            <img src="{{ asset('assets/images/icons/star.png') }}" alt="star"
                                style="width:1.2vw; height:1.2vw; margin-right:0.3vw;">
                            <img src="{{ asset('assets/images/icons/star.png') }}" alt="star"
                                style="width:1.2vw; height:1.2vw; margin-right:0.3vw;">
                            <img src="{{ asset('assets/images/icons/star.png') }}" alt="star"
                                style="width:1.2vw; height:1.2vw; margin-right:0.3vw;">
                            <img src="{{ asset('assets/images/icons/star.png') }}" alt="star"
                                style="width:1.2vw; height:1.2vw;">
                        </div>
                        <small class="text-muted" style="font-size: 0.7vw;">2 days ago</small>
                    </div>

                    <!-- Review Text -->
                    <p id="review-text" class="mb-3" style="font-size: 0.8vw;">
                        Jonathan is very professional and cooks with great attention to detail.
                        The jollof rice and grilled chicken were perfect. Clean, organized, and polite throughout.
                        Will definitely book again.
                    </p>

                    <!-- Edit Area -->
                    <textarea id="edit-review"></textarea>

                    <!-- Buttons -->
                    <div class="action-buttons">
                        <div class="edit-delete-buttons" id="edit-delete-buttons">
                            <a href="javascript:void(0);" class="view-btn" id="edit-btn" style="color: grey;">
                                <img src="{{ asset('assets/images/icons/edit-2.png') }}" alt="Edit" class="eye-icon">
                                Edit
                            </a>
                            <a href="#" class="view-btn" style="color: grey;">
                                <img src="{{ asset('assets/images/icons/trash-theme.png') }}" alt="Delete"
                                    class="eye-icon"> Delete
                            </a>
                        </div>
                        <button id="save-btn" class="save-btn-custom">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
