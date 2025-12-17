// Theme toggle functionality - versão simplificada
document.addEventListener('DOMContentLoaded', function() {
  const themeToggle = document.querySelector('.theme-toggle');
  
  // Inicializar os ícones baseado no tema atual
  updateThemeIcons(document.body.classList.contains('dark'));
  
  // Adicionar evento de clique ao botão de tema
  themeToggle.addEventListener('click', function() {
    // Verifica se o body tem a classe 'dark'
    if (document.body.classList.contains('dark')) {
      // Se tiver, remove a classe (muda para tema claro)
      document.body.classList.remove('dark');
      // Atualiza localStorage para manter o tema entre páginas
      localStorage.setItem('isDarkMode', 'false');
      // Atualiza ícones
      updateThemeIcons(false);
    } else {
      // Se não tiver, adiciona a classe (muda para tema escuro)
      document.body.classList.add('dark');
      // Atualiza localStorage para manter o tema entre páginas
      localStorage.setItem('isDarkMode', 'true');
      // Atualiza ícones
      updateThemeIcons(true);
    }
  });
  
  // Função auxiliar para mostrar o ícone correto
  function updateThemeIcons(isDarkMode) {
    const darkModeIcon = document.querySelector('.dark-mode');
    const lightModeIcon = document.querySelector('.light-mode');
    
    if (isDarkMode) {
      darkModeIcon.style.display = 'none';
      lightModeIcon.style.display = 'block';
    } else {
      darkModeIcon.style.display = 'block';
      lightModeIcon.style.display = 'none';
    }
  }
  
  // Verificar localStorage para aplicar tema salvo ao carregar a página
  const savedTheme = localStorage.getItem('isDarkMode');
  if (savedTheme === 'true' && !document.body.classList.contains('dark')) {
    document.body.classList.add('dark');
    updateThemeIcons(true);
  } else if (savedTheme === 'false' && document.body.classList.contains('dark')) {
    document.body.classList.remove('dark');
    updateThemeIcons(false);
  }
});

function FilterUserStats(idcliente) {
    document.getElementById('conteudostats').innerHTML = `
<div class="d-flex justify-content-center align-items-center" style="min-height: 150px;">
  <i class="fa fa-spinner fa-spin fa-2x text-muted"></i>
</div>
`;
    let a = document.getElementById('dataInicial1').value;
    let b = document.getElementById('dataFinal1').value;

    $.ajax({
        url: '/rpt-rf-st?id=' + idcliente + '&a=' + a + '&b=' + b,
        type: "GET",
        data: $(this).serialize(),
        success: function (response) {
            document.getElementById("conteudostats").innerHTML = response.html;
        },
        error: function (xhr) {
        }
    });
}

// Declarar variáveis globais
var InfoA = '';
var InfoB = '';
var InfoC = '';
var Tabela;

function TabelasProcess(qual, quem) {
    // Inicializar variáveis com valores padrão
    InfoA = '';
    InfoB = '';
    InfoC = '';
    
    if (qual === "transacoes") {
        let a = document.getElementById('example4');
        if (a){InfoA = document.getElementById('example4').value;}

        let b = document.getElementById('dataInicial4');
        if (b){InfoB = document.getElementById('dataInicial4').value;}

        let c = document.getElementById('dataFinal4');
        if (c){InfoC = document.getElementById('dataFinal4').value;}
    }else if (qual === "historico_jogos") {
        let a = document.getElementById('example3');
        if (a){InfoA = document.getElementById('example3').value;}

        let b = document.getElementById('dataInicial2');
        if (b){InfoB = document.getElementById('dataInicial2').value;}

        let c = document.getElementById('dataFinal2');
        if (c){InfoC = document.getElementById('dataFinal2').value;}
    }else if (qual === "afiliacao_agente") {
        let a = document.getElementById('example4');
        if (a){InfoA = document.getElementById('example4').value;}

        let b = document.getElementById('dataInicial3');
        if (b){InfoB = document.getElementById('dataInicial3').value;}

        let c = document.getElementById('dataFinal3');
        if (c){InfoC = document.getElementById('dataFinal3').value;}
    }

    $('#' + qual).DataTable().destroy();

    $(document).ready(function () {
        var columns;

        if (qual === "transacoes") {
            columns = [
                {data: 'type', name: 'type'},
                {data: 'amount', name: 'amount'},
                {data: 'gateway', name: 'gateway'},
                {data: 'status', name: 'status'},
                {data: 'updated_at', name: 'updated_at'}
            ];
        }else if (qual === "historico_jogos") {
            columns = [
                {data: 'game_name', name: 'game.name'},
                {data: 'amount', name: 'amount'},
                {data: 'action', name: 'action'},
                {data: 'updated_at', name: 'updated_at'}
            ];
        }else if (qual === "afiliacao_agente") {
            columns = [
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'amount', name: 'amount'},
                {data: 'updated_at', name: 'updated_at'}
            ];
        }

        Tabela = $('#' + qual).DataTable({
            processing: false,
            serverSide: true,
            ajax: {
                url: '/rpt-gh-2?d=' + quem + '&tab=' + qual + '&a=' + InfoA + '&b=' + InfoB + '&c=' + InfoC,
                type: 'GET'
            },
            columns: columns,

            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                "<'table-responsive'tr>" +
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Exibindo página _PAGE_ de _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Procurar...",
                "sLengthMenu": "Resultados :  _MENU_",
                sProcessing: "Processando...",
            },

            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 7,
            "ordering": true,
            "searching": false,
            "order": [],

            drawCallback: function () {
                var dtTooltip = document.querySelectorAll('.t-dot');
                for (let index = 0; index < dtTooltip.length; index++) {
                    var tooltip = new bootstrap.Tooltip(dtTooltip[index], {
                        template: '<div class="tooltip status rounded-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
                        title: `${dtTooltip[index].getAttribute('data-original-title')}`
                    })
                }
                
                // Inicializar tooltips Bootstrap para transações manuais
                var manualTooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                manualTooltips.forEach(function(element) {
                    new bootstrap.Tooltip(element, {
                        html: true,
                        placement: 'top'
                    });
                });
                
                $('.dataTables_wrapper table').removeClass('table-striped');
            }
        });
    });
}

TabelasProcessDirect('zero-config', '');

// Guardar temporariamente os dados do campo que será atualizado
let currentFieldData = {
    gameId: null,
    fieldName: null,
    value: null,
    element: null
};

// Função direta para atualizar os campos sem confirmação
function confirmAndUpdateGameField(gameId, fieldName, value, element) {
    // Evitar cliques repetidos
    if (window.isProcessingConfirmation) {
        return;
    }

    window.isProcessingConfirmation = true;

    // Atualizar diretamente, sem confirmação
    updateGameField(gameId, fieldName, value, element);
}

// Função para atualizar o campo
function updateGameField(gameId, fieldName, value, element) {
    // Evitar múltiplas chamadas em sequência
    if (window.isUpdatingField) {
        return;
    }

    window.isUpdatingField = true;

    // Use AJAX para atualizar o campo no banco de dados
    fetch(`/admin/cassino/update-field`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            id: gameId,
            field: fieldName,
            value: value
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostra mensagem de sucesso ou erro dependendo do valor
                if (value == 1) {
                    ToastManager.success(`Campo ativado com sucesso!`);
                } else {
                    ToastManager.error(`Campo desativado!`);
                }
            } else {
                // Mostra mensagem de erro usando o ToastManager
                ToastManager.error('Erro ao atualizar o campo!');

                // Reverte o estado do checkbox se houve falha
                const element = document.getElementById(`${fieldName}${gameId}`);
                if (element) {
                    element.checked = value != 1;
                }
            }
            // Libera flag para permitir novas chamadas
            window.isUpdatingField = false;
            window.isProcessingConfirmation = false;
        })
        .catch(error => {
            console.error('Erro:', error);

            // Mostra mensagem de erro usando o ToastManager
            ToastManager.error('Erro ao atualizar o campo!');

            // Reverte o estado do checkbox se houve falha
            const element = document.getElementById(`${fieldName}${gameId}`);
            if (element) {
                element.checked = value != 1;
            }

            // Libera flag para permitir novas chamadas
            window.isUpdatingField = false;
            window.isProcessingConfirmation = false;
        });
}

// Função para inicializar os eventos dos badges de provedor
function initializeProviderBadgeEvents() {
    document.querySelectorAll('.provider-badge').forEach(function(badge) {
        badge.addEventListener('click', function() {
            const gameId = this.getAttribute('data-game-id');
            const gameName = this.getAttribute('data-game-name');
            const provider = this.getAttribute('data-provider');

            // Preencher os campos do modal
            document.getElementById('game_id').value = gameId;
            document.getElementById('game_name').value = gameName;
            document.getElementById('current_provider').value = provider;

            // Selecionar o provedor atual no dropdown
            const newProviderSelect = document.getElementById('new_provider');
            for (let i = 0; i < newProviderSelect.options.length; i++) {
                if (newProviderSelect.options[i].value === provider) {
                    newProviderSelect.selectedIndex = i;
                    break;
                }
            }

            // Abrir o modal
            const modal = new bootstrap.Modal(document.getElementById('editProviderModal'));
            modal.show();
        });
    });
}

// Função para salvar a mudança de provedor
function saveProviderChange() {
    // Pegar os valores do form
    const gameId = document.getElementById('game_id').value;
    const gameName = document.getElementById('game_name').value;
    const newProvider = document.getElementById('new_provider').value;
    const currentProvider = document.getElementById('current_provider').value;

    // Validar se um novo provedor foi selecionado
    if (!newProvider) {
        ToastManager.error('Selecione um provedor!');
        return;
    }

    // Verificar se o provedor é o mesmo que já está configurado
    if (newProvider === currentProvider) {
        ToastManager.info('O provedor selecionado é o mesmo que já está configurado.');

        // Fechar o modal corretamente
        closeModalProperly();
        return;
    }

    // Mostrar indicador de carregamento
    const toast = ToastManager.info('Atualizando provedor, aguarde...');

    // Verificar se o nome do jogo contém aspas e escapá-las para JSON
    const safeGameName = gameName.replace(/"/g, '\\"');

    // Fazer a requisição para atualizar o campo provider_name
    fetch(`/admin/cassino/update-field`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            id: gameId,
            field: 'provider_name',
            value: newProvider
        })
    })
        .then(response => {
            // Verificar se a resposta foi bem-sucedida
            if (!response.ok) {
                throw new Error(`Erro HTTP: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            // Remover o toast de carregamento
            toast.remove();

            if (data.success) {
                // Mostrar mensagem de sucesso
                ToastManager.success(`Provedor atualizado com sucesso para "${safeGameName}"!`);

                // Atualizar o badge na UI
                document.querySelectorAll(`.provider-badge[data-game-id="${gameId}"]`).forEach(function(badge) {
                    badge.textContent = newProvider;
                    badge.setAttribute('data-provider', newProvider);
                });

                // Atualizar o atributo data-provider da linha para manter os filtros funcionando
                /*const row = document.querySelector(`tr[data-status][data-provider] .provider-badge[data-game-id="${gameId}"]`).closest('tr');
                if (row) {
                    row.setAttribute('data-provider', newProvider);
                }*/

                // Fechar o modal corretamente
                closeModalProperly();

                // Redesenhar a tabela para aplicar filtros atualizados se necessário
                $('#zero-config').DataTable().draw();
            } else {
                // Mostrar mensagem de erro detalhada
                let errorMsg = `Erro ao atualizar o provedor para "${safeGameName}"!`;
                if (data.error) {
                    errorMsg += ` Detalhes: ${data.error}`;
                }
                console.error('Erro na resposta do servidor:', data);
                ToastManager.error(errorMsg);
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);

            // Remover o toast de carregamento
            toast.remove();

            // Mostrar mensagem de erro com detalhes técnicos adicionais
            ToastManager.error(`Erro ao atualizar o provedor para "${safeGameName}". Detalhes técnicos: ${error.message}`);
        });
}

// Função para fechar o modal corretamente
function closeModalProperly() {
    // Fechar o modal usando o Bootstrap API
    const modalElement = document.getElementById('editProviderModal');
    const modalInstance = bootstrap.Modal.getInstance(modalElement);

    if (modalInstance) {
        modalInstance.hide();
    }

    // Remover o backdrop manualmente se ele ainda estiver presente
    setTimeout(() => {
        // Remover qualquer backdrop que possa ter ficado
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
            backdrop.remove();
        });

        // Remover a classe modal-open do body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }, 300);
}

function updateGameDistribution(element, gameId, isChecked, gameName) {
    // Evitar múltiplas chamadas em sequência
    if (window.isUpdatingField) {
        return;
    }

    window.isUpdatingField = true;

    // Mostrar indicador de carregamento ou mensagem
    const toast = ToastManager.info('Atualizando distribuição, aguarde...');

    // Fazer a requisição para atualizar o campo use_playfiver
    fetch(`/admin/cassino/update-field`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            id: gameId,
            field: 'use_playfiver',
            value: isChecked ? 1 : 0
        })
    })
        .then(response => response.json())
        .then(data => {
            // Remover o toast de carregamento
            toast.remove();

            if (data.success) {
                // Mostra mensagem específica para a distribuição com o nome do jogo
                if (isChecked) {
                    ToastManager.success(`PlayFiver ativado com sucesso para "${gameName}"!`);
                    // Atualizar classe visual
                    if (element.closest('.inner-label-toggle')) {
                        element.closest('.inner-label-toggle').classList.add('show');
                    }
                } else {
                    ToastManager.success(`TBS ativado com sucesso para "${gameName}"!`);
                    // Atualizar classe visual
                    if (element.closest('.inner-label-toggle')) {
                        element.closest('.inner-label-toggle').classList.remove('show');
                    }
                }
            } else {
                // Mostra mensagem de erro usando o ToastManager
                ToastManager.error(`Erro ao atualizar a distribuição para "${gameName}"!`);

                // Reverter o estado do toggle se houve falha
                element.checked = !isChecked;
                if (element.closest('.inner-label-toggle')) {
                    if (!isChecked) {
                        element.closest('.inner-label-toggle').classList.add('show');
                    } else {
                        element.closest('.inner-label-toggle').classList.remove('show');
                    }
                }
            }

            // Libera flag para permitir novas chamadas
            window.isUpdatingField = false;
        })
        .catch(error => {
            console.error('Erro:', error);

            // Remover o toast de carregamento
            toast.remove();

            // Mostra mensagem de erro usando o ToastManager
            ToastManager.error(`Erro ao atualizar a distribuição para "${gameName}"!`);

            // Reverter o estado do toggle se houve falha
            element.checked = !isChecked;
            if (element.closest('.inner-label-toggle')) {
                if (!isChecked) {
                    element.closest('.inner-label-toggle').classList.add('show');
                } else {
                    element.closest('.inner-label-toggle').classList.remove('show');
                }
            }

            // Libera flag para permitir novas chamadas
            window.isUpdatingField = false;
        });
}

function handleToggleChange() {
    const isChecked = this.checked;
    const gameId = this.getAttribute('data-game-id');
    const gameName = this.getAttribute('data-game-name');
    const slugPlayfiver = this.getAttribute('data-slug-playfiver');
    const slugTbs = this.getAttribute('data-slug-tbs');

    // Verificar se está tentando ativar o PlayFiver mas não tem slug_playfiver
    if (isChecked && (!slugPlayfiver || slugPlayfiver === 'null' || slugPlayfiver === '')) {
        // Reverter o toggle
        this.checked = false;
        if (this.closest('.inner-label-toggle')) {
            this.closest('.inner-label-toggle').classList.remove('show');
        }

        // Mostrar mensagem de erro
        ToastManager.error(`Não é possível ativar o PlayFiver para "${gameName}". Este jogo não possui na PlayFiver.`);
        return;
    }

    // Verificar se está tentando ativar o TBS mas não tem slug
    if (!isChecked && (!slugTbs || slugTbs === 'null' || slugTbs === '')) {
        // Reverter o toggle
        this.checked = true;
        if (this.closest('.inner-label-toggle')) {
            this.closest('.inner-label-toggle').classList.add('show');
        }

        // Mostrar mensagem de erro
        ToastManager.error(`Não é possível ativar o TBS para "${gameName}". Este jogo não possui na TBS.`);
        return;
    }

    // Alterar a distribuição do jogo
    updateGameDistribution(this, gameId, isChecked, gameName);

    // Atualizar o estilo visual do toggle (já feito no updateGameDistribution em caso de sucesso)

    // Atualizar o atributo de dados para o filtro de distribuição
    const row = $(this).closest('tr');
    row.attr('data-distribution', isChecked ? 'playfiver' : 'tbs');
}

function initializeDistributionToggles() {
    // Remover eventos anteriores para evitar duplicação
    document.querySelectorAll('.switch-input').forEach(function(toggle) {
        toggle.removeEventListener('change', handleToggleChange);
    });

    document.querySelectorAll('.switch-input').forEach(function(toggle) {
        // Aplicar estilo inicial baseado no estado checked
        if (toggle.checked) {
            toggle.closest('.inner-label-toggle').classList.add('show');
        } else {
            toggle.closest('.inner-label-toggle').classList.remove('show');
        }

        // Adicionar evento de change ao toggle
        toggle.addEventListener('change', handleToggleChange);
    });
}

function TabelasProcessDirect(qual, quem) {
    $('#' + qual).DataTable().destroy();

    $(document).ready(function () {
        var columns;

        columns = [
            {data: 'capa', name: 'capa'},
            {data: 'provedor', name: 'provedor'},
            {data: 'nome', name: 'nome'},
            {data: 'distribuicao', name: 'distribuicao'},
            {data: 'exibir_home', name: 'exibir_home'},
            {data: 'destaque', name: 'destaque'},
            {data: 'views', name: 'views'},
            {data: 'ativo', name: 'ativo'}
        ];

        Tabela = $('#' + qual).DataTable({
            processing: false,
            serverSide: true,
            ajax: {
                url: '/rpt-gh-3',
                type: 'GET'
            },
            columns: columns,

            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                "<'table-responsive'tr>" +
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Exibindo página _PAGE_ de _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Procurar...",
                "sLengthMenu": "Resultados :  _MENU_",
                sProcessing: "Processando...",
            },

            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 7,
            "ordering": true,
            "searching": false,
            "order": [],

            drawCallback: function () {
                var dtTooltip = document.querySelectorAll('.t-dot');
                for (let index = 0; index < dtTooltip.length; index++) {
                    var tooltip = new bootstrap.Tooltip(dtTooltip[index], {
                        template: '<div class="tooltip status rounded-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
                        title: `${dtTooltip[index].getAttribute('data-original-title')}`
                    })
                }
                
                // Inicializar tooltips Bootstrap para transações manuais
                var manualTooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                manualTooltips.forEach(function(element) {
                    new bootstrap.Tooltip(element, {
                        html: true,
                        placement: 'top'
                    });
                });
                
                $('.dataTables_wrapper table').removeClass('table-striped');

                

                ModalManager.init();

                // Inicializar os toggles de TBS/PlayFiver
                initializeDistributionToggles();

                initializeProviderBadgeEvents();

                // Configura o evento de clique para o botão de salvar mudança de provedor
                document.getElementById('saveProviderChange').addEventListener('click', saveProviderChange);

                // Adicionar eventos para fechar o modal corretamente
                document.querySelector('#editProviderModal .btn-close').addEventListener('click', closeModalProperly);
                document.querySelector('#editProviderModal .btn-secondary').addEventListener('click', closeModalProperly);

                // Adicionar evento para quando o modal for escondido via ESC ou clique fora
                document.getElementById('editProviderModal').addEventListener('hidden.bs.modal', function() {
                    closeModalProperly();
                });
            }
        });
    });
}