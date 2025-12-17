@extends('admin.layouts.app')
@section('content')
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Cupons de Bônus</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Histórico de Resgates</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <div class="row" style="margin-bottom: -20px; padding:15px;">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <h6>Cupom: {{ $coupon->code }}</h6>
                                <div>
                                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                        Voltar para Lista
                                    </a>
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                        Editar Cupom
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Informações do Cupom</h5>
                                        <p><strong>Código:</strong> <span class="copy-to-clipboard" data-clipboard-text="{{ $coupon->code }}" style="cursor: pointer;" title="Clique para copiar">{{ $coupon->code }}</span></p>
                                        <p><strong>Descrição:</strong> {{ $coupon->description ?? 'N/A' }}</p>
                                        <p><strong>Tipo:</strong> 
                                            @if($coupon->type == 'balance')
                                                <span class="badge badge-light-success">Saldo Real</span>
                                            @else
                                                <span class="badge badge-light-info">Bônus</span>
                                            @endif
                                        </p>
                                        <p><strong>Valor:</strong> R$ {{ number_format($coupon->amount, 2, ',', '.') }}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge badge-{{ $coupon->isValid() ? 'light-success' : 'light-danger' }} mb-2 me-4">
                                                {{ $coupon->isValid() ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Estatísticas</h5>
                                        <p><strong>Total de Resgates:</strong> {{ $redemptions->total() }}</p>
                                        <p><strong>Limite de Usos:</strong> {{ $coupon->max_usages }}</p>
                                        <p><strong>Usos Restantes:</strong> {{ max(0, $coupon->max_usages - $coupon->used_count) }}</p>
                                        <p><strong>Criado em:</strong> {{ $coupon->created_at->format('d/m/Y H:i') }}</p>
                                        <p><strong>Validade:</strong> 
                                            @if($coupon->valid_from && $coupon->valid_until)
                                                {{ $coupon->valid_from->format('d/m/Y') }} até {{ $coupon->valid_until->format('d/m/Y') }}
                                            @elseif($coupon->valid_from)
                                                A partir de {{ $coupon->valid_from->format('d/m/Y') }}
                                            @elseif($coupon->valid_until)
                                                Até {{ $coupon->valid_until->format('d/m/Y') }}
                                            @else
                                                Sem data limite
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped dt-table-hover dataTable" id="redemptions-table" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th>ID</th>
                                        <th>Usuário</th>
                                        <th>Email</th>
                                        <th>Valor</th>
                                        <th>Data do Resgate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($redemptions as $redemption)
                                        <tr>
                                            <td>{{ $redemption->id }}</td>
                                            <td>
                                                {{ $redemption->user->name ?? 'Usuário #' . $redemption->user_id }}
                                            </td>
                                            <td>{{ $redemption->user->email ?? 'N/A' }}</td>
                                            <td>R$ {{ number_format($redemption->amount, 2, ',', '.') }}</td>
                                            <td>{{ $redemption->redeemed_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    @endforeach

                                    @if($redemptions->isEmpty())
                                        <tr>
                                            <td colspan="5" class="text-center">Nenhum resgate encontrado para este cupom</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuário</th>
                                        <th>Email</th>
                                        <th>Valor</th>
                                        <th>Data do Resgate</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        {{ $redemptions->links() }}
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
        // Copiar código do cupom ao clicar
        $('.copy-to-clipboard').on('click', function() {
            const text = $(this).data('clipboard-text');
            navigator.clipboard.writeText(text)
                .then(() => {
                    ToastManager.success(`Código "${text}" copiado para a área de transferência!`);
                })
                .catch(err => {
                    console.error('Erro ao copiar texto: ', err);
                    ToastManager.error("Falha ao copiar o código");
                });
        });
        
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
@endpush 