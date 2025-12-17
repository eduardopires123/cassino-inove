<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detalhes do Usuário</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nome:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>ID:</strong> {{ $user->id }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Período Analisado:</strong> {{ $startDate->format('d/m/Y') }} até {{ $endDate->format('d/m/Y') }}</p>
                            <p><strong>Total de Perdas:</strong> R$ {{ number_format($details['grand_total'], 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Resumo de Perdas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Categoria</th>
                                    <th>Valor (R$)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cassino</td>
                                    <td>R$ {{ number_format($details['virtual']['total'], 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Apostas Esportivas</td>
                                    <td>R$ {{ number_format($details['sports']['total'], 2, ',', '.') }}</td>
                                </tr>
                                <tr class="table-primary">
                                    <td><strong>Total</strong></td>
                                    <td><strong>R$ {{ number_format($details['grand_total'], 2, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Perdas por Distribuição</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Distribuição</th>
                                    <th>Valor (R$)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($details['virtual']['by_provider'] as $provider => $loss)
                                <tr>
                                    <td>{{ $provider }}</td>
                                    <td>R$ {{ number_format($loss, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                @if($details['sports']['total'] > 0)
                                <tr>
                                    <td>Sports</td>
                                    <td>R$ {{ number_format($details['sports']['total'], 2, ',', '.') }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Histórico de Apostas Esportivas Perdidas</h5>
                    <span class="badge bg-primary">Total: {{ count($sportsLosses) }} apostas</span>
                </div>
                <div class="card-body">
                    @if(count($sportsLosses) > 0)
                    <div class="table-responsive">
                        <table id="sports-losses-table" class="table table-striped dt-table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>ID da Transação</th>
                                    <th>Valor Apostado</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sportsLosses as $bet)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($bet->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $bet->transactionId }}</td>
                                    <td>R$ {{ number_format($bet->bet_amount, 2, ',', '.') }}</td>
                                    <td>
                                        @if(!empty($bet->betslip))
                                            @php
                                                $betslip = json_decode($bet->betslip);
                                                $eventNames = [];
                                                
                                                // Verificar se existe a estrutura de aposta
                                                if (isset($betslip->bet_stakes)) {
                                                    // Se for aposta múltipla (mais de um evento)
                                                    if (isset($betslip->bet_stakes->BetStakes) && is_array($betslip->bet_stakes->BetStakes) && count($betslip->bet_stakes->BetStakes) > 1) {
                                                        foreach ($betslip->bet_stakes->BetStakes as $stake) {
                                                            if (isset($stake->EventNameOnly)) {
                                                                $eventNames[] = $stake->EventNameOnly;
                                                            }
                                                        }
                                                    }
                                                    // Se for aposta única
                                                    elseif (isset($betslip->bet_stakes->BetStakes) && is_array($betslip->bet_stakes->BetStakes) && count($betslip->bet_stakes->BetStakes) == 1) {
                                                        if (isset($betslip->bet_stakes->BetStakes[0]->EventNameOnly)) {
                                                            $eventNames[] = $betslip->bet_stakes->BetStakes[0]->EventNameOnly;
                                                        }
                                                    }
                                                    // Se for outro formato de dados
                                                    elseif (isset($betslip->bet_stakes->EventNameOnly)) {
                                                        $eventNames[] = $betslip->bet_stakes->EventNameOnly;
                                                    }
                                                }
                                            @endphp
                                            
                                            @if(count($eventNames) > 1)
                                                <span class="badge bg-warning" data-bs-toggle="tooltip" data-bs-html="true" 
                                                    title="{{ implode('<br>', $eventNames) }}">
                                                    Aposta Múltipla ({{count($eventNames)}} eventos)
                                                </span>
                                            @elseif(count($eventNames) == 1)
                                                <span class="badge bg-info me-2">{{$eventNames[0]}}</span>
                                            @else
                                                Sem detalhes disponíveis
                                            @endif
                                        @else
                                            Sem detalhes disponíveis
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info">Nenhuma aposta esportiva perdida no período.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Histórico de Jogos de Cassino Perdidos</h5>
                    <span class="badge bg-primary">Total: {{ count($virtualLosses) }} jogos</span>
                </div>
                <div class="card-body">
                    @if(count($virtualLosses) > 0)
                    <div class="table-responsive">
                        <table id="virtual-losses-table" class="table table-striped dt-table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Jogo</th>
                                    <th>Distribuição</th>
                                    <th>Provedor</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($virtualLosses as $game)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($game->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $game->game_name ?? 'N/A' }}</td>
                                    <td>{{ $game->provider }}</td>
                                    <td>{{ $game->provider_name }}</td>
                                    <td>R$ {{ number_format($game->amount, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info">Nenhum jogo virtual perdido no período.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    // Inicializar tooltips do Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            boundary: 'window',
            placement: 'top',
            html: true,
            maxWidth: '300px'
        });
    });
    
    // Inicializar DataTable para tabela de apostas esportivas
    if ($('#sports-losses-table').length > 0) {
        $('#sports-losses-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Processando...</span></div>',
                paginate: {
                    first: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>',
                    previous: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    next: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                    last: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>'
                },
                info: "Mostrando página _PAGE_ de _PAGES_"
            },
            dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                "<'table-responsive'tr>" +
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'desc']],
            drawCallback: function(settings) {
                var api = this.api();
                var info = api.page.info();
                
                $('#sports-losses-table_paginate').addClass('paging_simple_numbers');
                $('#sports-losses-table_paginate ul.pagination li').addClass('paginate_button page-item');
                $('#sports-losses-table_paginate ul.pagination li.previous').attr('id', 'sports-losses-table_previous');
                $('#sports-losses-table_paginate ul.pagination li.next').attr('id', 'sports-losses-table_next');
                $('#sports-losses-table_paginate ul.pagination li a').addClass('page-link');
                
                // Substituir ícones de paginação
                $('#sports-losses-table_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                $('#sports-losses-table_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                $('#sports-losses-table_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                $('#sports-losses-table_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');
                
                // Reinicializar tooltips após o desenho da tabela
                var newTooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var newTooltipList = newTooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        boundary: 'window',
                        placement: 'top',
                        html: true,
                        maxWidth: '300px'
                    });
                });
            }
        });
    }
    
    // Inicializar DataTable para tabela de jogos virtuais
    if ($('#virtual-losses-table').length > 0) {
        $('#virtual-losses-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Processando...</span></div>',
                paginate: {
                    first: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>',
                    previous: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    next: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                    last: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>'
                },
                info: "Mostrando página _PAGE_ de _PAGES_"
            },
            dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                "<'table-responsive'tr>" +
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            order: [[0, 'desc']],
            drawCallback: function(settings) {
                var api = this.api();
                var info = api.page.info();
                
                $('#virtual-losses-table_paginate').addClass('paging_simple_numbers');
                $('#virtual-losses-table_paginate ul.pagination li').addClass('paginate_button page-item');
                $('#virtual-losses-table_paginate ul.pagination li.previous').attr('id', 'virtual-losses-table_previous');
                $('#virtual-losses-table_paginate ul.pagination li.next').attr('id', 'virtual-losses-table_next');
                $('#virtual-losses-table_paginate ul.pagination li a').addClass('page-link');
                
                // Substituir ícones de paginação
                $('#virtual-losses-table_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                $('#virtual-losses-table_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                $('#virtual-losses-table_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                $('#virtual-losses-table_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');
                
                // Reinicializar tooltips após o desenho da tabela
                var newTooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var newTooltipList = newTooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        boundary: 'window',
                        placement: 'top',
                        html: true,
                        maxWidth: '300px'
                    });
                });
            }
        });
    }
});
</script>

<style>
/* Estilo customizado para tooltips */
.tooltip-inner {
    max-width: 350px;
    padding: 8px 12px;
    text-align: left;
    white-space: normal;
    overflow: visible;
}

/* Para tooltips muito longos, adicionar scroll horizontal */
@media (max-width: 768px) {
    .tooltip-inner {
        max-width: 250px;
    }
}
body.dark .page-item.active .page-link {
    color: #ffffff!important;
}
</style> 