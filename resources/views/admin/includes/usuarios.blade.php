@if($page=="blacklist")
    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Usuários</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Blacklist</li>
                    </ol>
                </nav>
            </div>

            <div class="switch form-switch-custom switch-inline form-switch-primary form-switch-custom inner-label-toggle show">
                <div class="input-checkbox">
                    <span class="switch-chk-label label-left">TBS</span>
                    <input class="switch-input" type="checkbox" role="switch" id="form-custom-switch-inner-label" onchange="this.checked ? this.closest('.inner-label-toggle').classList.add('show') : this.closest('.inner-label-toggle').classList.remove('show')" checked>
                    <span class="switch-chk-label label-right">PlayFiver</span>
                </div>
            </div>

            <div class="row" style="margin-top: 20px;">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content widget-content-area">
                            <table id="blacklist" class="table style-3 dt-table-hover" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Motivo Bloqueio</th>
                                    <th>Data Bloqueio</th>
                                    <th class="text-center dt-no-sorting">Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $userTable = App\Models\User::Where('banned', 1)->get();
                                @endphp
                                @foreach($userTable as $registro)
                                    @php
                                        $id = $registro->id;
                                    @endphp
                                    <tr>
                                        <td>{{$registro->name}}</td>
                                        <td>{{$registro->banned_reason}}</td>
                                        <td>{{$registro->banned_date->format('d/m/Y H:i:s')}}</td>
                                        <td class="text-center">
                                            <ul class="table-controls">
                                                <!--<li><a href="javascript:void(0);" class="bs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" data-original-title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-8 mb-1"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>-->
                                                <li><a href="javascript:void(0);" onclick="UnblockAgent('{{$id}}', '{{$registro->name}}');" class="bs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Desbloquear" data-original-title="Desbloquear"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle table-cancel"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Nome</th>
                                    <th>Motivo Bloqueio</th>
                                    <th>Data Bloqueio</th>
                                    <th class="text-center dt-no-sorting">Ação</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif($page=="carteiras")
            <div class="layout-px-spacing" id="contentaff">
                <div class="middle-content container-xxl p-0">
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Usuários</a></li>
                                <li class="breadcrumb-item active" aria-current="page" id="estatde">Carteiras</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                            <div class="statbox widget box box-shadow">
                                <div class="widget-content widget-content-area">
                                    <table id="wallets" class="table style-3 dt-table-hover" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Saldo</th>
                                            <th>Saldo Bônus</th>
                                            <th>Saldo Referidos</th>
                                            <th>Última Movimentação</th>
                                            <th class="text-center dt-no-sorting">Ação</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $userTable = App\Models\Wallet::orderby('id', 'desc')->get();
                                        @endphp

                                        @foreach($userTable as $registro)
                                            <tr>
                                                <td>{{$registro->user->name}}</td>
                                                <td>R$ {{sprintf("%.2f", $registro->balance)}}</td>
                                                <td>B$ {{sprintf("%.2f", $registro->balance_bonus)}}</td>
                                                <td>REF$ {{sprintf("%.2f", $registro->refer_rewards)}}</td>
                                                <td>{{$registro->updated_at->format('d/m/Y H:i:s')}}</td>
                                                <td class="text-center">
                                                    <ul class="table-controls">
                                                        <li><a href="javascript:void(0);" onclick="LoadAgent('{{$registro->user->id}}');" class="bs-tooltip" data-bs-toggle="modal" data-bs-target="#tabsModal" title="Visualizar Usuário" data-original-title="Visualizar Usuário"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-8 mb-1"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                                        <!--<li><a href="javascript:void(0);" class="bs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle table-cancel"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>-->
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Saldo</th>
                                            <th>Saldo Bônus</th>
                                            <th>Saldo Referidos</th>
                                            <th>Última Movimentação</th>
                                            <th class="text-center dt-no-sorting">Ação</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($page=="usuarios")
                    <div class="layout-px-spacing" id="contentaff">
                        <div class="middle-content container-xxl p-0">
                            <div class="page-meta">
                                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#">Usuários</a></li>
                                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Buscar Usuários</li>
                                    </ol>
                                </nav>
                            </div>

                            <div class="row" style="margin-top: 20px;">
                                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                                    <div class="statbox widget box box-shadow">
                                        <div class="widget-content widget-content-area">
                                            <table id="agents" class="table style-3 dt-table-hover" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>E-mail</th>
                                                    <th>Saldo</th>
                                                    <th>Data Cadastro</th>
                                                    <th>Último Depósito</th>
                                                    <th class="text-center dt-no-sorting">Ação</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php
                                                    $userTable = App\Models\User::get();
                                                @endphp

                                                @foreach($userTable as $registro)
                                                    <tr>
                                                        <td>{{$registro->name}}</td>
                                                        <td>{{$registro->email}}</td>
                                                        @php
                                                            $id = $registro->id;
                                                            $formatter = new NumberFormatter('pt_BR', NumberFormatter::DECIMAL);

                                                            if ($registro->Wallet->balance > 9999)
                                                            {$formatado = $formatter->format($registro->Wallet->balance);
                                                            }else{$formatado = sprintf("%.2f", $registro->Wallet->balance);}

                                                            $lastDeposit = App\Models\Transactions::Where('user_id', $id)->orderBy('id', 'desc')->first();

                                                            if($lastDeposit){
                                                            $formattedDate = $lastDeposit->updated_at->format('d/m/Y H:i:s');}
                                                        @endphp
                                                        <td>R$ {{$formatado}}</td>
                                                        <td>{{$registro->created_at->format('d/m/Y H:i:s')}}</td>
                                                        <td>
                                                            @if($lastDeposit)
                                                                {{$formattedDate}}
                                                            @else
                                                                Não há depósitos recentes
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <ul class="table-controls">
                                                                <li><a href="javascript:void(0);" onclick="LoadAgent('{{$id}}');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário" data-original-title="Visualizar Usuário"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-8 mb-1"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                                                                <li><a href="javascript:void(0);" onclick="DeleteAgent('{{$id}}', '{{$registro->name}}');" class="bs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Deletar Usuário" data-original-title="Deletar Usuário"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle table-cancel"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>E-mail</th>
                                                    <th>Saldo</th>
                                                    <th>Data Cadastro</th>
                                                    <th>Último Depósito</th>
                                                    <th class="text-center dt-no-sorting">Ação</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($page=="user_news")
                            <div class="layout-px-spacing" id="contentaff">
                                <div class="middle-content container-xxl p-0">
                                    <div class="page-meta">
                                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="#">Usuários</a></li>
                                                <li class="breadcrumb-item active" aria-current="page" id="estatde">Novos Usuários</li>
                                            </ol>
                                        </nav>
                                    </div>

                                    <div class="row" style="margin-top: 20px;">
                                        <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                                            <div class="statbox widget box box-shadow">
                                                <div class="widget-content widget-content-area">
                                                    <table id="user_news" class="table style-3 dt-table-hover" style="width:100%">
                                                        <thead>
                                                        <tr>
                                                            <th>Nome</th>
                                                            <th>E-mail</th>
                                                            <th>Saldo</th>
                                                            <th>Data Cadastro</th>
                                                            <th class="text-center dt-no-sorting">Ação</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php
                                                            $dataLimite = now()->subDays(31);

                                                            $userTable = App\Models\User::where('created_at', '>=', $dataLimite)
                                                                                        ->orderBy('created_at', 'desc')
                                                                                        ->get();
                                                        @endphp

                                                        @foreach($userTable as $registro)
                                                            <tr>
                                                                <td>{{$registro->name}}</td>
                                                                <td>{{$registro->email}}</td>
                                                                @php
                                                                    $id = $registro->id;
                                                                    $formatter = new NumberFormatter('pt_BR', NumberFormatter::DECIMAL);

                                                                    $balance = $registro->Wallet->balance;
                                                                    $formatado = $balance > 9999 ? $formatter->format($balance) : sprintf("%.2f", $balance);
                                                                @endphp
                                                                <td>R$ {{$formatado}}</td>
                                                                <td>{{$registro->created_at->format('d/m/Y H:i:s')}}</td>
                                                                <td class="text-center">
                                                                    <ul class="table-controls">
                                                                        <li>
                                                                            <a href="javascript:void(0);" onclick="LoadAgent('{{$id}}');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 p-1 br-8 mb-1">
                                                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                                                </svg>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="javascript:void(0);" onclick="DeleteAgent('{{$id}}', '{{$registro->name}}');" class="bs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Deletar Usuário">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle table-cancel">
                                                                                    <circle cx="12" cy="12" r="10"></circle>
                                                                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                                                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                                                                </svg>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <th>Nome</th>
                                                            <th>E-mail</th>
                                                            <th>Saldo</th>
                                                            <th>Data Cadastro</th>
                                                            <th class="text-center dt-no-sorting">Ação</th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @elseif($page=="load_agent")
                                    @php
                                        $user_permissao = auth()->user();

                                        $a = "";
                                        $b = "";
                                        $c = "";
                                        $user = null;

                                        if (isset($queryParams["aff"])) {$a = $queryParams["aff"];}else{$a = "";}
                                        if (isset($queryParams["di"])) {$b = $queryParams["di"]; $bb = Carbon\Carbon::parse($b)->startOfDay();}else{$bb = "";}
                                        if (isset($queryParams["df"])) {$c = $queryParams["df"]; $cc = Carbon\Carbon::parse($c)->endOfDay();}else{$cc = "";}

                                        $userTable = App\Models\Transactions::Where('type', 0)->orderBy('id', 'desc');

                                        if ($a != ""){
                                            $user = App\Models\User::Where('name', $a)->first();

                                            if ($user){$userTable->where('user_id', $user->id);}
                                        }

                                        if ($b && $c) {
                                            $userTable->whereBetween('updated_at', [$bb, $cc]);
                                        } elseif ($b && !$c) {
                                            $userTable->whereBetween('updated_at', [$bb, Carbon\Carbon::now()]);
                                        }

                                        $userTable = $userTable->get();
                                    @endphp
                                    <style>
                                        .widget-five .widget-content .progress-data .progress{
                                            height: 22px!important;
                                        }
                                        body.dark .page-item.active .page-link {
                                            color: #fff !important;
                                        }
                                        .page-item.active .page-link {
                                            color: #fff !important;
                                        }
                                        h5{
                                            color:#d6dce4;
                                        }

                                        body.dark .form-check-input {
                                            background-color: #1b2e4b;
                                            border-color: #1f3b66;
                                        }

                                        details {
                                            display: block;
                                            width: auto;
                                            margin: 10px 0;
                                        }

                                        body.dark .widget-five{
                                            background: rgb(0 0 0 / 9%);
                                        }

                                        .widget-five {
                                            background: rgb(0 0 0 / 0%);
                                        }

                                        details #accordeon {
                                            padding-left: 30px;
                                        }

                                        summary {
                                            display: flex;
                                            background: rgb(27 46 75);
                                            border-radius: 8px;
                                            padding: 10px;
                                            cursor: pointer;
                                            font-weight: 700;
                                            justify-content: flex-start;
                                            align-items: center;
                                            font-size: 15px;
                                        }

                                        summary::-webkit-details-marker {
                                            display: none;
                                        }

                                        summary:before {
                                            content: "+";
                                            font-size: 20px;
                                            font-weight: bold;
                                            margin: 0 5px;
                                            padding: 0;
                                            width: 20px;
                                            text-align: center;
                                        }
                                        h5{
                                            color: #5a5a5a;
                                        }
                                        h6{
                                            color: #949494;
                                        }

                                        details[open] summary:before {
                                            content: "-";
                                        }
                                    </style>

                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Perfil</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-estatisticas-tab" data-bs-toggle="pill" data-bs-target="#pills-estatisticas" type="button" role="tab" aria-controls="pills-estatisticas" aria-selected="false">Estatísticas</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Transações</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Histórico de Jogos</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-afiliados-tab" data-bs-toggle="pill" data-bs-target="#pills-afiliados" type="button" role="tab" aria-controls="pills-afiliados" aria-selected="false">Afiliação</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                            <form id="agentsForm" name="agentsForm" method="POST" action="">
                                                @csrf

                                                <input type="hidden" id="cid" name="cid" class="form-control" placeholder="" value="{{$Agent->id}}">

                                                <!-- Campos ocultos para verificação de concorrência -->
                                                <input type="hidden" name="original_name" value="{{$Agent->name}}">
                                                <input type="hidden" name="original_email" value="{{$Agent->email}}">
                                                <input type="hidden" name="original_phone" value="{{$Agent->phone}}">
                                                <input type="hidden" name="original_pix" value="{{$Agent->pix}}">
                                                <input type="hidden" name="original_is_demo" value="{{$Agent->is_demo_agent}}">
                                                <input type="hidden" name="original_banned" value="{{$Agent->banned}}">
                                                <input type="hidden" name="original_inviter" value="{{$Agent->inviter}}">
                                                <input type="hidden" name="original_is_admin" value="{{$Agent->is_admin}}">
                                                <input type="hidden" name="original_is_affiliate" value="{{$Agent->is_affiliate}}">
                                                <input type="hidden" name="original_balance" value="{{$Agent->Wallet->balance}}">
                                                <input type="hidden" name="original_balance_bonus" value="{{$Agent->Wallet->balance_bonus}}">
                                                <input type="hidden" name="original_free_spins" value="{{$Agent->Wallet->free_spins}}">
                                                <input type="hidden" name="original_coin" value="{{$Agent->Wallet->coin}}">
                                                <input type="hidden" name="original_refer_percent" value="{{$Agent->Wallet->referPercent}}">
                                                <input type="hidden" name="original_refer_rewards" value="{{$Agent->Wallet->refer_rewards}}">

                                                <input type="hidden" name="original_balance_bonus_rollover" value="{{$Agent->Wallet->balance_bonus_rollover}}">
                                                <input type="hidden" name="original_balance_bonus_rollover_used" value="{{$Agent->Wallet->balance_bonus_rollover_used}}">
                                                <input type="hidden" name="original_balance_bonus_expire" value="{{$Agent->Wallet->balance_bonus_expire}}">
                                                <input type="hidden" name="original_anti_bot" value="{{$Agent->Wallet->anti_bot}}">

                                                @if ($user_permissao->is_admin == 1)
                                                    <div class="form-group">
                                                        <label for="md5">Usuário Demo:</label>
                                                        <select class="form-control form-control-sm" id="cdemo" name="cdemo">
                                                            <option value="0" <?=($Agent->is_demo_agent == 1) ? "selected" : "";?>>Não</option>
                                                            <option value="1" <?=($Agent->is_demo_agent == 1) ? "selected" : "";?>>Sim</option>
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="md5">Nome:</label>
                                                            <input type="text" id="cname" name="cname" class="form-control" placeholder="Nome" value="{{$Agent->name}}" {!! ($user_permissao->is_admin != 1 ? "disabled" : "") !!}>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="md5">E-mail:</label>
                                                            <input type="text" id="cemail" name="cemail" class="form-control" placeholder="Email" value="{{$Agent->email}}" {!! ($user_permissao->is_admin != 1 ? "disabled" : "") !!}>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="md5">Telefone:</label>
                                                            <input type="text" id="ctelefone" name="ctelefone" class="form-control" placeholder="Telefone" value="{{$Agent->phone}}" {!! ($user_permissao->is_admin != 1 ? "disabled" : "") !!}>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="md5">Chave PIX:</label>
                                                            <input type="text" id="cpix" name="cpix" class="form-control" placeholder="Chave PIX" value="{{$Agent->pix}}" {!! ($user_permissao->is_admin != 1 ? "disabled" : "") !!}>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="csenha">Nova Senha:</label>
                                                            <div class="input-group">
                                                                <input type="text" id="csenha" name="csenha" class="form-control" placeholder="Nova senha" {!! ($user_permissao->is_admin != 1 ? "disabled" : "") !!}>
                                                                <button class="btn btn-primary" type="button" onclick="(function() {
                                                                    // Gerar uma senha aleatória com 8 caracteres
                                                                    var caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                                                                    var senha = '';

                                                                    for (var i = 0; i < 8; i++) {
                                                                        senha += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
                                                                    }

                                                                    // Atualizar o campo de input
                                                                    document.getElementById('csenha').value = senha;

                                                                    return false;
                                                                })()" {!! ($user_permissao->is_admin != 1 ? "disabled" : "") !!}>Gerar Senha</button>
                                                            </div>
                                                            <small class="form-text text-muted">Deixe em branco para manter a senha atual</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($user_permissao->is_admin == 1)
                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="md5">Banido:</label>
                                                                <select class="form-control" id="cbanido" name="cbanido" onchange="ShowReason(this.value);">
                                                                    <option value="0" <?=($Agent->banned == 0) ? "selected" : "";?>>Não</option>
                                                                    <option value="1" <?=($Agent->banned == 1) ? "selected" : "";?>>Sim</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="md5">Indicação:</label>

                                                                <select name="cindica" id="cindica" class="form-control">
                                                                    <option value="-1">Ninguém</option>
                                                                    @foreach($UsersTable as $registro)
                                                                        @if($registro->id == $Agent->inviter)
                                                                            <option value="{{ $registro->id }}" selected>{{$registro->id}} - {{ $registro->name }}</option>
                                                                        @else
                                                                            <option value="{{ $registro->id }}">{{$registro->id}} - {{ $registro->name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div id="motivo" {!! ($Agent->banned == 1) ? "" : "class=\"hidden\"" !!} style="margin-top: 10px;">
                                                            <div class="form-group">
                                                                <label for="md5">Motivo Bloqueio:</label>
                                                                <input type="text" id="cmotivo" name="cmotivo" class="form-control" placeholder="Motivo Bloqueio" value="{{$Agent->banned_reason}}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col">
                                                            <label for="md5">Acesso:</label>
                                                            <select class="form-control" id="cadm" name="cadm">
                                                                <option value="0" <?=($Agent->is_admin == 0) ? "selected" : "";?>>Usuário Comum</option>
                                                                <option value="1" <?=($Agent->is_admin == 1) ? "selected" : "";?>>Administrador</option>
                                                                <option value="2" <?=($Agent->is_admin == 2) ? "selected" : "";?>>Supervisor</option>
                                                                <option value="3" <?=($Agent->is_admin == 3) ? "selected" : "";?>>Afiliado</option>
                                                            </select>
                                                        </div>

                                                        <div class="col">
                                                            <label for="md5">Afiliado:</label>
                                                            <select class="form-control" id="caffiliate" name="caffiliate">
                                                                <option value="0" <?=($Agent->is_affiliate == 0) ? "selected" : "";?>>Não</option>
                                                                <option value="1" <?=($Agent->is_affiliate == 1) ? "selected" : "";?>>Sim</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div style="margin-top: 10px; text-align: center;">&nbsp;</div>

                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="md5">Balanço:</label>
                                                                <!---<input type="text" id="cbalanco" name="cbalanco" class="form-control" placeholder="0" value="">-->

                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text">R$</span>
                                                                    <input id="cbalanco" name="cbalanco" type="text" class="form-control" value="{{$Agent->Wallet->balance}}" aria-label="Balanço Real">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="md5">Balanço Bônus:</label>
                                                                <!--<input type="text" id="cbalancob" name="cbalancob" class="form-control" placeholder="0" value="">-->

                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text">B$</span>
                                                                    <input id="cbalancob" name="cbalancob" type="text" class="form-control" value="{{$Agent->Wallet->balance_bonus}}" aria-label="Balanço Bônus">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="md5">Rodadas Grátis:</label>
                                                                <!---<input type="text" id="cbalanco" name="cbalanco" class="form-control" placeholder="0" value="">-->

                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text">Nº</span>
                                                                    <input id="free_spins" name="free_spins" type="text" class="form-control" value="{{$Agent->Wallet->free_spins}}" aria-label="Rodadas Grátis">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="md5">Moedas (Coin):</label>
                                                                <!--<input type="text" id="cbalancob" name="cbalancob" class="form-control" placeholder="0" value="">-->

                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text">Nº</span>
                                                                    <input id="coin" name="coin" type="text" class="form-control" value="{{$Agent->Wallet->coin}}" aria-label="Moedas (Coin)">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="md5">% Referidos:</label>
                                                                <!--<input type="text" id="cbalancor" name="cbalancor" class="form-control" placeholder="0" value="">-->

                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text">%</span>
                                                                    <input id="cref" name="cref" type="text" class="form-control" value="{{$Agent->Wallet->referPercent}}" aria-label="Lucro com Referidos">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="md5">Balanço Referidos:</label>
                                                                <!--<input type="text" id="cbalancor" name="cbalancor" class="form-control" placeholder="0" value="">-->

                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text">REF$</span>
                                                                    <input id="cbalancor" name="cbalancor" type="text" class="form-control" value="{{$Agent->Wallet->refer_rewards}}" aria-label="Lucro com Referidos">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="md5">Rollover de bônus:</label>

                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text">B$</span>
                                                                    <input id="cbalance_bonus_rollover" name="cbalance_bonus_rollover" type="text" class="form-control" value="{{$Agent->Wallet->balance_bonus_rollover}}" aria-label="Rollover de bônus">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="md5">Rollover de bônus usado:</label>

                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text">B$</span>
                                                                    <input id="cbalance_bonus_rollover_used" name="cbalance_bonus_rollover_used" type="text" class="form-control" value="{{$Agent->Wallet->balance_bonus_rollover_used}}" aria-label="Rollover de bônus usado">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="md5">Expiração de saldo bônus:</label>

                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg></span>
                                                                    <input id="cbalance_bonus_expire" name="cbalance_bonus_expire" type="datetime-local" class="form-control" value="{{$Agent->Wallet->balance_bonus_expire}}" aria-label="Expiração de saldo bônus">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="md5">Anti Bot: (Proteção contra saque)</label>

                                                                <div class="input-group mb-3">
                                                                    <span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-crosshair"><circle cx="12" cy="12" r="10"></circle><line x1="22" y1="12" x2="18" y2="12"></line><line x1="6" y1="12" x2="2" y2="12"></line><line x1="12" y1="6" x2="12" y2="2"></line><line x1="12" y1="22" x2="12" y2="18"></line></svg></span>
                                                                    <input id="canti_bot" name="canti_bot" type="text" class="form-control" value="{{$Agent->Wallet->anti_bot}}" aria-label="Anti Bot (Proteção contra saque)">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </form>
                                        </div>

                                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                            <div class="row" style="margin-top: 20px;">
                                                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                                                    <div class="col-md-12">
                                                        <div class="row mb-3">
                                                            <div class="col-md-4 col-sm-6 mb-2">
                                                                <label for="dataInicial4" class="form-label">Data Inicial:</label>
                                                                <input type="date" id="dataInicial4" class="form-control" value="{{$b}}">
                                                            </div>
                                                            <div class="col-md-4 col-sm-6 mb-2">
                                                                <label for="dataFinal4" class="form-label">Data Final:</label>
                                                                <input type="date" id="dataFinal4" class="form-control" value="{{$c}}">
                                                            </div>
                                                            <div class="col-md-4 col-sm-12 mb-2">
                                                                <label for="transactionType" class="form-label">Tipo:</label>
                                                                <div class="input-group">
                                                                    <select id="example4" class="form-control">
                                                                        <option value="">Todos</option>
                                                                        <option value="deposito">Depósito</option>
                                                                        <option value="saque">Saque</option>
                                                                        <option value="bonus">Bônus</option>
                                                                        <option value="manual">Manual</option>
                                                                        <option value="cupom">Cupom</option>
                                                                        <option value="missao">Missão</option>
                                                                        <option value="vip">VIP Level</option>
                                                                    </select>
                                                                    <button class="btn btn-success _effect--ripple waves-effect waves-light" onclick="TabelasProcess('transacoes', '{{$Agent->id}}');" type="button"><span class="text">Buscar</span></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="widget-content widget-content-area br-8">
                                                        <div id="transacoes-wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                                            <div class="table-responsive">
                                                                <table id="transacoes" class="table table-striped dt-table-hover dataTable" style="width:100%" role="grid" aria-describedby="transacoes-info">
                                                                    <thead>
                                                                    <tr role="row">
                                                                        <th class="sorting_asc" tabindex="0" aria-controls="transacoes-table" rowspan="1" colspan="1" aria-sort="ascending">Tipo</th>
                                                                        <th class="sorting" tabindex="0" aria-controls="transacoes-table" rowspan="1" colspan="1">Valor</th>
                                                                        <th class="sorting" tabindex="0" aria-controls="transacoes-table" rowspan="1" colspan="1">Origem/Gateway</th>
                                                                        <th class="sorting" tabindex="0" aria-controls="transacoes-table" rowspan="1" colspan="1">Status</th>
                                                                        <th class="sorting" tabindex="0" aria-controls="transacoes-table" rowspan="1" colspan="1">Data</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <th rowspan="1" colspan="1">Tipo</th>
                                                                        <th rowspan="1" colspan="1">Valor</th>
                                                                        <th rowspan="1" colspan="1">Origem/Gateway</th>
                                                                        <th rowspan="1" colspan="1">Status</th>
                                                                        <th rowspan="1" colspan="1">Data</th>
                                                                    </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                                            <div class="row" style="margin-top: 20px;">
                                                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                                                    <div class="col-md-12">
                                                        <div class="row mb-3">
                                                            <div class="col-md-4 col-sm-6 mb-2">
                                                                <label for="dataInicial2" class="form-label">Data Inicial:</label>
                                                                <input type="date" id="dataInicial2" class="form-control" value="{{$b}}">
                                                            </div>
                                                            <div class="col-md-4 col-sm-6 mb-2">
                                                                <label for="dataFinal2" class="form-label">Data Final:</label>
                                                                <input type="date" id="dataFinal2" class="form-control" value="{{$c}}">
                                                            </div>
                                                            <div class="col-md-4 col-sm-12 mb-2">
                                                                <label for="gameType" class="form-label">Tipo:</label>
                                                                <div class="input-group">
                                                                    <select id="example3" class="form-control">
                                                                        <option value="">Todos</option>
                                                                        <option value="casino">Cassino</option>
                                                                        <option value="sports">Esportes</option>
                                                                    </select>
                                                                    <button class="btn btn-success _effect--ripple waves-effect waves-light" onclick="TabelasProcess('historico_jogos', '{{$Agent->id}}');" type="button"><span class="text">Buscar</span></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="widget-content widget-content-area br-8">
                                                        <div id="historico-wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                                            <div class="table-responsive">
                                                                <table id="historico_jogos" class="table table-striped dt-table-hover dataTable" style="width:100%" role="grid" aria-describedby="historico-info">
                                                                    <thead>
                                                                    <tr role="row">
                                                                        <th class="sorting_asc" tabindex="0" aria-controls="historico-table" rowspan="1" colspan="1" aria-sort="ascending">Jogo</th>
                                                                        <th class="sorting" tabindex="0" aria-controls="historico-table" rowspan="1" colspan="1">Quantia</th>
                                                                        <th class="sorting" tabindex="0" aria-controls="historico-table" rowspan="1" colspan="1">Resultado</th>
                                                                        <th class="sorting" tabindex="0" aria-controls="historico-table" rowspan="1" colspan="1">Data</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <th rowspan="1" colspan="1">Jogo</th>
                                                                        <th rowspan="1" colspan="1">Quantia</th>
                                                                        <th rowspan="1" colspan="1">Resultado</th>
                                                                        <th rowspan="1" colspan="1">Data</th>
                                                                    </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="pills-afiliados" role="tabpanel" aria-labelledby="pills-afiliados-tab" tabindex="0">
                                            <div class="row" style="margin-top: 20px;">
                                                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                                                    <div class="col-md-12">
                                                        <div class="row mb-3 justify-content-center">
                                                            <div class="col-md-4 col-sm-5 mb-2">
                                                                <label for="dataInicial4" class="form-label">Data Inicial:</label>
                                                                <input type="date" id="dataInicial3" class="form-control" value="{{$b}}">
                                                            </div>
                                                            <div class="col-md-4 col-sm-7 mb-2">
                                                                <label for="dataFinal4" class="form-label">Data Final:</label>
                                                                <div class="input-group">
                                                                    <input type="date" id="dataFinal3" class="form-control" value="{{$c}}">
                                                                    <button class="btn btn-success _effect--ripple waves-effect waves-light" onclick="TabelasProcess('afiliacao_agente', '{{$Agent->id}}');" type="button">
                                                                        <span class="text">Buscar</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="widget-content widget-content-area br-8">
                                                        <div id="afiliados-wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                                            <div class="table-responsive">
                                                                <table id="afiliacao_agente" class="table table-striped dt-table-hover dataTable" style="width:100%" role="grid" aria-describedby="afiliados-info">
                                                                    <thead>
                                                                    <tr role="row">
                                                                        <th class="sorting_asc" tabindex="0" aria-controls="afiliados-table" rowspan="1" colspan="1" aria-sort="ascending">Nome</th>
                                                                        <th class="sorting" tabindex="0" aria-controls="afiliados-table" rowspan="1" colspan="1">E-mail</th>
                                                                        <th class="sorting" tabindex="0" aria-controls="afiliados-table" rowspan="1" colspan="1">Total Arrecadado</th>
                                                                        <th class="sorting" tabindex="0" aria-controls="afiliados-table" rowspan="1" colspan="1">Data Cadastro</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    </tbody>
                                                                    <tfoot>
                                                                    <tr>
                                                                        <th rowspan="1" colspan="1">Nome</th>
                                                                        <th rowspan="1" colspan="1">E-mail</th>
                                                                        <th rowspan="1" colspan="1">Total Arrecadado</th>
                                                                        <th rowspan="1" colspan="1">Data Cadastro</th>
                                                                    </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="pills-estatisticas" role="tabpanel" aria-labelledby="pills-estatisticas-tab" tabindex="0">

                                            <div class="row" style="margin-top: 20px;">
                                                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                                                    <div class="col-md-12">
                                                        <div class="row mb-3 justify-content-center">
                                                            <div class="col-md-4 col-sm-5 mb-2">
                                                                <label for="dataInicial" class="form-label">Data Inicial:</label>
                                                                <input type="date" id="dataInicial1" class="form-control" value="{{$b}}">
                                                            </div>
                                                            <div class="col-md-4 col-sm-7 mb-2">
                                                                <label for="dataFinal" class="form-label">Data Final:</label>
                                                                <div class="input-group">
                                                                    <input type="date" id="dataFinal1" class="form-control" value="{{$c}}">
                                                                    <button class="btn btn-success _effect--ripple waves-effect waves-light" onclick="FilterUserStats('{{$Agent->id}}');" type="button">
                                                                        <span class="text">Buscar</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @php
                                                        // Calcular totais
                                                        $userId = $Agent->id;

                                                        // Total de depósitos
                                                        $totalDepositos = App\Models\Transactions::where('user_id', $userId)
                                                            ->where('type', 0)
                                                            ->where('status', 1)
                                                            ->sum('amount');

                                                        // Total de saques
                                                        $totalSaques = App\Models\Transactions::where('user_id', $userId)
                                                            ->where('type', 1)
                                                            ->where('status', 1)
                                                            ->sum('amount');

                                                        // Total de bônus (calcular a partir do histórico de movimentações)
                                                        $totalBonus = App\Models\Transactions::where('user_id', $userId)
                                                            ->where('with_type', 'bonus')
                                                            ->where('status', 1)
                                                            ->sum('amount');

                                                        // Total de apostas em esportes
                                                        $totalApostasEsportes = App\Models\SportBetSummary::where('user_id', $userId)->where('operation', 'debit')->sum('amount');

                                                        // Total de ganhos em esportes
                                                        $totalGanhosEsportes = App\Models\SportBetSummary::where('user_id', $userId)->where('operation', 'credit')->sum('amount');

                                                        // Total de apostas em cassino
                                                        $totalApostasCassino = App\Models\GameHistory::where('user_id', $userId)->sum('amount');

                                                        // Total de ganhos em cassino
                                                        $totalGanhosCassino = App\Models\GameHistory::where('user_id', $userId)->where('action', 'win')->sum('amount');

                                                        // Total de perdas em cassino
                                                        $totalPerdasCassino = App\Models\GameHistory::where('user_id', $userId)->where('action', 'loss')->sum('amount');

                                                        // Total de prêmios (ganhos em esportes + ganhos em cassino)
                                                        $totalPremios = $totalGanhosEsportes + $totalGanhosCassino;

                                                        // Calcular valor líquido (depósitos - saques)
                                                        $valorLiquido = $totalDepositos - $totalSaques;

                                                        // Calcular saldo cassino (ganhos - perdas)
                                                        $saldoCassino = $totalGanhosCassino - $totalPerdasCassino;

                                                        // Calcular percentuais para os progress bars
                                                        $totalMovimentacao = $totalDepositos + $totalSaques;
                                                        $percentualDeposito = ($totalMovimentacao > 0) ? ($totalDepositos / $totalMovimentacao) * 100 : 0;
                                                        $percentualSaque = ($totalMovimentacao > 0) ? ($totalSaques / $totalMovimentacao) * 100 : 0;

                                                        // Calcular percentuais para cassino
                                                        $totalCassinoMovimentacao = $totalGanhosCassino + $totalPerdasCassino;
                                                        $percentualGanhosCassino = ($totalCassinoMovimentacao > 0) ? ($totalGanhosCassino / $totalCassinoMovimentacao) * 100 : 0;
                                                        $percentualPerdasCassino = ($totalCassinoMovimentacao > 0) ? ($totalPerdasCassino / $totalCassinoMovimentacao) * 100 : 0;

                                                        // Formatar números para exibição
                                                        $formatarNumero = function($valor) {
                                                            return number_format($valor, 2, ',', '.');
                                                        };
                                                    @endphp
                                                    <style>
                                                        body.dark .widget.widget-card-four{
                                                            background:#1b2e4b!important;
                                                        }
                                                    </style>
                                                    <!-- Cards de estatísticas -->
                                                    <div style="min-height: 300px;" id="conteudostats">
                                                        <div class="row" style="padding-top: 20px;">
                                                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                                                                <div class="widget widget-card-four">
                                                                    <div class="widget-content">
                                                                        <div class="w-header">
                                                                            <div class="w-info">
                                                                                <h6 class="value">Total de Depósitos</h6>
                                                                            </div>
                                                                        </div>

                                                                        <div class="w-content">
                                                                            <div class="w-info">
                                                                                <p class="value" style="color: #2bc155;">R$ {{ $formatarNumero($totalDepositos) }} <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2bc155" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up" style="color: #2bc155;"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                                                                <div class="widget widget-card-four">
                                                                    <div class="widget-content">
                                                                        <div class="w-header">
                                                                            <div class="w-info">
                                                                                <h6 class="value">Total de Bônus</h6>
                                                                            </div>
                                                                        </div>

                                                                        <div class="w-content">
                                                                            <div class="w-info">
                                                                                <p class="value" style="color: #e2a03f;">R$ {{ $formatarNumero($totalBonus) }} <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e2a03f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                                                                <div class="widget widget-card-four">
                                                                    <div class="widget-content">
                                                                        <div class="w-header">
                                                                            <div class="w-info">
                                                                                <h6 class="value">Total de Prêmios <span class="badge badge-primary" style="font-size: 10px;">Cassino</span></h6>
                                                                            </div>
                                                                        </div>

                                                                        <div class="w-content">
                                                                            <div class="w-info">
                                                                                <p class="value" style="color: #4361ee;">R$ {{ $formatarNumero($totalGanhosCassino) }} <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4361ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                                                                <div class="widget widget-card-four">
                                                                    <div class="widget-content">
                                                                        <div class="w-header">
                                                                            <div class="w-info">
                                                                                <h6 class="value">Total de Saques</h6>
                                                                            </div>
                                                                        </div>

                                                                        <div class="w-content">
                                                                            <div class="w-info">
                                                                                <p class="value" style="color: #e7515a;">R$ {{ $formatarNumero($totalSaques) }} <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e7515a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-down" style="color: #e7515a;"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                                                                <div class="widget widget-card-four">
                                                                    <div class="widget-content">
                                                                        <div class="w-header">
                                                                            <div class="w-info">
                                                                                <h6 class="value">Total de Ap. em Esportes</h6>
                                                                            </div>
                                                                        </div>

                                                                        <div class="w-content">
                                                                            <div class="w-info">
                                                                                <p class="value" style="color: #805dca;">R$ {{ $formatarNumero($totalApostasEsportes) }} <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#805dca" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                                                                <div class="widget widget-card-four">
                                                                    <div class="widget-content">
                                                                        <div class="w-header">
                                                                            <div class="w-info">
                                                                                <h6 class="value">Total de Perdas <span class="badge badge-danger" style="font-size: 10px;">Cassino</span></h6>
                                                                            </div>
                                                                        </div>

                                                                        <div class="w-content">
                                                                            <div class="w-info">
                                                                                <p class="value" style="color: #e7515a;">R$ {{ $formatarNumero($totalPerdasCassino) }} <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e7515a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-down"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Progress Bars -->
                                                        <div class="row">
                                                            <div class="col-12 mb-4">
                                                                <div class="widget widget-five">
                                                                    <div class="widget-heading">
                                                                        <a href="javascript:void(0)" class="task-info">
                                                                            <div class="w-title">
                                                                                <h5>Comparativo Financeiro</h5>
                                                                                <p>Resumo das operações financeiras realizadas pelo usuário.</p>
                                                                            </div>
                                                                        </a>
                                                                    </div>

                                                                    <div class="widget-content">
                                                                        <div class="browser-list mt-4">
                                                                            <div class="w-icon">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2bc155" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up" style="color: #2bc155;"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                                                                            </div>
                                                                            <div class="w-browser-details">
                                                                                <div class="w-browser-info">
                                                                                    <h6>Depósitos</h6>
                                                                                    <p class="browser-count" style="float: right; margin-top: -25px;">{{ number_format($percentualDeposito, 1) }}%</p>
                                                                                </div>

                                                                                <div class="w-browser-stats">
                                                                                    <div class="progress">
                                                                                        <div class="progress-bar bg-gradient-success" role="progressbar" style="width: {{ $percentualDeposito }}%;" aria-valuenow="{{ $percentualDeposito }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="browser-list mt-4">
                                                                            <div class="w-icon">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e7515a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-down" style="color: #e7515a;"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
                                                                            </div>
                                                                            <div class="w-browser-details">
                                                                                <div class="w-browser-info">
                                                                                    <h6>Saques</h6>
                                                                                    <p class="browser-count" style="float: right; margin-top: -25px;">{{ number_format($percentualSaque, 1) }}%</p>
                                                                                </div>

                                                                                <div class="w-browser-stats">
                                                                                    <div class="progress">
                                                                                        <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: {{ $percentualSaque }}%;" aria-valuenow="{{ $percentualSaque }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="browser-list mt-4">
                                                                            <div class="w-icon">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4361ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-award"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                                                                            </div>
                                                                            <div class="w-browser-details">
                                                                                <div class="w-browser-info">
                                                                                    <h6>Ganhos Cassino</h6>
                                                                                    <p class="browser-count" style="float: right; margin-top: -25px;">{{ number_format($percentualGanhosCassino, 1) }}%</p>
                                                                                </div>

                                                                                <div class="w-browser-stats">
                                                                                    <div class="progress">
                                                                                        <div class="progress-bar bg-gradient-primary" role="progressbar" style="width: {{ $percentualGanhosCassino }}%;" aria-valuenow="{{ $percentualGanhosCassino }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="browser-list mt-4">
                                                                            <div class="w-icon">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e7515a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-down"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>
                                                                            </div>
                                                                            <div class="w-browser-details">
                                                                                <div class="w-browser-info">
                                                                                    <h6>Perdas Cassino</h6>
                                                                                    <p class="browser-count" style="float: right; margin-top: -25px;">{{ number_format($percentualPerdasCassino, 1) }}%</p>
                                                                                </div>

                                                                                <div class="w-browser-stats">
                                                                                    <div class="progress">
                                                                                        <div class="progress-bar bg-gradient-danger" role="progressbar" style="width: {{ $percentualPerdasCassino }}%;" aria-valuenow="{{ $percentualPerdasCassino }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="meta-info">
                                                                            <div class="due-time">
                                                                                <p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg> Valor Líquido: <strong> R$ {{ $formatarNumero($valorLiquido) }} </strong></p>
                                                                            </div>
                                                                            <div class="due-time">
                                                                                <p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg> Total de Apostas Cassino: <strong> R$ {{ $formatarNumero($totalApostasCassino) }} </strong></p>
                                                                            </div>
                                                                            <div class="due-time">
                                                                                <p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calculator"><rect x="4" y="3" width="16" height="18" rx="2" ry="2"></rect><line x1="8" y1="7" x2="16" y2="7"></line><line x1="8" y1="11" x2="16" y2="11"></line><line x1="8" y1="15" x2="16" y2="15"></line><line x1="8" y1="19" x2="16" y2="19"></line></svg> Saldo Cassino (Ganhos - Perdas): <strong style="color: {{ $saldoCassino >= 0 ? '#2bc155' : '#e7515a' }}"> R$ {{ $formatarNumero($saldoCassino) }} </strong></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div
@endif
