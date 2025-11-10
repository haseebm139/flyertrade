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
                <h3 class="delete-title">Delete Service Provider?</h3>
                <span class="delete-close" id="closeDeleteModal">&times;</span>
            </div>
            <p class="delete-text">Are you sure you want to delete this service provider?</p>
            <div class="delete-actions justify-content-start">
                <button class="confirm-delete-btn">Delete</button>
                <button class="cancel-delete-btn">Cancel</button>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteModal = document.getElementById("globalDeleteModal");
        const showButtons = document.querySelectorAll(".showDeleteModal");
        const closeButton = document.getElementById("closeDeleteModal");
        const cancelButton = document.querySelector(".cancel-delete-btn");

        // Jab kisi showDeleteModal button pr click ho
        showButtons.forEach(btn => {
            btn.addEventListener("click", () => {
                deleteModal.style.display = "flex"; // modal show karo
            });
        });

        // Close button ya cancel button pr click hone pr modal hide karo
        [closeButton, cancelButton].forEach(btn => {
            btn.addEventListener("click", () => {
                deleteModal.style.display = "none";
            });
        });

        // Optional: backdrop click se bhi band ho
        deleteModal.addEventListener("click", (e) => {
            if (e.target === deleteModal) {
                deleteModal.style.display = "none";
            }
        });
    });
</script>
<style>
    .deleteModal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        justify-content: center;
        align-items: center;
        z-index: 999;
        position: absolute;
        top:80px;
        right: 0px;
    }
    .delete-card {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        min-width: 300px;
    }
</style>
@endsection
