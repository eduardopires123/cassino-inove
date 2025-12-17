@extends('admin.layouts.app')
@section('content')
    <style>
        .w-info{
            width: 100%;
        }

        /* Missing CSS definitions */
        .dividerx {
            height: 1px;
            background-color: rgba(0, 0, 0, 0.1);
            width: 100%;
            margin: 15px 0;
        }

        .panelx {
            background-color: #1b2e4b;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            width: 100%;
        }

        .commission-background {
            border-left: 4px solid #44e305;
        }

        .comissão-footer {
            margin-bottom: 10px;
        }

        .comissão-footer h2 {
            margin: 0;
            color: #fff;
        }

        .comissão-footer-values {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comissão-footer-values p {
            margin: 0;
            font-size: 16px;
            color: #888ea8;
        }

        .comissão-footer-values h2 {
            margin: 0;
            font-size: 24px;
        }

        /* New CSS for table-content-insert alignment */
        .table-content-insert {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .table-content-insert .label {
            text-align: left;
        }

        .table-content-insert .value {
            text-align: right;
        }


    </style>

    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Afiliação</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Estátistica Geral</li>
                    </ol>
                </nav>
            </div>

            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="row" style="margin-bottom: 16px;">
                            <div class="col">
                                <label for="dataInicial" class="form-label">Data Inicial:</label>
                                <input type="date" id="dataInicial" name="di" class="form-control filter-input" value="{{ $dataInicial }}">
                            </div>
                            <div class="col">
                                <label for="dataFinal" class="form-label">Data Final:</label>
                                <input type="date" id="dataFinal" name="df" class="form-control filter-input" value="{{ $dataFinal }}">
                            </div>
                            <div class="col">
                                <label for="nomeAfiliado" class="form-label">Afiliado:</label>
                                <input type="text" id="nomeAfiliado" name="aff" placeholder="Insira o nome do Afiliado..." value="{{ $nomeAfiliado }}" class="form-control filter-input">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <div class="alert alert-arrow-left alert-icon-left alert-light-primary alert-dismissible fade show mb-4" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                Estátistica Geral
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-card-four">
                                <div class="widget-content">
                                    <div class="w-header">
                                        <div class="w-info">
                                            <h6 class="value">Depósitos</h6>
                                        </div>
                                        <div id="cperiodofi1" class="task-action">Período {{$dataInicial ? 'Personalizado' : 'Geral'}}</div>
                                    </div>

                                    <div class="w-content">
                                        <div class="w-info">
                                            <p class="value" style="color: #4CAF50;">R$ <span id="cfiin">{{number_format($SomaDep, 2, ',', '.')}}</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-card-four">
                                <div class="widget-content">
                                    <div class="w-header">
                                        <div class="w-info">
                                            <h6 class="value">Saques</h6>
                                        </div>
                                        <div id="cperiodofi2" class="task-action">Período {{$dataInicial ? 'Personalizado' : 'Geral'}}</div>
                                    </div>

                                    <div class="w-content">
                                        <div class="w-info">
                                            <p class="value">R$ <span id="cfiout">{{number_format($SomaSaq, 2, ',', '.')}}</span> <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="#ff0000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="m23 18l-9.5-9.5l-5 5L1 6"/><path d="M17 18h6v-6"/></g></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-card-four">
                                <div class="widget-content">
                                    <div class="w-header">
                                        <div class="w-info">
                                            <h6 class="value">Líquido</h6>
                                        </div>
                                        <div id="cperiodofi3" class="task-action">Período {{$dataInicial ? 'Personalizado' : 'Geral'}}</div>
                                    </div>

                                    <div class="w-content">
                                        <div class="w-info">
                                            <p class="value" style="color: #4CAF50;">R$ <span id="cfiliq">{{number_format($SomaDep-$SomaSaq, 2, ',', '.')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-card-four">
                                <div class="widget-content">
                                    <div class="w-header">
                                        <div class="w-info">
                                            <h6 class="value" style="text-align: left;">Cassino</h6>
                                        </div>
                                    </div>

                                    <div class="w-content">
                                        <div class="w-info">
                                            <div class="table-content" style="background: #00000012;padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Apostas</div>
                                                    <div class="value">R$&nbsp;{{number_format($lossAmount, 2, ',', '.')}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Número de apostas</div>
                                                    <div class="value">{{$totalCount}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="background: #00000012;padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Prêmios</div>
                                                    <div class="value">R$&nbsp;{{number_format($winAmount, 2, ',', '.')}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Número de prêmios</div>
                                                    <div class="value">{{$winCount}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="background: #00000012;padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Líquido</div>
                                                    <div class="value">R$&nbsp;{{number_format($casinoProfit, 2, ',', '.')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-card-four">
                                <div class="widget-content">
                                    <div class="w-header">
                                        <div class="w-info">
                                            <h6 class="value" style="text-align: left;white-space: nowrap;">Depósitos e Saques</h6>
                                        </div>
                                    </div>

                                    <div class="w-content">
                                        <div class="w-info">
                                            <div class="table-content" style="background: #00000012;padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Depósitos</div>
                                                    <div class="value">R$&nbsp;{{number_format($SomaDep, 2, ',', '.')}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Número de Depósitos</div>
                                                    <div class="value">{{$CountDep}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="background: #00000012;padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Saques</div>
                                                    <div class="value">R$&nbsp;{{number_format($SomaSaq, 2, ',', '.')}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Número de Saques</div>
                                                    <div class="value">{{$CountSaq}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="background: #00000012;padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Líquido</div>
                                                    <div class="value">R$&nbsp;{{number_format($TotalDS, 2, ',', '.')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-card-four">
                                <div class="widget-content">
                                    <div class="w-header">
                                        <div class="w-info">
                                            <h6 class="value" style="text-align: left;">RevShare</h6>
                                        </div>
                                    </div>

                                    <div class="w-content">
                                        <div class="w-info">
                                            <div class="table-content" style="background: #00000012;padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Valor líquido de revshare</div>
                                                    <div class="value">R$ {{number_format($total_rev, 2, ',', '.')}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Número de jogos</div>
                                                    <div class="value">{{$count_rev}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="panelx commission-background">
                                <div class="comissão-footer">
                                    <h2 style="font-weight: 900;text-transform: uppercase;">Total de Receita</h2>
                                </div>

                                <div class="comissão-footer-values">
                                    <p>Receita Geral:</p>
                                    <h2 style="color:#44e305;font-weight: 900;">R$&nbsp;{{number_format($total_all, 2, ',', '.')}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                // Variável para armazenar o timeout da digitação
                var typingTimer;
                var doneTypingInterval = 500; // Tempo em ms para aguardar após a digitação

                // Função para aplicar os filtros e atualizar a página
                function applyFilters() {
                    var dataInicial = $('#dataInicial').val();
                    var dataFinal = $('#dataFinal').val();
                    var nomeAfiliado = $('#nomeAfiliado').val();

                    // Atualizar a URL com os novos parâmetros
                    var newUrl = "{{ route('admin.afiliacao.estatisticas') }}?di=" + dataInicial + "&df=" + dataFinal + "&aff=" + encodeURIComponent(nomeAfiliado);
                    window.location.href = newUrl;
                }

                // Evento para campos de data - aplicar filtro imediatamente ao mudar
                $('.filter-input[type="date"]').on('change', function() {
                    applyFilters();
                });

                // Evento para campo de texto - aplicar filtro após parar de digitar
                $('#nomeAfiliado').on('keyup', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(applyFilters, doneTypingInterval);
                });

                $('#nomeAfiliado').on('keydown', function() {
                    clearTimeout(typingTimer);
                });

                // Evento para os botões de período
                $('.period-btn').on('click', function() {
                    var periodo = $(this).data('period');
                    let dataInicial = $('#dataInicial');
                    let dataFinal = $('#dataFinal');
                    let hoje = new Date();

                    if (periodo === 'hoje') {
                        let formattedDate = hoje.toISOString().split('T')[0];
                        dataInicial.val(formattedDate);
                        dataFinal.val(formattedDate);
                    } else if (periodo === '7' || periodo === '15' || periodo === '30') {
                        let dias = parseInt(periodo);
                        let dataPassada = new Date(hoje);
                        dataPassada.setDate(hoje.getDate() - dias);
                        dataInicial.val(dataPassada.toISOString().split('T')[0]);
                        dataFinal.val(hoje.toISOString().split('T')[0]);
                    } else if (periodo === 'geral') {
                        dataInicial.val('');
                        dataFinal.val('');
                    }

                    // Após definir as datas, aplica os filtros
                    applyFilters();
                });
            });
        </script>
    @endpush
@endsection
