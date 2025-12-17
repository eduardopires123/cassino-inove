@extends('admin.layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <!-- BREADCRUMB -->
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Personalização</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Configuração da Roleta</li>
                    </ol>
                </nav>
                
                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('admin.roulette.resgates') }}" class="btn btn-primary">
                        <i class="fas fa-chart-line"></i> Ver Resgates
                    </a>
                </div>
            </div>
            <!-- /BREADCRUMB -->

            <!-- Configurações da Roleta -->
            <div class="row layout-top-spacing">
                <!-- Card de Controle Geral -->
                <div class="col-xl-6 col-lg-6 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <div class="card roulette-control-card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Controle Geral da Roleta</h5>
                                <p class="card-text mt-2 text-muted">Ative ou desative a exibição da roleta no site.</p>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center mb-3">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-dice fa-2x text-primary me-3"></i>
                                            <div>
                                                <h6 class="mb-1">Status da Roleta</h6>
                                                <small class="text-muted">Controle a visibilidade da roleta flutuante</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="rouletteToggle" 
                                                   {{ $homeSections->show_roulette ? 'checked' : '' }}>
                                            <label class="form-check-label" for="rouletteToggle">
                                                <span id="rouletteStatus">{{ $homeSections->show_roulette ? 'Ativada' : 'Desativada' }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-gift fa-2x text-success me-3"></i>
                                            <div>
                                                <h6 class="mb-1">Prêmio Grátis Diário</h6>
                                                <small class="text-muted">Permitir 1 giro grátis por dia para usuários logados</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="freePrizeToggle" 
                                                   {{ $rouletteSettings->enable_free_daily_spin ?? true ? 'checked' : '' }}>
                                            <label class="form-check-label" for="freePrizeToggle">
                                                <span id="freePrizeStatus">{{ $rouletteSettings->enable_free_daily_spin ?? true ? 'Ativado' : 'Desativado' }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card de Configurações Avançadas -->
                <div class="col-xl-6 col-lg-6 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Configurações Avançadas</h5>
                                <p class="card-text mt-2 text-muted">Personalize o comportamento da roleta.</p>
                            </div>
                            <div class="card-body">
                                <form id="rouletteSettingsForm">
                                    <div class="mb-3">
                                        <label for="enableFreeDailySpin" class="form-label">Prêmio Grátis Diário</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="enableFreeDailySpin" name="enable_free_daily_spin" 
                                                   {{ $rouletteSettings->enable_free_daily_spin ?? true ? 'checked' : '' }}>
                                            <label class="form-check-label" for="enableFreeDailySpin">
                                                Permitir 1 giro grátis por dia para usuários logados
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="maxSpinsPerDay" class="form-label">Máximo de Giros por Dia</label>
                                        <input type="number" class="form-control" id="maxSpinsPerDay" name="max_spins_per_day" 
                                               value="{{ $rouletteSettings->max_spins_per_day ?? 5 }}" min="1" max="20">
                                        <small class="text-muted">Limite de giros diários para usuários logados</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="guestSpinsEnabled" class="form-label">Giros para Visitantes</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="guestSpinsEnabled" name="guest_spins_enabled" 
                                                   {{ $rouletteSettings->guest_spins_enabled ?? true ? 'checked' : '' }}>
                                            <label class="form-check-label" for="guestSpinsEnabled">
                                                Permitir giros ilimitados para usuários não logados
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="animationDuration" class="form-label">Duração da Animação (segundos)</label>
                                        <input type="number" class="form-control" id="animationDuration" name="animation_duration" 
                                               value="{{ $rouletteSettings->animation_duration ?? 4 }}" min="2" max="10" step="0.5">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Salvar Configurações
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Card de Configuração dos Itens -->
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Itens da Roleta</h5>
                                    <p class="card-text mt-2 text-muted">Gerencie os itens e probabilidades da roleta.</p>
                                </div>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                    <i class="fas fa-plus"></i> Adicionar Item
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%">ID</th>
                                                <th width="20%">Nome</th>
                                                <th width="10%">Giros</th>
                                                <th width="15%">Jogo</th>
                                                <th width="8%">Cor</th>
                                                <th width="12%">Cupom</th>
                                                <th width="10%">Prob. (%)</th>
                                                <th width="10%">Depósito</th>
                                                <th width="10%">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rouletteItems as $item)
                                                <tr id="item-row-{{ $item->id }}" class="{{ !$item->is_active ? 'table-secondary' : '' }}">
                                                    <td>
                                                        <span class="badge badge-outline-primary">{{ $item->id }}</span>
                                                    </td>
                                                    <td>
                                                        <strong>{{ $item->name }}</strong>
                                                        @if(!$item->is_active)
                                                            <small class="text-muted d-block">Inativo</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item->free_spins > 0)
                                                            <span class="badge badge-success">{{ $item->free_spins }}</span>
                                                        @else
                                                            <span class="badge badge-secondary">0</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item->game_name)
                                                            <small class="text-muted">{{ $item->game_name }}</small>
                                                        @else
                                                            <small class="text-muted">-</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="color-preview me-2" 
                                                                 style="width: 20px; height: 20px; background-color: {{ $item->color_code }}; border-radius: 3px; border: 1px solid #ddd;"></div>
                                                            <small>{{ $item->color_code }}</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($item->coupon_code && $item->coupon_code !== 'NADA')
                                                            <code class="text-primary">{{ $item->coupon_code }}</code>
                                                        @else
                                                            <small class="text-muted">-</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">{{ number_format($item->probability * 100, 2) }}%</span>
                                                    </td>
                                                    <td>
                                                        @if($item->deposit_value > 0)
                                                            <span class="text-success fw-bold">R$ {{ number_format($item->deposit_value, 2, ',', '.') }}</span>
                                                        @else
                                                            <span class="text-muted">Grátis</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-outline-primary edit-item-btn" 
                                                                    data-item="{{ json_encode($item) }}"
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#editItemModal"
                                                                    title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-success toggle-status-btn" 
                                                                    data-id="{{ $item->id }}"
                                                                    data-status="{{ $item->is_active }}"
                                                                    title="{{ $item->is_active ? 'Desativar' : 'Ativar' }}">
                                                                <i class="fas fa-{{ $item->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger delete-item-btn" 
                                                                    data-id="{{ $item->id }}"
                                                                    title="Excluir">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    
                                    @if($rouletteItems->isEmpty())
                                        <div class="text-center py-4">
                                            <i class="fas fa-dice fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Nenhum item da roleta encontrado</h5>
                                            <p class="text-muted">Adicione itens para configurar a roleta.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Configurações da Roleta -->

        </div>
    </div>

    <!-- Modal Adicionar Item -->
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Adicionar Item da Roleta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addItemForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="add_name" class="form-label">Nome do Item</label>
                                    <input type="text" class="form-control" id="add_name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="add_free_spins" class="form-label">Giros Grátis</label>
                                    <input type="number" class="form-control" id="add_free_spins" name="free_spins" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="add_game_name" class="form-label">Nome do Jogo</label>
                                    <input type="text" class="form-control" id="add_game_name" name="game_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="add_color_code" class="form-label">Cor (Hex)</label>
                                    <input type="color" class="form-control" id="add_color_code" name="color_code" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="add_coupon_code" class="form-label">Código do Cupom</label>
                                    <input type="text" class="form-control" id="add_coupon_code" name="coupon_code">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="add_probability" class="form-label">Probabilidade (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="add_probability" name="probability" min="0" max="100" step="0.01" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Chance de sortear este item (0 a 100%)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="add_deposit_value" class="form-label">Valor Depósito Mínimo</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control" id="add_deposit_value" name="deposit_value" min="0" step="0.01" required>
                                    </div>
                                    <small class="text-muted">Valor mínimo para resgatar o prêmio</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="add_show_modal" name="show_modal" checked>
                                        <label class="form-check-label" for="add_show_modal">
                                            Exibir Modal
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="add_is_active" name="is_active" checked>
                                        <label class="form-check-label" for="add_is_active">
                                            Ativo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Item -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel">Editar Item da Roleta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editItemForm">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Nome do Item</label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_free_spins" class="form-label">Giros Grátis</label>
                                    <input type="number" class="form-control" id="edit_free_spins" name="free_spins" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_game_name" class="form-label">Nome do Jogo</label>
                                    <input type="text" class="form-control" id="edit_game_name" name="game_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_color_code" class="form-label">Cor (Hex)</label>
                                    <input type="color" class="form-control" id="edit_color_code" name="color_code" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_coupon_code" class="form-label">Código do Cupom</label>
                                    <input type="text" class="form-control" id="edit_coupon_code" name="coupon_code">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_probability" class="form-label">Probabilidade (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="edit_probability" name="probability" min="0" max="100" step="0.01" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Chance de sortear este item (0 a 100%)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_deposit_value" class="form-label">Valor Depósito Mínimo</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control" id="edit_deposit_value" name="deposit_value" min="0" step="0.01" required>
                                    </div>
                                    <small class="text-muted">Valor mínimo para resgatar o prêmio</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit_show_modal" name="show_modal">
                                        <label class="form-check-label" for="edit_show_modal">
                                            Exibir Modal
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active">
                                        <label class="form-check-label" for="edit_is_active">
                                            Ativo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Controle do toggle da roleta
                $('#rouletteToggle').on('change', function() {
                    updateSetting($(this), 'show_roulette', '#rouletteStatus', 'Ativada', 'Desativada');
                });

                // Controle do toggle do prêmio grátis
                $('#freePrizeToggle').on('change', function() {
                    updateSetting($(this), 'enable_free_daily_spin', '#freePrizeStatus', 'Ativado', 'Desativado');
                });

                // Função genérica para atualizar configurações
                function updateSetting(element, settingName, statusElement, activeText, inactiveText) {
                    const isChecked = element.is(':checked');
                    const value = isChecked ? 1 : 0;

                    // Desabilitar o toggle enquanto processa
                    element.prop('disabled', true);

                    // Mostrar toast de "processando"
                    const processingToast = ToastManager.info('Processando, aguarde...');
                    
                    $.ajax({
                        url: "{{ route('admin.roulette.settings') }}",
                        type: "POST",
                        data: {
                            [settingName]: value,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Remover toast de processamento
                            processingToast.remove();

                            // Habilitar o toggle
                            element.prop('disabled', false);

                            if (response.success) {
                                ToastManager.success(response.message || 'Configurações atualizadas com sucesso!');
                                $(statusElement).text(isChecked ? activeText : inactiveText);
                            } else {
                                ToastManager.error(response.message || 'Erro ao atualizar configurações.');
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
                }

                // Salvar configurações avançadas
                $('#rouletteSettingsForm').on('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = $(this).serialize() + '&_token={{ csrf_token() }}';
                    
                    $.ajax({
                        url: "{{ route('admin.roulette.settings') }}",
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                ToastManager.success(response.message || 'Configurações salvas com sucesso!');
                            } else {
                                ToastManager.error(response.message || 'Erro ao salvar configurações.');
                            }
                        },
                        error: function(xhr) {
                            ToastManager.error('Erro ao salvar configurações avançadas');
                        }
                    });
                });

                // Adicionar item
                $('#addItemForm').on('submit', function(e) {
                    e.preventDefault();
                    
                    // Converter probabilidade de % para decimal
                    const probability = parseFloat($('#add_probability').val()) / 100;
                    
                    let formData = $(this).serialize() + '&_token={{ csrf_token() }}';
                    formData = formData.replace(/probability=[^&]*/, 'probability=' + probability);
                    
                    $.ajax({
                        url: "{{ route('admin.roulette.create') }}",
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                ToastManager.success(response.message);
                                $('#addItemModal').modal('hide');
                                $('#addItemForm')[0].reset();
                                location.reload();
                            } else {
                                ToastManager.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            const errors = xhr.responseJSON?.errors;
                            if (errors) {
                                let errorMessage = 'Erros de validação:\n';
                                Object.keys(errors).forEach(key => {
                                    errorMessage += '• ' + errors[key][0] + '\n';
                                });
                                ToastManager.error(errorMessage);
                            } else {
                                ToastManager.error('Erro ao adicionar item da roleta');
                            }
                        }
                    });
                });

                // Editar item
                $(document).on('click', '.edit-item-btn', function() {
                    const item = $(this).data('item');
                    
                    $('#edit_id').val(item.id);
                    $('#edit_name').val(item.name);
                    $('#edit_free_spins').val(item.free_spins);
                    $('#edit_game_name').val(item.game_name);
                    $('#edit_color_code').val(item.color_code);
                    $('#edit_coupon_code').val(item.coupon_code);
                    $('#edit_probability').val((item.probability * 100).toFixed(2)); // Converter para %
                    $('#edit_deposit_value').val(item.deposit_value);
                    $('#edit_show_modal').prop('checked', item.show_modal);
                    $('#edit_is_active').prop('checked', item.is_active);
                });

                $('#editItemForm').on('submit', function(e) {
                    e.preventDefault();
                    
                    // Converter probabilidade de % para decimal
                    const probability = parseFloat($('#edit_probability').val()) / 100;
                    
                    let formData = $(this).serialize() + '&_token={{ csrf_token() }}';
                    formData = formData.replace(/probability=[^&]*/, 'probability=' + probability);
                    
                    $.ajax({
                        url: "{{ route('admin.roulette.update') }}",
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                ToastManager.success(response.message);
                                $('#editItemModal').modal('hide');
                                location.reload();
                            } else {
                                ToastManager.error(response.message);
                            }
                        },
                        error: function(xhr) {
                            const errors = xhr.responseJSON?.errors;
                            if (errors) {
                                let errorMessage = 'Erros de validação:\n';
                                Object.keys(errors).forEach(key => {
                                    errorMessage += '• ' + errors[key][0] + '\n';
                                });
                                ToastManager.error(errorMessage);
                            } else {
                                ToastManager.error('Erro ao atualizar item da roleta');
                            }
                        }
                    });
                });

                // Toggle de status dos itens
                $(document).on('click', '.toggle-status-btn', function() {
                    const id = $(this).data('id');
                    const currentStatus = $(this).data('status');
                    const newStatus = !currentStatus;
                    const button = $(this);
                    
                    button.prop('disabled', true);
                    
                    $.ajax({
                        url: "{{ route('admin.roulette.toggle-status') }}",
                        type: "POST",
                        data: {
                            id: id,
                            status: newStatus ? 1 : 0,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                ToastManager.success(response.message);
                                location.reload();
                            } else {
                                ToastManager.error(response.message);
                                button.prop('disabled', false);
                            }
                        },
                        error: function(xhr) {
                            ToastManager.error('Erro ao alterar status do item');
                            button.prop('disabled', false);
                        }
                    });
                });

                // Deletar item
                $(document).on('click', '.delete-item-btn', function() {
                    const id = $(this).data('id');
                    const itemRow = $(`#item-row-${id}`);
                    const itemName = itemRow.find('strong').text();
                    
                    // Modal de confirmação mais elegante
                    if (confirm(`Tem certeza que deseja excluir o item "${itemName}"?\n\nEsta ação não pode ser desfeita.`)) {
                        const button = $(this);
                        button.prop('disabled', true);
                        
                        $.ajax({
                            url: "{{ route('admin.roulette.delete') }}",
                            type: "POST",
                            data: {
                                id: id,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    ToastManager.success(response.message);
                                    // Animação de remoção
                                    itemRow.fadeOut(300, function() {
                                        $(this).remove();
                                    });
                                } else {
                                    ToastManager.error(response.message);
                                    button.prop('disabled', false);
                                }
                            },
                            error: function(xhr) {
                                ToastManager.error('Erro ao deletar item da roleta');
                                button.prop('disabled', false);
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection 