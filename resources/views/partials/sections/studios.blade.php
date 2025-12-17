<div class="nM44t mb-4 md:mb-8">
            <div class="SM-j1">
                <div class="h9HDs">
                    <h2 data-v-debf714a="" class="title flex items-center justify-center">
                        <p class="" style="text-transform: uppercase;">{{ $homeSections->getSectionTitle('custom_title_studios', __('menu.studios')) }}</p>
                    </h2>
                </div>
                <div class="relative group flex items-center">
                <span class="inove-icon inove-icon--fill nQro9 cursor-pointer">
                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" fill="currentColor"></path>
                    </svg>
                </span>
                    <span class="inove-icon inove-icon--fill nQro9 cursor-pointer">
                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z" fill="currentColor"></path>
                    </svg>
                </span>
                </div>
                <a href="{{ route('cassino.provedores') }}" class="sXdS9">
                    <span>{{ __('menu.view_all') }}</span>
                    <span class="inove-icon inove-icon--fill">
                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z" fill="currentColor"></path>
                    </svg>
                </span>
                </a>
            </div>
            <div class="w-full">
                <div class="-JVa3 Vulse EEtS9" style="--620ba053: calc((100% - 110px) / 6); --063993a6: 22px; --8ec19218: calc((100% - 110px) / 6); --543ef9ea: 0;" id="providerSlider">
                    <div class="rpneC uyA-x H3vO2">
                        @foreach($cachedData['providers_cache'] as $provider)
                            <div class="peBY3 Jj-AP swiper-slide">
                                <a href="{{ route('cassino.provider', ['provider' => strtolower(str_replace(' ', '', $provider->name))]) }}" class="">
                                    <div class="u3Qxq AgLBc">
                                        <div class="g-hw5 AgLBc">
                                            <img alt="{{ str_replace('_', ' ', $provider->name) }}" class="vTFYb" src="{{ $provider->img }}" />
                                        </div>
                                        <section aria-label="{{ str_replace('_', ' ', $provider->name) }}" class="bBtlK"></section>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>