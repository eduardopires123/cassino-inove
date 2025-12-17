@extends('admin.layouts.app')
@section('content')
    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0"style="padding:20px;">
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Sportsbook</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Configurações</li>
                    </ol>
                </nav>
            </div>

            <div class="row" style="margin-top: 20px;">
                <div id="flLoginForm" class="col-lg-12 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content widget-content-area">
                            <form method="POST" id="settingsSports" name="settingsSports" action="#" style="padding:20px;" enctype="multipart/form-data">
                                @csrf
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="ccode">Ativar:</label>
                                            <br>
                                            <div class="form-check form-switch form-check-inline form-switch-primary">
                                                <input class="form-check-input" type="checkbox" role="switch" onchange="EnableDisableSport(this.checked, 0);" id="sportsbook_enabled" name="sportsbook_enabled"  {{ (App\Helpers\Core::getSetting()->enable_sports === 1) ? "checked" : "" }}>
                                                <label class="form-check-label" for="flexSwitchCheckDefault" style="color: darkorange;">Ative e Desative o Sportsbook</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="ccode">Aposta com Bônus:</label>
                                            <br>
                                            <div class="form-check form-switch form-check-inline form-switch-primary">
                                                <input class="form-check-input" type="checkbox" role="switch" onchange="EnableDisableSport(this.checked, 1);" id="sportsbook_enabled" name="sportsbook_enabled"  {{ (App\Helpers\Core::getSetting()->enable_sports_bonus === 1) ? "checked" : "" }}>
                                                <label class="form-check-label" for="flexSwitchCheckDefault" style="color: darkorange;">Ative e Desative aposta com bônus</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                            <div class="d-flex justify-content-center me-3">
                                <a href="https://api.inoveigaming.com/dashboard" class="btn btn-primary mb-2 btn-lg _effect--ripple waves-effect waves-light me-2">Painel Avançado Sportsbook</a>
                                <a href="{{ route('admin.sports.campeonatos_ocultos') }}" class="btn btn-success mb-2 btn-lg _effect--ripple waves-effect waves-light">Gerenciar Campeonatos Ocultos</a>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function EnableDisableSport(valor, bonus) {
        $.ajax({
            url: "/adm-sp-cfg/" + bonus,
            type: "POST",
            data: {
                valor: valor,
                _token: $('meta[name="csrf-token"]').attr('content')}
            ,
            success: function (response) {
                if (response.status) {
                    ToastManager.success(response.message);
                } else {
                    ToastManager.error(response.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 419) {
                    ToastManager.error('');
                } else {
                    ToastManager.error(xhr.msg);
                }
            }
        });
    }
</script>
