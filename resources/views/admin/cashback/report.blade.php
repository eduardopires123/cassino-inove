@extends('admin.layouts.app')
@section('content')
@php
    use Carbon\Carbon;
    
    // Formatar dados para exibição
    $startDate = $startDate ?? Carbon::now()->subMonth()->startOfDay();
    $endDate = $endDate ?? Carbon::now()->endOfDay();
    
    $formattedStartDate = $startDate->format('Y-m-d');
    $formattedEndDate = $endDate->format('Y-m-d');
    
    // Inicializa o relatório com valores padrão se não estiver definido
    if (!isset($report)) {
        $report = [
            'total_cashbacks' => 0,
            'total_amount' => 0,
            'by_type' => [
                'sports' => ['count' => 0, 'amount' => 0],
                'virtual' => ['count' => 0, 'amount' => 0],
                'all' => ['count' => 0, 'amount' => 0]
            ],
            'by_status' => [
                'pending' => ['count' => 0, 'amount' => 0],
                'credited' => ['count' => 0, 'amount' => 0],
                'expired' => ['count' => 0, 'amount' => 0]
            ],
            'loss_origins' => [
                'sports' => 0,
                'virtual' => 0
            ]
        ];
    }
@endphp
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <!-- Cabeçalho da página -->
        <div class="page-header d-flex justify-content-between align-items-center mb-4" style="margin-top:45px;">
            <div>
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Cashback</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Relatório</li>
                    </ol>
                </nav>
                <h2 class="page-title mt-2">Relatório de Cashback</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-body p-4">
                        <form method="GET" action="{{ route('admin.cashback.report') }}" class="mb-0">
                            <div class="row align-items-end">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <label for="start_date" class="form-label fw-bold">Data Inicial:</label>
                                    <input type="date" id="start_date" name="start_date" class="form-control form-control-lg shadow-sm" value="{{ $formattedStartDate }}">
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <label for="end_date" class="form-label fw-bold">Data Final:</label>
                                    <input type="date" id="end_date" name="end_date" class="form-control form-control-lg shadow-sm" value="{{ $formattedEndDate }}">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 h-55 d-flex align-items-center justify-content-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2 me-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                                        Gerar Relatório
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                        
                <!-- Resumo Geral -->
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header py-3 px-4 border-0">
                        <h5 class="card-title mb-0 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart me-2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                            Resumo Geral
                        </h5>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 h-100 rounded-3 shadow-hover bg-light-primary bg-opacity-50">
                                    <div class="card-body text-center p-4">
                                        <div class="icon-box mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-25" style="width:60px; height:60px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users text-primary"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        </div>
                                        <h6 class="card-subtitle mb-2 text-muted">Total de Cashbacks</h6>
                                        <h3 class="card-title text-primary mb-0">{{ number_format($report['total_cashbacks'], 0, ',', '.') }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 h-100 rounded-3 shadow-hover bg-light-success bg-opacity-50">
                                    <div class="card-body text-center p-4">
                                        <div class="icon-box mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-25" style="width:60px; height:60px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign text-success"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                        </div>
                                        <h6 class="card-subtitle mb-2 text-muted">Valor Total</h6>
                                        <h3 class="card-title text-success mb-0">R$ {{ number_format($report['total_amount'], 2, ',', '.') }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card border-0 h-100 rounded-3 shadow-hover bg-light-danger bg-opacity-50">
                                    <div class="card-body text-center p-4">
                                        <div class="icon-box mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-25" style="width:60px; height:60px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-down text-danger"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
                                        </div>
                                        <h6 class="card-subtitle mb-2 text-muted">Total de Perdas</h6>
                                        <h3 class="card-title text-danger mb-0">R$ {{ number_format(($report['loss_origins']['sports'] + $report['loss_origins']['virtual']), 2, ',', '.') }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                        
                <!-- Detalhes por Tipo e Status -->
                <div class="row">
                    <!-- Por Tipo -->
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                        <div class="card shadow-sm border-0 h-100 rounded-3">
                            <div class="card-header py-3 px-4 border-0">
                                <h5 class="card-title mb-0 d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid me-2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                    Detalhamento por Tipo de Jogo
                                </h5>
                            </div>
                            
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th class="py-3 ps-4">Tipo</th>
                                                <th class="py-3">Quantidade</th>
                                                <th class="py-3 pe-4">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="py-3 ps-4 fw-bold text-primary">
                                                    <div class="d-flex align-items-center">
                                                        <span class="icon-box d-flex align-items-center justify-content-center me-2 rounded-circle bg-primary bg-opacity-10" style="width:32px; height:32px;">
                                                            <i class="fas fa-futbol"></i>
                                                        </span>
                                                        Esportes
                                                    </div>
                                                </td>
                                                <td class="py-3">{{ number_format($report['by_type']['sports']['count'], 0, ',', '.') }}</td>
                                                <td class="py-3 pe-4 fw-bold">R$ {{ number_format($report['by_type']['sports']['amount'], 2, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="py-3 ps-4 fw-bold text-success">
                                                    <div class="d-flex align-items-center">
                                                        <span class="icon-box d-flex align-items-center justify-content-center me-2 rounded-circle bg-success bg-opacity-10" style="width:32px; height:32px;">
                                                            <i class="fas fa-gamepad"></i>
                                                        </span>
                                                        Cassino
                                                    </div>
                                                </td>
                                                <td class="py-3">{{ number_format($report['by_type']['virtual']['count'], 0, ',', '.') }}</td>
                                                <td class="py-3 pe-4 fw-bold">R$ {{ number_format($report['by_type']['virtual']['amount'], 2, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="py-3 ps-4 fw-bold">
                                                    <div class="d-flex align-items-center">
                                                        <span class="icon-box d-flex align-items-center justify-content-center me-2 rounded-circle bg-dark bg-opacity-10" style="width:32px; height:32px;">
                                                            <i class="fas fa-globe"></i>
                                                        </span>
                                                        Todos
                                                    </div>
                                                </td>
                                                <td class="py-3 fw-bold">{{ number_format($report['by_type']['all']['count'], 0, ',', '.') }}</td>
                                                <td class="py-3 pe-4 fw-bold">R$ {{ number_format($report['by_type']['all']['amount'], 2, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Por Status -->
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                        <div class="card shadow-sm border-0 h-100 rounded-3">
                            <div class="card-header py-3 px-4 border-0">
                                <h5 class="card-title mb-0 d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock me-2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    Detalhamento por Status
                                </h5>
                            </div>
                            
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th class="py-3 ps-4">Status</th>
                                                <th class="py-3">Quantidade</th>
                                                <th class="py-3 pe-4">Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="py-3 ps-4 fw-bold text-warning">
                                                    <div class="d-flex align-items-center">
                                                        <span class="icon-box d-flex align-items-center justify-content-center me-2 rounded-circle bg-warning bg-opacity-10" style="width:32px; height:32px;">
                                                            <i class="fas fa-clock"></i>
                                                        </span>
                                                        Pendente
                                                    </div>
                                                </td>
                                                <td class="py-3">{{ number_format($report['by_status']['pending']['count'], 0, ',', '.') }}</td>
                                                <td class="py-3 pe-4 fw-bold">R$ {{ number_format($report['by_status']['pending']['amount'], 2, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="py-3 ps-4 fw-bold text-success">
                                                    <div class="d-flex align-items-center">
                                                        <span class="icon-box d-flex align-items-center justify-content-center me-2 rounded-circle bg-success bg-opacity-10" style="width:32px; height:32px;">
                                                            <i class="fas fa-check-circle"></i>
                                                        </span>
                                                        Creditado
                                                    </div>
                                                </td>
                                                <td class="py-3">{{ number_format($report['by_status']['credited']['count'], 0, ',', '.') }}</td>
                                                <td class="py-3 pe-4 fw-bold">R$ {{ number_format($report['by_status']['credited']['amount'], 2, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="py-3 ps-4 fw-bold text-danger">
                                                    <div class="d-flex align-items-center">
                                                        <span class="icon-box d-flex align-items-center justify-content-center me-2 rounded-circle bg-danger bg-opacity-10" style="width:32px; height:32px;">
                                                            <i class="fas fa-times-circle"></i>
                                                        </span>
                                                        Expirado
                                                    </div>
                                                </td>
                                                <td class="py-3">{{ number_format($report['by_status']['expired']['count'], 0, ',', '.') }}</td>
                                                <td class="py-3 pe-4 fw-bold">R$ {{ number_format($report['by_status']['expired']['amount'], 2, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Origem das Perdas -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header py-3 px-4 border-0">
                        <h5 class="card-title mb-0 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity me-2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                            Origem das Perdas
                        </h5>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card border-start border-primary border-4 shadow-sm h-100 rounded-3">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-9">
                                                <h6 class="text-uppercase text-muted mb-2 small">Apostas Esportivas</h6>
                                                <h4 class="mb-1 fw-bold">R$ {{ number_format($report['loss_origins']['sports'], 2, ',', '.') }}</h4>
                                                @php
                                                    $totalLoss = $report['loss_origins']['sports'] + $report['loss_origins']['virtual'];
                                                    $sportsPercentage = $totalLoss > 0 ? ($report['loss_origins']['sports'] / $totalLoss) * 100 : 0;
                                                @endphp
                                                <div class="text-primary small">{{ number_format($sportsPercentage, 1) }}% do total</div>
                                            </div>
                                            <div class="col-3 text-center">
                                                <div class="icon-box mx-auto d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width:60px; height:60px;">
                                                    <i class="fas fa-futbol fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <div class="card border-start border-success border-4 shadow-sm h-100 rounded-3">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-9">
                                                <h6 class="text-uppercase text-muted mb-2 small">Cassino</h6>
                                                <h4 class="mb-1 fw-bold">R$ {{ number_format($report['loss_origins']['virtual'], 2, ',', '.') }}</h4>
                                                @php
                                                    $virtualPercentage = $totalLoss > 0 ? ($report['loss_origins']['virtual'] / $totalLoss) * 100 : 0;
                                                @endphp
                                                <div class="text-success small">{{ number_format($virtualPercentage, 1) }}% do total</div>
                                            </div>
                                            <div class="col-3 text-center">
                                                <div class="icon-box mx-auto d-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10" style="width:60px; height:60px;">
                                                    <i class="fas fa-gamepad fa-2x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="card shadow-sm border-0 rounded-3">
                                    <div class="card-body p-4">
                                        <h6 class="text-muted mb-3">Distribuição de Perdas</h6>
                                        <div class="progress overflow-visible rounded-pill" style="height: 24px; background-color: rgba(0,0,0,0.05);">
                                            <div class="progress-bar bg-primary position-relative" role="progressbar" style="width: {{ $sportsPercentage }}%; border-radius: 30px 0 0 30px;" aria-valuenow="{{ $sportsPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                                <span class="position-absolute start-50 top-50 translate-middle text-white">{{ number_format($sportsPercentage, 1) }}% Esportes</span>
                                            </div>
                                            <div class="progress-bar bg-success position-relative" role="progressbar" style="width: {{ $virtualPercentage }}%; border-radius: 0 30px 30px 0;" aria-valuenow="{{ $virtualPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                                <span class="position-absolute start-50 top-50 translate-middle text-white">{{ number_format($virtualPercentage, 1) }}% Virtuais</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    $(function () {
        // Código JavaScript adicional para relatórios, se necessário
    });
</script>
@endpush

@endsection 