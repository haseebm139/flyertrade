@extends('admin.layouts.app')

@section('title', 'Service Providers')
@section('header', 'User Management')
@section('content')

    <div class="col-lg-9">

        <livewire:admin.user-stats mode="providers"/>
    </div>
    <br>
    <div class="container">
        <h1 class="page-title">Service Providers</h1>
    </div>
    
    <livewire:admin.user-management.provider.table />
     
    <livewire:admin.user-management.provider.form />
     


    <script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     // These scripts are now handled within the Livewire components
    // });
</script>

@endsection
