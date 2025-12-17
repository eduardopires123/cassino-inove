@extends('layouts.app')

@section('content')
<div class="cHh-b">
    <div class="eNFX6">
        <header class="PAItV">
            <div class="smUTk">
                <button class="_8s2Sx" onclick="window.location.href='{{ route('lucky.boxes') }}'">
                    <span class="nuxt-icon nuxt-icon--fill">
                        <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"
                                fill="currentColor"
                            ></path>
                        </svg>
                    </span>
                </button>
                <h1 class="hFWlQ"><span>Histórico de Caixas</span></h1>
            </div>
        </header>
        
        <h4 class="gamificationPageTitle">
            <span class="nuxt-icon nuxt-icon--fill icon">
                <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M398.957 153.438C396.339 145.339 389.155 139.586 380.655 138.82L265.205 128.337L219.552 21.4828C216.186 13.6518 208.52 8.5827 200.002 8.5827C191.484 8.5827 183.818 13.6518 180.452 21.5011L134.8 128.337L19.3303 138.82C10.8462 139.604 3.68046 145.339 1.04673 153.438C-1.58701 161.538 0.845308 170.422 7.26332 176.022L94.5306 252.556L68.7975 365.91C66.9145 374.245 70.1495 382.86 77.0649 387.859C80.7821 390.544 85.1309 391.912 89.5164 391.912C93.2976 391.912 97.0483 390.892 100.415 388.878L200.002 329.358L299.553 388.878C306.838 393.261 316.021 392.861 322.921 387.859C329.839 382.845 333.071 374.226 331.188 365.91L305.455 252.556L392.722 176.037C399.14 170.422 401.591 161.553 398.957 153.438Z"
                        fill="currentColor"
                    ></path>
                </svg>
            </span>
            <span>Seu histórico de Caixas da Sorte</span>
        </h4>
        
        <div class="history-container" style="padding: 20px;">
            @if($purchases->count() > 0)
                <table class="history-table" style="width: 100%; border-collapse: collapse; background-color: #272727; border-radius: 10px; overflow: hidden;">
                    <thead>
                        <tr style="background-color: #1a1a1a;">
                            <th style="padding: 15px; text-align: left; color: gold;">Caixa</th>
                            <th style="padding: 15px; text-align: center; color: gold;">Custo</th>
                            <th style="padding: 15px; text-align: center; color: gold;">Prêmio</th>
                            <th style="padding: 15px; text-align: right; color: gold;">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                            <tr style="border-bottom: 1px solid #333;">
                                <td style="padding: 15px; text-align: left; color: white;">
                                    @if($purchase->level == 6)
                                        Caixa Misteriosa
                                    @else
                                        Caixa Nível {{ $purchase->level }}
                                    @endif
                                </td>
                                <td style="padding: 15px; text-align: center; color: #ff6b6b;">{{ $purchase->cost }} coins</td>
                                <td style="padding: 15px; text-align: center; color: {{ $purchase->prize > 0 ? '#4eff8a' : '#aaa' }};">
                                    {{ $purchase->prize }} rodadas
                                </td>
                                <td style="padding: 15px; text-align: right; color: #aaa;">{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="pagination" style="margin-top: 20px; text-align: center;">
                    {{ $purchases->links() }}
                </div>
            @else
                <div class="no-history" style="text-align: center; padding: 50px 20px; background-color: #272727; border-radius: 10px;">
                    <p style="color: #aaa; font-size: 18px; margin-bottom: 20px;">Você ainda não abriu nenhuma Caixa da Sorte!</p>
                    <a href="{{ route('lucky.boxes') }}" style="display: inline-block; background-color: gold; color: #333; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Abrir Caixas Agora</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 