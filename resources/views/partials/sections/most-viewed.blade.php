<div class="nM44t mb-4 md:mb-8">
                <div class="SM-j1">
                    <div class="h9HDs">
                        <h2 data-v-debf714a="" class="title flex items-center justify-center">
                            <p class="">{!! $homeSections->getSectionTitle('custom_title_most_viewed_games', __('menu.most_viewed_games')) !!}</p>
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
                    <a href="{{ route('cassino.todos-jogos') }}" class="sXdS9">
                        <span>{{ __('menu.view_all') }}</span>
                        <span class="inove-icon inove-icon--fill">
                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z" fill="currentColor"></path>
                    </svg>
                </span>
                    </a>
                </div>
                <div class="w-full">
                    <div class="-JVa3 Vulse EEtS9" style="--620ba053: calc((100% - 110px) / 6); --063993a6: 22px; --8ec19218: calc((100% - 110px) / 6); --543ef9ea: 0;" id="mostViewedGamesSlider">
                        <div class="rpneC uyA-x H3vO2">
                            @foreach($cachedData['mostViewedGames_cache'] as $index => $game)
                                <div class="peBY3 Jj-AP swiper-slide">
                                    <a href="JavaScript: void(0);" onclick="OpenGame('games', '{{ $game->id }}');" class="s3HXA">
                                        <div class="u3Qxq">
                                            <div class="g-hw5">
                                                <img alt="{{ $game->name }}" class="vTFYb" src="{{ $game->image_url ?? $game->image }}" />
                                            </div>
                                            <div class="hzP6t">
                                                <span class="phlJe">{{ $game->name }}</span>
                                                <span class="liQBm">{{ $game->provider_name }}</span>
                                            </div>
                                            <section class="bBtlK">
                                    <span class="Oe7Pi">
                                        <span class="inove-icon inove-icon--fill">
                                            <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <span>{{ __('menu.play') }}</span>
                                    </span>
                                            </section>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>