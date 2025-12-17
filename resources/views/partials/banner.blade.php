@php
    // Obter dados do banner mais recente através do controller
    $bannerData = \App\Http\Controllers\PartialsController::getLatestBannerData();
    $bannerMaisRecente = $bannerData['bannerMaisRecente'];
    $hasLatestBanner = $bannerData['hasLatestBanner'];
    $preloadUrl = $bannerData['preloadUrl'];
@endphp

{{-- Preload do banner mais recente para carregamento prioritário --}}
@if($preloadUrl)
    <link rel="preload" href="{{ $preloadUrl }}" as="image" fetchpriority="high">
@endif

<div data-v-fd333996="" class="bannerClass">
    @if($bannerMaisRecente)
        {{-- Banner dinâmico (com JavaScript/Swiper) --}}
        <section id="tranding" class="dynamic-banner-section">
            <div class="container">
                <div class="swiper tranding-slider">
                    <div class="swiper-wrapper">
                        {{-- Mostrar apenas o banner mais recente inicialmente --}}
                        <div class="swiper-slide tranding-slide">
                            @if (!empty($bannerMaisRecente->imagem))
                                <div class="tranding-slide-img">
                                    <a href="JavaScript: void(0);"
                                       onclick="{{ $bannerMaisRecente->link }}"
                                       class="slide-link">
                                        <img src="{{ $bannerMaisRecente->imagem }}"
                                             alt="Banner: Promoção especial"
                                             loading="eager"
                                             fetchpriority="high"
                                             decoding="sync" />
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="tranding-slider-control">
                        <div class="swiper-button-prev slider-arrow">
                            <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <div class="swiper-button-next slider-arrow">
                            <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg" style="transform: rotate(180deg);">
                                <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" fill="currentColor"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @else
        {{-- Mensagem quando não houver banners --}}
        <div class="banner-empty-state p-4 text-center">
            <div class="empty-state-content">
                <svg class="empty-state-icon" width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 16V8C21 6.89543 20.1046 6 19 6H5C3.89543 6 3 6.89543 3 8V16C3 17.1046 3.89543 18 5 18H19C20.1046 18 21 17.1046 21 16Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 10L12 13L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3 class="empty-state-title">Nenhum banner disponível</h3>
                <p class="empty-state-description">Os banners de promoção aparecerão aqui em breve.</p>
            </div>
        </div>
    @endif
</div>

{{-- Scripts para inicialização do banner --}}
<script>
    // Configurações globais para o banner
    window.bannerConfig = {
        autoplayDelay: {{ config('banner.autoplay_delay', 4000) }},
        transitionSpeed: {{ config('banner.transition_speed', 800) }},
        enableAutoplay: {{ config('banner.enable_autoplay', true) ? 'true' : 'false' }},
        hasLatestBanner: {{ $hasLatestBanner ? 'true' : 'false' }}
    };

    // Preload adicional da imagem do banner mais recente
    document.addEventListener('DOMContentLoaded', function() {
        @if($preloadUrl)
        // Força o carregamento prioritário da imagem do banner mais recente
        const img = new Image();
        img.src = '{{ $preloadUrl }}';
        img.fetchPriority = 'high';
        img.loading = 'eager';
        @endif
    });
</script>


