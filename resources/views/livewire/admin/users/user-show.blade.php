<div>
    <!-- Breadcrumb -->
    <div class="users-toolbar">
        <nav class="breadcrumb">
            <a href="{{ route('user-management.service.users.index') }}">Users</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-current">{{ $user->name ?? 'User Details' }}</span>
        </nav>
    </div>

    <!-- Toolbar -->
    <div class="users-toolbar">
        <div class="toolbar-left">
            @if ($user)
                <button class="edit-btn" wire:click="openEditModal">
                    <span class="download-icon">
                        <img src="{{ asset('assets/images/icons/edit.png') }}" alt="" class="icons-btn">
                    </span> Edit User
                </button>


                <button class="delete-btn" wire:click="openDeleteModal">
                    <span class="download-icon">
                        <img src="{{ asset('assets/images/icons/trash.png') }}" alt="" class="icons-btn">
                    </span>
                    Delete User
                </button>
            @endif
        </div>

        <div class="toolbar-right">
            <!-- User Profile -->
            <div class="user-profile">
                <img src="{{ asset($user->avatar) ?? asset('assets/images/user-profile-img.png') }}" alt="User"
                    class="user-avatar">
                <div class="user-infos">
                    <h4 class="user-name-user">{{ $user->name ?? 'Unknown User' }}</h4>
                    <p class="user-role">{{ $user->roles->first()->name ?? 'No Role' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Details Section -->
    <div id="details" style="border: 0.1vw solid #ddd; border-radius: 2vw; margin-bottom: 2vw;">
        <h3 style="font-size:1.4vw;" class="profile-heading">Profile Details</h3>
        <div class="profile-details">
            <p><span>Name</span> {{ $user->name ?? '' }}</p>
            <p><span>Email Address</span> {{ $user->email ?? '' }}</p>
            <p><span>Phone Number</span> {{ $user->phone ?? '' }}</p>
            <p><span>State of Residence</span> {{ $user->state ?? '' }}</p>
            <p><span>Home Address</span> {{ $user->address ?? '' }}</p>
            <p><span>User Type</span> {{ ucfirst($user->user_type ?? '') }}</p>
            {{-- <p><span>Country</span> {{ $user->country ?? '' }}</p>
            <p><span>City</span> {{ $user->city ?? '' }}</p> --}}

        </div>
    </div>





    <!-- Delete Modal -->
    @if ($showDeleteModal)
        <div class="deleteModal delete-card" id="global-delete-modal">
            <div class="delete-card-header">
                <h3 class="delete-title">Delete User</h3>
                <span class="delete-close" wire:click="closeDeleteModal">&times;</span>
            </div>
            <p class="delete-text">Are you sure you want to delete this user?</p>
            <div class="delete-actions">
                <button class="confirm-delete-btn" wire:click="deleteUser">Delete</button>
                <button class="cancel-delete-btn" wire:click="closeDeleteModal">Cancel</button>
            </div>
        </div>
    @endif


    <!-- Edit Modal -->
    @if ($showEditModal)
        <div
            style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;">
            <div
                style="background: white; padding: 2vw; border-radius: 0.6vw; width: 42vw; max-width: 500px; position: relative;">
                <span wire:click="closeEditModal"
                    style="position: absolute; top: 10px; right: 15px; font-size: 24px; cursor: pointer; color: #999;">&times;</span>
                <h3>Edit User</h3>
                <form wire:submit.prevent="updateUser">
                    <label>Name</label>
                    <input type="text" class="form-input" wire:model="editUser.name" placeholder="Enter name">
                    @error('editUser.name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror

                    <label>Email</label>
                    <input type="email" class="form-input" wire:model="editUser.email" placeholder="Enter email">
                    @error('editUser.email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror

                    <label>Phone Number</label>
                    <input type="text" class="form-input" wire:model="editUser.phone"
                        placeholder="Enter phone number">
                    @error('editUser.phone')
                        <span class="error-message">{{ $message }}</span>
                    @enderror

                    <label>Address</label>
                    <input type="text" class="form-input" wire:model="editUser.address" placeholder="Enter address">
                    @error('editUser.address')
                        <span class="error-message">{{ $message }}</span>
                    @enderror

                    <label>State</label>
                    <input type="text" class="form-input" wire:model="editUser.state" placeholder="Enter state">
                    @error('editUser.state')
                        <span class="error-message">{{ $message }}</span>
                    @enderror

                    <label>City</label>
                    <input type="text" class="form-input" wire:model="editUser.city" placeholder="Enter city">
                    @error('editUser.city')
                        <span class="error-message">{{ $message }}</span>
                    @enderror

                    <label>Country</label>
                    <input type="text" class="form-input" wire:model="editUser.country" placeholder="Enter country">
                    @error('editUser.country')
                        <span class="error-message">{{ $message }}</span>
                    @enderror

                    <label>Role</label>
                    <select class="form-input" wire:model="editUser.user_type">
                        <option value="customer">Customer</option>
                        <option value="provider">Provider</option>
                        <option value="admin">Admin</option>
                    </select>
                    @error('editUser.user_type')
                        <span class="error-message">{{ $message }}</span>
                    @enderror


                    <div class="form-actions justify-content-center">
                        <button type="button" class="cancel-btn" wire:click="closeEditModal">Cancel</button>
                        <button type="submit" class="submit-btn">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <style>
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }
    </style>

    <script>
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const deleteModal = document.querySelector('.deleteModal');
            if (event.target === deleteModal) {
                @this.call('closeDeleteModal');
            }
        });

        // Close modal with escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                @this.call('closeDeleteModal');
            }
        });
    </script>
</div>
