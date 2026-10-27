@if ($paginator->hasPages())
    @php
        $btn =
            'inline-flex h-9 min-w-[2.25rem] items-center justify-center border px-3 text-xs font-semibold transition';
        $idle = 'border-mist/40 text-navy hover:border-gold hover:text-gold dark:border-white/15 dark:text-ivory';
        $off = 'cursor-not-allowed border-mist/20 text-mist/60 dark:border-white/10';
    @endphp

    <nav aria-label="Pagination" class="flex flex-col items-center justify-between gap-4 sm:flex-row">
        <p class="text-xs text-charcoal/70 dark:text-ivory/70">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </p>

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="{{ $btn }} {{ $off }}" aria-disabled="true">&larr;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page"
                    class="{{ $btn }} {{ $idle }}">&larr;</a>
            @endif

            {{-- Phones get "Page 2 of 5", because a row of numbers would overflow --}}
            <span class="px-3 text-xs sm:hidden">Page {{ $paginator->currentPage() }} of
                {{ $paginator->lastPage() }}</span>

            <div class="hidden items-center gap-1 sm:flex">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-2 text-xs text-mist">…</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page"
                                    class="{{ $btn }} border-gold bg-gold text-navy">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" aria-label="Go to page {{ $page }}"
                                    class="{{ $btn }} {{ $idle }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page"
                    class="{{ $btn }} {{ $idle }}">&rarr;</a>
            @else
                <span class="{{ $btn }} {{ $off }}" aria-disabled="true">&rarr;</span>
            @endif
        </div>
    </nav>
@endif
