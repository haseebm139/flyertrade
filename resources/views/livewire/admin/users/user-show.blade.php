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
                <button class="edit-btn" wire:click="editUser">
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
                <img src="{{ $user->avatar ?? asset('assets/images/user-profile-img.png') }}" alt="User"
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
            <p><span>Name</span> {{ $user->name ?? 'N/A' }}</p>
            <p><span>Email Address</span> {{ $user->email ?? 'N/A' }}</p>
            <p><span>Phone Number</span> {{ $user->phone ?? 'N/A' }}</p>
            <p><span>State of Residence</span> {{ $user->state ?? 'N/A' }}</p>
            <p><span>Home Address</span> {{ $user->address ?? 'N/A' }}</p>
            <p><span>User Type</span> {{ ucfirst($user->user_type ?? 'N/A') }}</p>
            <p><span>Country</span> {{ $user->country ?? 'N/A' }}</p>
            <p><span>City</span> {{ $user->city ?? 'N/A' }}</p>
            <p><span>Verified Status</span>
                <span class="badge {{ $user->is_verified ? 'badge-verified' : 'badge-pending' }}">
                    {{ $user->is_verified ? 'Verified' : 'Pending' }}
                </span>
            </p>
            <p><span>Created At</span> {{ $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
            <p><span>Last Login</span> {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
        </div>
    </div>

    <!-- Roles Section -->
    <div class="users-toolbars">
        <h2 class="page-titles text-end">Assigned Roles</h2>
    </div>

    <div id="roles-section" style="border: 0.1vw solid #ddd; border-radius: 2vw; margin-bottom: 2vw;">
        <h3 style="font-size:1.4vw;" class="profile-heading">User Roles</h3>
        <div class="profile-details">
            @if ($user->roles->count() > 0)
                @foreach ($user->roles as $role)
                    <p><span>Role</span> {{ ucfirst($role->name) }}</p>
                @endforeach
            @else
                <p><span>No roles assigned</span></p>
            @endif
        </div>

        <!-- Role Management -->
        <div style="margin-top: 1vw;">
            <h4 style="font-size: 1.2vw; margin-bottom: 0.5vw;">Manage Roles</h4>
            <div style="display: flex; flex-wrap: wrap; gap: 0.5vw;">
                @foreach ($roles as $role)
                    <label style="display: flex; align-items: center; gap: 0.3vw; cursor: pointer;">
                        <input type="checkbox" wire:model="userRoles" value="{{ $role->name }}"
                            id="role_{{ $role->id }}">
                        <span>{{ ucfirst($role->name) }}</span>
                    </label>
                @endforeach
            </div>
            <div style="margin-top: 1vw;">
                <button type="button" class="submit-btn" wire:click="updateUserRoles">Update Roles</button>
            </div>
        </div>
    </div>

    <!-- Provider Profile Section (if user is a provider) -->
    @if ($user->user_type === 'provider' && $user->providerProfile)
        <div class="users-toolbars">
            <h2 class="page-titles text-end">Provider Profile</h2>
        </div>

        <div id="provider-section" style="border: 0.1vw solid #ddd; border-radius: 2vw; margin-bottom: 2vw;">
            <h3 style="font-size:1.4vw;" class="profile-heading">Provider Information</h3>
            <div class="profile-details">
                <p><span>Business Name</span> {{ $user->providerProfile->business_name ?? 'N/A' }}</p>
                <p><span>Description</span> {{ $user->providerProfile->description ?? 'N/A' }}</p>
                <p><span>Experience</span> {{ $user->providerProfile->experience ?? 'N/A' }} years</p>
                <p><span>Hourly Rate</span> ${{ $user->providerProfile->hourly_rate ?? 'N/A' }}</p>
                <p><span>Availability</span> {{ $user->providerProfile->availability ?? 'N/A' }}</p>
            </div>
        </div>
    @endif

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
        <div class="modal" style="display: flex;">
            <div class="modal-content add-user-modal">
                <span class="close-modal" wire:click="closeEditModal">&times;</span>
                <h3>Edit User</h3>
                <form wire:submit.prevent="updateUser">
                    <label>Name</label>
                    <input type="text" class="form-input" wire:model="user.name" placeholder="Enter name">

                    <label>Email</label>
                    <input type="email" class="form-input" wire:model="user.email" placeholder="Enter email">

                    <label>Phone Number</label>
                    <input type="text" class="form-input" wire:model="user.phone" placeholder="Enter phone number">

                    <label>Address</label>
                    <input type="text" class="form-input" wire:model="user.address" placeholder="Enter address">

                    <label>State</label>
                    <input type="text" class="form-input" wire:model="user.state" placeholder="Enter state">

                    <label>City</label>
                    <input type="text" class="form-input" wire:model="user.city" placeholder="Enter city">

                    <label>Country</label>
                    <input type="text" class="form-input" wire:model="user.country" placeholder="Enter country">

                    <div class="form-actions justify-content-center">
                        <button type="button" class="cancel-btn" wire:click="closeEditModal">Cancel</button>
                        <button type="submit" class="submit-btn">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.querySelector('.deleteModal');
            if (event.target === modal) {
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
