@extends('layouts.app')

@section('content')
<div class="_2sSj3">
    <div class="is0Ic">
        @include('profile.partials.menu')
       <div class="cnynX">
            <div class="fVeX8" data-headerheight="65" data-topbarheight="0" data-v-owner="2887" style="--236d1da4: 65px;">
                <a class="nuxt-icon nuxt-icon--fill pvpfG" href="{{ route('user.wallet') }}">
                    <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" fill="currentColor"></path>
                    </svg>
                </a>
                <span class="DxKO1">{{ __('menu.full_statement_title') }}</span>
                <div class="nu8zQ"></div>
            </div>
            <div class="_-2s9o">
                <div class="_6NoZq">
                    <!---->
                    <div class="identifierToGoTop">
                        <!---->
                        <!-- Filtros de Tipo de Transação -->
                        <div class="XNW0o">
                            <div class="Yir83">
                                <div class="NKUH3">
                                    <label class="{{ request('source_type', 'all') == 'all' ? 'yvWYA' : '' }}" for="source_all">
                                        <input id="source_all" type="radio" value="all" name="source_type" {{ request('source_type', 'all') == 'all' ? 'checked' : '' }} class="ajax-filter"> Todos
                                    </label>
                                    <label class="{{ request('source_type') == 'financial' ? 'yvWYA' : '' }}" for="source_financial">
                                        <input id="source_financial" type="radio" value="financial" name="source_type" {{ request('source_type') == 'financial' ? 'checked' : '' }} class="ajax-filter"> Financeiro
                                    </label>
                                    <label class="{{ request('source_type') == 'sports' ? 'yvWYA' : '' }}" for="source_sports">
                                        <input id="source_sports" type="radio" value="sports" name="source_type" {{ request('source_type') == 'sports' ? 'checked' : '' }} class="ajax-filter"> Apostas Esportivas
                                    </label>
                                    <label class="{{ request('source_type') == 'casino' ? 'yvWYA' : '' }}" for="source_casino">
                                        <input id="source_casino" type="radio" value="casino" name="source_type" {{ request('source_type') == 'casino' ? 'checked' : '' }} class="ajax-filter"> Cassino
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="F9gAI">
                            <button aria-hidden="true" type="button" style="position: fixed; height: 0px; padding: 0px; overflow: hidden; clip: rect(0px, 0px, 0px, 0px); white-space: nowrap; border-width: 0px;"></button>
                            <div class="_6LUMB G64pL">
                                <div id="headlessui-tabs-panel-nsiNM9WAguS_45" role="tabpanel" tabindex="0" data-headlessui-state="selected" class="rounded-xl ring-white outline-none">
                                    <div class="rqI4A">
                                        <table class="UHNq-">
                                            <tr class="HGAlV">
                                                <th>{{ __('menu.type') }}</th>
                                                <th>{{ __('menu.id') }}</th>
                                                <th>{{ __('menu.source') }}</th>
                                                <th>{{ __('menu.amount') }}</th>
                                                <th>{{ __('menu.status') }}</th>
                                                <th>{{ __('menu.date') }}</th>
                                            </tr>
                                            @if(isset($allTransactions))
                                                @forelse($allTransactions as $transaction)
                                                <tr class="WXGKq">
                                                    <td data-name="type" style="width: 50px; text-align: center;">
                                                        <div class="H32ns Vj0b-" style="{{ $transaction->type == 1 ? 'background-color: rgb(245, 47, 47) !important;' : '' }}">
                                                            <span class="nuxt-icon nuxt-icon--fill">
                                                                @if($transaction->type == 0)
                                                                <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z" fill="currentColor"></path>
                                                                </svg>
                                                                @else
                                                                <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M169.4 470.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 370.8V64c0-17.7-14.3-32-32-32s-32 14.3-32 32v306.7L54.6 265.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z" fill="currentColor"></path>
                                                                </svg>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td data-name="id">{{ $transaction->id }}</td>
                                                    <td data-name="src">
                                                        @if($transaction->source_type == 'financial')
                                                            @php
                                                                $labels = [
                                                                    0 => __('menu.deposit'),
                                                                    1 => __('menu.withdrawal'),
                                                                    2 => 'Saldo Bônus'
                                                                ];
                                                            @endphp

                                                            {{ $labels[$transaction->type] ?? 'Desconhecido' }}
                                                        @elseif($transaction->source_type == 'casino')
                                                            {{ __('menu.casino_bet') }} {{ !empty($transaction->game_name) ? '- ' . $transaction->game_name : '' }}
                                                        @elseif($transaction->source_type == 'sports')
                                                            {{ __('menu.sports_bet') }}
                                                        @elseif($transaction->source_type == 'manual_addition')
                                                            @if($transaction->action_type == 'removal')
                                                                <span style="color: #e7515a;">Remoção Manual de Saldo</span>
                                                            @else
                                                                <span style="color: #4361ee;">Adição Manual de Saldo</span>
                                                            @endif
                                                        @else
                                                            {{ __('menu.transaction') }}
                                                        @endif
                                                    </td>
                                                    <td data-name="amount">
                                                        @if(($transaction->source_type == 'casino' && $transaction->action_type == 'lose') || 
                                                            ($transaction->source_type == 'sports' && $transaction->action_type == 'debit'))
                                                            R$&nbsp;-{{ number_format((float)$transaction->amount, 2, ',', '.') }}
                                                        @else
                                                            R$&nbsp;{{ number_format((float)$transaction->amount, 2, ',', '.') }}
                                                        @endif
                                                    </td>
                                                    <td data-name="status">
                                                        <div class="H32ns Vj0b-" style="
                                                            @if($transaction->status == 0)
                                                                background-color: #ff9f43 !important;
                                                            @elseif($transaction->status != 1)
                                                                background-color: rgb(245, 47, 47) !important;
                                                            @endif
                                                            ">
                                                            <small>
                                                                @if($transaction->status == 1)
                                                                    {{ __('menu.approved') }}
                                                                @elseif($transaction->status == 0)
                                                                    {{ __('menu.pending') }}
                                                                @else
                                                                    {{ __('menu.rejected') }}
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td data-name="created_at">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y, H:i:s') }}</td>
                                                </tr>
                                                @empty
                                                <tr class="WXGKq">
                                                    <td colspan="7" style="text-align: center;">{{ __('menu.no_transactions_found') }}</td>
                                                </tr>
                                                @endforelse
                                            @else
                                                <tr class="WXGKq">
                                                    <td colspan="7" style="text-align: center;">{{ __('menu.transaction_data_unavailable') }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                    @if(isset($allTransactions) && $allTransactions->count() > 0)
                                        @include('partials.pagination', [
                                            'items' => $allTransactions,
                                            'paginationId' => 'transactions-pagination'
                                        ])
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adicionar event listeners aos filtros
    document.querySelectorAll('.ajax-filter').forEach(function(filter) {
        filter.addEventListener('change', function() {
            // Obter valor do filtro selecionado
            const sourceType = document.querySelector('input[name="source_type"]:checked')?.value || 'all';
            
            // Construir URL com parâmetros
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('source_type', sourceType);
            urlParams.delete('page'); // Resetar para primeira página
            
            // Recarregar página com novos parâmetros
            const newUrl = window.location.pathname + '?' + urlParams.toString();
            window.location.href = newUrl;
        });
    });
});
</script>
@endsection
