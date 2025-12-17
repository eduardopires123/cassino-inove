@extends('layouts.app')

@section('title', __('menu.vip_levels_title') . ' - ' . config('app.name'))

@section('content')
<div class="viXZB">
    <div class="OjPKd">
        <!-- Alertas para mensagens de sessão -->
        @if(session('success'))
        <div class="alert-vip alert-success">
            {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div class="alert-vip alert-error">
            {{ session('error') }}
        </div>
        @endif
        
        @php
            // Garante que todas as chaves necessárias estejam presentes no array $ranking
            if (!isset($ranking['has_reward'])) {
                $ranking['has_reward'] = false;
            }
            if (!isset($ranking['reward_id'])) {
                $ranking['reward_id'] = null;
            }
            if (!isset($ranking['level'])) {
                $ranking['level'] = 1;
            }
            if (!isset($ranking['name'])) {
                $ranking['name'] = 'Bronze';
            }
            if (!isset($ranking['image'])) {
                $ranking['image'] = 'img/ranking/1.png';
            }
            if (!isset($ranking['current_deposit'])) {
                $ranking['current_deposit'] = 0;
            }
            if (!isset($ranking['next_level'])) {
                $ranking['next_level'] = 2;
            }
            if (!isset($ranking['next_level_deposit'])) {
                $ranking['next_level_deposit'] = 200;
            }
        @endphp
        
        <div class="ebWCc">
            <div class="S-uDl">
            @php 
                $userImage = Auth::check() && Auth::user()->image ? Auth::user()->image :'img/avatar/12.png'; 
            @endphp
                <img class="xHW6R" draggable="false" src="{{ asset($userImage) }}" role="none">
                <div class="dNpyY">
                    @if($ranking)
                        <img alt="{{ $ranking['name'] }}" src="{{ asset($ranking['image']) }}" /> {{ $ranking['name'] }}
                    @else
                        <img alt="Bronze" src="https://d146b4m7rkvjkw.cloudfront.net/4a57debaa3b33489aedb89-BRONZE7.png" /> Bronze
                    @endif
                </div>
                <div class="dVkye">
                    <span class="g4swM"><span>{{ __('menu.progress_to_next_level') }}</span></span>
                    <div class="_8hw-Q">
                        <div class="_61sD-">
                            @if($ranking && isset($ranking['current_deposit']) && isset($ranking['next_level_deposit']) && $ranking['next_level_deposit'] > 0)
                                @php
                                    // Obter o valor mínimo do nível atual
                                    $currentLevelMinDeposit = $ranking['level'] > 1 ? $levelsArray[$ranking['level']-2]['min_deposit'] : 0;
                                    
                                    // Calcular quanto falta para o próximo nível
                                    $depositRange = $ranking['next_level_deposit'] - $currentLevelMinDeposit;
                                    $currentProgress = $ranking['current_deposit'] - $currentLevelMinDeposit;
                                    
                                    // Calcular a porcentagem de progresso
                                    $progressPercentage = $depositRange > 0 ? min(100, ($currentProgress / $depositRange) * 100) : 100;
                                @endphp
                                <div class="dUEky" style="width: {{ $progressPercentage }}%;"></div>
                            @elseif($ranking && !isset($ranking['next_level_deposit']))
                                <!-- Usuário já está no nível máximo -->
                                <div class="dUEky" style="width: 100%;"></div>
                            @else
                                <div class="dUEky" style="width: 0%;"></div>
                            @endif
                        </div>
                    </div>
                    <div class="oqBI4">
                        <div class="GduTY">
                            @if($ranking && isset($ranking['current_deposit']))
                                @if(isset($ranking['next_level_deposit']))
                                    {{ number_format($ranking['current_deposit'], 2, ',', '.') }} / {{ number_format($ranking['next_level_deposit'], 2, ',', '.') }}
                                @else
                                    {{ number_format($ranking['current_deposit'], 2, ',', '.') }} ({{ __('menu.max_level') }})
                                @endif
                            @endif
                        </div>
                        
                        <div class="dNpyY">
                            @if($ranking && isset($ranking['next_level']) && isset($levelsArray[$ranking['next_level']-1]))
                                <img alt="{{ $levelsArray[$ranking['next_level']-1]['name'] }} - Nível {{ $levelsArray[$ranking['next_level']-1]['level'] }}" 
                                     src="{{ asset($levelsArray[$ranking['next_level']-1]['image']) }}" /> 
                                {{ $levelsArray[$ranking['next_level']-1]['name'] }} - Nível {{ $levelsArray[$ranking['next_level']-1]['level'] }}
                            @elseif($ranking && !isset($ranking['next_level']))
                                <!-- Mostrar mensagem informando que o usuário já está no nível máximo -->
                                <span>{{ __('menu.max_level_reached') }}</span>
                            @else
                                <img alt="Bronze - Nível 2" src="{{ asset('img/ranking/1.png') }}" /> Bronze - Nível 2
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="-pfSe">
                <div class="al2o8">
                    <h1><span>{{ __('menu.welcome_to_levels') }}</span></h1>
                </div>
                <div class="MuqYH">
                    <p>
                        <span>
                            {!! __('menu.vip_club_description_1') !!}
                        </span>
                    </p>
                    <p>
                        <span>
                            {!! __('menu.vip_club_description_2') !!}
                        </span>
                    </p>
                    <p>
                        <span>{!! __('menu.vip_club_description_3') !!}</span>
                    </p>
                </div>
                <div class="t8xno">
                    <h4 class="yuVho"><span style="color:var(--text-btn-primary);">{{ __('menu.level_up_message') }}</span></h4>
                    
                    <!-- Botão de Resgate de Recompensas movido para cá -->
                    @php
                        // Verificar se usuário tem recompensa disponível
                        $canClaimReward = false;
                        $hasDeposit = false;
                        
                        if (Auth::check()) {
                            // Verificar se o usuário já fez algum depósito (type=0, status=1)
                            try {
                                $hasDeposit = \App\Models\Transactions::where('user_id', Auth::id())
                                    ->where('type', 0)
                                    ->where('status', 1)
                                    ->exists();
                            } catch (\Exception $e) {
                                \Illuminate\Support\Facades\Log::error("Erro ao verificar depósitos: " . $e->getMessage());
                                $hasDeposit = false;
                            }
                            
                            // Verificar se usuário tem recompensa disponível com base no nível atual real
                            $lastVipLevel = Auth::user()->last_vip_level ?? 0;
                            $currentLevel = $ranking['level'] ?? 0;
                            
                            // Identificar o ID do nível atual do usuário conforme determinado pelos depósitos
                            $currentLevelId = null;
                            foreach ($levelsArray as $levelData) {
                                if ($levelData['level'] == $currentLevel) {
                                    $currentLevelId = $levelData['id'] ?? null;
                                    break;
                                }
                            }
                            
                            // Verificar se já existe um registro de recompensa para o nível ATUAL do usuário
                            $existingReward = false;
                            if ($currentLevelId) {
                                try {
                                    $existingReward = \App\Models\VipReward::where('user_id', Auth::id())
                                        ->where('vip_level_id', $currentLevelId)
                                        ->where('is_claimed', true)
                                        ->exists();
                                } catch (\Exception $e) {
                                    \Illuminate\Support\Facades\Log::error("Erro ao verificar recompensas: " . $e->getMessage());
                                    $existingReward = false;
                                }
                            }
                            
                            // Permite resgatar se:
                            // 1. O nível atual for maior que o último nível recompensado 
                            //    OU se for o primeiro depósito (last_vip_level = 0) E
                            // 2. Não existir um registro de recompensa já resgatada para este nível atual E
                            // 3. O usuário já fez pelo menos um depósito
                            $hasCurrentDeposit = isset($ranking['current_deposit']) && $ranking['current_deposit'] > 0;
                            $canClaimReward = (($currentLevel > $lastVipLevel) || 
                                              ($lastVipLevel === 0 && $hasCurrentDeposit)) && 
                                              !$existingReward &&
                                              $hasDeposit;
                        }
                    @endphp
                    
                    @if(Auth::check() && $canClaimReward)
                    <div class="reward-claim-container mt-3">
                        <form action="{{ route('vip.claim-reward') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary reward-btn">
                                <span class="reward-icon">🎁</span> {{ __('menu.claim_vip_reward') }}
                            </button>
                        </form>
                        <div class="reward-notification">
                            <span class="pulse-dot"></span> {{ __('menu.reward_available') }}
                        </div>
                    </div>
                    @else
                    <div class="reward-claim-container mt-3">
                        <button type="button" class="btn btn-primary reward-btn disabled" disabled>
                            <span class="reward-icon">🎁</span> {{ __('menu.claim_vip_reward') }}
                        </button>
                        <div class="reward-notification unavailable">
                            @if(Auth::check())
                                @php
                                    $lastVipLevel = Auth::user()->last_vip_level ?? 0;
                                    $currentLevel = $ranking['level'] ?? 0;
                                @endphp
                                
                                @if(!$hasDeposit)
                                    {{ __('menu.need_deposit_for_reward') }}
                                @elseif($currentLevel <= $lastVipLevel && $lastVipLevel !== 0)
                                    {{ __('menu.need_level_up_for_reward') }}
                                @elseif($lastVipLevel === 0 && (!isset($ranking['current_deposit']) || $ranking['current_deposit'] <= 0))
                                    {{ __('menu.first_deposit_reward') }}
                                @else
                                    {{ __('menu.reward_unavailable') }}
                                @endif
                            @else
                                {{ __('menu.login_to_claim_rewards') }}
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div data-v-18616e66="" class="slideTitle">
            <h4 data-v-18616e66="" class="gamificationPageTitle">
                <span data-v-18616e66="" class="nuxt-icon nuxt-icon--fill nuxt-icon--stroke icon">
                    <svg fill="#ffdf1b" height="1em" stroke="#ffdf1b" viewBox="0 0 140.599 140.599" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <g fill="#ffdf1b" stroke-width="0"></g>
                        <g fill="#ffdf1b" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g fill="#ffdf1b">
                            <g>
                                <path
                                    d="M132.861,56.559c-4.27,0-7.742,3.473-7.742,7.741c0,1.893,0.685,3.626,1.815,4.973l-15.464,15.463 c-2.754,2.754-4.557,1.857-4.027-2l0.062-0.445c0.528-3.857-1.39-4.876-4.286-2.273l-0.531,0.479 c-2.898,2.603-5.828,1.609-6.544-2.219l-5.604-29.964c-0.717-3.828-2.129-3.886-3.156-0.129l-7.023,25.689 c-1.025,3.757-2.295,3.677-2.834-0.181L71.93,33.674c3.488-0.751,6.111-3.856,6.111-7.566c0-4.268-3.473-7.741-7.741-7.741 c-4.269,0-7.742,3.473-7.742,7.741c0,3.709,2.625,6.815,6.112,7.566l-5.592,40.019c-0.539,3.857-1.809,3.938-2.835,0.181 l-7.023-25.69c-1.027-3.757-2.44-3.699-3.156,0.129l-5.605,29.964c-0.716,3.828-3.645,4.82-6.543,2.219l-0.533-0.479 c-2.897-2.604-4.816-1.586-4.287,2.272l0.061,0.445c0.529,3.858-1.274,4.753-4.028,2L13.667,69.273 c1.132-1.347,1.816-3.08,1.816-4.973c0-4.269-3.473-7.741-7.741-7.741C3.473,56.559,0,60.032,0,64.3 c0,4.269,3.473,7.742,7.742,7.742c0.478,0,0.942-0.05,1.396-0.132l10.037,33.949c1.104,3.734,3.534,9.637,7.161,11.055 c8.059,3.153,24.72,5.318,43.964,5.318c19.245,0,35.905-2.165,43.965-5.318c3.626-1.418,6.058-7.32,7.161-11.055l10.037-33.949 c0.453,0.083,0.918,0.132,1.396,0.132c4.268,0,7.739-3.473,7.739-7.742C140.6,60.032,137.127,56.559,132.861,56.559z M11.103,66.708c-0.685,0.954-1.761,1.605-2.994,1.714c-0.121,0.011-0.243,0.019-0.367,0.019c-2.284,0-4.142-1.857-4.142-4.142 c0-2.284,1.858-4.141,4.142-4.141c2.283,0,4.141,1.857,4.141,4.141C11.883,65.2,11.592,66.031,11.103,66.708z M66.159,26.109 c0-2.283,1.858-4.141,4.142-4.141c2.283,0,4.143,1.857,4.143,4.141c0,1.892-1.276,3.488-3.014,3.981 c-0.359,0.102-0.737,0.16-1.129,0.16s-0.769-0.058-1.128-0.16C67.436,29.596,66.159,28,66.159,26.109z M70.301,115.405 l-15.36-15.361l15.36-15.36l15.359,15.359L70.301,115.405z M132.861,68.442c-0.125,0-0.248-0.008-0.369-0.019 c-1.231-0.109-2.309-0.76-2.993-1.714c-0.488-0.68-0.779-1.51-0.779-2.409c0-2.284,1.856-4.141,4.142-4.141 c2.282,0,4.142,1.857,4.142,4.141C137.001,66.583,135.143,68.442,132.861,68.442z M60.036,100.046l10.27-10.27l10.269,10.27 l-10.269,10.27L60.036,100.046z"
                                    fill="#ffdf1b"
                                ></path>
                            </g>
                        </g>
                    </svg>
                </span>
                <span data-v-18616e66="">{{ __('menu.level_benefits') }}</span>
            </h4>
            <div data-v-18616e66="" class="relative group flex items-center">
                <span data-v-18616e66="" class="nuxt-icon nuxt-icon--fill hover:text-primary cursor-pointer text-texts/60" onclick="javascript:levelsSlider.slidePrev()">
                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" fill="currentColor"></path>
                    </svg>
                </span>
                <span data-v-18616e66="" class="nuxt-icon nuxt-icon--fill hover:text-primary cursor-pointer text-texts/60 ml-3" onclick="javascript:levelsSlider.slideNext()">
                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z" fill="currentColor"></path>
                    </svg>
                </span>
            </div>
        </div>
        <div class="nM44t mb-4 md:mb-8">
            <div class="row_imgs">
                <div class="swiper-container" id="levelsSlider">
                    <div class="swiper-wrapper">
                        @foreach($levelsArray as $level)
                        <div class="swiper-slide level-card">
                            <div class="peBY3 _9CFK-" style="order: 3; cursor: pointer;" onclick="openLevelModal('{{ $level['name'] }}', {{ $level['level'] }}, '{{ asset($level['image']) }}', {{ $level['min_deposit'] }}, '{{ $level['benefits'] ?? 'Avance para este nível e receba benefícios exclusivos.' }}')">
                                <div data-v-18616e66="" class="defaultItemBox">
                                    @if($ranking && $ranking['level'] == $level['level'])
                                    <div data-v-18616e66="" class="tagBox current"><span data-v-18616e66="">Atual</span></div>
                                    @elseif($ranking && isset($ranking['next_level']) && $ranking['next_level'] == $level['level'])
                                    <div data-v-18616e66="" class="tagBox new"><span data-v-18616e66="">Próximo</span></div>
                                    @endif
                                    <div data-v-18616e66="" class="imgWrap"><img data-v-18616e66="" alt="{{ $level['name'] }} - Nível {{ $level['level'] }}" src="{{ asset($level['image']) }}"></div>
                                    <div data-v-18616e66="" class="defaultBoxContent">
                                        <div data-v-18616e66="" class="titleBox titleLevel">{{ $level['name'] }} - Nível {{ $level['level'] }}</div>
                                        <div data-v-18616e66="" class="priceBox inlined noCoins">
                                            {{ $level['min_deposit'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal do Nível -->
<div class="_8XokL hidden" data-v-owner="273" style="--8446db72: 600px;" id="levelModal">
    <div class="X-T4C" style="width: 60%!important;">
        <button class="pOB1m" id="close-level-modal">
            <span class="nuxt-icon nuxt-icon--fill">
                <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z" fill="currentColor"></path>
                </svg>
            </span>
        </button>
        <div class="jOkUz">
            <div class="gamificationModal">
                <img class="imageModal" id="modalImage" src="">
                <div class="modalDescription">
                    <div class="timerModal"></div>
                    <div class="titleModal" id="modalTitle"></div>
                    <div class="descriptionModal" id="modalDescription"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<style>
   

    /* Estilos para o carrossel */
    .nM44t {
        width: 100%;
        overflow: hidden;
        position: relative;
    }
    .row_imgs {
        width: 100%;
        margin: 0 auto;
    }
    .swiper-container {
        width: 100%;
        overflow: hidden;
        padding: 0;
    }
    .swiper-wrapper {
        display: flex;
        align-items: center;
        width: 100%;
    }
    .swiper-slide {
        width: auto;
        display: flex;
        justify-content: center;
    }
    /* Ajustes para manter alinhamento com conteúdo superior */
    .slideTitle {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 15px;
        margin-bottom: 15px;
    }
    /* Garantir que o carrossel não ultrapasse a largura do contêiner pai */
    .OjPKd {
        overflow: hidden;
        width: 100%;
    }
    
    /* Estilos para o botão de resgate de recompensa */
    .reward-claim-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 15px !important;
        position: relative;
    }
    
    /* Estilo específico para o botão quando está na seção t8xno */
    .t8xno .reward-claim-container {
        margin-top: 25px !important;
        margin-bottom: 15px;
    }
    
    .t8xno .reward-btn {
        min-width: 380px;
        font-size: 1.2em;
        padding: 15px 30px;
    }
    
    .t8xno .reward-notification {
        font-size: 1em;
        margin-top: 8px;
    }
    
    .reward-btn {
        background-color: #FFB30F;
        color: #121212;
        border: none;
        padding: 12px 24px;
        border-radius: 5px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
        font-size: 1.1em;
        box-shadow: 0 4px 8px rgba(255, 179, 15, 0.3);
        width: 100%;
        max-width: 250px;
        margin-bottom: 10px;
    }
    
    /* Estilos para botão desabilitado */
    .reward-btn.disabled {
        background-color: #b3b3b3;
        color: #5a5a5a;
        cursor: not-allowed;
        opacity: 0.7;
        box-shadow: none;
        animation: none;
        position: relative;
    }
    
    .reward-btn.disabled::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 5px;
        pointer-events: none;
    }
    
    .reward-btn.disabled .reward-icon {
        opacity: 0.5;
    }
    
    /* Estilo para mensagem de indisponibilidade */
    .reward-notification.unavailable {
        color: #888;
        font-style: italic;
    }
    
    .reward-btn:hover {
       opacity: 0.8;
    }
    
    .reward-btn.disabled:hover {
        transform: none;
        background-color: #b3b3b3;
        box-shadow: none;
    }
    
    .reward-icon {
        font-size: 1.3em;
    }
    
    .reward-notification {
        color: #FFB30F;
        font-weight: bold;
        font-size: 0.9em;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 5px;
    }
    
    .pulse-dot {
        width: 10px;
        height: 10px;
        background-color: #ff3b30;
        border-radius: 50%;
        display: inline-block;
        animation: pulse-red 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 179, 15, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(255, 179, 15, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 179, 15, 0);
        }
    }
    
    @keyframes pulse-red {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(255, 59, 48, 0.7);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 10px rgba(255, 59, 48, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(255, 59, 48, 0);
        }
    }
    
    /* Adicionar estilo de alerta para feedback de mensagens */
    .alert-vip {
        margin: 15px auto;
        padding: 15px;
        border-radius: 5px;
        text-align: center;
        max-width: 80%;
    }
    
    .alert-success {
        background-color: rgba(76, 175, 80, 0.2);
        color: #4CAF50;
        border: 1px solid #4CAF50;
    }
    
    .alert-error {
        background-color: rgba(244, 67, 54, 0.2);
        color: #F44336;
        border: 1px solid #F44336;
    }
</style>

@push('scripts')
<script>
// Função para fechar o modal
function closeModal() {
    document.getElementById('levelModal').classList.add('hidden');
}

// Função para abrir o modal do nível
function openLevelModal(name, level, image, minDeposit, description) {
    // Capturar o elemento do modal
    const levelModal = document.getElementById('levelModal');
    
    // Atualizar conteúdo do modal
    document.getElementById('modalTitle').textContent = name + " - Nível " + level;
    document.getElementById('modalImage').src = image;
    document.getElementById('modalDescription').textContent = description;
    
    // Mostrar o modal
    levelModal.classList.remove('hidden');
    
}

document.addEventListener('DOMContentLoaded', function() {
    // Verificar se o modal está presente
    const modal = document.getElementById('levelModal');
    if (modal) {
        
        // Adicionar evento de clique ao botão de fechar
        document.getElementById('close-level-modal').addEventListener('click', function() {
            closeModal();
        });
    } else {
        console.error('Modal não encontrado no DOM!');
    }
    
    // Verificar se o Swiper está disponível
    function checkSwiperAvailability(callback) {
        if (typeof Swiper !== 'undefined') {
            callback();
            return;
        }
        
        // Se o Swiper não estiver carregado, carregá-lo
        var swiperScript = document.createElement('script');
        swiperScript.src = 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js';
        swiperScript.onload = callback;
        document.head.appendChild(swiperScript);
        
        // Também carregar CSS do Swiper
        var swiperCSS = document.createElement('link');
        swiperCSS.rel = 'stylesheet';
        swiperCSS.href = 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css';
        document.head.appendChild(swiperCSS);
    }
    
    // Inicializar o slider de níveis
    function initLevelsSlider() {
        // Verificar se o elemento do slider existe
        if (!document.getElementById('levelsSlider')) {
            console.error('Elemento #levelsSlider não encontrado');
            return;
        }
        
        // Remover qualquer instância anterior do slider
        if (window.levelsSlider && typeof window.levelsSlider.destroy === 'function') {
            window.levelsSlider.destroy(true, true);
        }
        
        // Configuração do slider
        window.levelsSlider = new Swiper('#levelsSlider', {
            slidesPerView: 2, // Default for mobile
            spaceBetween: 16,
            centeredSlides: false,
            loop: false,
            grabCursor: true,
            watchOverflow: true,
            observer: true,
            observeParents: true,
            watchSlidesProgress: true,
            preventClicksPropagation: true,
            breakpoints: {
                // Quando a viewport é >= 768px (tablet)
                768: {
                    slidesPerView: 3,
                },
                // Quando a viewport é >= 1024px (desktop)
                1024: {
                    slidesPerView: 4,
                }
            },
            navigation: {
                nextEl: '[onclick="javascript:levelsSlider.slideNext()"]',
                prevEl: '[onclick="javascript:levelsSlider.slidePrev()"]'
            },
            on: {
                init: function() {
                    
                    // Marcar como inicializado
                    document.getElementById('levelsSlider').classList.add('swiper-initialized');
                    
                    // Atualizar após um breve atraso
                    setTimeout(() => {
                        this.update();
                    }, 100);
                },
                resize: function() {
                    // Atualizar em caso de redimensionamento
                    this.update();
                }
            }
        });
        
        // Registrar para reinicialização global
        if (typeof window.forceReinitSlider === 'function') {
            window.forceReinitSlider('levelsSlider');
        }
    }
    
    // Inicializar ou carregar Swiper conforme necessário
    checkSwiperAvailability(function() {
        // Pequeno atraso para garantir que o DOM esteja totalmente carregado
        setTimeout(initLevelsSlider, 100);
    });
    
    // Adicionar script do sliderNavigation.js se ainda não estiver carregado
    if (typeof window.forceReinitSlider === 'undefined') {
        var sliderNavScript = document.createElement('script');
        sliderNavScript.src = '/js/sliderNavigation.js';
        sliderNavScript.onload = function() {
            // Reinicializar o slider quando o script for carregado
            if (window.levelsSlider) {
                window.levelsSlider.update();
            }
        };
        document.body.appendChild(sliderNavScript);
    }
});
</script>
@endpush