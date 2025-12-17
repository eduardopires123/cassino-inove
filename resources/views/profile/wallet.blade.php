@extends('layouts.app')

@section('content')
    <div class="_2sSj3">
        <div class="is0Ic">
            @include('profile.partials.menu')
            <div class="cnynX">
                <div class="_2hA3J">
                    <div class="_6NoZq f5h-R">
                        <div class="IR9D5">
                            <div class="_1uK96">
                                <a class="nuxt-icon nuxt-icon--fill" href="{{ route('user.wallet') }}">
                                    <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M48 127.1L448 128C448.4 128 448.9 128 449.3 128C460.5 128.3 470.9 131.6 480 136.9V136.6C499.1 147.6 512 168.3 512 192V416C512 451.3 483.3 480 448 480H64C28.65 480 0 451.3 0 416V80C0 106.5 21.49 128 48 128L48 127.1zM416 336C433.7 336 448 321.7 448 304C448 286.3 433.7 272 416 272C398.3 272 384 286.3 384 304C384 321.7 398.3 336 416 336z" fill="currentColor"></path>
                                        <path d="M0 80C0 53.49 21.49 32 48 32H432C458.5 32 480 53.49 480 80V136.6C470.6 131.1 459.7 128 448 128L48 128C21.49 128 0 106.5 0 80V80z" fill="currentColor" opacity="0.4"></path>
                                    </svg>
                                </a>
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
                                        <path fill="#0052b4" d="M255.7 167a89 89 0 0 0-41.9 10.6 89 89 0 0 0-39.6 43.4 181.7 181.7 0 0 1 169.1 52.2 89 89 0 0 0-9-59.4 89 89 0 0 0-78.6-46.8zM212 250.5a149 149 0 0 0-45 6.8 89 89 0 0 0 10.5 40.9 89 89 0 0 0 120.6 36.2 89 89 0 0 0 30.7-27.3A151 151 0 0 0 212 250.5z"></path>
                                    </g>
                                </svg>
                            </span>
                            BRL
                            </div>
                            <div class="RHZ-U">#{{ auth()->user()->id }}</div>
                        </span>
                            <strong class="SwGZW" id="balance_wallet">R$&nbsp;{{ number_format(auth()->user()->wallet->balance ?? 0, 2, ',', '.') }}</strong>
                        </div>

                        <div class="mW59J">
                            <button class="SLZAB Ttn7-" id="wallet-deposit-btn">{{ __('header.deposit') }}</button>
                            <button class="SLZAB BBDgS" id="open-saque-modal">{{ __('menu.withdrawal') }}</button>
                        </div>
                    </div>

                    <div class="wallet-additional-balances">
                        <div class="wallet-balance-item">
                            <div class="wallet-balance-icon">
                                🎁
                            </div>
                            <div class="wallet-balance-details">
                                <span class="wallet-balance-label _3qn2o">{{ __('menu.bonus') }}</span>
                                <span class="wallet-balance-value">R$&nbsp;{{ number_format(auth()->user()->wallet->balance_bonus ?? 0, 2, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="wallet-balance-item">
                            <div class="wallet-balance-icon">
                                🎰
                            </div>
                            <div class="wallet-balance-details">
                                <span class="wallet-balance-label _3qn2o">{{ __('menu.free_spins') }}</span>
                                <span class="wallet-balance-value">{{ auth()->user()->wallet->free_spins ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="_6NoZq">
                        <div class="RwFIc">
                            <strong class="SwGZW" style="color:var(--primary-color);">Rollover Saque</strong>

                            <div class="COtxW GiLeC">
                                <div class="COtxW GiLeC">
                                    <div class="kEzf-">
                                        <div class="BeWVO">
                                            <span class="Z07F1">Valor a ser apostado</span>
                                            <span class="Z07F1 vC-zK text-texts"><strong>R$ {{$user->wallet->anti_bot}}</strong></span>
                                            <span class="Z07F1">Meta do rollover</span>
                                        </div>

                                        @php
                                            $valorAtual = $user->wallet->anti_bot;
                                            $valorTotal = $user->wallet->anti_bot_total;

                                            if ($valorTotal == 0) {
                                                $percentual = 0;
                                            } elseif ($valorAtual == 0) {
                                                $percentual = 100;
                                            } else {
                                                $percentual = 100 - ($valorAtual / $valorTotal) * 100;
                                            }
                                        @endphp

                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{$percentual}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="_6NoZq">
                        <div class="RwFIc">
                            <strong class="SwGZW" style="color:var(--primary-color);">Rollover Bônus</strong>

                            <div class="COtxW GiLeC">
                                <div class="COtxW GiLeC">
                                    <div class="kEzf-">
                                        <div class="BeWVO">
                                            <span class="Z07F1">Valor apostado</span>
                                            <span class="Z07F1 vC-zK text-texts"><strong>R$ {{$user->wallet->balance_bonus_rollover_used}} / R$ {{$user->wallet->balance_bonus_rollover}}</strong></span>
                                            <span class="Z07F1">Meta do rollover</span>
                                        </div>

                                        @php
                                            $valorAtual = $user->wallet->balance_bonus_rollover_used;
                                            $valorMaximo = $user->wallet->balance_bonus_rollover;

                                            $percentual = 0;
                                            if ($valorMaximo > 0) {
                                            $percentual = ($valorAtual / $valorMaximo) * 100;
                                            $percentual = max(0, min($percentual, 100));
                                            }

                                            $podereceber = 0;
                                            if ($valorAtual >= $valorMaximo) {
                                            $podereceber = 1;
                                            }

                                            $date = $user->wallet->balance_bonus_expire;

                                            if ($date == "") {
                                            $tempoRestante = "Expiração não informada.";
                                            $podereceber = 0;
                                            }else{
                                            $expira = Carbon::parse($user->wallet->balance_bonus_expire);
                                            $hoje = Carbon::now();

                                            if ($hoje->lessThan($expira)) {
                                            $diff = $hoje->diff($expira);

                                            $dias = floor($hoje->diffInDays($expira));
                                            $horas = $diff->h;
                                            $minutos = $diff->i;

                                            $tempoRestante = sprintf("Seu bônus expira em %d dias, %d horas, %d minutos", $dias, $horas, $minutos);
                                            }else{
                                            $podereceber = 0;
                                            $tempoRestante = "Seu bônus expirou em " . $expira;
                                            }
                                            }
                                        @endphp

                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{$percentual}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <div class="due-time">
                                            <p style="color: white; font-weight: 200; margin-top: 6px; display: flex; align-items: center;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock" style="margin-right: 5px;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                <span>{{$tempoRestante}}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mW59J" style="margin-top:15px; display: flex; justify-content: flex-end; gap: 10px;">
                            <a href="{{ route('cassino.todos-jogos') }}" class="btn-game" style="flex: 0 1 auto;">Acessar Jogos</a>
                            <button type="button" id="saqButton" onclick="AbreSaqueBonus();" class="btn-saqb" style="flex: 0 1 auto;" {!! ($podereceber == 0) ? "disabled" : "" !!}> Transferir Bônus</button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <a href="{{ route('user.complete-statement') }}" class="link-extrato">
                        <div class="alert alert-info text-xs">{{ __('menu.transaction_history_message') }} <strong>"{{ __('menu.balance_management_path') }}"</strong></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('payment.saque-modal')
    @include('payment.saqueb-modal')
    <style>
        .btn-saqb{
            --tw-bg-opacity: 1;
            background-color: var(--primary-color);
            font-weight: 700;
            --tw-text-opacity: 1;
            color: var(--text-btn-primary);
            align-items: center;
            border-radius: 0.375rem;
            flex: 1 1 0%;
            font-size: 0.875rem;
            justify-content: center;
            line-height: 1.25rem;
            min-width: 7rem;
            padding: 0.5rem 1rem;
        }

        .Z07F1.vC-zK.text-texts{
            color: var(--primary-color)!important;
        }

        /* Estilos adicionais para a rotação da seta */
        #gestao-seta-cima, #apostas-seta-cima {
            transition: transform 0.3s ease;
        }
        .btn-game{
            --tw-bg-opacity: 1;
            border: 1px solid #fff;
            font-weight: 600;
            --tw-text-opacity: 1;
            color: #fff;
            align-items: center;
            border-radius: 0.375rem;
            flex: 1 1 0%;
            font-size: 0.875rem;
            justify-content: center;
            line-height: 1.25rem;
            min-width: 7rem;
            padding: 0.5rem 1rem;
            background: transparent;
        }

        /* Estilo para sobrescrever a cor verde para vermelho */
        .H32ns.Vj0b-[style*="background-color: rgb(0, 201, 0)"] {
            background-color: rgb(201, 0, 0) !important;
        }

        /* Estilos para entradas (verde) e saídas (vermelho) */
        .entrada {
            color: rgb(0, 201, 0);
        }

        .saida {
            color: rgb(201, 0, 0);
        }

        /* Adicionar estilo para o indicador de loading */
        .loading {
            position: relative;
        }

        .loading::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 1;
        }

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

        @keyframes spinner-border {
            to { transform: rotate(360deg); }
        }

        /* Estilos para os saldos adicionais */
        .wallet-additional-balances {
            display: flex;
            gap: 1rem;
            margin: 0.5rem 0;
            padding: 0.5rem;
            border-radius: 0.375rem;
        }

        .wallet-balance-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            padding: 0.7rem;
            border-radius: 0.375rem;
            background-color: rgba(255, 255, 255, 0.09);
        }

        .wallet-balance-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: var(--text-btn-primary);
            font-size: 1.2rem;
            line-height: 1;
        }

        .wallet-balance-details {
            display: flex;
            flex-direction: column;
        }

        .wallet-balance-label {
            font-size: 0.75rem;
            color: #d1d1d1;
        }

        .wallet-balance-value {
            font-size: 1rem;
            font-weight: 700;
            color: #ffffff;
        }
    </style>
    <style>
        .RwFIc {
            border-radius: .375rem;
            position: relative;
        }
        .RwFIc .COtxW .kEzf- {
            display: flex;
            flex-direction: column;
            padding-bottom: .375rem;
            padding-top: .375rem;
            white-space: nowrap;
        }
        .RwFIc .mJ-u6 {
            border-radius: .25rem;
            display: inline-block;
            --tw-bg-opacity: 1;
            background-color: var(--color-primary-500);
            font-size: 1em;
            padding: 8px;
            --tw-text-opacity: 1;
            color: #000;
        }
        .RwFIc .COtxW .kEzf- .BeWVO .Z07F1 {
            font-size: .675rem;
            text-align: center;
            color: #d1d1d1;
            margin-top: 10px;
        }
        .RwFIc .COtxW .kEzf- .J-NM7 {
            grid-column: span 3 / span 3;
        }
        .J-NM7{
            background: #2e2f31;
            height: 10px !important;
        }
        .RwFIc .COtxW .kEzf- .BeWVO {
            align-items: center;
            display: flex;
            gap: .5rem;
            justify-content: space-between;
        }
        .bg-success {
            background-color: #e6001c; !important;
        }

        .progress {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            height: 0.6rem;
            overflow: hidden;
            font-size: .75rem;
            background-color: #ffffff0f;
            border-radius: .25rem;
        }

        .progress-bar {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            color: #fff;
            text-align: center;
            background-color: #007bff;
            transition: width .6s ease;
        }

        .due-time {
            align-self: center;
        }

        .due-time p {
            font-weight: 500;
            font-size: 11px;
            padding: 4px 6px 4px 6px;
            background: #272b2c;
            border-radius: 30px;
            color: #bfc9d4;
        }
    </style>
    <script>
        function AbreSaqueBonus() {
            // Mostra o modal
            var saquebModal = document.getElementById('saquebModal');
            if (saquebModal) {
                saquebModal.classList.remove('hidden');
            }

            // Adiciona evento para fechar o modal
            var closeButton = document.getElementById('close-saqueb-modal');
            if (closeButton) {
                closeButton.onclick = function() {
                    saquebModal.classList.add('hidden');
                };
            }

            // Configura o botão de saque de bônus
            var saqueBonusButton = document.getElementById('saqueBonusButton');
            if (saqueBonusButton) {
                saqueBonusButton.onclick = function(e) {
                    e.preventDefault();

                    const loadingElement = document.getElementById('saqueBonusButton');
                    loadingElement.innerHTML = "<i class=\"fa fa-spinner fa-spin\"></i>";
                    loadingElement.disabled = true;

                    const data = {
                        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    };

                    $.ajax({
                        url: "/fin-s4q-bns-j5t3",
                        type: "POST",
                        data: data,
                        dataType: "json",
                        success: function (response) {
                            if (response.status == false) {
                                mostrarMensagemErro(response.message);

                                loadingElement.innerHTML = "Transferir Bônus";
                                loadingElement.disabled = false;
                            } else {
                                saquebModal.classList.add('hidden');

                                var confirmacaoSaqueBModal = document.getElementById('confirmacaoSaqueBModal');
                                if (confirmacaoSaqueBModal) {
                                    confirmacaoSaqueBModal.classList.remove('hidden');
                                }

                                loadingElement.innerHTML = "Transferir Bônus";
                                loadingElement.disabled = false;
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 419) {
                                mostrarMensagemErro('Sua sessão expirou. Por favor, faça login novamente.');
                            } else {
                                mostrarMensagemErro(xhr.message);
                            }
                        }
                    });
                };
            }

            // Configura o botão para fechar o modal de confirmação
            var fecharConfirmacaoSaqueB = document.getElementById('fecharConfirmacaoSaqueB');
            if (fecharConfirmacaoSaqueB) {
                fecharConfirmacaoSaqueB.onclick = function() {
                    var confirmacaoSaqueBModal = document.getElementById('confirmacaoSaqueBModal');
                    if (confirmacaoSaqueBModal) {
                        confirmacaoSaqueBModal.classList.add('hidden');
                        window.location.reload();
                    }
                };
            }

            // Fecha o modal de confirmação quando clica no X
            var closeConfirmacaoSaqueb = document.getElementById('close-confirmacao-saqueb');
            if (closeConfirmacaoSaqueb) {
                closeConfirmacaoSaqueb.onclick = function() {
                    var confirmacaoSaqueBModal = document.getElementById('confirmacaoSaqueBModal');
                    if (confirmacaoSaqueBModal) {
                        confirmacaoSaqueBModal.classList.add('hidden');
                        window.location.reload();
                    }
                };
            }
        }
    </script>
@endsection
