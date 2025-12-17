@extends('layouts.app')

@section('title', 'Jogar - ' . $raspadinha->name)

@section('content')
<section data-v-1d35be9f="" id="casino">
    <section data-v-9dae45d3="" data-v-1d35be9f="" id="frame_game" class="">
        <header data-v-9dae45d3="" class="game-header">
            <h6 data-v-9dae45d3="">{{ $raspadinha->name }}</h6>
            <button data-v-9dae45d3="" class="close_full" onclick="window.history.back();">
                <span data-v-9dae45d3="" class="nuxt-icon nuxt-icon--fill">
                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z" fill="currentColor"></path>
                    </svg>
                </span>
            </button>
        </header>
        
        <!-- Container do jogo da raspadinha (integrado com o sistema existente) -->
        <div id="raspadinha-game-iframe" style="height: 100%; width: 100%; position: relative; overflow: hidden; box-sizing: border-box;">
            <div id="raspadinha-game-container" class="w-full h-full bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900">
                <!-- Título da Raspadinha -->
                <div class="raspadinha-title-section">
                    <h1 class="raspadinha-name">{{ $raspadinha?->name ?? 'Raspadinha' }}</h1>
                    <p class="raspadinha-subtitle">{{ $raspadinha?->description ?? 'Raspe os 9 quadradinhos, encontre 3 símbolos iguais e ganhe o prêmio!' }}</p>
                </div>

                <!-- Área principal reorganizada lado a lado -->
                <div class="raspadinha-main-area">
                    <!-- Container da Raspadinha (lado esquerdo) -->
                    <div class="raspadinha-game-section">
                        <div id="scratch-container" class="raspadinha-card">
                            <!-- Grid de prêmios (background) -->
                            <div id="prizes-grid" class="prizes-grid">
                                <!-- Os prêmios serão inseridos aqui dinamicamente -->
                            </div>
                            
                            <!-- Canvas de raspagem -->
                            <canvas id="scratch-canvas" class="scratch-canvas"></canvas>
                            
                            <!-- Overlay de compra inicial -->
                            <div id="btn-overlay" class="purchase-overlay">
                                <div class="purchase-content">
                                    <button id="btn-comprar-raspar" class="btn-comprar-raspar">
                                        <i class="fas fa-gift mr-2"></i>
                                        Comprar e Raspar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- Controles (lado direito) -->
                    <div class="raspadinha-controls">
                        <!-- Botões de ação -->
                        <div class="action-buttons">
                            <button id="btn-buy" class="play-btn">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-gift"></i>
                                        <span>Jogar</span>
                                    </div>
                                    <span class="price-tag">R$ {{ number_format($raspadinha?->price ?? 0, 2, ',', '.') }}</span>
                                </div>
                            </button>
                            <button id="btn-buy-turbo" class="play-btn turbo">
                                <div class="flex items-center justify-between w-full">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-bolt"></i>
                                        <span>Turbo</span>
                                    </div>
                                    <span class="price-tag">R$ {{ number_format($raspadinha?->turbo_price ?? 0, 2, ',', '.') }}</span>
                                </div>
                            </button>
                            <button id="btn-reveal" class="reveal-btn hidden">
                                <div class="flex items-center justify-center w-full" style="color:black;">
                                    <i class="fas fa-eye mr-2"></i>
                                <span>Revelar Tudo</span>
                                </div>
                            </button>
                            <button id="auto-play-button" class="auto-btn">
                                <div class="flex items-center justify-center w-full" style="color:black;">
                                    <i class="fas fa-magic mr-2"></i>
                                    <span>Automático</span>
                        </div>
                            </button>
                        </div>
                        
                        <!-- Top Ganhos da Raspadinha -->
                        <div class="top-ganhos">
                            <div class="top-ganhos-header">
                                <div class="top-ganhos-title">
                                    <i class="fas fa-trophy"></i>
                                    <span>TOP GANHOS</span>
                                </div>
                            </div>
                            <div class="top-ganhos-content">
                                <div class="top-ganhos-container" id="top-ganhos-container">
                                    <div class="top-ganhos-wrapper" id="top-ganhos-wrapper">
                                        @forelse($recentPrizes as $prize)
                                            <div class="top-ganhos-item">
                                                <div class="winner-info">
                                                    <div class="winner-avatar">
                                                        {{ substr($prize->user->name ?? 'J', 0, 1) }}
                                                    </div>
                                                    <div class="winner-details">
                                                        <div class="winner-name">
                                                            {{ substr($prize->user->name ?? 'Jogador', 0, 3) }}****
                                                            <span class="game-name">{{ $raspadinha->name }}</span>
                                                        </div>
                                                        <div class="winner-amount">
                                                            <span class="amount-won">R$ {{ number_format($prize->amount_won * 10, 2, ',', '.') }}</span>
                                                            <span class="time-ago">{{ $prize->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="no-winners-message">
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
    
    <div data-v-bd788567="" data-v-1d35be9f="" id="game_details">
        <div data-v-bd788567="" class="w-full">
            <h1 data-v-bd788567="">{{ $raspadinha?->name ?? 'Raspadinha' }}</h1>
            <p data-v-bd788567="" class="provider">Raspe e Ganhe!</p>
        </div>
        <div data-v-bd788567="" class="casino-buttons">
            <button data-v-bd788567="" class="casino-buttons__fullscreen" onclick="toggleFullscreen();" title="Tela Cheia">
                <span data-v-bd788567="" class="nuxt-icon nuxt-icon--fill">
                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M128 64H32C14.31 64 0 78.31 0 96v96c0 17.69 14.31 32 32 32s32-14.31 32-32V128h64c17.69 0 32-14.31 32-32S145.7 64 128 64zM480 288c-17.69 0-32 14.31-32 32v64h-64c-17.69 0-32 14.31-32 32s14.31 32 32 32h96c17.69 0 32-14.31 32-32v-96C512 302.3 497.7 288 480 288z" fill="currentColor"></path>
                        <path d="M480 64h-96c-17.69 0-32 14.31-32 32s14.31 32 32 32h64v64c0 17.69 14.31 32 32 32s32-14.31 32-32V96C512 78.31 497.7 64 480 64zM128 384H64v-64c0-17.69-14.31-32-32-32s-32 14.31-32 32v96c0 17.69 14.31 32 32 32h96c17.69 0 32-14.31 32-32S145.7 384 128 384z" fill="currentColor" opacity="0.4"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>

    <!-- Seção de Outras Raspadinhas -->
    <div data-v-1d35be9f="" class="nM44t mb-4 md:mb-8" style="--d879e6ea: 16px; --45b10934: 4;">
        <div class="SM-j1">
            <div class="h9HDs">
                <h2 data-v-debf714a="" class="title flex items-center justify-center">
                    <p class="">Outras <strong>RASPADINHAS</strong></p>
                </h2>
            </div>
        </div>
        <div class="w-full">
            <div class="-JVa3 Vulse EEtS9" style="--620ba053: calc((100% - 48px) / 4); --063993a6: 16px; --8ec19218: calc((100% - 48px) / 4); --543ef9ea: 0;">
                <div class="rpneC uyA-x H3vO2">
                    @php
                        // Buscar outras raspadinhas ativas (excluindo a atual)
                        $otherRaspadinhas = \App\Models\Raspadinha::active()
                            ->where('id', '!=', $raspadinha->id)
                            ->withCount(['history as plays_count'])
                            ->orderBy('plays_count', 'desc')
                            ->limit(30)
                            ->get();
                    @endphp
                    @foreach($otherRaspadinhas as $index => $otherRaspadinha)
                    <div class="peBY3 Jj-AP" style="order: {{ $loop->index + 1 }};">
                        <a href="JavaScript: void(0);" onclick="openRaspadinha({{ $otherRaspadinha->id }});" class="s3HXA">
                            <div class="u3Qxq">
                                <div class="g-hw5">
                                    <img alt="{{ $otherRaspadinha->name }}" class="vTFYb" src="{{ $otherRaspadinha->image_url }}" />
                                    <div class="raspadinha-overlay">
                                        <div class="price-badge normal">R$ {{ number_format($otherRaspadinha->price, 2, ',', '.') }}</div>
                                        <div class="price-badge turbo">⚡ R$ {{ number_format($otherRaspadinha->turbo_price, 2, ',', '.') }}</div>
                                        @if($otherRaspadinha->plays_count > 0)
                                            <div class="plays-badge">{{ $otherRaspadinha->plays_count }} jogadas</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="hzP6t">
                                    <span class="phlJe">{{ $otherRaspadinha->name }}</span>
                                    <span class="liQBm">Raspe e Ganhe!</span>
                                </div>
                                <section class="bBtlK">
                                    <span class="Oe7Pi">
                                        <span class="nuxt-icon nuxt-icon--fill">
                                            <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <span>{{ __('menu.play') }}</span>
                                    </span>
                                </section>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de Jogo Automático -->
<div id="auto-modal" class="auto-modal">
    <div class="auto-modal-content">
        <h3>Jogo Automático</h3>
        <div class="auto-options">
            <div class="input-group">
                <label>Quantidade de jogadas:</label>
                <input type="number" id="auto-quantity" min="1" max="100" value="10">
            </div>
            <div class="input-group">
                <label>
                    <input type="checkbox" id="auto-turbo"> Modo Turbo
                </label>
            </div>
        </div>
        <div class="auto-buttons">
            <button onclick="closeAutoModal()" class="cancel-btn">Cancelar</button>
            <button onclick="startAutoGame()" class="confirm-btn">Iniciar</button>
        </div>
    </div>
</div>

<!-- Modal de Resultado -->
<div id="result-modal" class="result-modal">
    <div class="result-modal-content">
        <button class="result-close-btn" onclick="closeResultModal()">
            <i class="fas fa-times"></i>
        </button>
        <div class="result-header">
            <div id="result-icon" class="result-icon"></div>
            <h3 id="result-title">Resultado</h3>
        </div>
        <div class="result-body">
            <div id="result-prize-image" class="result-prize-image"></div>
            <div id="result-content" class="result-text"></div>
        </div>
        <button onclick="closeResultModal()" class="close-result-btn" style="color:black;">Continuar Jogando</button>
    </div>
</div>

<!-- Canvas de Confete -->
<canvas id="confetti-canvas" class="confetti-canvas"></canvas>

<style>
/* Variáveis CSS do sistema */
:root {
    --primary-color: #00FF88;
    --secondary-color: #00FF66;
    --tertiary-color: #00FF88;
    --bg-color: #0D1F0D;
    --support-color: #00FF88;
}

/* Container do iframe */
#raspadinha-game-iframe {
    max-height: 100vh;
    overflow: hidden;
}

/* Estilos do header */
.game-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: rgba(0, 0, 0, 0.8);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.close_full {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    padding: 0.5rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
}

.close_full:hover {
    background: rgba(255, 0, 0, 0.2);
    transform: scale(1.05);
}

/* Estilos do jogo da raspadinha integrado */
#raspadinha-game-container {
    height: 100%;
    width: 100%;
    min-height: 0;
    max-height: 100vh;
    padding: 10px 15px;
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
    overflow: hidden;
    position: relative;
}

/* Seção de título da raspadinha */
.raspadinha-title-section {
    text-align: center;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    margin-bottom: 0.75rem;
    box-sizing: border-box;
    overflow: hidden;
    flex-shrink: 0;
}

.raspadinha-name {
    font-size: 1.25rem;
    font-weight: bold;
    color: var(--primary-color);
    margin: 0 0 0.25rem 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    line-height: 1.2;
}

.raspadinha-subtitle {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
    opacity: 0.9;
    font-weight: 500;
    line-height: 1.3;
}

.raspadinha-main-area {
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    justify-content: center;
    gap: 20px;
    max-width: 1200px;
    width: 100%;
    flex: 1 1 auto;
    padding: 0 10px;
    box-sizing: border-box;
    overflow: hidden;
    min-height: 0;
    max-height: 100%;
}

.raspadinha-game-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    flex: 1 1 auto;
    max-width: 500px;
    min-width: 0;
    width: 100%;
    box-sizing: border-box;
    overflow: hidden;
    min-height: 0;
}

/* Container de raspagem integrado */
#scratch-container {
    position: relative;
    width: 100%;
    max-width: 100%;
    max-height: 100%;
    aspect-ratio: 1 / 1;
    margin: 0 auto;
    border-radius: 20px;
    user-select: none;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    box-sizing: border-box;
    overflow: hidden;
}

/* Grid de prêmios (background) */
.prizes-grid {
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

.prizes-grid > div {
    background: rgba(0, 0, 0, 0.7);
    border-radius: 15px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    font-weight: 600;
    font-size: 0.85rem;
    border: 2px solid #444;
    box-shadow: 
        inset 2px 2px 5px rgba(255, 255, 255, 0.1),
        inset -2px -2px 5px rgba(0, 0, 0, 0.5),
        0 4px 10px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
}

.prizes-grid img {
    height: 48px;
    margin-top: 50px;
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
.prizes-grid > div.winner {
    background: linear-gradient(145deg, #3a2f0a, #2a1f00);
    border: 2px solid #ffd700;
    box-shadow: 
        inset 2px 2px 5px rgba(255, 215, 0, 0.2),
        inset -2px -2px 5px rgba(0, 0, 0, 0.7),
        0 0 15px rgba(255, 215, 0, 0.4),
        0 4px 10px rgba(0, 0, 0, 0.3);
    animation: winnerPulse 1.5s ease-in-out infinite alternate;
}

.prizes-grid > div.winner .prize-text {
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

/* Canvas de raspagem */
.scratch-canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 20px;
    z-index: 10;
    touch-action: none;
    cursor: pointer;
    user-select: none;
    background: rgba(0,0,0,0.1);
    opacity: 1;
    visibility: visible;
}

/* Overlay de compra inicial */
.purchase-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 30;
    border-radius: 20px;
}

.purchase-content {
    text-align: center;
    color: white;
}

.btn-comprar-raspar {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border: none;
    border-radius: 15px;
    padding: 15px 30px;
    color: black;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0, 255, 136, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 200px;
}

.btn-comprar-raspar:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0, 255, 136, 0.6);
}

/* Controles da raspadinha */
.raspadinha-controls {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    width: 100%;
    max-width: 400px;
    flex: 1 1 auto;
    min-width: 0;
    padding: 15px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    height: fit-content;
    max-height: 100%;
    box-sizing: border-box;
    overflow: hidden;
}



.action-buttons {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    width: 100%;
    box-sizing: border-box;
    overflow: hidden;
}

.play-btn, .auto-btn, .reveal-btn {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border: none;
    border-radius: 10px;
    padding: 12px 16px;
    color: white;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 255, 136, 0.3);
    width: 100%;
    min-width: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 48px;
}

.price-tag {
    background: rgba(0, 0, 0, 0.3);
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: bold;
    color: white;
}

.play-btn.turbo {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.auto-btn {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
}

.reveal-btn {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}

.play-btn:hover, .auto-btn:hover, .reveal-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 255, 136, 0.4);
}

.play-btn.turbo:hover, .reveal-btn:hover {
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
}

.auto-btn:hover {
    box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
}

/* Mensagem de resultado */
.result-message {
    margin-top: 1rem;
    font-weight: 700;
    text-align: center;
    min-height: 1.5em;
    display: none;
    font-size: 18px;
}

.result-message.show {
    display: block;
}

/* Modais */
.auto-modal, .result-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.auto-modal-content, .result-modal-content {
    background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
    border-radius: 20px;
    padding: 30px;
    max-width: 450px;
    width: 90%;
    text-align: center;
    color: white;
    position: relative;
    border: 2px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
}

.result-close-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.result-close-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

.result-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.result-icon {
    font-size: 4rem;
    margin-bottom: 10px;
}

.result-prize-image {
    margin: 20px 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80px;
}

.result-prize-image img {
    max-width: 120px;
    max-height: 120px;
    object-fit: contain;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.result-text {
    font-size: 18px;
    font-weight: 600;
    margin: 15px 0;
    line-height: 1.4;
}

.auto-options {
    margin: 20px 0;
}

.input-group {
    margin: 15px 0;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
    color: rgba(255, 255, 255, 0.8);
}

.input-group input[type="number"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #333;
    border-radius: 8px;
    background: #2a2a2a;
    color: white;
}

.auto-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 20px;
}

.cancel-btn, .confirm-btn, .close-result-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
}

.cancel-btn {
    background: #6b7280;
    color: white;
}

.confirm-btn, .close-result-btn {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: black;
}

/* Estilização dos cards de raspadinhas */
.raspadinha-overlay {
    position: absolute;
    top: 8px;
    left: 8px;
    right: 8px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    z-index: 2;
}

.price-badge {
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
    text-align: center;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.price-badge.normal {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: black;
}

.price-badge.turbo {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.plays-badge {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 2px 6px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: bold;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Estilo para os cards de raspadinha */
.s3HXA {
    position: relative;
    overflow: hidden;
}

.s3HXA:hover .raspadinha-overlay {
    opacity: 0.9;
}

.s3HXA .g-hw5 {
    position: relative;
}

/* Estados do jogo */
.game-loading {
    pointer-events: none;
    opacity: 0.7;
}

.game-finished .scratch-canvas {
    pointer-events: none;
}

/* Canvas de Confete */
.confetti-canvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
    display: none;
}

/* Top Ganhos Desktop */
.top-ganhos {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    margin-top: 1rem;
    overflow: hidden;
    width: 100%;
    box-sizing: border-box;
    flex-shrink: 0;
    max-height: 250px;
}

.top-ganhos-header {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.top-ganhos-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary-color);
    font-weight: bold;
    font-size: 1rem;
    text-transform: uppercase;
}

.top-ganhos-title i {
    font-size: 1.1rem;
    color: #ffd700;
}

.top-ganhos-content {
    padding: 0.75rem;
}

.top-ganhos-container {
    max-height: 120px;
    overflow: hidden;
    position: relative;
}

.top-ganhos-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    transition: opacity 0.5s ease-in-out;
}

.top-ganhos-item {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 0.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.top-ganhos-item:hover {
    background: rgba(255, 255, 255, 0.1);
}

.winner-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.winner-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: black;
    font-weight: bold;
    font-size: 0.75rem;
    flex-shrink: 0;
}

.winner-details {
    flex: 1;
    min-width: 0;
}

.winner-name {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.2;
}

.game-name {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.6);
    font-weight: 400;
}

.winner-amount {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.25rem;
}

.amount-won {
    color: var(--primary-color);
    font-weight: bold;
    font-size: 0.8rem;
}

.time-ago {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.7rem;
    font-weight: 400;
}

.no-winners-message {
    text-align: center;
    padding: 1rem;
    color: rgba(255, 255, 255, 0.6);
}

.no-winners-message div {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.3rem;
}

.no-winners-message small {
    font-size: 0.75rem;
    opacity: 0.7;
}

/* Media query específica para tablet - garantir visibilidade dos botões */
@media (min-width: 769px) and (max-width: 1024px) {
    .raspadinha-controls {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    .action-buttons {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        width: 100%;
    }
    
    .play-btn, .auto-btn, .reveal-btn, #auto-play-button {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
}

/* Responsividade */
@media (max-width: 1024px) {
    #raspadinha-game-container {
        padding: 10px;
        max-height: 100vh;
    }
    
    .raspadinha-main-area {
        flex-direction: column;
        align-items: center;
        gap: 15px;
        padding: 5px;
        overflow: hidden;
        max-height: calc(100vh - 120px);
    }
    
    .raspadinha-game-section {
        max-width: 100%;
        min-width: 0;
        width: 100%;
        overflow: hidden;
        flex: 1 1 auto;
        max-height: 50vh;
    }
    
    .raspadinha-controls {
        width: 100%;
        max-width: 400px;
        min-width: 0;
        overflow: visible;
        flex: 0 0 auto;
        display: flex !important;
        visibility: visible !important;
    }
    
    .action-buttons {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        display: flex !important;
        visibility: visible !important;
        width: 100%;
        gap: 10px;
        min-height: 50px;
    }
    
    .play-btn, .auto-btn, .reveal-btn, .auto-btn {
        width: auto;
        min-width: 140px;
        flex: 1;
        max-width: 200px;
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        min-height: 48px;
    }
    
    /* Garantir que o botão automático também apareça */
    #auto-play-button {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    .price-options {
        gap: 12px;
        max-width: 350px;
    }
    
    .btn-comprar-raspar {
        padding: 12px 20px;
        font-size: 16px;
        min-width: 160px;
        width: 100%;
        max-width: 300px;
    }
}

@media (max-width: 768px) {
    #raspadinha-game-container {
        padding: 8px;
        overflow: hidden;
        max-height: 100vh;
    }
    
    .raspadinha-main-area {
        gap: 10px;
        padding: 5px;
        overflow: hidden;
        max-height: calc(100vh - 100px);
    }
    
    .raspadinha-title-section {
        padding: 0.4rem 0.8rem;
        margin-bottom: 0.5rem;
        flex-shrink: 0;
    }
    
    .raspadinha-name {
        font-size: 1.1rem;
    }
    
    .raspadinha-subtitle {
        font-size: 0.7rem;
    }
    
    .raspadinha-game-section {
        max-width: 100%;
        width: 100%;
        max-height: 45vh;
        flex: 1 1 auto;
    }
    
    .raspadinha-controls {
        gap: 12px;
        width: 100%;
        max-width: 350px;
        padding: 12px;
        overflow: hidden;
        flex: 0 0 auto;
    }
    
    .price-options {
        flex-direction: column;
        gap: 8px;
        max-width: 300px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }
    
    .play-btn, .auto-btn, .reveal-btn {
        width: 100%;
        min-width: 100%;
        padding: 12px 16px;
        font-size: 13px;
    }
    
    .btn-comprar-raspar {
        padding: 12px 18px;
        font-size: 15px;
        min-width: 150px;
        width: 100%;
        max-width: 280px;
    }
    
    .info-title {
        font-size: 18px;
    }
    
    .info-description {
        font-size: 13px;
    }
}

/* Media query para telas com altura de 750px - esconder top ganhos */
@media (max-height: 750px) {
    .top-ganhos {
        display: none !important;
    }
    
    .raspadinha-controls {
        gap: 10px;
    }
}

/* Media query para telas com altura pequena */
@media (max-height: 700px) {
    #raspadinha-game-container {
        padding: 5px 10px;
    }
    
    .raspadinha-title-section {
        padding: 0.3rem 0.6rem;
        margin-bottom: 0.3rem;
    }
    
    .raspadinha-name {
        font-size: 1rem;
    }
    
    .raspadinha-subtitle {
        font-size: 0.65rem;
    }
    
    .raspadinha-main-area {
        gap: 8px;
        padding: 5px;
        max-height: calc(100vh - 60px);
        align-items: stretch;
    }
    
    .raspadinha-game-section {
        max-width: 100%;
        width: 100%;
    }
    
    .raspadinha-controls {
        padding: 10px;
        gap: 10px;
        height: auto;
    }
    
    .top-ganhos {
        display: none !important;
    }
}

/* Media query para telas muito pequenas em altura */
@media (max-height: 600px) {
    .raspadinha-title-section {
        padding: 0.25rem 0.5rem;
        margin-bottom: 0.25rem;
    }
    
    .raspadinha-name {
        font-size: 0.9rem;
    }
    
    .raspadinha-subtitle {
        font-size: 0.6rem;
    }
    
    .raspadinha-main-area {
        max-height: calc(100vh - 50px);
        align-items: stretch;
    }
    
    .raspadinha-game-section {
        max-width: 100%;
        width: 100%;
    }
    
    .raspadinha-controls {
        height: auto;
    }

    .top-ganhos {
        max-height: 120px;
        margin-top: 0.5rem;
    }
}

@media (max-width: 480px) {
    #raspadinha-game-container {
        padding: 5px;
        overflow: hidden;
        max-height: 100vh;
    }
    
    .raspadinha-main-area {
        gap: 8px;
        padding: 3px;
        overflow: hidden;
        max-height: calc(100vh - 80px);
    }
    
    .raspadinha-title-section {
        padding: 0.35rem 0.7rem;
        margin-bottom: 0.4rem;
        flex-shrink: 0;
    }
    
    .raspadinha-name {
        font-size: 1rem;
    }
    
    .raspadinha-subtitle {
        font-size: 0.65rem;
    }
    
    .raspadinha-game-section {
        max-width: 100%;
        width: 100%;
        max-height: 40vh;
        flex: 1 1 auto;
    }
    
    #scratch-container {
        max-width: min(300px, 80vw);
        width: 100%;
        max-height: min(300px, 40vh);
    }

    .raspadinha-controls {
        width: 100%;
        max-width: 100%;
        padding: 10px;
        overflow: hidden;
        flex: 0 0 auto;
    }
    
    .prizes-grid {
        gap: 6px;
        padding: 8px;
    }
    
    .prizes-grid > div {
        padding: 4px;
    }
    
    .prizes-grid img {
        width: 32px;
        height: 32px;
        margin-bottom: 2px;
    }
    
    .prize-text {
        font-size: 8px;
        line-height: 1.1;
    }
    
    .price-options {
        flex-direction: column;
        gap: 6px;
        max-width: 280px;
    }
    
    .price-option {
        padding: 10px 15px;
        min-width: 100px;
    }
    
    .btn-comprar-raspar {
        padding: 10px 16px;
        font-size: 14px;
        min-width: 140px;
        width: 100%;
        max-width: 250px;
    }
    
    .info-title {
        font-size: 16px;
    }
    
    .info-description {
        font-size: 12px;
    }
    
    .raspadinha-info {
        padding: 15px;
    }
}

/* Hidden class utility */
.hidden {
    display: none !important;
}
.flex.items-center.gap-2{
    color:black;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.8/dist/notiflix-aio-3.2.8.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-confetti@latest/dist/js-confetti.browser.js"></script>
<script>
// Detectar mobile e redirecionar para versão mobile
(function() {
    function isMobileDevice() {
        // Verificar User Agent
        const userAgent = navigator.userAgent || navigator.vendor || window.opera;
        const mobileRegex = /(android|iphone|ipad|ipod|mobile|blackberry|opera mini|windows phone|iemobile|webos|palm|symbian)/i;
        
        // Verificar largura da tela
        const screenWidth = window.innerWidth || document.documentElement.clientWidth || screen.width;
        
        // Verificar se é touch device
        const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        
        return mobileRegex.test(userAgent) || (screenWidth <= 768 && isTouchDevice);
    }
    
    // Se for mobile, redirecionar para versão mobile
    if (isMobileDevice()) {
        const currentPath = window.location.pathname;
        // Se não estiver já na rota mobile, redirecionar
        if (!currentPath.includes('/mobile')) {
            const raspadinhaId = currentPath.split('/').pop();
            window.location.href = `/raspadinha/${raspadinhaId}/mobile`;
            return;
        }
    }
})();

// Variáveis globais do jogo (integração com sistema existente)
let gameData = @json($raspadinha ?? []);
let container, canvas, ctx, prizesGrid, btnBuy, btnBuyTurbo, btnReveal, resultMsg, overlay;
let orderId = null;
let brushRadius = 55;
let isDrawing = false;
let scratchedPercentage = 0;
let isScratchEnabled = false;
let isGameActive = false;
let currentGameResult = null;
let gameState = 'purchase'; // purchase, scratch, completed

// Variáveis do confete
let confettiCanvas, confettiContext;
let confettiParticles = [];
let confettiAnimationId;

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

// Inicializar jogo quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    initializeGame();
    setupConfettiCanvas();
    initTopGanhosScroll();
    
    // Configurar Notiflix para aparecer na parte inferior
    if (typeof Notiflix !== 'undefined') {
        Notiflix.Notify.init({
            position: 'right-bottom',
            distance: '20px',
            zindex: 9999,
            timeout: 3000,
            showOnlyTheLastOne: true,
            clickToClose: true,
        });
    }
    
    // Verificar autenticação periodicamente
    setInterval(checkAuthStatus, 30000); // Verificar a cada 30 segundos
});

// Inicializar o jogo
function initializeGame() {
    
    // Verificar se os dados da raspadinha são válidos
    if (!gameData || !gameData.id) {
        if (typeof Notiflix !== 'undefined') {
            Notiflix.Notify.warning('Erro ao carregar dados da raspadinha. Redirecionando...');
        }
        setTimeout(() => {
            window.location.href = '{{ route("home") }}';
        }, 2000);
        return;
    }
    
    // Capturar elementos do DOM
    container = document.getElementById('scratch-container');
    canvas = document.getElementById('scratch-canvas');
    ctx = canvas.getContext('2d');
    prizesGrid = document.getElementById('prizes-grid');
    btnBuy = document.getElementById('btn-buy');
    btnBuyTurbo = document.getElementById('btn-buy-turbo');
    btnReveal = document.getElementById('btn-reveal');
    resultMsg = document.getElementById('result-msg');
    overlay = document.getElementById('btn-overlay');
    

    
    // Configurar canvas
    setupCanvas();
    showPurchaseScreen();
    
    // Event listeners para os botões (usar onclick para evitar conflitos)
    btnBuy.onclick = function() { handlePurchase(false); };
    btnBuyTurbo.onclick = function() { handlePurchase(true); };
    btnReveal.onclick = revelarTudo;
    document.getElementById('auto-play-button').onclick = openAutoModal;
    document.getElementById('btn-comprar-raspar').onclick = function() { handleComprarRaspar(); };
    
    window.addEventListener('resize', ajustarCanvas);
}

// Configurar canvas
function setupCanvas() {
    ajustarCanvas();
    addCanvasListeners();
}

// Ajustar canvas ao tamanho do container
function ajustarCanvas() {
    if (!container || !canvas) return;
    
    const size = container.clientWidth;
    canvas.width = size;
    canvas.height = size;
    
    // Garantir que o contexto seja resetado corretamente
    ctx.globalCompositeOperation = 'source-over';
    ctx.fillStyle = 'rgba(0,0,0,1)';
    
    if (gameState === 'scratch') {
        drawScratchLayer();
    }
}

// Adicionar event listeners ao canvas
function addCanvasListeners() {
    canvas.addEventListener('mousedown', handleStart);
    canvas.addEventListener('mousemove', handleMove);
    canvas.addEventListener('mouseup', handleEnd);
    canvas.addEventListener('mouseleave', handleEnd);
    canvas.addEventListener('touchstart', handleStart, {passive: false});
    canvas.addEventListener('touchmove', handleMove, {passive: false});
    canvas.addEventListener('touchend', handleEnd);
    canvas.addEventListener('touchcancel', handleEnd);
}

// Desenhar camada de raspagem
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
        
        // Adicionar texto de fallback
        ctx.fillStyle = '#ffffff';
        ctx.font = 'bold 24px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('RASPADINHA', canvas.width / 2, canvas.height / 2 - 20);
        ctx.font = '16px Arial';
        ctx.fillText('Toque para raspar', canvas.width / 2, canvas.height / 2 + 20);
    }
}

// Funções de raspagem
function handleStart(e) {
    if (!isScratchEnabled) return;
    
    e.preventDefault();
    isDrawing = true;
    canvas.style.cursor = 'grabbing';
    
    const pos = getMousePos(e);
    scratch(pos.x, pos.y);
}

function handleMove(e) {
    if (!isDrawing || !isScratchEnabled) return;
    
    e.preventDefault();
    const pos = getMousePos(e);
    scratch(pos.x, pos.y);
    scratchedPercentage = getScratchedPercentage();
    
    // Auto-revelar quando 60% foi raspado
    if (scratchedPercentage > 60 && isScratchEnabled) {
        autoFinishScratch();
    }
}

function handleEnd() {
    isDrawing = false;
    canvas.style.cursor = 'grab';
}

function getMousePos(e) {
    const rect = canvas.getBoundingClientRect();
    if (e.touches) {
        return {
            x: e.touches[0].clientX - rect.left,
            y: e.touches[0].clientY - rect.top
        };
    } else {
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
    }
}

function scratch(x, y) {
    if (!isScratchEnabled) return;
    ctx.globalCompositeOperation = 'destination-out';
    ctx.fillStyle = 'rgba(0,0,0,1)';
    ctx.beginPath();
    ctx.arc(x, y, brushRadius, 0, Math.PI * 2);
    ctx.fill();
}

function getScratchedPercentage() {
    if (!ctx || !canvas) return 0;
    
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const pixels = imageData.data;
    let transparentPixels = 0;
    
    for (let i = 3; i < pixels.length; i += 4) {
        if (pixels[i] === 0) transparentPixels++;
    }
    return (transparentPixels / (canvas.width * canvas.height)) * 100;
}

// Mostrar tela de compra
function showPurchaseScreen() {
    gameState = 'purchase';
    if (overlay) overlay.style.display = 'flex';
    if (prizesGrid) prizesGrid.style.display = 'none';
    if (canvas) {
        canvas.style.display = 'block';
        // Mostrar imagem da raspadinha de fundo
        drawScratchLayer();
    }
    if (btnReveal) btnReveal.classList.add('hidden');
    
    orderId = null;
    scratchedPercentage = 0;
    isScratchEnabled = false;
    isGameActive = false;
    isDrawing = false;
}

// Nova função para comprar e raspar
function handleComprarRaspar() {
    // Verificar se ainda há um jogo ativo
    if (gameState === 'scratch' && isGameActive && currentGameResult) {
        mostrarMensagemErro('Raspe a cartela atual antes de comprar uma nova!');
        return;
    }
    
    const button = document.getElementById('btn-comprar-raspar');
    
    // Desabilitar botão durante a compra
    button.disabled = true;
    
    const originalHTML = button.innerHTML;
    button.innerHTML = `
        <i class="fas fa-spinner fa-spin mr-2"></i>
        Comprando...
    `;
    
    // Fazer a compra (usando preço normal por padrão)
    handleFetchWithAuthCheck(`/raspadinha/${gameData.id}/play`, {
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
            currentGameResult = data;
            setupGameBackground(data);
            gameState = 'scratch';
            startScratchMode();
        } else {
            mostrarMensagemErro(data.message || 'Erro ao jogar');
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarMensagemErro('Erro de conexão');
        button.disabled = false;
        button.innerHTML = originalHTML;
    });
}

// Iniciar modo de raspagem
function startScratchMode() {
    gameState = 'scratch';
    if (overlay) overlay.style.display = 'none';
    if (prizesGrid) prizesGrid.style.display = 'grid';
    if (canvas) canvas.style.display = 'block';
    
    ajustarCanvas();
    drawScratchLayer();
    
    // Ocultar botões de compra e mostrar botão revelar
    if (btnBuy) {
        btnBuy.style.display = 'none';
        btnBuy.disabled = true;
    }
    if (btnBuyTurbo) {
        btnBuyTurbo.style.display = 'none';
        btnBuyTurbo.disabled = true;
    }
    // Ocultar botão automático durante a raspagem
    const autoBtn = document.getElementById('auto-play-button');
    if (autoBtn) {
        autoBtn.style.display = 'none';
        autoBtn.disabled = true;
    }
    if (btnReveal) {
        btnReveal.classList.remove('hidden');
        btnReveal.disabled = false;
    }
    
    isScratchEnabled = true;
    isGameActive = true;
    
    canvas.style.cursor = 'grab';
}

// Lidar com compra (integração com sistema Laravel)
function handlePurchase(isTurbo = false) {
    // Verificar se ainda há um jogo ativo (com canvas visível e prêmios na tela)
    if (gameState === 'scratch' && isGameActive && currentGameResult) {
        mostrarMensagemErro('Raspe a cartela atual antes de comprar uma nova!');
        return;
    }
    
    const button = isTurbo ? btnBuyTurbo : btnBuy;
    
    // Desabilitar todos os botões durante a compra
    disableAllButtons();
    
    const originalHTML = button.innerHTML;
    button.innerHTML = `
        <div class="flex items-center justify-center w-full" style="color:black;">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            <span>Gerando...</span>
        </div>
    `;
    
    hideResultMsg();
    if (prizesGrid) prizesGrid.innerHTML = '';
    if (prizesGrid) prizesGrid.style.display = 'none';
    if (overlay) overlay.style.display = 'none';
    
    handleFetchWithAuthCheck(`/raspadinha/${gameData.id}/play`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            is_turbo: isTurbo
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentGameResult = data;
            setupGameBackground(data);
            gameState = 'scratch';
            showScratchMode();
        } else {
            mostrarMensagemErro(data.message || 'Erro ao jogar');
            showPurchaseScreen();
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarMensagemErro('Erro de conexão');
        showPurchaseScreen();
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalHTML;
        // Não reabilitar outros botões aqui pois o jogo vai para modo scratch
    });
}

// Comprar e jogar (interface externa)
function purchaseAndPlay(isTurbo = false) {
    handlePurchase(isTurbo);
}

// Mostrar modo de raspagem
function showScratchMode() {
    gameState = 'scratch';
    if (overlay) overlay.style.display = 'none';
    if (prizesGrid) prizesGrid.style.display = 'grid';
    if (canvas) canvas.style.display = 'block';
    
    ajustarCanvas();
    drawScratchLayer();
    
    // Ocultar botões de compra e mostrar botão revelar
    if (btnBuy) {
        btnBuy.style.display = 'none';
        btnBuy.disabled = true;
    }
    if (btnBuyTurbo) {
        btnBuyTurbo.style.display = 'none';
        btnBuyTurbo.disabled = true;
    }
    // Ocultar botão automático durante a raspagem
    const autoBtn = document.getElementById('auto-play-button');
    if (autoBtn) {
        autoBtn.style.display = 'none';
        autoBtn.disabled = true;
    }
    if (btnReveal) {
        btnReveal.classList.remove('hidden');
        btnReveal.disabled = false;
    }
    
    isScratchEnabled = true;
    isGameActive = true;
    
    canvas.style.cursor = 'grab';
}

// Configurar fundo com produtos (integração com sistema de prêmios)
function setupGameBackground(data) {
    if (!prizesGrid) return;
    
    prizesGrid.innerHTML = '';
    
    const items = data.items || [];
    if (items.length === 0) {
        return;
    }
    
    // Usar o grid enviado pelo backend que já tem a lógica correta
    let gridData = data.grid || [];
    
    console.log('Setup Game Background - Data received:', {
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
    
    console.log('Grid setup completed. Winners count:', document.querySelectorAll('.prizes-grid > div.winner').length);
}

// Função buildCell para compatibilidade
function buildCell(prize) {
    return `
        <div data-item-id="${prize.id}">
            <img src="${prize.icone || prize.image_url}" alt="${prize.nome || prize.name}" />
            <span class="prize-text">${prize.valor > 0 ? 'R$ ' + prize.valor.toLocaleString('pt-BR', { minimumFractionDigits: 2 }) : (prize.nome || prize.name)}</span>
        </div>
    `;
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
            processGameResult(combinedData);
        } else {
            // Em caso de erro, ainda mostrar o resultado mas sem creditar
            processGameResult(gameData);
        }
    } catch (error) {
        // Em caso de erro, ainda mostrar o resultado mas sem creditar
        processGameResult(gameData);
    }
}

// Processar resultado do jogo
function processGameResult(data) {
    const jsConfetti = new JSConfetti();
    
    // Sempre mostrar modal de resultado
            setTimeout(() => {
        showResultModal(data);
    }, 1000);
    
    if (!data.is_winner || data.amount_won <= 0) {
        showResultMessage('<span class="text-red-400">Não foi dessa vez. 😢</span>');
        Notiflix.Notify.info('Não foi dessa vez. 😢');
    } else {
        // Usar o valor exato que foi ganho
        const prizeValue = data.amount_won;
        const prizeText = `R$ ${prizeValue.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
        showResultMessage(`<span class="text-green-400">🎉 Você ganhou ${prizeText}!</span>`);
        Notiflix.Notify.success(`🎉 Você ganhou ${prizeText}!`);
        
        // Mostrar confetes
        jsConfetti.addConfetti({
            emojis: ['🎉', '✨', '🎊', '🥳'],
            emojiSize: 20,
            confettiNumber: 200,
            confettiRadius: 6,
            confettiColors: ['#ff0a54', '#ff477e', '#ff85a1', '#fbb1b1', '#f9bec7']
        });
        
        startConfetti();
    }
    
    // Reset dos botões
    resetButtons();
    atualizarSaldoUsuario();
}

// Mostrar mensagem de resultado
function showResultMessage(html) {
    if (!resultMsg) return;
    
    resultMsg.innerHTML = html;
    resultMsg.style.display = 'block';
    resultMsg.classList.add('show');
}

// Esconder mensagem de resultado
function hideResultMsg() {
    if (!resultMsg) return;
    
    resultMsg.style.display = 'none';
    resultMsg.classList.remove('show');
    resultMsg.innerHTML = '';
}

// Resetar botões após jogo
function resetButtons() {
    // Resetar botão comprar e raspar para "Jogar Novamente"
    const btnComprarRaspar = document.getElementById('btn-comprar-raspar');
    if (btnComprarRaspar) {
        btnComprarRaspar.disabled = false;
        btnComprarRaspar.innerHTML = `
            <i class="fas fa-redo mr-2"></i>
            Jogar Novamente
        `;
        // Adicionar evento específico para jogar novamente
        btnComprarRaspar.onclick = function() {
            resetGameForNewPlay();
            handleComprarRaspar();
        };
    }
    
    if (btnBuy) {
        btnBuy.disabled = false;
        btnBuy.style.display = 'block';
        btnBuy.style.opacity = '1';
        btnBuy.style.pointerEvents = 'auto';
        btnBuy.innerHTML = `
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2">
                    <i class="fas fa-redo"></i>
                    <span>Novamente</span>
                </div>
                <span class="price-tag">R$ ${parseFloat(gameData.price).toFixed(2).replace('.', ',')}</span>
            </div>
        `;
        
        // Adicionar evento específico para jogar novamente
        btnBuy.onclick = function() {
            resetGameForNewPlay();
            handlePurchase(false);
        };
    }
    
    if (btnBuyTurbo) {
        btnBuyTurbo.disabled = false;
        btnBuyTurbo.style.display = 'block';
        btnBuyTurbo.style.opacity = '1';
        btnBuyTurbo.style.pointerEvents = 'auto';
        
        // Adicionar evento específico para jogar novamente
        btnBuyTurbo.onclick = function() {
            resetGameForNewPlay();
            handlePurchase(true);
        };
    }
    
    if (btnReveal) {
        btnReveal.disabled = false;
        btnReveal.classList.add('hidden');
        // Resetar HTML do botão revelar
        btnReveal.innerHTML = `
            <div class="flex items-center justify-center w-full" style="color:black;">
                <i class="fas fa-eye mr-2"></i>
                <span>Revelar Tudo</span>
            </div>
        `;
    }
    
    // Restaurar botão automático
    const autoBtn = document.getElementById('auto-play-button');
    if (autoBtn) {
        autoBtn.disabled = false;
        autoBtn.style.display = 'block';
        autoBtn.style.opacity = '1';
        autoBtn.style.pointerEvents = 'auto';
    }
}

// Resetar jogo para nova jogada
function resetGameForNewPlay() {
    gameState = 'purchase';
    currentGameResult = null;
    isGameActive = false;
    isScratchEnabled = false;
    scratchedPercentage = 0;
    isDrawing = false;
    
    // Resetar botão comprar e raspar para estado inicial
    const btnComprarRaspar = document.getElementById('btn-comprar-raspar');
    if (btnComprarRaspar) {
        btnComprarRaspar.disabled = false;
        btnComprarRaspar.innerHTML = `
            <i class="fas fa-gift mr-2"></i>
            Comprar e Raspar
        `;
    }
    
    // Resetar botões para estado inicial
    if (btnBuy) {
        btnBuy.disabled = false;
        btnBuy.style.display = 'block';
        btnBuy.style.opacity = '1';
        btnBuy.style.pointerEvents = 'auto';
        btnBuy.innerHTML = `
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2">
                    <i class="fas fa-gift"></i>
                    <span>Jogar</span>
                </div>
                <span class="price-tag">R$ ${parseFloat(gameData.price).toFixed(2).replace('.', ',')}</span>
            </div>
        `;
        btnBuy.onclick = function() { handlePurchase(false); };
    }
    
    if (btnBuyTurbo) {
        btnBuyTurbo.disabled = false;
        btnBuyTurbo.style.display = 'block';
        btnBuyTurbo.style.opacity = '1';
        btnBuyTurbo.style.pointerEvents = 'auto';
        btnBuyTurbo.innerHTML = `
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2">
                    <i class="fas fa-bolt"></i>
                    <span>Turbo</span>
                </div>
                <span class="price-tag">R$ ${parseFloat(gameData.turbo_price).toFixed(2).replace('.', ',')}</span>
            </div>
        `;
        btnBuyTurbo.onclick = function() { handlePurchase(true); };
    }
    
    // Restaurar botão automático
    const autoBtn = document.getElementById('auto-play-button');
    if (autoBtn) {
        autoBtn.disabled = false;
        autoBtn.style.display = 'block';
        autoBtn.style.opacity = '1';
        autoBtn.style.pointerEvents = 'auto';
    }
}

// Revelar tudo
function revelarTudo() {
    if (!isScratchEnabled || !currentGameResult) return;
    
    // Desabilitar botão revelar para evitar cliques múltiplos
    isScratchEnabled = false;
    btnReveal.disabled = true;
    btnReveal.innerHTML = `
        <div class="flex items-center justify-center w-full" style="color:black;">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            <span>Revelando...</span>
        </div>
    `;
    
    // Delay para mostrar o loading
    setTimeout(() => {
        // Revelar tudo
        if (ctx && canvas) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
        finishScratch();
    }, 1000); // 1 segundo de delay para mostrar o "revelando..."
}

// Modal de jogo automático
function openAutoModal() {
    document.getElementById('auto-modal').style.display = 'flex';
}

function closeAutoModal() {
    document.getElementById('auto-modal').style.display = 'none';
}

function startAutoGame() {
    const quantity = document.getElementById('auto-quantity').value;
    const isTurbo = document.getElementById('auto-turbo').checked;
    
    closeAutoModal();
    
    handleFetchWithAuthCheck(`/raspadinha/${gameData.id}/play-auto`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            quantity: parseInt(quantity),
            is_turbo: isTurbo
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAutoResult(data);
        } else {
            mostrarMensagemErro(data.message || 'Erro no jogo automático');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mostrarMensagemErro('Erro de conexão');
    });
}

function showAutoResult(data) {
    const modal = document.getElementById('result-modal');
    const content = document.getElementById('result-content');
    const title = document.getElementById('result-title');
    
    title.textContent = 'Resultado do Jogo Automático';
    
    content.innerHTML = `
        <div style="text-align: center;">
            <div style="margin: 20px 0;">
                <div style="font-size: 18px; margin: 10px 0;">
                    ${data.quantity} jogadas realizadas
                </div>
                <div style="font-size: 16px; margin: 10px 0;">
                    ${data.winners_count} vitórias
                </div>
            </div>
            <div style="background: rgba(0, 255, 136, 0.1); padding: 15px; border-radius: 10px; margin: 15px 0;">
                <div style="color: var(--primary-color); font-size: 20px; font-weight: bold;">
                    Total ganho: ${data.formatted_total_prize}
                </div>
            </div>
            <div style="font-size: 14px; opacity: 0.8;">
                Custo total: ${data.formatted_total_cost}
            </div>
        </div>
    `;
    
    modal.style.display = 'flex';
}

// Mostrar modal de resultado
function showResultModal(data) {
    const modal = document.getElementById('result-modal');
    const title = document.getElementById('result-title');
    const content = document.getElementById('result-content');
    const icon = document.getElementById('result-icon');
    const prizeImage = document.getElementById('result-prize-image');
    
    console.log('Dados do resultado para modal:', data); // Debug
    
    if (data.is_winner && data.amount_won > 0) {
        icon.innerHTML = '🎉';
        title.textContent = 'Parabéns! Você Ganhou!';
        
        // Buscar item premiado correto
        let prizedItem = null;
        let prizeImageSrc = null;
        let prizeName = '';
        
        // Verificar se temos informações do item premiado
        if (data.prize_info && data.prize_info.item_id) {
            prizedItem = data.items ? data.items.find(item => 
                item.id === data.prize_info.item_id
            ) : null;
            
            if (prizedItem) {
                prizeImageSrc = prizedItem.image_url;
                prizeName = prizedItem.name;
            }
        }
        
        // Se não encontrou o item, usar informações diretas do resultado
        if (!prizedItem && data.prize_info) {
            prizeName = data.prize_info.prize_name || data.prize_info.name || '';
            prizeImageSrc = data.prize_info.prize_image || data.prize_info.image_url;
        }
        
        // Mostrar imagem do prêmio
        if (prizeImageSrc) {
            prizeImage.innerHTML = `<img src="${prizeImageSrc}" alt="${prizeName}">`;
        } else {
            prizeImage.innerHTML = '<div style="font-size: 4rem;">💰</div>';
        }
        
        // Usar o valor exato que foi ganho
        const prizeValue = data.amount_won;
        const prizeText = `R$ ${prizeValue.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
        
        content.innerHTML = `
            <div style="color: var(--primary-color); font-size: 24px; font-weight: bold; margin: 15px 0;">
                ${prizeText}
            </div>
            <div style="font-size: 16px; opacity: 0.8;">
                ${prizeName || 'Prêmio em dinheiro'}
            </div>
        `;
    } else {
        icon.innerHTML = '😔';
        title.textContent = 'Não foi desta vez!';
        prizeImage.innerHTML = '<div style="font-size: 4rem;">🎯</div>';
        content.innerHTML = `
            <div style="color: #fbbf24; font-size: 20px; font-weight: bold; margin: 15px 0;">
                Tente novamente!
            </div>
            <div style="font-size: 16px; opacity: 0.8;">
                A sorte pode estar na próxima raspadinha
            </div>
        `;
    }
    
    modal.style.display = 'flex';
}

function closeResultModal() {
    document.getElementById('result-modal').style.display = 'none';
    stopConfetti();
    
    // Resetar estado para permitir novo jogo
    gameState = 'purchase';
    currentGameResult = null;
    isGameActive = false;
    isScratchEnabled = false;
    scratchedPercentage = 0;
    isDrawing = false;
    
    // Resetar botão comprar e raspar para estado inicial
    const btnComprarRaspar = document.getElementById('btn-comprar-raspar');
    if (btnComprarRaspar) {
        btnComprarRaspar.disabled = false;
        btnComprarRaspar.innerHTML = `
            <i class="fas fa-gift mr-2"></i>
            Comprar e Raspar
        `;
    }
    
    // Resetar completamente para novo jogo
    resetGameForNewPlay();
    showPurchaseScreen();
}

// Tela cheia
function toggleFullscreen() {
    const gameContainer = document.getElementById('frame_game');
    
    if (!document.fullscreenElement) {
        gameContainer.requestFullscreen().catch(err => {
            console.error('Erro ao entrar em tela cheia:', err);
        });
    } else {
        document.exitFullscreen();
    }
}

// Atualizar saldo do usuário
function atualizarSaldoUsuario() {
    // Implementar se necessário para atualizar saldo na interface
}

function updateBalance(newBalance) {
    // Implementar atualização do saldo na interface se necessário
    console.log('Saldo atualizado:', newBalance);
}

// Funções de mensagem
function mostrarMensagemErro(message) {
    if (typeof Notiflix !== 'undefined') {
        Notiflix.Notify.failure(message);
    } else {
        alert('Erro: ' + message);
    }
}

function mostrarMensagemSucesso(message) {
    if (typeof Notiflix !== 'undefined') {
        Notiflix.Notify.success(message);
    } else {
        alert('Sucesso: ' + message);
    }
}

// Configurar canvas de confete
function setupConfettiCanvas() {
    confettiCanvas = document.getElementById('confetti-canvas');
    if (!confettiCanvas) return;
    
    confettiContext = confettiCanvas.getContext('2d');
    resizeConfettiCanvas();
    window.addEventListener('resize', resizeConfettiCanvas);
}

function resizeConfettiCanvas() {
    if (!confettiCanvas) return;
    confettiCanvas.width = window.innerWidth;
    confettiCanvas.height = window.innerHeight;
}

// Classe para partícula de confete
class ConfettiParticle {
    constructor() {
        this.x = Math.random() * confettiCanvas.width;
        this.y = -10;
        this.vx = (Math.random() - 0.5) * 6;
        this.vy = Math.random() * 3 + 2;
        this.rotation = Math.random() * 360;
        this.rotationSpeed = (Math.random() - 0.5) * 10;
        this.size = Math.random() * 8 + 4;
        this.colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7', '#dda0dd', '#98d8c8', '#ff7675'];
        this.color = this.colors[Math.floor(Math.random() * this.colors.length)];
        this.gravity = 0.15;
        this.opacity = 1;
        this.fadeSpeed = Math.random() * 0.02 + 0.005;
    }
    
    update() {
        this.x += this.vx;
        this.y += this.vy;
        this.vy += this.gravity;
        this.rotation += this.rotationSpeed;
        this.opacity -= this.fadeSpeed;
        this.vx *= 0.99;
    }
    
    draw() {
        if (!confettiContext) return;
        confettiContext.save();
        confettiContext.globalAlpha = this.opacity;
        confettiContext.translate(this.x, this.y);
        confettiContext.rotate(this.rotation * Math.PI / 180);
        confettiContext.fillStyle = this.color;
        confettiContext.fillRect(-this.size/2, -this.size/2, this.size, this.size);
        confettiContext.restore();
    }
    
    isAlive() {
        return this.opacity > 0 && this.y < confettiCanvas.height + 50;
    }
}

// Iniciar animação de confete
function startConfetti() {
    if (!confettiCanvas) return;
    confettiCanvas.style.display = 'block';
    
    for (let i = 0; i < 150; i++) {
        confettiParticles.push(new ConfettiParticle());
    }
    
    animateConfetti();
    
    setTimeout(() => {
        stopConfetti();
    }, 5000);
}

function animateConfetti() {
    if (!confettiContext || !confettiCanvas) return;
    
    confettiContext.clearRect(0, 0, confettiCanvas.width, confettiCanvas.height);
    
    if (Math.random() < 0.3 && confettiParticles.length < 200) {
        for (let i = 0; i < 5; i++) {
            confettiParticles.push(new ConfettiParticle());
        }
    }
    
    for (let i = confettiParticles.length - 1; i >= 0; i--) {
        const particle = confettiParticles[i];
        particle.update();
        particle.draw();
        
        if (!particle.isAlive()) {
            confettiParticles.splice(i, 1);
        }
    }
    
    if (confettiParticles.length > 0) {
        confettiAnimationId = requestAnimationFrame(animateConfetti);
    } else {
        stopConfetti();
    }
}

function stopConfetti() {
    if (confettiAnimationId) {
        cancelAnimationFrame(confettiAnimationId);
        confettiAnimationId = null;
    }
    
    confettiParticles = [];
    if (confettiContext && confettiCanvas) {
    confettiContext.clearRect(0, 0, confettiCanvas.width, confettiCanvas.height);
    confettiCanvas.style.display = 'none';
}
}

// Alertas de ganhos simulados (compatibilidade com sistema existente)
const nomes = [
    "Ana", "Carlos", "João", "Maria", "Luiz", "Paula", "Pedro", "Fernanda", "Roberto", 
    "Juliana", "Lucas", "Camila", "Paulo", "André", "Carla", "Rafael", "Larissa", "Bruno", 
    "Tatiane", "Ricardo", "Jéssica", "Marcos", "Aline", "Otávio", "Maruam", "Stela", "Nayara", "Robertinha", "Marcelo", "Lúcio", "Felipe"
];

function gerarValorAleatorio() {
    const valor = Math.floor(Math.random() * (5000 / 100)) * 100 + 100;
    return valor;
}

function escolherNomeAleatorio() {
    const index = Math.floor(Math.random() * nomes.length);
    return nomes[index];
}

function emitirAlerta() {
    const nome = escolherNomeAleatorio();
    const valor = gerarValorAleatorio();
    if (typeof Notiflix !== 'undefined') {
        Notiflix.Notify.info(`${nome} ganhou R$ ${valor.toLocaleString('pt-BR')}`);
    }
}

function iniciarAlertas() {
    function emitirAlertaComIntervalo() {
        const tempoAleatorio = Math.floor(Math.random() * (40000 - 15000 + 1)) + 15000;
        setTimeout(emitirAlerta, tempoAleatorio);
        setTimeout(emitirAlertaComIntervalo, tempoAleatorio);
    }
    emitirAlertaComIntervalo();
}

// Funções de controle de botões
function disableAllButtons() {
    if (btnBuy) {
        btnBuy.disabled = true;
        btnBuy.style.opacity = '0.5';
        btnBuy.style.pointerEvents = 'none';
    }
    if (btnBuyTurbo) {
        btnBuyTurbo.disabled = true;
        btnBuyTurbo.style.opacity = '0.5';
        btnBuyTurbo.style.pointerEvents = 'none';
    }
    if (btnReveal) {
        btnReveal.disabled = true;
    }
}

function enableAllButtons() {
    if (btnBuy) {
        btnBuy.disabled = false;
        btnBuy.style.opacity = '1';
        btnBuy.style.pointerEvents = 'auto';
    }
    if (btnBuyTurbo) {
        btnBuyTurbo.disabled = false;
        btnBuyTurbo.style.opacity = '1';
        btnBuyTurbo.style.pointerEvents = 'auto';
    }
    if (btnReveal) {
        btnReveal.disabled = false;
    }
}

// Iniciar alertas de ganhos
iniciarAlertas();

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
        console.warn('Erro ao verificar autenticação:', error);
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
        window.location.href = '{{ route("home") }}';
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

// Função para abrir uma raspadinha
function openRaspadinha(raspadinhaId) {
    // Verificar se o usuário está logado
    if (!@json(Auth::check())) {
        if (typeof Notiflix !== 'undefined') {
            Notiflix.Notify.warning('Você precisa fazer login para jogar raspadinhas.');
        } else {
            alert('Você precisa fazer login para jogar raspadinhas.');
        }
        
        // Redirecionar para página de login ou modal de login
        setTimeout(() => {
            window.location.href = '{{ route("home") }}';
        }, 1500);
        return;
    }
    
    // Redirecionar para a raspadinha
    window.location.href = `/raspadinha/${raspadinhaId}`;
}

// Função para inicializar o scroll automático do top ganhos
function initTopGanhosScroll() {
    const container = document.getElementById('top-ganhos-container');
    const wrapper = document.getElementById('top-ganhos-wrapper');
    if (!container || !wrapper) return;
    
    const items = wrapper.querySelectorAll('.top-ganhos-item');
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
    setInterval(moveToNext, 5000); // Trocar a cada 5 segundos
}
</script>
@endsection 