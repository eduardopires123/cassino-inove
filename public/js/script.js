function JogaFullMobile() {
    var iframe = document.getElementById('gameIframe');
    if (iframe.requestFullscreen) {
        iframe.requestFullscreen();
    } else if (iframe.mozRequestFullScreen) { // Firefox
        iframe.mozRequestFullScreen();
    } else if (iframe.webkitRequestFullscreen) { // Chrome, Safari and Opera
        iframe.webkitRequestFullscreen();
    } else if (iframe.msRequestFullscreen) { // IE/Edge
        iframe.msRequestFullscreen();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const avatarEditBtn = document.getElementById('avatar-edit-btn');
    const avatarModal = document.getElementById('avatarModal');
    const closeAvatarModal = document.getElementById('closeAvatarModal');
    const avatarOptions = document.querySelectorAll('.avatar-option:not(.locked)');

    // Verificar se os elementos do modal de avatar existem antes de prosseguir
    if (avatarEditBtn && avatarModal) {
        let selectedAvatar = null;

        // Abrir o modal ao clicar no botão de edição
        avatarEditBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            avatarModal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevenir rolagem
        });

        // Fechar o modal
        if (closeAvatarModal) {
            closeAvatarModal.addEventListener('click', function() {
                avatarModal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Restaurar rolagem
            });
        }

        // Fechar o modal ao clicar fora
        window.addEventListener('click', function(event) {
            if (event.target == avatarModal) {
                avatarModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        // Selecionar um avatar (apenas para avatares não bloqueados)
        avatarOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Verificar se este avatar está bloqueado
                if (this.classList.contains('locked')) {
                    return; // Não faz nada se estiver bloqueado
                }

                // Remover a seleção anterior
                avatarOptions.forEach(opt => {
                    opt.style.border = '3px solid transparent';
                });

                // Destacar o avatar selecionado
                this.style.border = '3px solid var(--primary-color)';
                selectedAvatar = this.getAttribute('data-avatar');
                TypeAvatar = this.getAttribute('data-type');

                // Enviar imediatamente a seleção para o servidor
                updateUserAvatar(selectedAvatar, TypeAvatar);
            });
        });

        // Função para atualizar o avatar do usuário
        function updateUserAvatar(avatar, tipo) {
            // Identificar o prefixo de idioma atual
            const currentPath = window.location.pathname;
            const languagePrefixes = ['', 'en', 'es', 'pt_BR'];

            let languagePrefix = '';

            // Detectar o prefixo de idioma atual
            for (const prefix of languagePrefixes) {
                if (prefix && currentPath.startsWith(`/${prefix}`)) {
                    languagePrefix = prefix;
                    break;
                }
            }

            // Construir URL de atualização de avatar com prefixo de idioma
            let avatarUpdateUrl = '/profile/update-avatar';

            if (languagePrefix) {
                avatarUpdateUrl = `/${languagePrefix}${avatarUpdateUrl}`;
            }

            // Obter token CSRF de forma robusta
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Backup: tentar obter token de input hidden
            if (!csrfToken) {
                csrfToken = document.querySelector('input[name="_token"]')?.value;
            }

            // Segundo backup: procurar no cookie
            if (!csrfToken) {
                const tokenCookie = document.cookie.split('; ').find(cookie => cookie.startsWith('XSRF-TOKEN='));
                if (tokenCookie) {
                    csrfToken = decodeURIComponent(tokenCookie.split('=')[1]);
                }
            }

            // Validar token CSRF
            if (!csrfToken) {
                console.error('Token CSRF não encontrado');
                window.mostrarMensagemErro('Erro de segurança. Recarregue a página.');
                return;
            }

            // Validar avatar
            if (!avatar) {
                console.error('Avatar não fornecido');
                window.mostrarMensagemErro('Selecione uma imagem válida.');
                return;
            }

            if (typeof avatar === 'string' && !avatar.trim()) {
                console.error('Avatar string vazia');
                window.mostrarMensagemErro('Selecione uma imagem válida.');
                return;
            }


            if (typeof avatar === 'string') {
            }

            // Criar FormData para upload
            const formData = new FormData();

            // Verificar tipo do avatar
            if (avatar instanceof File || avatar instanceof Blob) {
                formData.append('image', avatar);
            } else if (typeof avatar === 'string') {
                formData.append('image', avatar);
            }

            formData.append('type', tipo);
            formData.append('_token', csrfToken);

            // Mostrar indicador de carregamento
            const loadingElement = document.getElementById('loading-indicator');
            if (loadingElement) {
                loadingElement.style.display = 'block';
            }

            // Fazer requisição de atualização
            fetch(avatarUpdateUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData,
                credentials: 'same-origin'
            })
                .then(response => {
                    // Ocultar indicador de carregamento
                    if (loadingElement) {
                        loadingElement.style.display = 'none';
                    }

                    if (!response.ok) {
                        // Tentar obter mais detalhes sobre o erro
                        return response.json().catch(() => {
                            throw new Error(`Erro HTTP: ${response.status}`);
                        }).then(errorData => {
                            // Se temos dados de erro detalhados
                            if (errorData && errorData.message) {
                                throw new Error(`Erro ${response.status}: ${errorData.message}`);
                            } else {
                                throw new Error(`Erro HTTP: ${response.status}`);
                            }
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success || data.avatarUrl || data.avatar) {
                        handleSuccess(data);
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro ao atualizar avatar:', error);
                    window.mostrarMensagemErro(error);

                    // Ocultar modal se necessário
                    const avatarModal = document.getElementById('avatarModal') ||
                        document.querySelector('.avatar-modal');
                    if (avatarModal) {
                        avatarModal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                });

            // Função para lidar com sucesso
            function handleSuccess(data) {

                // Atualizar imagens de avatar na interface
                const userAvatars = document.querySelectorAll('.xHW6R, .avatar-user, .user-avatar, img[alt="User Avatar"]');
                const avatarSrc = data.avatarUrl || data.avatar || data.url || avatar;

                userAvatars.forEach(img => {
                    if (img && img.tagName === 'IMG') {
                        // Adicionar timestamp para evitar cache
                        const timestamp = new Date().getTime();
                        img.src = typeof avatarSrc === 'string' && avatarSrc.includes('?')
                            ? `${avatarSrc}&t=${timestamp}`
                            : `${avatarSrc}?t=${timestamp}`;
                    }
                });

                // Fechar modal se existir
                const avatarModal = document.getElementById('avatarModal') ||
                    document.querySelector('.avatar-modal');
                if (avatarModal) {
                    avatarModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }

                // Mostrar mensagem de sucesso
                window.mostrarMensagemSucesso('Avatar atualizado com sucesso!');

                // Notificar componentes externos
                if (window.dispatchEvent) {
                    window.dispatchEvent(new CustomEvent('avatar:updated', {
                        detail: { avatarUrl: avatarSrc }
                    }));
                }
            }
        }
    }
});

// NOME DE USUARIO
document.addEventListener('DOMContentLoaded', function() {
    const usernameEditBtn = document.getElementById('username-edit-btn');
    const usernameModal = document.getElementById('usernameModal');
    const closeUsernameModal = document.getElementById('closeUsernameModal');
    const updateUsernameForm = document.getElementById('updateUsernameForm');

    // Abrir o modal ao clicar no botão de edição de nome
    if (usernameEditBtn) {
        usernameEditBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            usernameModal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevenir rolagem
        });
    }

    // Fechar o modal
    if (closeUsernameModal) {
        closeUsernameModal.addEventListener('click', function() {
            usernameModal.style.display = 'none';
            document.body.style.overflow = 'auto'; // Restaurar rolagem
        });
    }

    // Fechar o modal ao clicar fora
    window.addEventListener('click', function(event) {
        if (event.target == usernameModal) {
            usernameModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // Enviar o formulário
    if (updateUsernameForm) {
        updateUsernameForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const newUsername = document.getElementById('username').value;

            // Validar o username
            if (!newUsername || newUsername.trim() === '') {
                alert('Por favor, insira um nome válido.');
                return;
            }

            // Obter o token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Construir a URL corretamente
            const baseUrl = window.location.origin;
            let updateUsernameUrl = `${baseUrl}/user/update-username`;

            // Considerar locale da URL atual
            const path = window.location.pathname;
            if (path.startsWith('/en/') || path === '/en') {
                updateUsernameUrl = `${baseUrl}/en/user/update-username`;
            } else if (path.startsWith('/es/') || path === '/es') {
                updateUsernameUrl = `${baseUrl}/es/user/update-username`;
            }

            // Enviar a requisição AJAX
            fetch(updateUsernameUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ name: newUsername })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Atualizar o nome do usuário na interface
                        const userNameElements = document.querySelectorAll('.FzpBR');
                        userNameElements.forEach(el => {
                            // Mostrar apenas o primeiro nome
                            el.innerText = data.name.split(' ')[0];
                        });

                        // Fechar o modal
                        usernameModal.style.display = 'none';
                        document.body.style.overflow = 'auto';

                        // Mostrar mensagem de sucesso
                        if (typeof window.mostrarMensagemSucesso === 'function') {
                            window.mostrarMensagemSucesso('Nome atualizado com sucesso!');
                        }
                    } else if (data.error) {
                        // Mostrar mensagem de erro
                        if (typeof window.mostrarMensagemErro === 'function') {
                            window.mostrarMensagemErro(data.error);
                        } else {
                            alert(data.error);
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro ao atualizar nome de usuário:', error);

                    // Mostrar mensagem de erro
                    if (typeof window.mostrarMensagemErro === 'function') {
                        window.mostrarMensagemErro('Erro ao atualizar nome. Por favor, tente novamente.');
                    } else {
                        alert('Erro ao atualizar nome. Por favor, tente novamente.');
                    }
                });
        });
    }
});

// Adicionando a funcionalidade de pesquisa
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchResultsDropdown = document.getElementById('search-results-dropdown');
    const searchResultsContainer = document.getElementById('search-results-container');
    const searchLoadMoreBtn = document.getElementById('search-load-more-btn');
    const searchProgressBar = document.getElementById('search-progress-bar');
    const searchShowingText = document.getElementById('search-showing-text');
    let searchTimer;
    let currentShowing = 0;
    let totalGames = 0;
    let currentCategory = 'all'; // Adicionar controle de categoria

    // Verificar se todos os elementos necessários existem
    if (!searchInput || !searchResultsDropdown || !searchResultsContainer ||
        !searchLoadMoreBtn || !searchProgressBar || !searchShowingText) {
        console.warn('Elementos de pesquisa não encontrados no DOM. A funcionalidade de pesquisa não será inicializada.');
        return; // Sair da função se algum elemento estiver faltando
    }

    // Escutar mudanças de categoria vindas do home.blade.php
    window.addEventListener('categoryFilterChanged', function(event) {
        currentCategory = event.detail.category;

        // Se há um termo de busca ativo, refazer a pesquisa com a nova categoria
        const searchTerm = searchInput.value.trim();
        if (searchTerm.length >= 2) {
            // Limpar resultados anteriores
            searchResultsContainer.innerHTML = '';
            currentShowing = 0;
            searchLoadMoreBtn.setAttribute('data-page', '1');

            // Remover mensagem de "não encontramos resultados" se existir
            const existingMessage = document.getElementById('no-results-message');
            if (existingMessage) {
                existingMessage.remove();
            }

            // Executar nova pesquisa com categoria
            loadSearchResults(searchTerm, 1, currentCategory);
        }
    });

    // Função para mostrar/esconder o dropdown de resultados
    function toggleSearchResults(show) {
        searchResultsDropdown.style.display = show ? 'block' : 'none';

        // Garantir que a mensagem de erro esteja oculta quando abrimos o dropdown
        if (show) {
            const noResultsMessage = document.getElementById('no-results-message');
            if (noResultsMessage) {
                noResultsMessage.style.cssText = 'display: none !important';
            }
        }
    }

    // Função para processar a pesquisa
    function processSearch() {
        const searchTerm = searchInput.value.trim();

        // Se o termo de pesquisa estiver vazio, esconde o dropdown e sai da função
        if (!searchTerm) {
            toggleSearchResults(false);
            return;
        }

        // Limpar resultados anteriores
        searchResultsContainer.innerHTML = '';
        currentShowing = 0;
        searchLoadMoreBtn.setAttribute('data-page', '1');

        // Remover mensagem de "não encontramos resultados" se existir
        const existingMessage = document.getElementById('no-results-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Mostrar o dropdown de resultados
        toggleSearchResults(true);

        // Executar a pesquisa
        loadSearchResults(searchTerm, 1, currentCategory);
    }

    // Função para carregar resultados da pesquisa
    function loadSearchResults(searchTerm, page, category = 'all') {
        // Adicionar indicador de carregamento
        searchLoadMoreBtn.disabled = true;
        searchLoadMoreBtn.innerHTML = '<span>Carregando...</span>';

        // Construir a URL de pesquisa - usar a rota correta baseada na página atual
        let url;
        if (window.location.pathname === '/' || window.location.pathname.includes('/home')) {
            // Página home - usar rota específica do home
            url = `/jogos/pesquisar?search=${encodeURIComponent(searchTerm)}&page=${page}&per_page=12`;
            if (category && category !== 'all') {
                url += `&category=${encodeURIComponent(category)}`;
            }
        } else {
            // Outras páginas - usar rota genérica
            url = `/jogos/pesquisar?search=${encodeURIComponent(searchTerm)}&page=${page}&per_page=12`;
        }

        // Fazer a requisição AJAX
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na requisição: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                // Remover mensagem de erro existente, se houver
                const existingMessage = document.getElementById('no-results-message');
                if (existingMessage) {
                    existingMessage.remove();
                }

                // Se não houver resultados
                if (!data.games || data.games.length === 0) {
                    // Criar elemento de mensagem
                    const noResultsMessage = document.createElement('div');
                    noResultsMessage.id = 'no-results-message';
                    noResultsMessage.className = 'flex items-center justify-center self-center alert alert-warning';
                    noResultsMessage.style.display = 'flex';
                    noResultsMessage.innerHTML = '<span class="">Não encontramos resultados para sua busca</span>';

                    // Inserir a mensagem antes do container de resultados
                    searchResultsContainer.parentNode.insertBefore(noResultsMessage, searchResultsContainer);

                    searchShowingText.textContent = 'Mostrando 0 de 0 jogos';
                    searchProgressBar.style.width = '0%';
                    searchLoadMoreBtn.style.display = 'none';
                    return;
                } else {
                    const noResultsMessage = document.getElementById('no-results-message');
                    if (noResultsMessage) {
                        noResultsMessage.style.display = 'none';
                    }
                }

                // Adicionar jogos ao container
                data.games.forEach(game => {
                    const gameElement = `
                        <a href="JavaScript: void(0);" onclick="OpenGame('games', '${game.id}');" class="hZm-w s3HXA" data-game-id="${game.id}">
                            <div class="u3Qxq">
                                <div class="g-hw5">
                                    <img
                                        alt="${game.name}"
                                        class="vTFYb"
                                        src="${game.image_url ? game.image_url : game.image}"
                                    />
                                </div>
                                <div class="hzP6t"><span class="phlJe">${game.name}</span><span class="liQBm">${game.provider}</span></div>
                                <section class="bBtlK">
                                    <span class="Oe7Pi">
                                        <span class="nuxt-icon nuxt-icon--fill">
                                            <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <span>Jogar</span>
                                    </span>
                                </section>
                            </div>
                        </a>
                    `;
                    searchResultsContainer.insertAdjacentHTML('beforeend', gameElement);
                });

                // Atualizar contadores
                currentShowing += data.games.length;
                totalGames = data.total;
                searchShowingText.textContent = `Mostrando ${currentShowing} de ${totalGames} jogos`;

                // Atualizar barra de progresso
                const progressPercentage = (currentShowing / totalGames) * 100;
                searchProgressBar.style.width = `${progressPercentage}%`;

                // Atualizar botão de carregar mais
                searchLoadMoreBtn.setAttribute('data-page', (data.page + 1).toString());

                // Esconder o botão se todos os jogos foram carregados
                if (currentShowing >= totalGames) {
                    searchLoadMoreBtn.style.display = 'none';
                } else {
                    searchLoadMoreBtn.style.display = 'block';
                    searchLoadMoreBtn.disabled = false;
                    searchLoadMoreBtn.innerHTML = '<span>Carregar mais</span>';
                }

                // Adicionar eventos aos links dos jogos para registrar visualizações
                document.querySelectorAll('#search-results-container .hZm-w.s3HXA[data-game-id]').forEach(gameLink => {
                    if (!gameLink.hasEventListener) {
                        gameLink.addEventListener('click', function(e) {
                            // Se estiver clicando no botão de jogar, não impedir a navegação
                            if (e.target.closest('.Oe7Pi')) {
                                return;
                            }

                            // Prevenir a navegação imediata
                            e.preventDefault();

                            const gameId = this.getAttribute('data-game-id');

                            // Enviar solicitação AJAX para incrementar a visualização
                            fetch(`/jogos/incrementar-visualizacao/${gameId}`)
                                .then(response => response.json())
                                .then(data => {

                                    // Após registrar, redirecionar para a página do jogo
                                    window.location.href = this.href;
                                })
                                .catch(error => {
                                    console.error('Erro ao registrar visualização:', error);

                                    // Em caso de erro, ainda redireciona
                                    window.location.href = this.href;
                                });
                        });
                        gameLink.hasEventListener = true;
                    }
                });
            })
            .catch(error => {
                console.error('Erro ao pesquisar jogos:', error);

                // Exibir mensagem de erro
                searchResultsContainer.innerHTML = `
                    <div class="error-message p-4 text-center">
                        <p>Ocorreu um erro ao pesquisar jogos. Por favor, tente novamente.</p>
                    </div>
                `;
                searchLoadMoreBtn.disabled = false;
                searchLoadMoreBtn.innerHTML = '<span>Tentar novamente</span>';
            });
    }

    // Ouvinte para eventos de digitação no campo de pesquisa
    searchInput.addEventListener('input', function() {
        // Limpar o timer anterior
        if (searchTimer) {
            clearTimeout(searchTimer);
        }

        // Definir novo timer (500ms de atraso)
        searchTimer = setTimeout(processSearch, 500);
    });

    // Ouvinte para o botão "Carregar mais"
    searchLoadMoreBtn.addEventListener('click', function() {
        const page = parseInt(this.getAttribute('data-page'));
        const searchTerm = searchInput.value.trim();

        loadSearchResults(searchTerm, page, currentCategory);
    });

    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.gameSearchBar') && !event.target.closest('.searchResults')) {
            toggleSearchResults(false);
        }
    });

    // Fechar dropdown ao pressionar ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            toggleSearchResults(false);
        }
    });
});

// Limpar o campo de pesquisa após o carregamento da página
setTimeout(function() {
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.value = '';
        searchInput.removeAttribute('readonly');
    }
}, 100);


// verificação de idade
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se já existe uma verificação de idade válida no localStorage
    if (isAgeVerificationValid()) {
        hideAgeVerification();
        return;
    }

    // Pegar elementos do modal
    const container = document.querySelector('.age-verification-container');
    const confirmationModal = document.getElementById('confirmationModal');
    const errorModal = document.getElementById('errorModal');

    // Verificar se os elementos existem antes de prosseguir
    if (!confirmationModal || !errorModal) {
        console.warn('Elementos de verificação de idade não encontrados no DOM');
        return;
    }

    const btnYes = confirmationModal.querySelector('.OFi5b');
    const btnNo = confirmationModal.querySelector('.pCZTM');
    const btnClose = errorModal.querySelector('.i4ptq');

    // Inicialmente mostrar o modal de confirmação e esconder o de erro
    confirmationModal.classList.add('show');
    confirmationModal.classList.remove('hidden');
    errorModal.classList.add('hidden');
    errorModal.classList.remove('show');

    // Evento para o botão "Sim"
    if (btnYes) {
        btnYes.addEventListener('click', function() {
            // Salvar a verificação no localStorage com data de expiração
            saveAgeVerification();
            hideAgeVerification();
        });
    }

    // Evento para o botão "Não"
    if (btnNo) {
        btnNo.addEventListener('click', function() {
            showErrorModal();
        });
    }

    // Evento para o botão fechar no modal de restrição
    if (btnClose) {
        btnClose.addEventListener('click', function() {
            showConfirmationModal();
        });
    }
});

// Funções globais para uso nos atributos onclick no HTML
function hideAgeVerification() {
    // Esconder o container de verificação de idade
    const container = document.querySelector('.age-verification-container');
    if (container) {
        container.classList.add('hidden');
    }
}

function showErrorModal() {
    const confirmationModal = document.getElementById('confirmationModal');
    const errorModal = document.getElementById('errorModal');

    // Verificar se os elementos existem antes de manipulá-los
    if (confirmationModal) {
        confirmationModal.classList.remove('show');
        confirmationModal.classList.add('hidden');
    }

    if (errorModal) {
        errorModal.classList.remove('hidden');
        errorModal.classList.add('show');
    }
}

function showConfirmationModal() {
    const confirmationModal = document.getElementById('confirmationModal');
    const errorModal = document.getElementById('errorModal');

    // Verificar se os elementos existem antes de manipulá-los
    if (errorModal) {
        errorModal.classList.remove('show');
        errorModal.classList.add('hidden');
    }

    if (confirmationModal) {
        confirmationModal.classList.remove('hidden');
        confirmationModal.classList.add('show');
    }
}

function confirmAge() {
    // Salvar no localStorage que o usuário confirmou a idade com data de expiração
    saveAgeVerification();
    hideAgeVerification();
}

// Salvar verificação de idade por 15 dias
function saveAgeVerification() {
    const expirationDate = new Date();
    // Adicionar 15 dias à data atual
    expirationDate.setDate(expirationDate.getDate() + 15);

    const ageVerificationData = {
        verified: true,
        expiration: expirationDate.getTime() // Salvar como timestamp (milissegundos)
    };

    localStorage.setItem('age_verification', JSON.stringify(ageVerificationData));
}

// Verificar se a verificação de idade é válida (ainda não expirou)
function isAgeVerificationValid() {
    const ageVerificationString = localStorage.getItem('age_verification');

    // Se não existir verificação, retorna falso
    if (!ageVerificationString) {
        return false;
    }

    try {
        const ageVerification = JSON.parse(ageVerificationString);
        const now = new Date().getTime();

        // Verificar se a verificação ainda é válida
        return ageVerification.verified && now < ageVerification.expiration;
    } catch (error) {
        // Em caso de erro ao fazer parse do JSON, remover item inválido
        localStorage.removeItem('age_verification');
        return false;
    }
}
