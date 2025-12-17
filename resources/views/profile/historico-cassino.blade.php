@extends('layouts.app')

@section('content')
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
        <span class="DxKO1">{{ __('menu.casino_betting_history') }}</span>
        <div class="nu8zQ"></div>
    </div>
    <div class="KHLto" data-v-owner="269" style="--236d1da4: 65px;">
        <div class="_6NoZq">
            <!---->
            <div class="identifierToGoTop">
                <!---->
                <div class="F9gAI">
                    <button aria-hidden="true" type="button" style="position: fixed; height: 0px; padding: 0px; overflow: hidden; clip: rect(0px, 0px, 0px, 0px); white-space: nowrap; border-width: 0px;"></button>
                    @include('profile.partials.filter-date')
                    <div class="_6LUMB">
                        <div id="headlessui-tabs-panel-nsiNM9WAguS_1" role="tabpanel" tabindex="0" data-headlessui-state="selected" class="rounded-xl ring-white outline-none">
                            <div class="rqI4A">
                                <table class="UHNq-">
                                    <tr class="HGAlV">
                                        <th>{{ __('menu.type') }}</th>
                                        <th>{{ __('menu.id') }}</th>
                                        <th>{{ __('menu.game') }}</th>
                                        <th>{{ __('menu.amount') }}</th>
                                        <th>{{ __('menu.status') }}</th>
                                        <th>{{ __('menu.with_bonus') }}</th>
                                        <th>{{ __('menu.date') }}</th>
                                    </tr>
                                    @forelse($history as $item)
                                    <tr class="WXGKq">
                                        <td data-name="type" style="width: 50px; text-align: center;">
                                            <div class="H32ns Vj0b-" style="
                                                @if($item->action != 'win')
                                                    background-color: rgb(245, 47, 47) !important;
                                                    color: white !important;
                                                @endif
                                            ">
                                                <span class="nuxt-icon nuxt-icon--fill">
                                                    @if($item->type == 1)
                                                    <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"
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
                                        <td data-name="game_name">
                                            {{ $item->game_name ?? 'Jogo' }}
                                        </td>
                                        <td data-name="amount">R$&nbsp;{{ number_format($item->amount, 2, ',', '.') }}</td>
                                        <td data-name="status">
                                            <div class="H32ns Vj0b-" style="
                                                @if($item->action != 'win')
                                                    background-color: rgb(245, 47, 47) !important;
                                                    color: white !important;
                                                @endif
                                            ">
                                                <small>{{ $item->action == 'win' ? __('menu.won') : __('menu.lost') }}</small>
                                            </div>
                                        </td>
                                        <td data-name="with_bonus">{{ $item->with_bonus ? __('menu.yes') : __('menu.no') }}</td>
                                        <td data-name="created_at">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y, H:i:s') }}</td>
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
@endsection