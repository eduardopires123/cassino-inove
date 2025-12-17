@extends('admin.layouts.app')
@section('content')
@php
    use Carbon\Carbon;

    $search = request()->input('search', '');
    $type = request()->input('type', '');
    $start_date = request()->input('start_date', '');
    $end_date = request()->input('end_date', '');
@endphp

<div class="layout-px-spacing">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.lucky-boxes.index') }}">Caixas da Sorte</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Resgates - {{ $box->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="row" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-top-spacing layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div id="invoice-list_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <div class="row" style="margin-bottom: -20px; padding:15px;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="start_date" class="form-label">Data Inicial:</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control filter-input" value="{{ $start_date }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="end_date" class="form-label">Data Final:</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control filter-input" value="{{ $end_date }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="search" class="form-label">Buscar por Usuário:</label>
                                        <input type="text" id="search" name="search" placeholder="Buscar por usuário..." value="{{ $search }}" class="form-control filter-input">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="type" class="form-label">Tipo de Prêmio:</label>
                                        <select class="form-control filter-input" id="type" name="type">
                                            <option value="">Todos</option>
                                            @foreach($prizeTypes as $key => $value)
                                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <a href="{{ route('admin.lucky-boxes.index') }}" class="btn btn-secondary w-100">
                                            <i class="fas fa-arrow-left"></i> 
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable-redemptions" class="table table-striped dt-table-hover dataTable" style="width:100%" role="grid" aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="datatable-redemptions" rowspan="1" colspan="1">ID</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-redemptions" rowspan="1" colspan="1">Usuário</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-redemptions" rowspan="1" colspan="1">Tipo de Prêmio</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-redemptions" rowspan="1" colspan="1">Valor Anterior</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-redemptions" rowspan="1" colspan="1">Valor Atual</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-redemptions" rowspan="1" colspan="1">Valor Prêmio</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-redemptions" rowspan="1" colspan="1">Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Os dados serão preenchidos pelo DataTable -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">ID</th>
                                        <th rowspan="1" colspan="1">Usuário</th>
                                        <th rowspan="1" colspan="1">Tipo de Prêmio</th>
                                        <th rowspan="1" colspan="1">Valor Anterior</th>
                                        <th rowspan="1" colspan="1">Valor Atual</th>
                                        <th rowspan="1" colspan="1">Valor Prêmio</th>
                                        <th rowspan="1" colspan="1">Data</th>
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

<style>
    body.dark .form-check-input:checked {
        background-color: #4361ee;
        border-color: #4361ee;
    }
</style>

@push('scripts')
<script>
    $(function() {
        // Configuração para suprimir mensagens de erro do DataTables em console
        $.fn.dataTable.ext.errMode = 'none';
        
        // Variável para armazenar o timeout da digitação
        var typingTimer;
        var doneTypingInterval = 500; // Tempo em ms para aguardar após a digitação

        // Função para inicializar o datatable com as opções desejadas
        function initDatatable(start_date, end_date, search, type) {
            var table = $('#datatable-redemptions').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.lucky-boxes.redemptions.data', $box->id) }}",
                    data: function (d) {
                        d.start_date = start_date;
                        d.end_date = end_date;
                        d.search = search;
                        d.type = type;
                    },
                    error: function (xhr, error, thrown) {
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'usuario', name: 'usuario'},
                    {data: 'tipo_premio', name: 'tipo_premio'},
                    {data: 'old_value', name: 'old_value'},
                    {data: 'new_value', name: 'new_value'},
                    {data: 'valor_premio', name: 'valor_premio'},
                    {data: 'data', name: 'data'}
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
                order: [[0, 'desc']],
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
                    $('#datatable-redemptions_paginate').addClass('paging_simple_numbers');
                    $('#datatable-redemptions_paginate ul.pagination li').addClass('paginate_button page-item');
                    $('#datatable-redemptions_paginate ul.pagination li.previous').attr('id', 'datatable-redemptions_previous');
                    $('#datatable-redemptions_paginate ul.pagination li.next').attr('id', 'datatable-redemptions_next');
                    $('#datatable-redemptions_paginate ul.pagination li.first').attr('id', 'datatable-redemptions_first');
                    $('#datatable-redemptions_paginate ul.pagination li.last').attr('id', 'datatable-redemptions_last');
                    $('#datatable-redemptions_paginate ul.pagination li a').addClass('page-link');
                    
                    // Substituir o texto dos botões de paginação por ícones SVG
                    $('#datatable-redemptions_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                    $('#datatable-redemptions_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                    $('#datatable-redemptions_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                    $('#datatable-redemptions_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');
                    
                    // Inicializar os tooltips do Bootstrap
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                },
                initComplete: function() {
                    // Adiciona um evento para recarregar a tabela quando ocorrer um erro
                    $('#datatable-redemptions').on('error.dt', function(e, settings, techNote, message) {
                    });
                    
                    // Remover campo de busca gerado automaticamente
                    $('.dataTables_filter').remove();
                }
            });
            
            return table;
        }

        // Inicializar o datatable com os valores dos parâmetros
        var start_date = "{{ $start_date }}";
        var end_date = "{{ $end_date }}";
        var search = "{{ $search }}";
        var type = "{{ $type }}";
        var table = initDatatable(start_date, end_date, search, type);
        
        // Função para recarregar a tabela com os novos filtros
        function reloadTable() {
            var start_date = $('#start_date').val() || '';
            var end_date = $('#end_date').val() || '';
            var search = $('#search').val() || '';
            var type = $('#type').val() || '';
            
            // Destruir o datatable anterior
            $('#datatable-redemptions').DataTable().destroy();
            
            // Reinicializar com os novos parâmetros
            table = initDatatable(start_date, end_date, search, type);
            
            // Atualizar a URL para refletir os novos filtros sem recarregar a página
            var newUrl = "{{ route('admin.lucky-boxes.redemptions', $box->id) }}";
            var hasParams = false;
            
            if (start_date) {
                newUrl += (hasParams ? '&' : '?') + 'start_date=' + start_date;
                hasParams = true;
            }
            
            if (end_date) {
                newUrl += (hasParams ? '&' : '?') + 'end_date=' + end_date;
                hasParams = true;
            }
            
            if (search) {
                newUrl += (hasParams ? '&' : '?') + 'search=' + encodeURIComponent(search);
                hasParams = true;
            }
            
            if (type) {
                newUrl += (hasParams ? '&' : '?') + 'type=' + type;
            }
            
            window.history.pushState({}, '', newUrl);
        }
        
        // Evento para campos de data - aplicar filtro imediatamente ao mudar
        $('.filter-input[type="date"]').on('change', function() {
            reloadTable();
        });
        
        // Evento para campo de seleção - aplicar filtro imediatamente ao mudar
        $('#type').on('change', function() {
            reloadTable();
        });
        
        // Evento para campo de texto - aplicar filtro após parar de digitar
        $('#search').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(reloadTable, doneTypingInterval);
        });
        
        $('#search').on('keydown', function() {
            clearTimeout(typingTimer);
        });
        
        // Inicializar gerenciador de toasts
        @if(session('success'))
            ToastManager.success("{{ session('success') }}");
        @endif
        
        @if(session('error'))
            ToastManager.error("{{ session('error') }}");
        @endif
    });
</script>
@endpush

@endsection 