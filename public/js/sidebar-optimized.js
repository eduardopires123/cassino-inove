// Sidebar Otimizado - Performance Melhorada
(function() {
    'use strict';
    
    // Cache de elementos DOM
    const ElementCache = {
        elements: new Map(),
        
        get(selector) {
            if (!this.elements.has(selector)) {
                this.elements.set(selector, document.querySelector(selector));
            }
            return this.elements.get(selector);
        },
        
        getAll(selector) {
            const key = `all_${selector}`;
            if (!this.elements.has(key)) {
                this.elements.set(key, document.querySelectorAll(selector));
            }
            return this.elements.get(key);
        },
        
        clear() {
            this.elements.clear();
        }
    };
    
    // Utilitários de performance
    const Utils = {
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },
        
        throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        }
    };
    
    // Gerenciador principal da sidebar
    const SidebarManager = {
        initialized: false,
        elements: {},
        functions: {},
        
        init() {
            if (this.initialized) return;
            this.initialized = true;
            
            this.cacheElements();
            this.setupFunctions();
            this.setupEventListeners();
            this.executeInitialSetup();
        },
        
        cacheElements() {
            this.elements = {
                languageSwitcher: ElementCache.get('#languageSwitcher'),
                languageOptions: ElementCache.get('#languageOptions'),
                sidebarMenu: ElementCache.get('#divSidebarMenu'),
                languageOptionsElements: ElementCache.getAll('.language-option'),
                currentLanguageFlag: ElementCache.get('#currentLanguageFlag'),
                currentLanguageName: ElementCache.get('#currentLanguageName'),
                csrfToken: ElementCache.get('meta[name="csrf-token"]')
            };
        },
        
        setupFunctions() {
            this.functions = {
                getCSRFToken: () => this.elements.csrfToken?.getAttribute('content') || '',
                
                showToast: (message, type = 'success') => {
                    const toast = document.createElement('div');
                    toast.className = `inove-toast inove-toast--${type}`;
                    toast.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        z-index: 9999;
                        padding: 12px 20px;
                        border-radius: 6px;
                        color: white;
                        font-weight: 500;
                        font-size: 14px;
                        background-color: ${type === 'success' ? '#10b981' : '#ef4444'};
                        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                        transform: translateX(100%);
                        transition: transform 0.3s ease;
                    `;
                    toast.textContent = message;
                    
                    document.body.appendChild(toast);
                    
                    // Animar entrada
                    requestAnimationFrame(() => {
                        toast.style.transform = 'translateX(0)';
                    });
                    
                    // Remover após 3 segundos
                    setTimeout(() => {
                        toast.style.transform = 'translateX(100%)';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                },
                
                changeLanguage: (lang, flag, name) => {
                    if (!this.elements.currentLanguageFlag || !this.elements.currentLanguageName) {
                        console.error('Elementos de idioma não encontrados');
                        return;
                    }
                    
                    const originalSrc = this.elements.currentLanguageFlag.src;
                    const originalText = this.elements.currentLanguageName.textContent;
                    
                    // Mostrar loading
                    this.elements.currentLanguageName.textContent = 'Carregando...';
                    
                    // Fazer requisição para a rota correta
                    fetch('/language/switch', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.functions.getCSRFToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ language: lang })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Atualizar interface
                            this.elements.currentLanguageFlag.src = `/img/${flag}`;
                            this.elements.currentLanguageName.textContent = name;
                            this.elements.languageOptions?.classList.add('hidden');
                            
                            // Usar o toast padrão do sistema
                            if (typeof window.mostrarMensagemSucesso === 'function') {
                                window.mostrarMensagemSucesso(data.message || 'Idioma alterado com sucesso!');
                            }
                            
                            // Recarregar página após pequeno delay
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            throw new Error(data.message || 'Erro ao alterar idioma');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao alterar idioma:', error);
                        
                        // Restaurar valores originais
                        this.elements.currentLanguageFlag.src = originalSrc;
                        this.elements.currentLanguageName.textContent = originalText;
                        
                        // Usar o toast padrão do sistema para erro
                        if (typeof window.mostrarMensagemErro === 'function') {
                            window.mostrarMensagemErro(error.message || 'Erro ao alterar idioma');
                        }
                    });
                }
            };
        },
        
        setupEventListeners() {
            // Event delegation para melhor performance
            document.addEventListener('click', this.handleDocumentClick.bind(this), { passive: false });
        },
        
        handleDocumentClick(e) {
            // Language switcher
            if (e.target.closest('#languageSwitcher')) {
                e.stopPropagation();
                this.elements.languageOptions?.classList.toggle('hidden');
                return;
            }
            
            // Language option selection
            if (e.target.closest('.language-option')) {
                e.preventDefault();
                e.stopPropagation();
                
                const option = e.target.closest('.language-option');
                const lang = option.dataset.lang;
                const flag = option.dataset.flag;
                const name = option.dataset.name;
                
                // Verificar se todos os dados estão presentes
                if (!lang || !flag || !name) {
                    console.error('Dados do idioma incompletos:', { lang, flag, name });
                    return;
                }
                
                // Verificar se não é o idioma atual
                const currentLang = document.documentElement.lang || 'pt_BR';
                if (lang !== currentLang) {
                    this.functions.changeLanguage(lang, flag, name);
                } else {
                    this.elements.languageOptions?.classList.add('hidden');
                }
                return;
            }
            
            // Close language options when clicking outside
            if (!e.target.closest('#languageOptions')) {
                this.elements.languageOptions?.classList.add('hidden');
            }
        },
        
        executeInitialSetup() {
            // Configuração inicial
            this.elements.languageOptions?.classList.add('hidden');
        }
    };
    
    // Auto-inicialização
    function initSidebar() {
        SidebarManager.init();
    }
    
    // Inicializar quando DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebar);
    } else {
        initSidebar();
    }
    
    // Disponibilizar para uso externo
    window.SidebarManager = SidebarManager;
    
})(); 