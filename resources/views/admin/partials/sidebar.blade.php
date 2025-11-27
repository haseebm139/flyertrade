<!-- ========== Begin::SIDEBAR (always open) ========== -->
<aside class="sidebar">
    <!-- Brand / Logo -->
    <div class="brand" style="margin-bottom:1.125vw;">
        <a href="{{ route('dashboard') }}" class="logo-link">
            <img src="{{ asset('assets/images/icons/logo.svg') }}" alt="Company Logo" class="logo-img">
        </a>
    </div>

    <!-- Navigation -->
    <nav>
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span>
                <img src="{{ asset('assets/images/icons/' . (request()->routeIs('dashboard') ? 'home-icon.svg' : 'home.svg')) }}"
                    alt="Dashboard" class="nav-icon">
            </span>
            Dashboard
        </a>

        <!-- User Management -->
        <a class="nav-link dropdown-toggle {{ request()->routeIs('user-management.*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#userManagementMenu" role="button"
            aria-expanded="{{ request()->routeIs('user-management.*') ? 'true' : 'false' }}"
            aria-controls="userManagementMenu">
            <span>
                <img src="{{ asset('assets/images/icons/' . (request()->routeIs('user-management.*') ? 'user_management_icon_nav_active.svg' : 'user_management_icon.svg')) }}"
                    alt="User Management" class="nav-icon">
            </span>
            User management
        </a>

        <div class="collapse {{ request()->routeIs('user-management.*') ? 'show' : '' }}" id="userManagementMenu">
            <a href="{{ route('user-management.service.users.index') }}"
                class="nav-sublink {{ request()->routeIs('user-management.service.users.*') ? 'active' : '' }}">
                Service Users
            </a>
            <a href="{{ route('user-management.service.providers.index') }}"
                class="nav-sublink {{ request()->routeIs('user-management.service.providers.*') ? 'active' : '' }}">
                Service Providers
            </a>
        </div>

        <!-- Service Category -->
        <a href="{{ route('service-category.index') }}"
            class="nav-link {{ request()->routeIs('service-category.*') ? 'active' : '' }}">
            <span>
                <img
                    src="{{ asset(request()->routeIs('service-category.*') 
                ? 'assets/images/icons/service_catogrey_active_icon.png' 
                : 'assets/images/icons/service_catogrey_icon.svg') }}"
                    alt="Service Category"
                    class="nav-icon">
            </span>
            Service category
        </a>


        <!-- Bookings -->
        <a href="{{ route('booking.index') }}"
            class="nav-link {{ request()->routeIs('booking.*') ? 'active' : '' }}">
            <span>
                <img
                    src="{{ asset(request()->routeIs('booking.*') 
                ? 'assets/images/icons/booking-icon-active.svg' 
                : 'assets/images/icons/booking-icon.svg') }}"
                    alt="Bookings"
                    class="nav-icon">
            </span>
            Bookings
        </a>


        <!-- Transactions -->
        <a href="{{ route('transaction.index') }}"
            class="nav-link {{ request()->routeIs('transaction.*') ? 'active' : '' }}">
            <span>
                <img
                    src="{{ asset(request()->routeIs('transaction.*') 
                ? 'assets/images/icons/transition-icon-active.svg' 
                : 'assets/images/icons/transition-icon.svg') }}"
                    alt="Transactions"
                    class="nav-icon">
            </span>
            Transactions
        </a>

        <!-- Reviews -->
        <a href="{{ route('reviews.index') }}"
            class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}">
            <span>
                <img
                    src="{{ asset(request()->routeIs('reviews.*') 
                ? 'assets/images/icons/reviews-active.svg' 
                : 'assets/images/icons/reviews.svg') }}"
                    alt="Reviews & Ratings"
                    class="nav-icon">
            </span>
            Reviews & Ratings
        </a>


        <!-- Disputes -->
        <a href="{{ route('dispute.index') }}"
            class="nav-link {{ request()->routeIs('dispute.*') ? 'active' : '' }}">
            <span>
                <img
                    src="{{ asset(request()->routeIs('dispute.*') 
                ? 'assets/images/icons/dispute-active.svg' 
                : 'assets/images/icons/dispute.svg') }}"
                    alt="Disputes & Complaints"
                    class="nav-icon">
            </span>
            Disputes & complaints
        </a>


        <!-- Roles -->
        <a href="{{ route('roles-and-permissions.index') }}"
            class="nav-link {{ request()->routeIs('roles-and-permissions.*') ? 'active' : '' }}">
            <span>
                <img
                    src="{{ asset(request()->routeIs('roles-and-permissions.*') 
                ? 'assets/images/icons/roles-active.svg' 
                : 'assets/images/icons/roles.svg') }}"
                    alt="Roles & Permissions"
                    class="nav-icon">
            </span>
            Roles & permission
        </a>


        <!-- Messaging -->
        <a href="#"
            class="nav-link {{ request()->routeIs('messaging.*') ? 'active' : '' }}">
            <span>
                <img
                    src="{{ asset(request()->routeIs('messaging.*') 
                ? 'assets/images/icons/message-active.svg' 
                : 'assets/images/icons/message.svg') }}"
                    alt="Messaging"
                    class="nav-icon">
            </span>
            Messaging
        </a>
        <div class="space">
            <a href="{{ route('settings.index') }}"
                class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <span>
                    <img
                        src="{{ asset(request()->routeIs('settings.*') 
                ? 'assets/images/icons/setting-active.svg' 
                : 'assets/images/icons/setting.svg') }}"
                        alt="Settings"
                        class="nav-icon">
                </span>
                Settings
            </a>
            <!-- Profile -->
            <a href="#" class="nav-link" style="padding:0;">
                <div class="brand" id="profileBt">
                    <div class="logo-placeholder">
                        <img src="{{ asset('assets/images/icons/main_group.svg') }}" alt="" class="admin-img">
                    </div>

                    <div class="profile-info">
                        <div class="profile-name">Flyertrade</div>
                        <div class="profile-email" style="font-size:0.8vw; font-weight:400;">flyertrade@gmail.com</div>
                    </div>

                    <div class="profile-arrows">
                        <i class="fa-solid fa-chevron-up"></i>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </div>
            </a>
        </div>

        <!-- Settings -->



    </nav>
</aside>