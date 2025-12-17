@extends('admin.layouts.app')
@section('content')
@php
    use Carbon\Carbon;
    
    $a = request()->input('tipoEsporte', '');
    $b = request()->input('nome', '');
    $c = request()->input('status', '');
    
    $tipoEsporteFiltro = $a;
    $nomeEsporteFiltro = $b;
    $statusFiltro = $c;
@endphp
<div class="layout-px-spacing">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Sportsbook</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Ocultos</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-campeonatos-tab" data-bs-toggle="pill" data-bs-target="#campeonatos" type="button" role="tab" aria-controls="campeonatos" aria-selected="true">Campeonatos</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-categorias-tab" data-bs-toggle="pill" data-bs-target="#categorias" type="button" role="tab" aria-controls="categorias" aria-selected="false" tabindex="-1">Categorias de Esporte</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="campeonatos" role="tabpanel" aria-labelledby="pills-campeonatos-tab">
                            <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                <div class="row" style="margin-bottom: -20px; padding:15px;">
                                    <div class="col-md-12">
                                        <div class="row">
                                        <div class="col-md-4 mb-3">
                            <label for="tipoEsporte" class="form-label">Tipo de Esporte:</label>
                            <select id="tipoEsporte" name="tipoEsporte" class="form-control">
                                <option value="">Todos os Esportes</option>
                                <option value="1" {{ $tipoEsporteFiltro == '1' ? 'selected' : '' }}>Futebol</option>
                                <option value="4" {{ $tipoEsporteFiltro == '4' ? 'selected' : '' }}>Basquete</option>
                                <option value="3" {{ $tipoEsporteFiltro == '3' ? 'selected' : '' }}>Tênis</option>
                                <option value="12" {{ $tipoEsporteFiltro == '12' ? 'selected' : '' }}>Vôlei</option>
                                <option value="10" {{ $tipoEsporteFiltro == '10' ? 'selected' : '' }}>Hóquei no Gelo</option>
                                <option value="5" {{ $tipoEsporteFiltro == '5' ? 'selected' : '' }}>Beisebol</option>
                                <option value="25" {{ $tipoEsporteFiltro == '25' ? 'selected' : '' }}>Tênis de Mesa</option>
                                <option value="53" {{ $tipoEsporteFiltro == '53' ? 'selected' : '' }}>eSports</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="statusFiltro" class="form-label">Status:</label>
                            <select id="statusFiltro" name="status" class="form-control">
                                <option value="">Todos</option>
                                <option value="Oculto" {{ $statusFiltro == 'Oculto' ? 'selected' : '' }}>Ocultos</option>
                                <option value="Visível" {{ $statusFiltro == 'Visível' ? 'selected' : '' }}>Visíveis</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nomeCampeonato" class="form-label">Nome do Campeonato:</label>
                            <input type="text" id="nomeCampeonato" name="nome" placeholder="Digite para pesquisar..." value="{{$b}}" class="form-control">
                        </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end mb-3">
                                                <button class="btn btn-primary" id="btnAdicionarCampeonato">Adicionar Campeonato</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                <table id="datatable-campeonatos" class="table table-striped dt-table-hover dataTable" style="width:100%" role="grid" aria-describedby="zero-config_info">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting" tabindex="0">ID</th>
                                                <th class="sorting" tabindex="0">Nome do Campeonato</th>
                                                <th class="sorting" tabindex="0">Tipo de Esporte</th>
                                                <th class="sorting" tabindex="0">Status</th>
                                                <th class="sorting_disabled">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Os dados serão preenchidos pelo DataTable -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome do Campeonato</th>
                                                <th>Tipo de Esporte</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="categorias" role="tabpanel" aria-labelledby="pills-categorias-tab">
                            <div class="container-fluid">
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-3">
                                        <label for="statusCategoria" class="form-label">Status:</label>
                                        <select id="statusCategoria" class="form-control">
                                            <option value="">Todos</option>
                                            <option value="Oculto">Ocultos</option>
                                            <option value="Visível">Visíveis</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="tituloCategoria" class="form-label">Filtrar por Categoria:</label>
                                        <input type="text" id="tituloCategoria" class="form-control" placeholder="Digite para filtrar...">
                                    </div>
                                    <div class="col-md-2 mb-3 d-flex align-items-end justify-content-end">
                                        <button class="btn btn-primary" id="btnAdicionarCategoria">Adicionar Categoria</button>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table id="datatable-categorias" class="table table-striped dt-table-hover dataTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome da Categoria</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Os dados serão preenchidos pelo DataTable -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para adicionar novo campeonato -->
<div class="modal fade" id="modalAdicionarCampeonato" tabindex="-1" aria-labelledby="modalAdicionarCampeonatoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdicionarCampeonatoLabel">Adicionar Campeonato</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <form id="formAdicionarCampeonato">
                    <div class="mb-3">
                        <label for="modalCampeonatoId" class="form-label">ID do Campeonato:</label>
                        <input type="text" class="form-control" id="modalCampeonatoId" required>
                    </div>
                    <div class="mb-3">
                        <label for="modalNomeCampeonato" class="form-label">Nome do Campeonato:</label>
                        <input type="text" class="form-control" id="modalNomeCampeonato" required>
                    </div>
                    <div class="mb-3">
                        <label for="modalTipoEsporte" class="form-label">Tipo de Esporte:</label>
                        <select id="modalTipoEsporte" class="form-control" required>
                            <option value="">Selecione um Esporte</option>
                            <option value="1">Futebol</option>
                            <option value="4">Basquete</option>
                            <option value="3">Tênis</option>
                            <option value="12">Vôlei</option>
                            <option value="10">Hóquei no Gelo</option>
                            <option value="5">Beisebol</option>
                            <option value="25">Tênis de Mesa</option>
                            <option value="53">eSports</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modalStatusCampeonato" class="form-label">Status:</label>
                        <select id="modalStatusCampeonato" class="form-control" required>
                            <option value="Oculto">Oculto</option>
                            <option value="Visível">Visível</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnSalvarCampeonato">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para adicionar nova categoria -->
<div class="modal fade" id="modalAdicionarCategoria" tabindex="-1" aria-labelledby="modalAdicionarCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdicionarCategoriaLabel">Adicionar Categoria de Esporte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <form id="formAdicionarCategoria">
                    <div class="mb-3">
                        <label for="selectEsporte" class="form-label">Selecione o Esporte:</label>
                        <select id="selectEsporte" class="form-control" required>
                            <option value="">Selecione uma categoria</option>
                            <!-- Será preenchido via AJAX -->
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnSalvarCategoria">Salvar</button>
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
        
        // Mapeamento de IDs de esportes para nomes
        const tiposEsportes = {
            '1': 'Futebol',
            '4': 'Basquete',
            '3': 'Tênis',
            '12': 'Vôlei',
            '10': 'Hóquei no Gelo',
            '5': 'Beisebol',
            '25': 'Tênis de Mesa',
            '53': 'eSports'
        };

        // Função para inicializar o datatable com as opções desejadas
        function initDatatable(tipoEsporte, nomeCampeonato, statusFiltro) {
            var table = $('#datatable-campeonatos').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.sports.campeonatos_ocultos.data') }}",
                    data: function (d) {
                        d.tipoEsporte = tipoEsporte || $('#tipoEsporte').val();
                        d.nomeCampeonato = nomeCampeonato || $('#nomeCampeonato').val();
                        d.status = statusFiltro || $('#statusFiltro').val();
                    },
                    error: function (xhr, error, thrown) {
                    }
                },
                columns: [
                    {data: 'campeonato_id', name: 'campeonato_id'},
                    {data: 'nome', name: 'nome'},
                    {data: 'tipo_esporte', name: 'tipo_esporte', render: function(data) {
                        return `<span class="badge badge-light-primary">${tiposEsportes[data] || 'Desconhecido'}</span>`;
                    }},
                    {data: 'status', name: 'status', render: function(data) {
                        const statusClass = data === 'Oculto' ? 'badge-light-danger' : 'badge-light-success';
                        return `<span class="badge ${statusClass}">${data}</span>`;
                    }},
                    {data: 'acoes', name: 'acoes', orderable: false, searchable: false}
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
                order: [[1, 'asc']],
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
                    $('#datatable-campeonatos_paginate').addClass('paging_simple_numbers');
                    $('#datatable-campeonatos_paginate ul.pagination li').addClass('paginate_button page-item');
                    $('#datatable-campeonatos_paginate ul.pagination li.previous').attr('id', 'datatable-campeonatos_previous');
                    $('#datatable-campeonatos_paginate ul.pagination li.next').attr('id', 'datatable-campeonatos_next');
                    $('#datatable-campeonatos_paginate ul.pagination li.first').attr('id', 'datatable-campeonatos_first');
                    $('#datatable-campeonatos_paginate ul.pagination li.last').attr('id', 'datatable-campeonatos_last');
                    $('#datatable-campeonatos_paginate ul.pagination li a').addClass('page-link');
                    
                    // Substituir o texto dos botões de paginação por ícones SVG
                    $('#datatable-campeonatos_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                    $('#datatable-campeonatos_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                    $('#datatable-campeonatos_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                    $('#datatable-campeonatos_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');
                    
                    // Configurar manipuladores de eventos para botões de status e remoção
                    setupEventHandlers();
                },
                initComplete: function() {
                    // Adiciona um evento para recarregar a tabela quando ocorrer um erro
                    $('#datatable-campeonatos').on('error.dt', function(e, settings, techNote, message) {
                    });
                    
                    // Remover campo de busca gerado automaticamente
                    $('.dataTables_filter').remove();
                }
            });
            
            return table;
        }
        
        // Inicializar o datatable com os valores dos parâmetros da URL
        var tipoEsporteFiltro = "{{ $tipoEsporteFiltro }}";
        var nomeEsporteFiltro = "{{ $nomeEsporteFiltro }}";
        var statusFiltro = "{{ $statusFiltro }}";
        var table = initDatatable(tipoEsporteFiltro, nomeEsporteFiltro, statusFiltro);
        
        // Função para recarregar a tabela com os novos filtros
        function reloadTable() {
            var tipoEsporte = $('#tipoEsporte').val();
            var nomeCampeonato = $('#nomeCampeonato').val();
            var status = $('#statusFiltro').val();
            
            // Destruir o datatable anterior
            $('#datatable-campeonatos').DataTable().destroy();
            
            // Reinicializar com os novos parâmetros
            table = initDatatable(tipoEsporte, nomeCampeonato, status);
            
            // Atualizar a URL para refletir os novos filtros sem recarregar a página
            var newUrl = "{{ route('admin.sports.campeonatos_ocultos') }}?tipoEsporte=" + tipoEsporte + "&nome=" + encodeURIComponent(nomeCampeonato) + "&status=" + encodeURIComponent(status);
            window.history.pushState({}, '', newUrl);
        }
        
        // Evento para campo de seleção de esporte - aplicar filtro imediatamente ao mudar
        $('#tipoEsporte').on('change', function() {
            reloadTable();
        });
        
        // Evento para campo de seleção de status - aplicar filtro imediatamente ao mudar
        $('#statusFiltro').on('change', function() {
            reloadTable();
        });
        
        // Evento para campo de texto - aplicar filtro após parar de digitar
        $('#nomeCampeonato').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(reloadTable, doneTypingInterval);
        });
        
        $('#nomeCampeonato').on('keydown', function() {
            clearTimeout(typingTimer);
        });
        
        // Configurar manipuladores de eventos para botões de ação
        function setupEventHandlers() {
            // Botão de mudar status
            $('.btn-mudar-status').off('click').on('click', function() {
                const id = $(this).data('id');
                const status = $(this).data('status');
                const novoStatus = status === 'Oculto' ? 'Visível' : 'Oculto';
                
                mudarStatusCampeonato(id, novoStatus);
            });
            
            // Botão de remover
            $('.btn-remover').off('click').on('click', function() {
                const id = $(this).data('id');
                const nome = $(this).data('nome');
                
                ModalManager.showConfirmation(
                    'Remover Campeonato', 
                    `Tem certeza que deseja remover o campeonato "${nome}" da lista?`,
                    function() {
                        removerCampeonatoOculto(id);
                    }
                );
            });
        }
        
        // Função para mudar status de um campeonato
        function mudarStatusCampeonato(id, novoStatus) {
            $.ajax({
                url: "/Admin/MudarStatusCampeonato",
                type: "POST",
                data: {
                    id: id,
                    status: novoStatus,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.status) {
                        ToastManager.success(response.message);
                        table.ajax.reload(null, false); // Recarregar dados sem perder a paginação
                    } else {
                        ToastManager.error(response.message);
                    }
                },
                error: function(xhr) {
                    ToastManager.error('Erro ao mudar status do campeonato');
                }
            });
        }
        
        // Função para remover campeonato oculto
        function removerCampeonatoOculto(id) {
            $.ajax({
                url: "/Admin/RemoverCampeonatoOculto",
                type: "POST",
                data: {
                    id: id,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.status) {
                        ToastManager.success(response.message);
                        table.ajax.reload(null, false); // Recarregar dados sem perder a paginação
                    } else {
                        ToastManager.error(response.message);
                    }
                },
                error: function(xhr) {
                    ToastManager.error('Erro ao remover campeonato');
                }
            });
        }
        
        // Abrir modal para adicionar novo campeonato
        $('#btnAdicionarCampeonato').on('click', function() {
            $('#modalAdicionarCampeonato').modal('show');
        });
        
        // Salvar novo campeonato
        $('#btnSalvarCampeonato').on('click', function() {
            const campeonatoId = $('#modalCampeonatoId').val();
            const nome = $('#modalNomeCampeonato').val();
            const tipoEsporte = $('#modalTipoEsporte').val();
            const status = $('#modalStatusCampeonato').val();
            
            // Validação básica
            if (!campeonatoId || !nome || !tipoEsporte) {
                ToastManager.error('Preencha todos os campos obrigatórios');
                return;
            }
            
            // Enviar dados para o servidor
            $.ajax({
                url: "/Admin/SalvarCampeonatoOculto",
                type: "POST",
                data: {
                    id: campeonatoId,
                    nome: nome,
                    tipoEsporte: tipoEsporte,
                    status: status,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.status) {
                        ToastManager.success(response.message);
                        
                        // Limpar formulário
                        $('#formAdicionarCampeonato')[0].reset();
                        
                        // Fechar modal
                        $('#modalAdicionarCampeonato').modal('hide');
                        
                        // Recarregar tabela
                        table.ajax.reload(null, false);
                    } else {
                        ToastManager.error(response.message);
                    }
                },
                error: function(xhr) {
                    ToastManager.error('Erro ao adicionar campeonato');
                }
            });
        });

        // Datatable para categorias de esportes
        function initCategoriasDatatable() {
            var tableCategorias = $('#datatable-categorias').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "/Admin/CategoriasOcultasData",
                    data: function (d) {
                        d.statusCategoria = $('#statusCategoria').val();
                        d.tituloCategoria = $('#tituloCategoria').val();
                    },
                    error: function (xhr, error, thrown) {
                        ToastManager.error('Erro ao carregar categorias de esportes');
                    }
                },
                columns: [
                    {data: 'sport_id', name: 'sport_id'},
                    {data: 'titulo', name: 'titulo'},
                    {data: 'status', name: 'status', render: function(data) {
                        const statusClass = data === 'Oculto' ? 'badge-light-danger' : 'badge-light-success';
                        return `<span class="badge ${statusClass}">${data}</span>`;
                    }},
                    {data: 'acoes', name: 'acoes', orderable: false, searchable: false}
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
                order: [[1, 'asc']],
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
                    $('#datatable-categorias_paginate').addClass('paging_simple_numbers');
                    $('#datatable-categorias_paginate ul.pagination li').addClass('paginate_button page-item');
                    $('#datatable-categorias_paginate ul.pagination li a').addClass('page-link');
                    
                    // Substituir o texto dos botões de paginação por ícones SVG
                    $('#datatable-categorias_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                    $('#datatable-categorias_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                    $('#datatable-categorias_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                    $('#datatable-categorias_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');
                    
                    // Configurar manipuladores de eventos para botões de ação
                    setupCategoriasEventHandlers();
                },
                initComplete: function() {
                    // Remover campo de busca gerado automaticamente
                    $('.dataTables_filter').remove();
                }
            });
            
            return tableCategorias;
        }
        
        var tableCategorias;
        
        // Inicializa o DataTable de categorias quando a aba for clicada
        $('#pills-categorias-tab').on('shown.bs.tab', function(e) {
            if (!tableCategorias) {
                tableCategorias = initCategoriasDatatable();
            } else {
                tableCategorias.ajax.reload();
            }
        });
        
        // Eventos para os filtros da aba de categorias
        $('#filtroCategoria').on('keyup', function() {
            if (tableCategorias) {
                tableCategorias.search($(this).val()).draw();
            }
        });
        
        $('#filtroStatusCategoria').on('change', function() {
            if (tableCategorias) {
                // Filtrar pelo status selecionado
                const status = $(this).val();
                
                // Criar um filtro personalizado
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        // Esta função será aplicada a cada linha
                        // settings: configurações da tabela
                        // data: dados da linha
                        // dataIndex: índice da linha
                        
                        // data[2] contém o status (coluna índice 2)
                        const rowStatus = $(data[2]).text(); // Extrair texto do HTML
                        
                        // Se não tiver filtro, mostrar todas linhas
                        if (!status) {
                            return true;
                        }
                        
                        // Retornar true se o status da linha corresponder ao filtro
                        return rowStatus.includes(status);
                    }
                );
                
                tableCategorias.draw();
                
                // Remover o filtro personalizado após uso
                $.fn.dataTable.ext.search.pop();
            }
        });
        
        // Setup event handlers para o DataTable de categorias
        function setupCategoriasEventHandlers() {
            $('.btn-remover-categoria').off('click').on('click', function() {
                const id = $(this).data('id');
                const sportId = $(this).data('sport-id');
                const titulo = $(this).data('titulo');
                
                ModalManager.showConfirmation(
                    'Remover Categoria', 
                    `Tem certeza que deseja remover a categoria "${titulo}" da lista?`,
                    function() {
                        removerCategoriaOculta(sportId);
                    }
                );
            });
        }
        
        // Função para remover categoria oculta
        function removerCategoriaOculta(sportId) {
            $.ajax({
                url: "/Admin/RemoverCategoriaOculta",
                type: "POST",
                data: {
                    sport_id: sportId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.status) {
                        ToastManager.success(response.message);
                        if(tableCategorias) {
                            tableCategorias.ajax.reload(null, false);
                        }
                    } else {
                        ToastManager.error(response.message);
                    }
                },
                error: function(xhr) {
                    console.error("Erro ao remover categoria:", xhr);
                    ToastManager.error('Erro ao remover categoria');
                }
            });
        }
        
        // Carregar categorias de esportes disponíveis para o modal
        $('#btnAdicionarCategoria').on('click', function() {
            // Limpar select
            $('#selectEsporte').empty().append('<option value="">Selecione uma categoria</option>');
            
            // Carregar categorias
            $.ajax({
                url: "/Admin/CarregarTitulosEsportes",
                type: "GET",
                success: function(response) {
                    if(response.status && response.esportes.length > 0) {
                        response.esportes.forEach(function(esporte) {
                            $('#selectEsporte').append(
                                `<option value="${esporte.id}" data-titulo="${esporte.titulo}">${esporte.titulo} (${esporte.quantidade})</option>`
                            );
                        });
                        
                        // Abrir modal
                        $('#modalAdicionarCategoria').modal('show');
                    } else {
                        ToastManager.error('Não foi possível carregar as categorias de esportes');
                    }
                },
                error: function(xhr) {
                    ToastManager.error('Erro ao carregar categorias de esportes');
                }
            });
        });
        
        // Salvar categoria oculta
        $('#btnSalvarCategoria').on('click', function() {
            const sportId = $('#selectEsporte').val();
            const titulo = $('#selectEsporte option:selected').data('titulo') || $('#selectEsporte option:selected').text();
            
            // Validação básica
            if (!sportId) {
                ToastManager.error('Selecione uma categoria de esporte');
                return;
            }
            
            // Enviar dados para o servidor
            $.ajax({
                url: "/Admin/SalvarCategoriaOculta",
                type: "POST",
                data: {
                    sport_id: sportId,
                    titulo: titulo,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.status) {
                        ToastManager.success(response.message);
                        
                        // Limpar formulário
                        $('#formAdicionarCategoria')[0].reset();
                        
                        // Fechar modal
                        $('#modalAdicionarCategoria').modal('hide');
                        
                        // Recarregar tabela
                        if(tableCategorias) {
                            tableCategorias.ajax.reload(null, false);
                        }
                    } else {
                        ToastManager.error(response.message);
                    }
                },
                error: function(xhr) {
                    ToastManager.error('Erro ao adicionar categoria');
                }
            });
        });

        // Inicializar tabela de categorias
        var tableCategorias = initCategoriasDatatable();
        
        // Função para recarregar a tabela de categorias com os novos filtros
        function reloadCategoriasTable() {
            var statusCategoria = $('#statusCategoria').val();
            var tituloCategoria = $('#tituloCategoria').val();
            
            // Destruir o datatable anterior
            $('#datatable-categorias').DataTable().destroy();
            
            // Reinicializar com os novos parâmetros
            tableCategorias = initCategoriasDatatable();
            
            // Atualizar a URL para refletir os novos filtros sem recarregar a página
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('statusCategoria', statusCategoria);
            currentUrl.searchParams.set('tituloCategoria', tituloCategoria);
            window.history.pushState({}, '', currentUrl);
        }
        
        // Evento para campo de seleção de status de categoria - aplicar filtro imediatamente ao mudar
        $('#statusCategoria').on('change', function() {
            reloadCategoriasTable();
        });
        
        // Evento para campo de texto de categoria - aplicar filtro após parar de digitar
        var categoriasTypingTimer;
        $('#tituloCategoria').on('keyup', function() {
            clearTimeout(categoriasTypingTimer);
            categoriasTypingTimer = setTimeout(reloadCategoriasTable, doneTypingInterval);
        });
        
        $('#tituloCategoria').on('keydown', function() {
            clearTimeout(categoriasTypingTimer);
        });
        
        // Configurar manipuladores de eventos para botões de ação nas categorias
        function setupCategoriasEventHandlers() {
            // Botão de mudar status da categoria
            $('.btn-mudar-status-categoria').off('click').on('click', function() {
                const id = $(this).data('id');
                const sportId = $(this).data('sport-id');
                const status = $(this).data('status');
                const novoStatus = status === 'Oculto' ? 'Visível' : 'Oculto';
                
                mudarStatusCategoria(id, sportId, novoStatus);
            });
            
            // Botão de remover categoria
            $('.btn-remover-categoria').off('click').on('click', function() {
                const id = $(this).data('id');
                const sportId = $(this).data('sport-id');
                const titulo = $(this).data('titulo');
                
                ModalManager.showConfirmation(
                    'Remover Categoria', 
                    `Tem certeza que deseja remover a categoria "${titulo}" da lista?`,
                    function() {
                        removerCategoria(sportId);
                    }
                );
            });
        }
        
        // Função para mudar status de uma categoria
        function mudarStatusCategoria(id, sportId, novoStatus) {
            $.ajax({
                url: "/Admin/MudarStatusCategoria",
                type: "POST",
                data: {
                    id: id,
                    sport_id: sportId,
                    status: novoStatus,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.status) {
                        ToastManager.success(response.message);
                        tableCategorias.ajax.reload(null, false); // Recarregar dados sem perder a paginação
                    } else {
                        ToastManager.error(response.message);
                    }
                },
                error: function(xhr) {
                    ToastManager.error('Erro ao mudar status da categoria');
                }
            });
        }
        
        // Função para remover categoria oculta
        function removerCategoria(sportId) {
            $.ajax({
                url: "/Admin/RemoverCategoriaOculta",
                type: "POST",
                data: {
                    sport_id: sportId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.status) {
                        ToastManager.success(response.message);
                        tableCategorias.ajax.reload(null, false); // Recarregar dados sem perder a paginação
                    } else {
                        ToastManager.error(response.message);
                    }
                },
                error: function(xhr) {
                    ToastManager.error('Erro ao remover categoria');
                }
            });
        }
    });
</script>
@endpush 
@endsection