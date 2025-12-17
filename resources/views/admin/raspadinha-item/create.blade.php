@extends('admin.layouts.app')

@section('title', 'Novo Produto - ' . $raspadinha->name)

@section('content')
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.raspadinha.index') }}">Raspadinhas</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.raspadinha-item.index', $raspadinha) }}">{{ $raspadinha->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Novo Produto</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8" style="padding:20px;">
                    
                    <div class="row mb-4">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="m-0">Novo Produto</h4>
                                <p class="text-muted mb-0">Raspadinha: {{ $raspadinha->name }}</p>
                            </div>
                            <a href="{{ route('admin.raspadinha-item.index', $raspadinha) }}" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> Voltar
                            </a>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Informações das Probabilidades -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title">Probabilidades Disponíveis</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h4 class="mb-0" id="total-probability">{{ number_format($raspadinha->items()->sum('probability'), 2) }}%</h4>
                                        <small class="text-muted">Já Configurado</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h4 class="mb-0 text-success" id="remaining-probability">{{ number_format(100 - $raspadinha->items()->sum('probability'), 2) }}%</h4>
                                        <small class="text-muted">Disponível</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <span class="badge badge-info fs-6">{{ $raspadinha->items()->count() }} produtos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.raspadinha-item.store', $raspadinha) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name">Nome do Produto <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" 
                                           name="name" value="{{ old('name') }}" required placeholder="Ex: R$ 10,00">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="premio_type">Tipo de Prêmio <span class="text-danger">*</span></label>
                                    <select class="form-control @error('premio_type') is-invalid @enderror" id="premio_type" 
                                            name="premio_type" required>
                                        <option value="">Selecione o tipo de prêmio</option>
                                        @foreach(\App\Models\RaspadinhaItem::PREMIO_TYPES as $key => $label)
                                            <option value="{{ $key }}" {{ old('premio_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('premio_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6" id="value-field">
                                <div class="form-group mb-3">
                                    <label for="value"><span id="value-label">Valor (R$)</span> <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('value') is-invalid @enderror" id="value" 
                                           name="value" value="{{ old('value') }}" step="0.01" min="0" required>
                                    <small class="text-muted" id="value-help">Use 0 para "sem prêmio"</small>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6" id="product-description-field" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="product_description">Descrição do Produto <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('product_description') is-invalid @enderror" id="product_description" 
                                              name="product_description" rows="3" placeholder="Ex: iPhone 15 Pro Max 256GB">{{ old('product_description') }}</textarea>
                                    <small class="text-muted">Descreva detalhadamente o produto a ser premiado</small>
                                    @error('product_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="probability">Probabilidade (%) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('probability') is-invalid @enderror" id="probability" 
                                           name="probability" value="{{ old('probability') }}" step="0.01" min="0.01" 
                                           max="{{ 100 - $raspadinha->items()->sum('probability') }}" required>
                                    <small class="text-muted">Máximo: {{ number_format(100 - $raspadinha->items()->sum('probability'), 2) }}%</small>
                                    @error('probability')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="image">Imagem do Produto</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/avif">
                                    <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, WEBP, AVIF. Tamanho máximo: 2MB</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Ativo</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview da Imagem -->
                        <div class="row" id="image-preview" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Preview da Imagem</label>
                                    <div>
                                        <img id="preview-img" src="" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 8px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Dicas importantes:</strong>
                            <ul class="mb-0 mt-2">
                                <li>A soma das probabilidades não pode exceder 100%</li>
                                <li><strong>Saldo Real:</strong> Valor creditado diretamente na conta do usuário</li>
                                <li><strong>Saldo Bônus:</strong> Valor creditado como bônus (pode ter regras específicas)</li>
                                <li><strong>Rodadas Grátis:</strong> Quantidade de rodadas grátis em jogos</li>
                                <li><strong>Produto:</strong> Prêmios físicos ou digitais (não requer valor monetário)</li>
                                <li>Use probabilidades maiores para prêmios menores</li>
                                <li>Recomenda-se ter pelo menos um produto "sem prêmio" com probabilidade alta</li>
                            </ul>
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary" id="save-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Criar Produto
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicialização do gerenciador de modais quando o documento estiver pronto
    ModalManager.init();
    
    // Verificar se há mensagem de erro na sessão e exibir toast
    @if(session('error'))
        ToastManager.error("{{ session('error') }}");
    @endif

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
    
    // Validação de probabilidade
    const probabilityInput = document.getElementById('probability');
    const maxProbability = {{ 100 - $raspadinha->items()->sum('probability') }};
    
    probabilityInput.addEventListener('input', function() {
        const value = parseFloat(this.value) || 0;
        
        if (value > maxProbability) {
            this.setCustomValidity(`A probabilidade não pode exceder ${maxProbability.toFixed(2)}%`);
        } else if (value <= 0) {
            this.setCustomValidity('A probabilidade deve ser maior que zero');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Auto-sugerir nome baseado no valor
    const valueInput = document.getElementById('value');
    const nameInput = document.getElementById('name');
    
    valueInput.addEventListener('input', function() {
        const value = parseFloat(this.value) || 0;
        
        if (value === 0) {
            nameInput.placeholder = 'Ex: Sem prêmio';
        } else {
            nameInput.placeholder = `Ex: R$ ${value.toFixed(2).replace('.', ',')}`;
        }
        
        // Se o nome está vazio, auto-preencher
        if (!nameInput.value.trim()) {
            if (value === 0) {
                nameInput.value = 'Sem prêmio';
            } else {
                nameInput.value = `R$ ${value.toFixed(2).replace('.', ',')}`;
            }
        }
    });

         // Gerenciamento de campos baseado no tipo de prêmio
     const premioTypeSelect = document.getElementById('premio_type');
     const valueField = document.getElementById('value-field');
     const valueInput = document.getElementById('value');
     const valueLabel = document.getElementById('value-label');
     const valueHelp = document.getElementById('value-help');
     const productDescriptionField = document.getElementById('product-description-field');
     const productDescriptionInput = document.getElementById('product_description');

     function updateFieldsBasedOnPremioType() {
         const selectedType = premioTypeSelect.value;

         // Reset required attributes
         valueInput.removeAttribute('required');
         productDescriptionInput.removeAttribute('required');

         switch (selectedType) {
             case 'saldo_real':
                 valueField.style.display = 'block';
                 valueLabel.textContent = 'Valor em Saldo Real (R$)';
                 valueHelp.textContent = 'Valor que será creditado como saldo real';
                 productDescriptionField.style.display = 'none';
                 valueInput.setAttribute('required', 'required');
                 break;
             case 'saldo_bonus':
                 valueField.style.display = 'block';
                 valueLabel.textContent = 'Valor em Saldo Bônus (R$)';
                 valueHelp.textContent = 'Valor que será creditado como saldo bônus';
                 productDescriptionField.style.display = 'none';
                 valueInput.setAttribute('required', 'required');
                 break;
             case 'rodadas_gratis':
                 valueField.style.display = 'block';
                 valueLabel.textContent = 'Quantidade de Rodadas';
                 valueHelp.textContent = 'Número de rodadas grátis que serão concedidas';
                 productDescriptionField.style.display = 'none';
                 valueInput.setAttribute('required', 'required');
                 break;
             case 'produto':
                 valueField.style.display = 'none';
                 productDescriptionField.style.display = 'block';
                 productDescriptionInput.setAttribute('required', 'required');
                 break;
             default:
                 valueField.style.display = 'none';
                 productDescriptionField.style.display = 'none';
         }
     }

     premioTypeSelect.addEventListener('change', updateFieldsBasedOnPremioType);

     // Inicialização dos campos quando a página é carregada
     updateFieldsBasedOnPremioType();
});
</script>
@endpush 