document.addEventListener('DOMContentLoaded', function() {
    // Inicialização dos botões de visualização de apostas
    initBetViewButtons();
});

// Função para traduzir termos de apostas do inglês para português
function translateBetTerms(term) {
    // Dicionário de traduções
    const translations = {
        // Tipos de apostas Total
        'Total: Over': 'Total mais de',
        'Total: Under': 'Total menos de',
        // Tipos de apostas 1X2
        'Win1': 'Vitória Casa',
        'Win2': 'Vitória Visitante',
        'Draw': 'Empate',
        'X': 'Empate',
        // Tipos de apostas handicap
        'Handicap 1': 'Handicap Casa',
        'Handicap 2': 'Handicap Visitante',
        'Handicap X': 'Handicap Empate',
        // Tipos de apostas ambos marcam
        'Both Teams To Score: Yes': 'Ambos Marcam: Sim',
        'Both Teams To Score: No': 'Ambos Marcam: Não',
        // Outros termos comuns
        'Home': 'Casa',
        'Away': 'Visitante',
        'Over': 'Acima',
        'Under': 'Abaixo',
        'Yes': 'Sim',
        'No': 'Não',
        'First Half': 'Primeiro Tempo',
        'Second Half': 'Segundo Tempo'
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

// Função global para inicializar os botões de ver aposta
// Isso permite que seja chamado novamente após o DataTable ser redesenhado
function initBetViewButtons() {
    // Manipulação do modal quando o botão "Ver Aposta" é clicado
    const verApostaBtns = document.querySelectorAll('.ver-aposta');

    verApostaBtns.forEach(btn => {
        // Remover event listeners existentes para evitar duplicação
        btn.removeEventListener('click', handleBetViewClick);
        // Adicionar novo event listener
        btn.addEventListener('click', handleBetViewClick);
    });
}

// Função para lidar com o clique no botão "Ver Aposta"
function handleBetViewClick() {
    try {
        // Recuperar o JSON do atributo data-betslip
        let betslipData = this.getAttribute('data-betslip');
        let operation = this.getAttribute('data-operation');
        let amount = this.getAttribute('data-amount');
        let cashout = this.getAttribute('data-cashout');
        let receivedAmount = this.getAttribute('data-received-amount');
        let userId = this.getAttribute('data-user-id');
        let userName = this.getAttribute('data-user-name');

        // Primeiro, vamos limpar todos os valores
        showDefaultValues();

        // Decodificar entidades HTML no betslip (caso esteja escapado com htmlspecialchars)
        if (betslipData) {
            const textarea = document.createElement('textarea');
            textarea.innerHTML = betslipData;
            betslipData = textarea.value;
        }

        // Se não tiver betslip, usar objeto vazio para evitar erros
        if (!betslipData || betslipData.trim() === '') {
            betslipData = '{}';
        }

        // Verificar o formato simplificado de cashout ({"is_cashout":"1"})
        let simpleCashout = false;
        try {
            // Se o betslip é uma string simples de cashout
            if (betslipData && betslipData.includes('is_cashout')) {
                const simpleObj = JSON.parse(betslipData);
                if (simpleObj &&
                    Object.keys(simpleObj).length === 1 &&
                    simpleObj.is_cashout !== undefined) {

                    simpleCashout = simpleObj.is_cashout === "1" || simpleObj.is_cashout === 1;
                    if (simpleCashout) cashout = '1';
                }
            }
        } catch (e) {
            console.error('Erro ao verificar formato simples de cashout:', e);
            console.error('Conteúdo do betslip:', betslipData);
        }

        try {
            // Se for o formato simples de cashout, não precisamos processar o resto do betslip
            let hasCashout = cashout === '1' || simpleCashout;

            // Parsing do betslip (se não for o formato simples)
            let betslipObj = null;
            let bet = null;
            let factor = null;
            let maxWinAmount = null;
            let isMultipleBet = false;
            let multipleEvents = [];
            let betType = "Simples";

            if (!simpleCashout && betslipData) {
                betslipObj = JSON.parse(betslipData);

                // Estrutura específica fornecida: {"bet_stakes": {"Factor": 1.38, "BetStakes": [...]}}
                if (betslipObj && betslipObj.bet_stakes) {
                    // Extrair o Factor principal e MaxWinAmount
                    factor = betslipObj.bet_stakes.Factor;
                    maxWinAmount = betslipObj.bet_stakes.MaxWinAmount;

                    // Verificar se é uma aposta múltipla
                    if (betslipObj.bet_stakes.FullName && betslipObj.bet_stakes.FullName.includes("Multi")) {
                        betType = "Múltipla";
                    } else if (betslipObj.bet_stakes.BetTypeId === "3") {
                        betType = "Múltipla";
                    }

                    // Mostrar o tipo de aposta
                    document.getElementById('betType').textContent = betType;

                    // Verificar se há informação de cashout no betslip
                    if (betslipObj.bet_stakes.IsCashout) {
                        hasCashout = hasCashout || betslipObj.bet_stakes.IsCashout === '1' ||
                            betslipObj.bet_stakes.IsCashout === 1 ||
                            betslipObj.bet_stakes.IsCashout === true;
                    }

                    // Verificar se há cashout no objeto principal
                    if (betslipObj.is_cashout) {
                        hasCashout = hasCashout || betslipObj.is_cashout === '1' ||
                            betslipObj.is_cashout === 1 ||
                            betslipObj.is_cashout === true;
                    }

                    // Acessar o BetStakes array
                    if (betslipObj.bet_stakes.BetStakes && Array.isArray(betslipObj.bet_stakes.BetStakes)) {
                        // Verificar se é uma aposta múltipla (mais de 1 evento)
                        if (betslipObj.bet_stakes.BetStakes.length > 1) {
                            isMultipleBet = true;
                            multipleEvents = betslipObj.bet_stakes.BetStakes;

                            // Exibir o fator total para apostas múltiplas
                            const totalFactor = betslipObj.bet_stakes.Factor || betslipObj.bet_stakes.MaxAllFactor;
                            const oddBadgeClass = getOddBadgeColor(totalFactor);
                            document.getElementById('factorTotal').innerHTML = `<span class="badge ${oddBadgeClass}">${totalFactor}</span>`;
                        } else if (betslipObj.bet_stakes.BetStakes.length === 1) {
                            // Aposta simples com um evento
                            bet = betslipObj.bet_stakes.BetStakes[0];

                            // Exibir odd na seção principal
                            const oddBadgeClass = getOddBadgeColor(bet.Factor || factor);
                            document.getElementById('factorTotal').innerHTML = `<span class="badge ${oddBadgeClass}">${bet.Factor || factor}</span>`;
                        }
                    } else {
                        // Se não tiver BetStakes, tenta usar o próprio bet_stakes
                        bet = betslipObj.bet_stakes;

                        // Exibir odd na seção principal se disponível
                        if (bet.Factor || factor) {
                            const oddBadgeClass = getOddBadgeColor(bet.Factor || factor);
                            document.getElementById('factorTotal').innerHTML = `<span class="badge ${oddBadgeClass}">${bet.Factor || factor}</span>`;
                        }
                    }
                }
                // Formato alternativo
                else if (betslipObj && betslipObj.BetTypeId) {
                    bet = betslipObj;

                    // Exibir odd na seção principal se disponível
                    if (bet.Factor) {
                        const oddBadgeClass = getOddBadgeColor(bet.Factor);
                        document.getElementById('factorTotal').innerHTML = `<span class="badge ${oddBadgeClass}">${bet.Factor}</span>`;
                    }

                    // Verificar se há cashout no objeto
                    if (bet.IsCashout) {
                        hasCashout = hasCashout || bet.IsCashout === '1' ||
                            bet.IsCashout === 1 ||
                            bet.IsCashout === true;
                    }
                }

                // Exibir aposta múltipla ou evento único
                if (isMultipleBet && multipleEvents.length > 0) {
                    // Ocultar o contêiner de evento único
                    document.getElementById('singleEventContainer').style.display = 'none';

                    // Limpar e preparar o contêiner de múltiplos eventos
                    const multipleContainer = document.getElementById('multipleEventsContainer');
                    multipleContainer.innerHTML = '';

                    // Para cada evento na aposta múltipla, criar um card
                    multipleEvents.forEach((eventBet, index) => {
                        // Criar um novo card para cada evento
                        const eventCard = createEventCard(eventBet, index + 1);
                        multipleContainer.appendChild(eventCard);
                    });
                } else {
                    // Mostrar contêiner de evento único
                    document.getElementById('singleEventContainer').style.display = 'flex';
                    document.getElementById('multipleEventsContainer').innerHTML = '';

                    // Se encontramos dados de aposta única, vamos exibi-los
                    if (bet) {
                        // Informações do evento
                        const eventName = bet.EventNameOnly || bet.EventName || 'N/A';
                        document.getElementById('eventName').textContent = eventName;

                        // Formatação da data e hora do evento para o horário de Brasília (GMT-3)
                        let eventDate = 'N/A';
                        try {
                            if (bet.EventDate) {
                                // Usar a função para converter para o formato correto com horário 24h
                                eventDate = convertToCorrectFormat(bet.EventDate);
                            }
                            // Tratamento alternativo se não tiver EventDate
                            else {
                                // Tentar criar uma data a partir do nome do evento ou outra fonte
                                eventDate = 'Data não disponível';
                            }
                        } catch (e) {
                            console.error('Erro ao formatar data:', e);
                        }
                        document.getElementById('eventDate').textContent = eventDate;

                        // Informações do torneio
                        document.getElementById('tournamentName').textContent = bet.TournamentName || 'N/A';
                        document.getElementById('categoryName').textContent = bet.CategoryName || 'N/A';

                        // Informações da aposta - usar o Factor que pode estar em diferentes níveis
                        const oddValue = bet.Factor || factor || 'N/A';

                        const oddBadgeClass = getOddBadgeColor(oddValue);
                        document.getElementById('factor').innerHTML = `<span class="badge ${oddBadgeClass}" style="font-weight: bold; font-size: 1.1em;">Odd: ${oddValue}</span>`;

                        // Nome da aposta (time escolhido)
                        let stakeName = bet.StakeName || bet.StakeTypeName || 'N/A';

                        // Verificar se há informação de FullStake e traduzir
                        if (bet.FullStake) {
                            stakeName = translateBetTerms(bet.FullStake);
                        }

                        let teamName = null;

                        // Verificar se há dados de times e extrair o nome do time apostado
                        const findTeam = (teamsArray, sideValue) => {
                            if (!teamsArray || !Array.isArray(teamsArray)) return null;
                            return teamsArray.find(team => {
                                const side = team.Side !== undefined ? team.Side : team.side;
                                return side === sideValue || side === String(sideValue);
                            });
                        };

                        // Determinar qual lado (Side) foi apostado com base no StakeName
                        const isWin1 = stakeName === 'Win1' || stakeName.includes('Win1');
                        const isWin2 = stakeName === 'Win2' || stakeName.includes('Win2');
                        const sideToFind = isWin1 ? 1 : (isWin2 ? 2 : null);

                        // Tentar diferentes caminhos para encontrar o array Teams
                        let teams = null;
                        if (bet.Teams && Array.isArray(bet.Teams)) {
                            // Direto no objeto bet
                            teams = bet.Teams;
                        }
                        else if (betslipObj && betslipObj.Teams && Array.isArray(betslipObj.Teams)) {
                            // Direto no objeto betslip
                            teams = betslipObj.Teams;
                        }
                        else if (betslipObj && betslipObj.bet_stakes) {
                            // Dentro do objeto bet_stakes
                            if (betslipObj.bet_stakes.Teams && Array.isArray(betslipObj.bet_stakes.Teams)) {
                                teams = betslipObj.bet_stakes.Teams;
                            }
                            // Dentro do BetStakes array
                            else if (betslipObj.bet_stakes.BetStakes && Array.isArray(betslipObj.bet_stakes.BetStakes) &&
                                betslipObj.bet_stakes.BetStakes.length > 0) {
                                const firstBet = betslipObj.bet_stakes.BetStakes[0];
                                if (firstBet.Teams && Array.isArray(firstBet.Teams)) {
                                    teams = firstBet.Teams;
                                }
                            }
                        }

                        // Se encontrou o array Teams e temos um lado para procurar
                        if (teams && sideToFind) {
                            const teamFound = findTeam(teams, sideToFind);

                            if (teamFound) {
                                teamName = teamFound.Name || teamFound.name;
                            }
                        }

                        // Exibir o nome do time se encontrado, ou o stakeName original
                        let displayName = teamName || stakeName;

                        // Adicionar o tipo de aposta (Win1/Win2) como informação adicional se tiver o nome do time
                        if (teamName && (isWin1 || isWin2)) {
                            displayName += ` (${isWin1 ? 'Casa' : 'Visitante'})`;
                        }

                        document.getElementById('stakeName').textContent = displayName;

                        // Verifica se é ao vivo
                        const isLive = (bet.IsLive === '1' || bet.IsLive === 1 || bet.IsLive === true) ? 'Sim' : 'Não';
                        document.getElementById('isLive').textContent = isLive;

                        document.getElementById('betStake').textContent = bet.StakeName;
                    }
                    else {
                        // Se é o formato simples de cashout, mostramos informações limitadas
                        document.getElementById('eventName').textContent = 'Aposta com Cashout';
                        document.getElementById('tournamentName').textContent = 'N/A';
                        document.getElementById('categoryName').textContent = 'N/A';
                        document.getElementById('factor').innerHTML = '<span class="badge badge-light-dark" style="font-weight: bold; font-size: 1.1em;">Odd: N/A</span>';
                        document.getElementById('stakeName').textContent = 'N/A';
                        document.getElementById('isLive').textContent = 'N/A';
                    }
                }
            }
            else {
                // Se é o formato simples de cashout, mostramos informações limitadas
                document.getElementById('singleEventContainer').style.display = 'flex';
                document.getElementById('multipleEventsContainer').innerHTML = '';

                document.getElementById('eventName').textContent = 'Aposta com Cashout';
                document.getElementById('tournamentName').textContent = 'N/A';
                document.getElementById('categoryName').textContent = 'N/A';
                document.getElementById('factor').innerHTML = '<span class="badge badge-light-dark" style="font-weight: bold; font-size: 1.1em;">Odd: N/A</span>';
                document.getElementById('stakeName').textContent = 'N/A';
                document.getElementById('isLive').textContent = 'N/A';
            }

            // Verifica se teve cashout (usando nossa flag consolidada)
            document.getElementById('isCashout').textContent = hasCashout ? 'Sim' : 'Não';

            // Valor apostado (do parâmetro amount)
            const betAmount = parseFloat(amount || 0);
            document.getElementById('betAmount').textContent = 'R$ ' + betAmount.toFixed(2).replace('.', ',');

            // Valor recebido (do parâmetro receivedAmount)
            const recAmount = parseFloat(receivedAmount || 0);
            document.getElementById('receivedAmount').textContent = 'R$ ' + recAmount.toFixed(2).replace('.', ',');

            // Calcula retorno potencial (valor apostado * odd)
            // Usar MaxWinAmount se disponível, senão calcular
            let maxWin;
            if (maxWinAmount) {
                maxWin = parseFloat(maxWinAmount);
            } else {
                const betFactor = parseFloat(factor || 0);
                maxWin = betAmount * betFactor;
            }
            document.getElementById('maxWinAmount').textContent = 'R$ ' + maxWin.toFixed(2).replace('.', ',');

            // Status da aposta baseado na operação
            let status = '';
            switch(operation.toLowerCase()) {
                case 'debit':
                    status = '<span class="badge badge-light-info">Aposta em andamento</span>';
                    break;
                case 'credit':
                    if (hasCashout) {
                        status = '<span class="badge badge-light-primary">Cashout</span>';
                    } else {
                        status = '<span class="badge badge-light-success">Ganhou</span>';
                    }
                    break;
                case 'lose':
                    status = '<span class="badge badge-light-danger">Perdeu</span>';
                    break;
                case 'cancel_debit':
                case 'cancel_credit':
                    status = '<span class="badge badge-light-warning">Cancelada</span>';
                    break;
                default:
                    status = '<span class="badge badge-light-dark">Desconhecido</span>';
            }
            document.getElementById('betStatus').innerHTML = status;

            // Atualizar informações do usuário no modal
            document.getElementById('userName').textContent = userName || 'N/A';
            document.getElementById('userId').textContent = userId || 'N/A';

        } catch (error) {
            console.error('Erro ao processar o betslip:', error);
            console.error('Conteúdo do betslip:', betslipData);
            showDefaultValues();
        }
    } catch (error) {
        console.error('Erro geral ao processar o clique:', error);
        showDefaultValues();
    }
}

// Função para mostrar valores padrão quando não há dados disponíveis
function showDefaultValues() {
    // Resetar containers
    document.getElementById('singleEventContainer').style.display = 'flex';
    document.getElementById('multipleEventsContainer').innerHTML = '';

    // Resetar valores gerais
    document.getElementById('betAmount').textContent = 'N/A';
    document.getElementById('maxWinAmount').textContent = 'N/A';
    document.getElementById('receivedAmount').textContent = 'N/A';
    document.getElementById('betStatus').innerHTML = '<span class="badge badge-light-dark">Desconhecido</span>';
    document.getElementById('factorTotal').innerHTML = '<span class="badge badge-light-dark">N/A</span>';
    document.getElementById('betType').textContent = 'N/A';
    document.getElementById('userName').textContent = 'N/A';
    document.getElementById('userId').textContent = 'N/A';

    // Resetar valores do evento único
    document.getElementById('eventName').textContent = 'Dados não disponíveis';
    document.getElementById('eventDate').textContent = 'N/A';
    document.getElementById('tournamentName').textContent = 'N/A';
    document.getElementById('categoryName').textContent = 'N/A';
    document.getElementById('factor').innerHTML = '<span class="badge badge-light-dark" style="font-weight: bold; font-size: 1.1em;">Odd: N/A</span>';
    document.getElementById('stakeName').textContent = 'N/A';
    document.getElementById('isLive').textContent = 'N/A';
    document.getElementById('isCashout').textContent = 'N/A';
}

// Função para determinar a cor da badge da odd baseada no valor
function getOddBadgeColor(odd) {
    if (odd === 'N/A' || isNaN(odd)) return 'badge-light-dark';
    const oddNum = parseFloat(odd);
    if (oddNum < 1.5) return 'badge-light-info'; // Odd baixa
    if (oddNum < 2.0) return 'badge-light-primary'; // Odd média-baixa
    if (oddNum < 3.0) return 'badge-light-success'; // Odd média
    if (oddNum < 5.0) return 'badge-light-warning'; // Odd alta
    return 'badge-light-danger'; // Odd muito alta
}

// Função para criar um card de evento para apostas múltiplas
function createEventCard(eventBet, index) {
    const card = document.createElement('div');
    card.className = 'row mb-3';

    // Obter informações básicas do evento
    const eventName = eventBet.EventNameOnly || eventBet.EventName || 'N/A';
    const tournamentName = eventBet.TournamentName || 'N/A';
    const categoryName = eventBet.CategoryName || 'N/A';
    const oddValue = eventBet.Factor || 'N/A';
    const oddBadgeClass = getOddBadgeColor(oddValue);

    // Formatação da data do evento
    let eventDate = 'N/A';
    try {
        if (eventBet.EventDate) {
            // Usar a função para converter para o formato correto com horário 24h
            eventDate = convertToCorrectFormat(eventBet.EventDate);
        }
        // Tratamento alternativo se não tiver EventDate
        else {
            // Tentar criar uma data a partir do nome do evento ou outra fonte
            eventDate = 'Data não disponível';
        }
    } catch (e) {
        console.error('Erro ao formatar data:', e);
    }

    // Determinar time apostado
    let stakeName = eventBet.StakeName || eventBet.StakeTypeName || 'N/A';

    // Verificar se há informação de FullStake e traduzir
    if (eventBet.FullStake) {
        stakeName = translateBetTerms(eventBet.FullStake);
    }

    let teamName = null;

    // Verificar se há dados de times
    if (eventBet.Teams && Array.isArray(eventBet.Teams)) {
        const isWin1 = stakeName === 'Win1' || stakeName.includes('Win1');
        const isWin2 = stakeName === 'Win2' || stakeName.includes('Win2');
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

    // Montar nome de exibição
    let displayName = teamName || stakeName;
    if (teamName && (stakeName === 'Win1' || stakeName.includes('Win1'))) {
        displayName += ' (Casa)';
    } else if (teamName && (stakeName === 'Win2' || stakeName.includes('Win2'))) {
        displayName += ' (Visitante)';
    }

    // Verificar se é ao vivo
    const isLive = (eventBet.IsLive === '1' || eventBet.IsLive === 1 || eventBet.IsLive === true) ? 'Sim' : 'Não';

    let betStake = stakeName;

    // Montar HTML do card
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
                            <p><strong>Evento:</strong> ${eventName}</p>
                            <p><strong>Data/Hora:</strong> ${eventDate}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Aposta:</strong> ${displayName}</p>
                            <p><strong>Torneio:</strong> ${tournamentName} (${categoryName})</p>
                            <p><strong>Ao vivo:</strong> ${isLive}</p>
                        </div>
                        <div class="col">
                            <p><strong>Tipo de Aposta:</strong> ${betStake}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    return card;
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

// Função para extrair e formatar uma data string para o formato 24h
function formatDateTo24Hour(dateString) {
    if (!dateString || typeof dateString !== 'string') return dateString;

    try {
        // Verificar se a data contém formato AM/PM explícito
        const amPmMatch = dateString.match(/(\d{1,2}):(\d{2})\s*(am|pm|AM|PM)/i);
        if (amPmMatch) {
            // Extrair horas, minutos e período
            let hours = parseInt(amPmMatch[1], 10);
            const minutes = amPmMatch[2];
            const period = amPmMatch[3].toUpperCase();

            // Converter para formato 24h
            hours = convertTo24Hour(hours, period);

            // Substituir a hora original pela hora convertida e remover o AM/PM
            return dateString.replace(
                amPmMatch[0],
                `${hours.toString().padStart(2, '0')}:${minutes}`
            );
        }

        // Verificar se há algum outro formato de hora que precise ser convertido
        // Por exemplo: "5:30 da tarde" ou similares
        if (dateString.toLowerCase().includes('tarde') ||
            dateString.toLowerCase().includes('noite')) {
            // Procurar por padrão de hora (HH:MM)
            const timeMatch = dateString.match(/(\d{1,2}):(\d{2})/);
            if (timeMatch) {
                let hours = parseInt(timeMatch[1], 10);
                const minutes = timeMatch[2];

                // Se for tarde ou noite e a hora é menor que 12, adicionar 12
                if (hours < 12) {
                    hours += 12;
                }

                // Substituir a hora original pela hora convertida
                return dateString.replace(
                    timeMatch[0],
                    `${hours.toString().padStart(2, '0')}:${minutes}`
                );
            }
        }

        // Verificar se há referência a manhã ou madrugada
        if (dateString.toLowerCase().includes('manhã') ||
            dateString.toLowerCase().includes('madrugada')) {
            // Procurar por padrão de hora (HH:MM)
            const timeMatch = dateString.match(/(\d{1,2}):(\d{2})/);
            if (timeMatch) {
                let hours = parseInt(timeMatch[1], 10);
                const minutes = timeMatch[2];

                // Se for 12 da manhã (meia-noite), converter para 0
                if (hours === 12) {
                    hours = 0;
                }

                // Substituir a hora original pela hora convertida
                return dateString.replace(
                    timeMatch[0],
                    `${hours.toString().padStart(2, '0')}:${minutes}`
                );
            }
        }

        // Processamento para strings de data sem indicadores explícitos AM/PM
        // Se tiver o formato HH:MM e não tiver menção a HR, podemos ter uma conversão implícita
        const timeMatch = dateString.match(/(\d{1,2}):(\d{2})/);
        if (timeMatch) {
            // Extrair a parte de hora
            const hours = parseInt(timeMatch[1], 10);

            // Regra especial para jogos noturnos: se o horário está entre 1:00 e 8:59, provavelmente é noite
            // Se a hora está entre 1 e 8 (inclusive) e não tem texto adicional como "manhã"
            if (hours >= 1 && hours <= 8 &&
                !dateString.toLowerCase().includes('manhã') &&
                !dateString.toLowerCase().includes('madrugada')) {

                // Ajustar para formato 24h (adicionar 12 horas)
                const correctedHours = hours + 12;

                // Substituir a hora original
                return dateString.replace(
                    timeMatch[0],
                    `${correctedHours.toString().padStart(2, '0')}:${timeMatch[2]}`
                );
            }
        }

        // Se não houver formatação específica para converter, retornar a string original
        return dateString;
    } catch (e) {
        console.error('Erro ao formatar hora para 24h:', e);
        return dateString;
    }
}

// Função para converter data do formato original para horário de Brasília com formato 24h
function convertToCorrectFormat(isoDate) {
    try {
        if (!isoDate) return 'N/A';

        // Criar objeto Date - JavaScript interpreta automaticamente o timezone da string
        // Se não tiver timezone, será interpretado como hora local
        let date = new Date(isoDate);

        // Se a data não tem timezone explícito na string, assumir UTC
        if (isNaN(date.getTime())) {
            // Tentar adicionar Z (UTC) se não tiver timezone
            if (!isoDate.includes('Z') && !isoDate.match(/[+-]\d{2}:?\d{2}$/)) {
                date = new Date(isoDate + 'Z');
            } else {
                return 'Data inválida';
            }
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
        console.error('Erro ao converter data:', e);
        // Fallback: tentar método alternativo se Intl não funcionar
        try {
            const date = new Date(isoDate);
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
