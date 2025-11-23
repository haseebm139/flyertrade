<div>
    <livewire:admin.components.toolbar label="service users" />

    <!-- Users Table -->
    <table class="theme-table">
        <thead>
            <tr>
                <th><input type="checkbox" wire:model.live="selectAll"></th>
                <th class="sortable" data-column="0">Customer ID <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon" wire:click="sortBy('id')" {{ $sortField === 'id' ? $sortDirection : '' }}>
                </th>
                <th class="sortable" data-column="1">Customer Name <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon" wire:click="sortBy('name')" {{ $sortField === 'name' ? $sortDirection : '' }}>
                </th>
                <th class="sortable" data-column="2">Home Address <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon" wire:click="sortBy('address')"
                        {{ $sortField === 'address' ? $sortDirection : '' }}></th>
                <th class="sortable" data-column="3">Phone Number <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon" wire:click="sortBy('phone')"
                        {{ $sortField === 'phone' ? $sortDirection : '' }}></th>
                <th class="sortable" data-column="4">Status <img src="{{ asset('assets/images/icons/sort.png') }}"
                        class="sort-icon" wire:click="sortBy('status')"
                        {{ $sortField === 'status' ? $sortDirection : '' }}></th>

                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td><input type="checkbox" value="{{ $item->id }}" wire:model.live="selected"></td>
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
                    <td><span
                            class="status {{ $item->status == 'active' ? 'active' : 'inactive' }}">{{ ucfirst($item->status) ?? '' }}</span>
                    </td>
                    <td style="position:relative">
                        <div class="actions-dropdown">
                            <button class="actions-btn"> <img src="{{ asset('assets/images/icons/three-dots.png') }}"
                                    class="dots-img "></button>
                            <div class="actions-menu">
                                <a href="{{ route('user-management.service.users.view', ['id' => $item->id]) }}"><img src="{{ asset('assets/images/icons/eye.png') }}" alt="View User" class="w-5 h-5"> View user</a>
                                <a href="#" wire:click="edit({{ $item->id }}) "><img src="{{ asset('assets/images/icons/edit-icon.png') }}" alt="Edit User" class="w-5 h-5"> Edit user</a>
                                <a href="#" class="showDeleteModal___" data-id="{{ $item->id }}"><img src="{{ asset('assets/images/icons/delete-icon.png') }}" alt="Delete User" class="w-5 h-5"> Delete user</a>
                            </div>
                            <!-- ✅ Global Delete Modal -->

                        </div>
                        <div id="globalDeleteModal__{{ $item->id }}" class="deleteModal"
                            style="display: none;position:absolute;    top: 2.5vw; right: 1vw;">
                            <div class="delete-card">
                                <div class="delete-card-header">
                                    <h3 class="delete-title">Delete Service User?</h3>
                                    <span class="delete-close closeDeleteModal"
                                        data-id="{{ $item->id }}">&times;</span>
                                </div>
                                <p class="delete-text">Are you sure you want to delete this service user?</p>
                                <div class="delete-actions justify-content-start">
                                    <button class="confirm-delete-btn">Delete</button>
                                    <button class="cancel-delete-btn" data-id="{{ $item->id }}">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

            @empty
                <tr>
                    <td>No Service User found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $data->links('vendor.pagination.custom') }}



    @if ($showFilterModal)
        <div class="modal filter-theme-modal" style="display: flex;">
            <div class="modal-content filter-modal">
                <span class="close-modal" wire:click="closeFilterModal">&times;</span>
                <h3>Filter</h3>
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
                    <button type="button" class="reset-btn" wire:click="resetFilters">Reset</button>
                    <button type="button" class="submit-btn" wire:click="applyFilters">Apply Now</button>
                </div>
            </div>
        </div>
    @endif
</div>
<script>
    $(document).on('click', '.showDeleteModal___', function(e) {

        e.preventDefault();
        let id = $(this).data('id');

        $('#globalDeleteModal__' + id).css('display', 'block');
    })
    $(document).on('click', '.closeDeleteModal', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('#globalDeleteModal__' + id).css('display', 'none');
    })
    $(document).on('click', '.cancel-delete-btn', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('#globalDeleteModal__' + id).css('display', 'none');
    })
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.showDeleteModal___, .deleteModal').length) {
            $('.deleteModal').hide();
        }
    });
</script>
