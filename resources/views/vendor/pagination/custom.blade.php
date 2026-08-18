@if ($paginator->hasPages())
    <div class="pagination" role="navigation" aria-label="Pagination Navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-btn disabled" style="opacity: 0.5; cursor: not-allowed;" aria-disabled="true" aria-label="@lang('pagination.previous')">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn" rel="prev" aria-label="@lang('pagination.previous')">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="page-btn disabled" style="opacity: 0.5; cursor: not-allowed;" aria-disabled="true">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-btn active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" rel="next" aria-label="@lang('pagination.next')">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="page-btn disabled" style="opacity: 0.5; cursor: not-allowed;" aria-disabled="true" aria-label="@lang('pagination.next')">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </div>
@endif
