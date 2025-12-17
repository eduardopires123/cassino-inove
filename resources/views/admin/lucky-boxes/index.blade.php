@extends('admin.layouts.app')
@section('content')
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Caixas da Sorte</li>
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
                                <a href="{{ route('admin.lucky-boxes.create') }}" class="btn btn-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg></i> Adicionar Nova Caixa
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="zero-config" class="table table-striped dt-table-hover dataTable" id="lucky-boxes-table" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th>ID</th>
                                        <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>Nível</th>
                                        <th>Preço</th>
                                        <th>Prêmio Máx. (Saldo)</th>
                                        <th>Prêmio Máx. (Bônus)</th>
                                        <th>Prêmio Máx. (Rodadas)</th>
                                        <th>Status</th>
                                        <th>Ordem</th>
                                        <th style="width: 120px">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-boxes">
                                    @foreach($boxes as $box)
                                    <tr data-id="{{ $box->id }}" data-order="{{ $box->order }}">
                                        <td>{{ $box->id }}</td>
                                        <td class="handle">
                                            <img src="{{ $box->image }}" alt="{{ $box->name }}" height="40">
                                        </td>
                                        <td>{{ $box->name }}</td>
                                        <td>{{ $box->level }}</td>
                                        <td>{{ $box->price }}</td>
                                        <td>
                                            @php
                                                $realBalanceOption = $box->prizeOptions->where('prize_type', 'real_balance')->first();
                                                $maxRealBalance = $realBalanceOption ? $realBalanceOption->max_amount : null;
                                            @endphp
                                            {{ $maxRealBalance ? number_format($maxRealBalance, 2, ',', '.') . ' R$' : '-' }}
                                        </td>
                                        <td>
                                            @php
                                                $bonusOption = $box->prizeOptions->where('prize_type', 'bonus')->first();
                                                $maxBonus = $bonusOption ? $bonusOption->max_amount : null;
                                            @endphp
                                            {{ $maxBonus ? number_format($maxBonus, 2, ',', '.') . ' R$' : '-' }}
                                        </td>
                                        <td>
                                            @php
                                                $spinsOption = $box->prizeOptions->where('prize_type', 'free_spins')->first();
                                                $maxSpins = $spinsOption ? $spinsOption->max_spins : null;
                                            @endphp
                                            {{ $maxSpins ? $maxSpins . ' Rodadas' : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $box->is_active ? 'light-success' : 'light-danger' }} mb-2 me-4">
                                                {{ $box->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $box->order }}</span>
                                            <i class="fas fa-arrows-alt handle" style="cursor: move;"></i>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.lucky-boxes.edit', $box->id) }}" class="badge badge-light-primary text-start me-2 action-edit">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                            </a>
                                            <a href="{{ route('admin.lucky-boxes.redemptions', $box->id) }}" class="badge badge-light-info text-start me-2">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                            </a>
                                            <button class="badge badge-light-danger text-start action-delete" data-id="{{ $box->id }}" data-name="{{ $box->name }}">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>Nível</th>
                                        <th>Preço</th>
                                        <th>Prêmio Máx. (Saldo)</th>
                                        <th>Prêmio Máx. (Bônus)</th>
                                        <th>Prêmio Máx. (Rodadas)</th>
                                        <th>Status</th>
                                        <th>Ordem</th>
                                        <th style="width: 120px">Ações</th>
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

<style>
    body.dark .form-check-input:checked {
        background-color: #4361ee;
        border-color: #4361ee;
    }
    
    .handle {
        cursor: grab;
    }
    
    .handle:active {
        cursor: grabbing;
    }
</style>
@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    $(function() {
        // Inicializar ordenação
        $("#sortable-boxes").sortable({
            handle: ".handle",
            update: function(event, ui) {
                let boxes = [];
                $('#sortable-boxes tr').each(function(index) {
                    boxes.push({
                        id: $(this).data('id'),
                        order: index + 1
                    });
                    $(this).find('.badge.bg-info').text(index + 1);
                });
                
                // Mostrar toast de processamento
                const processingToast = ToastManager.info('Atualizando ordem, aguarde...');
                
                // Atualiza a ordem via AJAX
                $.ajax({
                    url: '{{ route("admin.lucky-boxes.update-order") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        boxes: boxes
                    },
                    success: function(response) {
                        // Remover toast de processamento
                        processingToast.remove();
                        
                        if (response.success) {
                            ToastManager.success('Ordem atualizada com sucesso!');
                        }
                    },
                    error: function() {
                        // Remover toast de processamento
                        processingToast.remove();
                        
                        ToastManager.error('Erro ao atualizar a ordem. Por favor, tente novamente.');
                    }
                });
            }
        });
        
        // Lidar com cliques no botão de exclusão
        $('.action-delete').on('click', function() {
            const boxId = $(this).data('id');
            const boxName = $(this).data('name');
            
            // Mostrar modal de confirmação
            ModalManager.showConfirmation(
                'Excluir Caixa da Sorte',
                `Tem certeza que deseja excluir a caixa "${boxName}"? Esta ação não pode ser desfeita.`,
                function() {
                    // Callback de confirmação
                    const deleteForm = $('#delete-form');
                    deleteForm.attr('action', `/admin/lucky-boxes/${boxId}`);
                    
                    // Mostrar toast de processamento
                    const processingToast = ToastManager.info('Excluindo caixa, aguarde...');
                    
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