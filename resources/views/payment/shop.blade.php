@extends('layouts.app')

@section('content')
<div class="cHh-b">
    <div class="eNFX6">
        <header class="PAItV">
            <div class="smUTk">
                <button class="_8s2Sx">
                    <a class="nuxt-icon nuxt-icon--fill" href="{{ route('home') }}">
                        <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"
                                fill="currentColor"
                            ></path>
                        </svg>
                    </a>
                </button>
                <h1 class="hFWlQ"><span>{{ __('lucky-box.title') }}</span></h1>
                <div class="_0UgdA">
                    <button>
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M449.9 39.96l-48.5 48.53C362.5 53.19 311.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.97 5.5 34.86-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c37.96 0 73 14.18 100.2 37.8L311.1 178C295.1 194.8 306.8 223.4 330.4 224h146.9C487.7 223.7 496 215.3 496 204.9V59.04C496 34.99 466.9 22.95 449.9 39.96z"
                                    fill="currentColor"
                                ></path>
                                <path
                                    d="M462.4 329.8C433.4 419.7 350.4 480 255.1 480c-55.41 0-106.5-21.19-145.4-56.49l-48.5 48.53C45.07 489 16 477 16 452.1V307.1C16 296.7 24.32 288.3 34.66 288h146.9c23.57 .5781 35.26 29.15 18.43 46l-44.18 44.2C183 401.8 218 416 256 416c66.58 0 125.1-42.53 145.5-105.8c5.422-16.78 23.36-26.03 40.3-20.59C458.6 294.1 467.9 313 462.4 329.8z"
                                    fill="currentColor"
                                    opacity="0.4"
                                ></path>
                            </svg>
                        </span>
                    </button>
                    <button>
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M505 41L320 225.93V488c0 19.51-22 30.71-37.76 19.66l-80-56A24 24 0 0 1 192 432V226L7 41C-8 25.87 2.69 0 24 0h464c21.33 0 32 25.9 17 41z" fill="currentColor" opacity="0.4"></path>
                            </svg>
                        </span>
                    </button>
                    <button>
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M500.3 443.7l-119.7-119.7c-15.03 22.3-34.26 41.54-56.57 56.57l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7z" fill="currentColor"></path>
                                <path
                                    d="M207.1 0C93.12 0-.0002 93.13-.0002 208S93.12 416 207.1 416s208-93.13 208-208S322.9 0 207.1 0zM207.1 336c-70.58 0-128-57.42-128-128c0-70.58 57.42-128 128-128s128 57.42 128 128C335.1 278.6 278.6 336 207.1 336z"
                                    fill="currentColor"
                                    opacity="0.4"
                                ></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
            <div class="Z5sev">
                <div class="jZKqA">
                    <span class="nuxt-icon nuxt-icon--fill">
                        <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M449.9 39.96l-48.5 48.53C362.5 53.19 311.4 32 256 32C161.5 32 78.59 92.34 49.58 182.2c-5.438 16.81 3.797 34.88 20.61 40.28c16.97 5.5 34.86-3.812 40.3-20.59C130.9 138.5 189.4 96 256 96c37.96 0 73 14.18 100.2 37.8L311.1 178C295.1 194.8 306.8 223.4 330.4 224h146.9C487.7 223.7 496 215.3 496 204.9V59.04C496 34.99 466.9 22.95 449.9 39.96z"
                                fill="currentColor"
                            ></path>
                            <path
                                d="M462.4 329.8C433.4 419.7 350.4 480 255.1 480c-55.41 0-106.5-21.19-145.4-56.49l-48.5 48.53C45.07 489 16 477 16 452.1V307.1C16 296.7 24.32 288.3 34.66 288h146.9c23.57 .5781 35.26 29.15 18.43 46l-44.18 44.2C183 401.8 218 416 256 416c66.58 0 125.1-42.53 145.5-105.8c5.422-16.78 23.36-26.03 40.3-20.59C458.6 294.1 467.9 313 462.4 329.8z"
                                fill="currentColor"
                                opacity="0.4"
                            ></path>
                        </svg>
                    </span>
                </div>
                <div class="exzws">
                    <div class="FFPEW">
                        <button id="order-by-level-btn" type="button" aria-haspopup="listbox" aria-expanded="false" data-headlessui-state="" class="Ks1vs"><span>{{ __('lucky-box.order_by') }}</span><strong>{{ __('lucky-box.level') }}</strong></button>
                        <!---->
                    </div>
                </div>
            </div>
        </header>
        <!---->
        <h4 class="gamificationPageTitle">
            <span class="nuxt-icon nuxt-icon--fill icon">
                <svg height="1em" fill="var(--primary-color)" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M398.957 153.438C396.339 145.339 389.155 139.586 380.655 138.82L265.205 128.337L219.552 21.4828C216.186 13.6518 208.52 8.5827 200.002 8.5827C191.484 8.5827 183.818 13.6518 180.452 21.5011L134.8 128.337L19.3303 138.82C10.8462 139.604 3.68046 145.339 1.04673 153.438C-1.58701 161.538 0.845308 170.422 7.26332 176.022L94.5306 252.556L68.7975 365.91C66.9145 374.245 70.1495 382.86 77.0649 387.859C80.7821 390.544 85.1309 391.912 89.5164 391.912C93.2976 391.912 97.0483 390.892 100.415 388.878L200.002 329.358L299.553 388.878C306.838 393.261 316.021 392.861 322.921 387.859C329.839 382.845 333.071 374.226 331.188 365.91L305.455 252.556L392.722 176.037C399.14 170.422 401.591 161.553 398.957 153.438Z" fill="var(--primary-color)"></path>
                </svg>
            </span>
            <span>{{ __('lucky-box.title') }}</span>
        </h4>
        <div class="Yylzs" id="boxesContainer">
            @php
            // Define a imagem padrão para ser usada se não houver imagem
            $defaultBoxImage = asset('img/box/luckbox2.png');
            @endphp
            
            @forelse($boxes as $box)
                <div class="defaultItemBox" data-level="{{ $box->level }}" data-id="{{ $box->id }}">
                    <div class="imgWrap">
                        <img 
                            alt="{{ $box->name }}" 
                            src="{{ !empty($box->image) ? $box->image : $defaultBoxImage }}" 
                        />
                    </div>
                    <div class="defaultBoxContent">
                        <div class="titleBox">{{ $box->name }}</div>
                        <div class="descriptionBox">{{ $box->description }}</div>
                        <div class="priceBox" style="background-image: url('{{ asset('img/coin.png') }}');">{{ $box->price }}</div>
                        <div class="actionBox">
                            <button onclick="openLuckyBox({{ $box->level }}, {{ $box->id }})"><span>{{ __('lucky-box.open_box') }}</span></button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    {{ __('lucky-box.no_boxes_available') }}
                </div>
            @endforelse
        </div>

        <!-- Modal para exibir o resultado -->
        <div id="resultModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.7);">
            <div id="confetti-container" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; overflow: hidden; z-index: 1001;"></div>
            <div class="modal-content" style="background-color: #333; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 10px; text-align: center; box-shadow: 0 0 20px rgba(255,215,0,0.7); position: relative; z-index: 1002;">
                <h2 id="resultTitle" style="color: var(--primary-color); margin-top: 0; font-size: 32px; text-shadow: 0 0 5px rgba(255,215,0,0.5);">{{ __('lucky-box.congratulations') }}</h2>
                <div id="boxName" style="color: #cccccc; margin-bottom: 15px; font-size: 16px;">Caixa da Sorte</div>
                <div id="prizeImage" style="margin: 20px auto; display: flex; justify-content: center; align-items: center;">
                    <img src="{{ asset('img/coin.png') }}" alt="Prize" style="max-width: 100px; max-height: 100px;" class="prize-animation">
                </div>
                <div style="background-color: rgba(0,0,0,0.3); padding: 15px; border-radius: 8px; margin: 15px 0;">
                    <div id="prizeType" style="font-size: 18px; margin-bottom: 8px; color: #a5a5a5;">Tipo do Prêmio</div>
                    <div id="prizeResult" style="font-size: 28px; font-weight: bold; color: var(--primary-color);">Valor do Prêmio</div>
                </div>
                <div id="encouragementMessage" style="font-size: 16px; margin: 15px 0; color: #aaaaaa; background-color: rgba(0,0,0,0.2); padding: 10px; border-radius: 5px;">Tente novamente! Cada caixa tem prêmios variados.</div>
                <button onclick="closeModal()" style="background-color: var(--primary-color); color: #333; border: none; padding: 10px 25px; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 15px; font-size: 16px;">{{ __('lucky-box.close') }}</button>
            </div>
        </div>

        <script>
            // Função para mostrar toasts de notificação
            function mostrarToast(mensagem, tipo) {
                // Remove existing toasts
                const existingToasts = document.querySelectorAll('.status-popup');
                existingToasts.forEach(toast => {
                    document.body.removeChild(toast);
                });
                
                // Create toast container
                const toast = document.createElement('div');
                toast.className = `status-popup status-popup-${tipo}`;
                
                // Create icon
                const icon = document.createElement('div');
                icon.className = `status-icon status-icon-${tipo}`;
                
                if (tipo === 'success') {
                    icon.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 6L9 17L4 12" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                } else {
                    icon.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 6L6 18" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 6L18 18" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>';
                }
                
                // Create message
                const message = document.createElement('div');
                message.className = 'status-message';
                message.textContent = mensagem;
                
                // Create close button
                const close = document.createElement('div');
                close.className = 'status-close';
                close.textContent = '×';
                close.onclick = function() {
                    document.body.removeChild(toast);
                };
                
                // Create progress bar
                const progress = document.createElement('div');
                progress.className = `status-progress-${tipo}`;
                
                // Append elements
                toast.appendChild(icon);
                toast.appendChild(message);
                toast.appendChild(close);
                toast.appendChild(progress);
                
                // Add to document
                document.body.appendChild(toast);
                
                // Auto close after animation
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                        toast.classList.add('hide');
                        setTimeout(() => {
                            if (document.body.contains(toast)) {
                                document.body.removeChild(toast);
                            }
                        }, 500);
                    }
                }, 5000);
                
                return toast;
            }

            function openLuckyBox(level, id) {
                // Identificar apenas o botão da caixa clicada
                const clickedBox = document.querySelector(`.defaultItemBox[data-level="${level}"]`);
                if (!clickedBox) {
                    console.error(`Caixa com level ${level} não encontrada`);
                    return;
                }
                
                const clickedButton = clickedBox.querySelector('.actionBox button');
                if (!clickedButton) {
                    console.error('Botão não encontrado na caixa');
                    return;
                }
                
                // Desabilitar apenas o botão da caixa clicada
                clickedButton.disabled = true;
                clickedButton.innerHTML = '<span>{{ __('lucky-box.opening') }}</span>';
                
                // Fazer requisição AJAX para o servidor
                fetch('/lucky-box/open', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        level: level,
                        id: id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    
                    // Reativar apenas o botão da caixa clicada
                    clickedButton.disabled = false;
                    clickedButton.innerHTML = '<span>{{ __('lucky-box.open_box') }}</span>';
                    
                    if (data.success) {
                        // Definir o título e a mensagem de incentivo diretamente da resposta do servidor
                        const resultTitle = document.getElementById('resultTitle');
                        if (resultTitle) {
                            resultTitle.textContent = data.title || '{{ __('lucky-box.congratulations') }}';
                        }
                        
                        // Validar o tipo de prêmio
                        const prizeType = data.prize_type || 'unknown';
                        
                        // Configurar o tipo e valor do prêmio
                        let prizeMessage = '';
                        let prizeTypeText = '';
                        let prizeImage = '{{ asset('img/coin.png') }}';
                        
                        switch(prizeType) {
                            case 'real_balance':
                                prizeMessage = `R$ ${parseFloat(data.amount).toFixed(2)}`;
                                prizeTypeText = 'Saldo Real';
                                prizeImage = '{{ asset('img/money.png') }}';
                                break;
                            case 'bonus':
                                prizeMessage = `R$ ${parseFloat(data.amount).toFixed(2)}`;
                                prizeTypeText = 'Saldo Bônus';
                                prizeImage = '{{ asset('img/bonus.png') }}';
                                break;
                            case 'free_spins':
                                const spinsAmount = parseInt(data.spins_amount) || 0;
                                prizeMessage = `${spinsAmount} Rodadas`;
                                prizeTypeText = 'Rodadas Grátis';
                                prizeImage = '{{ asset('img/spin.png') }}';
                                break;
                            case 'coins':
                                const coinsAmount = parseFloat(data.amount) || 0;
                                prizeMessage = `${coinsAmount} Coins`;
                                prizeTypeText = 'Coins';
                                prizeImage = '{{ asset('img/coin.png') }}';
                                break;
                            default:
                                if (data.title === 'Que pena!') {
                                    prizeMessage = 'Não ganhou nada';
                                    prizeTypeText = 'Sem prêmio';
                                    prizeImage = '{{ asset('img/coin.png') }}';
                                } else {
                                    const defaultAmount = parseFloat(data.amount) || 0;
                                    prizeMessage = `${defaultAmount} Coins`;
                                    prizeTypeText = 'Coins';
                                    prizeImage = '{{ asset('img/coin.png') }}';
                                }
                                break;
                        }
                        
                        // Definir os valores nos elementos HTML com verificação de existência
                        const prizeTypeElement = document.getElementById('prizeType');
                        if (prizeTypeElement) {
                            prizeTypeElement.textContent = prizeTypeText;
                        }
                        
                        const prizeResultElement = document.getElementById('prizeResult');
                        if (prizeResultElement) {
                            prizeResultElement.textContent = prizeMessage;
                            prizeResultElement.style.color = (data.title === 'Que pena!') ? '#f8f8f8' : 'var(--primary-color)';
                        }
                        
                        // Definir a mensagem de incentivo
                        const encouragementElement = document.getElementById('encouragementMessage');
                        if (encouragementElement) {
                            encouragementElement.textContent = data.message || 'Tente novamente! Cada caixa tem prêmios variados.';
                        }
                        
                        // Mostrar o nome da caixa
                        const boxNameElement = document.getElementById('boxName');
                        if (boxNameElement) {
                            boxNameElement.textContent = data.box_name ? `${data.box_name}` : 'Caixa da Sorte';
                        }
                        
                        // Atualizar a imagem do prêmio
                        updatePrizeImage(prizeImage, prizeTypeText);
                        
                        // Iniciar animação do prêmio
                        startPrizeAnimation();
                        
                        // Atualizar os saldos do usuário na interface
                        updateUserBalances(data);
                        
                        // Exibir o modal
                        const resultModal = document.getElementById('resultModal');
                        if (resultModal) {
                            resultModal.style.display = 'block';
                            
                            // Iniciar efeito de confete apenas se for uma vitória
                            if (data.title !== 'Que pena!') {
                                createConfetti();
                            }
                        }
                    } else {
                        // Exibir erro como toast
                        mostrarToast(data.message || '{{ __('lucky-box.error') }}', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    // Usar toast para mostrar erro
                    mostrarToast('{{ __('lucky-box.error') }}', 'error');
                    
                    // Reativar o botão da caixa clicada em caso de erro
                    clickedButton.disabled = false;
                    clickedButton.innerHTML = '<span>{{ __('lucky-box.open_box') }}</span>';
                });
            }
            
            // Função para atualizar a imagem do prêmio com tratamento de erros
            function updatePrizeImage(imageSrc, altText) {
                const imgElement = document.querySelector('#prizeImage img');
                if (!imgElement) {
                    console.warn('Elemento de imagem do prêmio não encontrado');
                    return;
                }
                
                // Criar uma imagem temporária para verificar se a fonte existe
                const tempImg = new Image();
                tempImg.onload = function() {
                    // A imagem carregou com sucesso
                    imgElement.src = imageSrc;
                    imgElement.alt = altText;
                };
                tempImg.onerror = function() {
                    // Erro ao carregar a imagem, usar imagem de fallback
                    console.warn('Erro ao carregar imagem:', imageSrc);
                    imgElement.src = '{{ asset('img/coin.png') }}';
                    imgElement.alt = altText;
                };
                tempImg.src = imageSrc;
            }
            
            // Função para iniciar a animação do prêmio
            function startPrizeAnimation() {
                const imgElement = document.querySelector('#prizeImage img');
                if (!imgElement) return;
                
                // Remover qualquer animação anterior
                imgElement.style.animation = 'none';
                
                // Forçar um reflow para reiniciar a animação
                void imgElement.offsetWidth;
                
                // Adicionar a nova animação
                imgElement.style.animation = 'prizeReveal 1s ease-out, prizeFloat 2s ease-in-out infinite';
            }
            
            // Função para atualizar os saldos do usuário
            function updateUserBalances(data) {
                // Verificar a existência dos elementos antes de tentar atualizar
                if (data.user_balance !== undefined) {
                    const balanceElement = document.getElementById('user-balance');
                    if (balanceElement) {
                        balanceElement.textContent = `R$ ${parseFloat(data.user_balance).toFixed(2)}`;
                    }
                }
                if (data.user_bonus !== undefined) {
                    const bonusElement = document.getElementById('user-bonus');
                    if (bonusElement) {
                        bonusElement.textContent = `R$ ${parseFloat(data.user_bonus).toFixed(2)}`;
                    }
                }
                if (data.user_free_spins !== undefined) {
                    const spinsElement = document.getElementById('user-free-spins');
                    if (spinsElement) {
                        spinsElement.textContent = data.user_free_spins;
                    }
                }
                if (data.user_coins !== undefined) {
                    const coinsElement = document.getElementById('user-coins');
                    if (coinsElement) {
                        coinsElement.textContent = data.user_coins;
                    }
                }
            }
            
            function closeModal() {
                document.getElementById('resultModal').style.display = 'none';
                // Parar animação de confete limpando o container
                const confettiContainer = document.getElementById('confetti-container');
                if (confettiContainer) {
                    confettiContainer.innerHTML = '';
                }
            }
            
            // Fechar o modal se o usuário clicar fora dele
            window.onclick = function(event) {
                const modal = document.getElementById('resultModal');
                if (event.target === modal) {
                    closeModal();
                }
            }

            // Função para ordenar as caixas por nível
            document.addEventListener('DOMContentLoaded', function() {
                const orderByLevelBtn = document.getElementById('order-by-level-btn');
                let ascendingOrder = true;

                orderByLevelBtn.addEventListener('click', function() {
                    ascendingOrder = !ascendingOrder;
                    orderBoxesByLevel(ascendingOrder);
                });

                function orderBoxesByLevel(ascending) {
                    const boxesContainer = document.getElementById('boxesContainer');
                    const boxes = Array.from(boxesContainer.querySelectorAll('.defaultItemBox'));
                    
                    boxes.sort((a, b) => {
                        // Obter o nível de um atributo data que adicionaremos aos elementos
                        const levelA = parseInt(a.dataset.level || 0);
                        const levelB = parseInt(b.dataset.level || 0);
                        
                        return ascending ? levelA - levelB : levelB - levelA;
                    });
                    
                    // Limpar o container
                    boxesContainer.innerHTML = '';
                    
                    // Adicionar os boxes ordenados
                    boxes.forEach(box => {
                        boxesContainer.appendChild(box);
                    });
                }
            });

            // Função para criar o efeito de confete/glitter
            function createConfetti() {
                const confettiContainer = document.getElementById('confetti-container');
                if (!confettiContainer) return;
                
                // Limpar qualquer confete anterior
                confettiContainer.innerHTML = '';
                
                // Criar 150 elementos de confete (aumentado de 100)
                const colors = ['var(--primary-color)', '#ff3e9d', '#0099ff', '#22ff00', '#ffff00', '#ff00ff', 'white', '#ffd700'];
                const shapes = ['circle', 'square', 'triangle', 'star'];
                
                for (let i = 0; i < 150; i++) {
                    const confetti = document.createElement('div');
                    const color = colors[Math.floor(Math.random() * colors.length)];
                    const shape = shapes[Math.floor(Math.random() * shapes.length)];
                    const size = Math.random() * 12 + 8; // 8-20px (aumentado)
                    
                    confetti.className = 'confetti-piece';
                    confetti.style.position = 'absolute';
                    confetti.style.width = `${size}px`;
                    confetti.style.height = `${size}px`;
                    confetti.style.backgroundColor = color;
                    confetti.style.opacity = Math.random() * 0.8 + 0.4; // 0.4-1.2 (aumentado)
                    confetti.style.borderRadius = shape === 'circle' ? '50%' : shape === 'square' ? '0' : shape === 'triangle' ? '0' : '50% 0 50% 50%';
                    
                    if (shape === 'triangle') {
                        confetti.style.width = '0';
                        confetti.style.height = '0';
                        confetti.style.backgroundColor = 'transparent';
                        confetti.style.borderLeft = `${size}px solid transparent`;
                        confetti.style.borderRight = `${size}px solid transparent`;
                        confetti.style.borderBottom = `${size * 1.5}px solid ${color}`;
                    } else if (shape === 'star') {
                        confetti.style.clipPath = 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)';
                    }
                    
                    // Distribuir por toda a tela, não apenas no modal
                    confetti.style.left = `${Math.random() * 100}%`;
                    confetti.style.top = `${Math.random() * 20 - 20}%`; // Começar de cima (e um pouco acima da tela)
                    confetti.style.transform = `rotate(${Math.random() * 360}deg)`;
                    
                    // Aumentar o brilho
                    confetti.style.boxShadow = `0 0 ${Math.random() * 15 + 10}px ${color}`;
                    confetti.style.filter = 'brightness(1.5)';
                    confetti.style.zIndex = '1001';
                    
                    // Adicionar animação com diferentes durações e atrasos
                    const duration = Math.random() * 4 + 3; // 3-7s (mais lento)
                    const delay = Math.random() * 3; // 0-3s
                    
                    // Animar queda e rotação
                    confetti.style.animation = `confettiFall ${duration}s ease-in ${delay}s forwards, 
                                               confettiRotate ${duration / 2}s linear ${delay}s infinite,
                                               confettiShine ${duration / 4}s ease-in-out ${delay}s infinite alternate`;
                    
                    confettiContainer.appendChild(confetti);
                }
                
                // Adicionar algumas partículas extras grandes e brilhantes
                for (let i = 0; i < 20; i++) {
                    const glitter = document.createElement('div');
                    const color = colors[Math.floor(Math.random() * colors.length)];
                    const size = Math.random() * 20 + 15; // 15-35px (partículas grandes)
                    
                    glitter.className = 'glitter-piece';
                    glitter.style.position = 'absolute';
                    glitter.style.width = `${size}px`;
                    glitter.style.height = `${size}px`;
                    glitter.style.backgroundColor = 'transparent';
                    glitter.style.borderRadius = '50%';
                    glitter.style.opacity = Math.random() * 0.4 + 0.6; // 0.6-1.0
                    
                    // Distribuir pela tela
                    glitter.style.left = `${Math.random() * 100}%`;
                    glitter.style.top = `${Math.random() * 20 - 20}%`;
                    
                    // Efeito de brilho forte
                    glitter.style.boxShadow = `0 0 ${size * 2}px ${size / 2}px ${color}`;
                    glitter.style.filter = 'brightness(1.8)';
                    glitter.style.zIndex = '1001';
                    
                    // Animação de brilho pulsante e queda
                    const duration = Math.random() * 5 + 5; // 5-10s (mais lento)
                    const delay = Math.random() * 2; // 0-2s
                    
                    glitter.style.animation = `confettiFall ${duration}s ease-in ${delay}s forwards, 
                                              glitterPulse ${duration / 8}s ease-in-out ${delay}s infinite alternate`;
                    
                    confettiContainer.appendChild(glitter);
                }
            }
        </script>
    </div>
</div>
@endsection
<style>
    /* Estilo para toasts */
    .status-popup {
        position: fixed;
        top: 20px;
        right: 20px;
        display: flex;
        align-items: center;
        background-color: rgba(33, 33, 33, 0.9);
        border-radius: 4px;
        padding: 12px 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        max-width: 320px;
        z-index: 9999;
        animation: slideIn 0.3s ease forwards;
    }
    
    .status-popup.hide {
        animation: slideOut 0.3s ease forwards;
    }
    
    .status-popup-success {
        border-left: 4px solid #4CAF50;
    }
    
    .status-popup-error {
        border-left: 4px solid #F44336;
    }
    
    .status-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }
    
    .status-icon-success {
        background-color: #4CAF50;
    }
    
    .status-icon-error {
        background-color: #F44336;
    }
    
    .status-message {
        color: white;
        flex: 1;
        font-size: 14px;
    }
    
    .status-close {
        color: #9E9E9E;
        font-size: 18px;
        cursor: pointer;
        margin-left: 12px;
    }
    
    .status-close:hover {
        color: white;
    }
    
    .status-progress-success,
    .status-progress-error {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
    }
    
    .status-progress-success {
        background-color: #4CAF50;
        animation: progress 5s linear forwards;
    }
    
    .status-progress-error {
        background-color: #F44336;
        animation: progress 5s linear forwards;
    }
    
    @keyframes progress {
        0% { width: 100%; }
        100% { width: 0%; }
    }
    
    @keyframes slideIn {
        0% { transform: translateX(120%); opacity: 0; }
        100% { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        0% { transform: translateX(0); opacity: 1; }
        100% { transform: translateX(120%); opacity: 0; }
    }
    
   
    
    /* Animações para o prêmio */
    @keyframes prizeReveal {
        0% {
            transform: scale(0) rotate(-180deg);
            opacity: 0;
        }
        60% {
            transform: scale(1.2) rotate(10deg);
        }
        80% {
            transform: scale(0.9) rotate(-5deg);
        }
        100% {
            transform: scale(1) rotate(0);
            opacity: 1;
        }
    }
    
    @keyframes prizeFloat {
        0% {
            transform: translateY(0) rotate(0);
        }
        50% {
            transform: translateY(-10px) rotate(5deg);
        }
        100% {
            transform: translateY(0) rotate(0);
        }
    }
    .alert.alert-info {
        width: 100%;
        position: absolute;
    }
    .prize-animation {
        animation: prizeReveal 1s ease-out, prizeFloat 2s ease-in-out infinite;
        transform-origin: center;
        filter: brightness(1.2);
        z-index: 1003;
    }
    
    /* Estilo para o modal com animação */
    .modal-content {
        animation: modalAppear 0.5s ease-out;
        box-shadow: 0 0 20px var(--primary-color) !important;
        position: relative;
        overflow: hidden;
    }
    
    @keyframes modalAppear {
        0% {
            transform: scale(0.7);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    /* Animações para o confete/glitter */
    @keyframes confettiFall {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }
    
    @keyframes confettiRotate {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    
    @keyframes confettiShine {
        0% {
            filter: brightness(1.5) blur(0px);
        }
        100% {
            filter: brightness(2.0) blur(1px);
        }
    }
    
    @keyframes glitterPulse {
        0% {
            opacity: 0.4;
            transform: scale(0.8);
            filter: brightness(1.5);
        }
        100% {
            opacity: 1;
            transform: scale(1.2);
            filter: brightness(2.0);
        }
    }
    
    .confetti-piece {
        pointer-events: none;
        will-change: transform, opacity;
    }
    
    .glitter-piece {
        pointer-events: none;
        will-change: transform, opacity;
    }
    
    #confetti-container {
        z-index: 1001;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }
</style>
