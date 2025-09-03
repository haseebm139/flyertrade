<!-- ========== Begin::SIDEBAR (always open) ========== -->

<aside class="sidebar">
    <!-- Brand / Logo -->
    <div class="brand">
        <a
            href="dashboard.html"
            class="logo-link"
        >
            <img
                src="{{ asset('assets/images/icons/logo.png') }}"
                alt="Company Logo"
                class="logo-img"
            >
        </a>
    </div>

    <!-- Navigation -->
    <nav>
        <a
            href="#"
            class="nav-link active"
        >
            <span><img
                    src="{{ asset('assets/images/icons/home-icon.png') }}"
                    alt="Dashboard"
                    class="nav-icon"
                ></span>
            Dashboard
        </a>

        <!-- User Management with Dropdown -->
        <a
            class="nav-link dropdown-toggle"
            data-bs-toggle="collapse"
            href="#userManagementMenu"
            role="button"
            aria-expanded="false"
            aria-controls="userManagementMenu"
        >
            <span><img
                    src="{{ asset('assets/images/icons/user-management-icon.png') }}"
                    alt="User Management"
                    class="nav-icon"
                ></span>
            User management
        </a>
        <div
            class="collapse"
            id="userManagementMenu"
        >
            <a
                href="user-details.php"
                class="nav-sublink"
            >Service Users</a>
            <a
                href="user-providers.php"
                class="nav-sublink"
            >Service Providers</a>
        </div>

        <a
            href="service-categories.php"
            class="nav-link"
        >
            <span><img
                    src="{{ asset('assets/images/icons/service-catogrey-icon.png') }}"
                    alt="Service Category"
                    class="nav-icon"
                ></span>
            Service category
        </a>
        <a
            href="bookings.php"
            class="nav-link"
        >
            <span><img
                    src="{{ asset('assets/images/icons/booking-icon.png') }}"
                    alt="Bookings"
                    class="nav-icon"
                ></span>
            Bookings
        </a>
        <a
            href="#"
            class="nav-link"
        >
            <span><img
                    src="{{ asset('assets/images/icons/transition-icon.png') }}"
                    alt="Transactions"
                    class="nav-icon"
                ></span>
            Transactions
        </a>
        <a
            href="#"
            class="nav-link"
        >
            <span><img
                    src="{{ asset('assets/images/icons/reviews.png') }}"
                    alt="Reviews & Ratings"
                    class="nav-icon"
                ></span>
            Reviews & Ratings
        </a>
        <a
            href="#"
            class="nav-link"
        >
            <span><img
                    src="{{ asset('assets/images/icons/dispute.png') }}"
                    alt="Disputes & Complaints"
                    class="nav-icon"
                ></span>
            Disputes & complaints
        </a>
        <a
            href="#"
            class="nav-link"
        >
            <span><img
                    src="{{ asset('assets/images/icons/roles.png') }}"
                    alt="Roles & Permissions"
                    class="nav-icon"
                ></span>
            Roles & permission
        </a>
        <a
            href="#"
            class="nav-link"
        >
            <span><img
                    src="{{ asset('assets/images/icons/message.png') }}"
                    alt="Messaging"
                    class="nav-icon"
                ></span>
            Messaging
        </a>
        <a
            href="#"
            class="nav-link"
        >
            <span><img
                    src="{{ asset('assets/images/icons/setting.png') }}"
                    alt="Settings"
                    class="nav-icon"
                ></span>
            Settings
        </a>



    </nav>
</aside>

<!-- ========== End::SIDEBAR (always open) ========== -->
