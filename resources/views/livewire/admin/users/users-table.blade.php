<div>
    <!-- Toolbar -->
    <div class="users-toolbar">
        <div class="toolbar-left">
            <button class="export-btn" wire:click="exportCsv">
                <span class="download-icon">
                    <img class="btn-icons" src="{{ asset('assets/images/icons/download.png') }}" alt="">
                </span> Export CSV
            </button>
            <button class="add-user-btn" wire:click="addUser">
               <i class="fa-solid fa-plus mr-3"></i> Add User
            </button>
        </div>
        <div class="toolbar-right">
            <input type="text" class="search-user" placeholder="Search users" wire:model.live="search">
            <button class="filter-btn" wire:click="openFilterModal">
                Filter&nbsp;<span class="download-icon">
                    <img class="btn-icons" src="{{ asset('assets/images/icons/button-icon.png') }}" alt="">
                </span>
            </button>
        </div>
    </div>

    <!-- Table -->
    <table class="theme-table roles">
        <thead>
            <tr>
                <th><input type="checkbox"></th>
                <th class="sortable" data-column="0" wire:click="sortBy('user_type')" style="cursor: pointer;">User
                    type<img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>

                <th class="sortable" wire:click="sortBy('name')" style="cursor: pointer;">User name
                    <img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>



                <th class="sortable" wire:click="sortBy('address')" style="cursor: pointer;" data-column="1">Home
                    address<img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>
                <th class="sortable" wire:click="sortBy('phone')" style="cursor: pointer;" data-column="1">Phone
                    number<img src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon">
                </th>



                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td><input type="checkbox" value="{{ $user->id }}" wire:model.live="selected"></td>
                    <td style='font-weight:500;'>
                        {{ ucfirst($user->user_type ?? 'N/A') }}
                    </td>
                    <td>
                        <div class="user-info">
                            <img src="{{ asset($user->avatar) ?? asset('assets/images/icons/person-one.png') }}  "
                                alt="User">
                            <div>
                                <p class="user-name"  style='font-weight:500;'>{{ $user->name }}</p>
                                <p class="user-email">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td  style='font-weight:500;'>{{ Str::limit($user->address ?? 'N/A', 30) }}</td>
                    <td  style='font-weight:500;'>{{ $user->phone ?? 'N/A' }}</td>
                    <td class="viw-parent">
                        <a href="javascript:void(0);" class="view-btn" wire:click="viewUser({{ $user->id }})">
                            <img src="{{ asset('assets/images/icons/eye-icon.png') }}" alt="View" class="eye-icon">
                            View
                        </a>
                        <button class="delete-btn showDeleteModal" wire:click="confirmDelete({{ $user->id }})">
                            <img src="{{ asset('assets/images/icons/trash_trash.png') }}" alt="Delete"
                                class="eye-icon">
                            <span style="    font-size: 0.9vw;
    color: #064f3c;
    cursor: pointer;     font-weight: 400;"> Delete </span>
                        </button>
                        @if ($confirmingId === $user->id)
                            <div class="deleteModal delete-card" id="global-delete-modal"
                                style="
    position: absolute;
    right: 12vw;
    top: 1vw;
    z-index: 99;
">
                                <div class="delete-card-header">
                                    <h3 class="delete-title">Delete User</h3>
                                    <span class="delete-close" wire:click="$set('confirmingId', null)">&times;</span>
                                </div>
                                <p class="delete-text">Are you sure you want to delete user
                                    <strong>{{ $user->name }}</strong>?
                                </p>
                                <div class="delete-actions justify-content-start">
                                    <button class="confirm-delete-btn"
                                        wire:click="deleteUser({{ $user->id }})">Delete</button>
                                    <button class="cancel-delete-btn"
                                        wire:click="$set('confirmingId', null)">Cancel</button>
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $users->links('vendor.pagination.custom') }}

    <!-- Filter Modal -->
    @if ($showFilterModal)
        <div class="modal filter-theme-modals" style="display: flex;">
            <div class="modal-content filter-modal">
                <span class="close-modal" wire:click="closeFilterModal">&times;</span>
                <h3>Filter</h3>
                <label style='color:#717171'>Select Date</label>
                <div class="row mt-3">
                    <div class='col-6'>
                        <span>From:</span>
                        <input type="date" class="form-input mt-2" wire:model="fromDate">
                    </div>
                    <div class='col-6'>
                        <span>To:</span>
                        <input type="date" class="form-input mt-2" wire:model="toDate">
                    </div>
                </div>
                <label style="color:#1b1b1b;font-weight:400">Status</label>
                <x-custom-select
                    name="roleFilter"
                    :options="[
                        ['value' => '', 'label' => 'All Types'],
                        ['value' => 'admin', 'label' => 'Admin'],
                        ['value' => 'customer', 'label' => 'Customer'],
                        ['value' => 'provider', 'label' => 'Provider']
                    ]"
                    placeholder="All Types"
                    wireModel="roleFilter"
                    class="form-input mt-2"
                />
                <div class="form-actions">
                    <button type="button" class="reset-btn" wire:click="resetFilters">Reset</button>
                    <button type="button" class="submit-btn" wire:click="applyFilters">Apply Now</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Add User Modal -->
    @if ($showModal)
        <div id="addUserModal" class="modal" style="display: flex;">
            <div class="modal-content add-user-modal">
                <span class="close-modal" wire:click="closeUserModal">&times;</span>
                <h3>Add User</h3>
                <form wire:submit.prevent="saveUser">
                    <label>Name</label>
                    <input type="text" class="form-input" wire:model="name" placeholder="Enter name">

                    <label>Email</label>
                    <input type="email" class="form-input" wire:model="email" placeholder="Enter email">

                    <label>Home Address</label>
                    <input type="text" class="form-input" wire:model="address" placeholder="Enter home address">

                    <label>Phone Number</label>
                    <input type="text" class="form-input" wire:model="phone" placeholder="Enter phone number">

                    <div class="mb-3">
                        <label for="userType" class="form-label">Role</label>
                        <x-custom-select
                            name="user_type"
                            id="userType"
                            :options="[
                                ['value' => '', 'label' => 'Select role'],
                                ['value' => 'customer', 'label' => 'Customer'],
                                ['value' => 'provider', 'label' => 'Provider'],
                                ['value' => 'admin', 'label' => 'Admin']
                            ]"
                            placeholder="Select role"
                            wireModel="user_type"
                            class="form-select"
                        />
                    </div>

                    <div class="form-actions">
                        <button type="button" class="cancel-btn" wire:click="closeUserModal">Cancel</button>
                        <button type="submit" class="submit-btn"><i class="fa-solid fa-plus mr-3"></i> Add User</button>
                    </div>
                </form>
            </div>
        </div>
    @endif


</div>
