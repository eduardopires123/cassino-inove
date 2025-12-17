@extends('admin.layouts.app')
@section('content')

    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div class="row" style="margin-bottom: 16px;">
                        <div class="col-md-3">
                            <label for="dataInicial" class="form-label">Data Inicial:</label>
                            <input type="date" id="dataInicial" name="di" class="form-control filter-input" value="{{ $dataInicial }}">
                        </div>
                        <div class="col-md-3">
                            <label for="dataFinal" class="form-label">Data Final:</label>
                            <input type="date" id="dataFinal" name="df" class="form-control filter-input" value="{{ $dataFinal }}">
                        </div>
                        <div class="col-md-3">
                            <label for="filtroDataPredefinida" class="form-label">Período:</label>
                            <select id="filtroDataPredefinida" name="filtroDataPredefinida" class="form-control table-filter">
                                <option value="nenhum">Nenhum</option>
                                <option value="1" selected>Hoje</option>
                                <option value="2">Últimos 2 dias</option>
                                <option value="3">Últimos 3 dias</option>
                                <option value="4">Últimos 4 dias</option>
                                <option value="5">Últimos 5 dias</option>
                                <option value="6">Últimos 6 dias</option>
                                <option value="7">Últimos 7 dias</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div class="alert alert-arrow-left alert-icon-left alert-light-primary alert-dismissible fade show mb-4" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        Resumo Sportsbook (Betby)
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Apostas</h6>
                                </div>
                                <div id="cperiodosb1" class="task-action">Total</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #4CAF50;">R$ <span id="csbin">{{ number_format($totalApostas, 2, ',', '.') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Novo card para apostas em aberto -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Em Aberto</h6>
                                </div>
                                <div id="cperiodosb4" class="task-action">Pendentes</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #FFA500;">R$ <span id="csbpending">{{ number_format($totalApostasAbertas, 2, ',', '.') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12,6 12,12 16,14"></polyline></svg></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Prêmios</h6>
                                </div>
                                <div id="cperiodosb2" class="task-action">Total</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value">R$ <span id="csbout">{{ number_format($totalPremios, 2, ',', '.') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="#ff0000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="m23 18l-9.5-9.5l-5 5L1 6"/><path d="M17 18h6v-6"/></g></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Líquido</h6>
                                </div>
                                <div id="cperiodosb3" class="task-action">Total</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #4CAF50;">R$ <span id="csbliq">{{ number_format($totalLiquido, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de apostas e prêmios -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-three">
                        <div class="widget-heading">
                            <div class="">
                                <h5 class="">Evolução de Apostas e Prêmios (Betby)</h5>
                            </div>
                        </div>

                        <div class="widget-content">
                            <div id="sportsEvolution"></div>
                        </div>
                    </div>
                </div>



                <!-- DataTable de estatísticas detalhadas por esporte -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-three">
                        <div class="widget-heading">
                            <div class="">
                                <h5 class="">Estatísticas Detalhadas por Esporte, País e Campeonato</h5>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row" style="padding: 15px;">
                            <div class="col-md-2">
                                <label for="esporteFiltro" class="form-label">Esporte:</label>
                                <select id="esporteFiltro" name="esporteFiltro" class="form-control table-filter">
                                    <option value="">Todos os Esportes</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="paisFiltro" class="form-label">País/Categoria:</label>
                                <select id="paisFiltro" name="paisFiltro" class="form-control table-filter" disabled>
                                    <option value="">Todos os Países</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="campeonatoFiltro" class="form-label">Campeonato:</label>
                                <select id="campeonatoFiltro" name="campeonatoFiltro" class="form-control table-filter" disabled>
                                    <option value="">Todos os Campeonatos</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable-estatisticas" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="datatable-estatisticas" rowspan="1" colspan="1" aria-label="Esporte - País - Campeonato: activate to sort column ascending">Esporte - País - Campeonato</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-estatisticas" rowspan="1" colspan="1" aria-label="Apostas: activate to sort column ascending">Apostas</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-estatisticas" rowspan="1" colspan="1" aria-label="Prêmios: activate to sort column ascending">Prêmios</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-estatisticas" rowspan="1" colspan="1" aria-label="Lucro: activate to sort column ascending">Lucro</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th rowspan="1" colspan="1">Esporte - País - Campeonato</th>
                                    <th rowspan="1" colspan="1">Apostas</th>
                                    <th rowspan="1" colspan="1">Prêmios</th>
                                    <th rowspan="1" colspan="1">Lucro</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Definir valores iniciais das datas como vazios
            document.getElementById('dataInicial').value = '';
            document.getElementById('dataFinal').value = '';
            
            // Gráfico de evolução inicial
            var chartEvolution;
            var sportsEvolution = {
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: false,
                    }
                },
                colors: ['#1b55e2', '#e7515a', '#8dbf42'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                series: {!! json_encode($series) !!},
                xaxis: {
                    categories: {!! json_encode($labels) !!},
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            return 'R$ ' + val.toLocaleString('pt-BR', { minimumFractionDigits: 2 })
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.3,
                        opacityFrom: 1,
                        opacityTo: 0.8,
                        stops: [0, 100]
                    }
                }
            };

            chartEvolution = new ApexCharts(
                document.querySelector("#sportsEvolution"),
                sportsEvolution
            );
            chartEvolution.render();

            // Função para atualizar cards de estatísticas
            function atualizarCards(dados) {
                document.getElementById('csbin').textContent = dados.totalApostas;
                document.getElementById('csbpending').textContent = dados.totalApostasAbertas;
                document.getElementById('csbout').textContent = dados.totalPremios;
                document.getElementById('csbliq').textContent = dados.totalLiquido;
            }

            // Função para atualizar gráfico
            function atualizarGrafico(series, labels) {
                chartEvolution.updateOptions({
                    series: series,
                    xaxis: {
                        categories: labels
                    }
                });
            }

            // Função para atualizar todos os dados sem recarregar a página
            function atualizarDadosSemReload() {
                var dataInicial = document.getElementById('dataInicial').value;
                var dataFinal = document.getElementById('dataFinal').value;
                var filtroDataPredefinida = document.getElementById('filtroDataPredefinida').value;

                // Preparar dados para requisição
                var params = {
                    dataInicial: dataInicial,
                    dataFinal: dataFinal,
                    filtroDataPredefinida: filtroDataPredefinida
                };

                // Fazer requisição AJAX para buscar dados atualizados
                $.ajax({
                    url: "{{ route('admin.betby-sports.sports_estatisticas') }}",
                    type: 'GET',
                    data: params,
                    success: function(response) {
                        // Extrair dados da resposta (assumindo que é HTML, precisamos dos dados JSON)
                        // Como a rota retorna HTML, vamos fazer uma requisição separada para os dados
                        buscarDadosEstatisticas(params);
                    },
                    error: function() {
                        console.error('Erro ao atualizar dados');
                    }
                });
            }

            // Função para buscar dados de estatísticas via AJAX
            function buscarDadosEstatisticas(params) {
                $.ajax({
                    url: "{{ route('admin.betby-sports.sports_estatisticas') }}",
                    type: 'GET',
                    data: Object.assign(params, { ajax: true }),
                    success: function(data) {
                        if (data.totalApostas !== undefined) {
                            // Atualizar cards
                            atualizarCards({
                                totalApostas: data.totalApostasFormatado,
                                totalApostasAbertas: data.totalApostasAbertasFormatado,
                                totalPremios: data.totalPremiosFormatado,
                                totalLiquido: data.totalLiquidoFormatado
                            });

                            // Atualizar gráfico
                            if (data.series && data.labels) {
                                atualizarGrafico(data.series, data.labels);
                            }
                        }
                    },
                    error: function() {
                        console.error('Erro ao buscar dados de estatísticas');
                    }
                });
            }

            // Evento para filtro de data predefinida
            $('#filtroDataPredefinida').on('change', function() {
                var periodo = $(this).val();
                
                if (periodo === '' || periodo === 'nenhum') {
                    // Nenhum período selecionado - limpar datas e não filtrar
                    document.getElementById('dataInicial').value = '';
                    document.getElementById('dataFinal').value = '';
                    return;
                }
                
                // Calcular as datas baseadas no período selecionado
                var dataFinal = new Date();
                var dataInicial = new Date();
                dataInicial.setDate(dataFinal.getDate() - (parseInt(periodo) - 1));

                // Formatar datas para o formato YYYY-MM-DD
                var dataInicialFormatada = dataInicial.toISOString().split('T')[0];
                var dataFinalFormatada = dataFinal.toISOString().split('T')[0];

                // Atualizar os campos de data
                document.getElementById('dataInicial').value = dataInicialFormatada;
                document.getElementById('dataFinal').value = dataFinalFormatada;

                // Atualizar dados sem recarregar página
                atualizarDadosSemReload();
            });

            // Evento para campos de data manual
            $('.filter-input').on('change', function() {
                var dataInicial = document.getElementById('dataInicial').value;
                var dataFinal = document.getElementById('dataFinal').value;
                
                // Se ambas as datas estão preenchidas, limpar filtro predefinido e atualizar
                if (dataInicial && dataFinal) {
                    document.getElementById('filtroDataPredefinida').value = 'nenhum';
                    atualizarDadosSemReload();
                }
            });

            // Função para mudar período (mantida para compatibilidade)
            function Periodos(tipo, periodo) {
                console.log('Função Periodos chamada:', tipo, periodo);
            }

            // Expor funções globalmente
            window.atualizarDadosSemReload = atualizarDadosSemReload;
            window.Periodos = Periodos;
        });
    </script>

    @push('scripts')
        <script>
            $(function () {
                // Configuração para suprimir mensagens de erro do DataTables em console
                $.fn.dataTable.ext.errMode = 'none';

                // Função para inicializar o datatable
                function initDatatable() {
                    var table = $('#datatable-estatisticas').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: {
                            dataSrc: 'data',
                            url: "{{ route('admin.betby-sports.sports_estatisticas.table') }}",
                            data: function (d) {
                                d.dataInicial = $('#dataInicial').val();
                                d.dataFinal = $('#dataFinal').val();
                                d.filtroDataPredefinida = $('#filtroDataPredefinida').val();
                                d.esporteFiltro = $('#esporteFiltro').val();
                                d.paisFiltro = $('#paisFiltro').val();
                                d.campeonatoFiltro = $('#campeonatoFiltro').val();
                            },
                            error: function (xhr, error, thrown) {
                                console.error('Erro ao carregar dados:', error);
                            }
                        },
                        columns: [
                            {data: 'esporte_pais_campeonato', name: 'esporte_pais_campeonato'},
                            {data: 'apostas_formatado', name: 'apostas'},
                            {data: 'premios_formatado', name: 'premios'},
                            {data: 'lucro_formatado', name: 'lucro'}
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
                            $('#datatable-estatisticas_paginate').addClass('paging_simple_numbers');
                            $('#datatable-estatisticas_paginate ul.pagination li').addClass('paginate_button page-item');
                            $('#datatable-estatisticas_paginate ul.pagination li.previous').attr('id', 'datatable-estatisticas_previous');
                            $('#datatable-estatisticas_paginate ul.pagination li.next').attr('id', 'datatable-estatisticas_next');
                            $('#datatable-estatisticas_paginate ul.pagination li.first').attr('id', 'datatable-estatisticas_first');
                            $('#datatable-estatisticas_paginate ul.pagination li.last').attr('id', 'datatable-estatisticas_last');
                            $('#datatable-estatisticas_paginate ul.pagination li a').addClass('page-link');

                            // Substituir o texto dos botões de paginação por ícones SVG
                            $('#datatable-estatisticas_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                            $('#datatable-estatisticas_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                            $('#datatable-estatisticas_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                            $('#datatable-estatisticas_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');
                        },
                        initComplete: function() {
                            // Adiciona um evento para recarregar a tabela quando ocorrer um erro
                            $('#datatable-estatisticas').on('error.dt', function(e, settings, techNote, message) {
                                console.error('Erro DataTable:', message);
                            });

                            // Remover campo de busca gerado automaticamente
                            $('.dataTables_filter').remove();
                        }
                    });

                    return table;
                }

                // Inicializar o datatable
                var table = initDatatable();

                // Função para recarregar a tabela
                function reloadTable() {
                    table.ajax.reload();
                }

                // Eventos de filtro que recarregam apenas a tabela
                $('#filtroDataPredefinida, .filter-input').on('change', function() {
                    reloadTable();
                });

                // Filtros em cascata
                $('#esporteFiltro').on('change', function() {
                    var esporte = $(this).val();
                    
                    // Limpar e desabilitar filtros dependentes
                    $('#paisFiltro').html('<option value="">Todos os Países</option>').prop('disabled', !esporte);
                    $('#campeonatoFiltro').html('<option value="">Todos os Campeonatos</option>').prop('disabled', true);
                    
                    if (esporte) {
                        // Carregar países para o esporte selecionado
                        loadPaises(esporte);
                    }
                    
                    reloadTable();
                });

                $('#paisFiltro').on('change', function() {
                    var esporte = $('#esporteFiltro').val();
                    var pais = $(this).val();
                    
                    // Limpar e configurar filtro de campeonatos
                    $('#campeonatoFiltro').html('<option value="">Todos os Campeonatos</option>').prop('disabled', !pais);
                    
                    if (esporte && pais) {
                        // Carregar campeonatos para o esporte e país selecionados
                        loadCampeonatos(esporte, pais);
                    }
                    
                    reloadTable();
                });

                $('#campeonatoFiltro').on('change', function() {
                    reloadTable();
                });

                // Carregar esportes disponíveis
                loadEsportes();

                // Função para carregar esportes
                function loadEsportes() {
                    $.ajax({
                        url: "{{ route('admin.betby-sports.sports_estatisticas.table') }}",
                        type: 'GET',
                        data: {
                            getEsportes: true
                        },
                        success: function(response) {
                            var esportes = response.esportes || [];
                            var select = $('#esporteFiltro');
                            
                            // Limpar opções existentes (exceto "Todos os Esportes")
                            select.find('option:not(:first)').remove();
                            
                            // Adicionar esportes únicos
                            esportes.forEach(function(esporte) {
                                if (esporte && esporte.trim() !== '') {
                                    select.append('<option value="' + esporte + '">' + esporte.toUpperCase() + '</option>');
                                }
                            });
                        },
                        error: function() {
                            console.log('Erro ao carregar esportes');
                        }
                    });
                }

                // Função para carregar países
                function loadPaises(esporte) {
                    $.ajax({
                        url: "{{ route('admin.betby-sports.sports_estatisticas.table') }}",
                        type: 'GET',
                        data: {
                            getPaises: true,
                            esporte: esporte
                        },
                        success: function(response) {
                            var paises = response.paises || [];
                            var select = $('#paisFiltro');
                            
                            // Limpar opções existentes (exceto "Todos os Países")
                            select.find('option:not(:first)').remove();
                            
                            // Adicionar países únicos
                            paises.forEach(function(pais) {
                                if (pais && pais.trim() !== '') {
                                    select.append('<option value="' + pais + '">' + pais.toUpperCase() + '</option>');
                                }
                            });
                            
                            select.prop('disabled', false);
                        },
                        error: function() {
                            console.log('Erro ao carregar países');
                        }
                    });
                }

                // Função para carregar campeonatos
                function loadCampeonatos(esporte, pais) {
                    $.ajax({
                        url: "{{ route('admin.betby-sports.sports_estatisticas.table') }}",
                        type: 'GET',
                        data: {
                            getCampeonatos: true,
                            esporte: esporte,
                            pais: pais
                        },
                        success: function(response) {
                            var campeonatos = response.campeonatos || [];
                            var select = $('#campeonatoFiltro');
                            
                            // Limpar opções existentes (exceto "Todos os Campeonatos")
                            select.find('option:not(:first)').remove();
                            
                            // Adicionar campeonatos únicos
                            campeonatos.forEach(function(campeonato) {
                                if (campeonato && campeonato.trim() !== '') {
                                    select.append('<option value="' + campeonato + '">' + campeonato + '</option>');
                                }
                            });
                            
                            select.prop('disabled', false);
                        },
                        error: function() {
                            console.log('Erro ao carregar campeonatos');
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
