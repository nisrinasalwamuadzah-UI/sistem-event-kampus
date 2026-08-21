@if ($paginator->hasPages())
    <div class="pagination-wrapper" style="display: flex; flex-direction: column; align-items: center; gap: 16px; margin: 24px 0;">
        <div class="pagination-info" style="font-size: 14px; color: #64748b; font-weight: 500;">
            Menampilkan <span style="font-weight: 700; color: #0f172a;">{{ $paginator->firstItem() }}</span> 
            hingga <span style="font-weight: 700; color: #0f172a;">{{ $paginator->lastItem() }}</span> 
            dari <span style="font-weight: 700; color: #0f172a;">{{ $paginator->total() }}</span> hasil
        </div>

        <ul class="pagination-nav" style="display: flex; list-style: none; padding: 0; margin: 0; gap: 8px; flex-wrap: wrap; justify-content: center;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="ph-bold ph-caret-left"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="ph-bold ph-caret-left"></i></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="ph-bold ph-caret-right"></i></a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="ph-bold ph-caret-right"></i></span>
                </li>
            @endif
        </ul>
    </div>
@endif
