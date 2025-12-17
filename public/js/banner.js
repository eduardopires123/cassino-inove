// Banner dinâmico com Swiper - Carregamento Otimizado
(function() {
    'use strict';
    
    let allBannersData = null;
    let swiperInstance = null;
    
    // Cache para dados dos banners
    const CACHE_KEY = 'banner_slides_cache';
    const CACHE_DURATION = 5 * 60 * 1000; // 5 minutos
    
    // Função para obter dados do cache
    function getCachedData() {
        try {
            const cached = localStorage.getItem(CACHE_KEY);
            if (cached) {
                const { data, timestamp } = JSON.parse(cached);
                if (Date.now() - timestamp < CACHE_DURATION) {
                    return data;
                }
            }
        } catch (e) {
            console.warn('Erro ao ler cache:', e);
        }
        return null;
    }
    
    // Função para salvar dados no cache
    function setCachedData(data) {
        try {
            localStorage.setItem(CACHE_KEY, JSON.stringify({
                data: data,
                timestamp: Date.now()
            }));
        } catch (e) {
            console.warn('Erro ao salvar cache:', e);
        }
    }
    
    // Função para mostrar loading placeholder
    function showLoadingPlaceholder() {
        const swiperWrapper = document.querySelector('.swiper-wrapper');
        if (!swiperWrapper) return;
        
        swiperWrapper.innerHTML = `
            <div class="swiper-slide tranding-slide loading-slide">
                <div class="tranding-slide-img">
                    <div class="banner-loading-placeholder">
                        <div class="loading-spinner"></div>
                    </div>
                </div>
            </div>
        `;
        
        // Adicionar estilos do loading
        if (!document.getElementById('banner-loading-styles')) {
            const style = document.createElement('style');
            style.id = 'banner-loading-styles';
            style.textContent = `
                .banner-loading-placeholder {
                    width: 100%;
                    height: 368px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: transparent;
                }
                
                .loading-spinner {
                    width: 40px;
                    height: 40px;
                    border: 3px solid rgba(0, 0, 0, 0.1);
                    border-top: 3px solid var(--primary-color);
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }
                
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    // Função para pré-carregar imagens
    function preloadImages(banners) {
        return Promise.all(
            banners.map(banner => {
                return new Promise((resolve) => {
                    if (!banner.imagem) {
                        resolve();
                        return;
                    }
                    
                    const img = new Image();
                    img.onload = () => resolve();
                    img.onerror = () => resolve(); // Continuar mesmo se uma imagem falhar
                    img.src = banner.imagem;
                    
                    // Timeout para não travar o carregamento
                    setTimeout(() => resolve(), 2000);
                });
            })
        );
    }
    
    // Função para obter dados dos banners via AJAX
    function fetchBannersData() {
        return new Promise((resolve, reject) => {
            // Tentar cache primeiro
            const cachedData = getCachedData();
            if (cachedData) {
                
                allBannersData = cachedData;
                resolve(cachedData);
                return;
            }
            
            // Buscar dados do servidor
            fetch('/banners/slide')
                .then(response => response.json())
                .then(data => {
                    allBannersData = data;
                    setCachedData(data); // Salvar no cache
                    resolve(data);
                })
                .catch(error => {
                    console.error('Erro ao carregar banners:', error);
                    reject(error);
                });
        });
    }
    
    // Função para substituir o conteúdo com todos os banners
    function replaceWithAllBanners() {
        if (!allBannersData || allBannersData.length === 0) return;
        
        const sliderContainer = document.querySelector('.tranding-slider');
        const swiperWrapper = sliderContainer?.querySelector('.swiper-wrapper');
        
        if (!swiperWrapper) return;
        
        // Limpar conteúdo atual
        swiperWrapper.innerHTML = '';
        
        // Adicionar todos os banners
        allBannersData.forEach((banner, index) => {
            if (banner.imagem) {
                const slideHtml = `
                    <div class="swiper-slide tranding-slide">
                        <div class="tranding-slide-img">
                            <a href="JavaScript: void(0);" 
                               onclick="${banner.link || 'void(0)'}"
                               class="slide-link">
                                <img src="${banner.imagem}" 
                                     alt="Banner ${index + 1}: Promoção especial"
                                     loading="eager"
                                     fetchpriority="high"
                                     decoding="sync" />
                            </a>
                        </div>
                    </div>
                `;
                swiperWrapper.insertAdjacentHTML('beforeend', slideHtml);
            }
        });
        
        
    }
    
    // Carregar Swiper de forma assíncrona
    function loadSwiperAsync() {
        return new Promise((resolve, reject) => {
            // Verificar se o Swiper já está carregado
            if (typeof Swiper !== 'undefined') {
                resolve();
                return;
            }
            
            // Carregar Swiper CSS se não estiver presente
            if (!document.querySelector('link[href*="swiper"]')) {
                const swiperCSS = document.createElement('link');
                swiperCSS.rel = 'stylesheet';
                swiperCSS.href = 'https://unpkg.com/swiper@8/swiper-bundle.min.css';
                document.head.appendChild(swiperCSS);
            }
            
            // Carregar Swiper JS se não estiver presente
            if (!document.querySelector('script[src*="swiper"]')) {
                const swiperScript = document.createElement('script');
                swiperScript.src = 'https://unpkg.com/swiper@8/swiper-bundle.min.js';
                swiperScript.onload = () => resolve();
                swiperScript.onerror = () => reject(new Error('Falha ao carregar Swiper'));
                document.head.appendChild(swiperScript);
            } else {
                resolve();
            }
        });
    }
    
    // Função para carregar imagens
    function loadImages() {
        const images = document.querySelectorAll('.tranding-slide img');
        images.forEach(img => {
            if (img.complete) {
                img.style.opacity = '1';
                img.classList.add('loaded');
            } else {
                img.addEventListener('load', function() {
                    this.style.opacity = '1';
                    this.classList.add('loaded');
                });
            }
        });
    }
    
    // Função para controlar visibilidade das setas fixas
    function handleArrowsVisibility() {
        const bannerSection = document.querySelector('#tranding');
        const arrows = document.querySelectorAll('.tranding-slider-control .slider-arrow');
        
        if (!bannerSection || !arrows.length) return;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                arrows.forEach(arrow => {
                    if (entry.isIntersecting) {
                        arrow.style.display = 'flex';
                    } else {
                        arrow.style.display = 'none';
                    }
                });
            });
        }, {
            threshold: 0.1
        });
        
        observer.observe(bannerSection);
    }
    
    // Função para otimizar performance
    function optimizePerformance() {
        const slides = document.querySelectorAll('.tranding-slide');
        slides.forEach(slide => {
            slide.style.willChange = 'transform';
        });
    }
    
    // Função para atualizar estilos dos slides
    function updateSlideStyles(swiper) {
        const slides = swiper.slides;
        const activeIndex = swiper.realIndex;
        
        slides.forEach((slide, index) => {
            const slideIndex = parseInt(slide.getAttribute('data-swiper-slide-index')) || index;
            const isActive = slideIndex === activeIndex;
            
            if (isActive) {
                slide.style.zIndex = '10';
                slide.style.opacity = '1';
                slide.style.transform = slide.style.transform + ' scale(1.05)';
            } else {
                slide.style.zIndex = '1';
                slide.style.opacity = '1';
            }
        });
    }
    
    // Função para adicionar efeito coverflow customizado
    function addCustomCoverflowEffect(swiper) {
        const slides = swiper.slides;
        const activeIndex = swiper.activeIndex;
        
        slides.forEach((slide, index) => {
            const offset = index - activeIndex;
            let rotateY = 0;
            let translateX = 0;
            let translateZ = 0;
            let scale = 1;
            let blur = 0;
            
            if (offset === 0) {
                rotateY = 0;
                translateX = 0;
                translateZ = 0;
                scale = 1;
                blur = 0;
            } else if (Math.abs(offset) === 1) {
                rotateY = offset > 0 ? -25 : 25;
                translateX = offset * 80;
                translateZ = -150;
                scale = 0.85;
                blur = 6;
            } else {
                rotateY = offset > 0 ? -35 : 35;
                translateX = offset * 120;
                translateZ = -250;
                scale = 0.7;
                blur = 10;
            }
            
            slide.style.transform = `
                translateX(${translateX}px) 
                translateZ(${translateZ}px) 
                rotateY(${rotateY}deg) 
                scale(${scale})
            `;
            slide.style.filter = `blur(${blur}px)`;
            slide.style.zIndex = Math.abs(offset) === 0 ? 10 : 10 - Math.abs(offset);
        });
    }
    
    // Inicializar Swiper dinâmico
    function initializeDynamicSwiper() {
        if (swiperInstance) {
            swiperInstance.destroy(true, true);
        }
        
        const sliderContainer = document.querySelector('.tranding-slider');
        if (!sliderContainer) {
            console.error('Container do slider não encontrado');
            return;
        }
        
        // Configuração do Swiper com efeito Coverflow
        swiperInstance = new Swiper('.tranding-slider', {
            effect: 'coverflow',
            grabCursor: true,
            centeredSlides: true,
            loop: true,
            slidesPerView: 'auto',
            spaceBetween: -80,
            initialSlide: 1,
            coverflowEffect: {
                rotate: 25,
                stretch: -40,
                depth: 250,
                modifier: 1.2,
                slideShadows: false,
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },
            a11y: {
                prevSlideMessage: 'Slide anterior',
                nextSlideMessage: 'Próximo slide',
                firstSlideMessage: 'Este é o primeiro slide',
                lastSlideMessage: 'Este é o último slide',
            },
            on: {
                init: function() {
                    
                    loadImages();
                    optimizePerformance();
                    updateSlideStyles(this);
                    addCustomCoverflowEffect(this);
                    handleArrowsVisibility();
                    
                    // Remover loading placeholder
                    const loadingStyles = document.getElementById('banner-loading-styles');
                    if (loadingStyles) {
                        loadingStyles.remove();
                    }
                    
                    // Reativar opacidade total das setas
                    const arrows = document.querySelectorAll('.tranding-slider-control .slider-arrow');
                    arrows.forEach(arrow => {
                        arrow.style.display = 'flex';
                        arrow.style.opacity = '0.7';
                    });
                },
                slideChange: function() {
                    updateSlideStyles(this);
                    addCustomCoverflowEffect(this);
                },
                progress: function() {
                    updateSlideStyles(this);
                    addCustomCoverflowEffect(this);
                },
                touchStart: function() {
                    this.autoplay.stop();
                },
                touchEnd: function() {
                    this.autoplay.start();
                },
                mouseEnter: function() {
                    this.autoplay.stop();
                },
                mouseLeave: function() {
                    this.autoplay.start();
                }
            }
        });
        
        // Configurar eventos de mouse para pausar autoplay
        const trandingSection = document.querySelector('#tranding');
        if (trandingSection && swiperInstance) {
            trandingSection.addEventListener('mouseenter', function() {
                swiperInstance.autoplay.stop();
            });
            
            trandingSection.addEventListener('mouseleave', function() {
                swiperInstance.autoplay.start();
            });
        }
        
        // Suporte a teclado
        document.addEventListener('keydown', function(e) {
            if (!swiperInstance) return;
            
            switch(e.key) {
                case 'ArrowLeft':
                    swiperInstance.slidePrev();
                    break;
                case 'ArrowRight':
                    swiperInstance.slideNext();
                    break;
            }
        });
        
        // Redimensionamento responsivo
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (swiperInstance) {
                    swiperInstance.update();
                }
            }, 250);
        });
        
        return swiperInstance;
    }
    
    // Função principal de inicialização
    function initBanner() {
        const dynamicBanner = document.getElementById('tranding');
        
        if (!dynamicBanner) {
            console.error('Banner dinâmico não encontrado');
            return;
        }
        
        
        
        // Mostrar loading placeholder imediatamente
        showLoadingPlaceholder();
        
        // Carregar Swiper primeiro (mais rápido)
        loadSwiperAsync()
            .then(() => {
                // Depois carregar dados dos banners
                return fetchBannersData();
            })
            .then((data) => {
                // Pré-carregar imagens em paralelo
                const preloadPromise = preloadImages(data);
                
                // Substituir conteúdo imediatamente
                replaceWithAllBanners();
                
                // Inicializar Swiper sem esperar o preload terminar
                initializeDynamicSwiper();
                
                // Marcar que os scripts de banner carregaram
                window.bannerScriptsLoaded = true;
                
                
                
                // Aguardar preload em background
                return preloadPromise;
            })
            .then(() => {
                
            })
            .catch((error) => {
                console.error('Erro ao carregar sistema de banners:', error);
                // Manter funcionalidade básica mesmo com erro
                handleArrowsVisibility();
            });
    }
    
    // Inicializar quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBanner);
    } else {
        // DOM já está pronto
        initBanner();
    }
    
})();