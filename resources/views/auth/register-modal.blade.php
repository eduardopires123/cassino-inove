<style>
    .F9HZz {
        border-radius: .125rem .125rem .25rem;
        bottom: 0;
        right: 0;
        --tw-bg-opacity: 1;
        background-color: rgb(241 65 108/1);
        font-size: .5rem;
        padding: .25rem .25rem .125rem .375rem;
        text-align: center;
        --tw-text-opacity: 1;
    }

    .Gqmf4 {
        display: grid;
        height: 100%;
        place-items: center;
        position: absolute;
        right: 0;
    }

    .F9HZz, .Fxeuw {
        color: rgb(253 255 255 / 1);
        position: absolute;
        margin-bottom: 15px;
    }
    .hidden {
        display: none;
    }

    #btnLoadingCPF {
        color: white;
        margin-right: 10px;
        margin-bottom: 10px;
    }

    .banner-desktop {
        display: block;
    }
    .banner-mobile {
        display: none;
    }

    @media (max-width: 768px) {
        .banner-desktop {
            display: none;
        }
        .banner-mobile {
            display: block;
        }
    }
</style>
@php
    $footerSettings = \App\Models\FooterSettings::getSettings();
@endphp

<div class="hS9Wq" id="register-modal">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="pOZuF">
        <div class="rdEzG">
            <div class="_3lvVF" style="position: relative;">
                <!-- Modal de Confirmação de Cancelamento -->
                <div class="cMU8g" id="cancel-confirmation-modal-register" style="display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1000; align-items: center; justify-content: center;">
                    <div class="rClOu">
                        <img alt="Quit icon" aria-hidden="true" class="dHdm7" src="https://static.rico.bet.br/deploy-671d24bd174e6bf229486c7258f6bbb7a23492ee-be38829a19c5c5b9dc3f/assets/images/svg/quit.svg">
                        <h6 class="h2bsN">Tem certeza que deseja cancelar seu registro?</h6>
                        <button class="hQlUG" id="continue-register-btn" type="button" style="color: var(--text-btn-primary);">Continuar <span class="nuxt-icon nuxt-icon--fill"><svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
  <path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z" fill="currentColor"></path>
</svg></span></button>
                        <button class="PLsB5" id="cancel-register-btn" type="button"><span class="nuxt-icon nuxt-icon--fill"><svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
  <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z" fill="currentColor"></path>
</svg></span> Sim, quero cancelar</button>
                    </div>
                </div>
                <div class="dMPL0">
                    <div class="select-none min-h-20 w-full h-auto md:h-full flex items-center justify-center">
                        <a aria-label="{{ \App\Models\Settings::first()->name ?? config('app.name') }}" class="bwSJI v1a-c">
                            @php
                                $registerBanners = \App\Models\Banner::where('tipo', 'register')->get();
                                $desktopBanner = $registerBanners->where('mobile', 'não')->first();
                                $mobileBanner = $registerBanners->where('mobile', 'sim')->first();
                            @endphp
                            <img alt="{{ \App\Models\Settings::first()->name ?? config('app.name') }}" id="desktop-banner" class="banner-desktop Ueilo" src="{{ $desktopBanner ? asset($desktopBanner->imagem) : '' }}" />
                            <img alt="{{ \App\Models\Settings::first()->name ?? config('app.name') }}" id="mobile-banner" class="banner-mobile j2x6J" style="height:auto!important;" src="{{ $mobileBanner ? asset($mobileBanner->imagem) : '' }}" />
                        </a>
                    </div>
                </div>
                <div class="EzYxM">
                    <header id="topBar" class="f-6B3">
                        <div class="Yi2c7">
                            @php
                                $contactButtonUrl = $footerSettings->contact_button_url ?? '#';
                            @endphp
                            <a class="vQI8R" href="{{ $contactButtonUrl }}">
                                    <span class="nuxt-icon nuxt-icon--fill jSHow">
                                        <svg fill="none" height="20" viewBox="0 0 25 20" width="25" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_25_1228)">
                                                <path
                                                    d="M9.16035 4.24609C9.51879 4.24609 9.81738 4.54477 9.81738 4.90312C9.81738 5.14207 9.69789 5.35117 9.48879 5.4707L7.78605 6.48633C7.54707 6.63672 7.42598 6.875 7.42598 7.11328V7.59102C7.42598 7.97934 7.75449 8.30781 8.14277 8.30781C8.53105 8.30781 8.85957 7.97922 8.85957 7.59102V7.53125L10.2338 6.69492C10.8635 6.30859 11.2502 5.62109 11.2502 4.90234C11.2502 3.73867 10.3244 2.8125 9.16035 2.8125H7.60566C6.44082 2.8125 5.51465 3.73867 5.51465 4.90352C5.51465 5.29184 5.84324 5.62031 6.23145 5.62031C6.61969 5.62031 6.94824 5.29172 6.94824 4.90352C6.94824 4.545 7.24691 4.24648 7.60527 4.24648H9.16035V4.24609ZM8.14473 9.33984C7.60566 9.33984 7.1877 9.75781 7.1877 10.293C7.1877 10.832 7.60566 11.25 8.14473 11.25C8.68262 11.25 9.10059 10.8316 9.10059 10.2941C9.09785 9.75781 8.67988 9.33984 8.14473 9.33984ZM23.4221 17.332C24.4064 16.168 25.0002 14.7109 25.0002 13.125C25.0002 9.32812 21.6408 6.25 17.5002 6.25C17.4879 6.25 17.476 6.2516 17.4637 6.25168C17.4807 6.45703 17.5002 6.66406 17.5002 6.875C17.5002 10.7273 14.3877 13.9531 10.2307 14.7852C11.0432 17.7773 13.9494 20 17.5002 20C18.8162 20 20.0518 19.6872 21.1271 19.1414C22.0783 19.6094 23.2854 20 24.6994 20C24.8189 20 24.9244 19.9326 24.9736 19.8201C25.0216 19.7077 24.9993 19.58 24.9173 19.4938C24.9064 19.4805 24.0588 18.5664 23.4221 17.332ZM21.1916 12.4141L17.9104 15.8516C17.7651 16.0036 17.5643 16.0908 17.3545 16.0933C17.1476 16.0933 16.9396 16.0109 16.7928 15.8644L15.2303 14.3019C14.9251 13.9968 14.9251 13.5023 15.2303 13.1972C15.5354 12.8921 16.0299 12.8921 16.335 13.1972L17.3322 14.1945L20.0611 11.3351C20.3602 11.0226 20.8541 11.011 21.1658 11.3095C21.4768 11.6094 21.4885 12.1016 21.1916 12.4141Z"
                                                    fill="currentColor"
                                                ></path>
                                                <path
                                                    d="M8.12507 0C3.63796 0 7.24202e-05 3.07812 7.24202e-05 6.875C7.24202e-05 8.4207 0.610229 9.84219 1.62781 10.9922C0.987572 12.2719 0.0953849 13.2312 0.0813224 13.2453C-0.00070883 13.3314 -0.0229745 13.4592 0.0250334 13.5716C0.0742912 13.6836 0.179838 13.75 0.299291 13.75C1.79734 13.75 3.06335 13.3156 4.03913 12.8109C5.24226 13.4023 6.63288 13.75 8.12507 13.75C12.6134 13.75 16.2501 10.6719 16.2501 6.875C16.2501 3.07812 12.6134 0 8.12507 0ZM8.1446 11.25C7.60554 11.25 7.18757 10.832 7.18757 10.293C7.18757 9.75508 7.60593 9.33711 8.14343 9.33711C8.68132 9.33711 9.09929 9.75547 9.09929 10.293C9.09773 10.832 8.67976 11.25 8.1446 11.25ZM10.1993 6.69531L8.85945 7.53125V7.59098C8.85945 7.9793 8.53085 8.30777 8.14265 8.30777C7.75445 8.30777 7.42585 7.98047 7.42585 7.59375V7.11328C7.42585 6.87434 7.54535 6.63555 7.78429 6.48594L9.48703 5.47031C9.69538 5.35156 9.81648 5.14062 9.81648 4.90234C9.81648 4.54383 9.51773 4.24531 9.15945 4.24531H7.60554C7.24703 4.24531 6.94851 4.54398 6.94851 4.90234C6.94851 5.29066 6.61992 5.61914 6.23171 5.61914C5.84339 5.61914 5.51492 5.29055 5.51492 4.90234C5.5157 3.73867 6.44148 2.8125 7.60554 2.8125H9.15867C10.3243 2.8125 11.2501 3.73867 11.2501 4.90234C11.2501 5.62109 10.8634 6.30859 10.1993 6.69531Z"
                                                    fill="currentColor"
                                                    opacity="0.4"
                                                ></path>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_25_1228">
                                                    <rect fill="white" height="20" width="25"></rect>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </span>
                                <p>
                                    {{ __('register.need_help') }} <br />
                                    <strong>{{ __('register.help') }}</strong>
                                </p>
                            </a>
                        </div>
                        <div class="_3lQOP">
                            <div class="Ytn0c"><span>{{ __('register.has_account') }}</span><a href="#" id="switch-to-login">{{ __('register.login_here') }}</a></div>
                            <button class="_8Plb- PZR2U Je4se" data-type="register" id="close-register-modal-btn">
                                    <span class="nuxt-icon nuxt-icon--fill">
                                        <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                                                fill="currentColor"
                                            ></path>
                                        </svg>
                                    </span>
                            </button>
                        </div>
                    </header>
                    <div class="QWiLj">
                        <section class="_2FLtL">
                            <div class="JiKuM">
                                <div class="ekJXu" style="--ddff8944: 32px;">
                                    <div class="kvu5c HuRI6"></div>
                                </div>
                                <div class="oiVgM">{{ __('register.or') }}</div>
                                <div class="Y3w7e">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="ref" id="ref" value="{{ Request::query('ref') }}">
                                    <div class="xdHOu hidden" id="div_nascimento">
                                        <div data-v-44b1d268="" class="input-group" autocomplete="off">
                                            <label data-v-44b1d268="" class="group hasLabel placeh" disabled="true" for="name">
                                                <input
                                                    data-v-44b1d268=""
                                                    id="nascimento"
                                                    class="peer input"
                                                    name="nascimento"
                                                    type="text"
                                                    validate-on-blur="true"
                                                    validate-on-change="true"
                                                    autocomplete="off"
                                                    readonly
                                                />

                                                <span data-v-44b1d268="" class="label" style="font-size: 0.52rem; top: 0.8rem;">Nascimento <small data-v-44b1d268="" class="required">{{ __('register.required') }}</small></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="xdHOu hidden" id="nome_cpf">
                                        <div data-v-44b1d268="" class="input-group" autocomplete="off">
                                            <label data-v-44b1d268="" class="group hasLabel placeh" disabled="true" for="name">
                                                <input
                                                    data-v-44b1d268=""
                                                    id="name"
                                                    class="peer input"
                                                    name="name"
                                                    type="text"
                                                    validate-on-blur="true"
                                                    validate-on-change="true"
                                                    autocomplete="off"
                                                    readonly
                                                />

                                                <span data-v-44b1d268="" class="label" style="font-size: 0.52rem; top: 0.8rem;">Nome <small data-v-44b1d268="" class="required">{{ __('register.required') }}</small></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="xdHOu">
                                        <div data-v-44b1d268="" class="input-group" autocomplete="off">
                                            <label data-v-44b1d268="" class="group hasLabel placeh" disabled="false" for="cpf">
                                                <input
                                                    data-v-44b1d268=""
                                                    id="cpf"
                                                    class="peer input"
                                                    name="cpf"
                                                    type="text"
                                                    validate-on-blur="true"
                                                    validate-on-change="true"
                                                    autocomplete="off"
                                                    onblur="ValidarCPF(this.value);"
                                                />

                                                <div id="btnLoadingCPF" class="Gqmf4 hidden"></div>
                                                <div id="btnChangeCPF" class="Gqmf4 hidden"><button type="button" onclick="ChangeCPF();" class="Fxeuw">Trocar CPF</button></div>
                                                <div id="cpfwrong" class="F9HZz hidden">CPF inválido</div>

                                                <span data-v-44b1d268="" class="label">{{ __('register.cpf') }} <small data-v-44b1d268="" class="required">{{ __('register.required') }}</small></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="dsEpF">
                                        <div data-v-44b1d268="" class="input-group" autocomplete="off">
                                            <label data-v-44b1d268="" class="group hasSuffix hasLabel placeh" disabled="false" for="email">
                                                <input
                                                    data-v-44b1d268=""
                                                    id="email"
                                                    class="peer input"
                                                    name="email"
                                                    type="email"
                                                    validate-on-blur="true"
                                                    validate-on-change="true"
                                                    autocomplete="off"
                                                />
                                                <span data-v-44b1d268="" class="label">{{ __('register.email') }} <small data-v-44b1d268="" class="required">{{ __('register.required') }}</small></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div data-v-44b1d268="" class="input-group" autocomplete="off">
                                            <div data-v-44b1d268="" class="prepend">
                                                <div class="S4pv-" data-headlessui-state="">
                                                    <button id="headlessui-listbox-button-nsiNM9WAguS_0" type="button" aria-haspopup="listbox" aria-expanded="false" data-headlessui-state="" class="z89Iw">
                                                            <span class="nuxt-icon text-xl mr-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512">
                                                                    <mask id="a"><circle cx="256" cy="256" r="256" fill="#fff"></circle></mask>
                                                                    <g mask="url(#a)">
                                                                        <path fill="#6da544" d="M0 0h512v512H0z"></path>
                                                                        <path fill="#ffda44" d="M256 100.2 467.5 256 256 411.8 44.5 256z"></path>
                                                                        <path fill="#eee" d="M174.2 221a87 87 0 0 0-7.2 36.3l162 49.8a88.5 88.5 0 0 0 14.4-34c-40.6-65.3-119.7-80.3-169.1-52z"></path>
                                                                        <path
                                                                            fill="#0052b4"
                                                                            d="M255.7 167a89 89 0 0 0-41.9 10.6 89 89 0 0 0-39.6 43.4 181.7 181.7 0 0 1 169.1 52.2 89 89 0 0 0-9-59.4 89 89 0 0 0-78.6-46.8zM212 250.5a149 149 0 0 0-45 6.8 89 89 0 0 0 10.5 40.9 89 89 0 0 0 120.6 36.2 89 89 0 0 0 30.7-27.3A151 151 0 0 0 212 250.5z"
                                                                        ></path>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        +55
                                                    </button>
                                                    @include('auth.partials.paises')
                                                </div>
                                            </div>
                                            <label data-v-44b1d268="" class="group hasSuffix prefix hasLabel placeh" disabled="false" for="phone">
                                                <input
                                                    data-v-44b1d268=""
                                                    id="phone"
                                                    class="peer input"
                                                    name="phone"
                                                    type="tel"
                                                    validate-on-blur="true"
                                                    validate-on-change="true"
                                                    autocomplete="off"
                                                />
                                                <span data-v-44b1d268="" class="label">{{ __('register.phone') }} <small data-v-44b1d268="" class="required">{{ __('register.required') }}</small></span>
                                                <div data-v-44b1d268="" class="suffix-icon mx-0">
                                                    <div data-v-44b1d268="" class="infoSection relative">
                                                            <span data-v-44b1d268="" class="nuxt-icon nuxt-icon--fill">
                                                                <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M256 192c17.67 0 32-14.33 32-32c0-17.67-14.33-32-32-32S224 142.3 224 160C224 177.7 238.3 192 256 192zM296 336h-16V248C280 234.8 269.3 224 256 224H224C210.8 224 200 234.8 200 248S210.8 272 224 272h8v64h-16C202.8 336 192 346.8 192 360S202.8 384 216 384h80c13.25 0 24-10.75 24-24S309.3 336 296 336z"fill="currentColor"></path>
                                                                    <path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 128c17.67 0 32 14.33 32 32c0 17.67-14.33 32-32 32S224 177.7 224 160C224 142.3 238.3 128 256 128zM296 384h-80C202.8 384 192 373.3 192 360s10.75-24 24-24h32c13.25 0 24 10.75 24 24v88h16c13.25 0 24 10.75 24 24S309.3 384 296 384z"fill="currentColor"opacity="0.4"></path>
                                                                </svg>
                                                            </span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="czrJA">
                                        <div class="T-IA3">
                                            <div data-v-44b1d268="" class="input-group" autocomplete="off">
                                                <label data-v-44b1d268="" class="group hasSuffix hasLabel placeh" disabled="false" for="senhaUsuario">
                                                    <input
                                                        data-v-44b1d268=""
                                                        id="senhaUsuario"
                                                        class="peer input padRight"
                                                        name="senhaUsuario"
                                                        type="password"
                                                        value=""
                                                        validate-on-blur="true"
                                                        validate-on-change="true"
                                                        autocomplete="new-password"
                                                    />
                                                    <span data-v-44b1d268="" class="label">{{ __('register.password') }} <small data-v-44b1d268="" class="required">{{ __('register.required') }}</small></span>
                                                    <div data-v-44b1d268="" class="suffix-icon cursor-pointer mx-4" onclick="window.togglePasswordVisibility('senhaUsuario')">
                                                            <span data-v-44b1d268="" class="nuxt-icon nuxt-icon--fill">
                                                                <svg height="1em" viewBox="0 0 640 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M5.112 9.196C13.29-1.236 28.37-3.065 38.81 5.112L630.8 469.1C641.2 477.3 643.1 492.4 634.9 502.8C626.7 513.2 611.6 515.1 601.2 506.9L9.196 42.89C-1.236 34.71-3.065 19.63 5.112 9.196V9.196z"
                                                                        fill="currentColor"
                                                                    ></path>
                                                                    <path
                                                                        d="M446.6 324.7C457.7 304.3 464 280.9 464 256C464 176.5 399.5 112 320 112C282.7 112 248.6 126.2 223.1 149.5L150.7 92.77C195 58.27 251.8 32 320 32C400.8 32 465.5 68.84 512.6 112.6C559.4 156 590.7 207.1 605.5 243.7C608.8 251.6 608.8 260.4 605.5 268.3C592.1 300.6 565.2 346.1 525.6 386.7L446.6 324.7zM313.4 220.3C317.6 211.8 320 202.2 320 192C320 180.5 316.1 169.7 311.6 160.4C314.4 160.1 317.2 160 320 160C373 160 416 202.1 416 256C416 269.7 413.1 282.7 407.1 294.5L313.4 220.3zM320 480C239.2 480 174.5 443.2 127.4 399.4C80.62 355.1 49.34 304 34.46 268.3C31.18 260.4 31.18 251.6 34.46 243.7C44 220.8 60.29 191.2 83.09 161.5L177.4 235.8C176.5 242.4 176 249.1 176 256C176 335.5 240.5 400 320 400C338.7 400 356.6 396.4 373 389.9L446.2 447.5C409.9 467.1 367.8 480 320 480H320z"
                                                                        fill="currentColor"
                                                                        opacity="0.4"
                                                                    ></path>
                                                                </svg>
                                                            </span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <section class="DgmEv">
                                        <div class="fjYNg">
                                            <input id="checkbox-termsAgreement" class="xlVD6 peer" type="checkbox" value="termsAgreement" />
                                            <label class="JFGXu group text-texts" for="checkbox-termsAgreement">
                                                    <span class="bg-white/20 group-[.peer:checked+&amp;]:bg-success group-[.peer:disabled+&amp;]:bg-white/10 group-[.peer:disabled+&amp;]:cursor-not-allowed">
                                                        <svg class="group-[.peer:checked+&amp;]:fill-white" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z"></path>
                                                        </svg>
                                                    </span>
                                                <p class="fqcHw">
                                                    {!!  __('register.terms_confirm') !!} <a href="/page/terms" target="_blank">{!! __('register.terms_and_conditions')  !!}</a> {!! __('register.and') !!}
                                                    <a href="/page/privacy" target="_blank">{{ __('register.privacy_policy') }}</a>.
                                                </p>
                                            </label>
                                        </div>
                                    </section>
                                    <!---->
                                    <div class="_4ee2q">
                                        <button id="complete-registration-btn" type="submit">{{ __('register.complete_registration') }}</button>
                                    </div>

                                    <div style="display: flex; justify-content: center; align-items: center;">
                                        <div id="my-turnstile" class="cf-turnstile" data-sitekey="0x4AAAAAABn-2cjkhBg8gBNC" data-callback="onTurnstileSuccess" data-theme="dark"></div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div id="dynamicContentFooterContainer" class="relative"></div>
                </div>
            </div>
        </div>
        <div class="GovTb"></div>
    </div>
</div>

<script>
    let TokenCloud = "";

    function onTurnstileSuccess(token) {
        TokenCloud = token;
    }
</script>

<!--
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
-->

<script>
    let CurrentUrl = window.location.href;

    // Variável global para controlar o estado de verificação de CPF
    let cpfVerificationCache = {};
    let currentCpfBeingVerified = null;

    function ChangeCPF() {
        // Limpar campos
        document.getElementById('name').value = "";
        document.getElementById('cpf').value = "";
        document.getElementById('nascimento').value = "";

        // Esconder elementos
        document.getElementById('btnChangeCPF').classList.add('hidden');
        document.getElementById('nome_cpf').classList.add('hidden');
        // REMOVIDO: não precisamos mais esconder o div_nascimento pois ele já fica sempre escondido
        // document.getElementById('div_nascimento').classList.add('hidden');

        // Liberar campos
        document.getElementById('cpf').readOnly = false;
        document.getElementById('name').readOnly = false;

        // Reativar botão
        document.getElementById('btnChangeCPF').disabled = false;

        // NOVO: Limpar cache de verificações anteriores
        cpfVerificationCache = {};
        currentCpfBeingVerified = null;

        // NOVO: Remover qualquer mensagem de erro que possa estar sendo exibida
        const cpfWrongElement = document.getElementById('cpfwrong');
        if (cpfWrongElement && !cpfWrongElement.classList.contains('hidden')) {
            cpfWrongElement.classList.add('hidden');
        }

        // NOVO: Garantir que o loading está escondido
        const loadingElement = document.getElementById('btnLoadingCPF');
        if (loadingElement) {
            loadingElement.classList.add('hidden');
            loadingElement.innerHTML = "";
        }

        // NOVO: Refocar no campo CPF para melhor UX
        setTimeout(() => {
            document.getElementById('cpf').focus();
        }, 100);
    }

    function CpfWrong() {
        document.getElementById('cpfwrong').classList.toggle('hidden');

        setTimeout(function () {
            document.getElementById('cpfwrong').classList.toggle('hidden');
        }, 3000);
    }

    function ValidarCPF(cpf) {
        let Ok = true;
        cpf = cpf.replace(/[^\d]+/g, '');

        if (cpf.length !== 11) {
            return;
        }

        if (/^(\d)\1+$/.test(cpf)) {
            CpfWrong();
            return;
        }

        let soma = 0;
        let resto;

        for (let i = 1; i <= 9; i++) {
            soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
        }

        resto = (soma * 10) % 11;

        if ((resto === 10) || (resto === 11)) {
            resto = 0;
        }

        if (resto !== parseInt(cpf.substring(9, 10))) {
            Ok = false;
        }

        soma = 0;

        for (let i = 1; i <= 10; i++) {
            soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
        }

        resto = (soma * 10) % 11;

        if ((resto === 10) || (resto === 11)) {
            resto = 0;
        }

        if (resto !== parseInt(cpf.substring(10, 11))) {
            Ok = false;
        }

        if (Ok) {
            OpenURLCPF(cpf);
        } else {
            CpfWrong();
        }

        return true;
    }

    async function OpenURLCPF(cpf) {
        cpf = cpf.replace(/\D/g, '');

        if (cpf) {
            // NOVO: Atualizar o CPF sendo verificado atualmente
            currentCpfBeingVerified = cpf;

            document.getElementById('cpf').readOnly = true;
            document.getElementById('btnLoadingCPF').classList.remove('hidden');
            document.getElementById('btnLoadingCPF').innerHTML = "<i class=\"FxeuwCenter fa fa-spinner fa-spin\"></i>";

            try {
                const response = await $.ajax({
                    url: 'https://api.inoveigaming.com/cpf/' + cpf,
                    type: "GET",
                    data: $(this).serialize()
                });

                // NOVO: Verificar se o CPF ainda é o mesmo sendo verificado (evitar condições de corrida)
                if (currentCpfBeingVerified !== cpf) {
                    return; // CPF mudou durante a verificação, ignorar resultado
                }

                if ((response.status === 1) || (response.status === true)) {
                    document.getElementById('nome_cpf').classList.remove('hidden');
                    document.getElementById('name').value = response.nome;

                    if (response.nasc) {
                        document.getElementById('nascimento').value = response.nasc;
                        // REMOVIDO: não mostrar mais o campo de nascimento para o usuário
                        // document.getElementById('div_nascimento').classList.remove('hidden');
                    }

                    document.getElementById('btnChangeCPF').classList.remove('hidden');
                    document.getElementById('btnLoadingCPF').classList.add('hidden');
                } else {
                    iziError(response.message, false);

                    document.getElementById('cpf').value = "";
                    document.getElementById('nascimento').value = "";
                    document.getElementById('cpf').readOnly = false;
                    document.getElementById('btnLoadingCPF').classList.add('hidden');
                    document.getElementById('nome_cpf').classList.add('hidden');
                    // REMOVIDO: não precisamos mais esconder o div_nascimento pois ele já fica sempre escondido
                    // document.getElementById('div_nascimento').classList.add('hidden');

                    // NOVO: Limpar cache para este CPF
                    delete cpfVerificationCache[cpf];
                    currentCpfBeingVerified = null;
                }
            } catch (xhr) {
                // NOVO: Verificar se o CPF ainda é o mesmo sendo verificado
                if (currentCpfBeingVerified !== cpf) {
                    return; // CPF mudou durante a verificação, ignorar resultado
                }

                let errorMessage = "Ocorreu um erro desconhecido.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    errorMessage = xhr.responseText;
                } else if (xhr.statusText) {
                    errorMessage = xhr.statusText;
                }
                iziError(errorMessage, false);

                document.getElementById('name').value = "";
                document.getElementById('nascimento').value = "";
                document.getElementById('nome_cpf').classList.add('hidden');
                // REMOVIDO: não precisamos mais esconder o div_nascimento pois ele já fica sempre escondido
                // document.getElementById('div_nascimento').classList.add('hidden');

                document.getElementById('cpf').value = "";
                document.getElementById('cpf').readOnly = false;
                document.getElementById('btnLoadingCPF').classList.add('hidden');

                // NOVO: Limpar cache para este CPF
                delete cpfVerificationCache[cpf];
                currentCpfBeingVerified = null;
            }
        }
    }

    // NOVA FUNÇÃO: Verificar se CPF já existe no banco (com cache inteligente)
    async function verificarCpfDuplicado(cpf, baseUrl, csrfToken) {
        // Se já temos o resultado em cache, retornar
        if (cpfVerificationCache.hasOwnProperty(cpf)) {
            return cpfVerificationCache[cpf];
        }

        let cpfExists = false;

        try {
            // Primeiro, tentar verificação direta
            const verifyFormData = new FormData();
            verifyFormData.append('cpf', cpf);
            verifyFormData.append('_token', csrfToken);

            const verifyResponse = await fetch(`${baseUrl}/register/verify`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: verifyFormData,
                credentials: 'same-origin'
            }).catch(() => ({ ok: false }));

            if (verifyResponse.ok) {
                const verifyData = await verifyResponse.json().catch(() => ({}));
                if (verifyData.cpf_exists) {
                    cpfExists = true;
                }
            }
        } catch (e) {
        }

        // Se não encontrou pela verificação direta, tentar outras abordagens
        if (!cpfExists) {
            try {
                const cpfCheckResponse = await fetch(`${baseUrl}/api/users/check-cpf?cpf=${encodeURIComponent(cpf)}&_=${Date.now()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    }
                }).catch(() => ({ ok: false }));

                if (cpfCheckResponse.ok) {
                    const cpfData = await cpfCheckResponse.json().catch(() => ({}));
                    cpfExists = cpfData.exists || !cpfData.available;
                }
            } catch (e) {
            }
        }

        // Última tentativa se ainda não encontrou
        if (!cpfExists) {
            try {
                const altCheckResponse = await fetch(`${baseUrl}/check-duplicate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Cache-Control': 'no-cache'
                    },
                    body: JSON.stringify({ cpf: cpf, field: 'cpf', _: Date.now() }),
                    credentials: 'same-origin'
                }).catch(() => ({ ok: false }));

                if (altCheckResponse.ok) {
                    const altData = await altCheckResponse.json().catch(() => ({}));
                    cpfExists = altData.exists || altData.duplicate || !altData.available;
                }
            } catch (e) {
            }
        }

        // Armazenar resultado no cache
        cpfVerificationCache[cpf] = cpfExists;

        return cpfExists;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Definir baseUrl no início para evitar o erro de referência
        const baseUrl = window.location.origin.replace(/^http:/, 'https:');

        // Funções para mostrar mensagens de erro e sucesso
        function mostrarMensagemErro(mensagem) {
            // Verifica se existe uma implementação global
            if (window.mostrarMensagemErro) {
                window.mostrarMensagemErro(mensagem);
                return;
            }

            // Implementação de fallback
            alert(mensagem);
        }

        function mostrarMensagemSucesso(mensagem) {
            // Verifica se existe uma implementação global
            if (window.mostrarMensagemSucesso) {
                window.mostrarMensagemSucesso(mensagem);
                return;
            }

            // Implementação de fallback
            alert(mensagem);
        }

        // Evite declarar passwordField duas vezes - removendo a primeira declaração
        const inputPassword = document.getElementById('senhaUsuario');
        if (inputPassword) {
            // Limpar qualquer valor pré-definido
            inputPassword.value = '';

            // Garantir que o tipo seja password inicialmente
            inputPassword.type = 'password';
        }

        // Mascaras para CPF e telefone
        function aplicarMascaraCPF() {
            const cpfInput = document.getElementById('cpf');
            if (cpfInput) {
                cpfInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');

                    if (value.length > 11) {
                        value = value.slice(0, 11);
                    }

                    if (value.length > 9) {
                        value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2}).*/, '$1.$2.$3-$4');
                    } else if (value.length > 6) {
                        value = value.replace(/^(\d{3})(\d{3})(\d{0,3}).*/, '$1.$2.$3');
                    } else if (value.length > 3) {
                        value = value.replace(/^(\d{3})(\d{0,3}).*/, '$1.$2');
                    }

                    e.target.value = value;
                });
            }
        }

        function aplicarMascaraTelefone() {
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');

                    if (value.length > 11) {
                        value = value.slice(0, 11);
                    }

                    if (value.length > 10) {
                        value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
                    } else if (value.length > 6) {
                        value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
                    } else if (value.length > 2) {
                        value = value.replace(/^(\d{2})(\d{0,5}).*/, '($1) $2');
                    }

                    e.target.value = value;
                });
            }
        }

        // Aplicar as máscaras
        aplicarMascaraCPF();
        aplicarMascaraTelefone();

        // Atualizar token CSRF quando o modal for aberto
        // Adicionar evento para capturar quando o modal é exibido
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'style' || mutation.attributeName === 'class') {
                    const modal = document.getElementById('register-modal');
                    if (modal && (modal.style.display === 'block' || modal.classList.contains('active') || modal.classList.contains('show') || window.getComputedStyle(modal).display !== 'none')) {
                        // O modal está visível, atualize o token CSRF
                        atualizarCsrfToken();
                    }
                }
            });
        });

        const registerModal = document.getElementById('register-modal');
        if (registerModal) {
            observer.observe(registerModal, { attributes: true });

            // Também verificar se o modal já está aberto
            if (registerModal.style.display === 'block' || registerModal.classList.contains('active') || registerModal.classList.contains('show') || window.getComputedStyle(registerModal).display !== 'none') {
                atualizarCsrfToken();
            }
        }

        // Gerenciamento das classes hasContent nos campos
        const formInputs = document.querySelectorAll('.peer.input');

        function updateContentClass(input) {
            if (input.value.trim() !== '') {
                input.classList.add('hasContent');
            } else {
                input.classList.remove('hasContent');
            }
        }

        formInputs.forEach(input => {
            updateContentClass(input);
            input.addEventListener('input', function() {
                updateContentClass(this);
            });
        });

        // Função para fechar modal de registro e abrir modal de depósito
        function fecharRegistroEAbrirDeposito() {
            // Fechar modal de registro
            if (typeof window.fecharModalRegistro === 'function') {
                window.fecharModalRegistro();
            } else {
                // Fallback: fechar manualmente
                const registerModal = document.getElementById('register-modal');
                if (registerModal) {
                    registerModal.style.display = 'none';
                }
            }
            
            // Função auxiliar para abrir o modal de depósito
            function abrirModalDeposito() {
                // Tentar usar a função global primeiro
                if (typeof window.openDepositModal === 'function') {
                    window.openDepositModal();
                    return;
                }
                
                // Fallback: tentar abrir manualmente
                const depositModal = document.getElementById('depositModal');
                if (depositModal) {
                    depositModal.classList.remove('hidden');
                    depositModal.classList.add('show');
                    
                    // Inicializar valor padrão
                    const depositAmount = document.getElementById('depositAmount');
                    if (depositAmount) {
                        depositAmount.value = "50,00";
                    }
                    
                    // Inicializar o sistema de bônus
                    if (typeof window.reinitializeBonusSystem === 'function') {
                        setTimeout(function() {
                            window.reinitializeBonusSystem();
                        }, 100);
                    }
                    
                    // Garantir que o modal esteja visível
                    depositModal.style.display = 'block';
                    depositModal.style.zIndex = '99999';
                }
            }
            
            // Aguardar o evento de login bem-sucedido ou header atualizado
            let modalAberto = false;
            let timeoutId = null;
            
            // Listener para quando o header for atualizado
            const headerUpdatedHandler = function() {
                if (!modalAberto) {
                    modalAberto = true;
                    if (timeoutId) clearTimeout(timeoutId);
                    
                    // Aguardar um pouco mais para garantir que tudo está pronto
                    setTimeout(function() {
                        abrirModalDeposito();
                    }, 500);
                    
                    // Remover listeners após usar
                    window.removeEventListener('header:updated', headerUpdatedHandler);
                    window.removeEventListener('auth:loginSuccess', loginSuccessHandler);
                }
            };
            
            // Listener para quando o login for bem-sucedido
            const loginSuccessHandler = function() {
                if (!modalAberto) {
                    modalAberto = true;
                    if (timeoutId) clearTimeout(timeoutId);
                    
                    // Aguardar um pouco mais para garantir que tudo está pronto
                    setTimeout(function() {
                        abrirModalDeposito();
                    }, 500);
                    
                    // Remover listeners após usar
                    window.removeEventListener('header:updated', headerUpdatedHandler);
                    window.removeEventListener('auth:loginSuccess', loginSuccessHandler);
                }
            };
            
            // Adicionar listeners
            window.addEventListener('header:updated', headerUpdatedHandler);
            window.addEventListener('auth:loginSuccess', loginSuccessHandler);
            
            // Timeout de segurança caso os eventos não sejam disparados
            timeoutId = setTimeout(function() {
                if (!modalAberto) {
                    modalAberto = true;
                    // Remover listeners
                    window.removeEventListener('header:updated', headerUpdatedHandler);
                    window.removeEventListener('auth:loginSuccess', loginSuccessHandler);
                    
                    // Tentar abrir o modal mesmo sem os eventos (fallback)
                    setTimeout(function() {
                        abrirModalDeposito();
                    }, 800);
                }
            }, 2000); // Timeout de 2 segundos como fallback
        }

        // Função para obter o token CSRF dinamicamente
        function getCsrfToken() {
            // Primeiro, verifica se há uma meta tag (prioridade mais alta)
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken) {
                return metaToken.getAttribute('content');
            }

            // Depois, verifica se há um campo _token explícito que adicionamos
            const tokenField = document.getElementById('csrf-token');
            if (tokenField && tokenField.value) {
                return tokenField.value;
            }

            // Por último, verifica se há um campo _token em qualquer formulário
            const tokenFieldGeneric = document.querySelector('input[name="_token"]');
            if (tokenFieldGeneric && tokenFieldGeneric.value) {
                return tokenFieldGeneric.value;
            }

            return '';
        }

        // Adicionar função para atualizar o CSRF token de forma dinâmica
        function atualizarCsrfToken() {
            return new Promise((resolve, reject) => {
                // Definir um timeout para a solicitação AJAX
                const timeoutPromise = new Promise((_, reject) => {
                    setTimeout(() => reject(new Error('Tempo limite atingido para obter token CSRF')), 5000);
                });

                // Fazer a solicitação AJAX com tempo limite
                Promise.race([
                    fetch('/csrf-token', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    }),
                    timeoutPromise
                ])
                    .then(response => {
                        if (!response.ok) {
                            // Se tiver um erro específico, use o token local
                            console.warn('Falha ao obter token CSRF, usando token local');
                            return null;
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data || !data.token) {
                            console.warn('Token CSRF não encontrado na resposta ou resposta inválida');
                            resolve(getCsrfToken());
                            return;
                        }

                        // Atualiza a meta tag com prioridade
                        let metaToken = document.querySelector('meta[name="csrf-token"]');
                        if (!metaToken) {
                            // Se não existir, crie uma nova meta tag
                            metaToken = document.createElement('meta');
                            metaToken.setAttribute('name', 'csrf-token');
                            document.head.appendChild(metaToken);
                        }
                        metaToken.setAttribute('content', data.token);

                        // Atualiza o campo hidden com o novo token
                        const tokenField = document.getElementById('csrf-token');
                        if (tokenField) {
                            tokenField.value = data.token;
                        }

                        resolve(data.token);
                    })
                    .catch(error => {
                        console.error('Erro ao atualizar token CSRF:', error);
                        // Continuar usando o token atual em caso de falha
                        resolve(getCsrfToken());
                    });
            });
        }

        // Validação específica de CPF
        function validarCPF(cpf) {
            cpf = cpf.replace(/[^\d]/g, '');

            if (cpf.length !== 11) return false;

            // Verificação de CPFs inválidos conhecidos
            if (/^(\d)\1+$/.test(cpf)) return false;

            // Cálculo dos dígitos verificadores
            let soma = 0;
            let resto;

            for (let i = 1; i <= 9; i++) {
                soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
            }

            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.substring(9, 10))) return false;

            soma = 0;
            for (let i = 1; i <= 10; i++) {
                soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
            }

            resto = (soma * 10) % 11;
            if (resto === 10 || resto === 11) resto = 0;
            if (resto !== parseInt(cpf.substring(10, 11))) return false;

            return true;
        }

        // Validação de email
        function validarEmail(email) {
            // Regex mais rigoroso para validar email
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

            // Verificações adicionais
            if (!email) return false;
            if (email.length > 191) return false; // Verificar comprimento máximo
            if (!re.test(email)) return false;

            // Verificar se tem domínio válido com pelo menos um ponto
            const parts = email.split('@');
            if (parts.length !== 2) return false;

            const domain = parts[1];
            if (!domain.includes('.')) return false;

            // Verificar se a extensão do domínio é válida (pelo menos 2 caracteres após o último ponto)
            const extension = domain.split('.').pop();
            if (extension.length < 2) return false;

            return true;
        }

        // Handler do formulário de registro
        const completeRegistrationBtn = document.getElementById('complete-registration-btn');
        if (completeRegistrationBtn) {
            completeRegistrationBtn.addEventListener('click', async function(e) {
                e.preventDefault();

                this.disabled = true;
                this.textContent = 'Processando...';

                try {
                    // Obter token CSRF atualizado
                    const csrfToken = await atualizarCsrfToken();

                    const emailEl = document.getElementById('email');
                    const phoneEl = document.getElementById('phone');
                    const passwordEl = document.getElementById('senhaUsuario');
                    const cpfEl = document.getElementById('cpf');
                    const termsAgreementEl = document.getElementById('checkbox-termsAgreement');

                    // Verificação melhorada de elementos
                    if (!emailEl || !phoneEl || !passwordEl || !cpfEl) {
                        mostrarMensagemErro("Erro nos campos do formulário.");
                        this.disabled = false;
                        this.textContent = 'Completar Registro';
                        return;
                    }

                    // Só então pegar os valores
                    const email = emailEl.value.trim();
                    const phone = phoneEl.value.trim();
                    const password = passwordEl.value;
                    const cpfFormatado = cpfEl.value.trim();
                    const cpf = cpfFormatado.replace(/[^\d]/g, ''); // Remove caracteres não numéricos
                    const termsAgreement = termsAgreementEl ? termsAgreementEl.checked : false;

                    // Validação melhorada
                    if (!email) {
                        mostrarMensagemErro("O campo de email é obrigatório.");
                        this.disabled = false;
                        this.textContent = 'Completar Registro';
                        return;
                    }

                    if (!validarEmail(email)) {
                        mostrarMensagemErro("O formato do email informado não é válido. Verifique se digitou corretamente (exemplo: nome@dominio.com).");
                        this.disabled = false;
                        this.textContent = 'Completar Registro';
                        return;
                    }

                    if (!phone || phone.replace(/[^\d]/g, '').length < 10) {
                        mostrarMensagemErro("Digite um número de telefone válido.");
                        this.disabled = false;
                        this.textContent = 'Completar Registro';
                        return;
                    }

                    if (!cpf || cpf.length !== 11) {
                        mostrarMensagemErro("O CPF precisa ter 11 dígitos.");
                        this.disabled = false;
                        this.textContent = 'Completar Registro';
                        return;
                    }

                    if (!validarCPF(cpf)) {
                        mostrarMensagemErro("O CPF informado não é válido.");
                        this.disabled = false;
                        this.textContent = 'Completar Registro';
                        return;
                    }

                    // Validação de senha - pelo menos 8 caracteres
                    if (password.length < 8) {
                        mostrarMensagemErro("A senha deve ter pelo menos 8 caracteres.");
                        this.disabled = false;
                        this.textContent = 'Completar Registro';
                        return;
                    }

                    if (!termsAgreement) {
                        mostrarMensagemErro("Você precisa aceitar os termos e condições.");
                        this.disabled = false;
                        this.textContent = 'Completar Registro';
                        return;
                    }

                    if (!csrfToken) {
                        mostrarMensagemErro("Erro de segurança com o token CSRF. Tente novamente.");
                        this.disabled = false;
                        this.textContent = 'Completar Registro';
                        return;
                    }

                    // NOVA ABORDAGEM: Usar a função unificada de verificação de CPF
                    try {
                        // Verificar se CPF já existe usando nossa nova função centralizada
                        const cpfExists = await verificarCpfDuplicado(cpf, baseUrl, csrfToken);

                        if (cpfExists) {
                            mostrarMensagemErro("Este CPF já está cadastrado. Por favor, faça login ou use outro CPF.");
                            this.disabled = false;
                            this.textContent = 'Completar Registro';
                            return;
                        }
                    } catch (e) {
                        // Continuar com o registro em caso de erro na verificação (para não bloquear usuário)
                    }

                    // Verificação adicional de EMAIL duplicado (mantemos separada)
                    try {
                        const verifyFormData = new FormData();
                        verifyFormData.append('email', email);
                        verifyFormData.append('_token', csrfToken);

                        const verifyResponse = await fetch(`${baseUrl}/register/verify`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: verifyFormData,
                            credentials: 'same-origin'
                        }).catch(err => {
                            return { ok: false };
                        });

                        if (verifyResponse.ok) {
                            const verifyData = await verifyResponse.json().catch(() => ({}));

                            if (verifyData.email_exists) {
                                mostrarMensagemErro("Este email já está cadastrado. Por favor, faça login ou use outro email.");
                                this.disabled = false;
                                this.textContent = 'Completar Registro';
                                return;
                            }
                        }
                    } catch (e) {
                    }

                    const formDataObj = new FormData();
                    // Usar o nome do usuário que foi preenchido pela consulta do CPF
                    const nameInput = document.getElementById('name');
                    formDataObj.append('name', nameInput ? nameInput.value : email.split('@')[0]);
                    formDataObj.append('email', email);
                    formDataObj.append('phone', phone.replace(/[^\d]/g, '')); // Apenas números
                    formDataObj.append('password', password);
                    // Removido password_confirmation pois não existe no modal
                    formDataObj.append('cpf', cpf); // CPF sem formatação
                    formDataObj.append('pix', cpf); // Adicionando o CPF como PIX também
                    formDataObj.append('image', 'img/avatar/15.png');
                    formDataObj.append('terms_agreement', '1'); // Convertido para string
                    formDataObj.append('_token', csrfToken);
                    formDataObj.append('ref', document.getElementById('ref').value);
                    formDataObj.append('nascimento', document.getElementById('nascimento').value);
                    formDataObj.append("cf-turnstile-response", TokenCloud);

                    const registerUrl = `${baseUrl}/register`;

                    try {
                        // Adicionar delay de 500ms para garantir que requisições não sejam muito rápidas
                        await new Promise(resolve => setTimeout(resolve, 500));

                        // Primeira tentativa com Fetch API
                        const response = await fetch(registerUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Cache-Control': 'no-cache' // Prevenir cache
                            },
                            credentials: 'same-origin',
                            body: formDataObj
                        });

                        // Verificar se foi redirecionado
                        if (response.redirected) {
                            //window.location.href = CurrentUrl;
                            window.atualizarHeaderAposLogin();
                            fecharRegistroEAbrirDeposito();
                            return;
                        }

                        // Verificar códigos de erro específicos
                        if (response.status === 419) {
                            // Token expirado, tentar novamente
                            const newToken = await atualizarCsrfToken();
                            if (!newToken) {
                                throw new Error("Erro de segurança com token CSRF.");
                            }

                            // Refazer FormData com novo token
                            const newFormData = new FormData();
                            // Recriamos todos os campos individualmente para garantir que não haja password_confirmation
                            newFormData.append('name', nameInput ? nameInput.value : email.split('@')[0]);
                            newFormData.append('email', email);
                            newFormData.append('phone', phone.replace(/[^\d]/g, ''));
                            newFormData.append('password', password);
                            newFormData.append('cpf', cpf);
                            newFormData.append('pix', cpf);
                            newFormData.append('image', 'img/avatar/15.png');
                            newFormData.append('terms_agreement', '1');
                            newFormData.append('_token', newToken);
                            newFormData.append("cf-turnstile-response", TokenCloud);

                            // Repetir requisição com novo token
                            const retryResponse = await fetch(registerUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': newToken,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                credentials: 'same-origin',
                                body: newFormData
                            });

                            if (retryResponse.redirected) {
                                //window.location.href = CurrentUrl;
                                window.atualizarHeaderAposLogin();
                                fecharRegistroEAbrirDeposito();
                                return;
                            }

                            if (!retryResponse.ok) {
                                // Processar resposta de erro
                                const contentType = retryResponse.headers.get('content-type');
                                if (contentType && contentType.includes('application/json')) {
                                    const errorData = await retryResponse.json();
                                    throw new Error(errorData.message || "Erro no registro. Verifique seus dados.");
                                } else {
                                    throw new Error(`Erro no servidor (${retryResponse.status}).`);
                                }
                            }

                            // Sucesso na segunda tentativa
                            mostrarMensagemSucesso("Registro realizado com sucesso!");
                            //window.location.href = CurrentUrl;
                            window.atualizarHeaderAposLogin();
                            fecharRegistroEAbrirDeposito();
                            return;
                        }

                        if (response.status === 422) {
                            // Erro de validação
                            const errorData = await response.json();
                            let errorMessage = "Verifique os dados informados:";

                            if (errorData.errors) {
                                const errors = errorData.errors;
                                const errorList = [];

                                if (errors.email) {
                                    // Verificar se o email já está em uso
                                    if (errors.email[0].includes("already been taken") ||
                                        errors.email[0].includes("já está sendo utilizado")) {
                                        errorMessage = "Este email já está cadastrado. Tente fazer login ou use outro email.";
                                        throw new Error(errorMessage);
                                    } else {
                                        errorList.push("Email: " + errors.email[0]);
                                    }
                                }

                                if (errors.cpf) {
                                    // Verificar se o CPF já está em uso
                                    if (errors.cpf[0].includes("already been taken") ||
                                        errors.cpf[0].includes("já está sendo utilizado")) {
                                        errorMessage = "Este CPF já está cadastrado. Tente fazer login ou use outro CPF.";
                                        throw new Error(errorMessage);
                                    } else {
                                        errorList.push("CPF: " + errors.cpf[0]);
                                    }
                                }

                                if (errors.pix) {
                                    // Verificar se o PIX (geralmente o CPF) já está em uso
                                    if (errors.pix[0].includes("already been taken") ||
                                        errors.pix[0].includes("já está sendo utilizado")) {
                                        errorMessage = "Este CPF/PIX já está cadastrado. Tente fazer login ou use outro CPF.";
                                        throw new Error(errorMessage);
                                    } else {
                                        errorList.push("PIX: " + errors.pix[0]);
                                    }
                                }

                                if (errors.phone) errorList.push("Telefone: " + errors.phone[0]);
                                if (errors.password) errorList.push("Senha: " + errors.password[0]);

                                if (errorList.length > 0) {
                                    errorMessage += " " + errorList.join("; ");
                                }
                            } else if (errorData.message) {
                                errorMessage = errorData.message;
                            }

                            throw new Error(errorMessage);
                        }

                        if (!response.ok) {
                            throw new Error(`Erro no servidor (${response.status}).`);
                        }

                        // Processar resposta de sucesso
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            const data = await response.json();

                            if (data.success || data.status === 'success') {
                                mostrarMensagemSucesso("Registro realizado com sucesso!");

                                // Redirecionar se necessário
                                if (data.redirect) {
                                    //window.location.href = CurrentUrl;
                                } else {
                                    //window.location.href = CurrentUrl;
                                }

                                window.atualizarHeaderAposLogin();
                                fecharRegistroEAbrirDeposito();
                            } else {
                                throw new Error(data.message || "Ocorreu um erro no registro.");
                            }
                        } else {
                            // Redirecionamento padrão em caso de sucesso
                            mostrarMensagemSucesso("Registro realizado com sucesso!");
                            //window.location.href = CurrentUrl;
                            window.atualizarHeaderAposLogin();
                            fecharRegistroEAbrirDeposito();
                        }
                    } catch (fetchError) {
                        console.error('Erro no fetch:', fetchError);

                        // Verificar se a mensagem de erro contém indicação de email ou CPF duplicado
                        const errorMsg = fetchError.message || "";

                        // Verificação específica para erro de email inválido
                        if (
                            errorMsg.toLowerCase().includes("email") &&
                            (
                                errorMsg.toLowerCase().includes("format") ||
                                errorMsg.toLowerCase().includes("formato") ||
                                errorMsg.toLowerCase().includes("inválido") ||
                                errorMsg.toLowerCase().includes("invalid") ||
                                errorMsg.toLowerCase().includes("válido")
                            )
                        ) {
                            mostrarMensagemErro("O formato do email é inválido. Por favor, verifique e tente novamente.");
                        }
                        // Verificação para email duplicado
                        else if (
                            errorMsg.toLowerCase().includes("email") &&
                            (
                                errorMsg.toLowerCase().includes("already") ||
                                errorMsg.toLowerCase().includes("taken") ||
                                errorMsg.toLowerCase().includes("exist") ||
                                errorMsg.toLowerCase().includes("já") ||
                                errorMsg.toLowerCase().includes("utilizado") ||
                                errorMsg.toLowerCase().includes("cadastrado")
                            )
                        ) {
                            // É um erro de email duplicado
                            mostrarMensagemErro("Este email já está cadastrado. Tente fazer login ou use outro email.");
                        }
                        // Verificação para CPF duplicado (só verifica se não foi um erro de email)
                        else if (
                            (errorMsg.toLowerCase().includes("cpf") || errorMsg.toLowerCase().includes("pix")) &&
                            (
                                errorMsg.toLowerCase().includes("already") ||
                                errorMsg.toLowerCase().includes("taken") ||
                                errorMsg.toLowerCase().includes("exist") ||
                                errorMsg.toLowerCase().includes("já") ||
                                errorMsg.toLowerCase().includes("utilizado") ||
                                errorMsg.toLowerCase().includes("cadastrado")
                            )
                        ) {
                            // É um erro de CPF duplicado
                            mostrarMensagemErro("Este CPF já está cadastrado. Tente fazer login ou use outro CPF.");
                        } else {
                            // Verificar explicitamente por erros comuns de validação
                            if (errorMsg.toLowerCase().includes("validation") ||
                                errorMsg.toLowerCase().includes("validação") ||
                                errorMsg.toLowerCase().includes("validator")) {
                                // Erros de validação genéricos
                                mostrarMensagemErro("Verifique os dados informados e tente novamente. " + errorMsg);
                            } else {
                                // Outros erros
                                mostrarMensagemErro(errorMsg || "Erro na comunicação com o servidor. Tente novamente.");
                            }
                        }

                        // Reativar botão
                        this.disabled = false;
                        this.textContent = 'Completar Registro';
                    }
                } catch (error) {
                    console.error('Erro geral:', error);
                    mostrarMensagemErro("Ocorreu um erro. Por favor, tente novamente mais tarde.");
                    this.disabled = false;
                    this.textContent = 'Completar Registro';
                }
            });
        }

        // Função para alternar a visibilidade da senha
        window.togglePasswordVisibility = function(fieldId) {
            const field = document.getElementById(fieldId);
            if (!field) return;

            // Encontrar o contêiner do ícone do olho
            const iconContainer = document.querySelector(`[onclick="window.togglePasswordVisibility('${fieldId}')"]`);
            if (!iconContainer) return;

            const svgContainer = iconContainer.querySelector('span');
            if (!svgContainer) return;

            if (field.type === 'password') {
                field.type = 'text';
                // Trocar para o ícone de olho aberto
                svgContainer.innerHTML = `
                    <svg height="1em" viewBox="0 0 576 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" fill="currentColor"></path>
                    </svg>
                `;
            } else {
                field.type = 'password';
                // Voltar para o ícone de olho fechado
                svgContainer.innerHTML = `
                    <svg height="1em" viewBox="0 0 640 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5.112 9.196C13.29-1.236 28.37-3.065 38.81 5.112L630.8 469.1C641.2 477.3 643.1 492.4 634.9 502.8C626.7 513.2 611.6 515.1 601.2 506.9L9.196 42.89C-1.236 34.71-3.065 19.63 5.112 9.196V9.196z"
                            fill="currentColor"
                        ></path>
                        <path
                            d="M446.6 324.7C457.7 304.3 464 280.9 464 256C464 176.5 399.5 112 320 112C282.7 112 248.6 126.2 223.1 149.5L150.7 92.77C195 58.27 251.8 32 320 32C400.8 32 465.5 68.84 512.6 112.6C559.4 156 590.7 207.1 605.5 243.7C608.8 251.6 608.8 260.4 605.5 268.3C592.1 300.6 565.2 346.1 525.6 386.7L446.6 324.7zM313.4 220.3C317.6 211.8 320 202.2 320 192C320 180.5 316.1 169.7 311.6 160.4C314.4 160.1 317.2 160 320 160C373 160 416 202.1 416 256C416 269.7 413.1 282.7 407.1 294.5L313.4 220.3zM320 480C239.2 480 174.5 443.2 127.4 399.4C80.62 355.1 49.34 304 34.46 268.3C31.18 260.4 31.18 251.6 34.46 243.7C44 220.8 60.29 191.2 83.09 161.5L177.4 235.8C176.5 242.4 176 249.1 176 256C176 335.5 240.5 400 320 400C338.7 400 356.6 396.4 373 389.9L446.2 447.5C409.9 467.1 367.8 480 320 480H320z"
                            fill="currentColor"
                            opacity="0.4"
                        ></path>
                    </svg>
                `;
            }
        };

        // Configuração do botão para alternar entre login e registro
        const switchToLoginBtn = document.getElementById('switch-to-login');
        if (switchToLoginBtn) {
            switchToLoginBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Fechar modal de registro
                const registerModal = document.getElementById('register-modal');
                if (registerModal) {
                    registerModal.style.display = 'none';
                }

                // Abrir modal de login (se existir)
                const loginModal = document.getElementById('login-modal');
                if (loginModal) {
                    loginModal.style.display = 'block';
                }
            });
        }

        // Gerenciamento do modal de confirmação de cancelamento para registro
        setTimeout(function() {
            const cancelModalRegister = document.getElementById('cancel-confirmation-modal-register');
            const continueRegisterBtn = document.getElementById('continue-register-btn');
            const cancelRegisterBtn = document.getElementById('cancel-register-btn');
            const registerModal = document.getElementById('register-modal');

            // Garantir que o modal de confirmação esteja escondido inicialmente
            if (cancelModalRegister) {
                cancelModalRegister.style.display = 'none';
            }

            // Botão "Continuar" - apenas fecha o modal de confirmação
            if (continueRegisterBtn) {
                // Remover listeners anteriores se existirem
                const newContinueBtn = continueRegisterBtn.cloneNode(true);
                continueRegisterBtn.parentNode.replaceChild(newContinueBtn, continueRegisterBtn);
                
                newContinueBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    const modal = document.getElementById('cancel-confirmation-modal-register');
                    if (modal) {
                        modal.style.display = 'none';
                    }
                    return false;
                });
            }

            // Botão "Sim, quero cancelar" - fecha ambos os modais
            if (cancelRegisterBtn) {
                // Remover listeners anteriores se existirem
                const newCancelBtn = cancelRegisterBtn.cloneNode(true);
                cancelRegisterBtn.parentNode.replaceChild(newCancelBtn, cancelRegisterBtn);
                
                newCancelBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    const modal = document.getElementById('cancel-confirmation-modal-register');
                    if (modal) {
                        modal.style.display = 'none';
                    }
                    
                    // Fechar o modal de registro e limpar overlay
                    if (typeof window.fecharModalRegistro === 'function') {
                        window.fecharModalRegistro();
                    } else {
                        // Fallback: fechar manualmente
                        if (registerModal) {
                            registerModal.style.display = 'none';
                            registerModal.classList.remove('show');
                            registerModal.classList.add('hidden');
                        }
                        
                        // Remover overlay se existir
                        const registerOverlay = document.getElementById('register-modal-overlay') ||
                            document.getElementById('register-overlay') ||
                            document.querySelector('.modal-overlay') ||
                            document.querySelector('.overlay');
                        if (registerOverlay) {
                            registerOverlay.style.display = 'none';
                            registerOverlay.classList.remove('show');
                            registerOverlay.classList.add('hidden');
                        }
                        
                        document.body.classList.remove('modal-open');
                        document.body.classList.remove('overflow-hidden');
                    }
                    return false;
                });
            }

            // Fechar modal ao clicar fora dele
            if (cancelModalRegister) {
                cancelModalRegister.addEventListener('click', function(e) {
                    if (e.target === cancelModalRegister) {
                        cancelModalRegister.style.display = 'none';
                    }
                });
            }
        }, 100);
    });
</script>
