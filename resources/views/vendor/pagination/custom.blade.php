@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display:flex;justify-content:center;align-items:center;gap:8px;flex-wrap:wrap;">
        
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="Previous" 
                style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border:1px solid var(--c-hairline);background:var(--c-surface-soft);color:var(--c-mute);border-radius: 0;cursor:not-allowed;opacity:0.5;">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous"
                style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border:1px solid var(--c-hairline);background:var(--c-surface-soft);color:var(--c-ink);border-radius: 0;cursor:pointer;text-decoration:none;transition:all 0.2s;font-weight:600;"
                onmouseenter="this.style.borderColor='var(--c-primary)';this.style.background='rgba(118,185,0,0.1)';this.style.color='var(--c-primary)';"
                onmouseleave="this.style.borderColor='var(--c-hairline)';this.style.background='var(--c-surface-soft)';this.style.color='var(--c-ink)';">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        <style>
            .pagination-desktop { display: inline-flex !important; }
            .pagination-mobile { display: none !important; }
            @media (max-width: 768px) {
                .pagination-desktop { display: none !important; }
                .pagination-mobile { display: inline-flex !important; }
            }
        </style>

        {{-- Pagination Elements (Desktop - Custom 10 item sliding window) --}}
        @php
            $startDesktop = max(1, $paginator->currentPage() - 4);
            $endDesktop = min($paginator->lastPage(), $startDesktop + 9);
            
            if ($endDesktop - $startDesktop < 9 && $paginator->lastPage() >= 10) {
                $startDesktop = max(1, $endDesktop - 9);
            }
        @endphp
        @for ($i = $startDesktop; $i <= $endDesktop; $i++)
            @if ($i == $paginator->currentPage())
                <span class="pagination-desktop" aria-current="page" 
                    style="align-items:center;justify-content:center;width:48px;height:48px;border:2px solid var(--c-primary);background:var(--c-primary);color:#000;border-radius: 0;font-weight:800;font-size:16px;box-shadow:0 4px 12px rgba(118,185,0,0.3);">
                    {{ $i }}
                </span>
            @else
                <a href="{{ $paginator->url($i) }}" class="pagination-desktop" aria-label="Go to page {{ $i }}"
                    style="align-items:center;justify-content:center;width:48px;height:48px;border:1px solid var(--c-hairline);background:var(--c-surface-soft);color:var(--c-ink);border-radius: 0;cursor:pointer;text-decoration:none;transition:all 0.2s;font-weight:600;font-size:16px;"
                    onmouseenter="this.style.borderColor='var(--c-primary)';this.style.background='rgba(118,185,0,0.1)';this.style.color='var(--c-primary)';"
                    onmouseleave="this.style.borderColor='var(--c-hairline)';this.style.background='var(--c-surface-soft)';this.style.color='var(--c-ink)';">
                    {{ $i }}
                </a>
            @endif
        @endfor

        {{-- Pagination Elements (Mobile - Custom 4 item sliding window) --}}
        @php
            $currentPage = $paginator->currentPage();
            $lastPage = $paginator->lastPage();
            
            $startMobile = max(1, $currentPage - 1);
            $endMobile = min($lastPage, $startMobile + 3);
            
            if ($endMobile - $startMobile < 3 && $lastPage >= 4) {
                $startMobile = max(1, $endMobile - 3);
            }
        @endphp
        @for ($i = $startMobile; $i <= $endMobile; $i++)
            @if ($i == $currentPage)
                <span class="pagination-mobile" aria-current="page" 
                    style="align-items:center;justify-content:center;width:48px;height:48px;border:2px solid var(--c-primary);background:var(--c-primary);color:#000;border-radius: 0;font-weight:800;font-size:16px;box-shadow:0 4px 12px rgba(118,185,0,0.3);">
                    {{ $i }}
                </span>
            @else
                <a href="{{ $paginator->url($i) }}" class="pagination-mobile" aria-label="Go to page {{ $i }}"
                    style="align-items:center;justify-content:center;width:48px;height:48px;border:1px solid var(--c-hairline);background:var(--c-surface-soft);color:var(--c-ink);border-radius: 0;cursor:pointer;text-decoration:none;transition:all 0.2s;font-weight:600;font-size:16px;"
                    onmouseenter="this.style.borderColor='var(--c-primary)';this.style.background='rgba(118,185,0,0.1)';this.style.color='var(--c-primary)';"
                    onmouseleave="this.style.borderColor='var(--c-hairline)';this.style.background='var(--c-surface-soft)';this.style.color='var(--c-ink)';">
                    {{ $i }}
                </a>
            @endif
        @endfor

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next"
                style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border:1px solid var(--c-hairline);background:var(--c-surface-soft);color:var(--c-ink);border-radius: 0;cursor:pointer;text-decoration:none;transition:all 0.2s;font-weight:600;"
                onmouseenter="this.style.borderColor='var(--c-primary)';this.style.background='rgba(118,185,0,0.1)';this.style.color='var(--c-primary)';"
                onmouseleave="this.style.borderColor='var(--c-hairline)';this.style.background='var(--c-surface-soft)';this.style.color='var(--c-ink)';">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span aria-disabled="true" aria-label="Next"
                style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border:1px solid var(--c-hairline);background:var(--c-surface-soft);color:var(--c-mute);border-radius: 0;cursor:not-allowed;opacity:0.5;">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </nav>
@endif
