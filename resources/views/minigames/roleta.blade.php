<!-- Modal da Roleta -->
<div id="roulette-modal" class="modal-overlay roulette-modal-overlay" style="
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.8);
    z-index: 9999;
    justify-content: center;
    align-items: center;
">
    <div class="container-link">
        <a class="redirecionar" href="#" target="_blank"></a>
    </div>

    <div id="errorModal" class="error-modal">
        <div class="error-modal-content">
            <p id="errorMessage"></p>
            <button id="closeErrorModal">Fechar</button>
        </div>
    </div>

    <!-- Botão de fechar -->
    <button onclick="window.closeRouletteModal()" style="
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(0, 0, 0, 0.7);
        color: #00ff41;
        border: 2px solid #00ff41;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
        font-weight: bold;
        z-index: 1000;
        transition: all 0.3s ease;
    " onmouseover="this.style.background='#00ff41'; this.style.color='#000';" 
       onmouseout="this.style.background='rgba(0, 0, 0, 0.7)'; this.style.color='#00ff41';">
        ×
    </button>

    <section class="section-roleta">
        <img class="tituloRoleta" src="{{ asset('img/roleta/titulo.png') }}" alt="tituloRoleta">
        
        <!-- Modal de Prêmio -->
        <div id="prize-modal-section" class="prize-modal-section" style="
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.9);
            z-index: 99998;
            justify-content: center;
            align-items: center;
        ">
            <div class="prize-modal-content" style="
                background: linear-gradient(145deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
                border: 2px solid #00ff41;
                border-radius: 16px;
                padding: 0;
                max-width: 380px;
                width: 90%;
                text-align: center;
                position: relative;
                box-shadow: 0 15px 35px rgba(0, 255, 65, 0.4), 0 5px 15px rgba(0, 0, 0, 0.3);
                overflow: hidden;
            ">
                <!-- Botão fechar -->
                <button onclick="window.closePrizeModal()" style="
                    position: absolute;
                    top: 15px;
                    right: 15px;
                    background: transparent;
                    color: #00ff41;
                    border: none;
                    font-size: 24px;
                    cursor: pointer;
                    z-index: 1001;
                ">×</button>

                <!-- Título do prêmio -->
                <div class="prize-title-container" style="
                    background: linear-gradient(135deg, #00ff41, #32ff32);
                    margin: 16px;
                    margin-top: 24px;
                    padding: 16px;
                    border-radius: 12px;
                    color: #000;
                    font-size: 22px;
                    font-weight: 800;
                    text-transform: uppercase;
                    letter-spacing: 2px;
                    box-shadow: 0 6px 16px rgba(0, 255, 65, 0.4);
                    border: 1px solid #32ff32;
                ">
                    <img class="premio-titulo" src="{{ asset('uploads/roulettes/67f56ebc54f57_Cópia de SEU PRÊMIO (1).png') }}" alt="Seu Prêmio" style="max-width: 160px; margin-bottom: 8px;">
                    <div id="prize-title">🎉 PARABÉNS! 🎉</div>
                </div>

                <!-- Conteúdo do prêmio -->
                <div class="prize-content" style="
                    background: linear-gradient(135deg, #ffffff, #f0fff0);
                    border: 2px solid #00ff41;
                    margin: 16px;
                    padding: 18px;
                    border-radius: 12px;
                    color: #003d1a;
                    font-size: 20px;
                    font-weight: 800;
                    text-transform: uppercase;
                    line-height: 1.3;
                    box-shadow: 0 6px 20px rgba(0, 255, 65, 0.2);
                ">
                    <div id="prize-value"></div>
                </div>

                <!-- Instruções de depósito (quando necessário) -->
                <div id="deposit-instruction" class="deposit-instruction" style="
                    background: linear-gradient(135deg, #e6ffe6, #ccffcc);
                    border: 1px solid #00cc33;
                    margin: 16px;
                    padding: 14px;
                    border-radius: 10px;
                    color: #004d1a;
                    font-size: 14px;
                    font-weight: 600;
                    line-height: 1.4;
                    display: none;
                ">
                    💰 DEPOSITE E USE O CUPOM ABAIXO PARA RESGATAR SEU PRÊMIO:
                </div>

                <!-- Cupom (quando disponível) -->
                <div id="coupon-section" class="coupon-section" style="display: none; margin: 16px;">
                    <div id="coupon-display" onclick="window.copyToClipboard(this.dataset.coupon)" style="
                        background: linear-gradient(135deg, #00ff41, #32ff32, #00cc33);
                        border: 2px solid #00ff41;
                        padding: 18px;
                        border-radius: 12px;
                        color: #000;
                        font-size: 18px;
                        font-weight: 800;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        cursor: pointer;
                        box-shadow: 0 8px 20px rgba(0, 255, 65, 0.4);
                        transition: all 0.3s ease;
                    ">
                        <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
                            <span style="font-size: 24px;">🎟️</span>
                            <span>CUPOM</span>
                            <span id="coupon-code" style="background: rgba(255,255,255,0.3); padding: 6px 12px; border-radius: 6px;"></span>
                        </div>
                        <div style="font-size: 12px; margin-top: 8px; color: #004d1a;">
                            👆 CLIQUE PARA COPIAR 👆
                        </div>
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="prize-actions" style="padding: 16px;">
                    <!-- Botão Depositar -->
                    <button id="deposit-button" onclick="window.openDepositFromPrize(); return false;" style="
                        background: linear-gradient(135deg, #00ff41, #32ff32);
                        color: #000;
                        border: 2px solid #00cc33;
                        padding: 14px 32px;
                        border-radius: 10px;
                        cursor: pointer;
                        font-size: 16px;
                        font-weight: 700;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        margin-bottom: 12px;
                        width: 100%;
                        box-shadow: 0 6px 16px rgba(0, 255, 65, 0.3);
                        transition: all 0.3s ease;
                        display: none;
                    ">
                        💳 DEPOSITAR AGORA 💳
                    </button>

                    <!-- Botão Cadastrar -->
                    <button id="register-button" onclick="window.openRegisterFromPrize(); return false;" style="
                        background: linear-gradient(135deg, #ff8c00, #ffa500);
                        color: #000;
                        border: 2px solid #e67e00;
                        padding: 14px 32px;
                        border-radius: 10px;
                        cursor: pointer;
                        font-size: 16px;
                        font-weight: 700;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        margin-bottom: 12px;
                        width: 100%;
                        box-shadow: 0 6px 16px rgba(255, 140, 0, 0.3);
                        transition: all 0.3s ease;
                        display: none;
                    ">
                        📝 CADASTRAR-SE AGORA 📝
                    </button>

                    <!-- Botão Fechar -->
                    <button onclick="window.closePrizeModal()" style="
                        background: linear-gradient(135deg, #444, #666);
                        color: #00ff41;
                        border: 1px solid #00ff41;
                        padding: 10px 24px;
                        border-radius: 8px;
                        cursor: pointer;
                        font-size: 14px;
                        font-weight: 600;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        transition: all 0.3s ease;
                        width: 100%;
                        box-shadow: 0 4px 12px rgba(0, 255, 65, 0.2);
                    ">
                        ❌ FECHAR ❌
                    </button>
                </div>
            </div>
        </div>

        <!-- Contador de giros -->
        <div class="spin-counter-container" style="text-align: center; margin-bottom: 20px;">
            <p class="spin-counter" style="color: #ff7300; font-weight: bold; font-size: 16px;">
                Carregando...
            </p>
        </div>

        <div class="container-roleta">
            <img class="bem-vindo" src="{{ asset('img/roleta/premio.png') }}" alt="">
            <div>
                <img class="roleta" src="{{ asset('img/roleta/roleta.png') }}" id="roleta">
                <div class="roleta-funcional">
                    <div class="one"></div>
                    <div class="two"></div>
                    <div class="three"></div>
                    <div class="four"></div>
                    <div class="five"></div>
                    <div class="six"></div>
                    <div class="seven"></div>
                    <div class="eight"></div>
                </div>
            </div>
            <img class="btn-girar" src="{{ asset('img/roleta/girar.png') }}" alt="">
        </div>
    </section>
</div>

<!-- Modal de Prêmio -->
<div id="prize-modal" class="prize-modal">
    <div id="body-roleta" class="body-roleta">
        <div id="goodLuck" class="popup-modal">
            <p class="line1">Parabéns!</p>
            <p class="line2">VOCÊ GANHOU</p>
            <p class="line3">DEPOSITE R$ 0,00 e use o cupom abaixo e resgate seu prêmio:</p>
            <p class="line4">
                <img class="premio" src="{{ asset('img/roleta/cupom.png') }}">
                <span class="codigo" id="promoCode" style="cursor: pointer; user-select: all;" title="Clique para copiar">CODIGO</span>
            </p>
            <button id="btn-click" style="
                background: linear-gradient(135deg, #000000, #000000);
                color: #ff7300;
                border: none;
                padding: 15px 30px;
                border-radius: 5px;
                font-size: 16px;
                font-weight: bold;
                cursor: pointer;
                margin: 10px 0;
                transition: all 0.3s ease;">
                <a href="http://virtus.br.com//?d=show" target="_blank" style="color: inherit; text-decoration: none;">
                    Deposite agora
                </a>
            </button>
            <a class="closeButton" href="#" onclick="document.getElementById('prize-modal').style.display='none'; return false;" style="
                background: #000000;
                color: #ff6c0a;
                padding: 10px 20px;
                border-radius: 5px;
                text-decoration: none;
                font-weight: bold;
                display: inline-block;
                margin-top: 10px;
                transition: all 0.3s ease;">
                FECHAR MENSAGEM
            </a>
        </div>
    </div>
</div>

<!-- Scripts e estilos -->
<link rel="stylesheet" href="{{ asset('css/roleta-animated.css') }}">
<link rel="stylesheet" href="{{ asset('css/roletas.css') }}">
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<!-- Definir variáveis JavaScript necessárias -->
<script>
    window.ROULETTE_ID = 1; // ID da roleta - pode ser dinâmico se necessário
    
    // Garantir que o CSRF token esteja disponível
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }
</script>

<script src="{{ asset('js/roulette-config.js') }}?v={{ time() }}&fix=tofixed-error"></script>

<style>
    .line1 {
        color: #ff7300;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .line2 {
        background-color: #ffffff;
        color: #ff7300;
        padding: 10px;
        border-radius: 5px;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    .line3 {
        background-color: #ffffff;
        color: #ff7300;
        padding: 10px;
        border-radius: 5px;
        font-size: 16px;
        margin-bottom: 15px;
        text-align: center;
    }
    .line4 {
        background-color: #a600ff;
        color: #ff7300;
        padding: 15px;
        border-radius: 5px;
        text-align: center;
        margin-bottom: 20px;
    }
    
    .codigo {
        background: rgba(255, 255, 255, 0.9);
        color: #000;
        padding: 8px 15px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 18px;
        margin-left: 10px;
        border: 2px solid #ff7300;
        transition: all 0.3s ease;
    }
    
    .codigo:hover {
        background: #ff7300;
        color: #fff;
        transform: scale(1.05);
    }
    
    .spin-counter-container {
        background: rgba(0, 0, 0, 0.7);
        padding: 10px;
        border-radius: 10px;
        border: 2px solid #ff7300;
    }
    
    .spin-counter {
        margin: 0;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
    }
    
    .free-spins-info {
        background: rgba(0, 255, 0, 0.1);
        border: 2px solid #00ff00;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .prize-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 99999;
    }
    
    .body-roleta {
        position: relative;
        z-index: 100000;
        max-width: 90%;
        max-height: 90%;
        overflow: auto;
    }
    
    .popup-modal {
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        max-width: 500px;
        width: 100%;
    }
    
    .error-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 10000;
    }
    
    .error-modal-content {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }
    
    .error-modal-content p {
        color: #333;
        font-size: 16px;
        margin-bottom: 20px;
        line-height: 1.5;
    }
    
    .error-modal-content button {
        background: #ff7300;
        color: #fff;
        border: none;
        padding: 12px 25px;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .error-modal-content button:hover {
        background: #e65a00;
        transform: translateY(-2px);
    }
</style>

<!-- Biblioteca de Confetti -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>