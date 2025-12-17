<div class="XNW0o">
                        <div class="Yir83">
                            <div class="NKUH3">
                                <label class="{{ request('period', 'all') == 'today' ? 'yvWYA' : '' }}" for="period_today">
                                    <input id="period_today" type="radio" value="today" name="period" {{ request('period') == 'today' ? 'checked' : '' }} class="ajax-filter" /> {{ __('filters.today_only') }}
                                </label>
                                <label class="{{ request('period') == '1' ? 'yvWYA' : '' }}" for="period_1">
                                    <input id="period_1" type="radio" value="1" name="period" {{ request('period') == '1' ? 'checked' : '' }} class="ajax-filter" /> {{ __('filters.yesterday_only') }}
                                </label>
                                <label class="{{ request('period') == '7' ? 'yvWYA' : '' }}" for="period_7">
                                    <input id="period_7" type="radio" value="7" name="period" {{ request('period') == '7' ? 'checked' : '' }} class="ajax-filter" /> {{ __('filters.last_7_days') }}
                                </label>
                                <label class="{{ request('period') == '30' ? 'yvWYA' : '' }}" for="period_30">
                                    <input id="period_30" type="radio" value="30" name="period" {{ request('period') == '30' ? 'checked' : '' }} class="ajax-filter" /> {{ __('filters.last_30_days') }}
                                </label>
                                <label class="{{ request('period') == '90' ? 'yvWYA' : '' }}" for="period_90">
                                    <input id="period_90" type="radio" value="90" name="period" {{ request('period') == '90' ? 'checked' : '' }} class="ajax-filter" /> {{ __('filters.last_90_days') }}
                                </label>
                                <label class="{{ request('period', 'all') == 'all' ? 'yvWYA' : '' }}" for="period_all">
                                    <input id="period_all" type="radio" value="all" name="period" {{ request('period', 'all') == 'all' ? 'checked' : '' }} class="ajax-filter" /> {{ __('filters.total_period') }}
                                </label>
                            </div>
                        </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa apenas se ainda não foi inicializado
    if (!window.filterDateInitialized) {
        window.filterDateInitialized = true;
        
        // Função para carregar os dados filtrados via AJAX
        window.loadFilteredData = function(periodValue) {
            
            // Sinalizar que estamos fazendo uma atualização AJAX
            window.isAjaxUpdate = true;
            
            // Mostrar um indicador de carregamento
            document.body.classList.add('page-loading');
            
            // Construir a URL para a solicitação AJAX
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('period', periodValue);
            
            // Resetar a página para 1 ao mudar o filtro
            urlParams.set('page', '1');
            
            // Adicionar parâmetro para indicar requisição AJAX
            urlParams.set('ajax', '1');
            
            // Atualizar a URL no navegador sem recarregar a página
            const newUrl = window.location.pathname + '?' + urlParams.toString();
            const baseUrl = newUrl.replace('&ajax=1', '').replace('?ajax=1&', '?').replace('?ajax=1', '');
            history.pushState({period: periodValue, ajax: true}, '', baseUrl);
            
            // Fazer a solicitação AJAX
            fetch(newUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html, application/xhtml+xml'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta da rede');
                }
                return response.text();
            })
            .then(html => {
                
                // Criar um elemento temporário para processar o HTML
                const tempElement = document.createElement('div');
                tempElement.innerHTML = html;
                
                // Estratégias de atualização, similares ao componente de paginação
                const updateSuccessful = updateContent(tempElement);
                
                // Sempre tentar atualizar a paginação, mesmo que updateContent tenha falhado
                // Isso garante que a paginação seja atualizada em todos os casos
                updatePagination(tempElement);
                
                // Se não conseguiu atualizar via AJAX, recarregar a página
                if (!updateSuccessful) {
                    console.warn("Não foi possível atualizar via AJAX, recarregando a página");
                    window.location.href = baseUrl;
                    return;
                }
                
                // Inicializar quaisquer scripts no novo conteúdo
                reinitializeScripts();
                
                // Remover o indicador de carregamento
                document.body.classList.remove('page-loading');
                
                // Remover flag de atualização AJAX após um pequeno delay
                setTimeout(() => {
                    window.isAjaxUpdate = false;
                }, 1000);
                
            })
            .catch(error => {
                console.error('Erro ao aplicar filtro:', error);
                
                // Remover o indicador de carregamento em caso de erro
                document.body.classList.remove('page-loading');
                
                // Remover flag de atualização AJAX
                window.isAjaxUpdate = false;
                
                // Em caso de erro, recarregar a página normalmente
                window.location.href = baseUrl;
            });
        };
        
        // Função para atualizar o conteúdo
        function updateContent(tempElement) {
            // Primeiro, verificamos se há uma mensagem específica de "sem dados" no HTML recebido
            const hasNoDataMessage = tempElement.querySelector('.alert-warning') !== null;
            
            // Identificar os possíveis contêineres de resultados
            const contentSelectors = [
                '#content-container',
                '.card-body', 
                '.data-table-container', 
                '.table-container',
                '.results-container',
                '.content-area',
                'table'
            ];
            
            // ESTRATÉGIA 1: Atualizar tabelas
            const existingTables = document.querySelectorAll('table');
            const newTables = tempElement.querySelectorAll('table');
            
            if (existingTables.length > 0 && newTables.length > 0) {
                
                let dataUpdated = false;
                
                existingTables.forEach((existingTable, index) => {
                    if (index < newTables.length) {
                        const newTable = newTables[index];
                        
                        // Verificar se a nova tabela tem dados ou mensagem de "sem dados"
                        const newTbody = newTable.querySelector('tbody');
                        const existingTbody = existingTable.querySelector('tbody');
                        
                        if (existingTbody && newTbody) {
                            // Verificar se há linhas de dados ou se é uma mensagem "sem dados"
                            const hasRows = newTbody.querySelectorAll('tr:not(.no-data-row)').length > 0;
                            
                            if (!hasRows && hasNoDataMessage) {
                                // Se não há linhas e temos uma mensagem "sem dados", mostrar a mensagem
                                existingTbody.innerHTML = `
                                    <tr class="no-data-row"><td colspan="100%" class="text-center">
                                        <div id="headlessui-tabs-panel-nsiNM9WAguS_2" role="tabpanel" tabindex="0" data-headlessui-state="selected" class="rounded-xl ring-white outline-none">
                                            <div class="alert alert-warning mt-4">Não ha informações para exibir.</div>
                                        </div>
                                    </td></tr>
                                `;
                            } else {
                                // Caso contrário, atualizar com os novos dados
                                existingTbody.innerHTML = newTbody.innerHTML;
                            }
                            
                            dataUpdated = true;
                        }
                    }
                });
                
                // Atualizar a paginação se existir
                updatePagination(tempElement);
                
                if (dataUpdated) {
                    return true;
                }
            }
            
            // ESTRATÉGIA 2: Atualizar contêineres específicos
            for (const selector of contentSelectors) {
                const existingContainer = document.querySelector(selector);
                const newContainer = tempElement.querySelector(selector);
                
                if (existingContainer && newContainer) {
                    
                    // Preservar classes
                    const originalClasses = Array.from(existingContainer.classList);
                    
                    // Verificar se há dados ou se deve mostrar mensagem "sem dados"
                    if (hasNoDataMessage && selector !== 'table') {
                        // Se há mensagem de "sem dados", exibi-la
                        existingContainer.innerHTML = `
                            <div id="headlessui-tabs-panel-nsiNM9WAguS_2" role="tabpanel" tabindex="0" data-headlessui-state="selected" class="rounded-xl ring-white outline-none">
                                <div class="alert alert-warning mt-4">Não ha informações para exibir.</div>
                            </div>
                        `;
                    } else {
                        // Caso contrário, atualizar com os novos dados
                        existingContainer.innerHTML = newContainer.innerHTML;
                    }
                    
                    // Restaurar classes
                    originalClasses.forEach(cls => {
                        if (!existingContainer.classList.contains(cls)) {
                            existingContainer.classList.add(cls);
                        }
                    });
                    
                    // Atualizar a paginação
                    updatePagination(tempElement);
                    
                    return true;
                }
            }
            
            // Se não encontrou nenhum contêiner conhecido, verificar elementos mais genéricos
            const mainContent = document.querySelector('main');
            const newMainContent = tempElement.querySelector('main');
            
            if (mainContent && newMainContent) {
                
                // Preservar referências e classes do elemento original
                const originalClasses = Array.from(mainContent.classList);
                const originalId = mainContent.id;
                
                // Atualizar o conteúdo
                mainContent.innerHTML = newMainContent.innerHTML;
                
                // Restaurar classes e ID
                if (originalId) mainContent.id = originalId;
                originalClasses.forEach(cls => {
                    if (!mainContent.classList.contains(cls)) {
                        mainContent.classList.add(cls);
                    }
                });
                
                // Atualizar a paginação
                updatePagination(tempElement);
                
                return true;
            }
            
            // Se nenhuma estratégia funcionou, ainda tentar atualizar a paginação
            // pois ela pode estar em um lugar diferente
            updatePagination(tempElement);
            
            return false;
        }
        
        // Função para atualizar a paginação
        function updatePagination(tempElement) {
            // Procurar paginação em vários lugares possíveis no HTML recebido
            let newPagination = tempElement.querySelector('.paginationWrapper');
            if (!newPagination) {
                newPagination = tempElement.querySelector('[class*="pagination"]');
            }
            if (!newPagination) {
                newPagination = tempElement.querySelector('.pagination');
            }
            if (!newPagination) {
                // Procurar por qualquer elemento que contenha "pagination" no HTML
                const allElements = tempElement.querySelectorAll('*');
                for (let el of allElements) {
                    if (el.className && (el.className.includes('pagination') || el.className.includes('Pagination'))) {
                        newPagination = el;
                        break;
                    }
                }
            }
            
            // Procurar a paginação atual também em vários lugares
            let currentPagination = document.querySelector('.paginationWrapper');
            if (!currentPagination) {
                currentPagination = document.querySelector('[class*="pagination"]');
            }
            if (!currentPagination) {
                currentPagination = document.querySelector('.pagination');
            }
            
            // Se encontrou nova paginação e existe uma atual, substituir
            if (newPagination && currentPagination) {
                currentPagination.outerHTML = newPagination.outerHTML;
                
                // Reinicializar eventos da paginação se a função existir
                if (typeof initPaginationEvents === 'function') {
                    initPaginationEvents();
                } else if (window.paginationInitialized) {
                    // Forçar reinicialização da paginação
                    Object.keys(window.paginationInitialized).forEach(key => {
                        window.paginationInitialized[key] = false;
                    });
                    
                    // Disparar evento para reinicializar paginação
                    document.dispatchEvent(new Event('DOMContentLoaded'));
                }
                
                return true;
            } 
            // Se encontrou nova paginação mas não existe uma atual, adicionar
            else if (newPagination && !currentPagination) {
                // Procurar onde inserir a paginação (geralmente após a tabela ou container)
                const table = document.querySelector('table.UHNq-');
                const tableContainer = table ? table.closest('.rqI4A') : null;
                const tabPanel = document.querySelector('[role="tabpanel"]');
                
                if (tableContainer) {
                    // Inserir após o container da tabela
                    tableContainer.insertAdjacentHTML('afterend', newPagination.outerHTML);
                } else if (tabPanel) {
                    // Inserir no final do painel de abas
                    tabPanel.insertAdjacentHTML('beforeend', newPagination.outerHTML);
                } else {
                    // Último recurso: inserir após a tabela
                    if (table) {
                        table.insertAdjacentHTML('afterend', newPagination.outerHTML);
                    }
                }
                
                // Reinicializar eventos
                if (typeof initPaginationEvents === 'function') {
                    initPaginationEvents();
                }
                
                return true;
            }
            // Se não encontrou nova paginação mas existe uma atual
            // NÃO remover - pode ser que não precise de paginação por ter poucos resultados
            // ou a paginação pode estar em outro lugar no HTML
            else if (currentPagination && !newPagination) {
                // Verificar se realmente não há paginação no HTML (pode estar em outro formato)
                const hasPaginationInResponse = tempElement.innerHTML.includes('pagination') || 
                                               tempElement.innerHTML.includes('page-link') ||
                                               tempElement.innerHTML.includes('paginationBtn');
                
                // Só remover se realmente não houver nenhuma referência a paginação
                if (!hasPaginationInResponse) {
                    // Verificar se há poucos resultados (menos de uma página)
                    const rows = tempElement.querySelectorAll('table tbody tr:not(.no-data-row)');
                    if (rows.length === 0 || rows.length < 10) {
                        // Se há poucos ou nenhum resultado, pode não precisar de paginação
                        // Mas vamos manter a paginação existente caso apareçam mais resultados depois
                        return true;
                    }
                }
                
                return true;
            }
            
            return false;
        }
        
        // Função para reinicializar scripts
        function reinitializeScripts() {
            const scripts = document.querySelectorAll('script:not([src])');
            scripts.forEach(script => {
                if (script.textContent && !script.hasAttribute('data-processed')) {
                    try {
                        eval(script.textContent);
                        script.setAttribute('data-processed', 'true');
                    } catch (e) {
                        console.error('Erro ao executar script:', e);
                    }
                }
            });
            
            // Reaplicar eventos aos filtros
            initFilterEvents();
        }
        
        // Função para inicializar eventos dos filtros
        function initFilterEvents() {
            const filterInputs = document.querySelectorAll('.ajax-filter');
            
            filterInputs.forEach(input => {
                // Remover eventos antigos para evitar duplicação
                input.removeEventListener('change', filterChangeHandler);
                input.addEventListener('change', filterChangeHandler);
            });
        }
        
        // Manipulador de evento para alteração de filtro
        function filterChangeHandler() {
            if (this.checked) {
                loadFilteredData(this.value);
                
                // Atualizar classes de destaque nos labels
                document.querySelectorAll('.NKUH3 label').forEach(label => {
                    label.classList.remove('yvWYA');
                });
                this.parentElement.classList.add('yvWYA');
            }
        }
        
        // Inicializar eventos dos filtros
        initFilterEvents();
        
        // Lidar com o botão voltar/avançar do navegador
        window.addEventListener('popstate', function(event) {
            if (event.state && event.state.ajax) {
                const urlParams = new URLSearchParams(window.location.search);
                
                // Se o estado contém period, é uma navegação de filtro
                if (event.state.period) {
                    const period = urlParams.get('period') || 'all';
                    
                    // Selecionar o filtro correto
                    const filterInput = document.querySelector(`input[name="period"][value="${period}"]`);
                    if (filterInput && !filterInput.checked) {
                        filterInput.checked = true;
                        loadFilteredData(period);
                    }
                } else {
                    // Caso contrário, recarregar a página
                    window.location.reload();
                }
            } else {
                window.location.reload();
            }
        });
    }
});
</script>