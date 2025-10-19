<div>
    <livewire:admin.components.toolbar label="service providers" />

    <!-- Begin::Table -->
    <table class="theme-table">
        <thead>
            <tr>
                <th><input type="checkbox" wire:model.live="selectAll"></th>
                <th class="sortable">
                    Service category
                    <img src="{{ asset('assets/images/icons/sort.png') }}" wire:click="sortBy('name')" class="sort-icon {{ $sortField === 'name' ? $sortDirection : '' }}">
                </th>
                <th class="sortable">
                    Registered providers
                    <img src="{{ asset('assets/images/icons/sort.png') }}" wire:click="sortBy('providers_count')" class="sort-icon {{ $sortField === 'providers_count' ? $sortDirection : '' }}">
                </th>
                <th class="sortable">
                    Date created
                    <img src="{{ asset('assets/images/icons/sort.png') }}" wire:click="sortBy('created_at')" class="sort-icon {{ $sortField === 'created_at' ? $sortDirection : '' }}">
                </th>
                <th class="sortable">
                    Description
                    <img src="{{ asset('assets/images/icons/sort.png') }}" wire:click="sortBy('description')" class="sort-icon {{ $sortField === 'description' ? $sortDirection : '' }}">
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td><input type="checkbox" value="{{ $item->id }}" wire:model.live="selected"></td>
                    <td>{{ $item->name }}</td>

                    <td style="cursor:pointer;" 
                        @if ($item->providers_count > 0)
                            wire:click="$dispatch('open-user-providers-modal', { serviceId: {{ $item->id }} })"
                        @endif>
                        @if ($item->providers_count > 0)
                            <div class="user-info">
                                <img src="{{ asset($item->providers[0]->avatar ?? 'assets/images/icons/person-one.png') }}" alt="User" class="avatar">
                                <span class="user-theme-name">{{ $item->providers[0]->name ?? '' }}</span>
                                <span class="more"> + {{ $item->providers_count - 1 }} more</span>
                            </div>
                        @endif
                    </td>

                    <td><span class="date">{{ dateFormat($item->created_at) }}</span></td>
                    <td><span class="desf">{{ $item->description }}</span></td>

                    <td>
                        <span class="desf d-flex">
                            <button class="edit-btn" wire:click="edit({{ $item->id }})" style="border: 0 !important">
                                <img src="{{ asset('assets/images/icons/edit-icon.png') }}" alt="Edit" class="action-icon" > 
                            </button>

                            <!-- ✅ Delete button -->
                            <button type="button" class="delete-btn showDeleteModal" data-id="{{ $item->id }}">
                                <img src="{{ asset('assets/images/icons/delete-icon.png') }}" alt="Delete" class="action-icon">
                            </button>
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No categories found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $data->links('vendor.pagination.custom') }}

    <!-- ✅ Global Delete Modal -->
    <div id="globalDeleteModal" class="deleteModal" style="display: none;">
        <div class="delete-card">
            <div class="delete-card-header">
                <h3 class="delete-title">Delete Service</h3>
                <span class="delete-close" id="closeDeleteModal">&times;</span>
            </div>
            <p class="delete-text">Are you sure you want to delete this service?</p>
            <div class="delete-actions justify-content-start">
                <button class="confirm-delete-btn">Delete</button>
                <button class="cancel-delete-btn">Cancel</button>
            </div>
        </div>
    </div>

    @if ($showFilterModal)
        <div class="modal filter-theme-modals" style="display: flex;">
            <div class="modal-content filter-modal">
                <span class="close-modal" wire:click="closeFilterModal">&times;</span>
                <h3>Filter</h3>
                <label>Select Date</label>
                <div class="date-range">
                    <div><span>From:</span><input type="date" class="form-input" wire:model="fromDate"></div>
                    <div><span>To:</span><input type="date" class="form-input" wire:model="toDate"></div>
                </div>
                <label>Status</label>
                <select class="form-input" wire:model="status">
                    <option value="">Select status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                <div class="form-actions">
                    <button type="button" class="reset-btn" wire:click="resetFilters">Reset</button>
                    <button type="button" class="submit-btn" wire:click="applyFilters">Apply Now</button>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("globalDeleteModal");
    const confirmBtn = modal.querySelector(".confirm-delete-btn");
    const cancelBtn = modal.querySelector(".cancel-delete-btn");
    const closeBtn = document.getElementById("closeDeleteModal");

    document.addEventListener("click", function(e) {
        if (e.target.closest(".showDeleteModal")) {
            const btn = e.target.closest(".showDeleteModal");
            const id = btn.getAttribute("data-id");
            const rect = btn.getBoundingClientRect();

            const modalWidth = window.innerWidth * 0.40;
            let topPos = window.scrollY + rect.top + 60;

            // 🔹 Left position reduced (closer to button)
            let leftPos = rect.left + rect.width / 2 - modalWidth / 2 - 200; // was -20 before, reduced more

            if (leftPos + modalWidth > window.innerWidth - 20) leftPos = window.innerWidth - modalWidth - 20;
            if (leftPos < 20) leftPos = 20;

            modal.style.display = "block";
            modal.style.position = "absolute";
            modal.style.top = `${topPos}px`;
            modal.style.left = `${leftPos}px`;
            modal.dataset.id = id;
        }
    });

    [cancelBtn, closeBtn].forEach(btn => {
        btn.addEventListener("click", () => {
            modal.style.display = "none";
        });
    });

    document.addEventListener("click", function(e) {
        if (!e.target.closest(".deleteModal") && !e.target.closest(".showDeleteModal")) {
            modal.style.display = "none";
        }
    });

    confirmBtn.addEventListener("click", function() {
        const id = modal.dataset.id;
        if (window.Livewire) {
            Livewire.dispatch('delete', { id });
        }
        modal.style.display = "none";
    });
});
</script>

@endpush
