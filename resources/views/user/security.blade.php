@extends('layouts.app')

@section('content')
<div class="_2sSj3">
    <div class="is0Ic">
        @include('profile.partials.menu')
   <div class="cnynX">
    <div class="fVeX8" data-headerheight="65" data-topbarheight="0" data-v-owner="1743" style="--236d1da4: 65px;">
        <a class="nuxt-icon nuxt-icon--fill pvpfG" href="{{ route('user.wallet') }}">
            <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"
                    fill="currentColor"
                ></path>
            </svg>
        </a>
        <span class="DxKO1">{{ __('menu.login_security_title') }}</span>
        <div class="nu8zQ"></div>
    </div>
    <div class="_6NoZq" data-v-owner="1743" style="--236d1da4: 65px;">
        <!---->
        <div class="flex flex-col gap-4 md:gap-5">
            <!---->
            <div class="mMF6F">
                <div class="yNtqu">
                    <div class="-dJAN">{{ __('menu.change_password') }}</div>
                    <div class="OHEi- vsh0K">
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                        {{ __('menu.strong_password') }}
                    </div>
                </div>
                <form class="BIyvI" id="passwordChangeForm" data-gtm-form-interact-id="2">
                    <div class="j51YU">{{ __('menu.password_update_instructions') }}</div>
                    <div class="Gquwz">
                        <label for="currentPassword">{{ __('menu.current_password') }}<span>*</span></label>
                        <div data-v-44b1d268="" class="input-group" autocomplete="current-password">
                            <div data-v-44b1d268="" class="group hasSuffix placeh" disabled="false" for="currentPassword">
                                <input
                                    data-v-44b1d268=""
                                    id="currentPassword"
                                    class="peer input padRight"
                                    name="currentPassword"
                                    placeholder="{{ __('menu.current_password') }}"
                                    type="password"
                                    validate-on-blur="true"
                                    validate-on-change="true"
                                    autocomplete="current-password"
                                    autocomplete="off"
                                /> 
                                <div data-v-44b1d268="" class="suffix-icon cursor-pointer mx-4" onclick="window.togglePasswordVisibility('currentPassword')">
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
                            </div>
                        </div>
                        <span class="error-message text-error hidden" id="currentPassword-error"></span>
                    </div>
                    <div class="Gquwz">
                        <label for="newPassword">{{ __('menu.new_password') }}<span>*</span></label>
                        <div data-v-44b1d268="" class="input-group" autocomplete="new-password">
                            <div data-v-44b1d268="" class="group hasSuffix placeh" disabled="false" for="newPassword">
                                <input
                                    data-v-44b1d268=""
                                    id="newPassword"
                                    class="peer input padRight hasContent"
                                    name="newPassword"
                                    placeholder="{{ __('menu.new_password') }}"
                                    type="password"
                                    validate-on-blur="true"
                                    validate-on-change="true"
                                    autocomplete="new-password"
                                    autocomplete="off"
                                    aria-autocomplete="list"
                                />
                                <div data-v-44b1d268="" class="suffix-icon cursor-pointer mx-4" onclick="window.togglePasswordVisibility('newPassword')">
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
                            </div>
                        </div>
                    </div>
                    <div class="Gquwz">
                        <label for="confirmPassword">{{ __('menu.confirm_password') }}<span>*</span></label>
                        <div data-v-44b1d268="" class="input-group" autocomplete="new-password">
                            <div data-v-44b1d268="" class="group hasSuffix placeh" disabled="false" for="confirmPassword">
                                <input
                                    data-v-44b1d268=""
                                    id="confirmPassword"
                                    class="peer input padRight hasContent"
                                    name="confirmPassword"
                                    placeholder="{{ __('menu.confirm_password') }}"
                                    type="password"
                                    validate-on-blur="true"
                                    validate-on-change="true"
                                    autocomplete="new-password"
                                    autocomplete="off"
                                />
                                <div data-v-44b1d268="" class="suffix-icon cursor-pointer mx-4" onclick="window.togglePasswordVisibility('confirmPassword')">
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
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 pb-1 flex flex-col gap-5">
                        <section style="--ea2c3f08: 50%;">
                            <div class="NdPac">
                                <div class="DJZny"></div>
                                <span class="w-1/2"></span><span class="w-1/4"></span><span class="w-3/4"></span>
                            </div>
                            <div class="iFklr">
                                <span>
                                    <span id="letterCheck" class="nuxt-icon nuxt-icon--fill text-error">
                                        <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                                                fill="currentColor"
                                            ></path>
                                        </svg>
                                    </span>
                                    {{ __('menu.at_least_one_letter') }}
                                </span>
                                <span>
                                    <span id="numberCheck" class="nuxt-icon nuxt-icon--fill text-error">
                                        <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                                                fill="currentColor"
                                            ></path>
                                        </svg>
                                    </span>
                                    {{ __('menu.at_least_one_number') }}
                                </span>
                                <span>
                                    <span id="specialCharCheck" class="nuxt-icon nuxt-icon--fill text-error">
                                        <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                                                fill="currentColor"
                                            ></path>
                                        </svg>
                                    </span>
                                    {{ __('menu.at_least_one_special_char') }}
                                </span>
                                <span>
                                    <span id="lengthCheck" class="nuxt-icon nuxt-icon--fill text-error">
                                        <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                                                fill="currentColor"
                                            ></path>
                                        </svg>
                                    </span>
                                    {{ __('menu.at_least_8_chars') }}
                                </span>
                            </div>
                        </section>
                    </div>
                    <div class="flex justify-between items-end w-full col-span-2 mt-4">
                        <button class="zc0q0" id="submitPasswordChange" type="submit">
                            <span class="nuxt-icon nuxt-icon--fill">
                                <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"
                                        fill="currentColor"
                                    ></path>
                                </svg>
                            </span>
                            {{ __('menu.submit') }}
                        </button>
                    </div>
                    <div id="statusMessage" class="mt-3 hidden"></div>
                </form>
            </div>
            <div class="JTrHM _8yOY3"></div>
            <!---->
            <div class="iWQcx tSfyb">
                <div class="_6lCJ3">
                    <p class="qZf2h">
                        {{ __('menu.two_factor_auth') }}
                        <span class="nuxt-icon nuxt-icon--fill relative">
                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M256 192c17.67 0 32-14.33 32-32c0-17.67-14.33-32-32-32S224 142.3 224 160C224 177.7 238.3 192 256 192zM296 336h-16V248C280 234.8 269.3 224 256 224H224C210.8 224 200 234.8 200 248S210.8 272 224 272h8v64h-16C202.8 336 192 346.8 192 360S202.8 384 216 384h80c13.25 0 24-10.75 24-24S309.3 336 296 336z"
                                    fill="currentColor"
                                ></path>
                                <path
                                    d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 128c17.67 0 32 14.33 32 32c0 17.67-14.33 32-32 32S224 177.7 224 160C224 142.3 238.3 128 256 128zM296 384h-80C202.8 384 192 373.3 192 360s10.75-24 24-24h16v-64H224c-13.25 0-24-10.75-24-24S210.8 224 224 224h32c13.25 0 24 10.75 24 24v88h16c13.25 0 24 10.75 24 24S309.3 384 296 384z"
                                    fill="currentColor"
                                    opacity="0.4"
                                ></path>
                            </svg>
                        </span>
                    </p>
                    <button class="uefgu">{{ __('menu.coming_soon') }}</button>
                </div>
                <div class="_5QRNa"><p class="_7hS8V">{{ __('menu.disabled') }}</p></div>
            </div>
            <div class="JTrHM _8yOY3"></div>
        </div>
    </div>
</div>
</div>
</div> 
@endsection
<script>
// Objeto global para armazenar as funções
window.passwordUtils = {
    // Ícones SVG para check e X
    checkIcon: `<svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"
                        fill="currentColor"
                    ></path>
                </svg>`,
    
    xIcon: `<svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                    fill="currentColor"
                ></path>
            </svg>`
};

// Exibe o popup de status usando o sistema integrado de toast
function mostrarMensagemSucesso(mensagem) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'status-popup status-popup-success';
    notification.innerHTML = `
        <div class="status-icon status-icon-success">
            <i class="fa fa-check"></i>
        </div>
        <div class="status-message">${mensagem}</div>
        <div class="status-close">&times;</div>
        <div class="status-progress-success"></div>
    `;
    
    // Add to DOM
    document.body.appendChild(notification);
    
    // Set up close button
    const closeBtn = notification.querySelector('.status-close');
    closeBtn.addEventListener('click', function() {
        document.body.removeChild(notification);
    });
    
    // Auto remove after animation completes
    setTimeout(() => {
        if (document.body.contains(notification)) {
            document.body.removeChild(notification);
        }
    }, 3000);
}

function mostrarMensagemErro(mensagem) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'status-popup status-popup-error';
    notification.innerHTML = `
        <div class="status-icon status-icon-error">
            <i class="fa fa-times"></i>
        </div>
        <div class="status-message">${mensagem}</div>
        <div class="status-close">&times;</div>
        <div class="status-progress-error"></div>
    `;
    
    // Add to DOM
    document.body.appendChild(notification);
    
    // Set up close button
    const closeBtn = notification.querySelector('.status-close');
    closeBtn.addEventListener('click', function() {
        document.body.removeChild(notification);
    });
    
    // Auto remove after animation completes
    setTimeout(() => {
        if (document.body.contains(notification)) {
            document.body.removeChild(notification);
        }
    }, 3000);
}

// Atualiza o indicador visual de requisito
function updatePasswordCheck(elementId, isValid) {
    const element = document.getElementById(elementId);
    if (element) {
        if (isValid) {
            element.classList.remove('text-error');
            element.classList.add('text-success');
            element.innerHTML = window.passwordUtils.checkIcon;
        } else {
            element.classList.remove('text-success'); 
            element.classList.add('text-error');
            element.innerHTML = window.passwordUtils.xIcon;
        }
    }
}

// Inicializa o formulário e configura os eventos
document.addEventListener('DOMContentLoaded', function() {
    const newPasswordInput = document.getElementById('newPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const currentPasswordInput = document.getElementById('currentPassword');
    const form = document.getElementById('passwordChangeForm');
    const statusMessage = document.getElementById('statusMessage');
    const currentPasswordError = document.getElementById('currentPassword-error');
    
    // Bloquear colar (paste) nos campos de senha para segurança
    function blockPaste(e) {
        e.preventDefault();
        mostrarMensagemErro('Por motivos de segurança, não é permitido colar senhas. Digite manualmente.');
        return false;
    }
    
    // Bloquear atalhos de teclado para colar
    function blockKeyboardPaste(e) {
        // Ctrl+V ou Cmd+V
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 86) {
            e.preventDefault();
            mostrarMensagemErro('Por motivos de segurança, não é permitido colar senhas. Digite manualmente.');
            return false;
        }
    }
    
    // Adicionar eventos para bloquear paste nos campos de senha
    if (currentPasswordInput) {
        currentPasswordInput.addEventListener('paste', blockPaste);
        currentPasswordInput.addEventListener('keydown', blockKeyboardPaste);
        currentPasswordInput.addEventListener('contextmenu', function(e) {
            e.preventDefault(); // Bloquear menu de contexto (botão direito)
        });
    }
    
    if (newPasswordInput) {
        newPasswordInput.addEventListener('paste', blockPaste);
        newPasswordInput.addEventListener('keydown', blockKeyboardPaste);
        newPasswordInput.addEventListener('contextmenu', function(e) {
            e.preventDefault(); // Bloquear menu de contexto (botão direito)
        });
    }
    
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('paste', blockPaste);
        confirmPasswordInput.addEventListener('keydown', blockKeyboardPaste);
        confirmPasswordInput.addEventListener('contextmenu', function(e) {
            e.preventDefault(); // Bloquear menu de contexto (botão direito)
        });
    }
    
    // Verifica força da senha em tempo real
    newPasswordInput.addEventListener('input', function() {
        const password = this.value;
        
        // Verifica letra
        updatePasswordCheck('letterCheck', /[a-zA-Z]/.test(password));
        
        // Verifica número
        updatePasswordCheck('numberCheck', /[0-9]/.test(password));
        
        // Verifica caractere especial
        updatePasswordCheck('specialCharCheck', /[^a-zA-Z\d]/.test(password));
        
        // Verifica comprimento mínimo
        updatePasswordCheck('lengthCheck', password.length >= 8);
        
        // Verifica se confirmação ainda coincide
        if (confirmPasswordInput.value) {
            if (confirmPasswordInput.value !== password) {
                confirmPasswordInput.classList.add('border-error');
            } else {
                confirmPasswordInput.classList.remove('border-error');
            }
        }
    });
    
    // Verifica se as senhas coincidem
    confirmPasswordInput.addEventListener('input', function() {
        if (this.value !== newPasswordInput.value) {
            this.classList.add('border-error');
        } else {
            this.classList.remove('border-error');
        }
    });
    
    // Submissão do formulário
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar senha atual
        if (!currentPasswordInput.value) {
            currentPasswordError.textContent = '{{ __('menu.current_password_required') }}';
            currentPasswordError.classList.remove('hidden');
            return;
        } else {
            currentPasswordError.classList.add('hidden');
        }
        
        // Validar nova senha
        const password = newPasswordInput.value;
        const isPasswordValid = 
            /[a-zA-Z]/.test(password) && 
            /[0-9]/.test(password) && 
            /[^a-zA-Z\d]/.test(password) && 
            password.length >= 8;
            
        if (!isPasswordValid) {
            mostrarMensagemErro('{{ __('menu.password_requirements_not_met') }}');
            return;
        }
        
        // Verificar se as senhas coincidem
        if (password !== confirmPasswordInput.value) {
            mostrarMensagemErro('{{ __('menu.passwords_dont_match') }}');
            return;
        }
        
        // Preparar dados para o envio AJAX
        const formData = new FormData();
        formData.append('currentPassword', currentPasswordInput.value);
        formData.append('newPassword', newPasswordInput.value);
        formData.append('newPassword_confirmation', confirmPasswordInput.value);
        
        // Obter CSRF token com fallback
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value;
        
        if (csrfToken) {
            formData.append('_token', csrfToken);
        } else {
            console.error('CSRF token não encontrado');
            mostrarMensagemErro('Erro de segurança. Recarregue a página e tente novamente.');
            return;
        }
        
        // Enviar requisição AJAX
        fetch('/user/password/update', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    // Se for erro de validação (422), mostrar a primeira mensagem de erro
                    if (response.status === 422 && errorData.errors) {
                        const firstError = Object.values(errorData.errors)[0][0];
                        throw new Error(firstError);
                    }
                    // Se houver uma mensagem específica no response
                    if (errorData.message) {
                        throw new Error(errorData.message);
                    }
                    // Mensagem genérica para outros erros
                    throw new Error('{{ __('menu.password_update_error') }}');
                }).catch(jsonError => {
                    // Se não conseguir fazer parse do JSON, mostrar erro genérico
                    throw new Error('Erro no servidor. Status: ' + response.status);
                });
            }
            return response.json();
        })
        .then(data => {
            // Mostrar popup de sucesso
            mostrarMensagemSucesso('{{ __('menu.password_update_success') }}');
            
            // Resetar formulário
            form.reset();
            
            // Resetar os indicadores
            updatePasswordCheck('letterCheck', false);
            updatePasswordCheck('numberCheck', false);
            updatePasswordCheck('specialCharCheck', false);
            updatePasswordCheck('lengthCheck', false);
        })
        .catch(error => {
            // Mostrar mensagem de erro
            mostrarMensagemErro(error.message || '{{ __('menu.request_processing_error') }}');
        });
    });
});
</script>
