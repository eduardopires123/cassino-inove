@extends('layouts.app')

@section('content')
<div class="_2sSj3">
    <div class="is0Ic">
        @include('profile.partials.menu')
        <div class="cnynX">
            <div class="fVeX8" style="--236d1da4: 65px;">
                <a class="nuxt-icon nuxt-icon--fill pvpfG" href="{{ route('user.wallet') }}">
                    <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" fill="currentColor"></path>
                    </svg>
                </a>
                <span class="DxKO1">{{ __('menu.login_history_title', ['default' => 'Histórico de Login']) }}</span>
                <div class="nu8zQ"></div>
            </div>
            <div class="TSJnw" data-v-owner="516" style="--236d1da4: 65px;">
                <div class="_6NoZq">
                    <div class="identifierToGoTop">
                        <div class="F9gAI">
                            @include('profile.partials.filter-date')
                            <div class="_6LUMB">
                                <div id="login-history-content" role="tabpanel" class="rounded-xl ring-white outline-none tabcontent">
                                    <div class="rqI4A">
                                        <table class="UHNq-">
                                            <tr class="HGAlV">
                                                <th>{{ __('menu.date_time', ['default' => 'Data/Hora']) }}</th>
                                                <th>{{ __('menu.ip_address', ['default' => 'IP']) }}</th>
                                                <th>{{ __('menu.location', ['default' => 'Cidade/Estado']) }}</th>
                                                <th>{{ __('menu.coordinates', ['default' => 'Coordenadas']) }}</th>
                                            </tr>
                                            @forelse($loginHistory as $login)
                                            <tr class="WXGKq">
                                                <td data-name="date">{{ \Carbon\Carbon::parse($login->created_at)->format('d/m/Y, H:i:s') }}</td>
                                                <td data-name="ip">{{ $login->ip }}</td>
                                                <td data-name="location">{{ $login->city ?? 'N/A' }} / {{ $login->state ?? 'N/A' }}</td>
                                                <td data-name="coordinates">
                                                    <p class="qf-zm">
                                                        <div><span>Lat:</span> {{ $login->lat ?? 'N/A' }}</div>
                                                        <div><span>Long:</span> {{ $login->lng ?? 'N/A' }}</div>
                                                    </p>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">
                                                    <div class="alert alert-info mt-4">
                                                        <i class="fas fa-info-circle mr-2"></i> {{ __('menu.no_login_history_found', ['default' => 'Nenhum histórico de login encontrado']) }}
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </table>
                                    </div>
                                    @include('partials.pagination', [
                                        'items' => $loginHistory,
                                        'paginationId' => 'login-history-pagination'
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