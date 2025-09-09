@extends('admin.layouts.app')

@section('title', 'Service Users')
@section('header', 'User Management')

@section('content')

    <livewire:admin.user-stats mode="customers"/>
    <br>
    <div class="container">
        <h1 class="page-title">Service Users</h1>
    </div>
    <livewire:admin.user-management.user.table />
    <livewire:admin.user-management.user.form />

     
    

     
@endsection
