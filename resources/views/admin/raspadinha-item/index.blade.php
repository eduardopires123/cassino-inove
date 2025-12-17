@extends('admin.layouts.app')

@section('title', 'Gerenciar Produtos - ' . $raspadinha->name)

@section('content')
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.raspadinha.index') }}">Raspadinhas</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">{{ $raspadinha->name }} - Produtos</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8" style="padding:20px;">
                    
                    <div class="row mb-4">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="m-0">Produtos da Raspadinha: {{ $raspadinha->name }}</h4>
                                <p class="text-muted mb-0">Preço Normal: R$ {{ number_format($raspadinha->price, 2, ',', '.') }} | Preço Turbo: R$ {{ number_format($raspadinha->turbo_price, 2, ',', '.') }}</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.raspadinha-item.create', $raspadinha) }}" class="btn btn-success me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-1">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg> Novo Produto
                                </a>
                                <a href="{{ route('admin.raspadinha.index') }}" class="btn btn-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> Voltar
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($items->count() > 0)
                        <div class="alert alert-info mb-4">
                            <strong>Dica:</strong> Arraste e solte os itens pela coluna "Pos." para reordenar sua posição na raspadinha.
                        </div>
                    @endif

                    <!-- Informações das Probabilidades -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title">Probabilidades Configuradas</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="mb-0 text-success">{{ number_format($raspadinha->items()->sum('probability'), 2) }}%</h4>
                                        <small class="text-muted">Chance de Ganhar</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="mb-0 text-danger">{{ number_format(100 - $raspadinha->items()->sum('probability'), 2) }}%</h4>
                                        <small class="text-muted">Chance de Perder</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <span class="badge badge-info fs-6">{{ $raspadinha->items()->count() }} produtos</span>
                                        <small class="text-muted d-block">Configurados</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        @if($raspadinha->items()->sum('probability') == 100)
                                            <span class="badge badge-success fs-6">✓ Balanceado</span>
                                        @elseif($raspadinha->items()->sum('probability') > 100)
                                            <span class="badge badge-danger fs-6">⚠ Excedido</span>
                                        @else
                                            <span class="badge badge-warning fs-6">⚡ Casa Favorita</span>
                                        @endif
                                        <small class="text-muted d-block">Status</small>
                                    </div>
                                </div>
                            </div>
                            
                            @if($raspadinha->items()->sum('probability') < 100)
                                <div class="alert alert-info mt-3 mb-0">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Sistema de Perda Automática:</strong> 
                                    Os {{ number_format(100 - $raspadinha->items()->sum('probability'), 2) }}% restantes representam jogadas onde o jogador não ganha nada.
                                    <br><small>
                                        <strong>Exemplo:</strong> Se os produtos somam 70% de probabilidade, em 30% das vezes o jogador não ganhará nada (mesmo vendo itens na tela).
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped dt-table-hover dataTable" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th scope="col" width="50px">Pos.</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Produto</th>
                                    <th scope="col">Tipo de Prêmio</th>
                                    <th scope="col">Valor</th>
                                    <th scope="col">Probabilidade</th>
                                    <th scope="col">Imagem</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-items">
                                @forelse($items as $item)
                                <tr data-id="{{ $item->id }}" style="cursor: move;">
                                    <td class="text-center handle">
                                        <div class="position-indicator">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu text-muted"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                                            <span class="position-number">{{ $item->position ?: $loop->iteration }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <strong>{{ $item->name }}</strong>
                                        @if($item->premio_type === 'produto' && $item->product_description)
                                            <br><small class="text-muted">{{ Str::limit($item->product_description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($item->premio_type ?? 'saldo_real') {
                                                'saldo_real' => 'badge-success',
                                                'saldo_bonus' => 'badge-warning',
                                                'rodadas_gratis' => 'badge-info',
                                                'produto' => 'badge-primary',
                                                default => 'badge-secondary'
                                            };
                                            $typeLabel = \App\Models\RaspadinhaItem::PREMIO_TYPES[$item->premio_type ?? 'saldo_real'] ?? 'Saldo Real';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $typeLabel }}</span>
                                    </td>
                                    <td>
                                        @if($item->premio_type === 'produto')
                                            <span class="badge badge-secondary">N/A</span>
                                        @elseif($item->premio_type === 'rodadas_gratis')
                                            <span class="badge badge-info">{{ $item->value }} rodadas</span>
                                        @else
                                            <span class="badge {{ $item->value > 0 ? 'badge-success' : 'badge-secondary' }}">
                                                R$ {{ number_format($item->value, 2, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $item->probability }}%">
                                                {{ number_format($item->probability, 2) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->image)
                                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div style="width: 40px; height: 40px; background-color: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image text-muted"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21,15 16,10 5,21"></polyline></svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $item->is_active ? 'light-success' : 'light-danger' }} mb-2 me-4">
                                            {{ $item->is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.raspadinha-item.edit', [$raspadinha, $item]) }}" class="badge badge-light-primary text-start me-2 action-edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                        </a>
                                        <button class="badge badge-light-danger text-start action-delete" data-id="{{ $item->id }}" data-name="{{ $item->name }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                            <h5>Nenhum produto encontrado</h5>
                                            <p class="text-muted">Adicione produtos para que os usuários possam jogar esta raspadinha.</p>
                                            <a href="{{ route('admin.raspadinha-item.create', $raspadinha) }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Adicionar Primeiro Produto
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($items->count() > 0)
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Mostrando {{ $items->firstItem() }} até {{ $items->lastItem() }} de {{ $items->total() }} resultados
                            </div>
                            {{ $items->links() }}
                        </div>
                    @endif

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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar drag and drop para reordenação de itens
    $("#sortable-items").sortable({
        handle: ".handle",
        update: function(event, ui) {
            updateItemPositions();
        }
    });

    // Função para atualizar as posições dos itens
    function updateItemPositions() {
        const items = [];
        const rows = document.querySelectorAll('#sortable-items tr[data-id]');
        
        rows.forEach((row, index) => {
            const itemId = row.getAttribute('data-id');
            const position = index + 1;
            
            // Atualizar o número da posição na interface
            const positionNumber = row.querySelector('.position-number');
            if (positionNumber) {
                positionNumber.textContent = position;
            }
            
            items.push({
                id: itemId,
                position: position
            });
        });
        
        // Mostrar toast de processamento
        const processingToast = ToastManager.info('Atualizando posições, aguarde...');
        
        // Enviar as novas posições para o servidor
        fetch('{{ route("admin.raspadinha.update-positions", $raspadinha) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                items: items
            })
        })
        .then(response => response.json())
        .then(data => {
            // Remover toast de processamento
            processingToast.remove();
            
            if (data.success) {
                ToastManager.success('Posições atualizadas com sucesso!');
            } else {
                ToastManager.error('Erro ao atualizar posições: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            // Remover toast de processamento
            processingToast.remove();
            console.error('Erro:', error);
            ToastManager.error('Erro ao comunicar com o servidor');
        });
    }

    // Lidar com cliques no botão de exclusão
    $('.action-delete').on('click', function() {
        const itemId = $(this).data('id');
        const itemName = $(this).data('name');

        // Mostrar modal de confirmação
        ModalManager.showConfirmation(
            'Excluir Produto',
            `Tem certeza que deseja excluir o produto "${itemName}"? Esta ação não pode ser desfeita.`,
            function() {
                // Callback de confirmação
                const deleteForm = $('#delete-form');
                const deleteUrl = '{{ route("admin.raspadinha-item.destroy", [$raspadinha, "__ITEM_ID__"]) }}';
                deleteForm.attr('action', deleteUrl.replace('__ITEM_ID__', itemId));

                // Mostrar toast de processamento
                const processingToast = ToastManager.info('Excluindo produto, aguarde...');

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

    // Atualizar informações de probabilidade dinamicamente
    function updateProbabilityInfo() {
        fetch('{{ route("admin.raspadinha-item.check-probabilities", $raspadinha) }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-probability').textContent = data.total.toFixed(2) + '%';
                document.getElementById('remaining-probability').textContent = data.remaining.toFixed(2) + '%';
                
                // Atualizar status badge
                const statusBadge = document.querySelector('.badge');
                statusBadge.className = 'badge fs-6 ';
                
                if (data.total === 100) {
                    statusBadge.classList.add('badge-success');
                    statusBadge.textContent = 'Completo';
                } else if (data.total > 100) {
                    statusBadge.classList.add('badge-danger');
                    statusBadge.textContent = 'Excedido!';
                } else {
                    statusBadge.classList.add('badge-warning');
                    statusBadge.textContent = 'Incompleto';
                }
            });
    }
    
    // Atualizar a cada 30 segundos
    setInterval(updateProbabilityInfo, 30000);
});
</script>
@endpush 