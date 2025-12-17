@extends('admin.layouts.app')
@section('content')
<div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Administração</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Banco</li>
                    </ol>
                </nav>
            </div>

            <div class="row" style="margin-top: 20px;">
                <div id="flLoginForm" class="col-lg-12 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content widget-content-area" style="padding: 20px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <button class="btn btn-primary" type="button" id="button-addon1">Entrada Hoje</button>
                                        <input type="text" id="ehj" class="form-control" placeholder="Aguarde..." aria-label="notification" aria-describedby="button-addon1" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <button class="btn btn-primary" type="button" id="button-addon1">Saída Hoje</button>
                                        <input type="text" id="shj" class="form-control" placeholder="Aguarde..." aria-label="notification" aria-describedby="button-addon1" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <button class="btn btn-primary" type="button" id="button-addon1">Saldo Disponível em Conta</button>
                                <input type="text" id="bdi" class="form-control" placeholder="Aguarde..." aria-label="notification" aria-describedby="button-addon1" readonly>
                            </div>

                            <hr>

                            <div class="input-group mb-3">
                                <button class="btn btn-primary" type="button" id="button-addon1">Total Saque</button>
                                <input type="number" id="asaq" class="form-control" onblur="this.value = parseFloat(this.value || 0).toFixed(2);">
                                <button class="btn btn-success btn-lg" type="button" onclick="RealizaSaque()" id="button-addon2">Sacar</button>
                            </div>

                            <div style="color: darkorange; text-align: center;">
                                O PIX de pagamento é a da conta do Administrador.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    //ToastManager.error("Load Balance");
    //ToastManager.success("Load Balance");

    function RealizaSaque(){
        var valor = document.getElementById('asaq').value;

        if (valor !== "") {
            Swal.fire({
                title: 'Confirmação',
                text: "Deseja realizar o saque de R$ " + valor + " ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Sacar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/gw-sq-v6n8",
                        type: "POST",
                        data: {
                            valor: valor,
                            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        success: function (response) {
                            if (response.status === false) {
                                ToastManager.error(response.message);
                            } else {
                                ToastManager.success(response.message);
                                setTimeout(() => location.reload(), 2000);
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 419) {
                                ToastManager.error('Ocorreu um erro, recarregue a página!');
                            } else {
                                ToastManager.error(xhr.msg);
                            }
                        }
                    });
                } else {
                    return 0;
                }
            })
        }
    }

    function FormataMoedaUSD(Entrada) {
        const Saida = new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 20,
            useGrouping: true
        }).format(Entrada);

        return 'R$ ' + Saida.trim();
    }

    async function GetBalanceEdPay(){
        try {
            const response = await $.ajax({
                url: "/gw-bl-h2k7",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                data: $(this).serialize()
            });

            if (response.status === false) {
                ToastManager.error(response.message)
            } else {
                document.getElementById('ehj').value = FormataMoedaUSD(response.infos.amountTodayPayIn);
                document.getElementById('shj').value = FormataMoedaUSD(response.infos.amountTodayPayOut);
                document.getElementById('bdi').value = FormataMoedaUSD(response.infos.availableBalance);
            }
        } catch (xhr) {
            if (xhr.status === 419) {
                ToastManager.error('Ocorreu um erro, recarregue a página!');
            } else {
                ToastManager.error(xhr.msg)
            }
        }
    }

    GetBalanceEdPay();
</script>
@endpush
@endsection