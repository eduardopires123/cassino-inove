@extends('admin.layouts.layout')

@section('title', 'Resgates da Roleta')

@section('content')
<div class="layout-px-spacing">
    <div class="middle-content container-xxl p-0">
        <!-- BREADCRUMB -->
        <div class="page-meta">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.roulette.config') }}">Configuração Roleta</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Resgates</li>
                </ol>
            </nav>
        </div>
        <!-- /BREADCRUMB -->

        <div class="row layout-top-spacing">
            <!-- Estatísticas -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4 class="mb-4">📊 Estatísticas da Roleta</h4>
                        </div>
                        
                        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total de Giros</h5>
                                    <h3 class="mb-0">{{ number_format($stats['total_spins']) }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Giros Grátis</h5>
                                    <h3 class="mb-0">{{ number_format($stats['total_free_spins']) }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Giros Pagos</h5>
                                    <h3 class="mb-0">{{ number_format($stats['total_paid_spins']) }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Prêmios Dados</h5>
                                    <h3 class="mb-0">R$ {{ number_format($stats['total_prizes_awarded'], 2, ',', '.') }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Giros Hoje</h5>
                                    <h3 class="mb-0">{{ number_format($stats['spins_today']) }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-4 col-md-6 col-sm-6 mb-4">
                            <div class="card bg-dark text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Usuários Únicos</h5>
                                    <h3 class="mb-0">{{ number_format($stats['unique_users']) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros e Tabela -->
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4>🎰 Histórico de Resgates</h4>
                                <div>
                                    <a href="{{ route('admin.roulette.config') }}" class="btn btn-secondary btn-sm me-2">
                                        <i class="fas fa-cog"></i> Configurações
                                    </a>
                                    <button type="button" class="btn btn-success btn-sm" onclick="exportData()">
                                        <i class="fas fa-download"></i> Exportar CSV
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">🔍 Filtros</h6>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="{{ route('admin.roulette.resgates') }}" id="filterForm">
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label for="user_name" class="form-label">Nome do Usuário</label>
                                                <input type="text" class="form-control" id="user_name" name="user_name" 
                                                       value="{{ request('user_name') }}" placeholder="Digite o nome...">
                                            </div>
                                            
                                            <div class="col-md-3 mb-3">
                                                <label for="item_name" class="form-label">Nome do Item</label>
                                                <input type="text" class="form-control" id="item_name" name="item_name" 
                                                       value="{{ request('item_name') }}" placeholder="Nome do prêmio...">
                                            </div>
                                            
                                            <div class="col-md-2 mb-3">
                                                <label for="prize_type" class="form-label">Tipo de Prêmio</label>
                                                <select class="form-select" id="prize_type" name="prize_type">
                                                    <option value="">Todos</option>
                                                    <option value="free_spins" {{ request('prize_type') == 'free_spins' ? 'selected' : '' }}>Giros Grátis</option>
                                                    <option value="money" {{ request('prize_type') == 'money' ? 'selected' : '' }}>Dinheiro</option>
                                                    <option value="coupon" {{ request('prize_type') == 'coupon' ? 'selected' : '' }}>Cupom</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-2 mb-3">
                                                <label for="is_free_spin" class="form-label">Giro Grátis</label>
                                                <select class="form-select" id="is_free_spin" name="is_free_spin">
                                                    <option value="">Todos</option>
                                                    <option value="1" {{ request('is_free_spin') == '1' ? 'selected' : '' }}>Sim</option>
                                                    <option value="0" {{ request('is_free_spin') == '0' ? 'selected' : '' }}>Não</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-2 mb-3">
                                                <label for="date_from" class="form-label">Data Inicial</label>
                                                <input type="date" class="form-control" id="date_from" name="date_from" 
                                                       value="{{ request('date_from') }}">
                                            </div>
                                            
                                            <div class="col-md-2 mb-3">
                                                <label for="date_to" class="form-label">Data Final</label>
                                                <input type="date" class="form-control" id="date_to" name="date_to" 
                                                       value="{{ request('date_to') }}">
                                            </div>
                                            
                                            <div class="col-md-10 mb-3 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary me-2">
                                                    <i class="fas fa-search"></i> Filtrar
                                                </button>
                                                <a href="{{ route('admin.roulette.resgates') }}" class="btn btn-secondary">
                                                    <i class="fas fa-times"></i> Limpar
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Usuário</th>
                                    <th>Item</th>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                    <th>Cupom</th>
                                    <th>Giro Grátis</th>
                                    <th>Data/Hora</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($spins as $spin)
                                <tr>
                                    <td><strong>#{{ $spin->id }}</strong></td>
                                    <td>
                                        @if($spin->user)
                                            <span class="badge bg-primary">{{ $spin->user->name }}</span>
                                        @else
                                            <span class="badge bg-secondary">Usuário Convidado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $spin->item_name }}</strong>
                                        @if($spin->rouletteItem)
                                            <br><small class="text-muted">ID: {{ $spin->rouletteItem->id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($spin->prize_type)
                                            @case('free_spins')
                                                <span class="badge bg-success">🎮 Giros Grátis</span>
                                                @break
                                            @case('money')
                                                <span class="badge bg-warning">💰 Dinheiro</span>
                                                @break
                                            @case('coupon')
                                                <span class="badge bg-info">🎟️ Cupom</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $spin->prize_type }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($spin->prize_awarded > 0)
                                            <strong class="text-success">R$ {{ number_format($spin->prize_awarded, 2, ',', '.') }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($spin->coupon_code)
                                            <code class="bg-light px-2 py-1 rounded">{{ $spin->coupon_code }}</code>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($spin->is_free_spin)
                                            <span class="badge bg-success">✅ Sim</span>
                                        @else
                                            <span class="badge bg-warning">❌ Não</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            {{ $spin->created_at->format('d/m/Y') }}<br>
                                            {{ $spin->created_at->format('H:i:s') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($spin->ip_address)
                                            <small class="text-muted">{{ $spin->ip_address }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Nenhum resgate encontrado</h5>
                                            <p class="text-muted">Tente ajustar os filtros ou aguarde novos giros na roleta.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    @if($spins->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $spins->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportData() {
    // Pegar parâmetros do formulário de filtro
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    // Redirecionar para a rota de export com os mesmos parâmetros
    window.location.href = '{{ route("admin.roulette.export") }}?' + params.toString();
}

// Auto-submit do formulário quando campos de data mudam
document.addEventListener('DOMContentLoaded', function() {
    const dateInputs = document.querySelectorAll('#date_from, #date_to');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Opcional: auto-submit quando datas mudam
            // document.getElementById('filterForm').submit();
        });
    });
});
</script>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.badge {
    font-size: 0.75em;
}

.table th {
    font-weight: 600;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
}

code {
    font-size: 0.8rem;
}

.btn {
    border-radius: 0.375rem;
}

.form-control, .form-select {
    border-radius: 0.375rem;
}
</style>
@endsection 