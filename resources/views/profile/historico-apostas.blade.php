@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<div class="_2sSj3">
    <div class="is0Ic">
        @include('profile.partials.menu')
<div class="cnynX">
    <div class="fVeX8" data-headerheight="65" data-topbarheight="0" data-v-owner="269" style="--236d1da4: 65px;">
        <a class="nuxt-icon nuxt-icon--fill pvpfG" href="{{ route('user.wallet') }}">
            <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"
                    fill="currentColor"
                ></path>
            </svg>
        </a>
        <span class="DxKO1">{{ __('menu.sports_betting_history') }}</span>
        <div class="nu8zQ"></div>
    </div>
    <div class="KHLto" data-v-owner="269" style="--236d1da4: 65px;">
        <div class="_6NoZq">
            <!---->
            <div class="identifierToGoTop">
                <!---->
                <div class="F9gAI">
                    <button aria-hidden="true" type="button" style="position: fixed; height: 0px; padding: 0px; overflow: hidden; clip: rect(0px, 0px, 0px, 0px); white-space: nowrap; border-width: 0px;"></button>
                    <!---->
                    @include('profile.partials.filter-date')
                    <div class="_6LUMB">
                        <div id="headlessui-tabs-panel-nsiNM9WAguS_1" role="tabpanel" tabindex="0" data-headlessui-state="selected" class="rounded-xl ring-white outline-none">
                            <div class="rqI4A">
                                <table class="UHNq-">
                                    <tr class="HGAlV">
                                        <th>{{ __('menu.type') }}</th>
                                        <th>{{ __('menu.id') }}</th>
                                        <th>{{ __('menu.source') }}</th>
                                        <th>{{ __('menu.amount') }}</th>
                                        <th>{{ __('menu.status') }}</th>
                                        <th>{{ __('menu.date') }}</th>
                                        <th>{{ __('menu.actions') }}</th>
                                    </tr>
                                    @forelse($history as $item)
                                    <tr class="WXGKq" data-transaction-id="{{ $item->transactionId ?? $item->id }}" data-operation="{{ $item->operation ?? 'unknown' }}" data-cashout="{{ property_exists($item, 'is_cashout') ? $item->is_cashout : '0' }}" data-bet-type="{{ property_exists($item, 'bet_type') ? $item->bet_type : 'simple' }}" data-provider="{{ $item->provider ?? 'unknown' }}">
                                        <td data-name="type" style="width: 50px; text-align: center;">
                                            <div class="H32ns Vj0b-" style="
                                                @if($item->operation == 'debit')
                                                    background-color: #2196f3 !important;
                                                    color: white !important;
                                                @elseif($item->operation != 'credit')
                                                    background-color: rgb(245, 47, 47) !important;
                                                    color: white !important;
                                                @else
                                                    background-color: #2b3 !important;
                                                    color: white !important;
                                                @endif
                                            ">
                                                <span class="nuxt-icon nuxt-icon--fill">
                                                    @if($item->operation == 'credit')
                                                    <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"
                                                            fill="currentColor"
                                                        ></path>
                                                    </svg>
                                                    @elseif($item->operation == 'debit')
                                                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 464c-114.7 0-208-93.31-208-208S141.3 48 256 48s208 93.31 208 208S370.7 464 256 464zM256 232c13.25 0 24-10.75 24-24c0-13.26-10.75-24-24-24S232 194.7 232 208C232 221.3 242.7 232 256 232zM304 368h-16V256c0-8.836-7.164-16-16-16h-32c-8.836 0-16 7.164-16 16s7.164 16 16 16h16v96h-16c-8.836 0-16 7.164-16 16s7.164 16 16 16h64c8.836 0 16-7.164 16-16S312.8 368 304 368z"
                                                            fill="currentColor"
                                                        ></path>
                                                    </svg>
                                                    @else
                                                    <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M169.4 470.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 370.8V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v306.7L54.6 265.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z"
                                                            fill="currentColor"
                                                        ></path>
                                                    </svg>
                                                    @endif
                                                </span>
                                            </div>
                                        </td>
                                        <td data-name="id">{{ $item->id }}</td>
                                        <td data-name="source" class="bet-source">
                                            <span class="bet-type-text">
                                            @if(property_exists($item, 'bet_type') && $item->bet_type == 'multiple')
                                                {{ __('menu.multiple_bet') }}
                                            @else
                                                {{ __('menu.simple_bet') }}
                                            @endif
                                            </span>
                                        </td>
                                        <td data-name="amount">R$&nbsp;{{ number_format($item->amount, 2, ',', '.') }}</td>
                                        <td data-name="status">
                                            <div class="H32ns Vj0b-" style="
                                                display: inline-block;
                                                white-space: nowrap;
                                                @if($item->operation == 'credit')
                                                    background-color: #2b3 !important;
                                                    color: white !important;
                                                @elseif(property_exists($item, 'is_cashout') && $item->is_cashout == 1)
                                                    background-color: #8bc34a !important;
                                                    color: white !important;
                                                @elseif(property_exists($item, 'status') && $item->status == 'cancelled')
                                                    background-color: #f39c12 !important;
                                                    color: white !important;
                                                @elseif($item->operation == 'debit')
                                                    background-color: #2196f3 !important;
                                                    color: white !important;
                                                @else
                                                    background-color: rgb(245, 47, 47) !important;
                                                    color: white !important;
                                                @endif
                                            ">
                                                @if($item->operation == 'credit')
                                                    <small><i class="fas fa-check-circle mr-1"></i> {{ __('menu.won') }}</small>
                                                @elseif(property_exists($item, 'is_cashout') && $item->is_cashout == 1)
                                                    <small><i class="fas fa-exchange-alt mr-1"></i> Cashout</small>
                                                @elseif(property_exists($item, 'status') && $item->status == 'cancelled')
                                                    <small><i class="fas fa-ban mr-1"></i> Cancelada</small>
                                                @elseif($item->operation == 'debit')
                                                    <small><i class="fas fa-hourglass-half mr-1"></i> Aposta</small>
                                                @else
                                                    <small><i class="fas fa-times-circle mr-1"></i> {{ __('menu.lost') }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td data-name="created_at">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y, H:i:s') }}</td>
                                        <td data-name="actions">
                                            @if($activeProvider === 'betby')
                                                {{-- Betby: Redirecionar para /sports/bets --}}
                                                <a href="{{ url('/sports/bets') }}" class="eye-icon-btn" target="_blank" 
                                                   title="Ver histórico de apostas Betby">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            @else
                                                {{-- Digitain: Abrir modal (funcionalidade original) --}}
                                                <button class="eye-icon-btn ver-aposta" type="button" 
                                                        data-betslip="{{ $item->betslip ?? '' }}"
                                                        data-id="{{ $item->id }}"
                                                        data-transaction-id="{{ $item->transactionId ?? $item->id }}"
                                                        data-operation="{{ $item->operation == 'credit' ? 'credit' : ($item->operation == 'debit' ? 'debit' : 'lose') }}"
                                                        data-amount="{{ $item->amount }}"
                                                        data-received-amount="{{ $item->operation == 'credit' ? $item->amount : '0' }}"
                                                        data-cashout="{{ property_exists($item, 'is_cashout') ? $item->is_cashout : '0' }}"
                                                        data-provider="{{ $item->provider ?? 'unknown' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="WXGKq">
                                        <td colspan="7" class="text-center py-4">{{ __('menu.no_records_found') }}</td>
                                    </tr>
                                    @endforelse
                                </table>
                            </div>
                            @include('partials.pagination', [
                                'items' => $history,
                                'paginationId' => 'history-pagination'
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!-- Modal Ver Aposta -->
<div class="hS9Wq" id="verApostaModal" style="display: none;">
    <div class="pOZuF">
        <div class="rdEzG">
            <div class="_3lvVF">
                <div class="EzYxM">
                    <header id="topBar" class="f-6B3">
                        <div class="Yi2c7">
                        </div>
                        <div class="_3lQOP">
                            <button class="_8Plb- PZR2U Je4se close-bet-modal" type="button">
                                <span class="inove-icon inove-icon--fill">
                                    <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z" fill="currentColor"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </header>
                    <div class="QWiLj">
                        <div class="w-full p-5">
                            <!-- Conteúdo do Modal -->
                            <div class="bet-details-container w-full flex flex-col gap-4">
                                <!-- Informações Gerais -->
                                <div class="bet-general-info" style="background-color: #323637; border-radius: 10px; padding: 12px; margin-bottom: 10px;">
                                    <h3 class="text-white text-base font-bold mb-2">Informações Gerais da Aposta</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        <div class="info-item">
                                            <span class="text-gray-400 text-sm">Valor apostado:</span>
                                            <div id="betAmount" class="text-white font-semibold"></div>
                                        </div>
                                        <div class="info-item">
                                            <span class="text-gray-400 text-sm">Retorno potencial:</span>
                                            <div id="maxWinAmount" class="text-white font-semibold"></div>
                                        </div>
                                        <div class="info-item">
                                            <span class="text-gray-400 text-sm">Valor recebido:</span>
                                            <div id="receivedAmount" class="text-white font-semibold"></div>
                                        </div>
                                        <div class="info-item">
                                            <span class="text-gray-400 text-sm">Status da aposta:</span>
                                            <div id="betStatus" class="font-semibold"></div>
                                        </div>
                                        <div class="info-item">
                                            <span class="text-gray-400 text-sm">Odds totais:</span>
                                            <div id="factorTotal" class="text-white font-semibold"></div>
                                        </div>
                                        <div class="info-item">
                                            <span class="text-gray-400 text-sm">Tipo de aposta:</span>
                                            <div id="betType" class="text-white font-semibold"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Container para apostas múltiplas -->
                                <div id="multipleEventsContainer" class="multiple-events">
                                    <!-- Aqui serão inseridos dinamicamente os cards de eventos na aposta múltipla -->
                                </div>
                                
                                <!-- Campo oculto para manter a funcionalidade de cashout -->
                                <div style="display: none;">
                                    <div id="isCashout"></div>
                                    <div class="single-cashout-field"></div>
                                </div>
                                
                                <!-- Removido o bloco de evento único, agora usando apenas o formato de cards -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="GovTb"></div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/bet-user.js') }}"></script>
<script>
$(document).ready(function() {
    // Verificar se o provedor ativo é Betby
    const activeProvider = '{{ $activeProvider ?? "digitain" }}';
    
    // Função para verificar apostas múltiplas baseado no betslip
    function analyzeBetslipAndUpdateBetType() {
        // Processar todas as linhas da tabela
        $('table.UHNq- tr.WXGKq').each(function() {
            const row = $(this);
            const viewButton = row.find('.ver-aposta');
            
            if (viewButton.length) {
                // Pegar o betslip do botão
                const betslipData = viewButton.attr('data-betslip');
                const provider = row.attr('data-provider') || 'unknown';
                
                if (betslipData && betslipData !== '' && betslipData !== '{}') {
                    try {
                        // Decodificar entidades HTML se necessário
                        let processedBetslip = betslipData;
                        if (processedBetslip.includes('&quot;')) {
                            const textarea = document.createElement('textarea');
                            textarea.innerHTML = processedBetslip;
                            processedBetslip = textarea.value;
                        }
                        
                        // Tentar parsear o betslip
                        const betslipObj = JSON.parse(processedBetslip);
                        
                        // Verificar se é uma aposta múltipla baseado no provedor
                        let isMultiple = false;
                        
                        if (provider === 'betby') {
                            // Betby: usar estrutura betslip.bets e betslip.type
                            if (betslipObj && betslipObj.betslip) {
                                // Verificar pelo tipo (ex: "3/3" = múltipla)
                                if (betslipObj.betslip.type && betslipObj.betslip.type.includes('/')) {
                                    const typeParts = betslipObj.betslip.type.split('/');
                                    if (typeParts.length >= 2 && parseInt(typeParts[0]) > 1) {
                                        isMultiple = true;
                                    }
                                }
                                // Verificar pela quantidade de bets
                                if (betslipObj.betslip.bets && Array.isArray(betslipObj.betslip.bets) && 
                                    betslipObj.betslip.bets.length > 1) {
                                    isMultiple = true;
                                }
                            }
                        } else {
                            // Digitain: usar estrutura tradicional de bet_stakes
                            if (betslipObj && betslipObj.bet_stakes) {
                                if ((betslipObj.bet_stakes.FullName && betslipObj.bet_stakes.FullName.includes("Multi")) ||
                                    betslipObj.bet_stakes.BetTypeId === "3" ||
                                    (betslipObj.bet_stakes.BetStakes && Array.isArray(betslipObj.bet_stakes.BetStakes) && 
                                    betslipObj.bet_stakes.BetStakes.length > 1)) {
                                    isMultiple = true;
                                }
                            }
                        }
                        
                        // Atualizar a linha com o tipo de aposta correto
                        row.attr('data-bet-type', isMultiple ? 'multiple' : 'simple');
                        
                        // Atualizar o texto na coluna de fonte
                        const sourceCell = row.find('[data-name="source"] .bet-type-text');
                        if (sourceCell.length) {
                            sourceCell.text(isMultiple ? 
                                "{{ __('menu.multiple_bet') }}" : 
                                "{{ __('menu.simple_bet') }}");
                        }
                    } catch (e) {
                        console.error('Erro ao analisar betslip:', e);
                    }
                }
            }
        });
    }
    
    // Executar análise inicial apenas se não for Betby
    if (activeProvider !== 'betby') {
        analyzeBetslipAndUpdateBetType();
    }
    
    // Adicionar ouvinte para paginação apenas se não for Betby
    if (activeProvider !== 'betby') {
        $(document).on('click', '#history-pagination a.page-link', function() {
            // Executar análise após um pequeno atraso para garantir que os dados foram carregados
            setTimeout(analyzeBetslipAndUpdateBetType, 500);
        });
    }
    
    // Adicionar listener para quando um usuário clica no botão Ver Aposta (apenas Digitain)
    if (activeProvider !== 'betby') {
        $(document).on('click', '.ver-aposta', function() {
            const $this = $(this);
            const row = $this.closest('tr');
            const transactionId = $this.attr('data-transaction-id');
            const provider = $this.attr('data-provider') || row.attr('data-provider') || 'unknown';
            
            // Após exibir os detalhes da aposta, verifique a estrutura do betslip para determinar o tipo
            setTimeout(function() {
                // Verificar se há dados de betslip disponíveis após a abertura do modal
                let betslipData = $this.attr('data-betslip');
                
                if (betslipData && betslipData !== '' && betslipData !== '{}') {
                    try {
                        // Decodificar entidades HTML se necessário
                        let processedBetslip = betslipData;
                        if (processedBetslip.includes('&quot;')) {
                            const textarea = document.createElement('textarea');
                            textarea.innerHTML = processedBetslip;
                            processedBetslip = textarea.value;
                        }
                        
                        // Tentar parsear o betslip
                        const betslipObj = JSON.parse(processedBetslip);
                        
                        // Verificar se é uma aposta múltipla baseado no provedor
                        let isMultiple = false;
                        
                        if (provider === 'betby') {
                            // Betby: usar estrutura betslip.bets e betslip.type
                            if (betslipObj && betslipObj.betslip) {
                                // Verificar pelo tipo (ex: "3/3" = múltipla)
                                if (betslipObj.betslip.type && betslipObj.betslip.type.includes('/')) {
                                    const typeParts = betslipObj.betslip.type.split('/');
                                    if (typeParts.length >= 2 && parseInt(typeParts[0]) > 1) {
                                        isMultiple = true;
                                    }
                                }
                                // Verificar pela quantidade de bets
                                if (betslipObj.betslip.bets && Array.isArray(betslipObj.betslip.bets) && 
                                    betslipObj.betslip.bets.length > 1) {
                                    isMultiple = true;
                                }
                            }
                        } else {
                            // Digitain: usar estrutura tradicional de bet_stakes
                            if (betslipObj && betslipObj.bet_stakes) {
                                if ((betslipObj.bet_stakes.FullName && betslipObj.bet_stakes.FullName.includes("Multi")) ||
                                    betslipObj.bet_stakes.BetTypeId === "3" ||
                                    (betslipObj.bet_stakes.BetStakes && Array.isArray(betslipObj.bet_stakes.BetStakes) && 
                                    betslipObj.bet_stakes.BetStakes.length > 1)) {
                                    isMultiple = true;
                                }
                            }
                        }
                        
                        // Atualizar TODAS as linhas com o mesmo transaction ID
                        $(`tr[data-transaction-id="${transactionId}"]`).each(function() {
                            const relatedRow = $(this);
                            relatedRow.attr('data-bet-type', isMultiple ? 'multiple' : 'simple');
                            
                            // Atualizar o texto na coluna de fonte
                            const sourceCell = relatedRow.find('[data-name="source"] .bet-type-text');
                            if (sourceCell.length) {
                                sourceCell.text(isMultiple ? 
                                    "{{ __('menu.multiple_bet') }}" : 
                                    "{{ __('menu.simple_bet') }}");
                            }
                        });
                        
                        // Após visualizar e analisar a aposta, atualize o atributo data-betslip no botão
                        // para que futuras análises possam acessar esses dados sem precisar clicar novamente
                        // na mesma aposta
                        $this.attr('data-processed-bet-type', isMultiple ? 'multiple' : 'simple');
                        
                    } catch (e) {
                        console.error('Erro ao analisar betslip após visualização:', e);
                    }
                }
                
                // Verificar se há multi-eventos no modal - outra maneira de detectar apostas múltiplas
                const multipleEventsContainer = document.getElementById('multipleEventsContainer');
                if (multipleEventsContainer && multipleEventsContainer.style.display !== 'none' && 
                    multipleEventsContainer.children.length > 0) {
                    
                    // É uma aposta múltipla baseada no conteúdo do modal
                    $(`tr[data-transaction-id="${transactionId}"]`).each(function() {
                        const relatedRow = $(this);
                        relatedRow.attr('data-bet-type', 'multiple');
                        
                        // Atualizar o texto na coluna de fonte
                        const sourceCell = relatedRow.find('[data-name="source"] .bet-type-text');
                        if (sourceCell.length) {
                            sourceCell.text("{{ __('menu.multiple_bet') }}");
                        }
                    });
                    
                    // Atualizar o botão para referência futura
                    $this.attr('data-processed-bet-type', 'multiple');
                }
                
            }, 1000); // Aguardar 1 segundo para garantir que o modal foi aberto e os dados processados
        });
    }
});
</script>
@endpush

<style>
.hS9Wq {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1050;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(0, 0, 0, 0.7);
}

.pOZuF {
  max-height: 750px; /* Limit height to 80% of viewport height */
  margin: 0 auto;
  position: relative;
  border-radius: 12px;
  overflow: hidden; /* Ensure content doesn't overflow */
}

.rdEzG {
  background-color: #212425;
  border-radius: 12px;
  overflow: hidden;
  display: inline!important;
}

.EzYxM {
  width: 100%;
  background-color: #323637;
  color: white;
}

.f-6B3 {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.Yi2c7 {
  display: flex;
  align-items: center;
}

.vQI8R {
  display: flex;
  align-items: center;
  gap: 10px;
  color: white;
}

.jSHow {
  color: var(--primary-color);
  font-size: 1.2rem;
}

._3lQOP {
  display: flex;
  align-items: center;
}

.PZR2U {
  background: transparent;
  border: none;
  cursor: pointer;
  color: white;
  font-size: 1.2rem;
}

.QWiLj {
  background-color: #212425;
  max-height: calc(90vh - 60px); /* Account for header height */
  overflow-y: auto; /* Enable vertical scrolling */
  overflow-x: hidden; /* Hide horizontal scrollbar */
}

.bet-details-container {
  background-color: #212425;
  color: white;
}

.bet-general-info, .event-info, .bet-info {
  background-color: #323637 !important;
  border-radius: 10px;
  padding: 15px;
}

.info-item {
  margin-bottom: 10px;
}

/* Eye Icon Button Styles */
.eye-icon-btn {
  background-color: var(--primary-color);
  color: var(--text-btn-primary);
  border: none;
  border-radius: 5px;
  padding: 8px 15px;
  font-size: 14px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.eye-icon-btn:hover {
  opacity: 0.9;
}

.eye-icon-btn svg {
  width: 16px;
  height: 16px;
}

/* New styles for improved display */
.odds-badge {
  display: inline-block;
  background-color: var(--primary-color);
  color: white;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 12px;
}

.mr-1 {
  margin-right: 4px;
}

.mr-2 {
  margin-right: 8px;
}

.flex {
  display: flex;
}

.justify-between {
  justify-content: space-between;
}

.items-center {
  align-items: center;
}

.event-card {
  transition: transform 0.2s ease;
}

.event-card:hover {
  transform: translateY(-2px);
}

/* Add scrollbar styling for better appearance */
.QWiLj::-webkit-scrollbar {
  width: 6px;
}

.QWiLj::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 3px;
}

.QWiLj::-webkit-scrollbar-thumb {
  background-color: var(--primary-color);
  border-radius: 3px;
}

/* Estilos para impedir duplicação de apostas com mesmo ID */
.hidden-by-merge {
  display: none !important;
  visibility: hidden !important;
  height: 0 !important;
  opacity: 0 !important;
  position: absolute !important;
  top: -9999px !important;
  left: -9999px !important;
  z-index: -999 !important;
  overflow: hidden !important;
  pointer-events: none !important;
}

/* Garantir que a linha principal esteja visível */
.merged-main-row {
  display: table-row !important;
  visibility: visible !important;
}

/* Estilo para as duas linhas com mesmo ID */
tr.WXGKq[data-transaction-id] + tr.WXGKq[data-transaction-id]:not(.merged-main-row) {
  display: none !important;
}
</style>
@endsection
