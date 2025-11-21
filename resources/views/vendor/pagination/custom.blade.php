@if ($paginator->hasPages())
    <div class="row align-items-center py-5">
        <div class="col-lg-3">
            Pagination ({{ $paginator->currentPage() }} of {{ $paginator->lastPage() }})
        </div>

        <div class="col-lg-6 text-center">
            <div class="custom-pagination d-inline-flex gap-2">

                {{-- Tombol Previous --}}
                @if ($paginator->onFirstPage())
                    <a href="#" class="disabled">&laquo;</a>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}">&laquo;</a>
                @endif

                {{-- Nomor Halaman --}}
                @foreach ($elements as $element)
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <a href="#" class="active">{{ $page }}</a>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Tombol Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}">&raquo;</a>
                @else
                    <a href="#" class="disabled">&raquo;</a>
                @endif
            </div>
        </div>
    </div>
@endif
