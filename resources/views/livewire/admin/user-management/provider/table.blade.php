<div>

    <livewire:admin.components.toolbar label="service providers" button_label="Users" search_label="user"/>

    <!-- Users Table -->
    <table class="theme-table">
        <thead>
            <tr>
                <th><input type="checkbox" wire:model.live="selectAll"></th>
                <th class="sortable" data-column="0">User ID <img src="{{ asset('assets/images/icons/sort.png') }}"
                        wire:click="sortBy('id')" {{ $sortField === 'id' ? $sortDirection : '' }} class="sort-icon"></th>
                <th class="sortable" data-column="1">Provider name <img src="{{ asset('assets/images/icons/sort.png') }}"
                        wire:click="sortBy('name')" {{ $sortField === 'name' ? $sortDirection : '' }} class="sort-icon">
                </th>
                <th class="sortable" data-column="2">Home address <img src="{{ asset('assets/images/icons/sort.png') }}"
                        wire:click="sortBy('address')" {{ $sortField === 'address' ? $sortDirection : '' }}
                        class="sort-icon"></th>
                <th class="sortable" data-column="3">Phone number <img src="{{ asset('assets/images/icons/sort.png') }}"
                        wire:click="sortBy('phone')" {{ $sortField === 'phone' ? $sortDirection : '' }}
                        class="sort-icon">
                </th>
                <th class="sortable" data-column="4">Service category <img
                        src="{{ asset('assets/images/icons/sort.png') }}" wire:click="sortBy('is_verified')"
                        {{ $sortField === 'is_verified' ? $sortDirection : '' }} class="sort-icon"></th>
                <th class="sortable" data-column="5">Verification status <img
                        src="{{ asset('assets/images/icons/sort.png') }}" class="sort-icon"
                        wire:click="sortBy('is_verified')" {{ $sortField === 'is_verified' ? $sortDirection : '' }}>
                </th>
                <th class="sortable" data-column="6"> Status <img src="{{ asset('assets/images/icons/sort.png') }}"
                        wire:click="sortBy('status')" {{ $sortField === 'status' ? $sortDirection : '' }}
                        class="sort-icon"></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <th><input type="checkbox" wire:model.live="selectAll"></th>
                    <td>{{ $item->id }}</td>
                    <td>
                        <div class="user-info">
                            <img src="{{ asset($item->avatar ?? 'assets/images/icons/person-one.png') }}"
                                alt="avatar">
                            <div>
                                <p class="user-name">{{ $item->name ?? '' }}</p>
                                <p class="user-email">{{ $item->email ?? '' }}</p>
                            </div>
                        </div>
                    </td>

                    <td>{{ $item->address ?? '', ' , ', $item->city ?? '', ' , ', $item->state ?? '', ' , ', $item->country ?? '' }}
                    </td>
                    <td>{{ $item->phone ?? '' }}</td>
                    <td>Plumbing <span class="more"> +2 more</span></td>
                    <td><span
                            class="status {{ $item->is_verified == 'verified'
                                ? 'active'
                                : ($item->is_verified == 'pending'
                                    ? 'pending'
                                    : ($item->is_verified == 'declined'
                                        ? 'inactive'
                                        : '')) }}">{{ ucfirst($item->is_verified ?? '') }}</span>
                    </td>
                    <td><span
                            class="status {{ $item->status == 'active' ? 'active' : 'inactive' }}">{{ ucfirst($item->status) ?? '' }}</span>
                    </td>
                    <td>
                        <div class="actions-dropdown">
                            <button class="actions-btn"> <img src="{{ asset('assets/images/icons/three-dots.png') }}"
                                    class="dots-img "></button>
                            <div class="actions-menu">
                                <a href="{{ route('user-management.service.providers.view', ['id' => $item->id]) }}"><img
                                        src="{{ asset('assets/images/icons/eye.png') }}" alt="View User"
                                        class="w-5 h-5">View user</a>
                                <a href="#"><img src="{{ asset('assets/images/icons/edit-icon.png') }}" alt="Edit User" class="w-5 h-5"> Edit user</a>
                                <a href="#" class='showDeleteModal'><img src="{{ asset('assets/images/icons/delete-icon.png') }}" alt="Delete User" class="w-5 h-5"> Delete user</a>
                                <!-- ✅ Global Delete Modal -->
                                <div id="globalDeleteModal{{ $item->id }}" class="deleteModal"
                                    style="display: none;position:absolute;    top: 2.5vw;">
                                    <div class="delete-card">
                                        <div class="delete-card-header">
                                            <h3 class="delete-title">Delete Service Provider?</h3>
                                            <span class="delete-close" id="closeDeleteModal">&times;</span>
                                        </div>
                                        <p class="delete-text">Are you sure you want to delete this service provider?
                                        </p>
                                        <div class="delete-actions justify-content-start">
                                            <button class="confirm-delete-btn">Delete</button>
                                            <button class="cancel-delete-btn">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

            @empty
                <tr>
                    <td>No Service Provider found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $data->links('vendor.pagination.custom') }}
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
    </style>
    @if ($showFilterModal)
        <div class="modal filter-theme-modal" style="display: flex;">
            <div class="modal-content filter-modal">
                <div class="modal_heaader">
                <span class="close-modal"  wire:click="closeFilterModal" >
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M0.75 11.236L5.993 5.993L11.236 11.236M11.236 0.75L5.992 5.993L0.75 0.75" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
</svg>
                </span>
                <h3 class="mt-0">Filter</h3>
                </div>
              
                <label style='color:#717171'>Select Date</label>
                <div class=" row mt-3">
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
                <x-custom-select name="status" :options="[
                    ['value' => '', 'label' => 'Select status'],
                    ['value' => 'active', 'label' => 'Active'],
                    ['value' => 'inactive', 'label' => 'Inactive'],
                ]" placeholder="Select status" wireModel="status"
                    class="form-input mt-2" />
                <div class="form-actions">
                    <button type="button" class="reset-btn filter_modal_reset" wire:click="resetFilters">Reset</button>
                    <button type="button" class="submit-btn" wire:click="applyFilters">Apply Now</button>
                </div>
            </div>
        </div>
    @endif
</div>
