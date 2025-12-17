<div class="hS9Wq" id="login-modal">
    @php
        $footerSettings = \App\Models\FooterSettings::getSettings();
    @endphp
    <style>
        .hidden-form {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
            position: absolute !important;
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

    <div class="pOZuF">
        <div class="rdEzG">
            <div class="_3lvVF" style="position: relative;">
                <!-- Modal de Confirmação de Cancelamento -->
                <div class="cMU8g" id="cancel-confirmation-modal" style="display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 1000; align-items: center; justify-content: center;">
                    <div class="rClOu">
                        <img alt="Quit icon" aria-hidden="true" class="dHdm7" src="https://static.rico.bet.br/deploy-671d24bd174e6bf229486c7258f6bbb7a23492ee-be38829a19c5c5b9dc3f/assets/images/svg/quit.svg">
                        <h6 class="h2bsN">Tem certeza que deseja cancelar seu login?</h6>
                        <button class="hQlUG" id="continue-login-btn" type="button" style="color: var(--text-btn-primary);">Continuar <span class="nuxt-icon nuxt-icon--fill"><svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
  <path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z" fill="currentColor"></path>
</svg></span></button>
                        <button class="PLsB5" id="cancel-login-btn" type="button"><span class="nuxt-icon nuxt-icon--fill"><svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
  <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z" fill="currentColor"></path>
</svg></span> Sim, quero cancelar</button>
                    </div>
                </div>
                <div class="dMPL0">
                    <div class="select-none min-h-20 w-full h-auto md:h-full flex items-center justify-center">
                        <a aria-label="{{ \App\Models\Settings::first()->name ?? config('app.name') }}" class="bwSJI v1a-c">
                            @php
                                $loginBanners = \App\Models\Banner::where('tipo', 'login')->get();
                                $desktopBanner = $loginBanners->where('mobile', 'não')->first();
                                $mobileBanner = $loginBanners->where('mobile', 'sim')->first();
                            @endphp
                            <img alt="{{ \App\Models\Settings::first()->name ?? config('app.name') }}" id="desktop-banner" class="banner-desktop Ueilo" src="{{ $desktopBanner ? asset($desktopBanner->imagem) : '' }}" />
                            <img alt="{{ \App\Models\Settings::first()->name ?? config('app.name') }}" id="mobile-banner" style="height:auto!important;" class="banner-mobile j2x6J" src="{{ $mobileBanner ? asset($mobileBanner->imagem) : '' }}" />
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
                                <span class="inove-icon inove-icon--fill jSHow">
                                    <svg fill="none" height="20" viewBox="0 0 25 20" width="25" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.16035 4.24609C9.51879 4.24609 9.81738 4.54477 9.81738 4.90312C9.81738 5.14207 9.69789 5.35117 9.48879 5.4707L7.78605 6.48633C7.54707 6.63672 7.42598 6.875 7.42598 7.11328V7.59102C7.42598 7.97934 7.75449 8.30781 8.14277 8.30781C8.53105 8.30781 8.85957 7.97922 8.85957 7.59102V7.53125L10.2338 6.69492C10.8635 6.30859 11.2502 5.62109 11.2502 4.90234C11.2502 3.73867 10.3244 2.8125 9.16035 2.8125H7.60566C6.44082 2.8125 5.51465 3.73867 5.51465 4.90352C5.51465 5.29184 5.84324 5.62031 6.23145 5.62031C6.61969 5.62031 6.94824 5.29172 6.94824 4.90352C6.94824 4.545 7.24691 4.24648 7.60527 4.24648H9.16035V4.24609ZM8.14473 9.33984C7.60566 9.33984 7.1877 9.75781 7.1877 10.293C7.1877 10.832 7.60566 11.25 8.14473 11.25C8.68262 11.25 9.10059 10.8316 9.10059 10.2941C9.09785 9.75781 8.67988 9.33984 8.14473 9.33984ZM23.4221 17.332C24.4064 16.168 25.0002 14.7109 25.0002 13.125C25.0002 9.32812 21.6408 6.25 17.5002 6.25C17.4879 6.25 17.476 6.2516 17.4637 6.25168C17.4807 6.45703 17.5002 6.66406 17.5002 6.875C17.5002 10.7273 14.3877 13.9531 10.2307 14.7852C11.0432 17.7773 13.9494 20 17.5002 20C18.8162 20 20.0518 19.6872 21.1271 19.1414C22.0783 19.6094 23.2854 20 24.6994 20C24.8189 20 24.9244 19.9326 24.9736 19.8201C25.0216 19.7077 24.9993 19.58 24.9173 19.4938C24.9064 19.4805 24.0588 18.5664 23.4221 17.332ZM21.1916 12.4141L17.9104 15.8516C17.7651 16.0036 17.5643 16.0908 17.3545 16.0933C17.1476 16.0933 16.9396 16.0109 16.7928 15.8644L15.2303 14.3019C14.9251 13.9968 14.9251 13.5023 15.2303 13.1972C15.5354 12.8921 16.0299 12.8921 16.335 13.1972L17.3322 14.1945L20.0611 11.3351C20.3602 11.0226 20.8541 11.011 21.1658 11.3095C21.4768 11.6094 21.4885 12.1016 21.1916 12.4141Z" fill="currentColor"></path>
                                            <path d="M8.12507 0C3.63796 0 7.24202e-05 3.07812 7.24202e-05 6.875C7.24202e-05 8.4207 0.610229 9.84219 1.62781 10.9922C0.987572 12.2719 0.0953849 13.2312 0.0813224 13.2453C-0.00070883 13.3314 -0.0229745 13.4592 0.0250334 13.5716C0.0742912 13.6836 0.179838 13.75 0.299291 13.75C1.79734 13.75 3.06335 13.3156 4.03913 12.8109C5.24226 13.4023 6.63288 13.75 8.12507 13.75C12.6134 13.75 16.2501 10.6719 16.2501 6.875C16.2501 3.07812 12.6134 0 8.12507 0ZM8.1446 11.25C7.60554 11.25 7.18757 10.832 7.18757 10.293C7.18757 9.75508 7.60593 9.33711 8.14343 9.33711C8.68132 9.33711 9.09929 9.75547 9.09929 10.293C9.09773 10.832 8.67976 11.25 8.1446 11.25ZM10.1993 6.69531L8.85945 7.53125V7.59098C8.85945 7.9793 8.53085 8.30777 8.14265 8.30777C7.75445 8.30777 7.42585 7.98047 7.42585 7.59375V7.11328C7.42585 6.87434 7.54535 6.63555 7.78429 6.48594L9.48703 5.47031C9.69538 5.35156 9.81648 5.14062 9.81648 4.90234C9.81648 4.54383 9.51773 4.24531 9.15945 4.24531H7.60554C7.24703 4.24531 6.94851 4.54398 6.94851 4.90234C6.94851 5.29066 6.61992 5.61914 6.23171 5.61914C5.84339 5.61914 5.51492 5.29055 5.51492 4.90234C5.5157 3.73867 6.44148 2.8125 7.60554 2.8125H9.15867C10.3243 2.8125 11.2501 3.73867 11.2501 4.90234C11.2501 5.62109 10.8634 6.30859 10.1993 6.69531Z" fill="currentColor" opacity="0.4"></path>
                                    </svg>
                                </span>
                                <p>{{ __('auth.need_help') }} <br />
                                    <strong>{{ __('auth.help') }}</strong>
                                </p>
                            </a>
                        </div>
                        <div class="_3lQOP">
                            <div class="Ytn0c"><span>{{ __('auth.no_account') }}</span><a href="#" id="switch-to-register">{{ __('auth.create_account') }}</a></div>
                            <button class="_8Plb- PZR2U Je4se" data-type="login" id="close-login-modal-btn">
                                <span class="inove-icon inove-icon--fill">
                                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z" fill="currentColor"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </header>
                    <div class="QWiLj">
                        <div class="l6oz0 v1a-c _1EXdb">
                            <a aria-label="{{ \App\Models\Settings::first()->name ?? config('app.name') }}" class="bwSJI v1a-c">
                                @php
                                    $settings = \App\Models\Settings::first();
                                @endphp
                                <img alt="{{ \App\Models\Settings::first()->name ?? config('app.name') }}" class="Ueilo" src="{{ asset($settings->logo) }}">
                                <img alt="{{ \App\Models\Settings::first()->name ?? config('app.name') }}" class="j2x6J" src="{{ asset($settings->logo) }}">
                            </a>
                        </div>
                        <div class="w-full p-5">
                            <form class="w-full max-w-[350px] md:max-w-[360px] flex flex-col gap-2 mx-auto login-form" method="POST" action="{{ route('login') }}" id="loginForm">
                                @csrf
                                <div class="login-error-message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2" style="display: none;"></div>
                                @if ($errors->any())
                                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div data-v-44b1d268="" class="input-group" autocomplete="off">
                                    <label data-v-44b1d268="" class="group hasLabel placeh" disabled="false" for="login">
                                        <input data-v-44b1d268="" id="login" class="peer input" name="email" placeholder="" type="text" validate-on-blur="true" validate-on-change="true" autocomplete="off" data-maska-value="{{ old('email') }}" data-gtm-form-interact-field-id="8" maxlength="50">
                                        <span data-v-44b1d268="" class="label">
                                            {{ __('auth.email') }} ou CPF
                                        </span>
                                    </label>
                                </div>
                                <div class="password-field">
                                    <div data-v-44b1d268="" class="input-group" autocomplete="off">
                                        <label data-v-44b1d268="" class="group hasSuffix hasLabel placeh" disabled="false" for="password">
                                            <input data-v-44b1d268="" id="password" class="peer input padRight" name="password" placeholder="" type="password" validate-on-blur="true" validate-on-change="true" autocomplete="off" data-maska-value="" data-gtm-form-interact-field-id="9">
                                            <span data-v-44b1d268="" class="label">{{ __('auth.password') }} <!----></span>
                                            <div data-v-44b1d268="" class="suffix-icon cursor-pointer mx-4" onclick="window.togglePasswordVisibility('password')">
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
                                <div class="flex justify-end text-xs text-texts/60 pb-2">
                                    <a class="cursor-pointer" onclick="toggleForms('recovery')">Esqueceu a senha?</a>
                                </div>
                                <div class="Zl0Ov">
                                    <button class="-t5QK" id="login_button" name="login_button" type="submit">{{ __('auth.login') }}</button>
                                </div>
                                <div class="absolute opacity-0 -z-10 pointer-events-none mt-0 mb-0">
                                    <div class="KmKPz CPF-I">
                                        <div class="k-1Tn">
                                            <div>
                                                <div><input type="hidden" name="cf-turnstile-response" id="cf-chl-widget-b8561_response" /></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ekJXu" style="--ddff8944: 40px;">
                                    <div class="_6SX0s"><span>{{ __('auth.login_with') }}</span></div>
                                    <div class="kvu5c HuRI6">
                                        <button type="button" onclick="openTwitchLogin()">
                                            <span class="inove-icon" aria-hidden="true">
                                                <img src="{{ asset('img/twitch.svg') }}" alt="" style="width: 22px; height: 22px;">
                                            </span>
                                            <span>{{ __('auth.login_with_twitch') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <!-- Formulário de Recuperação de Senha -->
                            <form class="w-full max-w-[350px] md:max-w-[360px] flex-col gap-2 mx-auto recovery-form hidden-form" id="recovery-form" style="display: none !important;" data-gtm-form-interact-id="1">
                                @csrf
                                <div class="password-recovery-message mt-2 mb-2" style="display: none;"></div>
                                @if (session('status'))
                                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-2" style="color: rgb(253 255 255); text-align: center;">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @if ($errors->has('email') || $errors->has('identifier'))
                                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2" style="color: rgb(253 255 255); text-align: center;">
                                        {{ $errors->first('email') ?: $errors->first('identifier') }}
                                    </div>
                                @endif

                                <div data-v-44b1d268="" class="input-group" autocomplete="off" tabindex="1">
                                    <label data-v-44b1d268="" class="group hasLabel placeh" disabled="false" for="login">
                                        <input data-v-44b1d268="" id="login-recovery" class="peer input" name="identifier" placeholder="" type="text" validate-on-blur="true" validate-on-change="true" autocomplete="off" data-maska-value="{{ old('identifier') }}" data-gtm-form-interact-field-id="8">
                                        <span data-v-44b1d268="" class="label">
                                            {{ __('auth.email_or_cpf') }}
                                        </span>
                                    </label>
                                </div>
                                <div class="flex gap-2 mt-2">
                                    <button class="Rw3Ra w-1/2" tabindex="1" type="button" onclick="sendEmailRecovery()">{{ __('auth.reset_via_email') }}</button>
                                    <button class="Rw3Ra w-1/2 bg-green-600 hover:bg-green-700" tabindex="1" type="button" onclick="sendWhatsAppRecovery()">{{ __('auth.reset_via_whatsapp') }}</button>
                                </div>
                                <div class="cinjw">
                                    <div class="text-auth-texts">{{ __('auth.has_account') }} <button type="button" id="back-to-login" class="text-auth-links" onclick="toggleForms('login');">{{ __('auth.login') }}</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="dynamicContentFooterContainer" class="relative"></div>
                </div>
            </div>
        </div>
        <div class="GovTb"></div>
    </div>
</div>

<!-- JavaScript para alternar entre os formulários -->
<script type="text/javascript">
    (function() {

        // Função global para alternar entre os formulários
        window.toggleForms = function(formToShow) {
            var loginForm = document.getElementById('loginForm');
            var recoveryForm = document.getElementById('recovery-form');

            if (formToShow === 'recovery') {
                if (loginForm) {
                    loginForm.style.cssText = 'display: none !important;';
                    loginForm.classList.add('hidden-form');
                }
                if (recoveryForm) {
                    recoveryForm.style.cssText = 'display: flex !important;';
                    recoveryForm.classList.remove('hidden-form');
                }
            } else {
                if (loginForm) {
                    loginForm.style.cssText = 'display: flex !important;';
                    loginForm.classList.remove('hidden-form');
                }
                if (recoveryForm) {
                    recoveryForm.style.cssText = 'display: none !important;';
                    recoveryForm.classList.add('hidden-form');
                }
            }
        };

        // Inicializar os formulários após o carregamento do DOM
        document.addEventListener('DOMContentLoaded', function() {
            var loginForm = document.getElementById('loginForm');
            var recoveryForm = document.getElementById('recovery-form');

            if (loginForm) {
                loginForm.style.cssText = 'display: flex !important;';
                loginForm.classList.remove('hidden-form');
            }
            if (recoveryForm) {
                recoveryForm.style.cssText = 'display: none !important;';
                recoveryForm.classList.add('hidden-form');
            }

            // Configurar a submissão do formulário de recuperação
            var recoveryEmailForm = document.getElementById('recovery-form');
            if (recoveryEmailForm) {
                recoveryEmailForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const method = e.submitter.value || 'email';
                    const url = method === 'email' ? '{{ route('password.email') }}' : '{{ route('password.email.whatsapp') }}';

                    // Se for WhatsApp e o navegador permitir, acompanhe os redirecionamentos
                    if (method === 'whatsapp') {
                        // Tratar como um caso especial para WhatsApp
                        const mensagemSucesso = 'Código de recuperação enviado para seu WhatsApp com sucesso!';

                        // Enviar o formulário e deixar o redirecionamento acontecer normalmente
                        if (window.mostrarMensagemSucesso) {
                            mostrarMensagemSucesso(mensagemSucesso);
                        } else if (typeof ToastManager !== 'undefined') {
                            ToastManager.success(mensagemSucesso);
                        } else if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Sucesso!',
                                text: mensagemSucesso,
                                icon: 'success',
                                timer: 3000,
                                timerProgressBar: true
                            });
                        } else {
                            alert(mensagemSucesso);
                        }

                        // Limpar o campo de email
                        document.getElementById('login-recovery').value = '';

                        // Voltar para o formulário de login
                        setTimeout(() => {
                            toggleForms('login');
                        }, 2000);

                        // Enviar o formulário de forma tradicional
                        this.submit();
                        return;
                    }

                    // Para o email, continua com a abordagem fetch
                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    })
                        .then(response => {
                            // Verificar o tipo de conteúdo antes de fazer o parse
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json();
                            }
                            // Se não for JSON, trate como um redirecionamento ou erro
                            if (response.ok) {
                                // Se for uma resposta válida mas não JSON, considere como sucesso
                                return { status: 'success', message: 'Solicitação processada com sucesso' };
                            } else {
                                // Se for um erro, retorne um objeto de erro
                                throw new Error('Resposta não-JSON recebida do servidor');
                            }
                        })
                        .then(data => {
                            if (data.status === 'success') {
                                const mensagem = method === 'email' ?
                                    'E-mail de recuperação enviado com sucesso! Verifique sua caixa de entrada.' :
                                    'Código de recuperação enviado para seu WhatsApp com sucesso!';

                                // Usar a função global de mensagem de sucesso
                                if (window.mostrarMensagemSucesso) {
                                    mostrarMensagemSucesso(mensagem);
                                } else if (typeof ToastManager !== 'undefined') {
                                    ToastManager.success(mensagem);
                                } else if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: 'Sucesso!',
                                        text: mensagem,
                                        icon: 'success',
                                        timer: 3000,
                                        timerProgressBar: true
                                    });
                                } else {
                                    alert(mensagem);
                                }

                                // Limpar o campo de e-mail
                                document.getElementById('login-recovery').value = '';

                                // Voltar para o formulário de login após 2 segundos
                                setTimeout(function() {
                                    toggleForms('login');
                                }, 2000);
                            } else {
                                const mensagemErro = data.message || 'Erro ao enviar o e-mail de recuperação. Tente novamente.';

                                // Usar a função global de mensagem de erro
                                if (window.mostrarMensagemErro) {
                                    mostrarMensagemErro(mensagemErro);
                                } else if (typeof ToastManager !== 'undefined') {
                                    ToastManager.error(mensagemErro);
                                } else if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: 'Erro!',
                                        text: mensagemErro,
                                        icon: 'error'
                                    });
                                } else {
                                    alert(mensagemErro);
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            const mensagemErro = 'Erro ao processar sua solicitação. Tente novamente.';

                            // Usar a função global de mensagem de erro
                            if (window.mostrarMensagemErro) {
                                mostrarMensagemErro(mensagemErro);
                            } else if (typeof ToastManager !== 'undefined') {
                                ToastManager.error(mensagemErro);
                            } else if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Erro!',
                                    text: mensagemErro,
                                    icon: 'error'
                                });
                            } else {
                                alert(mensagemErro);
                            }
                        });
                });
            }
        });
    })();

    // Função para alternar a visibilidade da senha
    window.togglePasswordVisibility = function(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        // Find eye icon container
        const iconContainer = document.querySelector(`[onclick="window.togglePasswordVisibility('${fieldId}')"]`);
        if (!iconContainer) return;

        const svgContainer = iconContainer.querySelector('span');
        if (!svgContainer) return;

        if (field.type === 'password') {
            field.type = 'text';
            // Switch to open eye icon
            svgContainer.innerHTML = `
                <svg height="1em" viewBox="0 0 576 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z" fill="currentColor"></path>
                </svg>
            `;
        } else {
            field.type = 'password';
            // Switch to closed eye icon
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

    // Função para enviar recuperação por e-mail
    function sendEmailRecovery() {
        const identifier = document.getElementById('login-recovery').value;
        if (!identifier) {
            alert('Por favor, preencha seu e-mail ou CPF');
            return;
        }

        // Criar FormData para enviar via AJAX
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('identifier', identifier);

        // Fazer requisição AJAX
        fetch('{{ route("password.email") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            }
            if (response.ok) {
                return { status: 'success', message: 'E-mail de recuperação enviado com sucesso!' };
            } else {
                throw new Error('Erro na resposta do servidor');
            }
        })
        .then(data => {
            if (data.status === 'success') {
                const mensagem = 'E-mail de recuperação enviado com sucesso! Verifique sua caixa de entrada.';

                // Mostrar mensagem de sucesso
                if (window.mostrarMensagemSucesso) {
                    mostrarMensagemSucesso(mensagem);
                } else if (typeof ToastManager !== 'undefined') {
                    ToastManager.success(mensagem);
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Sucesso!',
                        text: mensagem,
                        icon: 'success',
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    alert(mensagem);
                }

                // Limpar o campo e voltar para o login após 2 segundos
                document.getElementById('login-recovery').value = '';
                setTimeout(function() {
                    toggleForms('login');
                }, 2000);
            } else {
                const mensagemErro = data.message || 'Erro ao enviar o e-mail de recuperação. Tente novamente.';
                
                // Mostrar mensagem de erro
                if (window.mostrarMensagemErro) {
                    mostrarMensagemErro(mensagemErro);
                } else if (typeof ToastManager !== 'undefined') {
                    ToastManager.error(mensagemErro);
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Erro!',
                        text: mensagemErro,
                        icon: 'error'
                    });
                } else {
                    alert(mensagemErro);
                }
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            const mensagemErro = 'Erro ao processar sua solicitação. Tente novamente.';
            
            // Mostrar mensagem de erro
            if (window.mostrarMensagemErro) {
                mostrarMensagemErro(mensagemErro);
            } else if (typeof ToastManager !== 'undefined') {
                ToastManager.error(mensagemErro);
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Erro!',
                    text: mensagemErro,
                    icon: 'error'
                });
            } else {
                alert(mensagemErro);
            }
        });
    }

    // Função para enviar recuperação por WhatsApp
    function sendWhatsAppRecovery() {
        const identifier = document.getElementById('login-recovery').value;
        if (!identifier) {
            alert('Por favor, preencha seu e-mail ou CPF');
            return;
        }

        // Criar um formulário temporário para enviar a solicitação de recuperação por WhatsApp
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("password.email.whatsapp") }}';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';

        const identifierInput = document.createElement('input');
        identifierInput.type = 'hidden';
        identifierInput.name = 'identifier';
        identifierInput.value = identifier;

        form.appendChild(csrfInput);
        form.appendChild(identifierInput);
        document.body.appendChild(form);

        // Mostrar um indicador de carregamento ou mensagem
        if (window.mostrarMensagemSucesso) {
            mostrarMensagemSucesso('Processando solicitação...');
        } else if (typeof ToastManager !== 'undefined') {
            ToastManager.success('Processando solicitação...');
        }

        // Enviar o formulário (redirecionará para a página de verificação)
        form.submit();
    }

    // Função para abrir o login da Twitch em um popup
    function openTwitchLogin() {
        // Abrir uma janela popup para a autenticação da Twitch
        let popupWindow = window.open('{{ route('login.twitch') }}', 'TwitchLogin',
            'width=600,height=700,status=yes,scrollbars=yes');

        // Verificar periodicamente se o popup foi fechado
        let popupCheck = setInterval(function() {
            if (popupWindow.closed) {
                clearInterval(popupCheck);
                // Recarregar a página para refletir o login
                window.location.reload();
            }
        }, 500);
    }

    // Ouvir mensagens do popup
    window.addEventListener('message', function(event) {
        // Verificar se a mensagem é do nosso domínio
        if (event.origin !== window.location.origin) return;

        // Verificar se é uma mensagem de autenticação da Twitch
        if (event.data && event.data.type === 'twitch-auth') {

            if (event.data.success) {
                // Mostrar mensagem de sucesso (opcional)
                // Pode usar um toast ou outro sistema de notificação

                // Recarregar a página para refletir o login
                window.location.reload();
            } else {
                // Mostrar mensagem de erro
                alert('Erro no login com Twitch: ' + event.data.message);
            }
        }
    });

    function showRecoveryMessage(message, type = 'info') {
        const messageDiv = document.querySelector('.password-recovery-message');
        if (messageDiv) {
            messageDiv.style.display = 'block';

            let bgColor = 'bg-blue-100';
            let borderColor = 'border-blue-400';
            let textColor = 'text-blue-700';

            if (type === 'error') {
                bgColor = 'bg-red-100';
                borderColor = 'border-red-400';
                textColor = 'text-red-700';
            } else if (type === 'success') {
                bgColor = 'bg-green-100';
                borderColor = 'border-green-400';
                textColor = 'text-green-700';
            }

            messageDiv.className = `password-recovery-message mt-2 mb-2 ${bgColor} border ${borderColor} ${textColor} px-4 py-3 rounded`;
            messageDiv.textContent = message;
        }
    }

    // Gerenciamento do modal de confirmação de cancelamento
    document.addEventListener('DOMContentLoaded', function() {
        // Aguardar um pouco para garantir que os elementos estejam no DOM
        setTimeout(function() {
            const cancelModal = document.getElementById('cancel-confirmation-modal');
            const continueBtn = document.getElementById('continue-login-btn');
            const cancelBtn = document.getElementById('cancel-login-btn');
            const loginModal = document.getElementById('login-modal');

            // Garantir que o modal de confirmação esteja escondido inicialmente
            if (cancelModal) {
                cancelModal.style.display = 'none';
            }

            // Botão "Continuar" - apenas fecha o modal de confirmação
            if (continueBtn) {
                // Remover listeners anteriores se existirem
                const newContinueBtn = continueBtn.cloneNode(true);
                continueBtn.parentNode.replaceChild(newContinueBtn, continueBtn);
                
                newContinueBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    const modal = document.getElementById('cancel-confirmation-modal');
                    if (modal) {
                        modal.style.display = 'none';
                    }
                    return false;
                });
            }

            // Botão "Sim, quero cancelar" - fecha ambos os modais
            if (cancelBtn) {
                // Remover listeners anteriores se existirem
                const newCancelBtn = cancelBtn.cloneNode(true);
                cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
                
                newCancelBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    const modal = document.getElementById('cancel-confirmation-modal');
                    if (modal) {
                        modal.style.display = 'none';
                    }
                    
                    // Fechar o modal de login e limpar overlay
                    if (typeof window.fecharModalLogin === 'function') {
                        window.fecharModalLogin();
                    } else if (typeof fecharModalLogin === 'function') {
                        fecharModalLogin();
                    } else {
                        // Fallback: fechar manualmente
                        if (loginModal) {
                            loginModal.style.display = 'none';
                            loginModal.classList.remove('show');
                            loginModal.classList.add('hidden');
                        }
                        
                        // Remover overlay se existir
                        const modalOverlay = document.querySelector('.modal-overlay.show.active.visible') ||
                            document.querySelector('.modal-overlay') ||
                            document.getElementById('login-modal-overlay');
                        if (modalOverlay) {
                            modalOverlay.style.display = 'none';
                            modalOverlay.classList.remove('show');
                            modalOverlay.classList.add('hidden');
                        }
                        
                        document.body.classList.remove('modal-open');
                        document.body.classList.remove('overflow-hidden');
                    }
                    return false;
                });
            }

            // Fechar modal ao clicar fora dele
            if (cancelModal) {
                cancelModal.addEventListener('click', function(e) {
                    if (e.target === cancelModal) {
                        cancelModal.style.display = 'none';
                    }
                });
            }
        }, 100);
    });
</script>