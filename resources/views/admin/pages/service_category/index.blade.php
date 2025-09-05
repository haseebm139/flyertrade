@extends('admin.layouts.app')

@section('title', 'Service Category')

@section('content')

    <div class="container">
        <h1 class="page-title">Service Categories</h1>
    </div>
    <!-- Toolbar -->
    <div class="users-toolbar">
        <div class="toolbar-left">
            <button class="export-btn">
                <span class="download-icon"><img
                        src="{{ asset('assets/images/icons/download.png') }}"
                        alt=""
                    ></span> Export CSV
            </button>
            <button
                class="add-user-btn"
                id="openAddUserModal"
            >+ New service categories </button>
        </div>
        <div class="toolbar-right">
            <input
                type="text"
                class="search-user"
                placeholder="Search user"
            >
            <button
                class="filter-btn"
                id="openFilterModal"
            > <span class="download-icon"><img
                        src="{{ asset('assets/images/icons/button-icon.png') }}"
                        alt=""
                    ></span>Filter</button>
        </div>
    </div>

    <!-- Users Table -->
    <table class="users-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th class="sortable">Service category
                    <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    >
                </th>
                <th class="sortable">Registered providers
                    <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    >
                </th>
                <th></th>
                <th class="sortable">Date created
                    <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    >
                </th>
                <th></th>
                <th></th>
                <th class="sortable">Description
                    <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    >
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr
                onclick="openUserModal('Johnbosco Davies', 'johnboscodavies@gmail.com', '{{ asset('assets/images/icons/person-one.png') }}')">
                <td><input type="checkbox"></td>
                <td>AC repair</td>
                <td>
                    <div class="user-info">
                        <img
                            src="{{ asset('assets/images/icons/person-one.png') }}"
                            alt="User"
                            class="avatar"
                        >
                        <span>Johnbosco Davies</span>
                    </div>
                </td>
                <td></td>
                <td><span class="date">Jan,2025-01-31</span></td>
                <td></td>
                <td></td>
                <td>
                    <span class="desf">
                        Air conditioning unit installation, repairs, cleaning, <br> gas refilling, and
                        regular servicing to improve <br> cooling efficiency.
                    </span>
                </td>
                <td>
                    <button
                        class="edit-btn"
                        onclick="event.stopPropagation(); openModal('editUserModal')"
                    >
                        <img
                            src="{{ asset('assets/images/icons/edit-icon.png') }}"
                            alt="Edit"
                            class="action-icon"
                        >
                    </button>
                    <button class="delete-btn">
                        <img
                            src="{{ asset('assets/images/icons/delete-icon.png') }}"
                            alt="Delete"
                            class="action-icon"
                        >
                    </button>

                    <!-- Delete Popover -->

                </td>
            </tr>

        </tbody>
    </table>


    <!-- edit modAL -->
    <div
        id="editUserModal"
        class="modal"
    >
        <div class="modal-content centered-modal">
            <span
                class="close-modal"
                onclick="closeModal('editUserModal')"
            >&times;</span>
            <h3 class="adfa">Add service catogory</h3>
            <br>
            <form>
                <label>Service Name</label>
                <br>
                <input
                    type="text"
                    class="form-input"
                    placeholder="Enter name"
                >

                <label>Edit Description</label>
                <br>

                <input
                    type="text"
                    class="form-input"
                    placeholder="Edit description"
                >

                <div class="form-actions">
                    <div class="btns">
                        <button
                            type="button"
                            class="cancel-btn"
                            onclick="closeModal('editUserModal')"
                        >Cancel</button>
                        <button
                            type="submit"
                            class="submit-btn"
                        > + Save Changes</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- EDIT-MODAL-END -->

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


    <!-- Pagination -->
    <div class="pagination">
        <button
            class="page-btn prev"
            disabled
        >‹</button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <button class="page-btn">4</button>
        <button class="page-btn">5</button>
        <span class="dots">...</span>
        <button class="page-btn">14</button>
        <button class="page-btn next">›</button>

        <div class="rows-control">
            <label for="rows">Show</label>
            <select id="rows">
                <option>10 rows</option>
                <option>25 rows</option>
                <option>50 rows</option>
                <option>100 rows</option>
            </select>
        </div>
    </div>

    <!-- Add User Modal -->
    <div
        id="addUserModal"
        class="modal"
    >
        <div class="modal-content add-user-modal">
            <span
                class="close-modal"
                id="closeAddUserModal"
            >&times;</span>
            <h3 class="adfa">Add service catogory</h3>
            <form>
                <label>Service Name</label>
                <input
                    type="text"
                    class="form-input"
                    placeholder="Enter name"
                >

                <label>Add Descrption</label>
                <input
                    type="text"
                    class="form-input"
                    placeholder="add descrption"
                >

                <div class="form-actions">
                    <button
                        type="button"
                        class="cancel-btn"
                    >Cancel</button>
                    <button
                        type="submit"
                        class="submit-btn"
                    >+ Add Service</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Modal -->
    <div
        id="filterModal"
        class="modal"
    >
        <div class="modal-content filter-modal">
            <span
                class="close-modal"
                id="closeFilterModal"
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
