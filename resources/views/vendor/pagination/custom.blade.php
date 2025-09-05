<!-- End::Pagination -->
@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous --}}
        <button
            class="page-btn prev"
            wire:click="previousPage"
            @if (!$paginator->onFirstPage()) :disabled="false" @else disabled @endif
        >
            ‹
        </button>
        @php
            $totalPages = $paginator->lastPage();
            $currentPage = $paginator->currentPage();
            $visiblePages = 3; // how many numbers to show around current
        @endphp
        <button
            wire:click="gotoPage(1)"
            class="page-btn {{ $currentPage == 1 ? 'active' : '' }}"
        >
            1
        </button>
        {{-- Left dots --}}
        @if ($currentPage > $visiblePages)
            <span class="dots">...</span>
        @endif

        @for ($i = max(2, $currentPage - 1); $i <= min($totalPages - 1, $currentPage + 1); $i++)
            <button
                wire:click="gotoPage({{ $i }})"
                class="page-btn {{ $currentPage == $i ? 'active' : '' }}"
            >
                {{ $i }}
            </button>
        @endfor
        @if ($currentPage < $totalPages - $visiblePages + 1)
            <span class="dots">...</span>
        @endif

        <button
            wire:click="gotoPage({{ $totalPages }})"
            class="page-btn {{ $currentPage == $totalPages ? 'active' : '' }}"
        >
            {{ $totalPages }}
        </button>
        {{-- Next --}}
        <button
            class="page-btn next"
            wire:click="nextPage"
            @if (!$paginator->hasMorePages()) disabled @endif
        >
            ›
        </button>


        {{-- Rows per page --}}
        <div class="rows-control">
            <label for="perPage">Show</label>
            <select
                wire:model="perPage"
                id="perPage"
            >
                <option value="10">10 rows</option>
                <option value="25">25 rows</option>
                <option value="50">50 rows</option>
                <option value="100">100 rows</option>
            </select>
        </div>



    </div>
@endif
