@extends('admin.layouts.app')
@section('content')
@php
    use Carbon\Carbon;
    
    $s = request()->input('status', '');
    $t = request()->input('type', '');
    $u = request()->input('user_id', '');
    $v = request()->input('vip_level', '');
    $g = request()->input('is_global', '');
    
    $statusFiltro = $s;
    $tipoFiltro = $t;
    $usuarioFiltro = $u;
    $nivelVipFiltro = $v;
    $globalFiltro = $g;
@endphp
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Cashback</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Cashbacks Manual</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8" style="padding:20px;">
                    <div class="row mb-4">
                        <div class="col-md-12 text-end">
                            <a href="#" class="btn btn-primary mb-2 me-2" data-bs-toggle="modal" data-bs-target="#addManualCashbackModal">
                                <i class="fas fa-plus me-2"></i>Adicionar Cashback
                            </a>
                            <a href="#" class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#processAllModal">
                                <i class="fas fa-sync-alt me-2"></i>Processar Cashbacks
                            </a>
                        </div>
                    </div>

                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <div class="row" style="margin-bottom: -20px; padding:15px;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="statusFiltro" class="form-label">Status:</label>
                                        <select id="statusFiltro" name="status" class="form-control filter-input">
                                            <option value="" {{ $s == '' ? 'selected' : '' }}>Todos</option>
                                            <option value="pending" {{ $s == 'pending' ? 'selected' : '' }}>Pendente</option>
                                            <option value="credited" {{ $s == 'credited' ? 'selected' : '' }}>Creditado</option>
                                            <option value="expired" {{ $s == 'expired' ? 'selected' : '' }}>Expirado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tipoFiltro" class="form-label">Tipo:</label>
                                        <select id="tipoFiltro" name="type" class="form-control filter-input">
                                            <option value="" {{ $t == '' ? 'selected' : '' }}>Todos</option>
                                            <option value="all" {{ $t == 'all' ? 'selected' : '' }}>Todos os Jogos</option>
                                            <option value="sports" {{ $t == 'sports' ? 'selected' : '' }}>Apostas Esportivas</option>
                                            <option value="virtual" {{ $t == 'virtual' ? 'selected' : '' }}>Cassino</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nivelVipFiltro" class="form-label">Nível VIP:</label>
                                        <select id="nivelVipFiltro" name="vip_level" class="form-control filter-input">
                                            <option value="" {{ $v == '' ? 'selected' : '' }}>Todos</option>
                                            <option value="global" {{ $v == 'global' ? 'selected' : '' }}>Global</option>
                                            <option value="1" {{ $v == '1' ? 'selected' : '' }}>Bronze</option>
                                            <option value="2" {{ $v == '2' ? 'selected' : '' }}>Prata</option>
                                            <option value="3" {{ $v == '3' ? 'selected' : '' }}>Ouro</option>
                                            <option value="4" {{ $v == '4' ? 'selected' : '' }}>Diamante</option>
                                            <option value="5" {{ $v == '5' ? 'selected' : '' }}>Platina</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="usuarioFiltro" class="form-label">Usuário:</label>
                                        <input type="text" id="usuarioFiltro" name="user_id" placeholder="Nome, CPF, email ou ID..." value="{{$u}}" class="form-control filter-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="datatable-cashbacks-user" class="table table-striped dt-table-hover dataTable" style="width:100%" role="grid" aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashbacks-user" rowspan="1" colspan="1">Usuário</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashbacks-user" rowspan="1" colspan="1">Nível VIP</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashbacks-user" rowspan="1" colspan="1">Perdas</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashbacks-user" rowspan="1" colspan="1">Valor Cashback</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashbacks-user" rowspan="1" colspan="1">Percentual</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashbacks-user" rowspan="1" colspan="1">Tipo</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashbacks-user" rowspan="1" colspan="1">Status</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashbacks-user" rowspan="1" colspan="1">Data Expiração</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-cashbacks-user" rowspan="1" colspan="1">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Os dados serão preenchidos pelo DataTable -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">Usuário</th>
                                        <th rowspan="1" colspan="1">Nível VIP</th>
                                        <th rowspan="1" colspan="1">Perdas</th>
                                        <th rowspan="1" colspan="1">Valor Cashback</th>
                                        <th rowspan="1" colspan="1">Percentual</th>
                                        <th rowspan="1" colspan="1">Tipo</th>
                                        <th rowspan="1" colspan="1">Status</th>
                                        <th rowspan="1" colspan="1">Data Expiração</th>
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

<!-- Modal para Processar Todos os Cashbacks -->
<div class="modal fade" id="processAllModal" tabindex="-1" aria-labelledby="processAllModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="processAllModalLabel">Processar Cashbacks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <form action="{{ route('admin.cashback.process') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Selecione o tipo de cashback que deseja processar:</p>
                    <div class="mb-3">
                        <select name="type" class="form-control">
                            <option value="all">Todos os Jogos</option>
                            <option value="sports">Apostas Esportivas</option>
                            <option value="virtual">Cassino</option>
                        </select>
                    </div>
                    <p class="text-warning">Isso processará os cashbacks para todos os usuários com base nas perdas da semana anterior. Deseja continuar?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Processar Agora</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Aplicar Cashback -->
<div class="modal fade" id="applyCashbackModal" tabindex="-1" aria-labelledby="applyCashbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applyCashbackModalLabel">Aplicar Cashback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <form id="applyCashbackForm" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Você está aplicando o cashback para o usuário <strong id="cashbackUserName"></strong>.</p>
                    <p>Valor: <strong id="cashbackAmount"></strong></p>
                    <p>Deseja realmente creditar este valor na conta do usuário?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Aplicar Cashback</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Excluir Cashback -->
<div class="modal fade" id="deleteCashbackModal" tabindex="-1" aria-labelledby="deleteCashbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCashbackModalLabel">Excluir Cashback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <form id="deleteCashbackForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Você está prestes a excluir o cashback para o usuário <strong id="deleteCashbackUserName"></strong>.</p>
                    <p>Valor: <strong id="deleteCashbackAmount"></strong></p>
                    <p>Esta ação não pode ser desfeita. Deseja continuar?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Excluir Cashback</button>
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

<!-- Modal para Adicionar Cashback Manual -->
<div class="modal fade" id="addManualCashbackModal" tabindex="-1" aria-labelledby="addManualCashbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addManualCashbackModalLabel">Adicionar Cashback Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <form action="{{ route('admin.cashback.add.manual') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="userSearch" class="form-label">Buscar Usuário:</label>
                        <input type="text" class="form-control" id="userSearch" placeholder="Nome, CPF, email ou ID...">
                        <div id="userSearchResults" class="mt-2"></div>
                        <input type="hidden" name="user_id" id="selectedUserId">
                    </div>
                    <div class="mb-3">
                        <label for="selectedUserInfo" class="form-label">Usuário Selecionado:</label>
                        <div id="selectedUserInfo" class="p-2 border rounded bg-light">
                            <p class="mb-0">Nenhum usuário selecionado</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="cashbackType" class="form-label">Tipo de Cashback:</label>
                        <select name="type" id="cashbackType" class="form-control" required>
                            <option value="all">Todos os Jogos</option>
                            <option value="sports">Apostas Esportivas</option>
                            <option value="virtual">Cassino</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cashbackPercentage" class="form-label">Percentual do Cashback (%):</label>
                        <input type="number" class="form-control" id="cashbackPercentage" name="percentage" min="0.1" max="100" step="0.1" required>
                    </div>
                    <div class="mb-3">
                        <label for="cashbackAmount" class="form-label">Valor do Cashback (R$):</label>
                        <input type="number" class="form-control" id="cashbackAmount" name="amount" min="0.01" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="observation" class="form-label">Observação:</label>
                        <textarea class="form-control" id="observation" name="observation" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnAddCashback" disabled>Adicionar Cashback</button>
                </div>
            </form>
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
        
        // Variável para armazenar o timeout da digitação
        var typingTimer;
        var doneTypingInterval = 500; // Tempo em ms para aguardar após a digitação

        // Função para inicializar o datatable com as opções desejadas
        function initDatatable(statusFiltro, tipoFiltro, usuarioFiltro, nivelVipFiltro, globalFiltro) {
            var table = $('#datatable-cashbacks-user').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.cashback.users.data') }}",
                    data: function (d) {
                        d.statusFiltro = statusFiltro || $('#statusFiltro').val();
                        d.tipoFiltro = tipoFiltro || $('#tipoFiltro').val();
                        d.usuarioFiltro = usuarioFiltro || $('#usuarioFiltro').val();
                        d.nivelVipFiltro = nivelVipFiltro || $('#nivelVipFiltro').val();
                        d.globalFiltro = globalFiltro;
                    },
                    error: function (xhr, error, thrown) {
                    }
                },
                columns: [
                    {data: 'user', name: 'user'},
                    {data: 'vip_level', name: 'vip_level'},
                    {data: 'total_loss', name: 'total_loss'},
                    {data: 'cashback_amount', name: 'cashback_amount'},
                    {data: 'percentage_applied', name: 'percentage_applied'},
                    {data: 'type', name: 'type'},
                    {data: 'status', name: 'status'},
                    {data: 'expires_at', name: 'expires_at'},
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
                    $('#datatable-cashbacks-user_paginate').addClass('paging_simple_numbers');
                    $('#datatable-cashbacks-user_paginate ul.pagination li').addClass('paginate_button page-item');
                    $('#datatable-cashbacks-user_paginate ul.pagination li.previous').attr('id', 'datatable-cashbacks-user_previous');
                    $('#datatable-cashbacks-user_paginate ul.pagination li.next').attr('id', 'datatable-cashbacks-user_next');
                    $('#datatable-cashbacks-user_paginate ul.pagination li.first').attr('id', 'datatable-cashbacks-user_first');
                    $('#datatable-cashbacks-user_paginate ul.pagination li.last').attr('id', 'datatable-cashbacks-user_last');
                    $('#datatable-cashbacks-user_paginate ul.pagination li a').addClass('page-link');
                    
                    // Substituir o texto dos botões de paginação por ícones SVG
                    $('#datatable-cashbacks-user_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                    $('#datatable-cashbacks-user_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                    $('#datatable-cashbacks-user_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                    $('#datatable-cashbacks-user_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');
                    
                    // Inicializar botões de aplicar cashback
                    initApplyCashbackButtons();
                },
                initComplete: function() {
                    // Adiciona um evento para recarregar a tabela quando ocorrer um erro
                    $('#datatable-cashbacks-user').on('error.dt', function(e, settings, techNote, message) {
                    });
                    
                    // Remover campo de busca gerado automaticamente
                    $('.dataTables_filter').remove();
                }
            });
            
            return table;
        }

        // Inicializar o datatable com os valores dos parâmetros da URL
        var statusFiltro = "{{ $statusFiltro }}";
        var tipoFiltro = "{{ $tipoFiltro }}";
        var usuarioFiltro = "{{ $usuarioFiltro }}";
        var nivelVipFiltro = "{{ $nivelVipFiltro }}";
        var globalFiltro = "{{ $globalFiltro }}";
        var table = initDatatable(statusFiltro, tipoFiltro, usuarioFiltro, nivelVipFiltro, globalFiltro);
        
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
            var statusFiltro = $('#statusFiltro').val();
            var tipoFiltro = $('#tipoFiltro').val();
            var usuarioFiltro = $('#usuarioFiltro').val();
            var nivelVipFiltro = $('#nivelVipFiltro').val();
            var globalFiltro = $('#globalFiltro').val();
            
            // Destruir o datatable anterior
            $('#datatable-cashbacks-user').DataTable().destroy();
            
            // Reinicializar com os novos parâmetros
            table = initDatatable(statusFiltro, tipoFiltro, usuarioFiltro, nivelVipFiltro, globalFiltro);
            
            // Atualizar a URL para refletir os novos filtros sem recarregar a página
            var newUrl = "{{ route('admin.cashback.users') }}?status=" + statusFiltro + "&type=" + tipoFiltro + "&user_id=" + usuarioFiltro + "&vip_level=" + nivelVipFiltro + "&is_global=" + globalFiltro;
            window.history.pushState({}, '', newUrl);
        }
        
        // Evento para campos de filtro - aplicar filtro imediatamente ao mudar
        $('.filter-input[type="select"]').on('change', function() {
            reloadTable();
        });
        
        // Evento para campo de texto - aplicar filtro após parar de digitar
        $('#usuarioFiltro').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(reloadTable, doneTypingInterval);
        });
        
        $('#usuarioFiltro').on('keydown', function() {
            clearTimeout(typingTimer);
        });
        
        // Função para inicializar botões de aplicar cashback
        function initApplyCashbackButtons() {
            $('.btn-apply-cashback').on('click', function() {
                var cashbackId = $(this).data('id');
                var userName = $(this).data('user');
                var amount = $(this).data('amount');
                
                // Usar o ModalManager para confirmar a aplicação
                ModalManager.showConfirmation(
                    'Aplicar Cashback',
                    `Você está aplicando o cashback para o usuário ${userName}. Valor: R$ ${amount}. Deseja realmente creditar este valor na conta do usuário?`,
                    function() {
                        // Callback de confirmação - Prosseguir com a aplicação
                        // Mostrar toast de processamento
                        const processingToast = ToastManager.info('Processando, aguarde...');
                        
                        // Enviar o formulário via AJAX
                        $.ajax({
                            url: "{{ route('admin.cashback.apply', ':id') }}".replace(':id', cashbackId),
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // Remover toast de processamento
                                processingToast.remove();
                                
                                if (response.success) {
                                    // Recarregar datatable
                                    $('#datatable-cashbacks-user').DataTable().ajax.reload();
                                    
                                    // Mostrar mensagem de sucesso
                                    ToastManager.success(response.message || 'Cashback aplicado com sucesso!');
                                } else {
                                    // Mostrar mensagem de erro
                                    ToastManager.error(response.message || 'Erro ao aplicar cashback.');
                                }
                            },
                            error: function(xhr) {
                                // Remover toast de processamento
                                processingToast.remove();
                                
                                // Mostrar mensagem de erro
                                ToastManager.error('Ocorreu um erro ao aplicar o cashback. Por favor, tente novamente.');
                                
                                console.error('Erro na aplicação:', xhr);
                            }
                        });
                    }
                );
            });
            
            // Inicializar botões de exclusão de cashback
            $('.btn-delete-cashback').on('click', function() {
                var cashbackId = $(this).data('id');
                var userName = $(this).data('user');
                var amount = $(this).data('amount');
                
                // Usar o ModalManager para confirmar a exclusão
                ModalManager.showConfirmation(
                    'Excluir Cashback',
                    `Você está prestes a excluir o cashback para o usuário ${userName}. Valor: R$ ${amount}. Esta ação não pode ser desfeita. Deseja continuar?`,
                    function() {
                        // Callback de confirmação - Prosseguir com a exclusão
                        // Mostrar toast de processamento
                        const processingToast = ToastManager.info('Processando, aguarde...');
                        
                        // Enviar o formulário via AJAX
                        $.ajax({
                            url: "{{ route('admin.cashback.delete', ':id') }}".replace(':id', cashbackId),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // Remover toast de processamento
                                processingToast.remove();
                                
                                if (response.success) {
                                    // Recarregar datatable
                                    $('#datatable-cashbacks-user').DataTable().ajax.reload();
                                    
                                    // Mostrar mensagem de sucesso
                                    ToastManager.success(response.message || 'Cashback excluído com sucesso!');
                                } else {
                                    // Mostrar mensagem de erro
                                    ToastManager.error(response.message || 'Erro ao excluir cashback.');
                                }
                            },
                            error: function(xhr) {
                                // Remover toast de processamento
                                processingToast.remove();
                                
                                // Mostrar mensagem de erro
                                ToastManager.error('Ocorreu um erro ao excluir o cashback. Por favor, tente novamente.');
                                
                                console.error('Erro na exclusão:', xhr);
                            }
                        });
                    }
                );
            });
        }

        // Busca de usuário para adicionar cashback manual
        $('#userSearch').on('keyup', function() {
            var search = $(this).val();
            if (search.length >= 3) {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    // Mostrar toast de carregamento
                    const searchToast = ToastManager.info('Buscando usuários...');
                    
                    $.ajax({
                        url: "/admin/usuarios/search",
                        type: "GET",
                        data: { search: search },
                        dataType: "json",
                        success: function(response) {
                            // Remover toast de carregamento
                            searchToast.remove();
                            
                            var html = '';
                            if (response.users.length > 0) {
                                html += '<div class="list-group">';
                                $.each(response.users, function(index, user) {
                                    html += '<a href="#" class="list-group-item list-group-item-action select-user" data-id="' + user.id + '" data-name="' + user.name + '" data-email="' + user.email + '" data-cpf="' + (user.cpf || 'N/A') + '">';
                                    html += '<div class="d-flex w-100 justify-content-between">';
                                    html += '<h6 class="mb-1">' + user.name + '</h6>';
                                    html += '<small>ID: ' + user.id + '</small>';
                                    html += '</div>';
                                    html += '<p class="mb-1">' + user.email + '</p>';
                                    if (user.cpf) {
                                        html += '<small>CPF: ' + user.cpf + '</small>';
                                    }
                                    html += '</a>';
                                });
                                html += '</div>';
                            } else {
                                html = '<div class="alert alert-warning">Nenhum usuário encontrado</div>';
                                // Mostrar toast informativo
                                ToastManager.info('Nenhum usuário encontrado com este termo de busca.');
                            }
                            $('#userSearchResults').html(html);
                            initSelectUserButtons();
                        },
                        error: function(xhr, status, error) {
                            // Remover toast de carregamento
                            searchToast.remove();
                            
                            $('#userSearchResults').html('<div class="alert alert-danger">Erro ao buscar usuários</div>');
                            // Mostrar toast de erro
                            ToastManager.error('Erro ao buscar usuários. Por favor, tente novamente.');
                            
                            console.error('Erro na busca:', error);
                        }
                    });
                }, doneTypingInterval);
            } else {
                $('#userSearchResults').html('');
            }
        });

        function initSelectUserButtons() {
            $('.select-user').on('click', function(e) {
                e.preventDefault();
                var userId = $(this).data('id');
                var userName = $(this).data('name');
                var userEmail = $(this).data('email');
                var userCpf = $(this).data('cpf');
                
                $('#selectedUserId').val(userId);
                
                var userInfo = '<p class="mb-0"><strong>Nome:</strong> ' + userName + '</p>';
                userInfo += '<p class="mb-0"><strong>Email:</strong> ' + userEmail + '</p>';
                userInfo += '<p class="mb-0"><strong>CPF:</strong> ' + userCpf + '</p>';
                userInfo += '<p class="mb-0"><strong>ID:</strong> ' + userId + '</p>';
                
                $('#selectedUserInfo').html(userInfo);
                $('#userSearchResults').html('');
                $('#btnAddCashback').prop('disabled', false);
                
                // Mostrar toast de confirmação
                ToastManager.success('Usuário ' + userName + ' selecionado com sucesso!');
            });
        }

        // Manipular formulário de processamento de todos os cashbacks
        $('#processAllModal form').on('submit', function(e) {
            e.preventDefault();
            
            // Fechar o modal
            $('#processAllModal').modal('hide');
            
            // Mostrar toast de processamento
            const processingToast = ToastManager.info('Processando cashbacks, aguarde...');
            
            // Enviar requisição AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Remover toast de processamento
                    processingToast.remove();
                    
                    if (response.success) {
                        // Recarregar datatable
                        $('#datatable-cashbacks-user').DataTable().ajax.reload();
                        
                        // Mostrar mensagem de sucesso
                        ToastManager.success(response.message || 'Cashbacks processados com sucesso!');
                    } else {
                        // Mostrar mensagem de erro
                        ToastManager.error(response.message || 'Erro ao processar cashbacks.');
                    }
                },
                error: function(xhr) {
                    // Remover toast de processamento
                    processingToast.remove();
                    
                    // Mostrar mensagem de erro
                    ToastManager.error('Ocorreu um erro ao processar os cashbacks. Por favor, tente novamente.');
                    
                    console.error('Erro no processamento:', xhr);
                }
            });
        });
        
        // Inicializar modal de detalhes de perdas
        $(document).on('click', '.btn-view-losses', function(e) {
            e.preventDefault();
            var userId = $(this).data('user-id');
            
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

        // Adicionar handler para o formulário de adicionar cashback manual
        $('#addManualCashbackModal form').on('submit', function(e) {
            e.preventDefault();
            
            // Verificar se um usuário foi selecionado
            if (!$('#selectedUserId').val()) {
                ToastManager.error('Por favor, selecione um usuário antes de adicionar um cashback.');
                return;
            }
            
            // Fechar o modal
            $('#addManualCashbackModal').modal('hide');
            
            // Mostrar toast de processamento
            const processingToast = ToastManager.info('Adicionando cashback, aguarde...');
            
            // Enviar requisição AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Remover toast de processamento
                    processingToast.remove();
                    
                    if (response.success) {
                        // Recarregar datatable
                        $('#datatable-cashbacks-user').DataTable().ajax.reload();
                        
                        // Mostrar mensagem de sucesso
                        ToastManager.success(response.message || 'Cashback adicionado com sucesso!');
                        
                        // Limpar formulário
                        $('#addManualCashbackModal form')[0].reset();
                        $('#selectedUserInfo').html('<p class="mb-0">Nenhum usuário selecionado</p>');
                        $('#selectedUserId').val('');
                        $('#btnAddCashback').prop('disabled', true);
                    } else {
                        // Mostrar mensagem de erro
                        ToastManager.error(response.message || 'Erro ao adicionar cashback.');
                    }
                },
                error: function(xhr) {
                    // Remover toast de processamento
                    processingToast.remove();
                    
                    // Mostrar mensagem de erro
                    ToastManager.error('Ocorreu um erro ao adicionar o cashback. Por favor, tente novamente.');
                    
                    console.error('Erro ao adicionar cashback:', xhr);
                }
            });
        });
        
        // Exibir mensagens de erro ou sucesso do backend com Toast
        @if(session('error'))
            ToastManager.error('{{ session('error') }}');
        @endif
        
        @if(session('success'))
            ToastManager.success('{{ session('success') }}');
        @endif
    });
</script>
@endpush

@endsection 