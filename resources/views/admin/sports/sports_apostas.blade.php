@extends('admin.layouts.app')
@section('content')
    @php
        use Carbon\Carbon;

        $a = request()->input('aff', '');
        $b = request()->input('di', '');
        $c = request()->input('df', '');
        $statusFiltro = request()->input('statusFiltro', '');

        $dataInicial = $b;
        $dataFinal = $c;
        $nomeUsuario = $a;
    @endphp

    <style>
        #datatable-apostas_processing {
            color: white;
        }
    </style>

    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Sportsbook</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Histórico de Apostas</li>
                    </ol>
                </nav>
            </div>

            <div class="row layout-top-spacing">
                <!-- Cards de Estatísticas -->
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-card-four">
                                <div class="widget-content">
                                    <div class="w-header">
                                        <div class="w-info">
                                            <h6 class="value" id="bilhetes-abertos">0</h6>
                                            <p class="text-muted">Bilhetes em Abertos</p>
                                        </div>
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock text-warning">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12,6 12,12 16,14"></polyline>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-content">
                                        <div class="w-info">
                                            <p class="value" id="valor-apostas-abertas">R$ 0,00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-card-four">
                                <div class="widget-content">
                                    <div class="w-header">
                                        <div class="w-info">
                                            <h6 class="value" id="bilhetes-finalizados">0</h6>
                                            <p class="text-muted">Bilhetes Finalizados</p>
                                        </div>
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle text-success">
                                                <path d="m9 12 2 2 4-4"></path>
                                                <circle cx="12" cy="12" r="10"></circle>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-content">
                                        <div class="w-info">
                                            <p class="value" id="valor-apostas-finalizadas">R$ 0,00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-card-four">
                                <div class="widget-content">
                                    <div class="w-header">
                                        <div class="w-info">
                                            <h6 class="value" id="bilhetes-premiados">0</h6>
                                            <p class="text-muted">Bilhetes Premiados</p>
                                        </div>
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award text-primary">
                                                <circle cx="12" cy="8" r="7"></circle>
                                                <polyline points="8.21,13.89 7,23 12,20 17,23 15.79,13.88"></polyline>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="w-content">
                                        <div class="w-info">
                                            <p class="value" id="valor-premios">R$ 0,00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <div class="row" style="margin-bottom: -20px; padding:15px;">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="dataInicial" class="form-label">Data Inicial:</label>
                                            <input type="date" id="dataInicial" name="di" class="form-control filter-input" value="{{$b}}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="dataFinal" class="form-label">Data Final:</label>
                                            <input type="date" id="dataFinal" name="df" class="form-control filter-input" value="{{$c}}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="inputTexto" class="form-label">Nome:</label>
                                            <input type="text" id="nomeUsuario" name="aff" placeholder="Insira o nome do usuário..." value="{{$a}}" class="form-control filter-input">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="statusFiltro" class="form-label">Status dos Bilhetes:</label>
                                            <select id="statusFiltro" name="statusFiltro" class="form-control filter-input">
                                                <option value="" {{ $statusFiltro == '' ? 'selected' : '' }}>Todos</option>
                                                <option value="abertos" {{ $statusFiltro == 'abertos' ? 'selected' : '' }}>Apenas em Abertos</option>
                                                <option value="finalizados" {{ $statusFiltro == 'finalizados' ? 'selected' : '' }}>Apenas Finalizados</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="datatable-apostas" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="datatable-apostas" rowspan="1" colspan="1" aria-label="ID Transação: activate to sort column ascending">ID Transação</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-apostas" rowspan="1" colspan="1" aria-label="Usuário: activate to sort column ascending">Usuário</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-apostas" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Status</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-apostas" rowspan="1" colspan="1" aria-label="Odd: activate to sort column ascending">Odd</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-apostas" rowspan="1" colspan="1" aria-label="Valor: activate to sort column ascending" style="text-align: left;">Valor</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-apostas" rowspan="1" colspan="1" aria-label="Possível Ganho: activate to sort column ascending" style="text-align: left;">Possível Ganho</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-apostas" rowspan="1" colspan="1" aria-label="Data: activate to sort column ascending">Data</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-apostas" rowspan="1" colspan="1" aria-label="Ações: activate to sort column ascending">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Os dados serão preenchidos pelo DataTable -->
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">ID Transação</th>
                                        <th rowspan="1" colspan="1">Usuário</th>
                                        <th rowspan="1" colspan="1">Status</th>
                                        <th rowspan="1" colspan="1">Odd</th>
                                        <th rowspan="1" colspan="1" style="text-align: left;">Valor</th>
                                        <th rowspan="1" colspan="1" style="text-align: left;">Possível Ganho</th>
                                        <th rowspan="1" colspan="1">Data</th>
                                        <th rowspan="1" colspan="1">Ações</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ver Aposta -->
    <div class="modal fade" id="verApostaModal" tabindex="-1" aria-labelledby="verApostaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verApostaModalLabel">Detalhes da Aposta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <!-- Informações principais -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border-0">
                                <div class="card-header bg-success text-white">
                                    Informações Gerais da Aposta
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p><strong>Valor apostado:</strong> <span id="betAmount"></span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Retorno potencial:</strong> <span id="maxWinAmount"></span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Valor recebido:</strong> <span id="receivedAmount"></span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p><strong>Status da aposta:</strong> <span id="betStatus"></span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Odds totais:</strong> <span id="factorTotal"></span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Tipo de aposta:</strong> <span id="betType"></span></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Usuário:</strong> <span id="userName"></span> (ID: <span id="userId"></span>)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contêiner para apostas múltiplas -->
                    <div id="multipleEventsContainer">
                        <!-- Aqui serão inseridos dinamicamente os cards de eventos na aposta múltipla -->
                    </div>

                    <!-- Evento único (usado apenas quando não for múltiplo) -->
                    <div id="singleEventContainer" class="row">
                        <div class="col-md-6">
                            <div class="card border-0 mb-3">
                                <div class="card-header bg-primary text-white">
                                    Informações do Evento
                                </div>
                                <div class="card-body">
                                    <p><strong>Evento:</strong> <span id="eventName"></span></p>
                                    <p><strong>Data/Hora:</strong> <span id="eventDate"></span></p>
                                    <p><strong>Torneio:</strong> <span id="tournamentName"></span></p>
                                    <p><strong>País:</strong> <span id="categoryName"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 mb-3">
                                <div class="card-header bg-info text-white">
                                    Detalhes da Aposta
                                </div>
                                <div class="card-body">
                                    <p><strong>Odd:</strong> <span id="factor"></span></p>
                                    <p><strong>Time apostado:</strong> <span id="stakeName"></span></p>
                                    <p><strong>Ao vivo:</strong> <span id="isLive"></span></p>
                                    <p><strong>Cashout:</strong> <span id="isCashout"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card border-0 mb-3">
                                <div class="card-header bg-info text-white">
                                    Tipo de Aposta
                                </div>
                                <div class="card-body">
                                    <p><span id="betStake"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function () {
                // Configuração para suprimir mensagens de erro do DataTables em console
                $.fn.dataTable.ext.errMode = 'none';

                // Variável para armazenar o timeout da digitação
                var typingTimer;
                var doneTypingInterval = 500; // Tempo em ms para aguardar após a digitação

                // Função para inicializar o datatable com as opções desejadas
                function initDatatable(dataInicial, dataFinal, nomeUsuario, statusFiltro) {
                    var table = $('#datatable-apostas').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: {
                            dataSrc: 'data',
                            url: "{{ route('admin.sports.apostas.data') }}",
                            data: function (d) {
                                d.dataInicial = dataInicial || $('#dataInicial').val();
                                d.dataFinal = dataFinal || $('#dataFinal').val();
                                d.nomeUsuario = nomeUsuario || $('#nomeUsuario').val();
                                d.statusFiltro = statusFiltro || $('#statusFiltro').val();
                            },
                            error: function (xhr, error, thrown) {
                            }
                        },
                        columns: [
                            {data: 'id_transacao', name: 'id_transacao'},
                            {data: 'usuario', name: 'usuario'},
                            {data: 'status', name: 'status'},
                            {data: 'odd', name: 'odd'},
                            {data: 'valor', name: 'valor'},
                            {data: 'possivel_ganho', name: 'possivel_ganho'},
                            {data: 'data', name: 'data'},
                            {data: 'acoes', name: 'acoes'}
                        ],
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
                            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Processando...</span></div>',
                            paginate: {
                                first: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>',
                                previous: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                                next: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                                last: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>'
                            },
                            info: "Mostrando página _PAGE_ de _PAGES_"
                        },
                        dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'B><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                            "<'table-responsive'tr>" +
                            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
                        lengthMenu: [10, 25, 50, 100],
                        pageLength: 20,
                        pagingType: 'full_numbers',
                        buttons: [
                            {
                                extend: 'copy',
                                text: 'Copiar',
                                className: 'btn btn-sm btn-primary'
                            },
                            {
                                extend: 'excel',
                                text: 'Excel',
                                className: 'btn btn-sm btn-primary'
                            },
                            {
                                extend: 'pdf',
                                text: 'PDF',
                                className: 'btn btn-sm btn-primary'
                            },
                            {
                                extend: 'print',
                                text: 'Imprimir',
                                className: 'btn btn-sm btn-primary'
                            }
                        ],
                        drawCallback: function(settings) {
                            // Adicionar classes ao paginador
                            $('#datatable-apostas_paginate').addClass('paging_simple_numbers');
                            $('#datatable-apostas_paginate ul.pagination li').addClass('paginate_button page-item');
                            $('#datatable-apostas_paginate ul.pagination li.previous').attr('id', 'datatable-apostas_previous');
                            $('#datatable-apostas_paginate ul.pagination li.next').attr('id', 'datatable-apostas_next');
                            $('#datatable-apostas_paginate ul.pagination li.first').attr('id', 'datatable-apostas_first');
                            $('#datatable-apostas_paginate ul.pagination li.last').attr('id', 'datatable-apostas_last');
                            $('#datatable-apostas_paginate ul.pagination li a').addClass('page-link');

                            // Substituir o texto dos botões de paginação por ícones SVG
                            $('#datatable-apostas_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                            $('#datatable-apostas_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                            $('#datatable-apostas_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                            $('#datatable-apostas_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');

                            // Após desenhar a tabela, re-inicializar os botões "Ver Aposta"
                            // Isso é necessário porque o DataTables destrói e recria os elementos HTML
                            if (typeof initBetViewButtons === 'function') {
                                initBetViewButtons();
                            }
                        },
                        initComplete: function() {
                            // Adiciona um evento para recarregar a tabela quando ocorrer um erro
                            $('#datatable-apostas').on('error.dt', function(e, settings, techNote, message) {
                            });

                            // Remover campo de busca gerado automaticamente
                            $('.dataTables_filter').remove();
                        }
                    });

                    return table;
                }

                // Inicializar o datatable com os valores dos parâmetros da URL ou data de hoje
                var dataInicial = "{{ $dataInicial }}" || "{{ date('Y-m-d') }}";
                var dataFinal = "{{ $dataFinal }}" || "{{ date('Y-m-d') }}";
                var nomeUsuario = "{{ $nomeUsuario }}";
                var statusFiltro = "{{ $statusFiltro }}";
                
                // Definir valores nos campos se estiverem vazios
                if (!$('#dataInicial').val()) {
                    $('#dataInicial').val(dataInicial);
                }
                if (!$('#dataFinal').val()) {
                    $('#dataFinal').val(dataFinal);
                }
                
                var table = initDatatable(dataInicial, dataFinal, nomeUsuario, statusFiltro);

                // Função para recarregar a tabela com os novos filtros
                function reloadTable() {
                    var dataInicial = $('#dataInicial').val();
                    var dataFinal = $('#dataFinal').val();
                    var nomeUsuario = $('#nomeUsuario').val();
                    var statusFiltro = $('#statusFiltro').val();

                    // Destruir o datatable anterior
                    $('#datatable-apostas').DataTable().destroy();

                    // Reinicializar com os novos parâmetros
                    table = initDatatable(dataInicial, dataFinal, nomeUsuario, statusFiltro);

                    // Carregar estatísticas
                    loadStats();

                    // Atualizar a URL para refletir os novos filtros sem recarregar a página
                    var newUrl = "{{ route('admin.sports.sports_apostas') }}?di=" + dataInicial + "&df=" + dataFinal + "&aff=" + encodeURIComponent(nomeUsuario) + "&statusFiltro=" + encodeURIComponent(statusFiltro);
                    window.history.pushState({}, '', newUrl);
                }

                // Evento para campos de data - aplicar filtro imediatamente ao mudar
                $('.filter-input[type="date"]').on('change', function() {
                    reloadTable();
                });

                // Evento para campo de texto - aplicar filtro após parar de digitar
                $('#nomeUsuario').on('keyup', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(reloadTable, doneTypingInterval);
                });

                $('#nomeUsuario').on('keydown', function() {
                    clearTimeout(typingTimer);
                });

                // Evento para filtro de status
                $('#statusFiltro').on('change', function() {
                    reloadTable();
                });

                // Função para carregar estatísticas
                function loadStats() {
                    var dataInicial = $('#dataInicial').val();
                    var dataFinal = $('#dataFinal').val();
                    var nomeUsuario = $('#nomeUsuario').val();

                    $.ajax({
                        url: "{{ route('admin.sports.apostas.stats') }}",
                        type: 'GET',
                        data: {
                            dataInicial: dataInicial,
                            dataFinal: dataFinal,
                            nomeUsuario: nomeUsuario,
                            statusFiltro: $('#statusFiltro').val()
                        },
                        success: function(response) {
                            $('#bilhetes-abertos').text(response.bilhetes_abertos);
                            $('#bilhetes-finalizados').text(response.bilhetes_finalizados);
                            $('#bilhetes-premiados').text(response.bilhetes_premiados);
                            $('#valor-apostas-abertas').text('R$ ' + response.valor_apostas_abertas);
                            $('#valor-apostas-finalizadas').text('R$ ' + response.valor_apostas_finalizadas);
                            $('#valor-premios').text('R$ ' + response.valor_premios);
                        },
                        error: function() {
                            console.log('Erro ao carregar estatísticas');
                        }
                    });
                }

                // Carregar estatísticas iniciais
                loadStats();
            });
        </script>
    @endpush

@endsection
