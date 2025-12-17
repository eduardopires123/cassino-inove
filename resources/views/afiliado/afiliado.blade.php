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
        <span class="DxKO1">{{ __('messages.refer_friend_title') }}</span>
        <div class="nu8zQ"></div>
    </div>
    <!---->
    <div class="r0epo">
        <h1 class="C38H7">
            <span class="nuxt-icon nuxt-icon--fill _1yj9Z">
                <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M276.1 118.3C282.7 119.5 292.7 121.5 297.1 122.7C307.8 125.5 314.2 136.5 311.3 147.1C308.5 157.8 297.5 164.2 286.9 161.3C283 160.3 269.5 157.7 265.1 156.1C252.9 155.1 242.1 156.7 236.5 159.6C230.2 162.4 228.7 165.7 228.3 167.7C227.7 171.1 228.3 172.3 228.5 172.7C228.7 173.2 229.5 174.4 232.1 176.2C238.2 180.4 247.8 183.4 261.1 187.7L262.8 187.9C274.9 191.6 291.1 196.4 303.2 205.3C309.9 210.1 316.2 216.7 320.1 225.7C324.1 234.8 324.9 244.8 323.1 255.2C319.8 274.2 307.2 287.2 291.4 293.9C286.6 295.1 281.4 297.5 276.1 298.5V304C276.1 315.1 267.1 324.1 255.1 324.1C244.9 324.1 235.9 315.1 235.9 304V297.6C226.4 295.4 213.1 291.2 206.1 288.5C204.4 287.9 202.9 287.4 201.7 286.1C191.2 283.5 185.5 272.2 189 261.7C192.5 251.2 203.8 245.5 214.3 249C216.3 249.7 218.5 250.4 220.7 251.2C230.2 254.4 240.9 258 246.9 259C259.7 261 269.6 259.7 275.7 257.1C281.2 254.8 283.1 251.8 283.7 248.3C284.4 244.3 283.8 242.5 283.4 241.7C283.1 240.8 282.2 239.4 279.7 237.6C273.8 233.3 264.4 230.2 250.4 225.9L248.2 225.3C236.5 221.8 221.2 217.2 209.6 209.3C203 204.8 196.5 198.6 192.3 189.8C188.1 180.9 187.1 171 188.9 160.8C192.1 142.5 205.1 129.9 220 123.1C224.1 120.9 230.3 119.2 235.9 118V112C235.9 100.9 244.9 91.9 256 91.9C267.1 91.9 276.1 100.9 276.1 112L276.1 118.3zM136.2 416H64V448H448V416H375.8C403.1 399.7 428.6 377.9 448 352H464C490.5 352 512 373.5 512 400V464C512 490.5 490.5 512 464 512H48C21.49 512 0 490.5 0 464V400C0 373.5 21.49 352 48 352H63.98C83.43 377.9 108 399.7 136.2 416H136.2z"
                        fill="currentColor"
                    ></path>
                    <path
                        d="M48 208C48 93.12 141.1 0 256 0C370.9 0 464 93.12 464 208C464 322.9 370.9 416 256 416C141.1 416 48 322.9 48 208zM276.1 112C276.1 100.9 267.1 91.9 255.1 91.9C244.9 91.9 235.9 100.9 235.9 112V118C230.3 119.2 224.1 120.9 220 123.1C205.1 129.9 192.1 142.5 188.9 160.8C187.1 171 188.1 180.9 192.3 189.8C196.5 198.6 203 204.8 209.6 209.3C221.2 217.2 236.5 221.8 248.2 225.3L250.4 225.9C264.4 230.2 273.8 233.3 279.7 237.6C282.2 239.4 283.1 240.8 283.4 241.7C283.8 242.5 284.4 244.3 283.7 248.3C283.1 251.8 281.2 254.8 275.7 257.1C269.6 259.7 259.7 261 246.9 259C240.9 258 230.2 254.4 220.7 251.2C218.5 250.4 216.3 249.7 214.3 249C203.8 245.5 192.5 251.2 189 261.7C185.5 272.2 191.2 283.5 201.7 286.1C202.9 287.4 204.4 287.9 206.1 288.5C213.1 291.2 226.4 295.4 235.9 297.6V304C235.9 315.1 244.9 324.1 255.1 324.1C267.1 324.1 276.1 315.1 276.1 304V298.5C281.4 297.5 286.6 295.1 291.4 293.9C307.2 287.2 319.8 274.2 323.1 255.2C324.9 244.8 324.1 234.8 320.1 225.7C316.2 216.7 309.9 210.1 303.2 205.3C291.1 196.4 274.9 191.6 262.8 187.9L261.1 187.7C247.8 183.4 238.2 180.4 232.1 176.2C229.5 174.4 228.7 173.2 228.5 172.7C228.3 172.3 227.7 171.1 228.3 167.7C228.7 165.7 230.2 162.4 236.5 159.6C242.1 156.7 252.9 155.1 265.1 156.1C269.5 157.7 283 160.3 286.9 161.3C297.5 164.2 308.5 157.8 311.3 147.1C314.2 136.5 307.8 125.5 297.1 122.7C292.7 121.5 282.7 119.5 276.1 118.3L276.1 112z"
                        fill="currentColor"
                        opacity="0.4"
                    ></path>
                </svg>
            </span>
            {{ __('messages.refer_friend_title') }}
        </h1>
        <h2 class="Y65AZ">{{ __('messages.refer_friend_subtitle') }}</h2>
        <p class="Y65AZ _1CzXm">{{ __('messages.refer_friend_requirement') }}</p>
        <div class="Ti--1">
            <div data-v-44b1d268="" class="input-group flex-1 min-w-48">
                <div data-v-44b1d268="" class="group placeh" disabled="false" for="id" readonly="">
                    <input data-v-44b1d268="" id="id" class="peer input hasContent flex-1 min-w-48" name="input-name" value="{{ url('/') }}?ref={{ auth()->user()->id }}" readonly/>
                </div>
            </div>
            <div class="-rR3y">
                <button class="ow-Rk vXH--" id="btn-copiar-link-afiliado" type="button">
                    <span class="nuxt-icon nuxt-icon--fill _1yj9Z">
                        <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                            <path d="M512 96V336C512 362.508 490.51 384 464 384H272C245.49 384 224 362.508 224 336V48C224 21.492 245.49 0 272 0H416V96H512Z" fill="currentColor"></path>
                            <path
                                d="M192 352V128H48C21.49 128 0 149.492 0 176V464C0 490.508 21.49 512 48 512H240C266.51 512 288 490.508 288 464V416H256C220.652 416 192 387.344 192 352ZM416 0V96H512L416 0Z"
                                fill="currentColor"
                                opacity="0.4"
                            ></path>
                        </svg>
                    </span>
                    <span class="_-5lWE">{{ __('messages.copy_link') }}</span>
                </button>
            </div>
        </div>
    </div>
    @php
        $HideBalanceRefer = $user->wallet->hide_balancerefer;
        $id = $user->id;
    @endphp
    <div class="_6NoZq f5h-R">
        <div class="IR9D5">
            <div class="_1uK96">
                <span class="nuxt-icon nuxt-icon--fill">
                    <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M48 127.1L448 128C448.4 128 448.9 128 449.3 128C460.5 128.3 470.9 131.6 480 136.9V136.6C499.1 147.6 512 168.3 512 192V416C512 451.3 483.3 480 448 480H64C28.65 480 0 451.3 0 416V80C0 106.5 21.49 128 48 128L48 127.1zM416 336C433.7 336 448 321.7 448 304C448 286.3 433.7 272 416 272C398.3 272 384 286.3 384 304C384 321.7 398.3 336 416 336z"
                            fill="currentColor"
                        ></path>
                        <path d="M0 80C0 53.49 21.49 32 48 32H432C458.5 32 480 53.49 480 80V136.6C470.6 131.1 459.7 128 448 128L48 128C21.49 128 0 106.5 0 80V80z" fill="currentColor" opacity="0.4"></path>
                    </svg>
                </span>
            </div>
            <span class="FoLjG">
                <div class="_3qn2o">
                    <span class="nuxt-icon R5m-m">
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
                    BRL
                    
        <div class="f5h-Rx">
            <span class="mW59Je">
                    Indicados: <strong>{{$indicados}}</strong>
            </span>
        </div>
                </div>
                
            </span>
            <strong class="SwGZW">
                <div id="s1r" name="s1r" data-saldo="{!! number_format($user->wallet->refer_rewards, 2, ',', '.') !!}">
                            {!! 'R$ ' . number_format($user->wallet->refer_rewards, 2, ',', '.') !!}
                </div>
            </strong>
        </div>
        <div class="mW59J flex items-center justify-between">
            <button class="SLZAB Ttn7-" onclick="AbreSaqueAff();">Saque Afiliado</button>
        </div>
    </div>
    @include('afiliado.partials.referidos')
    @include('afiliado.partials.cpa-rev')
    <!-- Modal para saque de afiliados -->
    @include('payment.saqueaf-modal')
</div>
</div>
</div>
<style>
    .f5h-Rx {
        font-size: 0.7rem;
    }

    .f5h-Rx .mW59Je {
        background-color: var(--primary-color);
        --tw-text-opacity: 1;
        color: rgb(33 36 37 / var(--tw-text-opacity, 1));
        opacity: 1;
        border-radius: 0.25rem;
        cursor: pointer;
        font-weight: 600;
        padding: 0.15rem 0.4rem;
    }

</style>
<!-- Script inline para copiar link de afiliado -->
<script type="text/javascript">
    // Espera o carregamento da página
    window.onload = function() {
        // Obtém o botão
        var botao = document.getElementById('btn-copiar-link-afiliado');
        
        // Adiciona o evento de clique
        if (botao) {
            botao.onclick = function() {
                // Obtém o valor do input
                var input = document.querySelector('input[name="input-name"]');
                var texto = input.value;
                
                // Método simplificado
                var temp = document.createElement("input");
                document.body.appendChild(temp);
                temp.value = texto;
                temp.select();
                
                try {
                    // Tenta copiar
                    document.execCommand("copy");
                    botao.querySelector('._-5lWE').textContent = '{{ __("messages.link_copied") }}';
                } catch (err) {
                    // Em caso de erro
                    botao.querySelector('._-5lWE').textContent = '{{ __("messages.copy_error") }}';
                }
                
                // Remove o elemento temporário
                document.body.removeChild(temp);
                
                // Restaura o texto original
                setTimeout(function() {
                    botao.querySelector('._-5lWE').textContent = '{{ __("messages.copy_link") }}';
                }, 2000);
                
                return false;
            };
        }
    };
    
    // Função para abrir o modal de saque de afiliado
    function AbreSaqueAff() {
        // Mostra o modal
        var saqueModal = document.getElementById('saqueModal');
        if (saqueModal) {
            saqueModal.classList.remove('hidden');
        }

        // Adiciona evento para fechar o modal
        var closeButton = document.getElementById('close-saque-modal');
        if (closeButton) {
            closeButton.onclick = function() {
                saqueModal.classList.add('hidden');
            };
        }

        // Configura a formatação do campo de valor

    }
</script>
@endsection