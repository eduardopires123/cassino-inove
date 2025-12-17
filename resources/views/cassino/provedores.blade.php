@extends('layouts.app')
@section('content')
<style>
.providers-content {
    display: grid;
    gap: 12px;
    width: 100%;
    padding:5px;
}
.tXgOm {
    grid-template-columns: repeat(var(--45b10934), minmax(1, 1fr))!important;
}

.provider-card {
    width: 100%;
}

@media (max-width: 767px) {
    .providers-content {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 768px) and (max-width: 1099px) {
    .providers-content {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (min-width: 1100px) {
    .providers-content {
        grid-template-columns: repeat(6, 1fr);
    }
}
</style>

<section data-v-ab8dac3b="" class="providers-page">
    <header data-v-ab8dac3b="" class="providers-filters">
        <div data-v-ab8dac3b="">
            <!---->
            <div class="UTJUl">
                <span class="nuxt-icon nuxt-icon--fill text-texts">
                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M500.3 443.7l-119.7-119.7c-15.03 22.3-34.26 41.54-56.57 56.57l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7z" fill="currentColor"></path>
                        <path
                            d="M207.1 0C93.12 0-.0002 93.13-.0002 208S93.12 416 207.1 416s208-93.13 208-208S322.9 0 207.1 0zM207.1 336c-70.58 0-128-57.42-128-128c0-70.58 57.42-128 128-128s128 57.42 128 128C335.1 278.6 278.6 336 207.1 336z"
                            fill="currentColor"
                            opacity="0.4"
                        ></path>
                    </svg>
                </span>
                <input id="search-input" class="_6z1L2" name="search" placeholder="{{ __('providers.search_provider') }}" type="text" />
                <!----><!---->
            </div>
        </div>
    </header>
    <div data-v-ab8dac3b="" class="mt-6">
        <h1 data-v-ab8dac3b="" class="text-texts font-semibold text-xs lg:text-base">{{ __('providers.all_providers') }}</h1>
        <div data-v-ab8dac3b="" class="providers-content mb-10 mt-2">
            @php
                $providers = DB::table('providers')
                    ->select('id', 'name', 'name_home', 'img')
                    ->where('active', 1)
                    ->get()
                    ->map(function($provider) {
                        // Se name_home tem valor, usar name_home, senão usar name
                        $provider->display_name = !empty($provider->name_home) ? $provider->name_home : $provider->name;
                        return $provider;
                    });
            @endphp
            
            @foreach($providers as $provider)
                @php
                    $gameCount = DB::table('games_api')
                        ->where('games_api.provider_id', $provider->id)
                        ->where('games_api.status', 1)
                        ->count('games_api.id');
                @endphp
                <div data-v-ab8dac3b="" class="nM44t provider-card">
                    <!---->
                    <div class="tXgOm">
                        <a href="/cassino/provider/{{ strtolower(str_replace(' ', '', $provider->name)) }}" class="hZm-w">
                            <div class="u3Qxq AgLBc">
                                <div class="g-hw5 AgLBc">
                                    <img alt="{{ $provider->display_name }}" class="vTFYb" src="{{ $provider->img }}" srcset="{{ $provider->img }}"/>
                                    <span class="jDbkE">{{ $gameCount }}</span>
                                </div>
                                <section aria-label="{{ $provider->display_name }}" class="bBtlK"></section>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
function updateGridColumns() {
    const providerCards = document.querySelectorAll('.provider-card');
    const width = window.innerWidth;
    
    providerCards.forEach(card => {
        if (width >= 1100) { // Desktop
            card.style.setProperty('--45b10934', '6');
        } else if (width >= 768) { // Tablet
            card.style.setProperty('--45b10934', '4');
        } else { // Mobile
            card.style.setProperty('--45b10934', '3');
        }
    });
}

// Executar quando a página carregar
document.addEventListener('DOMContentLoaded', updateGridColumns);

// Executar quando a janela for redimensionada
window.addEventListener('resize', updateGridColumns);
</script>
@endsection