<?php
// Check if we have banners before rendering the carousel
$hasBanners = isset($banners2) && count($banners2) > 0;
?>

@if(isset($banners2) && count($banners2) > 0)
<!-- Scripts e Estilos do Carrossel (carregados apenas quando necessário) -->
<link rel="stylesheet" href="{{ asset('css/esportes-banners.css') }}">
<script src="{{ asset('js/esportes-banners.js') }}" defer></script>

<div class="master_fe_Carousel_featuredMatches master_fe_ViewStyles_desktopEuropeanFeaturedMatches master_fe_Carousel_fadeRight banners-carousel">
    <ul class="master_fe_Carousel_list">
        @foreach($banners2 as $key => $banner)
        <li class="banner-slide {{ $key === 0 ? 'active' : '' }}" {{ $key === 0 ? 'data-snappoint="true"' : '' }}>
            <button data-double="false" type="button" class="master_fe_Banner_banner" data-rac="" id="react-aria-{{ $key }}">
                @if($banner->link_url)
                <a href="{{ $banner->link_url }}" class="banner-link">
                @endif
                <img
                    src="{{ $banner->image_url }}"
                    class="master_fe_Banner_image"
                    alt="{{ $banner->title }}"
                    loading="lazy"
                />
                @if($banner->link_url)
                </a>
                @endif
            </button>
        </li>
        @endforeach
    </ul>
    @if(count($banners2) > 1)
    <div class="master_fe_Carousel_btnPrev">
        <button class="master_fe_ArrowButton_carouselArrow master_fe_ArrowButton_carouselArrowsimple">
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transform: rotate(90deg);">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
    </div>
    <div class="master_fe_Carousel_btnNext">
        <button class="master_fe_ArrowButton_carouselArrow master_fe_ArrowButton_carouselArrowsimple master_fe_ArrowButton_carouselArrowNext">
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transform: rotate(-90deg);">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
    </div>
    @endif
</div>

<script>
// Script de inicialização que só será executado quando o banner ficar visível
document.addEventListener('DOMContentLoaded', function() {
    // Função para inicializar o carrossel quando ele se tornar visível
    function checkBannerVisibility() {
        const bannerCarousel = document.querySelector('.banners-carousel');
        if (bannerCarousel && bannerCarousel.offsetParent !== null) {
            // Se o carrossel estiver visível, inicializar seus recursos
            if (typeof initializeBannerCarousel === 'function') {
                initializeBannerCarousel();
            }
            // Parar de verificar após inicializado
            clearInterval(checkInterval);
        }
    }
    
    // Verificar periodicamente se o banner está visível
    const checkInterval = setInterval(checkBannerVisibility, 500);
    
    // Também verificar quando o DOM muda (por exemplo, quando classes são removidas)
    const observer = new MutationObserver(checkBannerVisibility);
    const bannerSection = document.getElementById('banners-section');
    if (bannerSection) {
        observer.observe(bannerSection, { 
            attributes: true, 
            attributeFilter: ['class', 'style'] 
        });
    }
});
</script>
@endif