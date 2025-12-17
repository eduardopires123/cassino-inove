/**
 * Sistema de Modais e Toasts
 * Componente reutilizável para gerenciar toasts e modais de confirmação 
 */

// Gerenciador de Toasts
const ToastManager = {
    // Cria um novo toast e o adiciona ao container
    show: function(message, type = 'info', duration = 3000) {
        // Criar container de toasts se não existir
        if (!document.querySelector('.toast-container')) {
            const container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        
        // Criar elementos do toast
        const toast = document.createElement('div');
        toast.className = `custom-toast ${type}`;
        
        // Determinar ícone baseado no tipo
        let icon = '';
        if (type === 'success') {
            icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>';
        } else if (type === 'error') {
            icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
        } else {
            icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>';
        }
        
        // Criar HTML para o toast
        toast.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-content">${message}</div>
            <button class="toast-close">&times;</button>
        `;
        
        // Adicionar ao container
        document.querySelector('.toast-container').appendChild(toast);
        
        // Adicionar handler para o botão fechar
        toast.querySelector('.toast-close').addEventListener('click', function() {
            toast.remove();
        });
        
        // Mostrar o toast (com transição)
        setTimeout(() => toast.classList.add('show'), 10);
        
        // Auto-remover após o tempo especificado
        if (duration > 0) {
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300); // Remover após a transição
            }, duration);
        }
        
        return toast;
    },
    
    // Mostra um toast de sucesso
    success: function(message, duration = 4000) {
        return this.show(message, 'success', duration);
    },
    
    // Mostra um toast de erro
    error: function(message, duration = 4000) {
        return this.show(message, 'error', duration);
    },
    
    // Mostra um toast informativo
    info: function(message, duration = 4000) {
        return this.show(message, 'info', duration);
    }
};

// Objeto para gerenciar modais
const ModalManager = {
    // Função genérica para exibir modal de confirmação
    showConfirmation: function(title, message, onConfirm, onCancel) {
        // Verificar se o modal existe, caso contrário, criar
        if (!document.getElementById('confirmationModal')) {
            this.createConfirmationModal();
        }
        
        // Fechar todos os outros modais
        $('.modal').modal('hide');
        
        // Configurar o modal
        $('#confirmationModalTitle').text(title || 'Confirmar Ação');
        $('#confirmationModalText').text(message || 'Tem certeza que deseja realizar esta ação?');
        
        // Armazenar callbacks
        this._confirmCallback = onConfirm || function(){};
        this._cancelCallback = onCancel || function(){};
        
        // Exibir o modal
        $('#confirmationModal').modal('show');
    },
    
    // Cria o modal de confirmação no DOM se ele não existir
    createConfirmationModal: function() {
        const modalHTML = `
        <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalTitle">Confirmar Ação</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="modal-text" id="confirmationModalText">Tem certeza que deseja realizar esta ação?</p>
                    </div>
                    <div class="modal-footer md-button">
                        <button class="btn btn-light-dark _effect--ripple waves-effect waves-light" id="confirmationModalCancel" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="confirmationModalConfirm" class="btn btn-primary _effect--ripple waves-effect waves-light">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
        `;
        
        // Adicionar modal ao body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Configurar handlers do modal
        this.setupModalHandlers();
    },
    
    // Configurar os handlers dos botões do modal
    setupModalHandlers: function() {
        // Adicionar handler para o botão confirmar
        $('#confirmationModalConfirm').on('click', function() {
            $('#confirmationModal').modal('hide');
            if (typeof ModalManager._confirmCallback === 'function') {
                ModalManager._confirmCallback();
            }
        });
        
        // Adicionar handler para o botão cancelar e fechar
        $('#confirmationModalCancel, #confirmationModal .btn-close').on('click', function() {
            if (typeof ModalManager._cancelCallback === 'function') {
                ModalManager._cancelCallback();
            }
        });
    },
    
    // Inicializar eventos dos modais
    init: function() {
        // Verificar se o modal existe, caso contrário, criar
        if (!document.getElementById('confirmationModal')) {
            this.createConfirmationModal();
        } else {
            this.setupModalHandlers();
        }
        
        // Garantir que todos os modais sejam fechados no carregamento da página
        $('.modal').modal('hide');
        
        // Adicionar handlers para garantir que os modais possam ser fechados
        $('.btn-close, [data-bs-dismiss="modal"]').on('click', function() {
            const modalId = $(this).closest('.modal').attr('id');
            $(`#${modalId}`).modal('hide');
        });
        
        // Adicionar handler para tecla ESC para fechar todos os modais
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.modal').modal('hide');
            }
        });
        
        // Adicionar estilos de toast se ainda não existirem
        if (!document.getElementById('toastManagerStyles')) {
            this.addToastStyles();
        }
    },
    
    // Adicionar os estilos CSS do Toast se não existirem
    addToastStyles: function() {
        const styles = `
        <style id="toastManagerStyles">
        /* Toast/notificação estilo */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 350px;
        }
        
        .custom-toast {
            display: flex;
            align-items: center;
            background-color:rgba(0, 0, 0, 0.92);
            color: white;
            border-left: 4px solid;
            border-radius: 4px;
            padding: 12px 15px;
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transform: translateX(120%);
            transition: transform 0.3s ease-in-out;
            opacity: 0;
        }
        
        .custom-toast.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .custom-toast.success {
            border-left-color: #28a745;
        }
        
        .custom-toast.error {
            border-left-color: #dc3545;
        }
        
        .custom-toast.info {
            border-left-color: #17a2b8;
        }
        
        .custom-toast .toast-icon {
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
        }
        
        .custom-toast.success .toast-icon {
            background-color: #28a745;
        }
        
        .custom-toast.error .toast-icon {
            background-color: #dc3545;
        }
        
        .custom-toast.info .toast-icon {
            background-color: #17a2b8;
        }
        
        .custom-toast .toast-content {
            flex: 1;
        }
        
        .custom-toast .toast-close {
            color: #aaa;
            background: none;
            border: none;
            padding: 0;
            margin-left: 10px;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
        }
        
        .custom-toast .toast-close:hover {
            color: white;
        }
        </style>
        `;
        
        // Adicionar estilos ao head
        document.head.insertAdjacentHTML('beforeend', styles);
    }
};

// Inicialização automática quando o documento estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    ModalManager.init();
});
