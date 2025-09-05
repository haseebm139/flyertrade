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







    <!-- Filter Modal -->
    <div
        id="filterModal"
        class="modal"
        style="display:none;"
    >
        <div class="modal-content filter-modal">
            <span
                id="closeFilterModal"
                class="close-modal"
            >&times;</span>
            <h3>Filter</h3>
            <label>Select Date</label>
            <div class="date-range">
                <div>
                    <span>From:</span>
                    <input
                        type="date"
                        class="form-input"
                    >
                </div>
                <div>
                    <span>To:</span>
                    <input
                        type="date"
                        class="form-input"
                    >
                </div>
            </div>
            <label>Status</label>
            <select class="form-input">
                <option value="">Select status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <div class="form-actions">
                <button
                    type="button"
                    class="reset-btn"
                >Reset</button>
                <button
                    type="submit"
                    class="submit-btn"
                >Apply Now</button>
            </div>
        </div>
    </div>
@endsection
