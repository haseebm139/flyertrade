<div>
    
    <div class="users-toolbar">
        <div class="toolbar-left">
            <button class="export-btn" wire:click="exportCsv">
                <span class="download-icon"><img class="btn-icons"src="{{ asset('assets/images/icons/download.png') }}"
                        alt=""></span> Export CSV
            </button>
            @if($showAddButton)
            <button class="add-user-btn" id="openAddUserModal" wire:click="addItem">+ Add {{ $label }}</button>
            @endif
        </div>
        <div class="toolbar-right">
            <input type="text" class="search-user" placeholder="Search {{ strtolower($label) }}"
                wire:model.live.debounce.500ms="search">
            <button class="filter-btn" id="openFilterModal" wire:click="openFilterModal"> <span
                    class="download-icon"><img  class="btn-icons" src="{{ asset('assets/images/icons/button-icon.png') }} "
                        alt=""></span>Filter</button>
        </div>
    </div>
</div>
