<!-- ========== Begin::Header (always open) ========== -->
<style>
    .notification_text_wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;

    }

    .notification_item_wrapper {
        /* padding: 0.8vw 1.5vw; */
        padding: 0.8vw 1.5vw 0vw 1.5vw;
        gap: 10px;
        display: flex;
        flex-direction: column;
        /* border-bottom: 0.0625vw solid #f0f0f0; */
    }

    .notification-item.unread {
        /* background-color: #f8f9fa; */
        border-left: 3px solid #007bff;
    }
    .notification-item:hover {
        background-color: #f0f0f0;
    }
    .notification-item {
        display: flex;
        align-items: center;
    }
</style>
<style>
    .filter_active_btna___ {

        border: 0.052vw solid rgba(0, 78, 66, 0.3);

        border-radius: 0.521vw;

        padding: 0.625vw 1.25vw;

        background: rgba(0, 78, 66, 0.1);

        display: inline-flex;
        gap: 0.833vw;

        align-items: center;
        height: 2.708vw;

        min-width: auto;

        font-family: "Clash Display", sans-serif;
        font-weight: 500;
        font-size: 0.833vw;
        line-height: 150%;
        color: #555;
        text-decoration: none;

        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter_active_btna___:hover {
        background: rgba(0, 78, 66, 0.15);
    }

    .filter_active_btna___ span {
        white-space: nowrap;
    }

    .filter_active_btna___ i {
        color: #004E42;
        font-size: 0.833vw;
    }

    #profilePopup svg,
    #notifPopup_____ svg,
    #providerModal svg,
    .modal-content svg {
        width: 0.625vw;
        height: 0.625vw;
    }

    .flyertrade-admin {
        margin-top: 0.26vw;
        font-size: 0.833vw;
    }

    .flyertrade-email {
        font-size: 0.8125vw;
    }

    #notifPopup_____ .popup-title {
        font-size: 0.8vw;
    }

    .view_all_notification {
        padding: 0.8vw 1.5vw;
    }

    .notification-view a {
        font-size: 0.95vw;
    }

    #back_to_notify_modal {
        margin-right: 0.521vw;
    }

    @media(max-width:600px) {

        #profilePopup svg,
        #notifPopup_____ svg,
        #providerModal svg,
        .modal-content svg {
            width: 2vw;
            height: 2vw;
        }

        #profilePopup {
            padding: 2vw;
        }

        #profileBtn .profile-arrows {
            font-size: 1.9vw;
        }

        .flyertrade-admin {
            margin-top: 1vw;
            font-size: 2vw;
        }

        .flyertrade-email,
        .logout-btn {
            font-size: 2vw;
        }

        .img-log {
            width: 2vw !important;
            height: 2vw !important;
            margin-right: 2px;
        }

        .notification-item img {
            width: 4vw !important;
            height: 4vw !important;
        }

        .notification-title {
            font-size: 2vw;
        }

        .notification_item_wrapper {
            gap: 2px;
        }

        #notifPopup_____ .popup-title {
            font-size: 2vw;
        }

        .notification-view {
            font-size: 1.5vw;
        }

        .view_all_notification a {
            font-size: 1.5vw;
        }

        #providerModal .provider-modal-content {
            width: 60vw;
        }

        .provider-modal-body {
            width: 100%;
        }

        .provider-modal-header h6 {
            font-size: 1.5vw;
        }

        #back_to_notify_modal {
            margin-right: 0.521vw;
        }
    }
</style>
<div class="top-row">
    <a id="menubutton"><i class="fa-solid fa-bars"></i></a>
    <div class="page-title">@yield('header', 'Dashboard')</div>
    <div class="d-flex gap-3">
        <!-- Search Input -->
        <input type="text" class="search-box" placeholder="Search">

        <!-- Notifications -->
        <!-- Notification Icon -->
        <div class="icon-btn" id="notifBtn____" style="position: relative;">
            <img id="notification-icon" src="{{ asset('assets/images/icons/notification.svg') }}" alt="Notifications">
            <span id="notification-badge" style="position: absolute; top: -5px; right: -5px; background: #dc3545; color: white; border-radius: 50%; width: 18px; height: 18px; display: none; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;"></span>
            <!-- Notification Popup -->
            <div class="popup ioioios notification_popup" id="notifPopup_____">
                <div class="popup-header"
                    style="display: flex; align-items: center; justify-content: space-between; 
            padding: 1vw 1.5vw 0px; border-bottom: 0vw solid #ddd; background-color: #fff;border-radius: 20px;">

                    <span class="popup-title" style=" font-weight: 600; color: #333; letter-spacing: 0.05vw;">
                        Notification
                    </span>

                    <span class="popup-close" id="close_notify_popop"
                        style="cursor: pointer; display: flex; align-items: center;">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75"
                                stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg>
                    </span>
                </div>

                <livewire:admin.notifications.header-notifications />
            </div>

        </div>

        <!-- Provider Verification Modal -->
        <div class="provider-modal" id="providerModal" style="display: none;">
            <div class="provider-modal-content">
                <!-- Header -->
                <div class="provider-modal-header">
                    <h6>
                        <i class="fa-solid fa-arrow-left" id="back_to_notify_modal" style="cursor:pointer" onclick="closeProviderModal()"></i>
                        <span id="provider-modal-title">Notifications</span>
                    </h6>
                    <button class="provider-modal-close" id="provide_modal_close" data-close="providerModal" onclick="closeProviderModal()">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75"
                                stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="provider-modal-body" id="provider-modal-body">
                    <div style="text-align: center; padding: 2vw; color: #999;">
                        Loading...
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
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75"
                            stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </svg>
                </span>
            </div>
            <div class="profile-card">
                @php
                    $avatar = auth()->user()->avatar ?? '';
                    $avatarSrc = $avatar ? asset($avatar) : asset('assets/images/icons/person.svg');
                @endphp
                <img src="{{ $avatarSrc }}" alt="User" onerror="this.onerror=null;this.src='{{ asset('assets/images/icons/person.svg') }}';">
                <div style="font-weight:500;" class="flyertrade-admin">{{ auth()->user()->name ?? '-' }}</div>
                <div class="muted small flyertrade-email">{{ auth()->user()->email ?? '-'}}</div>

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
        closeProviderModal();
    });
    // Close modal when clicking outside modal content
    window.addEventListener('click', (e) => {
        if (e.target === providerModal) {
            providerModal.style.display = 'none';
        }
    });

    // Header Notifications Functions
    let isLoadingGroup = false;
    let currentLoadingGroup = null;
    
    function updateBadge(count) {
        const badge = document.getElementById('notification-badge');
        const icon = document.getElementById('notification-icon');
        
        // if (badge) {
        //     badge.textContent = count > 9 ? '9+' : count.toString();
        //     badge.style.display = count > 0 ? 'flex' : 'none';
        // }
        
        // Update icon based on unread count
        if (icon) {
            if (count > 0) {
                icon.src = '{{ asset("assets/images/icons/notification_with_dot.svg") }}';
            } else {
                icon.src = '{{ asset("assets/images/icons/notification.svg") }}';
            }
        }
    }

    function handleGroupView(event, groupType) {
        event.preventDefault();
        event.stopPropagation();
        
        // Prevent multiple simultaneous calls
        if (isLoadingGroup && currentLoadingGroup === groupType) {
            return;
        }
        
        // Find the Livewire component
        const componentEl = document.querySelector('[wire\\:poll\\.10s="loadNotifications"]');
        if (!componentEl) {
            console.error('Notification component not found');
            return;
        }
        
        const componentId = componentEl.getAttribute('wire:id');
        if (!componentId) {
            console.error('Component ID not found');
            return;
        }
        
        const component = Livewire.find(componentId);
        if (!component) {
            console.error('Livewire component not found with ID:', componentId);
            return;
        }
        
        // Set loading state
        isLoadingGroup = true;
        currentLoadingGroup = groupType;
        
        // Show loading in modal body
        const modalBody = document.getElementById('provider-modal-body');
        const modalTitle = document.getElementById('provider-modal-title');
        if (modalBody) {
            modalBody.innerHTML = '<div style="text-align: center; padding: 2vw; color: #999;">Loading...</div>';
        }
        if (modalTitle) {
            modalTitle.textContent = 'Loading...';
        }
        
        // Open modal immediately
        openProviderModal();
        
        // Call server to process and get formatted data
        component.call('viewGroup', groupType).then(() => {
            // Fallback: if event doesn't fire, get data directly after a short delay
            setTimeout(() => {
                if (isLoadingGroup) {
                    const updatedComp = Livewire.find(componentId);
                    if (updatedComp) {
                        const selectedGroup = updatedComp.get('selectedGroup');
                        const notifications = updatedComp.get('selectedGroupNotifications');
                        if (selectedGroup && notifications) {
                            updateProviderModalWithData(selectedGroup, notifications);
                            isLoadingGroup = false;
                            currentLoadingGroup = null;
                        }
                    }
                }
            }, 500);
        }).catch((error) => {
            console.error('Error loading group:', error);
            isLoadingGroup = false;
            currentLoadingGroup = null;
            if (modalBody) {
                modalBody.innerHTML = '<div style="text-align: center; padding: 2vw; color: #dc3545;">Error loading notifications.</div>';
            }
        });
    }

    function openProviderModal() {
        const notifPopup = document.getElementById('notifPopup_____');
        const providerModal = document.getElementById('providerModal');
        
        if (notifPopup) {
            notifPopup.style.display = 'none';
        }
        
        if (providerModal) {
            providerModal.style.display = 'flex';
        }
    }

    function updateProviderModalWithData(selectedGroup, notifications) {
        const modalTitle = document.getElementById('provider-modal-title');
        const modalBody = document.getElementById('provider-modal-body');
        const componentEl = document.querySelector('[wire\\:poll\\.10s="loadNotifications"]');
        const componentId = componentEl ? componentEl.getAttribute('wire:id') : '';
        
        if (selectedGroup && modalTitle) {
            modalTitle.textContent = selectedGroup.count + ' ' + selectedGroup.title;
        }
        
        if (notifications && modalBody) {
            let html = '';
            if (notifications.length > 0) {
                notifications.forEach(function(notif) {
                    const iconUrl = notif.icon_url || '{{ asset("assets/images/icons/person.svg") }}';
                    const message = notif.message || '';
                    const actionUrl = notif.action_url || '#';
                    const hasProfile = notif.has_profile || (notif.data && (notif.data.provider_id || notif.data.customer_id));
                    const linkText = notif.type === 'provider_registered' ? 'View Profile' : 'View';
                    const isUnread = !notif.read_at; // Check if read_at is null
                    const bgColor = isUnread ? 'background-color: #f0f9ff; border-left: 3px solid #00796B;' : '';
                    const hasAction = actionUrl !== '#';
                    const actionLink = hasAction
                        ? '<a href="' + actionUrl + '" class="provider-view-profile" onclick="event.preventDefault(); const comp = Livewire.find(\'' + componentId + '\'); if(comp) comp.call(\'markAsRead\', ' + notif.id + ').then(() => window.location.href = \'' + actionUrl + '\');">' + linkText + '</a>'
                        : '<span class="provider-view-profile" style="cursor: default; color: #999;">-</span>';
                    
                    const itemClick = 'onclick="event.preventDefault(); const comp = Livewire.find(\'' + componentId + '\'); if(comp) comp.call(\'markAsRead\', ' + notif.id + ').then(() => { if (\'' + actionUrl + '\' !== \'#\') { window.location.href = \'' + actionUrl + '\'; } });"';
                    const itemStyle = bgColor + ' cursor: pointer;';

                    html += '<div class="provider-item" style="' + itemStyle + '" ' + itemClick + '>';
                    html += '<img src="' + iconUrl + '" alt="">';
                    html += '<span>' + message + '</span>';
                    html += actionLink;
                    html += '</div>';
                });
            } else {
                html = '<div style="text-align: center; padding: 2vw; color: #999;">No notifications to display.</div>';
            }
            modalBody.innerHTML = html;
        }
    }

    function updateProviderModal(component) {
        if (!component) {
            // Try to find component
            const componentEl = document.querySelector('[wire\\:poll\\.10s="loadNotifications"]');
            if (componentEl) {
                const componentId = componentEl.getAttribute('wire:id');
                if (componentId) {
                    component = Livewire.find(componentId);
                }
            }
        }
        
        if (!component) {
            console.error('Component not found for updateProviderModal');
            return;
        }
        
        const modalTitle = document.getElementById('provider-modal-title');
        const modalBody = document.getElementById('provider-modal-body');
        
        try {
            // Get component ID for markAsRead calls
            const componentEl = document.querySelector('[wire\\:poll\\.10s="loadNotifications"]');
            const componentId = componentEl ? componentEl.getAttribute('wire:id') : (component.__instance?.id || '');
            
            const selectedGroup = component.get('selectedGroup');
            const notifications = component.get('selectedGroupNotifications');
            
            console.log('Selected Group:', selectedGroup);
            console.log('Notifications:', notifications);
            
            if (selectedGroup && modalTitle) {
                modalTitle.textContent = selectedGroup.count + ' ' + selectedGroup.title;
            }
            
            if (notifications && modalBody) {
                let html = '';
                if (notifications.length > 0) {
                    notifications.forEach(function(notif) {
                        const iconUrl = notif.icon_url || '{{ asset("assets/images/icons/person.svg") }}';
                        const message = notif.message || '';
                        const actionUrl = notif.action_url || '#';
                        const hasProfile = notif.has_profile || (notif.data && (notif.data.provider_id || notif.data.customer_id));
                        const linkText = hasProfile ? 'View Profile' : 'View';
                        const isUnread = !notif.read_at; // Check if read_at is null
                        const bgColor = isUnread ? 'background-color: #f0f9ff; border-left: 3px solid #00796B;' : '';
                        const hasAction = actionUrl !== '#';
                        const actionLink = hasAction
                            ? '<a href="' + actionUrl + '" class="provider-view-profile" onclick="event.preventDefault(); const comp = Livewire.find(\'' + componentId + '\'); if(comp) comp.call(\'markAsRead\', ' + notif.id + ').then(() => window.location.href = \'' + actionUrl + '\');">' + linkText + '</a>'
                            : '<span class="provider-view-profile" style="cursor: default; color: #999;">-</span>';
                        
                        const itemClick = 'onclick="event.preventDefault(); const comp = Livewire.find(\'' + componentId + '\'); if(comp) comp.call(\'markAsRead\', ' + notif.id + ').then(() => { if (\'' + actionUrl + '\' !== \'#\') { window.location.href = \'' + actionUrl + '\'; } });"';
                        const itemStyle = bgColor + ' cursor: pointer;';

                        html += '<div class="provider-item" style="' + itemStyle + '" ' + itemClick + '>';
                        html += '<img src="' + iconUrl + '" alt="">';
                        html += '<span>' + message + '</span>';
                        html += actionLink;
                        html += '</div>';
                    });
                } else {
                    html = '<div style="text-align: center; padding: 2vw; color: #999;">No notifications to display.</div>';
                }
                modalBody.innerHTML = html;
            } else {
                if (modalBody) {
                    modalBody.innerHTML = '<div style="text-align: center; padding: 2vw; color: #999;">No notifications found.</div>';
                }
            }
        } catch (error) {
            console.error('Error updating modal:', error);
            if (modalBody) {
                modalBody.innerHTML = '<div style="text-align: center; padding: 2vw; color: #dc3545;">Error loading notifications.</div>';
            }
        }
    }

    function closeProviderModal() {
        const providerModal = document.getElementById('providerModal');
        const notifPopup = document.getElementById('notifPopup_____');
        
        if (providerModal) {
            providerModal.style.display = 'none';
        }
        
        if (notifPopup) {
            notifPopup.style.display = 'block';
        }
        
        // Close group view - find component
        const notifComponent = document.querySelector('[wire\\:poll\\.10s="loadNotifications"]')?.__livewire;
        if (notifComponent) {
            notifComponent.call('closeGroupView');
        }
    }

    // Listen for notification updates
    document.addEventListener('livewire:init', () => {
        Livewire.on('notificationUpdated', (data) => {
             
            updateBadge(data?.unreadCount || 0);
        });
        
        // Listen for group loaded event - this fires immediately when data is ready
        Livewire.on('groupLoaded', (data) => {
            console.log('Group loaded event received:', data);
            if (data && data.group && data.notifications) {
                // Update modal immediately with the data (no delay)
                updateProviderModalWithData(data.group, data.notifications);
                // Reset loading state immediately
                isLoadingGroup = false;
                currentLoadingGroup = null;
            } else {
                console.warn('Group loaded event received but data is incomplete:', data);
            }
        });
        
        // Listen for component updates to refresh modal and badge (fallback, but event-based is primary)
        Livewire.hook('morph.updated', ({ component, el }) => {
            try {
                if (el && (el.hasAttribute('wire:poll.10s') || el.hasAttribute('wire:poll'))) {
                    const componentId = el.getAttribute('wire:id');
                    if (componentId) {
                        const comp = Livewire.find(componentId);
                        if (comp) {
                            // Update badge when component updates (polling refresh)
                            const unreadCount = comp.get('unreadCount') || 0;
                            updateBadge(unreadCount);
                            
                            // Update modal if we're waiting for data (fallback)
                            if (comp.get('selectedGroup') && isLoadingGroup) {
                                updateProviderModal(comp);
                                isLoadingGroup = false;
                                currentLoadingGroup = null;
                            }
                        }
                    }
                }
            } catch (error) {
                // Silently ignore morph errors
                console.debug('Morph update error (ignored):', error);
            }
        });
        
        // Initialize icon on page load
        setTimeout(() => {
            const componentEl = document.querySelector('[wire\\:poll\\.10s="loadNotifications"]');
            if (componentEl) {
                const componentId = componentEl.getAttribute('wire:id');
                if (componentId) {
                    const comp = Livewire.find(componentId);
                    if (comp) {
                        const unreadCount = comp.get('unreadCount') || 0;
                        updateBadge(unreadCount);
                    }
                }
            }
        }, 200);
        
        // Listen for all Livewire updates to check for notification changes
        Livewire.hook('morph', ({ el, component }) => {
            try {
                if (el && (el.hasAttribute('wire:poll.10s') || el.hasAttribute('wire:poll'))) {
                    const componentId = el.getAttribute('wire:id');
                    if (componentId) {
                        setTimeout(() => {
                            try {
                                const comp = Livewire.find(componentId);
                                if (comp) {
                                    const unreadCount = comp.get('unreadCount') || 0;
                                    updateBadge(unreadCount);
                                }
                            } catch (e) {
                                // Component might not exist yet, ignore
                            }
                        }, 100);
                    }
                }
            } catch (error) {
                // Silently ignore morph errors
                console.debug('Morph hook error (ignored):', error);
            }
        });
        
        // Also listen for component property updates
        Livewire.hook('commit', ({ component, commit }) => {
            try {
                if (component && component.__instance) {
                    const componentEl = document.querySelector('[wire\\:poll\\.10s="loadNotifications"]');
                    if (componentEl && componentEl.getAttribute('wire:id') === component.__instance.id) {
                        commit(() => {
                            setTimeout(() => {
                                try {
                                    const unreadCount = component.get('unreadCount') || 0;
                                    updateBadge(unreadCount);
                                } catch (e) {
                                    // Ignore errors
                                }
                            }, 50);
                        });
                    }
                }
            } catch (error) {
                // Silently ignore commit errors
                console.debug('Commit hook error (ignored):', error);
            }
        });
    });
</script>


<!-- ========== End::Header (always open) ========== -->
