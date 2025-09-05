@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
    <!-- top stat cards -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div>
                <h6>Total service users</h6>
                <h2>4650</h2>
            </div>
            <div class="icon-box">
                <img
                    src="{{ asset('assets/images/icons/service-providers.png') }}"
                    alt="User Icon"
                >
            </div>
        </div>

        <div class="dashboard-card">
            <div>
                <h6>Total service providers</h6>
                <h2>3280</h2>
            </div>
            <div class="icon-box">
                <img
                    src="{{ asset('assets/images/icons/new-provides.png') }}"
                    alt="Providers Icon"
                >
            </div>
        </div>

        <div class="dashboard-card">
            <div>
                <h6>Active bookings</h6>
                <h2>56</h2>
            </div>
            <div class="icon-box">
                <img
                    src="{{ asset('assets/images/icons/active-booking.png') }}"
                    alt="Booking Icon"
                >
            </div>
        </div>

        <div class="dashboard-card">
            <div>
                <h6>Total active users</h6>
                <h2>7930</h2>
            </div>
            <div class="icon-box">
                <img
                    src="{{ asset('assets/images/icons/active-members.png') }}"
                    alt="Active Users Icon"
                >
            </div>
        </div>
    </div>

    <!-- two-column area: left (finance + table) and right (recent activities) -->
    <div class="row g-3">
        <!-- MAIN LEFT COLUMN (8/12) -->
        <div class="col-lg-8">

            <!-- finances & disputes row -->
            <div class="row g-3">
                <div class="col-md-7">
                    <div class="finances-card">
                        <!-- Header -->
                        <div class="finances-header">
                            <h5 class="card-title ">Finances</h5>
                            <select
                                class="form-select form-select-sm"
                                style="width: auto;"
                            >
                                <option>February</option>
                                <option>January</option>
                                <option>March</option>
                            </select>
                        </div>

                        <!-- Progress bars -->
                        <div class="finances-progress">
                            <div class="progress-bar-custom progress-green"></div>
                            <div class="progress-bar-custom progress-brown"></div>
                        </div>

                        <!-- Stats -->
                        <div class="finance-stats">
                            <div class="stat-box">
                                <div class="stat-title">
                                    <span class="revenue-dot"></span> Total revenue
                                </div>
                                <div class="stat-value">
                                    $3000 <span class="stat-up"><img
                                            src="{{ asset('assets/images/icons/value-high.png') }}"
                                            class="img-log"
                                            alt=""
                                        ></span>
                                </div>
                            </div>

                            <div class="stat-box">
                                <div class="stat-title">
                                    <span class="payout-dot"></span> Total payout
                                </div>
                                <div class="stat-value">
                                    $800 <span class="stat-down"><img
                                            src="{{ asset('assets/images/icons/value-down.png') }}"
                                            class="img-log"
                                            alt=""
                                        ></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="recent-dispute-card">
                        <!-- Header -->
                        <div class="recent-dispute-header">
                            <h5>Recent dispute</h5>
                            <a href="#">View all</a>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table
                                class="table mb-0"
                                border="1"
                            >
                                <thead>
                                    <tr>
                                        <th>Affected user</th>
                                        <th>Dispute issue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <img
                                                    src="{{ asset('assets/images/icons/person.png') }}"
                                                    alt="User"
                                                >
                                                Johnbosco Davies
                                            </div>
                                        </td>
                                        <td>Service provider did not show up at the scheduled time and did
                                            not
                                            provide prior notice.</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <img
                                                    src="{{ asset('assets/images/icons/person.png') }}"
                                                    alt="User"
                                                >
                                                Johnbosco Davies
                                            </div>
                                        </td>
                                        <td>Provider left before finishing the work and did not return.</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="user-info">
                                                <img
                                                    src="{{ asset('assets/images/icons/person.png') }}"
                                                    alt="User"
                                                >
                                                Johnbosco Davies
                                            </div>
                                        </td>
                                        <td>Provider left before finishing the work and did not return.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recently added users DataTable -->
            <div class="card user-table-card mt-3">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Recently added users</h5>
                    <small class="muted-text">View all</small>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Home address</th>
                                <th>Phone number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="user-data asq">1234</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img
                                            src="https://i.pravatar.cc/40"
                                            class="rounded-circle me-2"
                                            alt="User"
                                        >
                                        <div>
                                            <div class="fw-semibold name">Johnbosco Davies</div>
                                            <small class="text-muted name-2">johnboscodavies@gmail.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="user-data asq">123, ABC Road, Dubai</td>
                                <td class="user-data asq">+234 4746 763 57</td>
                            </tr>
                            <tr>
                                <td class="user-data asq">1234</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img
                                            src="https://i.pravatar.cc/40?img=2"
                                            class="rounded-circle me-2"
                                            alt="User"
                                        >
                                        <div>
                                            <div class="fw-semibold name">Johnbosco Davies</div>
                                            <small class="text-muted name-2">johnboscodavies@gmail.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="user-data asq">123, ABC Road, Dubai</td>
                                <td class="user-data asq">+234 4746 763 57</td>
                            </tr>
                            <tr>
                                <td class="user-data asq">1234</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img
                                            src="https://i.pravatar.cc/40?img=3"
                                            class="rounded-circle me-2"
                                            alt="User"
                                        >
                                        <div>
                                            <div class="fw-semibold name">Johnbosco Davies</div>
                                            <small class="text-muted name-2">johnboscodavies@gmail.com</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="user-data asq ">123, ABC Road, Dubai</td>
                                <td class="user-data asq">+234 4746 763 57</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN (4/12): recent activities -->
        <div class="col-lg-4">
            <div class="card recent-activities">
                <div class="card-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Recent activities</h5>

                    </div>

                    <!-- Activity item -->
                    <div class="activity-item">
                        <div class="activity-icon bg-beige">
                            <img
                                src="{{ asset('assets/images/icons/new-booking.png') }}"
                                alt=""
                                class="icon-boxs"
                            >
                        </div>
                        <div class="activity-text">
                            <p class="title">New Booking Created:
                                <span>Alexander Johnson just booked Plumbing Service with Michael O.</span>
                            </p>
                            <small>1 hour ago</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon bg-green">
                            <img
                                src="{{ asset('assets/images/icons/new-provides.png') }}"
                                alt=""
                                class="icon-boxs"
                            >
                        </div>
                        <div class="activity-text">
                            <p class="title">New Service Provider Registered:
                                <span>Jonathan Davies just signed up as a Home Cleaner</span>
                            </p>
                            <small>1 hour ago</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon bg-blue">
                            <img
                                src="{{ asset('assets/images/icons/new-dispute.png') }}"
                                alt=""
                                class="icon-boxs"
                            >
                        </div>
                        <div class="activity-text">
                            <p class="title">New dispute:
                                <span>Dispute raised for Booking #FT32075 - Reason - Provider No-Show</span>
                            </p>
                            <small>1 hour ago</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon bg-light-brown">
                            <img
                                src="{{ asset('assets/images/icons/review-posted.png') }}"
                                alt=""
                                class="icon-boxs"
                            >
                        </div>
                        <div class="activity-text">
                            <p class="title">New Review Posted:
                                <span>Alexander Sarah K. rated Johnson Davies 'Home Cleaning' service 5
                                    stars</span>
                            </p>
                            <small>1 hour ago</small>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon bg-beige">
                            <img
                                src="{{ asset('assets/images/icons/verification-pending.png') }}"
                                alt=""
                                class="icon-boxs"
                            >
                        </div>
                        <div class="activity-text">
                            <p class="title">Verification Pending:
                                <span>3 new provider documents pending approval</span>
                            </p>
                            <small>1 hour ago</small>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <!-- <div class="pagination-controls">
                                                <button class="btn btn-light btn-sm me-1 rounded-circle border">
                                                <i class="bi bi-chevron-left"></i>
                                                </button>
                                                <button class="btn btn-light btn-sm rounded-circle border">
                                                <i class="bi bi-chevron-right"></i>
                                                </button>
                                            </div> -->
                </div>
            </div>
        </div>

    </div> <!-- end main row -->
@endsection
