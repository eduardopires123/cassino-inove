@extends('admin.layouts.app')
@section('content')
<div class="layout-px-spacing" id="contentaff">
            <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Afiliação</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Estátistica Geral</li>
                </ol>
            </nav>
        </div>

            <div class="row" style="margin-top: 20px;">
                <div id="flLoginForm" class="col-lg-12 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content widget-content-area">
                            <form method="POST" id="settingsaff" name="settingsaff" action="{{ route('admin.afiliacao.config.salvar') }}" style="padding: 20px;">
                                @csrf

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Mín Saque Afiliado:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="min_saque_af" name="min_saque_af" placeholder="10" value="{{$Settings->getAttribute('min_saque_af')}}">
                                            </div>
                                            <small style="color: darkorange;">Saque mínimo do afiliado</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Máx Saque Afiliado: </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="max_saque_af" name="max_saque_af" placeholder="10" value="{{$Settings->getAttribute('max_saque_af')}}">
                                            </div>
                                            <small style="color: darkorange;">Saque máximo do afiliado</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="ccode">Máx Saque Automático Afiliado:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="max_saque_aut_af" name="max_saque_aut_af" placeholder="10" value="{{$Settings->getAttribute('max_saque_aut_af')}}">
                                            </div>
                                            <small style="color: darkorange;">Saque acima do definido é feito manualmente pelo administrador</small>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="ccode">Ativar Bonificação CPA:</label>
                                            <br>
                                            <div class="form-check form-switch form-check-inline form-switch-primary">
                                                <input class="form-check-input" type="checkbox" role="switch" id="cpaenabled" name="cpaenabled" {!! ($Settings->cpaenabled) ? "checked" : "" !!}>
                                                <label class="form-check-label" for="flexSwitchCheckDefault" style="color: darkorange;">Ativa a bonificação por indicação relacionado ao depósito</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Mín Afiliado Dep:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="aff_min_dep" name="aff_min_dep" placeholder="" value="{{$Settings->getAttribute('aff_min_dep')}}">
                                            </div>
                                            <small style="color: darkorange;">Valor minimo de depósito do indicado para afiliado receber bônus</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Bônus Afiliado por Indicação:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="aff_amount" name="aff_amount" placeholder="" value="{{$Settings->getAttribute('aff_amount')}}">
                                            </div>
                                            <small style="color: darkorange;">Valor que afiliado recebe quando indicado depósita o mínimo</small>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="ccode">Ativar Bonificação RevShare:</label>
                                            <br>
                                            <div class="form-check form-switch form-check-inline form-switch-primary">
                                                <input class="form-check-input" type="checkbox" role="switch" id="revenabled" name="revenabled" {!! ($Settings->revenabled) ? "checked" : "" !!}>
                                                <label class="form-check-label" for="flexSwitchCheckDefault" style="color: darkorange;">Ativa a bonificação de ganho percentual em relação a perda nos jogos</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="ccode">Porcentagem Padrão:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="percent_aff" name="percent_aff" placeholder="" value="{{$Settings->getAttribute('percent_aff')}}">
                                            </div>
                                            <small style="color: darkorange;">Porcentagem padrão que o afiliado ganha de cada perda dos indicados. </small>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="d-flex justify-content-end me-3">
                                <button id="btnSalvar" type="button" class="btn btn-success mb-2 btn-lg _effect--ripple waves-effect waves-light">Salvar Configurações</button>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Array para armazenar os campos modificados
        let changedFields = {};
        
        // Formatação para campos monetários (BRL)
        const currencyFields = [
            'min_saque_af', 'max_saque_af', 'max_saque_aut_af', 'aff_min_dep', 'aff_amount'
        ];
        
        // Lista de campos percentuais
        const percentFields = ['percent_aff'];
        
        // Lista de campos de checkbox
        const checkboxFields = ['cpaenabled', 'revenabled'];
        
        // Capturar todos os campos do formulário
        const allFields = document.querySelectorAll('#settingsaff input, #settingsaff select, #settingsaff textarea');
        
        // Rastrear mudanças em todos os campos
        allFields.forEach(field => {
            // Armazenar o valor original para comparação
            const originalValue = field.type === 'checkbox' ? field.checked : field.value;
            
            // Adicionar evento para detectar mudanças
            field.addEventListener('change', function() {
                const currentValue = field.type === 'checkbox' ? field.checked : field.value;
                if (currentValue !== originalValue) {
                    changedFields[field.name] = currentValue;
                } else {
                    delete changedFields[field.name];
                }
            });
        });
        
        // Formatar campos monetários
        currencyFields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                // Formatar valor inicial
                let value = parseFloat(input.value);
                if (!isNaN(value)) {
                    input.value = value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
                
                // Adicionar evento para formatar ao digitar
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value === '') {
                        e.target.value = '';
                        return;
                    }
                    
                    value = parseFloat(value) / 100;
                    e.target.value = value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                });
            }
        });
        
        // Formatar campos de porcentagem
        percentFields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                // Formatar valor inicial
                let value = parseFloat(input.value);
                if (!isNaN(value)) {
                    input.value = value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
                
                // Adicionar evento para formatar ao digitar
                input.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value === '') {
                        e.target.value = '';
                        return;
                    }
                    
                    value = parseFloat(value) / 100;
                    e.target.value = value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                });
            }
        });
        
        // Verificar se os botões existem para evitar erros
        const btnSalvar = document.getElementById('btnSalvar');
        if (!btnSalvar) return;
        
        // Substitua o comportamento padrão do formulário para evitar redirecionamento
        const form = document.getElementById('settingsaff');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Impedir envio normal do formulário
            });
        }
        
        // Envio do formulário com toast e modal
        btnSalvar.addEventListener('click', function(e) {
            // Verificar se há campos alterados
            if (Object.keys(changedFields).length === 0) {
                if (typeof ToastManager !== 'undefined') {
                    ToastManager.info('Nenhuma alteração detectada para salvar.');
                } else {
                    alert('Nenhuma alteração detectada para salvar.');
                }
                return;
            }
            
            // Mostrar modal de confirmação usando ModalManager se disponível
            if (typeof ModalManager !== 'undefined' && typeof ModalManager.showConfirmation === 'function') {
                ModalManager.showConfirmation(
                    'Confirmar Alterações',
                    'Deseja salvar as alterações realizadas nas configurações de afiliados?',
                    function() {
                        // Antes de enviar, remover a formatação
                        enviarViaAjax();
                    }
                );
            } else {
                // Fallback para confirm nativo
                if (confirm('Deseja salvar as alterações nas configurações de afiliados?')) {
                    enviarViaAjax();
                }
            }
        });
        
        // Função para enviar via Ajax e tratar a resposta
        function enviarViaAjax() {
            // Exibir toast de processamento se disponível
            let processingToast;
            if (typeof ToastManager !== 'undefined' && typeof ToastManager.info === 'function') {
                processingToast = ToastManager.info('Processando, aguarde...');
            }
            
            // Clonar os valores originais antes de modificá-los para o envio
            const originalValues = {};
            
            // Remover formatação dos campos monetários e percentuais antes de enviar
            currencyFields.concat(percentFields).forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    originalValues[field] = input.value; // Guardar valor formatado
                    let value = input.value.replace(/\./g, '').replace(',', '.');
                    input.value = value; // Valor desformatado para envio
                }
            });
            
            // Preparar dados do formulário
            const form = document.getElementById('settingsaff');
            const formData = new FormData(form);
            
            // Enviar via fetch e tratar resposta
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                // Restaurar valores originais formatados
                currencyFields.concat(percentFields).forEach(field => {
                    const input = document.getElementById(field);
                    if (input && originalValues[field]) {
                        input.value = originalValues[field];
                    }
                });
                
                // Remover toast de processamento
                if (processingToast && typeof processingToast.remove === 'function') {
                    processingToast.remove();
                }
                
                // Mostrar mensagem de sucesso
                if (typeof ToastManager !== 'undefined' && typeof ToastManager.success === 'function') {
                    ToastManager.success('Configurações de afiliados salvas com sucesso!');
                } else {
                    alert('Configurações de afiliados salvas com sucesso!');
                }
                
                // Recarregar a página após um breve delay
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            })
            .catch(error => {
                // Restaurar valores originais formatados em caso de erro
                currencyFields.concat(percentFields).forEach(field => {
                    const input = document.getElementById(field);
                    if (input && originalValues[field]) {
                        input.value = originalValues[field];
                    }
                });
                
                // Remover toast de processamento
                if (processingToast && typeof processingToast.remove === 'function') {
                    processingToast.remove();
                }
                
                // Mostrar erro
                console.error('Erro:', error);
                if (typeof ToastManager !== 'undefined' && typeof ToastManager.error === 'function') {
                    ToastManager.error('Ocorreu um erro ao salvar as configurações.');
                } else {
                    alert('Ocorreu um erro ao salvar as configurações.');
                }
            });
        }
    });
</script>
@endpush