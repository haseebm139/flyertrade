<!-- ========== Begin::Header (always open) ========== -->
<style>
    .notification_text_wrapper{
        display: flex;
        align-items: center;
        justify-content: space-between;

    }
    .notification_item_wrapper{
       /* padding: 0.8vw 1.5vw; */
       padding: 0.8vw 1.5vw 0vw 1.5vw;
         gap:10px;  
         display: flex;
         flex-direction: column;
             /* border-bottom: 0.0625vw solid #f0f0f0; */
    }
    
</style>
    <style>
        .filter_active_btna___{
            border: 0.052vw solid  rgba(0, 78, 66, 0.3);
            border-radius: 0.521vw;
            padding: 0.625vw 1.25vw;
            background:  rgba(0, 78, 66, 0.3);
            display: none;
            gap:0.521vw;
            align-items: center;


            font-family:"Clash Display", sans-serif;
            font-weight: 500;
            font-size: 0.833vw;
            line-height: 150%;
            text-align: center;
            color: #555;

            cursor:pointer;
        }
        #profilePopup svg,
        #notifPopup_____ svg,
        #providerModal svg,
        .modal-content svg
        {
            width: 0.625vw;
            height: 0.625vw;
        }
    
 
        
    </style>
<div class="top-row">
    <a><i class="fa-solid fa-bars"></i></a>
    <div class="page-title">@yield('header', 'Dashboard')</div>
    <div class="d-flex gap-3">
        <!-- Search Input -->
        <input type="text" class="search-box" placeholder="Search" >

        <!-- Notifications -->
        <!-- Notification Icon -->
        <div class="icon-btn" id="notifBtn____">
            <img src="{{ asset('assets/images/icons/notification.svg') }}" alt="Notifications">
            <!-- Notification Popup -->
            <div class="popup ioioios notification_popup" id="notifPopup_____">
                <div class="popup-header"
                    style="display: flex; align-items: center; justify-content: space-between; 
            padding: 1vw 1.5vw 0px; border-bottom: 0vw solid #ddd; background-color: #fff;border-radius: 20px;">

                    <span class="popup-title"
                        style="font-size: 1vw; font-weight: 600; color: #333; letter-spacing: 0.05vw;">
                        Notification
                    </span>

                    <span class="popup-close" id="close_notify_popop"
                        style="cursor: pointer; display: flex; align-items: center;">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </div>

                <div class="notification_item_wrapper">
                    <div  class="notification-item">
                        <img src="{{ asset('assets/images/icons/manage.svg') }}" alt="">
                        <div class="notification-content">
                            <div class="notification-title">Document verification</div>
                        </div>
                    </div>
                    
                    <div class="notification_text_wrapper">
                        <div class="notification-text">
                                3 New Providers Awaiting Document
                                Verification.
                            </div>
                        <div class="notification-view" data-modal="providerModal" >View</div>
                    </div>
                    
                </div>
                      <div class="notification_item_wrapper ">
                    <div  class="notification-item">
                        <img src="{{ asset('assets/images/icons/manage.svg') }}" alt="">
                        <div class="notification-content">
                            <div class="notification-title">High Cancellation Alert</div>
                        </div>
                    </div>
                    
                    <div class="notification_text_wrapper">
                        <div class="notification-text">
                                David E. has 3 cancellations this week.
                            </div>
                        <div class="notification-view" data-modal="providerModal" >View</div>
                    </div>
                    
                </div>
              
                <div style="padding: 0.8vw 1.5vw; text-align: left;">
                    <a href="{{ route('notification.index') }}"
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
                    <h6><i class="fa-solid fa-arrow-left" id="back_to_notify_modal"
                            style="margin-right:10px;cursor:pointer"></i> 3 New Providers Awaiting Document
                        Verification.</h6>
                    <button class="provider-modal-close" id="provide_modal_close" data-close="providerModal">
                       <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="provider-modal-body">
                    <div class="provider-item">
                        <img src="{{ asset('assets/images/icons/person.svg') }}" alt="">
                        <span>Johnbosco Davies</span>
                        <a href="#" class="provider-view-profile">View profile</a>
                    </div>

                    <div class="provider-item">
                        <img src="{{ asset('assets/images/icons/person.svg') }}" alt="">
                        <span>Jane Doe</span>
                        <a href="#" class="provider-view-profile">View profile</a>
                    </div>

                    <div class="provider-item">
                        <img src="{{ asset('assets/images/icons/person.svg') }}" alt="">
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
                <img src="{{ asset('assets/images/icons/main_group.svg') }}" alt="" class="admin-img">
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
                <span class="popup-close" data-close="profilePopup">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
            </div>
            <div class="profile-card">
                <img src="{{ asset('assets/images/icons/person.svg') }}" alt="User">
                <div style="font-weight:500; margin-top:5px; font-size:0.833vw;">Flyertrade Admin</div>
                <div class="muted small">flyertrade@example.com</div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <button class="logout-btn"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <img
                        src="{{ asset('assets/images/icons/Icon-logout.svg') }}" class="img-log" alt="">
                    Logout</button>
            </div>
        </div>
    </div>
</div>


<script>
    // Get modal and close button
    const notifBtn____ = document.getElementById('notifBtn____');
    const providerModal = document.getElementById('providerModal');
    const closeBtn = document.getElementById('close_notify_popop');
    const provide_modal_close = document.getElementById('provide_modal_close');
    const back_to_notify_modal = document.getElementById('back_to_notify_modal');
    const notifPopup = document.getElementById('notifPopup_____'); // only if exists

    // Open modal
    document.querySelectorAll('[data-modal="providerModal"]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            notifPopup.style.display = 'none';
            providerModal.style.display = 'flex';
            // providerModal.style.animation = 'fadeIn 0.2s ease';
        });
    });
    // Close modal on close button click
    notifBtn____.addEventListener('click', () => {

        notifPopup.style.display = 'block';
         providerModal.style.display = 'none';
    });
    // Close modal on close button click
    closeBtn.addEventListener('click', () => {
        notifPopup.style.display = 'none';
    });
    // Close modal on close button click
    provide_modal_close.addEventListener('click', () => {
        providerModal.style.display = 'none';
    });
    back_to_notify_modal.addEventListener('click', () => {

        notifPopup.style.display = 'block';
        // providerModal.style.animation = 'fadeIn 0.2s ease';
        providerModal.style.display = 'none';
        // providerModal.style.animation = 'fadeIn 0.2s ease';
    });
    // Close modal when clicking outside modal content
    window.addEventListener('click', (e) => {
        if (e.target === providerModal) {
            providerModal.style.display = 'none';
        }
    });
</script>


<!-- ========== End::Header (always open) ========== -->
