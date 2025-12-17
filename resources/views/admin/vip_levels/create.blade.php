@extends('admin.layouts.app')
@section('content')
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vip-levels.index') }}">Níveis VIP</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Adicionar Nível</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8" style="padding:20px;">
                    <div class="row mb-4">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Novo Nível VIP</h4>
                            <a href="{{ route('admin.vip-levels.index') }}" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> Voltar
                            </a>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.vip-levels.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name">Nome do Nível <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" 
                                           name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="level">Número do Nível <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('level') is-invalid @enderror" id="level" 
                                           name="level" value="{{ old('level') }}" min="1" required>
                                    @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="order">Ordem <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" 
                                           name="order" value="{{ old('order') }}" min="0" required>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="min_deposit">Depósito Mínimo (R$) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('min_deposit') is-invalid @enderror" 
                                           id="min_deposit" name="min_deposit" value="{{ old('min_deposit') }}" min="0" required>
                                    @error('min_deposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="max_deposit">Depósito Máximo (R$) <small class="text-muted">(Deixe em branco para sem limite)</small></label>
                                    <input type="number" step="0.01" class="form-control @error('max_deposit') is-invalid @enderror" 
                                           id="max_deposit" name="max_deposit" value="{{ old('max_deposit') }}" min="0">
                                    @error('max_deposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="benefits">Benefícios</label>
                            <textarea class="form-control @error('benefits') is-invalid @enderror" id="benefits" 
                                      name="benefits" rows="3">{{ old('benefits') }}</textarea>
                            @error('benefits')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row" style="margin-bottom: 10px;">
                            <div class="card">
                                <div class="card-header">
                                    Recompensas
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="coins_reward">Coins</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control @error('coins_reward') is-invalid @enderror" 
                                                           id="coins_reward" name="coins_reward" value="{{ old('coins_reward', '0') }}" min="0" required>
                                                </div>
                                                <small class="text-muted">Quantidade de coins que o usuário recebe ao atingir este nível VIP</small>
                                                @error('coins_reward')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="balance_reward">Saldo Real R$</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" class="form-control @error('balance_reward') is-invalid @enderror" 
                                                           id="balance_reward" name="balance_reward" value="{{ old('balance_reward', '0.00') }}" min="0">
                                                </div>
                                                <small class="text-muted">Valor em saldo real que o usuário recebe ao atingir este nível VIP</small>
                                                @error('balance_reward')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="balance_bonus_reward">Saldo Bônus R$</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" class="form-control @error('balance_bonus_reward') is-invalid @enderror" 
                                                           id="balance_bonus_reward" name="balance_bonus_reward" value="{{ old('balance_bonus_reward', '0.00') }}" min="0">
                                                </div>
                                                <small class="text-muted">Valor em saldo bônus que o usuário recebe ao atingir este nível VIP</small>
                                                @error('balance_bonus_reward')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="free_spins_reward">Rodadas Grátis</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control @error('free_spins_reward') is-invalid @enderror" 
                                                           id="free_spins_reward" name="free_spins_reward" value="{{ old('free_spins_reward', '0') }}" min="0">
                                                </div>
                                                <small class="text-muted">Quantidade de rodadas grátis que o usuário recebe ao atingir este nível VIP</small>
                                                @error('free_spins_reward')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="image">Imagem do Nível</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/bmp">
                            <small class="form-text text-muted">Formatos permitidos: JPG, JPEG, PNG, GIF, WEBP, BMP. Tamanho máximo: 2MB</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-4">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="active" name="active" value="1" 
                                       {{ old('active') ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">Ativo</label>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary" id="save-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Salvar Nível
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
    $(function() {
        // Inicialização do gerenciador de modais quando o documento estiver pronto
        ModalManager.init();
        
        // Verificar se há mensagem de erro na sessão e exibir toast
        @if(session('error'))
            ToastManager.error("{{ session('error') }}");
        @endif
        
        // Verificação específica para erros de validação de imagem
        @if($errors->has('image'))
            @foreach($errors->get('image') as $imageError)
                ToastManager.error("Imagem inválida: {{ $imageError }}");
            @endforeach
        @endif
    });
</script>
@endpush