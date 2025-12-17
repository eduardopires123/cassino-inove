@php
    $Infos  = App\Helpers\Core::getSetting();

    // Definir o nome do usuário se estiver autenticado - movido para o início do arquivo
    $userName = Auth::check() ? Auth::user()->name : 'Visitante';
@endphp

<header id="divPageHeader" class="pydSP">
    <div class="_3BsQx">
        <a aria-current="page" href="{{ getCassinoUrl() }}" class="{{ !request()->routeIs('esportes*', 'sports*') ? 'router-link-active router-link-exact-active HkXi5 ywLAp' : 'HkXi5' }}">
            <span class="inove-icon inove-icon--fill">
                <svg height="1em" viewBox="0 0 640 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M220.7 7.468C247.3-7.906 281.4 1.218 296.8 27.85L463.8 317.1C479.1 343.8 470 377.8 443.4 393.2L250.5 504.5C223.9 519.9 189.9 510.8 174.5 484.2L7.468 194.9C-7.906 168.2 1.218 134.2 27.85 118.8L220.7 7.468zM143.8 277.3C136.9 303.2 152.3 329.1 178.3 336.9C204.3 343.9 230.1 328.5 237.9 302.5L240.3 293.6C240.4 293.3 240.5 292.9 240.6 292.5L258.4 323.2L246.3 330.2C239.6 334 237.4 342.5 241.2 349.2C245.1 355.9 253.6 358.1 260.2 354.3L308.4 326.5C315.1 322.6 317.4 314.1 313.5 307.4C309.7 300.8 301.2 298.5 294.5 302.3L282.5 309.3L264.7 278.6C265.1 278.7 265.5 278.8 265.9 278.9L274.7 281.2C300.7 288.2 327.4 272.8 334.4 246.8C341.3 220.8 325.9 194.1 299.9 187.1L196.1 159.6C185.8 156.6 174.4 163.2 171.4 174.3L143.8 277.3z"
                        fill="currentColor"
                    ></path>
                    <path
                        d="M324.1 499L459.4 420.9C501.3 396.7 515.7 343.1 491.5 301.1L354.7 64.25C356.5 64.08 358.2 64 360 64H584C614.9 64 640 89.07 640 120V456C640 486.9 614.9 512 584 512H360C346.4 512 333.8 507.1 324.1 499V499zM579.8 135.7C565.8 123.9 545.3 126.2 532.9 138.9L528.1 144.2L523.1 138.9C510.6 126.2 489.9 123.9 476.4 135.7C460.7 149.2 459.9 173.1 473.9 187.6L522.4 237.6C525.4 240.8 530.6 240.8 533.9 237.6L582 187.6C596 173.1 595.3 149.2 579.8 135.7H579.8z"
                        fill="currentColor"
                        opacity="0.4"
                    ></path>
                </svg>
            </span>
            {{ __('header.casino') }}
        </a>

        @if ($Infos->enable_sports === 1)
            <a href="{{ \App\Models\Settings::isBetbyActive() ? route('sports.betby') : route('esportes') }}" class="{{ request()->routeIs('esportes*', 'sports*', 'betby*') ? 'router-link-active router-link-exact-active HkXi5 ywLAp' : 'HkXi5' }}">
            <span class="inove-icon inove-icon--fill">
                <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M355.5 45.53L342.4 14.98c-27.95-9.983-57.18-14.98-86.42-14.98c-29.25 0-58.51 4.992-86.46 14.97L156.5 45.53l99.5 55.13L355.5 45.53zM86.78 96.15L53.67 99.09c-34.79 44.75-53.67 99.8-53.67 156.5L.0001 256c0 2.694 .0519 5.379 .1352 8.063l24.95 21.76l83.2-77.67L86.78 96.15zM318.8 336L357.3 217.4L255.1 144L154.7 217.4l38.82 118.6L318.8 336zM512 255.6c0-56.7-18.9-111.8-53.72-156.5L425.6 96.16L403.7 208.2l83.21 77.67l24.92-21.79C511.1 260.1 512 258.1 512 255.6zM51.77 367.7l-7.39 32.46c33.48 49.11 82.96 85.07 140 101.7l28.6-16.99l-48.19-103.3L51.77 367.7zM347.2 381.5l-48.19 103.3l28.57 17c57.05-16.66 106.5-52.62 140-101.7l-7.38-32.46L347.2 381.5z"
                        fill="currentColor"
                    ></path>
                    <path
                        d="M458.3 99.08L458.3 99.08L458.3 99.08zM511.8 264c-1.442 48.66-16.82 95.87-44.28 136.1l-7.38-32.46l-113 13.86l-48.19 103.3l28.22 16.84c-23.48 6.78-47.67 10.2-71.85 10.2c-23.76 0-47.51-3.302-70.58-9.962l28.23-17.06l-48.19-103.3l-113-13.88l-7.39 32.46c-27.45-40.19-42.8-87.41-44.25-136.1l24.95 21.76l83.2-77.67L86.78 96.15L53.67 99.09c29.72-38.29 69.67-67.37 115.2-83.88l.3613 .2684L156.5 45.53l99.5 55.13l99.5-55.13L342.4 14.98c45.82 16.48 86 45.64 115.9 84.11L425.6 96.16L403.7 208.2l83.21 77.67L511.8 264zM357.3 217.4L255.1 144L154.7 217.4l38.82 118.6L318.8 336L357.3 217.4z"
                        fill="currentColor"
                        opacity="0.4"
                    ></path>
                </svg>
            </span>
                {{ __('header.sports') }}
            </a>
        @endif
    </div>
    <div class="RcNcf ntdfP">
        <div class="_0vvpa">
            <div class="pZd1o ntdfP">
                <div class="mP6gC">
                    <button class="text-2xl text-header-texts">
                        <span class="inove-icon inove-icon--fill">
                            <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                    </button>
                </div>
                <h1 class="flex w-full relative">
                    <div class="l6oz0 !max-h-[42px] !h-9 w-full md:!h-12 pr-2 -mb-1">
                        <a aria-current="page" href="{{ getCassinoUrl() }}" class="{{ request()->routeIs('home*') ? 'router-link-active router-link-exact-active bwSJI' : 'bwSJI' }}" aria-label="{{ $Infos->name ?? config('app.name') }}">
                            <img alt="{{ $Infos->name ?? config('app.name') }}" class="Ueilo" src="{{ completeImageUrl($Infos->logo ?? 'img/logo-inove.png') }}" />
                            <img alt="{{ $Infos->name ?? config('app.name') }}" class="j2x6J" src="{{ completeImageUrl($Infos->logo ?? 'img/logo-inove.png') }}" />
                        </a>
                        <a
                            @auth
                                href="{{route('lucky.boxes')}}"
                            @else
                                href="javascript:void(0)"
                            onclick="document.getElementById('login-modal-overlay').style.display = 'block'; document.getElementById('login-modal').style.display = 'block';"
                            @endauth
                            class="_1KwUd" data-auth-link="true"
                            aria-label="Shop">
                            <span class="inove-icon inove-icon--fill Z5r6o">
                                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M280.1 44.45C296.3 16.91 325.9 0 357.8 0H360C408.6 0 448 39.4 448 88C448 136.6 408.6 176 360 176H288V256H224V176H152C103.4 176 64 136.6 64 88C64 39.4 103.4 0 152 0H154.2C186.1 0 215.7 16.91 231.9 44.45L256 85.46L280.1 44.45zM190.5 68.78C182.9 55.91 169.1 48 154.2 48H152C129.9 48 112 65.91 112 88C112 110.1 129.9 128 152 128H225.3L190.5 68.78zM286.7 128H360C382.1 128 400 110.1 400 88C400 65.91 382.1 48 360 48H357.8C342.9 48 329.1 55.91 321.5 68.78L286.7 128zM224 512V288H288V512H224z" fill="currentColor"></path>
                                    <path
                                        d="M152 176H224V256H32C14.33 256 0 241.7 0 224V160C0 142.3 14.33 128 32 128H73.6C88.16 156.5 117.8 176 152 176zM480 256H288V176H360C394.2 176 423.8 156.5 438.4 128H480C497.7 128 512 142.3 512 160V224C512 241.7 497.7 256 480 256zM32 288H224V512H80C53.49 512 32 490.5 32 464V288zM288 512V288H480V464C480 490.5 458.5 512 432 512H288z" fill="currentColor" opacity="0.6"
                                    ></path>
                                </svg>
                            </span>
                            <div class="Qj2aO"></div>
                        </a>
                    </div>
                </h1>
            </div>
            <div class="F2Y1D">
                @guest
                    <div data-v-0dcb06ac="" class="buttons new guest-only">
                        <button data-v-0dcb06ac="" class="btn btn-cta btn-register new inove" type="button" id="btn-register" onclick="abrirModalRegistro()">
                            <span data-v-0dcb06ac="">{{ __('header.register') }}</span>
                            <div data-v-0dcb06ac="" class="P2PzG" style="background: #ffffff;">
                                <img data-v-0dcb06ac="" src="{{ asset('img/gift.png') }}" /><span data-v-0dcb06ac="">100%</span>
                            </div>
                        </button>
                        <button data-v-0dcb06ac="" class="btn btn-login new" id="btn-login">
                            {{ __('header.login') }}
                        </button>
                    </div>
                @else
                    <div data-v-fe39fd3e="" data-v-0dcb06ac="" class="flex gap-[.4rem] pt-px xl:gap-[.6rem] auth-required">
                        <button data-v-fe39fd3e="" id="open-deposit-modal" class="btn btn-cta btn-deposit new inove">
                            <div data-v-fe39fd3e="" class="depositButtonPill">
                                <span data-v-fe39fd3e="" class="inove-icon">
                                <img src="{{ asset('img/pix.svg') }}" alt="" style="width: 10px; height: 10px;">
                                </span>
                                <span data-v-fe39fd3e="">PIX</span>
                            </div>
                            {{ __('header.deposit') }}
                        </button>
                        <div data-v-fe39fd3e="" class="btn btn-cta btn-balance new inove relative" style="opacity: 1 !important;">
                            <button data-v-fe39fd3e="" aria-label="Update Wallet Balance" class="reloadButton text-header-login-text">
                                <span data-v-fe39fd3e="" class="inove-icon inove-icon--fill reload-icon">
                                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M449.9 39.96l-48.5 48.53C362.5 53.19 311.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.97 5.5 34.86-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c37.96 0 73 14.18 100.2 37.8L311.1 178C295.1 194.8 306.8 223.4 330.4 224h146.9C487.7 223.7 496 215.3 496 204.9V59.04C496 34.99 466.9 22.95 449.9 39.96z"
                                            fill="currentColor"
                                        ></path>
                                        <path
                                            d="M462.4 329.8C433.4 419.7 350.4 480 255.1 480c-55.41 0-106.5-21.19-145.4-56.49l-48.5 48.53C45.07 489 16 477 16 452.1V307.1C16 296.7 24.32 288.3 34.66 288h146.9c23.57 .5781 35.26 29.15 18.43 46l-44.18 44.2C183 401.8 218 416 256 416c66.58 0 125.1-42.53 145.5-105.8c5.422-16.78 23.36-26.03 40.3-20.59C458.6 294.1 467.9 313 462.4 329.8z"
                                            fill="currentColor"
                                            opacity="0.4"
                                        ></path>
                                    </svg>
                                </span>
                            </button>
                            <div data-v-fe39fd3e="" class="flex flex-col gap-0">
                                <a data-v-fe39fd3e="" href="{{ route('user.wallet') }}" class="realAmount balance-trigger" id="balance_header">R$&nbsp;{{ number_format(Auth::user()->wallet->balance ?? 0, 2, ',', '.') }}</a>
                            </div>

                            <div id="balanceDropdown" class="balance-dropdown-menu" style="display: none; position: absolute; right: 0; top: 110%; min-width: 220px; background: #323637; border-radius: 12px; box-shadow: 0 2px 12px #00000040; z-index: 100; padding: 0.7rem 0;">
                                <div style="padding: 0.7rem 1.2rem; border-bottom: 1px solid #434647; font-size: 1.05em;">
                                    <div class="saldo-bloco">
                                        <span class="saldo-icone">
                                            <svg fill="#bdbebe" width="22px" height="22px" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M0 405.3V448c0 35.3 86 64 192 64s192-28.7 192-64v-42.7C342.7 434.4 267.2 448 192 448S41.3 434.4 0 405.3zM320 128c106 0 192-28.7 192-64S426 0 320 0 128 28.7 128 64s86 64 192 64zM0 300.4V352c0 35.3 86 64 192 64s192-28.7 192-64v-51.6c-41.3 34-116.9 51.6-192 51.6S41.3 334.4 0 300.4zm416 11c57.3-11.1 96-31.7 96-55.4v-42.7c-23.2 16.4-57.3 27.6-96 34.5v63.6zM192 160C86 160 0 195.8 0 240s86 80 192 80 192-35.8 192-80-86-80-192-80zm219.3 56.3c60-10.8 100.7-32 100.7-56.3v-42.7c-35.5 25.1-96.5 38.6-160.7 41.8 29.5 14.3 51.2 33.5 60 57.2z"></path></svg>
                                        </span>
                                        <span class="saldo-info">
                                            <span class="saldo-label">{{ __('header.balance_limit') }}</span>
                                            <span class="saldo-valor moeda"><span class="" id="balancesaldo_header_2"><i class="fa fa-spinner fa-spin" style="font-size: 0.75em;"></i></span></span>
                                        </span>
                                    </div>
                                </div>
                                <div style="padding: 0.7rem 1.2rem; border-bottom: 1px solid #434647; font-size: 1.05em;">
                                    <div class="saldo-bloco">
                                        <span class="saldo-icone">
                                            <svg width="22px" height="22px" viewBox="0 0 16 16" fill="#bdbebe" xmlns="http://www.w3.org/2000/svg"><path d="M3 3V0H5C6.65685 0 8 1.34315 8 3C8 1.34315 9.34315 0 11 0H13V3H16V6H0V3H3Z" fill="#bdbebe"></path><path d="M1 8H7V15H1V8Z" fill="#bdbebe"></path><path d="M15 8H9V15H15V8Z" fill="#bdbebe"></path></svg>
                                        </span>
                                        <span class="saldo-info">
                                            <span class="saldo-label">{{ __('header.balance_bonus') }}</span>
                                            <span class="saldo-valor moeda"><span class="" id="balancebonus_header_2"><i class="fa fa-spinner fa-spin" style="font-size: 0.75em;"></i></span></span>
                                        </span>
                                    </div>
                                </div>
                                <div style="padding: 0.7rem 1.2rem; border-bottom: 1px solid #434647; font-size: 1.05em;">
                                    <div class="saldo-bloco">
                                        <span class="saldo-icone">
                                            <svg width="22px" height="22px" viewBox="0 0 16 16" fill="#bdbebe" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1 4C1 2.34315 2.34315 1 4 1H15V3H4V5H15V15H4C2.34315 15 1 13.6569 1 12V4ZM12 11C12.5523 11 13 10.5523 13 10C13 9.44771 12.5523 9 12 9C11.4477 9 11 9.44771 11 10C11 10.5523 11.4477 11 12 11Z" fill="#bdbebe"></path></svg>
                                        </span>
                                        <span class="saldo-info">
                                            <span class="saldo-label">{{ __('header.balance') }}</span>
                                            <span class="saldo-valor moeda"><span class="" id="balance_header_2"><i class="fa fa-spinner fa-spin" style="font-size: 0.75em;"></i></span></span>
                                        </span>
                                    </div>
                                </div>
                                <div style="padding: 0.7rem 1.2rem;">
                                    <a href="{{ route('user.wallet') }}" class="btn btn-cta new inove" style="color: var(--text-btn-primary)!important;border-radius: 0.375rem;">
                                        <strong>{{ __('header.view_wallet') }}</strong>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div data-v-fe39fd3e="" data-headlessui-state="" class="relative inline-block text-left z-50">
                            <button id="userMenuButton" type="button" aria-haspopup="menu" aria-expanded="false" data-headlessui-state="" aria-label="Open User Menu" class="JBYxv IKuQK b8hOA inove">
                                <div class="Ae8DU _1Eexe">
                                    <div class="LX5Sm">
                                    <span class="nuxt-icon nuxt-icon--fill">
                                        <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M256 272c39.77 0 72-32.24 72-72S295.8 128 256 128C216.2 128 184 160.2 184 200S216.2 272 256 272zM288 320H224c-47.54 0-87.54 29.88-103.7 71.71C155.1 426.5 203.1 448 256 448s100.9-21.53 135.7-56.29C375.5 349.9 335.5 320 288 320z" fill="currentColor"></path>
                                            <path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 128c39.77 0 72 32.24 72 72S295.8 272 256 272c-39.76 0-72-32.24-72-72S216.2 128 256 128zM256 448c-52.93 0-100.9-21.53-135.7-56.29C136.5 349.9 176.5 320 224 320h64c47.54 0 87.54 29.88 103.7 71.71C356.9 426.5 308.9 448 256 448z" fill="currentColor" opacity="0.4"></path>
                                            </svg>
                                    </span>
                                    </div>
                                    <div class="D2jcZ">
                                        @php
                                            // Definir o nome do usuário se estiver autenticado
                                            $userName = Auth::check() ? Auth::user()->name : 'Visitante';

                                            // Definir ranking e progresso
                                            $ranking = Auth::check() ? Auth::user()->getRanking() : ['level' => 1, 'name' => 'Bronze', 'image' => 'img/ranking/1.png', 'progress' => 0];
                                            $progress = $ranking['progress'];

                                            // Definir imagem do perfil do usuário, com imagem padrão se não existir
                                            $userImage = Auth::check() && Auth::user()->image ? Auth::user()->image : 'img/avatar/12.png';
                                        @endphp
                                        <img alt="{{ $ranking['name'] }} - User Level Icon" draggable="false" src="{{ asset($ranking['image']) }}">
                                        <div class="l9lTb">
                                            <div class="xaSJG" style="height: 32px; width: 32px; position: relative;">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="16 16 32 32" style="transform: rotate(-90deg); overflow: visible;" fill="#000!important;">
                                                    <circle cx="32" cy="32" r="14.5" stroke="#000000" stroke-width="3" fill="none"></circle>
                                                    @php
                                                        // Reutilizando o mesmo cálculo para manter consistência
                                                        $circumference = 91.106186954104;
                                                        $dashoffset = $circumference * (1 - ($progress / 100));
                                                    @endphp
                                                    <circle cx="32" cy="32" r="14.5" fill="none" stroke-width="3" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $dashoffset }}" stroke-linecap="round" stroke="currentColor" style="transition: stroke-dashoffset 800ms;"></circle>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="JxY9S">{{ $ranking['name'] }} {{ $ranking['level'] }}</div>
                                </div>
                                <!---->
                            </button>
                            @include('profile.partials.user-dropdown')
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</header>

@auth
    @include('profile.partials.avatar-edit')
    @include('profile.partials.name-edit')
@endauth

<style>
    /* Estilo para o botão de logout */
    .XeHqK.wMNL2 {
        background-color: rgba(220, 53, 69, 0.1) !important;
        color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .XeHqK.wMNL2:hover {
        background-color: rgba(220, 53, 69, 0.2) !important;
    }

    .XeHqK.wMNL2 .OzfKA svg path {
        fill: #dc3545 !important;
    }

    /* Estilos para o dropdown do saldo */
    .btn-balance {
        position: relative;
    }

    .balance-dropdown-menu {
        transition: opacity 0.2s ease-in-out;
    }

    .balance-dropdown-menu.show {
        display: block !important;
        opacity: 1;
    }

    /* Layout saldo: ícone à esquerda, texto acima, valor abaixo com símbolo ao lado */
    .saldo-bloco {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 0.7em;
    }
    .saldo-bloco .saldo-icone {
        flex-shrink: 0;
        display: flex;
        align-items: center;
    }
    .saldo-bloco .saldo-info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.1em;
    }
    .saldo-bloco .saldo-label {
        color: #bdbdbd;
        font-weight: 400;
        line-height: 1.1;
    }
    .saldo-bloco .saldo-valor {
        color: #fff;
        font-weight: 700;
        display: flex;
        align-items: baseline;
        gap: 0.2em;
    }
    .saldo-bloco .saldo-valor .moeda {
        color: #bdbdbd;
        font-size: 0.95em;
        font-weight: 600;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const balanceButton = document.querySelector('.btn-balance');
        const balanceTrigger = document.querySelector('.balance-trigger');
        const balanceDropdown = document.getElementById('balanceDropdown');
        let timeoutId;
        let isMobile = window.innerWidth <= 768;

        // Função para mostrar o dropdown
        function showDropdown() {
            balanceDropdown.style.display = 'block';
            setTimeout(() => {
                balanceDropdown.classList.add('show');
            }, 10);
        }

        // Função para esconder o dropdown
        function hideDropdown() {
            balanceDropdown.classList.remove('show');
            setTimeout(() => {
                balanceDropdown.style.display = 'none';
            }, 200);
        }

        // Atualizar isMobile quando a janela for redimensionada
        window.addEventListener('resize', () => {
            isMobile = window.innerWidth <= 768;
        });

        // Eventos para desktop (hover)
        if (!isMobile) {
            if (balanceButton) {
                balanceButton.addEventListener('mouseenter', () => {
                    clearTimeout(timeoutId);
                    showDropdown();
                });

                balanceButton.addEventListener('mouseleave', () => {
                    timeoutId = setTimeout(() => {
                        hideDropdown();
                    }, 200);
                });
            }
        }

        // Eventos para mobile (click)
        if (balanceTrigger) {
            balanceTrigger.addEventListener('click', (e) => {
                e.preventDefault();
                if (balanceDropdown.style.display === 'none') {
                    showDropdown();
                } else {
                    hideDropdown();
                }
            });
        }

        // Fechar dropdown ao clicar fora
        document.addEventListener('click', (e) => {
            if (balanceButton) {
                if (!balanceButton.contains(e.target)) {
                    hideDropdown();
                }
            }
        });

        // Fechar dropdown ao clicar no botão de carteira no mobile
        if (balanceDropdown) {
            const walletButton = balanceDropdown.querySelector('a[href*="wallet"]');
            if (walletButton) {
                walletButton.addEventListener('click', () => {
                    if (isMobile) {
                        hideDropdown();
                    }
                });
            }
        }
    });
</script>

<!-- Elementos que aparecem apenas quando o usuário está logado ou deslogado -->
<div class="logout-only guest-only">
    <!-- Conteúdo existente para usuários não logados -->
</div>

<div class="login-only auth-required" style="display: none;">
    <!-- Conteúdo para usuários logados -->
</div>
