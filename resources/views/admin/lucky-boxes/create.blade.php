@extends('admin.layouts.app')
@section('content')

<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top: 45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.lucky-boxes.index') }}">Caixas da Sorte</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Criar Caixa</li>
                </ol>
            </nav>
        </div>
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8" style="padding: 20px;">
                    <div class="row mb-4">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h4 class="m-0">Criar Caixa da Sorte</h4>
                            <a href="{{ route('admin.lucky-boxes.index') }}" class="btn btn-secondary">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-arrow-left"
                                >
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg>
                                Voltar
                            </a>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.lucky-boxes.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body">
                            @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="row">
                                <!-- Primeira linha -->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="name">Nome</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="order">Ordem de Exibição</label>
                                        <input type="number" class="form-control" id="order" name="order" value="{{ old('order') }}" min="0" />
                                        <small class="text-muted">Deixe vazio para ordenação automática.</small>
                                    </div>
                                </div>

                                <!-- Segunda linha -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="level">Nível</label>
                                        <input type="number" class="form-control" id="level" name="level" value="{{ old('level') }}" required min="1" />
                                        <small class="text-muted">Digite um número único de nível para esta caixa.</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price">Preço (Coins)</label>
                                        <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" required min="1" />
                                        <small class="text-muted">Coins é a moeda obtida a cada nivel que o usuario atinge.</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="daily_limit">Limite Diário de Compra</label>
                                        <input type="number" class="form-control" id="daily_limit" name="daily_limit" value="{{ old('daily_limit', 0) }}" min="0" />
                                        <small class="text-muted">Defina como 0 para sem limite.</small>
                                    </div>
                                </div>

                                <!-- Terceira linha -->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="image">Imagem da Caixa</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="image" name="image" />
                                                <label class="custom-file-label" for="image">Escolher arquivo</label>
                                            </div>
                                        </div>
                                        <small class="text-muted">Se nenhuma imagem for fornecida, uma imagem padrão baseada no nível será usada. As imagens serão automaticamente otimizadas e convertidas para o formato AVIF.</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mt-4">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="is_mysterious" name="is_mysterious" value="1" {{ old('is_mysterious') ? 'checked' : '' }}>
                                            <label for="is_mysterious" class="custom-control-label">É Caixa Misteriosa</label>
                                        </div>
                                        <small class="text-muted">Caixas misteriosas têm aparências especiais e podem ter limites diários de compra.</small>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                        <label for="is_active" class="custom-control-label">Ativo</label>
                                    </div>
                                </div>

                                <!-- Quarta linha -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Descrição</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Seção de Opções de Prêmios -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h4>Opções de Prêmios</h4>
                                    <p class="text-muted">Configure os possíveis prêmios desta caixa. Pelo menos uma opção deve estar ativa.</p>
                                    <div class="alert alert-info">
                                        <strong>Sobre as chances:</strong>
                                        <ul class="mb-0">
                                            <li>A porcentagem (%) indica a chance daquele tipo de prêmio ser sorteado quando a caixa for aberta</li>
                                            <li>A soma das chances de todos os prêmios ativos deve ser 100%</li>
                                            <li>O valor/quantidade do prêmio será sorteado aleatoriamente entre o mínimo e máximo configurado</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="prize-options">
                                        <!-- Opção Saldo Real -->
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="card-title">Saldo Real</h5>
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="prizes[real_balance][active]" value="0">
                                                        <input type="checkbox" class="custom-control-input" id="real_balance_active" name="prizes[real_balance][active]" value="1" {{ old('prizes.real_balance.active') ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="real_balance_active">Ativo</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Valor Mínimo (R$)</label>
                                                            <input type="number" step="0.01" class="form-control" name="prizes[real_balance][min_amount]" value="{{ old('prizes.real_balance.min_amount', 0) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Valor Máximo (R$)</label>
                                                            <input type="number" step="0.01" class="form-control" name="prizes[real_balance][max_amount]" value="{{ old('prizes.real_balance.max_amount', 0) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Chance (%)</label>
                                                            <input type="number" step="0.01" class="form-control" name="prizes[real_balance][chance]" value="{{ old('prizes.real_balance.chance', 100) }}" max="100" min="0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Opção Bônus -->
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="card-title">Bônus</h5>
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="prizes[bonus][active]" value="0">
                                                        <input type="checkbox" class="custom-control-input" id="bonus_active" name="prizes[bonus][active]" value="1" {{ old('prizes.bonus.active') ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="bonus_active">Ativo</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Valor Mínimo (R$)</label>
                                                            <input type="number" step="0.01" class="form-control" name="prizes[bonus][min_amount]" value="{{ old('prizes.bonus.min_amount', 0) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Valor Máximo (R$)</label>
                                                            <input type="number" step="0.01" class="form-control" name="prizes[bonus][max_amount]" value="{{ old('prizes.bonus.max_amount', 0) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Chance (%)</label>
                                                            <input type="number" step="0.01" class="form-control" name="prizes[bonus][chance]" value="{{ old('prizes.bonus.chance', 100) }}" max="100" min="0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Opção Rodadas Grátis -->
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="card-title">Rodadas Grátis</h5>
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="prizes[free_spins][active]" value="0">
                                                        <input type="checkbox" class="custom-control-input" id="free_spins_active" name="prizes[free_spins][active]" value="1" {{ old('prizes.free_spins.active') ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="free_spins_active">Ativo</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Mínimo de Rodadas</label>
                                                            <input type="number" class="form-control" name="prizes[free_spins][min_spins]" value="{{ old('prizes.free_spins.min_spins', 0) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Máximo de Rodadas</label>
                                                            <input type="number" class="form-control" name="prizes[free_spins][max_spins]" value="{{ old('prizes.free_spins.max_spins', 0) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Chance (%)</label>
                                                            <input type="number" step="0.01" class="form-control" name="prizes[free_spins][chance]" value="{{ old('prizes.free_spins.chance', 100) }}" max="100" min="0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-lg btn-success">Criar Caixa</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Mostrar nome do arquivo no input de arquivo personalizado
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        // Mostrar/ocultar limite diário com base na caixa misteriosa
        $('#is_mysterious').change(function() {
            if($(this).is(':checked')) {
                $('#daily_limit').val(1);
            } else {
                $('#daily_limit').val(0);
            }
        });

        // Validação das opções de prêmios
        $('form').on('submit', function(e) {
            let hasActivePrize = false;
            let totalChance = 0;

            // Verificar se pelo menos uma opção está ativa
            $('input[type="checkbox"][name^="prizes"]').each(function() {
                if ($(this).is(':checked')) {
                    hasActivePrize = true;
                    let prizeType = $(this).attr('name').match(/prizes\[(.*?)\]/)[1];
                    totalChance += parseFloat($(`input[name="prizes[${prizeType}][chance]"]`).val() || 0);
                }
            });

            if (!hasActivePrize) {
                e.preventDefault();
                alert('Pelo menos uma opção de prêmio deve estar ativa!');
                return false;
            }

            if (Math.abs(totalChance - 100) > 0.01) {
                e.preventDefault();
                alert('A soma das chances deve ser exatamente 100%! Atualmente a soma é ' + totalChance.toFixed(2) + '%');
                return false;
            }
        });
    });
</script>
@endsection 

