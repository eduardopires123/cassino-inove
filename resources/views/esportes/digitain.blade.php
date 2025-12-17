@php
    if (Auth::check()) {
        App\Models\User::where('id', Auth::id())->update(['playing' => 1]);
    }
@endphp

@if (App\Helpers\Core::getSetting()->enable_sports === 0)
    <script>
        window.location.href = "{{ url('/') }}";
    </script>
@endif


@if (App\Models\Settings::isBetbyActive())
    <script>
        window.location.href = "{{ route('sports.index') }}";
    </script>
@endif
@extends('layouts.app')
@section('esportes')
    <div id="css-loading" style="position: fixed; top: 0; left: 0; width: 100%; height: 100vh; background: rgba(18, 18, 18, 0.95); z-index: 9999; display: flex; justify-content: center; align-items: center; flex-direction: column;">
        <div class="spinner"></div>
        <p class="loading-text">Carregando esportes...</p>
    </div>
    <div id="sport_div_iframe"></div>
    <div id="application-container" ref={containerRef}></div>
@endsection

<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @media only screen and (max-width: 768px){
        .mobileMenuContainer[data-v-4af8cbc5]{
            display: none;
        }
    }

    footer, .UrrmK {
        display: none !important;
    }

    /* Classe para prevenir scroll */
    body.no-scroll {
        overflow: hidden !important;
    }

    /* Classe para prevenir scroll */
    body.no-scroll {
        overflow: hidden !important;
    }

    /* Estilos para o iframe de desktop */
    #sport_div_iframe {
        width: 100%;
        min-height: 100vh;
        position: relative;
    }

    #sport_div_iframe iframe {
        width: 100%;
        min-height: 100vh;
        border: none;
    }

    /* Estilos para desktop */
    body.desktop-view #application-container {
        display: none;
    }

    /* Estilos para mobile */
    body.mobile-view #sport_div_iframe {
        display: none !important;
    }

    /* Estilos aprimorados para o loading */
    .spinner {
        border: 5px solid #333;
        border-top: 5px solid var(--primary-color);
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1.2s linear infinite;
        box-shadow: 0 0 15px rgba(var(--text-btn-primary), 0.3);
    }

    .loading-text {
        color: white;
        margin-top: 20px;
        font-size: 18px;
        font-weight: 500;
        text-align: center;
        text-shadow: 0 0 10px rgba(var(--text-btn-primary), 0.5);
    }

    /* Estilos responsivos para mobile */
    @media (max-width: 768px) {
        .spinner {
            width: 50px;
            height: 50px;
            border-width: 4px;
        }

        .loading-text {
            font-size: 16px;
            margin-top: 15px;
            padding: 0 20px;
        }
    }

    /* Estilo adicional para animação de fade-out */
    #css-loading {
        transition: opacity 0.3s ease-out;
    }

    #css-loading.fade-out {
        opacity: 0;
    }

    /* Garantindo que a tela de loading cubra toda a altura em dispositivos móveis */
    @media (max-width: 768px) {
        #css-loading {
            height: 100vh;
            height: calc(var(--vh, 1vh) * 100);
        }
    }
</style>
<script src="https://sport.bookiewiseapi.com/js/Partner/IntegrationLoader.min.js"></script>
<script src="https://sport.bookiewiseapi.com/js/partner/bootstrapper.min.js"></script>
<script src="{{ url(asset('js/sports.js')) }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cssLoading = document.getElementById('css-loading');
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 767.98;
        let contentLoaded = false;
        let redirected = false;

        document.body.classList.add('no-scroll');

        if (isMobile) {
            document.body.classList.add('mobile-view');
            
            // Adicionar entrada inicial no histórico para controlar navegação dentro do iframe
            const urlParams = new URLSearchParams(window.location.search);
            const initialPage = urlParams.get('page') || urlParams.get('l') || 'Home';
            
            const initialState = {
                digitainNavigation: true,
                page: initialPage,
                initial: true,
                timestamp: Date.now()
            };
            history.replaceState(initialState, '', window.location.href);
        } else {
            // Desktop também usa Digitain
            document.body.classList.add('desktop-view');
        }

        function closeLoading() {
            if (cssLoading && !contentLoaded) {
                contentLoaded = true;
                cssLoading.classList.add('fade-out');

                setTimeout(function() {
                    cssLoading.remove();
                    document.body.classList.remove('no-scroll');
                }, 300);
            }
        }

        // Timer de segurança - fecha após 10 segundos se não carregar antes (aumentado para dar tempo ao CSS)
        setTimeout(function() {
            if (!contentLoaded) {
                console.log('Fechando loading por timeout de segurança (CSS pode não ter carregado)');
                closeLoading();
            }
        }, 10000);

        // Escutar evento customizado do sports.js - AGORA SÓ FECHA QUANDO CSS ESTIVER APLICADO
        document.addEventListener('sportsCssLoaded', function(event) {
            if (!contentLoaded && event.detail && event.detail.cssApplied) {
                console.log('CSS personalizado aplicado, fechando loading', event.detail);
                // Aguardar um pouco mais para garantir renderização completa
                setTimeout(closeLoading, 300);
            }
        });

        // Listener de postMessage (caso o iframe envie)
        window.addEventListener('message', function(event) {
            // Não fechar loading aqui - aguardar evento sportsCssLoaded com cssApplied: true
            
            // Capturar navegações do Digitain via postMessage
            if (event.data.command === 'navigateToSport' && event.data.args) {
                const baseUrl = window.location.origin + window.location.pathname;
                let newUrl = baseUrl;
                let pageName = 'sports';
                
                if (event.data.args.sportId) {
                    pageName = 'sport-' + event.data.args.sportId;
                    newUrl += '?sport=' + event.data.args.sportId;
                } else if (event.data.args.route) {
                    pageName = event.data.args.route;
                    newUrl += '?page=' + encodeURIComponent(event.data.args.route);
                }
                
                // Atualizar stack de navegação
                if (!window.navigationStack) {
                    window.navigationStack = [];
                }
                if (window.navigationStack.length === 0 || window.navigationStack[window.navigationStack.length - 1] !== pageName) {
                    window.navigationStack.push(pageName);
                }
                
                history.pushState({
                    digitainNavigation: true,
                    page: pageName,
                    timestamp: Date.now()
                }, '', newUrl);
            }
            
            // Também capturar o evento sportAppEventDispatch
            if (event.data.command === 'sportAppEventDispatch') {
                let page = null;
                let sportId = null;
                let eventId = null;
                
                if (event.data.args) {
                    page = event.data.args.page || event.data.args.route || event.data.args.path || event.data.args.currentPage;
                    sportId = event.data.args.sportId || event.data.args.sport_id;
                    eventId = event.data.args.eventId || event.data.args.event_id;
                }
                
                // Se encontramos alguma informação de navegação, atualizar URL
                if (page || sportId || eventId) {
                    const baseUrl = window.location.origin + window.location.pathname;
                    let newUrl = baseUrl;
                    let pageName = 'sports';
                    
                    if (page) {
                        pageName = page;
                        newUrl += '?page=' + encodeURIComponent(page);
                    } else if (sportId) {
                        pageName = 'sport-' + sportId;
                        newUrl += '?sport=' + sportId;
                    } else if (eventId) {
                        pageName = 'event-' + eventId;
                        newUrl += '?event=' + eventId;
                    }
                    
                    // Atualizar stack de navegação
                    if (!window.navigationStack) {
                        window.navigationStack = [];
                    }
                    if (window.navigationStack.length === 0 || window.navigationStack[window.navigationStack.length - 1] !== pageName) {
                        window.navigationStack.push(pageName);
                    }
                    
                    history.pushState({
                        digitainNavigation: true,
                        page: pageName,
                        timestamp: Date.now()
                    }, '', newUrl);
                }
            }
        });
    });

    // Script para ajustar a altura em dispositivos móveis (corrige problemas com barra de endereço)
    window.addEventListener('load', function() {
        // Primeiro definir a altura
        let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);

        // Atualizar em caso de redimensionamento
        window.addEventListener('resize', function() {
            vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        });
    });

    window.addEventListener('message', function(event) {

        // Interceptar mensagens do iframe Digitain para controlar navegação
        if (event.data && event.data.type === 'navigation') {
            if (event.data.action === 'back') {
                // Prevenir navegação para fora do iframe
                event.preventDefault();
                return false;
            }
        }

        // Capturar quando o MobileFrame for criado
        if (event.data && event.data.MobileFrame) {
            MobileFrame = event.data.MobileFrame;
        }

        if (event.data.command === 'login') {
            const loginModal = document.getElementById('login-modal') ||
                document.getElementById('modal-login') ||
                document.querySelector('.login-modal') ||
                document.querySelector('.modal-login') ||
                document.querySelector('[data-modal="login"]');

            const loginOverlay = document.getElementById('login-modal-overlay') ||
                document.getElementById('login-overlay') ||
                document.querySelector('.modal-overlay') ||
                document.querySelector('.overlay');

            if (loginModal) {
                loginModal.style.display = 'block';
                loginModal.classList.remove('hidden');
                loginModal.classList.add('show');

                if (loginOverlay) {
                    loginOverlay.style.display = 'block';
                    loginOverlay.classList.remove('hidden');
                    loginOverlay.classList.add('show');
                }
            }
        }
    });

    window.addEventListener('header:updated', function(event) {
        // Verificar se estamos na página de esportes Digitain
        if (!document.getElementById('sport_div_iframe') && !document.getElementById('application-container')) {
            return; // Não estamos na página de esportes Digitain
        }

        if (event.detail?.reason === 'login') {
            // Login realizado via AJAX - recarregar página para reinicializar Digitain
            console.log('Login detectado via AJAX - recarregando página para reinicializar Digitain');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else if (event.detail?.reason === 'logout') {
            // Logout realizado via AJAX - recarregar página para reinicializar Digitain
            console.log('Logout detectado via AJAX - recarregando página para reinicializar Digitain');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
    });
</script>
