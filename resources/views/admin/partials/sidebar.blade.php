<!-- ========== Begin::SIDEBAR ========== -->
<aside class="sidebar">
    <!-- Brand -->
    <div class="brand">
        <a href="{{ route('dashboard') }}" class="logo-link">
            <img src="{{ asset('assets/images/icons/logo.png') }}" alt="Logo" class="logo-img">
        </a>
    </div>

    <!-- Navigation -->
    <nav>
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           data-icon-default="{{ asset('assets/images/icons/home-icon.png') }}"
           data-icon-active="{{ asset('assets/images/icons/home-icon-filled.png') }}">
            <span><img src="{{ asset('assets/images/icons/home-icon.png') }}" class="nav-icon"></span>
            Dashboard
        </a>

        <!-- User Management -->
        <a href="{{ route('user-management.service.users.index') }}" 
           class="nav-link {{ request()->routeIs('user-management.*') ? 'active' : '' }}"
           data-icon-default="{{ asset('assets/images/icons/user-management-icon.png') }}"
           data-icon-active="{{ asset('assets/images/icons/user-management-icon-filled.png') }}">
            <span><img src="{{ asset('assets/images/icons/user-management-icon.png') }}" class="nav-icon"></span>
            User Management
        </a>

        <!-- Service Category -->
        <a href="{{ route('service-category.index') }}" 
           class="nav-link {{ request()->routeIs('service-category.*') ? 'active' : '' }}"
           data-icon-default="{{ asset('assets/images/icons/service-catogrey-icon.png') }}"
           data-icon-active="{{ asset('assets/images/icons/service-catogrey-icon-filled.png') }}">
            <span><img src="{{ asset('assets/images/icons/service-catogrey-icon.png') }}" class="nav-icon"></span>
            Service Category
        </a>

        <!-- Bookings -->
        <a href="{{ route('booking.index') }}" 
           class="nav-link {{ request()->routeIs('booking.*') ? 'active' : '' }}"
           data-icon-default="{{ asset('assets/images/icons/booking-icon.png') }}"
           data-icon-active="{{ asset('assets/images/icons/booking-icon-filled.png') }}">
            <span><img src="{{ asset('assets/images/icons/booking-icon.png') }}" class="nav-icon"></span>
            Bookings
        </a>

        <!-- Transactions -->
        <a href="{{ route('transaction.index') }}" 
           class="nav-link {{ request()->routeIs('transaction.*') ? 'active' : '' }}"
           data-icon-default="{{ asset('assets/images/icons/transition-icon.png') }}"
           data-icon-active="{{ asset('assets/images/icons/transition-icon-filled.png') }}">
            <span><img src="{{ asset('assets/images/icons/transition-icon.png') }}" class="nav-icon"></span>
            Transactions
        </a>

        <!-- Reviews -->
        <a href="{{ route('reviews.index') }}" 
           class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}"
           data-icon-default="{{ asset('assets/images/icons/reviews.png') }}"
           data-icon-active="{{ asset('assets/images/icons/reviews-filled.png') }}">
            <span><img src="{{ asset('assets/images/icons/reviews.png') }}" class="nav-icon"></span>
            Reviews & Ratings
        </a>

        <!-- Disputes -->
        <a href="{{ route('dispute.index') }}" 
           class="nav-link {{ request()->routeIs('dispute.*') ? 'active' : '' }}"
           data-icon-default="{{ asset('assets/images/icons/dispute.png') }}"
           data-icon-active="{{ asset('assets/images/icons/dispute-filled.png') }}">
            <span><img src="{{ asset('assets/images/icons/dispute.png') }}" class="nav-icon"></span>
            Disputes & Complaints
        </a>

        <!-- Roles -->
        <a href="{{ route('roles-and-permissions.index') }}" 
           class="nav-link {{ request()->routeIs('roles-and-permissions.*') ? 'active' : '' }}"
           data-icon-default="{{ asset('assets/images/icons/roles.png') }}"
           data-icon-active="{{ asset('assets/images/icons/roles-filled.png') }}">
            <span><img src="{{ asset('assets/images/icons/roles.png') }}" class="nav-icon"></span>
            Roles & Permissions
        </a>

        <!-- Settings -->
        <a href="#" class="nav-link"
           data-icon-default="{{ asset('assets/images/icons/setting.png') }}"
           data-icon-active="{{ asset('assets/images/icons/setting-filled.png') }}">
            <span><img src="{{ asset('assets/images/icons/setting.png') }}" class="nav-icon"></span>
            Settings
        </a>
    </nav>
</aside>
<!-- ========== End::SIDEBAR ========== -->
