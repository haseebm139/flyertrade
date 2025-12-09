@extends('admin.layouts.app')

@section('title', 'Notifications')
@section('header', 'Dashboard')

@section('content')


 
 

<div class="users-toolbar">
    <nav class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span class="breadcrumb-separator"><i class="fa-solid fa-chevron-right"></i></span>
        <span class="breadcrumb-current">Notification</span>
    </nav>
</div>
 

@endsection