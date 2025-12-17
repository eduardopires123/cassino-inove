<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $topbarClosed ? 'topbar-closed' : '' }}">
<head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="{{ $siteName }}">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

            <title>{{ $siteName }}@if($siteSubtitle) - {{ $siteSubtitle }}@elseif($siteSubname && $siteSubname != $siteName) - {{ $siteSubname }}@endif</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover, minimal-ui">
            <meta name="description" content="{{ $siteDescription }}">
            <meta name="og:title" content="{{ $siteName }}">
            <meta name="og:site_name" content="{{ $siteSubname ?: $siteName }}">
            <meta name="og:type" content="website">
            <meta name="og:description" content="{{ $siteDescription }}">
            @if($siteFavicon)
            <link rel="icon" href="{{ completeImageUrl($siteFavicon) }}" type="image/png">
            @elseif($Infos->favicon)
            <link rel="icon" href="{{ completeImageUrl($Infos->favicon) }}" type="image/png">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">
    @if ($activeTheme == 1)
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @elseif ($activeTheme == 2)
        <link href="{{ asset('css/app2.css') }}" rel="stylesheet">
    @elseif ($activeTheme == 3)
        <link href="{{ asset('css/app3.css') }}" rel="stylesheet">
    @elseif ($activeTheme == 4)
        <link href="{{ asset('css/app4.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endif
    <link href="{{ asset('css/avatar-modal.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mobile-filters.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.css">
    <link rel="stylesheet" href="{{ asset('css/banner-modern.css') }}">

    @if(!Auth::check() || (Auth::check() && Auth::user()->is_admin > 1))
        <script disable-devtool-auto src="https://cdn.jsdelivr.net/npm/disable-devtool"></script>
    @endif

    @if(Auth::check())
        <meta name="user-name" content="{{ $User->name }}">
    @endif
    <style>
        {!! App\Http\Controllers\Admin\CustomCSSController::getAllInlineCss() !!}
        :root {
            --sidebar-top-value: {{ $sidebarTopValue }};
        }
        html {
            position: fixed;
            overflow: hidden;
            width: 100%;
            height: 100%;
        }

        body {
            width: 100vw;
            height: 100vh;
            overflow-x: hidden;
            overflow-y: auto;
            position: relative;
            touch-action: manipulation;
            -webkit-text-size-adjust: 100%;
        }

        * {
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
        }
        @media (max-width: 768px) {
            .banner-desktop {
                display: none;
            }
            .banner-mobile {
                display: block;
            }

            /* Prevenir zoom automático em inputs no mobile */
            .input[data-v-44b1d268],
            input.input,
            input[type="text"],
            input[type="password"],
            input[type="email"],
            input[type="tel"] {
                font-size: 16px !important;
            }

            /* Ajustar o label quando o input tem 16px */
            .label[data-v-44b1d268],
            .peer:placeholder-shown ~ .label[data-v-44b1d268] {
                font-size: 14px !important;
            }

            .peer:focus ~ .label[data-v-44b1d268],
            .peer:not(:placeholder-shown) ~ .label[data-v-44b1d268] {
                font-size: 12px !important;
            }
        }

         /* Ocultar em desktop */
         @media (min-width: 769px) {
            #divInstallAppPopup {
                display: none !important;
            }
         }
    </style>
    @stack('styles')
    @yield('head')
</head>
<body style="overflow-x: hidden !important;">

<div id="placegame"></div>
<div id="__inove" data-v-app="">
    <div id="divPageLayout" class="HA-nb index___pt-br page-container">
        @include('partials.header-mobile')
        <div id="divPageHeaderWrapper" class="S9VkN desktop-header-wrapper">
            @if($footerSettings->show_topbar)
                <div id="divTopBar" class="oC8aN" style="{{ $topbarStyle }}">
                    <span>{{ $footerSettings->topbar_text }}</span>
                    <button onclick="window.location.href='{{ $footerSettings->topbar_button_url }}'">{{ $footerSettings->topbar_button_text }}</button>
                    <div class="KiosR" onclick="closeTopBar()">
                            <span class="inove-icon inove-icon--fill">
                                <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                                          fill="currentColor"></path>
                                </svg>
                            </span>
                    </div>
                </div>
            @endif
            {{-- Header --}}
            @include('partials.header')
        </div>
        {{-- Sidebar --}}
        @include('partials.sidebar')

        <div class="Au03f ZsSUH">
            @include('partials.+18')
            @include('esportes.partials.banners')
            <div data-esportes-area>
                @yield('esportes')
            </div>
            <div class="UrrmK z-10">
                @yield('content')
            </div>
            <div class="jbmAp" style="--47d083a8: translateX(-25%); --43dee2fa: 10px;">
                <span class="nuxt-icon nuxt-icon--fill">
                    <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"
                              fill="currentColor"></path>
                    </svg>
                </span>
                <span>{{ __('layout.back_to_top') }}</span>
            </div>

            {{-- Footer --}}
            @if(View::exists('partials.footer'))
                @include('partials.footer')
            @endif
        </div>
    </div>
</div>

{{-- Auth Modals --}}
<div class="modal-overlay" id="login-modal-overlay">
    @include('auth.login-modal')
</div>

<div class="modal-overlay" id="register-modal-overlay">
    @include('auth.register-modal')
</div>

@include('payment.deposit-modal', ['isFirstDeposit' => $isFirstDeposit])

<!-- Scripts do final do body -->
<link rel="preload" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" as="style">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lrsjng.jquery-qrcode/0.14.0/jquery-qrcode.js"></script>

<script src="{{ asset('js/depdrawn.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/sliderNavigation.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/auth-modals.js') }}"></script>
<script src="{{ asset('js/sidebar.js') }}"></script>
<script src="{{ asset('js/game.js') }}"></script>

<script src="{{ asset('js/back-to-top.js') }}"></script>
<script src="{{ asset('js/banner.js') }}" defer></script>
<script src="{{ asset('js/mobile-controls.js') }}"></script>

@yield('scripts')
@stack('scripts')
<script>
    function IsMobile() {
        return /Mobi|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    // Controle de abas - apenas redirecionar se realmente for uma nova aba
    const channel = new BroadcastChannel('tab');
    let lastMessageTime = 0;

    // Inicializar flag de atualização AJAX se não existir
    if (typeof window.isAjaxUpdate === 'undefined') {
        window.isAjaxUpdate = false;
    }

    // Enviar mensagem apenas se não for uma atualização AJAX
    setTimeout(() => {
        if (!window.isAjaxUpdate) {
            const messageTime = Date.now();
            channel.postMessage({type: 'another-tab', timestamp: messageTime, tabId: sessionStorage.getItem('tab_id')});
        }
    }, 100);

    channel.addEventListener('message', (msg) => {
        // Ignorar mensagens muito recentes (pode ser da mesma aba)
        const now = Date.now();
        if (now - lastMessageTime < 200) {
            return;
        }
        lastMessageTime = now;

        // Não redirecionar se for uma atualização AJAX
        if (window.isAjaxUpdate) {
            return;
        }

        // Verificar se a mensagem é de outra aba
        const currentTabId = sessionStorage.getItem('tab_id');
        if (msg.data) {
            if (msg.data === 'another-tab') {
                // Mensagem simples - verificar se não é da mesma aba
                if (!window.isAjaxUpdate) {
                    window.location.href = '/tabControl';
                }
            } else if (msg.data.type === 'another-tab' && msg.data.tabId !== currentTabId) {
                // Mensagem com tabId - verificar se é de outra aba
                if (!window.isAjaxUpdate) {
                    window.location.href = '/tabControl';
                }
            }
        }
    });

    // Configuração global para todas as solicitações AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Configuração global para URL e CSRF Token
    window.Laravel = {
        baseUrl: "{{ config('app.url') }}",
        csrfToken: "{{ csrf_token() }}"
    };

    function getHeaderHeight() {
        const header = document.querySelector('.S9VkN, .desktop-header-wrapper, header');

        console.log("Header: ", header);
        return header ? header.offsetHeight : 80;
    }

    // Função para fechar a barra superior
    function closeTopBar() {
        // Esconder o topbar
        const topBar = document.getElementById('divTopBar');
        if (topBar) {
            topBar.style.display = 'none';
        }

        // Salvar em cookie com um tempo de expiração longo
        const date = new Date();
        date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000)); // 1 ano
        document.cookie = "topbar_closed=true; expires=" + date.toUTCString() + "; path=/; SameSite=Lax";

        // Adicionar a classe topbar-closed ao html para controlar a sidebar por CSS
        document.documentElement.classList.add('topbar-closed');

        // Atualizar a variável CSS root para o valor correto
        document.documentElement.style.setProperty('--sidebar-top-value', '65px');

        // Atualizar diretamente a sidebar apenas acima de 1024px
        if (window.innerWidth >= 1024) {
            const sidebarMenu = document.getElementById('divSidebarMenu');
            if (sidebarMenu) {
                sidebarMenu.style.setProperty('--7e9dc732', '65px', 'important');
                sidebarMenu.style.setProperty('--372e3822', '0px', 'important');
            }
        }

        // Chamar a função do app.js se existir (para sincronizar o estado)
        if (typeof window.sidebarControl !== 'undefined' && typeof window.sidebarControl.closeTopBar === 'function') {
            window.sidebarControl.closeTopBar();
        }

        if (typeof window.betbyRenderer !== 'undefined') {
            window.betbyRenderer.updateOptions(
                {
                    stickyTop: getHeaderHeight(),
                }
            );
        }
    }

    // Verificar cookie assim que o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', function () {
        const topbarClosed = document.cookie.split(';').some(item => item.trim().startsWith('topbar_closed=true'));
        const topBar = document.getElementById('divTopBar');
        const sidebarMenu = document.getElementById('divSidebarMenu');

        // Se o topbar deve estar fechado, garantir que esteja oculto
        if (topbarClosed) {
            if (topBar) {
                topBar.style.display = 'none';
            }

            // Garantir que a classe topbar-closed está aplicada
            document.documentElement.classList.add('topbar-closed');

            // Atualizar a variável CSS root
            document.documentElement.style.setProperty('--sidebar-top-value', '65px');

            // Atualizar altura da sidebar apenas acima de 1024px
            if (window.innerWidth >= 1024 && sidebarMenu) {
                sidebarMenu.style.setProperty('--7e9dc732', '65px', 'important');
                sidebarMenu.style.setProperty('--372e3822', '0px', 'important');
            }
        } else {
            // Se o topbar está visível, garantir que o sidebar use 105px
            // Verificar se o topbar realmente está visível
            if (topBar) {
                const computedStyle = window.getComputedStyle(topBar);
                const isTopBarVisible = computedStyle.display !== 'none' && topBar.offsetParent !== null;

                if (isTopBarVisible && window.innerWidth >= 1024 && sidebarMenu) {
                    sidebarMenu.style.setProperty('--7e9dc732', '105px', 'important');
                    sidebarMenu.style.setProperty('--372e3822', '0px', 'important');
                    document.documentElement.style.setProperty('--sidebar-top-value', '105px');
                }
            }
        }

        // Aguardar um pouco para garantir que o app.js já inicializou
        setTimeout(function() {
            if (window.sidebarControl && typeof window.sidebarControl.adjustClasses === 'function') {
                window.sidebarControl.adjustClasses();
            }
        }, 100);
    });

    // Adicionar listener para redimensionamento da janela
    window.addEventListener('resize', function () {
        const topbarClosed = document.cookie.split(';').some(item => item.trim().startsWith('topbar_closed=true'));
        const sidebarMenu = document.getElementById('divSidebarMenu');

        if (sidebarMenu && window.innerWidth >= 1024) {
            if (topbarClosed) {
                sidebarMenu.style.setProperty('--7e9dc732', '65px', 'important');
                sidebarMenu.style.setProperty('--372e3822', '0px', 'important');
            } else {
                sidebarMenu.style.setProperty('--7e9dc732', '105px', 'important');
                sidebarMenu.style.setProperty('--372e3822', '0px', 'important');
            }
        } else if (sidebarMenu) {
            sidebarMenu.style.removeProperty('--7e9dc732');
        }
    });

    function CheckPayment(id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: "/chk-py-z3m5/" + id,
                type: "GET",
                success: function (response) {
                    if (response.status !== undefined) {
                        resolve(response.status === true);
                    } else {
                        resolve(false);
                    }
                },
                error: function (xhr) {
                    reject(new Error("Erro ao checar pagamento"));
                }
            });
        });
    }

    let interval = 0;

    async function startCountdown(id, duration, display, progressBar) {
        let timer = duration;
        const totalWidth = 100;
        interval = setInterval(async () => {
            let minutes = Math.floor(timer / 60);
            let seconds = timer % 60;

            seconds = seconds < 10 ? '0' + seconds : seconds;

            display.textContent = `${minutes}:${seconds}`;

            const percentage = (timer / duration) * totalWidth;
            progressBar.style.width = percentage + '%';

            const Pago = await CheckPayment(id);

            if (Pago) {
                clearInterval(interval);

                document.getElementById('qrCodeModal').classList.add('hidden');
                document.getElementById('qrCodeModal').classList.remove('show');

                mostrarMensagemSucesso('Pagamento recebido!');
            }

            if (--timer < 0) {
                clearInterval(interval);
                display.textContent = '0:00';
                progressBar.style.width = '0%';

                document.getElementById('tempopagar').classList.toggle('hidden');
                document.getElementById('tempoexpirado').classList.toggle('hidden');
            }
        }, 1000);
    }

    function PagPix() {
        let valor = document.getElementById('depositAmount').value;

        const loadingElement = document.getElementById('depositButton');
        const loadingElement2 = document.querySelector('#open-deposit-modal[tabindex="1"]');

        const target = loadingElement || loadingElement2;

        target.innerHTML = "<i class=\"fa fa-spinner fa-spin\"></i>";
        target.disabled = true;

        const data = {
            amount: valor,
            accept_bonus: 0,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        $.ajax({
            url: "/fin-d3p-k8n2",
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status == false) {
                    if (response.message == "anti_bot") {
                        OpenBlockSaqueModal();
                    } else {
                        mostrarMensagemErro(response.message);

                        target.innerHTML = "DEPOSITAR";
                        target.disabled = false;
                    }
                } else {
                    const depositModal = document.getElementById('depositModal');
                    const qrCodeModal = document.getElementById('qrCodeModal');

                    depositModal.classList.remove('show');
                    depositModal.classList.add('hidden'); // Esconde o modal de depósito

                    qrCodeModal.classList.remove('hidden');
                    qrCodeModal.classList.add('show'); // Mostra o modal QR Code

                    if (response.qrcode === response.copiacola) {
                        var options = {
                            render: 'canvas',
                            left: 0,
                            top: 0,
                            size: 150,
                            fill: '#000',
                            text: response.qrcode,
                            image: null
                        };

                        $('#qr-code-img').empty().qrcode(options);

                        setTimeout(function () {
                            var canvas = $('#qr-code-img canvas')[0];
                            if (canvas) {
                                var dataURL = canvas.toDataURL("image/png");
                                document.getElementById('qr-code-img').src = dataURL;
                            }
                        }, 100);
                    } else {
                        document.getElementById('qr-code-img').style.height = '150px';
                        document.getElementById('qr-code-img').style.width = '150px';
                        document.getElementById('qr-code-img').src = 'data:image/png;base64, ' + response.qrcode;
                    }

                    document.getElementById('qr-code-copiacola').value = response.copiacola;
                    document.getElementById('valorpag').value = 'R$ ' + response.valor;

                    const countdownDuration = 5 * 60;
                    const display = document.getElementById('time');
                    const progressBar = document.getElementById('barratempo');

                    startCountdown(response.pedido, countdownDuration, display, progressBar);
                }
            },
            error: function (xhr) {
                // Restaurar o botão
                target.innerHTML = "DEPOSITAR";
                target.disabled = false;

                if (xhr.status === 419) {
                    mostrarMensagemErro('Sua sessão expirou. Por favor, faça login novamente.');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    mostrarMensagemErro(xhr.responseJSON.message);
                } else if (xhr.statusText) {
                    mostrarMensagemErro('Erro: ' + xhr.statusText);
                } else {
                    mostrarMensagemErro('Erro ao gerar QR Code. Tente novamente.');
                }
            }
        });
    }

    function Saque(IsAff) {
        const loadingElement = document.getElementById('saqueButton');
        loadingElement.innerHTML = "<i class=\"fa fa-spinner fa-spin\"></i>";
        loadingElement.disabled = true;

        const data = {
            amount: document.getElementById('amountsaq').value,
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        let url;

        if (IsAff) {
            url = "/fin-s4q-aff-p2r9";
        } else {
            url = "/fin-s4q-m7x1";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: data,
            success: function (response) {
                if (response.message === "anti_bot") {
                    OpenBlockSaqueModal();
                } else if (response.status == false) {
                    loadingElement.innerHTML = "SACAR";
                    loadingElement.disabled = false;

                    mostrarMensagemErro(response.message);
                } else {
                    const saqueModal = document.getElementById('saqueModal');
                    const confirmacaoSaqueModal = document.getElementById('confirmacaoSaqueModal');

                    saqueModal.classList.remove('show');
                    saqueModal.classList.add('hidden');

                    confirmacaoSaqueModal.classList.remove('hidden');
                    confirmacaoSaqueModal.classList.add('show');

                    document.getElementById('pedido').value = 'Solicitação #' + response.pedido;
                    document.getElementById('valorSaqueConfirmacao').value = 'R$ ' + response.valor;
                }
            },
            error: function (xhr) {
                if (xhr.status === 419) {
                    mostrarMensagemErro("Sua sessão expirou. Por favor, faça login novamente.");
                } else {
                    mostrarMensagemErro(xhr.message);
                }
            }
        });
    }

    window._intervalId = 0;
    window.logado = {{Auth::user() ? 1 : 0 }};

    let tabId = sessionStorage.getItem('tab_id');
    if (!tabId) {
        tabId = '{{$id}}';
        sessionStorage.setItem('tab_id', tabId);
    }

    if ((window._intervalId === 0) && (window.logado === 1)) {
        window._intervalId = setInterval(fetchRoute, 5000);
    }

    function fetchRoute() {
        $.ajax({
            url: '/usr-bl-q9w4',
            type: 'GET',
            data: {tab_id: tabId},
            success: function (response) {
                if (response.status) {
                    let bl = document.getElementById('balance_header');
                    let bl2 = document.getElementById('balance_wallet');
                    let bl3 = document.getElementById('balance_saque');
                    let bl4 = document.getElementById('balance_aff');
                    let bl5 = document.getElementById('s1r');

                    let bl6 = document.getElementById('balancesaldo_header_2');
                    let bl7 = document.getElementById('balancebonus_header_2');
                    let bl8 = document.getElementById('balance_header_2');

                    if (bl) {
                        bl.innerText = 'R$ ' + response.balance;
                    }

                    if (bl2) {
                        bl2.innerText = 'R$ ' + response.balance;
                    }

                    if (bl3) {
                        bl3.innerText = 'R$ ' + response.balance;
                    }

                    if (bl4) {
                        bl4.innerText = 'R$ ' + response.refer_rewards;
                    }

                    if (bl5) {
                        bl5.innerText = 'R$ ' + response.refer_rewards;
                    }

                    if (bl6) {
                        bl6.innerText = 'R$ ' + response.balance_total;
                    }

                    if (bl7) {
                        bl7.innerText = 'R$ ' + response.balance_bonus;
                    }

                    if (bl8) {
                        bl8.innerText = 'R$ ' + response.balance;
                    }
                } else {
                    window.location.reload();
                }
            },
            error: function (xhr) {
                window.location.reload();
            }
        });
    }

    @if(Request::query('ref') and !(bool)Auth::user())
    $(document).ready(function () {
        window.abrirModalRegistro();
    });
    @endif

    @if(session('email_verified'))
    // Mostrar toast de verificação de email bem-sucedida
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.mostrarMensagemSucesso === 'function') {
            window.mostrarMensagemSucesso('Email verificado com sucesso!');
        }
    });
    @endif

    // Controle global do status de jogo do usuário
    @if(Auth::check())
    // Função global para marcar que o usuário saiu do jogo
    function markUserAsNotPlaying() {
        if (navigator.sendBeacon) {
            // Usar FormData para incluir o token CSRF
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            navigator.sendBeacon("/gm-exit-h4n9", formData);
        } else {
            // Fallback para navegadores que não suportam sendBeacon
            $.ajax({
                url: "/gm-exit-h4n9",
                type: "POST",
                async: false,
                data: {
                    _token: "{{ csrf_token() }}"
                }
            });
        }
    }

    // Detectar quando o usuário sai da página (qualquer página)
    window.addEventListener('beforeunload', function(event) {
        markUserAsNotPlaying();
    });

    // Detectar quando a página perde o foco (usuário mudou de aba)
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'hidden') {
            markUserAsNotPlaying();
        }
    });

    // Detectar quando o usuário navega para outra página
    window.addEventListener('pagehide', function(event) {
        markUserAsNotPlaying();
    });

    // Detectar fechamento da aba/janela
    window.addEventListener('unload', function(event) {
        markUserAsNotPlaying();
    });
    @endif

        window.mindep = {{$Infos->min_dep}};
    window.minsaque = {{$Infos->min_saque_n}};
    window.wallet = {{$User->Wallet->balance ?? 0}};

    let BonusMulti      = {{$BonusMulti}};
    let isFirstDeposit  = {{$isFirstDeposit ? 'true' : 'false'}};
    let acceptBonus     = 1;

    function SetBonus(valor) {
        acceptBonus = valor;
        updateBonusDisplay();
    }

    function initializeBonusSystem() {
        const depositAmount = document.getElementById('depositAmount');
        if (depositAmount) {
            depositAmount.addEventListener('input', updateBonusDisplay);
            depositAmount.addEventListener('change', updateBonusDisplay);
        }

        const valueButtons = document.querySelectorAll('.IIFVb');
        valueButtons.forEach(button => {
            button.addEventListener('click', function() {
                setTimeout(updateBonusDisplay, 200); // Delay para garantir que o valor foi atualizado
            });
        });

        updateBonusDisplay();
    }

    function updateBonusDisplay() {
        const bonusAmountElement = document.getElementById('bonus_amount');
        if (!bonusAmountElement) return;

        const depositAmount = document.getElementById('depositAmount');
        if (!depositAmount) return;

        let rawValue = depositAmount.value;
        if (typeof rawValue === 'string') {
            rawValue = rawValue.replace(/[^\d,]/g, '').replace(',', '.');
        }
        const value = parseFloat(rawValue) || 0;

        if (acceptBonus && value > 0 && BonusMulti > 0) {
            const bonusValue = value * (BonusMulti / 100);
            bonusAmountElement.textContent = `+ R$ ${bonusValue.toFixed(2).replace('.', ',')} Bônus`;
            bonusAmountElement.style.display = 'block';
        } else {
            bonusAmountElement.textContent = '+ R$ 0,00 Bônus';
            bonusAmountElement.style.display = acceptBonus ? 'block' : 'none';
        }
    }

    window.updateBonusDisplay = updateBonusDisplay;
    window.initializeBonusSystem = initializeBonusSystem;

    document.addEventListener('DOMContentLoaded', function() {
        initializeBonusSystem();
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('#depositModal') || e.target.id === 'depositModal') {
            setTimeout(function() {
                initializeBonusSystem();
            }, 100);
        }
    });

    requestIdleCallback(() => {
        const scripts = [
            "https://sport.bookiewiseapi.com/js/Partner/IntegrationLoader.min.js",
            "https://sport.bookiewiseapi.com/js/partner/bootstrapper.min.js"
        ];

        scripts.forEach(src => {
            const s = document.createElement("script");
            s.src = src;
            s.defer = true;
            document.body.appendChild(s);
        });
    });
</script>

</body>
</html>
