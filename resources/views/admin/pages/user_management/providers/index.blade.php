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
     

         <!-- ✅ Global Delete Modal -->
    <div id="globalDeleteModal" class="deleteModal" style="display: none;">
        <div class="delete-card">
            <div class="delete-card-header">
                <h3 class="delete-title">Delete Service</h3>
                <span class="delete-close" id="closeDeleteModal">&times;</span>
            </div>
            <p class="delete-text">Are you sure you want to delete this service?</p>
            <div class="delete-actions justify-content-start">
                <button class="confirm-delete-btn">Delete</button>
                <button class="cancel-delete-btn">Cancel</button>
            </div>
        </div>
    </div>
@endsection
