@extends('admin.layouts.app')
@section('content')
@php
    use Carbon\Carbon;
    
    $a = request()->input('aff', '');
    $b = request()->input('di', '');
    $c = request()->input('df', '');
    
    $dataInicial = $b;
    $dataFinal = $c;
    $nomeUsuario = $a;
@endphp
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Pagamentos</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Saques Afiliados</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <div class="row" style="margin-bottom: -20px; padding:15px;">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="dataInicial" class="form-label">Data Inicial:</label>
                                        <input type="date" id="dataInicial" name="di" class="form-control filter-input" value="{{$b}}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="dataFinal" class="form-label">Data Final:</label>
                                        <input type="date" id="dataFinal" name="df" class="form-control filter-input" value="{{$c}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nomeUsuario" class="form-label">Nome ou CPF:</label>
                                        <input type="text" id="nomeUsuario" name="aff" placeholder="Buscar por nome ou CPF..." value="{{$a}}" class="form-control filter-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="datatable-saques-afiliados" class="table table-striped dt-table-hover dataTable" style="width:100%" role="grid" aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="datatable-saques-afiliados" rowspan="1" colspan="1">Nome</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-saques-afiliados" rowspan="1" colspan="1">Valor</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-saques-afiliados" rowspan="1" colspan="1">Gateway</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-saques-afiliados" rowspan="1" colspan="1">Status</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-saques-afiliados" rowspan="1" colspan="1">Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Os dados serão preenchidos pelo DataTable -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">Nome</th>
                                        <th rowspan="1" colspan="1">Valor</th>
                                        <th rowspan="1" colspan="1">Gateway</th>
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

<style>
    body.dark .form-check-input:checked {
        background-color: #4361ee;
        border-color: #4361ee;
    }
</style>

@push('scripts')
<!-- DataTable Buttons Scripts -->
<script src="{{ asset('src/plugins/src/table/datatable/button-ext/dataTables.buttons.min.js') }}"></script>
<script>
    $(function () {
        // Configuração para suprimir mensagens de erro do DataTables em console
        $.fn.dataTable.ext.errMode = 'none';
        
        // Variável para armazenar o timeout da digitação
        var typingTimer;
        var doneTypingInterval = 500; // Tempo em ms para aguardar após a digitação

        // Função para inicializar o datatable com as opções desejadas
        function initDatatable(dataInicial, dataFinal, nomeUsuario) {
            var table = $('#datatable-saques-afiliados').DataTable({
                processing: false,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.pagamentos.saques_afiliados.data') }}",
                    data: function (d) {
                        d.dataInicial = dataInicial || $('#dataInicial').val();
                        d.dataFinal = dataFinal || $('#dataFinal').val();
                        d.nomeUsuario = nomeUsuario || $('#nomeUsuario').val();
                    },
                    error: function (xhr, error, thrown) {
                    }
                },
                columns: [
                    {data: 'usuario', name: 'usuario'},
                    {data: 'valor', name: 'valor'},
                    {data: 'gateway', name: 'gateway'},
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
                order: [[4, 'desc']],
                dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'B><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
                lengthMenu: [10, 25, 50, 100],
                pageLength: 20,
                pagingType: 'full_numbers',
                buttons: [
                    {
                        text: 'Relatório PDF',
                        className: 'btn btn-sm btn-success',
                        action: function ( e, dt, node, config ) {
                            gerarRelatorioPDF();
                        }
                    }
                ],
                drawCallback: function(settings) {
                    // Adicionar classes ao paginador
                    $('#datatable-saques-afiliados_paginate').addClass('paging_simple_numbers');
                    $('#datatable-saques-afiliados_paginate ul.pagination li').addClass('paginate_button page-item');
                    $('#datatable-saques-afiliados_paginate ul.pagination li.previous').attr('id', 'datatable-saques-afiliados_previous');
                    $('#datatable-saques-afiliados_paginate ul.pagination li.next').attr('id', 'datatable-saques-afiliados_next');
                    $('#datatable-saques-afiliados_paginate ul.pagination li.first').attr('id', 'datatable-saques-afiliados_first');
                    $('#datatable-saques-afiliados_paginate ul.pagination li.last').attr('id', 'datatable-saques-afiliados_last');
                    $('#datatable-saques-afiliados_paginate ul.pagination li a').addClass('page-link');
                    
                    // Substituir o texto dos botões de paginação por ícones SVG
                    $('#datatable-saques-afiliados_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                    $('#datatable-saques-afiliados_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                    $('#datatable-saques-afiliados_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                    $('#datatable-saques-afiliados_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');
                },
                initComplete: function() {
                    // Adiciona um evento para recarregar a tabela quando ocorrer um erro
                    $('#datatable-saques-afiliados').on('error.dt', function(e, settings, techNote, message) {
                    });
                    
                    // Remover campo de busca gerado automaticamente
                    $('.dataTables_filter').remove();
                }
            });
            
            return table;
        }

        // Inicializar o datatable com os valores dos parâmetros da URL
        var dataInicial = "{{ $dataInicial }}";
        var dataFinal = "{{ $dataFinal }}";
        var nomeUsuario = "{{ $nomeUsuario }}";
        var table = initDatatable(dataInicial, dataFinal, nomeUsuario);
        
        // Função para recarregar a tabela com os novos filtros
        function reloadTable() {
            var dataInicial = $('#dataInicial').val();
            var dataFinal = $('#dataFinal').val();
            var nomeUsuario = $('#nomeUsuario').val();
            
            // Destruir o datatable anterior
            $('#datatable-saques-afiliados').DataTable().destroy();
            
            // Reinicializar com os novos parâmetros
            table = initDatatable(dataInicial, dataFinal, nomeUsuario);
            
            // Atualizar a URL para refletir os novos filtros sem recarregar a página
            var newUrl = "{{ route('admin.pagamentos.saques_afiliados') }}?di=" + dataInicial + "&df=" + dataFinal + "&aff=" + encodeURIComponent(nomeUsuario);
            window.history.pushState({}, '', newUrl);
        }
        
        // Evento para campos de data - aplicar filtro imediatamente ao mudar
        $('.filter-input[type="date"]').on('change', function() {
            reloadTable();
        });
        
        // Evento para campo de texto - aplicar filtro após parar de digitar
        $('#nomeUsuario').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(reloadTable, doneTypingInterval);
        });
        
        $('#nomeUsuario').on('keydown', function() {
            clearTimeout(typingTimer);
        });
    });
    
    // Função para gerar relatório em PDF - definida globalmente
    function gerarRelatorioPDF() {
        var dataInicial = $('#dataInicial').val();
        var dataFinal = $('#dataFinal').val();
        var nomeUsuario = $('#nomeUsuario').val();
        
        var url = "{{ route('admin.pagamentos.saques_afiliados.pdf') }}";
        var params = [];
        
        if (dataInicial) {
            params.push('di=' + encodeURIComponent(dataInicial));
        }
        if (dataFinal) {
            params.push('df=' + encodeURIComponent(dataFinal));
        }
        if (nomeUsuario) {
            params.push('aff=' + encodeURIComponent(nomeUsuario));
        }
        
        if (params.length > 0) {
            url += '?' + params.join('&');
        }
        
        // Abrir o PDF em uma nova aba
        window.open(url, '_blank');
    }
</script>
@endpush

@endsection