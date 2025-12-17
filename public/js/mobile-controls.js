document.addEventListener('DOMContentLoaded', function() {
    // Detectar se é um dispositivo móvel
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

    if (isMobile) {
        // Prevenir double-tap zoom
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function(event) {
            const now = Date.now();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, { passive: false });

        // Prevenir zoom em gesturestart (específico para iOS)
        document.addEventListener('gesturestart', function(event) {
            event.preventDefault();
        }, { passive: false });
        
        // Prevenir scroll horizontal
        let touchStartX;
        
        document.addEventListener('touchstart', function(event) {
            touchStartX = event.touches[0].clientX;
        }, { passive: true });
        
        document.addEventListener('touchmove', function(event) {
            if (!touchStartX) {
                return;
            }
            
            const touchCurrentX = event.touches[0].clientX;
            const diffX = touchStartX - touchCurrentX;
            
            // Se a tentativa de movimento for horizontal
            if (Math.abs(diffX) > 10) {
                event.stopPropagation();
            }
        }, { passive: true });
    }

    // ===== CONTROLE DE FILTROS MOBILE =====
    
    // Seleção de elementos
    const filterProvidersBtn = document.getElementById('filter-providers');
    const filterSearchBtn = document.getElementById('filter-search');
    const casinoFilters = document.querySelector('.casino-filters');
    const casinoSearch = document.querySelector('.casino-search');
    const filterButtons = document.querySelector('.casino-filters__buttons');

    // Verificar se estamos em modo mobile
    function isMobileScreen() {
        return window.innerWidth < 768;
    }

    // SVG para o botão de fechar
    const closeSvg = `<svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
        <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1a12 12 0 0 1 0 17L338 377.6a12 12 0 0 1-17 0L256 312l-65.1 65.6a12 12 0 0 1-17 0L134.4 338a12 12 0 0 1 0-17l65.6-65-65.6-65.1a12 12 0 0 1 0-17l39.6-39.6a12 12 0 0 1 17 0l65 65.7 65.1-65.6a12 12 0 0 1 17 0l39.6 39.6a12 12 0 0 1 0 17L312 256z" fill="currentColor" opacity="0.4"></path>
        <path d="M377.6 321.1a12 12 0 0 1 0 17L338 377.6a12 12 0 0 1-17 0L256 312l-65.1 65.6a12 12 0 0 1-17 0L134.4 338a12 12 0 0 1 0-17l65.6-65-65.6-65.1a12 12 0 0 1 0-17l39.6-39.6a12 12 0 0 1 17 0l65 65.7 65.1-65.6a12 12 0 0 1 17 0l39.6 39.6a12 12 0 0 1 0 17L312 256z" fill="currentColor"></path>
    </svg>`;

    // Guardar os SVGs originais
    let originalProvidersSvg = '';
    let originalSearchSvg = '';

    if (filterProvidersBtn && filterProvidersBtn.querySelector('.nuxt-icon')) {
        originalProvidersSvg = filterProvidersBtn.querySelector('.nuxt-icon').innerHTML;
    }

    if (filterSearchBtn && filterSearchBtn.querySelector('.nuxt-icon')) {
        originalSearchSvg = filterSearchBtn.querySelector('.nuxt-icon').innerHTML;
    }

    // Estado ativo
    let activeFilter = null;

    // Configurar estado inicial - CSS já cuida da visibilidade
    function setInitialState() {
        if (!casinoFilters) return;
        
        if (isMobileScreen() && !activeFilter) {
            // Remover classe open se não há filtro ativo
            casinoFilters.classList.remove('casino-filters-open');
        }
    }

    // Abrir filtro de provedores
    function openProviders() {
        if (!isMobileScreen() || !casinoFilters) return;

        // Adicionar classe open (CSS mostrará o elemento)
        casinoFilters.classList.add('casino-filters-open');

        // Mostrar botões de filtro e esconder busca
        if (filterButtons) filterButtons.classList.remove('hidden');
        if (casinoSearch) casinoSearch.classList.add('hidden');

        // Trocar ícone para X
        if (filterProvidersBtn && filterProvidersBtn.querySelector('.nuxt-icon')) {
            filterProvidersBtn.querySelector('.nuxt-icon').innerHTML = closeSvg;
        }

        // Resetar ícone de busca se estiver ativo
        if (activeFilter === 'search' && filterSearchBtn && filterSearchBtn.querySelector('.nuxt-icon')) {
            filterSearchBtn.querySelector('.nuxt-icon').innerHTML = originalSearchSvg;
        }

        activeFilter = 'providers';
    }

    // Abrir filtro de busca
    function openSearch() {
        if (!isMobileScreen() || !casinoFilters) return;

        // Adicionar classe open (CSS mostrará o elemento)
        casinoFilters.classList.add('casino-filters-open');

        // Esconder botões de filtro e mostrar busca
        if (filterButtons) filterButtons.classList.add('hidden');
        if (casinoSearch) casinoSearch.classList.remove('hidden');

        // Trocar ícone para X
        if (filterSearchBtn && filterSearchBtn.querySelector('.nuxt-icon')) {
            filterSearchBtn.querySelector('.nuxt-icon').innerHTML = closeSvg;
        }

        // Resetar ícone de provedores se estiver ativo
        if (activeFilter === 'providers' && filterProvidersBtn && filterProvidersBtn.querySelector('.nuxt-icon')) {
            filterProvidersBtn.querySelector('.nuxt-icon').innerHTML = originalProvidersSvg;
        }

        // Focar no input de busca
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            setTimeout(() => searchInput.focus(), 100);
        }

        activeFilter = 'search';
    }

    // Fechar filtros
    function closeFilters() {
        if (!casinoFilters) return;

        // Remover classe open (CSS esconderá o elemento em mobile)
        casinoFilters.classList.remove('casino-filters-open');

        // Resetar ícones
        if (activeFilter === 'providers' && filterProvidersBtn && filterProvidersBtn.querySelector('.nuxt-icon')) {
            filterProvidersBtn.querySelector('.nuxt-icon').innerHTML = originalProvidersSvg;
        } else if (activeFilter === 'search' && filterSearchBtn && filterSearchBtn.querySelector('.nuxt-icon')) {
            filterSearchBtn.querySelector('.nuxt-icon').innerHTML = originalSearchSvg;
        }

        activeFilter = null;
    }

    // Click handlers
    if (filterProvidersBtn) {
        filterProvidersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (activeFilter === 'providers') {
                closeFilters();
            } else {
                openProviders();
            }
        });
    }

    if (filterSearchBtn) {
        filterSearchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (activeFilter === 'search') {
                closeFilters();
            } else {
                openSearch();
            }
        });
    }

    // Prevenir fechamento ao interagir com elementos de filtro
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        searchInput.addEventListener('input', function(e) {
            e.stopPropagation();
        });
    }

    // Prevenir fechamento ao clicar em elementos de filtro
    document.querySelectorAll('.listBox-wrapper, .select-btn, .select-options, .select-opt').forEach(el => {
        if (el) {
            el.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });

    // Prevenir fechamento ao clicar dentro do container de filtros
    if (casinoFilters) {
        casinoFilters.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Redimensionamento da janela
    window.addEventListener('resize', function() {
        setTimeout(setInitialState, 50);
    });

    // Inicializar
    setInitialState();

    // Expor funções publicamente para depuração
    window.openProviders = openProviders;
    window.openSearch = openSearch;
    window.closeFilters = closeFilters;
});
