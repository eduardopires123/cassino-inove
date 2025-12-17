@extends('layouts.app')
@section('content')
    @php
        // Obter configurações das seções da página inicial
        $homeSections = \App\Models\HomeSectionsSettings::getSettings();

        // Obter ordem das seções
        $sectionsOrder = \App\Models\HomeSectionOrder::getOrderedSections();
    @endphp
    @include('partials.banner')
    <div class="qYQqp" id="content-game">
        @foreach($sectionsOrder as $section)
            @php
                $isCustomField = strpos($section->section_key, 'custom_field_') === 0;
            @endphp
            @if($isCustomField)
                @php
                    $fieldId = (int) str_replace('custom_field_', '', $section->section_key);
                    $customField = $customFields->firstWhere('id', $fieldId);
                @endphp
                @if($customField && isset($customFieldsData[$fieldId]) && $customFieldsData[$fieldId]->count() > 0)
                    @include('partials.sections.custom-field', [
                        'customField' => $customField,
                        'games' => $customFieldsData[$fieldId]
                    ])
                @endif
            @else
            @switch($section->section_key)
                @case('search_bar')
                    @include('partials.sections.search-bar')
                    @break

                @case('promo_banners')
                    @if($cachedData['hasActivePromoBanners_cache'])
                        @include('partials.promo')
                    @endif
                    @break

                @case('menu_icons')
                    <div id="divMenuHighlight" class="txCJA">
                        <div class="-JVa3 Vulse EEtS9" style="--620ba053: 0px; --063993a6: 12px; --8ec19218: auto; --543ef9ea: 1;">
                            @include('partials.icones')
                        </div>
                    </div>
                    @break

                @case('top_wins')
                    @if($cachedData['show_top_wins_cache'] == 1)
                        @include('partials.sections.top-wins')
                    @endif
                    @break

                @case('mini_banners')
                    @include('partials.sections.most-paid')
                    @break

                @case('modo_surpresa')
                    @include('partials.mdsurpresa')
                    @break

                @case('sports_icons')
                    @if (App\Helpers\Core::getSetting()->enable_sports === 1)
                        @include('partials.sportsic')
                    @endif
                    @break

                @case('providers_games')
                    @foreach($cachedData['activeProviders_cache'] as $provider)
                        @include('partials.sections.provider-active', ['provider' => $provider])
                    @endforeach
                    @break

                @case('live_casino')
                    @if($homeSections->show_live_casino)
                        @include('partials.sections.live-casino')
                    @endif
                    @break

                @case('top_matches')
                    @if (App\Helpers\Core::getSetting()->enable_sports === 1)
                        @include('partials.topmatches')
                    @endif
                    @break

                @case('new_games')
                    @if($homeSections->show_new_games)
                        @include('partials.sections.new-games')
                    @endif
                    @break

                @case('most_viewed_games')
                    @if($homeSections->show_most_viewed_games)
                        @include('partials.sections.most-viewed')
                    @endif
                    @break

                @case('raspadinhas')
                    @if($homeSections->show_raspadinhas_home && !empty($cachedData['mostPlayedRaspadinhas_cache']) && $cachedData['mostPlayedRaspadinhas_cache']->count() > 0)
                        @include('partials.sections.top-raspadinhas')
                    @endif
                    @break

                @case('providers_list')
                    @include('partials.sections.studios')
                    @break

                @case('recent_bets')
                    @include('partials.ultimasbet')
                    @break

                @case('floating_roulette')
                    @if($homeSections->show_roulette)
                        <div id="chat-icon" onclick="openRouletteModal()">
                            <img src="{{ url(asset('img/roleta/roleta.png')) }}" alt="Chat Icon" width="80" height="80">
                        </div>
                        @include('minigames.roleta')
                    @endif
                    @break

                @case('floating_whatsapp')
                    @if($homeSections->show_whatsapp_float && $cachedData['socialLinks_whatsapp_cache'] && $cachedData['socialLinks_whatsapp_cache']->show_whatsapp && !empty($cachedData['socialLinks_whatsapp_cache']->whatsapp))
                        <div id="whatsapp-float" onclick="openWhatsApp()">
                            <div style="background: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; position: relative;">
                                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.464 3.488" fill="white"/>
                                </svg>
                            </div>
                        </div>
                    @endif
                    @break
            @endswitch
            @endif
        @endforeach

        <!-- Scripts JavaScript para funcionalidades da página -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Código do carrossel dos maiores ganhos
                const carousel = document.getElementById('top-wins-carousel');
                if (carousel) {
                    const items = carousel.querySelectorAll('.peBY3');
                    if (items.length > 0) {

                        // Criar container interno para animação
                        const wrapper = document.createElement('div');
                        wrapper.style.display = 'flex';
                        wrapper.style.animation = 'carousel-scroll linear infinite';
                        wrapper.style.willChange = 'transform';

                        // Configurar o carrossel principal
                        carousel.style.overflow = 'hidden';
                        carousel.style.whiteSpace = 'nowrap';
                        carousel.style.position = 'relative';

                        // Adicionar margin em todos os itens para criar espaçamento
                        items.forEach((item) => {
                            item.style.marginLeft = '3px';
                            item.style.marginRight = '3px';
                        });

                        // Mover todos os itens para o wrapper
                        while (carousel.firstChild) {
                            wrapper.appendChild(carousel.firstChild);
                        }

                        // Duplicar os itens para criar loop infinito
                        const originalHTML = wrapper.innerHTML;
                        wrapper.innerHTML = originalHTML + originalHTML;

                        // Adicionar o wrapper ao carrossel
                        carousel.appendChild(wrapper);

                        // Calcular a largura total dos itens originais
                        setTimeout(() => {
                            const totalWidth = wrapper.scrollWidth / 2; // Dividir por 2 porque duplicamos
                            const duration = totalWidth / 100; // Velocidade: 50px por segundo (ajuste conforme necessário)

                            // Criar e inserir a animação CSS
                            const style = document.createElement('style');
                            style.textContent = `
                    @keyframes carousel-scroll {
                        0% {
                            transform: translateX(0);
                        }
                        100% {
                            transform: translateX(-${totalWidth}px);
                        }
                    }
                `;
                            document.head.appendChild(style);

                            // Aplicar a duração calculada
                            wrapper.style.animationDuration = `${duration}s`;

                            // Pausar animação no hover
                            carousel.addEventListener('mouseenter', () => {
                                wrapper.style.animationPlayState = 'paused';
                            });

                            carousel.addEventListener('mouseleave', () => {
                                wrapper.style.animationPlayState = 'running';
                            });

                            // Pausar quando a página não está visível
                            document.addEventListener('visibilitychange', () => {
                                if (document.hidden) {
                                    wrapper.style.animationPlayState = 'paused';
                                } else {
                                    wrapper.style.animationPlayState = 'running';
                                }
                            });

                        }, 100);
                    }
                }
            });

            // Função para filtrar por categoria (necessária para os botões de filtro)
            function filterByCategory(category) {
                // Atualizar botões ativos
                document.querySelectorAll('.filterWrapper .btn').forEach(btn => {
                    btn.classList.remove('categoryActive');
                });
                document.querySelector(`[data-category="${category}"]`).classList.add('categoryActive');

                // Disparar evento personalizado para que o script.js possa capturar
                window.dispatchEvent(new CustomEvent('categoryFilterChanged', {
                    detail: { category: category }
                }));
            }

            // Função para abrir o modal da roleta
            window.openRouletteModal = function() {
                const modal = document.getElementById('roulette-modal');
                if (modal) {
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    // Tentar carregar dados da roleta se a função existir
                    if (typeof loadRouletteData === 'function') {
                        loadRouletteData();
                    }
                }
            };

            // Função para abrir o WhatsApp
            window.openWhatsApp = function() {
                @if($cachedData['socialLinks_whatsapp_cache'] && $cachedData['socialLinks_whatsapp_cache']->show_whatsapp && !empty($cachedData['socialLinks_whatsapp_cache']->whatsapp))
                const whatsappUrl = '{{ $cachedData['socialLinks_whatsapp_cache']->whatsapp }}';
                // Simplesmente abre a URL do banco de dados sem formatação
                window.open(whatsappUrl, '_blank');
                @endif
            };
        </script>
    </div>


    <style>
        /* Estilos para raspadinhas */
        .raspadinha-overlay {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .price-badge {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .price-badge.normal {
            background: linear-gradient(45deg, #007bff, #0056b3);
        }

        .price-badge.turbo {
            background: linear-gradient(45deg, #ff9800, #e65100);
        }

        .plays-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        @media (min-width: 768px) {
            #banner-desktop {
                display: block!important;
            }
            #banner-mobile {
                display: none!important;
            }
        }
        @media (max-width: 768px) {
            #banner-desktop {
                display: none!important;
            }
            #banner-mobile {
                display: block!important;
            }
        }
        /* Corrige o container dos resultados da busca para não sair da div e exibir no máximo 6 itens visíveis, com rolagem para os demais */
        #search-results-container {
            max-height: 420px; /* Altura para 6 cards, ajuste conforme o tamanho dos cards */
            overflow-y: auto;
            overflow-x: hidden;
            width: 100%;
            padding-right: 4px; /* espaço para barra de rolagem */
            box-sizing: border-box;
        }
        #search-results-container > a {
            flex: 0 0 auto;
            margin-bottom: 8px;
        }

        /* Estilos do botão flutuante da roleta */
        #chat-icon {
            position: fixed;
            bottom: 190px;
            right: 13px;
            cursor: pointer;
            display: block !important;
            z-index: 600 !important;
            /* Simplificar animações para melhor performance */
            transition: transform 0.2s ease;
            /* Remover animações complexas que causam lentidão */
            animation: none;
        }

        /* Remover animações de pulsação que consomem recursos */
        @keyframes roulette-pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.7);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(255, 215, 0, 0.2);
            }
        }

        /* Simplificar animação de flutuação */
        @keyframes roulette-float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-5px);
            }
        }

        /* Otimizar animação de rotação */
        @keyframes roulette-spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        #chat-icon img {
            z-index: 999999999 !important;
            position: relative;
            border-radius: 50%;
            /* Simplificar efeitos visuais */
            filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.5));
            animation: roulette-spin 12s linear infinite;
            transition: filter 0.2s ease;
            /* Otimizações de performance */
            will-change: transform;
            transform: translateZ(0);
        }

        #chat-icon:hover {
            transform: scale(1.05);
            /* Remover animações complexas no hover */
            animation: none;
        }

        #chat-icon:hover img {
            filter: drop-shadow(0 0 8px rgba(255, 215, 0, 0.8));
            animation: roulette-spin 6s linear infinite;
        }

        #chat-icon:active {
            transform: scale(0.98);
        }

        #search-pagination {
            display: none!important;
            padding-top: 10px!important;
        }
        .resultsWrapper {
            padding-bottom: 40px!important;
        }

        /* Remover efeito de brilho complexo */
        #chat-icon::before {
            display: none;
        }

        @media only screen and (max-width: 1024px) {
            .game-view:not(:empty) ~ #chat-icon,
            .game-view.active ~ #chat-icon,
            body:has(.game-view:not(:empty)) #chat-icon,
            body:has(#accept-cookies:not(.hidden)) #chat-icon {
                display: none !important;
            }

            /* Desabilitar animações no mobile para melhor performance */
            #chat-icon img {
                animation: none;
            }
        }

        /* Estilos do botão flutuante do WhatsApp - otimizado */
        #whatsapp-float {
            position: fixed;
            bottom: 80px;
            right: 20px;
            cursor: pointer;
            display: block !important;
            z-index: 600 !important;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(37, 211, 102, 0.3);
            /* Remover transformações que causam o "pulo" inicial */
            /* Simplificar animação */
            animation: whatsapp-float 4s ease-in-out infinite;
        }

        /* Animação de flutuação mais suave */
        @keyframes whatsapp-float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-4px);
            }
        }

        /* Hover mais simples */
        #whatsapp-float:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.5);
            /* Remover animação complexa no hover */
            animation: none;
        }

        #whatsapp-float:active {
            transform: scale(0.98);
        }

        /* Responsividade otimizada */
        @media only screen and (max-width: 1024px) {
            #whatsapp-float {
                bottom: 80px;
                right: 15px;
                /* Desabilitar animações no mobile */
                animation: none;
            }

            #whatsapp-float div {
                width: 55px !important;
                height: 55px !important;
            }

            #whatsapp-float svg {
                width: 30px !important;
                height: 30px !important;
            }

            .game-view:not(:empty) ~ #whatsapp-float,
            .game-view.active ~ #whatsapp-float,
            body:has(.game-view:not(:empty)) #whatsapp-float,
            body:has(#accept-cookies:not(.hidden)) #whatsapp-float {
                display: none !important;
            }
        }
    </style>
    @if(App\Helpers\Core::getSetting()->tawkto_active)
        <!--Start of Tawk.to Script-->
        <script type="text/javascript">
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){
                var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                s1.async=true;
                s1.src='{{ App\Helpers\Core::getSetting()->tawkto_src }}';
                s1.charset='UTF-8';
                s1.setAttribute('crossorigin','*');
                s0.parentNode.insertBefore(s1,s0);
            })();

            // Custom styling of Offset starts here
            Tawk_API.customStyle = {
                visibility: {
                    desktop: {
                        position: 'br',
                        xOffset: '30px',
                        yOffset: 20
                    },
                    mobile: {
                        position: 'br',
                        xOffset: 10,
                        yOffset: '80px'
                    },
                    bubble: {
                        rotate: '0deg',
                        xOffset: -999999, // Move para fora da tela
                        yOffset: -999999, // Move para fora da tela
                        opacity: 0 // Tenta definir opacidade zero
                    }
                }
            };
        </script>
        <!--End of Tawk.to Script-->
    @endif

    @if(App\Helpers\Core::getSetting()->jivochat_active)
        <!--Start of JivoChat Script-->
        <script type="text/javascript">
            (function(){
                var widget_id = '{{ App\Helpers\Core::getSetting()->jivochat_src }}';
                var s = document.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = '//code.jivosite.com/script/widget/'+widget_id;
                var ss = document.getElementsByTagName('script')[0];
                ss.parentNode.insertBefore(s, ss);
            })();
        </script>
        <!--End of JivoChat Script-->
    @endif

    {{-- Script para verificar mensagens de sessão --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar mensagem de erro da sessão
            @if(session('error'))
            if (typeof window.mostrarMensagemErro === 'function') {
                window.mostrarMensagemErro('{{ session('error') }}');
            }
            @endif

            // Verificar mensagem de sucesso da sessão
            @if(session('success'))
            if (typeof window.mostrarMensagemSucesso === 'function') {
                window.mostrarMensagemSucesso('{{ session('success') }}');
            }
            @endif

            // Verificar mensagem de status da sessão (usado para senha alterada com sucesso)
            @if(session('status'))
            if (typeof window.mostrarMensagemSucesso === 'function') {
                window.mostrarMensagemSucesso('{{ session('status') }}');
            }
            @endif
        });

        // Passar dados do modo surpresa para o JavaScript
        @if(isset($surpresaGames) && $surpresaGames->count() > 0)
            window.surpresaGamesData = @json($surpresaGames);
        @endif

        // Verificar se o usuário está autenticado
        window.isUserAuthenticated = @json(auth()->check());

        // Função para verificar autenticação dinamicamente
        function checkUserAuthentication() {
            return new Promise((resolve) => {
                // Verificar se existe menu de usuário (indica logado)
                const userMenu = document.getElementById('userMenuButton') ||
                    document.querySelector('.user-menu') ||
                    document.querySelector('.FzpBR');

                if (userMenu && userMenu.style.display !== 'none' && !userMenu.classList.contains('hidden')) {
                    window.isUserAuthenticated = true;
                    resolve(true);
                    return;
                }

                // Verificar se existe botão de login visível (indica não logado)
                const loginButton = document.querySelector('.btn-login:not([style*="display: none"]), #btn-login:not([style*="display: none"])');
                if (loginButton) {
                    window.isUserAuthenticated = false;
                    resolve(false);
                    return;
                }

                // Fazer requisição para verificar status
                fetch('/user/check-auth', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                    .then(response => {
                        const isAuthenticated = response.ok && response.status !== 401;
                        window.isUserAuthenticated = isAuthenticated;
                        resolve(isAuthenticated);
                    })
                    .catch(() => {
                        // Fallback: verificar pelo DOM
                        const isLoggedIn = !document.querySelector('.btn-login:not([style*="display: none"])');
                        window.isUserAuthenticated = isLoggedIn;
                        resolve(isLoggedIn);
                    });
            });
        }

        // Função corrigida para abrir raspadinha (global)
        window.openRaspadinha = function(raspadinhaId) {
            // Verificação mais direta e eficiente do estado de autenticação
            function isCurrentlyAuthenticated() {
                // 1. Verificar variável global atualizada
                if (typeof window.isUserAuthenticated === 'boolean') {
                    return window.isUserAuthenticated;
                }

                // 2. Verificar presença de elementos do usuário logado no DOM
                const userMenu = document.getElementById('userMenuButton') ||
                    document.querySelector('.user-menu') ||
                    document.querySelector('.FzpBR');

                if (userMenu && userMenu.style.display !== 'none' && !userMenu.classList.contains('hidden')) {
                    window.isUserAuthenticated = true;
                    return true;
                }

                // 3. Verificar ausência de botões de login visíveis
                const loginButton = document.querySelector('.btn-login:not([style*="display: none"]), #btn-login:not([style*="display: none"])');
                if (!loginButton) {
                    window.isUserAuthenticated = true;
                    return true;
                }

                // 4. Verificar se há saldo visível (indica usuário logado)
                const balanceElement = document.querySelector('.realAmount');
                if (balanceElement && balanceElement.textContent && balanceElement.textContent.includes('R$')) {
                    window.isUserAuthenticated = true;
                    return true;
                }

                window.isUserAuthenticated = false;
                return false;
            }

            if (isCurrentlyAuthenticated()) {
                window.location.href = `/raspadinha/${raspadinhaId}`;
            } else {
                if (typeof window.abrirModalLogin === 'function') {
                    window.abrirModalLogin();
                } else {
                    window.location.href = '/login';
                }
            }
        };

        // Escutar eventos de login/logout para atualizar status
        window.addEventListener('header:updated', function(event) {
            if (event.detail) {
                if (event.detail.reason === 'login') {
                    window.isUserAuthenticated = true;
                } else if (event.detail.reason === 'logout') {
                    window.isUserAuthenticated = false;
                }
            }
        });

        // Escutar eventos específicos de autenticação
        window.addEventListener('auth:loginSuccess', function(event) {
            window.isUserAuthenticated = true;
            console.log('Estado de autenticação atualizado: logado');
        });

        window.addEventListener('auth:logoutSuccess', function(event) {
            window.isUserAuthenticated = false;
            console.log('Estado de autenticação atualizado: deslogado');
        });

        // Interceptar o sucesso do login no formulário
        document.addEventListener('DOMContentLoaded', function() {
            // Sobrescrever função de sucesso do login se existir
            if (typeof $ !== 'undefined') {
                const originalAjax = $.ajax;
                $.ajax = function(options) {
                    const originalSuccess = options.success;

                    if (options.url && options.url.includes('login') && originalSuccess) {
                        options.success = function(response) {
                            // Executar callback original
                            originalSuccess(response);
                            // Atualizar status de autenticação
                            window.isUserAuthenticated = true;
                        };
                    }

                    return originalAjax.call(this, options);
                };
            }
        });
    </script>
@endsection

@if(isset($betbyConfig) && App\Models\Settings::isBetbyActive())
    @push('scripts')
        <!-- Carregar o script do Betby para os banners -->
        <script src="{{ $betbyConfig['bt_library_url'] }}"></script>
    @endpush
@endif
