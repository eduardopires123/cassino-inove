@extends('layouts.app')

@section('content')
<div class="_2sSj3">
    <div class="is0Ic">
        @include('profile.partials.menu')
<div class="cnynX">
    <div class="fVeX8" data-headerheight="65" data-topbarheight="0" data-v-owner="516" style="--236d1da4: 65px;">
        <a class="nuxt-icon nuxt-icon--fill pvpfG" href="{{ route('user.wallet') }}">
            <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" fill="currentColor"></path>
            </svg>
        </a>
        <span class="DxKO1">{{ __('menu.deposit_history_title') }}</span>
        <div class="nu8zQ"></div>
    </div>
    <div class="TSJnw" data-v-owner="516" style="--236d1da4: 65px;">
        <div class="_6NoZq">
            <div class="identifierToGoTop">
                <div class="F9gAI">
                  @include('profile.partials.filter-date')
                    <div class="_6LUMB">
                        <div id="deposits-content" role="tabpanel" class="rounded-xl ring-white outline-none tabcontent">
                            <div class="rqI4A">
                                <table class="UHNq-">
                                    <tr class="HGAlV">
                                        <th>{{ __('menu.type') }}</th>
                                        <th>{{ __('menu.amount') }}</th>
                                        <th>{{ __('menu.status') }}</th>
                                        <th>{{ __('menu.date') }}</th>
                                    </tr>
                                    @forelse($deposits as $transaction)
                                    <tr class="WXGKq">
                                        <td data-name="type" style="width: 50px; text-align: center;">
                                            <div class="H32ns Vj0b-" style="background-color: rgb(0, 201, 0) !important;">
                                                <span class="nuxt-icon nuxt-icon--fill">
                                                    <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z" fill="currentColor"></path>
                                                    </svg>
                                                </span>
                                            </div>
                                        </td>
                                        <td data-name="amount">R$&nbsp;{{ number_format($transaction->amount ?? 0, 2, ',', '.') }}</td>
                                        <td data-name="status">
                                            <div class="H32ns Vj0b-" style="@if(($transaction->status ?? 0) != 1) background-color: rgb(245, 47, 47) !important; @endif">
                                                <small>
                                                    @if(($transaction->status ?? 0) == 1)
                                                        {{ __('menu.approved') }}
                                                    @elseif(($transaction->status ?? 0) == 2)
                                                        {{ __('menu.rejected') }}
                                                    @else
                                                        {{ __('menu.pending') }}
                                                    @endif
                                                </small>
                                            </div>
                                        </td>
                                        <td data-name="created_at">{{ $transaction->created_at ? \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y, H:i:s') : '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="alert alert-info mt-4">
                                                <i class="fas fa-info-circle mr-2"></i> {{ __('menu.no_deposits_found') }}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </table>
                            </div>
                            @include('partials.pagination', [
                                'items' => $deposits,
                                'paginationId' => 'deposits-pagination'
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
@endsection