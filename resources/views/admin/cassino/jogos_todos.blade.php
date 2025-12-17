@extends('admin.layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Cassino</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Jogos</li>
                    </ol>
                </nav>
            </div>
            <!-- /BREADCRUMB -->

            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <!-- FILTROS -->
                        <div class="row p-3">
                            <div class="col-md-3 mb-3">
                                <label for="filter-distribution" class="form-label">Distribuição</label>
                                <select id="filter-distribution" class="form-select filter-control">
                                    <option value="all">Todas</option>
                                    @if(isset($distributions) && is_array($distributions))
                                        @foreach($distributions as $distribution)
                                            <option value="{{ $distribution }}">{{ ucfirst($distribution) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="filter-status" class="form-label">Status</label>
                                <select id="filter-status" class="form-select filter-control">
                                    <option value="all">Todos</option>
                                    <option value="1">Ativos</option>
                                    <option value="0">Inativos</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="filter-provider" class="form-label">Provedor</label>
                                <select id="filter-provider" class="form-select filter-control">
                                    <option value="all">Todos</option>
                                    @if(isset($providersWithData) && is_array($providersWithData))
                                        @foreach($providersWithData as $provider)
                                            <option value="{{ $provider['name'] }}" 
                                                    data-distribution="{{ $provider['distribution'] ?? '' }}" 
                                                    data-wallets="{{ $provider['wallets'] ?? '' }}">
                                                {{ $provider['name'] }}
                                            </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3 mb-3" id="filter-wallet-container" style="display: none;">
                                <label for="filter-wallet" class="form-label">Carteira</label>
                                <select id="filter-wallet" class="form-select filter-control">
                                    <option value="all">Todas</option>
                                    @if(isset($availableWallets) && is_array($availableWallets))
                                        @foreach($availableWallets as $wallet)
                                            <option value="{{ $wallet }}">{{ $wallet }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3 mb-3" id="filter-original-container" style="display: none;">
                                <label for="filter-original" class="form-label">Tipo</label>
                                <select id="filter-original" class="form-select filter-control">
                                    <option value="all">Todos</option>
                                    <option value="original">Oficial</option>
                                    <option value="clone">Clone</option>
                                </select>
                            </div>
                        </div>
                        <!-- /FILTROS -->
                        <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <div class="table-responsive">                                <table id="jogos-datatable" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="jogos-datatable" rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending">ID</th>
                                        <th class="sorting" tabindex="0" aria-controls="jogos-datatable" rowspan="1" colspan="1" aria-label="Capa: activate to sort column ascending">Capa</th>
                                        <th class="sorting" tabindex="0" aria-controls="jogos-datatable" rowspan="1" colspan="1" aria-label="Nome: activate to sort column ascending">Nome</th>
                                        <th class="sorting" tabindex="0" aria-controls="jogos-datatable" rowspan="1" colspan="1" aria-label="Provedor: activate to sort column ascending">Provedor</th>
                                        <th class="sorting" tabindex="0" aria-controls="jogos-datatable" rowspan="1" colspan="1" aria-label="Distribuição: activate to sort column ascending">Distribuição</th>
                                        <th class="sorting" tabindex="0" aria-controls="jogos-datatable" rowspan="1" colspan="1" aria-label="Exibir na Home: activate to sort column ascending">Exibir na Home</th>
                                        <th class="sorting" tabindex="0" aria-controls="jogos-datatable" rowspan="1" colspan="1" aria-label="Destaques: activate to sort column ascending">Destaques</th>
                                        <th class="sorting" tabindex="0" aria-controls="jogos-datatable" rowspan="1" colspan="1" aria-label="Views: activate to sort column ascending">Views</th>
                                        <th class="sorting" tabindex="0" aria-controls="jogos-datatable" rowspan="1" colspan="1" aria-label="Ativo: activate to sort column ascending">Ativo</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Dados carregados via Ajax -->
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">ID</th>
                                        <th rowspan="1" colspan="1">Capa</th>
                                        <th rowspan="1" colspan="1">Nome</th>
                                        <th rowspan="1" colspan="1">Provedor</th>
                                        <th rowspan="1" colspan="1">Distribuição</th>
                                        <th rowspan="1" colspan="1">Exibir na Home</th>
                                        <th rowspan="1" colspan="1">Destaques</th>
                                        <th rowspan="1" colspan="1">Views</th>
                                        <th rowspan="1" colspan="1">Ativo</th>
                                    </tr>
                                    </tfoot>
                                </table></div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Modal para edição de provedor -->
        <div class="modal fade" id="editProviderModal" tabindex="-1" role="dialog" aria-labelledby="editProviderModalLabel" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProviderModalLabel">Alterar Provedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <div class="modal-body">
                        <form id="editProviderForm">
                            <input type="hidden" id="game_id" name="game_id">
                            <div class="mb-3">
                                <label for="game_name" class="form-label">Nome do Jogo</label>
                                <input type="text" class="form-control" id="game_name" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="current_provider" class="form-label">Provedor Atual</label>
                                <input type="text" class="form-control" id="current_provider" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="new_provider" class="form-label">Novo Provedor</label>
                                <select class="form-select" id="new_provider" name="new_provider">
                                    <option value="">Selecione um provedor</option>
                                    @if(isset($providers) && is_array($providers))
                                    @foreach($providers as $provider)
                                        <option value="{{ $provider }}">{{ $provider }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="saveProviderChange">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para edição de detalhes do jogo -->
        <div class="modal fade" id="editGameDetailsModal" tabindex="-1" role="dialog" aria-labelledby="editGameDetailsModalLabel" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editGameDetailsModalLabel">Editar Detalhes do Jogo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <div class="modal-body">
                        <form id="editGameDetailsForm">
                            <input type="hidden" id="edit_game_id" name="game_id">
                            
                            <div class="mb-3">
                                <label for="edit_game_name" class="form-label">Nome do Jogo</label>
                                <input type="text" class="form-control" id="edit_game_name" name="name" required>
                                <div class="form-text">Nome que será exibido para os usuários</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_game_slug" class="form-label">Slug do Jogo</label>
                                <input type="text" class="form-control" id="edit_game_slug" name="slug" placeholder="Digite o slug do jogo...">
                                <div class="form-text">Identificador único do jogo usado nas URLs</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="saveGameDetails">Salvar Alterações</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para confirmação de ações genéricas (adicionado pelo ModalManager) -->
        <!-- Será gerenciado pelo modals.js -->

        <!-- Modal para visualização da imagem do jogo -->
        <div class="modal fade" id="gameImageModal" tabindex="-1" role="dialog" aria-labelledby="gameImageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="gameImageModalLabel">Imagem do Jogo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div id="gameImageContent">
                            <!-- Conteúdo será inserido dinamicamente -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light-dark" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                $(document).ready(function() {
                    // Inicializar DataTable com estrutura similar à página de provedores
                    var dataTable = $('#jogos-datatable').DataTable({
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        ajax: {
                        url: "{{ route('admin.cassino.jogos.data') }}",
                            data: function(d) {
                                // Adicionar filtros aos dados enviados
                                d.status = $('#filter-status').val();
                                d.provider = $('#filter-provider').val();
                                d.distribution = $('#filter-distribution').val();
                                d.wallet = $('#filter-wallet').val();
                                d.original = $('#filter-original').val();
                            }
                        },
                        columns: [
                            { data: 'id', name: 'id' },
                            { data: 'capa', name: 'capa', orderable: false },
                            { data: 'nome', name: 'name' },
                            { data: 'provedor', name: 'provedor', orderable: false },
                            { data: 'distribuicao', name: 'distribuicao', orderable: false },
                            { data: 'exibir_home', name: 'show_home', orderable: false },
                            { data: 'destaque', name: 'destaque', orderable: false },
                            { data: 'views', name: 'views' },
                            { data: 'ativo', name: 'status', orderable: false }
                        ],
                        order: [[0, 'asc']], // Ordenar por ID (primeira coluna)
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
                            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Processando...</span></div>',
                            paginate: {
                                first: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>',
                                previous: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                                next: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                                last: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>'
                            },
                            info: "Mostrando página _PAGE_ de _PAGES_",
                            search: "Buscar:",
                            lengthMenu: "Exibir _MENU_ registros por página",
                            emptyTable: "Nenhum registro encontrado",
                            zeroRecords: "Nenhum registro encontrado",
                            infoEmpty: "Mostrando 0 a 0 de 0 registros",
                            infoFiltered: "(filtrado de _MAX_ registros no total)"
                        },
                        dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                            "<'table-responsive'tr>" +
                            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
                        lengthMenu: [10, 25, 50, 100],
                        pageLength: 20,
                        pagingType: 'full_numbers',
                        drawCallback: function(settings) {
                            // Adicionar classes ao paginador
                            $('#jogos-datatable_paginate').addClass('paging_simple_numbers');
                            $('#jogos-datatable_paginate ul.pagination li').addClass('paginate_button page-item');
                            $('#jogos-datatable_paginate ul.pagination li.previous').attr('id', 'jogos-datatable_previous');
                            $('#jogos-datatable_paginate ul.pagination li.next').attr('id', 'jogos-datatable_next');
                            $('#jogos-datatable_paginate ul.pagination li.first').attr('id', 'jogos-datatable_first');
                            $('#jogos-datatable_paginate ul.pagination li.last').attr('id', 'jogos-datatable_last');
                            $('#jogos-datatable_paginate ul.pagination li a').addClass('page-link');

                            // Substituir o texto dos botões de paginação por ícones SVG
                            $('#jogos-datatable_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                            $('#jogos-datatable_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                            $('#jogos-datatable_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                            $('#jogos-datatable_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');

                            // Reinicializar os inputs de imagem após o redraw
                            initializeGameImageInputs();
                        }
                    });

                    // Event listeners específicos para cada filtro com lógica de limpeza automática
                    $('#filter-distribution').on('change', function() {
                        const value = $(this).val();
                        
                        // Limpar filtros dependentes primeiro
                        if (value === 'all') {
                            $('#filter-provider').val('all');
                            $('#filter-wallet').val('all');
                            $('#filter-original').val('all');
                            $('#filter-wallet-container').hide();
                            $('#filter-original-container').hide();
                        }
                        
                        // Atualizar filtros condicionais e recarregar apenas quando terminar
                        updateConditionalFilters(function() {
                            highlightActiveFilters();
                            dataTable.ajax.reload();
                        });
                    });

                    $('#filter-provider').on('change', function() {
                        const value = $(this).val();
                        
                        // Limpar filtros dependentes primeiro
                        if (value === 'all') {
                            $('#filter-wallet').val('all');
                        }
                        
                        // Atualizar filtros condicionais e recarregar apenas quando terminar
                        updateConditionalFilters(function() {
                            highlightActiveFilters();
                            dataTable.ajax.reload();
                        });
                    });

                    $('#filter-status').on('change', function() {
                        const value = $(this).val();
                        
                        // Atualizar lista de provedores e depois atualizar filtros condicionais
                        updateProvidersList(function() {
                            updateConditionalFilters(function() {
                                highlightActiveFilters();
                                dataTable.ajax.reload();
                            });
                        });
                    });

                    $('#filter-wallet').on('change', function() {
                        const walletValue = $(this).val();
                        
                        // Atualizar lista de provedores baseado na carteira selecionada
                        if (walletValue !== 'all') {
                            updateProvidersListByWallet(walletValue, function() {
                                highlightActiveFilters();
                                dataTable.ajax.reload();
                            });
                        } else {
                            // Se "Todas" foi selecionado, atualizar lista completa de provedores
                            updateConditionalFilters(function() {
                                highlightActiveFilters();
                                dataTable.ajax.reload();
                            });
                        }
                    });

                    $('#filter-original').on('change', function() {
                        highlightActiveFilters();
                        dataTable.ajax.reload();
                    });

                    // Função para atualizar lista de provedores baseada no status
                    function updateProvidersList(callback) {
                        var status = $('#filter-status').val();
                        var currentProvider = $('#filter-provider').val();
                        
                        $.ajax({
                            url: "{{ route('admin.cassino.get-providers-by-status') }}",
                            type: "GET",
                            data: { status: status },
                            success: function(response) {
                                if (response.success) {
                                    var providerSelect = $('#filter-provider');
                                    providerSelect.empty();
                                    providerSelect.append('<option value="all">Todos os Provedores</option>');
                                    
                                    $.each(response.providers, function(index, provider) {
                                        providerSelect.append('<option value="' + provider + '">' + provider + '</option>');
                                    });
                                    
                                    if (currentProvider !== 'all' && response.providers.includes(currentProvider)) {
                                        providerSelect.val(currentProvider);
                                    } else {
                                        providerSelect.val('all');
                                    }
                                }
                                
                                // Executar callback se fornecido
                                if (typeof callback === 'function') {
                                    callback();
                                }
                            },
                            error: function(xhr, status, error) {
                                if (typeof ToastManager !== 'undefined') {
                                    ToastManager.error('Erro ao atualizar lista de provedores.');
                                }
                                
                                // Executar callback mesmo em caso de erro
                                if (typeof callback === 'function') {
                                    callback();
                                }
                            }
                        });
                    }

                    // Função para atualizar lista de provedores baseada na carteira selecionada
                    function updateProvidersListByWallet(wallet, callback) {
                        var currentProvider = $('#filter-provider').val();
                        var distributionFilter = $('#filter-distribution').val();
                        var statusFilter = $('#filter-status').val();
                        
                        $.ajax({
                            url: "{{ route('admin.cassino.get-providers-by-wallet') }}",
                            type: "GET",
                            data: { 
                                wallet: wallet,
                                distribution: distributionFilter,
                                status: statusFilter
                            },
                            success: function(response) {
                                if (response.success) {
                                    var providerSelect = $('#filter-provider');
                                    
                                    providerSelect.empty();
                                    providerSelect.append('<option value="all">Todos os Provedores</option>');
                                    
                                    $.each(response.providers, function(index, provider) {
                                        providerSelect.append('<option value="' + provider + '">' + provider + '</option>');
                                    });
                                    
                                    // Manter valor selecionado se ainda estiver disponível
                                    if (currentProvider !== 'all' && response.providers.includes(currentProvider)) {
                                        providerSelect.val(currentProvider);
                                    } else {
                                        providerSelect.val('all');
                                    }
                                }
                                
                                // Executar callback se fornecido
                                if (typeof callback === 'function') {
                                    callback();
                                }
                            },
                            error: function(xhr, status, error) {
                                if (typeof ToastManager !== 'undefined') {
                                    ToastManager.error('Erro ao atualizar lista de provedores: ' + error);
                                }
                                
                                // Executar callback mesmo em caso de erro
                                if (typeof callback === 'function') {
                                    callback();
                                }
                            }
                        });
                    }

                    // Função para atualizar filtros condicionais
                    function updateConditionalFilters(callback) {
                        const distributionFilter = $('#filter-distribution').val();
                        const providerFilter = $('#filter-provider').val();
                        
                        // Contador de requisições pendentes
                        let pendingRequests = 0;
                        let completedRequests = 0;
                        
                        function checkCompletion() {
                            completedRequests++;
                            if (completedRequests === pendingRequests && typeof callback === 'function') {
                                callback();
                            }
                        }
                        
                        // Atualizar select de provedores baseado na distribuição
                        pendingRequests++;
                        updateProviderOptions(distributionFilter, checkCompletion);
                        
                        // Atualizar visibilidade do filtro de wallets
                        pendingRequests++;
                        updateWalletFilterVisibility(distributionFilter, providerFilter, checkCompletion);
                        
                        // Atualizar visibilidade do filtro original/clone (não faz AJAX)
                        updateOriginalFilterVisibility(distributionFilter);
                    }

                    // Função para atualizar opções do select de provedores
                    function updateProviderOptions(distributionFilter, callback) {
                        const providerSelect = $('#filter-provider');
                        const currentValue = providerSelect.val();
                        const walletFilter = $('#filter-wallet').val();
                        
                        // Se há filtro de carteira ativo, usar a função específica para carteira
                        if (walletFilter && walletFilter !== 'all') {
                            updateProvidersListByWallet(walletFilter, callback);
                            return;
                        }
                        
                        // Buscar provedores baseado na distribuição selecionada
                        $.ajax({
                            url: "{{ route('admin.cassino.get-providers-by-distribution') }}",
                            type: "GET",
                            data: { distribution: distributionFilter },
                            success: function(response) {
                                if (response.success) {
                                    // Limpar e repopular o select
                                    providerSelect.empty();
                                    providerSelect.append('<option value="all">Todos os Provedores</option>');
                                    
                                    $.each(response.providers, function(index, provider) {
                                        providerSelect.append('<option value="' + provider + '">' + provider + '</option>');
                                    });
                                    
                                    // Manter valor selecionado se ainda estiver disponível
                                    if (currentValue !== 'all' && response.providers.includes(currentValue)) {
                                        providerSelect.val(currentValue);
                                    } else {
                                        providerSelect.val('all');
                                    }
                                }
                                
                                // Executar callback se fornecido
                                if (typeof callback === 'function') {
                                    callback();
                                }
                            },
                            error: function(xhr, status, error) {
                                if (typeof ToastManager !== 'undefined') {
                                    ToastManager.error('Erro ao atualizar lista de provedores.');
                                }
                                
                                // Executar callback mesmo em caso de erro
                                if (typeof callback === 'function') {
                                    callback();
                                }
                            }
                        });
                    }

                    // Função para atualizar visibilidade do filtro de wallets
                    function updateWalletFilterVisibility(distributionFilter, providerFilter, callback) {
                        const walletContainer = $('#filter-wallet-container');
                        const walletSelect = $('#filter-wallet');
                        const currentWallet = walletSelect.val();

                        // Buscar informações de wallets do backend
                        $.ajax({
                            url: "{{ route('admin.cassino.check-wallets-availability') }}",
                            type: "GET",
                            data: { 
                                distribution: distributionFilter,
                                provider: providerFilter,
                                status: $('#filter-status').val() // Incluir o status atual
                            },
                            success: function(response) {
                                if (response.success) {
                                    if (response.hasWallets) {
                                        // Atualizar opções de wallets
                                        walletSelect.empty();
                                        walletSelect.append('<option value="all">Todas as Carteiras</option>');
                                        
                                        $.each(response.wallets, function(index, wallet) {
                                            walletSelect.append('<option value="' + wallet + '">' + wallet + '</option>');
                                        });
                                        
                                        // Manter valor selecionado se ainda estiver disponível
                                        if (currentWallet !== 'all' && response.wallets.includes(currentWallet)) {
                                            walletSelect.val(currentWallet);
                                        } else {
                                            walletSelect.val('all');
                                        }
                                        
                                        walletContainer.show();
                                    } else {
                                        walletContainer.hide();
                                        // Auto-limpar wallet quando esconder
                                        if (walletSelect.val() !== 'all') {
                                            walletSelect.val('all');
                                        }
                                    }
                                }
                                
                                // Executar callback se fornecido
                                if (typeof callback === 'function') {
                                    callback();
                                }
                            },
                            error: function(xhr, status, error) {
                                // Em caso de erro, esconder o filtro
                                walletContainer.hide();
                                if (walletSelect.val() !== 'all') {
                                    walletSelect.val('all');
                                }
                                
                                // Executar callback mesmo em caso de erro
                                if (typeof callback === 'function') {
                                    callback();
                                }
                            }
                        });
                    }

                    // Função para atualizar visibilidade do filtro original/clone
                    function updateOriginalFilterVisibility(distributionFilter) {
                        const originalContainer = $('#filter-original-container');
                        const originalSelect = $('#filter-original');
                        
                        // Mostrar filtro original/clone apenas quando distribuição for TBS
                        if (distributionFilter === 'TBS') {
                            originalContainer.show();
                        } else {
                            originalContainer.hide();
                            // Auto-limpar original quando esconder
                            if (originalSelect.val() !== 'all') {
                                originalSelect.val('all');
                            }
                        }
                    }

                    // Inicializar filtros condicionais na primeira carga
                    updateConditionalFilters(function() {
                        highlightActiveFilters();
                    });

                    // Função para destacar filtros ativos
                    function highlightActiveFilters() {
                        // Remover classe active de todos os filtros
                        $('.filter-control').removeClass('active');

                        // Adicionar classe active aos filtros que têm valores diferentes do padrão
                        if ($('#filter-distribution').val() !== 'all') {
                            $('#filter-distribution').addClass('active');
                        }

                        if ($('#filter-provider').val() !== 'all') {
                            $('#filter-provider').addClass('active');
                        }

                        if ($('#filter-status').val() !== 'all') {
                            $('#filter-status').addClass('active');
                        }

                        if ($('#filter-wallet').val() !== 'all' && $('#filter-wallet-container').is(':visible')) {
                            $('#filter-wallet').addClass('active');
                        }

                        if ($('#filter-original').val() !== 'all' && $('#filter-original-container').is(':visible')) {
                            $('#filter-original').addClass('active');
                        }
                    }

                    // Abrir modal ao clicar no badge do provedor
                    $(document).on('click', '.provider-badge', function() {
                        const gameId = $(this).data('game-id');
                        const gameName = $(this).data('game-name');
                        const provider = $(this).data('provider');

                        $('#game_id').val(gameId);
                        $('#game_name').val(gameName);
                        $('#current_provider').val(provider);
                        $('#new_provider').val('');

                        $('#editProviderModal').modal('show');
                    });

                    // Salvar alteração de provedor
                    $('#saveProviderChange').on('click', function() {
                        const gameId = $('#game_id').val();
                        const newProvider = $('#new_provider').val();

                        if (!newProvider) {
                            ToastManager.error('Selecione um provedor para continuar.');
                            return;
                        }

                        // Fechar o modal de edição
                        $('#editProviderModal').modal('hide');

                        // Mostrar toast de "processando"
                        const processingToast = ToastManager.info('Processando, aguarde...');

                        $.ajax({
                            url: "{{ route('admin.games.update-field') }}",
                            type: "POST",
                            data: {
                                id: gameId,
                                field: "provider_name",
                                value: newProvider,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                // Remover toast de processamento
                                processingToast.remove();

                                if (response.success) {
                                    ToastManager.success('Provedor atualizado com sucesso!');
                                    dataTable.ajax.reload(null, false);
                                } else {
                                    ToastManager.error(response.message || "Erro ao atualizar o provedor.");
                                }
                            },
                            error: function(xhr, status, error) {
                                // Remover toast de processamento
                                processingToast.remove();

                                ToastManager.error("Erro ao processar a solicitação.");
                            }
                        });
                    });


                });

                // Função para abrir o modal de edição de detalhes do jogo
                window.openEditGameModal = function(gameId, gameName) {
                    // Mostrar indicador de carregamento no modal
                    $('#edit_game_id').val(gameId);
                    $('#edit_game_name').val('Carregando...').prop('disabled', true);
                    $('#edit_game_slug').val('Carregando...').prop('disabled', true);
                    $('#saveGameDetails').prop('disabled', true);
                    
                    // Abrir o modal primeiro
                    $('#editGameDetailsModal').modal('show');
                    
                    // Buscar dados atualizados do servidor
                    $.ajax({
                        url: "{{ route('admin.cassino.get-game-details') }}",
                        type: "POST",
                        data: {
                            game_id: gameId,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success && response.game) {
                                // Preencher com os dados atualizados do servidor
                                $('#edit_game_name').val(response.game.name).prop('disabled', false);
                                $('#edit_game_slug').val(response.game.slug || '').prop('disabled', false);
                                
                                $('#saveGameDetails').prop('disabled', false);
                            } else {
                                ToastManager.error('Erro ao carregar dados do jogo.');
                                $('#editGameDetailsModal').modal('hide');
                            }
                        },
                        error: function(xhr, status, error) {
                            ToastManager.error('Erro ao carregar dados do jogo.');
                            $('#editGameDetailsModal').modal('hide');
                        }
                    });
                };

                // Funções de múltiplos slugs removidas - agora usamos apenas um slug

                // Salvar alterações do jogo
                $('#saveGameDetails').click(function() {
                    const gameId = $('#edit_game_id').val();
                    const gameName = $('#edit_game_name').val().trim();
                    const gameSlug = $('#edit_game_slug').val().trim();
                    
                    // Validação básica
                    if (!gameName) {
                        ToastManager.error('Nome do jogo é obrigatório.');
                        return;
                    }
                    
                    // Mostrar indicador de carregamento
                    const processingToast = ToastManager.info('Salvando alterações...');
                    
                    $.ajax({
                        url: "{{ route('admin.cassino.update-game-details') }}",
                        type: "POST",
                        data: {
                            game_id: gameId,
                            name: gameName,
                            slug: gameSlug,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            processingToast.remove();
                            
                            if (response.success) {
                                ToastManager.success('Detalhes do jogo atualizados com sucesso!');
                                $('#editGameDetailsModal').modal('hide');
                                
                                // Recarregar a tabela para mostrar as alterações
                                if (typeof dataTable !== 'undefined' && dataTable) {
                                    dataTable.ajax.reload(null, false);
                                }
                            } else {
                                ToastManager.error('Erro: ' + (response.message || 'Erro desconhecido'));
                            }
                        },
                        error: function(xhr, status, error) {
                            processingToast.remove();
                            ToastManager.error('Erro ao salvar: ' + error);
                        }
                    });
                });

                // Função para atualizar campos do jogo (show_home, destaque, status)
                function confirmAndUpdateGameField(id, field, value, element) {
                    // Para esses campos, usar apenas toast de aviso sem confirmação
                    const processingToast = ToastManager.info('Processando, aguarde...');

                    $.ajax({
                        url: "{{ route('admin.cassino.jogo.atualizar') }}",
                        type: "POST",
                        data: {
                            id: id,
                            field: field,
                            value: value,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Remover toast de processamento
                            processingToast.remove();

                            if (response.success) {
                                ToastManager.success(response.message || 'Campo atualizado com sucesso!');
                            } else {
                                ToastManager.error(response.message || 'Erro ao atualizar o campo.');
                                // Reverter a mudança visual
                                $(element).prop('checked', !$(element).prop('checked'));
                            }
                        },
                        error: function() {
                            // Remover toast de processamento
                            processingToast.remove();

                            ToastManager.error('Erro ao processar a solicitação.');
                            // Reverter a mudança visual
                            $(element).prop('checked', !$(element).prop('checked'));
                        }
                    });
                }

                // Função para atualizar campos do slug (active)
                function confirmAndUpdateSlugField(id, field, value, element) {
                    // Para esses campos, usar apenas toast de aviso sem confirmação
                    const processingToast = ToastManager.info('Processando, aguarde...');

                    $.ajax({
                        url: "{{ route('admin.cassino.slug.atualizar') }}",
                        type: "POST",
                        data: {
                            id: id,
                            field: field,
                            value: value,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Remover toast de processamento
                            processingToast.remove();

                            if (response.success) {
                                ToastManager.success(response.message || 'Slug atualizado com sucesso!');
                            } else {
                                ToastManager.error(response.message || 'Erro ao atualizar o slug.');
                                // Reverter a mudança visual
                                $(element).prop('checked', !$(element).prop('checked'));
                            }
                        },
                        error: function() {
                            // Remover toast de processamento
                            processingToast.remove();

                            ToastManager.error('Erro ao processar a solicitação.');
                            // Reverter a mudança visual
                            $(element).prop('checked', !$(element).prop('checked'));
                        }
                    });
                }

                // Variáveis para controlar o upload de imagem do jogo
                let currentGameImageData = {
                    gameId: null,
                    gameName: null,
                    file: null,
                    fileInput: null
                };

                // Variável para controlar o debounce do modal de upload de imagem
                let uploadGameImageModalTimeout = null;
                let isUploadGameImageModalOpen = false;

                // Função para processar upload de imagem do jogo
                function processGameImageUpload(gameId, gameName, file, fileInput) {
                    // Verificar se já existe um modal de upload aberto
                    if (isUploadGameImageModalOpen) {
                        return;
                    }
                    
                    // Limpar timeout anterior se existir
                    if (uploadGameImageModalTimeout) {
                        clearTimeout(uploadGameImageModalTimeout);
                    }
                    

                    
                    // Validação de tipos de arquivo permitidos (incluindo MIME types alternativos para AVIF)
                    const allowedTypes = [
                        'image/jpeg', 
                        'image/jpg', 
                        'image/png', 
                        'image/gif', 
                        'image/webp', 
                        'image/avif',
                        'image/avif-sequence' // MIME type alternativo para AVIF
                    ];
                    
                    // Verificar também por extensão se o MIME type não for reconhecido
                    const fileExtension = file.name.toLowerCase().split('.').pop();
                    const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
                    
                    if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
                        ToastManager.error('Tipo de arquivo não permitido. Use apenas imagens JPG, PNG, GIF, WEBP ou AVIF.');
                        fileInput.value = '';
                        return;
                    }
                    
                    // Validação de tamanho do arquivo (máximo 5MB)
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    if (file.size > maxSize) {
                        ToastManager.error('Arquivo muito grande. O tamanho máximo permitido é 5MB.');
                        fileInput.value = '';
                        return;
                    }
                    
                    // Armazenar dados do jogo atual
                    currentGameImageData = {
                        gameId: gameId,
                        gameName: gameName,
                        file: file,
                        fileInput: fileInput
                    };
                    
                    // Definir timeout para evitar múltiplas chamadas
                    uploadGameImageModalTimeout = setTimeout(() => {
                        // Marcar que o modal está aberto
                        isUploadGameImageModalOpen = true;
                        
                        // Mostrar modal de confirmação
                        ModalManager.showConfirmation(
                            'Confirmar Upload',
                            `Deseja realmente fazer upload desta imagem para o jogo "${gameName}"?`,
                            function() {
                                // Callback de confirmação
                                executeGameImageUpload();
                                // Resetar variável de controle
                                isUploadGameImageModalOpen = false;
                            },
                            function() {
                                // Callback de cancelamento
                                fileInput.value = '';
                                // Resetar variável de controle
                                isUploadGameImageModalOpen = false;
                            }
                        );
                    }, 100); // Debounce de 100ms
                }

                // Função para executar o upload da imagem do jogo após confirmação
                function executeGameImageUpload() {
                    // Extrair dados do objeto temporário
                    const { gameId, gameName, file, fileInput } = currentGameImageData;
                    

                    
                    // Mostrar toast de "processando"
                    const processingToast = ToastManager.info('Enviando imagem do jogo, aguarde...');
                    
                    // Criar FormData
                    const formData = new FormData();
                    formData.append('image', file);
                    formData.append('game_id', gameId);
                    formData.append('game_name', gameName);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    

                    
                    // Fazer o upload via AJAX
                    fetch('/admin/cassino/update-game-image', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 500) {
                                throw new Error('Erro interno do servidor (500). Verifique se o diretório de upload existe e tem permissões adequadas.');
                            }
                            return response.text().then(text => {
                                throw new Error(`Erro ${response.status}: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Atualizar a imagem exibida
                            const imagePreview = document.getElementById(`gameImagePreview${gameId}`);
                            
                            // Usar a URL correta do banco de dados
                            const imageUrl = data.image_url || data.image_path;
                            
                            // Criar um timestamp para evitar cache da imagem
                            const timestamp = new Date().getTime();
                            
                            // Atualizar o conteúdo do preview mantendo a funcionalidade de clique
                            imagePreview.innerHTML = `
                                <img src="${imageUrl}?t=${timestamp}" 
                                    alt="${gameName}" 
                                    class="img-fluid" 
                                    style="width: 100%; height: 100%; object-fit: contain;"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center;">
                                    <i class="fa fa-image text-muted" style="font-size: 20px;"></i>
                                </div>
                            `;
                            
                            // Atualizar o onclick do preview para usar a nova imagem
                            imagePreview.setAttribute('onclick', `openGameImageModal('${gameId}', '${gameName}', '${imageUrl}')`);
                            
                            // Mostrar mensagem de sucesso com informações sobre a conversão
                            let successMessage = 'Imagem do jogo enviada com sucesso!';
                            if (data.game && data.game.image && data.game.image.endsWith('.webp')) {
                                successMessage += ' (Convertida para WebP com transparência)';
                            }
                            ToastManager.success(successMessage);
                            
                            // Recarregar a imagem do banco de dados após um pequeno delay
                            setTimeout(() => {
                                refreshGameImageFromDatabase(gameId, gameName);
                            }, 500);
                        } else {
                            ToastManager.error('Erro ao enviar imagem: ' + (data.message || 'Erro desconhecido'));
                        }
                    })
                    .catch(error => {
                        ToastManager.error('Erro: ' + error.message);
                    })
                    .finally(() => {
                        // Remover toast de processamento
                        processingToast.remove();
                        
                        // Resetar variáveis de controle
                        isUploadGameImageModalOpen = false;
                        
                        // Limpar o input file
                        fileInput.value = '';
                    });
                }

                // Função para inicializar os inputs de upload de imagem do jogo
                function initializeGameImageInputs() {
                    document.querySelectorAll('.game-image-input').forEach(input => {
                        // Verificar se já tem event listener para evitar duplicação
                        if (!input.hasAttribute('data-listener-added')) {
                            input.addEventListener('change', function(e) {
                                // Verificar se o modal já está aberto
                                if (isUploadGameImageModalOpen) {
                                    return;
                                }
                                
                                const file = this.files[0];
                                if (!file) {
                                    return;
                                }
                                
                                const gameId = this.getAttribute('data-game-id');
                                const gameName = this.getAttribute('data-game-name');
                                
                                processGameImageUpload(gameId, gameName, file, this);
                            });
                            
                            input.setAttribute('data-listener-added', 'true');
                        }
                    });
                }

                // Função para recarregar a imagem do jogo do banco de dados
                function refreshGameImageFromDatabase(gameId, gameName) {
                    
                    // Fazer requisição para buscar dados atualizados do jogo
                    fetch('/admin/cassino/get-game-image', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            game_id: gameId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.game && data.game.image) {
                            const imagePreview = document.getElementById(`gameImagePreview${gameId}`);
                            const imageUrl = data.game.image_url;
                            const timestamp = new Date().getTime();
                            
                            // Atualizar o preview com a URL do banco de dados
                            imagePreview.innerHTML = `
                                <img src="${imageUrl}?t=${timestamp}" 
                                    alt="${gameName}" 
                                    class="img-fluid" 
                                    style="width: 100%; height: 100%; object-fit: contain;"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center;">
                                    <i class="fa fa-image text-muted" style="font-size: 20px;"></i>
                                </div>
                            `;
                            
                            // Atualizar o onclick do preview
                            imagePreview.setAttribute('onclick', `openGameImageModal('${gameId}', '${gameName}', '${imageUrl}')`);
                        }
                    })
                    .catch(error => {
                        // Erro silencioso
                    });
                }

                // Função para abrir o modal de visualização da imagem do jogo
                function openGameImageModal(gameId, gameName, imageUrl) {
                    // Verificar se a URL da imagem está vazia
                    if (!imageUrl || imageUrl.trim() === '') {
                        ToastManager.error('Este jogo não possui imagem para visualizar.');
                        return;
                    }
                    
                    const modalContent = document.getElementById('gameImageContent');
                    const modalLabel = document.getElementById('gameImageModalLabel');
                    
                    // Atualizar o título do modal
                    modalLabel.textContent = `Imagem do Jogo: ${gameName}`;
                    
                    // Mostrar indicador de carregamento
                    modalContent.innerHTML = `
                        <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                        </div>
                    `;
                    
                    // Abrir o modal
                    const gameImageModal = new bootstrap.Modal(document.getElementById('gameImageModal'));
                    gameImageModal.show();
                    
                    // Criar elemento de imagem para verificar se carrega
                    const img = new Image();
                    
                    img.onload = function() {
                        // Imagem carregada com sucesso
                        modalContent.innerHTML = `
                            <img src="${imageUrl}?t=${new Date().getTime()}" 
                                alt="Imagem do Jogo ${gameName}" 
                                class="img-fluid" 
                                style="max-height: 70vh; max-width: 100%; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        `;
                    };
                    
                    img.onerror = function() {
                        // Erro ao carregar a imagem
                        modalContent.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle me-2"></i>
                                <strong>Erro:</strong> Não foi possível carregar a imagem.<br>
                                <small>URL: ${imageUrl}</small>
                            </div>
                        `;
                    };
                    
                    // Tentar carregar a imagem
                    img.src = imageUrl + '?t=' + new Date().getTime();
                }

                // Inicializar os inputs de imagem ao carregar a página
                initializeGameImageInputs();
            </script>
        @endpush
@endsection
