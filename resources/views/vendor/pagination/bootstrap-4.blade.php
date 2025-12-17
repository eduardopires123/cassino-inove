@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Link para "Anterior" --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    @php
                        $locale = App::getLocale();
                        $prevPageUrl = $paginator->previousPageUrl();
                        // Adicionar o prefixo de idioma se não estiver usando o idioma padrão
                        if ($locale != 'pt_BR' && strpos($prevPageUrl, "/{$locale}/") === false) {
                            $prevPageUrl = "/{$locale}" . $prevPageUrl;
                        }
                    @endphp
                    <a class="page-link" href="{{ $prevPageUrl }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Links de Página --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @php
                            $locale = App::getLocale();
                            // Adicionar o prefixo de idioma se não estiver usando o idioma padrão
                            if ($locale != 'pt_BR' && strpos($url, "/{$locale}/") === false) {
                                $url = "/{$locale}" . $url;
                            }
                        @endphp
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Link para "Próximo" --}}
            @if ($paginator->hasMorePages())
                @php
                    $locale = App::getLocale();
                    $nextPageUrl = $paginator->nextPageUrl();
                    // Adicionar o prefixo de idioma se não estiver usando o idioma padrão
                    if ($locale != 'pt_BR' && strpos($nextPageUrl, "/{$locale}/") === false) {
                        $nextPageUrl = "/{$locale}" . $nextPageUrl;
                    }
                @endphp
                <li class="page-item">
                    <a class="page-link" href="{{ $nextPageUrl }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif 