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
                    <td>
                        <div class="actions-dropdown">
                            <button class="actions-btn">⋮</button>
                            <div class="actions-menu">
                                <a href="{{ route('user-management.service.users.view', ['id' => $item->id]) }}" ><i class="fa fa-eye"></i> View user</a>
                                <a href="#" wire:click="edit({{ $item->id }}) "><i class="fa fa-pen"></i> Edit user</a>
                                <a href="#"><i class="fa fa-trash"></i> Delete user</a>
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
        <div class="modal" style="display: flex;">
            <div class="modal-content filter-modal">
                <span class="close-modal" wire:click="closeFilterModal">&times;</span>
                <h3>Filter</h3>
                <label>Select Date</label>
                <div class="date-range">
                    <div>
                        <span>From:</span>
                        <input type="date" class="form-input" wire:model="fromDate">
                    </div>
                    <div>
                        <span>To:</span>
                        <input type="date" class="form-input" wire:model="toDate">
                    </div>
                </div>
                <label>Status</label>
                <select class="form-input" wire:model="status">
                    <option value="">Select status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <div class="form-actions">
                    <button type="button" class="reset-btn" wire:click="resetFilters">Reset</button>
                    <button type="button" class="submit-btn" wire:click="applyFilters">Apply Now</button>
                </div>
            </div>
        </div>
    @endif
</div>
