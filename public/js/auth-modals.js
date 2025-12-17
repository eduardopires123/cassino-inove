// CÓDIGO CORRIGIDO - auth-modals.js
// Resolver problemas de conteúdo misto e handleLogout


// Closure imediata para proteção de escopo
(function() {
    // =====================================
    // SEÇÃO 1: CORREÇÃO DE URLS E LOGOUT
    // =====================================

    // Correção para jQuery AJAX
    if (typeof $ !== 'undefined' && $.ajax) {
        const originalAjax = $.ajax;
        $.ajax = function(options) {
            if (typeof options === 'string' && options.startsWith('http://')) {
                options = options.replace('http://', 'https://');
            } else if (options && typeof options.url === 'string' && options.url.startsWith('http://')) {
                options.url = options.url.replace('http://', 'https://');
            }
            return originalAjax.call(this, options);
        };
    }

    // Definir handleLogout globalmente ANTES de qualquer uso
    window.handleLogout = function() {
        try {
            // Secure logout URL
            const baseUrl = '';//window.location.origin.replace('http:', 'https:');
            const logoutUrl = baseUrl + '/logout';

            $.ajax({
                url: logoutUrl,
                type: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    $.ajax({
                        url: "/header",
                        type: "GET",
                        data: $(this).serialize(),
                        success: function (response) {
                            document.getElementById('divPageHeader').innerHTML = response;
                            initializeHeaderComponents();

                            clearInterval(_intervalId);
                            window.mostrarMensagemErro("Você saiu da sua conta!");

                            // Atualizar estado de autenticação
                            window.isUserAuthenticated = false;

                            // Disparar evento customizado para outros scripts
                            if (window.dispatchEvent) {
                                window.dispatchEvent(new CustomEvent('header:updated', {
                                    detail: { reason: 'logout' }
                                }));

                                window.dispatchEvent(new CustomEvent('auth:logoutSuccess', {
                                    detail: {
                                        authenticated: false,
                                        timestamp: Date.now()
                                    }
                                }));
                            }

                            // Apenas recarregar se não estiver na home
                            if (window.location.pathname !== "/") {
                                setTimeout(() => {
                                    window.location.href = "/";
                                }, 1500);
                            }
                        },
                        error: function (response) {
                            // Em caso de erro, redirecionar para home ao invés de reload
                            setTimeout(() => {
                                window.location.href = "/";
                            }, 1000);
                        }
                    });
                },
                error: function (response) {
                    window.location.reload();
                }
            });
        } catch (e) {
            console.error('Erro no logout:', e);
            window.location.reload();
        }
    };

    // Função auxiliar para logout com formulário
    function logoutViaForm(token, url) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.style.display = 'none';

        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = token;

        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }

    // Obter a URL base segura
    function getBaseUrl() {
        let baseUrl = window.Laravel && window.Laravel.baseUrl
            ? window.Laravel.baseUrl
            : window.location.origin;

        // Garantir HTTPS
        if (baseUrl.startsWith('http:')) {
            baseUrl = baseUrl.replace('http:', 'https:');
        }

        return '';
    }

    // Mensagens de feedback para o usuário
    window.mostrarMensagemSucesso = function(mensagem) {
        const popup = document.createElement('div');
        popup.className = 'status-popup status-popup-success';
        popup.innerHTML = `
            <div class="status-icon status-icon-success">✓</div>
            <div class="status-message">${mensagem}</div>
            <div class="status-close">&times;</div>
            <div class="status-progress-success"></div>
        `;
        document.body.appendChild(popup);
        configurarPopup(popup);
    };

    window.mostrarMensagemErro = function(mensagem) {
        const popup = document.createElement('div');
        popup.className = 'status-popup status-popup-error';
        popup.innerHTML = `
            <div class="status-icon status-icon-error">✕</div>
            <div class="status-message">${mensagem}</div>
            <div class="status-close">&times;</div>
            <div class="status-progress-error"></div>
        `;
        document.body.appendChild(popup);
        configurarPopup(popup);
    };

    function configurarPopup(popup) {
        const closeBtn = popup.querySelector('.status-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                popup.remove();
            });
        }

        setTimeout(() => {
            popup.style.animation = 'fadeOutRight 0.3s ease-out forwards';
            popup.addEventListener('animationend', (e) => {
                if (e.animationName === 'fadeOutRight') {
                    popup.remove();
                }
            });
        }, 3000);
    }

    // =====================================
    // SEÇÃO 2: FUNÇÕES UTILITÁRIAS
    // =====================================

    // Função para obter URL base segura
    function getBaseUrl() {
        let baseUrl = window.Laravel && window.Laravel.baseUrl
            ? window.Laravel.baseUrl
            : window.location.origin;

        // Garantir HTTPS
        if (baseUrl.startsWith('http:')) {
            baseUrl = baseUrl.replace('http:', 'https:');
        }

        return '';
    }

    // Mensagens de feedback para o usuário
    window.mostrarMensagemSucesso = function(mensagem) {
        const popup = document.createElement('div');
        popup.className = 'status-popup status-popup-success';
        popup.innerHTML = `
            <div class="status-icon status-icon-success">✓</div>
            <div class="status-message">${mensagem}</div>
            <div class="status-close">&times;</div>
            <div class="status-progress-success"></div>
        `;
        document.body.appendChild(popup);
        configurarPopup(popup);
    };

    window.mostrarMensagemErro = function(mensagem) {
        const popup = document.createElement('div');
        popup.className = 'status-popup status-popup-error';
        popup.innerHTML = `
            <div class="status-icon status-icon-error">✕</div>
            <div class="status-message">${mensagem}</div>
            <div class="status-close">&times;</div>
            <div class="status-progress-error"></div>
        `;
        document.body.appendChild(popup);
        configurarPopup(popup);
    };

    function configurarPopup(popup) {
        const closeBtn = popup.querySelector('.status-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                popup.remove();
            });
        }

        setTimeout(() => {
            popup.style.animation = 'fadeOutRight 0.3s ease-out forwards';
            popup.addEventListener('animationend', (e) => {
                if (e.animationName === 'fadeOutRight') {
                    popup.remove();
                }
            });
        }, 3000);
    }

    // Atualização de token CSRF
    function atualizarCSRFToken() {
        return new Promise((resolve, reject) => {
            try {
                const baseUrl = '';//getBaseUrl();

                // Detectar o idioma atual a partir da URL
                let csrfUrl = '/csrf-token';
                const currentPath = window.location.pathname;
                if (currentPath.startsWith('/en/') || currentPath === '/en') {
                    csrfUrl = '/en/csrf-token';
                } else if (currentPath.startsWith('/es/') || currentPath === '/es') {
                    csrfUrl = '/es/csrf-token';
                }

                fetch(`${baseUrl}${csrfUrl}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Cache-Control': 'no-cache'
                    },
                    credentials: 'same-origin'
                })
                    .then(response => {
                        if (!response.ok) throw new Error(`Falha: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        if (!data.token) throw new Error('Token não retornado');

                        // Atualizar meta tag
                        const metaTag = document.querySelector('meta[name="csrf-token"]');
                        if (metaTag) metaTag.setAttribute('content', data.token);

                        // Atualizar jQuery
                        if (typeof $ !== 'undefined') {
                            $.ajaxSetup({
                                headers: { 'X-CSRF-TOKEN': data.token }
                            });
                        }

                        resolve(data.token);
                    })
                    .catch(error => {
                        console.error('Erro ao atualizar token CSRF:', error);
                        resolve(document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
                    });
            } catch (error) {
                console.error('Exceção ao atualizar token:', error);
                resolve(document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
            }
        });
    }

    // =====================================
    // SEÇÃO 3: MANIPULAÇÃO DE MODAIS
    // =====================================

    // Função de inicialização principal
    function initializeHeaderComponents() {
        // Encontrar elementos de modal
        const loginModal = document.getElementById('login-modal') ||
            document.getElementById('modal-login') ||
            document.querySelector('.login-modal') ||
            document.querySelector('.modal-login') ||
            document.querySelector('[data-modal="login"]');

        const loginOverlay = document.getElementById('login-modal-overlay') ||
            document.getElementById('login-overlay') ||
            document.querySelector('.modal-overlay') ||
            document.querySelector('.overlay');

        const registerModal = document.getElementById('register-modal') ||
            document.getElementById('modal-register') ||
            document.querySelector('.register-modal') ||
            document.querySelector('.modal-register') ||
            document.querySelector('[data-modal="register"]');

        const registerOverlay = document.getElementById('register-modal-overlay') ||
            document.getElementById('register-overlay') ||
            document.querySelector('.modal-overlay') ||
            document.querySelector('.overlay');

        // Botões de login
        configurarBotoesLogin(loginModal, loginOverlay);

        // Botões de registro
        configurarBotoesRegistro(registerModal, registerOverlay);

        // Depositar
        LoadJS();

        // Fechar modais
        configurarBotoesFechar(loginModal, loginOverlay, registerModal, registerOverlay);

        // Menu do usuário e avatares
        configurarMenuUsuario();

        // Dropdown "Ver mais" do Club VIP
        configurarDropdownVerMais();

        // Atualização de saldo
        configurarAtualizacaoSaldo();

        // Formulário de login
        configurarFormularioLogin();

        // Botões de logout
        configurarBotoesLogout();
    }

    // Funções auxiliares para inicialização
    function configurarBotoesLogin(loginModal, loginOverlay) {
        const loginButtons = document.querySelectorAll('.btn-login, #btn-login, [data-action="login"], a[href*="login"], .login-btn');

        if (loginButtons.length > 0) {
            loginButtons.forEach((button) => {
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);

                newButton.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (loginModal) {
                        loginModal.style.display = 'block';
                        loginModal.classList.remove('hidden');
                        loginModal.classList.add('show');

                        if (loginOverlay) {
                            loginOverlay.style.display = 'block';
                            loginOverlay.classList.remove('hidden');
                            loginOverlay.classList.add('show');
                        }

                        document.body.classList.add('modal-open');
                    }
                });
            });
        }
    }

    function configurarBotoesRegistro(registerModal, registerOverlay) {
        const registerButtons = document.querySelectorAll('a[href*="registre-se"], .register-btn');
        registerButtons.forEach(button => {
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);

            newButton.addEventListener('click', function(e) {
                e.preventDefault();

                if (registerOverlay && registerModal) {
                    registerOverlay.classList.add('show');
                    registerModal.classList.add('show');
                    registerModal.classList.remove('hidden');
                    registerModal.style.display = 'block';
                    document.body.classList.add('modal-open');
                }
            });
        });
    }

    function configurarBotoesFechar(loginModal, loginOverlay, registerModal, registerOverlay) {
        const closeButtons = document.querySelectorAll('.close-modal');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.auth-modal');
                if (modal) {
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');

                    if (loginModal) loginModal.classList.remove('show');
                    if (loginOverlay) loginOverlay.classList.remove('show');
                    if (registerModal) registerModal.classList.remove('show');
                    if (registerOverlay) registerOverlay.classList.remove('show');
                }
            });
        });
    }

    function configurarMenuUsuario() {
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenuDropdown = document.getElementById('userMenuDropdown');

        // Função para alternar dropdown
        function toggleUserMenu(e) {
            e.preventDefault();
            e.stopPropagation();
            userMenuDropdown.classList.toggle('hidden');
        }

        if (userMenuButton && userMenuDropdown) {
            userMenuButton.removeEventListener('click', toggleUserMenu);
            userMenuButton.addEventListener('click', toggleUserMenu);

            // Fechar dropdown ao clicar fora
            document.addEventListener('click', function(event) {
                if (userMenuDropdown && !userMenuDropdown.classList.contains('hidden')) {
                    if (!userMenuDropdown.contains(event.target) && (!userMenuButton || !userMenuButton.contains(event.target))) {
                        userMenuDropdown.classList.add('hidden');
                    }
                }
            });
        }

        // Configurar nome de usuário
        configurarNomeUsuario(userMenuDropdown);
    }

    function configurarNomeUsuario(userMenuDropdown) {
        const userNameElement = document.querySelector('.FzpBR');

        function showUsernameModal(event) {
            event.preventDefault();
            event.stopPropagation();

            const usernameModal = document.getElementById('usernameModal');
            if (usernameModal) {
                usernameModal.style.display = 'block';
                if (userMenuDropdown) userMenuDropdown.classList.add('hidden');
            }
        }

        function closeUsername() {
            const usernameModal = document.getElementById('usernameModal');
            if (usernameModal) usernameModal.style.display = 'none';
        }

        if (userNameElement) {
            userNameElement.removeEventListener('click', showUsernameModal);
            userNameElement.addEventListener('click', showUsernameModal);
        }

        const closeUsernameModal = document.getElementById('closeUsernameModal');
        if (closeUsernameModal) {
            closeUsernameModal.removeEventListener('click', closeUsername);
            closeUsernameModal.addEventListener('click', closeUsername);
        }

        // Formulário de atualização de nome
        const updateUsernameForm = document.getElementById('updateUsernameForm');
        if (updateUsernameForm) {
            updateUsernameForm.removeEventListener('submit', updateUsername);
            updateUsernameForm.addEventListener('submit', updateUsername);
        }

        function updateUsername(event) {
            event.preventDefault();
            const baseUrl = getBaseUrl();

            const newUsername = document.getElementById('username').value;
            if (!newUsername || !newUsername.trim()) {
                window.mostrarMensagemErro('O nome de usuário é obrigatório');
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`${baseUrl}/user/update-username`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: newUsername })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const nameElement = document.querySelector('.FzpBR');
                        if (nameElement) nameElement.textContent = newUsername.split(' ')[0].toUpperCase();

                        closeUsername();
                        window.mostrarMensagemSucesso('Nome atualizado com sucesso!');
                    } else {
                        window.mostrarMensagemErro(data.message || 'Erro ao atualizar nome');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    window.mostrarMensagemErro('Erro ao atualizar nome');
                });
        }
    }

    function configurarDropdownVerMais() {
        const toggleButton = document.getElementById('toggleClubVip');
        const clubVipMenu = document.getElementById('clubVipMenu');
        const toggleIcon = document.getElementById('toggleIcon');

        if (toggleButton && clubVipMenu && toggleIcon) {
            // Remover event listeners anteriores para evitar duplicação
            const newToggleButton = toggleButton.cloneNode(true);
            toggleButton.parentNode.replaceChild(newToggleButton, toggleButton);

            // Referenciar o novo elemento
            const updatedToggleButton = document.getElementById('toggleClubVip');
            const updatedClubVipMenu = document.getElementById('clubVipMenu');
            const updatedToggleIcon = document.getElementById('toggleIcon');

            if (updatedToggleButton && updatedClubVipMenu && updatedToggleIcon) {
                updatedToggleButton.addEventListener('click', function() {
                    const isOpen = updatedClubVipMenu.style.display !== 'none';

                    // Toggle menu visibility
                    updatedClubVipMenu.style.display = isOpen ? 'none' : 'block';

                    // Rotate icon when menu is open - Corrigido para mostrar a seta correta
                    if (isOpen) {
                        // Menu está sendo fechado, mostrar seta para baixo
                        updatedToggleIcon.innerHTML = `<svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5-12.5-32.8-12.5-45.3 0s-12.5-32.8 0-45.3l160-160z" fill="currentColor"></path>
                        </svg>`;

                    } else {
                        // Menu está sendo aberto, mostrar seta para cima
                        updatedToggleIcon.innerHTML = `<svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M201.4 374.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 306.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z" fill="currentColor"></path>
                        </svg>`;
                    }
                });
            }
        }
    }

    function configurarAtualizacaoSaldo() {
        const reloadButton = document.querySelector('.reloadButton');
        const reloadIcon = document.querySelector('.reload-icon');

        // Adicionar estilos CSS para as animações
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .spinning {
                animation: spin 1s linear infinite;
                display: inline-block;
            }
            .reloadButton {
                cursor: pointer;
            }
            @keyframes balanceHighlight {
                0% { opacity: 1; transform: scale(1); }
                50% { opacity: 1; transform: scale(1.1); color:rgba(0, 0, 0, 0.76); }
                100% { opacity: 1; transform: scale(1); }
            }
            .balance-highlight {
                animation: balanceHighlight 1s ease-in-out;
            }
        `;
        document.head.appendChild(style);

        if (reloadButton && reloadIcon) {
            reloadButton.addEventListener('click', function() {
                const baseUrl = getBaseUrl();

                // Adiciona classe para iniciar a animação
                reloadIcon.classList.add('spinning');

                // Desabilita o botão para evitar múltiplos cliques
                reloadButton.disabled = true;

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                // Requisição AJAX para atualizar o saldo
                fetch(`${baseUrl}/user/refresh-balance`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token || ''
                    },
                    credentials: 'same-origin'
                })
                    .then(response => {
                        if (response.status === 401) {
                            return { success: false, redirectToLogin: true };
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Se precisar redirecionar, não seguir com a atualização
                        if (data.redirectToLogin) return;

                        // Atualiza o saldo na interface
                        if (data.success) {
                            const balanceElements = document.querySelectorAll('.realAmount');
                            balanceElements.forEach(element => {
                                // Armazenar o valor anterior para comparação
                                const oldValue = parseFloat(element.textContent.replace('R$', '').replace(' ', '').replace(',', '.').trim()) || 0;
                                const newValue = parseFloat(data.balance) || 0;

                                // Atualizar com o novo valor formatado
                                element.textContent = 'R$\u00A0' + newValue.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                                // Adicionar classe para o efeito de destaque apenas se o valor mudou
                                if (oldValue !== newValue) {
                                    element.classList.remove('balance-highlight');
                                    void element.offsetWidth;
                                    element.classList.add('balance-highlight');
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao atualizar saldo:', error);
                    })
                    .finally(() => {
                        // Remove a classe de animação e reabilita o botão após 1 segundo
                        setTimeout(() => {
                            reloadIcon.classList.remove('spinning');
                            reloadButton.disabled = false;
                        }, 1000);
                    });
            });
        }
    }

    function RestoreLoginButton() {
        document.getElementById('login_button').disabled = false;
        document.getElementById('login_button').innerHTML = 'Entrar';
    }

    function configurarFormularioLogin() {
        const loginForm = document.getElementById('loginForm');
        if (!loginForm) return;

        // Remover manipuladores anteriores
        const newForm = loginForm.cloneNode(true);
        if (loginForm.parentNode) {
            loginForm.parentNode.replaceChild(newForm, loginForm);
        }

        RestoreLoginButton();

        newForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const baseUrl = getBaseUrl();

            try {
                // Campos obrigatórios
                const emailField = this.querySelector('input[name="email"]');
                const passwordField = this.querySelector('input[name="password"]');

                if (!emailField || !emailField.value.trim()) {
                    window.mostrarMensagemErro('O campo de email é obrigatório');
                    return;
                }

                if (!passwordField || !passwordField.value.trim()) {
                    window.mostrarMensagemErro('O campo de senha é obrigatório');
                    return;
                }

                // Formulário e envio
                const formData = new FormData(this);
                const actionUrl = this.action.replace('http:', 'https:');

                document.getElementById('login_button').disabled = true;
                document.getElementById('login_button').innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

                $.ajax({
                    url: actionUrl,
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        fecharModalLogin();
                        window.mostrarMensagemSucesso("Login realizado com sucesso!");

                        // Atualizar estado de autenticação IMEDIATAMENTE
                        window.isUserAuthenticated = true;

                        // Atualizar os componentes do header sem recarregar a página
                        atualizarHeaderAposLogin();

                        // Check if fetchRoute function exists before setting the interval
                        if (typeof fetchRoute === 'function') {
                            window.logado = 1; // Update login status
                            _intervalId = setInterval(fetchRoute, 5000);
                        }

                        // Disparar evento customizado para notificar outros scripts
                        if (window.dispatchEvent) {
                            window.dispatchEvent(new CustomEvent('auth:loginSuccess', {
                                detail: {
                                    authenticated: true,
                                    timestamp: Date.now()
                                }
                            }));
                        }
                    },
                    error: function (response) {
                        var errorResponse = response.responseJSON;

                        window.mostrarMensagemErro(errorResponse.message);
                        RestoreLoginButton();
                    }
                });
            } catch (error) {
                console.error('Erro na requisição de login:', error);
                window.mostrarMensagemErro('Ocorreu um erro de conexão. Verifique sua internet e tente novamente.');
            }
        });
    }

    // Função para configurar botões de logout
    function configurarBotoesLogout() {
        const logoutButtons = document.querySelectorAll('a.logout-button, button.logout-button');

        logoutButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                // Obter o token CSRF atual
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                    document.querySelector('input[name="_token"]')?.value || '';

                // Mostrar mensagem antes do logout
                window.mostrarMensagemErro("Saindo da sua conta...");

                // Criar um formulário de logout
                const logoutForm = document.createElement('form');
                logoutForm.method = 'POST';
                logoutForm.action = '/logout';
                logoutForm.style.display = 'none';

                // Adicionar token CSRF
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = token;

                // Adicionar input para redirecionamento
                const redirectInput = document.createElement('input');
                redirectInput.type = 'hidden';
                redirectInput.name = 'redirect';
                redirectInput.value = '/';

                // Montar e submeter o formulário
                logoutForm.appendChild(tokenInput);
                logoutForm.appendChild(redirectInput);
                document.body.appendChild(logoutForm);

                // Enviar o formulário após um pequeno delay para mostrar a mensagem
                setTimeout(() => {
                    // Forçar redirecionamento para home após logout
                    logoutForm.addEventListener('submit', function() {
                        setTimeout(() => {
                            window.location.href = '/';
                        }, 100);
                    });

                    logoutForm.submit();
                }, 500);
            });
        });
    }

    // =====================================
    // SEÇÃO 4: FUNÇÕES PARA AVATARES E MODAIS
    // =====================================

    // Atualização de avatar
    async function atualizarAvatar(avatarPath) {
        if (!avatarPath) return;

        const baseUrl = getBaseUrl();

        try {
            // Atualizar o token CSRF antes da requisição
            const token = await atualizarCSRFToken();

            if (!token) {
                window.mostrarMensagemErro('Erro de segurança. Recarregue a página e tente novamente.');
                return;
            }

            // Determinar locale para a URL correta
            let locale = '';
            const currentPath = window.location.pathname;

            if (currentPath.startsWith('/en/') || currentPath === '/en') {
                locale = 'en';
            } else if (currentPath.startsWith('/es/') || currentPath === '/es') {
                locale = 'es';
            }

            // URL baseada no locale
            let submitUrl = locale ? `${baseUrl}/${locale}/user/update-avatar` : `${baseUrl}/user/update-avatar`;

            // FormData
            const formData = new FormData();
            formData.append('image', avatarPath);
            formData.append('_token', token);

            // Enviar requisição
            $.ajax({
                url: submitUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(data) {
                    if (data && data.success) {
                        // Atualizar imagem no dropdown e todas as instâncias do avatar
                        atualizarImagensAvatar(baseUrl, avatarPath, data.avatarUrl);

                        // Fechar o modal
                        const avatarModal = document.getElementById('avatarModal');
                        if (avatarModal) {
                            avatarModal.style.display = 'none';
                        }

                        window.mostrarMensagemSucesso("Avatar atualizado com sucesso!");
                    } else {
                        window.mostrarMensagemErro(data && data.message ? data.message : 'Erro ao atualizar avatar');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na atualização de avatar:', error, 'Status:', xhr.status);

                    // Se for erro 419 ou 404, tentar URL alternativa com novo token
                    if (xhr.status === 419 || xhr.status === 404) {

                        // Tentar rotas alternativas em ordem
                        const alternativeUrls = [
                            `${baseUrl}/api/user/update-avatar`,
                            `${baseUrl}/user/avatar/update`,
                            `${baseUrl}/profile/avatar`
                        ];

                        // Tentar com a primeira URL alternativa
                        tryNextUrl(0);

                        function tryNextUrl(index) {
                            if (index >= alternativeUrls.length) {
                                // Tentamos todas as URLs sem sucesso
                                window.mostrarMensagemErro('Não foi possível atualizar o avatar. Tente novamente mais tarde.');
                                return;
                            }

                            // Atualizar o token novamente
                            atualizarCSRFToken().then(newToken => {
                                // Novo FormData com token atualizado
                                const newFormData = new FormData();
                                newFormData.append('image', avatarPath);
                                newFormData.append('_token', newToken);

                                $.ajax({
                                    url: alternativeUrls[index],
                                    type: 'POST',
                                    data: newFormData,
                                    processData: false,
                                    contentType: false,
                                    headers: {
                                        'X-CSRF-TOKEN': newToken,
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    success: function(data) {
                                        if (data && data.success) {
                                            // Atualizar imagem no dropdown e todas as instâncias do avatar
                                            atualizarImagensAvatar(baseUrl, avatarPath, data.avatarUrl);

                                            const avatarModal = document.getElementById('avatarModal');
                                            if (avatarModal) avatarModal.style.display = 'none';

                                            window.mostrarMensagemSucesso("Avatar atualizado com sucesso!");
                                        } else {
                                            // Tentar com a próxima URL alternativa
                                            tryNextUrl(index + 1);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(`Erro na tentativa ${index + 1}:`, error, 'Status:', xhr.status);
                                        // Tentar com a próxima URL alternativa
                                        tryNextUrl(index + 1);
                                    }
                                });
                            }).catch(error => {
                                console.error('Erro ao atualizar token CSRF:', error);
                                window.mostrarMensagemErro('Erro de segurança. Recarregue a página e tente novamente.');
                            });
                        }
                    } else {
                        window.mostrarMensagemErro('Erro ao atualizar avatar. Por favor, tente novamente.');
                    }
                }
            });
        } catch (error) {
            console.error('Exceção ao atualizar avatar:', error);
            window.mostrarMensagemErro('Ocorreu um erro. Por favor, recarregue a página e tente novamente.');
        }
    }

    // Exportar função para uso global
    window.atualizarAvatar = atualizarAvatar;

    // Função auxiliar para atualizar todas as instâncias do avatar na página
    function atualizarImagensAvatar(baseUrl, avatarPath, responseUrl) {
        // Usar a URL retornada pelo servidor se disponível, caso contrário usar o caminho local
        const avatarSrc = responseUrl || `${baseUrl}/${avatarPath}`;

        // Atualizar avatar no menu dropdown (selector mais comum)
        const avatarElements = document.querySelectorAll('.xHW6R, .avatar-user, .user-avatar, img[alt="User Avatar"]');
        avatarElements.forEach(element => {
            if (element && element.tagName === 'IMG') {
                // Verificar se a URL atual contém marcações de template não processadas
                const currentSrc = element.getAttribute('src');
                if (currentSrc && (currentSrc.includes('{{') || currentSrc.includes('route(') || currentSrc.includes('%7B%7B'))) {
                    element.src = avatarSrc;
                } else if (currentSrc) {
                    element.src = avatarSrc;
                }
            }
        });

        // Notificar possíveis componentes externos que o avatar foi atualizado
        if (window.dispatchEvent) {
            window.dispatchEvent(new CustomEvent('avatar:updated', {
                detail: { avatarUrl: avatarSrc }
            }));
        }
    }

    // Funções para manipulação de modais
    function fecharModalLogin() {
        // Verificar se existe modal de confirmação
        const cancelModal = document.getElementById('cancel-confirmation-modal');
        if (cancelModal && cancelModal.style.display !== 'none' && cancelModal.style.display !== '') {
            // Se o modal de confirmação estiver aberto, não fechar o login ainda
            return;
        }

        const loginModal = document.getElementById('login-modal');
        const modalOverlay = document.querySelector('.modal-overlay.show.active.visible') ||
            document.querySelector('.modal-overlay') ||
            document.getElementById('login-modal-overlay');

        // Fechar modal de confirmação se estiver aberto
        if (cancelModal) {
            cancelModal.style.display = 'none';
        }

        if (loginModal) {
            loginModal.style.display = 'none';
            loginModal.classList.remove('show');
            loginModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.body.classList.remove('modal-open');
        }

        if (modalOverlay) {
            modalOverlay.classList.remove('show');
            modalOverlay.classList.add('hidden');
            modalOverlay.style.display = 'none';
        }
    }

    // Função para verificar e mostrar modal de confirmação antes de fechar login
    function tentarFecharModalLogin() {
        const cancelModal = document.getElementById('cancel-confirmation-modal');
        if (cancelModal) {
            // Mostrar modal de confirmação
            cancelModal.style.display = 'flex';
            cancelModal.style.alignItems = 'center';
            cancelModal.style.justifyContent = 'center';
            return false; // Prevenir fechamento imediato
        }
        // Se não houver modal de confirmação, fechar normalmente
        fecharModalLogin();
        return true;
    }

    window.abrirModalLogin = function() {
        const loginModal = document.getElementById('login-modal') ||
            document.getElementById('modal-login') ||
            document.querySelector('.login-modal') ||
            document.querySelector('.modal-login') ||
            document.querySelector('[data-modal="login"]');

        const loginOverlay = document.getElementById('login-modal-overlay') ||
            document.getElementById('login-overlay') ||
            document.querySelector('.modal-overlay') ||
            document.querySelector('.overlay');

        // Garantir que o modal de confirmação esteja escondido
        const cancelModal = document.getElementById('cancel-confirmation-modal');
        if (cancelModal) {
            cancelModal.style.display = 'none';
        }

        if (loginModal) {
            loginModal.style.display = 'block';
            loginModal.classList.remove('hidden');
            loginModal.classList.add('show');

            if (loginOverlay) {
                loginOverlay.style.display = 'block';
                loginOverlay.classList.remove('hidden');
                loginOverlay.classList.add('show');
            }
        }
    }

    // Funções globais para alternância entre modais
    window.abrirModalRegistro = function() {
        const registerModal = document.getElementById('register-modal') ||
            document.getElementById('modal-register') ||
            document.querySelector('.register-modal') ||
            document.querySelector('.modal-register') ||
            document.querySelector('[data-modal="register"]');

        const registerOverlay = document.getElementById('register-modal-overlay') ||
            document.getElementById('register-overlay') ||
            document.querySelector('.modal-overlay') ||
            document.querySelector('.overlay');

        // Garantir que o modal de confirmação esteja escondido
        const cancelModalRegister = document.getElementById('cancel-confirmation-modal-register');
        if (cancelModalRegister) {
            cancelModalRegister.style.display = 'none';
        }

        if (registerModal) {
            registerModal.style.display = 'block';
            registerModal.classList.remove('hidden');
            registerModal.classList.add('show');

            if (registerOverlay) {
                registerOverlay.style.display = 'block';
                registerOverlay.classList.remove('hidden');
                registerOverlay.classList.add('show');
            }

            document.body.classList.add('modal-open');
        } else {
            console.error('Modal de registro não encontrado na página');
            window.location.href = `${getBaseUrl()}/register`;
        }
    };

    window.fecharModalRegistro = function() {
        // Verificar se existe modal de confirmação
        const cancelModalRegister = document.getElementById('cancel-confirmation-modal-register');
        if (cancelModalRegister && cancelModalRegister.style.display !== 'none' && cancelModalRegister.style.display !== '') {
            // Se o modal de confirmação estiver aberto, não fechar o registro ainda
            return;
        }

        const registerModal = document.getElementById('register-modal') ||
            document.getElementById('modal-register') ||
            document.querySelector('.register-modal') ||
            document.querySelector('.modal-register') ||
            document.querySelector('[data-modal="register"]');

        const registerOverlay = document.getElementById('register-modal-overlay') ||
            document.getElementById('register-overlay') ||
            document.querySelector('.modal-overlay') ||
            document.querySelector('.overlay');

        // Fechar modal de confirmação se estiver aberto
        if (cancelModalRegister) {
            cancelModalRegister.style.display = 'none';
        }

        if (registerModal) {
            registerModal.style.display = 'none';
            registerModal.classList.remove('show');
            registerModal.classList.add('hidden');

            if (registerOverlay) {
                registerOverlay.style.display = 'none';
                registerOverlay.classList.remove('show');
                registerOverlay.classList.add('hidden');
            }

            document.body.classList.remove('modal-open');
            document.body.classList.remove('overflow-hidden');
        }
    };

    // Função para verificar e mostrar modal de confirmação antes de fechar registro
    function tentarFecharModalRegistro() {
        const cancelModalRegister = document.getElementById('cancel-confirmation-modal-register');
        if (cancelModalRegister) {
            // Mostrar modal de confirmação
            cancelModalRegister.style.display = 'flex';
            cancelModalRegister.style.alignItems = 'center';
            cancelModalRegister.style.justifyContent = 'center';
            return false; // Prevenir fechamento imediato
        }
        // Se não houver modal de confirmação, fechar normalmente
        window.fecharModalRegistro();
        return true;
    }

    window.alternarParaLogin = function() {
        // Fechar modal de registro
        const registerModal = document.getElementById('register-modal') ||
            document.getElementById('modal-register') ||
            document.querySelector('.register-modal') ||
            document.querySelector('.modal-register') ||
            document.querySelector('[data-modal="register"]');

        const registerOverlay = document.getElementById('register-modal-overlay') ||
            document.getElementById('register-overlay') ||
            document.querySelector('.modal-overlay.show') ||
            document.querySelector('.overlay');

        if (registerModal) {
            registerModal.style.display = 'none';
            registerModal.classList.remove('show');
            registerModal.classList.add('hidden');
        }

        if (registerOverlay) {
            registerOverlay.classList.remove('show');
            registerOverlay.classList.add('hidden');
            registerOverlay.style.display = 'none';
        }

        // Abrir modal de login
        const loginModal = document.getElementById('login-modal') ||
            document.getElementById('modal-login') ||
            document.querySelector('.login-modal') ||
            document.querySelector('.modal-login') ||
            document.querySelector('[data-modal="login"]');

        const loginOverlay = document.getElementById('login-modal-overlay') ||
            document.getElementById('login-overlay') ||
            document.querySelector('.modal-overlay') ||
            document.querySelector('.overlay');

        if (loginModal) {
            loginModal.style.display = 'block';
            loginModal.classList.remove('hidden');
            loginModal.classList.add('show');

            if (loginOverlay) {
                loginOverlay.style.display = 'block';
                loginOverlay.classList.remove('hidden');
                loginOverlay.classList.add('show');
            }
        }
    };

    window.alternarParaRegistro = function() {
        // Fechar modal de login
        const loginModal = document.getElementById('login-modal') ||
            document.getElementById('modal-login') ||
            document.querySelector('.login-modal') ||
            document.querySelector('.modal-login') ||
            document.querySelector('[data-modal="login"]');

        const loginOverlay = document.getElementById('login-modal-overlay') ||
            document.getElementById('login-overlay') ||
            document.querySelector('.modal-overlay.show') ||
            document.querySelector('.overlay');

        if (loginModal) {
            loginModal.style.display = 'none';
            loginModal.classList.remove('show');
            loginModal.classList.add('hidden');
        }

        if (loginOverlay) {
            loginOverlay.classList.remove('show');
            loginOverlay.classList.add('hidden');
            loginOverlay.style.display = 'none';
        }

        // Abrir modal de registro
        const registerModal = document.getElementById('register-modal') ||
            document.getElementById('modal-register') ||
            document.querySelector('.register-modal') ||
            document.querySelector('.modal-register') ||
            document.querySelector('[data-modal="register"]');

        const registerOverlay = document.getElementById('register-modal-overlay') ||
            document.getElementById('register-overlay') ||
            document.querySelector('.modal-overlay') ||
            document.querySelector('.overlay');

        if (registerModal) {
            registerModal.style.display = 'block';
            registerModal.classList.remove('hidden');
            registerModal.classList.add('show');

            if (registerOverlay) {
                registerOverlay.style.display = 'block';
                registerOverlay.classList.remove('hidden');
                registerOverlay.classList.add('show');
            }
        }
    };

    // Configuração de links entre modais
    function configurarLinksAlternancia() {
        // Links para alternar para registro no modal de login
        const switchToRegisterLinks = document.querySelectorAll('#switch-to-register, .switch-to-register, [data-action="switch-to-register"]');
        if (switchToRegisterLinks.length > 0) {
            switchToRegisterLinks.forEach(link => {
                const newLink = link.cloneNode(true);
                link.parentNode.replaceChild(newLink, link);

                newLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.alternarParaRegistro();
                });
            });
        }

        // Links para alternar para login no modal de registro
        const switchToLoginLinks = document.querySelectorAll('#switch-to-login, .switch-to-login, [data-action="switch-to-login"]');
        if (switchToLoginLinks.length > 0) {
            switchToLoginLinks.forEach(link => {
                const newLink = link.cloneNode(true);
                link.parentNode.replaceChild(newLink, link);

                newLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.alternarParaLogin();
                });
            });
        }
    }

    // Configuração do botão de fechamento do modal de registro - com verificação de modal de confirmação
    function configurarFechamentoModalRegistro() {
        // Usar setTimeout para garantir que seja executado após os handlers dos arquivos blade
        setTimeout(function() {
            const closeRegisterBtn = document.getElementById('close-register-modal-btn') || document.querySelector('button[data-type="register"]');
            if (closeRegisterBtn) {
                // Adicionar listener com capture phase para executar ANTES de outros handlers
                closeRegisterBtn.addEventListener('click', function(e) {
                    // Verificar se existe modal de confirmação
                    const cancelModalRegister = document.getElementById('cancel-confirmation-modal-register');
                    if (cancelModalRegister) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        // Mostrar modal de confirmação dentro do modal de registro
                        cancelModalRegister.style.display = 'flex';
                        cancelModalRegister.style.alignItems = 'center';
                        cancelModalRegister.style.justifyContent = 'center';
                        return false;
                    }
                    // Se não houver modal de confirmação, permitir comportamento padrão
                }, true); // true = capture phase (executa antes)
            }
        }, 100);
    }

    // =====================================
    // SEÇÃO 5: FUNÇÃO PARA ATUALIZAR HEADER APÓS LOGIN
    // =====================================

    // Função para atualizar o header após login bem-sucedido
    function atualizarHeaderAposLogin() {
        try {
            const baseUrl = getBaseUrl();

            // Fazer requisição para obter o header atualizado
            $.ajax({
                url: "/header",
                type: "GET",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                },
                success: function (response) {
                    // Atualizar o conteúdo do header
                    const headerElement = document.getElementById('divPageHeader');
                    if (headerElement) {
                        headerElement.innerHTML = response;

                        // Reinicializar os componentes do header
                        initializeHeaderComponents();

                        // Reinicializar componentes específicos do sistema
                        if (typeof window.initSidebarControl === 'function') {
                            window.initSidebarControl();
                        }

                        // Reinicializar controles de depósito/saque se necessário
                        if (typeof LoadJS === 'function') {
                            LoadJS();
                        }

                        // Atualizar token CSRF após login
                        atualizarCSRFToken();

                        // Obter token JWT atualizado se estivermos na página de sports
                        if (document.getElementById('betby-sportsbook-container')) {
                            obterTokenJWTEDispararEvento();
                        } else {
                            // Atualizar estado de autenticação
                            window.isUserAuthenticated = true;

                            // Disparar evento customizado para outros scripts
                            if (window.dispatchEvent) {
                                window.dispatchEvent(new CustomEvent('header:updated', {
                                    detail: { reason: 'login' }
                                }));

                                window.dispatchEvent(new CustomEvent('auth:loginSuccess', {
                                    detail: {
                                        authenticated: true,
                                        timestamp: Date.now(),
                                        source: 'headerUpdate'
                                    }
                                }));
                            }
                        }

                        // Aguardar um pouco e verificar se há elementos que precisam ser reconfigurados
                        setTimeout(() => {
                            // Reconfigurar sidebar se existir
                            if (typeof window.sidebarControl !== 'undefined' &&
                                typeof window.sidebarControl.adjustClasses === 'function') {
                                window.sidebarControl.adjustClasses();
                            }

                            // Reconfigurar banners responsivos
                            if (typeof gerenciarBannersResponsivos === 'function') {
                                gerenciarBannersResponsivos();
                            }

                            // Reconfigurar inputs de formulário
                            if (typeof handleInputClasses === 'function') {
                                handleInputClasses();
                            }

                            // Verificar se os avatares padrão precisam ser corrigidos
                            const avatarElements = document.querySelectorAll('.xHW6R, .avatar-user, .user-avatar, img[alt="User Avatar"]');
                            avatarElements.forEach(element => {
                                if (element && element.tagName === 'IMG') {
                                    const currentSrc = element.getAttribute('src');
                                    if (currentSrc && (currentSrc.includes('{{') || currentSrc.includes('route(') || currentSrc.includes('%7B%7B'))) {
                                        // Avatar tem template não processado, usar imagem padrão
                                        element.src = '/images/avatar-default.png';
                                    }
                                }
                            });
                        }, 100);
                    }
                },
                error: function (xhr, status, error) {
                    // Em caso de erro, fazer um reload parcial mais suave
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            });
        } catch (error) {
            // Fallback para reload em caso de erro crítico
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
    }

    // Função para obter token JWT e disparar evento (específica para Betby)
    function obterTokenJWTEDispararEvento() {
        try {
            const baseUrl = getBaseUrl();

            // Obter token JWT do Betby (usar a mesma rota do sports)
            fetch(`/betby/token/refresh`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.token) {
                        // Atualizar token JWT global para o Betby
                        window.jwtToken = data.token;
                    } else {
                        window.jwtToken = null;
                    }

                    // Disparar evento customizado com o token atualizado
                    if (window.dispatchEvent) {
                        window.dispatchEvent(new CustomEvent('header:updated', {
                            detail: {
                                reason: 'login',
                                jwtToken: window.jwtToken
                            }
                        }));
                    }
                })
                .catch(error => {
                    // Disparar evento mesmo sem token (modo visitante)
                    window.jwtToken = null;
                    if (window.dispatchEvent) {
                        window.dispatchEvent(new CustomEvent('header:updated', {
                            detail: {
                                reason: 'login',
                                jwtToken: null
                            }
                        }));
                    }
                });
        } catch (error) {
            // Fallback: disparar evento sem token
            if (window.dispatchEvent) {
                window.dispatchEvent(new CustomEvent('header:updated', {
                    detail: {
                        reason: 'login',
                        jwtToken: null
                    }
                }));
            }
        }
    }

    // Expor as funções globalmente
    window.atualizarHeaderAposLogin = atualizarHeaderAposLogin;
    window.obterTokenJWTEDispararEvento = obterTokenJWTEDispararEvento;

    // =====================================
    // SEÇÃO 6: INICIALIZAÇÃO
    // =====================================

    // Expor as funções globalmente
    window.initializeHeaderComponents = initializeHeaderComponents;

    // Inicialização quando DOM estiver pronto
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // Inicializar componentes principais
            initializeHeaderComponents();

            // Configuração de botão de fechar login - com verificação de modal de confirmação
            // Usar setTimeout para garantir que seja executado após os handlers dos arquivos blade
            setTimeout(function() {
                const closeLoginBtn = document.getElementById('close-login-modal-btn') || document.querySelector('button[data-type="login"]');
                if (closeLoginBtn) {
                    // Adicionar listener com capture phase para executar ANTES de outros handlers
                    closeLoginBtn.addEventListener('click', function(e) {
                        // Verificar se existe modal de confirmação
                        const cancelModal = document.getElementById('cancel-confirmation-modal');
                        if (cancelModal) {
                            e.preventDefault();
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            // Mostrar modal de confirmação dentro do modal de login
                            cancelModal.style.display = 'flex';
                            cancelModal.style.alignItems = 'center';
                            cancelModal.style.justifyContent = 'center';
                            return false;
                        }
                        // Se não houver modal de confirmação, permitir comportamento padrão
                    }, true); // true = capture phase (executa antes)
                }
            }, 100);

            // Fechar modal ao clicar fora
            window.addEventListener('click', function(event) {
                const loginModal = document.getElementById('login-modal');
                if (loginModal && loginModal.style.display !== 'none') {
                    if (event.target === loginModal || event.target.classList.contains('modal-overlay')) {
                        fecharModalLogin();
                    }
                }
            });

            // Configurar links de alternância entre modais
            configurarLinksAlternancia();

            // Configurar fechamento do modal de registro
            configurarFechamentoModalRegistro();
        } catch (e) {
            console.error('Erro na inicialização dos componentes:', e);
        }
    });
})();
