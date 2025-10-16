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
                <div class="popup-header" style="display: flex; align-items: center; justify-content: space-between; 
            padding: 1vw 1.5vw; border-bottom: 0vw solid #ddd; background-color: #fff;border-radius: 20px;">

                    <span class="popup-title"
                        style="font-size: 1.1vw; font-weight: 500; color: #333; letter-spacing: 0.05vw;">
                        Notification
                    </span>

                    <span class="popup-close" 
                        style="cursor: pointer; display: flex; align-items: center;">
                        <img src="{{ asset('assets/images/icons/iconoir_cancel.png') }}" alt=""
                            style="width: 1.2vw; height: auto; transition: transform 0.2s ease;">
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
                <div style="padding: 0.8vw 1.5vw; text-align: left;">
      <a href="#" style="color: #00796B; font-size: 0.95vw; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 0.3vw;">
        View all notifications 
        <span style="font-size: 1vw;">&#8250;</span>
      </a>
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
   


        <!-- Brand / Profile -->
        <div class="brand" id="profileBtn">
            <div class="logo-placeholder ">
                <img src="{{ asset('assets/images/icons/person.png') }}" alt="" class="admin-img ">
            </div>
            <div>
                <div style="font-weight:500; font-size:0.8vw;">Flyertrade</div>
                <div class="muted small " style=" font-size:0.8vw;">flyertrade@example.com</div>
            </div>
        </div>

        <!-- Profile Popup (moved outside profileBtn) -->
        <div class="popup" id="profilePopup"
            style="display: none; position: absolute; top: 100%; right: 0; background: white; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); z-index: 1000; min-width: 200px;">
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
                        src="{{ asset('assets/images/icons/Icon-logout.png') }}" class="img-log" alt="">
                    Logout</button>
            </div>
        </div>
    </div>
</div>




<!-- ========== End::Header (always open) ========== -->