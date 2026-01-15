@extends('admin.layouts.app')

@section('title', 'Service Users')
@section('header', 'User Management')

@section('content')
    <div class="col-lg-9">

        <livewire:admin.user-stats mode="customers" />
    </div>
    <br>
    <div class="container">
        <h1 class="page-title">Service Users</h1>
    </div>
    <livewire:admin.user-management.user.table />
    <livewire:admin.user-management.user.form />

    @if (session()->has('success_delete'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    background: '#FFFFFF',
                    position: 'top-end',
                    title: 'Success',
                    text: "{{ session('success_delete') }}",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            });
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    background: '#FFFFFF',
                    position: 'top-end',
                    title: 'Error',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            });
        </script>
    @endif



     
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // These scripts are now handled within the Livewire components
            // or by event delegation in the table view.
        });
    </script>

@endsection
