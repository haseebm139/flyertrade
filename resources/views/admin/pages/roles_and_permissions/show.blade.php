@extends('admin.layouts.app')

@section('title', 'Roles and Permission')
@section('header', 'Roles and Permission')
@section('content')

    @if ($type === 'role')
        <livewire:admin.roles.role-show :roleId="$data->id" :key="'role-show-' . $data->id" />
        <!-- Role Form Modal for editing -->
        <livewire:admin.roles.role-form :key="'role-form-' . time()" />
    @elseif($type === 'user')
        <div class="users-toolbar">
            <nav class="breadcrumb">
                <a href="#">User</a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-current">Johnbosco Davies</span>
            </nav>
        </div>

        <!-- Toolbar -->
        <div class="users-toolbar">
            <div class="toolbar-left">


                <button class="export-btn" id="openAddUserModal">
                    <span class="download-icon"><img src="assets/images/edit.png" alt="" class="icons-btn"></span>
                    Edit User
                </button>

                <button class="delete-btn">
                    <span class="download-icon"><img src="assets/images/trash.png" alt="" class="icons-btn"></span>
                    Delete user
                </button>
            </div>

            <div class="toolbar-right">
                <!-- ✅ User Profile -->
                <div class="user-profile">
                    <img src="assets/images/user-profile-img.png" alt="User" class="user-avatar">
                    <div class="user-infos">
                        <h4 class="user-name-user">Johnbosco Davies</h4>
                        <p class="user-role">Sub Admin</p>
                    </div>


                </div>
            </div>
        </div>

        <!-- tabs-section -->


        <div id="details" style="border: 0.1vw solid #ddd;
    border-radius: 2vw;">
            <h3 style="font-size:1.4vw;" class="profile-heading">Profile details</h3>
            <div class="profile-details">
                <p><span>Name</span> Johnbosco Davies</p>
                <p><span>Email address</span> Johnboscodaviess@gmail.com</p>
                <p><span>Phone number</span> 081 4596 58598</p>
                <p><span>State of residence</span> Dubai</p>
                <p><span>Home address</span> 123, ABC road, Dubai</p>
                <p><span>User type</span> Sub admin</p>

            </div>
        </div>




        <!-- tabs section end -->


        </div>








        <!-- Add User Modal -->
        <div id="addUserModal" class="modal">
            <div class="modal-content add-user-modal">
                <span class="close-modal" id="closeAddUserModal">&times;</span>
                <h3>Edit User</h3>
                <form>
                    <label>Name</label>
                    <input type="text" class="form-input" placeholder="Enter name">
                    <label>Email</label>
                    <input type="email" class="form-input" placeholder="Enter email">
                    <label>Home Address</label>
                    <input type="text" class="form-input" placeholder="Enter home address">
                    <label>Phone Number</label>
                    <input type="text" class="form-input" placeholder="Enter phone number">
                    <div class="form-actions justify-content-center">
                        <button type="button" class="cancel-btn">Cancel</button>
                        <button type="submit" class="submit-btn"> + Add User</button>
                    </div>
                </form>
            </div>
        </div>
    @endif



@endsection
