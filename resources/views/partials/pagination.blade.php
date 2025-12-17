@php
    $paginationData = \App\Http\Controllers\PartialsController::processPaginationData(
        $items ?? $saques ?? null,
        $paginationId ?? 'pagination-default',
        $route ?? null,
        $targetId ?? 'content-container'
    );
@endphp

@if($paginationData['hasPagination'])
    <div data-v-302152a3="" class="paginationWrapper">
        <div data-v-302152a3="" class="paginationNavWrapper">
            <nav data-v-302152a3="" aria-label="Page navigation" class="paginationNav">
                <ul data-v-302152a3="" class="paginationNavList">
                    <!-- Primeira Página -->
                    <li data-v-302152a3="">
                        @if($paginationData['states']['onFirstPage'])
                            <button data-v-302152a3="" class="paginationBtn" disabled>
                                <span data-v-302152a3="">{{ $paginationData['translations']['first_page'] }}</span>
                            </button>
                        @else
                            <a data-v-302152a3="" href="{{ $paginationData['urls']['first'] }}" class="paginationBtn">
                                <span data-v-302152a3="">{{ $paginationData['translations']['first_page'] }}</span>
                            </a>
                        @endif
                    </li>
                    
                    <!-- Página Anterior -->
                    <li data-v-302152a3="" class="hiddenMobile">
                        @if($paginationData['states']['onFirstPage'])
                            <button data-v-302152a3="" class="paginationBtn" disabled>
                                <span data-v-302152a3="">{{ $paginationData['translations']['prev_page'] }}</span>
                            </button>
                        @else
                            <a data-v-302152a3="" href="{{ $paginationData['urls']['previous'] }}" class="paginationBtn">
                                <span data-v-302152a3="">{{ $paginationData['translations']['prev_page'] }}</span>
                            </a>
                        @endif
                    </li>
                    
                    <!-- Primeiro número, se necessário com ellipsis -->
                    @if($paginationData['states']['showFirstPage'])
                        <li data-v-302152a3="">
                            <a data-v-302152a3="" href="{{ $paginationData['urls']['first'] }}" class="paginationBtn">
                                <span data-v-302152a3="">1</span>
                            </a>
                        </li>
                        @if($paginationData['states']['showFirstEllipsis'])
                            <li data-v-302152a3="">
                                <button data-v-302152a3="" class="paginationBtn" disabled>
                                    <span data-v-302152a3="">...</span>
                                </button>
                            </li>
                        @endif
                    @endif
                    
                    <!-- Páginas dentro do intervalo -->
                    @foreach($paginationData['pageRange'] as $page)
                        <li data-v-302152a3="">
                            @if($page['isCurrent'])
                                <button data-v-302152a3="" class="paginationBtn active" disabled>
                                    <span data-v-302152a3="">{{ $page['number'] }}</span>
                                </button>
                            @else
                                <a data-v-302152a3="" href="{{ $page['url'] }}" class="paginationBtn">
                                    <span data-v-302152a3="">{{ $page['number'] }}</span>
                                </a>
                            @endif
                        </li>
                    @endforeach
                    
                    <!-- Último número, se necessário com ellipsis -->
                    @if($paginationData['states']['showLastPage'])
                        @if($paginationData['states']['showLastEllipsis'])
                            <li data-v-302152a3="">
                                <button data-v-302152a3="" class="paginationBtn" disabled>
                                    <span data-v-302152a3="">...</span>
                                </button>
                            </li>
                        @endif
                        <li data-v-302152a3="">
                            <a data-v-302152a3="" href="{{ $paginationData['urls']['last'] }}" class="paginationBtn">
                                <span data-v-302152a3="">{{ $paginationData['lastPage'] }}</span>
                            </a>
                        </li>
                    @endif
                    
                    <!-- Próxima Página -->
                    <li data-v-302152a3="" class="hiddenMobile">
                        @if($paginationData['states']['hasMorePages'])
                            <a data-v-302152a3="" href="{{ $paginationData['urls']['next'] }}" class="paginationBtn">
                                <span data-v-302152a3="">{{ $paginationData['translations']['next_page'] }}</span>
                            </a>
                        @else
                            <button data-v-302152a3="" class="paginationBtn" disabled>
                                <span data-v-302152a3="">{{ $paginationData['translations']['next_page'] }}</span>
                            </button>
                        @endif
                    </li>
                    
                    <!-- Última Página -->
                    <li data-v-302152a3="">
                        @if($paginationData['states']['hasMorePages'])
                            <a data-v-302152a3="" href="{{ $paginationData['urls']['last'] }}" class="paginationBtn paginationNext">
                                <span data-v-302152a3="">{{ $paginationData['translations']['last_page'] }}</span>
                            </a>
                        @else
                            <button data-v-302152a3="" class="paginationBtn paginationNext" disabled>
                                <span data-v-302152a3="">{{ $paginationData['translations']['last_page'] }}</span>
                            </button>
                        @endif
                    </li>
                </ul>
            </nav>
            
            <form data-v-302152a3="" class="paginationDirect" id="{{ $paginationData['paginationId'] }}-form" action="{{ $paginationData['urls']['current'] }}" method="GET">
                <!-- Preservar parâmetros de filtro existentes -->
                @foreach($paginationData['queryParams'] as $param)
                    <input type="hidden" name="{{ $param['name'] }}" value="{{ $param['value'] }}" />
                @endforeach
                
                <input data-v-302152a3="" class="paginationDirect-input" type="text" name="page" id="{{ $paginationData['paginationId'] }}-input" aria-label="{{ $paginationData['translations']['goto_page'] }}" />
                <button data-v-302152a3="" class="paginationBtn" type="submit">
                    <span data-v-302152a3="" class="nuxt-icon nuxt-icon--fill">
                        <svg height="1em" viewBox="0 0 576 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M148.5 497.9C129.8 516.6 99.38 516.6 80.64 497.9L46.06 463.3C27.31 444.6 27.31 414.2 46.06 395.4L316.7 124.7L419.2 227.2L148.5 497.9z" fill="currentColor"></path>
                            <path
                                d="M248.8 4.994C249.9 1.99 252.8 .0001 256 .0001C259.2 .0001 262.1 1.99 263.2 4.994L277.3 42.67L315 56.79C318 57.92 320 60.79 320 64C320 67.21 318 70.08 315 71.21L277.3 85.33L263.2 123C262.1 126 259.2 128 256 128C252.8 128 249.9 126 248.8 123L234.7 85.33L196.1 71.21C193.1 70.08 192 67.21 192 64C192 60.79 193.1 57.92 196.1 56.79L234.7 42.67L248.8 4.994zM529.9 116.5L419.2 227.2L379.6 187.6L484.6 82.58L461.4 59.31L356.3 164.3L316.7 124.7L427.4 14.06C446.2-4.686 476.6-4.686 495.3 14.06L529.9 48.64C548.6 67.38 548.6 97.78 529.9 116.5H529.9zM7.491 117.2L64 96L85.19 39.49C86.88 34.98 91.19 32 96 32C100.8 32 105.1 34.98 106.8 39.49L128 96L184.5 117.2C189 118.9 192 123.2 192 128C192 132.8 189 137.1 184.5 138.8L128 160L106.8 216.5C105.1 221 100.8 224 96 224C91.19 224 86.88 221 85.19 216.5L64 160L7.491 138.8C2.985 137.1 0 132.8 0 128C0 123.2 2.985 118.9 7.491 117.2zM359.5 373.2L416 352L437.2 295.5C438.9 290.1 443.2 288 448 288C452.8 288 457.1 290.1 458.8 295.5L480 352L536.5 373.2C541 374.9 544 379.2 544 384C544 388.8 541 393.1 536.5 394.8L480 416L458.8 472.5C457.1 477 452.8 480 448 480C443.2 480 438.9 477 437.2 472.5L416 416L359.5 394.8C354.1 393.1 352 388.8 352 384C352 379.2 354.1 374.9 359.5 373.2z"
                                fill="currentColor"
                                opacity="0.4"
                            ></path>
                        </svg>
                    </span>
                </button>
            </form>
        </div>
        <div data-v-302152a3="" class="paginationInfo">
            {{ $paginationData['translations']['showing'] }} {{ $paginationData['firstItem'] }} {{ $paginationData['translations']['to'] }} {{ $paginationData['lastItem'] }} {{ $paginationData['translations']['of'] }} {{ $paginationData['total'] }} {{ $paginationData['translations']['records'] }}
        </div>
    </div>
@endif