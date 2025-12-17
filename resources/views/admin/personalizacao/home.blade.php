@extends('admin.layouts.app')
@section('content')
    @php
        // Obter configurações das seções da página inicial
        $homeSections = App\Models\HomeSectionsSettings::getSettings();
    @endphp
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Personalização</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Seções da Página Inicial</li>
                    </ol>
                </nav>
            </div>
            <!-- /BREADCRUMB -->

            <!-- Configurações de Seções da Página Inicial -->
            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Configurações de Seções da Página Inicial</h5>
                                <p class="card-text mt-2 text-muted">Configure quais seções serão exibidas na página inicial do site.</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input section-toggle"
                                                   type="checkbox"
                                                   id="show_live_casino"
                                                   data-field="show_live_casino"
                                                {{ $homeSections->show_live_casino ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_live_casino">
                                                <strong>Exibir Cassino ao Vivo</strong>
                                                <br><small class="text-muted">Seção com jogos de cassino ao vivo</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input section-toggle"
                                                   type="checkbox"
                                                   id="show_new_games"
                                                   data-field="show_new_games"
                                                {{ $homeSections->show_new_games ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_new_games">
                                                <strong>Exibir Novos Jogos</strong>
                                                <br><small class="text-muted">Seção com os jogos mais recentes</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input section-toggle"
                                                   type="checkbox"
                                                   id="show_most_viewed_games"
                                                   data-field="show_most_viewed_games"
                                                {{ $homeSections->show_most_viewed_games ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_most_viewed_games">
                                                <strong>Exibir Jogos Mais Jogados</strong>
                                                <br><small class="text-muted">Seção com os jogos mais populares</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input section-toggle"
                                                   type="checkbox"
                                                   id="show_top_wins"
                                                   data-field="show_top_wins"
                                                {{ $homeSections->show_top_wins == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_top_wins">
                                                <strong>Exibir Maiores Ganhos de Hoje</strong>
                                                <br><small class="text-muted">Seção com os maiores prêmios do dia</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input section-toggle"
                                                   type="checkbox"
                                                   id="show_last_bets"
                                                   data-field="show_last_bets"
                                                {{ $homeSections->show_last_bets == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_last_bets">
                                                <strong>Exibir Últimas Apostas</strong>
                                                <br><small class="text-muted">Seção com as apostas mais recentes</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input section-toggle"
                                                   type="checkbox"
                                                   id="show_roulette"
                                                   data-field="show_roulette"
                                                {{ $homeSections->show_roulette ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_roulette">
                                                <strong>Exibir Ícone Flutuante da Roleta</strong>
                                                <br><small class="text-muted">Ícone flutuante para acesso rápido à roleta</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input section-toggle"
                                                   type="checkbox"
                                                   id="show_whatsapp_float"
                                                   data-field="show_whatsapp_float"
                                                {{ $homeSections->show_whatsapp_float ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_whatsapp_float">
                                                <strong>Exibir Botão Flutuante do WhatsApp</strong>
                                                <br><small class="text-muted">Botão flutuante para contato via WhatsApp</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input section-toggle"
                                                   type="checkbox"
                                                   id="show_raspadinhas_home"
                                                   data-field="show_raspadinhas_home"
                                                {{ $homeSections->show_raspadinhas_home ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_raspadinhas_home">
                                                <strong>Exibir Raspadinhas Mais Jogadas</strong>
                                                <br><small class="text-muted">Seção com as raspadinhas mais populares na home</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Configurações de Seções da Página Inicial -->

            <!-- Títulos Personalizados das Seções -->
            <div class="row layout-top-spacing mt-4">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Títulos Personalizados das Seções</h5>
                                    <p class="card-text mt-2 text-muted">Personalize os títulos das seções da página inicial. Deixe em branco para usar o título padrão.</p>
                                </div>
                                <button type="button" class="btn btn-warning" onclick="resetCustomTitles()">
                                    <i class="fas fa-undo"></i> Redefinir
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="custom_title_live_casino" class="form-label">
                                            <strong>Título: Cassino ao Vivo</strong>
                                            <br><small class="text-muted">Padrão: {{ __('menu.live_casino') }}</small>
                                        </label>
                                        <input type="text" 
                                               class="form-control custom-title-input" 
                                               id="custom_title_live_casino"
                                               data-field="custom_title_live_casino"
                                               value="{{ $homeSections->custom_title_live_casino ?? '' }}"
                                               placeholder="Deixe em branco para usar o padrão">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="custom_title_new_games" class="form-label">
                                            <strong>Título: Novos Jogos</strong>
                                            <br><small class="text-muted">Padrão: {{ __('menu.new_games') }}</small>
                                        </label>
                                        <input type="text" 
                                               class="form-control custom-title-input" 
                                               id="custom_title_new_games"
                                               data-field="custom_title_new_games"
                                               value="{{ $homeSections->custom_title_new_games ?? '' }}"
                                               placeholder="Deixe em branco para usar o padrão">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="custom_title_most_viewed_games" class="form-label">
                                            <strong>Título: Jogos Mais Jogados</strong>
                                            <br><small class="text-muted">Padrão: {{ __('menu.most_viewed_games') }}</small>
                                        </label>
                                        <input type="text" 
                                               class="form-control custom-title-input" 
                                               id="custom_title_most_viewed_games"
                                               data-field="custom_title_most_viewed_games"
                                               value="{{ $homeSections->custom_title_most_viewed_games ?? '' }}"
                                               placeholder="Deixe em branco para usar o padrão">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="custom_title_top_wins" class="form-label">
                                            <strong>Título: Maiores Ganhos</strong>
                                            <br><small class="text-muted">Padrão: {{ __('menu.top_wins_today') }}</small>
                                        </label>
                                        <input type="text" 
                                               class="form-control custom-title-input" 
                                               id="custom_title_top_wins"
                                               data-field="custom_title_top_wins"
                                               value="{{ $homeSections->custom_title_top_wins ?? '' }}"
                                               placeholder="Deixe em branco para usar o padrão">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="custom_title_most_paid" class="form-label">
                                            <strong>Título: Mais Pagou Hoje</strong>
                                            <br><small class="text-muted">Padrão: {{ __('menu.most_paid_today') }}</small>
                                        </label>
                                        <input type="text" 
                                               class="form-control custom-title-input" 
                                               id="custom_title_most_paid"
                                               data-field="custom_title_most_paid"
                                               value="{{ $homeSections->custom_title_most_paid ?? '' }}"
                                               placeholder="Deixe em branco para usar o padrão">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="custom_title_studios" class="form-label">
                                            <strong>Título: Estúdios</strong>
                                            <br><small class="text-muted">Padrão: {{ __('menu.studios') }}</small>
                                        </label>
                                        <input type="text" 
                                               class="form-control custom-title-input" 
                                               id="custom_title_studios"
                                               data-field="custom_title_studios"
                                               value="{{ $homeSections->custom_title_studios ?? '' }}"
                                               placeholder="Deixe em branco para usar o padrão">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="custom_title_top_raspadinhas" class="form-label">
                                            <strong>Título: Top Raspadinhas</strong>
                                            <br><small class="text-muted">Padrão: TOP RASPADINHAS</small>
                                        </label>
                                        <input type="text" 
                                               class="form-control custom-title-input" 
                                               id="custom_title_top_raspadinhas"
                                               data-field="custom_title_top_raspadinhas"
                                               value="{{ $homeSections->custom_title_top_raspadinhas ?? '' }}"
                                               placeholder="Deixe em branco para usar o padrão">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="custom_title_modo_surpresa" class="form-label">
                                            <strong>Título: Modo Surpresa</strong>
                                            <br><small class="text-muted">Padrão: {{ __('game.surprise_mode_title') }}</small>
                                        </label>
                                        <input type="text" 
                                               class="form-control custom-title-input" 
                                               id="custom_title_modo_surpresa"
                                               data-field="custom_title_modo_surpresa"
                                               value="{{ $homeSections->custom_title_modo_surpresa ?? '' }}"
                                               placeholder="Deixe em branco para usar o padrão">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="custom_title_sports_icons" class="form-label">
                                            <strong>Título: Ícones de Esportes</strong>
                                            <br><small class="text-muted">Padrão: 🏆 {{ __('menu.sportshome') }}</small>
                                        </label>
                                        <input type="text" 
                                               class="form-control custom-title-input" 
                                               id="custom_title_sports_icons"
                                               data-field="custom_title_sports_icons"
                                               value="{{ $homeSections->custom_title_sports_icons ?? '' }}"
                                               placeholder="Deixe em branco para usar o padrão">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="custom_title_last_bets" class="form-label">
                                            <strong>Título: Últimas Apostas</strong>
                                            <br><small class="text-muted">Padrão: {{ __('menu.last_bets') }}</small>
                                        </label>
                                        <input type="text" 
                                               class="form-control custom-title-input" 
                                               id="custom_title_last_bets"
                                               data-field="custom_title_last_bets"
                                               value="{{ $homeSections->custom_title_last_bets ?? '' }}"
                                               placeholder="Deixe em branco para usar o padrão">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Títulos Personalizados das Seções -->

            <!-- Campos Personalizados -->
            <div class="row layout-top-spacing mt-4">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Campos Personalizados</h5>
                                    <p class="card-text mt-2 text-muted">Crie campos personalizados para exibir jogos selecionados na página inicial.</p>
                                </div>
                                <button type="button" class="btn btn-primary" onclick="openCreateCustomFieldModal()">
                                    <i class="fas fa-plus"></i> Novo Campo
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="customFieldsList">
                                    <!-- Campos serão carregados aqui via JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Campos Personalizados -->

        </div>
    </div>

    <!-- Modal para Criar/Editar Campo Personalizado -->
    <div class="modal fade" id="customFieldModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customFieldModalTitle">Novo Campo Personalizado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="customFieldForm">
                        <input type="hidden" id="customFieldId" name="id">
                        <div class="mb-3">
                            <label for="customFieldTitle" class="form-label">Título do Campo</label>
                            <input type="text" class="form-control" id="customFieldTitle" name="title" required placeholder="Ex: Jogos de Dados">
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="customFieldActive" name="is_active" checked>
                                <label class="form-check-label" for="customFieldActive">Ativo</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="saveCustomField()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Selecionar Jogos -->
    <div class="modal fade" id="selectGamesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Selecionar Jogos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="searchGamesInput" placeholder="Buscar jogos..." onkeyup="searchGames()">
                    </div>
                    <div id="gamesList" style="max-height: 400px; overflow-y: auto;">
                        <!-- Jogos serão carregados aqui -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="addSelectedGames()">Adicionar Selecionados</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Configura os toggles de seções da página inicial
                $('.section-toggle').on('change', function() {
                    const field = $(this).data('field');
                    const value = $(this).prop('checked') ? 1 : 0;
                    const element = $(this);

                    // Desabilitar o toggle enquanto processa
                    element.prop('disabled', true);

                    // Mostrar toast de "processando"
                    const processingToast = ToastManager.info('Processando, aguarde...');

                    $.ajax({
                        url: "{{ route('admin.cassino.update-home-section-settings') }}",
                        type: "POST",
                        data: {
                            field: field,
                            value: value,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Remover toast de processamento
                            processingToast.remove();

                            // Habilitar o toggle
                            element.prop('disabled', false);

                            if (response.success) {
                                ToastManager.success(response.message || 'Configuração atualizada com sucesso!');
                            } else {
                                ToastManager.error(response.message || 'Erro ao atualizar a configuração.');
                                // Reverter a mudança visual
                                element.prop('checked', !element.prop('checked'));
                            }
                        },
                        error: function(xhr, status, error) {
                            // Remover toast de processamento
                            processingToast.remove();

                            // Habilitar o toggle
                            element.prop('disabled', false);

                            ToastManager.error("Erro ao processar a solicitação.");
                            console.error('Erro ao atualizar configuração:', error);

                            // Reverter a mudança visual
                            element.prop('checked', !element.prop('checked'));
                        }
                    });
                });

                // Configurar inputs de títulos personalizados
                $('.custom-title-input').on('blur', function() {
                    const field = $(this).data('field');
                    const value = $(this).val();
                    const element = $(this);

                    // Desabilitar o input enquanto processa
                    element.prop('disabled', true);

                    $.ajax({
                        url: "{{ route('admin.cassino.update-home-section-settings') }}",
                        type: "POST",
                        data: {
                            field: field,
                            value: value,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Habilitar o input
                            element.prop('disabled', false);

                            if (response.success) {
                                ToastManager.success('Título atualizado com sucesso!');
                            } else {
                                ToastManager.error(response.message || 'Erro ao atualizar o título.');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Habilitar o input
                            element.prop('disabled', false);

                            ToastManager.error("Erro ao processar a solicitação.");
                            console.error('Erro ao atualizar título:', error);
                        }
                    });
                });

                // Carregar campos personalizados ao carregar a página
                loadCustomFields();
            });

            function resetCustomTitles() {
                Swal.fire({
                    title: 'Redefinir Títulos',
                    html: 'Tem certeza que deseja redefinir todos os títulos personalizados?<br><br>Todos os títulos serão limpos e voltarão aos valores padrão.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sim, redefinir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.cassino.reset-custom-titles') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    ToastManager.success(response.message);
                                    // Limpar todos os campos de títulos
                                    $('.custom-title-input').val('');
                                } else {
                                    ToastManager.error(response.message || 'Erro ao redefinir títulos.');
                                }
                            },
                            error: function() {
                                ToastManager.error('Erro ao redefinir títulos personalizados.');
                            }
                        });
                    }
                });
            }

            let currentEditingFieldId = null;
            let selectedGameIds = [];

            function loadCustomFields() {
                $.ajax({
                    url: "{{ route('admin.cassino.custom-fields') }}",
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            renderCustomFields(response.fields);
                        }
                    },
                    error: function() {
                        ToastManager.error('Erro ao carregar campos personalizados.');
                    }
                });
            }

            function renderCustomFields(fields) {
                const container = $('#customFieldsList');
                container.empty();

                if (fields.length === 0) {
                    container.html('<p class="text-muted text-center py-4">Nenhum campo personalizado criado ainda.</p>');
                    return;
                }

                fields.forEach(function(field) {
                    const fieldHtml = `
                        <div class="card mb-3 custom-field-card" data-field-id="${field.id}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="mb-1">${field.title}</h6>
                                        <small class="text-muted">ID: ${field.id} | Posição: ${field.position}</small>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch" style="margin: 0;">
                                            <input class="form-check-input custom-field-toggle" type="checkbox" 
                                                   data-field-id="${field.id}" ${field.is_active ? 'checked' : ''}
                                                   style="width: 38px; height: 20px;"
                                                   title="${field.is_active ? 'Desativar' : 'Ativar'}">
                                        </div>
                                        <button class="btn btn-sm btn-primary" onclick="openSelectGamesModal(${field.id})" title="Adicionar Jogos">
                                            <i class="fas fa-plus"></i> Adicionar Jogos
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="editCustomField(${field.id}, '${field.title.replace(/'/g, "\\'")}')" title="Editar">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDeleteCustomField(${field.id}, '${field.title.replace(/'/g, "\\'")}')" title="Deletar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="fieldGames_${field.id}" class="field-games-slider-container">
                                    <div class="field-games-slider" id="fieldGamesSlider_${field.id}">
                                        <!-- Jogos serão carregados aqui -->
                                    </div>
                                    <button class="field-games-slider-btn field-games-slider-prev" onclick="slideFieldGames(${field.id}, 'prev')" style="display: none;">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="field-games-slider-btn field-games-slider-next" onclick="slideFieldGames(${field.id}, 'next')" style="display: none;">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(fieldHtml);
                    loadFieldGames(field.id);
                });

                // Configurar toggles de ativo/inativo
                $('.custom-field-toggle').on('change', function() {
                    const fieldId = $(this).data('field-id');
                    const isActive = $(this).prop('checked');
                    updateCustomFieldStatus(fieldId, isActive);
                });
            }

            function loadFieldGames(fieldId) {
                $.ajax({
                    url: "{{ route('admin.cassino.custom-fields.games', ['id' => ':id']) }}".replace(':id', fieldId),
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            renderFieldGames(fieldId, response.games);
                        }
                    }
                });
            }

            function renderFieldGames(fieldId, games) {
                const slider = $(`#fieldGamesSlider_${fieldId}`);
                const container = slider.parent();
                slider.empty();

                if (games.length === 0) {
                    slider.html('<p class="text-muted small text-center py-3">Nenhum jogo adicionado ainda.</p>');
                    container.find('.field-games-slider-prev, .field-games-slider-next').hide();
                    return;
                }

                const gamesHtml = games.map(function(game, index) {
                    return `
                        <div class="field-game-item">
                            <div class="field-game-card">
                                <img src="${game.image_url || game.image}" alt="${game.name}" class="field-game-image">
                                <p class="field-game-name">${game.name}</p>
                                <button class="field-game-remove" onclick="removeGameFromField(${fieldId}, ${game.id})" title="Remover">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');

                slider.html(gamesHtml);
                
                // Mostrar/ocultar botões de navegação baseado no número de jogos
                const prevBtn = container.find('.field-games-slider-prev');
                const nextBtn = container.find('.field-games-slider-next');
                
                if (games.length > 4) {
                    prevBtn.show();
                    nextBtn.show();
                    // Resetar posição do slider
                    slider.css('transform', 'translateX(0)');
                } else {
                    prevBtn.hide();
                    nextBtn.hide();
                }
            }

            function slideFieldGames(fieldId, direction) {
                const slider = $(`#fieldGamesSlider_${fieldId}`);
                const container = slider.parent();
                const itemWidth = 140; // largura de cada item + margin
                const visibleItems = 4;
                const currentTransform = slider.css('transform');
                let currentX = 0;
                
                if (currentTransform && currentTransform !== 'none') {
                    const matrix = currentTransform.match(/matrix.*\((.+)\)/);
                    if (matrix) {
                        currentX = parseFloat(matrix[1].split(',')[4]) || 0;
                    }
                }
                
                const maxX = -(slider.children().length - visibleItems) * itemWidth;
                let newX = currentX;
                
                if (direction === 'next') {
                    newX = Math.max(currentX - (itemWidth * visibleItems), maxX);
                } else {
                    newX = Math.min(currentX + (itemWidth * visibleItems), 0);
                }
                
                slider.css('transform', `translateX(${newX}px)`);
                slider.css('transition', 'transform 0.3s ease');
                
                // Atualizar visibilidade dos botões
                const prevBtn = container.find('.field-games-slider-prev');
                const nextBtn = container.find('.field-games-slider-next');
                prevBtn.toggle(newX < 0);
                nextBtn.toggle(newX > maxX);
            }

            function openCreateCustomFieldModal() {
                currentEditingFieldId = null;
                $('#customFieldModalTitle').text('Novo Campo Personalizado');
                $('#customFieldForm')[0].reset();
                $('#customFieldId').val('');
                $('#customFieldActive').prop('checked', true);
                new bootstrap.Modal(document.getElementById('customFieldModal')).show();
            }

            function editCustomField(id, title) {
                currentEditingFieldId = id;
                $('#customFieldModalTitle').text('Editar Campo Personalizado');
                $('#customFieldId').val(id);
                $('#customFieldTitle').val(title);
                // Buscar o status atual do campo
                $.ajax({
                    url: "{{ route('admin.cassino.custom-fields') }}",
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            const field = response.fields.find(f => f.id == id);
                            if (field) {
                                $('#customFieldActive').prop('checked', field.is_active);
                            }
                        }
                    }
                });
                new bootstrap.Modal(document.getElementById('customFieldModal')).show();
            }

            function saveCustomField() {
                const formData = {
                    title: $('#customFieldTitle').val(),
                    is_active: $('#customFieldActive').prop('checked') ? true : false, // Garantir boolean
                    _token: "{{ csrf_token() }}"
                };

                const fieldId = $('#customFieldId').val();
                const url = fieldId 
                    ? "{{ route('admin.cassino.custom-fields.update', ['id' => ':id']) }}".replace(':id', fieldId)
                    : "{{ route('admin.cassino.custom-fields.create') }}";
                const method = fieldId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            ToastManager.success(response.message);
                            bootstrap.Modal.getInstance(document.getElementById('customFieldModal')).hide();
                            loadCustomFields();
                        } else {
                            ToastManager.error(response.message);
                        }
                    },
                    error: function() {
                        ToastManager.error('Erro ao salvar campo personalizado.');
                    }
                });
            }

            function confirmDeleteCustomField(id, title) {
                Swal.fire({
                    title: 'Confirmar Exclusão',
                    html: `Tem certeza que deseja deletar o campo <strong>"${title}"</strong>?<br><br>Todos os jogos associados serão removidos e esta ação não pode ser desfeita.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sim, deletar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteCustomField(id);
                    }
                });
            }

            function deleteCustomField(id) {
                $.ajax({
                    url: "{{ route('admin.cassino.custom-fields.delete', ['id' => ':id']) }}".replace(':id', id),
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        if (response.success) {
                            ToastManager.success(response.message);
                            loadCustomFields();
                        } else {
                            ToastManager.error(response.message);
                        }
                    },
                    error: function() {
                        ToastManager.error('Erro ao deletar campo personalizado.');
                    }
                });
            }

            function updateCustomFieldStatus(fieldId, isActive) {
                // Buscar o título atual do campo
                const fieldCard = $('.custom-field-card[data-field-id="' + fieldId + '"]');
                const title = fieldCard.find('h6').text().trim();
                
                $.ajax({
                    url: "{{ route('admin.cassino.custom-fields.update', ['id' => ':id']) }}".replace(':id', fieldId),
                    type: "PUT",
                    data: {
                        title: title,
                        is_active: isActive ? true : false, // Garantir que seja boolean
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            ToastManager.success('Status atualizado com sucesso!');
                        } else {
                            ToastManager.error(response.message);
                        }
                    },
                    error: function() {
                        ToastManager.error('Erro ao atualizar status.');
                    }
                });
            }

            function openSelectGamesModal(fieldId) {
                currentEditingFieldId = fieldId;
                selectedGameIds = [];
                searchGames();
                new bootstrap.Modal(document.getElementById('selectGamesModal')).show();
            }

            function searchGames() {
                const search = $('#searchGamesInput').val();
                $.ajax({
                    url: "{{ route('admin.cassino.games-for-selection') }}",
                    type: "GET",
                    data: { search: search, limit: 100 },
                    success: function(response) {
                        if (response.success) {
                            renderGamesList(response.games);
                        }
                    }
                });
            }

            function renderGamesList(games) {
                const container = $('#gamesList');
                container.empty();

                if (games.length === 0) {
                    container.html('<p class="text-muted text-center py-4">Nenhum jogo encontrado.</p>');
                    return;
                }

                const gamesHtml = games.map(function(game) {
                    const isSelected = selectedGameIds.includes(game.id);
                    return `
                        <div class="game-selection-item mb-2 p-2 border rounded d-flex align-items-center position-relative">
                            <input class="form-check-input game-checkbox" type="checkbox" 
                                   value="${game.id}" id="game_${game.id}" 
                                   ${isSelected ? 'checked' : ''} 
                                   onchange="toggleGameSelection(${game.id})">
                            <label class="game-selection-label d-flex align-items-center w-100" for="game_${game.id}">
                                <img src="${game.image_url || game.image}" alt="${game.name}" 
                                     class="me-2" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                <div>
                                    <strong>${game.name}</strong><br>
                                    <small class="text-muted">${game.provider_name}</small>
                                </div>
                            </label>
                        </div>
                    `;
                }).join('');

                container.html(gamesHtml);
            }

            function toggleGameSelection(gameId) {
                const index = selectedGameIds.indexOf(gameId);
                if (index > -1) {
                    selectedGameIds.splice(index, 1);
                } else {
                    selectedGameIds.push(gameId);
                }
            }

            function addSelectedGames() {
                if (selectedGameIds.length === 0) {
                    ToastManager.warning('Selecione pelo menos um jogo.');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.cassino.custom-fields.games.add', ['id' => ':id']) }}".replace(':id', currentEditingFieldId),
                    type: "POST",
                    data: {
                        game_ids: selectedGameIds,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            ToastManager.success(response.message);
                            bootstrap.Modal.getInstance(document.getElementById('selectGamesModal')).hide();
                            loadFieldGames(currentEditingFieldId);
                        } else {
                            ToastManager.error(response.message);
                        }
                    },
                    error: function() {
                        ToastManager.error('Erro ao adicionar jogos.');
                    }
                });
            }

            function removeGameFromField(fieldId, gameId) {
                if (!confirm('Tem certeza que deseja remover este jogo?')) {
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.cassino.custom-fields.games.remove', ['fieldId' => ':fieldId', 'gameId' => ':gameId']) }}"
                        .replace(':fieldId', fieldId)
                        .replace(':gameId', gameId),
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        if (response.success) {
                            ToastManager.success(response.message);
                            loadFieldGames(fieldId);
                        } else {
                            ToastManager.error(response.message);
                        }
                    },
                    error: function() {
                        ToastManager.error('Erro ao remover jogo.');
                    }
                });
            }

        </script>
    @endpush

    <style>
        /* Estilos para os toggles de seções da página inicial */
        .form-check-input {
            width: 50px;
            height: 25px;
        }

        /* Toggle menor para custom fields */
        .custom-field-card .form-check-input.custom-field-toggle {
            width: 38px;
            height: 20px;
            margin: 0;
        }

        .custom-field-card .form-check {
            padding: 0;
            border: none;
            background-color: transparent;
        }

        body.dark .custom-field-card .form-check {
            background-color: transparent;
            border: none;
        }

        .form-check-label {
            margin-left: 15px;
            font-weight: 500;
        }

        .form-check-input:checked {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        .switch-input{
            background-color: #0d6efd!important;
            border-color: #0d6efd!important;
        }

        body.dark .form-check-input:checked{
            background-color: #4361ee;
            border-color: #4361ee;
        }

        .card-header {
            border-bottom: 1px solid #e0e6ed;
        }

        body.dark .card-header {
            border-bottom: 1px solid #3b3f5c;
        }

        .form-check {
            padding: 15px;
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        body.dark .form-check {
            border-color: #3b3f5c;
            background-color: #1b2e4b;
        }

        .form-check:hover {
            border-color: #4361ee;
            box-shadow: 0 2px 8px rgba(67, 97, 238, 0.1);
        }

        .form-check-label small {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .custom-field-card {
            border: 1px solid #e0e6ed;
            transition: all 0.3s ease;
        }

        body.dark .custom-field-card {
            border-color: #3b3f5c;
        }

        .custom-field-card:hover {
            box-shadow: 0 2px 8px rgba(67, 97, 238, 0.1);
        }

        .field-games-slider-container {
            position: relative;
            overflow: hidden;
            padding: 10px 50px;
            background-color: #f8f9fa;
            border-radius: 4px;
            min-height: 140px;
        }

        body.dark .field-games-slider-container {
            background-color: #1b2e4b;
        }

        .field-games-slider {
            display: flex;
            gap: 10px;
            transition: transform 0.3s ease;
            will-change: transform;
        }

        .field-game-item {
            flex: 0 0 130px;
            width: 130px;
        }

        .field-game-card {
            position: relative;
            background-color: #fff;
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            padding: 8px;
            text-align: center;
            transition: all 0.2s;
            height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        body.dark .field-game-card {
            background-color: #1b2e4b;
            border-color: #3b3f5c;
        }

        .field-game-card:hover {
            box-shadow: 0 2px 8px rgba(67, 97, 238, 0.15);
            transform: translateY(-2px);
        }

        body.dark .field-game-card:hover {
            box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
        }

        .field-game-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #e0e6ed;
        }

        body.dark .field-game-image {
            border-color: #3b3f5c;
        }

        .field-game-name {
            font-size: 0.75rem;
            margin: 4px 0 0 0;
            color: #3b3f5c;
            font-weight: 500;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100%;
        }

        body.dark .field-game-name {
            color: #e0e6ed;
        }

        .field-game-remove {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: #dc3545;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.2s;
            padding: 0;
        }

        .field-game-remove:hover {
            background-color: #c82333;
            transform: scale(1.1);
        }

        .field-games-slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #4361ee;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .field-games-slider-btn:hover {
            background-color: #2f4cdd;
            transform: translateY(-50%) scale(1.1);
        }

        .field-games-slider-prev {
            left: 8px;
        }

        .field-games-slider-next {
            right: 8px;
        }

        /* Estilos para o modal de seleção de jogos */
        .game-selection-item {
            position: relative;
            min-height: 70px;
            transition: background-color 0.2s;
        }

        .game-selection-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .game-selection-item .game-checkbox {
            position: absolute !important;
            left: 15px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 10 !important;
            width: 20px !important;
            height: 20px !important;
            cursor: pointer !important;
            margin: 0 !important;
            opacity: 1 !important;
            visibility: visible !important;
            flex-shrink: 0 !important;
        }

        .game-selection-item .game-selection-label {
            margin-left: 40px !important;
            cursor: pointer;
            width: calc(100% - 40px);
            padding-left: 0;
        }

        body.dark .game-selection-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
    </style>
@endsection
