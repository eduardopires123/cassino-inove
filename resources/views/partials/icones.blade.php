<div class="rpneC uyA-x H3vO2">
    @if(isset($cachedData['icons_cache']) && is_iterable($cachedData['icons_cache']))
        @foreach($cachedData['icons_cache'] as $icon)
            <div class="peBY3" style="order: {{ $icon->ordem }};">
                @if ($icon->game_id)
                    <a href="JavaScript: void(0);" onclick="OpenGame('games', '{{ $icon->game_id }}');" class="LApSo">
                        <div class="_6nfK9">
                            <span class="nuxt-icon nuxt-icon--fill">
                                {!! $icon->svg !!}
                            </span>
                        </div>
                        <span class="_3dNkw">
                            {!! $icon->formatted_name !!}
                        </span>
                    </a>
                @elseif ($icon->link)
                    @if ($icon->is_route)
                        <a href="{{ $icon->route_url }}" class="LApSo">
                            <div class="_6nfK9">
                                <span class="nuxt-icon nuxt-icon--fill">
                                    {!! $icon->svg !!}
                                </span>
                            </div>
                            <span class="_3dNkw">
                                {!! $icon->formatted_name !!}
                            </span>
                        </a>
                    @elseif ($icon->is_js_function)
                        <a href="JavaScript: void(0);" onclick="{!! $icon->link !!}" class="LApSo">
                            <div class="_6nfK9">
                                <span class="nuxt-icon nuxt-icon--fill">
                                    {!! $icon->svg !!}
                                </span>
                            </div>
                            <span class="_3dNkw">
                                {!! $icon->formatted_name !!}
                            </span>
                        </a>
                    @else
                        <a href="{{ $icon->link }}" class="LApSo">
                            <div class="_6nfK9">
                                <span class="nuxt-icon nuxt-icon--fill">
                                    {!! $icon->svg !!}
                                </span>
                            </div>
                            <span class="_3dNkw">
                                {!! $icon->formatted_name !!}
                            </span>
                        </a>
                    @endif
                @else
                    <a href="javascript: void(0);" class="LApSo">
                        <div class="_6nfK9">
                            <span class="nuxt-icon nuxt-icon--fill">
                                {!! $icon->svg !!}
                            </span>
                        </div>
                        <span class="_3dNkw">
                            {!! $icon->formatted_name !!}
                        </span>
                    </a>
                @endif
            </div>
        @endforeach
    @endif
</div>
