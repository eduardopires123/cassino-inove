@extends('admin.layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-card-four">
                        <div class="widget-content">
                            <div class="w-header">
                                <div class="w-title">
                                    <h4>Editar Cupom: {{ $coupon->code }}</h4>
                                </div>
                                <div class="task-action">
                                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                        Voltar
                                    </a>
                                </div>
                            </div>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="code">Código do Cupom</label>
                                        <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $coupon->code) }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="description">Descrição (opcional)</label>
                                        <input type="text" class="form-control" id="description" name="description" value="{{ old('description', $coupon->description) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="type">Tipo de Bônus</label>
                                        <select class="form-select" id="type" name="type" required>
                                            <option value="bonus" {{ old('type', $coupon->type) == 'bonus' ? 'selected' : '' }}>Bônus (balance_bonus)</option>
                                            <option value="balance" {{ old('type', $coupon->type) == 'balance' ? 'selected' : '' }}>Saldo Real (balance)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="amount">Valor</label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', $coupon->amount) }}" step="0.01" min="0.01" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="max_usages">Limite de Usos</label>
                                        <input type="number" class="form-control" id="max_usages" name="max_usages" value="{{ old('max_usages', $coupon->max_usages) }}" min="1" required>
                                        <small class="form-text text-muted">Número máximo de vezes que este cupom pode ser resgatado.</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="valid_from">Válido a partir de (opcional)</label>
                                        <input type="datetime-local" class="form-control" id="valid_from" name="valid_from" value="{{ old('valid_from', $coupon->valid_from ? $coupon->valid_from->format('Y-m-d\TH:i') : '') }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="valid_until">Válido até (opcional)</label>
                                        <input type="datetime-local" class="form-control" id="valid_until" name="valid_until" value="{{ old('valid_until', $coupon->valid_until ? $coupon->valid_until->format('Y-m-d\TH:i') : '') }}">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Ativo</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title">Estatísticas</h6>
                                                <p class="card-text">Usos: {{ $coupon->used_count }} / {{ $coupon->max_usages }}</p>
                                                <p class="card-text">Criado em: {{ $coupon->created_at->format('d/m/Y H:i') }}</p>
                                                <p class="card-text">Atualizado em: {{ $coupon->updated_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // No script needed since rollover_multiplier field is removed
    });
</script>
@endsection 