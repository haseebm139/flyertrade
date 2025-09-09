@extends('admin.layouts.app')

@section('title', 'Service Providers')
@section('header', 'Booking Management')
@section('content')

     <livewire:admin.user-stats mode="providers"/>
    <br>
    <div class="container">
        <h1 class="page-title">Service Providers</h1>
    </div>
    
    <livewire:admin.user-management.provider.table />
     
    <livewire:admin.user-management.provider.form />
     

     
@endsection
