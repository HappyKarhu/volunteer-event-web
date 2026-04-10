@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center">
        <div class="flex flex-wrap items-center justify-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400">
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center rounded-lg border border-emerald-200 bg-white px-4 py-2 text-sm font-medium text-emerald-600 shadow-sm transition hover:bg-emerald-50">
                    Previous
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex items-center rounded-lg px-3 py-2 text-sm text-gray-500">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex items-center rounded-lg border border-emerald-200 bg-white px-4 py-2 text-sm font-medium text-emerald-600 shadow-sm transition hover:bg-emerald-50 hover:text-amber-500">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center rounded-lg border border-emerald-200 bg-white px-4 py-2 text-sm font-medium text-emerald-600 shadow-sm transition hover:bg-emerald-50">
                    Next
                </a>
            @else
                <span class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400">
                    Next
                </span>
            @endif
        </div>
    </nav>
@endif
