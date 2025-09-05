@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous --}}
        <button
            class="page-btn prev"
            wire:click="previousPage"
            @disabled($paginator->onFirstPage())
        >
            ‹
        </button>

        @php
            $totalPages = $paginator->lastPage();
            $currentPage = $paginator->currentPage();
            $visiblePages = 3; // how many numbers to show around current
        @endphp

        {{-- First page --}}
        <button
            wire:click="gotoPage(1)"
            class="page-btn {{ $currentPage == 1 ? 'active' : '' }}"
        >
            1
        </button>

        {{-- Left dots --}}
        @if ($currentPage > $visiblePages + 1)
            <span class="dots">...</span>
        @endif

        {{-- Middle pages --}}
        @for ($i = max(2, $currentPage - 1); $i <= min($totalPages - 1, $currentPage + 1); $i++)
            @if ($i > 1 && $i < $totalPages)
                <button
                    wire:click="gotoPage({{ $i }})"
                    class="page-btn {{ $currentPage == $i ? 'active' : '' }}"
                >
                    {{ $i }}
                </button>
            @endif
        @endfor

        {{-- Right dots --}}
        @if ($currentPage < $totalPages - $visiblePages)
            <span class="dots">...</span>
        @endif

        {{-- Last page --}}
        @if ($totalPages > 1)
            <button
                wire:click="gotoPage({{ $totalPages }})"
                class="page-btn {{ $currentPage == $totalPages ? 'active' : '' }}"
            >
                {{ $totalPages }}
            </button>
        @endif

        {{-- Next --}}
        <button
            class="page-btn next"
            wire:click="nextPage"
            @disabled(!$paginator->hasMorePages())
        >
            ›
        </button>

        {{-- Rows per page --}}
        <div class="rows-control">
            <label for="perPage">Show</label>
            <select
                wire:model.live="perPage"
                id="perPage"
            >
                <option
                    value="10"
                    selected
                >10 rows</option>
                <option value="25">25 rows</option>
                <option value="50">50 rows</option>
                <option value="100">100 rows</option>
            </select>
        </div>
    </div>
@endif
