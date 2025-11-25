<div>
    
    <div class="users-toolbar">
        <div class="toolbar-left">
            <button class="export-btn" wire:click="exportCsv">
                <span class="download-icon"><img class="btn-icons"src="{{ asset('assets/images/icons/download.png') }}"
                        alt=""></span> Export CSV
            </button>
            @if($showAddButton)
            <button class="add-user-btn" id="openAddUserModal" wire:click="addItem"><i class="fa-solid fa-plus mr-3"></i> Add {{ $button_label }}</button>
            @endif
        </div>
        <div class="toolbar-right">
            <input type="text" class="search-user" placeholder="Search {{ strtolower($search_label) }}"
                wire:model.live.debounce.500ms="search">
            <button class="filter-btn" id="openFilterModal" wire:click="openFilterModal"> <span
                    class="download-icon">
                    Filter
                    <img  class="btn-icons" src="{{ asset('assets/images/icons/button-icon.png') }} "
                        alt=""></span></button>
        </div>
    </div>
</div>
