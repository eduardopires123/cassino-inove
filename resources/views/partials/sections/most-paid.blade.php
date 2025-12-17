<div class="nM44t mb-4 md:mb-8">
            <div class="SM-j1">
                <!-- mais pagou hoje -->
                <div data-v-25a087dc="" class="recommended_title">
                    <h2 data-v-debf714a="" data-v-25a087dc="" class="title flex items-center justify-center">
                        {!! $homeSections->getSectionTitle('custom_title_most_paid', __('menu.most_paid_today')) !!}
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
                <a href="{{ route('cassino.todos-jogos') }}" class="sXdS9"><span>Ver todos</span><span class="nuxt-icon nuxt-icon--fill"><svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
              <path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z" fill="currentColor"></path>
            </svg></span></a>
            </div>
            <!-- Mais pagou hoje -->
            <div data-v-25a087dc="" class="row_imgs" style="margin-bottom: 0;">
                <div data-v-25a087dc="" class="-JVa3 Vulse EEtS9" style="--620ba053: calc((100% - 20px) / 3)!important; --063993a6: 10px!important; --8ec19218: calc((100% - 20px) / 3)!important; --543ef9ea: 0!important;">
                    <div class="rpneC uyA-x H3vO2">
                        @foreach($cachedData['banners_mini_cache'] as $banner)
                            @if($banner->mobile === 'não')
                                @php
                                    $bannerLink = isset($banner->link) ? json_decode($banner->link) : null;
                                    $param = ($bannerLink && isset($bannerLink->param)) ? $bannerLink->param : '0';
                                @endphp
                                <div class="peBY3 UCo9l" style="order: {{ $banner->ordem }};" id="banner-desktop">
                                    <a data-v-25a087dc="" href="JavaScript: void(0);" onclick="{{$banner->link}}" class="rounded-md shadow-md overflow-hidden flex items-center justify-center banner-link">
                                        <img data-v-25a087dc="" alt="Banner-desktop" class="banner-desktop object-contain object-center" src="{{ $banner->imagem }}" />
                                    </a>
                                </div>
                            @endif
                        @endforeach

                        @foreach($cachedData['banners_mini_cache'] as $banner)
                            @if($banner->mobile === 'sim')
                                @php
                                    $bannerLink = isset($banner->link) ? json_decode($banner->link) : null;
                                    $param = ($bannerLink && isset($bannerLink->param)) ? $bannerLink->param : '0';
                                @endphp
                                <div class="peBY3 UCo9l" id="banner-mobile" style="order: {{ $banner->ordem }};">
                                    <a data-v-25a087dc="" href="JavaScript: void(0);" onclick="{{$banner->link}}" class="rounded-md shadow-md overflow-hidden flex items-center justify-center banner-link">
                                        <img data-v-25a087dc="" alt="Banner-mobile" class="banner-mobile object-contain object-center" src="{{ $banner->imagem }}" />
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>
        </div>