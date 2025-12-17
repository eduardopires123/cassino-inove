@extends('admin.layouts.app')

@section('title', 'Estatísticas - Raspadinha')

@section('content')
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.raspadinha.index') }}">Raspadinhas</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Estatísticas</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8" style="padding:20px;">
                    <div class="row mb-4">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Estatísticas das Raspadinhas</h4>
                            <a href="{{ route('admin.raspadinha.index') }}" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> Voltar
                            </a>
                        </div>
                    </div>

                    <!-- Cards de Estatísticas Gerais -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="icon-circle bg-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play-circle text-white"><circle cx="12" cy="12" r="10"></circle><polygon points="10,8 16,12 10,16 10,8"></polygon></svg>
                                        </div>
                                    </div>
                                    <h3 class="mb-1">{{ number_format($stats['total_games']) }}</h3>
                                    <p class="text-muted mb-0">Total de Jogadas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="icon-circle bg-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign text-white"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                        </div>
                                    </div>
                                    <h3 class="mb-1">R$ {{ number_format($stats['total_revenue'], 2, ',', '.') }}</h3>
                                    <p class="text-muted mb-0">Receita Total</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="icon-circle bg-warning">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-gift text-white"><polyline points="20,12 20,22 4,22 4,12"></polyline><rect x="2" y="7" width="20" height="5"></rect><line x1="12" y1="22" x2="12" y2="7"></line><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"></path><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"></path></svg>
                                        </div>
                                    </div>
                                    <h3 class="mb-1">R$ {{ number_format($stats['total_prizes'], 2, ',', '.') }}</h3>
                                    <p class="text-muted mb-0">Prêmios Pagos</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="d-flex justify-content-center mb-3">
                                        <div class="icon-circle {{ $stats['profit'] >= 0 ? 'bg-info' : 'bg-danger' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up text-white"><polyline points="23,6 13.5,15.5 8.5,10.5 1,18"></polyline><polyline points="17,6 23,6 23,12"></polyline></svg>
                                        </div>
                                    </div>
                                    <h3 class="mb-1 {{ $stats['profit'] >= 0 ? 'text-info' : 'text-danger' }}">
                                        R$ {{ number_format($stats['profit'], 2, ',', '.') }}
                                    </h3>
                                    <p class="text-muted mb-0">Lucro Total</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cards de Estatísticas Diárias -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h4 class="text-primary">{{ number_format($stats['today_games']) }}</h4>
                                    <p class="text-muted mb-0">Jogadas Hoje</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h4 class="text-success">R$ {{ number_format($stats['today_revenue'], 2, ',', '.') }}</h4>
                                    <p class="text-muted mb-0">Receita Hoje</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h4 class="text-info">{{ number_format($stats['active_raspadinhas']) }}</h4>
                                    <p class="text-muted mb-0">Raspadinhas Ativas</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Top Winners -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award me-2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21,13.89 7,23 12,20 17,23 15.79,13.88"></polyline></svg>
                                        Top 10 Ganhadores
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($topWinners->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Pos.</th>
                                                        <th>Usuário</th>
                                                        <th>Total Ganho</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($topWinners as $index => $winner)
                                                    <tr>
                                                        <td>
                                                            @if($index == 0)
                                                                <span class="badge badge-warning">🥇</span>
                                                            @elseif($index == 1)
                                                                <span class="badge badge-light">🥈</span>
                                                            @elseif($index == 2)
                                                                <span class="badge badge-secondary">🥉</span>
                                                            @else
                                                                <span class="badge badge-light">{{ $index + 1 }}º</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($winner->user)
                                                                <strong>{{ $winner->user->name }}</strong><br>
                                                                <small class="text-muted">{{ $winner->user->email }}</small>
                                                            @else
                                                                <span class="text-muted">Usuário removido</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-success">
                                                                R$ {{ number_format($winner->total_won, 2, ',', '.') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users text-muted mb-2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                            <p class="text-muted">Nenhum ganhador ainda</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Raspadinhas Mais Populares -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up me-2"><polyline points="23,6 13.5,15.5 8.5,10.5 1,18"></polyline><polyline points="17,6 23,6 23,12"></polyline></svg>
                                        Raspadinhas Mais Populares
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($popularRaspadinhas->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Pos.</th>
                                                        <th>Raspadinha</th>
                                                        <th>Jogadas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($popularRaspadinhas as $index => $popular)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-primary">{{ $index + 1 }}º</span>
                                                        </td>
                                                        <td>
                                                            @if($popular->raspadinha)
                                                                <strong>{{ $popular->raspadinha->name }}</strong><br>
                                                                <small class="text-muted">
                                                                    Normal: R$ {{ number_format($popular->raspadinha->price, 2, ',', '.') }} | 
                                                                    Turbo: R$ {{ number_format($popular->raspadinha->turbo_price, 2, ',', '.') }}
                                                                </small>
                                                            @else
                                                                <span class="text-muted">Raspadinha removida</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-info">
                                                                {{ number_format($popular->total_plays) }} jogadas
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart text-muted mb-2"><line x1="12" y1="20" x2="12" y2="10"></line><line x1="18" y1="20" x2="18" y2="4"></line><line x1="6" y1="20" x2="6" y2="16"></line></svg>
                                            <p class="text-muted">Nenhuma jogada ainda</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
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
    // Inicialização do gerenciador de modais quando o documento estiver pronto
    ModalManager.init();
    
    // Verificar se há mensagem de sucesso na sessão e exibir toast
    @if(session('success'))
        ToastManager.success("{{ session('success') }}");
    @endif
    
    // Verificar se há mensagem de erro na sessão e exibir toast
    @if(session('error'))
        ToastManager.error("{{ session('error') }}");
    @endif
});
</script>

<style>
.icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
</style>
@endpush 