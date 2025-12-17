@extends('admin/layouts/app')
@section('content')

    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <div class="row layout-top-spacing">


                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div class="col-md-12">
                        <div class="row mb-3 justify-content-center">
                            <div class="col">
                                <label for="dataInicial" class="form-label">Data Inicial:</label>
                                <input type="date" id="dataInicial" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col">
                                <label for="dataFinal" class="form-label">Data Final:</label>
                                <div class="input-group">
                                    <input type="date" id="dataFinal" class="form-control" value="{{ date('Y-m-d') }}">
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
                    <div class="alert alert-arrow-left alert-icon-left alert-light-success alert-dismissible fade show mb-4" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        Financeiro
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Depósitos</h6>
                                </div>
                                <div id="cperiodofi1" class="task-action">{{ $periodoLabel ?? date('d/m') }}</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #4CAF50;">R$ <span id="cfiin" style="font-size: 2.5rem;">{{ number_format($total_in_hoje, 2, ',', '.') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></p>
                                </div>
                            </div>
                            <!-- Novos campos PIX e Manual -->
                            <div class="w-info mt-3">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Depósitos PIX</small>
                                        <p class="value" style="color: #4CAF50; font-size: 1.5rem; cursor: pointer;" onclick="openUniversalModal('pix', 'hoje')" data-bs-toggle="modal" data-bs-target="#universalModal">
                                            R$ <span id="cpixin">{{ number_format($total_pix_hoje, 2, ',', '.') }}</span>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Saldo Manual</small>
                                        <p class="value" style="color: #ffc107; font-size: 1.5rem; cursor: pointer;" onclick="openUniversalModal('manual', 'hoje')" data-bs-toggle="modal" data-bs-target="#universalModal">
                                            R$ <span id="cmanualdep">{{ number_format($total_manual_hoje, 2, ',', '.') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Saques</h6>
                                </div>
                                <div id="cperiodofi2" class="task-action">{{ $periodoLabel ?? date('d/m') }}</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value">R$ <span id="cfiout" style="font-size: 2.5rem;">{{ number_format($total_out_hoje, 2, ',', '.') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="#ff0000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="m23 18l-9.5-9.5l-5 5L1 6"/><path d="M17 18h6v-6"/></g></svg>
                                </div>
                            </div>
                            <!-- Novos campos Saques Normais e Afiliados -->
                            <div class="w-info mt-3">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Saques</small>
                                        <p class="value" style="color: #dc3545; font-size: 1.5rem; cursor: pointer;" onclick="openUniversalModal('saques', 'hoje')" data-bs-toggle="modal" data-bs-target="#universalModal">
                                            R$ <span id="csaquesnormais">{{ number_format($total_out_normal_hoje, 2, ',', '.') }}</span>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Saques Afiliados</small>
                                        <p class="value" style="color: #6f42c1; font-size: 1.5rem; cursor: pointer;" onclick="openUniversalModal('afiliados', 'hoje')" data-bs-toggle="modal" data-bs-target="#universalModal">
                                            R$ <span id="csaquesafiliados">{{ number_format($total_out_afiliados_hoje, 2, ',', '.') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Saldo Manual</h6>
                                </div>
                                <div id="cperiodofi4" class="task-action">{{ $periodoLabel ?? date('d/m') }}</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #17a2b8;">R$ <span id="cfimanual" style="font-size: 2.5rem;">{{ number_format($total_manual_hoje, 2, ',', '.') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Líquido</h6>
                                </div>
                                <div id="cperiodofi3" class="task-action">{{ $periodoLabel ?? date('d/m') }}</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #4CAF50;">R$ <span id="cfiliq" style="font-size: 2.5rem;">{{ number_format($total_hoje, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div class="alert alert-arrow-left alert-icon-left alert-light-primary alert-dismissible fade show mb-4" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        Cassino
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Apostas</h6>
                                </div>
                                <div id="cperiodoca1" class="task-action">{{ $periodoLabel ?? date('d/m') }}</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #4CAF50;">R$ <span id="ccain" style="font-size: 2.5rem;">{{ number_format($cassino_loss_today, 2, ',', '.') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></p>
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
                                <div id="cperiodoca2" class="task-action">{{ $periodoLabel ?? date('d/m') }}</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value">R$  <span id="ccaout" style="font-size: 2.5rem;">{{ number_format($cassino_win_today, 2, ',', '.') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="#ff0000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="m23 18l-9.5-9.5l-5 5L1 6"/><path d="M17 18h6v-6"/></g></svg>
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
                                <div id="cperiodoca3" class="task-action">{{ $periodoLabel ?? date('d/m') }}</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #4CAF50;">R$  <span id="ccaliq" style="font-size: 2.5rem;">{{ number_format($cassino_total_today, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <div class="alert alert-arrow-left alert-icon-left alert-light-warning alert-dismissible fade show mb-4" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                        Esportes - {{ $sports_active_provider ?? 'DIGITAIN' }}
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Apostas</h6>
                                </div>
                                <div id="cperiodoesp1" class="task-action">{{ $periodoLabel ?? date('d/m') }}</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #4CAF50;">R$ <span id="cespin" style="font-size: 2.5rem;">{{ number_format($sports_bets_today, 2, ',', '.') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Em Aberto <badge class="badge bg-primary">{{ $bilhetes_abertos_hoje['quantidade'] ?? 0 }}</badge></h6>
                                </div>
                                <div id="cperiodoesp4" class="task-action">{{ $periodoLabel ?? date('d/m') }}</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #ffc107;">R$
                                        <span id="cespabertos" style="font-size: 2.5rem;">
                                             {{ number_format($bilhetes_abertos_hoje['valor'] ?? 0, 2, ',', '.') }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12,6 12,12 16,14"></polyline>
                                        </svg>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Prêmios</h6>
                                </div>
                                <div id="cperiodoesp2" class="task-action">{{ $periodoLabel ?? date('d/m') }}</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value">R$  <span id="cespout" style="font-size: 2.5rem;">{{ number_format($sports_wins_today, 2, ',', '.') }}</span> <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="#ff0000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="m23 18l-9.5-9.5l-5 5L1 6"/><path d="M17 18h6v-6"/></g></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-info">
                                    <h6 class="value">Líquido</h6>
                                </div>
                                <div id="cperiodoesp3" class="task-action">{{ $periodoLabel ?? date('d/m') }}</div>
                            </div>

                            <div class="w-content">
                                <div class="w-info">
                                    <p class="value" style="color: #4CAF50;">R$  <span id="cespliq" style="font-size: 2.5rem;">{{ number_format($sports_total_today, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($showGgrCassinoClones || $showGgrCassinoOriginais || $showGgrEsportes)
                    <!-- GGR Section -->
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12" id="ggr-header">
                        <div class="alert alert-arrow-left alert-icon-left alert-light-danger alert-dismissible fade show mb-4" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                            {{ $ggrTitle }}
                            <div class="float-end">
                                <label class="form-label me-2 mb-0" style="font-size: 12px; color: #dc3545;">Seção:</label>
                                <select id="ggrSectionSelect" class="form-select form-select-sm d-inline-block" style="width: auto; min-width: 120px;" onchange="changeGgrSection()">
                                    @if($showGgrCassinoClones)
                                        <option value="clones" {{ $providerCassino == 'clones' ? 'selected' : '' }}>Clones</option>
                                    @endif
                                    @if($showGgrCassinoOriginais)
                                        <option value="originais" {{ $providerCassino == 'originais' ? 'selected' : '' }}>Originais</option>
                                    @endif
                                    @if($showGgrEsportes)
                                        <option value="esportes">Esportes</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                @endif

                @if($showGgrCassinoClones || $showGgrCassinoOriginais || $showGgrEsportes)
                    <!-- Cards GGR - Seção Única -->
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-header">
                                    <div class="w-info">
                                        <h6 class="value">Apostas Perdidas</h6>
                                    </div>
                                    <div class="task-action ggr-period-label">{{ $periodoLabel ?? date('d/m') }}</div>
                                </div>
                                <div class="w-content">
                                    <div class="w-info">
                                        <p class="value" style="color: #ff6b6b;">R$ <span style="font-size: 2.5rem;" id="apostas-perdidas-value">
                                        @if($providerCassino == 'esportes')
                                                    {{ number_format($apostasPerdidasSports ?? 0, 2, ',', '.') }}
                                                @else
                                                    {{ number_format($apostasPerdidasCassino, 2, ',', '.') }}
                                                @endif
                                    </span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="#ff0000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="m23 18l-9.5-9.5l-5 5L1 6"/><path d="M17 18h6v-6"/></g></svg>
                                        </p>
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
                                        <h6 class="value">Apostas Ganhadoras</h6>
                                    </div>
                                    <div class="task-action ggr-period-label">{{ $periodoLabel ?? date('d/m') }}</div>
                                </div>
                                <div class="w-content">
                                    <div class="w-info">
                                        <p class="value" style="color: #4CAF50;">R$ <span style="font-size: 2.5rem;" id="apostas-ganhadoras-value">
                                        @if($providerCassino == 'esportes')
                                                    {{ number_format($totalPremiosSports, 2, ',', '.') }}
                                                @else
                                                    {{ number_format($apostasGanhadorasCassino, 2, ',', '.') }}
                                                @endif
                                    </span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                                        </p>
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
                                        <h6 class="value">GGR Consumido</h6>
                                    </div>
                                    <div class="task-action ggr-period-label">{{ $periodoLabel ?? date('d/m') }}</div>
                                </div>
                                <div class="w-content">
                                    <div class="w-info">
                                        <p class="value" style="color: #17a2b8;">R$ <span style="font-size: 2.5rem;" id="ggr-consumido-value">
                                        @if($providerCassino == 'esportes')
                                                    {{ number_format($ggrConsumidoSports, 2, ',', '.') }}
                                                @else
                                                    {{ number_format($ggrConsumidoCassino, 2, ',', '.') }}
                                                @endif
                                    </span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!--- MENU FIM -->
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-three">
                        <div class="widget-heading">
                            <div class="">
                                <h5 class="">Visitantes</h5>
                            </div>
                        </div>

                        <div class="widget-content">
                            <div id="uniqueVisits"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget-four">
                        <div class="widget-heading">
                            <h5 class="">Visitas por Dispositivos</h5>
                        </div>
                        <div class="widget-content">
                            <div class="vistorsBrowser">
                                <div class="browser-list">
                                    <div class="w-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" fill-rule="evenodd"><path d="M24 0v24H0V0zM12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="white" d="M20 14v5a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3v-5zM12 3c1.33 0 2.584.324 3.687.899l.606-.606a1 1 0 1 1 1.414 1.414l-.35.35A7.98 7.98 0 0 1 20 11v1H4v-1a7.98 7.98 0 0 1 2.644-5.942l-.351-.35a1 1 0 0 1 1.414-1.415l.606.606A8 8 0 0 1 12 3M9 7a1 1 0 1 0 0 2a1 1 0 0 0 0-2m6 0a1 1 0 1 0 0 2a1 1 0 0 0 0-2"/></g></svg>
                                    </div>
                                    <div class="w-browser-details">
                                        <div class="w-browser-info">
                                            <h6>Android</h6>
                                            <p class="browser-count">{{ $androidPercentage ?? 0 }}%</p>
                                        </div>
                                        <div class="w-browser-stats">
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: {{ $androidPercentage ?? 0 }}%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="browser-list">
                                    <div class="w-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="white" d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47c-1.34.03-1.77-.79-3.29-.79c-1.53 0-2 .77-3.27.82c-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51c1.28-.02 2.5.87 3.29.87c.78 0 2.26-1.07 3.81-.91c.65.03 2.47.26 3.64 1.98c-.09.06-2.17 1.28-2.15 3.81c.03 3.02 2.65 4.03 2.68 4.04c-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5c.13 1.17-.34 2.35-1.04 3.19c-.69.85-1.83 1.51-2.95 1.42c-.15-1.15.41-2.35 1.05-3.11"/></svg>
                                    </div>
                                    <div class="w-browser-details">
                                        <div class="w-browser-info">
                                            <h6>IOS</h6>
                                            <p class="browser-count">{{ $iosPercentage ?? 0 }}%</p>
                                        </div>

                                        <div class="w-browser-stats">
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: {{ $iosPercentage ?? 0 }}%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="browser-list">
                                    <div class="w-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                    </div>
                                    <div class="w-browser-details">
                                        <div class="w-browser-info">
                                            <h6>Outros</h6>
                                            <p class="browser-count">{{ $desktopPercentage ?? 0 }}%</p>
                                        </div>

                                        <div class="w-browser-stats">
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-warning" role="progressbar" style="width: {{ $desktopPercentage ?? 0 }}%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="row widget-statistic">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing">
                            <div class="widget widget-one_hybrid widget-followers">
                                <div class="widget-heading">
                                    <div class="w-title">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="white" fill-rule="evenodd" d="M18.75 10.75V8h1.5v2.75H23v1.5h-2.75V15h-1.5v-2.75H16v-1.5zm-10.918 1.6C7.096 11.478 6.5 9.85 6.5 8.71V7a4 4 0 0 1 8 0v1.71c0 1.14-.6 2.773-1.332 3.642l-.361.428c-.59.699-.406 1.588.419 1.99l5.66 2.762c.615.3 1.114 1.093 1.114 1.783v.687a1 1 0 0 1-1.001.998H2a1 1 0 0 1-1-.998v-.687c0-.685.498-1.483 1.114-1.784l5.66-2.762c.821-.4 1.012-1.288.42-1.99z"/></svg>
                                        </div>
                                        <div class="">
                                            <p class="w-value">{{$TotalUsers}}</p>
                                            <h5 class="">Total de usuários</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content">
                                    <div class="w-chart">
                                        <div id="hybrid_followers"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing">
                            <div class="widget widget-one_hybrid widget-referral">
                                <div class="widget-heading">
                                    <div class="w-title">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="white" fill-rule="evenodd" d="M18.75 10.75V8h1.5v2.75H23v1.5h-2.75V15h-1.5v-2.75H16v-1.5zm-10.918 1.6C7.096 11.478 6.5 9.85 6.5 8.71V7a4 4 0 0 1 8 0v1.71c0 1.14-.6 2.773-1.332 3.642l-.361.428c-.59.699-.406 1.588.419 1.99l5.66 2.762c.615.3 1.114 1.093 1.114 1.783v.687a1 1 0 0 1-1.001.998H2a1 1 0 0 1-1-.998v-.687c0-.685.498-1.483 1.114-1.784l5.66-2.762c.821-.4 1.012-1.288.42-1.99z"/></svg>
                                        </div>
                                        <div class="">
                                            <p class="w-value">{{$Afiliados}}</p>
                                            <h5 class="">T. Afiliado</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content">
                                    <div class="w-chart">
                                        <div id="hybrid_followers1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12 layout-spacing">
                            <div class="widget widget-one_hybrid widget-engagement">
                                <div class="widget-heading">
                                    <div class="w-title">
                                        <div class="w-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></g></svg>
                                        </div>
                                        <div class="">
                                            <p class="w-value">{{$DemoAgents}}</p>
                                            <h5 class="">T. Influenciadores</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content">
                                    <div class="w-chart">
                                        <div id="hybrid_followers3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Universal -->
    <div class="modal fade modal-xl" id="universalModal" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="universalModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Filtros de Data -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="startDate" class="form-label">Data Inicial</label>
                            <input type="date" class="form-control" id="startDate" name="startDate" onclick="SetPersonalized();">
                        </div>
                        <div class="col-md-4">
                            <label for="endDate" class="form-label">Data Final</label>
                            <input type="date" class="form-control" id="endDate" name="endDate" onclick="SetPersonalized();">
                        </div>
                        <div class="col-md-4">
                            <label for="periodFilter" class="form-label">Período Rápido</label>
                            <select class="form-select" id="periodFilter" name="periodFilter">
                                <option value="">Personalizado</option>
                                <option value="hoje">Hoje</option>
                                <option value="7">Últimos 7 dias</option>
                                <option value="15">Últimos 15 dias</option>
                                <option value="30">Últimos 30 dias</option>
                                <option value="geral">Todos os dados</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="universalTable" class="table table-striped dt-table-hover dataTable" style="width:100%">
                            <thead id="universalTableHead">
                            <!-- Cabeçalho será gerado dinamicamente -->
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #universalTable_paginate .page-link {
            color: white !important;
        }

        /* Spinner de loading para valores dos cards */
        .value-spinner {
            display: inline-block !important;
            width: 30px;
            height: 30px;
            border: 4px solid rgba(76, 175, 80, 0.2);
            border-radius: 50%;
            border-top-color: #4CAF50;
            animation: spin 0.8s linear infinite;
            vertical-align: middle;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
        /* Gráfico */

        function Graficos() {
            var d_1options1 = {
                chart: {
                    height: 300,
                    type: 'area',
                    toolbar: {
                        show: false,
                    }
                },
                colors: ['#622bd7', '#ffbb44'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded',
                        borderRadius: 10,

                    },
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    markers: {
                        width: 10,
                        height: 10,
                        offsetX: -5,
                        offsetY: 0
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 8
                    }
                },
                grid: {
                    borderColor: '#191e3a',
                },
                stroke: {
                    curve: 'smooth'
                },
                series: [{
                    name: 'Direto',
                    data: {{$dadosDirect}}
                }, {
                    name: 'Orgânico',
                    data: {{$dadosOrganico}}
                }],
                xaxis: {
                    categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.3,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 0.8,
                        stops: [0, 100]
                    }
                },
                tooltip: {
                    marker : {
                        show: false,
                    },
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            return val
                        }
                    },
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    }
                },
                responsive: [
                    {
                        breakpoint: 767,
                        options: {
                            plotOptions: {
                                bar: {
                                    borderRadius: 0,
                                    columnWidth: "50%"
                                }
                            }
                        }
                    },
                ]
            }

            /*var d_1C_3 = new ApexCharts(
                document.querySelector("#uniqueVisits"),
                d_1options1
            );
            d_1C_3.render();

            d_1C_3.updateOptions({
                grid: {
                    borderColor: '#191e3a',
                },
            })

            d_1C_3.updateOptions({
                grid: {
                    borderColor: '#e0e6ed',
                },
            })*/
        }

        Graficos();

        function EnableMultiTable(Id){
            $('#' + Id).DataTable({
                "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                "oLanguage": {
                    "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                    "sInfo": "Exibindo página _PAGE_ de _PAGES_",
                    "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    "sSearchPlaceholder": "Procurar...",
                    "sLengthMenu": "Resultados :  _MENU_",
                },

                "stripeClasses": [],
                "lengthMenu": [7, 10, 20, 50],
                "pageLength": 7,
                "ordering": true,
                "order": [],
                "columnDefs": [],

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
        }

        /**
         * Aplica os filtros de data e recarrega a página com os parâmetros
         */
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

            // Mostrar loading no botão
            showLoadingButton(true);

            // Formatar label do período (ex: "25/11 a 25/11" ou "20/11 a 25/11")
            const formattedDateRange = formatPeriodLabel(dataInicial, dataFinal);

            // Atualizar todos os cards (Financeiro, Cassino, Esportes e GGR)
            updateAllCards(dataInicial, dataFinal, formattedDateRange);
        }

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

        /**
         * Adiciona spinner de loading em um elemento (substitui o valor)
         */
        function addLoadingSpinner(elementId) {
            const element = document.getElementById(elementId);
            if (!element) return;
            
            // Salvar o valor original
            element.setAttribute('data-original-value', element.textContent);
            
            // Limpar o conteúdo e adicionar apenas o spinner
            element.textContent = '';
            
            // Criar e adicionar spinner
            const spinner = document.createElement('span');
            spinner.className = 'value-spinner';
            spinner.style.display = 'inline-block';
            
            element.appendChild(spinner);
        }

        /**
         * Remove spinner de loading e restaura o valor atualizado
         */
        function removeLoadingSpinner(elementId) {
            const element = document.getElementById(elementId);
            if (!element) return;
            
            const spinner = element.querySelector('.value-spinner');
            if (spinner) {
                spinner.remove();
            }
            
            // Remove o atributo data (o novo valor já foi definido pelo fetch)
            element.removeAttribute('data-original-value');
        }

        /**
         * Adiciona spinners em todos os valores de uma seção
         */
        function showSectionLoading(section) {
            const ids = {
                'financial': ['cfiin', 'cpixin', 'cfiout', 'csaquesnormais', 'csaquesafiliados', 'cfimanual', 'cmanualdep', 'cfiliq'],
                'casino': ['ccain', 'ccaout', 'ccaliq'],
                'sports': ['cespin', 'cespout', 'cespliq', 'cespabertos'],
                'ggr': ['apostas-perdidas-value', 'apostas-ganhadoras-value', 'ggr-consumido-value']
            };

            if (ids[section]) {
                ids[section].forEach(id => addLoadingSpinner(id));
            }
        }

        /**
         * Remove spinners de todos os valores de uma seção
         */
        function hideSectionLoading(section) {
            const ids = {
                'financial': ['cfiin', 'cpixin', 'cfiout', 'csaquesnormais', 'csaquesafiliados', 'cfimanual', 'cmanualdep', 'cfiliq'],
                'casino': ['ccain', 'ccaout', 'ccaliq'],
                'sports': ['cespin', 'cespout', 'cespliq', 'cespabertos'],
                'ggr': ['apostas-perdidas-value', 'apostas-ganhadoras-value', 'ggr-consumido-value']
            };

            if (ids[section]) {
                ids[section].forEach(id => removeLoadingSpinner(id));
            }
        }

        /**
         * Formata o período para exibir nos cards (ex: "25/11 a 25/11")
         */
        function formatPeriodLabel(dataInicial, dataFinal) {
            const inicio = new Date(dataInicial + 'T00:00:00');
            const fim = new Date(dataFinal + 'T00:00:00');

            const diaInicio = String(inicio.getDate()).padStart(2, '0');
            const mesInicio = String(inicio.getMonth() + 1).padStart(2, '0');
            const diaFim = String(fim.getDate()).padStart(2, '0');
            const mesFim = String(fim.getMonth() + 1).padStart(2, '0');

            return `${diaInicio}/${mesInicio} a ${diaFim}/${mesFim}`;
        }

        /**
         * Atualiza todos os cards (Financeiro, Cassino, Esportes e GGR)
         */
        function updateAllCards(dataInicial, dataFinal, formattedLabel) {
            // Contador de requisições completadas
            let completedRequests = 0;
            const totalRequests = 4;

            const onRequestComplete = () => {
                completedRequests++;
                if (completedRequests === totalRequests) {
                    // Todas as requisições terminaram, remover loading
                    showLoadingButton(false);
                }
            };

            updateFinancialCards(dataInicial, dataFinal, formattedLabel, onRequestComplete);
            updateCasinoCards(dataInicial, dataFinal, formattedLabel, onRequestComplete);
            updateSportsCards(dataInicial, dataFinal, formattedLabel, onRequestComplete);
            updateGgrCards(dataInicial, dataFinal, formattedLabel, onRequestComplete);
        }

        /**
         * Atualiza os cards financeiros
         */
        function updateFinancialCards(dataInicial, dataFinal, formattedLabel, callback) {
            // Mostrar loading
            showSectionLoading('financial');

            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('dataInicial', dataInicial);
            formData.append('dataFinal', dataFinal);

            fetch('/admin/dash/financial-data', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar valores (IDs corretos)
                    document.getElementById('cfiin').textContent = data.data.depositos;
                    document.getElementById('cpixin').textContent = data.data.depositosProcessando;
                    document.getElementById('cfiout').textContent = data.data.saques;
                    document.getElementById('csaquesnormais').textContent = data.data.saquesProcessando;
                    document.getElementById('csaquesafiliados').textContent = data.data.bonus;
                    document.getElementById('cfimanual').textContent = data.data.cpaRewards;
                    document.getElementById('cmanualdep').textContent = data.data.cpaRewards;
                    // Calcular líquido
                    let depositos = parseFloat(data.data.depositos.replace(/\./g, '').replace(',', '.'));
                    let saques = parseFloat(data.data.saques.replace(/\./g, '').replace(',', '.'));
                    let liquido = depositos - saques;
                    document.getElementById('cfiliq').textContent = liquido.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                    
                    // Atualizar labels de período
                    document.getElementById('cperiodofi1').textContent = formattedLabel;
                    document.getElementById('cperiodofi2').textContent = formattedLabel;
                    document.getElementById('cperiodofi3').textContent = formattedLabel;
                    document.getElementById('cperiodofi4').textContent = formattedLabel;
                }
            })
            .catch(error => console.error('Erro ao atualizar financeiro:', error))
            .finally(() => {
                // Remover loading
                hideSectionLoading('financial');
                if (callback) callback();
            });
        }

        /**
         * Atualiza os cards de cassino
         */
        function updateCasinoCards(dataInicial, dataFinal, formattedLabel, callback) {
            // Mostrar loading
            showSectionLoading('casino');

            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('dataInicial', dataInicial);
            formData.append('dataFinal', dataFinal);

            fetch('/admin/dash/casino-data', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar valores (IDs corretos)
                    document.getElementById('ccain').textContent = data.data.apostas;
                    document.getElementById('ccaout').textContent = data.data.premios;
                    document.getElementById('ccaliq').textContent = data.data.liquido;
                    
                    // Atualizar labels de período
                    document.getElementById('cperiodoca1').textContent = formattedLabel;
                    document.getElementById('cperiodoca2').textContent = formattedLabel;
                    document.getElementById('cperiodoca3').textContent = formattedLabel;
                }
            })
            .catch(error => console.error('Erro ao atualizar cassino:', error))
            .finally(() => {
                // Remover loading
                hideSectionLoading('casino');
                if (callback) callback();
            });
        }

        /**
         * Atualiza os cards de esportes
         */
        function updateSportsCards(dataInicial, dataFinal, formattedLabel, callback) {
            // Mostrar loading
            showSectionLoading('sports');

            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('dataInicial', dataInicial);
            formData.append('dataFinal', dataFinal);

            fetch('/admin/dash/sports-data', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar valores (IDs corretos)
                    document.getElementById('cespin').textContent = data.data.apostas;
                    document.getElementById('cespout').textContent = data.data.premios;
                    document.getElementById('cespliq').textContent = data.data.liquido;
                    document.getElementById('cespabertos').textContent = data.data.bilhetesAbertos;
                    
                    // Atualizar labels de período
                    document.getElementById('cperiodoesp1').textContent = formattedLabel;
                    document.getElementById('cperiodoesp2').textContent = formattedLabel;
                    document.getElementById('cperiodoesp3').textContent = formattedLabel;
                    document.getElementById('cperiodoesp4').textContent = formattedLabel;
                }
            })
            .catch(error => console.error('Erro ao atualizar esportes:', error))
            .finally(() => {
                // Remover loading
                hideSectionLoading('sports');
                if (callback) callback();
            });
        }

        /**
         * Atualiza os cards de GGR
         */
        function updateGgrCards(dataInicial, dataFinal, formattedLabel, callback) {
            // Mostrar loading
            showSectionLoading('ggr');

            var ggrSectionSelect = document.getElementById('ggrSectionSelect');
            var ggrSection = ggrSectionSelect ? ggrSectionSelect.value : 'clones';

            var formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('dataInicial', dataInicial);
            formData.append('dataFinal', dataFinal);
            formData.append('provider_cassino', ggrSection);

            fetch('/admin/dash/ggr-data', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const apostasPerdidasEl = document.getElementById('apostas-perdidas-value');
                    const apostasGanhadorasEl = document.getElementById('apostas-ganhadoras-value');
                    const ggrConsumidoEl = document.getElementById('ggr-consumido-value');

                    if (data.data.providerCassino === 'esportes') {
                        // Dados de esportes
                        if (apostasPerdidasEl) apostasPerdidasEl.textContent = data.data.apostasPerdidasSports;
                        if (apostasGanhadorasEl) apostasGanhadorasEl.textContent = data.data.totalPremiosSports;
                        if (ggrConsumidoEl) ggrConsumidoEl.textContent = data.data.ggrConsumidoSports;
                    } else {
                        // Dados de cassino (clones ou originais)
                        if (apostasPerdidasEl) apostasPerdidasEl.textContent = data.data.apostasPerdidasCassino;
                        if (apostasGanhadorasEl) apostasGanhadorasEl.textContent = data.data.apostasGanhadorasCassino;
                        if (ggrConsumidoEl) ggrConsumidoEl.textContent = data.data.ggrConsumidoCassino;
                    }

                    // Atualizar labels de período dos cards GGR
                    const ggrLabels = document.querySelectorAll('.ggr-period-label');
                    ggrLabels.forEach(label => {
                        label.textContent = formattedLabel;
                    });
                }
            })
            .catch(error => console.error('Erro ao atualizar GGR:', error))
            .finally(() => {
                // Remover loading
                hideSectionLoading('ggr');
                if (callback) callback();
            });
        }

        /**
         * Muda a seção ativa do GGR via AJAX
         */
        function changeGgrSection() {
            var dataInicial = document.getElementById('dataInicial').value;
            var dataFinal = document.getElementById('dataFinal').value;
            const formattedDateRange = formatPeriodLabel(dataInicial, dataFinal);

            updateGgrCards(dataInicial, dataFinal, formattedDateRange);
        }

        /**
         * Define período específico para GGR via AJAX
         */

        // Variáveis globais para controle do modal
        let currentModalType = '';
        let currentModalPeriod = '';
        let universalDataTable = null;

        function SetPersonalized() {
            document.getElementById('periodFilter').value = "";
        }

        /**
         * Função universal para abrir modal com dados
         */
        function openUniversalModal(type, period) {
            // Armazenar tipo e período atuais
            currentModalType = type;
            currentModalPeriod = period;
            // Configurações por tipo
            const configs = {
                'pix': {
                    title: 'Depósitos PIX',
                    url: '/admin/dash/pix-transactions',
                    columns: [
                        {data: 'usuario', name: 'usuario'},
                        {data: 'valor', name: 'valor'},
                        {data: 'gateway', name: 'gateway'},
                        {data: 'status', name: 'status'},
                        {data: 'data', name: 'data'}
                    ],
                    headers: ['Usuário', 'Valor', 'Gateway', 'Status', 'Data']
                },
                'manual': {
                    title: 'Saldo Manual',
                    url: '/admin/dash/manual-transactions',
                    columns: [
                        {data: 'usuario', name: 'usuario'},
                        {data: 'valor', name: 'valor'},
                        {data: 'admin', name: 'admin'},
                        {data: 'observacao', name: 'observacao'},
                        {data: 'data', name: 'data'}
                    ],
                    headers: ['Usuário', 'Valor', 'Admin', 'Observação', 'Data']
                },
                'saques': {
                    title: 'Saques Normais',
                    url: '/admin/dash/normal-withdrawals',
                    columns: [
                        {data: 'usuario', name: 'usuario'},
                        {data: 'valor', name: 'valor'},
                        {data: 'gateway', name: 'gateway'},
                        {data: 'status', name: 'status'},
                        {data: 'data', name: 'data'}
                    ],
                    headers: ['Usuário', 'Valor', 'Gateway', 'Status', 'Data']
                },
                'afiliados': {
                    title: 'Saques de Afiliados',
                    url: '/admin/dash/affiliate-withdrawals',
                    columns: [
                        {data: 'usuario', name: 'usuario'},
                        {data: 'valor', name: 'valor'},
                        {data: 'gateway', name: 'gateway'},
                        {data: 'tipo', name: 'tipo'},
                        {data: 'status', name: 'status'},
                        {data: 'data', name: 'data'}
                    ],
                    headers: ['Usuário', 'Valor', 'Gateway', 'Tipo', 'Status', 'Data']
                }
            };

            const config = configs[type];
            if (!config) return;

            // Atualizar título do modal
            $('#universalModalLabel').text(config.title + ' - ' + getPeriodLabel(period));

            // Atualizar cabeçalho da tabela
            let headerHtml = '<tr>';
            config.headers.forEach(header => {
                headerHtml += '<th>' + header + '</th>';
            });
            headerHtml += '</tr>';
            $('#universalTableHead').html(headerHtml);

            // Inicializar filtros de data
            initializeDateFilters(period);

            // Destruir tabela existente se houver
            if ($.fn.DataTable.isDataTable('#universalTable')) {
                $('#universalTable').DataTable().destroy();
            }

            // Inicializar DataTable (apenas uma vez por abertura de modal)
            universalDataTable = $('#universalTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: config.url,
                    data: function(d) {
                        d.period = $('#periodFilter').val();
                        d.start_date = $('#startDate').val();
                        d.end_date = $('#endDate').val();
                    }
                },
                columns: config.columns,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    paginate: {
                        first: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>',
                        previous: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        next: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                        last: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>'
                    },
                    info: "Mostrando página _PAGE_ de _PAGES_"
                },
                order: [[config.columns.length - 1, 'desc']],
                pageLength: 10,
                responsive: true,

                drawCallback: function(settings) {
                    // Adicionar classes ao paginador
                    $('#universalTable_paginate').addClass('paging_simple_numbers');
                    $('#universalTable_paginate ul.pagination li').addClass('paginate_button page-item');
                    $('#universalTable_paginate ul.pagination li.previous').attr('id', 'universalTable_previous');
                    $('#universalTable_paginate ul.pagination li.next').attr('id', 'universalTable_next');
                    $('#universalTables_paginate ul.pagination li.first').attr('id', 'universalTable_first');
                    $('#universalTable_paginate ul.pagination li.last').attr('id', 'universalTable_last');
                    $('#universalTable_paginate ul.pagination li a').addClass('page-link');

                    // Substituir o texto dos botões de paginação por ícones SVG
                    $('#universalTable_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                    $('#universalTable_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                    $('#universalTable_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                    $('#universalTable_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');

                    // Melhorar o seletor de quantidade por página
                    $('.dataTables_length select').addClass('form-select form-select-sm');
                    $('.dataTables_length label').addClass('form-label fw-bold');

                    // Garantir que o lengthMenu seja visível
                    if ($('.dataTables_length').length === 0) {
                        var lengthHtml = '<div class="dataTables_length">' +
                            '<label>Mostrar <select class="form-select form-select-sm">' +
                            '<option value="5">5</option>' +
                            '<option value="15">15</option>' +
                            '<option value="20" selected>20</option>' +
                            '<option value="50">50</option>' +
                            '<option value="100">100</option>' +
                            '</select> registros por página</label>' +
                            '</div>';
                        $('.dt--bottom-section').prepend(lengthHtml);
                    }

                    // Inicializar os tooltips do Bootstrap
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                },
                initComplete: function() {
                    // Adiciona um evento para recarregar a tabela quando ocorrer um erro
                    $('#universalTable').on('error.dt', function(e, settings, techNote, message) {
                    });

                    $('.dataTables_processing').html('<div class="" role="status" style="color: white;">Processando...</div>');

                    // Remover campo de busca gerado automaticamente
                    $('.dataTables_filter').remove();
                }
            });

            // Adicionar listeners para recarregar ao alterar inputs (sem destruir/recriar a tabela)
            $('#startDate, #endDate, #periodFilter').off('change').on('change', function() {
                if (universalDataTable) {
                    universalDataTable.ajax.reload();
                }
            });
        }

        /**
         * Função auxiliar para obter o label do período
         */
        function getPeriodLabel(period) {
            const labels = {
                'hoje': 'Hoje',
                '7': '7 Dias',
                '15': '15 Dias',
                '30': '30 Dias',
                'geral': 'Geral'
            };
            return labels[period] || period;
        }

        /**
         * Inicializar filtros de data baseado no período
         */
        function initializeDateFilters(period) {
            const today = new Date();
            let startDate = null;
            let endDate = today.toISOString().split('T')[0];

            // Definir data inicial baseada no período
            switch(period) {
                case 'hoje':
                    startDate = endDate;
                    break;
                case '7':
                    startDate = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                    break;
                case '15':
                    startDate = new Date(today.getTime() - 15 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                    break;
                case '30':
                    startDate = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                    break;
                case 'geral':
                    startDate = '';
                    endDate = '';
                    break;
            }

            // Definir valores nos campos
            $('#startDate').val(startDate || '');
            $('#endDate').val(endDate || '');
            $('#periodFilter').val(period);
        }

        /**
         * Aplicar filtro de data
         */
        function applyDateFilter() {
            if (universalDataTable) {
                universalDataTable.ajax.reload();
            }
        }

        /**
         * Limpar filtros de data
         */
        function clearDateFilter() {
            $('#startDate').val('');
            $('#endDate').val('');
            $('#periodFilter').val('');

            if (universalDataTable) {
                universalDataTable.ajax.reload();
            }
        }

        /**
         * Manipular mudança no seletor de período rápido

         $(document).ready(function() {
         // Ao alterar qualquer input, recarregar a tabela
         $('#startDate, #endDate, #periodFilter').on('change', function() {
         if (universalDataTable) {
         universalDataTable.ajax.reload();
         }
         });
         });*/


        // Inicialização simples e rápida
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tooltips do Bootstrap se disponível
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });
    </script>
@endsection

