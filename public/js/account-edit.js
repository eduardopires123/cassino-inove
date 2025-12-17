// Definir função carregarModal globalmente antes de tudo
window.carregarModal = function(tipo) {
    // Criar ou reutilizar o container do modal
    let modalContainer = document.getElementById('modal-container');
    if (!modalContainer) {
        modalContainer = document.createElement('div');
        modalContainer.id = 'modal-container';
        modalContainer.style.position = 'fixed';
        modalContainer.style.top = '0';
        modalContainer.style.left = '0';
        modalContainer.style.width = '100%';
        modalContainer.style.height = '100%';
        modalContainer.style.backgroundColor = 'rgba(0,0,0,0.5)';
        modalContainer.style.zIndex = '9999';
        modalContainer.style.display = 'flex';
        modalContainer.style.justifyContent = 'center';
        modalContainer.style.alignItems = 'center';
        document.body.appendChild(modalContainer);
    }
    
    // Limpar quaisquer timers existentes antes de reabrir o modal
    if (window.countdownTimers) {
        window.countdownTimers.forEach(timerId => clearTimeout(timerId));
        window.countdownTimers = [];
    } else {
        window.countdownTimers = [];
    }
    
    // Mostrar o container
    modalContainer.style.display = 'flex';
    
    // Usar o template HTML pré-carregado
    if (tipo === 'email') {
        const template = document.getElementById('email-modal-template');
        if (template) {
            modalContainer.innerHTML = template.innerHTML;
            
            // Atualizar o email exibido no modal com o email editado
            const emailInput = document.getElementById('email');
            const emailDisplay = modalContainer.querySelector('.RvkWs');
            if (emailDisplay) {
                let currentEmail = '';
                
                // Priorizar o valor do input (se foi editado)
                if (emailInput && emailInput.value) {
                    currentEmail = emailInput.value;
                } else {
                    // Caso contrário, pegar o email exibido na tela
                    const emailDisplayElement = document.querySelector('.ihDn-');
                    if (emailDisplayElement) {
                        const emailText = emailDisplayElement.textContent.trim();
                        // Extrair apenas o email (remover texto do botão)
                        const emailMatch = emailText.match(/^[^\s]+@[^\s]+\.[^\s]+/);
                        if (emailMatch) {
                            currentEmail = emailMatch[0];
                        }
                    }
                }
                
                if (currentEmail) {
                    emailDisplay.textContent = currentEmail;
                }
            }
        }
    } else if (tipo === 'telefone') {
        const template = document.getElementById('phone-modal-template');
        if (template) {
            modalContainer.innerHTML = template.innerHTML;
            
            // Atualizar o telefone exibido no modal com o telefone editado
            const phoneInput = document.getElementById('phone');
            const phoneDisplay = modalContainer.querySelector('.vWJmL');
            if (phoneDisplay) {
                let currentPhone = '';
                
                // Priorizar o valor do input (se foi editado)
                if (phoneInput && phoneInput.value) {
                    currentPhone = phoneInput.value;
                } else {
                    // Caso contrário, pegar o telefone exibido na tela
                    const phoneDisplayElement = document.getElementById('phone-display');
                    if (phoneDisplayElement) {
                        const phoneText = phoneDisplayElement.textContent.trim();
                        // Extrair apenas o telefone (primeira linha antes do \n)
                        const phoneMatch = phoneText.split('\n')[0];
                        if (phoneMatch) {
                            currentPhone = phoneMatch.trim();
                        }
                    }
                }
                
                if (currentPhone) {
                    // Formatar o telefone no mesmo padrão do template
                    const phoneValue = currentPhone.replace(/\D/g, ''); // Remove formatação
                    if (phoneValue.length >= 10) {
                        const formattedPhone = phoneValue.replace(/(\d{2})(\d{4,5})(\d{4})/, '+55 ($1) $2-$3');
                        phoneDisplay.textContent = formattedPhone;
                    } else {
                        phoneDisplay.textContent = currentPhone;
                    }
                }
            }
        }
    }
    
    // Configurar eventos após carregar o modal
    if (window.configureModal) {
        window.configureModal(modalContainer, tipo);
    }
    
    // Iniciar envio de código automaticamente
    if (window.enviarCodigoVerificacao) {
        window.enviarCodigoVerificacao(tipo);
    }
};

// Função para enviar código de verificação - definir globalmente
window.enviarCodigoVerificacao = function(tipo) {
    const url = tipo === 'email' 
        ? '/verify-email-request' 
        : '/verify-phone-request';
    
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('CSRF token not found');
        if (typeof mostrarMensagemErro === 'function') {
            mostrarMensagemErro('Erro de segurança: Token CSRF não encontrado.');
        }
        return;
    }

    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        // Empty body since we're just requesting a code
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) {
            console.error(`HTTP error! status: ${response.status}`);
        }
        return response.text().then(text => {
            try {
                return text ? JSON.parse(text) : {};
            } catch (e) {
                console.error('Error parsing JSON response:', e);
                console.error('Response text:', text);
                throw new Error('Invalid JSON response from server');
            }
        });
    })
    .then(data => {
        if (data.success) {
            if (typeof mostrarMensagemSucesso === 'function') {
                mostrarMensagemSucesso(data.message || `Código de verificação enviado para seu ${tipo === 'email' ? 'email' : 'telefone'}!`);
            }
        } else {
            if (typeof mostrarMensagemErro === 'function') {
                mostrarMensagemErro(data.message || `Erro ao enviar código de verificação para seu ${tipo === 'email' ? 'email' : 'telefone'}.`);
            }
        }
    })
    .catch(error => {
        console.error(`Erro ao enviar código de verificação para ${tipo}:`, error);
        if (typeof mostrarMensagemErro === 'function') {
            mostrarMensagemErro(`Erro ao enviar código de verificação para seu ${tipo === 'email' ? 'email' : 'telefone'}.`);
        }
    });
};

// Configurar eventos do modal após carregamento - definir globalmente
window.configureModal = function(container, tipo) {
    // Botão de fechar modal
    const closeBtn = container.querySelector('.pOB1m');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            container.style.display = 'none';
            
            // Limpar timers quando o modal for fechado
            if (window.countdownTimers) {
                window.countdownTimers.forEach(timerId => clearTimeout(timerId));
                window.countdownTimers = [];
            }
        });
    }
    
    // Configurar inputs de código
    if (window.configurarInputsCodigo) {
        window.configurarInputsCodigo(container);
    }
    
    if (tipo === 'email') {
        // Configurar envio de código
        const sendCodeBtn = container.querySelector('.gwv7B');
        if (sendCodeBtn && window.configureResendCode) {
            window.configureResendCode(sendCodeBtn, 'email');
        }
        
        // Configurar botão de confirmação
        const confirmBtn = container.querySelector('.rueTo');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                const codigo = window.getConfirmationCode ? window.getConfirmationCode(container) : '';
                if (codigo && codigo.length === 5) {
                    if (window.verificarCodigo) {
                        window.verificarCodigo('email', codigo);
                    }
                }
            });
        }
    } else if (tipo === 'telefone') {
        // Configurar envio de código
        const sendCodeBtn = container.querySelector('.bNxZM');
        if (sendCodeBtn && window.configureResendCode) {
            window.configureResendCode(sendCodeBtn, 'telefone');
        }
        
        // Configurar botão de confirmação
        const confirmBtn = container.querySelector('.LGPM6');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                const codigo = window.getConfirmationCode ? window.getConfirmationCode(container) : '';
                if (codigo && codigo.length === 5) {
                    if (window.verificarCodigo) {
                        window.verificarCodigo('telefone', codigo);
                    }
                }
            });
        }
    }
};

// Função para configurar os inputs de código - definir globalmente
window.configurarInputsCodigo = function(container) {
    const inputs = container.querySelectorAll('input[name="code"]');
    
    inputs.forEach((input, index) => {
        // Focar no primeiro input ao carregar
        if (index === 0) {
            setTimeout(() => input.focus(), 100);
        }
        
        input.addEventListener('input', function() {
            // Permitir apenas números
            this.value = this.value.replace(/[^0-9]/g, '');
            
            if (this.value.length === 1) {
                // Ir para próximo input
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            }
            
            // Verificar se todos os inputs estão preenchidos após cada entrada
            if (window.verificarCodigoCompleto) {
                window.verificarCodigoCompleto(container);
            }
        });
        
        input.addEventListener('keydown', function(e) {
            // Se o usuário pressionar backspace e o campo estiver vazio
            if (e.key === 'Backspace' && this.value === '') {
                // Voltar para input anterior
                if (index > 0) {
                    inputs[index - 1].focus();
                }
            }
            
            // Verificar após qualquer entrada ou remoção
            setTimeout(() => {
                if (window.verificarCodigoCompleto) {
                    window.verificarCodigoCompleto(container);
                }
            }, 10);
        });
        
        // Permitir colar múltiplos dígitos
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const onlyNumbers = pastedText.replace(/[^0-9]/g, '');
            
            // Distribuir os números pelos inputs
            for (let i = 0; i < Math.min(onlyNumbers.length, inputs.length - index); i++) {
                inputs[index + i].value = onlyNumbers.charAt(i);
            }
            
            // Focar no próximo input vazio ou no último se todos estiverem preenchidos
            let nextEmptyIndex = index + onlyNumbers.length;
            if (nextEmptyIndex >= inputs.length) {
                nextEmptyIndex = inputs.length - 1;
            }
            inputs[nextEmptyIndex].focus();
            
            // Verificar código completo
            if (window.verificarCodigoCompleto) {
                window.verificarCodigoCompleto(container);
            }
        });
    });
};

// Verificar se o código está completo - definir globalmente
window.verificarCodigoCompleto = function(container) {
    const inputs = container.querySelectorAll('input[name="code"]');
    const confirmBtn = container.querySelector('.rueTo') || container.querySelector('.LGPM6');
    
    if (!confirmBtn) return;
    
    let isComplete = true;
    
    inputs.forEach(input => {
        if (input.value === '') {
            isComplete = false;
        }
    });
    
    confirmBtn.disabled = !isComplete;
};

// Obter código de confirmação dos inputs - definir globalmente
window.getConfirmationCode = function(container) {
    const inputs = container.querySelectorAll('input[name="code"]');
    let codigo = '';
    
    inputs.forEach(input => {
        codigo += input.value;
    });
    
    return codigo;
};

// Função para configurar reenvio de código - definir globalmente
window.configureResendCode = function(button, tipo) {
    if (window.setupCountdownButton) {
        window.setupCountdownButton(button, 60, 
            `REENVIAR CÓDIGO em %s`, 
            'REENVIAR CÓDIGO', 
            function() {
                if (window.enviarCodigoVerificacao) {
                    window.enviarCodigoVerificacao(tipo);
                }
            }
        );
    }
};

// Função genérica para configurar botões com contagem regressiva - definir globalmente
window.setupCountdownButton = function(button, seconds, countdownTemplate, finalText, callback) {
    let countdown = seconds;
    
    // Remover qualquer listener de clique existente para evitar duplicações
    const newButton = button.cloneNode(true);
    button.parentNode.replaceChild(newButton, button);
    button = newButton;
    
    const updateButton = () => {
        // Verificar se o modal está visível antes de atualizar
        const modalContainer = document.getElementById('modal-container');
        const isModalVisible = modalContainer && modalContainer.style.display !== 'none';
        
        if (!isModalVisible) {
            return; // Não continuar atualizando se o modal estiver fechado
        }
        
        if (countdown > 0) {
            button.disabled = true;
            button.textContent = countdownTemplate.replace('%s', `${countdown}s`);
            countdown--;
            const timerId = setTimeout(updateButton, 1000);
            
            // Armazenar o ID do timer para poder limpá-lo depois
            if (!window.countdownTimers) window.countdownTimers = [];
            window.countdownTimers.push(timerId);
        } else {
            button.disabled = false;
            button.textContent = finalText;
            // Remover qualquer listener de clique existente
            const newBtn = button.cloneNode(true);
            button.parentNode.replaceChild(newBtn, button);
            button = newBtn;
            
            // Adicionar novo listener de clique
            button.addEventListener('click', function clickHandler() {
                // Remover este listener para evitar múltiplas chamadas
                button.removeEventListener('click', clickHandler);
                
                callback();
                countdown = seconds;
                updateButton();
            });
        }
    };
    
    updateButton();
    
    return button;
};

// Função para verificar código - definir globalmente
window.verificarCodigo = function(tipo, codigo) {
    const url = tipo === 'email' 
        ? '/verify-email-code' 
        : '/verify-phone-code';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ code: codigo })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof mostrarMensagemSucesso === 'function') {
                mostrarMensagemSucesso(data.message || `${tipo === 'email' ? 'Email' : 'Telefone'} verificado com sucesso!`);
            }
            
            // Fechar o modal
            const modalContainer = document.getElementById('modal-container');
            if (modalContainer) {
                modalContainer.style.display = 'none';
            }
            
            // Atualizar a interface sem recarregar a página
            if (window.updateVerificationStatus) {
                window.updateVerificationStatus(tipo);
            }
            
        } else {
            if (typeof mostrarMensagemErro === 'function') {
                mostrarMensagemErro(data.message || 'Código inválido. Tente novamente.');
            }
        }
    })
    .catch(error => {
        console.error(`Erro ao verificar código de ${tipo}:`, error);
        if (typeof mostrarMensagemErro === 'function') {
            mostrarMensagemErro(`Erro ao verificar código de ${tipo}.`);
        }
    });
};

// Função para atualizar status de verificação - definir globalmente
window.updateVerificationStatus = function(tipo) {
    if (tipo === 'email') {
        const emailSection = document.querySelector('.email-section');
        if (emailSection) {
            const badge = emailSection.querySelector('.badge');
            const buttonContainer = emailSection.querySelector('.button-container');
            
            if (badge) {
                badge.textContent = 'Verificado';
                badge.className = 'badge badge-success';
            }
            
            if (buttonContainer) {
                buttonContainer.innerHTML = '<button class="btn btn-outline-secondary btn-sm edit-email">Editar</button>';
                
                // Adicionar event listener para o novo botão de editar
                const editBtn = buttonContainer.querySelector('.edit-email');
                if (editBtn) {
                    editBtn.addEventListener('click', function() {
                        const form = emailSection.querySelector('.edit-form');
                        if (form) {
                            form.style.display = form.style.display === 'none' ? 'block' : 'none';
                        }
                    });
                }
            }
        }
    } else if (tipo === 'telefone') {
        const phoneSection = document.querySelector('.phone-section');
        if (phoneSection) {
            const badge = phoneSection.querySelector('.badge');
            const buttonContainer = phoneSection.querySelector('.button-container');
            
            if (badge) {
                badge.textContent = 'Verificado';
                badge.className = 'badge badge-success';
            }
            
            if (buttonContainer) {
                buttonContainer.innerHTML = '<button class="btn btn-outline-secondary btn-sm edit-phone">Editar</button>';
                
                // Adicionar event listener para o novo botão de editar
                const editBtn = buttonContainer.querySelector('.edit-phone');
                if (editBtn) {
                    editBtn.addEventListener('click', function() {
                        const form = phoneSection.querySelector('.edit-form');
                        if (form) {
                            form.style.display = form.style.display === 'none' ? 'block' : 'none';
                        }
                    });
                }
            }
        }
    }
};

// Funções auxiliares para mensagens - definir globalmente
window.mostrarMensagemSucesso = function(message) {
    if (typeof showMessage === 'function') {
        showMessage(message, 'success');
    } else {
        alert(message);
    }
};

window.mostrarMensagemErro = function(message) {
    if (typeof showMessage === 'function') {
        showMessage(message, 'error');
    } else {
        alert(message);
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // Função para exibir mensagens de feedback
    function showMessage(message, type = 'info') {
        // Remover mensagens existentes
        const existingMessages = document.querySelectorAll('.feedback-message');
        existingMessages.forEach(msg => msg.remove());
        
        // Criar elemento da mensagem
        const messageElement = document.createElement('div');
        messageElement.className = `feedback-message alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'}`;
        messageElement.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 15px 20px;
            border-radius: 4px;
            color: white;
            font-weight: 500;
            min-width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.3s ease-out;
        `;
        
        // Definir cores baseadas no tipo
        if (type === 'success') {
            messageElement.style.backgroundColor = '#28a745';
        } else if (type === 'error') {
            messageElement.style.backgroundColor = '#dc3545';
        } else {
            messageElement.style.backgroundColor = '#17a2b8';
        }
        
        messageElement.textContent = message;
        
        // Adicionar CSS da animação se não existir
        if (!document.querySelector('#feedback-animations')) {
            const style = document.createElement('style');
            style.id = 'feedback-animations';
            style.textContent = `
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Adicionar ao documento
        document.body.appendChild(messageElement);
        
        // Remover após 5 segundos
        setTimeout(() => {
            messageElement.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                if (messageElement.parentNode) {
                    messageElement.remove();
                }
            }, 300);
        }, 5000);
    }

    // Adicionar event listeners para os botões de verificação
    const emailVerifyBtn = document.querySelector('.email-verify');
    if (emailVerifyBtn) {
        emailVerifyBtn.addEventListener('click', function() {
            carregarModal('email');
        });
    }

    const phoneVerifyBtn = document.querySelector('.phone-verify');
    if (phoneVerifyBtn) {
        phoneVerifyBtn.addEventListener('click', function() {
            carregarModal('telefone');
        });
    }

    // Auto preenchimento de endereço com ViaCEP
    const cepInput = document.getElementById('zipcode');
    if (cepInput) {
        cepInput.addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            
            if (cep.length !== 8) {
                return;
            }
            
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('logradouro').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('estado').value = data.uf;
                        
                        // Focar no campo de número, que não é preenchido automaticamente
                        document.getElementById('numero').focus();
                    }
                })
                .catch(error => {
                    console.error('Erro ao consultar o CEP:', error);
                });
        });
    }
    
    // Função para atualizar o status de verificação na interface
    function updateVerificationStatus(tipo) {
        if (tipo === 'email') {
            // Encontrar a seção do email especificamente
            const emailSection = document.querySelector('._94Q8s:first-of-type');
            if (emailSection) {
                // Atualizar badge de verificação do email
                const emailBadge = emailSection.querySelector('.nZvE6.qzYqb');
                if (emailBadge) {
                    emailBadge.className = 'nZvE6 qzYqb bg-success';
                    emailBadge.innerHTML = `
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="currentColor"></path>
                            </svg>
                        </span> 
                        Verificado
                    `;
                }
                
                // Remover botão de verificar e atualizar botões
                const buttonContainer = emailSection.querySelector('.g5YkC');
                if (buttonContainer) {
                    const verifyButton = buttonContainer.querySelector('.email-verify');
                    if (verifyButton) {
                        verifyButton.remove();
                    }
                    
                    // Remover botão não verificado se existir
                    const unverifiedButton = buttonContainer.querySelector('#email-edit-btn-unverified');
                    if (unverifiedButton) {
                        unverifiedButton.remove();
                    }
                    
                    // Garantir que existe apenas o botão de editar verificado
                    let editButton = buttonContainer.querySelector('#email-edit-btn');
                    if (!editButton) {
                        editButton = document.createElement('button');
                        editButton.id = 'email-edit-btn';
                        editButton.className = 'cyeNp';
                        editButton.textContent = 'Editar';
                        buttonContainer.appendChild(editButton);
                        
                        // Reconfigurar o event listener para o novo botão
                        editButton.addEventListener('click', function() {
                            const emailDisplay = document.querySelector('.ihDn-');
                            const emailForm = document.getElementById('email-edit-form');
                            
                            if (emailForm && emailDisplay) {
                                if (emailForm.style.display === 'none' || emailForm.style.display === '') {
                                    emailDisplay.style.display = 'none';
                                    emailForm.style.display = 'block';
                                    document.getElementById('email').focus();
                                    this.textContent = 'Cancelar';
                                } else {
                                    emailDisplay.style.display = 'block';
                                    emailForm.style.display = 'none';
                                    this.textContent = 'Editar';
                                }
                            }
                        });
                    }
                    
                    // Remover div flex se ainda existir
                    const flexDiv = buttonContainer.querySelector('.flex.gap-2');
                    if (flexDiv) {
                        // Mover botão de editar para fora da div flex
                        if (editButton && flexDiv.contains(editButton)) {
                            buttonContainer.appendChild(editButton);
                        }
                        flexDiv.remove();
                    }
                }
            }
            
        } else if (tipo === 'telefone') {
            // Encontrar a seção do telefone especificamente (segunda seção _94Q8s)
            const phoneSection = document.querySelectorAll('._94Q8s')[1];
            if (phoneSection) {
                // Atualizar badge de verificação do telefone
                const phoneBadge = phoneSection.querySelector('.nZvE6.qzYqb');
                if (phoneBadge) {
                    phoneBadge.className = 'nZvE6 qzYqb bg-success';
                    phoneBadge.innerHTML = `
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z" fill="currentColor"></path>
                            </svg>
                        </span> 
                        Verificado
                    `;
                }
                
                // Remover botão de verificar e atualizar botões
                const buttonContainer = phoneSection.querySelector('.g5YkC');
                if (buttonContainer) {
                    const verifyButton = buttonContainer.querySelector('.phone-verify');
                    if (verifyButton) {
                        verifyButton.remove();
                    }
                    
                    // Remover botão não verificado se existir
                    const unverifiedButton = buttonContainer.querySelector('#phone-edit-btn-unverified');
                    if (unverifiedButton) {
                        unverifiedButton.remove();
                    }
                    
                    // Garantir que existe apenas o botão de editar verificado
                    let editButton = buttonContainer.querySelector('#phone-edit-btn');
                    if (!editButton) {
                        editButton = document.createElement('button');
                        editButton.id = 'phone-edit-btn';
                        editButton.className = 'cyeNp';
                        editButton.textContent = 'Editar';
                        buttonContainer.appendChild(editButton);
                        
                        // Reconfigurar o event listener para o novo botão
                        editButton.addEventListener('click', function() {
                            const phoneDisplay = document.getElementById('phone-display');
                            const phoneForm = document.getElementById('phone-edit-form');
                            
                            if (phoneForm && phoneDisplay) {
                                if (phoneForm.style.display === 'none' || phoneForm.style.display === '') {
                                    phoneDisplay.style.display = 'none';
                                    phoneForm.style.display = 'block';
                                    document.getElementById('phone').focus();
                                    this.textContent = 'Cancelar';
                                } else {
                                    phoneDisplay.style.display = 'block';
                                    phoneForm.style.display = 'none';
                                    this.textContent = 'Editar';
                                }
                            }
                        });
                    }
                    
                    // Remover div flex se ainda existir
                    const flexDiv = buttonContainer.querySelector('.flex.gap-2');
                    if (flexDiv) {
                        // Mover botão de editar para fora da div flex
                        if (editButton && flexDiv.contains(editButton)) {
                            buttonContainer.appendChild(editButton);
                        }
                        flexDiv.remove();
                    }
                }
            }
        }
    }

    // Função para marcar campo como não verificado após edição
    function markAsUnverified(tipo) {
        if (tipo === 'email') {
            // Encontrar a seção do email
            const emailSection = document.querySelector('._94Q8s:first-of-type');
            if (emailSection) {
                // Atualizar badge para não verificado
                const emailBadge = emailSection.querySelector('.nZvE6.qzYqb');
                if (emailBadge) {
                    emailBadge.className = 'nZvE6 qzYqb';
                    emailBadge.innerHTML = `
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z" fill="currentColor"></path>
                            </svg>
                        </span> 
                        Não verificado
                    `;
                }
                
                // Atualizar botões para estado não verificado
                const buttonContainer = emailSection.querySelector('.g5YkC');
                if (buttonContainer) {
                    // Remover botão de editar verificado se existir
                    const verifiedButton = buttonContainer.querySelector('#email-edit-btn');
                    if (verifiedButton) {
                        verifiedButton.remove();
                    }
                    
                    // Criar estrutura de botões não verificados
                    const flexDiv = document.createElement('div');
                    flexDiv.className = 'flex gap-2';
                    
                    const editButton = document.createElement('button');
                    editButton.id = 'email-edit-btn-unverified';
                    editButton.className = 'cyeNp';
                    editButton.textContent = 'Editar';
                    
                    const verifyButton = document.createElement('button');
                    verifyButton.className = 'cyeNp email-verify';
                    verifyButton.textContent = 'Verificar';
                    verifyButton.onclick = function() { carregarModal('email'); };
                    
                    flexDiv.appendChild(editButton);
                    flexDiv.appendChild(verifyButton);
                    buttonContainer.appendChild(flexDiv);
                    
                    // Configurar event listener para o botão de editar
                    editButton.addEventListener('click', function() {
                        const emailDisplay = document.querySelector('.ihDn-');
                        const emailForm = document.getElementById('email-edit-form');
                        
                        if (emailForm && emailDisplay) {
                            if (emailForm.style.display === 'none' || emailForm.style.display === '') {
                                emailDisplay.style.display = 'none';
                                emailForm.style.display = 'block';
                                document.getElementById('email').focus();
                                this.textContent = 'Cancelar';
                            } else {
                                emailDisplay.style.display = 'block';
                                emailForm.style.display = 'none';
                                this.textContent = 'Editar';
                            }
                        }
                    });
                }
            }
            
        } else if (tipo === 'telefone') {
            // Encontrar a seção do telefone
            const phoneSection = document.querySelectorAll('._94Q8s')[1];
            if (phoneSection) {
                // Atualizar badge para não verificado
                const phoneBadge = phoneSection.querySelector('.nZvE6.qzYqb');
                if (phoneBadge) {
                    phoneBadge.className = 'nZvE6 qzYqb';
                    phoneBadge.innerHTML = `
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z" fill="currentColor"></path>
                            </svg>
                        </span> 
                        Não verificado
                    `;
                }
                
                // Atualizar botões para estado não verificado
                const buttonContainer = phoneSection.querySelector('.g5YkC');
                if (buttonContainer) {
                    // Remover botão de editar verificado se existir
                    const verifiedButton = buttonContainer.querySelector('#phone-edit-btn');
                    if (verifiedButton) {
                        verifiedButton.remove();
                    }
                    
                    // Criar estrutura de botões não verificados
                    const flexDiv = document.createElement('div');
                    flexDiv.className = 'flex gap-2';
                    
                    const editButton = document.createElement('button');
                    editButton.id = 'phone-edit-btn-unverified';
                    editButton.className = 'cyeNp';
                    editButton.textContent = 'Editar';
                    
                    const verifyButton = document.createElement('button');
                    verifyButton.className = 'cyeNp phone-verify';
                    verifyButton.textContent = 'Verificar';
                    verifyButton.onclick = function() { carregarModal('telefone'); };
                    
                    flexDiv.appendChild(editButton);
                    flexDiv.appendChild(verifyButton);
                    buttonContainer.appendChild(flexDiv);
                    
                    // Configurar event listener para o botão de editar
                    editButton.addEventListener('click', function() {
                        const phoneDisplay = document.getElementById('phone-display');
                        const phoneForm = document.getElementById('phone-edit-form');
                        
                        if (phoneForm && phoneDisplay) {
                            if (phoneForm.style.display === 'none' || phoneForm.style.display === '') {
                                phoneDisplay.style.display = 'none';
                                phoneForm.style.display = 'block';
                                document.getElementById('phone').focus();
                                this.textContent = 'Cancelar';
                            } else {
                                phoneDisplay.style.display = 'block';
                                phoneForm.style.display = 'none';
                                this.textContent = 'Editar';
                            }
                        }
                    });
                }
            }
        }
    }

    // Handler para formulário de email
    if (emailForm) {
        emailForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            submitButton.textContent = 'Salvando...';
            submitButton.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar o email exibido
                    const emailDisplay = document.querySelector('.ihDn-');
                    if (emailDisplay) {
                        emailDisplay.textContent = data.email;
                    }
                    
                    // Ocultar formulário e mostrar display
                    this.style.display = 'none';
                    if (emailDisplay) {
                        emailDisplay.style.display = 'block';
                    }
                    
                    // Marcar como não verificado
                    markAsUnverified('email');
                    
                    // Mostrar mensagem de sucesso
                    showMessage(data.message, 'success');
                } else {
                    showMessage(data.message || 'Erro ao atualizar email', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showMessage('Erro ao processar solicitação', 'error');
            })
            .finally(() => {
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        });
    }

    // Handler para formulário de telefone
    if (phoneForm) {
        phoneForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            submitButton.textContent = 'Salvando...';
            submitButton.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar o telefone exibido
                    const phoneDisplay = document.getElementById('phone-display');
                    if (phoneDisplay) {
                        phoneDisplay.textContent = data.formatted_phone;
                    }
                    
                    // Ocultar formulário e mostrar display
                    this.style.display = 'none';
                    if (phoneDisplay) {
                        phoneDisplay.style.display = 'block';
                    }
                    
                    // Marcar como não verificado
                    markAsUnverified('telefone');
                    
                    // Mostrar mensagem de sucesso
                    showMessage(data.message, 'success');
                } else {
                    showMessage(data.message || 'Erro ao atualizar telefone', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showMessage('Erro ao processar solicitação', 'error');
            })
            .finally(() => {
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
        });
    }
});