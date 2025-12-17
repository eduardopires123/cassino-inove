@extends('layouts.app')
@section('content')
<div class="_2sSj3">
    <div class="is0Ic">
        @include('profile.partials.menu')
<div class="cnynX">
    <div class="fVeX8" data-headerheight="65" data-topbarheight="0" data-v-owner="375" style="--236d1da4: 65px;">
        <a class="nuxt-icon nuxt-icon--fill pvpfG" href="{{ route('user.wallet') }}">
            <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"
                    fill="currentColor"
                ></path>
            </svg>
        </a>
        <span class="DxKO1">{{ __('menu.coupons') }}</span>
        <div class="nu8zQ"></div>
    </div>
    <!---->
    <div class="r0epo">
        <h1 class="C38H7">
            <span class="nuxt-icon nuxt-icon--fill _1yj9Z">
            <svg viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg">
                            <path d="M448 128C465.7 128 480 142.3 480 160V352C480 369.7 465.7 384 448 384H128C110.3 384 96 369.7 96 352V160C96 142.3 110.3 128 128 128H448zM448 160H128V352H448V160z" fill="currentColor"></path>
                            <path d="M128 160H448V352H128V160zM512 64C547.3 64 576 92.65 576 128V208C549.5 208 528 229.5 528 256C528 282.5 549.5 304 576 304V384C576 419.3 547.3 448 512 448H64C28.65 448 0 419.3 0 384V304C26.51 304 48 282.5 48 256C48 229.5 26.51 208 0 208V128C0 92.65 28.65 64 64 64H512zM96 352C96 369.7 110.3 384 128 384H448C465.7 384 480 369.7 480 352V160C480 142.3 465.7 128 448 128H128C110.3 128 96 142.3 96 160V352z" fill="currentColor" opacity="0.4"></path>
                        </svg>
            </span>
            {{ __('menu.coupons') }}
        </h1>
        <h2 class="Y65AZ">{{ __('menu.coupon_description') }}</h2>
                    <form id="coupon-form" method="POST" action="{{ route('coupons.redeem') }}" style="
    text-align: center;
">
                        @csrf
                        <div class="mt-4 space-y-4">
                            <label class="block">
                                <div class="NKUH3" style="text-align: center!important;">
                                    <label style="text-align: center!important;flex-direction: column-reverse;">{{ __('menu.coupon_code') }}</label>
                                </div>
                                <div class="flex items-center space-x-2 mt-1.5">
                                    <input
                                        name="code"
                                        id="coupon-code"
                                        class="input-coupon"
                                        placeholder="{{ __('menu.coupon_placeholder') }}"
                                        type="text"
                                        required
                                        autocomplete="off"
                                     style="width: 100%;
    text-align: center;
    background: #00000036;
    font-weight: 700;"/>
                                </div>
                            </label>
                            <button type="submit" class="btn btn-primary" style="color: var(--text-btn-primary);margin-top: 20px;">
                                {{ __('menu.redeem_coupon') }}
                            </button>
                        </div>
                    </form>
        
    </div>
</div>
</div>
</div>
@endsection

<style>
/* Estilo para o spinner de carregamento */
.spinner-border {
    display: inline-block;
    width: 1em;
    height: 1em;
    border: 0.2em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border .75s linear infinite;
    vertical-align: -0.125em;
    margin-right: 0.5em;
}

.spinner-border-sm {
    width: 1em;
    height: 1em;
    border-width: 0.2em;
}

@keyframes spinner-border {
    to { transform: rotate(360deg); }
}

/* Estilo para indicador de loading */
.loading {
    position: relative;
    pointer-events: none;
    opacity: 0.7;
}

.loading::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}
</style>

<!-- Inclui o arquivo de notificações JS -->
<script src="{{ asset('js/notifications.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Processar mensagens de sessão (success/error) para exibir notificações
    @if(session('success'))
        if(typeof mostrarMensagemSucesso === 'function') {
            mostrarMensagemSucesso("{{ session('success') }}");
        }
    @endif
    
    @if(session('error'))
        if(typeof mostrarMensagemErro === 'function') {
            mostrarMensagemErro("{{ session('error') }}");
        }
    @endif
    
    // Manipulação do formulário de cupom via AJAX
    const couponForm = document.getElementById('coupon-form');
    if (couponForm) {
        couponForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const couponCode = document.getElementById('coupon-code').value.trim();
            if (!couponCode) {
                if(typeof mostrarMensagemErro === 'function') {
                    mostrarMensagemErro("{{ __('menu.coupon_empty_error') }}");
                }
                return false;
            }
            
            const submitBtn = couponForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Desabilitar botão e mostrar loading
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('menu.processing') }}';
            submitBtn.disabled = true;
            
            // Adicionar classe loading ao formulário
            couponForm.classList.add('loading');
            
            // Coletar dados do formulário
            const formData = new FormData(couponForm);
            
            // Enviar requisição AJAX
            fetch(couponForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Limpar campo após sucesso
                    document.getElementById('coupon-code').value = '';
                    
                    // Mostrar mensagem de sucesso
                    if(typeof mostrarMensagemSucesso === 'function') {
                        mostrarMensagemSucesso(data.message || "{{ __('menu.coupon_success') }}");
                    }
                } else {
                    // Mostrar mensagem de erro
                    if(typeof mostrarMensagemErro === 'function') {
                        mostrarMensagemErro(data.error || "{{ __('menu.coupon_error') }}");
                    }
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                
                // Mostrar mensagem de erro
                if(typeof mostrarMensagemErro === 'function') {
                    mostrarMensagemErro("{{ __('menu.request_error') }}");
                }
            })
            .finally(() => {
                // Restaurar estado do botão
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
                
                // Remover classe loading do formulário
                couponForm.classList.remove('loading');
            });
        });
    }
});
</script>