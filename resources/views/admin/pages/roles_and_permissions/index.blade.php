@extends('admin.layouts.app')

@section('title', 'Roles and permission')
@section('header', 'Roles and permission')
@section('content')

    <div class="container" bis_skin_checked="1">
        <h1 class="page-title">Roles and Permission</h1>
    </div>
    <!-- Top Stat Cards -->
    <div class="tabs-section">
        <div class="tab active roles-tab" data-target="users">Users</div>
        <div class="tab roles-tab" data-target="roles">Roles</div>

    </div>

    <div id="users" class="tab-content active">
        <div class="users-toolbar">
            <div class="toolbar-left">
                <button class="export-btn">
                    <span class="download-icon"><img class="btn-icons" src="{{ asset('assets/images/icons/download.png') }}"
                            alt=""></span> Export
                    CSV
                </button>
                <button class="add-user-btn" id="openAddUserModal">+ Add User</button>
            </div>
            <div class="toolbar-right">
                <input type="text" class="search-user" placeholder="Search user">
                <button class="filter-btn" id="openFilterModal"> <span class="download-icon"><img class="btn-icons"
                            src="{{ asset('assets/images/icons/button-icon.png') }}" alt=""></span>Filter</button>
            </div>
        </div>

        <!-- booking -table-->
        <table class="theme-table roles">
            <thead>


                <tr>
                    <th><input type="checkbox"></th>
                    <th class="sortable" data-column="0">User type<img src="{{ asset('assets/images/icons/sort.png') }}"
                            class="sort-icon">
                    </th>

                    <th class="sortable">User name
                        <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                    </th>



                    <th class="sortable" data-column="1">Home address<img src="{{ asset('assets/images/icons/sort.png') }}"
                            class="sort-icon">
                    </th>
                    <th class="sortable" data-column="1">Phone number<img src="{{ asset('assets/images/icons/sort.png') }}"
                            class="sort-icon">
                    </th>



                    <th></th>
                </tr>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Super Admin</td>
                    <td>
                        <div class="user-info">
                            <img src="http://127.0.0.1:8000/assets/images/avatar/default.png" alt="avatar">
                            <div>
                                <p class="user-name">Tara Heathcote Jr.</p>
                                <p class="user-email">aileen68@example.net</p>
                            </div>
                        </div>
                    </td>
                    <td>7338 Reinhold Extension Apt. 750</td>
                    <td>08036528962</td>
                    <!-- Actions in same cell -->
                    <td class="viw-parent">
                        <a href="" class="view-btn">
                            <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                            View
                        </a>
                        <a href="" class="view-btn">
                            <img src="{{ asset('assets/images/icons/trash_trash.png') }}" alt="Delete" class="eye-icon">
                            Delete
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>




        <!-- Pagination -->
        <div class="pagination">
            <button class="page-btn prev" disabled>‹</button>
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
    <div id="roles" class="tab-content ">
        <div class="users-toolbar">
            <div class="toolbar-left">
                <button class="export-btn">
                    <span class="download-icon"><img class="btn-icons" src="{{ asset('assets/images/icons/download.png') }}"
                            alt=""></span> Export
                    CSV
                </button>
                <button class="add-user-btn" id="openaddRoleModal">+ Add role</button>
            </div>
            <div class="toolbar-right">
                <input type="text" class="search-user" placeholder="Search user">
                <button class="filter-btn" id="openFilterModal"> <span class="download-icon"><img class="btn-icons"
                            src="{{ asset('assets/images/icons/button-icon.png') }}" alt=""></span>Filter</button>
            </div>
        </div>

        <!-- booking -table-->
        <table class="theme-table roles">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>User type</th>
                    <th>Assignees</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Super Admin</td>
                    <td>
                        <div class="user-info">
                            <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                            <div>
                                <p class="user-name">Johnbosco Davies <span class="more"> +2 more</span></p>
                            </div>
                        </div>
                    </td>


                    <!-- Actions in same cell -->
                    <td class="viw-parent theme-parent-class">
                        <a href="sub-admin.php" class="view-btn">
                            <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                            View
                        </a>
                        <a href="" class="view-btn">
                            <img src="{{ asset('assets/images/icons/edit.png') }}" alt="View" class="eye-icon">
                            Edit
                        </a>
                        <a href="" class="view-btn">
                            <img src="{{ asset('assets/images/icons/trash_trash.png') }}" alt="Delete"
                                class="eye-icon">
                            Delete
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>



        <!-- Pagination -->
        <div class="pagination">
            <button class="page-btn prev" disabled>‹</button>
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
    <!-- view booking modal -->


    <div id="addRoleModal" class="modal">
        <div class="modal-content role-add add-user-modal">
            <span class="close-modal" id="closeaddRoleModal">&times;</span>
            <h3>Add Permission</h3>
            <form id="roleForm">
                <!-- Role input -->
                <label>Role</label>
                <input type="text" class="form-input" placeholder="Enter name">

                <!-- Permission Section (hidden by default) -->
                <div class="permission-section" id="permissionSection" style="display: none;">
                    <!-- Tabs navigation -->
                    <div class="tabs-wrapper">
                        <!-- Left Control -->
                        <button class="tab-control left">
                            <img src="{{ asset('assets/images/icons/left-control.png') }}" alt="Left">
                        </button>

                        <!-- Tabs Navigation -->
                        <div class="tabs-nav">
                            <div class="tab active roles-permission-theme-tab" data-target="dashboard">
                                Dashboard</div>
                            <div class="tab roles-permission-theme-tab" data-target="brands">Brands</div>
                            <div class="tab roles-permission-theme-tab" data-target="documents">Documents
                            </div>
                            <div class="tab roles-permission-theme-tab" data-target="documentstypes">
                                Documents types</div>
                            <div class="tab roles-permission-theme-tab" data-target="sche">Sche</div>
                        </div>

                        <!-- Right Control -->
                        <button class="tab-control right">
                            <img src="{{ asset('assets/images/icons/right-control.png') }}" alt="Right">
                        </button>
                    </div>


                    <!-- Tab content -->
                    <!-- Dashboard (active by default) -->
                    <!-- Dashboard Tab Content -->
                    <div id="dashboard" class="tab-content active">

                        <div class="permission-item">
                            <span class="user-name">Can manage dashboard</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can assign roles</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage payment</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage bookings</span>
                            <input type="checkbox">
                        </div>

                    </div>


                    <!-- Brands -->
                    <div id="brands" class="tab-content">

                        <div class="permission-item">
                            <span class="user-name ">Can manage dashboard</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can assign roles</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage payment</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage bookings</span>
                            <input type="checkbox">
                        </div>
                    </div>

                    <!-- Documents -->
                    <div id="documents" class="tab-content">

                        <div class="permission-item">
                            <span class="user-name ">Can manage dashboard</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can assign roles</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage payment</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage bookings</span>
                            <input type="checkbox">
                        </div>
                    </div>

                    <!-- Documents types -->
                    <div id="documentstypes" class="tab-content">

                        <div class="permission-item">
                            <span class="user-name ">Can manage dashboard</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can assign roles</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage payment</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage bookings</span>
                            <input type="checkbox">
                        </div>
                    </div>

                    <!-- Sche -->
                    <div id="sche" class="tab-content">

                        <div class="permission-item">
                            <span class="user-name ">Can manage dashboard</span>
                            <input type="checkbox" checked>
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can assign roles</span>
                            <input type="checkbox" checked>
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage payment</span>
                            <input type="checkbox" checked>
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage bookings</span>
                            <input type="checkbox" checked>
                        </div>
                    </div>

                </div>
<br>
<br>
                <!-- Form actions -->
                <div class="form-actions justify-content-center">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="button" class="submit-btn" id="showPermission"> + Add Permission</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addUserModal" class="modal">
        <div class="modal-content add-user-modal">
            <span class="close-modal" id="closeAddUserModal">&times;</span>
            <h3>Add User</h3>
            <form>
                <label>Name</label>
                <input type="text" class="form-input" placeholder="Enter name">
                <label>Email</label>
                <input type="email" class="form-input" placeholder="Enter email">
                <label>Home Address</label>
                <input type="text" class="form-input" placeholder="Enter home address">
                <label>Phone Number</label>
                <input type="text" class="form-input" placeholder="Enter phone number">
                <div class="mb-3">
                    <label for="homeAddress" class="form-label">Home Address</label>
                    <select class="form-select" id="homeAddress">
                        <option selected disabled>Admin</option>
                        <option value="Admin"> Admin</option>
                        <option value="Admin">Sub Admin</option>



                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="submit" class="submit-btn"> + Add User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Modal -->
    <div id="filterModal" class="modal">
        <div class="modal-content filter-modal">
            <span class="close-modal" id="closeFilterModal">&times;</span>
            <h3>Filter</h3>
            <label>Select Date</label>
            <div class="date-range">
                <div>
                    <span>From:</span>
                    <input type="date" class="form-input">
                </div>
                <div>
                    <span>To:</span>
                    <input type="date" class="form-input">
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="reset-btn">Reset</button>
                <button type="submit" class="submit-btn">Apply Now</button>
            </div>
        </div>
    </div>
    </div>

    <div id="addRoleModal" class="modal">
        <div class="modal-content role-add add-user-modal">
            <span class="close-modal" id="closeaddRoleModal">&times;</span>
            <h3>Add Permission</h3>
            <form id="roleForm">
                <!-- Role input -->
                <label>Role</label>
                <input type="text" class="form-input" placeholder="Enter name">

                <!-- Permission Section (hidden by default) -->
                <div class="permission-section" id="permissionSection" style="display: none;">
                    <!-- Tabs navigation -->
                    <div class="tabs-wrapper">
                        <!-- Left Control -->
                        <button class="tab-control left">
                            <img src="{{ asset('assets/images/icons/left-control.png') }}" alt="Left">
                        </button>

                        <!-- Tabs Navigation -->
                        <div class="tabs-nav">
                            <div class="tab active roles-permission-theme-tab" data-target="dashboard">
                                Dashboard</div>
                            <div class="tab roles-permission-theme-tab" data-target="brands">Brands</div>
                            <div class="tab roles-permission-theme-tab" data-target="documents">Documents
                            </div>
                            <div class="tab roles-permission-theme-tab" data-target="documentstypes">
                                Documents types</div>
                            <div class="tab roles-permission-theme-tab" data-target="sche">Sche</div>
                        </div>

                        <!-- Right Control -->
                        <button class="tab-control right">
                            <img src="{{ asset('assets/images/icons/right-control.png') }}" alt="Right">
                        </button>
                    </div>


                    <!-- Tab content -->
                    <!-- Dashboard (active by default) -->
                    <!-- Dashboard Tab Content -->
                    <div id="dashboard" class="tab-content active">

                        <div class="permission-item">
                            <span class="user-name">Can manage dashboard</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can assign roles</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage payment</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage bookings</span>
                            <input type="checkbox">
                        </div>

                    </div>


                    <!-- Brands -->
                    <div id="brands" class="tab-content">

                        <div class="permission-item">
                            <span class="user-name ">Can manage dashboard</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can assign roles</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage payment</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage bookings</span>
                            <input type="checkbox">
                        </div>
                    </div>

                    <!-- Documents -->
                    <div id="documents" class="tab-content">

                        <div class="permission-item">
                            <span class="user-name ">Can manage dashboard</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can assign roles</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage payment</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage bookings</span>
                            <input type="checkbox">
                        </div>
                    </div>

                    <!-- Documents types -->
                    <div id="documentstypes" class="tab-content">

                        <div class="permission-item">
                            <span class="user-name ">Can manage dashboard</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can assign roles</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage payment</span>
                            <input type="checkbox">
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage bookings</span>
                            <input type="checkbox">
                        </div>
                    </div>

                    <!-- Sche -->
                    <div id="sche" class="tab-content">

                        <div class="permission-item">
                            <span class="user-name ">Can manage dashboard</span>
                            <input type="checkbox" checked>
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can assign roles</span>
                            <input type="checkbox" checked>
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage payment</span>
                            <input type="checkbox" checked>
                        </div>

                        <div class="permission-item">
                            <span class="user-name ">Can manage bookings</span>
                            <input type="checkbox" checked>
                        </div>
                    </div>

                </div>

                <!-- Form actions -->
                <div class="form-actions justify-content-center">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="button" class="submit-btn" id="showPermission"> + Add Permission</button>
                </div>
            </form>
        </div>
    </div>

@endsection
