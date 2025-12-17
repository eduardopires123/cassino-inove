
<div class="_6NoZq">
    <div class="NRWMP">
        <div class="Qeti8">
            <div class="NKUH3 yZqzq">
                <label class="" for="period_today"><input id="period_today" type="radio" name="period_type" value="today" /> RevShare</label>
                <label class="yvWYA" for="period_1"><input id="period_1" type="radio" name="period_type" value="1" checked /> CPA</label>
            </div>
        </div>
    </div>

    <div class="rqI4A">
        <!-- RevShare Table -->
        <table class="UHNq- revshare-table" style="display: none;">
            <tr class="HGAlV">
                <th>Tipo</th>
                <th>Nome</th>
                @if($user->is_affiliate == 1)
                <th>Jogo</th>
                <th>Lucro</th>
                @endif
                <th>Data</th>
            </tr>
            
            @php
                $revshareTable = App\Models\AffiliatesHistory::Where('inviter', $id)->Where('game', '!=', 'CPA')->orderBy('id', 'desc')->paginate(15);
            @endphp

            @foreach($revshareTable as $registro)
                @php
                    // Nova abordagem: buscar diretamente na tabela consolidada games_api
                    $gameName = DB::table('games_api')
                        ->where('slug', 'LIKE', '%' . $registro->game . '%')
                        ->where('status', 1)
                        ->first();
                @endphp
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
                    <td data-name="game_name">{{ $registro->user->name }}</td>
                    <td data-name="game">{{ $gameName->name ?? "Jogo não encontrado" }}</td>
                    <td data-name="amount">{!! 'R$ ' . number_format($registro->amount, 2, ',', '.') !!}</td>
                    <td data-name="created_at">{!! Carbon\Carbon::parse($registro->created_at)->format('d/m/Y, H:i:s') !!}</td>
                </tr>
            @endforeach
        </table>

        <!-- CPA Table (Hidden by default) -->
        <table class="UHNq- cpa-table">
            <tr class="HGAlV">
                <th>Tipo</th>
                <th>Nome</th>
                <th>Lucro</th>
                <th>Data</th>
            </tr>
            
            @php
                $cpaTable = App\Models\AffiliatesHistory::Where('inviter', $id)->Where('game', 'CPA')->orderBy('id', 'desc')->paginate(15);
            @endphp

            @foreach($cpaTable as $registro)
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
                    <td data-name="game_name">{{ $registro->user->name }}</td>
                    <td data-name="amount">{!! 'R$ ' . number_format($registro->amount, 2, ',', '.') !!}</td>
                    <td data-name="created_at">{!! Carbon\Carbon::parse($registro->created_at)->format('d/m/Y, H:i:s') !!}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="revshare-pagination" style="display: none;">
        @include('partials.pagination', [
            'items' => $revshareTable,
            'paginationId' => 'history-revshare-pagination'
        ])
    </div>
    <div class="cpa-pagination">
        @include('partials.pagination', [
            'items' => $cpaTable,
            'paginationId' => 'history-cpa-pagination'
        ])
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radioButtons = document.querySelectorAll('.NKUH3 input[type="radio"]');
        const revshareTable = document.querySelector('.revshare-table');
        const cpaTable = document.querySelector('.cpa-table');
        const revsharePages = document.querySelector('.revshare-pagination');
        const cpaPages = document.querySelector('.cpa-pagination');
        
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove active class from all labels
                document.querySelectorAll('.NKUH3 label').forEach(label => {
                    label.classList.remove('yvWYA');
                });
                
                // Add active class to selected label
                this.parentElement.classList.add('yvWYA');
                
                // Show/hide tables based on selection
                if (this.value === 'today') {
                    // RevShare selected
                    revshareTable.style.display = '';
                    cpaTable.style.display = 'none';
                    revsharePages.style.display = '';
                    cpaPages.style.display = 'none';
                } else if (this.value === '1') {
                    // CPA selected
                    revshareTable.style.display = 'none';
                    cpaTable.style.display = '';
                    revsharePages.style.display = 'none';
                    cpaPages.style.display = '';
                }
            });
        });
        
        // Set CPA as default
        document.getElementById('period_1').checked = true;
        document.getElementById('period_1').parentElement.classList.add('yvWYA');
        revshareTable.style.display = 'none';
        revsharePages.style.display = 'none';
        cpaTable.style.display = '';
        cpaPages.style.display = '';
    });
    
    function Referidos(tipo) {
        if (tipo === 'Indicados') {
            document.getElementById('tindicados').textContent = 'Indicados';
            document.getElementById('cindicados').classList.add('!bg-tabs-active-bg', '!text-muted-100/70', '!border-tabs-active-border');
            document.getElementById('ccpa').classList.remove('!bg-tabs-active-bg', '!text-muted-100/70', '!border-tabs-active-border');
            document.getElementById('Indicados').classList.remove('hidden');
            document.getElementById('CPA').classList.add('hidden');
        } else if (tipo === 'CPA') {
            document.getElementById('tindicados').textContent = 'CPA';
            document.getElementById('ccpa').classList.add('!bg-tabs-active-bg', '!text-muted-100/70', '!border-tabs-active-border');
            document.getElementById('cindicados').classList.remove('!bg-tabs-active-bg', '!text-muted-100/70', '!border-tabs-active-border');
            document.getElementById('CPA').classList.remove('hidden');
            document.getElementById('Indicados').classList.add('hidden');
        } else if (tipo === 'RevShare') {
            document.getElementById('tindicados').textContent = 'RevShare';
            document.getElementById('crevshare').classList.add('!bg-tabs-active-bg', '!text-muted-100/70', '!border-tabs-active-border');
        }
    }
</script>
