@extends('admin.layouts.app')

@section('title', 'Roles and Permission')
@section('header', 'Roles and Permission')
@section('content')

    <!-- Top Stat Cards -->


    <div class="users-toolbar">
        <nav class="breadcrumb">
            <a href="{{ route('roles-and-permissions.index') }}">{{ $type ?? 'Users' }}</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">{{ $title?? '' }}</span>
        </nav>
    </div>

    <!-- Toolbar -->
    <div class="users-toolbar">
        <div class="toolbar-left">
            @if ($data)
                <button class="export-btn" onclick="editItem()">
                    <span class="download-icon"><img src="{{ asset('assets/images/icons/edit.png') }}" alt=""
                            class="icons-btn"></span> Edit
                    {{ $type === 'role' ? 'Role' : 'User' }}
                </button>

                <button class="delete-btn" onclick="deleteItem()">
                    <span class="download-icon"><img src="{{ asset('assets/images/icons/trash.png') }}" alt=""
                            class="icons-btn"></span>
                    Delete {{ $type === 'role' ? 'Role' : 'User' }}
                </button>
            @endif
        </div>

        <div class="toolbar-right">
            <h2 class="page-titles">{{ $title }}</h2>
        </div>
    </div>

    <div class="users-toolbars">

        <h2 class="page-titles text-end">Permission</h2>
    </div>


    <div class="tabs-wrapper">
        <!-- Left Control -->
        <button class="tab-control left">
            <img src="{{ asset('assets/images/icons/left-control.png') }}" alt="Left">
        </button>

        <!-- Tabs Navigation -->
        <div class="tabs-nav theme-btn-class-roles-module">
            <div class="tab active roles-permission-theme-tabs" data-target="dashboard">Dashboard</div>
            <div class="tab roles-permission-theme-tabs" data-target="brands">Brands</div>
            <div class="tab roles-permission-theme-tabs" data-target="documents">Documents</div>
            <div class="tab roles-permission-theme-tabs" data-target="documentstypes">Documents types</div>
            <div class="tab roles-permission-theme-tabs" data-target="payment">Payment</div>
            <div class="tab roles-permission-theme-tabs" data-target="payment2">Payment</div>
            <div class="tab roles-permission-theme-tabs" data-target="payment3">Payment</div>
            <div class="tab roles-permission-theme-tabs" data-target="payment4">Payment</div>
            <div class="tab roles-permission-theme-tabs" data-target="payment5">Payment</div>
            <div class="tab roles-permission-theme-tabs" data-target="payment6">Payment</div>
            <div class="tab roles-permission-theme-tabs" data-target="payment7">Payment</div>
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
        <hr>
        <div class="form-actions d-flex justify-content-end theme-btn-class-roles-module">
            <button type="button" class="cancel-btn">Cancel</button>
            <button type="button" class="submit-btn"> Submit</button>
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
        <hr>
        <div class="form-actions d-flex justify-content-end theme-btn-class-roles-module">
            <button type="button" class="cancel-btn">Cancel</button>
            <button type="button" class="submit-btn"> Submit</button>
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
        <hr>
        <div class="form-actions d-flex justify-content-end theme-btn-class-roles-module">
            <button type="button" class="cancel-btn">Cancel</button>
            <button type="button" class="submit-btn"> Submit</button>
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
        <hr>
        <div class="form-actions d-flex justify-content-end theme-btn-class-roles-module">
            <button type="button" class="cancel-btn">Cancel</button>
            <button type="button" class="submit-btn"> Submit</button>
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
        <hr>
        <div class="form-actions d-flex justify-content-end theme-btn-class-roles-module">
            <button type="button" class="cancel-btn">Cancel</button>
            <button type="button" class="submit-btn"> Submit</button>
        </div>
    </div>

    </div>

    <!-- tabs section end -->
    <!-- admin-table-start -->
    <table class="theme-table">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th class="sortable" data-column="0">User Type <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon">
                </th>
                <th class="sortable" data-column="4">User name<img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"></th>
                <th class="sortable"> Last login
                    <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>






                <th class="sortable" data-column="6"> Date added<img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon">
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox"></td>
                <td>Sub Admin</td>







                <td>
                    <div class="user-info" bis_skin_checked="1">
                        <img src="{{ asset('assets/images/icons/person-one.png') }}" alt="User">
                        <div bis_skin_checked="1">
                            <p class="user-name">Johnbosco Davies</p>

                        </div>
                    </div>
                </td>

                <td><span class="status last-seen ">2 min ago </span></td>
                <td><span class="date">2025-05-31</span> </td>


            </tr>









        </tbody>
    </table>


    <!-- admin table end -->

    <div id="addUserModal" class="modal">
        <div class="modal-content role-add add-user-modal">
            <span class="close-modal" id="closeAddUserModal">&times;</span>
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
    <!-- end-modal -->

    <script>
        function editItem() {
            const type = '{{ $type }}';
            const id = '{{ $data->id ?? '' }}';

            if (type === 'role') {
                // Redirect to edit role
                window.location.href = '{{ route('roles-and-permissions.index') }}?edit_role=' + id;
            } else {
                // Redirect to edit user
                window.location.href = '{{ route('roles-and-permissions.index') }}?edit_user=' + id;
            }
        }

        function deleteItem() {
            const type = '{{ $type }}';
            const name = '{{ $data->name ?? '' }}';

            if (confirm(`Are you sure you want to delete this ${type}?`)) {
                // Here you would typically make an AJAX call to delete the item
                alert(`${type} "${name}" would be deleted.`);
                // For now, just redirect back
                window.location.href = '{{ route('roles-and-permissions.index') }}';
            }
        }
    </script>
@endsection
