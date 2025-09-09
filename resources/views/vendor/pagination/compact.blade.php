{{-- resources/views/vendor/pagination/compact.blade.php --}}
@if ($paginator->hasPages())
    @php
        $total   = $paginator->lastPage();
        $current = $paginator->currentPage();

        // 数字ボタンの最大表示数（例：8）
        $maxNumbers = 5;

        // ウィンドウの開始・終了を決める
        $start = max(1, $current - intdiv($maxNumbers - 1, 2));
        $end   = min($total, $start + $maxNumbers - 1);
        // 右端が足りない場合の巻き戻し
        $start = max(1, $end - $maxNumbers + 1);
    @endphp

    <nav>
        <ul class="pagination pagination-sm flex-wrap gap-2 mb-0">

            {{-- 最初へ（≪） --}}
            <li class="page-item {{ $current === 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="First">&laquo;</a>
            </li>

            {{-- 前へ（＜） --}}
            <li class="page-item {{ !$paginator->onFirstPage() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() ?? '#' }}" rel="prev" aria-label="Previous">&lsaquo;</a>
            </li>

            {{-- 先頭省略（…） --}}
            @if ($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>
                @if ($start > 2)
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                @endif
            @endif

            {{-- 中央のページ番号 --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                @endif
            @endfor

            {{-- 末尾省略（…） --}}
            @if ($end < $total)
                @if ($end < $total - 1)
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                @endif
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($total) }}">{{ $total }}</a>
                </li>
            @endif

            {{-- 次へ（＞） --}}
            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() ?? '#' }}" rel="next" aria-label="Next">&rsaquo;</a>
            </li>

            {{-- 最後へ（≫） --}}
            <li class="page-item {{ $current === $total ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($total) }}" aria-label="Last">&raquo;</a>
            </li>

        </ul>
    </nav>
@endif
