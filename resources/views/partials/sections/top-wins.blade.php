<div class="G0Y3O">
                <section class="_6AwC0">
                    <img alt="Trophy icon" data-nuxt-img="" src="{{ asset('img/trophy.webp') }}">
                    <span class="">{!! $homeSections->getSectionTitle('custom_title_top_wins', __('menu.top_wins_today')) !!}</span>
                </section>

                <div class="SBFb1">
                    <div class="-JVa3 Vulse EEtS9" style="--47a21ed8: 0px; --081bd0e8: 10px; --57a30296: auto; --2c3a336a: 1;">
                        <div class="rpneC uyA-x IRS8a H3vO2" id="top-wins-carousel">
                            @if(!empty($cachedData['top_wins_cache']) && $cachedData['top_wins_cache']->count() > 0)
                                @foreach($cachedData['top_wins_cache'] as $win)
                                    <div class="peBY3" style="order: {{ $loop->index + 1 }};">
                                        <div class="WdwGH">
                                            <a class="yoJvT" href="JavaScript: void(0);" onclick="{{ !empty($win->game_id) ? "OpenGame('games', '" . $win->game_id . "')" : '' }}">
                                                <img alt="{{ $win->game_name ?? 'Jogo' }}" class="M5ltJ" src="{{ $win->game_image }}">
                                                <section class="nLKq0">
                                                    <h1 class="gTUMB">{{ substr(($win->masked_user_name ?? 'Jogador'), 0, 3) . '****' }}</h1>
                                                    <span class="hcN5S">{{ $win->game_name ?? 'Jogo' }}</span>
                                                    <span class="Ex86b">R$&nbsp;{{ number_format(($win->amount * 20), 2, ',', '.') }}</span>
                                                </section>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="peBY3" style="order: 1;">
                                    <div class="WdwGH">
                                        <a class="yoJvT">
                                            <section class="nLKq0">
                                                <h1 class="gTUMB">Jog****</h1>
                                                <span class="hcN5S">Carregando maiores ganhos...</span>
                                                <span class="Ex86b">R$&nbsp;0,00</span>
                                            </section>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>