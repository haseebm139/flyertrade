<!-- ========== Begin::Header (always open) ========== -->

<div class="top-row">
    <div class="page-title">@yield('header', 'Dashboard')</div>
    <div class="d-flex gap-3">
        <!-- Search Input -->
        <input type="text" class="search-box" placeholder="Search...">

        <!-- Notifications -->
        <!-- Notification Icon -->
        <div class="icon-btn" id="notifBtn">
            <img src="{{ asset('assets/images/icons/notification.png') }}" alt="Notifications">
            <!-- Notification Popup -->
            <div class="popup ioioios" id="notifPopup">
                <div class="popup-header">
                    <span class="popup-close" data-close="notifPopup">
                        <img src="{{ asset('assets/images/icons/iconoir_cancel.png') }}" alt="">
                    </span>
                </div>
                <div class="notification-item">
                    <img src="{{ asset('assets/images/icons/manage.png') }}" alt="">
                    <div class="notification-content">
                        <div class="notification-title">Document verification</div>
                        <div class="notification-text">3 New Providers Awaiting Document Verification.
                        </div>
                    </div>
                    <div class="notification-view" data-modal="providerModal">View</div>
                </div>
                <div class="notification-item">
                    <img src="{{ asset('assets/images/icons/manage.png') }}" alt="">
                    <div class="notification-content">
                        <div class="notification-title">High Cancellation Alert</div>
                        <div class="notification-text">David E. has 3 cancellations this week.</div>
                    </div>
                    <div class="notification-view">View</div>
                </div>
            </div>
        </div>

        <!-- Provider Verification Modal -->
        <div class="provider-modal" id="providerModal">
            <div class="provider-modal-header">
                <span class="provider-modal-close" data-close="providerModal">
                    <img src="{{ asset('assets/images/icons/iconoir_cancel.png') }}" alt="">
                </span>
            </div>
            <div class="provider-modal-body">
                <h6>

                    3 New Providers Awaiting Document Verification.
                </h6>
                <div class="provider-item">
                    <img src="{{ asset('assets/images/icons/person.png') }}" alt="">
                    <span>Johnbosco Davies</span>
                    <a href="#" class="provider-view-profile">View profile</a>
                </div>
                <div class="provider-item">
                    <img src="{{ asset('assets/images/icons/person.png') }}" alt="">
                    <span>Jane Doe</span>
                    <a href="#" class="provider-view-profile">View profile</a>
                </div>
                <div class="provider-item">
                    <img src="{{ asset('assets/images/icons/person.png') }}" alt="">
                    <span>Michael Smith</span>
                    <a href="#" class="provider-view-profile">View profile</a>
                </div>
            </div>
        </div>

        <!-- Provider Verification Popup -->
        <div class="popup" id="docModal">
            <div class="popup-header">
                <span class="popup-close" data-close="docModal">
                    <img src="{{ asset('assets/images/icons/iconoir_cancel.png') }}" alt="">
                </span>
            </div>
            <div class="provider-popup">
                <h6>

                    3 New Providers Awaiting Document Verification.
                </h6>
                <div class="provider-item">
                    <img src="{{ asset('assets/images/icons/person.png') }}" alt="">
                    <span>Johnbosco Davies</span>
                    <a href="#" class="view-profile">View profile</a>
                </div>
                <div class="provider-item">
                    <img src="{{ asset('assets/images/icons/person.png') }}" alt="">
                    <span>Jane Doe</span>
                    <a href="#" class="view-profile">View profile</a>
                </div>
                <div class="provider-item">
                    <img src="{{ asset('assets/images/icons/person.png') }}" alt="">
                    <span>Michael Smith</span>
                    <a href="#" class="view-profile">View profile</a>
                </div>
            </div>
        </div>


        <!-- Brand / Profile -->
        <div class="brand" id="profileBtn">
            <div class="logo-placeholder ">
                <img src="{{ asset('assets/images/icons/person.png') }}" alt="" class="admin-img ">
            </div>
            <div>
                <div style="font-weight:500; font-size:0.8vw;">Flyertrade</div>
                <div class="muted small " style=" font-size:0.8vw;">flyertrade@example.com</div>
            </div>
            <div class="popup" id="profilePopup">
                <div class="popup-header">

                    <span class="popup-close" data-close="profilePopup"><img
                            src="{{ asset('assets/images/icons/iconoir_cancel.png') }}" alt=""></span>
                </div>
                <div class="profile-card">
                    <img src="{{ asset('assets/images/icons/person.png') }}" alt="User">
                    <div style="font-weight:400; margin-top:5px; font-size:16px;">Flyertrade</div>
                    <div class="muted small">flyertrade@example.com</div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    <button class="logout-btn"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <img
                            src="{{ asset('assets/images/icons/Icon-logout.png') }}" class="img-log"alt="">
                        Logout</button>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- ========== End::Header (always open) ========== -->
