@extends('admin.layouts.app')

@section('title', 'Editar Raspadinha')

@section('content')
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.raspadinha.index') }}">Raspadinhas</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Editar Raspadinha</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8" style="padding:20px;">
                    <div class="row mb-4">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Editar Raspadinha: {{ $raspadinha->name }}</h4>
                            <a href="{{ route('admin.raspadinha.index') }}" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> Voltar
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('admin.raspadinha.update', $raspadinha) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name">Nome da Raspadinha <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" 
                                           name="name" value="{{ old('name', $raspadinha->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="price">Preço Normal (R$) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" 
                                           name="price" value="{{ old('price', $raspadinha->price) }}" step="0.01" min="0.01" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="turbo_price">Preço Turbo (R$) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('turbo_price') is-invalid @enderror" id="turbo_price" 
                                           name="turbo_price" value="{{ old('turbo_price', $raspadinha->turbo_price) }}" step="0.01" min="0.01" required>
                                    @error('turbo_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="rtp_percentage">RTP - Return to Player (%) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('rtp_percentage') is-invalid @enderror" id="rtp_percentage" 
                                           name="rtp_percentage" value="{{ old('rtp_percentage', $raspadinha->rtp_percentage ?? 75.00) }}" step="0.01" min="50.00" max="95.00" required>
                                    <small class="text-muted">
                                        Percentual que os jogadores podem ganhar de volta. Ex: 75% = casa fica com 25% de lucro.
                                    </small>
                                    @error('rtp_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="turbo_boost_percentage">Boost Turbo (%) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('turbo_boost_percentage') is-invalid @enderror" id="turbo_boost_percentage" 
                                           name="turbo_boost_percentage" value="{{ old('turbo_boost_percentage', $raspadinha->turbo_boost_percentage ?? 5.00) }}" step="0.01" min="0.00" max="20.00" required>
                                    <small class="text-muted">
                                        Percentual adicional de chance no modo turbo. Ex: 5% normal + 3% boost = 8% turbo.
                                    </small>
                                    @error('turbo_boost_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Margem da Casa</label>
                                    <div class="alert alert-info mb-0">
                                        <div id="house-margin-display">
                                            <strong>Normal: <span id="house-margin">{{ 100 - ($raspadinha->rtp_percentage ?? 75) }}</span>%</strong>
                                            <br><strong>Turbo: <span id="turbo-margin">{{ 100 - (($raspadinha->rtp_percentage ?? 75) + ($raspadinha->turbo_boost_percentage ?? 5)) }}</span>%</strong>
                                            <br><small>Margem de lucro garantida para casa.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description">Descrição</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" 
                                              name="description" rows="3">{{ old('description', $raspadinha->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="image">Imagem da Raspadinha</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/avif">
                                    <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, WEBP, AVIF. Tamanho máximo: 2MB. Deixe em branco para manter a imagem atual.</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Imagem Atual e Preview -->
                        @if($raspadinha->image)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Imagem Atual</label>
                                        <div>
                                            <img src="{{ $raspadinha->image_url }}" alt="{{ $raspadinha->name }}" 
                                                 style="max-width: 300px; max-height: 200px; border: 1px solid #ddd; border-radius: 8px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Preview da Nova Imagem -->
                        <div class="row" id="image-preview" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Preview da Nova Imagem</label>
                                    <div>
                                        <img id="preview-img" src="" style="max-width: 300px; max-height: 200px; border: 1px solid #ddd; border-radius: 8px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $raspadinha->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Ativo</label>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary" id="update-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Atualizar Raspadinha
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Seção de Gerenciamento de Itens -->
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8" style="padding:20px;">
                    <div class="row mb-4">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Itens da Raspadinha</h4>
                            <a href="{{ route('admin.raspadinha-item.create', $raspadinha) }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Novo Item
                            </a>
                        </div>
                    </div>

                    @if($raspadinha->items->count() > 0)
                        <div class="alert alert-info">
                            <strong>Dica:</strong> Arraste e solte os itens para reordenar sua posição na raspadinha.
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="50px">Pos.</th>
                                        <th width="80px">Imagem</th>
                                        <th>Nome</th>
                                        <th width="120px">Valor</th>
                                        <th width="120px">Probabilidade</th>
                                        <th width="100px">Status</th>
                                        <th width="150px">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="sortable-items">
                                    @foreach($raspadinha->items as $item)
                                    <tr data-id="{{ $item->id }}" style="cursor: move;">
                                        <td class="text-center">
                                            <div class="position-indicator">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu text-muted"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                                                <span class="position-number">{{ $item->position ?: $loop->iteration }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($item->image)
                                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                            @else
                                                <div style="width: 50px; height: 50px; background-color: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image text-muted"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21,15 16,10 5,21"></polyline></svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $item->name }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-success">{{ $item->formatted_value }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary">{{ number_format($item->probability, 2) }}%</span>
                                        </td>
                                        <td class="text-center">
                                            @if($item->is_active)
                                                <span class="badge badge-light-success">Ativo</span>
                                            @else
                                                <span class="badge badge-light-danger">Inativo</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.raspadinha-item.edit', [$raspadinha, $item]) }}" 
                                               class="badge badge-light-primary text-start me-2 action-edit" title="Editar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.raspadinha-item.toggle-status', [$raspadinha, $item]) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="badge badge-light-warning text-start me-2" 
                                                        title="{{ $item->is_active ? 'Desativar' : 'Ativar' }}">
                                                    @if($item->is_active)
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye-off"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                    @endif
                                                </button>
                                            </form>
                                            <button class="badge badge-light-danger text-start action-delete" 
                                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}" title="Deletar">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <div class="alert alert-warning">
                                <strong>Resumo das Probabilidades:</strong>
                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <span class="badge badge-primary">Total: {{ number_format($raspadinha->items->sum('probability'), 2) }}%</span>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="badge badge-{{ $raspadinha->items->sum('probability') <= 100 ? 'success' : 'danger' }}">
                                            Restante: {{ number_format(100 - $raspadinha->items->sum('probability'), 2) }}%
                                        </span>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="badge badge-info">Itens: {{ $raspadinha->items->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package text-muted mb-3"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27,6.96 12,12.01 20.73,6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                            <h5 class="text-muted">Nenhum item cadastrado</h5>
                            <p class="text-muted">Adicione itens para que os jogadores possam ganhar prêmios nesta raspadinha.</p>
                            <a href="{{ route('admin.raspadinha-item.create', $raspadinha) }}" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Adicionar Primeiro Item
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- Formulário oculto para exclusão -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    $(function() {
        // Inicialização do gerenciador de modais quando o documento estiver pronto
        ModalManager.init();
        
        // Verificar se há mensagem de erro na sessão e exibir toast
        @if(session('error'))
            ToastManager.error("{{ session('error') }}");
        @endif
        
            // Validação para garantir que preço turbo seja maior que preço normal
    const priceInput = document.getElementById('price');
    const turboPriceInput = document.getElementById('turbo_price');
    const rtpInput = document.getElementById('rtp_percentage');
    const turboBoostInput = document.getElementById('turbo_boost_percentage');
    const houseMarginSpan = document.getElementById('house-margin');
    const turboMarginSpan = document.getElementById('turbo-margin');
        
        function validatePrices() {
            const price = parseFloat(priceInput.value) || 0;
            const turboPrice = parseFloat(turboPriceInput.value) || 0;
            
            if (turboPrice > 0 && price > 0 && turboPrice <= price) {
                turboPriceInput.setCustomValidity('O preço turbo deve ser maior que o preço normal');
            } else {
                turboPriceInput.setCustomValidity('');
            }
        }
        
        priceInput.addEventListener('input', validatePrices);
        turboPriceInput.addEventListener('input', validatePrices);
        
        // Função para calcular e exibir margens da casa
        function updateHouseMargins() {
            const rtp = parseFloat(rtpInput.value) || 75;
            const turboBoost = parseFloat(turboBoostInput.value) || 5;
            
            // Margem normal
            const normalMargin = 100 - rtp;
            houseMarginSpan.textContent = normalMargin.toFixed(2);
            
            // Margem turbo (considera que boost aumenta chance de ganho)
            const turboMargin = 100 - (rtp + turboBoost);
            turboMarginSpan.textContent = Math.max(turboMargin, 0).toFixed(2);
            
            // Atualizar cor baseada na margem normal
            const alertDiv = document.querySelector('#house-margin-display').parentElement;
            alertDiv.className = 'alert mb-0';
            
            if (normalMargin >= 30) {
                alertDiv.classList.add('alert-success'); // Verde para margem alta
            } else if (normalMargin >= 20) {
                alertDiv.classList.add('alert-warning'); // Amarelo para margem média
            } else {
                alertDiv.classList.add('alert-danger'); // Vermelho para margem baixa
            }
        }
        
        // Atualizar margens quando valores mudarem
        rtpInput.addEventListener('input', updateHouseMargins);
        turboBoostInput.addEventListener('input', updateHouseMargins);
        
        // Inicializar margens
        updateHouseMargins();

        // Preview da imagem
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });
        
        // Lidar com cliques no botão de exclusão
        $('.action-delete').on('click', function() {
            const itemId = $(this).data('id');
            const itemName = $(this).data('name');

            // Mostrar modal de confirmação
            ModalManager.showConfirmation(
                'Excluir Item',
                `Tem certeza que deseja excluir o item "${itemName}"? Esta ação não pode ser desfeita.`,
                function() {
                    // Callback de confirmação
                    const deleteForm = $('#delete-form');
                    deleteForm.attr('action', `{{ route("admin.raspadinha-item.destroy", [$raspadinha, ":id"]) }}`.replace(':id', itemId));

                    // Mostrar toast de processamento
                    const processingToast = ToastManager.info('Excluindo item, aguarde...');

                    // Enviar o formulário
                    deleteForm.submit();
                }
            );
        });
        
        // Inicializar drag and drop para reordenação de itens
        const sortableElement = document.getElementById('sortable-items');
        if (sortableElement) {
            const sortable = Sortable.create(sortableElement, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    updateItemPositions();
                }
            });
        }
        
        // Função para atualizar as posições dos itens
        function updateItemPositions() {
            const items = [];
            const rows = document.querySelectorAll('#sortable-items tr');
            
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
                if (data.success) {
                    ToastManager.success('Posições atualizadas com sucesso!');
                } else {
                    ToastManager.error('Erro ao atualizar posições: ' + (data.message || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                ToastManager.error('Erro ao comunicar com o servidor');
            });
        }
    });
</script>
@endpush 