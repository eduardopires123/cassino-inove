@extends('admin.layouts.app')
@section('content')
    <style>
        .w-info{
            width: 100%;
        }

        /* Styling elements similar to estatisticas_afiliados.blade.php */
        .dividerx {
            height: 1px;
            background-color: rgba(0, 0, 0, 0.1);
            width: 100%;
            margin: 15px 0;
        }

        .panelx {
            background-color: #1b2e4b;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            width: 100%;
        }

        .commission-background {
            border-left: 4px solid #44e305;
        }

        .comissão-footer {
            margin-bottom: 10px;
        }

        .comissão-footer h2 {
            margin: 0;
            color: #fff;
        }

        .comissão-footer-values {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comissão-footer-values p {
            margin: 0;
            font-size: 16px;
            color: #888ea8;
        }

        .comissão-footer-values h2 {
            margin: 0;
            font-size: 24px;
        }

        /* New CSS for table-content-insert alignment */
        .table-content-insert {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .table-content-insert .label {
            text-align: left;
        }

        .table-content-insert .value {
            text-align: right;
        }

        body.dark .form-check-input:checked {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        .form-afil {
            margin-right: 10px;
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid #3b3f5c;
            background-color: #1b2e4b;
            color: #888ea8;
        }
    </style>
    @php
        $Settings = App\Helpers\Core::getSetting();
        $user_logado = auth()->user();

        $a = request()->input('aff', '');
        $b = request()->input('di', '');
        $c = request()->input('df', '');

        $dataInicial = $b;
        $dataFinal = $c;
        $nomeUsuario = $a;

        if ($b) {$bb = Carbon\Carbon::parse($b)->startOfDay();}else{$bb = "";}
        if ($c) {$cc = Carbon\Carbon::parse($c)->endOfDay();}else{$cc = "";}

        $user_a = null;
        if ($a != "") {
            $user = App\Models\User::where('name', $a)->first();
            if ($user) {
                $user_a = $user->id;
            }
        }

        $IdUser = $user_logado->id;

        if ($user_logado->is_admin == 1) {
            if ($user_a != null) {
                $IdUser = $user_a;
                $user_a = null;
            }
        }

        // Contagem de registros de usuários
        $Registros = App\Models\User::where('inviter', $IdUser)->count();

        // Query para agrupamento por usuário (para PrimeirosDep, SegundosDep, TerceirosDep)
        $agrupamentoQuery = App\Models\Transactions::where('type', 0)
            ->where('status', 1)
            ->whereHas('user', function($query) use ($user_logado, $user_a, $IdUser) {
                if ($user_a) {
                    $query->where('id', $user_a);
                } else {
                    $query->where('inviter', $IdUser);
                }
            });

        if ($b && $c) {
            $agrupamentoQuery->whereBetween('created_at', [$bb, $cc]);
        } elseif ($b && !$c) {
            $agrupamentoQuery->whereBetween('created_at', [$bb, Carbon\Carbon::now()]);
        }

        $resultados = $agrupamentoQuery->select('user_id', DB::raw('COUNT(*) as total'))
            ->groupBy('user_id')
            ->get();

        $PrimeirosDep = $resultados->where('total', 1)->count();
        $SegundosDep = $resultados->where('total', 2)->count();
        $TerceirosDep = $resultados->where('total', '>=', 3)->count();

        // Query para soma total de depósitos (NÃO use a mesma query do agrupamento!)
        $somaQuery = App\Models\Transactions::where('type', 0)
            ->where('status', 1)
            ->whereHas('user', function($query) use ($user_logado, $user_a, $IdUser) {
                if ($user_a) {
                    $query->where('id', $user_a);
                } else {
                    $query->where('inviter', $IdUser);
                }
            });

        if ($b && $c) {
            $somaQuery->whereBetween('created_at', [$bb, $cc]);
        } elseif ($b && !$c) {
            $somaQuery->whereBetween('created_at', [$bb, Carbon\Carbon::now()]);
        }

        $TotalDep = $somaQuery;

        // Query para saques
        $baseQuerySaque = App\Models\Transactions::where('type', 1)
            ->where('status', 1)
            ->whereHas('user', function($query) use ($user_logado, $user_a, $IdUser) {
                if ($user_a) {
                    $query->where('id', $user_a);
                } else {
                    $query->where('inviter', $IdUser);
                }
            });

        // Aplicando filtros de data para saques
        if ($b && $c) {
            $baseQuerySaque->whereBetween('created_at', [$bb, $cc]);
        } elseif ($b && !$c) {
            $baseQuerySaque->whereBetween('created_at', [$bb, Carbon\Carbon::now()]);
        }

        $TotalSaq = $baseQuerySaque;

        // Resto do código para AffiliatesHistory
        $total_rev = App\Models\AffiliatesHistory::Where('game', '!=', 'CPA');
        $count_rev = App\Models\AffiliatesHistory::Where('game', '!=', 'CPA');

        if ($b && $c) {
            $total_rev->whereBetween('updated_at', [$bb, $cc]);
            $count_rev->whereBetween('updated_at', [$bb, $cc]);
        } elseif ($b && !$c) {
            $total_rev->whereBetween('updated_at', [$bb, Carbon\Carbon::now()]);
            $count_rev->whereBetween('updated_at', [$bb, Carbon\Carbon::now()]);
        }

        $total_rev = $total_rev->where($user_a ? 'user_id' : 'inviter', $user_a ? : $IdUser)->sum('amount');
        $count_rev = $count_rev->where($user_a ? 'user_id' : 'inviter', $user_a ? : $IdUser)->count();

        $total_cpa = App\Models\AffiliatesHistory::Where('game', 'CPA');
        $count_cpa = App\Models\AffiliatesHistory::Where('game', 'CPA');

        if ($b && $c) {
            $total_cpa->whereBetween('updated_at', [$bb, $cc]);
            $count_cpa->whereBetween('updated_at', [$bb, $cc]);
        } elseif ($b && !$c) {
            $total_cpa->whereBetween('updated_at', [$bb, Carbon\Carbon::now()]);
            $count_cpa->whereBetween('updated_at', [$bb, Carbon\Carbon::now()]);
        }

        $total_cpa = $total_cpa->where($user_a ? 'user_id' : 'inviter', $user_a ? : $IdUser)->sum('amount');
        $count_cpa = $count_cpa->where($user_a ? 'user_id' : 'inviter', $user_a ? : $IdUser)->count();

        $total_all = $total_rev + $total_cpa;
    @endphp

    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Afiliados</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Estátistica Gerente</li>
                    </ol>
                </nav>
            </div>

            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <div class="row" style="margin-bottom: 16px;">
                            <div class="col-md-3">
                                <label for="dataInicial" class="form-label">Data Inicial:</label>
                                <input type="date" id="dataInicial" name="di" class="form-control filter-input" value="{{ $b }}">
                            </div>
                            <div class="col-md-3">
                                <label for="dataFinal" class="form-label">Data Final:</label>
                                <input type="date" id="dataFinal" name="df" class="form-control filter-input" value="{{ $c }}">
                            </div>
                            <div class="col">
                                <label for="nomeUsuario" class="form-label">Afiliado:</label>
                                <input type="text" id="example2" name="example2" placeholder="Insira o nome do afiliado..." value="{{$a}}" class="form-control filter-input" onblur="ReloadifEmpty(this);">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @if(!$user_a)
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                                <div class="widget widget-card-four">
                                    <div class="widget-content">
                                        <div class="w-header">
                                            <div class="w-info">
                                                <h6 class="value" style="text-align: left;">Registros e Depósitos</h6>
                                            </div>
                                        </div>

                                        <div class="w-content">
                                            <div class="w-info">
                                                <div class="table-content" style="background: #00000012;padding: 10px;">
                                                    <div class="table-content-insert">
                                                        <div class="label">Nº Registros</div>
                                                        <div class="value">{{$Registros}}</div>
                                                    </div>
                                                </div>

                                                <div class="table-content" style="padding: 10px;">
                                                    <div class="table-content-insert">
                                                        <div class="label">Nº Primeiros Depósitos</div>
                                                        <div class="value">{{$PrimeirosDep}}</div>
                                                    </div>
                                                </div>

                                                <div class="table-content" style="padding: 10px;">
                                                    <div class="table-content-insert">
                                                        <div class="label">Nº Segundos Depósitos</div>
                                                        <div class="value">{{$SegundosDep}}</div>
                                                    </div>
                                                </div>

                                                <div class="table-content" style="padding: 10px;">
                                                    <div class="table-content-insert">
                                                        <div class="label">Nº Terceiros ou mais Depósitos</div>
                                                        <div class="value">{{$TerceirosDep}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="{{($user_a) ? "col-xl-12" : "col-xl-6"}} col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-card-four">
                                <div class="widget-content">
                                    <div class="w-header">
                                        <div class="w-info">
                                            <h6 class="value" style="text-align: left;">Depósitos e Saques</h6>
                                        </div>
                                    </div>

                                    <div class="w-content">
                                        <div class="w-info">
                                            <div class="table-content" style="background: #00000012;padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Total em Depósitos ({{ $TotalDep->count() }})</div>
                                                    <div class="value">R$&nbsp;{{number_format($TotalDep->sum('amount'), 2, ',', '.')}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Total em Saques ({{ $TotalSaq->count() }})</div>
                                                    <div class="value">R$ {{number_format($TotalSaq->sum('amount'), 2, ',', '.')}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label"> </div>
                                                    <div class="value"> </div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="background: #00000012;padding: 10px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Líquido</div>
                                                    <div class="value">R$&nbsp;{{number_format($TotalDep->sum('amount') - $TotalSaq->sum('amount'), 2, ',', '.')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-card-four">
                                <div class="widget-content">
                                    <div class="w-header">
                                        <div class="w-info">
                                            <h6 class="value" style="text-align: left;white-space: nowrap;">CPA</h6>
                                        </div>
                                    </div>

                                    <div class="w-content">
                                        <div class="w-info">
                                            <div class="table-content" style="background: #0000001c;padding: 5px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Depósitos</div>
                                                    <div class="value">R$&nbsp;{{number_format($total_cpa, 2, ',', '.')}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="padding: 5px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Número de Depósitos</div>
                                                    <div class="value">{{$count_cpa}}</div>
                                                </div>
                                            </div>

                                            <div class="dividerx" style="margin-bottom: 10px;"></div>
                                            <div class="table-content" style="padding: 5px;">
                                                <div class="table-content-insert" style="color: #4bff00;">
                                                    <div class="label">Comissão total</div>
                                                    <div class="value">R$&nbsp;{{number_format($total_cpa, 2, ',', '.')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                            <div class="widget widget-card-four">
                                <div class="widget-content">
                                    <div class="w-header">
                                        <div class="w-info">
                                            <h6 class="value" style="text-align: left;">RevShare</h6>
                                        </div>
                                    </div>

                                    <div class="w-content">
                                        <div class="w-info">
                                            <div class="table-content" style="background: #0000001c;padding: 5px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Valor líquido de revshare</div>
                                                    <div class="value">R$ {{number_format($total_rev, 2, ',', '.')}}</div>
                                                </div>
                                            </div>

                                            <div class="table-content" style="padding: 5px;">
                                                <div class="table-content-insert">
                                                    <div class="label">Número de jogos</div>
                                                    <div class="value">{{$count_rev}}</div>
                                                </div>
                                            </div>

                                            <div class="dividerx" style="margin-bottom: 10px;"></div>
                                            <div class="table-content" style="padding: 5px;">
                                                <div class="table-content-insert" style="color: #4bff00;">
                                                    <div class="label">Comissão total</div>
                                                    <div class="value">R$&nbsp;{{number_format($total_rev, 2, ',', '.')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="panelx commission-background">
                            <div class="comissão-footer">
                                <h2 style="font-weight: 900;text-transform: uppercase;">Total de Receita</h2>
                            </div>

                            <div class="comissão-footer-values">
                                <p>Receita Geral:</p>
                                <h2 style="color:#44e305;font-weight: 900;">R$&nbsp;{{number_format($total_all + ($TotalDep->sum('amount') - $TotalSaq->sum('amount')), 2, ',', '.')}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function ReloadifEmpty(campo) {
                if (campo.value === '') {
                    var dataInicial = $('#dataInicial').val();
                    var dataFinal = $('#dataFinal').val();

                    var newUrl = "{{ route('admin.afiliacao.estatisticas.gerente') }}?di=" + dataInicial + "&df=" + dataFinal;
                    window.location.href = newUrl;
                }
            }
            function EnableAutoComplete() {
                const example2 = new autoComplete({
                    selector: "#example2",
                    placeHolder: "Insira o nome do afiliado...",
                    data: {
                        src: async (query) => {
                            if (!query) return [];

                            try {
                                const response = await fetch(`/adm-sr-ag?name=${query}`);
                                const data = await response.json();

                                return data.map(user => user.name);
                            } catch (error) {
                                console.error("Error fetching data:", error);
                                return [];
                            }
                        },
                        cache: false,
                    },
                    resultsList: {
                        element: (list, data) => {
                            if (!data.results.length) {
                                const message = document.createElement("div");
                                message.setAttribute("class", "no_result");
                                message.innerHTML = '<span>Nenhum resultado encontrado!</span>';
                                list.prepend(message);
                            }
                        },
                        noResults: true,
                    },
                    resultItem: {
                        highlight: {
                            render: true
                        }
                    },
                    events: {
                        input: {
                            focus() {
                                if (example2.input.value.length) example2.start();
                            },
                            selection(event) {
                                const feedback = event.detail;
                                const selection = feedback.selection.value;

                                example2.input.value = selection;

                                var dataInicial = $('#dataInicial').val();
                                var dataFinal = $('#dataFinal').val();
                                var nomeUsuario = $('#example2').val();

                                var newUrl = "{{ route('admin.afiliacao.estatisticas.gerente') }}?di=" + dataInicial + "&df=" + dataFinal + "&aff=" + encodeURIComponent(nomeUsuario);
                                window.location.href = newUrl;
                            },
                        },
                    },
                });
            }

            $(function() {
                EnableAutoComplete();

                // Variável para armazenar o timeout da digitação
                var typingTimer;
                var doneTypingInterval = 500; // Tempo em ms para aguardar após a digitação

                // Função para aplicar os filtros e atualizar a página
                function applyFilters() {
                    var dataInicial = $('#dataInicial').val();
                    var dataFinal = $('#dataFinal').val();
                    var nomeUsuario = $('#example2').val();

                    // Atualizar a URL com os novos parâmetros
                    var newUrl = "{{ route('admin.afiliacao.estatisticas.gerente') }}?di=" + dataInicial + "&df=" + dataFinal + "&aff=" + encodeURIComponent(nomeUsuario);
                    window.location.href = newUrl;
                }

                // Evento para campos de data - aplicar filtro imediatamente ao mudar
                $('.filter-input[type="date"]').on('change', function() {
                    applyFilters();
                });

                // Evento para campo de texto - aplicar filtro após parar de digitar
                $('#nomeUsuario').on('keyup', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(applyFilters, doneTypingInterval);
                });

                $('#nomeUsuario').on('keydown', function() {
                    clearTimeout(typingTimer);
                });

                // Evento para os botões de período
                $('.period-btn').on('click', function() {
                    var periodo = $(this).data('period');
                    let dataInicial = $('#dataInicial');
                    let dataFinal = $('#dataFinal');
                    let hoje = new Date();

                    if (periodo === 'hoje') {
                        let formattedDate = hoje.toISOString().split('T')[0];
                        dataInicial.val(formattedDate);
                        dataFinal.val(formattedDate);
                    } else if (periodo === '7' || periodo === '15' || periodo === '30') {
                        let dias = parseInt(periodo);
                        let dataPassada = new Date(hoje);
                        dataPassada.setDate(hoje.getDate() - dias);
                        dataInicial.val(dataPassada.toISOString().split('T')[0]);
                        dataFinal.val(hoje.toISOString().split('T')[0]);
                    } else if (periodo === 'geral') {
                        dataInicial.val('');
                        dataFinal.val('');
                    }

                    // Após definir as datas, aplica os filtros
                    applyFilters();
                });
            });
        </script>
    @endpush
@endsection
