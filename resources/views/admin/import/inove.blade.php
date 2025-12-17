@extends('admin.layouts.app')

@section('content')
<div class="layout-px-spacing">
    <div class="middle-content container-xxl p-0">
        <!-- BREADCRUMB -->
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Cassino</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Importar Jogos - Inove Gaming</li>
                </ol>
            </nav>
        </div>
        <!-- /BREADCRUMB -->
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <form action="#" method="POST" id="importForm">
                                @csrf
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2" style="padding: 20px;">
                                        <div>
                                            <button type="button" class="btn btn-primary" id="importButton" disabled>
                                                <i class="fas fa-database"></i> Carregando jogos...
                                            </button>
                                            <button type="button" class="btn btn-success ms-2" id="importProvidersButton">
                                                <i class="fas fa-download"></i> Importar Provedores
                                            </button>
                                            <button type="button" class="btn btn-warning ms-2" id="updateProvidersButton">
                                                <i class="fas fa-sync-alt"></i> Atualizar Provedores
                                            </button>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="me-3 mb-2 mb-sm-0">
                                                <select id="statusFilter" class="form-select">
                                                    <option value="all">Todos os status</option>
                                                    <option value="not-imported">Não importados</option>
                                                                    <option value="imported">Já importados</option>
                                                </select>
                                            </div>
                                            <div class="me-3 mb-2 mb-sm-0">
                                                <select id="inoveProviderFilter" class="form-select" title="Selecione um provedor específico da Inove Gaming">
                                                    <option value="all">Todos os provedores</option>
                                                    <!-- Será preenchido dinamicamente com os provedores disponíveis -->
                                                </select>
                                            </div>
                                            <div class="me-3 mb-2 mb-sm-0">
                                                <select id="categoryFilter" class="form-select" title="Filtrar por categoria">
                                                    <option value="all">Todas as categorias</option>
                                                    <!-- Será preenchido dinamicamente com as categorias disponíveis -->
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Loading state -->
                                    <div id="loadingState" class="text-center p-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Carregando...</span>
                                        </div>
                                        <p class="mt-2">Carregando jogos da Inove Gaming... <span id="loadingProgress">0</span>%</p>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                    <!-- Estatísticas de jogos -->
                                    <div id="gameStats" class="row mb-3" style="display: none; padding: 0 20px;">
                                        <div class="col-md-3">
                                            <div class="card text-center">
                                                <div class="card-body p-2">
                                                    <h5 class="card-title text-success mb-1" id="slotCount">0</h5>
                                                    <small class="text-muted">Slots</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card text-center">
                                                <div class="card-body p-2">
                                                    <h5 class="card-title text-warning mb-1" id="tableCount">0</h5>
                                                    <small class="text-muted">Jogos de Mesa</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card text-center">
                                                <div class="card-body p-2">
                                                    <h5 class="card-title text-info mb-1" id="totalGames">0</h5>
                                                    <small class="text-muted">Total de Jogos</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card text-center">
                                                <div class="card-body p-2">
                                                    <h5 class="card-title text-primary mb-1" id="visibleGames">0</h5>
                                                    <small class="text-muted">Jogos Visíveis</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive" id="tableContainer" style="display: none;">
                                        <table id="zero-config" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                            <thead>
                                                <tr role="row">
                                                    <th width="5%">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                                            <label class="form-check-label" for="selectAll"></label>
                                                        </div>
                                                    </th>
                                                    <th class="sorting_asc text-center" tabindex="0" aria-controls="zero-config" rowspan="1" colspan="1" aria-sort="ascending" style="width: 15%;">Imagem</th>
                                                    <th class="sorting" tabindex="0" aria-controls="zero-config" rowspan="1" colspan="1" style="width: 30%;">Nome do Jogo</th>
                                                    <th class="sorting" tabindex="0" aria-controls="zero-config" rowspan="1" colspan="1" style="width: 15%;">Provedor</th>
                                                    <th class="sorting" tabindex="0" aria-controls="zero-config" rowspan="1" colspan="1" style="width: 15%;">Categoria</th>
                                                    <th class="sorting" tabindex="0" aria-controls="zero-config" rowspan="1" colspan="1" style="width: 10%;">ID</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Os dados serão carregados via JavaScript -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Selecionar</th>
                                                    <th class="text-center">Imagem</th>
                                                    <th>Nome do Jogo</th>
                                                    <th>Provedor</th>
                                                    <th>Categoria</th>
                                                    <th>ID</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Progresso de Importação -->
<div class="modal fade" id="importProgressModal" tabindex="-1" aria-labelledby="importProgressModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importProgressModalLabel">
                    <i class="fas fa-download"></i> Progresso da Importação
                </h5>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold">Progresso Geral:</span>
                        <span id="progressText">0 / 0 jogos processados</span>
                    </div>
                    <div class="progress mb-3" style="height: 20px;">
                        <div id="importProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            0%
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Status Atual:</strong>
                    <div id="currentStatus" class="mt-2 p-2 bg-light rounded">
                        Preparando importação...
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Jogos Processados:</strong>
                    <div id="processedGamesList" class="mt-2" style="max-height: 300px; overflow-y: auto;">
                        <!-- Lista de jogos processados -->
                    </div>
                </div>
                
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-body p-2">
                                <h5 class="card-title text-success mb-1" id="importedCount">0</h5>
                                <small class="text-muted">Importados</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-warning">
                            <div class="card-body p-2">
                                <h5 class="card-title text-warning mb-1" id="updatedCount">0</h5>
                                <small class="text-muted">Atualizados</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-danger">
                            <div class="card-body p-2">
                                <h5 class="card-title text-danger mb-1" id="errorCount">0</h5>
                                <small class="text-muted">Erros</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeProgressModal" disabled>
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    body.dark .form-check-input:checked {
        background-color: #4361ee !important;
    }
    
    /* Estilo para importando */
    tr.importing {
        background-color: rgba(67, 97, 238, 0.1) !important;
    }
    
    /* Estilo para jogos já importados */
    tr.imported-game {
        background-color: rgba(25, 135, 84, 0.05) !important;
    }
    
    /* Estilos para imagens dos jogos */
    .usr-img-frame {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 8px;
        background: #f5f5f5;
    }
    
    body.dark .usr-img-frame {
        background: #2a2a2a;
    }
    
    .usr-img-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .usr-img-frame img:hover {
        transform: scale(1.05);
    }
    
    /* Placeholder para imagens que falharam */
    .usr-img-frame img[src*="data:image/svg"] {
        opacity: 0.3;
        filter: grayscale(100%);
    }
    
</style>
@push('scripts')
<script>
    /**
     * Script de importação de jogos da Inove Gaming - Versão adaptada
     */
    $(document).ready(function() {
        // Verificar se este é realmente a página Inove para evitar conflitos
        if (!document.location.pathname.includes('/inove') && !document.getElementById('inoveProviderFilter')) {
            return; // Sair se não estiver na página Inove
        }

        // Verificar se já existe uma instância do script rodando
        if (window.inoveScriptLoaded) {
            return; // Evitar múltiplas execuções
        }
        window.inoveScriptLoaded = true;
        
        // Variáveis globais
        let allGames = []; // Array com todos os jogos da API
        let existingGames = []; // Jogos já existentes no banco de dados (Inove)
        let table; // Referência à DataTable
        let isLoading = false; // Controle de carregamento
        let providers = new Set(); // Conjunto de provedores disponíveis
        let categories = new Set(); // Conjunto de categorias disponíveis
        
        // Função para normalizar nome do provedor (mesma lógica do backend)
        function normalizeProviderName(providerName) {
            if (!providerName) return '';
            
            // Converter para maiúsculas mantendo a estrutura original
            let result = providerName.toUpperCase().replace(/_/g, ' ');
            
            // Detecta: oficial, Oficial, OFICIAL, OfIcIaL, etc.
            if (/oficial/i.test(result)) {
                result = result.replace(/oficial/gi, 'ORIGINAL');
            }
            
            return result;
        }
        
        // Inicializar DataTable com configurações otimizadas
        initializeDataTable();
        
        // Carregar jogos da Inove Gaming
        loadinoveGames();
        
        // Função para inicializar o DataTable com configurações otimizadas
        function initializeDataTable() {
            table = $('#zero-config').DataTable({
                responsive: true,
                pageLength: 50,
                deferRender: true, // Renderização sob demanda
                processing: true, // Mostrar indicador de processamento
                language: {
                    paginate: {
                        previous: "<i class='fas fa-chevron-left'></i>",
                        next: "<i class='fas fa-chevron-right'></i>"
                    },
                    info: "Mostrando _START_ até _END_ de _TOTAL_ jogos",
                    lengthMenu: "Mostrar _MENU_ jogos",
                    search: "Buscar:",
                    emptyTable: "Nenhum jogo disponível",
                    zeroRecords: "Nenhum jogo encontrado",
                    processing: "Processando..."
                },
                "columnDefs": [
                    { "orderable": false, "targets": 0 } // Desabilitar ordenação na coluna de checkbox
                ],
                "order": [[2, 'asc']] // Ordenar por nome do jogo por padrão
            });
        }

        // Função para carregar jogos da Inove Gaming
        function loadinoveGames() {
            console.log('loadinoveGames iniciada - versão adaptada');
            
            // Verificar se já está carregando para evitar múltiplas execuções
            if (isLoading) {
                console.log('loadinoveGames já está executando, ignorando chamada duplicada');
                return;
            }
            
            // Exibir estado de carregamento
            showLoadingState();
            
            // Limpar a tabela
            resetTable();
            
            // Definir flag de carregamento
            isLoading = true;
            
            // Obter jogos existentes primeiro
            $.ajax({
                url: "{{ route('admin.inove.existing-games') }}",
                type: "GET",
                dataType: "json",
                success: function(existingResponse) {
                    if (existingResponse.success) {
                        // Armazenar os jogos existentes da Inove para verificação rápida
                        existingGames = existingResponse.existingGames || [];

                        // Chamar a API da Inove Gaming
                        $.ajax({
                            url: "{{ route('admin.inove.games') }}",
                            type: "GET",
                            dataType: "json",
                            beforeSend: function() {
                                ToastManager.info('Carregando jogos da Inove Gaming...');
                            },
                            success: function(inoveResponse) {
                                if (inoveResponse.success) {
                                    // Verificar se há jogos
                                    if (!inoveResponse.games || inoveResponse.games.length === 0) {
                                        ToastManager.warning('Nenhum jogo encontrado na API da Inove Gaming.');
                                        hideLoadingState();
                                        return;
                                    }

                                    // Armazenar os jogos para processamento em lotes
                                    allGames = inoveResponse.games || [];

                                    // Processar os jogos em lotes para não travar a interface
                                    processinoveGamesInBatches(allGames);
                                } else {
                                    console.error('inove API Error:', inoveResponse);
                                    ToastManager.error('Erro ao buscar jogos da Inove Gaming: ' + (inoveResponse.message || 'Resposta inválida da API'));
                                    hideLoadingState();
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('inove API Request Error:', {xhr: xhr, status: status, error: error});
                                ToastManager.error('Erro ao conectar com a API da Inove Gaming: ' + error);
                                hideLoadingState();
                            }
                        });
                    } else {
                        ToastManager.error('Erro ao verificar jogos existentes: ' + existingResponse.message);
                        hideLoadingState();
                    }
                },
                error: function(xhr, status, error) {
                    ToastManager.error('Erro ao verificar jogos existentes: ' + error);
                    hideLoadingState();
                }
            });
        }
        
        // Função para exibir o estado de carregamento
        function showLoadingState() {
            $('#tableContainer').hide();
            $('#loadingState').show();
            $('#progressBar').css('width', '0%');
            $('#loadingProgress').text('0');
            $('#importButton').prop('disabled', true);
        }
        
        // Função para atualizar o progresso de carregamento
        function updateLoadingProgress(percent) {
            $('#progressBar').css('width', percent + '%');
            $('#loadingProgress').text(Math.round(percent));
        }
        
        // Função para esconder o estado de carregamento e exibir a tabela
        function hideLoadingState() {
            $('#loadingState').hide();
            $('#tableContainer').show();
            isLoading = false;
            
            // Atualizar o texto do botão de importação
            updateImportButtonText();
        }
        
        // Função para limpar e reiniciar a tabela
        function resetTable() {
            table.clear().draw();
            allGames = [];
            existingGames = [];
            providers = new Set();

            // Desabilitar o botão de importação até que os jogos sejam carregados
            $('#importButton').prop('disabled', true);
        }
        
        // Função para processar jogos da Inove Gaming em lotes
        function processinoveGamesInBatches(games) {
            const batchSize = 100; // Número de jogos por lote
            const totalGames = games.length;
            let processedGames = 0;
            
            // Função para processar um lote de jogos
            function processBatch(startIndex) {
                const endIndex = Math.min(startIndex + batchSize, totalGames);
                const batch = games.slice(startIndex, endIndex);
                
                // Processar cada jogo do lote
                batch.forEach(game => {
                    addinoveGameToTable(game);
                });
                
                // Atualizar progresso
                processedGames += batch.length;
                const progress = (processedGames / totalGames) * 100;
                updateLoadingProgress(progress);
                
                // Se ainda houver jogos a processar, agendar o próximo lote
                if (endIndex < totalGames) {
                    setTimeout(() => {
                        processBatch(endIndex);
                    }, 0); // Sem delay, mas permite que a UI responda
                } else {
                    // Todos os jogos foram processados
                    populateinoveProviderFilter(providers);
                    populateinoveCategoryFilter(categories);
                    finalizeLoading();
                }
            }
            
            // Iniciar o processamento do primeiro lote
            processBatch(0);
        }
        
        // Função para finalizar o carregamento
        function finalizeLoading() {
            // Esconder o estado de carregamento
            hideLoadingState();
            
            // Mostrar estatísticas
            $('#gameStats').show();
            updateGameStats();
            
            // Aplicar filtros iniciais
            applyFilters();
            
            // Atualizar texto do botão de importação
            updateImportButtonText();
            
            ToastManager.success('Jogos carregados com sucesso!');
        }
        
        // Função para atualizar estatísticas de jogos
        function updateGameStats() {
            const totalRows = table.rows().nodes();
            let slotCount = 0;
            let tableCount = 0;

            $(totalRows).each(function() {
                const gameCategory = $(this).attr('data-game-category');
                if (gameCategory === 'slot') {
                    slotCount++;
                } else if (gameCategory === 'table') {
                    tableCount++;
                }
            });

            const totalGames = slotCount + tableCount;
            const visibleGames = table.rows({search: 'applied'}).count();

            $('#slotCount').text(slotCount);
            $('#tableCount').text(tableCount);
            $('#totalGames').text(totalGames);
            $('#visibleGames').text(visibleGames);
        }

        // Função para adicionar um jogo da Inove Gaming à tabela
        function addinoveGameToTable(game) {
            // Verificar se o jogo é válido
            if (!game || !game.id || !game.name) {
                console.warn('Jogo inválido encontrado:', game);
                return;
            }
            
            // Debug: Log do primeiro jogo para verificar estrutura
            if (allGames.length === 1) {
                console.log('=== ESTRUTURA DA API INOVE ===');
                console.log('Jogo:', game);
                console.log('Imagem:', game.slugs[0]?.image);
                console.log('Slug:', game.slugs[0]?.slug);
                console.log('================================');
            }
            
            try {
                // Usar o código do provedor para o campo 'name' e o provider_name para gravação
                let providerCode = game.provider || 'DESCONHECIDO';
                let originalProviderName = game.provider_name || 'Desconhecido';

                // Usar o código do provedor para exibição e agrupamento
                providers.add(providerCode);

                // Adicionar categoria
                const gameCategory = game.category || 'other';
                categories.add(gameCategory);

                let gameExists = false;
                let gameID = null;
                let existingGameData = null;

                // Validação usando a nova estrutura consolidada (games_api):
                // 1. slug deve existir na coluna slug da tabela games_api
                // 2. name deve bater com a coluna name da tabela games_api
                // 3. provider_id deve bater com o provedor correto
                // 4. status = 1 indica jogo ativo (não usa mais campo active)
                let existsWithSlug = false;

                // Obter slug do jogo da Inove (ID do jogo)
                const slug = game.id;

                // Procurar jogo com mesmo slug específico na tabela consolidada games_api
                existingGames.forEach(existingGame => {
                    if (existingGame.slug === slug.toString()) {
                        existsWithSlug = true;
                        gameExists = true;
                        existingGameData = existingGame;
                        gameID = existingGame.id;
                        currentSluginove = slug;
                    }
                });
                
                // Definir classe da linha e status do checkbox
                let rowClass = '';
                let isDisabled = false;
                let statusBadge = '';

                if (existsWithSlug) {
                    // Jogo já existe com este slug específico da Inove
                    rowClass = 'imported-game';
                    isDisabled = true;
                    statusBadge = '<span class="badge badge-success">Já importado</span>';
                } else {
                    // Jogo não existe ou não tem este slug específico
                    rowClass = '';
                    isDisabled = false;
                    statusBadge = '<span class="badge badge-secondary">Não importado</span>';
                }

                // Adicionar classe para categoria de jogo
                rowClass += (rowClass ? ' ' : '') + gameCategory + '-game';
                
                // Preparar HTML para imagem - estrutura atual da API
                // A imagem está dentro de game.slugs[0].image
                let gameImageUrl = (game.slugs && game.slugs.length > 0 && game.slugs[0].image) 
                    ? game.slugs[0].image.toString().trim() 
                    : '';
                
                // Usar proxy para evitar problemas de CORS
                let proxiedImageUrl = '';
                if (gameImageUrl) {
                    proxiedImageUrl = "{{ route('admin.inove.proxy-image') }}?url=" + encodeURIComponent(gameImageUrl);
                }
                
                // SVG placeholder para quando não houver imagem
                const placeholderSvg = 'data:image/svg+xml,%3Csvg xmlns=\"http://www.w3.org/2000/svg\" width=\"80\" height=\"80\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%23888\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"%3E%3Crect x=\"3\" y=\"3\" width=\"18\" height=\"18\" rx=\"2\" ry=\"2\"%3E%3C/rect%3E%3Ccircle cx=\"8.5\" cy=\"8.5\" r=\"1.5\"%3E%3C/circle%3E%3Cpolyline points=\"21,15 16,10 5,21\"%3E%3C/polyline%3E%3C/svg%3E';
                
                const imageHtml = `<div class="d-flex justify-content-center">
                    <div class="usr-img-frame me-2">
                        <img alt="${game.name}" 
                             class="img-fluid game-thumbnail" 
                             src="${proxiedImageUrl || placeholderSvg}"
                             onerror="this.onerror=null; this.src='${placeholderSvg}'; console.warn('Erro ao carregar imagem via proxy:', '${gameImageUrl}');"
                             loading="lazy">
                    </div>
                </div>`;
                
                // Adicionar linha à tabela
                const newRow = table.row.add([
                    `<div class="form-check">
                        <input class="form-check-input game-checkbox" type="checkbox"
                            name="selected_games[]"
                            value="${game.id}"
                            data-game-name="${game.name}"
                            ${isDisabled ? 'disabled' : ''}
                            id="game-${game.id}">
                        <label class="form-check-label" for="game-${game.id}"></label>
                    </div>`,
                    imageHtml,
                    `${game.name} ${statusBadge}`,
                    `<span class="badge badge-light-info mb-2 me-4">${providerCode}</span>`,
                    ucfirst(gameCategory),
                    game.id
                ]).node();
                
                // Adicionar classes e atributos de dados à linha
                $(newRow).addClass(rowClass);
                $(newRow).attr('data-name', game.name.toLowerCase());
                $(newRow).attr('data-exists', existsWithSlug ? 'true' : 'false');
                $(newRow).attr('data-status', existsWithSlug ? 'imported' : 'not-imported');
                $(newRow).attr('data-game-name', game.name);
                $(newRow).attr('data-game-id', game.id);
                $(newRow).attr('data-provider-id', providerCode.toLowerCase());
                $(newRow).attr('data-provider-type', 'inove');
                $(newRow).attr('data-provider-name', providerCode.toLowerCase());
                $(newRow).attr('data-game-category', gameCategory);
            } catch (error) {
                console.error('Erro ao processar jogo Inove Gaming:', error, game);
            }
        }
        
        // Função para popular o filtro de provedores da Inove Gaming
        function populateinoveProviderFilter(providers) {
            const filter = $('#inoveProviderFilter');
            filter.empty().append('<option value="all">Todos os provedores</option>');

            const sortedProviders = Array.from(providers).sort();
            sortedProviders.forEach(provider => {
                filter.append(`<option value="${provider.toLowerCase()}">${provider}</option>`);
            });
        }

        // Função para popular o filtro de categorias da Inove Gaming
        function populateinoveCategoryFilter(categories) {
            const filter = $('#categoryFilter');
            filter.empty().append('<option value="all">Todas as categorias</option>');

            const sortedCategories = Array.from(categories).sort();
            sortedCategories.forEach(category => {
                filter.append(`<option value="${category.toLowerCase()}">${ucfirst(category)}</option>`);
            });
        }

        // Função auxiliar para deixar primeira letra maiúscula
        function ucfirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
        
        // Função para aplicar filtros
        function applyFilters() {
            const statusFilter = $('#statusFilter').val();
            const inoveProviderFilter = $('#inoveProviderFilter').val();
            const categoryFilter = $('#categoryFilter').val();
            
            // Limpar filtros anteriores
            $.fn.dataTable.ext.search.pop();
            
            // Aplicar filtros customizados
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    const row = table.row(dataIndex).node();
                    const status = $(row).data('status');
                    const providerId = $(row).data('provider-id');
                    const gameCategory = $(row).data('game-category');
                    
                    // Verificação de status
                    if (statusFilter !== 'all') {
                        if (statusFilter === 'not-imported' && status !== 'not-imported') {
                            return false;
                        }
                        if (statusFilter === 'imported' && status !== 'imported') {
                            return false;
                        }
                    }
                    
                    // Verificação de provedor
                    if (inoveProviderFilter !== 'all') {
                        if (providerId !== inoveProviderFilter) {
                            return false;
                        }
                    }

                    // Verificação de categoria
                    if (categoryFilter !== 'all') {
                        if (gameCategory !== categoryFilter) {
                            return false;
                        }
                    }
                    
                    return true;
                }
            );
            
            // Redesenhar a tabela com os filtros aplicados
            table.draw();
            
            // Atualizar estatísticas
            updateGameStats();
            
            // Resetar checkbox "Selecionar todos"
            $('#selectAll').prop('checked', false);
            // Atualizar o estado do checkbox "Selecionar todos"
            updateSelectAllCheckbox();
            
            // Atualizar texto do botão de importação
            updateImportButtonText();
        }
        
        // Função para atualizar o texto do botão de importação
        function updateImportButtonText() {
            const selectedCount = $('.game-checkbox:checked:not(:disabled)').length;
            const button = $('#importButton');
            
            // Contar jogos visíveis que podem ser importados/atualizados
            const visibleActionableGames = table.$('tr', {search: 'applied'}).find('.game-checkbox:not(:disabled)').length;
            
            if (selectedCount > 0) {
                button.prop('disabled', false);
                button.html(`<i class="fas fa-database"></i> Importar/Atualizar ${selectedCount} Selecionados`);
            } else if (visibleActionableGames > 0) {
                // Quando nenhum jogo estiver selecionado, mostrar "Importar Todos os Jogos"
                button.prop('disabled', false);
                button.html(`<i class="fas fa-database"></i> Importar Todos os Jogos (${visibleActionableGames})`);
            } else {
                button.prop('disabled', true);
                button.html('<i class="fas fa-database"></i> Nenhum Jogo para Importar');
            }
        }
        
        // Função para atualizar o estado do checkbox "Selecionar todos"
        function updateSelectAllCheckbox() {
            const visibleCheckboxes = table.$('tr', {search: 'applied'}).find('.game-checkbox:not(:disabled)');
            const checkedCheckboxes = table.$('tr', {search: 'applied'}).find('.game-checkbox:checked:not(:disabled)');
            
            const selectAllCheckbox = $('#selectAll');
            
            if (visibleCheckboxes.length === 0) {
                selectAllCheckbox.prop('indeterminate', false);
                selectAllCheckbox.prop('checked', false);
            } else if (checkedCheckboxes.length === visibleCheckboxes.length) {
                selectAllCheckbox.prop('indeterminate', false);
                selectAllCheckbox.prop('checked', true);
            } else if (checkedCheckboxes.length > 0) {
                selectAllCheckbox.prop('indeterminate', true);
                selectAllCheckbox.prop('checked', false);
            } else {
                selectAllCheckbox.prop('indeterminate', false);
                selectAllCheckbox.prop('checked', false);
            }
        }
        
        // Função para importar jogos selecionados
        function importSelectedGames() {
            const selectedGames = $('.game-checkbox:checked:not(:disabled)');
            let gameIds = [];
            let importMessage;
            
            // Determinar quais jogos serão importados
            if (selectedGames.length > 0) {
                // Importar apenas jogos selecionados
                selectedGames.each(function() {
                    gameIds.push($(this).val());
                });
                importMessage = `Deseja importar/atualizar ${selectedGames.length} jogos selecionados?`;
            } else {
                // Importar jogos visíveis filtrados não desabilitados
                table.rows({ search: 'applied' }).nodes().each((row) => {
                    const checkbox = $(row).find('.game-checkbox:not(:disabled)');
                    if (checkbox.length > 0) {
                        gameIds.push(checkbox.val());
                    }
                });
                
                if (gameIds.length === 0) {
                    ToastManager.error('Não há jogos disponíveis para importação no filtro atual.');
                    return;
                }
                
                // Quando nenhum jogo estiver selecionado, mostrar mensagem para importar todos
                importMessage = `Deseja importar TODOS os ${gameIds.length} jogos disponíveis?`;
            }
            
            // Confirmar a operação
            ModalManager.showConfirmation(
                'Confirmar Operação',
                importMessage,
                function() {
                    // Verificar se devemos usar o modal de progresso (mais de 15 jogos)
                    if (gameIds.length > 15) {
                        importGamesWithProgressModal(gameIds);
                    } else {
                        importGamesNormal(gameIds);
                    }
                }
            );
        }
        
        // Função para importar jogos normalmente (sem modal, para 5 jogos ou menos)
        function importGamesNormal(gameIds) {
            // Desabilitar o botão durante a importação
            $('#importButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processando...');
            
            // Marcar linhas como importando
            gameIds.forEach(gameCode => {
                $(`#game-${gameCode}`).closest('tr').addClass('importing');
            });
            
            // Nova estrutura não precisa coletar slugs separadamente

            // Fazer a requisição de importação
            $.ajax({
                url: "{{ route('admin.inove.import') }}",
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    games: gameIds
                },
                dataType: "json",
                timeout: 120000, // 2 minutos de timeout
                success: function(response) {
                    console.log('Import Response:', response);
                    
                    if (response.success) {
                        let message = response.message;
                        if (response.imported_count > 0 || response.updated_count > 0) {
                            ToastManager.success(message);
                        } else {
                            ToastManager.info(message);
                        }
                        
                        // Atualizar as linhas dos jogos processados
                        if (response.games) {
                            Object.keys(response.games).forEach(gameCode => {
                                updateGameRow(gameCode, response.games[gameCode]);
                            });
                        }
                        
                        // Para compatibilidade com versões antigas
                        if (response.imported_games) {
                            Object.keys(response.imported_games).forEach(gameCode => {
                                updateGameRow(gameCode, response.imported_games[gameCode]);
                            });
                        }
                        
                        // Aplicar filtros para atualizar a visualização
                        applyFilters();
                    } else {
                        ToastManager.error('Erro na importação: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Import Error:', {xhr: xhr, status: status, error: error});
                    let errorMessage = 'Erro ao importar jogos: ' + error;
                    
                    if (xhr.status === 0) {
                        errorMessage = 'Erro de conexão. Verifique sua internet e tente novamente.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Erro interno do servidor. Verifique os logs do sistema.';
                    } else if (xhr.status === 422) {
                        errorMessage = 'Dados inválidos enviados para o servidor.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = 'Erro na importação: ' + xhr.responseJSON.message;
                    }
                    
                    ToastManager.error(errorMessage);
                },
                complete: function() {
                    // Restaurar o botão de importação
                    resetImportButton();
                    
                    // Remover classe de importação de todas as linhas
                    $('tr.importing').removeClass('importing');
                }
            });
        }
        
        // Função para importar jogos com modal de progresso (mais de 5 jogos)
        function importGamesWithProgressModal(gameIds) {
            // Inicializar o modal de progresso
            initializeProgressModal(gameIds.length);
            
            // Desabilitar o botão durante a importação
            $('#importButton').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processando...');
            
            // Mostrar o modal
            $('#importProgressModal').modal('show');
            
            // Marcar linhas como importando
            gameIds.forEach(gameCode => {
                $(`#game-${gameCode}`).closest('tr').addClass('importing');
            });
            
            // Variáveis de controle do progresso
            let processedCount = 0;
            let importedCount = 0;
            let updatedCount = 0;
            let errorCount = 0;
            
            // Atualizar status inicial
            updateCurrentStatus('Iniciando importação...');
            
            // Processar jogos em lotes menores para mostrar progresso
            const batchSize = 50; // Inove pode processar mais por lote - OTIMIZADO
            let currentBatch = 0;
            
            function processBatch() {
                const start = currentBatch * batchSize;
                const end = Math.min(start + batchSize, gameIds.length);
                const batchGameIds = gameIds.slice(start, end);
                
                if (batchGameIds.length === 0) {
                    // Finalizar importação
                    finalizeImportWithProgress();
                    return;
                }
                
                updateCurrentStatus(`Processando lote ${currentBatch + 1}... (${batchGameIds.length} jogos)`);
                
                // Nova estrutura não precisa coletar slugs separadamente

                // Fazer a requisição de importação para este lote
                $.ajax({
                    url: "{{ route('admin.inove.import') }}",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        games: batchGameIds
                    },
                    dataType: "json",
                    timeout: 180000, // 3 minutos de timeout por lote - AUMENTADO
                    success: function(response) {
                        console.log('Batch Import Response:', response);
                        
                        if (response.success) {
                            // Atualizar as linhas dos jogos processados
                            if (response.games) {
                                Object.keys(response.games).forEach(gameCode => {
                                    const gameData = response.games[gameCode];
                                    updateGameRow(gameCode, gameData);
                                    
                                    // Adicionar à lista de jogos processados
                                    addToProcessedGamesList(gameCode, gameData);
                                    
                                    // Atualizar contadores
                                    if (gameData.status === 'imported') {
                                        importedCount++;
                                    } else if (gameData.status === 'updated') {
                                        updatedCount++;
                                    } else if (gameData.status === 'already_exists') {
                                        // Não contar jogos que já existem como importados ou atualizados
                                        // Manter contadores como estão
                                    }
                                });
                            }
                            
                            // Para compatibilidade com versões antigas
                            if (response.imported_games) {
                                Object.keys(response.imported_games).forEach(gameCode => {
                                    const gameData = response.imported_games[gameCode];
                                    updateGameRow(gameCode, gameData);
                                    addToProcessedGamesList(gameCode, gameData);
                                    importedCount++;
                                });
                            }
                        } else {
                            // Marcar todos os jogos do lote como erro
                            batchGameIds.forEach(gameCode => {
                                addToProcessedGamesList(gameCode, {
                                    status: 'error',
                                    name: getGameNameById(gameCode),
                                    message: response.message || 'Erro desconhecido'
                                });
                                errorCount++;
                            });
                        }
                        
                        // Atualizar progresso
                        processedCount += batchGameIds.length;
                        updateProgress(processedCount, gameIds.length, importedCount, updatedCount, errorCount);
                        
                        // Processar próximo lote IMEDIATAMENTE - SEM DELAY
                        currentBatch++;
                        processBatch();
                    },
                    error: function(xhr, status, error) {
                        console.error('Batch Import Error:', {xhr: xhr, status: status, error: error});
                        
                        // Marcar todos os jogos do lote como erro
                        batchGameIds.forEach(gameCode => {
                            addToProcessedGamesList(gameCode, {
                                status: 'error',
                                name: getGameNameById(gameCode),
                                message: 'Erro na requisição: ' + error
                            });
                            errorCount++;
                        });
                        
                        // Atualizar progresso
                        processedCount += batchGameIds.length;
                        updateProgress(processedCount, gameIds.length, importedCount, updatedCount, errorCount);
                        
                        // Processar próximo lote IMEDIATAMENTE - SEM DELAY
                        currentBatch++;
                        processBatch();
                    }
                });
            }
            
            // Iniciar processamento
            processBatch();
        }
        
        // Função para inicializar o modal de progresso
        function initializeProgressModal(totalGames) {
            $('#progressText').text(`0 / ${totalGames} jogos processados`);
            $('#importProgressBar').css('width', '0%').text('0%').attr('aria-valuenow', 0);
            $('#currentStatus').text('Preparando importação...');
            $('#processedGamesList').empty();
            $('#importedCount').text('0');
            $('#updatedCount').text('0');
            $('#errorCount').text('0');
            $('#closeProgressModal').prop('disabled', true);
        }
        
        // Função para atualizar o progresso no modal
        function updateProgress(processed, total, imported, updated, errors) {
            const percentage = Math.round((processed / total) * 100);
            
            $('#progressText').text(`${processed} / ${total} jogos processados`);
            $('#importProgressBar').css('width', percentage + '%').text(percentage + '%').attr('aria-valuenow', percentage);
            $('#importedCount').text(imported);
            $('#updatedCount').text(updated);
            $('#errorCount').text(errors);
        }
        
        // Função para atualizar o status atual
        function updateCurrentStatus(status) {
            $('#currentStatus').text(status);
        }
        
        // Função para adicionar jogo à lista de processados
        function addToProcessedGamesList(gameCode, gameData) {
            const gameName = gameData.name || getGameNameById(gameCode);
            let statusClass = 'success';
            let statusText = 'Importado';
            let statusIcon = 'fas fa-check-circle';
            
            if (gameData.status === 'updated') {
                statusClass = 'warning';
                statusText = 'Atualizado';
                statusIcon = 'fas fa-edit';
            } else if (gameData.status === 'error') {
                statusClass = 'danger';
                statusText = 'Erro';
                statusIcon = 'fas fa-exclamation-circle';
            } else if (gameData.status === 'already_exists') {
                statusClass = 'info';
                statusText = 'Já existe';
                statusIcon = 'fas fa-info-circle';
            }
            
            const gameItem = `
                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                    <div>
                        <strong>${gameName}</strong>
                        <small class="d-block text-muted">ID: ${gameCode}</small>
                        ${gameData.message ? `<small class="d-block text-muted">${gameData.message}</small>` : ''}
                    </div>
                    <div>
                        <span class="badge bg-${statusClass}">
                            <i class="${statusIcon}"></i> ${statusText}
                        </span>
                    </div>
                </div>
            `;
            
            $('#processedGamesList').append(gameItem);
            
            // Scroll para o final da lista
            const gamesList = document.getElementById('processedGamesList');
            gamesList.scrollTop = gamesList.scrollHeight;
        }
        
        // Função para finalizar importação com progresso
        function finalizeImportWithProgress() {
            updateCurrentStatus('Importação concluída!');
            $('#closeProgressModal').prop('disabled', false);
            
            // Restaurar o botão de importação
            resetImportButton();
            
            // Remover classe de importação de todas as linhas
            $('tr.importing').removeClass('importing');
            
            // Aplicar filtros para atualizar a visualização
            applyFilters();
            
            // Mostrar toast de conclusão
            ToastManager.success('Importação concluída! Verifique os resultados no modal.');
        }
        
        // Função auxiliar para obter nome do jogo pelo ID
        function getGameNameById(gameId) {
            const checkbox = $(`#game-${gameId}`);
            return checkbox.attr('data-game-name') || `Jogo ${gameId}`;
        }
        
        // Event listener para fechar o modal
        $('#closeProgressModal').on('click', function() {
            $('#importProgressModal').modal('hide');
        });
        
        // Event listeners
        
        // Filtros
        $('#statusFilter, #inoveProviderFilter, #categoryFilter').on('change', applyFilters);
        
        // Busca
        $('#zero-config_filter input').on('keyup', function() {
            applyFilters();
        });
        
        // Checkbox "Selecionar todos"
        $('#selectAll').on('change', function() {
            const isChecked = $(this).prop('checked');
            
            // Selecionar apenas checkboxes visíveis não desabilitados
            table.$('tr', {search: 'applied'}).each(function() {
                $(this).find('.game-checkbox:not(:disabled)').prop('checked', isChecked);
            });
            
            // Atualizar texto do botão
            updateImportButtonText();
        });
        
        // Checkboxes dos jogos
        $(document).on('change', '.game-checkbox', function() {
            updateImportButtonText();
            updateSelectAllCheckbox();
        });
        
        // Botão de importação
        $('#importButton').on('click', importSelectedGames);

        // Botão de importar provedores
        $('#importProvidersButton').on('click', importProviders);

        // Botão de atualizar provedores
        $('#updateProvidersButton').on('click', updateProviders);

        // Função para atualizar a linha da tabela após importação/atualização
        function updateGameRow(gameId, gameData) {
            const row = $(`#game-${gameId}`).closest('tr');
            
            if (row.length === 0) {
                console.warn('Linha não encontrada para o jogo:', gameId);
                return;
            }
            
            // Remover o estado de importação
            row.removeClass('importing');
            
            // Atualizar linha com base no status
            if (gameData.status === 'imported' || gameData.status === 'updated') {
                // Redefinir classes e atributos
                row.addClass('imported-game');
                row.attr('data-exists', 'true');
                row.attr('data-status', 'imported');
                
                // Atualizar badge
                const nameCell = row.find('td:nth-child(3)');
                const gameType = row.attr('data-game-type');
                
                nameCell.find('.badge').remove();
                nameCell.find('small').remove();
                
                const gameName = nameCell.text().split(' ')[0]; // Pegar apenas o nome sem o badge
                nameCell.html(`${gameData.name} <span class="badge badge-success">Já importado</span>`);
                
                // Atualizar categoria do jogo (5ª coluna)
                if (gameData.category) {
                    row.find('td:nth-child(5)').text(ucfirst(gameData.category));
                }

                // Atualizar nome do provedor (4ª coluna)
                if (gameData.provider_name) {
                    const providerCell = row.find('td:nth-child(4)');
                    providerCell.html(`<span class="badge badge-light-info mb-2 me-4">${gameData.provider_name}</span>`);
                }

                // Desabilitar checkbox
                $(`#game-${gameId}`).prop('disabled', true);
            } else if (gameData.status === 'already_exists') {
                // Jogo já existe e não precisava de atualização
                row.addClass('imported-game');
                row.attr('data-status', 'imported');
                $(`#game-${gameId}`).prop('disabled', true);
            } else if (gameData.status === 'provider_error') {
                // Erro ao criar/encontrar provedor
                ToastManager.warning(`Erro ao processar provedor para o jogo: ${gameData.name}`);
            }
        }
        
        // Função para restaurar o estado do botão de importação
        function resetImportButton() {
            $('#importButton').prop('disabled', false);
            updateImportButtonText();
        }

        // Função para importar provedores
        function importProviders() {
            // Confirmar a operação
            ModalManager.showConfirmation(
                'Confirmar Importação',
                'Deseja importar todos os provedores da API Inove Gaming?<br><br>Esta operação irá:<br>• Importar novos provedores<br>• Atualizar provedores existentes com informações em branco<br>• Atualizar imagens e status',
                function() {
                    // Desabilitar o botão durante a operação
                    const $button = $('#importProvidersButton');
                    const originalText = $button.html();
                    $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importando...');

                    // Fazer a requisição
                    $.ajax({
                        url: "{{ route('admin.inove.import-providers') }}",
                        type: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        timeout: 120000, // 2 minutos de timeout
                        success: function(response) {
                            console.log('Import Providers Response:', response);

                            if (response.success) {
                                if (response.imported_count > 0 || response.updated_count > 0) {
                                    ToastManager.success(response.message);

                                    // Mostrar detalhes dos provedores processados
                                    if (response.providers && response.providers.length > 0) {
                                        let details = '<div class="mt-3"><strong>Detalhes da importação:</strong><ul>';
                                        response.providers.forEach(provider => {
                                            if (provider.status === 'imported') {
                                                details += `<li><strong>${provider.name}</strong> - Importado</li>`;
                                            } else if (provider.status === 'updated') {
                                                details += `<li><strong>${provider.name}</strong> - Campos atualizados: ${provider.updated_fields.join(', ')}</li>`;
                                            }
                                        });
                                        details += '</ul></div>';

                                        ToastManager.info('Verificar log para mais detalhes da importação.');
                                    }
                                } else {
                                    ToastManager.info(response.message);
                                }
                            } else {
                                ToastManager.error('Erro na importação: ' + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Import Providers Error:', {xhr: xhr, status: status, error: error});
                            let errorMessage = 'Erro ao importar provedores: ' + error;

                            if (xhr.status === 0) {
                                errorMessage = 'Erro de conexão. Verifique sua internet e tente novamente.';
                            } else if (xhr.status === 500) {
                                errorMessage = 'Erro interno do servidor. Verifique os logs do sistema.';
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = 'Erro na importação: ' + xhr.responseJSON.message;
                            }

                            ToastManager.error(errorMessage);
                        },
                        complete: function() {
                            // Restaurar o botão
                            $button.prop('disabled', false).html(originalText);
                        }
                    });
                }
            );
        }

        // Função para atualizar provedores
        function updateProviders() {
            // Confirmar a operação
            ModalManager.showConfirmation(
                'Confirmar Atualização',
                'Deseja atualizar todos os provedores existentes no banco de dados com as informações mais recentes da API Inove Gaming?<br><br>Esta operação irá:<br>• Atualizar URLs de imagens em branco<br>• Atualizar informações de carteiras em branco<br>• Corrigir campos de distribuição',
                function() {
                    // Desabilitar o botão durante a operação
                    const $button = $('#updateProvidersButton');
                    const originalText = $button.html();
                    $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Atualizando...');

                    // Fazer a requisição
                    $.ajax({
                        url: "{{ route('admin.inove.update-providers') }}",
                        type: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        timeout: 120000, // 2 minutos de timeout
                        success: function(response) {
                            console.log('Update Providers Response:', response);

                            if (response.success) {
                                if (response.updated_count > 0) {
                                    ToastManager.success(response.message);

                                    // Mostrar detalhes dos provedores atualizados
                                    if (response.providers && response.providers.length > 0) {
                                        let details = '<div class="mt-3"><strong>Detalhes da atualização:</strong><ul>';
                                        response.providers.forEach(provider => {
                                            if (provider.status === 'updated') {
                                                details += `<li><strong>${provider.name}</strong> - Campos atualizados: ${provider.updated_fields.join(', ')}</li>`;
                                            }
                                        });
                                        details += '</ul></div>';

                                        ToastManager.info('Verificar log para mais detalhes da atualização.');
                                    }
                                } else {
                                    ToastManager.info(response.message);
                                }
                            } else {
                                ToastManager.error('Erro na atualização: ' + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Update Providers Error:', {xhr: xhr, status: status, error: error});
                            let errorMessage = 'Erro ao atualizar provedores: ' + error;

                            if (xhr.status === 0) {
                                errorMessage = 'Erro de conexão. Verifique sua internet e tente novamente.';
                            } else if (xhr.status === 500) {
                                errorMessage = 'Erro interno do servidor. Verifique os logs do sistema.';
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = 'Erro na atualização: ' + xhr.responseJSON.message;
                            }

                            ToastManager.error(errorMessage);
                        },
                        complete: function() {
                            // Restaurar o botão
                            $button.prop('disabled', false).html(originalText);
                        }
                    });
                }
            );
        }

    });
</script>
@endpush
@endsection 