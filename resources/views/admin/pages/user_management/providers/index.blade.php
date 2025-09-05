@extends('admin.layouts.app')

@section('title', 'Service Providers')

@section('content')

    <!-- Top Stat Cards -->
    <div class=" combo-class">
        <div class="dashboard-card">
            <div>
                <h6>Total users</h6>
                <h2>1200</h2>
            </div>
            <div class="icon-box">
                <img
                    src="{{ asset('assets/images/icons/service-providers.png') }}"
                    alt="User Icon"
                >
            </div>
        </div>
        <div class="dashboard-card">
            <div>
                <h6>Active users</h6>
                <h2>1200</h2>
            </div>
            <div class="icon-box">
                <img
                    src="{{ asset('assets/images/icons/service-providers.png') }}"
                    alt="User Icon"
                >
            </div>
        </div>
        <div class="dashboard-card">
            <div>
                <h6>Inactive user</h6>
                <h2>1200</h2>
            </div>
            <div class="icon-box">
                <img
                    src="{{ asset('assets/images/icons/service-providers.png') }}"
                    alt="User Icon"
                >
            </div>
        </div>

    </div>
    <br>
    <div class="container">
        <h1 class="page-title">Service Providers</h1>
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
            >+ Add User</button>
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
                <th
                    class="sortable"
                    data-column="0"
                >User ID <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    ></th>
                <th
                    class="sortable"
                    data-column="1"
                >Provider name <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    ></th>
                <th
                    class="sortable"
                    data-column="2"
                >Home address <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    ></th>
                <th
                    class="sortable"
                    data-column="3"
                >Phone number <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    ></th>
                <th
                    class="sortable"
                    data-column="4"
                >Service category <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    ></th>
                <th
                    class="sortable"
                    data-column="5"
                >Verification status <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    ></th>
                <th
                    class="sortable"
                    data-column="6"
                > Status <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    ></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>
                    <div class="user-info">
                        <img
                            src="{{ asset('assets/images/icons/person-one.png') }}"
                            alt="User"
                        >
                        <div>
                            <p class="user-name">Johnbosco Davies</p>
                            <p class="user-email">johnboscodavies@gmail.com</p>
                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>08036528962</td>
                <td>Plumbing <span class="more"> +2 more</span></td>
                <td><span class="status active">Verified</span></td>
                <td><span class="status active">Active</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a href="#"><i class="fa fa-eye"></i> View user</a>
                            <a href="#"><i class="fa fa-pen"></i> Edit user</a>
                            <a href="#"><i class="fa fa-trash"></i> Delete user</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>
                    <div class="user-info">
                        <img
                            src="{{ asset('assets/images/icons/three.png') }}"
                            alt="User"
                        >
                        <div>
                            <p class="user-name">Johnbosco Davies</p>
                            <p class="user-email">johnboscodavies@gmail.com</p>
                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>08036528962</td>

                <td>Cleaning <span class="more"> +2 more</span></td>
                <td><span class="status active">Verified</span></td>
                <td><span class="status active">Active</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a href="#"><i class="fa fa-eye"></i> View user</a>
                            <a href="#"><i class="fa fa-pen"></i> Edit user</a>
                            <a href="#"><i class="fa fa-trash"></i> Delete user</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>
                    <div class="user-info">
                        <img
                            src="{{ asset('assets/images/icons/four.png') }}"
                            alt="User"
                        >
                        <div>
                            <p class="user-name">Johnbosco Davies</p>
                            <p class="user-email">johnboscodavies@gmail.com</p>
                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>08036528962</td>
                <td>Plumbing <span class="more"> +2 more</span></td>
                <td><span class="status active">Verified</span></td>
                <td><span class="status active">Active</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a href="#"><i class="fa fa-eye"></i> View user</a>
                            <a href="#"><i class="fa fa-pen"></i> Edit user</a>
                            <a href="#"><i class="fa fa-trash"></i> Delete user</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>
                    <div class="user-info">
                        <img
                            src="{{ asset('assets/images/icons/five.png') }}"
                            alt="User"
                        >
                        <div>
                            <p class="user-name">Johnbosco Davies</p>
                            <p class="user-email">johnboscodavies@gmail.com</p>
                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>08036528962</td>
                <td>Electric work</td>
                <td><span class="status active">Verified</span></td>
                <td><span class="status active">Active</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a href="#"><i class="fa fa-eye"></i> View user</a>
                            <a href="#"><i class="fa fa-pen"></i> Edit user</a>
                            <a href="#"><i class="fa fa-trash"></i> Delete user</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>
                    <div class="user-info">
                        <img
                            src="{{ asset('assets/images/icons/six.png') }}"
                            alt="User"
                        >
                        <div>
                            <p class="user-name">Johnbosco Davies</p>
                            <p class="user-email">johnboscodavies@gmail.com</p>
                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>08036528962</td>
                <td>Plumbing <span class="more"> +2 more</span></td>
                <td><span class="status inactive">Declined</span></td>
                <td><span class="status inactive">Inactive</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a href="#"><i class="fa fa-eye"></i> View user</a>
                            <a href="#"><i class="fa fa-pen"></i> Edit user</a>
                            <a href="#"><i class="fa fa-trash"></i> Delete user</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>
                    <div class="user-info">
                        <img
                            src="{{ asset('assets/images/icons/seven.png') }}"
                            alt="User"
                        >
                        <div>
                            <p class="user-name">Johnbosco Davies</p>
                            <p class="user-email">johnboscodavies@gmail.com</p>
                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>08036528962</td>
                <td>Plumbing <span class="more"> +2 more</span></td>
                <td><span class="status pending">pending</span></td>
                <td><span class="status active">Active</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a href="#"><i class="fa fa-eye"></i> View user</a>
                            <a href="#"><i class="fa fa-pen"></i> Edit user</a>
                            <a href="#"><i class="fa fa-trash"></i> Delete user</a>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>
                    <div class="user-info">
                        <img
                            src="{{ asset('assets/images/icons/eight.png') }}"
                            alt="User"
                        >
                        <div>
                            <p class="user-name">Johnbosco Davies</p>
                            <p class="user-email">johnboscodavies@gmail.com</p>
                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>08036528962</td>
                <td>Plumbing <span class="more"> +2 more</span></td>
                <td><span class="status active">Verified</span></td>
                <td><span class="status inactive">Inactive</span></td>
                <div class="actions-dropdown">
                    <td>
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a href="#"><i class="fa fa-eye"></i> View user</a>
                            <a href="#"><i class="fa fa-pen"></i> Edit user</a>
                            <a href="#"><i class="fa fa-trash"></i> Delete user</a>
                        </div>
                </div>
                </td>
            </tr>
            <tr>
                <td><input type="checkbox"></td>
                <td>12345</td>
                <td>
                    <div class="user-info">
                        <img
                            src="{{ asset('assets/images/icons/five.png') }}"
                            alt="User"
                        >
                        <div>
                            <p class="user-name">Johnbosco Davies</p>
                            <p class="user-email">johnboscodavies@gmail.com</p>
                        </div>
                    </div>
                </td>
                <td>123, Abc Road, Dubai</td>
                <td>08036528962</td>
                <td>Plumbing <span class="more"> +2 more</span></td>
                <td><span class="status active">Verified</span></td>
                <td><span class="status inactive">Inactive</span></td>
                <td>
                    <div class="actions-dropdown">
                        <button class="actions-btn">⋮</button>
                        <div class="actions-menu">
                            <a href="#"><i class="fa fa-eye"></i> View user</a>
                            <a href="#"><i class="fa fa-pen"></i> Edit user</a>
                            <a href="#"><i class="fa fa-trash"></i> Delete user</a>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>


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
            <h3>Add User</h3>
            <form>
                <label>Name</label>
                <input
                    type="text"
                    class="form-input"
                    placeholder="Enter name"
                >
                <label>Email</label>
                <input
                    type="email"
                    class="form-input"
                    placeholder="Enter email"
                >
                <label>Home Address</label>
                <input
                    type="text"
                    class="form-input"
                    placeholder="Enter home address"
                >
                <label>Phone Number</label>
                <input
                    type="text"
                    class="form-input"
                    placeholder="Enter phone number"
                >
                <div class="form-actions">
                    <button
                        type="button"
                        class="cancel-btn"
                    >Cancel</button>
                    <button
                        type="submit"
                        class="submit-btn"
                    > + Add User</button>
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
