@extends('layouts.app')

@section('content')
<section data-v-1d35be9f="" id="casino">
    <!---->
    <section data-v-9dae45d3="" data-v-1d35be9f="" id="frame_game" class="">
        <!----><!---->
        <header data-v-9dae45d3="" class="game-header">
            <h6 data-v-9dae45d3="">{{ $name }}</h6>
            <button data-v-9dae45d3="" class="close_full">
                <span data-v-9dae45d3="" class="nuxt-icon nuxt-icon--fill">
                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                            fill="currentColor"
                        ></path>
                    </svg>
                </span>
            </button>
        </header>
        <iframe id="gameIframe" src="{{$gameURL}}" height="100%" width="100%" title="{{ $name }}" allow="fullscreen"></iframe>
        <!----><!---->
    </section>
    <div data-v-bd788567="" data-v-1d35be9f="" id="game_details" >
        <div data-v-bd788567="" class="w-full">
            <h1 data-v-bd788567="">{{ $name }}</h1>
            <p data-v-bd788567="" class="provider">{{ trim(preg_replace('/\b(ORIGINAL|OFICIAL)\b/i', '', $provider)) }}</p>
        </div>
        <div data-v-bd788567="" class="casino-buttons">
            <button data-v-bd788567="" class="casino-buttons__fullscreen" onclick="JogaFullMobile();" title="{{ __('game.fullscreen_button') }}">
                <span data-v-bd788567="" class="nuxt-icon nuxt-icon--fill">
                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M128 64H32C14.31 64 0 78.31 0 96v96c0 17.69 14.31 32 32 32s32-14.31 32-32V128h64c17.69 0 32-14.31 32-32S145.7 64 128 64zM480 288c-17.69 0-32 14.31-32 32v64h-64c-17.69 0-32 14.31-32 32s14.31 32 32 32h96c17.69 0 32-14.31 32-32v-96C512 302.3 497.7 288 480 288z"
                            fill="currentColor"
                        ></path>
                        <path
                            d="M480 64h-96c-17.69 0-32 14.31-32 32s14.31 32 32 32h64v64c0 17.69 14.31 32 32 32s32-14.31 32-32V96C512 78.31 497.7 64 480 64zM128 384H64v-64c0-17.69-14.31-32-32-32s-32 14.31-32 32v96c0 17.69 14.31 32 32 32h96c17.69 0 32-14.31 32-32S145.7 384 128 384z"
                            fill="currentColor"
                            opacity="0.4"
                        ></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
    <div data-v-1d35be9f="" class="nM44t mb-4 md:mb-8" style="--d879e6ea: 16px; --45b10934: 4;">
        <div class="SM-j1">
            <div class="h9HDs">
                <h2 data-v-debf714a="" class="title flex items-center justify-center">
                    <!---->
                    <p class="">{{ __('game.related_games') }}</p>
                </h2>
            </div>
        </div>
        <div class="w-full">
            <div class="-JVa3 Vulse EEtS9" style="--620ba053: calc((100% - 48px) / 4); --063993a6: 16px; --8ec19218: calc((100% - 48px) / 4); --543ef9ea: 0;">
                <div class="rpneC uyA-x H3vO2">
                    @foreach($mostViewedGames as $index => $game)
                    @if(isset($game->status) ? $game->status == 1 : true)
                    <div class="peBY3 Jj-AP" style="order: {{ $loop->index + 1 }};">
                        <a href="JavaScript: void(0);" onclick="OpenGame('games', '{{ $game->id }}');" class="s3HXA">
                            <div class="u3Qxq">
                                <div class="g-hw5">
                                    <img alt="{{ $game->name }}" class="vTFYb" src="{{ $game->image_url }}" />
                                </div>
                                <div class="hzP6t">
                                    <span class="phlJe">{{ $game->name }}</span>
                                    <span class="liQBm">{{ $game->provider_name }}</span>
                                </div>
                                <section class="bBtlK">
                                    <span class="Oe7Pi">
                                        <span class="nuxt-icon nuxt-icon--fill">
                                            <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <span>{{ __('game.play_button') }}</span>
                                    </span>
                                </section>
                            </div>
                        </a>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection 