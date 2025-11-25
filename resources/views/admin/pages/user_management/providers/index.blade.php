@extends('admin.layouts.app')

@section('title', 'Service Providers')
@section('header', 'User Management')
@section('content')
    <style>
  .theme-table td{
    font-weight:500;
  }
</style>
     <livewire:admin.user-stats mode="providers"/>
    <br>
    <div class="container">
        <h1 class="page-title">Service Providers</h1>
    </div>
    
    <livewire:admin.user-management.provider.table />
     
    <livewire:admin.user-management.provider.form />
     


    <script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     const deleteModal = document.getElementById("globalDeleteModal");
    //     const showButtons = document.querySelectorAll(".showDeleteModal");
    //     const closeButton = document.getElementById("closeDeleteModal");
    //     const cancelButton = document.querySelector(".cancel-delete-btn");

    //     // Jab kisi showDeleteModal button pr click ho
    //     showButtons.forEach(btn => {
    //         btn.addEventListener("click", () => {
    //             deleteModal.style.display = "flex"; // modal show karo
    //         });
    //     });

    //     // Close button ya cancel button pr click hone pr modal hide karo
    //     [closeButton, cancelButton].forEach(btn => {
    //         btn.addEventListener("click", () => {
    //             deleteModal.style.display = "none";
    //         });
    //     });

    //     // Optional: backdrop click se bhi band ho
    //     deleteModal.addEventListener("click", (e) => {
    //         if (e.target === deleteModal) {
    //             deleteModal.style.display = "none";
    //         }
    //     });
    // });
</script>

@endsection
