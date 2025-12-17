@extends('admin.layouts.app')

@section('title', 'Nova Raspadinha')

@section('content')
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.raspadinha.index') }}">Raspadinhas</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Nova Raspadinha</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8" style="padding:20px;">
                    <div class="row mb-4">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Nova Raspadinha</h4>
                            <a href="{{ route('admin.raspadinha.index') }}" class="btn btn-secondary">
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

                    <form action="{{ route('admin.raspadinha.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name">Nome da Raspadinha <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" 
                                           name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="price">Preço Normal (R$) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" 
                                           name="price" value="{{ old('price') }}" step="0.01" min="0.01" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="turbo_price">Preço Turbo (R$) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('turbo_price') is-invalid @enderror" id="turbo_price" 
                                           name="turbo_price" value="{{ old('turbo_price') }}" step="0.01" min="0.01" required>
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
                                           name="rtp_percentage" value="{{ old('rtp_percentage', 75.00) }}" step="0.01" min="50.00" max="95.00" required>
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
                                           name="turbo_boost_percentage" value="{{ old('turbo_boost_percentage', 5.00) }}" step="0.01" min="0.00" max="20.00" required>
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
                                            <strong>Normal: <span id="house-margin">25.00</span>%</strong>
                                            <br><strong>Turbo: <span id="turbo-margin">20.00</span>%</strong>
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
                                              name="description" rows="3">{{ old('description') }}</textarea>
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
                                    <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, WEBP, AVIF. Tamanho máximo: 2MB. Esta imagem será exibida na home e listagem de raspadinhas.</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Preview da Imagem -->
                        <div class="row" id="image-preview" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Preview da Imagem</label>
                                    <div>
                                        <img id="preview-img" src="" style="max-width: 300px; max-height: 200px; border: 1px solid #ddd; border-radius: 8px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Ativo</label>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Atenção:</strong> Após criar a raspadinha, você poderá adicionar os produtos/prêmios na seção de itens.
                        </div>

                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary" id="save-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Criar Raspadinha
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
});
</script>
@endpush 