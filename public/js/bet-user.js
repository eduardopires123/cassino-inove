$(document).ready(function() {
    // Comentário adicionado: Este script implementa o agrupamento de apostas com base no transactionId
    // A validação é feita pelo transactionId e não pelo id, pois o transactionId é o que pode ter valores repetidos
    
    // Inicialização inicial - apenas uma vez
    initializeHistoryPage();
    
    // Ouvinte para cliques na paginação
    $(document).on('click', '#history-pagination a.page-link', function(e) {
        
        // Observer específico para mudanças na tabela após paginação
        const targetNode = document.querySelector('table.UHNq-');
        if (!targetNode) return;
        
        const paginationObserver = new MutationObserver(function(mutationsList, observer) {
            // Desconectar o observer depois que ele for acionado
            observer.disconnect();
            
            // Executar o agrupamento
            setTimeout(function() {
                initializeHistoryPage();
                
                // Verificar se o agrupamento funcionou
                setTimeout(function() {
                    const resultado = diagnosticarAgrupamento();
                    if (resultado.linhasVisiveis > resultado.idsUnicos) {
                        forcarAgrupamento();
                    }
                }, 300);
            }, 100);
        });
        
        // Iniciar a observação
        paginationObserver.observe(targetNode, { childList: true, subtree: true });
        
        // Como fallback, ainda tentamos um agrupamento após um tempo
        setTimeout(function() {
            // Desconectar o observer se ainda estiver ativo
            paginationObserver.disconnect();
            
            // Reagrupar e verificar
            initializeHistoryPage();
            
            // Verificar e corrigir problemas persistentes
            setTimeout(function() {
                const resultado = diagnosticarAgrupamento();
                if (resultado.linhasVisiveis > resultado.idsUnicos || resultado.idsRepetidos.length > 0) {
                    console.warn('Persistência de problemas após paginação, forçando agrupamento...');
                    forcarAgrupamento();
                    
                    // Verificar novamente
                    setTimeout(diagnosticarAgrupamento, 200);
                }
            }, 500);
        }, 1000);
    });
    
    // Função para inicializar a página de histórico
    function initializeHistoryPage() {
        
        // Verificar se todas as linhas têm o atributo data-transaction-id
        $('table.UHNq- tr.WXGKq').each(function() {
            const row = $(this);
            if (!row.attr('data-transaction-id')) {
                // Se não tiver o atributo data-transaction-id, tentamos obtê-lo do HTML
                // Primeiro verificamos se há um campo transactionId visível
                let transactionId = '';
                
                // Tentar encontrar o campo 'transactionId' ou qualquer campo que contenha esse valor
                const cells = row.find('td');
                cells.each(function() {
                    const cell = $(this);
                    const cellName = cell.attr('data-name');
                    
                    // Se for a célula que contém o transactionId
                    if (cellName === 'transaction_id' || cellName === 'transactionId') {
                        transactionId = cell.text().trim();
                    }
                });
                
                // Se não encontramos a célula específica, verificar o botão de visualização
                if (!transactionId) {
                    const btn = row.find('.ver-aposta');
                    if (btn.length && btn.attr('data-transaction-id')) {
                        transactionId = btn.attr('data-transaction-id');
                    }
                }
                
                // Se ainda não temos o transactionId, usar o id como fallback (mas isso deve ser evitado)
                if (!transactionId) {
                    transactionId = row.find('[data-name="id"]').text().trim();
                    console.warn(`Usando id como fallback para transactionId: ${transactionId}`);
                }
                
                // Definir o atributo na linha
                if (transactionId) {
                    row.attr('data-transaction-id', transactionId);
                }
            }
        });
        
    // Agrupar apostas com mesmo ID de transação
    mergeTransactionsWithSameId();
    
    // Initialize bet view buttons with custom handler for user page
    initUserBetViewButtons();
    }
    
    // Remover as chamadas duplicadas
    // mergeTransactionsWithSameId();
    // initUserBetViewButtons();
    
    // Função para mesclar transações com mesmo ID
    function mergeTransactionsWithSameId() {
        // Objeto para armazenar as apostas agrupadas por ID
        const transactionGroups = {};
        const processedIds = {};
        
        // Antes de tudo, vamos garantir que todas as linhas estejam visíveis para o agrupamento inicial
        $('table.UHNq- tr.WXGKq').show();
        
        // Primeira passagem: agrupar as linhas por ID de transação
        $('table.UHNq- tr.WXGKq').each(function() {
            const row = $(this);
            // Usar o atributo data-transaction-id que foi definido com o valor correto 
            const transactionId = row.attr('data-transaction-id');
            
            if (!transactionGroups[transactionId]) {
                transactionGroups[transactionId] = [];
            }
            
            transactionGroups[transactionId].push(row);
        });
        
        // Contagem de agrupamentos para debug
        let countAgrupadosMultiplos = 0;
        let countAgrupadosUnicos = 0;
        
        // Segunda passagem: processar cada grupo de linhas
        Object.keys(transactionGroups).forEach(transactionId => {
            const rows = transactionGroups[transactionId];
            
            // Marcar este ID como processado
            processedIds[transactionId] = true;
            
            // Se temos mais de uma linha com o mesmo ID (aposta + resultado)
            if (rows.length > 1) {
                countAgrupadosMultiplos++;
                
                // Identificar linha de aposta (debit) e resultado (credit, lose)
                let apostaRow = null; 
                let resultadoRow = null;
                let cashoutRow = null;
                
                // Para cada linha no grupo, determinar seu tipo
                rows.forEach(row => {
                    // Usar os atributos data da linha diretamente
                    const operation = row.attr('data-operation');
                    const isCashout = row.attr('data-cashout') === '1';
                    
                    if (operation === 'debit') {
                        apostaRow = row;
                    } else if (operation === 'credit') {
                        // Verificar se é cashout com base no atributo data-cashout
                        if (isCashout) {
                            cashoutRow = row;
                        } else {
                            resultadoRow = row;
                        }
                    } else if (operation !== 'credit' && operation !== 'debit') {
                        resultadoRow = row;
                    } else {
                        // Fallback para verificação via texto do status
                    const statusText = row.find('[data-name="status"] small').text().trim().toLowerCase();
                    
                    if (statusText.includes('aposta') || statusText.includes('pendente')) {
                        apostaRow = row;
                    } else if (statusText.includes('cashout')) {
                        cashoutRow = row;
                    } else if (statusText.includes('ganhou') || statusText.includes('perdeu') || 
                              statusText.includes('won') || statusText.includes('lost')) {
                        resultadoRow = row;
                        }
                    }
                });
                
                // Determinar qual linha deve ser a principal (visível)
                // Prioridade: resultado > cashout > aposta
                const mainRow = resultadoRow || cashoutRow || apostaRow || rows[0];
                
                // Esconder todas as linhas exceto a principal - usar métodos mais agressivos
                rows.forEach(row => {
                    if (row !== mainRow) {
                        row.hide();  // jQuery hide
                        row.css('display', 'none'); // CSS style diretamente
                        row.attr('style', 'display: none !important');
                        
                        // Adicionar classe para verificação posterior
                        row.addClass('hidden-by-merge');
                    } else {
                        // Garantir que a linha principal esteja visível
                        row.show();
                        row.css('display', 'table-row');
                        row.removeClass('hidden-by-merge');
                        
                        // Adicionar classe para saber que é uma linha agrupada
                        row.addClass('merged-main-row');
                    }
                });
                
                // Vamos combinar os status na linha principal
                const statusContainer = $('<div class="merged-status-container" style="display: flex; flex-direction: column; gap: 4px;"></div>');
                const uniqueStatuses = {};
                
                // Verificar primeiro se temos um resultado (ganhou, perdeu, cashout) 
                // e mostrar apenas ele, caso contrário mostrar o status da aposta
                let temResultado = false;
                
                // Verificar se temos cashout na betslip
                let hasCashoutFromBetslip = false;
                try {
                    // Tentar verificar se há cashout no betslip
                    if (resultadoRow || cashoutRow) {
                        const rowToCheck = resultadoRow || cashoutRow;
                        const viewButton = rowToCheck.find('.ver-aposta');
                        if (viewButton.length) {
                            let betslipData = viewButton.attr('data-betslip') || '{}';
                            
                            // Decodificar betslip se estiver em entidades HTML
                            if (betslipData && betslipData.includes('&quot;')) {
                                const textarea = document.createElement('textarea');
                                textarea.innerHTML = betslipData;
                                betslipData = textarea.value;
                            }
                            
                            // Verificar se contém {"is_cashout":true} ou variações
                            if (betslipData && betslipData.includes('is_cashout')) {
                                try {
                                    const betslipObj = JSON.parse(betslipData);
                                    if (betslipObj && betslipObj.is_cashout !== undefined) {
                                        hasCashoutFromBetslip = betslipObj.is_cashout === true || 
                                                               betslipObj.is_cashout === 1 || 
                                                               betslipObj.is_cashout === "1";
                                        
                                        // Atualizar o botão para refletir cashout
                                        if (hasCashoutFromBetslip && viewButton) {
                                            viewButton.attr('data-cashout', '1');
                                        }
                                    }
                                } catch (e) {
                                    console.error('Erro ao verificar cashout no betslip:', e);
                                }
                            }
                        }
                    }
                } catch (e) {
                    console.error('Erro ao verificar cashout:', e);
                }
                
                // Agora verificamos apenas os status de ganhou/perdeu/aposta, não mostramos cashout como status
                if (resultadoRow) {
                    const statusElement = resultadoRow.find('[data-name="status"] .H32ns');
                        const statusText = statusElement.find('small').text().trim();
                        
                            uniqueStatuses[statusText] = true;
                            statusContainer.append(statusElement.clone());
                    temResultado = true;
                }
                
                // Se não temos resultado, mostrar o status da aposta
                if (!temResultado && apostaRow) {
                    const statusElement = apostaRow.find('[data-name="status"] .H32ns');
                    const statusText = statusElement.find('small').text().trim();
                    
                    uniqueStatuses[statusText] = true;
                    statusContainer.append(statusElement.clone());
                }
                
                mainRow.find('[data-name="status"]').html(statusContainer);
                
                // Atualizar o botão "Ver Aposta" na linha principal
                const viewButton = mainRow.find('.ver-aposta');
                if (viewButton.length) {
                    // Começar com a linha principal
                    let betslipData = viewButton.attr('data-betslip') || '{}';
                    let operation = viewButton.attr('data-operation') || '';
                    let amount = parseFloat(viewButton.attr('data-amount') || '0');
                    let hasCashout = viewButton.attr('data-cashout') === '1';
                    
                    // Se temos uma linha de aposta, usar seu betslip e amount
                    if (apostaRow && apostaRow !== mainRow) {
                        const apostaButton = apostaRow.find('.ver-aposta');
                        if (apostaButton.length) {
                            // Se a linha principal não for a aposta, usar dados da aposta
                            betslipData = apostaButton.attr('data-betslip') || betslipData;
                            amount = parseFloat(apostaButton.attr('data-amount') || '0');
                        }
                    }
                    
                    // Se temos cashout, marcar
                    if (cashoutRow) {
                        hasCashout = true;
                        
                        // Se o betslip do cashout é mais informativo, usar ele
                        const cashoutButton = cashoutRow.find('.ver-aposta');
                        if (cashoutButton.length) {
                            const cashoutBetslip = cashoutButton.attr('data-betslip') || '{}';
                            // Se o betslip do cashout não for vazio ou não contiver apenas {}, usar ele
                            if (cashoutBetslip !== '{}' && cashoutBetslip.length > 2) {
                                betslipData = cashoutBetslip;
                            }
                        }
                    }
                    
                    // Se temos resultado, atualizar a operação e received-amount
                    if (resultadoRow) {
                        const resultButton = resultadoRow.find('.ver-aposta');
                        if (resultButton.length) {
                            operation = resultButton.attr('data-operation') || operation;
                            
                            // Se for ganho, usar amount do resultado como received-amount
                            if (operation === 'credit') {
                                const receivedAmount = parseFloat(resultButton.attr('data-amount') || '0');
                                viewButton.attr('data-received-amount', receivedAmount.toFixed(2));
                            }
                        }
                    }
                    
                    // Atualizar atributos do botão
                    viewButton.attr('data-betslip', betslipData);
                    viewButton.attr('data-operation', operation);
                    viewButton.attr('data-amount', amount.toFixed(2));
                    viewButton.attr('data-cashout', hasCashout ? '1' : '0');
                }
            }
            // Mesmo que seja apenas uma linha, garantir que a cor da seta esteja correta
            else if (rows.length === 1) {
                countAgrupadosUnicos++;
                const row = rows[0];
                
                // Verificar se é uma aposta usando o botão ou o texto do status
                const btn = row.find('.ver-aposta');
                let isAposta = false;
                
                if (btn.length && btn.attr('data-operation')) {
                    isAposta = btn.attr('data-operation').toLowerCase() === 'debit';
                } else {
                const statusText = row.find('[data-name="status"] small').text().trim().toLowerCase();
                    isAposta = statusText.includes('aposta') || statusText.includes('pendente');
                }
                
                // Se for apenas "Aposta", garantir que o ícone seja atualizado corretamente
                if (isAposta) {
                    // Primeiro atualizamos a cor de fundo
                    row.find('[data-name="type"] .H32ns').css('background-color', '#3498db');
                    
                    // Também vamos garantir que o ícone seja o SVG de informação para apostas
                    const iconContainer = row.find('[data-name="type"] .nuxt-icon');
                    if (iconContainer.length) {
                        iconContainer.html(`
                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 464c-114.7 0-208-93.31-208-208S141.3 48 256 48s208 93.31 208 208S370.7 464 256 464zM256 232c13.25 0 24-10.75 24-24c0-13.26-10.75-24-24-24S232 194.7 232 208C232 221.3 242.7 232 256 232zM304 368h-16V256c0-8.836-7.164-16-16-16h-32c-8.836 0-16 7.164-16 16s7.164 16 16 16h16v96h-16c-8.836 0-16 7.164-16 16s7.164 16 16 16h64c8.836 0 16-7.164 16-16S312.8 368 304 368z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        `);
                    }
                }
            }
        });
        
        // Verificação final para garantir que linhas duplicadas estejam ocultas
        $('table.UHNq- tr.WXGKq').each(function() {
            const row = $(this);
            const transactionId = row.attr('data-transaction-id');
            
            // Encontrar todas as linhas com este ID
            const sameIdRows = $(`tr[data-transaction-id="${transactionId}"]`);
            
            // Se temos mais de uma linha com este ID
            if (sameIdRows.length > 1) {
                // Verificar se esta linha deve estar oculta (não é a principal)
                if (!row.hasClass('merged-main-row')) {
                    row.hide();
                    row.css('display', 'none');
                    row.attr('style', 'display: none !important');
                    row.addClass('hidden-by-merge');
                }
            }
        });
    }
    
    function initUserBetViewButtons() {
        const verApostaBtns = document.querySelectorAll('.ver-aposta');
        
        verApostaBtns.forEach(btn => {
            // Remove existing event listeners to prevent duplications
            btn.removeEventListener('click', handleUserBetViewClick);
            
            // Copy data attributes from row cells if not present on button
            const row = btn.closest('tr');
            if (row) {
                // Usar o transaction ID da linha para o botão se não estiver definido
                if (!btn.hasAttribute('data-transaction-id') && row.hasAttribute('data-transaction-id')) {
                    btn.setAttribute('data-transaction-id', row.getAttribute('data-transaction-id'));
                }
                
                // Get operation from status cell if not already set
                if (!btn.hasAttribute('data-operation')) {
                    let operation = 'debit'; // Default
                    
                    // Verificar se há várias badges de status (agrupamento)
                    const mergedStatusContainer = row.querySelector('[data-name="status"] .merged-status-container');
                    
                    if (mergedStatusContainer) {
                        // Se temos um container de status agrupados, priorizar o status 'ganhou' ou 'perdeu'
                        const statusElements = mergedStatusContainer.querySelectorAll('.H32ns small');
                        let foundWinOrLose = false;
                        
                        statusElements.forEach(statusEl => {
                            const statusText = statusEl.textContent.trim().toLowerCase();
                            if (statusText.includes('ganhou') || statusText.includes('won')) {
                                operation = 'credit';
                                foundWinOrLose = true;
                            } else if (!foundWinOrLose && (statusText.includes('perdeu') || statusText.includes('lost'))) {
                                operation = 'lose';
                                foundWinOrLose = true;
                            }
                        });
                    } else {
                        // Status único
                    const statusCell = row.querySelector('[data-name="status"] small');
                    if (statusCell) {
                        const statusText = statusCell.textContent.trim().toLowerCase();
                        
                        if (statusText.includes('ganhou') || statusText.includes('won')) {
                            operation = 'credit';
                        } else if (statusText.includes('perdeu') || statusText.includes('lost')) {
                            operation = 'lose';
                            } else if (statusText.includes('cashout')) {
                                operation = 'credit';
                                btn.setAttribute('data-cashout', '1');
                            }
                        }
                        }
                        
                        btn.setAttribute('data-operation', operation);
                }
                
                // Get amount from amount cell if not already set
                if (!btn.hasAttribute('data-amount')) {
                    const amountCell = row.querySelector('[data-name="amount"]');
                    if (amountCell) {
                        const amount = amountCell.textContent.replace(/[^\d,\.]/g, '').trim();
                        btn.setAttribute('data-amount', amount);
                    }
                }
                
                // Set received amount based on operation if not already set
                if (!btn.hasAttribute('data-received-amount')) {
                    const operation = btn.getAttribute('data-operation');
                    const amountCell = row.querySelector('[data-name="amount"]');
                    if (operation === 'credit' && amountCell) {
                        const amount = amountCell.textContent.replace(/[^\d,\.]/g, '').trim();
                        btn.setAttribute('data-received-amount', amount);
                    } else {
                        btn.setAttribute('data-received-amount', '0');
                    }
                }
            }
            
            // Add new event listener
            btn.addEventListener('click', handleUserBetViewClick);
        });
        
        // Initialize close modal button
        const closeModalBtn = document.querySelector('.close-bet-modal');
        if (closeModalBtn) {
            closeModalBtn.removeEventListener('click', closeModal);
            closeModalBtn.addEventListener('click', closeModal);
        }
        
        // Ensure we only bind the document click event once
        document.removeEventListener('click', handleDocumentClick);
        document.addEventListener('click', handleDocumentClick);
        
    }
    
    // Helper function for closing modal
    function closeModal() {
                document.getElementById('verApostaModal').style.display = 'none';
                document.body.style.overflow = 'auto';
        }
        
    // Helper function for handling document clicks
    function handleDocumentClick(event) {
            const modal = document.getElementById('verApostaModal');
            if (event.target === modal) {
            closeModal();
            }
    }
    
    function handleUserBetViewClick() {
        try {
            // Remove active class from all buttons
            $('.ver-aposta').removeClass('active');
            // Add active class to the clicked button
            $(this).addClass('active');
            
            // Get data from button and closest row
            const row = this.closest('tr');
            if (!row) return;
            
            // Verificar se esta linha é resultado de um agrupamento
            const isMergedRow = row.querySelector('[data-name="status"] .merged-status-container') !== null;
            
            // Get data from the button, usando os atributos corretos
            // Nota: data-transaction-id é usado para agrupar, data-id é usado para identificar o registro específico
            const betslip = this.getAttribute('data-betslip') || '{}';
            const betId = this.getAttribute('data-id');
            const transactionId = this.getAttribute('data-transaction-id');
            const operation = this.getAttribute('data-operation') || '';
            const amount = this.getAttribute('data-amount') || '0';
            let receivedAmount = this.getAttribute('data-received-amount') || '0';
            let cashout = this.getAttribute('data-cashout') || '0';
            
            // Procurar valor recebido em todas as linhas com o mesmo transactionId
            // Se a operação atual for 'debit', precisamos encontrar o valor do 'credit' ou 'lose'
            if (operation === 'debit') {
                // Buscar em todas as linhas com o mesmo transactionId
                const rowsWithSameId = document.querySelectorAll(`tr[data-transaction-id="${transactionId}"]`);
                
                rowsWithSameId.forEach(otherRow => {
                    // Verificar se é uma linha diferente da atual
                    if (otherRow !== row) {
                        const otherBtn = otherRow.querySelector('.ver-aposta');
                        if (otherBtn) {
                            const otherOperation = otherBtn.getAttribute('data-operation');
                            // Se for credit ou lose, pegar o valor
                            if (otherOperation === 'credit' || otherOperation === 'lose') {
                                receivedAmount = otherBtn.getAttribute('data-amount') || '0';
                            }
                        }
                    }
                });
            }
            
            // Try to determine bet type from betslip
            let isMultipleBet = false;
            
            try {
                if (betslip && betslip !== '{}') {
                    let betslipData = betslip;
                    if (betslipData.includes('&quot;')) {
                        const textarea = document.createElement('textarea');
                        textarea.innerHTML = betslipData;
                        betslipData = textarea.value;
                    }
                    
                    // Try to parse the betslip
                    try {
                        const betslipObj = JSON.parse(betslipData);
                        
                        // Check if it's a multiple bet
                        if (betslipObj && betslipObj.bet_stakes) {
                            if ((betslipObj.bet_stakes.FullName && betslipObj.bet_stakes.FullName.includes("Multi")) ||
                                betslipObj.bet_stakes.BetTypeId === "3" ||
                                (betslipObj.bet_stakes.BetStakes && Array.isArray(betslipObj.bet_stakes.BetStakes) && 
                                 betslipObj.bet_stakes.BetStakes.length > 1)) {
                                isMultipleBet = true;
                                
                                // Update the row's data-bet-type attribute
                                $(row).attr('data-bet-type', 'multiple');
                                
                                // Update the source column text if using our new structure
                                const sourceCell = $(row).find('[data-name="source"]');
                                if (sourceCell.length) {
                                    const currentText = sourceCell.text().trim();
                                    if (currentText === "{{ __('menu.simple_bet') }}" || 
                                        currentText === "{{ __('menu.multiple_bet') }}") {
                                        sourceCell.text("{{ __('menu.multiple_bet') }}");
                                    }
                                }
                            } else {
                                // Simple bet
                                $(row).attr('data-bet-type', 'simple');
                                
                                // Update the source column text if using our new structure
                                const sourceCell = $(row).find('[data-name="source"]');
                                if (sourceCell.length) {
                                    const currentText = sourceCell.text().trim();
                                    if (currentText === "{{ __('menu.simple_bet') }}" || 
                                        currentText === "{{ __('menu.multiple_bet') }}") {
                                        sourceCell.text("{{ __('menu.simple_bet') }}");
                                    }
                                }
                            }
                        }
                    } catch (e) {
                        console.error('Error parsing betslip to determine bet type:', e);
                    }
                }
            } catch (e) {
                console.error('Error checking bet type:', e);
            }
            
            // Verificar se há cashout no betslip
            try {
                if (betslip && betslip.includes('is_cashout')) {
                    // Decodificar entidades HTML se necessário
                    let betslipData = betslip;
                    if (betslipData && betslipData.includes('&quot;')) {
                        const textarea = document.createElement('textarea');
                        textarea.innerHTML = betslipData;
                        betslipData = textarea.value;
                    }
                    
                    // Verificar o formato de cashout no betslip
                    try {
                        const betslipObj = JSON.parse(betslipData);
                        if (betslipObj && betslipObj.is_cashout !== undefined) {
                            const hasCashoutInBetslip = betslipObj.is_cashout === true || 
                                                      betslipObj.is_cashout === 1 || 
                                                      betslipObj.is_cashout === "1";
                            // Atualizar o atributo de cashout se detectado no betslip
                            if (hasCashoutInBetslip) {
                                cashout = '1';
                                
                                // Se for um resultado e tem cashout, ajustar a operação para exibir corretamente
                                if (operation === 'credit') {
                                    // Atualizar a exibição no modal para cashout
                                }
                            }
                        }
                    } catch (e) {
                        console.error('Erro ao analisar betslip para cashout:', e);
                    }
                }
            } catch (e) {
                console.error('Erro ao verificar cashout no betslip:', e);
            }
            
            // Define a flag hasCashout baseada no valor de cashout
            const hasCashout = cashout === '1' || cashout === 1 || cashout === true;
            
            // Clear previous data
            resetModalValues();
            
            // Format currency values
            const betAmount = parseFloat(amount || 0);
            document.getElementById('betAmount').textContent = 'R$ ' + betAmount.toFixed(2).replace('.', ',');
            
            // Set received amount based on operation
            const recAmount = parseFloat(receivedAmount || 0);
            document.getElementById('receivedAmount').textContent = 'R$ ' + recAmount.toFixed(2).replace('.', ',');
            
            // Set bet status based on operation
            let status = '';
            
            // Se a linha foi agrupada, podemos mostrar múltiplos status
            if (isMergedRow) {
                const statusContainer = document.createElement('div');
                statusContainer.classList.add('status-badges');
                statusContainer.style.display = 'flex';
                statusContainer.style.flexDirection = 'column';
                statusContainer.style.gap = '4px';
                
                // Precisamos identificar qual é o status principal a ser mostrado
                // Prioridade: resultado (ganhou/perdeu) > aposta
                let statusPrincipal = null;
                
                // Verificar o betslip para cashout, mas não mostraremos isso no status
                let hasCashoutFromBetslip = false;
                try {
                    if (betslip && betslip.includes('is_cashout')) {
                        // Decodificar betslip se necessário
                        let betslipData = betslip;
                        if (betslipData.includes('&quot;')) {
                            const textareaEl = document.createElement('textarea');
                            textareaEl.innerHTML = betslipData;
                            betslipData = textareaEl.value;
                        }
                        
                        try {
                            const betslipObj = JSON.parse(betslipData);
                            if (betslipObj && betslipObj.is_cashout !== undefined) {
                                hasCashoutFromBetslip = betslipObj.is_cashout === true || 
                                                       betslipObj.is_cashout === 1 || 
                                                       betslipObj.is_cashout === "1";
                                
                                // Apenas atualizamos o valor de cashout para usar mais tarde
                                if (hasCashoutFromBetslip) {
                                    cashout = '1';
                                }
                            }
                        } catch (parseError) {
                            console.error('Erro ao analisar betslip para cashout no modal:', parseError);
                        }
                    }
                } catch (e) {
                    console.error('Erro ao verificar cashout no betslip no modal:', e);
                }
                
                // Clone os status da linha para análise
                const statusElements = row.querySelectorAll('[data-name="status"] .H32ns');
                let temResultado = false;
                
                // Procurar status de ganhou/perdeu (não mostrar cashout no status)
                statusElements.forEach(statusEl => {
                    if (temResultado) return; // Se já encontramos, ignorar os demais
                    
                    const statusText = statusEl.textContent.trim().toLowerCase();
                    if (statusText.includes('ganhou') || statusText.includes('won') || 
                        statusText.includes('perdeu') || statusText.includes('lost')) {
                        statusPrincipal = statusEl;
                        temResultado = true;
                    }
                });
                
                // Se não encontramos resultado, usar o status de aposta
                if (!statusPrincipal) {
                    statusElements.forEach(statusEl => {
                        if (statusPrincipal) return; // Se já encontramos, ignorar os demais
                        
                        const statusText = statusEl.textContent.trim().toLowerCase();
                        if (statusText.includes('aposta') || statusText.includes('pendente')) {
                            statusPrincipal = statusEl;
                        }
                    });
                }
                
                // Se ainda não temos status principal, usar o primeiro status disponível
                if (!statusPrincipal && statusElements.length > 0) {
                    statusPrincipal = statusElements[0];
                }
                
                // Adicionar apenas o status principal ao container
                if (statusPrincipal) {
                    const clonedStatus = statusPrincipal.cloneNode(true);
                    
                    // Aplicar estilos corretos ao elemento clonado
                    const clonedDiv = clonedStatus.querySelector('.H32ns') || clonedStatus;
                    if (clonedDiv) {
                        // Garantir que os estilos de padding e border-radius estejam aplicados
                        clonedDiv.style.paddingLeft = '0.4rem';
                        clonedDiv.style.paddingRight = '0.4rem';
                        clonedDiv.style.borderRadius = '8px';
                        clonedDiv.style.display = 'inline-block';
                        clonedDiv.style.whiteSpace = 'nowrap';
                        clonedDiv.style.width = 'fit-content';
                    }
                    
                    statusContainer.appendChild(clonedStatus);
                }
                
                document.getElementById('betStatus').innerHTML = '';
                document.getElementById('betStatus').appendChild(statusContainer);
            } else {
                // Status único baseado na operação
            switch(operation.toLowerCase()) {
                case 'debit':
                    status = '<span style="display: inline-block; white-space: nowrap; width: fit-content; padding-left: 0.4rem; padding-right: 0.4rem; border-radius: 8px; background-color: #2196f3 !important; color: white !important;"><small><i class="fas fa-hourglass-half mr-1"></i> Aposta</small></span>';
                    break;
                case 'credit':
                    // Verificar se temos cashout no betslip
                    let hasCashoutInBetslip = false;
                    try {
                        if (betslip && betslip.includes('is_cashout')) {
                            // Decodificar entidades HTML se necessário
                            let betslipData = betslip;
                            if (betslipData.includes('&quot;')) {
                                const textarea = document.createElement('textarea');
                                textarea.innerHTML = betslipData;
                                betslipData = textarea.value;
                            }
                            
                            const betslipObj = JSON.parse(betslipData);
                            if (betslipObj && betslipObj.is_cashout !== undefined) {
                                hasCashoutInBetslip = betslipObj.is_cashout === true || 
                                                     betslipObj.is_cashout === 1 || 
                                                     betslipObj.is_cashout === "1";
                                
                                // Atualizar cashout para usar mais tarde
                                if (hasCashoutInBetslip) {
                                    cashout = '1';
                                }
                            }
                        }
                    } catch (e) {
                        console.error('Erro ao verificar cashout no betslip para status único:', e);
                    }
                    
                    // Mostrar apenas status de ganhou para operação 'credit', não mostrar cashout no status
                    status = '<span style="display: inline-block; white-space: nowrap; width: fit-content; padding-left: 0.4rem; padding-right: 0.4rem; border-radius: 8px; background-color: #2b3 !important; color: white !important;"><small><i class="fas fa-check-circle mr-1"></i> Ganhou</small></span>';
                    break;
                case 'lose':
                    status = '<span style="display: inline-block; white-space: nowrap; width: fit-content; padding-left: 0.4rem; padding-right: 0.4rem; border-radius: 8px; background-color: rgb(245, 47, 47) !important; color: white !important;"><small><i class="fas fa-times-circle mr-1"></i> Perdeu</small></span>';
                    break;
                case 'cancel_debit':
                case 'cancel_credit':
                    status = '<span style="display: inline-block; white-space: nowrap; width: fit-content; padding-left: 0.4rem; padding-right: 0.4rem; border-radius: 8px; background-color: #f39c12 !important; color: white !important;"><small><i class="fas fa-ban mr-1"></i> Cancelada</small></span>';
                    break;
                default:
                    // Check status text from the row if operation is not specified
                    const statusCell = row.querySelector('[data-name="status"] small');
                    if (statusCell) {
                        const statusText = statusCell.textContent.trim().toLowerCase();
                        
                        if (statusText.includes('ganhou') || statusText.includes('won')) {
                            status = '<span style="display: inline-block; white-space: nowrap; width: fit-content; padding-left: 0.4rem; padding-right: 0.4rem; border-radius: 8px; background-color: #2b3 !important; color: white !important;"><small><i class="fas fa-check-circle mr-1"></i> Ganhou</small></span>';
                        } else if (statusText.includes('perdeu') || statusText.includes('lost')) {
                            status = '<span style="display: inline-block; white-space: nowrap; width: fit-content; padding-left: 0.4rem; padding-right: 0.4rem; border-radius: 8px; background-color: rgb(245, 47, 47) !important; color: white !important;"><small><i class="fas fa-times-circle mr-1"></i> Perdeu</small></span>';
                        } else {
                            status = '<span style="display: inline-block; white-space: nowrap; width: fit-content; padding-left: 0.4rem; padding-right: 0.4rem; border-radius: 8px; background-color: #2196f3 !important; color: white !important;"><small><i class="fas fa-hourglass-half mr-1"></i> Aposta</small></span>';
                        }
                    } else {
                        status = '<span style="display: inline-block; white-space: nowrap; width: fit-content; padding-left: 0.4rem; padding-right: 0.4rem; border-radius: 8px; background-color: #7f8c8d !important; color: white !important;"><small><i class="fas fa-question-circle mr-1"></i> Desconhecido</small></span>';
                    }
            }
            
            document.getElementById('betStatus').innerHTML = status;
            }
            
            // Default bet type to Simple if not specified
            document.getElementById('betType').innerHTML = '<i class="fas fa-ticket-alt mr-1"></i> ' + translateBetTerms('Simple Bet');
            
            // Check if betslip has valid data but avoid large operations if empty
            if (betslip && betslip !== '{}') {
                try {
                    let betslipObj;
                    
                    // Limitar tamanho do betslip para evitar problemas de processamento
                    if (betslip.length > 100000) {
                        console.warn('Betslip data too large, truncating for performance');
                        betslipObj = { large_data: true };
                    } else {
                        // Handle HTML entities if needed
                        const textarea = document.createElement('textarea');
                        textarea.innerHTML = betslip;
                        const cleanBetslip = textarea.value;
                        
                        // Try to parse the JSON
                        try {
                            // Primeiro, vamos tentar extrair o MaxWinAmount diretamente do betslip original
                            let maxWinAmountFromRaw = null;
                            if (cleanBetslip && cleanBetslip.includes('MaxWinAmount')) {
                                const match = cleanBetslip.match(/"MaxWinAmount"\s*:\s*([0-9.]+)/);
                                if (match && match[1]) {
                                    maxWinAmountFromRaw = parseFloat(match[1]);
                                }
                            }
                            
                            betslipObj = JSON.parse(cleanBetslip);
                            
                            // Se encontramos o MaxWinAmount no texto, adicioná-lo ao objeto
                            if (maxWinAmountFromRaw !== null) {
                                // Adicionar ao objeto principal
                                if (!betslipObj.MaxWinAmount) {
                                    betslipObj.MaxWinAmount = maxWinAmountFromRaw;
                                }
                                
                                // Se tiver bet_stakes, adicionar lá também
                                if (betslipObj.bet_stakes && !betslipObj.bet_stakes.MaxWinAmount) {
                                    betslipObj.bet_stakes.MaxWinAmount = maxWinAmountFromRaw;
                                }
                            }
                            
                            // Extrair valores de retorno potencial e odds do betslip
                            extractBetslipValues(betslipObj, betAmount);
                            
                        } catch (parseError) {
                            console.error('Error parsing betslip JSON:', parseError);
                            
                            // Try basic fixes for common issues
                            try {
                                const fixedBetslip = cleanBetslip.replace(/\\/g, '\\\\').replace(/\n/g, '\\n');
                                betslipObj = JSON.parse(fixedBetslip);
                                
                                // Extrair valores de retorno potencial e odds do betslip
                                extractBetslipValues(betslipObj, betAmount);
                                
                            } catch (secondError) {
                                console.error('Failed to parse betslip after fixes');
                                betslipObj = { parse_error: true };
                            }
                        }
                    }
                    
                    // Definir o valor de cashout antes de processar qualquer dado
                    // Isso garante que está disponível antes da renderização dos containers
                    document.getElementById('isCashout').innerHTML = hasCashout ? 
                        '<i class="fas fa-exchange-alt mr-1"></i> Sim' : 
                        '<i class="fas fa-exchange-alt mr-1"></i> Não';
                    
                    // Process bet slip data with timeout protection
                    const processingTimeout = setTimeout(() => {
                        console.warn('Processing timeout, showing basic info');
                        document.getElementById('maxWinAmount').textContent = 'R$ ' + (betAmount * 1).toFixed(2).replace('.', ',');
                        document.getElementById('multipleEventsContainer').innerHTML = '<div style="padding: 10px; text-align: center;">Detalhes indisponíveis devido a timeout</div>';
                    }, 2000); // 2 second timeout
                    
                    // Verificar diretamente por "MaxWinAmount" no texto original do betslip
                    if (typeof betslip === 'string' && betslip.includes('MaxWinAmount')) {
                        const match = betslip.match(/"MaxWinAmount"\s*:\s*([0-9.]+)/);
                        if (match && match[1]) {
                            // Guardar esse valor para uso posterior
                            window.maxWinAmountFromRawBetslip = parseFloat(match[1]);
                        }
                    }
                    
                    processBetData(betslipObj, hasCashout);
                    clearTimeout(processingTimeout);
                    
                    // Verificar se temos o valor do MaxWinAmount extraído diretamente do betslip original
                    if (window.maxWinAmountFromRawBetslip) {
                        document.getElementById('maxWinAmount').textContent = formatCurrency(window.maxWinAmountFromRawBetslip);
                        // Limpar o valor para não interferir com outras apostas
                        delete window.maxWinAmountFromRawBetslip;
                    }
                    
                    // Calculate max win amount if not set by processBetData
                    const maxWinElement = document.getElementById('maxWinAmount');
                    if (!maxWinElement.textContent) {
                        let factor = 1;
                        
                        // Try to get factor from betslipObj
                        if (betslipObj && betslipObj.bet_stakes && betslipObj.bet_stakes.Factor) {
                            factor = parseFloat(betslipObj.bet_stakes.Factor);
                        } else if (document.getElementById('factorTotal').textContent) {
                            const factorText = document.getElementById('factorTotal').textContent.trim();
                            factor = parseFloat(factorText) || 1;
                        }
                        
                        const maxWin = betAmount * factor;
                        maxWinElement.textContent = 'R$ ' + maxWin.toFixed(2).replace('.', ',');
                    }
                    
                    // Verificar se é aposta múltipla com base no betslip e esconder singleEventContainer se for
                    if (betslipObj && betslipObj.bet_stakes && betslipObj.bet_stakes.BetStakes && 
                        betslipObj.bet_stakes.BetStakes.length > 1) {
                        // É uma aposta múltipla
                        
                        // Esconder os blocos "Informações do Evento" e "Detalhes da Aposta" do modal
                        document.getElementById('multipleEventsContainer').style.display = 'block';
                        
                        // Não exibir o campo de cashout embaixo, pois já está no cabeçalho
                        document.querySelector('.single-cashout-field').style.display = 'none';
                    } else {
                        // Apostas simples: exibir normalmente
                        document.getElementById('multipleEventsContainer').style.display = 'block';
                        
                        // Set cashout status para aposta simples
                        document.querySelector('.single-cashout-field').style.display = 'block';
                        document.getElementById('isCashout').innerHTML = hasCashout ? 
                            '<i class="fas fa-exchange-alt mr-1"></i> Sim' : 
                            '<i class="fas fa-exchange-alt mr-1"></i> Não';
                    }
                    
                } catch (e) {
                    console.error('Error processing betslip:', e);
                    // Set default max win amount if processing failed
                    document.getElementById('maxWinAmount').textContent = 'R$ ' + (betAmount * 1).toFixed(2).replace('.', ',');
                    document.getElementById('multipleEventsContainer').innerHTML = '<div style="padding: 10px; text-align: center; color: white;">Erro ao processar detalhes da aposta</div>';
                }
            } else {
                // If no betslip data, set default max win amount
                document.getElementById('maxWinAmount').textContent = 'R$ ' + (betAmount * 1).toFixed(2).replace('.', ',');
                
                // Hide details if no betslip data
                document.getElementById('multipleEventsContainer').innerHTML = '';
            }
            
            // Nova função para extrair valores do betslip
            function extractBetslipValues(betslipObj, betAmount) {
                try {
                    // Extrair MaxWinAmount para Retorno Potencial
                    let maxWinAmount = null;
                    
                    // Extrair MaxAllFactor para Odds Totais
                    let maxFactor = null;
                    
                    if (betslipObj && betslipObj.bet_stakes) {
                        // Tentar extrair o MaxWinAmount diretamente - este é o campo correto
                        maxWinAmount = betslipObj.bet_stakes.MaxWinAmount;
                        
                        // Tentar extrair o MaxAllFactor (ou Factor como fallback)
                        maxFactor = betslipObj.bet_stakes.MaxAllFactor || betslipObj.bet_stakes.Factor || betslipObj.bet_stakes.Odd;
                        
                        // Definir o retorno potencial no modal
                        if (maxWinAmount !== undefined && maxWinAmount !== null) {
                            document.getElementById('maxWinAmount').textContent = formatCurrency(maxWinAmount);
                        } else if (maxFactor) {
                            // Se não tiver MaxWinAmount, calcular com base no fator
                            const calculatedWin = betAmount * parseFloat(maxFactor);
                            document.getElementById('maxWinAmount').textContent = 'R$ ' + calculatedWin.toFixed(2).replace('.', ',');
                        } else {
                            // Se não conseguir calcular, usar o valor do betAmount
                            document.getElementById('maxWinAmount').textContent = 'R$ 0,00';
                        }
                        
                        // Definir as odds totais no modal
                        if (maxFactor) {
                            // Formatar o fator para mostrar apenas 2 casas decimais
                            const formattedFactor = parseFloat(maxFactor).toFixed(2);
                            const oddColor = getOddBadgeColor(formattedFactor);
                            document.getElementById('factorTotal').innerHTML = `<span style="display: inline-block; background-color: ${oddColor}; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px;"><i class="fas fa-calculator mr-1"></i> ${formattedFactor}</span>`;
                        } else {
                            document.getElementById('factorTotal').innerHTML = `<span style="display: inline-block; background-color: #7f8c8d; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px;"><i class="fas fa-calculator mr-1"></i> N/A</span>`;
                        }
                    } else if (betslipObj) {
                        // Verificar estrutura alternativa para apostas simples
                        maxWinAmount = betslipObj.MaxWinAmount || betslipObj.PotentialWin || betslipObj.ExpectedWin;
                        maxFactor = betslipObj.Odd || betslipObj.Factor || betslipObj.MaxAllFactor;
                        
                        // Definir o retorno potencial no modal
                        if (maxWinAmount !== undefined && maxWinAmount !== null) {
                            document.getElementById('maxWinAmount').textContent = formatCurrency(maxWinAmount);
                        } else if (maxFactor) {
                            // Se não tiver MaxWinAmount, calcular com base no fator
                            const calculatedWin = betAmount * parseFloat(maxFactor);
                            document.getElementById('maxWinAmount').textContent = 'R$ ' + calculatedWin.toFixed(2).replace('.', ',');
                        } else {
                            // Se não conseguir calcular, mostrar valor zero
                            document.getElementById('maxWinAmount').textContent = 'R$ 0,00';
                        }
                        
                        // Definir as odds totais no modal
                        if (maxFactor) {
                            // Formatar o fator para mostrar apenas 2 casas decimais
                            const formattedFactor = parseFloat(maxFactor).toFixed(2);
                            const oddColor = getOddBadgeColor(formattedFactor);
                            document.getElementById('factorTotal').innerHTML = `<span style="display: inline-block; background-color: ${oddColor}; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px;"><i class="fas fa-calculator mr-1"></i> ${formattedFactor}</span>`;
                        } else {
                            document.getElementById('factorTotal').innerHTML = `<span style="display: inline-block; background-color: #7f8c8d; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px;"><i class="fas fa-calculator mr-1"></i> N/A</span>`;
                        }
                    }
                } catch (error) {
                    console.error('Erro ao extrair valores do betslip:', error);
                    // Em caso de erro, mostrar valores padrão
                    document.getElementById('maxWinAmount').textContent = 'R$ 0,00';
                    document.getElementById('factorTotal').innerHTML = `<span style="display: inline-block; background-color: #7f8c8d; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px;"><i class="fas fa-calculator mr-1"></i> N/A</span>`;
                }
            }
            
            // Show modal
            document.getElementById('verApostaModal').style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent scrolling

        } catch (error) {
            console.error('Error processing bet view:', error);
            // Show error message and ensure modal is visible
            document.getElementById('verApostaModal').style.display = 'block';
            document.getElementById('multipleEventsContainer').innerHTML = '<div style="padding: 20px; text-align: center; color: white; background-color: #e74c3c; border-radius: 8px;"><i class="fas fa-exclamation-circle"></i> Ocorreu um erro ao exibir detalhes da aposta</div>';
        }
    }
    
    function getOddBadgeColor(odd) {
        if (odd === 'N/A' || isNaN(odd)) return '#7f8c8d'; // Dark gray for unknown
        const oddNum = parseFloat(odd);
        if (oddNum < 1.5) return '#3498db'; // Light blue for low odds
        if (oddNum < 2.0) return '#2980b9'; // Medium blue for medium-low odds
        if (oddNum < 3.0) return 'var(--primary-color)'; // Primary color for medium odds
        if (oddNum < 5.0) return '#f39c12'; // Orange for high odds
        return '#e74c3c'; // Red for very high odds
    }
    
    function processBetData(betslipObj, hasCashout) {
        try {
            let factor = null;
            let maxWinAmount = null;
            let betType = "Simples";
            let isMultipleBet = false;
            let multipleEvents = [];
            let bet = null;
            
            // Obter valor da aposta do elemento HTML
            const betAmountText = document.getElementById('betAmount').textContent;
            const betAmount = parseFloat(betAmountText.replace(/[^0-9.,]/g, '').replace(',', '.')) || 0;
            
            // Initialize the isCashout element before reading it elsewhere
            document.getElementById('isCashout').innerHTML = hasCashout ? 
                '<i class="fas fa-exchange-alt mr-1"></i> Sim' : 
                '<i class="fas fa-exchange-alt mr-1"></i> Não';
            
            // Hide containers by default
            document.getElementById('multipleEventsContainer').style.display = 'none';
            document.getElementById('multipleEventsContainer').innerHTML = '';
            
            // Check standard bet_stakes structure
            if (betslipObj && betslipObj.bet_stakes) {
                // Extrair o fator (odds) - preferir MaxAllFactor, mas cair para Factor se não disponível
                factor = betslipObj.bet_stakes.MaxAllFactor || betslipObj.bet_stakes.Factor;
                
                // Extrair o MaxWinAmount (valor do retorno potencial)
                maxWinAmount = betslipObj.bet_stakes.MaxWinAmount;
                
                // Se não encontrou maxWinAmount explicitamente, tente calcular a partir do fator
                if (maxWinAmount === undefined && factor) {
                    maxWinAmount = betAmount * parseFloat(factor);
                }
                
                // Check if it's a multiple bet
                if ((betslipObj.bet_stakes.FullName && betslipObj.bet_stakes.FullName.includes("Multi")) ||
                    betslipObj.bet_stakes.BetTypeId === "3" ||
                    (betslipObj.bet_stakes.BetStakes && Array.isArray(betslipObj.bet_stakes.BetStakes) && 
                    betslipObj.bet_stakes.BetStakes.length > 1)) {
                    betType = "Múltipla";
                    isMultipleBet = true;
                    
                    // Update the bet type in the active transaction row
                    const activeRow = $('tr.WXGKq.merged-main-row[data-transaction-id="' + $('.ver-aposta[data-transaction-id].active, .ver-aposta[data-transaction-id]:active').attr('data-transaction-id') + '"]');
                    if (activeRow.length) {
                        activeRow.attr('data-bet-type', 'multiple');
                        activeRow.find('[data-name="source"]').html(activeRow.find('[data-name="source"]').text().includes('multiple') ? 
                            activeRow.find('[data-name="source"]').text() : 
                            isMultipleBet ? translateBetTerms('Multiple Bet') : translateBetTerms('Simple Bet'));
                    }
                } else {
                    // Update the bet type in the active transaction row for simple bets
                    const activeRow = $('tr.WXGKq.merged-main-row[data-transaction-id="' + $('.ver-aposta[data-transaction-id].active, .ver-aposta[data-transaction-id]:active').attr('data-transaction-id') + '"]');
                    if (activeRow.length) {
                        activeRow.attr('data-bet-type', 'simple');
                        activeRow.find('[data-name="source"]').html(activeRow.find('[data-name="source"]').text().includes('simple') ? 
                            activeRow.find('[data-name="source"]').text() : 
                            translateBetTerms('Simple Bet'));
                    }
                }
                
                // Set potential return
                if (maxWinAmount !== undefined) {
                    document.getElementById('maxWinAmount').textContent = formatCurrency(maxWinAmount);
                }
                
                // Show bet type with icon
                document.getElementById('betType').innerHTML = `<i class="fas ${isMultipleBet ? 'fa-layer-group' : 'fa-ticket-alt'} mr-1"></i> ${translateBetTerms(betType)}`;
                
                // Display total odds with color based on value and format to 2 decimal places
                if (factor) {
                    // Format factor to show only 2 decimal places
                    const formattedFactor = parseFloat(factor).toFixed(2);
                    const oddColor = getOddBadgeColor(formattedFactor);
                    document.getElementById('factorTotal').innerHTML = `<span style="display: inline-block; background-color: ${oddColor}; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px;"><i class="fas fa-calculator mr-1"></i> ${formattedFactor}</span>`;
                }
                
                // Process bet stakes (events)
                if (betslipObj.bet_stakes.BetStakes && Array.isArray(betslipObj.bet_stakes.BetStakes)) {
                    if (betslipObj.bet_stakes.BetStakes.length > 1) {
                        // Multiple events
                        isMultipleBet = true;
                        
                        // Preencher o valor de cashout mesmo para apostas múltiplas
                        // isso é necessário para que o cabeçalho possa mostrar corretamente
                        document.getElementById('isCashout').innerHTML = hasCashout ? 
                            '<i class="fas fa-exchange-alt mr-1"></i> Sim' : 
                            '<i class="fas fa-exchange-alt mr-1"></i> Não';
                        
                        // Depois processar as apostas múltiplas
                        processMultipleBets(betslipObj.bet_stakes.BetStakes);
                        
                        // Para apostas múltiplas, não exibimos o campo de cashout no final
                        // Ele já aparece no cabeçalho da aposta múltipla
                        document.querySelector('.single-cashout-field').style.display = 'none';
                    } else if (betslipObj.bet_stakes.BetStakes.length === 1) {
                        // Single event
                        processSingleBet(betslipObj.bet_stakes.BetStakes[0]);
                        
                        // Para apostas simples, exibimos o campo de cashout normalmente
                        document.querySelector('.single-cashout-field').style.display = 'block';
                        
                        // Set cashout status para apostas simples
                        document.getElementById('isCashout').innerHTML = hasCashout ? 
                            '<i class="fas fa-exchange-alt mr-1"></i> Sim' : 
                            '<i class="fas fa-exchange-alt mr-1"></i> Não';
                    }
                }
            }
            // Alternate structure
            else if (betslipObj && betslipObj.BetTypeId) {
                // Extrair valores diretamente do objeto principal
                factor = betslipObj.Factor || betslipObj.Odd;
                maxWinAmount = betslipObj.MaxWinAmount || betslipObj.PotentialWin;
                
                // Se não encontrou maxWinAmount explicitamente, tente calcular a partir do fator
                if (maxWinAmount === undefined && factor) {
                    maxWinAmount = betAmount * parseFloat(factor);
                }
                
                // Set potential return
                if (maxWinAmount !== undefined) {
                    document.getElementById('maxWinAmount').textContent = formatCurrency(maxWinAmount);
                }
                
                // Display total odds if available
                if (factor) {
                    const formattedFactor = parseFloat(factor).toFixed(2);
                    const oddColor = getOddBadgeColor(formattedFactor);
                    document.getElementById('factorTotal').innerHTML = `<span style="display: inline-block; background-color: ${oddColor}; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px;"><i class="fas fa-calculator mr-1"></i> ${formattedFactor}</span>`;
                }
                
                processSingleBet(betslipObj);
                
                // Para apostas simples, exibimos o campo de cashout normalmente
                document.querySelector('.single-cashout-field').style.display = 'block';
                
                // Set cashout status para apostas simples
                document.getElementById('isCashout').innerHTML = hasCashout ? 
                    '<i class="fas fa-exchange-alt mr-1"></i> Sim' : 
                    '<i class="fas fa-exchange-alt mr-1"></i> Não';
            }
            
        } catch (error) {
            console.error('Error processing bet data:', error);
        }
    }
    
    function processMultipleBets(bets) {
        if (!bets || !Array.isArray(bets)) return;
        
        // Show multiple events container with header
        document.getElementById('multipleEventsContainer').style.display = 'block';
        
        // Clear previous content only once
        const container = document.getElementById('multipleEventsContainer');
        container.innerHTML = '';
        
        // Get cashout status from the hidden isCashout element
        const hasCashout = document.getElementById('isCashout').innerHTML.includes('Sim');
        
        // Add header for multiple bets with cashout information
        const header = document.createElement('div');
        header.className = 'multiple-bet-header';
        header.style.marginBottom = '15px';
        header.style.padding = '10px 15px';
        header.style.backgroundColor = '#323637';
        header.style.borderRadius = '8px';
        header.style.display = 'flex';
        header.style.justifyContent = 'space-between';
        header.style.alignItems = 'center';
        
        // Create header content with title and cashout
        header.innerHTML = `
            <div>
                <h5 style="font-weight: 600; margin-bottom: 0; color: white;">
                    <i class="fas fa-layer-group mr-2"></i>Aposta Múltipla (${bets.length})
                </h5>
            </div>
            <div>
                <span style="display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 12px; color: white; background-color: ${hasCashout ? '#8bc34a' : '#7f8c8d'};">
                    <i class="fas fa-exchange-alt mr-1"></i> Cashout: ${hasCashout ? 'Sim' : 'Não'}
                </span>
            </div>
        `;
        
        container.appendChild(header);
        
        // Create scrollable container for cards
        const cardsContainer = document.createElement('div');
        cardsContainer.className = 'multiple-bet-cards';
        cardsContainer.style.maxHeight = '350px';
        cardsContainer.style.overflowY = 'auto';
        cardsContainer.style.display = 'grid';
        cardsContainer.style.gridTemplateColumns = 'repeat(1, 1fr)';
        cardsContainer.style.gap = '10px';
        cardsContainer.style.padding = '5px';
        
        // Add custom scrollbar styles
        const scrollbarStyle = document.createElement('style');
        scrollbarStyle.textContent = `
            .multiple-bet-cards::-webkit-scrollbar {
                width: 6px;
            }
            .multiple-bet-cards::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.05);
                border-radius: 3px;
            }
            .multiple-bet-cards::-webkit-scrollbar-thumb {
                background-color: var(--primary-color);
                border-radius: 3px;
            }
        `;
        document.head.appendChild(scrollbarStyle);
        
        // Add event cards to the scrollable container
        bets.forEach((eventBet, index) => {
            // Process each bet in the multiple bet
            // Translate any bet terms if needed
            if (eventBet.StakeName) {
                eventBet.StakeName = translateBetTerms(eventBet.StakeName);
            }
            
            if (eventBet.StakeTypeName) {
                eventBet.StakeTypeName = translateBetTerms(eventBet.StakeTypeName);
            }
            
            if (eventBet.FullStake) {
                eventBet.FullStake = translateBetTerms(eventBet.FullStake);
            }
            
            // Format dates
            if (eventBet.EventDate) {
                eventBet.FormattedDate = formatBetDate(eventBet.EventDate);
            } else if (eventBet.Date) {
                eventBet.FormattedDate = formatBetDate(eventBet.Date);
            } else if (eventBet.StartDate) {
                eventBet.FormattedDate = formatBetDate(eventBet.StartDate);
            } else if (eventBet.GameDate) {
                eventBet.FormattedDate = formatBetDate(eventBet.GameDate);
            }
            
            const card = createEventCard(eventBet, index + 1);
            cardsContainer.appendChild(card);
        });
        
        // Add scrollable container to main container
        container.appendChild(cardsContainer);
    }
    
    function createEventCard(eventBet, index) {
        const card = document.createElement('div');
        card.className = 'event-card';
        card.style.border = '1px solid #323637';
        card.style.borderRadius = '6px';
        card.style.overflow = 'hidden';
        card.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
        
        // Verificar todos os possíveis campos em diferentes estruturas de betslip
        // Get event information - tentar diferentes propriedades para maior compatibilidade
        const eventName = eventBet.EventNameOnly || eventBet.EventName || eventBet.Name || eventBet.TeamName || 'N/A';
        const tournamentName = eventBet.TournamentName || eventBet.Tournament || eventBet.League || 'N/A';
        const categoryName = eventBet.CategoryName || eventBet.Sport || eventBet.Category || 'N/A';
        
        // Format odd to 2 decimal places - tentar diferentes propriedades
        let oddValue = eventBet.Factor || eventBet.Odd || eventBet.Odds || eventBet.Quota || 'N/A';
        if (oddValue !== 'N/A') {
            oddValue = parseFloat(oddValue).toFixed(2);
        }
        
        // Ver se temos odds no container pai
        if (oddValue === 'N/A' && document.getElementById('factorTotal').textContent) {
            // Extrair apenas o valor numérico
            const textContent = document.getElementById('factorTotal').textContent;
            const match = textContent.match(/\d+(\.\d+)?/);
            if (match) {
                oddValue = match[0];
            }
        }
        
        const oddColor = getOddBadgeColor(oddValue);
        
        // Verificar tipo de aposta para mostrar corretamente
        let stakeName = eventBet.StakeName || eventBet.StakeTypeName || eventBet.Market || eventBet.Selection || 'N/A';
        
        // Verificar se há informação de FullStake para tradução
        if (eventBet.FullStake) {
            stakeName = translateBetTerms(eventBet.FullStake);
        } else {
            // Traduzir o stakeName usando a função de tradução
            stakeName = translateBetTerms(stakeName);
        }
        
        let displayName = stakeName;
        
        // Verificar se há dados de times
        let teamName = null;
        if (eventBet.Teams && Array.isArray(eventBet.Teams)) {
            // Verificar se é Win1 (Casa), Win2 (Visitante) ou outro tipo
            const isWin1 = /Win1|^1$|casa|home/i.test(stakeName);
            const isWin2 = /Win2|^2$|visitante|away/i.test(stakeName);
            const sideToFind = isWin1 ? 1 : (isWin2 ? 2 : null);
            
            if (sideToFind) {
                const teamFound = eventBet.Teams.find(team => {
                    const side = team.Side !== undefined ? team.Side : team.side;
                    return side === sideToFind || side === String(sideToFind);
                });
                
                if (teamFound) {
                    teamName = teamFound.Name || teamFound.name;
                }
            }
        }
        
        // Montar nome de exibição com time se disponível
        if (teamName) {
            if (stakeName.toLowerCase().includes('casa') || /Win1|^1$/i.test(stakeName)) {
                displayName = `${teamName} (Casa)`;
            } else if (stakeName.toLowerCase().includes('visitante') || /Win2|^2$/i.test(stakeName)) {
                displayName = `${teamName} (Visitante)`;
            } else {
                displayName = teamName;
            }
        }
        
        // Verificar se é ao vivo - tentar diferentes campos
        const isLive = eventBet.IsLive === '1' || eventBet.IsLive === 1 || eventBet.IsLive === true || 
                      eventBet.Live === '1' || eventBet.Live === 1 || eventBet.Live === true || false;
        
        // Format date usando a nova função de formatação
        const eventDate = eventBet.EventDate || eventBet.Date || eventBet.StartDate || eventBet.GameDate || 'N/A';
        const formattedDate = formatBetDate(eventDate);
        
        // Create card header
        const cardHeader = document.createElement('div');
        cardHeader.style.backgroundColor = '#323637';
        cardHeader.style.borderBottom = '1px solid #323637';
        cardHeader.style.padding = '8px 12px';
        cardHeader.style.display = 'flex';
        cardHeader.style.justifyContent = 'space-between';
        cardHeader.style.alignItems = 'center';
        
        // Event number badge
        const eventNumber = document.createElement('span');
        eventNumber.style.backgroundColor = 'var(--primary-color)';
        eventNumber.style.color = 'white';
        eventNumber.style.borderRadius = '50%';
        eventNumber.style.width = '22px';
        eventNumber.style.height = '22px';
        eventNumber.style.display = 'inline-flex';
        eventNumber.style.justifyContent = 'center';
        eventNumber.style.alignItems = 'center';
        eventNumber.style.fontSize = '12px';
        eventNumber.style.marginRight = '8px';
        eventNumber.textContent = index;
        
        // Event name with number
        const headerTitle = document.createElement('div');
        headerTitle.style.display = 'flex';
        headerTitle.style.alignItems = 'center';
        headerTitle.appendChild(eventNumber);
        
        const headerText = document.createElement('span');
        headerText.style.fontWeight = 'bold';
        headerText.style.fontSize = '14px';
        headerText.textContent = eventName;
        headerTitle.appendChild(headerText);
        
        // Odd badge
        const oddBadge = document.createElement('span');
        oddBadge.style.backgroundColor = oddColor;
        oddBadge.style.color = 'white';
        oddBadge.style.padding = '2px 6px';
        oddBadge.style.borderRadius = '4px';
        oddBadge.style.fontSize = '11px';
        oddBadge.textContent = oddValue;
        
        // Add elements to header
        cardHeader.appendChild(headerTitle);
        cardHeader.appendChild(oddBadge);
        card.appendChild(cardHeader);
        
        // Create card body
        const cardBody = document.createElement('div');
        cardBody.style.padding = '10px 12px';
        cardBody.style.fontSize = '13px';
        
        // Create two-column layout
        const rowDiv = document.createElement('div');
        rowDiv.style.display = 'grid';
        rowDiv.style.gridTemplateColumns = 'repeat(2, 1fr)';
        rowDiv.style.gap = '8px';
        
        // Column 1: Stake and Date
        const col1 = document.createElement('div');
        
        const stakeInfo = document.createElement('p');
        stakeInfo.style.marginBottom = '6px';
        stakeInfo.innerHTML = `<strong><i class="fas fa-users mr-1"></i> Aposta:</strong> ${displayName}`;
        col1.appendChild(stakeInfo);
        
        const dateInfo = document.createElement('p');
        dateInfo.style.marginBottom = '6px';
        dateInfo.innerHTML = `<strong><i class="far fa-calendar-alt mr-1"></i> Data:</strong> ${formattedDate}`;
        col1.appendChild(dateInfo);
        
        // Column 2: Tournament and Live status
        const col2 = document.createElement('div');
        
        const tournamentInfo = document.createElement('p');
        tournamentInfo.style.marginBottom = '6px';
        tournamentInfo.innerHTML = `<strong><i class="fas fa-trophy mr-1"></i> Torneio:</strong> ${tournamentName}`;
        col2.appendChild(tournamentInfo);
        
        const liveInfo = document.createElement('p');
        liveInfo.style.marginBottom = '6px';
        liveInfo.innerHTML = `<strong><i class="fas fa-broadcast-tower mr-1"></i> Ao vivo:</strong> ${isLive ? 'Sim' : 'Não'}`;
        col2.appendChild(liveInfo);
        
        // Add columns to the row
        rowDiv.appendChild(col1);
        rowDiv.appendChild(col2);
        
        // Add row to card body
        cardBody.appendChild(rowDiv);
        
        // Add card body to the card
        card.appendChild(cardBody);
        
        return card;
    }
    
    function resetModalValues() {
        // Reset all text fields to prevent null issues
        const elements = [
            'betAmount', 'maxWinAmount', 'receivedAmount', 'betStatus',
            'factorTotal', 'betType', 'eventName', 'eventDate',
            'tournamentName', 'categoryName', 'factor', 'stakeName',
            'isLive', 'isCashout'
        ];
        
        elements.forEach(id => {
            const element = document.getElementById(id);
            if (element) element.textContent = '';
        });
        
        // Clear containers
        document.getElementById('multipleEventsContainer').innerHTML = '';
        // Por padrão, ocultar o container de evento único e a seção de "Informações do Evento" e "Detalhes da Aposta"
        document.getElementById('multipleEventsContainer').style.display = 'none';
    }
    
    function formatCurrency(value) {
        try {
            // Garantir que value seja tratado como número
            let numValue = value;
            
            // Se for string, converter para número
            if (typeof value === 'string') {
                // Remover caracteres não numéricos, exceto ponto e vírgula
                numValue = value.replace(/[^\d.,]/g, '');
                // Substituir vírgulas por pontos para parsing correto
                numValue = numValue.replace(',', '.');
                // Converter para número
                numValue = parseFloat(numValue);
            }
            
            // Se não for um número válido após tentativas, retornar valor zero formatado
            if (isNaN(numValue)) {
                return 'R$ 0,00';
            }
            
            // Formatar o número com 2 casas decimais e separador de milhar
            return 'R$ ' + numValue.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        } catch (e) {
            console.error('Erro ao formatar moeda:', e);
            return 'R$ 0,00';
        }
    }
    
    function getOddBadgeColor(odd) {
        if (odd === 'N/A' || isNaN(odd)) return '#7f8c8d'; // Dark gray for unknown
        const oddNum = parseFloat(odd);
        if (oddNum < 1.5) return '#3498db'; // Light blue for low odds
        if (oddNum < 2.0) return '#2980b9'; // Medium blue for medium-low odds
        if (oddNum < 3.0) return 'var(--primary-color)'; // Primary color for medium odds
        if (oddNum < 5.0) return '#f39c12'; // Orange for high odds
        return '#e74c3c'; // Red for very high odds
    }
    
    // Função auxiliar para diagnosticar problemas
    function diagnosticarAgrupamento() {
        // Contar IDs únicos vs linhas totais
        const idsUnicos = new Set();
        const linhasVisiveis = [];
        const linhasOcultas = [];
        const idsMergeados = {};
        
        // Para cada linha da tabela
        $('table.UHNq- tr.WXGKq').each(function() {
            const row = $(this);
            // Usar o atributo data-transaction-id para verificação
            const transactionId = row.attr('data-transaction-id');
            const isVisible = row.is(':visible');
            
            idsUnicos.add(transactionId);
            
            // Contabilizar IDs que aparecem mais de uma vez
            if (!idsMergeados[transactionId]) {
                idsMergeados[transactionId] = 0;
            }
            idsMergeados[transactionId]++;
            
            if (isVisible) {
                linhasVisiveis.push(transactionId);
            } else {
                linhasOcultas.push(transactionId);
            }
        });
        
        // Identificar IDs que aparecem mais de uma vez
        const idsRepetidos = Object.entries(idsMergeados)
            .filter(([id, count]) => count > 1)
            .map(([id, count]) => ({ id, count }));
        
        
        // Verificar linhas visíveis com IDs repetidos
        const linhasVisiveisComIdsRepetidos = linhasVisiveis.filter(id => 
            idsMergeados[id] > 1
        );
        
        if (linhasVisiveisComIdsRepetidos.length > 0) {
            console.warn('PROBLEMA: IDs repetidos que ainda estão visíveis:');
            console.warn(linhasVisiveisComIdsRepetidos);
            
            // Corrigir automaticamente - ocultar linhas extras
            linhasVisiveisComIdsRepetidos.forEach(id => {
                // Garantir que apenas uma linha com este ID esteja visível
                const linhasComEsteId = $(`tr[data-transaction-id="${id}"]`);
                if (linhasComEsteId.length > 1) {
                    
                    let manterVisivel = false;
                    
                    linhasComEsteId.each(function(index) {
                        const row = $(this);
                        if (!manterVisivel) {
                            // Manter apenas a primeira linha visível
                            row.show();
                            row.css('display', 'table-row');
                            manterVisivel = true;
                        } else {
                            // Ocultar as demais
                            row.hide();
                            row.css('display', 'none');
                            row.attr('style', 'display: none !important');
                        }
                    });
                }
            });
        }
        
        if (linhasVisiveis.length > idsUnicos.size) {
            console.warn('ALERTA: Há mais linhas visíveis que IDs únicos!');
        }
        
        if (linhasOcultas.length === 0 && Object.values(idsMergeados).some(count => count > 1)) {
            console.warn('ALERTA: Não há linhas ocultas, mas existem IDs repetidos!');
        }
        
        return {
            idsUnicos: idsUnicos.size,
            linhasVisiveis: linhasVisiveis.length,
            linhasOcultas: linhasOcultas.length,
            idsRepetidos: idsRepetidos
        };
    }
    
    // Executar diagnóstico após a inicialização e tentar corrigir problemas
    setTimeout(function() {
        const resultado = diagnosticarAgrupamento();
        
        // Se ainda houver problemas, tentar uma abordagem mais direta
        if (resultado.linhasVisiveis > resultado.idsUnicos || 
            (resultado.idsRepetidos.length > 0 && resultado.linhasOcultas === 0)) {
            
            console.warn('Problemas persistentes detectados, tentando abordagem direta...');
            
            // Abordagem direta: manter apenas uma linha para cada ID
            const idsVistos = {};
            
            $('table.UHNq- tr.WXGKq').each(function() {
                const row = $(this);
                const transactionId = row.attr('data-transaction-id') || row.find('[data-name="id"]').text().trim();
                
                if (!idsVistos[transactionId]) {
                    // Primeira vez que vemos este ID, marcar como visto e manter visível
                    idsVistos[transactionId] = true;
                    row.show();
                    row.css('display', 'table-row');
                } else {
                    // ID repetido, ocultar
                    row.hide();
                    row.css('display', 'none');
                    row.attr('style', 'display: none !important');
                }
            });
            
            // Executar diagnóstico novamente para verificar se o problema foi resolvido
            setTimeout(diagnosticarAgrupamento, 200);
        }
    }, 500);

    // Adicionar MutationObserver para garantir que o agrupamento seja mantido
    function setupMutationObserver() {
        // Selecionar o elemento da tabela a ser observado
        const targetNode = document.querySelector('table.UHNq-');
        if (!targetNode) return;
        
        // Configurações do observer
        const config = { childList: true, subtree: true };
        
        // Callback a ser executado quando uma mutação for observada
        const callback = function(mutationsList, observer) {
            for (const mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    // Se algum nó foi adicionado à tabela, reagrupar
                    if (mutation.addedNodes.length > 0) {
                        // Dar um pequeno delay para garantir que o DOM esteja estabilizado
                        setTimeout(function() {
                            // Antes de reagrupar, verificar se os atributos data-transaction-id estão presentes
                            $('table.UHNq- tr.WXGKq').each(function() {
                                const row = $(this);
                                if (!row.attr('data-transaction-id')) {
                                    // Se não tiver o atributo data-transaction-id, tentar obter do botão de visualização
                                    const btn = row.find('.ver-aposta');
                                    if (btn.length && btn.attr('data-transaction-id')) {
                                        row.attr('data-transaction-id', btn.attr('data-transaction-id'));
                                    } 
                                    // Se ainda não tiver o atributo, usar o campo id como fallback
                                    else {
                                        const id = row.find('[data-name="id"]').text().trim();
                                        row.attr('data-transaction-id', id);
                                    }
                                }
                            });
                            
                            // Reexecutar o agrupamento
                            mergeTransactionsWithSameId();
                            
                            // Verificar se resolveu o problema
                            diagnosticarAgrupamento();
                        }, 100);
                        break;
                    }
                }
            }
        };
        
        // Criar um observer com o callback e as opções
        const observer = new MutationObserver(callback);
        
        // Iniciar a observação
        observer.observe(targetNode, config);
        
        
        return observer;
    }
    
    // Configurar o MutationObserver após a inicialização
    setTimeout(setupMutationObserver, 800);

    // Função para forçar o agrupamento
    function forcarAgrupamento() {
        // Abordagem direta: manter apenas uma linha para cada ID
        const idsVistos = {};
        
        $('table.UHNq- tr.WXGKq').each(function() {
            const row = $(this);
            // Usar o atributo data-transaction-id em vez de id
            const transactionId = row.attr('data-transaction-id');
            
            if (!idsVistos[transactionId]) {
                // Primeira vez que vemos este ID, marcar como visto e manter visível
                idsVistos[transactionId] = true;
                row.show();
                row.css('display', 'table-row');
                row.removeClass('hidden-by-merge');
                row.addClass('merged-main-row');
            } else {
                // ID repetido, ocultar
                row.hide();
                row.css('display', 'none');
                row.attr('style', 'display: none !important');
                row.addClass('hidden-by-merge');
                row.removeClass('merged-main-row');
            }
        });
        
        return Object.keys(idsVistos).length;
    }
    
    // Adicionar na paginação um último recurso de agrupamento por força bruta
    $(document).on('click', '#history-pagination a.page-link', function(e) {
        // Após reagrupar normalmente, ainda tentar um agrupamento por força bruta
        setTimeout(function() {
            const resultado = diagnosticarAgrupamento();
            if (resultado.linhasVisiveis > resultado.idsUnicos) {
                const linhasAgrupadas = forcarAgrupamento();
                
                // Verificar novamente
                setTimeout(diagnosticarAgrupamento, 200);
            }
        }, 600);
    });

    function processSingleBet(bet) {
        if (!bet) return;
        
        // Instead of showing the old single event container, we'll use the multiple events container
        document.getElementById('multipleEventsContainer').style.display = 'block';
        
        // Clear previous content only once
        const container = document.getElementById('multipleEventsContainer');
        container.innerHTML = '';
        
        // Get cashout status from the hidden isCashout element
        const hasCashout = document.getElementById('isCashout').innerHTML.includes('Sim');
        
        // Verificar se temos dados do betslip para retorno potencial e odds
        let retornoPotencial = 'R$ 0,00';
        if (document.getElementById('maxWinAmount').textContent) {
            retornoPotencial = document.getElementById('maxWinAmount').textContent;
        } else if (bet.MaxWinAmount) {
            retornoPotencial = formatCurrency(bet.MaxWinAmount);
        } else if (bet.PotentialWin) {
            retornoPotencial = formatCurrency(bet.PotentialWin);
        } else if (bet.ExpectedWin) {
            retornoPotencial = formatCurrency(bet.ExpectedWin);
        }
        
        // Verificar odds
        let oddValue = bet.Factor || bet.Odd || 'N/A';
        if (document.getElementById('factorTotal').textContent && 
            document.getElementById('factorTotal').textContent !== 'N/A') {
            // Extrair apenas o valor numérico
            const textContent = document.getElementById('factorTotal').textContent;
            const match = textContent.match(/\d+(\.\d+)?/);
            if (match) {
                oddValue = match[0];
            }
        }
        
        // Add header for single bet with cashout information - similar to multiple bets header
        const header = document.createElement('div');
        header.className = 'multiple-bet-header';
        header.style.marginBottom = '15px';
        header.style.padding = '10px 15px';
        header.style.backgroundColor = '#323637';
        header.style.borderRadius = '8px';
        header.style.display = 'flex';
        header.style.justifyContent = 'space-between';
        header.style.alignItems = 'center';
        
        // Create header content with title and cashout - note "Aposta Simples" instead of "Aposta Múltipla"
        header.innerHTML = `
            <div>
                <h5 style="font-weight: 600; margin-bottom: 0; color: white;">
                    <i class="fas fa-ticket-alt mr-2"></i>Aposta Simples
                </h5>
            </div>
            <div>
                <span style="display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 12px; color: white; background-color: ${hasCashout ? '#8bc34a' : '#7f8c8d'};">
                    <i class="fas fa-exchange-alt mr-1"></i> Cashout: ${hasCashout ? 'Sim' : 'Não'}
                </span>
            </div>
        `;
        
        container.appendChild(header);
        
        // Create scrollable container for the card - same as in multiple bets
        const cardsContainer = document.createElement('div');
        cardsContainer.className = 'multiple-bet-cards';
        cardsContainer.style.maxHeight = '350px';
        cardsContainer.style.overflowY = 'auto';
        cardsContainer.style.display = 'grid';
        cardsContainer.style.gridTemplateColumns = 'repeat(1, 1fr)';
        cardsContainer.style.gap = '10px';
        cardsContainer.style.padding = '5px';
        
        // Create a single event card using the same createEventCard function
        const card = createEventCard(bet, 1);
        cardsContainer.appendChild(card);
        
        // Add the card container to the main container
        container.appendChild(cardsContainer);
    }
    
    // Função para traduzir termos de apostas do inglês para português
    function translateBetTerms(term) {
        // Dicionário de traduções
        const translations = {
            // Tipos de apostas Total
            'Total: Over': 'Gols Mais de',
            'Total: Under': 'Gols Menos de',
            'Total Over': 'Total Acima',
            'Total Under': 'Total Abaixo',
            'Total Goals Over': 'Total de Gols Acima',
            'Total Goals Under': 'Total de Gols Abaixo',
            'Total Points Over': 'Total de Pontos Acima',
            'Total Points Under': 'Total de Pontos Abaixo',
            'Over/Under': 'Acima/Abaixo',
            
            // Tipos de apostas 1X2
            'Win1': 'Vitória Casa',
            'Win2': 'Vitória Visitante',
            'Draw': 'Empate',
            'X': 'Empate',
            '1': 'Casa',
            '2': 'Visitante',
            '1X2': '1X2',
            'Match Result': 'Resultado da Partida',
            'Match Winner': 'Vencedor da Partida',
            
            // Tipos de apostas handicap
            'Handicap 1': 'Handicap Casa',
            'Handicap 2': 'Handicap Visitante',
            'Handicap X': 'Handicap Empate',
            'Asian Handicap': 'Handicap Asiático',
            'European Handicap': 'Handicap Europeu',
            
            // Tipos de apostas ambos marcam
            'Both Teams To Score': 'Ambos Marcam',
            'Both Teams To Score: Yes': 'Ambos Marcam: Sim',
            'Both Teams To Score: No': 'Ambos Marcam: Não',
            'BTTS': 'Ambos Marcam',
            'BTTS: Yes': 'Ambos Marcam: Sim',
            'BTTS: No': 'Ambos Marcam: Não',
            'GG': 'Ambos Marcam',
            'NG': 'Ambos Não Marcam',
            
            // Escanteios/Corners
            'Corner': 'Escanteio',
            'Corners': 'Escanteios',
            'Total Corners': 'Total de Escanteios',
            'Corners Over': 'Escanteios Acima',
            'Corners Under': 'Escanteios Abaixo',
            
            // Cartões
            'Card': 'Cartão',
            'Cards': 'Cartões',
            'Yellow Card': 'Cartão Amarelo',
            'Yellow Cards': 'Cartões Amarelos',
            'Red Card': 'Cartão Vermelho',
            'Red Cards': 'Cartões Vermelhos',
            'Total Cards': 'Total de Cartões',
            'Cards Over': 'Cartões Acima',
            'Cards Under': 'Cartões Abaixo',
            
            // Gols
            'Goal': 'Gol',
            'Goals': 'Gols',
            'First Goal': 'Primeiro Gol',
            'Last Goal': 'Último Gol',
            'Goals Over': 'Gols Acima',
            'Goals Under': 'Gols Abaixo',
            'Team Goals': 'Gols da Equipe',
            'Team 1 Goals': 'Gols do Time da Casa',
            'Team 2 Goals': 'Gols do Time Visitante',
            'Exact Goals': 'Gols Exatos',
            'Both Teams Score': 'Ambos Marcam',
            'Clean Sheet': 'Sem Sofrer Gols',
            'Clean Sheet - Home': 'Casa Sem Sofrer Gols',
            'Clean Sheet - Away': 'Visitante Sem Sofrer Gols',
            
            // Resultados parciais
            'Half Time Result': 'Resultado 1º Tempo',
            'Half Time/Full Time': '1º Tempo/Resultado Final',
            'First Half': 'Primeiro Tempo',
            'Second Half': 'Segundo Tempo',
            '1st Half': 'Primeiro Tempo',
            '2nd Half': 'Segundo Tempo',
            'HT': '1º Tempo',
            'FT': 'Tempo Completo',
            'HT/FT': '1º Tempo/Final',
            
            // Double Chance (Dupla Possibilidade)
            'Double Chance': 'Dupla Possibilidade',
            'Double Chance 1X': 'Dupla Possibilidade 1X',
            'Double Chance X2': 'Dupla Possibilidade X2',
            'Double Chance 12': 'Dupla Possibilidade 12',
            '1X': 'Casa ou Empate',
            'X2': 'Empate ou Visitante',
            '12': 'Casa ou Visitante',
            
            // Odd/Even (Par/Ímpar)
            'Odd/Even': 'Ímpar/Par',
            'Odd': 'Ímpar',
            'Even': 'Par',
            'Goals Odd': 'Gols Ímpar',
            'Goals Even': 'Gols Par',
            
            // Marcar/Não Marcar
            'To Score': 'Marcar Gol',
            'Not To Score': 'Não Marcar Gol',
            'Player To Score': 'Jogador Marcar Gol',
            'Anytime Goalscorer': 'Marcar a Qualquer Momento',
            'First Goalscorer': 'Primeiro Marcador',
            'Last Goalscorer': 'Último Marcador',
            
            // Empate Anula
            'Draw No Bet': 'Empate Anula',
            'DNB': 'Empate Anula',
            'Draw No Bet 1': 'Empate Anula - Casa',
            'Draw No Bet 2': 'Empate Anula - Visitante',
            
            // Resultados Exatos
            'Correct Score': 'Placar Exato',
            'Exact Score': 'Placar Exato',
            
            // Tipos de Apostas
            'Simple Bet': 'Aposta Simples',
            'Multiple Bet': 'Aposta Múltipla',
            'Simples': 'Aposta Simples',
            'Múltipla': 'Aposta Múltipla',
            'System Bet': 'Aposta Sistema',
            
            // Outros termos comuns
            'Home': 'Casa',
            'Away': 'Visitante',
            'Over': 'Acima',
            'Under': 'Abaixo',
            'Yes': 'Sim',
            'No': 'Não',
            'Win': 'Vitória',
            'Loss': 'Derrota',
            'Winner': 'Vencedor',
            'Loser': 'Perdedor',
            'To Win': 'Para Vencer',
            'To Qualify': 'Para Classificar',
            'To Lift The Cup': 'Para Levantar a Taça',
            'Winning Margin': 'Margem de Vitória',
            'Method of Victory': 'Método de Vitória',
            'Race To': 'Primeiro a Alcançar',
            'Outright': 'Vencedor Final',
            'Moneyline': 'Resultado Final',
            'Spread': 'Handicap',
            'Tie': 'Empate',
            'Push': 'Empate',
            'Void': 'Anulado',
            'Cancelled': 'Cancelado',
            'Overtime': 'Prorrogação',
            'Penalty Shootout': 'Disputa de Pênaltis',
            'Extra Time': 'Tempo Extra',
            'Live': 'Ao Vivo',
            'Pre Match': 'Pré-Jogo',
            'In Play': 'Ao Vivo',
            'Cashout': 'Encerrar Aposta'
        };
        
        // Se o termo contém parênteses, vamos traduzir separadamente
        if (term && term.includes('(') && term.includes(')')) {
            const parts = term.split('(');
            const mainPart = parts[0].trim();
            const valuePart = parts[1].replace(')', '').trim();
            
            // Verificar se o termo principal existe no dicionário
            const translatedMain = translations[mainPart] || mainPart;
            
            // Retornar com o formato original
            return `${translatedMain} (${valuePart})`;
        }
        
        // Retorna a tradução se existir, ou o próprio termo
        return translations[term] || term;
    }
    
    // Função para formatar datas de eventos
    function formatBetDate(eventDate) {
        if (!eventDate || eventDate === 'N/A') return 'N/A';
        
        try {
            // Criar objeto Date - JavaScript interpreta automaticamente o timezone da string
            // Se não tiver timezone, será interpretado como hora local
            let date;
            
            // Se for um timestamp numérico
            if (typeof eventDate === 'number') {
                date = new Date(eventDate);
            }
            // Se for uma string
            else if (typeof eventDate === 'string') {
                // Criar objeto Date mantendo o timezone original se presente
                date = new Date(eventDate);
                
                // Se a data não tem timezone explícito na string, assumir UTC
                if (isNaN(date.getTime())) {
                    // Tentar adicionar Z (UTC) se não tiver timezone
                    if (!eventDate.includes('Z') && !eventDate.match(/[+-]\d{2}:?\d{2}$/)) {
                        date = new Date(eventDate + 'Z');
                    } else {
                        return 'Data inválida';
                    }
                }
            } else {
                return 'Data inválida';
            }

            if (isNaN(date.getTime())) {
                return 'Data inválida';
            }

            // Usar Intl.DateTimeFormat para converter diretamente para o timezone de Brasília
            // Isso é mais confiável do que manipulação manual
            const options = {
                timeZone: 'America/Sao_Paulo',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            };

            const formatter = new Intl.DateTimeFormat('pt-BR', options);
            const parts = formatter.formatToParts(date);
            
            const dia = parts.find(p => p.type === 'day').value;
            const mes = parts.find(p => p.type === 'month').value;
            const ano = parts.find(p => p.type === 'year').value;
            const hora = parts.find(p => p.type === 'hour').value;
            const minuto = parts.find(p => p.type === 'minute').value;

            return `${dia}/${mes}/${ano} - ${hora}:${minuto}hrs (Brasilia)`;
        } catch (e) {
            console.error('Erro ao formatar data:', e);
            // Fallback: tentar método alternativo se Intl não funcionar
            try {
                let date;
                if (typeof eventDate === 'number') {
                    date = new Date(eventDate);
                } else if (typeof eventDate === 'string') {
                    date = new Date(eventDate);
                    if (isNaN(date.getTime())) {
                        if (!eventDate.includes('Z') && !eventDate.match(/[+-]\d{2}:?\d{2}$/)) {
                            date = new Date(eventDate + 'Z');
                        } else {
                            return 'Data inválida';
                        }
                    }
                } else {
                    return 'Data inválida';
                }
                
                if (isNaN(date.getTime())) return 'Data inválida';
                
                // Calcular offset manualmente: UTC para GMT-3
                // Brasília está 3 horas atrás de UTC, então subtraímos 3 horas
                const utcTime = date.getTime() + (date.getTimezoneOffset() * 60000);
                const brasiliaTime = new Date(utcTime - (3 * 60 * 60000));
                
                const dia = brasiliaTime.getDate().toString().padStart(2, '0');
                const mes = (brasiliaTime.getMonth() + 1).toString().padStart(2, '0');
                const ano = brasiliaTime.getFullYear();
                const hora = brasiliaTime.getHours().toString().padStart(2, '0');
                const minuto = brasiliaTime.getMinutes().toString().padStart(2, '0');
                
                return `${dia}/${mes}/${ano} - ${hora}:${minuto}hrs (Brasilia)`;
            } catch (e2) {
                console.error('Erro no fallback de conversão de data:', e2);
                return 'Erro na data';
            }
        }
    }
    
    // Função auxiliar para converter AM/PM para formato 24h
    function convertTo24Hour(hours, period) {
        // Converter para número para garantir
        hours = parseInt(hours, 10);
        
        // Se for PM e não for meio-dia, adicionar 12 horas
        if (period === 'PM' && hours < 12) {
            return hours + 12;
        }
        // Se for AM e for meia-noite (12 AM), converter para 0
        else if (period === 'AM' && hours === 12) {
            return 0;
        }
        // Outros casos, manter o valor
        return hours;
    }
});