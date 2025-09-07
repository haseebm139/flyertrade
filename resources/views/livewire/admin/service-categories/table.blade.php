<div>
    <button wire:click="testToastr">Test Toastr</button>
    <!-- Begin::Toolbar -->
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
                wire:click="$dispatch('openModal')"
            >+ New service categories </button>
        </div>
        <div class="toolbar-right">
            <input
                type="text"
                class="search-user"
                placeholder="Search Category"
                wire:model.live.debounce.500ms="search"
            >
            <button
                class="filter-btn"
                id="openFilterModal"
                wire:click="openFilterModal"
            > <span class="download-icon"><img
                        src="{{ asset('assets/images/icons/button-icon.png') }}"
                        alt=""
                    ></span>Filter</button>
        </div>
    </div>
    <!-- End::Toolbar -->

    <!-- Begin::Table -->
    <table class="theme-table">
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
                 
                <th class="sortable">Date created
                    <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    >
                </th>
                
                <th class="sortable">Description
                    <img
                        src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon"
                    >
                </th>
                <th>Action </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $item)
                <tr >
                    <td><input type="checkbox"></td>
                    <td>{{ $item->name }}</td>
                    <td>
                        @if ($item->providers_count > 0)
                            <div class="user-info">
                                <img
                                    src="{{ asset($item->providers[0]->avatar ?? 'assets/images/icons/person-one.png') }}"
                                    alt="User"
                                    class="avatar"
                                >
                                <span>{{ $item->providers[0]->name ?? '' }}</span>
                                <span>+{{ $item->providers_count - 1 }}
                                    more</span>
                            </div>
                        @endif
                    </td>
                     
                    <td><span class="date">{{ dateFormat($item->created_at) }}</span></td>
                     
                    <td>
                        <span class="desf">
                            {{ $item->description }}
                        </span>
                    </td>
                    <td>
                        <button
                            class="edit-btn"
                            wire:click="edit({{ $item->id }})"
                        >
                            <img
                                src="{{ asset('assets/images/icons/edit-icon.png') }}"
                                alt="Edit"
                                class="action-icon"
                            >
                        </button>
                        <button
                        data-id="{{ $item->id }}"
                            class="delete-btn showDeleteModal"
                            wire:click="confirmDelete({{ $item->id }})"
                        >
                            <img
                                src="{{ asset('assets/images/icons/delete-icon.png') }}"
                                alt="Delete"
                                class="action-icon"
                            >
                            
                        </button>
                        @if($confirmingId === $item->id)                            

                            <div class="deleteModal delete-card" id="global-delete-modal"  >
                                <div class="delete-card-header">
                                    <h3 class="delete-title">Delete Service</h3>
                                    <span class="delete-close" wire:click="$set('confirmingId', null)">&times;</span>
                                </div>
                                <p class="delete-text">Are you sure you want to delete this service?</p>
                                <div class="delete-actions">
                                    <button class="confirm-delete-btn" wire:click="delete({{ $item->id }})">Delete</button>
                                    <button class="cancel-delete-btn" wire:click="$set('confirmingId', null)">Cancel</button>
                                </div>
                            </div>

                        @endif

                        <!-- Delete Popover -->
                        
                    </td>
                    
                </tr>
                
            @empty
                <tr>
                    <td>No categories found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <!-- End::Table -->
 
    {{ $categories->links('vendor.pagination.custom') }}

    @if ($showFilterModal)
        <div
            class="modal"
            style="display: flex;"
        >
            <div class="modal-content filter-modal">
                <span
                    class="close-modal"
                    wire:click="closeFilterModal"
                >&times;</span>
                <h3>Filter</h3>
                <label>Select Date</label>
                <div class="date-range">
                    <div>
                        <span>From:</span>
                        <input
                            type="date"
                            class="form-input"
                            wire:model="fromDate"
                        >
                    </div>
                    <div>
                        <span>To:</span>
                        <input
                            type="date"
                            class="form-input"
                            wire:model="toDate"
                        >
                    </div>
                </div>
                <label>Status</label>
                <select
                    class="form-input"
                    wire:model="status"
                >
                    <option value="">Select status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                <div class="form-actions">
                    <button
                        type="button"
                        class="reset-btn"
                        wire:click="resetFilters"
                    >Reset</button>
                    <button
                        type="button"
                        class="submit-btn"
                        wire:click="applyFilters"
                    >Apply Now</button>
                </div>
            </div>
        </div>
    @endif

 
     

</div>
