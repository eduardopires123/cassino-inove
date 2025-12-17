function CleareDefaultDep() {
    clearInterval(interval);

    let loadingElement = document.getElementById('depositButton');
    const loadingElement2 = document.querySelector('#open-deposit-modal[tabindex="1"]');

    const target = loadingElement || loadingElement2;

    target.innerHTML = "DEPOSITAR";
    target.disabled = false;
}

function CleareDefaultSaq() {
    let campo = document.getElementById('saqueButton');

    if (campo) {
        campo.innerHTML = "SACAR";
        campo.disabled = false;
    }

    document.querySelector('.cXaPe._8J-o-').value = "";
}

function OpenBlockSaqueModal() {
    const BlockSaqueModal = document.getElementById('BlockSaqueModal');
    const closeConfirmacaoSaque = document.getElementById('close-block-saque');

    if (BlockSaqueModal) {
        BlockSaqueModal.classList.remove('hidden');
        BlockSaqueModal.classList.add('show');

        if (closeConfirmacaoSaque && BlockSaqueModal) {
            closeConfirmacaoSaque.addEventListener('click', function() {
                BlockSaqueModal.classList.remove('show');
                BlockSaqueModal.classList.add('hidden');
            });
        }

        // Fecha modal quando clicar fora dele
        window.addEventListener('click', function(event) {
            if (event.target === BlockSaqueModal && BlockSaqueModal) {
                BlockSaqueModal.classList.remove('show');
                BlockSaqueModal.classList.add('hidden');
            }

        });
    }
}

// Script para controlar o modal de saque
document.addEventListener('DOMContentLoaded', function() {
    // Seleciona os elementos do DOM
    const saqueModal = document.getElementById('saqueModal');
    const confirmacaoSaqueModal = document.getElementById('confirmacaoSaqueModal');

    const closeSaqueModal = document.getElementById('close-saque-modal');
    const openSaqueModal = document.getElementById('open-saque-modal');
    const saqueButton = document.getElementById('saqueButton');
    const saqueAmountInput = document.querySelector('.cXaPe._8J-o-'); // Input de valor de saque
    const closeConfirmacaoSaque = document.getElementById('close-confirmacao-saque');
    const fecharConfirmacaoSaque = document.getElementById('fecharConfirmacaoSaque');
    const valorSaqueConfirmacao = document.getElementById('valorSaqueConfirmacao');

    // Formatador de moeda BRL
    const formatter = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
        minimumFractionDigits: 2
    });

    // Adiciona evento de clique para abrir o modal de saque
    if (openSaqueModal) {
        openSaqueModal.addEventListener('click', function(e) {
            e.preventDefault();
            saqueModal.classList.remove('hidden');
            saqueModal.classList.add('show');
        });
    }

    // Função para formatar valor para exibição
    function formatBRL(value) {
        return formatter.format(value);
    }

    // Função para formatar valor como string BRL sem o símbolo de moeda (apenas números)
    function formatNumberBRL(value) {
        if (!value && value !== 0) return '';

        // Converte para número e garante que é um número válido
        const numValue = parseFloat(typeof value === 'string' ? value.replace(/\./g, '').replace(',', '.') : value);

        if (isNaN(numValue)) return '';

        // Formata com separador de milhar e decimal brasileiros
        return numValue.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Adiciona o evento de input para permitir apenas números e formatação
    if (saqueAmountInput) {
        saqueAmountInput.addEventListener('input', function(e) {
            // Remove qualquer formato existente e mantém apenas números e um separador decimal
            const rawValue = this.value.replace(/\./g, '').replace(/[^\d,]/g, '');

            // Se houver mais de uma vírgula, remove as extras
            let cleanValue = rawValue;
            const commaIndex = rawValue.indexOf(',');
            if (commaIndex !== -1) {
                cleanValue = rawValue.substring(0, commaIndex + 1) + rawValue.substring(commaIndex + 1).replace(/,/g, '');
            }

            // Limita a 2 casas decimais após a vírgula
            if (cleanValue.includes(',')) {
                const parts = cleanValue.split(',');
                if (parts[1] && parts[1].length > 2) {
                    cleanValue = parts[0] + ',' + parts[1].substring(0, 2);
                }
            }

            // Atualiza o valor
            this.value = cleanValue;

            // Verifica se o valor é maior que o mínimo para habilitar o botão
            //checkSaqueValue();
        });

        // Formata o valor quando o campo perde o foco
        saqueAmountInput.addEventListener('blur', function() {
            if (this.value) {
                // Converte o valor para o formato numérico (substituindo , por . para cálculos)
                const numValue = parseFloat(this.value.replace(/\./g, '').replace(',', '.'));

                if (!isNaN(numValue)) {
                    // Aplica a formatação completa brasileira
                    this.value = formatNumberBRL(numValue);
                }
            }

            // Verifica se o valor é maior que o mínimo para habilitar o botão
            //checkSaqueValue();
        });
    }

    // Função para verificar se o valor do saque é válido
    function checkSaqueValue() {
        if (!saqueAmountInput || !saqueButton) return;

        const inputValue = saqueAmountInput.value.replace(/\./g, '').replace(',', '.');
        const numValue = parseFloat(inputValue);
        const minValue = window.minsaque; // Valor mínimo de saque (R$ 30,00)
        const maxValue = window.wallet; // Saldo disponível

        if (!isNaN(numValue) && numValue >= minValue && numValue <= maxValue) {
            //saqueButton.disabled = false;
        } else {
            //saqueButton.disabled = true;
        }
    }

    // Verifica inicialmente o valor para desabilitar o botão
    if (saqueAmountInput && saqueButton) {
        //checkSaqueValue();
    }

    // Processa o saque quando o botão for clicado
    if (saqueButton) {
        saqueButton.addEventListener('click', function(e) {
            e.preventDefault(); // Previne o envio do formulário

            if (!saqueAmountInput || !valorSaqueConfirmacao) return;

            // Obtém o valor digitado e converte para formato numérico
            let inputValue = saqueAmountInput.value.replace(/\./g, '').replace(',', '.');
            if (!inputValue) inputValue = "0";

            const numValue = parseFloat(inputValue);

            // Atualiza o valor no modal de confirmação
            valorSaqueConfirmacao.textContent = formatBRL(numValue);

            // Esconde o modal de saque e mostra o de confirmação
            if (saqueModal && confirmacaoSaqueModal) {
                saqueModal.classList.remove('show');
                saqueModal.classList.add('hidden');

                var AffCheck = document.getElementById('aff');
                if (AffCheck) {
                    Saque(true);
                }else{
                    Saque();
                }
                CleareDefaultSaq();
            }
        });
    }

    // Fecha o modal de saque
    if (closeSaqueModal && saqueModal) {
        closeSaqueModal.addEventListener('click', function() {
            saqueModal.classList.remove('show');
            saqueModal.classList.add('hidden');
        });

        CleareDefaultSaq();
    }

    // Fecha o modal de confirmação
    if (closeConfirmacaoSaque && confirmacaoSaqueModal) {
        closeConfirmacaoSaque.addEventListener('click', function() {
            confirmacaoSaqueModal.classList.remove('show');
            confirmacaoSaqueModal.classList.add('hidden');
        });
    }

    // Botão fechar no modal de confirmação
    if (fecharConfirmacaoSaque && confirmacaoSaqueModal) {
        fecharConfirmacaoSaque.addEventListener('click', function() {
            confirmacaoSaqueModal.classList.remove('show');
            confirmacaoSaqueModal.classList.add('hidden');
        });
    }

    // Fecha modal quando clicar fora dele
    window.addEventListener('click', function(event) {
        if (event.target === saqueModal && saqueModal) {
            saqueModal.classList.remove('show');
            saqueModal.classList.add('hidden');

            CleareDefaultSaq();
        }
        if (event.target === confirmacaoSaqueModal && confirmacaoSaqueModal) {
            confirmacaoSaqueModal.classList.remove('show');
            confirmacaoSaqueModal.classList.add('hidden');
        }

        // Fecha os modais de saque de bônus quando clica fora deles
        var saquebModal = document.getElementById('saquebModal');
        if (event.target === saquebModal && saquebModal) {
            saquebModal.classList.add('hidden');
        }

        var confirmacaoSaqueBModal = document.getElementById('confirmacaoSaqueBModal');
        if (event.target === confirmacaoSaqueBModal && confirmacaoSaqueBModal) {
            confirmacaoSaqueBModal.classList.add('hidden');
        }
    });
});

function LoadJS() {
    // Script para controlar o modal de depósito
    //document.addEventListener('DOMContentLoaded', function () {
    // Usar o sistema de bônus do modal
    if (typeof window.updateBonusDisplay === 'function') {
        setTimeout(function() {
            window.updateBonusDisplay();
        }, 100);
    } else {
        // Fallback para compatibilidade - só executar se BonusMulti estiver definido
        let bonus = document.getElementById('bonus_amount');
        if (bonus && typeof BonusMulti !== 'undefined') {
            bonus.innerHTML = '+ R$ ' + formatNumberBRL(50 * (BonusMulti / 100)) + ' Bônus';
        }
    }

    // Seleciona os elementos do DOM
    const depositModal = document.getElementById('depositModal');
    const qrCodeModal = document.getElementById('qrCodeModal');

    const openDepositModal = document.getElementById('open-deposit-modal');
    const closeDepositModal = document.getElementById('close-deposit-modal');
    const depositButton = document.getElementById('depositButton');
    const depositAmountInput = document.getElementById('depositAmount');

    // Seleciona os botões de valores predefinidos
    const valueButtons = [
        document.getElementById('button_0'), // R$ 20
        document.getElementById('button_1'), // R$ 50
        document.getElementById('button_2'), // R$ 100
        document.getElementById('button_3'), // R$ 250
        document.getElementById('button_4'), // R$ 500
        document.getElementById('button_5')  // R$ 1.000
    ];

    // Valores correspondentes aos botões
    const buttonValues = [20, 50, 100, 250, 500, 1000];

    // Formatador de moeda BRL
    const formatter = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
        minimumFractionDigits: 2
    });

    // Função para formatar valor para exibição
    function formatBRL(value) {
        return formatter.format(value);
    }

    // Função para formatar valor como string BRL sem o símbolo de moeda (apenas números)
    function formatNumberBRL(value) {
        if (!value && value !== 0) return '';

        // Converte para número e garante que é um número válido
        const numValue = parseFloat(typeof value === 'string' ? value.replace(/\./g, '').replace(',', '.') : value);

        if (isNaN(numValue)) return '';

        // Formata com separador de milhar e decimal brasileiros
        return numValue.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Adicionar o ícone de verificação a todos os botões (se ainda não tiverem)
    valueButtons.forEach((button) => {
        if (button) {
            // Verifica se o botão já tem o ícone de verificação
            let checkIcon = button.querySelector('._5jP8r');

            // Se não tiver, cria e adiciona o ícone de verificação (inicialmente oculto)
            if (!checkIcon) {
                const checkIconHTML = `
                        <span class="_5jP8r" style="display: none;">
                            <span class="nuxt-icon nuxt-icon--fill">
                                <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"
                                        fill="currentColor"
                                    ></path>
                                </svg>
                            </span>
                        </span>
                    `;
                button.insertAdjacentHTML('beforeend', checkIconHTML);
            }
        }
    });

    // Adiciona o evento de input para permitir apenas números e formatação
    if (depositAmountInput) {
        depositAmountInput.addEventListener('input', function (e) {
            // Remove qualquer formato existente e mantém apenas números e um separador decimal
            const rawValue = this.value.replace(/\./g, '').replace(/[^\d,]/g, '');

            // Se houver mais de uma vírgula, remove as extras
            let cleanValue = rawValue;
            const commaIndex = rawValue.indexOf(',');
            if (commaIndex !== -1) {
                cleanValue = rawValue.substring(0, commaIndex + 1) + rawValue.substring(commaIndex + 1).replace(/,/g, '');
            }

            // Limita a 2 casas decimais após a vírgula
            if (cleanValue.includes(',')) {
                const parts = cleanValue.split(',');
                if (parts[1] && parts[1].length > 2) {
                    cleanValue = parts[0] + ',' + parts[1].substring(0, 2);
                }
            }

            // Atualiza o valor
            this.value = cleanValue;

            // Quando o usuário digita um valor, desmarca qualquer botão selecionado
            valueButtons.forEach(btn => {
                if (btn) {
                    btn.classList.remove('vpp6I');

                    // Esconde o ícone de verificação
                    const checkIcon = btn.querySelector('._5jP8r');
                    if (checkIcon) {
                        checkIcon.style.display = 'none';
                    }
                }
            });

            // Verifica se o valor é maior que o mínimo para habilitar o botão
            //checkDepositValue();
        });

        // Formata o valor quando o campo perde o foco
        depositAmountInput.addEventListener('blur', function () {
            if (this.value) {
                // Converte o valor para o formato numérico (substituindo , por . para cálculos)
                const numValue = parseFloat(this.value.replace(/\./g, '').replace(',', '.'));

                if (!isNaN(numValue)) {
                    // Aplica a formatação completa brasileira
                    this.value = formatNumberBRL(numValue);
                }

                // Usar o sistema de bônus do modal
                if (typeof window.updateBonusDisplay === 'function') {
                    window.updateBonusDisplay();
                } else {
                    // Fallback para compatibilidade - só executar se BonusMulti estiver definido
                    let bonus = document.getElementById('bonus_amount');
                    if (bonus && typeof BonusMulti !== 'undefined') {
                        bonus.innerHTML = '+ R$ ' + formatNumberBRL(numValue * (BonusMulti / 100)) + ' Bônus';
                    }
                }
            }

            // Verifica se o valor é maior que o mínimo para habilitar o botão
            //checkDepositValue();
        });
    }

    // Função para verificar se o valor do depósito é válido
    function checkDepositValue() {
        //if (!depositAmountInput || !depositButton) return;

        const inputValue = depositAmountInput.value.replace(/\./g, '').replace(',', '.');
        const numValue = parseFloat(inputValue);
        const minValue = window.mindep; // Valor mínimo de depósito (R$ 20,00)

        /* if (!isNaN(numValue) && numValue >= minValue) {
             depositButton.disabled = false;
             depositButton.classList.remove('disabled');
         } else {
             depositButton.disabled = true;
             depositButton.classList.add('disabled');
         }*/
    }

    // Adiciona eventos de clique aos botões de valor predefinido
    valueButtons.forEach((button, index) => {
        if (button) {
            button.addEventListener('click', function () {
                // Remove a classe de selecionado de todos os botões e esconde todos os ícones
                valueButtons.forEach(btn => {
                    if (btn) {
                        btn.classList.remove('vpp6I');

                        // Esconde o ícone de verificação
                        const checkIcon = btn.querySelector('._5jP8r');
                        if (checkIcon) {
                            checkIcon.style.display = 'none';
                        }
                    }
                });

                // Adiciona classe de selecionado ao botão clicado
                this.classList.add('vpp6I');

                // Exibe o ícone de verificação no botão clicado
                const checkIcon = this.querySelector('._5jP8r');
                if (checkIcon) {
                    checkIcon.style.display = 'inline-block';
                }

                // Preenche o input com o valor formatado corretamente
                const value = buttonValues[index];
                if (depositAmountInput) {
                    depositAmountInput.value = formatNumberBRL(value);
                }

                // Usar o sistema de bônus do modal
                if (typeof window.updateBonusDisplay === 'function') {
                    window.updateBonusDisplay();
                } else {
                    // Fallback para compatibilidade - só executar se BonusMulti estiver definido
                    let bonus = document.getElementById('bonus_amount');
                    if (bonus && typeof BonusMulti !== 'undefined') {
                        bonus.innerHTML = '+ R$ ' + formatNumberBRL(value * (BonusMulti / 100)) + ' Bônus';
                    }
                }

                // Atualiza também o valor no modal QR Code
                if (qrCodeModal) {
                    const depositValueEl = qrCodeModal.querySelector('.depositValue');
                    if (depositValueEl) {
                        depositValueEl.textContent = formatBRL(value);
                    }
                }

                // Habilita o botão já que um valor válido foi selecionado
                if (depositButton) {
                    depositButton.disabled = false;
                    depositButton.classList.remove('disabled');
                }
            });
        }
    });

    // Atualiza o valor no QR Code quando o botão de depósito for clicado
    if (depositButton) {
        depositButton.addEventListener('click', function (e) {
            e.preventDefault(); // Previne o envio do formulário

            if (!depositAmountInput || !qrCodeModal) return;

            // Obtém o valor digitado e converte para formato numérico
            let inputValue = depositAmountInput.value.replace(/\./g, '').replace(',', '.');
            if (!inputValue) inputValue = "0";

            const numValue = parseFloat(inputValue);

            // Verifica se o valor é válido
            //if (isNaN(numValue) || numValue < window.mindep) {
            //    return; // Não prossegue se o valor for inválido
            //}

            // Atualiza o valor no modal QR Code
            const depositValueEl = qrCodeModal.querySelector('.depositValue');
            if (depositValueEl) {
                depositValueEl.textContent = formatBRL(numValue);
            }

            if (depositModal) {
                PagPix();
            }
        });
    }

    // Inicializa o botão pré-selecionado (R$ 50)
    const defaultButton = document.getElementById('button_1'); // R$ 50
    if (defaultButton) {
        // Seleciona o botão de R$ 50 por padrão
        defaultButton.classList.add('vpp6I');

        // Exibe o ícone de verificação
        const checkIcon = defaultButton.querySelector('._5jP8r');
        if (checkIcon) {
            checkIcon.style.display = 'inline-block';
        }

        // Preenche o input com o valor padrão formatado
        if (depositAmountInput) {
            depositAmountInput.value = formatNumberBRL(50);
        }

        // Atualiza também o valor no modal QR Code
        if (qrCodeModal) {
            const depositValueEl = qrCodeModal.querySelector('.depositValue');
            if (depositValueEl) {
                depositValueEl.textContent = formatBRL(50);
            }
        }

        // Habilita o botão já que um valor válido foi definido por padrão
        if (depositButton) {
            depositButton.disabled = false;
            depositButton.classList.remove('disabled');
        }
    }

    // Abre o modal de depósito quando clicar no botão "Depositar"
    if (openDepositModal) {
        document.addEventListener('click', function (e) {
            if (e.target && e.target.id === 'open-deposit-modal') {
                depositModal.classList.remove('hidden');
                depositModal.classList.add('show');

                document.getElementById('depositAmount').value = "50,00";

                // Inicializar o sistema de bônus
                if (typeof window.reinitializeBonusSystem === 'function') {
                    setTimeout(function() {
                        window.reinitializeBonusSystem();
                    }, 100);
                }
            }
        });
    } else {
        // Backup - procura por botões com classe ou texto que contenha "Depositar"
        const possibleDepositButtons = document.querySelectorAll('button, a, [role="button"]');
        possibleDepositButtons.forEach(button => {
            if (
                (button.textContent && button.textContent.includes('Depositar')) ||
                button.classList.contains('deposit-button') ||
                button.getAttribute('data-action') === 'deposit'
            ) {
                button.id = 'open-deposit-modal'; // Adiciona ID para identificação futura
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (depositModal) {
                        depositModal.classList.remove('hidden');
                        depositModal.classList.add('show');

                        document.getElementById('depositAmount').value = "50,00";

                        // Inicializar o sistema de bônus
                        if (typeof window.reinitializeBonusSystem === 'function') {
                            setTimeout(function() {
                                window.reinitializeBonusSystem();
                            }, 100);
                        }
                    }
                });
            }
        });
    }

    // Fecha o modal de depósito
    if (closeDepositModal && depositModal) {
        closeDepositModal.addEventListener('click', function () {
            depositModal.classList.remove('show');
            depositModal.classList.add('hidden');

            CleareDefaultDep();
        });
    }

    // Botão voltar no modal QR Code
    if (qrCodeModal) {
        const backButton = qrCodeModal.querySelector('.flex.flex-row.items-center.gap-3.cursor-pointer');
        if (backButton && depositModal) {
            backButton.addEventListener('click', function () {
                qrCodeModal.classList.remove('show');
                qrCodeModal.classList.add('hidden'); // Esconde o modal QR Code
                depositModal.classList.remove('hidden');
                depositModal.classList.add('show'); // Mostra novamente o modal de depósito

                document.getElementById('depositAmount').value = "50,00";

                // Inicializar o sistema de bônus
                if (typeof window.reinitializeBonusSystem === 'function') {
                    setTimeout(function() {
                        window.reinitializeBonusSystem();
                    }, 100);
                }

                CleareDefaultDep();
            });
        }

        // Adiciona evento de clique ao botão X (fechar) no modal QR Code
        const closeButton = qrCodeModal.querySelector('.pOB1m');
        if (closeButton) {
            closeButton.addEventListener('click', function () {
                qrCodeModal.classList.remove('show');
                qrCodeModal.classList.add('hidden'); // Esconde o modal QR Code

                CleareDefaultDep();
            });
        }
    }

    // Código adicional para melhorar fechamento dos modais
    // Fecha modal quando clicar fora dele
    window.addEventListener('click', function (event) {
        if (event.target === depositModal && depositModal) {
            depositModal.classList.remove('show');
            depositModal.classList.add('hidden');
        }
        if (event.target === qrCodeModal && qrCodeModal) {
            qrCodeModal.classList.remove('show');
            qrCodeModal.classList.add('hidden');
        }


    });

    // Configurando o botão de copiar código QR
    if (qrCodeModal) {
        const copyButton = qrCodeModal.querySelector('.buttonsBottom.betvip');
        if (copyButton) {
            copyButton.addEventListener('click', function () {
                const codeInput = qrCodeModal.querySelector('.inputCode input');
                if (codeInput) {
                    // Seleciona o texto do input
                    codeInput.select();
                    codeInput.setSelectionRange(0, 99999); // Para dispositivos móveis

                    // Copia o texto para a área de transferência
                    navigator.clipboard.writeText(codeInput.value)
                        .then(() => {
                            // Feedback visual (opcional)
                            const originalText = copyButton.textContent;
                            copyButton.textContent = 'Código copiado!';

                            setTimeout(() => {
                                copyButton.textContent = originalText;
                            }, 2000);
                        })
                        .catch(err => {
                            console.error('Erro ao copiar: ', err);
                        });
                }
            });
        }
    }

    // Verifica inicialmente o valor para habilitar/desabilitar o botão
    if (depositAmountInput && depositButton) {
        //checkDepositValue();
    }
    //});
}

// ABRIR MODAL DEPOSITO HEADER
document.addEventListener('DOMContentLoaded', function() {
    // Função para configurar botões de depósito
    function setupDepositButtons() {
        // Selecionar TODOS os botões de depósito em todo o site
        const allDepositBtns = document.querySelectorAll('[id="wallet-deposit-btn"], .deposit-btn, [data-action="deposit"]');
        const depositModal = document.getElementById('depositModal');

        if (depositModal) {
            // Adicionar listener a todos os botões encontrados
            allDepositBtns.forEach(function(btn) {
                if (btn) {
                    // Remover qualquer listener existente para evitar duplicação
                    btn.removeEventListener('click', openDepositModal);
                    // Adicionar o novo listener
                    btn.addEventListener('click', openDepositModal);
                }
            });

        } else {

            // Esperar pelo carregamento do modal (em caso de carregamento AJAX)
            setTimeout(function() {
                if (document.getElementById('depositModal')) {
                    setupDepositButtons();
                }
            }, 1000);
        }

        // Função para abrir o modal
        function openDepositModal(e) {
            e.preventDefault();

            const modal = document.getElementById('depositModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('show');

                document.getElementById('depositAmount').value = "50,00";

                // Inicializar o sistema de bônus
                if (typeof window.reinitializeBonusSystem === 'function') {
                    setTimeout(function() {
                        window.reinitializeBonusSystem();
                    }, 100);
                }
            } else {
                console.error('Erro: Modal de depósito não encontrado');
            }
        }
    }

    // Configurar inicialmente
    setupDepositButtons();

    // Observar mudanças no DOM para botões adicionados dinamicamente
    const observer = new MutationObserver(function(mutations) {
        let shouldCheck = false;

        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                // Verificar se algum dos nós adicionados pode conter botões de depósito
                for (let i = 0; i < mutation.addedNodes.length; i++) {
                    const node = mutation.addedNodes[i];
                    if (node.nodeType === 1) { // ELEMENT_NODE
                        if (node.id === 'wallet-deposit-btn' ||
                            node.classList?.contains('deposit-btn') ||
                            node.getAttribute('data-action') === 'deposit' ||
                            node.querySelector('#wallet-deposit-btn, .deposit-btn, [data-action="deposit"]')) {
                            shouldCheck = true;
                            break;
                        }
                    }
                }
            }
        });

        if (shouldCheck) {
            setupDepositButtons();
        }
    });

    // Iniciar observação de mudanças no DOM
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Expor função para acesso via console (depuração)
    window.openDepositModal = function() {
        const modal = document.getElementById('depositModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('show');

            // Inicializar o sistema de bônus
            if (typeof window.reinitializeBonusSystem === 'function') {
                setTimeout(function() {
                    window.reinitializeBonusSystem();
                }, 100);
            }

            return true;
        } else {
            console.error('Erro: Modal de depósito não encontrado');
            return false;
        }
    };

});

// Função específica para inicializar o botão de depósito na página de jogos
function initGameDepositButton() {

    const depositBtn = document.getElementById('deposit-btn');
    const depositModal = document.getElementById('depositModal');
    const depositButton = document.getElementById('depositButton');

    if (depositBtn && depositModal) {
        // Remover eventos antigos para evitar duplicação
        const newBtn = depositBtn.cloneNode(true);
        depositBtn.parentNode.replaceChild(newBtn, depositBtn);

        // Adicionar novo evento
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Mostrar modal sem recarregar a página
            depositModal.classList.remove('hidden');
            depositModal.classList.add('show');

            // Impedir que o fundo role
            document.body.style.overflow = 'hidden';

            // Garantir que o modal esteja acima do iframe
            if (window.getComputedStyle(depositModal).zIndex < 99999) {
                depositModal.style.zIndex = '99999';
            }

            // Inicializar o sistema de bônus
            if (typeof window.reinitializeBonusSystem === 'function') {
                setTimeout(function() {
                    window.reinitializeBonusSystem();
                }, 100);
            }

            // Verificar campo de valor
            const depositAmountInput = document.getElementById('depositAmount');
            if (depositAmountInput && !depositAmountInput.value) {
                // Selecionar botão de 50 reais por padrão
                const defaultButton = document.getElementById('button_1'); // R$ 50
                if (defaultButton) {
                    // Simular clique para aplicar a seleção
                    defaultButton.click();
                }
            }
        });

        // Se o botão de depósito do modal existir, configura ele também
        if (depositButton) {
            // Remover eventos antigos para evitar duplicação
            const newDepositButton = depositButton.cloneNode(true);
            depositButton.parentNode.replaceChild(newDepositButton, depositButton);

            // Adicionar novo evento
            newDepositButton.addEventListener('click', function(e) {
                e.preventDefault();

                // Verificar input de valor
                const depositAmountInput = document.getElementById('depositAmount');
                if (!depositAmountInput) return;

                // Obter e validar valor
                let inputValue = depositAmountInput.value.replace(/\./g, '').replace(',', '.');
                if (!inputValue) inputValue = "0";
                const numValue = parseFloat(inputValue);

                // Mostrar mensagem de carregamento no botão
                this.innerHTML = "Gerando...";
                this.disabled = true;

                // Verificar se a página tem uma função própria para gerar QR code
                if (typeof window.generateQRCodeViaAjax === 'function') {
                    // Usar a função da página se existir
                    window.generateQRCodeViaAjax(numValue);
                } else if (typeof window.PagPix === 'function') {
                    // Fallback para a função global PagPix
                    window.PagPix();
                } else {
                    // Restaurar botão se não encontrar um método para processar
                    this.innerHTML = "DEPOSITAR";
                    this.disabled = false;
                    alert('Método de pagamento não disponível');
                }
            });
        }

    } else {
        // Tentar novamente após um curto período (caso elementos ainda não estejam disponíveis)
        setTimeout(initGameDepositButton, 500);
    }
}

// Tornar a função disponível globalmente
window.initGameDepositButton = initGameDepositButton;

// Auto-inicialização para páginas de jogos
if (document.querySelector('.gamesBar') && document.getElementById('gameIframe')) {
    // Esta é uma página de jogo, inicializar automaticamente
    document.addEventListener('DOMContentLoaded', function() {
        initGameDepositButton();
    });

    // Também tentar inicializar imediatamente (caso DOMContentLoaded já tenha ocorrido)
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
        initGameDepositButton();
    }
}

// =====================================
// SEÇÃO: INTEGRAÇÃO COM SISTEMA DE LOGIN
// =====================================

// Listener para eventos de atualização do header (login/logout)
window.addEventListener('header:updated', function(event) {
    // Reconfigurar botões de depósito após login/logout
    setTimeout(() => {
        // Reconfigurar botões de depósito no header
        if (typeof setupDepositButtons === 'function') {
            setupDepositButtons();
        }

        // Reinicializar LoadJS para reconfigurar modais de depósito
        if (typeof LoadJS === 'function') {
            LoadJS();
        }

        // Reconfigurar botões de saque se existirem
        const saqueButtons = document.querySelectorAll('#open-saque-modal, .saque-btn');
        saqueButtons.forEach(btn => {
            if (btn && !btn.hasAttribute('data-configured')) {
                btn.setAttribute('data-configured', 'true');
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const saqueModal = document.getElementById('saqueModal');
                    if (saqueModal) {
                        saqueModal.classList.remove('hidden');
                        saqueModal.classList.add('show');
                    }
                });
            }
        });

        // Se estiver em página de jogo, reconfigurar botão específico
        if (document.querySelector('.gamesBar') && document.getElementById('gameIframe')) {
            initGameDepositButton();
        }

        // Reconfigurar sistema de bônus se disponível
        if (typeof window.reinitializeBonusSystem === 'function') {
            setTimeout(window.reinitializeBonusSystem, 100);
        }

    }, 250);

    // Componentes de depósito/saque reconfigurados
});
