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
<livewire:admin.notifications.notifications-list />
@endsection