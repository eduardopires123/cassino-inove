@extends('layouts.app')

@section('title', 'Jogar - ' . $raspadinha->name)

@section('content')
<div data-v-bba270d3="" data-v-9dae45d3="" class="gamesBar flex flex-col items-center w-full">
    @include('payment.deposit-modal')
    <div data-v-bba270d3="" class="menu flex flex-row items-center justify-between w-full h-10 px-2 border-bg-primary/60 border-b border-solid">
        <div data-v-bba270d3="" class="burger justify-start w-1/3" onclick="goBack();">
            <span data-v-bba270d3="" class="nuxt-icon nuxt-icon--fill menuBtn home">
                <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" fill="currentColor"></path>
                </svg>
            </span>
        </div>
        <div data-v-bba270d3="" class="l6oz0 v1a-c justify-center logo w-1/3 px-4 py-0.5">
            <a aria-label="{{ config('app.name') }}" class="bwSJI v1a-c" href="{{ route('home') }}">
                @php
                   $settings = \App\Models\Setting::first();
                @endphp
                <img alt="{{ \App\Models\Setting::first()->name ?? config('app.name') }}" class="j2x6J" src="{{ asset($settings->logo) }}" />
            </a>
        </div>
        <div data-v-bba270d3="" class="deposit flex justify-end w-1/3">
            <a data-v-bba270d3="" href="{{ route('user.wallet') }}" class="btn btn-deposit" type="button" id="deposit-btn">{{ __('game-page.deposit') }}</a>
        </div>
    </div>
</div>

<section data-v-1d35be9f="" id="casino">
    <section data-v-9dae45d3="" data-v-1d35be9f="" id="frame_game" class="fullScreenOn mobile-raspadinha"> 
        <!-- Container do jogo da raspadinha mobile -->
        <div id="raspadinha-game-iframe" class="mobile-game-container">
            <div id="raspadinha-game-container" class="w-full h-full bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900">
                <!-- Área principal mobile -->
                <div class="raspadinha-main-area-mobile">
                    <!-- Título da Raspadinha Mobile -->
                    <div class="raspadinha-title-section-mobile">
                        <div class="game-title-mobile">
                            <h1 class="raspadinha-name-mobile">{{ $raspadinha?->name ?? 'Raspadinha' }}</h1>
                            <p class="raspadinha-subtitle-mobile">{{ $raspadinha?->description ?? 'Raspe e Ganhe!' }}</p>
                        </div>
                    </div>
                    
                    <!-- Container da Raspadinha -->
                    <div class="raspadinha-game-section-mobile">
                        <div id="scratch-container" class="raspadinha-card-mobile">
                            <!-- Grid de prêmios (background) -->
                            <div id="prizes-grid" class="prizes-grid-mobile">
                                <!-- Os prêmios serão inseridos aqui dinamicamente -->
                            </div>
                            
                            <!-- Canvas de raspagem -->
                            <canvas id="scratch-canvas" class="scratch-canvas-mobile"></canvas>
                            
                            <!-- Overlay de compra inicial -->
                            <div id="btn-overlay" class="purchase-overlay-mobile">
                                <div class="purchase-content-mobile">
                                    <button id="btn-comprar-raspar-mobile" class="btn-comprar-raspar-mobile">
                                        <i class="fas fa-gift mr-2"></i>
                                        Comprar e Raspar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Container dos Controles (parte inferior) -->
                    <div class="raspadinha-controls-mobile">
                        <!-- Informações principais -->
                        <div class="raspadinha-info-mobile">
                            <div class="info-item-mobile">
                                <span class="info-label-mobile">Saldo:</span>
                                <span class="info-value-mobile" id="user-balance">R$ {{ number_format(auth()->user()?->wallet?->balance ?? 0, 2, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <!-- Botões de ação -->
                        <div class="action-buttons-mobile">
                            <button class="play-btn-mobile" data-price="{{ $raspadinha?->price ?? 0 }}" data-turbo="false">
                                <div class="btn-content-mobile">
                                    <div class="btn-text-mobile">
                                        <i class="fas fa-gift"></i>
                                        <span>Jogar</span>
                                    </div>
                                    <div class="price-tag-mobile">R$ {{ number_format($raspadinha?->price ?? 0, 2, ',', '.') }}</div>
                                </div>
                            </button>
                            
                            <button class="auto-btn-mobile" data-price="{{ $raspadinha?->turbo_price ?? 0 }}" data-turbo="true">
                                <div class="btn-content-mobile">
                                    <div class="btn-text-mobile">
                                        <i class="fas fa-bolt"></i>
                                        <span>Turbo</span>
                                    </div>
                                    <div class="price-tag-mobile">R$ {{ number_format($raspadinha?->turbo_price ?? 0, 2, ',', '.') }}</div>
                                </div>
                            </button>
                            
                            <button class="reveal-btn-mobile" style="display: none;">
                                <div class="btn-content-mobile">
                                    <div class="btn-text-mobile">
                                        <i class="fas fa-eye"></i>
                                        <span>Revelar Tudo</span>
                                    </div>
                                </div>
                            </button>
                            
                            <button class="auto-game-btn-mobile">
                                <div class="btn-content-mobile">
                                    <div class="btn-text-mobile">
                                        <i class="fas fa-magic"></i>
                                        <span>Auto</span>
                                    </div>
                                    <div class="price-tag-mobile">
                                        <i class="fas fa-robot"></i>
                                    </div>
                                </div>
                            </button>
                        </div>
                        
                        <!-- Top Ganhos da Raspadinha -->
                        <div class="top-ganhos-mobile">
                            <div class="top-ganhos-header-mobile">
                                <div class="top-ganhos-title-mobile">
                                    <i class="fas fa-trophy"></i>
                                    <span>TOP GANHOS</span>
                                </div>
                            </div>
                            <div class="top-ganhos-content-mobile">
                                <div class="top-ganhos-container-mobile" id="top-ganhos-container">
                                    <div class="top-ganhos-wrapper-mobile" id="top-ganhos-wrapper">
                                        @forelse($recentPrizes as $prize)
                                            <div class="top-ganhos-item-mobile">
                                                <div class="winner-info-mobile">
                                                    <div class="winner-avatar-mobile">
                                                        {{ substr($prize->user->name ?? 'J', 0, 1) }}
                                                    </div>
                                                    <div class="winner-details-mobile">
                                                        <div class="winner-name-mobile">
                                                            {{ substr($prize->user->name ?? 'Jogador', 0, 3) }}****
                                                            <span class="game-name-mobile">{{ $raspadinha->name }}</span>
                                                        </div>
                                                        <div class="winner-amount-mobile">
                                                            <span class="amount-won-mobile">R$ {{ number_format($prize->amount_won * 10, 2, ',', '.') }}</span>
                                                            <span class="time-ago-mobile">{{ $prize->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="no-winners-message-mobile">
                                                <div>Nenhum ganhador recente</div>
                                                <small>Os prêmios aparecerão aqui</small>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

<!-- Modal de Resultado Mobile -->
<div id="result-modal" class="result-modal-mobile" style="display: none;">
    <div class="modal-overlay-mobile" onclick="closeResultModal()"></div>
    <div class="modal-content-mobile">
        <div class="modal-header-mobile">
            <h3 id="modal-title" class="modal-title-mobile">Parabéns!</h3>
            <button onclick="closeResultModal()" class="modal-close-mobile">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body-mobile">
            <div id="modal-prize-image" class="modal-prize-image-mobile">
                <!-- Imagem do prêmio será inserida aqui -->
            </div>
            <div id="modal-prize-info" class="modal-prize-info-mobile">
                <h4 id="modal-prize-title" class="modal-prize-title-mobile">Nome do Prêmio</h4>
                <p id="modal-prize-value" class="modal-prize-value-mobile">R$ 0,00</p>
                <p id="modal-prize-description" class="modal-prize-description-mobile">Descrição do prêmio</p>
            </div>
        </div>
        <div class="modal-footer-mobile">
            <button onclick="closeResultModal()" class="btn-play-again-mobile">
                <i class="fas fa-play"></i>
                Jogar Novamente
            </button>
        </div>
    </div>
</div>

<!-- Modal de Auto Jogo Mobile -->
<div id="auto-game-modal" class="auto-game-modal-mobile" style="display: none;">
    <div class="modal-overlay-mobile" onclick="closeAutoGameModal()"></div>
    <div class="modal-content-mobile">
        <div class="modal-header-mobile">
            <h3 class="modal-title-mobile">Jogo Automático</h3>
            <button onclick="closeAutoGameModal()" class="modal-close-mobile">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body-mobile">
            <div class="auto-game-options-mobile">
                <label class="auto-option-mobile">
                    <input type="radio" name="auto_quantity" value="10" checked>
                    <span>10 jogadas</span>
                    <span class="auto-price-mobile">R$ {{ number_format(($raspadinha?->price ?? 0) * 10, 2, ',', '.') }}</span>
                </label>
                <label class="auto-option-mobile">
                    <input type="radio" name="auto_quantity" value="25">
                    <span>25 jogadas</span>
                    <span class="auto-price-mobile">R$ {{ number_format(($raspadinha?->price ?? 0) * 25, 2, ',', '.') }}</span>
                </label>
                <label class="auto-option-mobile">
                    <input type="radio" name="auto_quantity" value="50">
                    <span>50 jogadas</span>
                    <span class="auto-price-mobile">R$ {{ number_format(($raspadinha?->price ?? 0) * 50, 2, ',', '.') }}</span>
                </label>
                <label class="auto-option-mobile">
                    <input type="radio" name="auto_quantity" value="100">
                    <span>100 jogadas</span>
                    <span class="auto-price-mobile">R$ {{ number_format(($raspadinha?->price ?? 0) * 100, 2, ',', '.') }}</span>
                </label>
                
                <div class="turbo-toggle-mobile">
                    <label class="toggle-switch-mobile">
                        <input type="checkbox" id="auto-turbo-mode">
                        <span class="toggle-slider-mobile"></span>
                        <span class="toggle-label-mobile">Modo Turbo</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="modal-footer-mobile">
            <button onclick="startAutoGame()" class="btn-start-auto-mobile">
                <i class="fas fa-robot"></i>
                Iniciar Auto Jogo
            </button>
        </div>
    </div>
</div>

<style>

/* Bloquear scroll vertical no body */
html, body {
    overflow-y: hidden !important;
    height: 100vh !important;
}

/* Estilos específicos para mobile */
#casino {
    width: 100%;
    height: 100vh;
    overflow: hidden; /* Bloquear scroll aqui */
}

#frame_game {
    width: 100%;
    height: calc(100vh - 50px);
    position: relative;
    overflow: hidden;
}

#divPageHeaderWrapper {
    display: none !important;
}

#divMobileMenu {
    display: none !important;
}

.gamesBar .menu {
    background: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
}

.menuBtn {
    cursor: pointer;
    padding: 0.5rem;
}

.menuBtn:hover {
    opacity: 0.7;
}

.btn-deposit {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
    line-height: 0.6rem;
}

/* Variáveis CSS do sistema */
:root {
    --primary-color: #00FF88;
    --secondary-color: #00FF66;
    --tertiary-color: #00FF88;
    --bg-color: #0D1F0D;
    --support-color: #00FF88;
}

/* Estilos específicos da raspadinha mobile */
.mobile-game-container {
    height: 100%;
    width: 100%;
    position: relative;
    overflow-y: auto; /* Permitir scroll vertical apenas aqui */
    overflow-x: hidden;
    scroll-behavior: smooth; /* Scroll suave */
    -webkit-overflow-scrolling: touch; /* Scroll suave no iOS */
}

.raspadinha-main-area-mobile {
    display: flex;
    flex-direction: column;
    min-height: calc(100vh - 50px);
    padding: 0.8rem;
    gap: 0.8rem;
    overflow: visible;
}

/* Seção de título da raspadinha mobile */
.raspadinha-title-section-mobile {
    text-align: center;
    padding: 0.8rem 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    margin-bottom: 0.5rem;
}

.game-title-mobile {
    color: white;
}

.raspadinha-name-mobile {
    font-size: 1.4rem;
    font-weight: bold;
    color: var(--primary-color);
    margin: 0 0 0.3rem 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    line-height: 1.2;
}

.raspadinha-subtitle-mobile {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
    opacity: 0.9;
    font-weight: 500;
}

.raspadinha-game-section-mobile {
    flex: 0 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}

.raspadinha-card-mobile {
    position: relative;
    width: 100%;
    height: calc(100vw - 1.6rem);
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    aspect-ratio: 1;
    user-select: none;
}

.prizes-grid-mobile {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: none;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(3, 1fr);
    gap: 8px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: white;
    border-radius: 20px;
    z-index: 1;
}

.prizes-grid-mobile > div {
    background: rgba(0, 0, 0, 0.7);
    border-radius: 15px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    font-weight: 600;
    font-size: 0.9rem;
    border: 2px solid #444;
    box-shadow: 
        inset 2px 2px 5px rgba(255, 255, 255, 0.1),
        inset -2px -2px 5px rgba(0, 0, 0, 0.5),
        0 4px 10px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
}

.prizes-grid-mobile img {
    height: 48px;
    margin-top: 35px;
    object-fit: contain;
    margin-bottom: 6px;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.5));
    z-index: 2;
    position: relative;
}

.prize-text {
    font-size: 10px;
    font-weight: 600;
    color: #e0e0e0;
    text-align: center;
    line-height: 1.2;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.8);
    z-index: 2;
    position: relative;
    margin-top: auto;
}

/* Estilo para produtos ganhadores */
.prizes-grid-mobile > div.winner {
    background: linear-gradient(145deg, #3a2f0a, #2a1f00);
    border: 2px solid #ffd700;
    box-shadow: 
        inset 2px 2px 5px rgba(255, 215, 0, 0.2),
        inset -2px -2px 5px rgba(0, 0, 0, 0.7),
        0 0 15px rgba(255, 215, 0, 0.4),
        0 4px 10px rgba(0, 0, 0, 0.3);
    animation: winnerPulse 1.5s ease-in-out infinite alternate;
}

.prizes-grid-mobile > div.winner .prize-text {
    color: #ffd700;
    text-shadow: 0 0 8px rgba(255, 215, 0, 0.6);
}

@keyframes winnerPulse {
    from { 
        transform: scale(1);
        box-shadow: 
            inset 2px 2px 5px rgba(255, 215, 0, 0.2),
            inset -2px -2px 5px rgba(0, 0, 0, 0.7),
            0 0 15px rgba(255, 215, 0, 0.4),
            0 4px 10px rgba(0, 0, 0, 0.3);
    }
    to { 
        transform: scale(1.02);
        box-shadow: 
            inset 2px 2px 5px rgba(255, 215, 0, 0.3),
            inset -2px -2px 5px rgba(0, 0, 0, 0.7),
            0 0 25px rgba(255, 215, 0, 0.6),
            0 6px 15px rgba(0, 0, 0, 0.4);
    }
}

.scratch-canvas-mobile {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10;
    cursor: pointer;
    touch-action: none;
    user-select: none;
    border-radius: 20px;
    background: rgba(0,0,0,0.1);
    opacity: 1;
    visibility: visible;
}

.purchase-overlay-mobile {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 30;
    border-radius: 20px;
}

.purchase-content-mobile {
    text-align: center;
    color: white;
}

.btn-comprar-raspar-mobile {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border: none;
    border-radius: 15px;
    padding: 15px 25px;
    color: black;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0, 255, 136, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 180px;
}

.btn-comprar-raspar-mobile:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0, 255, 136, 0.6);
}


.raspadinha-controls-mobile {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    padding: 1rem 1rem 2rem 1rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    flex: 0 0 auto;
}

.raspadinha-info-mobile {
    margin-bottom: 1rem;
    text-align: center;
}

.info-item-mobile {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.8rem 1.2rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: white;
}

.info-label-mobile {
    font-weight: 600;
    font-size: 1rem;
}

.info-value-mobile {
    font-weight: bold;
    color: var(--primary-color);
    font-size: 1.1rem;
}

/* Top Ganhos Mobile */
.top-ganhos-mobile {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    margin-top: 2rem;
    margin-bottom: 1rem;
    overflow: hidden;
}

.top-ganhos-header-mobile {
    padding: 0.8rem 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.top-ganhos-title-mobile {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary-color);
    font-weight: bold;
    font-size: 0.9rem;
    text-transform: uppercase;
}

.top-ganhos-title-mobile i {
    font-size: 1rem;
    color: #ffd700;
}

.top-ganhos-content-mobile {
    padding: 0.5rem;
}

.top-ganhos-container-mobile {
    max-height: 150px;
    overflow: hidden;
    position: relative;
}

.top-ganhos-wrapper-mobile {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    transition: opacity 0.5s ease-in-out;
}

.top-ganhos-item-mobile {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 0.5rem;
    transition: background 0.3s ease;
    cursor: pointer;
}

.top-ganhos-item-mobile:hover {
    background: rgba(255, 255, 255, 0.1);
}

.winner-info-mobile {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.winner-avatar-mobile {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: black;
    font-weight: bold;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.winner-details-mobile {
    flex: 1;
    min-width: 0;
}

.winner-name-mobile {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.2;
}

.game-name-mobile {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.6);
    font-weight: 400;
}

.winner-amount-mobile {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.2rem;
}

.amount-won-mobile {
    color: var(--primary-color);
    font-weight: bold;
    font-size: 0.8rem;
}

.time-ago-mobile {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.7rem;
    font-weight: 400;
}

.no-winners-message-mobile {
    text-align: center;
    padding: 1rem;
    color: rgba(255, 255, 255, 0.6);
}

.no-winners-message-mobile div {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.3rem;
}

.no-winners-message-mobile small {
    font-size: 0.8rem;
    opacity: 0.7;
}

.action-buttons-mobile {
    display: flex;
    flex-direction: row;
    gap: 8px;
    justify-content: space-between;
    align-items: center;
}

.play-btn-mobile {
    flex: 2; /* Botão Jogar será 2x maior */
    padding: 12px 8px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: black;
    box-shadow: 0 4px 15px rgba(0, 255, 136, 0.3);
    min-height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
}

.auto-btn-mobile,
.reveal-btn-mobile,
.auto-game-btn-mobile {
    flex: 1;
    padding: 12px 8px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: black;
    box-shadow: 0 4px 15px rgba(0, 255, 136, 0.3);
    min-height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
}

.btn-content-mobile {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    gap: 2px;
}

.btn-text-mobile {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: bold;
}

.price-tag-mobile {
    background: rgb(0 0 0 / 56%);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: bold;
    color: white;
    margin-top: 2px;
}

.auto-btn-mobile {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.reveal-btn-mobile {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.auto-game-btn-mobile {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
}

.play-btn-mobile:hover,
.auto-btn-mobile:hover,
.reveal-btn-mobile:hover,
.auto-game-btn-mobile:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 255, 136, 0.4);
}

.auto-btn-mobile:hover,
.reveal-btn-mobile:hover {
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
}

.auto-game-btn-mobile:hover {
    box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
}

.play-btn-mobile:disabled,
.auto-btn-mobile:disabled,
.reveal-btn-mobile:disabled,
.auto-game-btn-mobile:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Modal Styles Mobile */
.result-modal-mobile,
.auto-game-modal-mobile {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.modal-overlay-mobile {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(5px);
}

.modal-content-mobile {
    position: relative;
    background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    border-radius: 20px;
    max-width: 90vw;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
    border: 2px solid rgba(255, 255, 255, 0.1);
}

.modal-header-mobile {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.modal-title-mobile {
    color: white;
    font-size: 1.25rem;
    font-weight: bold;
    margin: 0;
}

.modal-close-mobile {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.3s ease;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-close-mobile:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

.modal-body-mobile {
    padding: 1.5rem;
    text-align: center;
}

.modal-prize-image-mobile {
    margin-bottom: 1rem;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80px;
}

.modal-prize-image-mobile img {
    max-width: 120px;
    max-height: 120px;
    object-fit: contain;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.modal-prize-info-mobile {
    color: white;
}

.modal-prize-title-mobile {
    font-size: 1.1rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: var(--primary-color);
}

.modal-prize-value-mobile {
    font-size: 1.5rem;
    font-weight: bold;
    color: #fbbf24;
    margin-bottom: 0.5rem;
}

.modal-prize-description-mobile {
    font-size: 0.9rem;
    color: #d1d5db;
    line-height: 1.4;
}

.modal-footer-mobile {
    padding: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-play-again-mobile,
.btn-start-auto-mobile {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: black;
    border: none;
    border-radius: 12px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1rem;
}

.btn-play-again-mobile:hover,
.btn-start-auto-mobile:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 255, 136, 0.4);
}

.auto-game-options-mobile {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.auto-option-mobile {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.3s ease;
    color: white;
}

.auto-option-mobile:hover {
    background: rgba(255, 255, 255, 0.1);
}

.auto-option-mobile input[type="radio"] {
    margin-right: 0.5rem;
}

.auto-price-mobile {
    font-weight: bold;
    color: var(--primary-color);
}

.turbo-toggle-mobile {
    margin-top: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}

.toggle-switch-mobile {
    display: flex;
    align-items: center;
    cursor: pointer;
    color: white;
}

.toggle-switch-mobile input[type="checkbox"] {
    display: none;
}

.toggle-slider-mobile {
    position: relative;
    width: 50px;
    height: 24px;
    background: #374151;
    border-radius: 12px;
    margin-right: 0.5rem;
    transition: background 0.3s ease;
}

.toggle-slider-mobile::before {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s ease;
}

.toggle-switch-mobile input[type="checkbox"]:checked + .toggle-slider-mobile {
    background: var(--primary-color);
}

.toggle-switch-mobile input[type="checkbox"]:checked + .toggle-slider-mobile::before {
    transform: translateX(26px);
}

.toggle-label-mobile {
    font-weight: 600;
}

/* Estados dos botões */
.scratching {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
}

.won {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
}

.lost {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

/* Estados do jogo */
.game-loading {
    pointer-events: none;
    opacity: 0.7;
}

.game-finished .scratch-canvas-mobile {
    pointer-events: none;
}

/* Hidden class utility */
.hidden {
    display: none !important;
}

/* Ajustar posição dos toasts para não ficarem atrás do header */
#NotiflixNotifyWrap {
    top: 60px !important;
    z-index: 9999 !important;
}

.notiflix-notify {
    margin-top: 10px !important;
}
#deposit-btn{         
line-height: 1;}

/* Responsividade para telas muito pequenas */
@media (max-width: 360px) {
    .raspadinha-card-mobile {
        width: 100%;
        height: calc(100vw - 1.6rem);
        max-width: 320px;
        max-height: 320px;
    }
    
    .btn-text-mobile {
        font-size: 11px;
    }
    
    .price-tag-mobile {
        font-size: 9px;
    }
    
    .action-buttons-mobile {
        gap: 6px;
    }
    
    .play-btn-mobile,
    .auto-btn-mobile,
    .reveal-btn-mobile,
    .auto-game-btn-mobile {
        min-height: 50px;
        padding: 10px 6px;
    }
    
    .raspadinha-title-section-mobile {
        padding: 0.6rem 0.8rem;
        margin-bottom: 0.4rem;
    }
    
    .raspadinha-name-mobile {
        font-size: 1.2rem;
    }
    
    .raspadinha-subtitle-mobile {
        font-size: 0.8rem;
    }
    
    .top-ganhos-header-mobile {
        padding: 0.6rem 0.8rem;
    }
    
    .top-ganhos-title-mobile {
        font-size: 0.8rem;
    }
    
    .top-ganhos-content-mobile {
        padding: 0.4rem;
    }
    
    .winner-avatar-mobile {
        width: 28px;
        height: 28px;
        font-size: 0.7rem;
    }
    
    .winner-name-mobile {
        font-size: 0.75rem;
    }
    
    .game-name-mobile {
        font-size: 0.65rem;
    }
    
    .amount-won-mobile {
        font-size: 0.75rem;
    }
    
    .time-ago-mobile {
        font-size: 0.65rem;
    }
}

/* Para telas maiores, manter proporção */
@media (min-width: 768px) {
    .raspadinha-card-mobile {
        width: 100%;
        height: calc(100vw - 1.6rem);
        max-width: 400px;
        max-height: 400px;
    }
    
    .raspadinha-name-mobile {
        font-size: 1.6rem;
    }
    
    .raspadinha-subtitle-mobile {
        font-size: 1rem;
    }
    
    .top-ganhos-title-mobile {
        font-size: 1rem;
    }
    
    .winner-avatar-mobile {
        width: 36px;
        height: 36px;
        font-size: 0.9rem;
    }
    
    .winner-name-mobile {
        font-size: 0.9rem;
    }
    
    .game-name-mobile {
        font-size: 0.75rem;
    }
    
    .amount-won-mobile {
        font-size: 0.9rem;
    }
    
    .time-ago-mobile {
        font-size: 0.75rem;
    }
}
</style>

<script>
function goBack() {
    window.location.href = "{{ route('home') }}";
}

// Reutilizar todo o JavaScript da versão desktop mas com adaptações para mobile
// Variáveis globais
let canvas, ctx;
let gameState = 'waiting'; // waiting, purchased, scratch, finished
let currentGameResult = null;
let isGameActive = false;
let isScratchEnabled = false;
let scratchedArea = 0;
let isMouseDown = false;
let scratchRadius = 25; // Aumentado para mobile

// Variável global para a imagem da raspadinha
let globalScratchImage = new Image();
let isImageLoaded = false;

// Pré-carregar a imagem da raspadinha
globalScratchImage.onload = function() {
    isImageLoaded = true;
};

globalScratchImage.onerror = function() {
    isImageLoaded = false;
};

// Carregar imagem imediatamente
globalScratchImage.src = '/raspadinha/raspadinha.webp?id=' + Date.now();

document.addEventListener('DOMContentLoaded', function() {
    initializeGame();
    setupEventListeners();
    
    // Configurar Notiflix para mobile
    if (typeof Notiflix !== 'undefined') {
        Notiflix.Notify.init({
            position: 'right-top',
            distance: '60px',
            zindex: 9999,
            timeout: 3000,
            showOnlyTheLastOne: true,
            clickToClose: true,
        });
    }
    
    // Verificar autenticação periodicamente
    setInterval(checkAuthStatus, 30000); // Verificar a cada 30 segundos
    
    // Inicializar scroll do top ganhos
    initTopGanhosScroll();
});

function initializeGame() {
    // Verificar se os dados da raspadinha são válidos
    if (!@json(isset($raspadinha) && $raspadinha)) {
        if (typeof Notiflix !== 'undefined') {
            Notiflix.Notify.warning('Erro ao carregar dados da raspadinha. Redirecionando...');
        }
        setTimeout(() => {
            window.location.href = "{{ route('home') }}";
        }, 2000);
        return;
    }
    
    canvas = document.getElementById('scratch-canvas');
    ctx = canvas.getContext('2d');
    
    if (!canvas || !ctx) {
        return;
    }
    
    resizeCanvas();
    resetGame();
}

function resizeCanvas() {
    const container = document.getElementById('scratch-container');
    if (!container) return;
    
    const rect = container.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = rect.height;
    
    // Redraw if necessary
    if (gameState === 'scratch' && isImageLoaded) {
        drawScratchLayer();
    }
}

function setupEventListeners() {
    // Botões de ação mobile
    document.querySelector('.play-btn-mobile')?.addEventListener('click', () => handlePurchase(false));
    document.querySelector('.auto-btn-mobile')?.addEventListener('click', () => handlePurchase(true));
    document.querySelector('.reveal-btn-mobile')?.addEventListener('click', revelarTudo);
    document.querySelector('.auto-game-btn-mobile')?.addEventListener('click', openAutoGameModal);
    document.getElementById('btn-comprar-raspar-mobile')?.addEventListener('click', handleComprarRasparMobile);
    
    // Eventos de scratch para mobile (touch)
setupScratchEvents();

// Resize listener
window.addEventListener('resize', resizeCanvas);

// Prevenir zoom no mobile durante interação
document.addEventListener('touchstart', function(e) {
    if (e.touches.length > 1) {
        e.preventDefault();
    }
}, { passive: false });

let lastTouchEnd = 0;
document.addEventListener('touchend', function(e) {
    const now = (new Date()).getTime();
    if (now - lastTouchEnd <= 300) {
        e.preventDefault();
    }
    lastTouchEnd = now;
}, false);
}

function setupScratchEvents() {
    // Touch events para mobile
    canvas.addEventListener('touchstart', handleTouchStart, { passive: false });
    canvas.addEventListener('touchmove', handleTouchMove, { passive: false });
    canvas.addEventListener('touchend', handleTouchEnd, { passive: false });
    
    // Mouse events para desktop/teste
    canvas.addEventListener('mousedown', handleMouseDown);
    canvas.addEventListener('mousemove', handleMouseMove);
    canvas.addEventListener('mouseup', handleMouseUp);
    canvas.addEventListener('mouseleave', handleMouseUp);
}

function handleTouchStart(e) {
    e.preventDefault();
    if (!isScratchEnabled) return;
    
    isMouseDown = true;
    const touch = e.touches[0];
    const rect = canvas.getBoundingClientRect();
    const x = touch.clientX - rect.left;
    const y = touch.clientY - rect.top;
    
    startScratch(x, y);
}

function handleTouchMove(e) {
    e.preventDefault();
    if (!isMouseDown || !isScratchEnabled) return;
    
    const touch = e.touches[0];
    const rect = canvas.getBoundingClientRect();
    const x = touch.clientX - rect.left;
    const y = touch.clientY - rect.top;
    
    scratch(x, y);
}

function handleTouchEnd(e) {
    e.preventDefault();
    isMouseDown = false;
}

function handleMouseDown(e) {
    if (!isScratchEnabled) return;
    
    isMouseDown = true;
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    startScratch(x, y);
}

function handleMouseMove(e) {
    if (!isMouseDown || !isScratchEnabled) return;
    
    const rect = canvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    
    scratch(x, y);
}

function handleMouseUp(e) {
    isMouseDown = false;
}

function startScratch(x, y) {
    ctx.globalCompositeOperation = 'destination-out';
    ctx.beginPath();
    ctx.arc(x, y, scratchRadius, 0, 2 * Math.PI);
    ctx.fill();
    
    updateScratchedArea();
}

function scratch(x, y) {
    ctx.globalCompositeOperation = 'destination-out';
    ctx.lineWidth = scratchRadius * 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    
    ctx.lineTo(x, y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(x, y);
    
    updateScratchedArea();
}

function updateScratchedArea() {
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const pixelData = imageData.data;
    let transparentPixels = 0;
    
    for (let i = 3; i < pixelData.length; i += 4) {
        if (pixelData[i] === 0) {
            transparentPixels++;
        }
    }
    
    scratchedArea = (transparentPixels / (canvas.width * canvas.height)) * 100;
    
    // Auto-revelar quando 60% foi raspado
    if (scratchedArea >= 60 && isScratchEnabled) {
        autoFinishScratch();
    }
}

function drawScratchLayer() {
    if (!ctx || !canvas) return;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.globalCompositeOperation = 'source-over';
    ctx.fillStyle = 'rgba(0,0,0,1)';

    // Usar a imagem pré-carregada
    if (isImageLoaded && globalScratchImage.complete) {
        ctx.drawImage(globalScratchImage, 0, 0, canvas.width, canvas.height);
    } else {
        // Fallback visual simples se a imagem não carregar
        const gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
        gradient.addColorStop(0, '#1f2937');
        gradient.addColorStop(1, '#374151');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 20px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('RASPADINHA', canvas.width / 2, canvas.height / 2 - 15);
        ctx.font = '14px Arial';
        ctx.fillText('Toque para raspar', canvas.width / 2, canvas.height / 2 + 15);
    }
}

// Finalizar raspagem automaticamente
function autoFinishScratch() {
    isScratchEnabled = false;
    
    // Limpar canvas imediatamente
    if (ctx && canvas) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }
    
    finishScratch();
}

async function handlePurchase(isTurbo) {
    if (gameState === 'scratch' && isGameActive && currentGameResult) {
        Notiflix.Notify.warning('Raspe a cartela atual antes de comprar uma nova!');
        return;
    }

    const button = isTurbo ? document.querySelector('.auto-btn-mobile') : document.querySelector('.play-btn-mobile');
    const price = button ? parseFloat(button.dataset.price || 0) : 0;
    
    // Desabilitar botões durante a compra
    disableButtons();
    
    try {
        const response = await handleFetchWithAuthCheck(`/raspadinha/{{ $raspadinha?->id ?? 0 }}/play`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                is_turbo: isTurbo
            })
        });

        const data = await response.json();


        if (data.success) {
            processGameResult(data);
            updateBalance(data.new_balance);
        } else {
            Notiflix.Notify.failure(data.message || 'Erro ao processar compra');
            enableButtons();
        }
    } catch (error) {
        Notiflix.Notify.failure('Erro interno. Tente novamente.');
        enableButtons();
    }
}

function processGameResult(data) {
    currentGameResult = data;
    gameState = 'scratch';
    isGameActive = true;
    
    // Configurar fundo com produtos (mesma lógica do desktop)
    setupGameBackground(data);
    
    // Ocultar overlay de compra
    const overlay = document.getElementById('btn-overlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
    
    // Desenhar camada de raspagem
    drawScratchLayer();
    
    // Habilitar raspagem
    isScratchEnabled = true;
    
    // Ocultar botões de compra e mostrar botão revelar
    const playBtn = document.querySelector('.play-btn-mobile');
    const autoBtn = document.querySelector('.auto-btn-mobile');
    const revealBtn = document.querySelector('.reveal-btn-mobile');
    const autoGameBtn = document.querySelector('.auto-game-btn-mobile');
    
    if (playBtn) playBtn.style.display = 'none';
    if (autoBtn) autoBtn.style.display = 'none';
    if (autoGameBtn) autoGameBtn.style.display = 'none';
    if (revealBtn) {
        revealBtn.style.display = 'block';
        revealBtn.disabled = false;
    }
    
}

// Configurar fundo com produtos (integração com sistema de prêmios) - Mesma lógica do desktop
function setupGameBackground(data) {
    const prizesGrid = document.getElementById('prizes-grid');
    if (!prizesGrid) return;
    
    prizesGrid.innerHTML = '';
    
    const items = data.items || [];
    if (items.length === 0) {
        return;
    }
    
    // Usar o grid enviado pelo backend que já tem a lógica correta
    let gridData = data.grid || [];
    
    console.log('Setup Game Background Mobile - Data received:', {
        is_winner: data.is_winner,
        grid_length: gridData.length,
        grid_data: gridData,
        prize_info: data.prize_info
    });
    
    // Construir HTML dos prêmios baseado no grid correto do backend
    gridData.forEach((itemId, index) => {
        const item = items.find(i => i.id === itemId);
        if (!item) {
            console.warn('Item não encontrado no grid:', itemId);
            return;
        }
        
        const cellDiv = document.createElement('div');
        cellDiv.dataset.itemId = itemId;
        cellDiv.dataset.gridPosition = index;
        
        // Verificar se este item específico é o ganhador
        // Só marcar como winner se for vitória E for o item premiado
        const isWinner = data.is_winner && 
                        data.prize_info && 
                        data.prize_info.item_id == itemId;
        
        if (isWinner) {
            cellDiv.classList.add('winner');
        }
        
        if (item.image_url) {
            cellDiv.innerHTML = `
                <img src="${item.image_url}" alt="${item.name}" />
                <span class="prize-text">${item.name}</span>
            `;
        } else {
            cellDiv.innerHTML = `
                <span class="prize-text">${item.name}</span>
            `;
        }
        
        prizesGrid.appendChild(cellDiv);
    });
    
    // Mostrar o grid
    prizesGrid.style.display = 'grid';
    
    console.log('Mobile Grid setup completed. Winners count:', document.querySelectorAll('.prizes-grid-mobile > div.winner').length);
}

function revelarTudo() {
    if (!isScratchEnabled || !currentGameResult) return;
    
    // Desabilitar botão revelar para evitar cliques múltiplos
    isScratchEnabled = false;
    document.querySelector('.reveal-btn-mobile').disabled = true;
    document.querySelector('.reveal-btn-mobile').innerHTML = `
        <div class="btn-content-mobile">
            <div class="btn-text-mobile">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Revelando...</span>
            </div>
        </div>
    `;
    
    // Delay para mostrar o loading
    setTimeout(() => {
        // Limpar canvas completamente
        if (ctx && canvas) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        finishScratch();
    }, 1000); // 1 segundo de delay para mostrar o "revelando..."
}

// Finalizar raspagem e processar resultado
function finishScratch() {
    if (!currentGameResult) return;
    
    // Agora precisamos chamar o endpoint para processar o prêmio
    claimPrize(currentGameResult);
}

// Função para processar prêmio após raspagem
async function claimPrize(gameData) {
    try {
        const requestBody = {
            history_id: gameData.history_id
        };
        
        // Adicionar session_token se disponível
        if (gameData.session_token) {
            requestBody.session_token = gameData.session_token;
        }
        
        // Adicionar game_checksum se disponível
        if (gameData.game_checksum) {
            requestBody.game_checksum = gameData.game_checksum;
        }
        
        const response = await handleFetchWithAuthCheck(`/raspadinha/{{ $raspadinha?->id ?? 0 }}/claim-prize`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(requestBody)
        });

        const data = await response.json();

        if (data.success) {
            // Atualizar saldo no frontend
            updateBalance(data.new_balance);
            
            // Combinar dados para exibição
            const combinedData = {
                ...gameData,
                ...data,
                amount_won: data.amount_won || gameData.amount_won
            };
            
            // Processar resultado final
            processGameResultFinal(combinedData);
        } else {
            // Em caso de erro, ainda mostrar o resultado mas sem creditar
            processGameResultFinal(gameData);
        }
    } catch (error) {
        // Em caso de erro, ainda mostrar o resultado mas sem creditar
        processGameResultFinal(gameData);
    }
}

// Processar resultado final do jogo (após revelar)
function processGameResultFinal(data) {
    const jsConfetti = new JSConfetti();
    
    // Sempre mostrar modal de resultado
    setTimeout(() => {
        showResultModal(data);
    }, 1000);
    
    if (!data.is_winner || data.amount_won <= 0) {
        if (typeof Notiflix !== 'undefined') {
            Notiflix.Notify.info('Não foi dessa vez. 😢');
        }
    } else {
        // Usar o valor exato que foi ganho
        const prizeValue = data.amount_won;
        const prizeText = `R$ ${prizeValue.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
        if (typeof Notiflix !== 'undefined') {
            Notiflix.Notify.success(`🎉 Você ganhou ${prizeText}!`);
        }
        
        // Mostrar confetes
        jsConfetti.addConfetti({
            emojis: ['🎉', '✨', '🎊', '🥳'],
            emojiSize: 20,
            confettiNumber: 200,
            confettiRadius: 6,
            confettiColors: ['#ff0a54', '#ff477e', '#ff85a1', '#fbb1b1', '#f9bec7']
        });
    }
    
    // Reset dos botões
    resetButtons();
    gameState = 'finished';
}


function showResultModal(data) {
    const modal = document.getElementById('result-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalPrizeImage = document.getElementById('modal-prize-image');
    const modalPrizeTitle = document.getElementById('modal-prize-title');
    const modalPrizeValue = document.getElementById('modal-prize-value');
    const modalPrizeDescription = document.getElementById('modal-prize-description');
    

    
    if (data.is_winner && data.amount_won > 0) {
        modalTitle.textContent = 'Parabéns! Você ganhou!';
        
        // Buscar item premiado correto
        let prizedItem = null;
        let prizeImageSrc = null;
        let prizeName = '';
        let prizeDescription = '';
        
        // Verificar se temos informações do item premiado
        if (data.prize_info && data.prize_info.item_id) {
            prizedItem = data.items ? data.items.find(item => 
                item.id === data.prize_info.item_id
            ) : null;
            
            if (prizedItem) {
                prizeImageSrc = prizedItem.image_url;
                prizeName = prizedItem.name;
                prizeDescription = prizedItem.product_description || prizedItem.name;
            }
        }
        
        // Se não encontrou o item, usar informações diretas do resultado
        if (!prizedItem && data.prize_info) {
            prizeName = data.prize_info.prize_name || data.prize_info.name || '';
            prizeImageSrc = data.prize_info.prize_image || data.prize_info.image_url;
            prizeDescription = data.prize_info.prize_description || data.prize_info.product_description || '';
        }
        
        // Configurar título e descrição
        modalPrizeTitle.textContent = prizeName || 'Prêmio';
        modalPrizeValue.textContent = `R$ ${parseFloat(data.amount_won || 0).toFixed(2).replace('.', ',')}`;
        modalPrizeDescription.textContent = prizeDescription || 'Descrição do prêmio';
        
        // Mostrar imagem do prêmio
        if (prizeImageSrc) {
            modalPrizeImage.innerHTML = `<img src="${prizeImageSrc}" alt="${prizeName}" onerror="this.style.display='none'">`;
        } else {
            modalPrizeImage.innerHTML = '<i class="fas fa-gift text-6xl text-green-400"></i>';
        }
    } else {
        modalTitle.textContent = 'Não foi desta vez!';
        modalPrizeTitle.textContent = 'Tente novamente';
        modalPrizeValue.textContent = 'R$ 0,00';
        modalPrizeDescription.textContent = 'Continue tentando, a sorte pode estar próxima!';
        modalPrizeImage.innerHTML = '<i class="fas fa-sad-tear text-6xl text-gray-400" style="color:white;"></i>';
    }
    
    modal.style.display = 'flex';
}

function closeResultModal() {
    document.getElementById('result-modal').style.display = 'none';
    
    // Resetar estado para permitir novo jogo
    gameState = 'waiting';
    currentGameResult = null;
    isGameActive = false;
    isScratchEnabled = false;
    scratchedArea = 0;
    isMouseDown = false;
    
    // Resetar completamente para novo jogo
    resetGameForNewPlay();
}

function resetGameForNewPlay() {
    gameState = 'waiting';
    currentGameResult = null;
    isGameActive = false;
    isScratchEnabled = false;
    scratchedArea = 0;
    
    // Limpar grid
    const prizesGrid = document.getElementById('prizes-grid');
    if (prizesGrid) {
        prizesGrid.innerHTML = '';
        prizesGrid.style.display = 'none';
    }
    
    // Mostrar imagem da raspadinha de fundo
    if (canvas && ctx) {
        drawScratchLayer();
    }
    
    // Mostrar overlay de compra
    const overlay = document.getElementById('btn-overlay');
    if (overlay) {
        overlay.style.display = 'flex';
    }
    
    // Resetar botão comprar e raspar
    const btnComprarRaspar = document.getElementById('btn-comprar-raspar-mobile');
    if (btnComprarRaspar) {
        btnComprarRaspar.disabled = false;
        btnComprarRaspar.innerHTML = `
            <i class="fas fa-gift mr-2"></i>
            Comprar e Raspar
        `;
    }
    
    // Resetar botões para estado inicial
    resetButtonsToInitialState();
}

// Resetar botões após jogo (seguindo lógica do desktop)
function resetButtons() {
    const playBtn = document.querySelector('.play-btn-mobile');
    const turboBtn = document.querySelector('.auto-btn-mobile');
    const revealBtn = document.querySelector('.reveal-btn-mobile');
    const autoGameBtn = document.querySelector('.auto-game-btn-mobile');
    
    if (playBtn) {
        playBtn.disabled = false;
        playBtn.style.display = 'block';
        playBtn.style.opacity = '1';
        playBtn.style.pointerEvents = 'auto';
        playBtn.innerHTML = `
            <div class="btn-content-mobile">
                <div class="btn-text-mobile">
                    <i class="fas fa-redo"></i>
                    <span>Novamente</span>
                </div>
                <div class="price-tag-mobile">R$ {{ number_format($raspadinha?->price ?? 0, 2, ',', '.') }}</div>
            </div>
        `;
        
        // Adicionar evento específico para jogar novamente
        playBtn.onclick = function() {
            resetGameForNewPlay();
            handlePurchase(false);
        };
    }
    
    if (turboBtn) {
        turboBtn.disabled = false;
        turboBtn.style.display = 'block';
        turboBtn.style.opacity = '1';
        turboBtn.style.pointerEvents = 'auto';
        
        // Adicionar evento específico para jogar novamente
        turboBtn.onclick = function() {
            resetGameForNewPlay();
            handlePurchase(true);
        };
    }
    
    if (revealBtn) {
        revealBtn.disabled = false;
        revealBtn.style.display = 'none';
        // Resetar HTML do botão revelar
        revealBtn.innerHTML = `
            <div class="btn-content-mobile">
                <div class="btn-text-mobile">
                    <i class="fas fa-eye"></i>
                    <span>Revelar Tudo</span>
                </div>
            </div>
        `;
    }
    
    if (autoGameBtn) {
        autoGameBtn.disabled = false;
        autoGameBtn.style.display = 'block';
        autoGameBtn.style.opacity = '1';
        autoGameBtn.style.pointerEvents = 'auto';
    }
}

// Nova função para comprar e raspar no mobile
function handleComprarRasparMobile() {
    if (gameState === 'scratch' && isGameActive && currentGameResult) {
        Notiflix.Notify.warning('Raspe a cartela atual antes de comprar uma nova!');
        return;
    }
    
    const button = document.getElementById('btn-comprar-raspar-mobile');
    
    // Desabilitar botão durante a compra
    button.disabled = true;
    
    const originalHTML = button.innerHTML;
    button.innerHTML = `
        <i class="fas fa-spinner fa-spin mr-2"></i>
        Comprando...
    `;
    
    // Fazer a compra
    handleFetchWithAuthCheck(`/raspadinha/{{ $raspadinha?->id ?? 0 }}/play`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            is_turbo: false // Sempre normal no botão comprar e raspar
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            processGameResult(data);
            updateBalance(data.new_balance);
        } else {
            Notiflix.Notify.failure(data.message || 'Erro ao processar compra');
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
    })
    .catch(error => {
        Notiflix.Notify.failure('Erro interno. Tente novamente.');
        button.disabled = false;
        button.innerHTML = originalHTML;
    });
}

function resetGame() {
    resetGameForNewPlay();
}

// Resetar botões para estado inicial (antes de comprar)
function resetButtonsToInitialState() {
    const playBtn = document.querySelector('.play-btn-mobile');
    const turboBtn = document.querySelector('.auto-btn-mobile');
    const revealBtn = document.querySelector('.reveal-btn-mobile');
    const autoGameBtn = document.querySelector('.auto-game-btn-mobile');
    
    if (playBtn) {
        playBtn.disabled = false;
        playBtn.style.display = 'block';
        playBtn.style.opacity = '1';
        playBtn.style.pointerEvents = 'auto';
        playBtn.innerHTML = `
            <div class="btn-content-mobile">
                <div class="btn-text-mobile">
                    <i class="fas fa-gift"></i>
                    <span>Jogar</span>
                </div>
                <div class="price-tag-mobile">R$ {{ number_format($raspadinha?->price ?? 0, 2, ',', '.') }}</div>
            </div>
        `;
        playBtn.onclick = function() { handlePurchase(false); };
    }
    
    if (turboBtn) {
        turboBtn.disabled = false;
        turboBtn.style.display = 'block';
        turboBtn.style.opacity = '1';
        turboBtn.style.pointerEvents = 'auto';
        turboBtn.innerHTML = `
            <div class="btn-content-mobile">
                <div class="btn-text-mobile">
                    <i class="fas fa-bolt"></i>
                    <span>Turbo</span>
                </div>
                <div class="price-tag-mobile">R$ {{ number_format($raspadinha?->turbo_price ?? 0, 2, ',', '.') }}</div>
            </div>
        `;
        turboBtn.onclick = function() { handlePurchase(true); };
    }
    
    if (revealBtn) {
        revealBtn.style.display = 'none';
        revealBtn.disabled = false;
    }
    
    if (autoGameBtn) {
        autoGameBtn.disabled = false;
        autoGameBtn.style.display = 'block';
        autoGameBtn.style.opacity = '1';
        autoGameBtn.style.pointerEvents = 'auto';
    }
}

function disableButtons() {
    // Desabilitar e ocultar visualmente os botões de compra
    const playBtn = document.querySelector('.play-btn-mobile');
    const autoBtn = document.querySelector('.auto-btn-mobile');
    const revealBtn = document.querySelector('.reveal-btn-mobile');
    
    if (playBtn) {
        playBtn.disabled = true;
        playBtn.style.opacity = '0.5';
        playBtn.style.pointerEvents = 'none';
    }
    
    if (autoBtn) {
        autoBtn.disabled = true;
        autoBtn.style.opacity = '0.5';
        autoBtn.style.pointerEvents = 'none';
    }
    
    if (revealBtn) {
        revealBtn.disabled = true;
    }
}

function enableButtons() {
    // Reabilitar e restaurar visibilidade dos botões de compra
    const playBtn = document.querySelector('.play-btn-mobile');
    const autoBtn = document.querySelector('.auto-btn-mobile');
    const revealBtn = document.querySelector('.reveal-btn-mobile');
    
    if (playBtn) {
        playBtn.disabled = false;
        playBtn.style.opacity = '1';
        playBtn.style.pointerEvents = 'auto';
    }
    
    if (autoBtn) {
        autoBtn.disabled = false;
        autoBtn.style.opacity = '1';
        autoBtn.style.pointerEvents = 'auto';
    }
    
    if (revealBtn) {
        revealBtn.disabled = false;
    }
}

function updateBalance(newBalance) {
    const balanceElements = document.querySelectorAll('#user-balance, .balance-display');
    const formattedBalance = `R$ ${parseFloat(newBalance).toFixed(2).replace('.', ',')}`;
    
    balanceElements.forEach(element => {
        element.textContent = formattedBalance;
    });
}

// Mostrar resultado do jogo automático
function showAutoResult(data) {
    const modal = document.getElementById('result-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalPrizeImage = document.getElementById('modal-prize-image');
    const modalPrizeTitle = document.getElementById('modal-prize-title');
    const modalPrizeValue = document.getElementById('modal-prize-value');
    const modalPrizeDescription = document.getElementById('modal-prize-description');
    
    modalTitle.textContent = 'Resultado do Jogo Automático';
    
    // Ícone para jogo automático
    modalPrizeImage.innerHTML = '<i class="fas fa-robot text-6xl" style="color: var(--primary-color);"></i>';
    
    // Título com estatísticas
    modalPrizeTitle.textContent = `${data.quantity} jogadas realizadas`;
    
    // Valor total ganho
    let totalWon = 'R$ 0,00';
    if (data.formatted_total_prize) {
        totalWon = data.formatted_total_prize;
    } else if (data.total_prize) {
        totalWon = `R$ ${parseFloat(data.total_prize).toFixed(2).replace('.', ',')}`;
    }
    modalPrizeValue.textContent = totalWon;
    
    // Descrição detalhada
    const winnersCount = data.winners_count || 0;
    let formattedCost = 'R$ 0,00';
    if (data.formatted_total_cost) {
        formattedCost = data.formatted_total_cost;
    } else if (data.total_cost) {
        formattedCost = `R$ ${parseFloat(data.total_cost).toFixed(2).replace('.', ',')}`;
    }
    
    modalPrizeDescription.innerHTML = `
        <div style="text-align: center; line-height: 1.6;">
            <div style="margin: 10px 0; padding: 10px; background: rgba(0, 255, 136, 0.1); border-radius: 8px;">
                <strong style="color: var(--primary-color);">${winnersCount} vitórias</strong> de ${data.quantity} jogadas
            </div>
            <div style="margin: 10px 0; font-size: 14px; opacity: 0.8;">
                Custo total: ${formattedCost}
            </div>
            <div style="margin: 10px 0; font-size: 14px; opacity: 0.8;">
                ${winnersCount > 0 ? 
                    `Taxa de vitória: ${((winnersCount / data.quantity) * 100).toFixed(1)}%` : 
                    'Nenhuma vitória desta vez'
                }
            </div>
        </div>
    `;
    
    modal.style.display = 'flex';
}

// Modal de Auto Jogo
function openAutoGameModal() {
    document.getElementById('auto-game-modal').style.display = 'flex';
}

function closeAutoGameModal() {
    document.getElementById('auto-game-modal').style.display = 'none';
}

async function startAutoGame() {
    const selectedQuantity = document.querySelector('input[name="auto_quantity"]:checked')?.value;
    const isTurbo = document.getElementById('auto-turbo-mode').checked;
    
    if (!selectedQuantity) {
        Notiflix.Notify.warning('Selecione a quantidade de jogadas');
        return;
    }
    
    closeAutoGameModal();
    
    // Desabilitar todos os botões
    disableButtons();
    
    try {
        const response = await handleFetchWithAuthCheck(`/raspadinha/{{ $raspadinha?->id ?? 0 }}/play-auto`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: parseInt(selectedQuantity),
                is_turbo: isTurbo
            })
        });

        const data = await response.json();

        if (data.success) {
            // Mostrar resultado detalhado do auto jogo
            showAutoResult(data);
            updateBalance(data.new_balance);
        } else {
            Notiflix.Notify.failure(data.message || 'Erro ao processar auto jogo');
        }
    } catch (error) {
        Notiflix.Notify.failure('Erro interno. Tente novamente.');
    } finally {
        enableButtons();
    }
}

// Função para verificar se o usuário ainda está autenticado
async function checkAuthStatus() {
    try {
        const response = await fetch('/raspadinha/user/balance', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.status === 401) {
            handleAuthenticationError();
        }
    } catch (error) {
        // Em caso de erro de rede, não fazer nada
    }
}

// Função para lidar com erro de autenticação
function handleAuthenticationError() {
    // Parar qualquer jogo em andamento
    isGameActive = false;
    isScratchEnabled = false;
    
    // Mostrar notificação
    if (typeof Notiflix !== 'undefined') {
        Notiflix.Notify.warning('Sua sessão expirou. Você precisa estar logado para acessar a raspadinha.');
    } else {
        alert('Sua sessão expirou. Você precisa estar logado para acessar a raspadinha.');
    }
    
    // Redirecionar para a página inicial após 2 segundos
    setTimeout(() => {
        window.location.href = "{{ route('home') }}";
    }, 2000);
}

// Função para interceptar erros 401 em todas as requisições
function handleFetchWithAuthCheck(url, options = {}) {
    return fetch(url, options)
        .then(response => {
            if (response.status === 401) {
                handleAuthenticationError();
                throw new Error('Unauthorized');
            }
            return response;
        });
}

// Função para inicializar o scroll automático do top ganhos
function initTopGanhosScroll() {
    const container = document.getElementById('top-ganhos-container');
    const wrapper = document.getElementById('top-ganhos-wrapper');
    if (!container || !wrapper) return;
    
    const items = wrapper.querySelectorAll('.top-ganhos-item-mobile');
    if (items.length <= 3) return; // Se há 3 ou menos itens, não precisa de scroll
    
    let currentIndex = 0;
    
    // Adicionar animação de transição
    wrapper.style.transition = 'opacity 0.5s ease-in-out';
    
    // Função para mover para o próximo conjunto de itens
    function moveToNext() {
        // Fade out
        wrapper.style.opacity = '0.3';
        
        setTimeout(() => {
            currentIndex = (currentIndex + 1) % items.length;
            
            // Ocultar todos os itens
            items.forEach(item => {
                item.style.display = 'none';
                item.style.opacity = '0';
                item.style.transform = 'translateY(10px)';
                item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            });
            
            // Mostrar os próximos 3 itens (ou menos se não houver 3)
            for (let i = 0; i < 3; i++) {
                const index = (currentIndex + i) % items.length;
                if (items[index]) {
                    items[index].style.display = 'block';
                    items[index].style.order = i + 1;
                    
                    // Animar entrada
                    setTimeout(() => {
                        items[index].style.opacity = '1';
                        items[index].style.transform = 'translateY(0)';
                    }, i * 100); // Delay escalonado
                }
            }
            
            // Fade in
            wrapper.style.opacity = '1';
        }, 300);
    }
    
    // Iniciar com os primeiros 3 itens visíveis
    moveToNext();
    
    // Definir intervalo para mover para o próximo conjunto
    setInterval(moveToNext, 5000); // Trocar a cada 5 segundos (aumentado para dar tempo da animação)
}
</script>

<!-- Scripts necessários -->
<script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/notiflix@3.2.5/dist/notiflix-3.2.5.min.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/notiflix@3.2.5/dist/notiflix-aio-3.2.5.min.js"></script>

@endsection 