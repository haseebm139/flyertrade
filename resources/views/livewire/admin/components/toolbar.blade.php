<div>
    
    <div class="users-toolbar">
        <div class="toolbar-left">
                <button class="export-btn d-flex align-items-center gap-1"  wire:click="exportCsv" style="color:#004E42; line-height:1">
                <span class="download-icon"><img class="btn-icons"src="{{ asset('assets/images/icons/download.svg') }}"
                        alt=""></span> &nbsp;Export CSV
            </button>
            @if($showAddButton)
            <button class="add-user-btn d-flex align-items-center gap-1" style="line-height:1" id="openAddUserModal" wire:click="addItem"><i class="fa-solid fa-plus mr-3"></i> Add {{ $button_label }}</button>
            @endif
        </div>
        <div class="toolbar-right">
            <input type="text" class="search-user" placeholder="Search {{ strtolower($search_label) }}"
                wire:model.live.debounce.500ms="search">
            <button class="filter-btn" id="openFilterModal" wire:click="openFilterModal"> <span
                    class="download-icon">
                    Filter
                    <img  class="btn-icons"  src="{{ asset('assets/images/icons/button-icon.svg') }} "
                        alt=""></span></button>
                           <a href="#" class="filter_active_btna___">
                            <span>Active users</span>
                            <i class="fa-solid fa-xmark"></i>
                    </a>
        </div>
    </div>
</div>
