@if ($paginator->hasPages())
    <nav>
        <ul class="pagination flex justify-center items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="opacity-50">
                    <span class="px-3 py-1 border border-[#e5e7eb] rounded bg-white text-[#4b5563] cursor-not-allowed">&lsaquo; Vorige</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 border border-[#e5e7eb] rounded bg-white text-[#2563eb] hover:bg-[#2563eb] hover:text-white transition">&lsaquo; Vorige</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="opacity-50">
                        <span class="px-3 py-1 border border-[#e5e7eb] rounded bg-white text-[#4b5563]">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="px-3 py-1 border border-[#2563eb] rounded bg-[#2563eb] text-white font-semibold">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="px-3 py-1 border border-[#e5e7eb] rounded bg-white text-[#2563eb] hover:bg-[#2563eb] hover:text-white transition">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 border border-[#e5e7eb] rounded bg-white text-[#2563eb] hover:bg-[#2563eb] hover:text-white transition">Volgende &rsaquo;</a>
                </li>
            @else
                <li class="opacity-50">
                    <span class="px-3 py-1 border border-[#e5e7eb] rounded bg-white text-[#4b5563] cursor-not-allowed">Volgende &rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
