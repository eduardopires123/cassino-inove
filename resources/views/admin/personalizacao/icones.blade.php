@extends('admin.layouts.app')

@section('content')
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Ícones</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <div class="row" style="margin-bottom: -20px; padding:15px;">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <div class="filter-buttons">
                                    <button class="btn btn-outline-primary me-2 table-switch active" data-table="icon-table">Ícones</button>
                                    <button class="btn btn-outline-primary table-switch" data-table="league-table">Ligas</button>
                                </div>
                                <div class="add-buttons">
                                    <button id="add-icon-btn" class="btn btn-success me-2 add-button" data-type="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></i> Adicionar Ícone
                                    </button>
                                    <button id="add-league-btn" class="btn btn-info add-button" data-type="league">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></i> Adicionar Liga
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Table for Icons -->
                        <div id="icon-table-container" class="table-responsive">
                            <table id="icon-table" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th style="width: 80px">Ordem</th>
                                        <th>Ícone</th>
                                        <th>Nome</th>
                                        <th>Link/Game</th>
                                        <th>Status</th>
                                        <th style="width: 120px">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-icons">
                                    @php
                                        $icons = App\Models\Admin\Icon::where('type', 'icon')->orWhereNull('type')->orderBy('ordem', 'asc')->get();
                                    @endphp
                                    @foreach($icons as $icon) 
                                    <tr data-id="{{ $icon->id }}" data-type="icon" class="icon-row">
                                        <td class="handle">
                                            <span>{{ $icon->ordem }}</span>
                                            <i class="fas fa-grip-lines"></i>
                                        </td>
                                        <td>
                                            <div class="svg-container" style="font-size: 24px; width: 100%; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                {!! $icon->svg !!}
                                            </div>
                                        </td>
                                        <td>{{ $icon->name }}</td>
                                        <td>
                                            @if($icon->game_id)
                                                <span class="text-info">Game ID: {{ $icon->game_id }}</span>
                                            @elseif($icon->link)
                                                <span class="text-success">Link: {{ $icon->link }}</span>
                                            @else
                                                <span class="text-muted">Sem link</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $icon->active ? 'light-success' : 'light-danger' }} mb-2 me-4">
                                                {{ $icon->active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="badge badge-light-primary text-start me-2 edit-icon" data-id="{{ $icon->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                            </button>
                                            <button class="badge badge-light-danger text-start delete-icon" data-id="{{ $icon->id }}" data-name="{{ $icon->name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="width: 80px">Ordem</th>
                                        <th>Ícone</th>
                                        <th>Nome</th>
                                        <th>Link/Game</th>
                                        <th>Status</th>
                                        <th style="width: 120px">Ações</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- Table for Leagues -->
                        <div id="league-table-container" class="table-responsive" style="display: none;">
                            <table id="league-table" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th style="width: 80px">Ordem</th>
                                        <th>Ícone</th>
                                        <th>Nome</th>
                                        <th>Destaque</th>
                                        <th>Status</th>
                                        <th style="width: 120px">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-leagues">
                                    @php
                                        $leagues = App\Models\Admin\Icon::where('type', 'league')->orderBy('ordem', 'asc')->get();
                                    @endphp
                                    @foreach($leagues as $league) 
                                    <tr data-id="{{ $league->id }}" data-type="league" class="league-row">
                                        <td class="handle">
                                            <span>{{ $league->ordem }}</span>
                                            <i class="fas fa-grip-lines"></i>
                                        </td>
                                        <td>
                                            <div class="svg-container" style="font-size: 24px; width: 100%; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                {!! $league->svg !!}
                                            </div>
                                        </td>
                                        <td>{{ $league->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $league->hot == 1 ? 'light-warning' : ($league->hot == 2 ? 'light-info' : 'light-dark') }} mb-2 me-4">
                                                {{ $league->hot == 1 ? 'HOT' : ($league->hot == 2 ? 'NEW' : 'Normal') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $league->active ? 'light-success' : 'light-danger' }} mb-2 me-4">
                                                {{ $league->active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="badge badge-light-primary text-start me-2 edit-icon" data-id="{{ $league->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                            </button>
                                            <button class="badge badge-light-danger text-start delete-icon" data-id="{{ $league->id }}" data-name="{{ $league->name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="width: 80px">Ordem</th>
                                        <th>Ícone</th>
                                        <th>Nome</th>
                                        <th>Destaque</th>
                                        <th>Status</th>
                                        <th style="width: 120px">Ações</th>
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

<!-- Modal para adicionar/editar ícone -->
<div class="modal fade" id="iconModal" tabindex="-1" role="dialog" aria-labelledby="iconModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconModalLabel">Gerenciar Ícone</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="iconForm">
                    <input type="hidden" id="icon-id" value="">
                    <input type="hidden" id="icon-type" value="icon">
                    
                    <div class="form-group mb-3">
                        <label class="form-label"><strong>Nome:</strong></label>
                        <input type="text" class="form-control" id="icon-name" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label"><strong>SVG:</strong></label>
                        <textarea class="form-control" id="icon-svg" rows="5" required></textarea>
                        <small class="form-text text-muted">Cole o código SVG do ícone aqui.</small>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label"><strong>Visualização:</strong></label>
                        <div id="icon-preview" class="text-center" style="min-height: 80px; background-color: #00000021; border-radius: 8px; padding: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            <!-- Preview will be shown here -->
                        </div>
                    </div>
                    
                    <div id="icon-type-group" class="form-group mb-3" style="display: none;">
                        <label class="form-label"><strong>Tipo de Ícone:</strong></label>
                        <select id="icon-type-select" class="form-control">
                            <option value="icon">Ícone Regular</option>
                            <option value="league">Liga (Esporte)</option>
                        </select>
                        <small class="form-text text-muted">Selecione "Liga" para ícones que devem aparecer na seção de ligas esportivas.</small>
                    </div>
                    
                    <div id="link-type-group" class="form-group mb-3">
                        <label class="form-label"><strong>Tipo de Link:</strong></label>
                        <select id="link-type" class="form-control">
                            <option value="url">URL</option>
                            <option value="js">Função JavaScript</option>
                        </select>
                    </div>

                    <div class="form-group mb-3" id="url-link-group">
                        <label class="form-label" id="url-link-label"><strong>Link do Ícone / ID do Jogo:</strong></label>
                        <select id="icon-link" class="icon-link-select">
                            <option value="">Digite URL ou selecione um jogo...</option>
                            <optgroup label="Jogos" id="games-optgroup">
                                @php
                                    $games = App\Models\GamesApi::where('status', 1)->orderBy('name', 'asc')->get();
                                @endphp
                                @foreach($games as $game)
                                    <option value="{{ $game->id }}">{{ $game->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group mb-3" id="js-link-group" style="display: none;">
                        <label class="form-label"><strong>Função JavaScript:</strong></label>
                        <input type="text" class="form-control" id="js-link" placeholder="Ex: LinkMobile('/Live/page')">
                        <small class="form-text text-muted">Insira a função JavaScript com os parâmetros.</small>
                    </div>
                    
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="icon-active" checked>
                        <label class="form-check-label" for="icon-active">Ativo</label>
                    </div>
                    
                    <div id="hot-checkbox-group" class="form-group mb-3">
                        <label class="form-label"><strong>Destaque:</strong></label>
                        <select class="form-control" id="icon-hot">
                            <option value="0">Normal</option>
                            <option value="1">Hot</option>
                            <option value="2">New</option>
                        </select>
                        <small class="form-text text-muted">Selecione o tipo de destaque para o ícone.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light-dark" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" id="save-icon">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Formulário oculto para exclusão -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
    body.dark .form-check-input:checked {
        background-color: #4361ee;
        border-color: #4361ee;
    }
    
    .handle {
        cursor: grab;
    }
    
    .handle:active {
        cursor: grabbing;
    }

    /* Classe para arrastar elementos */
    .ui-sortable-helper {
        display: table;
        background-color: rgba(67, 97, 238, 0.1) !important;
        border: 1px dashed #4361ee !important;
    }
    
    .table-switch.active {
        background-color: #4361ee;
        color: white;
    }
    
    .hidden-row {
        display: none;
    }
    
    /* Cor padrão para SVG nas tabelas */
    .svg-container svg {
        color: #4361ee !important;
        fill: #4361ee !important;
    }
    
    .svg-container svg path,
    .svg-container svg circle,
    .svg-container svg rect,
    .svg-container svg line,
    .svg-container svg polyline,
    .svg-container svg polygon {
        stroke: #4361ee !important;
        fill: #4361ee !important;
    }
</style>
@endsection
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/src/tomSelect/tom-select.default.min.css') }}">
@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
<script>
    // Implementar ToastManager fallback se não existir
    if (typeof ModalManager === 'undefined') {
        window.ModalManager = {
            showConfirmation: function(title, message, confirmCallback, cancelCallback) {
                if (confirm(message)) {
                    if (confirmCallback) confirmCallback();
                } else {
                    if (cancelCallback) cancelCallback();
                }
            },
            init: function() {}
        };
    }

    $(function() {
        // Alternar entre tabelas
        $('.table-switch').on('click', function() {
            const tableId = $(this).data('table');
            
            // Atualizar classes dos botões
            $('.table-switch').removeClass('active');
            $(this).addClass('active');
            
            // Exibir a tabela selecionada
            if (tableId === 'icon-table') {
                $('#icon-table-container').show();
                $('#league-table-container').hide();
                $('#add-icon-btn').show();
                $('#add-league-btn').hide();
            } else {
                $('#icon-table-container').hide();
                $('#league-table-container').show();
                $('#add-icon-btn').hide();
                $('#add-league-btn').show();
            }
        });
        
        // Configuração inicial: mostrar tabela de ícones, esconder tabela de ligas
        $('#icon-table-container').show();
        $('#league-table-container').hide();
        $('#add-icon-btn').show();
        $('#add-league-btn').hide();
        
        // Inicializar TomSelect para o ícone no modal
        let tomSelect = new TomSelect('#icon-link', {
            create: true,
            sortField: {
                field: "text",
                direction: "asc"
            },
            createFilter: function(input) {
                // Permitir criação apenas se parece uma URL
                return input.length > 0 && (input.startsWith('http://') || input.startsWith('https://') || input.startsWith('/'));
            },
            render: {
                option_create: function(data, escape) {
                    return '<div class="create">Usar como URL: <strong>' + escape(data.input) + '</strong></div>';
                },
                no_results: function(data, escape) {
                    const isIconType = $('#icon-type').val() === 'icon';
                    if (isIconType) {
                        return '<div class="no-results">Digite uma URL válida (http://, https:// ou /) ou selecione um jogo</div>';
                    } else {
                        return '<div class="no-results">Digite uma URL válida (http://, https:// ou /)</div>';
                    }
                }
            }
        });
        
        // Inicializar ordenação com jQuery UI Sortable para ícones
        $("#sortable-icons").sortable({
            handle: ".handle",
            helper: "clone",
            cursor: "grabbing",
            placeholder: "ui-state-highlight",
            update: function(event, ui) {
                // Mostrar toast de processamento
                const processingToast = ToastManager.info('Atualizando ordem, aguarde...');
                
                // Coletar os IDs dos ícones na nova ordem
                var icons = {};
                $('#sortable-icons tr').each(function(index) {
                    icons[$(this).data('id')] = index + 1;
                    // Atualizar o número de ordem na interface
                    $(this).find('.handle span').text(index + 1);
                });
                
                // Enviar dados para o servidor
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.personalizacao.icones.update-order') }}",
                    data: {
                        icons: icons,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Remover toast de processamento
                        processingToast.remove();
                        
                        // Mostrar toast de sucesso
                        if (response && response.success) {
                            ToastManager.success('Ordem atualizada com sucesso!');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Remover toast de processamento
                        processingToast.remove();
                        
                        // Mostrar toast de erro
                        ToastManager.error('Erro ao atualizar a ordem. Por favor, tente novamente.');
                        console.error('Erro:', error);
                    }
                });
            }
        });
        
        // Inicializar ordenação com jQuery UI Sortable para ligas
        $("#sortable-leagues").sortable({
            handle: ".handle",
            helper: "clone",
            cursor: "grabbing",
            placeholder: "ui-state-highlight",
            update: function(event, ui) {
                // Mostrar toast de processamento
                const processingToast = ToastManager.info('Atualizando ordem, aguarde...');
                
                // Coletar os IDs das ligas na nova ordem
                var icons = {};
                $('#sortable-leagues tr').each(function(index) {
                    icons[$(this).data('id')] = index + 1;
                    // Atualizar o número de ordem na interface
                    $(this).find('.handle span').text(index + 1);
                });
                
                // Enviar dados para o servidor
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.personalizacao.icones.update-order') }}",
                    data: {
                        icons: icons,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Remover toast de processamento
                        processingToast.remove();
                        
                        // Mostrar toast de sucesso
                        if (response && response.success) {
                            ToastManager.success('Ordem atualizada com sucesso!');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Remover toast de processamento
                        processingToast.remove();
                        
                        // Mostrar toast de erro
                        ToastManager.error('Erro ao atualizar a ordem. Por favor, tente novamente.');
                        console.error('Erro:', error);
                    }
                });
            }
        });
        
        // Preview de SVG ao digitar
        $('#icon-svg').on('input', function() {
            const svgCode = $(this).val();
            $('#icon-preview').html(svgCode);
        });
        
        // Alternar entre os tipos de link
        $('#link-type').on('change', function() {
            const linkType = $(this).val();
            
            // Ocultar todos os grupos de link
            $('#url-link-group, #js-link-group').hide();
            
            // Mostrar o grupo correspondente ao tipo selecionado
            if (linkType === 'url') {
                $('#url-link-group').show();
            } else if (linkType === 'js') {
                $('#js-link-group').show();
            }
        });
        
        // Função para configurar o modal para ícones
        function configureIconModal() {
            $('#iconModalLabel').text('Adicionar Novo Ícone');
            $('#icon-id').val('');
            $('#icon-name').val('');
            $('#icon-svg').val('');
            $('#icon-preview').html('');
            tomSelect.clear();
            $('#icon-active').prop('checked', true);
            $('#icon-type').val('icon');
            $('#icon-hot').val('0');
            
            // Mostrar todos os tipos de link
            $('#link-type-group').show();
            $('#link-type').val('url').trigger('change');
            
            // Mostrar lista de jogos para ícones
            $('#games-optgroup').show();
            $('#url-link-label').html('<strong>Link do Ícone / ID do Jogo:</strong>');
            
            // Ocultar o campo Hot
            $('#hot-checkbox-group').hide();
        }
        
        // Função para configurar o modal para ligas
        function configureLeagueModal() {
            $('#iconModalLabel').text('Adicionar Nova Liga');
            $('#icon-id').val('');
            $('#icon-name').val('');
            $('#icon-svg').val('');
            $('#icon-preview').html('');
            tomSelect.clear();
            $('#icon-active').prop('checked', true);
            $('#icon-type').val('league');
            $('#icon-hot').val('0');
            
            // Mostrar todos os tipos de link
            $('#link-type-group').show();
            $('#link-type').val('url').trigger('change');
            
            // Ocultar lista de jogos para ligas
            $('#games-optgroup').hide();
            $('#url-link-label').html('<strong>URL do Ícone:</strong>');
            
            // Mostrar o campo Hot
            $('#hot-checkbox-group').show();
        }
        
        // Função para configurar o modal para edição de ícones
        function configureEditIconModal(icon) {
            $('#iconModalLabel').text('Editar Ícone');
            $('#icon-id').val(icon.id);
            $('#icon-name').val(icon.name);
            $('#icon-svg').val(icon.svg);
            $('#icon-preview').html(icon.svg);
            $('#icon-active').prop('checked', icon.active);
            $('#icon-type').val('icon');
            $('#icon-hot').val(icon.hot || '0');
            
            // Mostrar todos os tipos de link
            $('#link-type-group').show();
            
            // Mostrar lista de jogos para ícones
            $('#games-optgroup').show();
            $('#url-link-label').html('<strong>Link do Ícone / ID do Jogo:</strong>');
            
            // Definir o valor do link/game
            tomSelect.clear();
            $('#js-link').val('');
            
            // Determinar o tipo de link e selecionar a opção correta
            let linkType = 'url';
            if (icon.link) {
                if (icon.link.includes('(') && icon.link.includes(')')) {
                    linkType = 'js';
                    $('#js-link').val(icon.link);
                } else {
                    // Pode ser necessário adicionar a opção primeiro se for URL personalizada
                    if (!tomSelect.options[icon.link]) {
                        tomSelect.addOption({
                            value: icon.link,
                            text: icon.link
                        });
                    }
                    tomSelect.setValue(icon.link);
                }
            } else if (icon.game_id) {
                // Se tiver game_id, usar o ID do jogo
                tomSelect.setValue(icon.game_id);
            }
            
            // Atualizar o seletor de tipo de link
            $('#link-type').val(linkType).trigger('change');
            
            // Ocultar o campo Hot para ícones regulares
            $('#hot-checkbox-group').hide();
        }
        
        // Função para configurar o modal para edição de ligas
        function configureEditLeagueModal(league) {
            $('#iconModalLabel').text('Editar Liga');
            $('#icon-id').val(league.id);
            $('#icon-name').val(league.name);
            $('#icon-svg').val(league.svg);
            $('#icon-preview').html(league.svg);
            $('#icon-active').prop('checked', league.active);
            $('#icon-type').val('league');
            $('#icon-hot').val(league.hot || '0');
            
            // Mostrar todos os tipos de link
            $('#link-type-group').show();
            
            // Ocultar lista de jogos para ligas
            $('#games-optgroup').hide();
            $('#url-link-label').html('<strong>URL do Ícone:</strong>');
            
            // Definir o valor do link
            tomSelect.clear();
            $('#js-link').val('');
            
            // Determinar o tipo de link e selecionar a opção correta
            let linkType = 'url';
            if (league.link) {
                if (league.link.includes('(') && league.link.includes(')')) {
                    linkType = 'js';
                    $('#js-link').val(league.link);
                } else {
                    // Pode ser necessário adicionar a opção primeiro se for URL personalizada
                    if (!tomSelect.options[league.link]) {
                        tomSelect.addOption({
                            value: league.link,
                            text: league.link
                        });
                    }
                    tomSelect.setValue(league.link);
                }
            }
            
            // Atualizar o seletor de tipo de link
            $('#link-type').val(linkType).trigger('change');
            
            // Mostrar o campo Hot
            $('#hot-checkbox-group').show();
        }
        
        // Botão para adicionar novo ícone
        $('#add-icon-btn').on('click', function() {
            configureIconModal();
            
            // Abrir o modal
            const modal = new bootstrap.Modal(document.getElementById('iconModal'));
            modal.show();
        });
        
        // Botão para adicionar nova liga
        $('#add-league-btn').on('click', function() {
            configureLeagueModal();
            
            // Abrir o modal
            const modal = new bootstrap.Modal(document.getElementById('iconModal'));
            modal.show();
        });
        
        // Botão para editar ícone existente
        $('.edit-icon').on('click', function() {
            const iconId = $(this).data('id');
            const iconType = $(this).closest('tr').data('type') || 'icon';
            
            // Mostrar toast de carregamento
            const loadingToast = ToastManager.info('Carregando dados do ícone...');
            
            // Buscar dados do ícone
            $.ajax({
                type: "GET",
                url: "{{ route('admin.personalizacao.icones.show', ['id' => '_ID_']) }}".replace('_ID_', iconId),
                success: function(data) {
                    loadingToast.remove();
                    
                    if (data && data.icon) {
                        const icon = data.icon;
                        
                        // Configurar o modal com base no tipo
                        if (icon.type === 'league') {
                            configureEditLeagueModal(icon);
                        } else {
                            configureEditIconModal(icon);
                        }
                        
                        // Abrir o modal
                        const modal = new bootstrap.Modal(document.getElementById('iconModal'));
                        modal.show();
                    } else {
                        ToastManager.error('Erro ao carregar dados do ícone.');
                    }
                },
                error: function(xhr, status, error) {
                    loadingToast.remove();
                    ToastManager.error('Erro ao carregar dados do ícone. Por favor, tente novamente.');
                    console.error('Erro:', error);
                }
            });
        });
        
        // Botão para salvar ícone
        $('#save-icon').on('click', function() {
            const iconId = $('#icon-id').val();
            const name = $('#icon-name').val();
            const svg = $('#icon-svg').val();
            const type = $('#icon-type').val();
            const hot = $('#icon-hot').val();
            const linkType = $('#link-type').val();
            let linkValue = '';
            
            // Obter o valor do link com base no tipo selecionado
            if (linkType === 'url') {
                linkValue = tomSelect.getValue();
            } else if (linkType === 'js') {
                linkValue = $('#js-link').val();
            }
            
            const active = $('#icon-active').prop('checked');
            
            // Validação básica
            if (!name || !svg) {
                ToastManager.error('Por favor, preencha todos os campos obrigatórios.');
                return;
            }
            
            // Mostrar toast de processamento
            const processingToast = ToastManager.info('Salvando ícone, aguarde...');
            
            // Definir URL e método com base em se é criação ou atualização
            const url = iconId ? "{{ route('admin.personalizacao.icones.update', ['id' => '_ID_']) }}".replace('_ID_', iconId) : "{{ route('admin.personalizacao.icones.store') }}";
            const method = iconId ? 'PUT' : 'POST';
            
            // Preparar dados com base no tipo de link
            let postData = {
                _token: "{{ csrf_token() }}",
                name: name,
                svg: svg,
                type: type,
                hot: hot,
                active: active ? 1 : 0  // Convertendo o boolean para 0/1 para garantir compatibilidade
            };
            
            if (linkType === 'url') {
                // Se for tipo ícone e o valor for numérico, é um game_id
                if (type === 'icon' && !isNaN(linkValue) && linkValue !== '') {
                    postData.game_id = linkValue;
                    postData.link = null;
                } else {
                    postData.link = linkValue;
                    postData.game_id = null;
                }
            } else if (linkType === 'js') {
                postData.link = linkValue;
                postData.game_id = null;
            }
            
            // Enviar dados para o servidor
            $.ajax({
                type: method,
                url: url,
                data: postData,
                success: function(response) {
                    processingToast.remove();
                    
                    if (response.success) {
                        // Fechar o modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('iconModal'));
                        modal.hide();
                        
                        // Mostrar toast de sucesso
                        ToastManager.success(iconId ? 'Ícone atualizado com sucesso!' : 'Ícone criado com sucesso!');
                        
                        // Atualizar apenas a tabela
                        $.ajax({
                            url: window.location.href,
                            success: function(data) {
                                const iconType = $(`.icon-row[data-id="${iconId}"], .league-row[data-id="${iconId}"]`).data('type') || 'icon';
                                
                                if (iconType === 'icon') {
                                    const newTableContent = $(data).find('#sortable-icons').html();
                                    $('#sortable-icons').html(newTableContent);
                                } else if (iconType === 'league') {
                                    const newTableContent = $(data).find('#sortable-leagues').html();
                                    $('#sortable-leagues').html(newTableContent);
                                }
                                
                                configurarEventosBotoes();
                                $("#sortable-icons").sortable('refresh');
                                $("#sortable-leagues").sortable('refresh');
                            }
                        });
                    } else {
                        ToastManager.error('Erro ao salvar ícone: ' + (response.message || 'Erro desconhecido'));
                    }
                },
                error: function(xhr, status, error) {
                    processingToast.remove();
                    ToastManager.error('Erro ao salvar ícone. Por favor, tente novamente.');
                    console.error('Erro:', error);
                }
            });
        });
        
        // Função para configurar os eventos nos botões
        function configurarEventosBotoes() {
            // Botões para alternar entre tabelas
            $('.table-switch').off('click').on('click', function() {
                const tableId = $(this).data('table');
                
                // Atualizar classes dos botões
                $('.table-switch').removeClass('active');
                $(this).addClass('active');
                
                // Exibir a tabela selecionada
                if (tableId === 'icon-table') {
                    $('#icon-table-container').show();
                    $('#league-table-container').hide();
                    $('#add-icon-btn').show();
                    $('#add-league-btn').hide();
                } else {
                    $('#icon-table-container').hide();
                    $('#league-table-container').show();
                    $('#add-icon-btn').hide();
                    $('#add-league-btn').show();
                }
            });
            
            // Configurar eventos de edição
            $('.edit-icon').off('click').on('click', function() {
                const iconId = $(this).data('id');
                const iconType = $(this).closest('tr').data('type') || 'icon';
                
                // Mostrar toast de carregamento
                const loadingToast = ToastManager.info('Carregando dados do ícone...');
                
                // Buscar dados do ícone
                $.ajax({
                    type: "GET",
                    url: "{{ route('admin.personalizacao.icones.show', ['id' => '_ID_']) }}".replace('_ID_', iconId),
                    success: function(data) {
                        loadingToast.remove();
                        
                        if (data && data.icon) {
                            const icon = data.icon;
                            
                            // Configurar o modal com base no tipo
                            if (icon.type === 'league') {
                                configureEditLeagueModal(icon);
                            } else {
                                configureEditIconModal(icon);
                            }
                            
                            // Abrir o modal
                            const modal = new bootstrap.Modal(document.getElementById('iconModal'));
                            modal.show();
                        } else {
                            ToastManager.error('Erro ao carregar dados do ícone.');
                        }
                    },
                    error: function(xhr, status, error) {
                        loadingToast.remove();
                        ToastManager.error('Erro ao carregar dados do ícone. Por favor, tente novamente.');
                        console.error('Erro:', error);
                    }
                });
            });
            
            // Configurar eventos de exclusão
            $('.delete-icon').off('click').on('click', function() {
                const iconId = $(this).data('id');
                const iconName = $(this).data('name');
                
                // Mostrar modal de confirmação
                ModalManager.showConfirmation(
                    'Excluir Ícone',
                    `Tem certeza que deseja excluir o ícone "${iconName}"? Esta ação não pode ser desfeita.`,
                    function() {
                        // Callback de confirmação
                        const deleteForm = $('#delete-form');
                        deleteForm.attr('action', "{{ route('admin.personalizacao.icones.destroy', ['id' => '_ID_']) }}".replace('_ID_', iconId));
                        
                        // Mostrar toast de processamento
                        const processingToast = ToastManager.info('Excluindo ícone, aguarde...');
                        
                        // Enviar o formulário de forma assíncrona
                        $.ajax({
                            url: deleteForm.attr('action'),
                            type: 'POST',
                            data: deleteForm.serialize(),
                            success: function(response) {
                                processingToast.remove();
                                if (response.success) {
                                    ToastManager.success('Ícone excluído com sucesso!');
                                    
                                    // Atualizar apenas a tabela
                                    $.ajax({
                                        url: window.location.href,
                                        success: function(data) {
                                            const iconType = $(`.icon-row[data-id="${iconId}"], .league-row[data-id="${iconId}"]`).data('type') || 'icon';
                                            
                                            if (iconType === 'icon') {
                                                const newTableContent = $(data).find('#sortable-icons').html();
                                                $('#sortable-icons').html(newTableContent);
                                            } else if (iconType === 'league') {
                                                const newTableContent = $(data).find('#sortable-leagues').html();
                                                $('#sortable-leagues').html(newTableContent);
                                            }
                                            
                                            configurarEventosBotoes();
                                            $("#sortable-icons").sortable('refresh');
                                            $("#sortable-leagues").sortable('refresh');
                                        }
                                    });
                                } else {
                                    ToastManager.error('Erro ao excluir ícone: ' + (response.message || 'Erro desconhecido'));
                                }
                            },
                            error: function() {
                                processingToast.remove();
                                ToastManager.error('Erro ao excluir ícone. Por favor, tente novamente.');
                            }
                        });
                        return false;
                    }
                );
            });
        }
        
        // Configurar eventos quando a página carregar
        configurarEventosBotoes();
        
        // Inicialização do gerenciador de modais quando o documento estiver pronto
        ModalManager.init();
        
        // Verificar se há mensagem de sucesso na sessão e exibir toast
        @if(session('success'))
            ToastManager.success("{{ session('success') }}");
        @endif
        
        // Verificar se há mensagem de erro na sessão e exibir toast
        @if(session('error'))
            ToastManager.error("{{ session('error') }}");
        @endif
    });
</script>
@endpush

