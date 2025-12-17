<div id="surpresa-mode-wrapper" style="margin-bottom: 20px;">
    <div class="SM-j1">
                <div data-v-25a087dc="" class="recommended_title">
                    <h2 data-v-debf714a="" data-v-25a087dc="" class="title flex items-center justify-center">
                    {!! $homeSections->getSectionTitle('custom_title_modo_surpresa', __('game.surprise_mode_title')) !!}
                    </h2>
                </div>
    </div>
    
    <div class="surpresa-roulette-section">
        <div class="surpresa-roulette-area">
            <div class="surpresa-roulette-track" id="surpresa-track">
                <div class="surpresa-games-strip" id="surpresa-games-strip">
                    <!-- Jogos serão inseridos aqui dinamicamente -->
                </div>
            </div>
        </div>
        
        <div class="surpresa-spin-button-container">
            <button id="surpresa-spin-btn" class="surpresa-spin-button">
                <span class="surpresa-spin-icon">
                    <svg height="1em" viewBox="0 0 95 95" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M59.4,10.9c-3.7-1.2-7.7-1.9-11.9-1.9v11.9c2.9,0,5.6.5,8.2,1.3l3.7-11.3ZM69,31.9l9.6-7c-2.4-3.3-5.2-6.1-8.5-8.5l-7,9.6c2.3,1.6,4.3,3.6,5.9,5.9ZM69,63.1l9.6,7c2.4-3.2,4.2-6.8,5.5-10.7l-11.3-3.7c-.8,2.7-2.1,5.2-3.8,7.4ZM35.6,84.1c3.8,1.2,7.8,1.9,11.9,1.9v-11.9c-2.9,0-5.6-.5-8.2-1.3l-3.7,11.3ZM26,63.1l-9.6,7c2.4,3.3,5.2,6.1,8.5,8.5l7-9.6c-2.3-1.6-4.3-3.6-5.9-5.9ZM20.9,47.5h-11.9c0,4.2.7,8.2,1.9,11.9l11.3-3.7c-.9-2.6-1.3-5.3-1.3-8.2ZM26,31.9l-9.6-7c-2.4,3.2-4.2,6.8-5.5,10.7l11.3,3.7c.9-2.7,2.1-5.2,3.8-7.4ZM39.3,22.2l-3.7-11.3c-3.9,1.3-7.5,3.1-10.7,5.5l7,9.6c2.2-1.7,4.7-2.9,7.4-3.8ZM74.1,47.5h11.9c0-4.2-.7-8.2-1.9-11.9l-11.3,3.7c.9,2.6,1.3,5.3,1.3,8.2ZM55.7,72.8l3.7,11.3c3.9-1.3,7.5-3.1,10.7-5.5l-7-9.6c-2.2,1.7-4.7,2.9-7.4,3.8Z" fill="currentColor" opacity="0.7"></path>
                        <path d="M47.5,0C21.3,0,0,21.3,0,47.5s21.3,47.5,47.5,47.5,47.5-21.3,47.5-47.5S73.7,0,47.5,0ZM47.5,88.7c-22.7,0-41.2-18.5-41.2-41.2S24.8,6.3,47.5,6.3s41.2,18.5,41.2,41.2-18.5,41.2-41.2,41.2ZM23.6,47.5c0,13.2,10.7,23.9,23.9,23.9s23.9-10.7,23.9-23.9-10.7-23.9-23.9-23.9-23.9,10.7-23.9,23.9ZM34,44.6c.6,0,1.1.2,1.5.4,1.4.9,3.1,1.1,4.8,1.1h1.6c.5-2.1,2.1-3.7,4.2-4.2v-1.6c0-1.7-.3-3.4-1.1-4.8-.3-.4-.4-1-.4-1.5,0-1.6,1.3-2.9,2.9-2.9s2.9,1.3,2.9,2.9-.2,1.1-.4,1.5c-.9,1.4-1.1,3.1-1.1,4.8v1.6c2.1.5,3.7,2.1,4.2,4.2h1.6c1.7,0,3.4-.3,4.8-1.1.4-.3,1-.4,1.5-.4,1.6,0,2.9,1.3,2.9,2.9s-1.3,2.9-2.9,2.9-1.1-.2-1.5-.4c-1.4-.9-3.1-1.1-4.8-1.1h-1.6c-.5,2.1-2.1,3.7-4.2,4.2v1.6c0,1.7.3,3.4,1.1,4.8.3.4.4,1,.4,1.5,0,1.6-1.3,2.9-2.9,2.9s-2.9-1.3-2.9-2.9.2-1.1.4-1.5c.9-1.4,1.1-3.1,1.1-4.8v-1.6c-2.1-.5-3.7-2.1-4.2-4.2h-1.6c-1.7,0-3.4.3-4.8,1.1-.4.3-1,.4-1.5.4-1.6,0-2.9-1.3-2.9-2.9s1.3-2.9,2.9-2.9Z" fill="currentColor"></path>
                    </svg>
                </span>
                <span class="surpresa-spin-text">{{ __('game.surprise_mode_spin') }}</span>
            </button>
            
            <button id="surpresa-play-btn" class="surpresa-play-button" style="display: none;">
                <span class="surpresa-play-icon">
                    <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" fill="currentColor"></path>
                    </svg>
                </span>
                <span class="surpresa-play-text">{{ __('game.surprise_mode_play') }}</span>
            </button>
        </div>
    </div>
</div>

<style>
/* Reset e container principal */
#surpresa-mode-wrapper {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0;
}

/* Header do modo surpresa */
.surpresa-header {
    width: 100%;
    margin-bottom: 1rem;
}

.surpresa-title-container {
    position: relative;
    width: 100%;
}

.surpresa-title-text {
    color: white;
    font-size: 1.125rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Seção da roleta */
.surpresa-roulette-section {
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
}

/* Área da roleta */
.surpresa-roulette-area {
    position: relative;
    padding: 2rem 1rem;
    overflow: hidden;
}

/* Track da roleta */
.surpresa-roulette-track {
    position: relative;
    width: 100%;
    height: 160px;
    border-radius: 0.5rem;
    /* Garantir que não corte o badge */
    overflow: visible;
    padding-top: 20px; /* Espaço para o badge */
}

/* Strip dos jogos */
.surpresa-games-strip {
    display: flex;
    gap: 1rem;
    height: 100%;
    align-items: center;
    will-change: transform;
    transition: none;
    /* Iniciar completamente fora da tela */
    transform: translateX(-5000px);
    /* Otimizações de performance - simplificadas */
    transform-style: preserve-3d;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    /* Garantir que badges sejam visíveis */
    overflow: visible;
}

/* Efeito de blur durante alta velocidade - muito otimizado */
.surpresa-games-strip.high-speed {
    filter: blur(1px); /* Reduzido drasticamente de 3px para 1px */
    transform-origin: center;
    /* Usar GPU para blur */
    -webkit-filter: blur(1px);
}

.surpresa-games-strip.medium-speed {
    filter: blur(0.5px); /* Reduzido drasticamente de 2px para 0.5px */
    -webkit-filter: blur(0.5px);
}

/* Blur inicial para todos os jogos - muito otimizado */
.surpresa-games-strip.initial-blur {
    filter: none; /* Removido completamente para melhor performance */
    -webkit-filter: none;
}

/* Container individual do jogo */
.surpresa-game-item {
    flex-shrink: 0;
    width: 128px;
    height: 144px;
    position: relative;
    border-radius: 0.75rem;
    overflow: visible;
    transition: opacity 0.2s ease; /* Simplificado */
    opacity: 0.6;
    /* Blur individual removido para melhor performance */
    filter: none;
    -webkit-filter: none;
    /* Desabilitar cliques por padrão */
    pointer-events: none;
    /* Otimizações de performance */
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}

.surpresa-game-item:hover {
    transform: translateY(-2px) translateZ(0); /* Reduzido de -5px para -2px */
    opacity: 0.8;
}

/* Jogo destacado (sorteado) - otimizado */
.surpresa-game-highlighted {
    transform: scale(1.05) translateZ(0); /* Reduzido de 1.1 para 1.05 */
    opacity: 1 !important;
    z-index: 150;
    box-shadow: 0 0 10px var(--primary-color); /* Reduzido de 20px para 10px */
    border: 2px solid var(--primary-color);
    filter: none !important;
    -webkit-filter: none !important;
    pointer-events: auto !important;
    cursor: pointer;
    /* Otimização para destaque */
    will-change: transform;
    position: relative;
    overflow: visible !important;
}

.surpresa-game-highlighted:hover {
    transform: scale(1.03) translateZ(0); /* Reduzido de 1.08 para 1.03 */
}

/* Jogos não sorteados (quando há destaque) - muito otimizado */
.surpresa-games-strip.has-winner .surpresa-game-item:not(.surpresa-game-highlighted) {
    opacity: 0.3; /* Aumentado de 0.2 para 0.3 */
    filter: blur(0.5px); /* Reduzido drasticamente de 2px para 0.5px */
    -webkit-filter: blur(0.5px);
}

/* Wrapper da imagem do jogo */
.surpresa-game-wrapper {
    width: 100%;
    height: 100%;
    position: relative;
    border-radius: 0.75rem;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.1);
    /* Otimização de performance */
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    /* Garantir que não interfira com o badge */
    z-index: 1;
}

/* Link do jogo */
.surpresa-game-link {
    display: block;
    width: 100%;
    height: 100%;
    position: relative;
}

/* Container da imagem */
.surpresa-game-image-container {
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
    border-radius: 0.75rem;
}

/* Imagem do jogo - muito otimizada */
.surpresa-game-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: none; /* Removido transition para melhor performance */
    /* Otimizações de performance para imagens */
    image-rendering: optimizeSpeed;
    image-rendering: -webkit-optimize-contrast;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}

.surpresa-game-item:hover .surpresa-game-image {
    transform: scale(1.02) translateZ(0); /* Reduzido de 1.05 para 1.02 */
}

/* Badge de sorteado */
.surpresa-winner-badge {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000; /* Z-index muito alto para garantir visibilidade */
    padding: 0.25rem 0.75rem;
    background: var(--primary-color);
    color: var(--text-btn-primary);
    font-size: 0.6rem;
    font-weight: bold;
    text-transform: uppercase;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3), 0 0 0 2px rgba(255, 255, 255, 0.1);
    white-space: nowrap;
    /* Garantir que não seja cortado */
    overflow: visible;
    /* Adicionar borda para melhor contraste */
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.surpresa-winner-badge svg {
    width: 0.75rem;
    height: 0.75rem;
    flex-shrink: 0;
}

/* Container do botão */
.surpresa-spin-button-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    position: relative;
    top: -1.5rem;
    z-index: 20;
}

/* Botão de girar (com ícone e texto) - otimizado */
.surpresa-spin-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: var(--primary-color);
    color: var(--text-btn-primary);
    font-weight: bold;
    font-size: 1rem;
    border-radius: 0.75rem;
    border: 1px solid var(--primary-color);
    cursor: pointer;
    transition: all 0.2s ease; /* Reduzido de 0.3s para 0.2s */
    box-shadow: 0 -1px 69px -8px var(--primary-color);
}

.surpresa-spin-button:hover {
    opacity: 0.9;
    transform: scale(1.02); /* Reduzido de 1.05 para 1.02 */
    box-shadow: 0 -1px 69px -8px var(--primary-color);
}

.surpresa-spin-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Botão de jogar - otimizado */
.surpresa-play-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: var(--primary-color);
    color: var(--text-btn-primary);
    font-weight: bold;
    font-size: 1rem;
    border-radius: 0.75rem;
    border: 1px solid var(--primary-color);
    cursor: pointer;
    transition: all 0.2s ease; /* Reduzido de 0.3s para 0.2s */
    box-shadow: 0 -1px 69px -8px var(--primary-color);
}

.surpresa-play-button:hover {
    opacity: 0.9;
    transform: scale(1.02); /* Reduzido de 1.05 para 1.02 */
    box-shadow: 0 -1px 69px -8px var(--primary-color);
}

/* Ícone do botão */
.surpresa-spin-icon {
    display: flex;
    align-items: center;
    font-size: 1rem;
    transition: transform 0.2s ease;
}

.surpresa-spin-text {
    font-size: 1rem;
    font-weight: bold;
}

.surpresa-play-icon {
    display: flex;
    align-items: center;
    font-size: 1rem;
}

.surpresa-play-text {
    font-size: 1rem;
}

/* Animação de rotação */
@keyframes surpresa-spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.surpresa-spinning .surpresa-spin-icon {
    animation: surpresa-spin 1s linear infinite;
}

/* Responsividade */
@media (max-width: 640px) {
    .surpresa-title-gradient {
        padding: 0.5rem 0.75rem;
    }
    
    .surpresa-title-text {
        font-size: 0.875rem;
    }
    
    .surpresa-roulette-area {
        padding: 1.5rem 0.5rem;
    }
    
    .surpresa-roulette-track {
        height: 120px;
    }
    
    .surpresa-game-item {
        width: 96px;
        height: 108px;
        /* Remover blur completamente no mobile para melhor performance */
        filter: none;
        -webkit-filter: none;
        /* Simplificar transições no mobile */
        transition: opacity 0.1s ease;
    }
    
    .surpresa-games-strip {
        gap: 0.75rem; /* 12px gap no mobile */
        /* Otimizações específicas para mobile */
        -webkit-overflow-scrolling: touch;
    }
    
    /* Remover blur completamente no mobile para melhor performance */
    .surpresa-games-strip.high-speed,
    .surpresa-games-strip.medium-speed,
    .surpresa-games-strip.initial-blur {
        filter: none;
        -webkit-filter: none;
    }
    
    .surpresa-games-strip.has-winner .surpresa-game-item:not(.surpresa-game-highlighted) {
        opacity: 0.4; /* Aumentado para melhor visibilidade no mobile */
        filter: none; /* Removido blur no mobile */
        -webkit-filter: none;
    }
    
    .surpresa-indicator-line {
        height: 60px;
    }
    
    .surpresa-spin-button {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        /* Simplificar transição no mobile */
        transition: opacity 0.1s ease;
    }
    
    .surpresa-spin-button:hover {
        transform: none; /* Remover scale no mobile */
    }
    
    .surpresa-play-button:hover {
        transform: none; /* Remover scale no mobile */
    }
    
    .surpresa-spin-button-container {
        top: -1rem;
    }
    
    /* Ajustar o jogo destacado no mobile */
    .surpresa-game-highlighted {
        transform: scale(1.02) translateZ(0); /* Menor escala no mobile */
    }
    
    .surpresa-game-highlighted:hover {
        transform: scale(1.02) translateZ(0); /* Manter escala consistente no mobile */
    }
    
    /* Diminuir badge sorteado no mobile */
    .surpresa-winner-badge {
        padding: 0.25rem 0.5rem;
        font-size: 0.5rem;
        top: -12px;
        z-index: 1000;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.2);
    }
    
    .surpresa-winner-badge svg {
        width: 0.625rem;
        height: 0.625rem;
    }
    
    /* Otimizações específicas para dispositivos móveis */
    .surpresa-game-image {
        image-rendering: optimizeSpeed;
        -webkit-image-rendering: optimizeSpeed;
        /* Desabilitar transições completamente no mobile */
        transition: none;
    }
    
    .surpresa-game-item:hover .surpresa-game-image {
        transform: none; /* Desabilitar hover no mobile */
    }
    
    /* Simplificar animações no mobile */
    .surpresa-game-item {
        transition: opacity 0.1s ease; /* Muito simplificado */
    }
    
    .surpresa-game-item:hover {
        transform: none; /* Remover hover no mobile */
        opacity: 0.8;
    }
}

/* Desabilitar cliques por padrão */
.surpresa-games-strip.clicks-disabled .surpresa-game-item {
    pointer-events: none !important;
    cursor: default !important;
}

/* Habilitar cliques apenas para vencedores */
.surpresa-games-strip .surpresa-game-item.winner-clickable {
    pointer-events: auto !important;
    cursor: pointer !important;
    margin-bottom: 20px;
}

/* Otimizações de performance para dispositivos lentos */
@media (max-width: 640px), (max-device-width: 640px) {
    .surpresa-games-strip {
        /* Usar transform2d no mobile para melhor performance */
        transform-style: flat;
        -webkit-transform-style: flat;
    }
    
    .surpresa-game-item {
        /* Reduzir efeitos visuais no mobile */
        transition: none; /* Remover todas as transições */
        will-change: auto;
    }
    
    .surpresa-game-item:hover {
        transform: none;
    }
    
    /* Desabilitar blur completamente em dispositivos lentos */
    .surpresa-games-strip.high-speed,
    .surpresa-games-strip.medium-speed,
    .surpresa-games-strip.initial-blur {
        filter: none !important;
        -webkit-filter: none !important;
    }
    
    .surpresa-game-item {
        filter: none !important;
        -webkit-filter: none !important;
    }
    
    .surpresa-games-strip.has-winner .surpresa-game-item:not(.surpresa-game-highlighted) {
        filter: none !important;
        -webkit-filter: none !important;
    }
}

/* Otimizações gerais de performance */
.surpresa-games-strip {
    /* Usar GPU acceleration mais eficiente */
    transform: translate3d(-5000px, 0, 0);
    will-change: transform;
}

.surpresa-game-image {
    /* Otimizar imagens */
    will-change: auto;
    contain: layout style paint;
}
</style>

<script>
// Aguardar DOM estar completamente carregado
document.addEventListener('DOMContentLoaded', function() {
    // Elementos principais
    const gamesStrip = document.getElementById('surpresa-games-strip');
    const spinButton = document.getElementById('surpresa-spin-btn');
    const playButton = document.getElementById('surpresa-play-btn');
    
    // Verificação de elementos essenciais
    if (!gamesStrip || !spinButton || !playButton) {
        console.error('Elementos não encontrados:', {
            gamesStrip: !!gamesStrip,
            spinButton: !!spinButton, 
            playButton: !!playButton
        });
        return;
    }
    
    // Variáveis de controle
    let isSpinning = false;
    let gamesList = [];
    let selectedGame = null;
    let isLoadingComplete = false;
    let isInitialized = false;
    let isPageVisible = true;
    let animationFrame = null;
    let performanceMode = false;
    
    // AbortController para cancelar requisições se necessário
    let loadController = null;
    
    // Detectar dispositivos com baixa performance
    function detectPerformanceMode() {
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        const isLowEnd = navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 2;
        const hasLowMemory = navigator.deviceMemory && navigator.deviceMemory <= 2;
        
        performanceMode = isMobile || isLowEnd || hasLowMemory;
        
        if (performanceMode) {
            document.documentElement.style.setProperty('--blur-amount', '1px');
            document.documentElement.style.setProperty('--animation-duration', '0.2s');
        }
    }
    
    // Detectar visibilidade da página
    function handleVisibilityChange() {
        isPageVisible = !document.hidden;
        
        if (!isPageVisible) {
            if (loadController) {
                loadController.abort();
            }
            if (animationFrame) {
                cancelAnimationFrame(animationFrame);
                animationFrame = null;
            }
        }
    }
    
    // Listener para mudança de visibilidade
    document.addEventListener('visibilitychange', handleVisibilityChange);
    
    // Função helper para calcular dimensões responsivas com cache
    let dimensionsCache = null;
    let lastWindowWidth = 0;
    
    function getResponsiveDimensions() {
        const currentWidth = window.innerWidth;
        
        if (dimensionsCache && lastWindowWidth === currentWidth) {
            return dimensionsCache;
        }
        
        try {
            const isMobile = currentWidth <= 640;
            let gameWidth, gap;
            
            gameWidth = isMobile ? 96 : 128;
            gap = isMobile ? 12 : 16;
            
            dimensionsCache = {
                gameWidth: gameWidth + gap,
                itemWidth: gameWidth,
                gap: gap,
                isMobile: isMobile
            };
            
            lastWindowWidth = currentWidth;
            return dimensionsCache;
        } catch (error) {
            const isMobile = currentWidth <= 640;
            return {
                gameWidth: isMobile ? 108 : 144,
                itemWidth: isMobile ? 96 : 128,
                gap: isMobile ? 12 : 16,
                isMobile: isMobile
            };
        }
    }
    
    // Função para carregar jogos - otimizada e sem delays
    async function loadGames() {
        try {
            if (loadController) {
                loadController.abort();
            }
            
            loadController = new AbortController();
            
            // Timeout mais rápido
            const timeoutId = setTimeout(() => loadController.abort(), 5000);
            
            const response = await fetch('/modo-surpresa/jogos-roleta?limite=50', {
                signal: loadController.signal,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });
            
            clearTimeout(timeoutId);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const data = await response.json();
            
            console.log('Resposta do servidor Modo Surpresa:', data);
            
            if (data.success && data.jogos && Array.isArray(data.jogos) && data.jogos.length > 0) {
                gamesList = data.jogos;
                console.log('Jogos carregados:', gamesList.length);
                isLoadingComplete = true;
                
                // Renderizar imediatamente
                renderGames();
                isInitialized = true;
                
                return true;
            } else {
                console.error('Dados inválidos:', {
                    success: data.success,
                    hasJogos: !!data.jogos,
                    isArray: Array.isArray(data.jogos),
                    length: data.jogos?.length,
                    message: data.message
                });
                throw new Error(data.message || 'Dados inválidos recebidos do servidor');
            }
            
        } catch (error) {
            console.error('Erro ao carregar jogos do Modo Surpresa:', error);
            
            // Mostrar mensagem de erro visualmente
            if (gamesStrip) {
                gamesStrip.innerHTML = `
                    <div style="padding: 2rem; text-align: center; color: #fff;">
                        <p>Erro ao carregar jogos: ${error.message}</p>
                        <button onclick="location.reload()" style="margin-top: 1rem; padding: 0.5rem 1rem; background: var(--primary-color); color: #fff; border: none; border-radius: 0.5rem; cursor: pointer;">
                            Tentar Novamente
                        </button>
                    </div>
                `;
            }
            
            return false;
        }
    }
    
    // Função para renderizar jogos - simplificada
    function renderGames() {
        if (!gamesStrip || !Array.isArray(gamesList) || gamesList.length === 0) {
            return;
        }
        
        try {
            // Limpar conteúdo anterior
            const range = document.createRange();
            range.selectNodeContents(gamesStrip);
            range.deleteContents();
            
            // Criar cópias para roleta
            const copies = performanceMode ? 3 : 4;
            const fragment = document.createDocumentFragment();
            
            for (let i = 0; i < copies; i++) {
                gamesList.forEach((game, index) => {
                    if (!game || !game.id) return;
                    
                    const gameElement = document.createElement('div');
                    gameElement.className = 'surpresa-game-item';
                    gameElement.dataset.gameId = game.id;
                    gameElement.dataset.index = (i * gamesList.length) + index;
                    gameElement.dataset.originalIndex = index;
                    
                    const imageSrc = game.image_url || game.image || '';
                    const gameName = (game.name || 'Jogo').replace(/['"<>&]/g, '');
                    
                    gameElement.innerHTML = `
                        <div class="surpresa-game-wrapper">
                            <a href="javascript:void(0);" onclick="OpenGame('games', '${game.id}');" class="surpresa-game-link">
                                <div class="surpresa-game-image-container">
                                    <img 
                                        src="${imageSrc}" 
                                        alt="${gameName}"
                                        class="surpresa-game-image"
                                        loading="lazy"
                                        onerror="this.style.opacity='0.3';"
                                    />
                                </div>
                            </a>
                        </div>
                    `;
                    
                    fragment.appendChild(gameElement);
                });
            }
            
            // Adicionar todos os elementos de uma vez
            gamesStrip.appendChild(fragment);
            
            // Aplicar estilos iniciais
            gamesStrip.classList.add('initial-blur');
            
            // Posicionar fora da tela e desabilitar cliques
            resetPositionOffScreen();
            disableAllGameClicks();
            
        } catch (error) {
            console.error('Erro ao renderizar jogos:', error);
        }
    }
    
    // Função para resetar posição fora da tela
    function resetPositionOffScreen() {
        if (!gamesStrip) return;
        
        try {
            gamesStrip.style.transition = 'none';
            gamesStrip.style.transform = 'translate3d(-5000px, 0, 0)';
            gamesStrip.offsetHeight; // Force reflow
        } catch (error) {
            // Erro silencioso
        }
    }
    
    // Função para desabilitar todos os cliques
    function disableAllGameClicks() {
        if (!gamesStrip) return;
        
        try {
            gamesStrip.classList.add('clicks-disabled');
        } catch (error) {
            // Erro silencioso
        }
    }
    
    // Função para habilitar clique apenas no jogo sorteado
    function enableWinnerClick(gameId) {
        if (!gamesStrip || !gameId) return;
        
        try {
            gamesStrip.classList.remove('clicks-disabled');
            
            const winnerElements = gamesStrip.querySelectorAll(`[data-game-id="${gameId}"]`);
            winnerElements.forEach(element => {
                if (element) {
                    element.classList.add('winner-clickable');
                }
            });
        } catch (error) {
            // Erro silencioso
        }
    }
    
    // Função para limpar destaques
    function clearHighlights() {
        if (!gamesStrip) return;
        
        try {
            gamesStrip.className = 'surpresa-games-strip initial-blur clicks-disabled';
            
            if (playButton) {
                playButton.style.display = 'none';
            }
            
            const badges = gamesStrip.querySelectorAll('.surpresa-winner-badge');
            badges.forEach(badge => badge.remove());
            
        } catch (error) {
            // Erro silencioso
        }
    }
    
    // Função para destacar jogo vencedor
    function highlightWinner(gameId) {
        if (!gamesStrip || !gameId) return;
        
        try {
            gamesStrip.classList.add('has-winner');
            gamesStrip.classList.remove('initial-blur');
            
            const winnerElements = gamesStrip.querySelectorAll(`[data-game-id="${gameId}"]`);
            winnerElements.forEach(element => {
                if (element && !element.classList.contains('surpresa-game-highlighted')) {
                    element.classList.add('surpresa-game-highlighted');
                    
                    if (!element.querySelector('.surpresa-winner-badge')) {
                        const badge = document.createElement('div');
                        badge.className = 'surpresa-winner-badge';
                        badge.innerHTML = `
                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M398.957 153.438C396.339 145.339 389.155 139.586 380.655 138.82L265.205 128.337L219.552 21.4828C216.186 13.6518 208.52 8.5827 200.002 8.5827C191.484 8.5827 183.818 13.6518 180.452 21.5011L134.8 128.337L19.3303 138.82C10.8462 139.604 3.68046 145.339 1.04673 153.438C-1.58701 161.538 0.845308 170.422 7.26332 176.022L94.5306 252.556L68.7975 365.91C66.9145 374.245 70.1495 382.86 77.0649 387.859C80.7821 390.544 85.1309 391.912 89.5164 391.912C93.2976 391.912 97.0483 390.892 100.415 388.878L200.002 329.358L299.553 388.878C306.838 393.261 316.021 392.861 322.921 387.859C329.839 382.845 333.071 374.226 331.188 365.91L305.455 252.556L392.722 176.037C399.14 170.422 401.591 161.553 398.957 153.438Z" fill="currentColor"></path>
                            </svg>
                            {{ __('game.surprise_mode_winner') }}
                        `;
                        
                        element.insertBefore(badge, element.firstChild);
                    }
                }
            });
            
            enableWinnerClick(gameId);
            
            if (playButton) {
                playButton.style.display = 'flex';
                playButton.onclick = () => {
                    if (typeof OpenGame === 'function') {
                        const selectedGameId = selectedGame.id;
                        OpenGame('games', selectedGameId);
                    }
                };
            }
        } catch (error) {
            // Erro silencioso
        }
    }
    
    // Função principal de sorteio
    async function spinRoulette() {
        if (isSpinning || !Array.isArray(gamesList) || gamesList.length === 0) {
            return;
        }
        
        try {
            isSpinning = true;
            
            if (spinButton) {
                spinButton.disabled = true;
                spinButton.classList.add('surpresa-spinning');
            }
            
            clearHighlights();
            
            // Sortear jogo
            const randomIndex = Math.floor(Math.random() * gamesList.length);
            selectedGame = gamesList[randomIndex];
            
            if (!selectedGame || !selectedGame.id) {
                throw new Error('Jogo sorteado inválido');
            }
            
            // Obter dimensões
            const dimensions = getResponsiveDimensions();
            const containerWidth = gamesStrip.parentElement?.offsetWidth || window.innerWidth;
            const centerPosition = containerWidth / 2 - (dimensions.itemWidth / 2);
            
            // Calcular posição final
            const middleCopyStart = gamesList.length * Math.floor(gamesList.length > 6 ? 2 : 1);
            const targetIndex = middleCopyStart + randomIndex;
            const finalPosition = -(targetIndex * dimensions.gameWidth) + centerPosition;
            
            // Animação
            gamesStrip.classList.remove('initial-blur');
            
            if (!performanceMode) {
                gamesStrip.classList.add('high-speed');
            }
            
            // FASE 1: Movimento rápido
            gamesStrip.style.transition = 'transform 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            gamesStrip.style.transform = `translate3d(${finalPosition - 3000}px, 0, 0)`;
            
            await new Promise(resolve => setTimeout(resolve, 150));
            
            // FASE 2: Parada final
            gamesStrip.classList.remove('high-speed');
            gamesStrip.style.transition = 'transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            gamesStrip.style.transform = `translate3d(${finalPosition}px, 0, 0)`;
            
            await new Promise(resolve => setTimeout(resolve, 800));
            
            // Destacar vencedor
            highlightWinner(selectedGame.id);
            
        } catch (error) {
            clearHighlights();
        } finally {
            if (gamesStrip) {
                gamesStrip.classList.remove('high-speed', 'medium-speed');
            }
            
            isSpinning = false;
            if (spinButton) {
                spinButton.disabled = false;
                spinButton.classList.remove('surpresa-spinning');
            }
        }
    }
    
    // Event listener do botão com debounce
    let spinDebounce = null;
    if (spinButton) {
        spinButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (spinDebounce) return;
            
            spinDebounce = setTimeout(() => {
                spinDebounce = null;
                spinRoulette().catch(() => {});
            }, 200);
        });
    }
    
    // Listener para redimensionamento
    let resizeTimeout;
    window.addEventListener('resize', function() {
        dimensionsCache = null;
        
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            if (!isSpinning && selectedGame && gamesStrip && isPageVisible) {
                try {
                    const dimensions = getResponsiveDimensions();
                    const containerWidth = gamesStrip.parentElement?.offsetWidth || window.innerWidth;
                    const centerPosition = containerWidth / 2 - (dimensions.itemWidth / 2);
                    
                    const randomIndex = gamesList.findIndex(game => game.id === selectedGame.id);
                    if (randomIndex !== -1) {
                        const middleCopyStart = gamesList.length * Math.floor(gamesList.length > 6 ? 2 : 1);
                        const targetIndex = middleCopyStart + randomIndex;
                        const finalPosition = -(targetIndex * dimensions.gameWidth) + centerPosition;
                        
                        gamesStrip.style.transition = 'transform 0.3s ease';
                        gamesStrip.style.transform = `translate3d(${finalPosition}px, 0, 0)`;
                    }
                } catch (error) {
                    // Erro silencioso
                }
            }
        }, 500);
    });
    
    // Cleanup ao sair da página
    window.addEventListener('beforeunload', function() {
        if (loadController) {
            loadController.abort();
        }
        
        if (animationFrame) {
            cancelAnimationFrame(animationFrame);
        }
        if (resizeTimeout) {
            clearTimeout(resizeTimeout);
        }
        if (spinDebounce) {
            clearTimeout(spinDebounce);
        }
        
        document.removeEventListener('visibilitychange', handleVisibilityChange);
        
        gamesList = [];
        selectedGame = null;
        isSpinning = false;
        isInitialized = false;
        isLoadingComplete = false;
        dimensionsCache = null;
    });
    
    // Detectar modo de performance
    detectPerformanceMode();
    
    // Inicializar carregamento imediatamente
    loadGames().catch(() => {
        console.error('Falha ao carregar jogos iniciais');
    });
});
</script>