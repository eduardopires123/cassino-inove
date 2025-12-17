// ========================================
// UTILITÁRIOS GLOBAIS
// ========================================
console.log('🔄 Roulette Config v2.0 - Carregado com correções toFixed()');


// Função global segura para formatar valores monetários
function formatCurrencyValue(value) {
    try {
        // Garantir que é um número válido
        let numValue = 0;
        
        if (value !== undefined && value !== null && value !== '') {
            if (typeof value === 'string') {
                // Se for string, limpar caracteres especiais e converter
                const cleanValue = value.toString().replace(/[^0-9.,\-]/g, '').replace(',', '.');
                numValue = parseFloat(cleanValue);
            } else if (typeof value === 'number') {
                numValue = value;
            } else {
                // Tentar converter outros tipos
                numValue = parseFloat(value);
            }
        }
        
        // Verificar se é um número válido e finito
        if (isNaN(numValue) || !isFinite(numValue) || numValue < 0) {
            numValue = 0;
        }
        
        // Formatar para 2 casas decimais e trocar ponto por vírgula
        return numValue.toFixed(2).replace('.', ',');
        
    } catch (error) {
        console.error('❌ Erro ao formatar valor monetário:', error, 'Valor original:', value, 'Tipo:', typeof value);
        return '0,00';
    }
}

// ========================================
// MAPEAMENTO DA ROLETA (baseado na imagem)
// ========================================
const RouletteWheel = {
    // Baseado na imagem REAL da roleta fornecida
    // Mapeamento correto dos segmentos (do topo, sentido horário)
    // Corrigindo o mapeamento com base no feedback do usuário
    segments: [
        { id: 1, name: 'TENTE OUTRA VEZ', degrees: 22.5, range: [0, 45] },       // Topo-centro (branco)
        { id: 7, name: 'DEPOSITE E GANHE', degrees: 67.5, range: [45, 90] },     // Direita-topo (branco)
        { id: 2, name: '30 GIROS GRÁTIS', degrees: 112.5, range: [90, 135] },    // Direita (verde)
        { id: 5, name: '100 GIROS GRÁTIS', degrees: 157.5, range: [135, 180] },  // Direita-baixo (branco)
        { id: 6, name: '120 GIROS GRÁTIS', degrees: 202.5, range: [180, 225] },  // Baixo (verde)
        { id: 4, name: '80 GIROS GRÁTIS', degrees: 247.5, range: [225, 270] },   // Esquerda-baixo (branco)
        { id: 3, name: '60 GIROS GRÁTIS', degrees: 292.5, range: [270, 315] },   // Esquerda (verde)
        { id: 8, name: '150 GIROS GRÁTIS', degrees: 337.5, range: [315, 360] },  // Esquerda-topo (branco)
    ],

    // Função para determinar qual prêmio foi selecionado baseado no ângulo final
    getSelectedPrize(finalAngle) {
        // Normalizar o ângulo para 0-360
        const normalizedAngle = ((finalAngle % 360) + 360) % 360;
        
        console.log(`🔍 Analisando ângulo final: ${normalizedAngle.toFixed(2)}°`);
        
        // Encontrar o segmento correspondente
        for (const segment of this.segments) {
            if (normalizedAngle >= segment.range[0] && normalizedAngle < segment.range[1]) {
                console.log(`🎯 ACERTO! Roleta parou em ${normalizedAngle.toFixed(2)}° - Segmento: ${segment.name} (ID: ${segment.id})`);
                return segment;
            }
        }
        
        // Fallback para o primeiro segmento se não encontrar
        console.warn(`⚠️ Ângulo ${normalizedAngle}° não mapeado, usando TENTE OUTRA VEZ como fallback`);
        console.log('🗺️ Mapa de segmentos disponíveis:', this.segments.map(s => `${s.range[0]}-${s.range[1]}°: ${s.name}`));
        return this.segments[0];
    },

    // Debug: Mostrar todos os segmentos
    showSegmentMap() {
        console.log('🗺️ MAPA COMPLETO DA ROLETA (baseado na imagem real):');
        console.log('Posicionamento correto a partir do topo (0°) no sentido horário:');
        this.segments.forEach((segment, index) => {
            console.log(`${index + 1}. ${segment.range[0]}-${segment.range[1]}°: ${segment.name} (ID: ${segment.id})`);
        });
    },

    // Debug: Testar mapeamento para um ID específico
    testMapping(itemId) {
        const segment = this.segments.find(seg => seg.id === itemId);
        if (segment) {
            console.log(`✅ ID ${itemId} mapeado para: ${segment.name} (${segment.degrees}°)`);
            return segment;
        } else {
            console.error(`❌ ID ${itemId} não encontrado no mapeamento`);
            return null;
        }
    }
};

// ========================================
// CONFIGURAÇÕES DA ROLETA
// ========================================
const RouletteConfig = {
    animation: {
        type: 'spinToStop',
        duration: 5,
        spins: 8
    },
    confetti: {
        particleCount: 100,
        spread: 70,
        origin: { y: 0.6 }
    },
    segments: {
        1: { angle: 0, name: 'TENTE OUTRA VEZ' },
        7: { angle: 45, name: 'DEPOSITE E GANHE' },
        2: { angle: 90, name: '30 GIROS GRÁTIS' },
        5: { angle: 135, name: '100 GIROS GRÁTIS' },
        6: { angle: 180, name: '120 GIROS GRÁTIS' },
        4: { angle: 225, name: '80 GIROS GRÁTIS' },
        3: { angle: 270, name: '60 GIROS GRÁTIS' },
        8: { angle: 315, name: '150 GIROS GRÁTIS' }
    }
};

// ========================================
// CLASSE PRINCIPAL DA ROLETA
// ========================================
class Roulette {
    constructor(type) {
        this.type = type;
        this.isSpinning = false;
        this.csrfToken = this.getCSRFToken();
        this.currentRotation = 0;
        
        if (!this.checkRequiredElements()) {
            console.error('Elementos necessários para a roleta não foram encontrados');
            return;
        }
        
        this.init();
    }

    // ========================================
    // INICIALIZAÇÃO
    // ========================================
    init() {
        console.log('🎰 Inicializando sistema de roleta...');
        
        this.initializeElements();
        this.initializeEventListeners();
        this.cleanOldGuestSpins(); // Limpar dados antigos
        this.loadRouletteData();
        this.setupEmergencyControls();
        
        // Mostrar mapa da roleta para debug
        console.log('🗺️ Carregando mapa da roleta baseado na imagem real...');
        RouletteWheel.showSegmentMap();
    }

    initializeElements() {
        this.btnGirar = document.querySelector('.btn-girar');
        this.roleta = document.getElementById('roleta');
        this.spinCounter = document.querySelector('.spin-counter');
        this.errorModal = document.getElementById('errorModal');
        this.errorMessage = document.getElementById('errorMessage');
    }

    initializeEventListeners() {
        if (this.btnGirar) {
            this.btnGirar.addEventListener('click', () => this.spin());
        }

        // Fechar modal de erro
        const closeErrorModal = document.getElementById('closeErrorModal');
        if (closeErrorModal) {
            closeErrorModal.addEventListener('click', () => this.hideErrorModal());
        }

        // ESC para emergência
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isBlurred()) {
                this.emergencyClean();
            }
        });
    }

    // ========================================
    // UTILITÁRIOS
    // ========================================
    getCSRFToken() {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        return metaTag ? metaTag.getAttribute('content') : '';
    }

    // Função helper segura para formatar valores monetários
    formatCurrency(value) {
        try {
            // Garantir que é um número válido
            let numValue = 0;
            
            if (value !== undefined && value !== null) {
                if (typeof value === 'string') {
                    // Se for string, tentar converter
                    numValue = parseFloat(value.replace(',', '.'));
                } else if (typeof value === 'number') {
                    numValue = value;
                }
            }
            
            // Verificar se é um número válido
            if (isNaN(numValue) || !isFinite(numValue)) {
                numValue = 0;
            }
            
            // Formatar para 2 casas decimais e trocar ponto por vírgula
            return numValue.toFixed(2).replace('.', ',');
            
        } catch (error) {
            console.error('Erro ao formatar valor monetário:', error, 'Valor original:', value);
            return '0,00';
        }
    }

    checkRequiredElements() {
        return document.querySelector('#roleta') || document.querySelector('.section-roleta');
    }

    /**
     * Limpar dados antigos de giros de convidados do localStorage
     */
    cleanOldGuestSpins() {
        try {
            const today = new Date().toDateString();
            const keys = Object.keys(localStorage);
            
            for (const key of keys) {
                if (key.startsWith('guest_roulette_spin_')) {
                    const dateStr = key.replace('guest_roulette_spin_', '');
                    if (dateStr !== today) {
                        localStorage.removeItem(key);
                        console.log('🧹 Removido dado antigo:', key);
                    }
                }
            }
        } catch (error) {
            console.error('Erro ao limpar dados antigos:', error);
        }
    }

    isBlurred() {
        return document.body.classList.contains('roulette-active') || 
               document.querySelector('._8XokL.roulette-blurred');
    }

    // ========================================
    // CARREGAMENTO DE DADOS
    // ========================================
    async loadRouletteData() {
        try {
            const response = await fetch('/roulette/data', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            const data = await response.json();
            
            if (data.success) {
                this.rouletteData = data; // Armazenar dados para verificações
                this.updateSpinCounter(data);
            }
        } catch (error) {
            console.error('Erro ao carregar dados da roleta:', error);
        }
    }

    updateSpinCounter(data) {
        if (this.spinCounter) {
            if (data.is_guest) {
                if (data.guest_can_spin) {
                    this.spinCounter.innerHTML = `
                        <span style="color: #00ff41; font-weight: bold; font-size: 16px;">🎁 Você tem uma rodada por dia, aproveite!</span><br>
                        <span style="color: #ffaa00; font-size: 14px;">Cadastre-se para resgatar prêmios</span>
                    `;
                } else {
                    this.spinCounter.innerHTML = `
                        <span style="color: #ff6b6b; font-weight: bold;">⏰ GIRO DIÁRIO USADO!</span><br>
                        <span style="color: #ffaa00; font-size: 14px;">Volte amanhã ou cadastre-se para mais giros</span>
                    `;
                }
            } else {
                const remaining = Math.max(0, data.max_spins - data.spins_today);
                const freeText = data.can_spin_free ? '✨ 1 GIRO GRÁTIS DISPONÍVEL!' : '⭐ Giro grátis já usado hoje';
                
                this.spinCounter.innerHTML = `
                    <div style="margin-bottom: 8px;">
                        <span style="color: ${data.can_spin_free ? '#00ff41' : '#ffaa00'}; font-weight: bold;">${freeText}</span>
                    </div>
                    <div>
                        <span style="color: #ff7300;">Giros restantes: ${remaining}/${data.max_spins}</span>
                    </div>
                `;
            }
        }
    }

    // ========================================
    // LÓGICA DO SPIN
    // ========================================
    async spin() {
        if (this.isSpinning) return;

        // Verificar cache local para convidados (proteção adicional)
        if (this.rouletteData && this.rouletteData.is_guest) {
            const today = new Date().toDateString();
            const guestSpinKey = `guest_roulette_spin_${today}`;
            const localSpinUsed = localStorage.getItem(guestSpinKey);
            
            if (this.rouletteData.guest_spin_used || localSpinUsed) {
                this.showError('Você já girou hoje! Volte amanhã ou cadastre-se para mais giros.');
                return;
            }
        }

        // Verificar se usuário logado atingiu limite
        if (this.rouletteData && !this.rouletteData.is_guest) {
            const remaining = Math.max(0, this.rouletteData.max_spins - this.rouletteData.spins_today);
            if (remaining <= 0) {
                this.showError('Você atingiu o limite de giros diários. Volte amanhã!');
                return;
            }
        }

        this.startSpin();
        
        try {
            const result = await this.sendSpinRequest();
            
            if (result && result.success) {
                this.handleSpinSuccess(result);
            } else {
                this.handleSpinError(result?.message || 'Erro desconhecido');
            }
        } catch (error) {
            console.error('Erro no spin:', error);
            this.handleSpinError('Erro de conexão. Tente novamente.');
        }
    }

    startSpin() {
        this.isSpinning = true;
        this.applyBackgroundBlur();
        
        if (this.btnGirar) {
            this.btnGirar.disabled = true;
            this.btnGirar.textContent = 'GIRANDO...';
        }
    }

    async sendSpinRequest() {
        const headers = {
            'Content-Type': 'application/json'
        };
        
        // Adicionar CSRF token apenas se disponível (usuários logados)
        if (this.csrfToken) {
            headers['X-CSRF-TOKEN'] = this.csrfToken;
        }
        
        const response = await fetch('/roulette/spin', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({})
        });

        const data = await response.json();
        console.log('Resposta do backend (raw):', data);
        
        return data;
    }

    handleSpinSuccess(result) {
        console.log('✅ Resultado do spin recebido:', result);
        
        // Verificar se o resultado é válido
        if (!result || !result.selectedItem) {
            console.error('❌ Resultado do spin inválido:', result);
            this.handleSpinError('Dados do prêmio não foram recebidos corretamente.');
            return;
        }
        
        const selectedItem = result.selectedItem;
        console.log('🎁 Item selecionado:', selectedItem);
        
        // Verificar se selectedItem tem propriedades válidas
        if (typeof selectedItem !== 'object') {
            console.error('❌ selectedItem não é um objeto válido:', selectedItem);
            this.handleSpinError('Formato de dados do prêmio inválido.');
            return;
        }
        
        // Anexar dados do resultado ao item para usar no modal
        selectedItem.prize_result = result.prize_result;
        selectedItem.is_guest = result.is_guest;
        selectedItem.is_free_spin = result.is_free_spin;
        
        // Atualizar dados da roleta se for convidado que acabou de usar o giro
        if (result.is_guest && result.guest_daily_spin_used) {
            // Marcar no localStorage para proteção adicional
            const today = new Date().toDateString();
            const guestSpinKey = `guest_roulette_spin_${today}`;
            localStorage.setItem(guestSpinKey, 'true');
            
            this.loadRouletteData(); // Recarregar para atualizar contador
        }
        
        // Apenas iniciar a animação - o modal será mostrado quando a animação terminar
        this.animateRoulette(selectedItem);
    }

    handleSpinError(message) {
        this.resetSpinState();
        this.showError(message);
    }

    animateRoulette(selectedItem) {
        if (!this.roleta) {
            console.error('❌ Elemento roleta não encontrado');
            return;
        }

        // Verificar se selectedItem existe e tem propriedades válidas
        if (!selectedItem || typeof selectedItem !== 'object') {
            console.error('❌ selectedItem inválido:', selectedItem);
            return;
        }

        console.log('🎰 Iniciando animação da roleta para:', selectedItem);

        // Encontrar o segmento correspondente ao prêmio sorteado
        const itemId = selectedItem.id || selectedItem.item_id || 1;
        const targetSegment = RouletteWheel.segments.find(seg => seg.id === itemId);
        
        if (!targetSegment) {
            console.error(`❌ Segmento não encontrado para ID: ${itemId}`);
            console.log('🗺️ Segmentos disponíveis:', RouletteWheel.segments.map(s => `ID ${s.id}: ${s.name}`));
            return;
        }

        console.log(`🎯 Segmento encontrado: ID ${targetSegment.id} - ${targetSegment.name} (${targetSegment.degrees}°)`);

                 // Calcular rotação
         const spins = 8; // Número de voltas completas
         const randomOffset = Math.random() * 20 - 10; // Offset aleatório para naturalidade (-10 a +10 graus)
         
         // O ponteiro está no topo (0°), então precisamos calcular para onde girar
         // Para que o prêmio fique no ponteiro, giramos 360° - graus do segmento
         const targetAngle = 360 - targetSegment.degrees + randomOffset;
         const totalRotation = (360 * spins) + targetAngle;
        
        console.log(`🎯 Roleta girará para ${totalRotation}°, alvo: ${targetSegment.name}`);
        
        // Aplicar animação
        this.roleta.style.transition = 'transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99)';
        this.roleta.style.transform = `rotate(${totalRotation}deg)`;

        // Aguardar fim da animação para mostrar resultado
        setTimeout(() => {
            console.log('✅ Animação da roleta finalizada');
            
            // Verificar onde realmente parou e mostrar o prêmio correto
            const actualSegment = RouletteWheel.getSelectedPrize(totalRotation);
            
            // Se o prêmio calculado for diferente do sorteado, usar o correto
            const finalPrize = actualSegment.id === itemId ? selectedItem : {
                ...selectedItem,
                id: actualSegment.id,
                name: actualSegment.name
            };
            
            this.showPrizeModal(finalPrize);
        }, 4100); // Um pouco depois da animação terminar
    }

    // ========================================
    // MODAL DE PRÊMIO
    // ========================================
    showPrizeModal(selectedItem) {
        console.log('🎁 Iniciando exibição do modal de prêmio:', selectedItem);
        
        // Sempre usar o método direto e robusto
        this.showDirectPrizeModal(selectedItem);
    }

    /**
     * Método direto e robusto para mostrar modal de prêmio
     */
    showDirectPrizeModal(selectedItem) {
        try {
            console.log('🎯 Método direto para exibir modal:', selectedItem);
            
            // Preparar dados básicos com validação segura
            const itemName = (selectedItem && selectedItem.name) || (selectedItem && selectedItem.item_name) || 'PRÊMIO';
            let freeSpins = 0;
            let depositValue = '20,00'; // Valor padrão de depósito
            let couponCode = '';
            
            // Processar free_spins com segurança
            if (selectedItem && selectedItem.free_spins) {
                const parsed = parseInt(selectedItem.free_spins);
                if (!isNaN(parsed) && parsed > 0) {
                    freeSpins = parsed;
                }
            }
            
            // Processar deposit_value com segurança - SEMPRE definir um valor
            if (selectedItem && selectedItem.deposit_value) {
                try {
                    if (typeof selectedItem.deposit_value === 'number') {
                        if (selectedItem.deposit_value > 0) {
                            depositValue = selectedItem.deposit_value.toFixed(2).replace('.', ',');
                        }
                    } else if (typeof selectedItem.deposit_value === 'string') {
                        const num = parseFloat(selectedItem.deposit_value.replace(',', '.'));
                        if (!isNaN(num) && num > 0) {
                            depositValue = num.toFixed(2).replace('.', ',');
                        }
                    }
                } catch (e) {
                    console.warn('Erro ao processar deposit_value:', e);
                    depositValue = '20,00'; // Valor padrão em caso de erro
                }
            }
            
            // Se ainda for 0,00, definir valor padrão baseado no tipo de prêmio
            if (depositValue === '0,00') {
                if (freeSpins >= 100) {
                    depositValue = '50,00';
                } else if (freeSpins >= 50) {
                    depositValue = '30,00';
                } else {
                    depositValue = '20,00';
                }
            }
            
            // Processar coupon_code com segurança
            if (selectedItem && selectedItem.coupon_code && selectedItem.coupon_code !== 'NADA') {
                couponCode = selectedItem.coupon_code;
            }
            
            // Determinar o texto do prêmio
            const prizeText = freeSpins > 0 ? `${freeSpins} GIROS GRÁTIS` : itemName.toUpperCase();
            
            console.log('📋 Dados processados:', { itemName, freeSpins, depositValue, couponCode, prizeText });
            
            // Atualizar elementos do modal de forma segura
            this.updateModalElements(prizeText, depositValue, couponCode, selectedItem);
            
            // Mostrar o modal
            this.displayPrizeModalSafe();
            
            // Adicionar confetti com delay
            setTimeout(() => {
                this.addConfettiSafe();
            }, 800);
            
            console.log('✅ Modal exibido com sucesso pelo método direto');
            
        } catch (error) {
            console.error('❌ Erro no método direto:', error);
            this.showEmergencyPrizeAlert(selectedItem);
        }
    }

    /**
     * Atualizar elementos do modal de forma segura
     */
    updateModalElements(prizeText, depositValue, couponCode, selectedItem) {
        try {
            // Atualizar título do prêmio
            const prizeValueElement = document.getElementById('prize-value');
            if (prizeValueElement) {
                prizeValueElement.textContent = prizeText;
                console.log('✅ Título do prêmio atualizado:', prizeText);
            }
            
            const isGuest = selectedItem && selectedItem.is_guest;
            
            // SEMPRE mostrar instruções de depósito e cupom quando o usuário ganhar
            const depositInstruction = document.getElementById('deposit-instruction');
            const depositButton = document.getElementById('deposit-button');
            const registerButton = document.getElementById('register-button');
            
            // Mostrar instruções de depósito
            if (depositInstruction) {
                depositInstruction.style.display = 'block';
                if (isGuest) {
                    depositInstruction.innerHTML = `📝 CADASTRE-SE E DEPOSITE R$ ${depositValue} PARA RESGATAR SEU PRÊMIO:`;
                } else {
                    depositInstruction.innerHTML = `💰 DEPOSITE R$ ${depositValue} E USE O CUPOM ABAIXO PARA RESGATAR SEU PRÊMIO:`;
                }
                console.log('✅ Instruções de depósito configuradas');
            }
            
            // Mostrar botões apropriados baseado no tipo de usuário
            if (isGuest) {
                // Para guests: mostrar botão de cadastro
                if (registerButton) {
                    registerButton.style.display = 'block';
                }
                if (depositButton) {
                    depositButton.style.display = 'none';
                }
            } else {
                // Para usuários logados: mostrar botão de depósito
                if (depositButton) {
                    depositButton.style.display = 'block';
                }
                if (registerButton) {
                    registerButton.style.display = 'none';
                }
            }
            
            // SEMPRE mostrar o cupom quando o usuário ganhar
            const couponSection = document.getElementById('coupon-section');
            const couponCodeElement = document.getElementById('coupon-code');
            const couponDisplay = document.getElementById('coupon-display');
            
            // Gerar cupom se não existir
            let finalCouponCode = couponCode;
            if (!finalCouponCode || finalCouponCode === '' || finalCouponCode === 'NADA') {
                // Gerar cupom baseado no ID do usuário ou timestamp
                finalCouponCode = 'WINNER' + Date.now().toString().slice(-6);
            }
            
            if (couponSection) {
                couponSection.style.display = 'block';
                if (couponCodeElement) {
                    couponCodeElement.textContent = finalCouponCode;
                }
                if (couponDisplay) {
                    couponDisplay.setAttribute('data-coupon', finalCouponCode);
                }
                console.log('✅ Cupom configurado:', finalCouponCode);
            }
            
        } catch (error) {
            console.error('❌ Erro ao atualizar elementos do modal:', error);
        }
    }

    /**
     * Mostrar modal de forma segura
     */
        closePrizeModalSafe() {
        try {
            const modal = document.getElementById('prize-modal-section');
            if (modal) {
                modal.style.display = 'none';
            }
            
            // Verificar se estamos no modo quest antes de fechar o modal da roleta
            const isQuestMode = window.location.href.includes('quest') || 
                               document.body.classList.contains('quest-mode') ||
                               localStorage.getItem('quest_mode') === 'true';
            
            if (!isQuestMode) {
                const rouletteModal = document.getElementById('roulette-modal');
                if (rouletteModal) {
                    rouletteModal.style.display = 'none';
                }
            }
            
            this.resetSpinState();
            console.log('✅ Modal fechado com segurança');
        } catch (error) {
            console.error('❌ Erro ao fechar modal:', error);
        }
    }

    /**
     * Adicionar confetti de forma segura
     */
    addConfettiSafe() {
        try {
            if (typeof window.confetti === 'function') {
                console.log('🎊 Adicionando confetti...');
                
                // Configurar z-index do canvas de confetti
                setTimeout(() => {
                    const canvases = document.querySelectorAll('canvas');
                    canvases.forEach(canvas => {
                        if (canvas.style.position === 'fixed' || canvas.style.position === 'absolute') {
                            canvas.style.zIndex = '999999';
                            canvas.style.pointerEvents = 'none';
                        }
                    });
                }, 200);
                
                // Confetti simples mas efetivo
                window.confetti({
                    particleCount: 100,
                    spread: 70,
                    origin: { y: 0.6 },
                    colors: ['#00ff41', '#32ff32', '#FFD700', '#FFA500']
                });
                
                console.log('✅ Confetti adicionado');
            } else {
                console.warn('⚠️ Biblioteca de confetti não disponível');
            }
        } catch (error) {
            console.warn('⚠️ Erro no confetti (ignorado):', error);
        }
    }

    /**
     * Fechar modal de forma segura
     */
    closePrizeModalSafe() {
        try {
            const modal = document.getElementById('prize-modal-section');
            if (modal) {
                modal.style.display = 'none';
            }
            
            const rouletteModal = document.getElementById('roulette-modal');
            if (rouletteModal) {
                rouletteModal.style.display = 'none';
            }
            
            this.resetSpinState();
            console.log('✅ Modal fechado com segurança');
        } catch (error) {
            console.error('❌ Erro ao fechar modal:', error);
        }
    }

    /**
     * Alert de emergência se tudo falhar
     */
    showEmergencyPrizeAlert(selectedItem) {
        try {
            const itemName = (selectedItem && selectedItem.name) || (selectedItem && selectedItem.item_name) || 'PRÊMIO';
            const freeSpins = selectedItem && selectedItem.free_spins ? parseInt(selectedItem.free_spins) : 0;
            const prizeText = freeSpins > 0 ? `${freeSpins} GIROS GRÁTIS` : itemName;
            
            alert(`🎉 PARABÉNS!\n\nVocê ganhou: ${prizeText}`);
            this.resetSpinState();
            console.log('✅ Alert de emergência exibido');
        } catch (error) {
            alert('🎉 PARABÉNS! Você ganhou um prêmio!');
            this.resetSpinState();
        }
    }





    generateModalHTML(selectedItem) {
        // Verificar e definir valores padrão para propriedades com validação segura
        const itemName = selectedItem.name || selectedItem.item_name || 'PRÊMIO';
        
        // Validação segura para free_spins
        let freeSpins = 0;
        if (selectedItem.free_spins !== undefined && selectedItem.free_spins !== null) {
            const parsed = parseInt(selectedItem.free_spins);
            freeSpins = !isNaN(parsed) ? parsed : 0;
        }
        
        // Validação segura para deposit_value
        let depositValue = 0;
        if (selectedItem.deposit_value !== undefined && selectedItem.deposit_value !== null) {
            const parsed = parseFloat(selectedItem.deposit_value);
            depositValue = !isNaN(parsed) ? parsed : 0;
        }
        
        const couponCode = selectedItem.coupon_code || '';
        
        const prizeTitle = freeSpins > 0 
            ? `${freeSpins} GIROS GRÁTIS`
            : itemName.toUpperCase();

        const hasDeposit = depositValue > 0;
        const hasCoupon = couponCode && couponCode !== 'NADA';

        return `
            <div class="prize-modal-content">
                <!-- Header -->
                <div class="prize-header">
                    🎉 PARABÉNS! 🎉
                </div>

                <!-- Prize Info -->
                <div class="prize-info">
                    VOCÊ GANHOU<br>
                    <strong>${prizeTitle}</strong>
                </div>

                ${hasDeposit ? `
                <div class="prize-deposit">
                    💰 DEPOSITE R$ ${formatCurrencyValue(depositValue)} 
                    E USE O CUPOM ABAIXO PARA RESGATAR:
                </div>
                ` : ''}

                ${hasCoupon ? `
                <div class="prize-coupon" onclick="copyToClipboard('${couponCode}')">
                    <div class="coupon-icon">🎟️</div>
                    <div class="coupon-text">
                        <span>CUPOM</span>
                        <strong>${couponCode}</strong>
                    </div>
                    <div class="coupon-hint">👆 CLIQUE PARA COPIAR</div>
                </div>
                ` : ''}

                <!-- Actions -->
                <div class="prize-actions">
                    ${hasDeposit ? `
                    <button class="btn-deposit" onclick="window.open('/?d=show', '_blank')">
                        💳 DEPOSITAR AGORA
                    </button>
                    ` : ''}
                    
                    <button class="btn-close" onclick="closePrizeModal()">
                        ❌ FECHAR
                    </button>
                </div>
            </div>
        `;
    }

    addModalStyles() {
        if (document.getElementById('prize-modal-styles')) return;

        const styles = document.createElement('style');
        styles.id = 'prize-modal-styles';
        styles.textContent = `
            .prize-result-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: radial-gradient(circle, rgba(0, 40, 20, 0.95), rgba(0, 0, 0, 0.98));
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 999999999;
                backdrop-filter: blur(8px);
            }

            .prize-modal-content {
                background: linear-gradient(145deg, #1a1a1a, #2d2d2d);
                border: 3px solid #00ff41;
                border-radius: 20px;
                padding: 30px;
                max-width: 450px;
                width: 90%;
                text-align: center;
                font-family: Arial, sans-serif;
                box-shadow: 0 20px 40px rgba(0, 255, 65, 0.3);
            }

            .prize-header {
                color: #000;
                background: linear-gradient(135deg, #00ff41, #32ff32);
                padding: 20px;
                border-radius: 15px;
                font-size: 28px;
                font-weight: 900;
                margin-bottom: 20px;
                text-shadow: 1px 1px 2px rgba(255,255,255,0.3);
            }

            .prize-info {
                background: linear-gradient(135deg, #ffffff, #f0fff0);
                color: #003d1a;
                padding: 25px;
                border-radius: 15px;
                border: 3px solid #00ff41;
                font-size: 18px;
                font-weight: 900;
                margin-bottom: 20px;
                text-transform: uppercase;
            }

            .prize-deposit {
                background: #e6ffe6;
                color: #004d1a;
                padding: 15px;
                border-radius: 10px;
                border: 2px solid #00cc33;
                font-weight: bold;
                margin-bottom: 20px;
            }

            .prize-coupon {
                background: linear-gradient(135deg, #00ff41, #32ff32);
                color: #000;
                padding: 20px;
                border-radius: 15px;
                margin-bottom: 20px;
                cursor: pointer;
                transition: transform 0.3s ease;
                border: 3px solid #00cc33;
                box-shadow: 0 10px 25px rgba(0, 255, 65, 0.4);
            }

            .prize-coupon:hover {
                transform: scale(1.05);
            }

            .coupon-icon {
                font-size: 24px;
                margin-bottom: 10px;
            }

            .coupon-text {
                font-size: 20px;
                font-weight: 900;
                margin-bottom: 10px;
            }

            .coupon-hint {
                font-size: 12px;
                opacity: 0.8;
            }

            .prize-actions {
                margin-top: 20px;
            }

            .btn-deposit, .btn-close {
                padding: 15px 30px;
                border-radius: 10px;
                border: none;
                font-weight: bold;
                font-size: 16px;
                cursor: pointer;
                margin: 5px;
                transition: all 0.3s ease;
            }

            .btn-deposit {
                background: linear-gradient(135deg, #00ff41, #32ff32);
                color: #000;
            }

            .btn-deposit:hover {
                transform: scale(1.05);
            }

            .btn-close {
                background: #666;
                color: #00ff41;
                border: 2px solid #00ff41;
            }

            .btn-close:hover {
                background: #00ff41;
                color: #000;
            }

            @media (max-width: 768px) {
                .prize-modal-content {
                    padding: 20px;
                    width: 95%;
                }
                
                .prize-header {
                    font-size: 24px;
                    padding: 15px;
                }
                
                .prize-info {
                    font-size: 16px;
                    padding: 20px;
                }
            }
        `;

        document.head.appendChild(styles);
    }

    displayPrizeModal() {
        const modal = document.getElementById('prize-result-modal');
        if (modal) {
            modal.style.display = 'flex';
            
            // Click no fundo para fechar
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closePrizeModal();
                }
            });
        }
    }

    closePrizeModal() {
        const modal = document.getElementById('prize-result-modal');
        if (modal) {
            modal.remove();
        }
        this.resetSpinState();
    }

    configureConfettiZIndex() {
        try {
            // Procurar pelo canvas do confetti e ajustar z-index
            setTimeout(() => {
                const confettiCanvas = document.querySelector('canvas[style*="position: fixed"]');
                if (confettiCanvas) {
                    confettiCanvas.style.zIndex = '999999';
                    confettiCanvas.style.pointerEvents = 'none';
                    console.log('✅ Z-index do confetti configurado para 999999');
                } else {
                    console.warn('⚠️ Canvas de confetti não encontrado para configurar z-index');
                }
            }, 100);
        } catch (error) {
            console.error('❌ Erro ao configurar z-index do confetti:', error);
        }
    }

    addConfetti() {
        if (typeof window.confetti === 'function') {
            console.log('🎊 Iniciando efeito de confetti avançado...');
            
            // Configurar z-index do canvas de confetti para ficar na frente do modal
            this.configureConfettiZIndex();
            
            const confettiColors = ['#00ff41', '#32ff32', '#FFD700', '#FFA500', '#FF4500', '#FF0000', '#FF1493'];
            const duration = 5 * 1000;
            const end = Date.now() + duration;
            
            // Explosão inicial grande
            window.confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 },
                colors: confettiColors,
                zIndex: 999999
            });
            
            // Animação contínua dos lados e centro
            const frame = () => {
                // Confetti da esquerda
                window.confetti({
                    particleCount: 3,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: confettiColors,
                    zIndex: 999999
                });
                
                // Confetti da direita
                window.confetti({
                    particleCount: 3,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: confettiColors,
                    zIndex: 999999
                });
                
                // Confetti do centro-baixo
                window.confetti({
                    particleCount: 2,
                    angle: 90,
                    spread: 45,
                    origin: { x: 0.5, y: 0.8 },
                    colors: confettiColors,
                    zIndex: 999999
                });
            
                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                } else {
                    // Explosão final
                    window.confetti({
                        particleCount: 150,
                        spread: 100,
                        origin: { y: 0.6 },
                        colors: confettiColors,
                        zIndex: 999999
                    });
                    console.log('✨ Efeito de confetti finalizado');
                }
            };
            frame();
        } else {
            console.warn('⚠️ Biblioteca de confetti não encontrada');
        }
    }

    // ========================================
    // CONTROLE DE BLUR/SCROLL
    // ========================================
    applyBackgroundBlur() {
        if (document.body.classList.contains('roulette-active')) return;

        // Aplicar blur nos elementos do tema
        document.querySelectorAll('._8XokL').forEach(element => {
            if (!element.classList.contains('roulette-blurred')) {
                element.style.filter = 'blur(5px)';
                element.classList.add('roulette-blurred');
            }
        });

        // Travar scroll
        document.body.style.overflow = 'hidden';
        document.body.classList.add('roulette-active');
    }

    removeBackgroundBlur() {
        // Remover blur
        document.querySelectorAll('._8XokL.roulette-blurred').forEach(element => {
            element.style.filter = '';
            element.classList.remove('roulette-blurred');
        });

        // Liberar scroll
        document.body.style.overflow = '';
        document.body.classList.remove('roulette-active');
    }

    // ========================================
    // RESET E LIMPEZA
    // ========================================
    resetSpinState() {
        this.isSpinning = false;
        this.removeBackgroundBlur();
        
        if (this.btnGirar) {
            this.btnGirar.disabled = false;
            this.btnGirar.textContent = 'GIRAR';
        }
    }

    emergencyClean() {
        console.log('🆘 Limpeza de emergência iniciada...');
        
        // Fechar modal principal da roleta
        const rouletteModal = document.getElementById('roulette-modal');
        if (rouletteModal) {
            rouletteModal.style.display = 'none';
            console.log('✅ Modal principal da roleta fechado');
        }
        
        // Remover todos os modais de prêmio
        ['prize-result-modal', 'simple-result-modal', 'prize-modal', 'prize-modal-section'].forEach(id => {
            const modal = document.getElementById(id);
            if (modal) {
                if (modal.style) modal.style.display = 'none';
                if (modal.remove && id !== 'prize-modal-section') modal.remove();
                console.log(`✅ Modal ${id} fechado`);
            }
        });
        
        // Limpar z-index de todos os modais
        const allModals = document.querySelectorAll('[class*="modal"], [id*="modal"]');
        allModals.forEach(modal => {
            if (modal.style.zIndex) {
                modal.style.zIndex = '';
            }
        });
        
        this.resetSpinState();
        
        // Notificação
        if (typeof window.mostrarMensagemSucesso === 'function') {
            window.mostrarMensagemSucesso('✅ Tela desbloqueada com sucesso!');
        }
        
        console.log('✅ Limpeza concluída!');
    }

    /**
     * Fecha o modal de prêmio estruturado
     */
    closePrizeModal() {
        console.log('🚪 Fechando modal de prêmio...');
        
        try {
            // Fechar modal principal da roleta
            const rouletteModal = document.getElementById('roulette-modal');
            if (rouletteModal) {
                rouletteModal.style.display = 'none';
                console.log('✅ Modal principal da roleta fechado');
            }

            // Fechar modal de prêmio estruturado
            const prizeModal = document.getElementById('prize-modal-section');
            if (prizeModal) {
                prizeModal.style.display = 'none';
                console.log('✅ Modal de prêmio fechado');
            }

            // Remover modais antigos (compatibilidade)
            const oldModal = document.getElementById('prize-result-modal');
            if (oldModal) {
                oldModal.remove();
                console.log('✅ Modal antigo removido');
            }

            const simpleModal = document.getElementById('simple-prize-modal');
            if (simpleModal) {
                simpleModal.style.display = 'none';
                console.log('✅ Modal simples ocultado');
            }

            const oldPrizeModal = document.getElementById('prize-modal');
            if (oldPrizeModal) {
                oldPrizeModal.style.display = 'none';
                console.log('✅ Modal de prêmio antigo fechado');
            }

            // Limpar estado
            this.resetSpinState();
            
            console.log('✅ Modal de prêmio fechado com sucesso');
        } catch (error) {
            console.error('❌ Erro ao fechar modal de prêmio:', error);
            this.emergencyClean();
        }
    }

    /**
     * Abre o modal de depósito a partir do modal de prêmio
     */
    openDepositFromPrize() {
        console.log('💰 Abrindo modal de depósito...');
        
        try {
            // 1. Fechar completamente o modal da roleta
            const rouletteModal = document.getElementById('roulette-modal');
            if (rouletteModal) {
                rouletteModal.style.display = 'none';
                console.log('✅ Modal da roleta fechado');
            }

            // 2. Fechar modal de prêmio
            const prizeModal = document.getElementById('prize-modal-section');
            if (prizeModal) {
                prizeModal.style.display = 'none';
                console.log('✅ Modal de prêmio fechado');
            }

            // 3. Limpar estado da roleta completamente
            this.resetSpinState();
            
            // 4. Dar um pequeno delay para garantir que tudo foi fechado
            setTimeout(() => {
                // 5. Tentar diferentes seletores para o modal de depósito
                const depositModalSelectors = [
                    '#depositModal',
                    '#deposit-modal', 
                    '[data-modal="deposit"]',
                    '.deposit-modal'
                ];
                
                let depositModal = null;
                for (const selector of depositModalSelectors) {
                    depositModal = document.querySelector(selector);
                    if (depositModal) {
                        console.log(`✅ Modal de depósito encontrado com seletor: ${selector}`);
                        break;
                    }
                }

                if (depositModal) {
                    // Remover classes que possam estar escondendo o modal
                    depositModal.classList.remove('hidden', 'd-none', 'fade');
                    depositModal.style.display = 'flex';
                    depositModal.style.zIndex = '999999'; // Z-index maior que o da roleta (10000)
                    depositModal.style.position = 'fixed';
                    depositModal.style.top = '0';
                    depositModal.style.left = '0';
                    depositModal.style.width = '100vw';
                    depositModal.style.height = '100vh';
                    depositModal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
                    
                    // Garantir que o modal está visível
                    depositModal.setAttribute('aria-hidden', 'false');
                    depositModal.classList.add('show');
                    
                    console.log('✅ Modal de depósito aberto com z-index 999999');
                    
                    // Garantir que o scroll está bloqueado
                    document.body.style.overflow = 'hidden';
                    
                    // Adicionar evento para fechar o modal de depósito quando necessário
                    const closeDepositModal = () => {
                        document.body.style.overflow = '';
                        console.log('✅ Scroll restaurado ao fechar depósito');
                    };
                    
                    // Procurar botão de fechar no modal de depósito
                    const closeButtons = depositModal.querySelectorAll('[data-dismiss="modal"], .close, .btn-close');
                    closeButtons.forEach(btn => {
                        btn.addEventListener('click', closeDepositModal, { once: true });
                    });
                    
                } else {
                    console.warn('❌ Modal de depósito não encontrado em nenhum seletor');
                    console.log('🔍 Tentando encontrar modal de depósito por conteúdo...');
                    
                    // Buscar por modais que contenham texto relacionado a depósito
                    const allModals = document.querySelectorAll('[class*="modal"], [id*="modal"]');
                    for (const modal of allModals) {
                        const modalText = modal.textContent.toLowerCase();
                        if (modalText.includes('depósito') || modalText.includes('deposito') || 
                            modalText.includes('deposit') || modalText.includes('pix')) {
                            console.log('✅ Modal de depósito encontrado por conteúdo:', modal.id || modal.className);
                            modal.style.display = 'flex';
                            modal.style.zIndex = '999999';
                            depositModal = modal;
                            break;
                        }
                    }
                    
                    if (!depositModal) {
                        console.log('🔄 Redirecionando para página de depósito...');
                        // Fallback: redirecionar para página de depósito
                        window.open('/?d=show', '_blank');
                    }
                }
            }, 200);
            
        } catch (error) {
            console.error('❌ Erro ao abrir modal de depósito:', error);
            // Fallback: redirecionar para página de depósito
            console.log('🔄 Fallback: abrindo página de depósito...');
            window.open('/?d=show', '_blank');
        }
    }

    // ========================================
    // CONTROLES DE EMERGÊNCIA
    // ========================================
    setupEmergencyControls() {
        // Função global de emergência
        window.emergencyCleanRouletteState = () => this.emergencyClean();
        
        // Função global para fechar modal
        window.closePrizeModal = () => this.closePrizeModalSafe();
        
        // Função para fechar resultado (compatibilidade)
        window.closeRouletteResult = () => this.closePrizeModalSafe();
        
        // Função global para fechar completamente a roleta
        window.closeRouletteModal = () => {
            console.log('🚪 Fechando modal da roleta completamente...');
            
            // Fechar modal principal da roleta
            const rouletteModal = document.getElementById('roulette-modal');
            if (rouletteModal) {
                rouletteModal.style.display = 'none';
            }
            
            // Fechar todos os modais relacionados
            this.closePrizeModalSafe();
            
            console.log('✅ Roleta fechada completamente');
        };
        
        // Função para abrir depósito a partir do prêmio
        window.openDepositFromPrize = this.openDepositFromPrize.bind(this);
        
        // Função para copiar cupom
        window.copyToClipboard = (text) => {
            if (!text || text === 'undefined') {
                alert('Nenhum cupom disponível para copiar.');
                return;
            }
            
            navigator.clipboard.writeText(text).then(() => {
                if (typeof window.mostrarMensagemSucesso === 'function') {
                    window.mostrarMensagemSucesso(`📋 Cupom copiado: ${text}`);
                } else {
                    alert(`Cupom copiado: ${text}`);
                }
            }).catch(err => {
                console.error('Erro ao copiar:', err);
                alert(`Cupom: ${text}\n\nCopie manualmente`);
            });
        };
        
        // Função de emergência para mostrar prêmio simples
        window.showEmergencyPrize = (itemName = 'PRÊMIO') => {
            alert(`🎉 Parabéns! Você ganhou: ${itemName}`);
            if (window.rouletteInstance) {
                window.rouletteInstance.resetSpinState();
            }
        };

        // Auto-detecção de problemas
        setTimeout(() => {
            if (this.isBlurred() && !document.getElementById('prize-result-modal')) {
                console.warn('⚠️ Estado travado detectado! Executando limpeza...');
                this.emergencyClean();
            }
        }, 5000);

        // Função global de teste do mapeamento
        window.testRouletteMapping = (itemId) => {
            if (itemId) {
                return RouletteWheel.testMapping(itemId);
            } else {
                console.log('🎯 Testando todos os mapeamentos:');
                for (let i = 1; i <= 8; i++) {
                    RouletteWheel.testMapping(i);
                }
            }
        };

        // Função de teste para modal de depósito
        window.testDepositModal = () => {
            console.log('🧪 Testando modal de depósito...');
            this.openDepositFromPrize();
        };

        // Função de debug para testar formatação de valores
        window.debugFormatCurrency = (value) => {
            console.log('🧪 Testando formatação de valor:', value);
            console.log('📄 Tipo:', typeof value);
            console.log('💰 Resultado:', formatCurrencyValue(value));
            return formatCurrencyValue(value);
        };

        // Instruções no console
        console.log('%c🎰 ROLETA - Sistema de Emergência Ativo', 'color: #00ff41; font-size: 16px; font-weight: bold;');
        console.log('%c• ESC: Sair da roleta', 'color: #00ff41;');
        console.log('%c• emergencyCleanRouletteState(): Limpeza total', 'color: #ffff00; background: #000; padding: 2px;');
        console.log('%c• testRouletteMapping(ID): Testar mapeamento específico', 'color: #00ffff; background: #000; padding: 2px;');
        console.log('%c• testRouletteMapping(): Testar todos os mapeamentos', 'color: #00ffff; background: #000; padding: 2px;');
        console.log('%c• testDepositModal(): Testar abertura do modal de depósito', 'color: #ff00ff; background: #000; padding: 2px;');
        console.log('%c• openDepositFromPrize(): Abrir modal de depósito do prêmio', 'color: #ff8000; background: #000; padding: 2px;');
        console.log('%c• debugFormatCurrency(valor): Testar formatação de valores monetários', 'color: #8000ff; background: #000; padding: 2px;');
    }

    // ========================================
    // MODAIS DE ERRO
    // ========================================
    showError(message) {
        if (this.errorModal && this.errorMessage) {
            this.errorMessage.textContent = message;
            this.errorModal.style.display = 'flex';
        } else {
            alert(message);
        }
    }

    hideErrorModal() {
        if (this.errorModal) {
            this.errorModal.style.display = 'none';
        }
    }
}

// ========================================
// INICIALIZAÇÃO AUTOMÁTICA
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    try {
        const wheelContainer = document.querySelector('.wheel-container');
        const roletaElement = document.querySelector('#roleta, .section-roleta');
        
        if (wheelContainer) {
            console.log('🎰 Inicializando roleta tipo wheel');
            window.rouletteInstance = new Roulette('wheel');
        } else if (roletaElement) {
            console.log('🎰 Inicializando roleta tipo image');
            window.rouletteInstance = new Roulette('image');
        } else {
            console.warn('⚠️ Nenhum elemento de roleta encontrado na página');
        }
    } catch (error) {
        console.error('❌ Erro ao inicializar roleta:', error);
    }
});

// ========================================
// EXPORTAÇÕES GLOBAIS
// ========================================
if (typeof window !== 'undefined') {
    window.Roulette = Roulette;
    window.RouletteConfig = RouletteConfig;
}
