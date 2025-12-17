@extends('admin.layouts.app')
@section('content')
@php
    use Carbon\Carbon;
    
    $t = request()->input('type', '');
    $a = request()->input('active', '');
    $v = request()->input('vip', '');
    
    $tipoFiltro = $t;
    $statusAtivo = $a;
    $nivelVIP = $v;
@endphp
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Cashback</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Configurações</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8" style="padding: 20px;">
                    <div class="row mb-4">
                        <div class="col-md-12 text-end">
                            <a href="#" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#createCashbackModal">
                                <i class="fas fa-plus me-2"></i>Nova Configuração
                            </a>
                            
                            <a href="#" class="btn btn-info mb-2 ms-2" data-bs-toggle="modal" data-bs-target="#userSpecificCashbackModal">
                                <i class="fas fa-user me-2"></i>Cashback para Usuário
                            </a>

                            <a href="#" class="btn btn-success mb-2 ms-2" data-bs-toggle="modal" data-bs-target="#processScheduledModal">
                                <i class="fas fa-clock me-2"></i>Processar Agendados
                            </a>
                        </div>
                    </div>

                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <div class="row" style="margin-bottom: -20px; padding:15px;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="tipoFiltro" class="form-label">Tipo:</label>
                                        <select id="tipoFiltro" name="type" class="form-control filter-input">
                                            <option value="" {{ $t == '' ? 'selected' : '' }}>Todos</option>
                                            <option value="all" {{ $t == 'all' ? 'selected' : '' }}>Todos os Jogos</option>
                                            <option value="sports" {{ $t == 'sports' ? 'selected' : '' }}>Apostas Esportivas</option>
                                            <option value="virtual" {{ $t == 'virtual' ? 'selected' : '' }}>Cassino</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="statusAtivo" class="form-label">Status:</label>
                                        <select id="statusAtivo" name="active" class="form-control filter-input">
                                            <option value="" {{ $a == '' ? 'selected' : '' }}>Todos</option>
                                            <option value="1" {{ $a == '1' ? 'selected' : '' }}>Ativo</option>
                                            <option value="0" {{ $a == '0' ? 'selected' : '' }}>Inativo</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="nivelVIP" class="form-label">Nível VIP:</label>
                                        <select id="nivelVIP" name="vip" class="form-control filter-input">
                                            <option value="" {{ $v == '' ? 'selected' : '' }}>Todos</option>
                                            <option value="global" {{ $v == 'global' ? 'selected' : '' }}>Global</option>
                                            <option value="1" {{ $v == '1' ? 'selected' : '' }}>Bronze</option>
                                            <option value="2" {{ $v == '2' ? 'selected' : '' }}>Prata</option>
                                            <option value="3" {{ $v == '3' ? 'selected' : '' }}>Ouro</option>
                                            <option value="4" {{ $v == '4' ? 'selected' : '' }}>Diamante</option>
                                            <option value="5" {{ $v == '5' ? 'selected' : '' }}>Platina</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="datatable-cashback" class="table table-striped dt-table-hover dataTable" style="width:100%" role="grid" aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashback" rowspan="1" colspan="1">Nome</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashback" rowspan="1" colspan="1">Tipo</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashback" rowspan="1" colspan="1">Percentual</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashback" rowspan="1" colspan="1">Mín. Perda</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashback" rowspan="1" colspan="1">Máx. Cashback</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashback" rowspan="1" colspan="1">Nível VIP</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashback" rowspan="1" colspan="1">Agendamento</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashback" rowspan="1" colspan="1">Status</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashback" rowspan="1" colspan="1">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Os dados serão preenchidos pelo DataTable -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">Nome</th>
                                        <th rowspan="1" colspan="1">Tipo</th>
                                        <th rowspan="1" colspan="1">Percentual</th>
                                        <th rowspan="1" colspan="1">Mín. Perda</th>
                                        <th rowspan="1" colspan="1">Máx. Cashback</th>
                                        <th rowspan="1" colspan="1">Nível VIP</th>
                                        <th rowspan="1" colspan="1">Agendamento</th>
                                        <th rowspan="1" colspan="1">Status</th>
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

<!-- Modal para Processar Cashbacks Agendados -->
<div class="modal fade" id="processScheduledModal" tabindex="-1" aria-labelledby="processScheduledModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="processScheduledModalLabel">Processar Cashbacks Agendados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <p>Isso processará todos os cashbacks agendados que estão prontos para execução. Deseja continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('admin.cashback.process.scheduled') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Processar Agora</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Criar Nova Configuração de Cashback -->
<div class="modal fade" id="createCashbackModal" tabindex="-1" aria-labelledby="createCashbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCashbackModalLabel">Nova Configuração de Cashback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <form action="{{ route('admin.cashback.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome da Configuração <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="type" class="form-label">Tipo de Jogo <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="all">Todos os Jogos</option>
                                <option value="sports">Apostas Esportivas</option>
                                <option value="virtual">Cassino</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="percentage" class="form-label">Percentual (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0.01" max="100" class="form-control" id="percentage" name="percentage" required>
                        </div>
                        <div class="col-md-4">
                            <label for="min_loss" class="form-label">Perda Mínima (R$) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="min_loss" name="min_loss" required>
                        </div>
                        <div class="col-md-4">
                            <label for="max_cashback" class="form-label">Cashback Máximo (R$)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="max_cashback" name="max_cashback">
                            <small class="text-muted">Deixe em branco para sem limite</small>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="expiry_days" class="form-label">Dias para Expirar <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control" id="expiry_days" name="expiry_days" value="7" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-block">Aplicação Automática</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="auto_apply" name="auto_apply" value="1">
                                <label class="form-check-label" for="auto_apply">Aplicar automaticamente</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label d-block">Nível VIP</label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="is_global" name="is_global" value="1" checked>
                                <label class="form-check-label" for="is_global">Global (todos os níveis)</label>
                            </div>
                            <select class="form-control" id="vip_level" name="vip_level" disabled>
                                <option value="">Selecione o nível VIP</option>
                                <option value="1">Bronze</option>
                                <option value="2">Prata</option>
                                <option value="3">Ouro</option>
                                <option value="4">Diamante</option>
                                <option value="5">Platina</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="active" name="active" value="1" checked>
                                <label class="form-check-label" for="active">Ativo</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6>Configurações de Agendamento</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="schedule_active" name="schedule_active" value="1">
                                <label class="form-check-label" for="schedule_active">Habilitar processamento agendado</label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="schedule-options" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="scheduled_frequency" class="form-label">Frequência</label>
                                <select class="form-control" id="scheduled_frequency" name="scheduled_frequency">
                                    <option value="daily">Diária</option>
                                    <option value="weekly">Semanal</option>
                                    <option value="biweekly">Quinzenal</option>
                                    <option value="monthly">Mensal</option>
                                </select>
                            </div>
                            <div class="col-md-4" id="weekday-container" style="display: none;">
                                <label for="scheduled_day" class="form-label">Dia da Semana</label>
                                <select class="form-control" id="scheduled_day" name="scheduled_day">
                                    <option value="0">Domingo</option>
                                    <option value="1">Segunda</option>
                                    <option value="2">Terça</option>
                                    <option value="3">Quarta</option>
                                    <option value="4">Quinta</option>
                                    <option value="5">Sexta</option>
                                    <option value="6">Sábado</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="scheduled_hour" class="form-label">Hora</label>
                                <select class="form-control" id="scheduled_hour" name="scheduled_hour">
                                    @for ($i = 0; $i < 24; $i++)
                                        <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="scheduled_minute" class="form-label">Minuto</label>
                                <select class="form-control" id="scheduled_minute" name="scheduled_minute">
                                    @for ($i = 0; $i < 60; $i += 5)
                                        <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Configurar Cashback para Usuário Específico -->
<div class="modal fade" id="userSpecificCashbackModal" tabindex="-1" aria-labelledby="userSpecificCashbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userSpecificCashbackModalLabel">Cashback para Usuário Específico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <form action="/admin/cashback/user/specific" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="select-user" class="form-label">Selecionar Usuário <span class="text-danger">*</span></label>
                            <select id="select-user" placeholder="Buscar usuário por nome ou email..." autocomplete="off" name="user_id" required>
                                <option value="">Buscar usuário...</option>
                            </select>
                            <div id="user-actions" class="mt-2" style="display: none;">
                                <button type="button" id="btn-view-user-losses" class="btn btn-sm btn-info">
                                    <i class="fas fa-chart-line me-1"></i>Visualizar Perdas
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="user_cashback_name" class="form-label">Nome da Configuração <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="user_cashback_name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="user_cashback_type" class="form-label">Tipo de Jogo <span class="text-danger">*</span></label>
                            <select class="form-control" id="user_cashback_type" name="type" required>
                                <option value="all">Todos os Jogos</option>
                                <option value="sports">Apostas Esportivas</option>
                                <option value="virtual">Cassino</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="user_cashback_percentage" class="form-label">Percentual (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0.01" max="100" class="form-control" id="user_cashback_percentage" name="percentage" required>
                        </div>
                        <div class="col-md-4">
                            <label for="user_cashback_min_loss" class="form-label">Perda Mínima (R$) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="user_cashback_min_loss" name="min_loss" required>
                        </div>
                        <div class="col-md-4">
                            <label for="user_cashback_max_cashback" class="form-label">Cashback Máximo (R$)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="user_cashback_max_cashback" name="max_cashback">
                            <small class="text-muted">Deixe em branco para sem limite</small>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="user_cashback_expiry_days" class="form-label">Dias para Expirar <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control" id="user_cashback_expiry_days" name="expiry_days" value="7" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-block">Aplicação Automática</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="user_cashback_auto_apply" name="auto_apply" value="1">
                                <label class="form-check-label" for="user_cashback_auto_apply">Aplicar automaticamente</label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6>Configurações de Agendamento</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="user_schedule_active" name="schedule_active" value="1" checked>
                                <label class="form-check-label" for="user_schedule_active">Habilitar processamento agendado</label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="user-schedule-options">
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="schedule_frequency" class="form-label">Frequência de Processamento</label>
                                <select class="form-control" id="schedule_frequency" name="schedule_frequency" required>
                                    <option value="once">Uma vez (na data selecionada)</option>
                                    <option value="daily">Diariamente</option>
                                    <option value="weekly">Semanalmente</option>
                                    <option value="biweekly">Quinzenalmente</option>
                                    <option value="monthly">Mensalmente</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="user_scheduled_date" class="form-label">Data de Processamento</label>
                                <input type="date" class="form-control" id="user_scheduled_date" name="scheduled_date" required min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="user_scheduled_time" class="form-label">Hora de Processamento</label>
                                <input type="time" class="form-control" id="user_scheduled_time" name="scheduled_time" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Configuração de Cashback -->
<div class="modal fade" id="editCashbackModal" tabindex="-1" aria-labelledby="editCashbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCashbackModalLabel">Editar Configuração de Cashback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <form id="editCashbackForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_name" class="form-label">Nome da Configuração <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_type" class="form-label">Tipo de Jogo <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_type" name="type" required>
                                <option value="all">Todos os Jogos</option>
                                <option value="sports">Apostas Esportivas</option>
                                <option value="virtual">Cassino</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="edit_percentage" class="form-label">Percentual (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0.01" max="100" class="form-control" id="edit_percentage" name="percentage" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_min_loss" class="form-label">Perda Mínima (R$) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="edit_min_loss" name="min_loss" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_max_cashback" class="form-label">Cashback Máximo (R$)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="edit_max_cashback" name="max_cashback">
                            <small class="text-muted">Deixe em branco para sem limite</small>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="edit_expiry_days" class="form-label">Dias para Expirar <span class="text-danger">*</span></label>
                            <input type="number" min="1" class="form-control" id="edit_expiry_days" name="expiry_days" value="7" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label d-block">Aplicação Automática</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit_auto_apply" name="auto_apply" value="1">
                                <label class="form-check-label" for="edit_auto_apply">Aplicar automaticamente</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label d-block">Configuração Global/VIP</label>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="edit_is_global" name="is_global" value="1">
                                <label class="form-check-label" for="edit_is_global">Global (todos os níveis)</label>
                            </div>
                            <select class="form-control" id="edit_vip_level" name="vip_level">
                                <option value="">Selecione o nível VIP</option>
                                <option value="1">Bronze</option>
                                <option value="2">Prata</option>
                                <option value="3">Ouro</option>
                                <option value="4">Diamante</option>
                                <option value="5">Platina</option>
                            </select>
                        </div>
                    </div>
                    
                    <hr>
                    <h6>Configurações de Agendamento</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="edit_schedule_active" name="schedule_active" value="1">
                                <label class="form-check-label" for="edit_schedule_active">Habilitar processamento agendado</label>
                            </div>
                        </div>
                    </div>
                    
                    <div id="edit-schedule-options" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="edit_scheduled_frequency" class="form-label">Frequência</label>
                                <select class="form-control" id="edit_scheduled_frequency" name="scheduled_frequency">
                                    <option value="daily">Diária</option>
                                    <option value="weekly">Semanal</option>
                                    <option value="biweekly">Quinzenal</option>
                                    <option value="monthly">Mensal</option>
                                </select>
                            </div>
                            <div class="col-md-4" id="edit-weekday-container" style="display: none;">
                                <label for="edit_scheduled_day" class="form-label">Dia da Semana</label>
                                <select class="form-control" id="edit_scheduled_day" name="scheduled_day">
                                    <option value="0">Domingo</option>
                                    <option value="1">Segunda</option>
                                    <option value="2">Terça</option>
                                    <option value="3">Quarta</option>
                                    <option value="4">Quinta</option>
                                    <option value="5">Sexta</option>
                                    <option value="6">Sábado</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_scheduled_hour" class="form-label">Hora</label>
                                <select class="form-control" id="edit_scheduled_hour" name="scheduled_hour">
                                    @for ($i = 0; $i < 24; $i++)
                                        <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="edit_scheduled_minute" class="form-label">Minuto</label>
                                <select class="form-control" id="edit_scheduled_minute" name="scheduled_minute">
                                    @for ($i = 0; $i < 60; $i += 5)
                                        <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Visualizar Detalhes das Perdas -->
<div class="modal fade" id="viewLossesModal" tabindex="-1" aria-labelledby="viewLossesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewLossesModalLabel">Detalhes das Perdas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <div id="lossesContent" class="p-2">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
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

<style>
    body.dark .form-check-input:checked {
        background-color: #4361ee;
        border-color: #4361ee;
    }
</style>

@push('scripts')
<script>
    $(function () {
        // Configuração para suprimir mensagens de erro do DataTables em console
        $.fn.dataTable.ext.errMode = 'none';
        
        // TomSelect para busca de usuários
        var userSelect;
        
        // Verificar se o elemento existe e se o TomSelect já foi inicializado
        if ($("#select-user").length && !$("#select-user").hasClass("tomselected")) {
            userSelect = new TomSelect("#select-user", {
                valueField: 'id',
                labelField: 'name',
                searchField: ['name', 'email', 'cpf'],
                create: false,
                load: function(query, callback) {
                    if (!query.length) return callback();
                    
                    $.ajax({
                        url: "/admin/usuarios/search",
                        type: "GET",
                        data: { search: query },
                        dataType: "json",
                        error: function() {
                            callback();
                        },
                        success: function(response) {
                            if (response.users && response.users.length > 0) {
                                callback(response.users.map(function(user) {
                                    return {
                                        id: user.id,
                                        name: user.name + ' (' + user.email + ')',
                                        email: user.email,
                                        cpf: user.cpf
                                    };
                                }));
                            } else {
                                callback();
                            }
                        }
                    });
                },
                render: {
                    option: function(item, escape) {
                        return '<div>' + 
                            '<div><strong>' + escape(item.name) + '</strong></div>' +
                            '<div>Email: ' + escape(item.email) + '</div>' +
                            (item.cpf ? '<div>CPF: ' + escape(item.cpf) + '</div>' : '') +
                        '</div>';
                    },
                    item: function(item, escape) {
                        return '<div>' + escape(item.name) + '</div>';
                    }
                },
                onItemAdd: function(value, item) {
                    const userName = item.textContent.split(' (')[0];
                    $('#user_cashback_name').val('Cashback Especial - ' + userName);
                    
                    // Mostrar ações do usuário
                    $('#user-actions').show();
                    
                    // Configurar botão de visualização de perdas
                    $('#btn-view-user-losses').data('user-id', value);
                    $('#btn-view-user-losses').data('user-name', userName);
                }
            });
        } else if ($("#select-user").length) {
            // Se já estiver inicializado, apenas obtemos a instância existente
            userSelect = $("#select-user")[0].tomselect;
        }
        
        // Inicializar botão de visualizar perdas do usuário
        $('#btn-view-user-losses').on('click', function() {
            var userId = $(this).data('user-id');
            var userName = $(this).data('user-name');
            
            // Atualizar título do modal
            $('#viewLossesModalLabel').text('Detalhes das Perdas - ' + userName);
            
            // Abrir o modal
            $('#viewLossesModal').modal('show');
            
            // Mostrar toast de carregamento
            const loadingToast = ToastManager.info('Carregando detalhes, aguarde...');
            
            // Carregar os dados
            $.ajax({
                url: "{{ route('admin.cashback.user.losses.ajax') }}",
                type: "GET",
                data: { user_id: userId },
                dataType: "html",
                success: function(response) {
                    // Remover toast de carregamento
                    loadingToast.remove();
                    
                    $('#lossesContent').html(response);
                },
                error: function(xhr, status, error) {
                    // Remover toast de carregamento
                    loadingToast.remove();
                    
                    $('#lossesContent').html('<div class="alert alert-danger">Erro ao carregar detalhes: ' + error + '</div>');
                    
                    // Mostrar mensagem de erro
                    ToastManager.error('Erro ao carregar detalhes das perdas.');
                }
            });
        });
        
        // Reset do modal de cashback específico para usuário quando fechado
        $('#userSpecificCashbackModal').on('hidden.bs.modal', function() {
            $('#user-actions').hide();
            $(this).find('form')[0].reset();
            userSelect.clear();
        });
        
        // Variável para armazenar o timeout da digitação
        var typingTimer;
        var doneTypingInterval = 500; // Tempo em ms para aguardar após a digitação

        // Função para inicializar o datatable com as opções desejadas
        function initDatatable(tipoFiltro, statusAtivo, nivelVIP) {
            var table = $('#datatable-cashback').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.cashback.settings.data') }}",
                    data: function (d) {
                        d.tipoFiltro = tipoFiltro || $('#tipoFiltro').val();
                        d.statusAtivo = statusAtivo || $('#statusAtivo').val();
                        d.nivelVIP = nivelVIP || $('#nivelVIP').val();
                    },
                    error: function (xhr, error, thrown) {
                    }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'type', name: 'type'},
                    {data: 'percentage', name: 'percentage'},
                    {data: 'min_loss', name: 'min_loss'},
                    {data: 'max_cashback', name: 'max_cashback'},
                    {data: 'vip_level', name: 'vip_level'},
                    {data: 'schedule', name: 'schedule'},
                    {data: 'status', name: 'status'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
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
                order: [[7, 'desc']],
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
                    $('#datatable-cashback_paginate').addClass('paging_simple_numbers');
                    $('#datatable-cashback_paginate ul.pagination li').addClass('paginate_button page-item');
                    $('#datatable-cashback_paginate ul.pagination li.previous').attr('id', 'datatable-cashback_previous');
                    $('#datatable-cashback_paginate ul.pagination li.next').attr('id', 'datatable-cashback_next');
                    $('#datatable-cashback_paginate ul.pagination li.first').attr('id', 'datatable-cashback_first');
                    $('#datatable-cashback_paginate ul.pagination li.last').attr('id', 'datatable-cashback_last');
                    $('#datatable-cashback_paginate ul.pagination li a').addClass('page-link');
                    
                    // Substituir o texto dos botões de paginação por ícones SVG
                    $('#datatable-cashback_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                    $('#datatable-cashback_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                    $('#datatable-cashback_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                    $('#datatable-cashback_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');
                },
                initComplete: function() {
                    // Adiciona um evento para recarregar a tabela quando ocorrer um erro
                    $('#datatable-cashback').on('error.dt', function(e, settings, techNote, message) {
                    });
                    
                    // Remover campo de busca gerado automaticamente
                    $('.dataTables_filter').remove();
                }
            });
            
            return table;
        }

        // Inicializar o datatable com os valores dos parâmetros da URL
        var tipoFiltro = "{{ $tipoFiltro }}";
        var statusAtivo = "{{ $statusAtivo }}";
        var nivelVIP = "{{ $nivelVIP }}";
        var table = initDatatable(tipoFiltro, statusAtivo, nivelVIP);
        
        // Inicializar o sistema de modal e toast
        if (typeof ModalManager !== 'undefined' && typeof ModalManager.init === 'function') {
            ModalManager.init();
        } else {
            console.warn('ModalManager não encontrado, usando fallback');
            
            // Se o ModalManager não estiver disponível, usar fallback com SweetAlert
            window.ModalManager = {
                showConfirmation: function(title, message, onConfirm, onCancel) {
                    Swal.fire({
                        title: title || 'Confirmar Ação',
                        text: message || 'Tem certeza que deseja realizar esta ação?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#4361ee'
                    }).then((result) => {
                        if (result.isConfirmed && typeof onConfirm === 'function') {
                            onConfirm();
                        } else if (typeof onCancel === 'function') {
                            onCancel();
                        }
                    });
                }
            };
        }
        
        // Função para recarregar a tabela com os novos filtros
        function reloadTable() {
            var tipoFiltro = $('#tipoFiltro').val();
            var statusAtivo = $('#statusAtivo').val();
            var nivelVIP = $('#nivelVIP').val();
            
            try {
                // Destruir o datatable anterior com segurança
                if ($.fn.DataTable.isDataTable('#datatable-cashback')) {
                    $('#datatable-cashback').DataTable().destroy();
                }
                
                // Reinicializar com os novos parâmetros
                table = initDatatable(tipoFiltro, statusAtivo, nivelVIP);
                
                // Atualizar a URL para refletir os novos filtros sem recarregar a página
                var newUrl = "{{ route('admin.cashback.index') }}?type=" + tipoFiltro + "&active=" + statusAtivo + "&vip=" + nivelVIP;
                window.history.pushState({}, '', newUrl);
            } catch (error) {
                console.error('Erro ao recarregar tabela:', error);
                ToastManager.error('Ocorreu um erro ao atualizar a tabela. Tente recarregar a página.');
            }
        }
        
        // Evento para campos de filtro - aplicar filtro imediatamente ao mudar
        $('.filter-input').on('change', function() {
            reloadTable();
        });
        
        // Gerenciamento de nível VIP (global vs específico)
        $('#is_global').on('change', function() {
            if ($(this).is(':checked')) {
                $('#vip_level').prop('disabled', true);
                $('#vip_level').val('');
            } else {
                $('#vip_level').prop('disabled', false);
            }
        });
        
        // Controle de exibição das opções de agendamento global
        $('#schedule_active').on('change', function() {
            if ($(this).is(':checked')) {
                $('#schedule-options').slideDown();
            } else {
                $('#schedule-options').slideUp();
            }
        });
        
        // Controle de exibição de campos baseados na frequência
        $('#scheduled_frequency').on('change', function() {
            const value = $(this).val();
            
            if (value === 'weekly') {
                $('#weekday-container').slideDown();
            } else {
                $('#weekday-container').slideUp();
            }
        });
        
        // Controle para usuário específico - opções baseadas na frequência
        $('#schedule_frequency').on('change', function() {
            const value = $(this).val();
            
            // Atualizar campos conforme a frequência selecionada
            if (value === 'once') {
                $('#user_scheduled_date').prop('disabled', false);
            } else if (value === 'biweekly') {
                // Para quinzenal, mostrar mensagem explicativa
                if (!$('#biweekly-info').length) {
                    $('#schedule_frequency').after('<small id="biweekly-info" class="text-info d-block mt-1">Processamento nos dias 1 e 16 de cada mês</small>');
                }
            } else if (value === 'monthly') {
                // Para mensal, mostrar mensagem explicativa
                if (!$('#monthly-info').length) {
                    $('#schedule_frequency').after('<small id="monthly-info" class="text-info d-block mt-1">Processamento no dia 1 de cada mês</small>');
                }
            } else {
                // Remover mensagens explicativas para outras frequências
                $('#biweekly-info, #monthly-info').remove();
            }
        });
        
        // Controle de exibição das opções de agendamento para usuário específico
        $('#user_schedule_active').on('change', function() {
            if ($(this).is(':checked')) {
                $('#user-schedule-options').slideDown();
            } else {
                $('#user-schedule-options').slideUp();
            }
        });
        
        // Inicialização - mostrar campos conforme estado inicial
        if ($('#schedule_active').is(':checked')) {
            $('#schedule-options').show();
            
            if ($('#scheduled_frequency').val() === 'weekly') {
                $('#weekday-container').show();
            }
        }
        
        // Inicializar com a frequência selecionada para usuário específico
        $('#schedule_frequency').trigger('change');
        
        // Resetar formulário quando o modal é fechado
        $('#createCashbackModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $('#vip_level').prop('disabled', true);
            $('#schedule-options').hide();
            $('#weekday-container').hide();
        });
        
        // Controle do modal de cashback específico para usuário
        $('#userSpecificCashbackModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            userSelect.clear();
        });
        
        // Configurar data e hora atual para o agendamento
        $('#userSpecificCashbackModal').on('shown.bs.modal', function() {
            const now = new Date();
            const today = now.toISOString().substring(0, 10);
            const time = now.getHours().toString().padStart(2, '0') + ':' + 
                       (now.getMinutes() + 5).toString().padStart(2, '0');
            
            $('#user_scheduled_date').val(today);
            $('#user_scheduled_time').val(time);
        });
        
        // Exibir mensagens de erro ou sucesso do backend
        @if(session('error'))
            // Verificar se já foi mostrado um toast recentemente (via AJAX)
            if (!window.lastToastTime || (Date.now() - window.lastToastTime > 2000)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#4361ee'
                });
            }
        @endif
        
        @if(session('success'))
            // Verificar se já foi mostrado um toast recentemente (via AJAX)
            if (!window.lastToastTime || (Date.now() - window.lastToastTime > 2000)) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#4361ee'
                });
            }
        @endif

        // Configurar evento de exclusão
        $(document).on('click', '.delete-cashback-btn', function() {
            const cashbackId = $(this).data('id');
            const cashbackName = $(this).data('name');
            
            // Usar o ModalManager para confirmar a exclusão
            ModalManager.showConfirmation(
                'Confirmar Exclusão',
                `Tem certeza que deseja excluir a configuração de cashback "${cashbackName}"? Esta ação não pode ser desfeita e será executada mesmo se houver cashbacks de usuários vinculados a esta configuração.`,
                function() {
                    // Callback de confirmação - Prosseguir com a exclusão
                    // Mostrar toast de processamento
                    const processingToast = ToastManager.info('Processando, aguarde...');
                    
                    $.ajax({
                        url: `/admin/cashback/${cashbackId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Remover toast de processamento
                            processingToast.remove();
                            
                            if (response.success) {
                                // Recarregar datatable com cuidado para evitar reinicialização do TomSelect
                                try {
                                    // Definir flag para controlar que o toast já foi mostrado
                                    window.lastToastTime = Date.now();
                                    
                                    // Atualizar apenas a tabela sem reinicializar o TomSelect
                                    $('#datatable-cashback').DataTable().ajax.reload(null, false);
                                    
                                    // Mostrar apenas um toast de sucesso
                                    ToastManager.success(response.message || 'Configuração excluída com sucesso!');
                                } catch (error) {
                                    console.error('Erro ao recarregar tabela:', error);
                                    // Se falhar, mostrar mensagem sem recarregar a tabela
                                    ToastManager.success(response.message || 'Configuração excluída com sucesso! Recarregue a página para ver as alterações.');
                                }
                            } else {
                                // Mostrar mensagem de erro
                                ToastManager.error(response.message || 'Erro ao excluir configuração.');
                            }
                        },
                        error: function(xhr) {
                            // Remover toast de processamento
                            processingToast.remove();
                            
                            // Mostrar mensagem de erro baseada na resposta do servidor
                            if (xhr.status === 404) {
                                ToastManager.error('Configuração não encontrada. Ela pode já ter sido excluída.');
                            } else {
                                ToastManager.error(xhr.responseJSON?.message || 'Ocorreu um erro ao excluir a configuração. Por favor, tente novamente.');
                            }
                            
                            console.error('Erro na exclusão:', xhr);
                        }
                    });
                }
            );
        });
        
        // Configurar evento de edição
        $(document).on('click', '.btn-edit-cashback', function(e) {
            e.preventDefault();
            const cashbackId = $(this).data('id');
            
            // Limpar formulário
            $('#editCashbackForm')[0].reset();
            
            // Carregar dados da configuração
            $.ajax({
                url: `/admin/cashback/${cashbackId}/json`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Preencher formulário com dados
                    $('#edit_name').val(data.name);
                    $('#edit_type').val(data.type);
                    $('#edit_percentage').val(data.percentage);
                    $('#edit_min_loss').val(data.min_loss);
                    $('#edit_max_cashback').val(data.max_cashback);
                    $('#edit_expiry_days').val(data.expiry_days);
                    
                    // Checkboxes
                    $('#edit_auto_apply').prop('checked', data.auto_apply == 1);
                    $('#edit_is_global').prop('checked', data.is_global == 1);
                    $('#edit_schedule_active').prop('checked', data.schedule_active == 1);
                    
                    // Controlar exibição dos campos dependentes
                    if (data.is_global == 1) {
                        $('#edit_vip_level').val('').prop('disabled', true);
                    } else {
                        $('#edit_vip_level').val(data.vip_level).prop('disabled', false);
                    }
                    
                    // Configurar campos de agendamento
                    if (data.schedule_active == 1) {
                        $('#edit-schedule-options').show();
                        $('#edit_scheduled_frequency').val(data.scheduled_frequency);
                        $('#edit_scheduled_hour').val(data.scheduled_hour);
                        $('#edit_scheduled_minute').val(data.scheduled_minute);
                        
                        if (data.scheduled_frequency === 'weekly') {
                            $('#edit-weekday-container').show();
                            $('#edit_scheduled_day').val(data.scheduled_day);
                        } else {
                            $('#edit-weekday-container').hide();
                        }
                    } else {
                        $('#edit-schedule-options').hide();
                    }
                    
                    // Configurar URL de envio do formulário
                    $('#editCashbackForm').attr('action', `/admin/cashback/${cashbackId}`);
                    
                    // Abrir modal
                    $('#editCashbackModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Não foi possível carregar os dados da configuração. Por favor, tente novamente.',
                        confirmButtonColor: '#4361ee'
                    });
                    
                    console.error('Erro ao carregar dados:', xhr);
                }
            });
        });
        
        // Controle de exibição de campos no modal de edição
        $('#edit_is_global').on('change', function() {
            if ($(this).is(':checked')) {
                $('#edit_vip_level').val('').prop('disabled', true);
            } else {
                $('#edit_vip_level').prop('disabled', false);
            }
        });
        
        $('#edit_schedule_active').on('change', function() {
            if ($(this).is(':checked')) {
                $('#edit-schedule-options').slideDown();
            } else {
                $('#edit-schedule-options').slideUp();
            }
        });
        
        $('#edit_scheduled_frequency').on('change', function() {
            const value = $(this).val();
            
            if (value === 'weekly') {
                $('#edit-weekday-container').slideDown();
                // Remover mensagens explicativas
                $('#edit-biweekly-info, #edit-monthly-info').remove();
            } else {
                $('#edit-weekday-container').slideUp();
                
                // Adicionar mensagens explicativas para frequências específicas
                if (value === 'biweekly') {
                    // Para quinzenal, mostrar mensagem explicativa
                    if (!$('#edit-biweekly-info').length) {
                        $('#edit_scheduled_frequency').after('<small id="edit-biweekly-info" class="text-info d-block mt-1">Processamento nos dias 1 e 16 de cada mês</small>');
                    }
                    $('#edit-monthly-info').remove();
                } else if (value === 'monthly') {
                    // Para mensal, mostrar mensagem explicativa
                    if (!$('#edit-monthly-info').length) {
                        $('#edit_scheduled_frequency').after('<small id="edit-monthly-info" class="text-info d-block mt-1">Processamento no dia 1 de cada mês</small>');
                    }
                    $('#edit-biweekly-info').remove();
                } else {
                    // Remover mensagens explicativas para outras frequências
                    $('#edit-biweekly-info, #edit-monthly-info').remove();
                }
            }
        });
    });
</script>
@endpush

@endsection 