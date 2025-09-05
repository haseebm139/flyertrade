@extends('admin.layouts.app')

@section('title', 'Service Category')

@section('content')

    <div class="container">
        <h1 class="page-title">Service Categories</h1>
    </div>


    <livewire:admin.service-categories.table />
    <livewire:admin.service-categories.form />



    <!-- table modal -->


    <div
        class="modal fade "
        id="userModal"
        tabindex="-1"
        aria-labelledby="userModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered jusoio">
            <div class="modal-content  rounded-3">
                <div class="modal-header border-0">
                    <h5
                        class="modal-title moddal"
                        id="userModalLabel"
                    >AC repair</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <div class="user-card d-flex align-items-center p-2 mb-2 border rounded">
                        <img
                            id="userImage"
                            src=""
                            class="rounded-circle me-3"
                            style="width:40px; height:40px; object-fit:cover;"
                            alt="User"
                        >
                        <div>
                            <h6
                                class="mb-0"
                                id="userName"
                            ></h6>
                            <small
                                class="text-muted"
                                id="userEmail"
                            ></small>
                        </div>
                    </div>
                    <div
                        id="userList"
                        class="list-group"
                    >
                        <!-- Cloned user items will go here -->
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- end -->








@endsection
