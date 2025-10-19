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

        <!-- Service Category -->
     <a href="{{ route('service-category.index') }}" 
   class="nav-link {{ request()->routeIs('service-category.*') ? 'active' : '' }}">
    <span>
        <img 
            src="{{ asset(request()->routeIs('service-category.*') 
                ? 'assets/images/icons/service-catogrey-icon-active.png' 
                : 'assets/images/icons/service-catogrey-icon.png') }}" 
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
                ? 'assets/images/icons/booking-icon-active.png' 
                : 'assets/images/icons/booking-icon.png') }}" 
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
                ? 'assets/images/icons/transition-icon-active.png' 
                : 'assets/images/icons/transition-icon.png') }}" 
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
                ? 'assets/images/icons/reviews-active.png' 
                : 'assets/images/icons/reviews.png') }}" 
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
                ? 'assets/images/icons/dispute-active.png' 
                : 'assets/images/icons/dispute.png') }}" 
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
                ? 'assets/images/icons/roles-active.png' 
                : 'assets/images/icons/roles.png') }}" 
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
                ? 'assets/images/icons/message-active.png' 
                : 'assets/images/icons/message.png') }}" 
            alt="Messaging" 
            class="nav-icon">
    </span>
    Messaging
</a>


        <!-- Settings -->
<a href="#" 
   class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
    <span>
        <img 
            src="{{ asset(request()->routeIs('settings.*') 
                ? 'assets/images/icons/setting-active.png' 
                : 'assets/images/icons/setting.png') }}" 
            alt="Settings" 
            class="nav-icon">
    </span>
    Settings
</a>


        <!-- Profile -->
        <a href="#" class="nav-link">
            <div class="brand" style="display:flex; align-items:center; gap:0.8vw; cursor:pointer;">
                <div class="logo-placeholder">
                    <img src="{{ asset('assets/images/icons/person.png') }}" alt="" class="admin-img">
                </div>
                <div>
                    <div style="font-weight:500; font-size:0.8vw;">Flyertrade</div>
                    <div class="muted small" style="font-size:0.8vw;">flyertrade@example.com</div>
                </div>
            </div>
        </a>
    </nav>
</aside>
