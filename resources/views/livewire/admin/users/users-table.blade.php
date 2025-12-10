<div>
    <!-- Toolbar -->
    <div class="users-toolbar">
        <div class="toolbar-left">
            <button class="export-btn" wire:click="exportCsv">
                <span class="download-icon">
                    <img class="btn-icons" src="{{ asset('assets/images/icons/download.svg') }}" alt="">
                </span> &nbsp;Export CSV
            </button>
            <button class="add-user-btn" wire:click="addUser">
               <i class="fa-solid fa-plus mr-3"></i> Add User
            </button>
        </div>
        <div class="toolbar-right">
            <input type="text" class="search-user" placeholder="Search users" wire:model.live="search">
            <button class="filter-btn" wire:click="openFilterModal">
                Filter&nbsp;<span class="download-icon">
                    <img class="btn-icons" src="{{ asset('assets/images/icons/button-icon.svg') }}" alt="">
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
                    type<img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                </th>

                <th class="sortable" wire:click="sortBy('name')" style="cursor: pointer;">User name
                    <img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                </th>



                <th class="sortable" wire:click="sortBy('address')" style="cursor: pointer;" data-column="1">Home
                    address<img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
                </th>
                <th class="sortable" wire:click="sortBy('phone')" style="cursor: pointer;" data-column="1">Phone
                    number<img src="{{ asset('assets/images/icons/sort.svg') }}" class="sort-icon">
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
                            <img src="{{ asset($user->avatar) ?? asset('assets/images/icons/person-one.svg') }}  "
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
                            <img src="{{ asset('assets/images/icons/eye_icon.svg') }}" alt="View" class="eye-icon">
                            View
                        </a>
                        <button class="delete-btn showDeleteModal" wire:click="confirmDelete({{ $user->id }})">
                           
                            <span style="    font-size: 0.9vw;
    color: #064f3c;
    cursor: pointer;     font-weight: 400;"> Delete </span>
     <img src="{{ asset('assets/images/icons/delete-icon-active.svg') }}" alt="Delete"
                                class="eye-icon">
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
   <style>
        .modal_heaader{
            display: flex;
            position: relative;
            border-bottom: 1.50px solid #f1f1f1;
            margin-bottom: 1.2vw;

        }
         .modal_heaader .close-modal{
            top:0px;
            right:0px;
            line-height: 1;
         }
         .filter_modal_reset{
            border: 1px solid #f1f1f1;
            border-radius: 10px;
            padding: 12px 24px;
         }
         .date_field_wraper{
            position: relative;
         }
         .date-input {
            position: relative;
            padding-right: 35px; /* space for icon */
            font-family: Clash Display;
            color:#555;
            font-style: Medium;
           font-weight: 500;
            }

            /* Hide default icon */
            .date-input::-webkit-calendar-picker-indicator {
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            position: absolute;
            }

            /* Add your SVG as custom icon */
            .date-input {
            background-image: url('data:image/svg+xml;utf8,<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.66406 1.66602V4.16602" stroke="%23717171" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.3359 1.66602V4.16602" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.91406 7.57422H17.0807" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.5 7.08268V14.166C17.5 16.666 16.25 18.3327 13.3333 18.3327H6.66667C3.75 18.3327 2.5 16.666 2.5 14.166V7.08268C2.5 4.58268 3.75 2.91602 6.66667 2.91602H13.3333C16.25 2.91602 17.5 4.58268 17.5 7.08268Z" stroke="%23555555" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.99803 11.4167H10.0055" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.91209 11.4167H6.91957" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.91209 13.9167H6.91957" stroke="%23555555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
            }

    </style>

    <!-- Filter Modal -->
    @if ($showFilterModal)
        <div class="modal filter-theme-modals" style="display: flex;">
            <div class="modal-content filter-modal">
           <div class="modal_heaader">
                <span class="close-modal" id="closeFilterModal"  wire:click="closeFilterModal" >
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                </span>
                <h3 class="mt-0">Filter</h3>
                </div>
              
                <label style='color:#717171;font-weight:500;'>Select Date</label>
                <div class=" row mt-3">
                    <div class='col-6'>
                        <span style="font-weight:500">From:</span>
                        <div class="date_field_wraper">
                            <input type="date" class="form-input mt-2 date-input" wire:model="fromDate">
                        </div>
                       
                    </div>
                    <div class='col-6'>
                        <span style="font-weight:500"> To:</span>
                        <div class="date_field_wraper">
                            <input type="date" class="form-input mt-2 date-input" wire:model="toDate">
                        </div>
                       
                    </div>
                </div>
                <label  style='color:#717171;font-weight:500;'>Status</label>
                <x-custom-select
                    name="roleFilter"
                    :options="array_merge(
                        [['value' => '', 'label' => 'All Types']],
                        $roles->map(function($role) {
                            return ['value' => $role->name, 'label' => ucfirst($role->name)];
                        })->toArray()
                    )"
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
                <span class="close-modal" wire:click="closeUserModal"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg></span>
                <h3>Add User</h3>
                <form wire:submit.prevent="saveUser">
                    <label>Name</label>
                    <input type="text" class="form-input" wire:model="name" placeholder="Enter name">

                    <label>Email</label>
                    <input type="email" class="form-input" wire:model="email" placeholder="Enter email">

                    <label>Home Address</label>
                    <input type="text" class="form-input" wire:model="address" placeholder="Enter home address">

                    <label>Phone Number</label>
                    <input type="number" class="form-input" wire:model="phone" placeholder="Enter phone number">

                    <div class="mb-3">
                        <label for="userType" class="form-label">Role</label>
                        <x-custom-select
                            name="user_type"
                            id="userType"
                            :options="array_merge(
                                [['value' => '', 'label' => 'Select role']],
                                $roles->map(function($role) {
                                    return ['value' => $role->name, 'label' => ucfirst($role->name)];
                                })->toArray()
                            )"
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
