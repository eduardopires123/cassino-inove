@php
    $sidebarData = App\Http\Controllers\PartialsController::getCompleteSidebarData();
@endphp

<div data-v-581de4ad="" id="divSidebarMenu" class="sidebar no-scrollbar" style="--7e9dc732: {{ $sidebarData['sidebarHeight'] }}; --372e3822: 0px; transform: translateX(-100%);">
    <div data-v-581de4ad="" class="menu-wrapper">
        <div class="_5vWEW">
            <div class="l6oz0 mr-auto !max-h-[42px] !h-9 md:!h-12 !justify-start">
                <a aria-current="page" href="{{ $sidebarData['homeUrl'] }}" class="{{ $sidebarData['isHomeActive'] ? 'router-link-active router-link-exact-active bwSJI' : 'bwSJI' }}" aria-label="{{ $sidebarData['siteInfo']->name }}">
                    <img alt="{{ $sidebarData['siteInfo']->name }}" class="Ueilo" src="{{ asset($sidebarData['siteInfo']->logo) }}" />
                    <img alt="{{ $sidebarData['siteInfo']->name }}" class="j2x6J" src="{{ asset($sidebarData['siteInfo']->logo) }}" />
                </a>
            </div>
            <div class="W37on">
                <span class="inove-icon inove-icon--fill" id="btnCloseSidebar">
                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                            fill="currentColor"
                        ></path>
                    </svg>
                </span>
            </div>
        </div>
        <div class="_531PZ">
            <div class="IO8Xi">
                @foreach($sidebarData['topMenuItems'] as $item)
                    <a href="{{ $item['linkUrl'] }}" class="{{ $item['buttonClass'] }}">
                        <span class="xv-nQ"><small>{{ $item['translatedName'] }}</small> {{ $item['translatedSlug'] }}</span>
                        <span class="UBVQS"><span class="T2jJT">{!! $item['icone'] !!}</span></span>
                        <div class="MWIU3"></div>
                    </a>
                @endforeach
            </div>

            @foreach($sidebarData['categories'] as $category)
                <div class="d-yW8">
                    <div class="HvFmh">
                        <span class="font-bold text-sm text-sidebar-titles">{{ $category['translatedSlug'] }}</span>
                        <span class="text-sm text-sidebar-links">
                            <span class="inove-icon inove-icon--fill">
                                <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z" fill="currentColor"></path>
                                </svg>
                            </span>
                        </span>
                    </div>
                    <div class="overflow-hidden max-h-none pt-3">
                        @foreach($category['items'] as $item)
                            <a {!! $item['link'] !!} class="sdAXM">
                                <span class="inove-icon inove-icon--fill inove-icon--stroke sidebarIcon">
                                    {!! $item['icone'] !!}
                                </span>
                                <span>{{ $item['translatedName'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
            
            <div class="d-yW8">
                <a href="{{ $sidebarData['contactButtonUrl'] }}" rel="noopener noreferrer" class="sdAXM">
                    <span class="inove-icon inove-icon--fill sidebarIcon">
                        <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M342.5 214.7C342.6 214.6 342.4 214.8 342.5 214.7l128.1-128.1c12.5-12.5 12.5-32.75 0-45.25s-32.75-12.5-45.25 0L297.3 169.5c-.0742 .0742 .0742-.0762 0 0C317.1 178.1 333 194.9 342.5 214.7zM169.5 297.3C169.4 297.4 169.6 297.2 169.5 297.3l-128.1 128.1c-12.5 12.5-12.5 32.75 0 45.25C47.63 476.9 55.81 480 64 480s16.38-3.125 22.62-9.375l128.1-128.1c.0742-.0742-.0742 .0762 0 0C194.9 333 178.1 317.1 169.5 297.3zM342.5 297.3C342.4 297.2 342.6 297.4 342.5 297.3c-9.463 19.78-25.43 35.74-45.21 45.21c.0742 .0762-.0742-.0742 0 0l128.1 128.1C431.6 476.9 439.8 480 448 480s16.38-3.125 22.62-9.375c12.5-12.5 12.5-32.75 0-45.25L342.5 297.3zM86.63 41.38c-12.5-12.5-32.75-12.5-45.25 0s-12.5 32.75 0 45.25L169.5 214.7c.0742 .0742-.0762-.0742 0 0c9.463-19.78 25.43-35.74 45.21-45.21c-.0742-.0762 .0742 .0742 0 0L86.63 41.38z"
                                fill="currentColor"
                            ></path>
                            <path
                                d="M214.7 169.5C227.2 163.5 241.2 160 256 160s28.76 3.51 41.29 9.502c.0742-.0762-.0742 .0742 0 0l115.5-115.6C369.5 20.26 315.2 0 256 0S142.5 20.26 99.2 53.95L214.7 169.5C214.8 169.6 214.6 169.4 214.7 169.5zM169.5 297.3C163.5 284.8 160 270.8 160 256s3.51-28.76 9.502-41.29c-.0762-.0742 .0742 .0742 0 0L53.95 99.2C20.26 142.5 0 196.8 0 256s20.26 113.5 53.95 156.8L169.5 297.3C169.6 297.2 169.4 297.4 169.5 297.3zM458.1 99.2l-115.6 115.5c-.0742 .0742 .0762-.0742 0 0C348.5 227.2 352 241.2 352 256s-3.51 28.76-9.502 41.29c.0762 .0742-.0742-.0742 0 0l115.6 115.5C491.7 369.5 512 315.2 512 256S491.7 142.5 458.1 99.2zM297.3 342.5C284.8 348.5 270.8 352 256 352s-28.76-3.51-41.29-9.502c-.0742 .0762 .0742-.0742 0 0l-115.5 115.6C142.5 491.7 196.8 512 256 512s113.5-20.26 156.8-53.95L297.3 342.5C297.2 342.4 297.4 342.6 297.3 342.5z"
                                fill="currentColor"
                                opacity="0.4"
                            ></path>
                        </svg>
                    </span>
                    <span>{{ $sidebarData['translations']['help_center'] }}</span>
                </a>
                <a href="#" rel="noopener noreferrer" target="_blank" class="sdAXM">
                    <span class="inove-icon inove-icon--fill sidebarIcon">
                        <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M63.1 351.9C28.63 351.9 0 380.6 0 416s28.63 64 63.1 64s64.08-28.62 64.08-64S99.37 351.9 63.1 351.9z" fill="currentColor"></path>
                            <path
                                d="M32 32C14.33 32 0 46.31 0 64s14.33 32 32 32c194.1 0 352 157.9 352 352c0 17.69 14.33 32 32 32s32-14.31 32-32C448 218.6 261.4 32 32 32zM25.57 176.1c-13.16-.7187-24.66 9.156-25.51 22.37C-.8071 211.7 9.223 223.1 22.44 223.9c120.1 7.875 225.7 112.7 233.6 233.6C256.9 470.3 267.4 480 279.1 480c.5313 0 1.062-.0313 1.594-.0625c13.22-.8437 23.25-12.28 22.39-25.5C294.6 310.3 169.7 185.4 25.57 176.1z"
                                fill="currentColor"
                                opacity="0.4"
                            ></path>
                        </svg>
                    </span>
                    <span>{{ $sidebarData['translations']['blog'] }}</span>
                </a>
            </div>
            
            <div class="d-yW8">
                <div data-v-2b455ae2="" class="flex justify-center w-full relative">
                    <section data-v-3a2bd099="" data-v-2b455ae2="" class="listBoxWrapper w-full">
                        <button data-v-3a2bd099="" class="listBoxButton w-full justify-between" id="languageSwitcher">
                            <img data-v-3a2bd099="" alt="{{ $sidebarData['siteInfo']->name }}" loading="lazy" data-nuxt-img="" class="w-4" id="currentLanguageFlag" src="{{ asset('img/' . $sidebarData['currentLocale'] . '.png') }}" />
                            <span data-v-3a2bd099="" class="mr-auto" id="currentLanguageName">{{ $sidebarData['currentLanguageName'] }}</span>
                            <span data-v-3a2bd099="" class="nuxt-icon nuxt-icon--fill angle-down">
                                <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M201.4 374.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 306.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z" fill="currentColor"></path>
                                </svg>
                            </span>
                        </button>
                        <section data-v-3a2bd099="" class="listBoxOptions hidden" id="languageOptions">
                            <ul data-v-3a2bd099="">
                                @foreach($sidebarData['languageOptions'] as $language)
                                    <li data-v-3a2bd099="" value="{{ $language['locale'] }}">
                                        <a data-v-3a2bd099="" href="#" class="language-option languageItem" 
                                           data-lang="{{ $language['locale'] }}" 
                                           data-flag="{{ $language['flag'] }}" 
                                           data-name="{{ $language['name'] }}">
                                            <img data-v-3a2bd099="" alt="{{ $sidebarData['siteInfo']->name }}" loading="lazy" data-nuxt-img="" class="w-4" src="{{ asset('img/' . $language['flag']) }}" />
                                            <span data-v-3a2bd099="" class="mr-auto">{{ $language['name'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="inoveTooltipWrap" style="display: none;">
    <span id="inoveTooltip" class="toolnove"></span>
</div>

<style>
    .hidden {
        display: none !important;
    }
</style>

{{-- Carregar JavaScript otimizado da sidebar --}}
<script src="{{ asset('js/sidebar-optimized.js') }}" defer></script>
