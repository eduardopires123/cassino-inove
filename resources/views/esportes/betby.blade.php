@if (!App\Models\Settings::isBetbyActive())
    <script>
        window.location.href = "{{ route('home') }}";
    </script>
@endif

@extends('layouts.app')
@section('esportes')
    <div id="betby-sportsbook-container" style="z-index: 1; position: relative;">
        <!-- Container principal do Betby -->
        <div id="betby" style="width: 100%; min-height: 100vh;"></div>
    </div>

    <style>
        /* Ocultar elementos que podem interferir no Betby */
        footer,
        .UrrmK {
            display: none !important;
        }

        /* Estilos específicos para o Betby */
        #betby-sportsbook-container {
            margin: 0;
            padding: 0;
            width: 100%;
        }

        /* Garantir que o Betby tenha espaço suficiente */
        body.betby-active {
            /*overflow-x: hidden;*/
        }

        .depositModal {
            z-index: 1000 !important;
        }

        #_8XokL {
            z-index: 1000 !important;
        }

        .jbmAp.uqx9L {
            display: none !important;
        }
        
        .bt18.bt21{
            top:0px!important;
        }

        /* Ajustar conteúdo do Betby para não ficar atrás do menu mobile sports */
        @media only screen and (max-width: 768px) {
            body.sports-page #betby-sportsbook-container {
                padding-bottom: 80px !important; /* Espaço para o menu mobile sports na parte inferior */
            }

            body.sports-page #betby {
                min-height: calc(100vh - 80px) !important;
            }

            /* Garantir que o menu mobile sports fique acima do conteúdo */
            body.sports-page #divMobileMenuSports {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                z-index: 99999 !important;
            }
        }
    </style>

    <script>
        // Adicionar classe ao body para identificar página de sports
        document.body.classList.add('sports-page');

        // Configurações do Betby vindas do backend
        const betbyConfig = @json($betbyConfig);
        const jwtToken = @json($jwtToken);
        const activeTheme = @json($activeTheme);
        const btPath = @json($btPath);
        const btBookingCode = @json($btBookingCode);

        // Armazenar token globalmente para atualizações
        window.jwtToken = jwtToken;

        // Variável para controlar inicialização
        let isInitializing = false;

        // Função para inicializar o Betby
        function initializeBetby() {
            // Evitar múltiplas inicializações simultâneas
            if (isInitializing) {
                return;
            }

            isInitializing = true;

            try {
                // Verificar se o script do Betby foi carregado
                if (typeof BTRenderer === 'undefined') {
                    isInitializing = false;
                    return;
                }

                // Verificar se o container existe
                const targetContainer = document.getElementById('betby');
                if (!targetContainer) {
                    isInitializing = false;
                    return;
                }

                // Configurações do Betby
                const config = {
                    brand_id: betbyConfig.brand_id,
                    token: window.jwtToken || jwtToken,
                    themeName: betbyConfig.theme_name,
                    lang: mapLanguageToBetby(betbyConfig.language),
                    target: targetContainer,
                    betSlipOffsetTop: getHeaderHeight(),
                    betSlipOffsetBottom: getFooterHeight(),
                    stickyTop: getHeaderHeight(),
                    betslipZIndex: 0,
                    basename: '/sports',
                    heroBannersSlidingRate: 5,
                    lineBannersSlidingRate: 6,

                    onLogin: function() {
                        if (typeof window.abrirModalLogin === 'function') {
                            window.abrirModalLogin();
                        }
                    },
                    onRegister: function() {
                        if (typeof window.abrirModalRegistro === 'function') {
                            window.abrirModalRegistro();
                        }
                    },
                    onSessionRefresh: function() {
                    },
                    onTokenExpired: function() {
                        return new Promise((resolve, reject) => {
                            fetch('/betby/token/refresh', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(`HTTP ${response.status}`);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.token) {
                                        window.jwtToken = data.token;
                                        resolve(data.token);
                                    } else {
                                        reject(new Error('Não foi possível renovar o token'));
                                    }
                                })
                                .catch(error => {
                                    console.error('Erro ao renovar token:', error);
                                    reject(error);
                                });
                        });
                    },
                    onRecharge: function() {
                        if (typeof window.openDepositModal === 'function') {
                            window.openDepositModal();
                        }
                    },
                    onBetSlipStateChange: function(state) {
                        // Estado do betslip mudou
                    }
                };

                // Inicializar Betby
                window.betbyRenderer = new BTRenderer().initialize(config);
                document.body.classList.add('betby-active');

                // Navegar para path específico se fornecido
                setTimeout(() => {
                    // Obter o caminho atual da URL (após /sports)
                    const currentPath = window.location.pathname.replace('/sports', '') + window.location.search;

                    if (btBookingCode && window.betbyRenderer?.navigate) {
                        // Navegar para booking code
                        window.betbyRenderer.navigate('?btBookingCode=' + btBookingCode);
                    } else if (btPath && btPath !== '/' && window.betbyRenderer?.navigate) {
                        // Navegar para path específico passado do backend
                        window.betbyRenderer.navigate(btPath);
                    } else if (currentPath && currentPath !== '/' && window.betbyRenderer?.navigate) {
                        // Navegar para o path atual da URL
                        window.betbyRenderer.navigate(currentPath);
                    }

                    // Disparar evento customizado após inicialização
                    document.dispatchEvent(new CustomEvent('betbyInitialized'));
                }, 500);

                isInitializing = false;

            } catch (error) {
                isInitializing = false;
            }
        }

        // Função para mapear idioma
        function mapLanguageToBetby(locale) {
            const languageMap = {
                'pt_BR': 'pt-br',
                'en': 'en',
                'es': 'es'
            };
            return languageMap[locale] || 'en';
        }

        // Função para obter altura do header
        function getHeaderHeight() {
            const header = document.querySelector('.S9VkN, .desktop-header-wrapper, header');
            return header ? header.offsetHeight : 80;
        }

        // Função para obter altura do footer
        function getFooterHeight() {
            const footer = document.querySelector('footer');
            return footer ? footer.offsetHeight : 0;
        }

        // Função helper para navegar programaticamente no Betby
        window.navigateToBetbyPath = function(path) {
            if (window.betbyRenderer && typeof window.betbyRenderer.navigate === 'function') {
                window.betbyRenderer.navigate(path);
                return true;
            }
            console.warn('Betby Renderer não está disponível para navegação');
            return false;
        };

        // Aguardar o carregamento do DOM e do script do Betby
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar se o script do Betby já foi carregado
            if (typeof BTRenderer !== 'undefined') {
                initializeBetby();
            } else {
                // Aguardar um pouco para o script carregar
                setTimeout(() => {
                    initializeBetby();
                }, 500);
            }
        });

        // Listener para reinicializar Betby após login/logout
        window.addEventListener('header:updated', function(event) {
            // Verificar se estamos na página correta
            if (!document.getElementById('betby-sportsbook-container')) {
                return; // Não estamos na página de sports
            }

            if (event.detail?.reason === 'login') {
                window.betbyRenderer.kill();
                initializeBetby();
            } else if (event.detail?.reason === 'logout') {
                // Para logout, limpar token e reinicializar com visitante
                window.jwtToken = null;
                setTimeout(() => {
                    reinitializeBetbyAfterAuth('logout');
                }, 100);
            }
        });

        // Função para reinicializar Betby após mudança de autenticação
        function reinitializeBetbyAfterAuth(reason) {

            // Destruir instância atual do Betby se existir
            if (window.betbyRenderer && typeof window.betbyRenderer.kill === 'function') {
                try {
                    // Aguardar a destruição assíncrona
                    window.betbyRenderer.kill().then(() => {
                        continueReinit();
                    }).catch((error) => {
                        continueReinit();
                    });
                } catch (error) {
                    continueReinit();
                }
            } else {
                continueReinit();
            }

            function continueReinit() {
                // Aguardar um pouco para garantir cleanup completo
                setTimeout(() => {
                    // Limpar referência e resetar flag
                    window.betbyRenderer = null;
                    isInitializing = false;

                    // Limpar o container de forma mais segura
                    const betbyContainer = document.getElementById('betby');
                    if (betbyContainer) {
                        // Usar timeout para evitar conflitos de React
                        setTimeout(() => {
                            betbyContainer.innerHTML = '';
                        }, 50);
                    }

                    // Se há usuário logado e não temos token JWT ainda, obter novo token
                    if (reason === 'login' && !window.jwtToken) {
                        // Aguardar antes de fazer a requisição
                        setTimeout(() => {
                            fetch('{{ route("betby.token.refresh") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                    'Accept': 'application/json'
                                }
                            })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.token) {
                                        // Atualizar token global
                                        window.jwtToken = data.token;
                                    } else {
                                        window.jwtToken = null;
                                    }

                                    // Reinicializar Betby após delay
                                    setTimeout(() => {
                                        initializeBetby();
                                        // Reaplicar CSS após reinicialização
                                        setTimeout(() => aplicarCSSEmTodos(true), 1000);
                                    }, 300);
                                })
                                .catch(error => {
                                    // Fallback: usar modo visitante
                                    window.jwtToken = null;
                                    setTimeout(() => {
                                        initializeBetby();
                                        // Reaplicar CSS após reinicialização
                                        setTimeout(() => aplicarCSSEmTodos(true), 1000);
                                    }, 300);
                                });
                        }, 200);
                    } else {
                        // Para logout ou casos onde já temos token, reinicializar diretamente
                        setTimeout(() => {
                            initializeBetby();
                            // Reaplicar CSS após reinicialização
                            setTimeout(() => aplicarCSSEmTodos(true), 1000);
                        }, 300);
                    }

                }, 300);
            }
        }

        // Cleanup quando sair da página
        window.addEventListener('beforeunload', function() {
            if (window.betbyRenderer && typeof window.betbyRenderer.kill === 'function') {
                window.betbyRenderer.kill();
            }
        });

        // Atualizar offsets quando a janela for redimensionada
        window.addEventListener('resize', function() {
            if (window.betbyRenderer && typeof window.betbyRenderer.updateOptions === 'function') {
                window.betbyRenderer.updateOptions({
                    betSlipOffsetTop: getHeaderHeight(),
                    betSlipOffsetBottom: getFooterHeight()
                });
            }
        });

        // Rolar para o topo quando navegar dentro do Betby
        document.addEventListener('betbyInitialized', function() {
            let lastUrl = location.href;

            function scrollToTop() {
                window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
                document.documentElement.scrollTop = 0;
                document.body.scrollTop = 0;
            }

            // Interceptar pushState e replaceState
            const originalPushState = history.pushState;
            const originalReplaceState = history.replaceState;

            history.pushState = function() {
                originalPushState.apply(this, arguments);
                if (location.href !== lastUrl && location.pathname.startsWith('/sports')) {
                    lastUrl = location.href;
                    scrollToTop();
                }
            };

            history.replaceState = function() {
                originalReplaceState.apply(this, arguments);
                if (location.href !== lastUrl && location.pathname.startsWith('/sports')) {
                    lastUrl = location.href;
                    scrollToTop();
                }
            };
        });
    </script>

    <!-- Script para injeção de CSS em shadow roots do Betby -->
    <script>
        // Função simplificada para aplicar CSS ao Shadow DOM do Betby
        function aplicarCSSBetby(elemento, forceReapply = false) {
            try {
                // Se o theme 4 estiver ativo, não usar nenhum arquivo CSS
                if (activeTheme == 4) {
                    return;
                }

                const shadowRoot = elemento.shadowRoot;
                if (!shadowRoot) return;

                // Se já tem o estilo injetado e não é forceReapply, pular
                if (shadowRoot.querySelector('#betby-tema-customizado') && !forceReapply) return;

                // Remover CSS existente se forceReapply
                if (forceReapply) {
                    const existingLink = shadowRoot.querySelector('#betby-tema-customizado');
                    if (existingLink) existingLink.remove();
                    
                    const existingCustomStyle = shadowRoot.querySelector('#betby-menu-mobile-fix');
                    if (existingCustomStyle) existingCustomStyle.remove();
                }

                // Determinar qual arquivo CSS usar baseado no tema ativo
                let cssFileName;
                if (activeTheme == 2) {
                    cssFileName = 'betby2.css';
                } else if (activeTheme == 3) {
                    cssFileName = 'betby3.css';
                } else {
                    cssFileName = 'betby.css';
                }
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = window.location.origin + `/css/${cssFileName}`;
                link.id = 'betby-tema-customizado';
                link.type = 'text/css';

                shadowRoot.appendChild(link);

                // Adicionar CSS customizado para ajustar o botão flutuante do betslip (apenas mobile)
                const customStyle = document.createElement('style');
                customStyle.id = 'betby-menu-mobile-fix';
                customStyle.textContent = `
                    @media only screen and (max-width: 768px) {
                        .spt-bet-slip {
                            bottom: 3.5rem !important;
                        }
                    }
                `;
                shadowRoot.appendChild(customStyle);
            } catch (e) {
                // Silenciar erros
            }
        }

        // Função para aplicar CSS em todos os elementos shadow DOM
        function aplicarCSSEmTodos(force = false) {
            document.querySelectorAll('*').forEach(el => {
                if (el.shadowRoot) {
                    aplicarCSSBetby(el, force);
                }
            });
        }

        // Observer principal para novos elementos
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1 && node.shadowRoot) {
                        aplicarCSSBetby(node);
                    }
                });
            });
        });
        00
        observer.observe(document.body, { childList: true, subtree: true });

        // Aplicar CSS inicial
        setTimeout(() => aplicarCSSEmTodos(), 1000);

        // Observer específico para recarregamentos do Betby
        const betbyContainer = document.getElementById('betby');
        if (betbyContainer) {
            let lastReload = 0;

            const betbyObserver = new MutationObserver(() => {
                const now = Date.now();
                if (now - lastReload > 2000) { // Throttle de 2s
                    lastReload = now;
                    setTimeout(() => aplicarCSSEmTodos(true), 1000);
                }
            });

            betbyObserver.observe(betbyContainer, { childList: true, subtree: true });
        }

        // Listeners para eventos do Betby
        document.addEventListener('betbyInitialized', () => {
            setTimeout(() => aplicarCSSEmTodos(true), 1000);
        });
    </script>


    <!-- Carregar o script do Betby -->
    <script src="{{ $betbyConfig['bt_library_url'] }}"></script>
@endsection

@section('meta_tags')
    <!-- Meta tag para Content Security Policy -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' 'unsafe-inline' 'unsafe-eval'; script-src 'self' 'unsafe-inline' 'unsafe-eval' {{ $betbyConfig['api_url'] }} *.sptpub.com; connect-src 'self' {{ $betbyConfig['api_url'] }} *.sptpub.com wss://*.sptpub.com; img-src 'self' data: {{ $betbyConfig['api_url'] }} *.sptpub.com; style-src 'self' 'unsafe-inline' {{ $betbyConfig['api_url'] }} *.sptpub.com; font-src 'self' {{ $betbyConfig['api_url'] }} *.sptpub.com;">
@endsection
