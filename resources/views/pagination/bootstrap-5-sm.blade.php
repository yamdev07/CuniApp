@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="cuni-pagination-wrapper">
    <div class="cuni-pagination-info">
        <span class="cuni-pagination-text">
            {!! __('Affichage de') !!}
            @if ($paginator->firstItem())
                <strong>{{ $paginator->firstItem() }}</strong>
                {!! __('à') !!}
                <strong>{{ $paginator->lastItem() }}</strong>
            @else
                {{ $paginator->count() }}
            @endif
            {!! __('sur') !!}
            <strong>{{ $paginator->total() }}</strong>
            {!! __('résultats') !!}
        </span>
    </div>
    
    <ul class="cuni-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="cuni-page-item disabled" aria-disabled="true">
                <span class="cuni-page-link">
                    <i class="bi bi-chevron-left"></i>
                </span>
            </li>
        @else
            <li class="cuni-page-item">
                <a class="cuni-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                    <i class="bi bi-chevron-left"></i>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="cuni-page-item disabled" aria-disabled="true">
                    <span class="cuni-page-link cuni-page-dots">{{ $element }}</span>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="cuni-page-item active" aria-current="page">
                            <span class="cuni-page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="cuni-page-item">
                            <a class="cuni-page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="cuni-page-item">
                <a class="cuni-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </li>
        @else
            <li class="cuni-page-item disabled" aria-disabled="true">
                <span class="cuni-page-link">
                    <i class="bi bi-chevron-right"></i>
                </span>
            </li>
        @endif
    </ul>
</nav>
@endif