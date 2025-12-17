@php
    use App\Models\Settings;

    // Variáveis globais usadas no layout (sidebar, navbar, etc.)
    $Settings = Settings::first();
    $saques_pendentes = App\Models\Transactions::where('type', 1)->where('status', 0)->count();
    $JogandoAgora = App\Models\User::where('playing', 1)->count();
    $NPendencia = App\Models\Transactions::Where('type', 1)->Where('status', 0)->count();
    
    // NOTA: Todas as queries de dashboard (transações, cassino, esportes) foram
    // movidas para o DashboardController para melhor organização e performance.
    // As variáveis são passadas via controller para a view dash.blade.php
@endphp

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Models\Settings::first()->name ?? config('app.name') }} - {{ \App\Models\Settings::first()->subname ?? config('app.name') }}</title>
    <link rel="icon" href="{{ url(asset(\App\Models\Settings::first()->favicon)) }}" type="image/png">
    <link href="{{ asset('layouts/modern-light-menu/css/light/loader.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('layouts/modern-light-menu/css/dark/loader.css') }}" rel="stylesheet" type="text/css" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('src/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('layouts/modern-light-menu/css/light/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('layouts/modern-light-menu/css/dark/plugins.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('src/assets/css/light/apps/chat.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/apps/chat.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/src/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/light/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/dark/table/datatable/dt-global_style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/light/table/datatable/custom_dt_custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/dark/table/datatable/custom_dt_custom.css') }}">
    <link href="{{ asset('src/assets/css/light/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/scrollspyNav.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/light/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/elements/tooltip.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/light/components/tabs.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/components/tabs.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('src/assets/css/light/forms/switches.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('src/assets/css/dark/forms/switches.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('src/assets/css/light/components/accordions.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/components/accordions.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/light/apps/chat.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/apps/chat.css') }}" rel="stylesheet" type="text/css" />
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="{{ asset('src/assets/css/light/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/dashboard/dash_1.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/plugins/src/autocomplete/css/autoComplete.02.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/plugins/css/light/autocomplete/css/custom-autoComplete.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/plugins/css/dark/autocomplete/css/custom-autoComplete.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/light/table/datatable/custom_dt_miscellaneous.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/dark/table/datatable/custom_dt_miscellaneous.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/src/tagify/tagify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/light/tagify/custom-tagify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/dark/tagify/custom-tagify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/src/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/light/bootstrap-touchspin/custom-jquery.bootstrap-touchspin.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/dark/bootstrap-touchspin/custom-jquery.bootstrap-touchspin.min.css') }}">
    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="{{ asset('src/plugins/src/notification/snackbar/snackbar.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/plugins/src/sweetalerts2/sweetalerts2.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/light/components/modal.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/light/editors/quill/quill.snow.css') }}">
    <link href="{{ asset('src/assets/css/light/apps/mailbox.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/plugins/css/light/sweetalerts2/custom-sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/components/modal.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/dark/editors/quill/quill.snow.css') }}">
    <link href="{{ asset('src/assets/css/dark/apps/mailbox.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/plugins/css/dark/sweetalerts2/custom-sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('src/assets/css/light/elements/alert.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/assets/css/dark/elements/alert.css') }}">
    <link href="{{ asset('src/assets/css/light/components/list-group.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('src/assets/css/light/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/components/list-group.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('src/assets/css/dark/users/user-profile.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/plugins/src/apex/apexcharts.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @if(!Auth::check() || (Auth::check() && Auth::user()->is_admin > 1))
        <script disable-devtool-auto src="https://cdn.jsdelivr.net/npm/disable-devtool"></script>
    @endif
    <style>
            body.dark .form-check-input:checked {
                background-color: #4361ee;
                border-color: #4361ee;
            }
            .form-check-input:checked {
                background-color: #4361ee;
                border-color: #4361ee;
            }
        .form-afil {
            border: 1px solid #1b2e4b !important;
            color: #009688 !important;
            border-radius: 6px !important;
            background: #1b2e4b !important;
            min-width: 150px !important;
            padding: 7px !important;
            flex: none;
            width: auto;
        }
        body.dark .modal-content .modal-header .btn-close{
            color:#4361ee !important;
            font-size: 1.5em !important;
        }
        .dark .modal-content .modal-header .btn-close{
            font-size: 1.5em !important;
        }
        /* Styling for horizontal scrollbar */
        ::-webkit-scrollbar {
            height: 8px;
            background-color: #00000012;
        }

        ::-webkit-scrollbar-track {
            background-color: #00000012;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #4361ee;
            border-radius: 4px;
        }

        /* For Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #4361ee #00000012;
        }

        /* For Edge and IE */
        ::-webkit-scrollbar-button {
            display: none;
        }

        body.dark #jogos_partidas_processing {
            color: white;
        }
        body.dark #transacoes_processing {
            color: white;
        }
        body.dark #historico_jogos_processing {
            color: white;
        }
        body.dark #afiliacao_agente_processing {
            color: white;
        }
        body.dark #afiliacao_agente_processing {
            color: white;
        }
        body.dark #zero-config_processing {
            color: white;
        }
        .border-light {
            --bs-border-opacity: 1;
            border-color: rgb(255 255 255 / 6%) !important;
        }
    </style>

    <!-- Stack styles -->
    @stack('styles')

    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/src/tomSelect/tom-select.default.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
</head>

<body class="layout-boxed dark">
<!-- BEGIN LOADER -->
<div id="load_screen">
    <div class="loader">
        <div class="loader-content">
            <div class="spinner-grow align-self-center"></div>
        </div>
    </div>
</div>
<!--  END LOADER -->

<!--  BEGIN NAVBAR  -->
<div class="header-container container-xxl">
    @include('admin.partials.navbar')
</div>
<!--  END NAVBAR  -->

<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container" id="container">
    <div class="overlay"></div>
    <div class="search-overlay"></div>

    <!--  BEGIN SIDEBAR  -->
    <div class="sidebar-wrapper sidebar-theme" style="top: 0px;">
        @include('admin.partials.sidebar')
    </div>
    <!--  END SIDEBAR  -->

    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content" style="">
        @yield('content')
        <!--  BEGIN FOOTER  -->
        <div class="footer-wrapper">
            @include('admin/layouts/footer')
        </div>
        <!--  END FOOTER  -->
    </div>
    <!--  END CONTENT AREA  -->
</div>
<!-- END MAIN CONTAINER -->
@include('admin.modal.modal')

<!-- Password Change Modal -->
@include('admin.auth.change-password')
<!-- End Password Change Modal -->
<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->

<script src="{{ asset('src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
<script src="{{ asset('src/plugins/src/global/vendors.min.js') }}"></script>

<script src="{{ asset('src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/mousetrap/mousetrap.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/waves/waves.min.js') }}"></script>
<script src="{{ asset('layouts/modern-light-menu/app.js') }}"></script>
<script src="{{ asset('src/assets/js/custom.js') }}"></script>
<script src="{{ asset('src/plugins/src/highlight/highlight.pack.js') }}"></script>
<script src="{{ asset('layouts/modern-light-menu/loader.js') }}"></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset('src/plugins/src/table/datatable/datatables.js') }}"></script>
<script src="{{ asset('src/assets/js/scrollspyNav.js') }}"></script>
<script src="{{ asset('src/plugins/src/apex/apexcharts.min.js') }}"></script>
<script src="{{ asset('src/assets/js/dashboard/dash_1.js') }}"></script>
<script src="{{ asset('src/plugins/src/autocomplete/autoComplete.min.js') }}"></script>
<script src="{{ asset('js/modals.js') }}"></script>
<script src="{{ asset('src/plugins/src/editors/quill/quill.js') }}"></script>
<script src="{{ asset('src/plugins/src/sweetalerts2/sweetalerts2.min.js') }}"></script>
<script src="{{ asset('src/plugins/src/notification/snackbar/snackbar.min.js') }}"></script>
<script src="{{ asset('js/admin/mailbox.js') }}"></script>
<script src="{{ asset('src/plugins/src/tagify/tagify.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lrsjng.jquery-qrcode/0.14.0/jquery-qrcode.js"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<script>
    // Configuração para suprimir mensagens de erro do DataTables
    $.fn.dataTable.ext.errMode = 'none';

    $('#zero-config').DataTable({
        "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Mostrando página _PAGE_ de _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Busque aqui...",
            "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [7, 10, 15, 20, 25, 30, 35, 40, 50],
        "pageLength": 10
    });
</script>
<script>
    function FormataMoeda(Entrada) {
        const Saida = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        }).format(Entrada);

        return Saida.replace('R$', '').trim();
    }

    function FormataMoedaUSD(Entrada) {
        const Saida = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 20,
            useGrouping: true
        }).format(Entrada);

        return 'R$ ' + Saida.trim();
    }

    function FormataMoedaDireto(Entrada) {
        const Saida = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
        }).format(Entrada);

        return Saida.replace('$', '').trim();
    }

    function TabelasProcess(qual, quem) {
        let a = document.getElementById('example4');
        if (a){InfoA = document.getElementById('example4').value;}

        let b = document.getElementById('dataInicial4');
        if (b){InfoB = document.getElementById('dataInicial4').value;}

        let c = document.getElementById('dataFinal4');
        if (c){InfoC = document.getElementById('dataFinal4').value;}

        if (qual === "historico_jogos") {
            let a = document.getElementById('example3');
            if (a){InfoA = document.getElementById('example3').value;}

            let b = document.getElementById('dataInicial2');
            if (b){InfoB = document.getElementById('dataInicial2').value;}

            let c = document.getElementById('dataFinal2');
            if (c){InfoC = document.getElementById('dataFinal2').value;}
        }else if (qual === "afiliacao_agente") {
            let a = document.getElementById('example4');
            if (a){InfoA = document.getElementById('example4').value;}

            let b = document.getElementById('dataInicial3');
            if (b){InfoB = document.getElementById('dataInicial3').value;}

            let c = document.getElementById('dataFinal3');
            if (c){InfoC = document.getElementById('dataFinal3').value;}
        }

        $('#' + qual).DataTable().destroy();

        $(document).ready(function () {
            var columns;

            if (qual === "transacoes") {
                columns = [
                    {data: 'type', name: 'type'},
                    {data: 'amount', name: 'amount'},
                    {data: 'gateway', name: 'gateway'},
                    {data: 'status', name: 'status'},
                    {data: 'updated_at', name: 'updated_at'}
                ];
            }else if (qual === "historico_jogos") {
                columns = [
                    {data: 'game_name', name: 'game.name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'action', name: 'action'},
                    {data: 'updated_at', name: 'updated_at'}
                ];
            }else if (qual === "afiliacao_agente") {
                columns = [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'amount', name: 'amount'},
                    {data: 'updated_at', name: 'updated_at'}
                ];
            }

            Tabela = $('#' + qual).DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/rpt-gh-2?d=' + quem + '&tab=' + qual + '&a=' + InfoA + '&b=' + InfoB + '&c=' + InfoC,
                    type: 'GET'
                },
                columns: columns,

                "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                "oLanguage": {
                    "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                    "sInfo": "Exibindo página _PAGE_ de _PAGES_",
                    "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    "sSearchPlaceholder": "Procurar...",
                    "sLengthMenu": "Resultados :  _MENU_",
                    sProcessing: "Processando...",
                },

                "stripeClasses": [],
                "lengthMenu": [7, 10, 20, 50],
                "pageLength": 7,
                "ordering": true,
                "searching": false,
                "order": [],

                drawCallback: function () {
                    var dtTooltip = document.querySelectorAll('.t-dot');
                    for (let index = 0; index < dtTooltip.length; index++) {
                        var tooltip = new bootstrap.Tooltip(dtTooltip[index], {
                            template: '<div class="tooltip status rounded-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
                            title: `${dtTooltip[index].getAttribute('data-original-title')}`
                        })
                    }
                    $('.dataTables_wrapper table').removeClass('table-striped');
                }
            });
        });
    }

    function LoadAgent(ID){
        document.getElementById("return_agents").innerHTML = "<center><i class=\"fa fa-spinner fa-spin\" style=\"margin-top: 20px;\"></i></center>";

        $.ajax({
            url: '/adm-ld-ag/' + ID,
            type: "GET",
            data: $(this).serialize(),
            success: function (response) {
                document.getElementById("return_agents").innerHTML = response;

                // Resetar os filtros para garantir que todas as transações sejam exibidas inicialmente
                document.getElementById('example4').value = "";

                // Inicializar as tabelas
                TabelasProcess('transacoes', ID);
                TabelasProcess('historico_jogos', ID);
                TabelasProcess('afiliacao_agente', ID);

                //EnableAutoCompleteTeste('example3', 'Insira o nome do jogo...', '/Adm/Games');
            },
            error: function (xhr) {
            }
        });
    }

    function SaveAgent() {
        $.ajax({
            url: "/adm-up-ag",
            type: "POST",
            data: $('#agentsForm').serialize(),
            success: function (response) {
                if (response.status) {
                    $('#tabsModal').modal('hide');
                    ToastManager.success(response.message);
                } else {
                    ToastManager.error(response.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 419) {
                    ToastManager.error('');
                } else {
                    ToastManager.error(xhr.msg);
                }
            }
        });
    }

    function CancelSportBet(id){
        Swal.fire({
            title: 'Cancelar Aposta ' + id + '?',
            text: "Apenas usar se o apostador estiver bloqueado!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, cancelar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/Admin/CancelSportBet",
                    type: "POST",
                    data: {
                        cid: id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status) {
                            Swal.fire(
                                'Cancelada!',
                                'A aposta foi cancelada com sucesso.',
                                'success'
                            )

                            location.reload();
                        } else {
                            ToastManager.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 419) {
                            ToastManager.error('');
                        } else {
                            ToastManager.error(xhr.msg);
                        }
                    }
                });
            }
        })
    }

    function DeleteAgent(id, name){
        Swal.fire({
            title: 'Deletar Usuário ' + name + '?',
            text: "Essa ação não pode ser revertida!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, deletar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/adm-rm-ag",
                    type: "POST",
                    data: {
                        cid: id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status) {
                            Swal.fire(
                                'Deletado!',
                                'O usuário foi deletado com sucesso.',
                                'success'
                            )

                            location.reload();
                        } else {
                            ToastManager.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 419) {
                            ToastManager.error('');
                        } else {
                            ToastManager.error(xhr.msg);
                        }
                    }
                });
            }
        })
    }

    function UnblockAgent(id, name){
        Swal.fire({
            title: 'Desbloquear Usuário ' + name + '?',
            text: "Essa ação não pode ser revertida!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, desbloquear!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/adm-ub-ag",
                    type: "POST",
                    data: {
                        cid: id,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status) {
                            Swal.fire(
                                'Desbloquear!',
                                'O usuário foi desbloqueado com sucesso.',
                                'success'
                            )

                            location.reload();
                        } else {
                            ToastManager.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 419) {
                            ToastManager.error('');
                        } else {
                            ToastManager.error(xhr.msg);
                        }
                    }
                });
            }
        })
    }
</script>

<script>
    // Password change form handling
    document.addEventListener('DOMContentLoaded', function() {
        const passwordForm = document.getElementById('changePasswordForm');

        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                const password = document.querySelector('input[name="password"]').value;
                const passwordConfirm = document.querySelector('input[name="password_confirmation"]').value;

                // Clear any existing error messages
                const existingErrors = document.querySelectorAll('.password-error');
                existingErrors.forEach(error => error.remove());

                // Validate password match
                if (password !== passwordConfirm) {
                    e.preventDefault();

                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger password-error mt-3';
                    errorDiv.innerHTML = 'As senhas não correspondem';

                    const submitButton = document.querySelector('#changePasswordForm button[type="submit"]');
                    submitButton.parentNode.insertBefore(errorDiv, submitButton);
                    return false;
                }

                // Validate password length
                if (password.length < 8) {
                    e.preventDefault();

                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger password-error mt-3';
                    errorDiv.innerHTML = 'A senha deve ter pelo menos 8 caracteres';

                    const submitButton = document.querySelector('#changePasswordForm button[type="submit"]');
                    submitButton.parentNode.insertBefore(errorDiv, submitButton);
                    return false;
                }

                return true;
            });
        }

        // Display error messages in modal if present in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('password_error')) {
            const passwordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
            passwordModal.show();
        }
    });

</script>
{{-- Floating Theme Toggle Button --}}
<div class="theme-toggle-float">
    <a href="javascript: void(0);" class="theme-toggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-mode"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-mode"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
    </a>
</div>

<style>
    .theme-toggle-float {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 999;
        background-color: var(--secondary-color, #4361ee);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .theme-toggle-float:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
    }

    .theme-toggle-float .theme-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #fff;
    }

    .theme-toggle-float .dark-mode,
    .theme-toggle-float .light-mode {
        width: 24px;
        height: 24px;
    }

    /* Show only appropriate icon based on theme */
    body.dark .theme-toggle-float .dark-mode {
        display: none;
    }

    body.dark .theme-toggle-float .light-mode {
        display: block;
    }

    body:not(.dark) .theme-toggle-float .dark-mode {
        display: block;
    }

    body:not(.dark) .theme-toggle-float .light-mode {
        display: none;
    }

    @media (max-width: 768px) {
        .theme-toggle-float {
            width: 45px;
            height: 45px;
            bottom: 15px;
            right: 15px;
        }
    }
    body.dark div.dataTables_wrapper div.dataTables_filter input {
        width: 250px!important;
    }
    div.dataTables_wrapper div.dataTables_filter input {
        width: 250px!important;
    }
</style>

<script src="{{ asset('js/bet-vie.js') }}"></script>

<!-- SCRIPTS ADICIONAIS DAS PÁGINAS -->
@stack('scripts')

<!-- Theme Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.querySelector('.theme-toggle');

        // Function to toggle dark mode
        function toggleDarkMode() {
            document.body.classList.toggle('dark');

            // Save preference to localStorage
            const isDarkMode = document.body.classList.contains('dark');
            localStorage.setItem('darkMode', isDarkMode ? 'enabled' : 'disabled');
        }

        // Check for saved theme preference
        const savedTheme = localStorage.getItem('darkMode');

        // Apply saved preference if available
        if (savedTheme === 'enabled') {
            document.body.classList.add('dark');
        } else if (savedTheme === 'disabled') {
            document.body.classList.remove('dark');
        }

        // Add click event to theme toggle button
        if (themeToggle) {
            themeToggle.addEventListener('click', toggleDarkMode);
        }
    });
</script>
<!-- END SCRIPTS -->
</body>
</html>
