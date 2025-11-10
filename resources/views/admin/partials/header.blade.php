<!-- ========== Begin::Header (always open) ========== -->

<div class="top-row">
    <div class="page-title">@yield('header', 'Dashboard')</div>
    <div class="d-flex gap-3">
        <!-- Search Input -->
        <input type="text" class="search-box" placeholder="Search...">

        <!-- Notifications -->
        <!-- Notification Icon -->
        <div class="icon-btn" id="notifBtn____">
            <img src="{{ asset('assets/images/icons/notification.png') }}" alt="Notifications">
            <!-- Notification Popup -->
            <div class="popup ioioios" id="notifPopup">
                <div class="popup-header" style="display: flex; align-items: center; justify-content: space-between; 
            padding: 1vw 1.5vw; border-bottom: 0vw solid #ddd; background-color: #fff;border-radius: 20px;">

                    <span class="popup-title"
                        style="font-size: 1.2vw; font-weight: 600; color: #333; letter-spacing: 0.05vw;">
                        Notification
                    </span>

                    <span class="popup-close" style="cursor: pointer; display: flex; align-items: center;">
                        <img src="{{ asset('assets/images/icons/iconoir_cancel.png') }}" alt=""
                            style="width: 1.2vw; height: auto; transition: transform 0.2s ease;">
                    </span>
                </div>

                <div class="notification-item">
                    <img src="{{ asset('assets/images/icons/manage.png') }}" alt="">
                    <div class="notification-content">
                        <div class="notification-title">Document verification</div>
                        <br>
                        <div class="notification-text" style="margin-left:-3vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                    </div>
                    <div class="notification-view" data-modal="providerModal" style="    margin-top: 2.9vw;
">View</div>
                </div>
                <div class="notification-item">
                    <img src="{{ asset('assets/images/icons/manage.png') }}" alt="">
                    <div class="notification-content">
                        <div class="notification-title">High Cancellation Alert</div>
                        <br>
                        <div class="notification-text" style="margin-left:-3vw;">David E. has 3 cancellations this week.
                        </div>
                    </div>
                    <div class="notification-view" style="    margin-top: 2.9vw;
">View</div>
                </div>
                <div style="padding: 0.8vw 1.5vw; text-align: left;">
                    <a href="#"
                        style="color: #00796B; font-size: 0.95vw; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 0.3vw;">
                        View all notifications
                        <span style="font-size: 1vw;">&#8250;</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Provider Verification Modal -->
        <div class="provider-modal" id="providerModal">
            <div class="provider-modal-content">
                <!-- Header -->
                <div class="provider-modal-header">
                    <h6><i class="fa-solid fa-arrow-left" style="margin-right:10px;"></i> 3 New Providers Awaiting Document Verification.</h6>
                    <button class="provider-modal-close" data-close="providerModal">
                        <img src="{{ asset('assets/images/icons/iconoir_cancel.png') }}" alt="Close">
                    </button>
                </div>

                <!-- Body -->
                <div class="provider-modal-body">
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
        </div>

        <!-- Provider Verification Popup -->
        <div class="icon-separator"></div> <!-- 🧱 yahan border line -->


        <!-- Brand / Profile -->
        <div class="brand" id="profileBtn">
            <div class="logo-placeholder">
                <img src="{{ asset('assets/images/icons/main-group.png') }}" alt="" class="admin-img">
            </div>

            <div class="profile-info">
                <div class="profile-name">Flyertrade</div>
                <div class="profile-email">flyertrade@gmail.com</div>
            </div>

            <div class="profile-arrows">
                <i class="fa-solid fa-chevron-up"></i>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </div>


        <!-- Profile Popup (moved outside profileBtn) -->
        <div class="popup" id="profilePopup"
            style="display: none; position: absolute; top: 100%; right: 0; background: white; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); z-index: 9999999; min-width: 200px;">
            <div class="popup-header">
                <span class="popup-close" data-close="profilePopup"><img
                        src="{{ asset('assets/images/icons/iconoir_cancel.png') }}" alt=""></span>
            </div>
            <div class="profile-card">
                <img src="{{ asset('assets/images/icons/person.png') }}" alt="User">
                <div style="font-weight:500; margin-top:5px; font-size:16px;">Flyertrade Admin</div>
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


<script>
// Get modal and close button
const notifBtn____ = document.getElementById('notifBtn____');
const providerModal = document.getElementById('providerModal');
const closeBtn = document.querySelector('[data-close="providerModal"]');
const notifPopup = document.getElementById('notifPopup'); // only if exists

// Open modal
document.querySelectorAll('[data-modal="providerModal"]').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (notifPopup) notifPopup.style.display = 'none';
        providerModal.style.display = 'none';
        providerModal.style.animation = 'fadeIn 0.2s ease';
    });
});
// Close modal on close button click
notifBtn____.addEventListener('click', () => {
    notifPopup.style.display = 'block';
});
// Close modal on close button click
closeBtn.addEventListener('click', () => {
      if (notifPopup) notifPopup.style.display = 'block';
});

// Close modal when clicking outside modal content
window.addEventListener('click', (e) => {
    if (e.target === providerModal) {
        providerModal.style.display = 'none';
    }
});
</script>


<!-- ========== End::Header (always open) ========== -->