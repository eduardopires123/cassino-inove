/**
 * Sistema de Raspadinha - JavaScript para animações e lógica do jogo
 */

class RaspadinhaGame {
    constructor(raspadinhaData) {
        this.raspadinha = raspadinhaData;
        this.currentGame = null;
        this.isPlaying = false;
        this.scratchedItems = 0;
        this.gameResults = [];
        this.autoPlayInterval = null;
        this.turboMode = false;
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.setupGrid();
        this.updateAutoCost();
    }

    bindEvents() {
        // Eventos dos botões de jogo
        document.getElementById('play-single')?.addEventListener('click', () => this.playSingle());
        document.getElementById('play-auto')?.addEventListener('click', () => this.playAuto());
        
        // Eventos dos controles
        document.getElementById('auto_quantity')?.addEventListener('input', () => this.updateAutoCost());
        document.querySelectorAll('input[name="auto_mode"]').forEach(radio => {
            radio.addEventListener('change', () => this.updateAutoCost());
        });
        
        // Eventos dos itens da raspadinha para raspar manual
        this.setupScratchEvents();
        
        // Evento para resetar jogo
        document.addEventListener('click', (e) => {
            if (e.target.matches('[onclick*="resetGame"]')) {
                this.resetGame();
            }
        });
    }

    setupGrid() {
        const grid = document.getElementById('raspadinha-grid');
        if (!grid) return;

        // Adicionar efeitos de hover e animações
        const items = grid.querySelectorAll('.raspadinha-item');
        items.forEach((item, index) => {
            item.dataset.index = index;
            
            // Efeito de hover
            item.addEventListener('mouseenter', () => {
                if (!this.isPlaying && this.gameResults.length === 0) {
                    item.style.transform = 'scale(1.05) rotate(2deg)';
                }
            });
            
            item.addEventListener('mouseleave', () => {
                if (!this.isPlaying) {
                    item.style.transform = '';
                }
            });
        });
    }

    setupScratchEvents() {
        document.querySelectorAll('.raspadinha-item').forEach(item => {
            item.addEventListener('click', (e) => this.handleScratch(e));
        });
    }

    handleScratch(event) {
        if (!this.gameResults.length || this.isPlaying) return;
        
        const item = event.currentTarget;
        const index = parseInt(item.dataset.index);
        const overlay = item.querySelector('.scratch-overlay');
        
        if (!overlay.classList.contains('scratched')) {
            this.scratchItem(item, index);
        }
    }

    scratchItem(item, index, animated = true) {
        const overlay = item.querySelector('.scratch-overlay');
        const content = item.querySelector('.prize-text');
        
        if (this.gameResults[index]) {
            content.textContent = this.gameResults[index].name;
            
            if (animated) {
                // Adicionar animação de scratch
                overlay.style.background = 'linear-gradient(45deg, transparent 40%, #95a5a6 50%, transparent 60%)';
                overlay.style.backgroundSize = '20px 20px';
                overlay.style.animation = 'scratch-reveal 0.5s ease-out forwards';
                
                setTimeout(() => {
                    overlay.classList.add('scratched');
                    this.addScratchParticles(item);
                }, 500);
            } else {
                overlay.classList.add('scratched');
            }
        }
    }

    addScratchParticles(item) {
        // Criar partículas de "arranhão"
        for (let i = 0; i < 5; i++) {
            const particle = document.createElement('div');
            particle.className = 'scratch-particle';
            particle.style.cssText = `
                position: absolute;
                width: 4px;
                height: 4px;
                background: #7f8c8d;
                border-radius: 50%;
                pointer-events: none;
                animation: particle-fly 0.6s ease-out forwards;
                left: ${Math.random() * 100}%;
                top: ${Math.random() * 100}%;
            `;
            
            item.appendChild(particle);
            
            setTimeout(() => particle.remove(), 600);
        }
    }

    async playSingle() {
        if (this.isPlaying) return;
        
        const isTurbo = document.getElementById('single_turbo')?.checked || false;
        this.turboMode = isTurbo;
        
        try {
            this.isPlaying = true;
            this.showLoading();
            this.resetGame();
            
            const response = await this.makeRequest('/play', {
                is_turbo: isTurbo
            });
            
            this.hideLoading();
            
            if (response.success) {
                this.gameResults = response.results;
                this.updateBalance(response.new_balance);
                await this.startScratchAnimation(response);
            } else {
                this.showError(response.message);
            }
        } catch (error) {
            this.hideLoading();
            this.showError('Erro interno. Tente novamente.');
        } finally {
            this.isPlaying = false;
        }
    }

    async playAuto() {
        if (this.isPlaying) return;
        
        const quantity = parseInt(document.getElementById('auto_quantity')?.value) || 1;
        const isTurbo = document.getElementById('auto_turbo')?.checked || false;
        this.turboMode = isTurbo;
        
        if (quantity < 1 || quantity > 100) {
            this.showError('Quantidade deve ser entre 1 e 100');
            return;
        }
        
        try {
            this.isPlaying = true;
            this.showLoading();
            this.resetGame();
            
            const response = await this.makeRequest('/play-auto', {
                quantity: quantity,
                is_turbo: isTurbo
            });
            
            this.hideLoading();
            
            if (response.success) {
                this.updateBalance(response.new_balance);
                await this.showAutoResults(response);
            } else {
                this.showError(response.message);
            }
        } catch (error) {
            this.hideLoading();
            this.showError('Erro interno. Tente novamente.');
        } finally {
            this.isPlaying = false;
        }
    }

    async makeRequest(endpoint, data) {
        const response = await fetch(`/raspadinha/${this.raspadinha.id}${endpoint}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        
        return await response.json();
    }

    async startScratchAnimation(gameData) {
        const items = document.querySelectorAll('.raspadinha-item');
        this.scratchedItems = 0;
        const delay = this.turboMode ? 100 : 300;
        
        // Adicionar efeito de suspense antes de começar
        await this.showSuspenseEffect();
        
        // Revelar resultados com delay
        for (let i = 0; i < this.gameResults.length; i++) {
            await new Promise(resolve => {
                setTimeout(() => {
                    const item = items[i];
                    this.scratchItem(item, i, true);
                    this.scratchedItems++;
                    
                    // Adicionar efeito sonoro (se disponível)
                    this.playScratchSound();
                    
                    if (this.scratchedItems === 9) {
                        setTimeout(() => {
                            this.showResult(gameData);
                        }, 800);
                    }
                    
                    resolve();
                }, i * delay);
            });
        }
    }

    async showSuspenseEffect() {
        const grid = document.getElementById('raspadinha-grid');
        if (!grid) return;
        
        // Efeito de "preparando"
        grid.style.animation = 'pulse-glow 1s ease-in-out 2';
        
        return new Promise(resolve => {
            setTimeout(() => {
                grid.style.animation = '';
                resolve();
            }, 2000);
        });
    }

    showResult(gameData) {
        const resultDisplay = document.getElementById('result-display');
        if (!resultDisplay) return;
        
        const resultIcon = resultDisplay.querySelector('.result-icon i');
        const resultIconContainer = resultDisplay.querySelector('.result-icon');
        const resultTitle = resultDisplay.querySelector('.result-title');
        const resultText = resultDisplay.querySelector('#result-text');
        const resultValue = resultDisplay.querySelector('.result-value');
        
        // Configurar resultado
        if (gameData.is_winner) {
            resultIcon.className = 'fas fa-trophy';
            resultIconContainer.className = 'result-icon winner';
            resultTitle.textContent = 'Parabéns! Você ganhou!';
            resultText.textContent = gameData.formatted_prize;
            resultValue.className = 'result-value winner';
            
            // Efeito de confetti
            this.showConfetti();
            this.playWinSound();
        } else {
            resultIcon.className = 'fas fa-times-circle';
            resultIconContainer.className = 'result-icon loser';
            resultTitle.textContent = 'Que pena! Tente novamente!';
            resultText.textContent = 'Sem prêmio desta vez';
            resultValue.className = 'result-value loser';
        }
        
        // Mostrar com animação
        resultDisplay.style.display = 'block';
        resultDisplay.style.animation = 'result-reveal 0.5s ease-out forwards';
    }

    async showAutoResults(data) {
        const winnersCount = data.winners_count;
        const totalPrize = data.formatted_total_prize;
        
        // Animação rápida mostrando várias jogadas
        await this.showAutoGameAnimation(data);
        
        const resultDisplay = document.getElementById('result-display');
        if (!resultDisplay) return;
        
        const resultIcon = resultDisplay.querySelector('.result-icon i');
        const resultIconContainer = resultDisplay.querySelector('.result-icon');
        const resultTitle = resultDisplay.querySelector('.result-title');
        const resultText = resultDisplay.querySelector('#result-text');
        const resultValue = resultDisplay.querySelector('.result-value');
        
        if (winnersCount > 0) {
            resultIcon.className = 'fas fa-trophy';
            resultIconContainer.className = 'result-icon winner';
            resultTitle.textContent = `${winnersCount} vitória${winnersCount > 1 ? 's' : ''} em ${data.quantity} jogadas!`;
            resultText.textContent = `Total ganho: ${totalPrize}`;
            resultValue.className = 'result-value winner';
            
            this.showConfetti();
            this.playWinSound();
        } else {
            resultIcon.className = 'fas fa-times-circle';
            resultIconContainer.className = 'result-icon loser';
            resultTitle.textContent = `${data.quantity} jogadas realizadas`;
            resultText.textContent = 'Nenhum prêmio desta vez';
            resultValue.className = 'result-value loser';
        }
        
        resultDisplay.style.display = 'block';
        resultDisplay.style.animation = 'result-reveal 0.5s ease-out forwards';
    }

    async showAutoGameAnimation(data) {
        const grid = document.getElementById('raspadinha-grid');
        if (!grid) return;
        
        // Simular jogadas rápidas
        for (let game = 0; game < Math.min(data.quantity, 5); game++) {
            grid.style.transform = 'scale(0.8)';
            grid.style.filter = 'blur(2px)';
            
            await new Promise(resolve => setTimeout(resolve, 200));
            
            grid.style.transform = 'scale(1)';
            grid.style.filter = 'none';
            
            if (data.results[game]?.is_winner) {
                grid.style.boxShadow = '0 0 20px #28a745';
                setTimeout(() => grid.style.boxShadow = '', 300);
            }
            
            await new Promise(resolve => setTimeout(resolve, 100));
        }
    }

    showConfetti() {
        // Criar efeito de confetti
        const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f9ca24', '#f0932b', '#eb4d4b', '#6c5ce7'];
        const confettiContainer = document.createElement('div');
        confettiContainer.className = 'confetti-container';
        confettiContainer.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
        `;
        
        document.body.appendChild(confettiContainer);
        
        for (let i = 0; i < 50; i++) {
            const confetti = document.createElement('div');
            confetti.style.cssText = `
                position: absolute;
                width: 10px;
                height: 10px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                left: ${Math.random() * 100}%;
                top: -10px;
                border-radius: ${Math.random() > 0.5 ? '50%' : '0'};
                animation: confetti-fall ${2 + Math.random() * 3}s linear forwards;
                transform: rotate(${Math.random() * 360}deg);
            `;
            
            confettiContainer.appendChild(confetti);
        }
        
        setTimeout(() => confettiContainer.remove(), 5000);
    }

    resetGame() {
        const items = document.querySelectorAll('.raspadinha-item');
        const resultDisplay = document.getElementById('result-display');
        
        items.forEach(item => {
            const overlay = item.querySelector('.scratch-overlay');
            const content = item.querySelector('.prize-text');
            
            overlay.classList.remove('scratching', 'scratched');
            overlay.style.animation = '';
            overlay.style.background = '';
            content.textContent = '--';
            item.style.transform = '';
            
            // Remover partículas existentes
            item.querySelectorAll('.scratch-particle').forEach(p => p.remove());
        });
        
        if (resultDisplay) {
            resultDisplay.style.display = 'none';
            resultDisplay.style.animation = '';
        }
        
        this.scratchedItems = 0;
        this.gameResults = [];
    }

    updateBalance(newBalance = null) {
        const balanceEl = document.getElementById('user-balance');
        if (!balanceEl) return;
        
        if (newBalance !== null) {
            const formatted = 'R$ ' + newBalance.toLocaleString('pt-BR', { 
                minimumFractionDigits: 2,
                maximumFractionDigits: 2 
            });
            
            // Animação de mudança de saldo
            balanceEl.style.animation = 'balance-update 0.5s ease-out';
            balanceEl.textContent = formatted;
            
            setTimeout(() => balanceEl.style.animation = '', 500);
            return;
        }
        
        // Buscar saldo atual
        fetch('/raspadinha/user/balance')
            .then(response => response.json())
            .then(data => {
                balanceEl.textContent = data.formatted_balance;
            })
            .catch(error => console.error('Erro ao atualizar saldo:', error));
    }

    updateAutoCost() {
        const quantityEl = document.getElementById('auto_quantity');
        const turboEl = document.getElementById('auto_turbo');
        const buttonEl = document.getElementById('play-auto');
        
        if (!quantityEl || !buttonEl) return;
        
        const quantity = parseInt(quantityEl.value) || 1;
        const isTurbo = turboEl?.checked || false;
        const price = isTurbo ? this.raspadinha.turboPrice : this.raspadinha.price;
        const total = quantity * price;
        
        const formatted = total.toLocaleString('pt-BR', { 
            minimumFractionDigits: 2,
            maximumFractionDigits: 2 
        });
        
        buttonEl.innerHTML = `<i class="fas fa-magic"></i> Jogar ${quantity}x (R$ ${formatted})`;
    }

    showLoading() {
        const modal = document.getElementById('loadingModal');
        if (modal && typeof bootstrap !== 'undefined') {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    }

    hideLoading() {
        const modal = document.getElementById('loadingModal');
        if (modal && typeof bootstrap !== 'undefined') {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();
        }
    }

    showError(message) {
        // Criar toast de erro moderno
        const toast = document.createElement('div');
        toast.className = 'error-toast';
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
            z-index: 10000;
            animation: toast-slide-in 0.3s ease-out;
            max-width: 400px;
        `;
        toast.innerHTML = `
            <div style="display: flex; align-items: center;">
                <i class="fas fa-exclamation-circle" style="margin-right: 10px;"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'toast-slide-out 0.3s ease-in forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    playScratchSound() {
        // Efeito sonoro opcional (se o áudio estiver disponível)
        try {
            if (window.scratchAudio) {
                window.scratchAudio.currentTime = 0;
                window.scratchAudio.play().catch(() => {});
            }
        } catch (e) {}
    }

    playWinSound() {
        // Efeito sonoro de vitória opcional
        try {
            if (window.winAudio) {
                window.winAudio.currentTime = 0;
                window.winAudio.play().catch(() => {});
            }
        } catch (e) {}
    }
}

// CSS para animações (será injetado dinamicamente)
const raspadinhaCSS = `
@keyframes scratch-reveal {
    0% { opacity: 1; }
    50% { opacity: 0.5; transform: scale(1.1); }
    100% { opacity: 0; transform: scale(1); }
}

@keyframes particle-fly {
    0% { 
        opacity: 1; 
        transform: translate(0, 0) scale(1); 
    }
    100% { 
        opacity: 0; 
        transform: translate(${Math.random() * 60 - 30}px, ${Math.random() * 60 - 30}px) scale(0); 
    }
}

@keyframes pulse-glow {
    0%, 100% { 
        box-shadow: 0 0 10px rgba(255, 215, 0, 0.5); 
        transform: scale(1); 
    }
    50% { 
        box-shadow: 0 0 25px rgba(255, 215, 0, 0.8); 
        transform: scale(1.02); 
    }
}

@keyframes result-reveal {
    0% { 
        opacity: 0; 
        transform: translate(-50%, -50%) scale(0.5); 
    }
    100% { 
        opacity: 1; 
        transform: translate(-50%, -50%) scale(1); 
    }
}

@keyframes balance-update {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); color: #28a745; }
}

@keyframes confetti-fall {
    0% { 
        transform: translateY(-100vh) rotate(0deg); 
        opacity: 1; 
    }
    100% { 
        transform: translateY(100vh) rotate(720deg); 
        opacity: 0; 
    }
}

@keyframes toast-slide-in {
    0% { 
        opacity: 0; 
        transform: translateX(100%); 
    }
    100% { 
        opacity: 1; 
        transform: translateX(0); 
    }
}

@keyframes toast-slide-out {
    0% { 
        opacity: 1; 
        transform: translateX(0); 
    }
    100% { 
        opacity: 0; 
        transform: translateX(100%); 
    }
}

.scratch-particle {
    z-index: 1000;
}

.error-toast {
    font-family: inherit;
    font-size: 14px;
    line-height: 1.4;
}
`;

// Injetar CSS
if (!document.getElementById('raspadinha-animations')) {
    const style = document.createElement('style');
    style.id = 'raspadinha-animations';
    style.textContent = raspadinhaCSS;
    document.head.appendChild(style);
}

// Exportar para uso global
window.RaspadinhaGame = RaspadinhaGame; 