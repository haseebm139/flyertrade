@extends('admin.layouts.app')

@section('title', 'Roles and Permission')
@section('header', 'Roles and Permission')
@section('content')

    @if ($type === 'role')
        <livewire:admin.roles.role-show :roleId="$data->id" :key="'role-show-' . $data->id" />
        <!-- Role Form Modal for editing -->
        <livewire:admin.roles.role-form :key="'role-form-' . time()" />
    @elseif($type === 'user')
        <!-- User show content can be added here if needed -->
        <div class="container">
            <h1 class="page-title">User Details</h1>
            <p>User: {{ $data->name ?? 'Unknown User' }}</p>
            <!-- Add user-specific content here -->
        </div>
    @endif

    <script>
        // Livewire event listeners for SweetAlert2 notifications
        document.addEventListener('livewire:init', () => {
            Livewire.on('showSweetAlert', (data) => {
                console.log('SweetAlert event received:', data);

                // Handle both old and new parameter formats
                let type, message, title;

                if (typeof data === 'object' && data.type) {
                    // New format with named parameters
                    type = data.type;
                    message = data.message;
                    title = data.title;
                } else if (Array.isArray(data) && data.length >= 3) {
                    // Old format with positional parameters
                    type = data[0];
                    message = data[1];
                    title = data[2];
                } else {
                    console.error('Invalid SweetAlert data format:', data);
                    return;
                }

                console.log('Showing SweetAlert:', {
                    type,
                    message,
                    title
                });

                // Map toastr types to SweetAlert2 types
                const alertType = type === 'error' ? 'error' :
                    type === 'warning' ? 'warning' :
                    type === 'info' ? 'info' : 'success';

                Swal.fire({
                    title: title,
                    text: message,
                    icon: alertType,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            });
        });
    </script>

@endsection
