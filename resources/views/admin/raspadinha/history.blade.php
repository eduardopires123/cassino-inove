@extends('admin.layouts.app')

@section('title', 'Histórico de Jogadas - Raspadinha')

@section('content')
@php
    use Carbon\Carbon;
    
    $raspadinha_id = request()->input('raspadinha_id', '');
    $status = request()->input('status', '');
    $date_from = request()->input('date_from', Carbon::now()->subDays(7)->format('Y-m-d'));
    $date_to = request()->input('date_to', Carbon::now()->format('Y-m-d'));
@endphp

<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.raspadinha.index') }}">Raspadinhas</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Histórico de Jogadas</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row mb-4" style="padding: 20px 20px 0 20px;">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Histórico de Jogadas</h4>
                            <a href="{{ route('admin.raspadinha.index') }}" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> Voltar
                            </a>
                        </div>
                    </div>

                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <div class="row" style="margin-bottom: -20px; padding:15px;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="raspadinha_id" class="form-label">Raspadinha:</label>
                                        <select class="form-control filter-input" id="raspadinha_id" name="raspadinha_id">
                                            <option value="">Todas</option>
                                            @foreach($raspadinhas as $rasp)
                                                <option value="{{ $rasp->id }}" {{ $raspadinha_id == $rasp->id ? 'selected' : '' }}>
                                                    {{ $rasp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="status" class="form-label">Status:</label>
                                        <select class="form-control filter-input" id="status" name="status">
                                            <option value="">Todos</option>
                                            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completo</option>
                                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pendente</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="date_from" class="form-label">Data Inicial:</label>
                                        <input type="date" id="date_from" name="date_from" class="form-control filter-input" value="{{ $date_from }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="date_to" class="form-label">Data Final:</label>
                                        <input type="date" id="date_to" name="date_to" class="form-control filter-input" value="{{ $date_to }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable-raspadinha" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="datatable-raspadinha" rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending">ID</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-raspadinha" rowspan="1" colspan="1" aria-label="Usuário: activate to sort column ascending">Usuário</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-raspadinha" rowspan="1" colspan="1" aria-label="Raspadinha: activate to sort column ascending">Raspadinha</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-raspadinha" rowspan="1" colspan="1" aria-label="Valor Pago: activate to sort column ascending">Valor Pago</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-raspadinha" rowspan="1" colspan="1" aria-label="Valor Ganho: activate to sort column ascending">Valor Ganho</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-raspadinha" rowspan="1" colspan="1" aria-label="Tipo: activate to sort column ascending">Tipo</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-raspadinha" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">Status</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-raspadinha" rowspan="1" colspan="1" aria-label="Data: activate to sort column ascending">Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Os dados serão preenchidos pelo DataTable -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">ID</th>
                                        <th rowspan="1" colspan="1">Usuário</th>
                                        <th rowspan="1" colspan="1">Raspadinha</th>
                                        <th rowspan="1" colspan="1">Valor Pago</th>
                                        <th rowspan="1" colspan="1">Valor Ganho</th>
                                        <th rowspan="1" colspan="1">Tipo</th>
                                        <th rowspan="1" colspan="1">Status</th>
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

@endsection

@push('scripts')
<script>
$(function() {
    // Configuração para suprimir mensagens de erro do DataTables em console
    $.fn.dataTable.ext.errMode = 'none';
    
    // Variável para armazenar o timeout da digitação
    var typingTimer;
    var doneTypingInterval = 500; // Tempo em ms para aguardar após a digitação

    // Função para inicializar o datatable com as opções desejadas
    function initDatatable(raspadinha_id, status, date_from, date_to) {
        var table = $('#datatable-raspadinha').DataTable({
            processing: false,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('admin.raspadinha.history.data') }}",
                data: function (d) {
                    d.raspadinha_id = raspadinha_id || $('#raspadinha_id').val();
                    d.status = status || $('#status').val();
                    d.date_from = date_from || $('#date_from').val();
                    d.date_to = date_to || $('#date_to').val();
                },
                error: function (xhr, error, thrown) {
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'usuario', name: 'usuario'},
                {data: 'raspadinha', name: 'raspadinha'},
                {data: 'valor_pago', name: 'valor_pago'},
                {data: 'valor_ganho', name: 'valor_ganho'},
                {data: 'tipo', name: 'tipo'},
                {data: 'status', name: 'status'},
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
                $('#datatable-raspadinha_paginate').addClass('paging_simple_numbers');
                $('#datatable-raspadinha_paginate ul.pagination li').addClass('paginate_button page-item');
                $('#datatable-raspadinha_paginate ul.pagination li.previous').attr('id', 'datatable-raspadinha_previous');
                $('#datatable-raspadinha_paginate ul.pagination li.next').attr('id', 'datatable-raspadinha_next');
                $('#datatable-raspadinha_paginate ul.pagination li.first').attr('id', 'datatable-raspadinha_first');
                $('#datatable-raspadinha_paginate ul.pagination li.last').attr('id', 'datatable-raspadinha_last');
                $('#datatable-raspadinha_paginate ul.pagination li a').addClass('page-link');
                
                // Substituir o texto dos botões de paginação por ícones SVG
                $('#datatable-raspadinha_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                $('#datatable-raspadinha_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                $('#datatable-raspadinha_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                $('#datatable-raspadinha_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');
            },
            initComplete: function() {
                // Adiciona um evento para recarregar a tabela quando ocorrer um erro
                $('#datatable-raspadinha').on('error.dt', function(e, settings, techNote, message) {
                });
                
                // Remover campo de busca gerado automaticamente
                $('.dataTables_filter').remove();
            }
        });
        
        return table;
    }

    // Inicializar o datatable com os valores dos parâmetros da URL
    var raspadinha_id = "{{ $raspadinha_id }}";
    var status = "{{ $status }}";
    var date_from = "{{ $date_from }}";
    var date_to = "{{ $date_to }}";
    var table = initDatatable(raspadinha_id, status, date_from, date_to);
    
    // Função para recarregar a tabela com os novos filtros
    function reloadTable() {
        var raspadinha_id = $('#raspadinha_id').val();
        var status = $('#status').val();
        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        
        // Destruir o datatable anterior
        $('#datatable-raspadinha').DataTable().destroy();
        
        // Reinicializar com os novos parâmetros
        table = initDatatable(raspadinha_id, status, date_from, date_to);
        
        // Atualizar a URL para refletir os novos filtros sem recarregar a página
        var newUrl = "{{ route('admin.raspadinha.history') }}?raspadinha_id=" + raspadinha_id + "&status=" + status + "&date_from=" + date_from + "&date_to=" + date_to;
        window.history.pushState({}, '', newUrl);
    }
    
    // Evento para campos de data - aplicar filtro imediatamente ao mudar
    $('.filter-input[type="date"]').on('change', function() {
        reloadTable();
    });
    
    // Evento para selects - aplicar filtro imediatamente ao mudar
    $('.filter-input[type!="date"]').on('change', function() {
        reloadTable();
    });
});
</script>
@endpush 