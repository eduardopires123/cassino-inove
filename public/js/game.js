function iziError(msg, reload) {
    mostrarMensagemErro(msg);
}

function isMobile() {
    return /Mobi|Android/i.test(navigator.userAgent);
}

function detectOS() {
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;

    if (/android/i.test(userAgent)) {
        return "Android";
    }

    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return "iOS";
    }

    return "Unknown";
}

function disableScroll() {
    document.body.classList.add('no-scroll');
}

function enableScroll() {
    document.body.classList.remove('no-scroll');
}

// Função para criar slug amigável melhorado
function createSlug(text) {
    if (!text) return '';

    return text
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '') // Remove acentos
        .replace(/[^a-z0-9\s-]/g, '') // Remove caracteres especiais
        .replace(/\s+/g, '-') // Substitui espaços por hífens
        .replace(/-+/g, '-') // Remove hífens duplos
        .replace(/^-+|-+$/g, '') // Remove hífens do início e fim
        .trim();
}

// Função para limpar nome do provedor
function cleanProviderName(providerName) {
    if (!providerName) return '';

    // Remover "ORIGINAL" e "OFICIAL" do nome
    let cleanName = providerName.replace(/\b(ORIGINAL|OFICIAL)\b\s*-?\s*/gi, '');
    cleanName = cleanName.trim();
    cleanName = cleanName.replace(/\s+/g, ' '); // Remover espaços duplos
    cleanName = cleanName.replace(/^[-\s]+|[-\s]+$/g, ''); // Remover hífens e espaços nas extremidades

    return cleanName;
}

// Função para obter dados do jogo
function getGameData(gameId) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '/api/game-data/' + gameId,
            type: 'GET',
            success: function(data) {
                if (data.success) {
                    resolve(data);
                } else {
                    reject(data.message || 'Erro ao buscar dados do jogo');
                }
            },
            error: function() {
                reject('Erro na comunicação com o servidor');
            }
        });
    });
}

// Função para atualizar URL amigável após sucesso no carregamento do jogo
function updateGameUrl(gameId) {
    // Verificar se gameId é válido
    if (!gameId || isNaN(gameId)) {
        console.log('Game ID inválido para atualizar URL:', gameId);
        return;
    }

    getGameData(gameId).then(function(gameData) {
        if (gameData && gameData.success && gameData.provider_name && gameData.name) {
            // Limpar e criar slugs
            const cleanProvider = cleanProviderName(gameData.provider_name);
            const providerSlug = createSlug(cleanProvider);
            const gameSlug = createSlug(gameData.name);

            // Validar slugs antes de atualizar URL
            if (providerSlug && gameSlug && providerSlug.length > 0 && gameSlug.length > 0) {
                // Garantir que os slugs não contenham caracteres inválidos
                if (/^[a-z0-9\-]+$/.test(providerSlug) && /^[a-z0-9\-]+$/.test(gameSlug)) {
                const friendlyUrl = `/games/${providerSlug}/${gameSlug}`;

                    // Atualizar URL no navegador apenas se a URL for válida
                    try {
                history.pushState({
                    gameId: gameId,
                    provider: cleanProvider,
                    gameSlug: gameSlug
                }, gameData.name, friendlyUrl);
                    } catch (e) {
                        console.error('Erro ao atualizar URL no histórico:', e);
                    }
                } else {
                    console.warn('Slugs contêm caracteres inválidos:', { providerSlug, gameSlug });
                }
            } else {
                console.warn('Slugs vazios ou inválidos:', { providerSlug, gameSlug });
            }
        } else {
            console.warn('Dados do jogo incompletos:', gameData);
        }
    }).catch(function(error) {
        console.log('Erro ao atualizar URL amigável:', error);
        // Continua sem URL amigável em caso de erro
    });
}

function OpenGame(URL, SUB) {
    if (window.Tawk_API) {
        window.Tawk_API.hideWidget();
    }

    // Verificar e limpar status antes de abrir o jogo
    if (window.gameTabsPrevention) {
        window.gameTabsPrevention.verifyBackendStatus();

        // Aguardar um pouco para a verificação processar
        setTimeout(() => {
            continueOpenGame(URL, SUB);
        }, 200);
    } else {
        continueOpenGame(URL, SUB);
    }
}

function continueOpenGame(URL, SUB) {
    // SUB agora deve ser o ID do jogo, não o slug
    MAINURL = '/gm-init-v2-k7m3?id=' + SUB + '&platform=MOBILE';

    if (isMobile()) {
        if (detectOS() === "iOS") {
            disableScroll();
        }
    }

    $.ajax({
        url: MAINURL,
        type: "GET",
        data: $(this).serialize(),
        success: function (response) {
            if (response.message){
                iziError(response.message, false);
                return;
            }

            // Verificar se o usuário já está jogando
            if (response.show_toast && response.message) {
                // Verificar se o usuário está saindo antes de mostrar o toast
                const userExiting = localStorage.getItem('user_exiting_game');
                const exitTimestamp = localStorage.getItem('user_exit_timestamp');

                if (userExiting === 'true' && exitTimestamp) {
                    const timeDiff = Date.now() - parseInt(exitTimestamp);
                    if (timeDiff < 3000) { // Se foi há menos de 3 segundos

                        return;
                    }
                }

                // Verificar se realmente está jogando no backend antes de mostrar o toast
                if (window.gameTabsPrevention) {
                    window.gameTabsPrevention.verifyBackendStatus();

                    // Aguardar um pouco e tentar novamente
                    setTimeout(() => {
                        // Se após a verificação ainda há conflito, mostrar o toast
                        if (typeof window.mostrarMensagemErro === 'function') {
                            window.mostrarMensagemErro(response.message);
                        } else if (typeof iziError === 'function') {
                            iziError(response.message, false);
                        }
                    }, 500);
                } else {
                    if (typeof window.mostrarMensagemErro === 'function') {
                        window.mostrarMensagemErro(response.message);
                    } else if (typeof iziError === 'function') {
                        iziError(response.message, false);
                    }
                }
                return;
            }

            // Scroll para o topo apenas quando o jogo estiver disponível e carregado
            window.scrollTo(0, 0);
            document.body.scrollTop = 0; // Para Safari
            document.documentElement.scrollTop = 0; // Para Chrome, Firefox, IE e Opera

            if (isMobile()) {
                document.getElementById("__inove").style.display = 'none';
                document.getElementById("placegame").innerHTML = "<center><i class=\"fa fa-spinner fa-spin\"></i></center>";
            } else {
                document.getElementById("content-game").innerHTML = "<center><i class=\"fa fa-spinner fa-spin\"></i></center>";
            }

            if (isMobile()) {
                document.getElementById("placegame").innerHTML = response;
            } else {
                document.getElementById("content-game").innerHTML = response;
            }

            if (SUB.includes('3002')) {
                if (_classCont === "") {
                    _classCont = document.getElementById('content-game').className;
                    document.getElementById('content-game').className = "";
                    document.getElementById('baixogame').style.display = 'none';
                }
            }

            // Só atualizar a URL se o jogo carregou com sucesso (usuário logado)
            updateGameUrl(SUB);

            // Aguardar o carregamento completo da página do jogo e então fazer scroll
            setTimeout(function () {
                // Verificar se o jogo foi carregado com sucesso
                const gameLoaded = isMobile() ?
                    document.getElementById('placegame').innerHTML.trim() !== '' &&
                    !document.getElementById('placegame').innerHTML.includes('fa-spinner') :
                    document.getElementById('content-game').innerHTML.trim() !== '' &&
                    !document.getElementById('content-game').innerHTML.includes('fa-spinner');

                if (gameLoaded) {
                    // Scroll definitivo para o topo quando o jogo estiver carregado
                    window.scrollTo(0, 0);
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;

                    // Scroll suave para o elemento do jogo
                    const target = document.getElementById('placegame');
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            }, 500); // Aumentei o tempo para garantir que o jogo carregue
        },
        error: function (xhr, status, error) {
            if (xhr.status === 401) {
                // Exibe mensagem de erro para o usuário
                if (typeof mostrarMensagemErro === 'function') {
                    mostrarMensagemErro("Para jogar, faça login na sua conta.");
                } else if (typeof iziError === 'function') {
                    iziError("Para jogar, faça login na sua conta.", false);
                }

                // Força a abertura do modal de login
                var loginModal = document.getElementById('login-modal-overlay');
                if (loginModal) {
                    // Remove qualquer classe que possa estar escondendo o modal
                    loginModal.classList.remove('hide', 'hidden');
                    loginModal.classList.add('show');

                    // Se o modal tiver estilo inline, garante que ele seja visível
                    if (loginModal.style.display === 'none') {
                        loginModal.style.display = 'block';
                    }

                    // Manipulação adicional do elemento login-modal
                    var loginModalInner = document.getElementById('login-modal');
                    if (loginModalInner) {
                        loginModalInner.classList.remove('hidden');
                        loginModalInner.style.display = 'block';
                    }
                }

                // NÃO alterar a URL quando há erro 401 (usuário não logado)
                return; // Evita que o erro seja propagado
            } else {
                console.error('Erro ao carregar o jogo:', error);
            }
        }
    });
}

// Interceptar erros AJAX para exibir o modal
if (typeof $ !== 'undefined') {
    $(document).ajaxError(function(event, xhr, settings, error) {
        if (xhr.status === 401) {
            // Exibe mensagem de erro para o usuário
            if (typeof mostrarMensagemErro === 'function') {
                mostrarMensagemErro("Para jogar, faça login na sua conta.");
            } else if (typeof iziError === 'function') {
                iziError("Para jogar, por favor, faça login na sua conta.", false);
            }

            // Força a abertura do modal de login
            var loginModal = document.getElementById('login-modal-overlay');
            if (loginModal) {
                // Remove qualquer classe que possa estar escondendo o modal
                loginModal.classList.remove('hide', 'hidden');
                loginModal.classList.add('show');

                // Se o modal tiver estilo inline, garante que ele seja visível
                if (loginModal.style.display === 'none') {
                    loginModal.style.display = 'block';
                }

                // Manipulação adicional do elemento login-modal
                var loginModalInner = document.getElementById('login-modal');
                if (loginModalInner) {
                    loginModalInner.classList.remove('hidden');
                    loginModalInner.style.display = 'block';
                }
            }
        }
    });
}
