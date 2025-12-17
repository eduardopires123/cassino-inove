let MobileFrame = null;
let isNavigatingBack = false;

// Usar window.navigationStack diretamente para manter sincronizado
if (!window.navigationStack) {
    window.navigationStack = [];
}
let navigationStack = window.navigationStack;

// Sistema de rastreamento de CSS para garantir que o loading só feche após CSS ser injetado
window.cssInjectionTracker = {
    desktop: {
        cssConfigured: false,
        iframeLoaded: false,
        cssApplied: false
    },
    mobile: {
        elementsFound: 0,
        cssInjected: 0,
        cssLoaded: 0,
        allLoaded: false
    }
};

function closeLoading() {
    const cssLoading = document.getElementById('css-loading');

    if (cssLoading) {
        // Adicionar classe de fade-out
        cssLoading.classList.add('fade-out');

        // Remover após a animação
        setTimeout(function() {
            cssLoading.remove();
            // Restaurar scroll
            document.body.classList.remove('no-scroll');
        }, 300);
    }
}

function OpenLoginMobile() {
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
}

async function IniciaSport(location) {
    const token = await mToken();
    location = location || window.location;

    const sportDivIframe = document.getElementById('sport_div_iframe');
    if (sportDivIframe) {
        sportDivIframe.style.display = 'block';
    }

    const url = new URL(window.location.href);
    const btBookingCode = url.searchParams.get('btBookingCode');

    if (!isMobile()) {
        const params = [
            ['server', 'https://sport.bookiewiseapi.com'],
            ['token', token],
            ['parent', [location.host]],
            ['currentPage', (!isNaN(location)) ? 'Upcoming' : (location !== 'Home') ? location : 'Home'],
            ['eventId', (!isNaN(location)) ? location : 0],
            ['language', 'pt-BR'],
            ['sportsBookView', 'asianView'],
            ['partner', ['any']],
            ['sportPartner', 'f41206f1-981a-4a44-b762-022e958ecd63']
        ];

        if (btBookingCode) {
            params.push(['betslipBookNumber', btBookingCode]);
        }

        const DesktopFrame = await SportFrame.frame(params);

        setTimeout(configureIframe, 500);

        // Interceptar eventos de navegação do iframe
        const iframe = document.querySelector('#sport_div_iframe iframe');
        if (iframe) {
            // Tentar acessar o contentWindow do iframe (pode não funcionar devido a CORS)
            try {
                iframe.contentWindow.addEventListener('hashchange', function() {
                    // Hash changed
                });
            } catch (e) {
                // CORS bloqueado
            }

            // Observer para mudanças no src do iframe
            const srcObserver = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'src') {
                        const newSrc = iframe.src;

                        // Extrair informação da URL do iframe
                        try {
                            const iframeUrl = new URL(newSrc);
                            const searchParams = iframeUrl.searchParams;
                            const hash = iframeUrl.hash;

                            // Atualizar URL do navegador baseado na URL do iframe
                            if (searchParams.has('page') || searchParams.has('sport') || hash) {
                                const baseUrl = window.location.origin + window.location.pathname;
                                let newUrl = baseUrl;

                                if (searchParams.has('page')) {
                                    newUrl += '?page=' + searchParams.get('page');
                                } else if (searchParams.has('sport')) {
                                    newUrl += '?sport=' + searchParams.get('sport');
                                } else if (hash) {
                                    newUrl += '?view=' + encodeURIComponent(hash);
                                }

                                history.pushState({
                                    digitainNavigation: true,
                                    page: searchParams.get('page') || searchParams.get('sport') || hash,
                                    timestamp: Date.now()
                                }, '', newUrl);
                            }
                        } catch (e) {
                            // Erro ao processar URL
                        }
                    }
                });
            });

            srcObserver.observe(iframe, { attributes: true });
        }
    } else {
        var params = {
            server: 'https://sport.bookiewiseapi.com',
            containerId: 'application-container',
            token: token,
            parent: [location.host],
            currentPage: (location != 'Home') ? location : 'Home',
            defaultLanguage: 'pt-BR',
            sportPartner: 'f41206f1-981a-4a44-b762-022e958ecd63',

            login: function () {
                OpenLoginMobile()
            },
        };

        if (btBookingCode) {
            params.betslipBookNumber = btBookingCode;
        }

        if (typeof Bootstrapper !== 'undefined' && typeof Bootstrapper.bootIframe === 'function') {

            // Hide the container initially
            const container = document.getElementById('application-container');
            if (container) {
                container.style.visibility = 'hidden';
            }

            // Pre-inject CSS into the document
            const preloadCSS = document.createElement('link');
            preloadCSS.rel = 'stylesheet';
            preloadCSS.href = window.location.origin + "/css/mobile.css";
            preloadCSS.id = 'preload-mobile-css';
            document.head.appendChild(preloadCSS);

            (async () => {
                MobileFrame = await Bootstrapper.bootIframe(params, {name: 'Mobile'});

                if (MobileFrame) {

                    // Resetar tracker para nova inicialização
                    window.cssInjectionTracker.mobile = {
                        elementsFound: 0,
                        cssInjected: 0,
                        cssLoaded: 0,
                        allLoaded: false
                    };

                    // Aplicar CSS imediatamente após a criação do iframe
                    const elementos = [
                        document.querySelector('sport-mobile'),
                        document.querySelector('sport-modal'),
                        document.querySelector('sport-betslip')
                    ];

                    elementos.forEach(el => {
                        if (el && el.shadowRoot) {
                            aplicarCSSMobile(el);
                        }
                    });

                    // Verificar elementos com shadow root imediatamente
                    document.querySelectorAll('*').forEach(el => {
                        if (el.shadowRoot &&
                            el.tagName.toLowerCase().includes('sport') &&
                            !el.shadowRoot.querySelector('#tema-escuro-customizado')) {
                            aplicarCSSMobile(el);
                        }
                    });

                    MobileFrame.addEventListener('page-loaded', function handlePageLoad(event) {
                        if (container) {
                            container.style.visibility = 'visible';
                        }

                        // NÃO fechar loading aqui - aguardar CSS ser injetado
                        // O loading será fechado quando todos os CSSs forem carregados

                        if (event.data && event.data.page) {
                            const page = event.data.page;

                            if (isNavigatingBack) {
                                isNavigatingBack = false;
                                return;
                            }

                            const baseUrl = window.location.origin + window.location.pathname;
                            let newUrl = baseUrl;

                            if (page && page !== 'homepage') {
                                newUrl += '?page=' + encodeURIComponent(page);
                            }

                            // Prevenir duplicação no stack
                            const lastPage = navigationStack.length > 0 ? navigationStack[navigationStack.length - 1] : null;
                            if (lastPage !== page) {
                                navigationStack.push(page);
                            }

                            const historyState = {
                                digitainNavigation: true,
                                page: page,
                                timestamp: Date.now()
                            };

                            history.pushState(historyState, '', newUrl);
                        }

                        // Setup MutationObserver to watch for new sport elements
                        const observer = new MutationObserver((mutations) => {
                            mutations.forEach((mutation) => {
                                mutation.addedNodes.forEach((node) => {
                                    if (node.nodeType === 1) { // Element node
                                        if (node.tagName && node.tagName.toLowerCase().includes('sport')) {
                                            aplicarCSSMobile(node);
                                        }
                                        // Check child elements
                                        node.querySelectorAll('*').forEach(el => {
                                            if (el.tagName && el.tagName.toLowerCase().includes('sport')) {
                                                aplicarCSSMobile(el);
                                            }
                                        });
                                    }
                                });
                            });
                        });

                        // Start observing the document with the configured parameters
                        observer.observe(document.body, {
                            childList: true,
                            subtree: true
                        });

                        // Initial application of CSS
                        const elementos = [
                            document.querySelector('sport-mobile'),
                            document.querySelector('sport-modal'),
                            document.querySelector('sport-betslip')
                        ];

                        elementos.forEach(el => {
                            if (el && el.shadowRoot) {
                                aplicarCSSMobile(el);
                            }
                        });

                        // Retry mechanism for initial elements
                        let retryCount = 0;
                        const maxRetries = 8; // Aumentado para dar mais tempo
                        const retryInterval = setInterval(() => {
                            if (retryCount >= maxRetries) {
                                clearInterval(retryInterval);
                                
                                // Se ainda não carregou tudo, forçar verificação final
                                if (!window.cssInjectionTracker.mobile.allLoaded) {
                                    setTimeout(() => {
                                        verificarCSSMobileCompleto();
                                    }, 500);
                                }
                                return;
                            }

                            document.querySelectorAll('*').forEach(el => {
                                if (el.shadowRoot &&
                                    el.tagName.toLowerCase().includes('sport') &&
                                    !el.shadowRoot.querySelector('#tema-escuro-customizado')) {
                                    aplicarCSSMobile(el);
                                }
                            });

                            retryCount++;
                        }, 1000);

                        if (event.data.page === 'bethistory') {}
                    });
                }
            })();
        }

        if (typeof _mainContent !== 'undefined' && typeof _SpaceSport !== 'undefined') {
            _mainContent.style.marginTop = _SpaceSport - 1 + 'px';
        }
    }

    function aplicarCSSMobile(elemento) {
        try {
            const shadowRoot = elemento.shadowRoot;
            if (!shadowRoot) {
                setTimeout(() => {
                    if (elemento.shadowRoot) {
                        aplicarCSSMobile(elemento);
                    }
                }, 100);
                return;
            }

            // Se já tem CSS injetado, não fazer nada
            if (shadowRoot.querySelector('#tema-escuro-customizado')) {
                return;
            }

            // Incrementar contador de elementos encontrados
            window.cssInjectionTracker.mobile.elementsFound++;

            const fullURL = window.location.origin + "/css/mobile.css";
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = fullURL;
            link.id = 'tema-escuro-customizado';
            link.type = 'text/css';

            // Incrementar contador de CSS injetado
            window.cssInjectionTracker.mobile.cssInjected++;

            link.onerror = () => {
                // Mesmo em erro, considerar como carregado para não travar
                const style = document.createElement('style');
                style.id = 'tema-escuro-customizado';
                style.textContent = `/* Fallback styles */`;
                shadowRoot.appendChild(style);
                
                // Marcar como carregado mesmo em caso de erro
                window.cssInjectionTracker.mobile.cssLoaded++;
                verificarCSSMobileCompleto();
            };

            shadowRoot.appendChild(link);

            link.onload = function() {
                // Incrementar contador de CSS carregado
                window.cssInjectionTracker.mobile.cssLoaded++;
                
                // Verificar se todos os CSSs foram carregados
                verificarCSSMobileCompleto();
            };
        } catch (e) {
            console.error('Erro ao aplicar CSS mobile:', e);
        }
    }

    function verificarCSSMobileCompleto() {
        const tracker = window.cssInjectionTracker.mobile;
        
        // Se já marcamos como completo, não fazer nada
        if (tracker.allLoaded) {
            return;
        }

        // Verificar se temos elementos e se todos os CSSs foram carregados
        if (tracker.elementsFound > 0 && 
            tracker.cssInjected > 0 && 
            tracker.cssLoaded >= tracker.cssInjected) {
            
            // Aguardar um pouco mais para garantir que o CSS foi aplicado
            setTimeout(() => {
                tracker.allLoaded = true;
                
                // Disparar evento apenas quando tudo estiver pronto
                document.dispatchEvent(new CustomEvent('sportsCssLoaded', {
                    detail: {
                        type: 'mobile',
                        elementsFound: tracker.elementsFound,
                        cssInjected: tracker.cssInjected,
                        cssLoaded: tracker.cssLoaded,
                        cssApplied: true
                    }
                }));
            }, 500); // Tempo adicional para garantir que o CSS foi aplicado
        }
    }

    function configureIframe() {
        let iframe = document.querySelector("#sport_div_iframe iframe");
        if (!iframe) {
            // Retry se o iframe ainda não existe
            setTimeout(configureIframe, 200);
            return;
        }

        try {
            let url = new URL(iframe.src);
            const baseUrl = window.location.origin;
            url.searchParams.set("customCssUrl", baseUrl + "/css/sports.css");
            url.searchParams.set("clearSiteStyles", "false");
            url.searchParams.set("resetAllStyles", "false");

            if (iframe.src !== url.href) {
                iframe.src = url.href;
            }

            // Marcar que o CSS foi configurado
            window.cssInjectionTracker.desktop.cssConfigured = true;

            // Listener para quando o iframe carregar completamente (após CSS ser aplicado)
            iframe.addEventListener('load', function onIframeLoad() {
                // Remover o listener para evitar múltiplas chamadas
                iframe.removeEventListener('load', onIframeLoad);
                
                // Marcar que o iframe carregou
                window.cssInjectionTracker.desktop.iframeLoaded = true;
                
                // Aguardar um pouco mais para garantir que o CSS foi aplicado pelo iframe
                setTimeout(() => {
                    window.cssInjectionTracker.desktop.cssApplied = true;
                    
                    // Disparar evento apenas quando tudo estiver pronto
                    document.dispatchEvent(new CustomEvent('sportsCssLoaded', {
                        detail: {
                            type: 'desktop',
                            cssApplied: true
                        }
                    }));
                }, 800); // Tempo adicional para garantir que o CSS foi aplicado
            });

            // Se o iframe já estiver carregado, verificar imediatamente
            if (iframe.complete) {
                window.cssInjectionTracker.desktop.iframeLoaded = true;
                setTimeout(() => {
                    window.cssInjectionTracker.desktop.cssApplied = true;
                    document.dispatchEvent(new CustomEvent('sportsCssLoaded', {
                        detail: {
                            type: 'desktop',
                            cssApplied: true
                        }
                    }));
                }, 800);
            }
        } catch (error) {
            console.error('Erro ao configurar iframe:', error);
        }
    }
}

// Função helper para detectar mobile
function isMobile() {
    return (window.innerWidth <= 767.98) ||
        (navigator.userAgent.match(/Android/i) ||
            navigator.userAgent.match(/iPhone/i) ||
            navigator.userAgent.match(/iPad/i) ||
            navigator.userAgent.match(/iPod/i) ||
            navigator.userAgent.match(/BlackBerry/i) ||
            navigator.userAgent.match(/Windows Phone/i));
}

let Add = "";

if (window.location.href.includes('/esportes')) {
    if (window.location.href.includes('?l=BetsHistory')){
        Add = "BetsHistory";
    }else{
        if (window.location.href.includes('?l=')){
            const urlParams = new URLSearchParams(window.location.search);
            const lValue = urlParams.get('l');

            Add = lValue;
        }else{
            Add = "Home";
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (isMobile()) {
            const initialPage = Add || 'Home';
            const initialState = {
                digitainNavigation: true,
                page: initialPage,
                initial: true,
                timestamp: Date.now()
            };

            navigationStack = [initialPage.toLowerCase()];

            if (!history.state || !history.state.digitainNavigation) {
                history.replaceState(initialState, '', window.location.href);
            }
        }

        IniciaSport(Add);

        const checkMobileFrameInterval = setInterval(function() {
            const pendingLink = sessionStorage.getItem('pendingMobileLink');
            if (pendingLink && MobileFrame) {
                MobileFrame.navigateTo(pendingLink);

                sessionStorage.removeItem('pendingMobileLink');
                clearInterval(checkMobileFrameInterval);
            }
        }, 500);

        setTimeout(function() {
            clearInterval(checkMobileFrameInterval);
        }, 10000);
    });
}

function gToken() {
    return new Promise((resolve, reject) => {
        const data = {
            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        $.ajax({
            url: '/tk-gn-q4m8',
            type: 'POST',
            data: data,
            success: function(response) {
                resolve(response);
            },
            error: function(xhr) {
                reject(new Error("Erro ao obter token"));
            }
        });
    });
}

async function mToken() {
    try {
        return await gToken();
    } catch (error) {
        return "-";
    }
}

function LinkMobile(link) {
    if (MobileFrame) {
        const sidebar = document.getElementById('divSidebarMenu');
        const isOpen = sidebar ? sidebar.getAttribute('data-isopen') === 'true' : false;

        if (isOpen && sidebar) {
            sidebar.classList.remove('open');
            sidebar.setAttribute('data-isopen', 'false');
        }

        // Adicionar ao stack de navegação
        const pageName = link.split('/').pop() || link;
        if (navigationStack.length === 0 || navigationStack[navigationStack.length - 1] !== pageName) {
            navigationStack.push(pageName);
        }

        // Criar URL adequada baseada no link
        const baseUrl = window.location.origin + window.location.pathname;
        let newUrl = baseUrl + '?nav=' + encodeURIComponent(link);

        // Adicionar estado no histórico antes de navegar
        const navigationState = {
            digitainNavigation: true,
            page: pageName,
            timestamp: Date.now()
        };

        history.pushState(navigationState, '', newUrl);
        MobileFrame.navigateTo(link);
    } else {
        sessionStorage.setItem('pendingMobileLink', link);

        const parts = link.split('/');

        if (parts.length >= 3 && parts[2]) {
            const secondValue = parts[2];
            window.location.href = '/esportes?l=' + secondValue;
        } else {
            window.location.href = '/esportes';
        }
    }
}

window.addEventListener('popstate', function(event) {
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 767.98;

    if (isMobile && MobileFrame && event.state) {
        if (event.state.digitainNavigation) {
            if (navigationStack.length > 0) {
                navigationStack.pop();
            }

            const previousPage = navigationStack.length > 0 ? navigationStack[navigationStack.length - 1] : 'homepage';

            const pageToRoute = {
                'homepage': '/',
                'home': '/',
                'live': '/live',
                'prematch': '/prematch',
                'bethistory': '/betHistory',
                'groupstage': '/groupStage',
                'betrace': '/betRace'
            };

            const route = pageToRoute[previousPage.toLowerCase()] || '/';

            isNavigatingBack = true;

            try {
                MobileFrame.navigateTo(route);
            } catch (e) {
                isNavigatingBack = false;
            }

            event.preventDefault();
            return false;
        }
    }

    const url = window.location.href.toLowerCase();
    if (!url.includes('sports') && !url.includes('esportes')) {
        window.location.reload();
    }
});
