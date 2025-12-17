/**
 * Classe para processar e exibir dados de apostas da Betby
 * 
 * DIFERENÇAS IMPORTANTES DA DIGITAIN:
 * - Campo odds: "odds" (não "factor" como na Digitain)
 * - Formato moeda: 100 = R$ 1,00 (dividir por 100)
 * - Status: usar coluna "status" (não "operation")
 */
class BetbyBetModal {
    constructor() {
        // Inicialização se necessário
        this.initBetViewButtons();
    }

    /**
     * Função principal chamada pelo blade template
     */
    mostrarModal(betslipData, operation, amount, cashout, receivedAmount, userId, userName, provider, operacoesBase64) {
        $('#verApostaModal').modal('show');
        this.processarDadosAposta(betslipData, operation, amount, cashout, receivedAmount, userId, userName, provider, operacoesBase64);
    }

    /**
     * Obter classe CSS para badge da odd
     */
    getOddBadgeColor(odd) {
        const oddValue = parseFloat(odd);
        if (isNaN(oddValue)) return 'badge-secondary';
        
        if (oddValue >= 5.0) return 'badge-danger';
        if (oddValue >= 3.0) return 'badge-warning';
        if (oddValue >= 2.0) return 'badge-info';
        return 'badge-success';
    }

    /**
     * Limpar valores padrão
     */
    showDefaultValues() {
        // Função auxiliar para definir valor seguro
        const setElementValue = (id, value, isHTML = false) => {
            const element = document.getElementById(id);
            if (element) {
                if (isHTML) {
                    element.innerHTML = value;
                } else {
                    element.textContent = value;
                }
            }
        };
        
        setElementValue('betAmount', 'N/A');
        setElementValue('maxWinAmount', 'N/A');
        setElementValue('receivedAmount', 'N/A');
        setElementValue('betStatus', '<span class="badge badge-secondary">N/A</span>', true);
        setElementValue('factorTotal', '<span class="badge badge-secondary">N/A</span>', true);
        setElementValue('betType', 'N/A');
        setElementValue('userName', 'N/A');
        setElementValue('providerName', 'N/A');
        setElementValue('eventName', 'N/A');
        setElementValue('eventDate', 'N/A');
        setElementValue('tournamentName', 'N/A');
        setElementValue('categoryName', 'N/A');
        setElementValue('sportName', 'N/A');
        setElementValue('factor', '<span class="badge badge-secondary">N/A</span>', true);
        setElementValue('stakeName', 'N/A');
        setElementValue('marketName', 'N/A');
        setElementValue('isLive', 'N/A');
        setElementValue('isCashout', 'N/A');
        setElementValue('hasCashout', 'N/A');
        
        // Limpar containers com verificação
        const singleContainer = document.getElementById('singleEventContainer');
        if (singleContainer) {
            singleContainer.style.display = 'none';
        }
        
        const multipleContainer = document.getElementById('multipleEventsContainer');
        if (multipleContainer) {
            multipleContainer.innerHTML = '';
        }
    }

    /**
     * Formatar moeda Betby (dividir por 100)
     */
    formatarMoedaBetby(valor) {
        if (!valor || valor === 0) return 'R$ 0,00';
        const valorReal = parseFloat(valor) / 100;
        return valorReal.toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });
    }

    /**
     * Calcular odds total multiplicando todas as odds
     */
    calcularOddsTotal(bets) {
        if (!bets || bets.length === 0) return 'N/A';
        
        let oddsTotal = 1;
        bets.forEach(bet => {
            const oddValue = parseFloat(bet.odds || 1);
            oddsTotal *= oddValue;
        });
        
        return oddsTotal.toFixed(2);
    }

    /**
     * Agrupar apostas por timestamp e competidores para detectar "Criar Aposta"
     */
    agruparApostasPorScheduled(bets) {
        if (!bets || bets.length === 0) return [];
        
        const grupos = {};
        
        bets.forEach(bet => {
            // Criar chave única combinando scheduled + competitor_name
            const timestamp = bet.scheduled;
            const competidores = Array.isArray(bet.competitor_name) ? 
                               bet.competitor_name.slice().sort().join('|') : 
                               bet.competitor_name || '';
            
            const chaveUnica = `${timestamp}_${competidores}`;
            
            if (!grupos[chaveUnica]) {
                grupos[chaveUnica] = [];
            }
            grupos[chaveUnica].push(bet);
        });
        
        const apostasProcessadas = [];
        
        Object.values(grupos).forEach(grupo => {
            if (grupo.length > 1) {
                // Múltiplas apostas no mesmo evento (mesmo scheduled + mesmo competitor_name) = "Criar Aposta"
                apostasProcessadas.push({
                    isCriarAposta: true,
                    bets: grupo
                });
            } else {
                // Aposta normal
                apostasProcessadas.push(grupo[0]);
            }
        });
        
        return apostasProcessadas;
    }

    /**
     * Formatar array de competidores
     * Mantém a ordem original: primeiro time (casa) vs segundo time (visitante)
     */
    formatarCompetidores(competitorNames) {
        if (!competitorNames || !Array.isArray(competitorNames)) {
            return 'N/A';
        }
        // Sempre manter a ordem original: primeiro é casa, segundo é visitante
        const resultado = competitorNames.join(' vs ');
        return resultado;
    }

    /**
     * Formatar timestamp Unix para data/hora
     */
    formatarDataTimestamp(timestamp) {
        if (!timestamp) return 'N/A';
        
        try {
            const date = new Date(timestamp * 1000);
            return date.toLocaleString('pt-BR');
        } catch (e) {
            return 'N/A';
        }
    }

    /**
     * Formatar status da aposta Betby
     */
    formatarStatusAposta(status) {
        switch (status?.toLowerCase()) {
            case 'pending':
                return '<span class="badge badge-light-warning">Pendente</span>';
            case 'win':
                return '<span class="badge badge-light-success">Ganhou</span>';
            case 'lost':
                return '<span class="badge badge-light-danger">Perdeu</span>';
            case 'debit':
                return '<span class="badge badge-light-info">Aposta</span>';
            case 'credit':
                return '<span class="badge badge-light-success">Pago</span>';
            default:
                return `<span class="badge badge-light-dark">Status: ${status}</span>`;
        }
    }

    /**
     * Função principal para processar dados da aposta Betby
     */
    processarDadosAposta(betslipData, operation, amount, cashout, receivedAmount, userId, userName, provider, operacoesBase64) {
        // Decodificar dados das operações
        let operacoes = [];
        if (operacoesBase64) {
            try {
                const jsonStr = atob(operacoesBase64);
                operacoes = JSON.parse(jsonStr);
            } catch (e) {
                console.error('Erro ao decodificar operações:', e);
            }
        }
        
        // Primeiro, vamos limpar todos os valores
        this.showDefaultValues();
        
        // Preencher histórico de operações
        this.fillOperationsHistory(operacoes);
        
        try {
            let betslipObj = null;
            
            // Verificar se betslipData está vazio (problema comum na Betby)
            if (!betslipData || betslipData === '') {
                // Criar objeto básico com os dados disponíveis
                betslipObj = {
                    amount: amount,
                    operation: operation,
                    provider: provider,
                    user_id: userId,
                    user_name: userName,
                };
                    } else {
                betslipObj = typeof betslipData === 'object' ? betslipData : JSON.parse(betslipData);
            }
            
            const formattedContent = JSON.stringify(betslipObj, null, 2);
            const betslipContentElement = document.getElementById('betslip-content');
            if (betslipContentElement) {
                betslipContentElement.textContent = formattedContent;
            }
            
            // Processar dados específicos da Betby
            this.processarDadosBetby(betslipObj, operation, amount, cashout, receivedAmount, userId, userName, provider);
            
        } catch (e) {
            console.error('Erro ao processar dados da Betby:', e);
            // Mesmo com erro, tentar processar com dados básicos
            const basicObj = {
                amount: amount,
                operation: operation,
                provider: provider,
                user_name: userName
            };
            const betslipContentElement = document.getElementById('betslip-content');
            if (betslipContentElement) {
                betslipContentElement.textContent = 'Dados básicos (betslip vazio): ' + JSON.stringify(basicObj, null, 2);
            }
            this.processarDadosBetby(basicObj, operation, amount, cashout, receivedAmount, userId, userName, provider);
        }
    }

    /**
     * Processar dados específicos da Betby
     */
    processarDadosBetby(betslipObj, operation, amount, cashout, receivedAmount, userId, userName, provider) {
        // Função auxiliar para definir valor seguro
        const setElementValue = (id, value, isHTML = false) => {
            const element = document.getElementById(id);
            if (element) {
                if (isHTML) {
                    element.innerHTML = value;
                } else {
                    element.textContent = value;
                }
            }
        };
        
        // User e provider
        setElementValue('userName', userName || 'N/A');
        setElementValue('providerName', provider || 'Betby');
        
        // Cashout - verificar pela coluna cashout (0=Não, 1=Sim)
        const cashoutText = (cashout === '1' || cashout === 1) ? 'Sim' : 'Não';
        setElementValue('hasCashout', cashoutText);
        
        // Valor apostado - no JSON da Betby: amount ou betslip.sum
        const valorApostado = amount || betslipObj.amount || (betslipObj.betslip ? betslipObj.betslip.sum : 0) || 0;
        // Para Betby, valor já vem em centavos corretos (100 = R$ 1,00)
        setElementValue('betAmount', this.formatarMoedaBetby(valorApostado));
        
        // Valor recebido
        const valorRecebido = receivedAmount || betslipObj.amount_win || 0;
        setElementValue('receivedAmount', this.formatarMoedaBetby(valorRecebido));
        
        // Valor potencial (retorno potencial) - no JSON da Betby: potential_win pode estar no root ou dentro de betslip
        const potentialWin = betslipObj.potential_win || 
                            (betslipObj.betslip ? betslipObj.betslip.potential_win : 0) || 
                            betslipObj.potential_comboboost_win || 0;
        setElementValue('maxWinAmount', this.formatarMoedaBetby(potentialWin));
        
        // Status da aposta - priorizar status da Betby
        let status = operation;
        if (betslipObj.status && betslipObj.status !== 'make') {
            status = betslipObj.status;
        } else if (betslipObj.bet_status) {
            status = betslipObj.bet_status;
        } else if (operation && operation !== 'make' && operation !== 'debit') {
            status = operation;
        }
        setElementValue('betStatus', this.formatarStatusAposta(status), true);
        
        // Processar apostas - estrutura correta da Betby
        let bets = null;
        
        // Betby: buscar em betslipObj.betslip.bets
        if (betslipObj.betslip && betslipObj.betslip.bets && Array.isArray(betslipObj.betslip.bets)) {
            bets = betslipObj.betslip.bets;
        }
        // Fallback: tentar outros caminhos
        else if (betslipObj.bets && Array.isArray(betslipObj.bets)) {
            bets = betslipObj.bets;
        }
        // Se for array direto
        else if (Array.isArray(betslipObj)) {
            bets = betslipObj;
        }
        // Objeto único
        else if (betslipObj.id || betslipObj.event_id) {
            bets = [betslipObj];
        }
        
        if (!bets || bets.length === 0) {
            setElementValue('betType', 'Sem dados de apostas');
            setElementValue('factorTotal', '<span class="badge badge-secondary">N/A</span>', true);
            return;
        }
        
        // Agrupar apostas por scheduled para detectar "Criar Aposta"
        const apostasProcessadas = this.agruparApostasPorScheduled(bets);
        
        // Calcular odds totais - no JSON da Betby: betslip.k ou multiplicar odds individuais
        let oddsTotal = 'N/A';
        if (betslipObj.betslip && betslipObj.betslip.k) {
            // Betby já fornece a odd total em betslip.k
            oddsTotal = parseFloat(betslipObj.betslip.k).toFixed(2);
        } else if (betslipObj.k) {
            // Pode estar diretamente no root do objeto
            oddsTotal = parseFloat(betslipObj.k).toFixed(2);
        } else if (bets && bets.length > 0) {
            // Calcular multiplicando as odds individuais
            oddsTotal = this.calcularOddsTotal(bets);
        }
        
        const oddBadgeClass = this.getOddBadgeColor(oddsTotal);
        setElementValue('factorTotal', `<span class="badge ${oddBadgeClass}">${oddsTotal}</span>`, true);
        
        // Determinar tipo de aposta - no JSON da Betby: betslip.type ou type no root
        let betType = 'Simples';
        let typeStr = null;
        
        if (betslipObj.betslip && betslipObj.betslip.type) {
            typeStr = betslipObj.betslip.type;
        } else if (betslipObj.type) {
            typeStr = betslipObj.type;
        }
        
        if (typeStr) {
            // Contar eventos reais: "Criar Aposta" = 1 evento, eventos normais = 1 cada
            const eventosReais = apostasProcessadas.length;
            
            if (typeStr.includes('/') && typeStr !== '1/1') {
                if (eventosReais === 1 && apostasProcessadas[0].isCriarAposta) {
                    // Só tem uma "Criar Aposta" com múltiplos mercados
                    betType = `Criar Aposta (${apostasProcessadas[0].bets.length} mercados)`;
                } else {
                    // Múltipla normal ou com "Criar Aposta" + outros eventos
                    betType = `Múltipla (${eventosReais} eventos)`;
                }
            } else {
                betType = 'Simples';
            }
        } else if (apostasProcessadas.length > 1) {
            // Contar eventos reais para múltiplas sem typeStr
            const eventosReais = apostasProcessadas.length;
            betType = `Múltipla (${eventosReais} eventos)`;
        } else if (apostasProcessadas.length === 1 && apostasProcessadas[0].isCriarAposta) {
            betType = `Criar Aposta (${apostasProcessadas[0].bets.length} mercados)`;
        }

        setElementValue('betType', betType);
        
        // Processar eventos
        if (apostasProcessadas.length === 1 && !apostasProcessadas[0].isCriarAposta) {
            // Evento único
            const singleContainer = document.getElementById('singleEventContainer');
            if (singleContainer) {
                singleContainer.style.display = 'flex';
            }
            this.processarEventoUnicoBetby(apostasProcessadas[0]);
        } else {
            // Múltiplos eventos
            const singleContainer = document.getElementById('singleEventContainer');
            if (singleContainer) {
                singleContainer.style.display = 'none';
            }
            this.processarEventosMultiplosBetby(apostasProcessadas);
        }
    }

    /**
     * Processar evento único da Betby
     */
    processarEventoUnicoBetby(bet) {
        // Função auxiliar para definir valor seguro
        const setElementValue = (id, value, isHTML = false) => {
            const element = document.getElementById(id);
            if (element) {
                if (isHTML) {
                    element.innerHTML = value;
                } else {
                    element.textContent = value;
                }
            }
        };
        
        // Nome do evento (competidores) - array de nomes
        const eventoNome = this.formatarCompetidores(bet.competitor_name);
        setElementValue('eventName', eventoNome);
        
        // Data do evento - timestamp Unix
        const dataEvento = this.formatarDataTimestamp(bet.scheduled);
        setElementValue('eventDate', dataEvento);
        
        // Torneio
        setElementValue('tournamentName', bet.tournament_name || 'N/A');
        
        // País/Categoria
        setElementValue('categoryName', bet.category_name || 'N/A');
        
        // Esporte
        setElementValue('sportName', bet.sport_name || 'N/A');
        
        // Odd do evento - string no JSON da Betby
        const oddValue = bet.odds || 'N/A';
        const oddBadgeClass = this.getOddBadgeColor(oddValue);
        setElementValue('factor', `<span class="badge ${oddBadgeClass}" style="font-weight: bold; font-size: 1.1em;">Odd: ${oddValue}</span>`, true);
        
        // Resultado apostado
        setElementValue('stakeName', bet.outcome_name || 'N/A');
        
        // Mercado da aposta
        setElementValue('marketName', bet.market_name || 'N/A');
        setElementValue('betStake', bet.market_name || 'N/A');
        
        // Ao vivo - boolean
        const isLive = bet.live ? 'Sim' : 'Não';
        setElementValue('isLive', isLive);
        
        // Cashout (Betby geralmente não tem cashout, definir como Não)
        setElementValue('isCashout', 'Não');
    }

    /**
     * Processar eventos múltiplos da Betby
     */
    processarEventosMultiplosBetby(apostasProcessadas) {
        const multipleContainer = document.getElementById('multipleEventsContainer');
        multipleContainer.innerHTML = '';
        
        apostasProcessadas.forEach((aposta, index) => {
            if (aposta.isCriarAposta) {
                // Criar card para "criar aposta"
                const card = this.criarCardCriarAposta(aposta, index + 1);
                multipleContainer.appendChild(card);
            } else {
                // Criar card para aposta normal
                const card = this.criarCardEventoBetby(aposta, index + 1);
                multipleContainer.appendChild(card);
            }
        });
    }

    /**
     * Criar card para evento individual da Betby
     */
    criarCardEventoBetby(bet, index) {
    const card = document.createElement('div');
    card.className = 'row mb-3';

        // Informações básicas
        const eventoNome = this.formatarCompetidores(bet.competitor_name);
        const torneio = bet.tournament_name || 'N/A';
        const pais = bet.category_name || 'N/A';
        const esporte = bet.sport_name || 'N/A';
        const mercado = bet.market_name || 'N/A';
        // No JSON da Betby o campo é "odds", não "Factor" como na Digitain
        const oddValue = bet.odds || 'N/A';
        const oddBadgeClass = this.getOddBadgeColor(oddValue);
        const dataEvento = this.formatarDataTimestamp(bet.scheduled);
        const stakeName = bet.outcome_name || 'N/A';
        const isLive = bet.live ? 'Sim' : 'Não';
        
        // HTML do card
        card.innerHTML = `
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between">
                        <span>Evento ${index}</span>
                        <span class="badge ${oddBadgeClass}" style="font-weight: bold;">Odd: ${oddValue}</span>
                        </div>
                        <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Evento:</strong> ${eventoNome}</p>
                                <p><strong>Data/Hora:</strong> ${dataEvento}</p>
                                <p><strong>Esporte:</strong> ${esporte}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Aposta:</strong> ${stakeName}</p>
                                <p><strong>Mercado:</strong> ${mercado}</p>
                                <p><strong>Torneio:</strong> ${torneio} (${pais})</p>
                                <p><strong>Ao vivo:</strong> ${isLive}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        return card;
    }

    /**
     * Criar card para "criar aposta" (múltiplas apostas no mesmo evento)
     */
    criarCardCriarAposta(grupoAposta, index) {
        const card = document.createElement('div');
        card.className = 'row mb-3';
        
        const primeiraAposta = grupoAposta.bets[0];
        const eventoNome = this.formatarCompetidores(primeiraAposta.competitor_name);
        const torneio = primeiraAposta.tournament_name || 'N/A';
        const pais = primeiraAposta.category_name || 'N/A';
        const esporte = primeiraAposta.sport_name || 'N/A';
        const dataEvento = this.formatarDataTimestamp(primeiraAposta.scheduled);
        const isLive = primeiraAposta.live ? 'Sim' : 'Não';
        
        // Calcular odd total do grupo
        let oddsTotalGrupo = 1;
        grupoAposta.bets.forEach(bet => {
            // No JSON da Betby o campo é "odds" não "factor"
            oddsTotalGrupo *= parseFloat(bet.odds || 1);
        });
        const oddBadgeClass = this.getOddBadgeColor(oddsTotalGrupo.toFixed(2));
        
        // Criar lista de apostas do grupo com visual compacto
        let listApostas = '';
        grupoAposta.bets.forEach((bet, i) => {
            const oddBadgeClass = this.getOddBadgeColor(bet.odds);
            listApostas += `
                <div class="mb-2 p-2 bg-dark bg-opacity-10 border border-secondary rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <div class="fw-bold text-primary">${bet.outcome_name || 'N/A'}</div>
                            <small class="text-muted">Mercado: ${bet.market_name || 'N/A'}</small>
                        </div>
                        <div class="ms-2">
                            <span class="badge ${oddBadgeClass} px-2 py-1">
                                ${bet.odds || 'N/A'}
                            </span>
                        </div>
                    </div>
                </div>
            `;
        });
        
        // HTML do card
        card.innerHTML = `
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between">
                        <span>Criar Aposta ${index} (${grupoAposta.bets.length} mercados)</span>
                        <span class="badge ${oddBadgeClass}" style="font-weight: bold;">Odd Total: ${oddsTotalGrupo.toFixed(2)}</span>
                        </div>
                        <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Evento:</strong> ${eventoNome}</p>
                                <p><strong>Data/Hora:</strong> ${dataEvento}</p>
                                <p><strong>Esporte:</strong> ${esporte}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Torneio:</strong> ${torneio} (${pais})</p>
                                <p><strong>Ao vivo:</strong> ${isLive}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-layer-group text-primary me-2"></i>
                                    <h6 class="mb-0 fw-bold text-primary">Mercados da Aposta:</h6>
                                </div>
                                <div class="border-start border-primary border-3 ps-2">
                                    ${listApostas}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

    return card;
}

    /**
     * Preencher histórico de operações
     */
    fillOperationsHistory(operacoes) {
        const tbody = document.getElementById('operationHistoryBody');
        if (!tbody) return; // Se não existir a tabela, não fazer nada
        
        tbody.innerHTML = '';
        
        if (!operacoes || operacoes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center">Nenhuma operação encontrada</td></tr>';
            return;
        }
        
        operacoes.forEach(operacao => {
            const row = document.createElement('tr');
            
            // Status formatado para Betby
            let statusFormatado = operacao.operation || operacao.status || 'N/A';
            if (operacao.status && ['win', 'lost', 'pending'].includes(operacao.status)) {
                statusFormatado = this.formatarStatusAposta(operacao.status);
            } else if (operacao.operation) {
                statusFormatado = this.formatarStatusAposta(operacao.operation);
            }
            
            // Valor formatado
            const valor = this.formatarMoedaBetby(operacao.amount || 0);
            
            // Data formatada
            const data = operacao.created_at ? 
                new Date(operacao.created_at).toLocaleString('pt-BR') : 'N/A';
            
            row.innerHTML = `
                <td>${statusFormatado}</td>
                <td>${valor}</td>
                <td>${data}</td>
            `;
            
            tbody.appendChild(row);
        });
    }

    /**
     * Inicialização dos botões de visualização de apostas
     */
    initBetViewButtons() {
        // Aguardar o DOM carregar
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.setupBetViewButtons();
            });
        } else {
            this.setupBetViewButtons();
        }
    }

    /**
     * Configurar botões de ver aposta
     */
    setupBetViewButtons() {
        // Manipulação do modal quando o botão "Ver Aposta" é clicado
        const verApostaBtns = document.querySelectorAll('.ver-aposta-btn');

        verApostaBtns.forEach(btn => {
            // Remover event listeners existentes para evitar duplicação
            btn.removeEventListener('click', this.handleBetViewClick.bind(this));
            // Adicionar novo event listener
            btn.addEventListener('click', this.handleBetViewClick.bind(this));
        });
    }

    /**
     * Lidar com clique no botão "Ver Aposta"
     */
    handleBetViewClick(event) {
        try {
            const btn = event.currentTarget;
            
            // Recuperar dados dos atributos
            let betslipData = btn.getAttribute('data-betslip');
            let operation = btn.getAttribute('data-operation');
            let amount = btn.getAttribute('data-amount');
            let cashout = btn.getAttribute('data-cashout');
            let receivedAmount = btn.getAttribute('data-received-amount');
            let userId = btn.getAttribute('data-user-id') || btn.getAttribute('data-partner-id');
            let userName = btn.getAttribute('data-user-name') || btn.getAttribute('data-partner-name');
            let provider = btn.getAttribute('data-provider') || 'Betby';
            let operacoesBase64 = btn.getAttribute('data-operations');

            // Chamar a função principal para mostrar o modal
            this.mostrarModal(betslipData, operation, amount, cashout, receivedAmount, userId, userName, provider, operacoesBase64);

        } catch (error) {
            console.error('Erro ao processar clique na aposta:', error);
            // Mostrar modal com erro
            if (typeof $ !== 'undefined' && $('#verApostaModal').length) {
                $('#verApostaModal').modal('show');
            }
            this.showDefaultValues();
            
            // Definir valores de erro com verificação
            const eventNameElement = document.getElementById('eventName');
            if (eventNameElement) {
                eventNameElement.textContent = 'Erro ao carregar dados da aposta';
            }
            
            const betStatusElement = document.getElementById('betStatus');
            if (betStatusElement) {
                betStatusElement.innerHTML = '<span class="badge badge-danger">Erro</span>';
            }
        }
    }
}

// Instanciar a classe globalmente (apenas se não existir)
if (typeof window.betbyBetModal === 'undefined') {
    const betbyBetModal = new BetbyBetModal();
    window.betbyBetModal = betbyBetModal;
}

// Função global para inicializar os botões de ver aposta (compatibilidade)
if (typeof window.initBetViewButtons === 'undefined') {
    window.initBetViewButtons = function() {
        if (window.betbyBetModal && window.betbyBetModal.setupBetViewButtons) {
            window.betbyBetModal.setupBetViewButtons();
        }
    };
}

// Aguardar carregamento do DOM
document.addEventListener('DOMContentLoaded', function() {
    initBetViewButtons();
}); 