<!-- ========== Begin::SIDEBAR (always open) ========== -->
<aside class="sidebar">
    <!-- Brand / Logo -->
    <div class="brand" style="margin-bottom:1.125vw;position:relative;">
        <a href="{{ route('dashboard') }}" class="logo-link">
            <img src="{{ asset('assets/images/icons/logo.svg') }}" alt="Company Logo" class="logo-img">
        </a>
        <a id="closemenumutton"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg></a>
    </div>

    <!-- Navigation -->
    <nav>
        <!-- Dashboard -->
        @can('Read Dashboard')
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} {{ request()->routeIs('notification.index') ? 'active' : '' }}">
                <span>
                    <img src="{{ asset('assets/images/icons/' . (request()->routeIs('dashboard') ? 'home-icon.svg' : 'home.svg')) }}"
                        alt="Dashboard" class="nav-icon">
                </span>
                Dashboard
            </a>
        @endcan

        <!-- User Management -->
        @if(auth()->user()->can('Read Users') || auth()->user()->can('Read Providers'))
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
                @can('Read Users')
                    <a href="{{ route('user-management.service.users.index') }}"
                        class="nav-sublink {{ request()->routeIs('user-management.service.users.*') ? 'active' : '' }}">
                        Service Users
                    </a>
                @endcan
                @can('Read Providers')
                    <a href="{{ route('user-management.service.providers.index') }}"
                        class="nav-sublink {{ request()->routeIs('user-management.service.providers.*') ? 'active' : '' }}">
                        Service Providers
                    </a>
                @endcan
            </div>
        @endif

        <!-- Service Category -->
        @can('Read Service Categories')
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
        @endcan


        <!-- Bookings -->
        @can('Read Bookings')
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
        @endcan


        <!-- Transactions -->
        @can('Read Transactions')
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
        @endcan

        <!-- Reviews -->
        @can('Read Reviews')
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
        @endcan


        <!-- Disputes -->
        @can('Read Disputes')
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
        @endcan


        <!-- Roles -->
        @can('Read Roles')
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
        @endcan


        <!-- Messaging -->
        @can('Read Messages')
            <a href="{{ route('messages.index') }}"
                class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                <span>
                    <img
                        src="{{ asset(request()->routeIs('messages.*') 
                    ? 'assets/images/icons/message-active.svg' 
                    : 'assets/images/icons/message.svg') }}"
                        alt="Messaging"
                        class="nav-icon">
                </span>
                Messaging
            </a>
        @endcan
        <div class="space">
            @can('Read Settings')
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
            @endcan
            <!-- Profile -->
            <a href="#" class="nav-link" style="padding:0;">
                <div class="brand" id="profileBt">
                    <div class="logo-placeholder">
                        <img src="{{ asset('assets/images/icons/main_group.svg') }}" alt="" class="admin-img">
                    </div>

                    <div class="profile-info">
                        <div class="profile-name">Flyertrade</div>
                        <div class="profile-email" style="font-weight:400;">flyertrade@gmail.com</div>
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