@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="_6NoZq">
        <h2 class="text-2xl font-bold text-white mb-6">{{ __('auth.reset_password') }}</h2>
        
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('status') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif
        
        @if (isset($tokenValid) && $tokenValid)
            <form id="reset-password-form" method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token ?? '' }}">

                <div class="mb-4">
                    <label for="email" class="block text-gray-300 text-sm font-medium mb-2">{{ __('auth.enter_email') ?? 'Email Address' }}</label>
                    <input id="email" type="email" placeholder="{{ __('auth.enter_email_placeholder') ?? 'Enter your email' }}" style="background-color: #0000004d; color: #f7f9f9;" class="w-full border border-gray-600 rounded py-2 px-3 reset-password-input @error('email') border-red-500 @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-300 text-sm font-medium mb-2">{{ __('auth.new_password') }}</label>
                    <div class="relative">
                        <input id="password" type="password" placeholder="{{ __('auth.enter_new_password') ?? 'Enter your new password' }}" style="background-color: #0000004d; color: #f7f9f9;" class="w-full border border-gray-600 rounded py-2 px-3 pr-10 reset-password-input @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="window.togglePasswordVisibility('password')">
                            <span class="nuxt-icon nuxt-icon--fill">
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
                    
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password-confirm" class="block text-gray-300 text-sm font-medium mb-2">{{ __('auth.confirm_new_password') }}</label>
                    <div class="relative">
                        <input id="password-confirm" type="password" placeholder="{{ __('auth.confirm_password_placeholder') ?? 'Confirm your password' }}" style="background-color: #0000004d; color: #f7f9f9;" class="w-full border border-gray-600 rounded py-2 px-3 pr-10 reset-password-input" name="password_confirmation" required autocomplete="new-password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="window.togglePasswordVisibility('password-confirm')">
                            <span class="nuxt-icon nuxt-icon--fill">
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

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" id="reset-submit-btn" class="btn btn-cta new inove">
                        <span id="submit-text" style="color: var(--text-btn-primary);">{{ __('auth.reset_password') }}</span>
                        <span id="submit-spinner" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Alterando senha...
                        </span>
                    </button>
                </div>
            </form>
        @else
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <p class="font-bold">{{ __('auth.invalid_token_title') ?? 'Invalid or expired token' }}</p>
                <p class="mt-2">{{ __('auth.invalid_token_message') ?? 'This password reset token is invalid or has expired.' }}</p>
            </div>
            
            <div class="mt-6 text-center">
                <a href="{{ route('password.request') }}" class="text-blue-400 hover:underline">
                    {{ __('auth.request_new_token') ?? 'Request a new password reset link' }}
                </a>
            </div>
        @endif
    </div>
</div>
<style>
    /* Spinner animation */
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    /* Button loading state */
    #reset-submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    /* Prevent input click from triggering sidebar */
    .reset-password-input {
        position: relative;
        z-index: 10;
    }
    
    /* Estilos para os popups de status */
    .status-popup {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9000;
        border-radius: 6px;
        padding: 9px 13px;
        display: flex;
        align-items: center;
        min-width: 320px;
        max-width: 400px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: fadeInRight 0.3s ease-out forwards, fadeOutRight 0.3s ease-out 2.7s forwards;
    }

    .status-popup-success {
        background-color: rgb(0, 0, 0);
        border-left: 4px solid #4CAF50;
    }

    .status-popup-error {
        background-color: rgb(0, 0, 0);
        border-left: 4px solid #F44336;
    }

    .status-icon {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .status-icon-success {
        background-color: #4CAF50;
    }

    .status-icon-error {
        background-color: #F44336;
    }

    .status-message {
        color: #FFFFFF;
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        flex-grow: 1;
    }

    .status-close {
        margin-left: 12px;
        color: #FFFFFF;
        opacity: 0.7;
        cursor: pointer;
        font-size: 18px;
    }

    .status-close:hover {
        opacity: 1;
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(20px);
        }
    }

    @keyframes progress {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }
</style>

<script>
    // Global variables
    let isFormSubmitting = false;
    
    // Toggle password visibility function
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
    
    // Toast notification functions
    window.mostrarMensagemSucesso = function(mensagem) {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.status-popup');
        existingToasts.forEach(toast => toast.remove());
        
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
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        });
        
        // Auto remove after animation completes
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 3000);
    };
    
    window.mostrarMensagemErro = function(mensagem) {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.status-popup');
        existingToasts.forEach(toast => toast.remove());
        
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
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        });
        
        // Auto remove after animation completes
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 3000);
    };
    
    // Function to show spinner
    function showSpinner() {
        const submitBtn = document.getElementById('reset-submit-btn');
        const submitText = document.getElementById('submit-text');
        const submitSpinner = document.getElementById('submit-spinner');
        
        if (submitBtn && submitText && submitSpinner) {
            
            // Disable button
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.7';
            submitBtn.style.cursor = 'not-allowed';
            
            // Hide text and show spinner
            submitText.style.display = 'none';
            submitSpinner.style.display = 'inline-flex';
            submitSpinner.classList.remove('hidden');
            
            isFormSubmitting = true;
            
            return true;
        }
        
        console.error('❌ Não foi possível ativar o spinner - elementos não encontrados');
        return false;
    }
    
    // Function to hide spinner
    function hideSpinner() {
        const submitBtn = document.getElementById('reset-submit-btn');
        const submitText = document.getElementById('submit-text');
        const submitSpinner = document.getElementById('submit-spinner');
        
        if (submitBtn && submitText && submitSpinner) {
            
            // Enable button
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
            
            // Show text and hide spinner
            submitText.style.display = 'inline';
            submitSpinner.style.display = 'none';
            submitSpinner.classList.add('hidden');
            
            isFormSubmitting = false;
            
            return true;
        }
        
        console.error('❌ Não foi possível desativar o spinner - elementos não encontrados');
        return false;
    }
    
    // Main initialization function
    function initializeResetForm() {
        
        // Reset form state
        isFormSubmitting = false;
        hideSpinner();
        
        // Get form elements
        const resetForm = document.getElementById('reset-password-form');
        const submitBtn = document.getElementById('reset-submit-btn');
        
            resetForm: !!resetForm,
            submitBtn: !!submitBtn,
            submitText: !!document.getElementById('submit-text'),
            submitSpinner: !!document.getElementById('submit-spinner')
        });
        
        if (!resetForm || !submitBtn) {
            console.error('❌ Elementos essenciais não encontrados');
            return;
        }
        
        // Remove existing event listeners by cloning elements
        const newSubmitBtn = submitBtn.cloneNode(true);
        submitBtn.parentNode.replaceChild(newSubmitBtn, submitBtn);
        
        const newResetForm = resetForm.cloneNode(true);
        resetForm.parentNode.replaceChild(newResetForm, resetForm);
        
        // Get the new elements
        const form = document.getElementById('reset-password-form');
        const btn = document.getElementById('reset-submit-btn');
        
        // Add form submit listener
        form.addEventListener('submit', function(e) {
            
            if (isFormSubmitting) {
                e.preventDefault();
                return false;
            }
            
            // Show spinner immediately
            if (showSpinner()) {
                
                // Store flag for success message
                localStorage.setItem('showResetSuccess', 'true');
                
                // Check for errors after a delay
                setTimeout(function() {
                    const errorElement = document.querySelector('.text-red-500, .invalid-feedback, [role="alert"].bg-red-100');
                    if (errorElement && errorElement.textContent.trim() !== '') {
                        hideSpinner();
                    }
                }, 1500);
            } else {
                console.error('❌ Falha ao ativar spinner');
            }
        });
        
        // Add button click listener as backup
        btn.addEventListener('click', function(e) {
            
            if (isFormSubmitting) {
                e.preventDefault();
                return false;
            }
            
            // Validate form first
            const form = document.getElementById('reset-password-form');
            if (form && form.checkValidity && !form.checkValidity()) {
                return;
            }
            
            // Small delay to ensure form processing
            setTimeout(function() {
                if (!isFormSubmitting) {
                    showSpinner();
                }
            }, 50);
        });
        
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initializeResetForm, 100);
        });
    } else {
        setTimeout(initializeResetForm, 100);
    }
    
    // Also initialize on window load as backup
    window.addEventListener('load', function() {
        setTimeout(initializeResetForm, 200);
    });
    
    // Reinitialize every 2 seconds for the first 10 seconds (debugging)
    let reinitCount = 0;
    const reinitInterval = setInterval(function() {
        reinitCount++;
        initializeResetForm();
        
        if (reinitCount >= 5) {
            clearInterval(reinitInterval);
        }
    }, 2000);
    
    // Rest of the original functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent sidebar toggle on input interactions
        const resetInputs = document.querySelectorAll('.reset-password-input');
        resetInputs.forEach(input => {
            input.addEventListener('click', function(e) { e.stopPropagation(); });
            input.addEventListener('focus', function(e) { e.stopPropagation(); });
            input.addEventListener('blur', function(e) { e.stopPropagation(); });
            input.addEventListener('input', function(e) { e.stopPropagation(); });
        });
        
        // Prevent clicks on the form container from triggering sidebar
        const formContainer = document.querySelector('#reset-password-form');
        if (formContainer) {
            formContainer.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        // Language dropdown functionality
        const languageSwitcher = document.getElementById('languageSwitcher');
        const languageOptions = document.getElementById('languageOptions');
        
        if (languageSwitcher && languageOptions) {
            languageSwitcher.addEventListener('click', function(e) {
                e.preventDefault();
                languageOptions.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function(e) {
                if (!languageSwitcher.contains(e.target) && !languageOptions.contains(e.target)) {
                    languageOptions.classList.add('hidden');
                }
            });
        }
        
        // Check if token is valid and show authentication success message
        @if(isset($tokenValid) && $tokenValid)
            const urlParams = new URLSearchParams(window.location.search);
            const isFirstAccess = !localStorage.getItem('resetPageVisited_{{ $token ?? '' }}');
            
            if (isFirstAccess && typeof window.mostrarMensagemSucesso === 'function') {
                window.mostrarMensagemSucesso('Link autenticado com sucesso! Você pode alterar sua senha agora.');
                localStorage.setItem('resetPageVisited_{{ $token ?? '' }}', 'true');
            }
        @endif
        
        // Check for form success on page load
        if (localStorage.getItem('showResetSuccess') === 'true') {
            localStorage.removeItem('showResetSuccess');
            
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith('resetPageVisited_')) {
                    localStorage.removeItem(key);
                }
            });
            
            if (typeof window.mostrarMensagemSucesso === 'function') {
                window.mostrarMensagemSucesso("{{ __('auth.password_reset_success') ?? 'Senha alterada com sucesso!' }}");
            }
        }
        
        // Check for session messages
        if ("{{ session('status') }}") {
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith('resetPageVisited_')) {
                    localStorage.removeItem(key);
                }
            });
            
            if (typeof window.mostrarMensagemSucesso === 'function') {
                window.mostrarMensagemSucesso("{{ session('status') }}");
            }
        }
        
        if ("{{ session('success') }}") {
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith('resetPageVisited_')) {
                    localStorage.removeItem(key);
                }
            });
            
            if (typeof window.mostrarMensagemSucesso === 'function') {
                window.mostrarMensagemSucesso("{{ session('success') }}");
            }
        }
        
        if ("{{ session('error') }}") {
            if (typeof window.mostrarMensagemErro === 'function') {
                window.mostrarMensagemErro("{{ session('error') }}");
            }
        }
        
        // Check for error messages from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const errorMessage = urlParams.get('error');
        if (errorMessage) {
            if (typeof window.mostrarMensagemErro === 'function') {
                window.mostrarMensagemErro(decodeURIComponent(errorMessage));
            }
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }
        
        // Check for error messages on page load
        const errorElements = document.querySelectorAll('.text-red-500, .invalid-feedback, [role="alert"].bg-red-100');
        errorElements.forEach(element => {
            if (element && element.textContent && element.textContent.trim() !== '') {
                if (typeof window.mostrarMensagemErro === 'function') {
                    window.mostrarMensagemErro(element.textContent.trim());
                }
            }
        });
        
        // Hide alert containers after toast is shown
        const alertContainers = document.querySelectorAll('[role="alert"]');
        alertContainers.forEach(container => {
            if (container) {
                container.style.display = 'none';
            }
        });
    });
</script>
@endsection
