@php
    // Verificar qual provedor de API de sports está ativo
    $sportsApiProvider = App\Models\Settings::getSportsApiProvider();
    $isBetbyActive = $sportsApiProvider === 'betby';
    $isDigitainActive = $sportsApiProvider === 'digitain' || $sportsApiProvider === null;
@endphp

@if ($isDigitainActive)
    {{-- Top-Matches Widget - Digitain --}}
    <div id="top-matches-container"></div>
@endif

@if ($isBetbyActive)
    {{-- Betby Line Banner - operator_page2 --}}
    <div id="betby-banner-container" style="width: 100%; margin: 20px 0; background: transparent; position: relative; z-index: 1;">
        <div id="betby-line-banner"></div>
    </div>
    
    <style>
        /* Forçar visualização dos elementos do banner Betby */
        #betby-line-banner * {
            opacity: 1 !important;
            visibility: visible !important;
            min-height: auto !important;
            max-height: none !important;
        }
        
        #betby-line-banner > div {
            min-height: 100px !important;
            height: auto !important;
        }
        
        #betby-line-banner svg {
            min-height: 80px !important;
            height: auto !important;
        }
        
        #betby-line-banner a {
            display: block !important;
            opacity: 1 !important;
        }
    </style>
@endif

@if ($isDigitainActive)
<script>
    // Cache de token para evitar múltiplas requisições
    let topMatchesTokenCache = null;
    let topMatchesTokenPromise = null;
    
    // Função para obter token via AJAX - Otimizada com cache
    async function obterTokenTopMatches() {
        // Usar cache se disponível
        if (topMatchesTokenCache) {
            return topMatchesTokenCache;
        }
        
        // Evitar múltiplas requisições simultâneas
        if (topMatchesTokenPromise) {
            return topMatchesTokenPromise;
        }
        
        topMatchesTokenPromise = (async () => {
            try {
                const data = {
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                
                const response = await fetch('/tk-gn-q4m8', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                });
                
                if (response.ok) {
                    const result = await response.text();
                    topMatchesTokenCache = result;
                    return result;
                }
                return "";
            } catch (error) {
                return "";
            } finally {
                topMatchesTokenPromise = null;
            }
        })();
        
        return topMatchesTokenPromise;
    }
    
    // Função para detectar mobile - mesma lógica do sports.js
    function isMobileDevice() {
        return (window.innerWidth <= 767.98) ||
            (navigator.userAgent.match(/Android/i) ||
                navigator.userAgent.match(/iPhone/i) ||
                navigator.userAgent.match(/iPad/i) ||
                navigator.userAgent.match(/iPod/i) ||
                navigator.userAgent.match(/BlackBerry/i) ||
                navigator.userAgent.match(/Windows Phone/i));
    }
    
    // Função para aplicar CSS ao Shadow DOM - adaptada do sports.js
    function aplicarCSSTopMatches(elemento) {
        try {
            const shadowRoot = elemento.shadowRoot;
            if (!shadowRoot) {
                setTimeout(() => {
                    if (elemento.shadowRoot) {
                        aplicarCSSTopMatches(elemento);
                    }
                }, 50); // Reduzido de 100ms para 50ms
                return;
            }

            if (shadowRoot.querySelector('#tema-escuro-customizado')) return;

            const fullURL = window.location.origin + "/css/mobile.css";
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = fullURL;
            link.id = 'tema-escuro-customizado';
            link.type = 'text/css';

            link.onerror = () => {
                const style = document.createElement('style');
                style.id = 'tema-escuro-customizado';
                style.textContent = `/* Fallback styles */`;
                shadowRoot.appendChild(style);
            };

            shadowRoot.appendChild(link);

            link.onload = function() {
                document.dispatchEvent(new CustomEvent('sportsCssLoaded', {detail: {silent: true}}));
            };
        } catch (e) {
            // Suprime erros para evitar notificações no console
        }
    }
    
    // Event Listeners para Top-Matches conforme documentação
    function addTopMatchesEventsListeners(topMatches) {
        // Event listener para página carregada
        topMatches.addEventListener('page-loaded', function handlePageLoad() {
            // Aplicar CSS aos elementos com shadow root - mesma lógica do sports.js
            const elementos = [
                document.querySelector('sport-mobile'),
                document.querySelector('sport-modal'),
                document.querySelector('sport-betslip')
            ];

            elementos.forEach(el => {
                if (el && el.shadowRoot) {
                    aplicarCSSTopMatches(el);
                }
            });

            // Verificar elementos com shadow root imediatamente
            document.querySelectorAll('*').forEach(el => {
                if (el.shadowRoot &&
                    el.tagName.toLowerCase().includes('sport') &&
                    !el.shadowRoot.querySelector('#tema-escuro-customizado')) {
                    aplicarCSSTopMatches(el);
                }
            });

            // Setup MutationObserver para observar novos elementos sport
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === 1) { // Element node
                            if (node.tagName && node.tagName.toLowerCase().includes('sport')) {
                                aplicarCSSTopMatches(node);
                            }
                            // Verificar elementos filhos
                            node.querySelectorAll('*').forEach(el => {
                                if (el.tagName && el.tagName.toLowerCase().includes('sport')) {
                                    aplicarCSSTopMatches(el);
                                }
                            });
                        }
                    });
                });
            });

            // Começar a observar o documento
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            // Mecanismo de retry para elementos iniciais - otimizado
            let retryCount = 0;
            const maxRetries = 2; // Reduzido de 3 para 2
            const retryInterval = setInterval(() => {
                if (retryCount >= maxRetries) {
                    clearInterval(retryInterval);
                    return;
                }

                document.querySelectorAll('*').forEach(el => {
                    if (el.shadowRoot &&
                        el.tagName.toLowerCase().includes('sport') &&
                        !el.shadowRoot.querySelector('#tema-escuro-customizado')) {
                        aplicarCSSTopMatches(el);
                    }
                });

                retryCount++;
            }, 250); // Reduzido de 500ms para 250ms
        });
        
        // Event listener para navegação para evento
        topMatches.addEventListener('navigateToEvent', function (messageEvent) {
            if (messageEvent.data && messageEvent.data.Id) {
                if (typeof LinkMobile === 'function') {
                    LinkMobile('sport/event/' + messageEvent.data.Id);
                } else {
                    window.location.href = '/esportes?eventId=' + messageEvent.data.Id;
                }
            }
        });
        
        // Event listener para navegação para campeonato
        topMatches.addEventListener('navigateToChampionship', function (messageEvent) {
            if (messageEvent.data && messageEvent.data.Id) {
                if (typeof LinkMobile === 'function') {
                    LinkMobile('sport/championship/' + messageEvent.data.Id);
                } else {
                    window.location.href = '/esportes?champId=' + messageEvent.data.Id;
                }
            }
        });
    }
    
    // Variável global para controlar a instância do widget
    let topMatchesInstance = null;
    
    // Função principal para inicializar o Top-Matches conforme documentação
    async function iniciarTopMatches() {
        const container = document.getElementById('top-matches-container');
        if (!container) {
            return;
        }
        
        if (typeof Bootstrapper === 'undefined') {
            return;
        }
        
        const token = await obterTokenTopMatches();
        const isMobile = isMobileDevice();
        
        if (!isMobile) {
            // DESKTOP - Implementação com Bootstrapper.boot
            const params = {
                server: "https://sport.bookiewiseapi.com",
                target: "#top-matches-container",
                defaultLanguage: "pt-BR",
                token: token,
                sportsBookView: "africanView",
                sportPartner: "f41206f1-981a-4a44-b762-022e958ecd63"
            };
            
            try {
                topMatchesInstance = await Bootstrapper.boot(params, { name: "TopMatches" });
                
                if (topMatchesInstance) {
                    addTopMatchesEventsListeners(topMatchesInstance);
                    window.topMatchesWidget = topMatchesInstance;
                }
            } catch (error) {
                // Erro silencioso
            }
            
        } else {
            // MOBILE - Implementação com Bootstrapper.bootIframe
            const preloadCSS = document.createElement('link');
            preloadCSS.rel = 'stylesheet';
            preloadCSS.href = window.location.origin + "/css/mobile.css";
            preloadCSS.id = 'preload-mobile-css';
            document.head.appendChild(preloadCSS);
            
            const params = {
                server: "https://sport.bookiewiseapi.com",
                containerId: "top-matches-container",
                defaultLanguage: "pt-BR",
                token: token,
                parent: [location.host],
                sportsBookView: "africanView",
                sportPartner: "f41206f1-981a-4a44-b762-022e958ecd63"
            };
            
            try {
                topMatchesInstance = await Bootstrapper.bootIframe(params, { name: "TopMatches" });
                
                if (topMatchesInstance) {
                    addTopMatchesEventsListeners(topMatchesInstance);
                    window.topMatchesWidget = topMatchesInstance;
                }
            } catch (error) {
                // Erro silencioso
            }
        }
    }
    
    // Função para destruir o widget conforme documentação
    function destroyTopMatches() {
        if (topMatchesInstance && typeof topMatchesInstance.destroy === 'function') {
            topMatchesInstance.destroy();
            topMatchesInstance = null;
            window.topMatchesWidget = null;
        }
    }
    
    // Expor função de destruição globalmente
    window.destroyTopMatches = destroyTopMatches;
    
    // Inicialização otimizada - aguardar DOM e Bootstrapper
    function inicializarTopMatches() {
        // Verificar se o DOM está pronto
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', inicializarTopMatches);
            return;
        }
        
        // Verificar se o Bootstrapper está disponível
        if (typeof Bootstrapper === 'undefined') {
            // Aguardar Bootstrapper com timeout otimizado
            let tentativas = 0;
            const maxTentativas = 20; // 20 tentativas = 10 segundos máximo
            
            const verificarBootstrapper = () => {
                if (typeof Bootstrapper !== 'undefined') {
                    iniciarTopMatches();
                    return;
                }
                
                tentativas++;
                if (tentativas < maxTentativas) {
                    setTimeout(verificarBootstrapper, 50);
                }
            };
            
            verificarBootstrapper();
        } else {
            // Bootstrapper já está disponível
            iniciarTopMatches();
        }
    }
    
    // Iniciar imediatamente se possível, caso contrário aguardar
    inicializarTopMatches();
</script>
@endif

@if ($isBetbyActive)
<script>
    let betbyBannerInstance = null;
    
    const betbyBannerConfig = @json($betbyConfig ?? []);
    const betbyBannerJwtToken = @json($jwtToken ?? null);
    
    if (!window.jwtToken && betbyBannerJwtToken) {
        window.jwtToken = betbyBannerJwtToken;
    }
    
    function mapLanguageToBetbyBanner(locale) {
        const languageMap = {
            'pt_BR': 'pt-br',
            'en': 'en',
            'es': 'es'
        };
        return languageMap[locale] || 'en';
    }
    
    async function iniciarBannerBetby() {
        let token = window.jwtToken || betbyBannerJwtToken;
        
        if (!token) {
            try {
                const response = await fetch('{{ route("betby.token.refresh") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const tokenData = await response.json();
                    token = tokenData.token || tokenData;
                    window.jwtToken = token;
                }
            } catch (e) {
                return null;
            }
        }
        
        try {
            const container = document.getElementById('betby-line-banner');
            if (!container) return null;
            
            betbyBannerInstance = new BTRenderer().initialize({
                brand_id: betbyBannerConfig.brand_id || @json(config('betby.brand_id')),
                token: token,
                lang: mapLanguageToBetbyBanner(betbyBannerConfig.language || @json(app()->getLocale())),
                target: container,
                themeName: betbyBannerConfig.theme_name || @json(config('betby.theme_name', 'default')),
                widgetName: 'promo',
                widgetParams: {
                    placeholder: 'operator_page2',
                    onBannerClick: ({ url }) => {
                        if (url) {
                            window.location.href = url.startsWith('http') ? url : '/sports' + (url.startsWith('/') ? url : '/' + url);
                        } else {
                            window.location.href = '/sports';
                        }
                    },
                    onOutcomeClick: ({ url }) => {
                        if (url) {
                            window.location.href = url.startsWith('http') ? url : '/sports' + (url.startsWith('/') ? url : '/' + url);
                        }
                    }
                }
            });
            
            window.betbyLineBanner = betbyBannerInstance;
            return betbyBannerInstance;
        } catch (error) {
            return null;
        }
    }
    
    function inicializarBanner() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', inicializarBanner);
            return;
        }
        
        if (typeof BTRenderer === 'undefined') {
            let tentativas = 0;
            const verificar = () => {
                if (typeof BTRenderer !== 'undefined') {
                    iniciarBannerBetby();
                } else if (++tentativas < 10) {
                    setTimeout(verificar, 500);
                }
            };
            verificar();
        } else {
            iniciarBannerBetby();
        }
    }
    
    inicializarBanner();
    
    window.addEventListener('header:updated', function(event) {
        if (event.detail?.reason === 'login' || event.detail?.reason === 'logout') {
            if (window.betbyLineBanner && typeof window.betbyLineBanner.kill === 'function') {
                window.betbyLineBanner.kill();
            }
            window.jwtToken = null;
            setTimeout(iniciarBannerBetby, 1000);
        }
    });

    // =====================================
    // INJEÇÃO DE CSS NO SHADOW DOM DO BANNER BETBY
    // =====================================
    
    // Função para aplicar CSS ao Shadow DOM do banner Betby
    function aplicarCSSBetbyBanner(elemento, forceReapply = false) {
        try {
            const shadowRoot = elemento.shadowRoot;
            if (!shadowRoot) return;

            // Se já tem o estilo injetado e não é forceReapply, pular
            if (shadowRoot.querySelector('#betby-banner-custom-css') && !forceReapply) return;

            // Remover CSS existente se forceReapply
            if (forceReapply) {
                const existingStyle = shadowRoot.querySelector('#betby-banner-custom-css');
                if (existingStyle) existingStyle.remove();
            }

            // Obter a cor primária do tema do documento
            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim() || 
                                getComputedStyle(document.body).getPropertyValue('--primary-color').trim();

            // Adicionar CSS customizado para o banner
            const customStyle = document.createElement('style');
            customStyle.id = 'betby-banner-custom-css';
            customStyle.textContent = `
                .sc-1djzvw3-2.iNKokj {
                    position: relative !important;
                }
                
                .sc-1djzvw3-2.iNKokj::after {
                    content: "";
                    position: absolute;
                    inset: 0;
                    background-color: ${primaryColor} !important;
                    opacity: 0.7 !important;
                    pointer-events: none !important;
                }
            `;
            shadowRoot.appendChild(customStyle);
        } catch (e) {
            // Silenciar erros
        }
    }

    // Função para aplicar CSS em todos os elementos shadow DOM do banner
    function aplicarCSSBannerEmTodos(force = false) {
        document.querySelectorAll('*').forEach(el => {
            if (el.shadowRoot) {
                aplicarCSSBetbyBanner(el, force);
            }
        });
    }

    // Observer principal para novos elementos do banner
    const bannerObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1 && node.shadowRoot) {
                    aplicarCSSBetbyBanner(node);
                }
            });
        });
    });

    // Observar o container do banner
    const bannerContainer = document.getElementById('betby-line-banner');
    if (bannerContainer) {
        bannerObserver.observe(bannerContainer, { childList: true, subtree: true });
    }

    // Aplicar CSS inicial após um delay
    setTimeout(() => aplicarCSSBannerEmTodos(), 1000);

    // Observer específico para recarregamentos do banner
    if (bannerContainer) {
        let lastReload = 0;

        const betbyBannerObserver = new MutationObserver(() => {
            const now = Date.now();
            if (now - lastReload > 2000) { // Throttle de 2s
                lastReload = now;
                setTimeout(() => aplicarCSSBannerEmTodos(true), 1000);
            }
        });

        betbyBannerObserver.observe(bannerContainer, { childList: true, subtree: true });
    }

    // Listener para quando o banner for inicializado
    if (window.betbyLineBanner) {
        setTimeout(() => aplicarCSSBannerEmTodos(true), 1500);
    }
</script>
@endif
