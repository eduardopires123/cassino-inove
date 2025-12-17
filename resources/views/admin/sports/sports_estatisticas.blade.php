@extends('admin.layouts.app')
@section('content')

    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div class="col-md-12">
                        <div class="row mb-3 justify-content-center">
                            <div class="col">
                                <label for="dataInicial" class="form-label">Data Inicial:</label>
                                <input type="date" id="dataInicial" class="form-control" value="{{ $dataInicial }}">
                            </div>
                            <div class="col">
                                <label for="dataFinal" class="form-label">Data Final:</label>
                                <div class="input-group">
                                    <input type="date" id="dataFinal" class="form-control" value="{{ $dataFinal }}">
                                    <button id="btnBuscar" class="btn btn-success _effect--ripple waves-effect waves-light" onclick="aplicarFiltros();" type="button">
                                        <span id="btnBuscarText">Buscar</span>
                                        <span id="btnBuscarSpinner" class="spinner-border spinner-border-sm" role="status" style="display: none; margin-left: 5px;">
                                            <span class="visually-hidden">Carregando...</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div class="alert alert-arrow-left alert-icon-left alert-light-primary alert-dismissible fade show mb-4" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        Resumo Sportsbook
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Apostas</h6>
                                </div>
                                <div id="cperiodosb1" class="task-action">Total</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #4CAF50;">R$ <span id="csbin">{{ number_format($totalApostasDireto, 2, ',', '.') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></p>
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
                                    <h6 class="value">Prêmios</h6>
                                </div>
                                <div id="cperiodosb2" class="task-action">Total</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value">R$ <span id="csbout">{{ number_format($totalPremios, 2, ',', '.') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="#ff0000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="m23 18l-9.5-9.5l-5 5L1 6"/><path d="M17 18h6v-6"/></g></svg>
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
                                <div id="cperiodosb3" class="task-action">Total</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #4CAF50;">R$ <span id="csbliq">{{ number_format($totalLiquido, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de apostas e prêmios -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-three">
                        <div class="widget-heading">
                            <div class="">
                                <h5 class="">Evolução de Apostas e Prêmios</h5>
                            </div>
                        </div>

                        <div class="widget-content">
                            <div id="sportsEvolution"></div>
                        </div>
                    </div>
                </div>

                <!-- Gráfico de distribuição por esportes -->
                <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-three">
                        <div class="widget-heading">
                            <div class="">
                                <h5 class="">Distribuição de Apostas por Esporte</h5>
                            </div>
                        </div>

                        <div class="widget-content">
                            <div id="sportsDistribution"></div>
                        </div>
                    </div>
                </div>

                <!-- Tabela de estatísticas por esporte -->
                <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-table-two">
                        <div class="widget-content">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><div class="th-content">Esporte</div></th>
                                        <th><div class="th-content">Apostas</div></th>
                                        <th><div class="th-content">Prêmios</div></th>
                                        <th><div class="th-content">Lucro</div></th>
                                        <th><div class="th-content">Margem</div></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($esportes as $esporte => $valores)
                                        <tr>
                                            <td><div class="td-content">{{ $esporte }}</div></td>
                                            <td><div class="td-content">R$ {{ number_format($valores['apostas'], 2, ',', '.') }}</div></td>
                                            <td><div class="td-content">R$ {{ number_format($valores['premios'], 2, ',', '.') }}</div></td>
                                            <td><div class="td-content pricing"><span class="{{ $valores['lucro'] >= 0 ? 'text-success' : 'text-danger' }}">R$ {{ number_format($valores['lucro'], 2, ',', '.') }}</span></div></td>
                                            <td><div class="td-content">{{ $valores['percentual'] }}%</div></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Gráfico de evolução
            var sportsEvolution = {
                chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                        show: false,
                    }
                },
                colors: ['#1b55e2', '#e7515a', '#8dbf42'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                series: {!! json_encode($series) !!},
                xaxis: {
                    categories: {!! json_encode($labels) !!},
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            return 'R$ ' + val.toLocaleString('pt-BR', { minimumFractionDigits: 2 })
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.3,
                        opacityFrom: 1,
                        opacityTo: 0.8,
                        stops: [0, 100]
                    }
                }
            }

            var chartEvolution = new ApexCharts(
                document.querySelector("#sportsEvolution"),
                sportsEvolution
            );
            chartEvolution.render();

            // Gráfico de distribuição por esportes
            var sportsDistribution = {
                chart: {
                    type: 'donut',
                    height: 350
                },
                colors: ['#1b55e2', '#e7515a', '#8dbf42', '#e2a03f', '#2196f3'],
                dataLabels: {
                    enabled: true,
                },
                series: {!! $esporteData !!},
                labels: {!! $esporteLabels !!},
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            return 'R$ ' + val.toLocaleString('pt-BR', { minimumFractionDigits: 2 })
                        }
                    }
                }
            }

            var chartDistribution = new ApexCharts(
                document.querySelector("#sportsDistribution"),
                sportsDistribution
            );
            chartDistribution.render();
        });

        /**
         * Controla o estado de loading do botão buscar
         */
        function showLoadingButton(show) {
            const btn = document.getElementById('btnBuscar');
            const text = document.getElementById('btnBuscarText');
            const spinner = document.getElementById('btnBuscarSpinner');

            if (show) {
                btn.disabled = true;
                text.textContent = 'Carregando';
                spinner.style.display = 'inline-block';
            } else {
                btn.disabled = false;
                text.textContent = 'Buscar';
                spinner.style.display = 'none';
            }
        }

        // Função para aplicar filtros
        function aplicarFiltros() {
            var dataInicial = document.getElementById('dataInicial').value;
            var dataFinal = document.getElementById('dataFinal').value;

            if (!dataInicial || !dataFinal) {
                alert('Por favor, selecione as datas inicial e final.');
                return;
            }

            if (new Date(dataInicial) > new Date(dataFinal)) {
                alert('A data inicial não pode ser maior que a data final!');
                return;
            }

            // Mostrar loading
            showLoadingButton(true);

            // Redirecionar
            window.location.href = '/admin/sports/sports_estatisticas?dataInicial=' + dataInicial + '&dataFinal=' + dataFinal;
        }
    </script>
@endsection
