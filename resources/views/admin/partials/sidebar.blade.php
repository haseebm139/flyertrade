<!-- ========== Begin::SIDEBAR (always open) ========== -->

<aside class="sidebar">
    <!-- Brand / Logo -->
    <div class="brand">
        <a href="{{ route('dashboard') }}" class="logo-link">
            <img src="{{ asset('assets/images/icons/logo.png') }}" alt="Company Logo" class="logo-img">
        </a>
    </div>

    <!-- Navigation -->
    <nav>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span><img src="{{ asset('assets/images/icons/home-icon.png') }}" alt="Dashboard" class="nav-icon"></span>
            Dashboard
        </a>

        <!-- User Management with Dropdown -->
        <a class="nav-link dropdown-toggle {{ request()->routeIs('user-management.*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#userManagementMenu" role="button"
            aria-expanded="{{ request()->routeIs('user-management.*') ? 'true' : 'false' }}"
            aria-controls="userManagementMenu">
            <span><img src="{{ asset('assets/images/icons/user-management-icon.png') }}" alt="User Management"
                    class="nav-icon"></span>
            User management
        </a>
        <div class="collapse {{ request()->routeIs('user-management.*') ? 'show' : '' }}" id="userManagementMenu">
            <a href="{{ route('user-management.service.users.index') }}"
                class="nav-sublink {{ request()->routeIs('user-management.service.users.*') ? 'active' : '' }}">Service
                Users</a>
            <a href="{{ route('user-management.service.providers.index') }}"
                class="nav-sublink {{ request()->routeIs('user-management.service.providers.*') ? 'active' : '' }}">Service
                Providers</a>
        </div>

        <a href="{{ route('service-category.index') }}"
            class="nav-link {{ request()->routeIs('service-category.*') ? 'active' : '' }}">
            <span><img src="{{ asset('assets/images/icons/service-catogrey-icon.png') }}" alt="Service Category"
                    class="nav-icon"></span>
            Service category
        </a>
        <a href="{{ route('booking.index') }}" class="nav-link {{ request()->routeIs('booking.*') ? 'active' : '' }}">
            <span><img src="{{ asset('assets/images/icons/booking-icon.png') }}" alt="Bookings"
                    class="nav-icon"></span>
            Bookings
        </a>
        <a href="{{ route('transaction.index') }}"
            class="nav-link {{ request()->routeIs('transaction.*') ? 'active' : '' }}">
            <span><img src="{{ asset('assets/images/icons/transition-icon.png') }}" alt="Transactions"
                    class="nav-icon"></span>
            Transactions
        </a>
        <a href="{{ route('reviews.index') }}" class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}">
            <span><img src="{{ asset('assets/images/icons/reviews.png') }}" alt="Reviews & Ratings"
                    class="nav-icon "></span>
            Reviews & Ratings
        </a>
        <a href="{{ route('dispute.index') }}" class="nav-link {{ request()->routeIs('dispute.*') ? 'active' : '' }}">
            <span><img src="{{ asset('assets/images/icons/dispute.png') }}" alt="Disputes & Complaints"
                    class="nav-icon "></span>
            Disputes & complaints
        </a>
        <a href="{{ route('roles-and-permissions.index') }}"
            class="nav-link {{ request()->routeIs('roles-and-permissions.*') ? 'active' : '' }}">
            <span><img src="{{ asset('assets/images/icons/roles.png') }}" alt="Roles & Permissions"
                    class="nav-icon"></span>
            Roles & permission
        </a>
        <a href="#" class="nav-link">
            <span><img src="{{ asset('assets/images/icons/message.png') }}" alt="Messaging" class="nav-icon"></span>
            Messaging
        </a>
        <a href="#" class="nav-link">
            <span><img src="{{ asset('assets/images/icons/setting.png') }}" alt="Settings" class="nav-icon"></span>
            Settings
        </a>
        <a href="#" class="nav-link">
            <div class="brand" id="profileBtn"
                style="display:flex; align-items:center; gap:0.8vw; cursor:pointer; position:relative;">
                <div class="logo-placeholder">
                    <img src="{{ asset('assets/images/icons/person.png') }}" alt="" class="admin-img">
                </div>

                <div>
                    <div style="font-weight:500; font-size:0.8vw;">Flyertrade</div>
                    <div class="muted small" style="font-size:0.8vw;">flyertrade@example.com</div>
                </div>

                <!-- arrows -->


                <!-- popup -->
                <div class="popup" id="profilePopup">
                    <div class="popup-header">
                        <span class="popup-close" data-close="profilePopup">
                            <img src="{{ asset('assets/images/icons/iconoir_cancel.png') }}" alt="">
                        </span>
                    </div>

                    <div class="profile-card">
                        <img src="{{ asset('assets/images/icons/person.png') }}" alt="User">
                        <div style="font-weight:400; margin-top:5px; font-size:16px;">Flyertrade</div>
                        <div class="muted small">flyertrade@example.com</div>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                        <button class="logout-btn"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <img src="{{ asset('assets/images/icons/Icon-logout.png') }}" class="img-log"
                                alt="">
                            Logout
                        </button>
                    </div>
                </div>
            </div>

        </a>



    </nav>
</aside>
