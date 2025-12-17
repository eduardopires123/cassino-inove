<div class="fVeX8" data-headerheight="65" data-topbarheight="0" data-v-owner="381" style="--236d1da4: 65px;">
        <span class="nuxt-icon nuxt-icon--fill pvpfG">
            <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"
                    fill="currentColor"
                ></path>
            </svg>
        </span>
        <span class="DxKO1">{{ __('messages.my_referrals') }}</span>
        <div class="nu8zQ"></div>
</div>
<div class="_6NoZq">
        <div class="rqI4A">
            <table class="UHNq-">
                <tr class="HGAlV">
                    <th>{{ __('messages.type') }}</th>
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.date') }}</th>
                </tr>
                @foreach($history as $item)
                <tr class="WXGKq">
                    <td data-name="type" style="width: 50px; text-align: center;">
                        <div class="H32ns Vj0b-">
                            <span class="nuxt-icon nuxt-icon--fill">
                                <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"
                                        fill="currentColor"
                                    ></path>
                                </svg>
                            </span>
                        </div>
                    </td>
                    <td data-name="game_name">{{ $item->user->name ?? __('messages.user_not_found') }}</td>
                    <td data-name="created_at">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat(__('datetime.datetime_format')) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @include('partials.pagination', [
            'items' => $history,
            'paginationId' => 'history-pagination'
        ])
</div>