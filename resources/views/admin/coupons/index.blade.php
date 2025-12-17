@extends('admin.layouts.app')
@section('content')
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Cupons de Bônus</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <div class="row" style="margin-bottom: -20px; padding:15px;">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <h6></h6>
                                <a href="{{ route('admin.coupons.create') }}" class="btn btn-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Novo Cupom
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped dt-table-hover dataTable" id="zero-config" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th>Código</th>
                                        <th>Descrição</th>
                                        <th>Tipo</th>
                                        <th>Valor</th>
                                        <th>Usos</th>
                                        <th>Validade</th>
                                        <th>Status</th>
                                        <th style="width: 120px">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coupons as $coupon)
                                        <tr>
                                            <td class="copy-to-clipboard" data-clipboard-text="{{ $coupon->code }}" style="cursor: pointer;" title="Clique para copiar">{{ $coupon->code }}</td>
                                            <td>{{ $coupon->description ?? '-' }}</td>
                                            <td>
                                                @if($coupon->type == 'balance')
                                                    <span class="badge badge-light-success">Saldo Real</span>
                                                @else
                                                    <span class="badge badge-light-info">Bônus</span>
                                                @endif
                                            </td>
                                            <td>R$ {{ number_format($coupon->amount, 2, ',', '.') }}</td>
                                            <td>{{ $coupon->used_count }}/{{ $coupon->max_usages }}</td>
                                            <td>
                                                @if($coupon->valid_from && $coupon->valid_until)
                                                    {{ $coupon->valid_from->format('d/m/Y') }} até {{ $coupon->valid_until->format('d/m/Y') }}
                                                @elseif($coupon->valid_from)
                                                    A partir de {{ $coupon->valid_from->format('d/m/Y') }}
                                                @elseif($coupon->valid_until)
                                                    Até {{ $coupon->valid_until->format('d/m/Y') }}
                                                @else
                                                    Sem data limite
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $coupon->isValid() ? 'light-success' : 'light-danger' }} mb-2 me-4">
                                                    {{ $coupon->isValid() ? 'Ativo' : 'Inativo' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="badge badge-light-primary text-start me-2 action-edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                                </a>
                                                <a href="{{ route('admin.coupons.redemptions', $coupon->id) }}" class="badge badge-light-info text-start me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                                </a>
                                                <button class="badge badge-light-danger text-start action-delete" data-id="{{ $coupon->id }}" data-code="{{ $coupon->code }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @if($coupons->isEmpty())
                                        <tr>
                                            <td colspan="9" class="text-center">Nenhum cupom encontrado</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descrição</th>
                                        <th>Tipo</th>
                                        <th>Valor</th>
                                        <th>Usos</th>
                                        <th>Validade</th>
                                        <th>Status</th>
                                        <th>Ações</th>
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

<!-- Formulário oculto para exclusão -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
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

        // Lidar com cliques no botão de exclusão
        $('.action-delete').on('click', function() {
            const couponId = $(this).data('id');
            const couponCode = $(this).data('code');
            
            // Mostrar modal de confirmação
            ModalManager.showConfirmation(
                'Excluir Cupom',
                `Tem certeza que deseja excluir o cupom "${couponCode}"? Esta ação não pode ser desfeita.`,
                function() {
                    // Callback de confirmação
                    const deleteForm = $('#delete-form');
                    deleteForm.attr('action', `/admin/coupons/${couponId}`);
                    
                    // Mostrar toast de processamento
                    const processingToast = ToastManager.info('Excluindo cupom, aguarde...');
                    
                    // Enviar o formulário
                    deleteForm.submit();
                }
            );
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