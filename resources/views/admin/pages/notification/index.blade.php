@extends('admin.layouts.app')

@section('title', 'Notifications')
@section('header', 'Dashboard')

@section('content')




<style>
    .tab-content {
        max-width: 36.719vw;
    }

    .notification_item_wrapper {
        padding-left: 0px;
        padding-right: 0px;
    }
    .profile-details {
    padding: 0vw 1vw 1vw;
}
</style>
<div class="users-toolbar">
    <nav class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span class="breadcrumb-separator"><i class="fa-solid fa-chevron-right"></i></span>
        <span class="breadcrumb-current">Notification</span>
    </nav>
</div>
<!-- tabs-section -->
<div class="tabs-section">
    <div class="tab active" data-target="all">All</div>
    <div class="tab" data-target="reviews">Reviews</div>
    <div class="tab" data-target="bookings">Bookings</div>
    <div class="tab" data-target="transactions">Transactions</div>
    <div class="tab" data-target="admin-actions">Admin actions</div>
</div>
<!-- personal details -->
<div id="all" class="tab-content active" style="border: 0.1vw solid #ddd;border-radius: 0.521vw;">
    <h3 style="font-weight:500;font-size:0.833vw;color:#1b1b1b;border:none;padding-bottom:0px;" class="profile-heading">Today</h3>
    <div class="profile-details">
        <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}" alt="" style="border-radius: 0.521vw;">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <h3 style="font-weight:500;font-size:0.833vw;color:#1b1b1b;border:none;padding-bottom:0px;" class="profile-heading">Yesterday</h3>
    <div class="profile-details">
        <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- personal details-end -->
<!-- reviews details -->
<div id="reviews" class="tab-content " style="border: 0.1vw solid #ddd;border-radius: 0.521vw;">
    <h3 style="font-weight:500;font-size:0.833vw;color:#1b1b1b;border:none;padding-bottom:0px;" class="profile-heading">Today</h3>
    <div class="profile-details">
        <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}" alt="" style="border-radius: 0.521vw;">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <h3 style="font-weight:500;font-size:0.833vw;color:#1b1b1b;border:none;padding-bottom:0px;" class="profile-heading">Yesterday</h3>
    <div class="profile-details">
        <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- reviews details-end -->
 <!-- bookings details -->
<div id="bookings" class="tab-content " style="border: 0.1vw solid #ddd;border-radius: 0.521vw;">
    <h3 style="font-weight:500;font-size:0.833vw;color:#1b1b1b;border:none;padding-bottom:0px;" class="profile-heading">Today</h3>
    <div class="profile-details">
        <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}" alt="" style="border-radius: 0.521vw;">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <h3 style="font-weight:500;font-size:0.833vw;color:#1b1b1b;border:none;padding-bottom:0px;" class="profile-heading">Yesterday</h3>
    <div class="profile-details">
        <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- bookings details-end -->
  <!-- transactions details -->
<div id="transactions" class="tab-content " style="border: 0.1vw solid #ddd;border-radius: 0.521vw;">
    <h3 style="font-weight:500;font-size:0.833vw;color:#1b1b1b;border:none;padding-bottom:0px;" class="profile-heading">Today</h3>
    <div class="profile-details">
        <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}" alt="" style="border-radius: 0.521vw;">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <h3 style="font-weight:500;font-size:0.833vw;color:#1b1b1b;border:none;padding-bottom:0px;" class="profile-heading">Yesterday</h3>
    <div class="profile-details">
        <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- transactions details-end -->
   <!-- admin-actions details -->
<div id="admin-actions" class="tab-content " style="border: 0.1vw solid #ddd;border-radius: 0.521vw;">
    <h3 style="font-weight:500;font-size:0.833vw;color:#1b1b1b;border:none;padding-bottom:0px;" class="profile-heading">Today</h3>
    <div class="profile-details">
        <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}" alt="" style="border-radius: 0.521vw;">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <h3 style="font-weight:500;font-size:0.833vw;color:#1b1b1b;border:none;padding-bottom:0px;" class="profile-heading">Yesterday</h3>
    <div class="profile-details">
        <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
                <div class="notification_item_wrapper">
            <div class="notification-item">
                <img src="{{ asset('assets/images/icons/manage.svg') }}"  style="border-radius: 0.521vw;" alt="">
                <div class="notification-content">
                    <div class="notification-title" style="line-height:1;margin-bottom:0.433vw;">Document verification</div>
                    <div class="notification_text_wrapper">
                        <div class="notification-text" style="line-height:1;font-weight: 500;color:#8e8e8e; font-size: 0.833vw;">
                            3 New Providers Awaiting Document
                            Verification.
                        </div>
                        <div class="notification-view" data-modal="providerModal" style="line-height:1;font-weight: 500; font-size: 0.833vw;color:#8e8e8e;">30 min ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- admin-actions details-end -->
@endsection