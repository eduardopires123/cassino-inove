@extends('admin.layouts.app')
@section('content')
@php
        $a = "";
        $b = "";
        $c = "";
        $user = null;

        if (isset($queryParams["aff"])) {$a = $queryParams["aff"];}else{$a = "";}
        if (isset($queryParams["di"])) {$b = $queryParams["di"]; $bb = Carbon\Carbon::parse($b)->startOfDay();}else{$bb = "";}
        if (isset($queryParams["df"])) {$c = $queryParams["df"]; $cc = Carbon\Carbon::parse($c)->endOfDay();}else{$cc = "";}

        $userTable = App\Models\Transactions::Where('type', 2)->orderBy('id', 'desc');

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
<div class="layout-px-spacing" id="contentaff">
            <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Pagamentos</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Histórico de Pagamentos</li>
                </ol>
            </nav>
        </div>

        <div class="row" style="margin-top: 20px;">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12" style="margin-top:15px;">
            <div class="alert alert-outline-primary alert-dismissible fade show mb-4" role="alert">
                <div class="form-row" style="text-align:center;">
                    <label for="dataInicial" class="form-label">Data Inicial:</label>
                    <input type="date" id="dataInicial" class="form-afil" value="{{$b}}">

                    <label for="dataFinal" class="form-label">Data Final:</label>
                    <input type="date" id="dataFinal" class="form-afil" value="{{$c}}">

                    <label for="inputTexto" class="form-label">Nome:</label>
                    <input type="text" id="example2" placeholder="insira o nome do usuário..." value="{{$a}}" class="form-afil">

                    <button class="btn btn-success btn-icon-split" onclick="OpenURL('page/depositos', 'pagamentos?aff='+document.getElementById('example2').value+'&di='+document.getElementById('dataInicial').value+'&df='+document.getElementById('dataFinal').value);" type="button"><span class="text">Buscar</span></button>
                </div>
            </div>
        </div>

                <div class="statbox widget box box-shadow">
                    <div class="widget-content widget-content-area">
                        <table id="zero-config" class="table table-striped dt-table-hover dataTable" style="width:100%" role="grid" aria-describedby="zero-config_info">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Valor</th>
                                <th>Gateway</th>
                                <th>Status</th>
                                <th>Data</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                //$userTable = App\Models\Transactions::Where('type', 2)->orderBy('id', 'desc')->get();
                            @endphp
                            @foreach($userTable as $registro)
                                @php
                                    if ($registro->status == 0){
                                        $status = '<span class="badge badge-light-warning mb-2 me-4">Pendente</span>';
                                    }elseif ($registro->status == 1){
                                        $status = '<span class="badge badge-light-success mb-2 me-4">Concluído</span>';
                                    }elseif ($registro->status == 2){
                                        $status = '<span class="badge badge-light-danger mb-2 me-4">Cancelado</span>';
                                    }
                                @endphp
                                <tr>
                                    <td><a href="javascript:void(0);" onclick="LoadAgent('{{$registro->User->id}}');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário" data-original-title="Visualizar Usuário">{{$registro->User->name}}</a></td>
                                    <td>R$ {{$registro->amount}}</td>
                                    <td>{{$registro->gateway}}</td>
                                    <td>{!! $status !!}</td>
                                    <td>{{$registro->updated_at->format('d/m/Y H:i:s')}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Nome</th>
                                <th>Valor</th>
                                <th>Gateway</th>
                                <th>Status</th>
                                <th>Data</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
@endsection
