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
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span>
                <img src="{{ asset('assets/images/icons/' . (request()->routeIs('dashboard') ? 'home-icon.png' : 'home.png')) }}"
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
                <img src="{{ asset('assets/images/icons/' . (request()->routeIs('user-management.*') ? 'user-management-icon-nav-active.png' : 'user-management-icon.png')) }}"
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
        <div class="spacenin">
        <a href="#" class="nav-link">
            <span><img src="{{ asset('assets/images/icons/setting.png') }}" alt="Settings" class="nav-icon"></span>
            Settings
        </a>
        <a href="#" class="nav-link">
         <!-- Include Font Awesome (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="brand"  
     style="display:flex; align-items:center; gap:0.5vw; cursor:pointer; position:relative;">
    
    <!-- Logo -->
    <div class="logo-placeholder">
        <img src="{{ asset('assets/images/icons/person.png') }}" alt="" class="admin-img" 
             style="width:2vw; height:2vw; border-radius:50%;">
    </div>

    <!-- Text -->
    <div>
        <div style="font-weight:500; font-size:0.8vw;">Flyertrade</div>
        <div class="muted small" style="font-size:0.8vw;">flyertrade@example.com</div>
    </div>

    <!-- Arrows -->
    <div class="arrow-icons" style="display:flex; flex-direction:column;  font-size:0.8vw;">
        <i class="fa-solid fa-chevron-up" style="line-height:0.6vw;"></i>
        <i class="fa-solid fa-chevron-down" style="line-height:0.6vw;"></i>
    </div>
</div>


        </a>
        </div>
      



    </nav>
</aside>
