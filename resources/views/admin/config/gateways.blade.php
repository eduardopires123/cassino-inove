@extends('admin.layouts.app')
@section('content')
    @php
        $InfoEd = App\Models\Gateways::Where('nome', 'EdPay')->first();
    @endphp
    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Administração</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Gateways</li>
                    </ol>
                </nav>
            </div>

            <div class="row" style="margin-top: 20px;">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div id="iconsAccordion" class="accordion-icons accordion">
                        <div class="card">
                            <div class="card-header" id="...">
                                <section class="mb-0 mt-0">
                                    <div role="menu" class="" data-bs-toggle="collapse" data-bs-target="#iconAccordionOne" aria-expanded="true" aria-controls="iconAccordionOne">
                                        EdPay  <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                                    </div>
                                </section>
                            </div>
                            <div id="iconAccordionOne" class="collapse show" aria-labelledby="..." data-bs-parent="#iconsAccordion">
                                <div class="card-body" style="color: #888ea8;">
                                    <div class="form-group">
                                        <div>
                                            <input type="checkbox" id="cedpay" class="form-check-input" name="cedpay" onclick="(this.checked) ? AttGateway('EdPay', 'active', 1) : AttGateway('EdPay', 'active', 0)" {!! ($InfoEd->active == 1) ? "checked" : "" !!} />
                                            <label for="scales">Ativado</label>
                                        </div>
                                        <hr>
                                        <form class="row g-3">
                                            <div class="col-md-6">
                                                <label for="inputEmail4" class="form-label">Secret Key</label>

                                                <div class="input-group">
                                                    <div class="input-group-text" id="hidecskedpay" style="cursor: pointer;" onclick="HideField('hidecskedpay', 'cskedpay');"><i class="fa fa-eye" aria-hidden="true"></i></div>
                                                    <input type="text" class="form-control" id="cskedpay" value="{{$InfoEd->secret_key}}" data-id="{{$InfoEd->secret_key}}" onblur="AttGateway('EdPay', 'secret_key', this);">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="inputPassword4" class="form-label">Public & Client Id Key</label>

                                                <div class="input-group">
                                                    <div class="input-group-text" id="hidecpckseday" style="cursor: pointer;" onclick="HideField('hidecpckseday', 'cpckedpay');"><i class="fa fa-eye" aria-hidden="true"></i></div>
                                                    <input type="text" class="form-control" id="cpckedpay" value="{{$InfoEd->public_clientid_key}}" data-id="{{$InfoEd->public_clientid_key}}" onblur="AttGateway('EdPay', 'public_clientid_key', this);">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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
                // Função para atualizar campos de gateway
                function AttGateway(nome, field, value) {
                    // Verificar se o valor é um objeto (elemento de campo de texto)
                    if (typeof value === 'object' && value !== null) {
                        const element = value;
                        const originalValue = element.getAttribute('data-id') || '';
                        value = element.value || ''; // Garantir que valores undefined sejam tratados como string vazia

                        // Se o valor não mudou, não fazemos nada
                        if (value === originalValue) {
                            return;
                        }

                        // Armazenar o valor original para caso seja necessário reverter
                        const savedOriginalValue = originalValue;

                        // Atualizar o atributo data-id para refletir o novo valor
                        element.setAttribute('data-id', value);
                    }

                    // Prevenir múltiplas chamadas simultâneas
                    if (window.isUpdatingGateway) {
                        ToastManager.info('Outra atualização está em andamento. Aguarde...');
                        return;
                    }

                    window.isUpdatingGateway = true;

                    // Mostrar indicador de carregamento
                    const loadingToast = ToastManager.info('Salvando alterações...', 0);

                    // Enviar solicitação para atualizar o gateway
                    fetch('/admin/config/atualizar-gateway', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            nome: nome,
                            field: field,
                            value: value
                        })
                    })
                        .then(response => {
                            // Remover toast de carregamento
                            if (loadingToast) {
                                loadingToast.remove();
                            }

                            // Verificar se a resposta está OK (status 200-299)
                            if (!response.ok) {
                                if (response.status === 400) {
                                    throw new Error('Parâmetros inválidos. Verifique os dados informados.');
                                } else if (response.status === 500) {
                                    throw new Error('Erro interno do servidor. Tente novamente mais tarde.');
                                } else if (response.status === 404) {
                                    throw new Error('Gateway não encontrado.');
                                } else {
                                    throw new Error('Erro na resposta do servidor: ' + response.status);
                                }
                            }

                            // Tentar converter a resposta para JSON
                            try {
                                return response.json();
                            } catch (error) {
                                throw new Error('Erro ao processar resposta do servidor. Formato inválido.');
                            }
                        })
                        .then(data => {
                            window.isUpdatingGateway = false;

                            if (data && data.success) {
                                // Mostrar mensagem de sucesso
                                if (field === 'active') {
                                    if (value == 1) {
                                        ToastManager.success('Gateway ativado com sucesso!');
                                    } else {
                                        ToastManager.error('Gateway desativado!');
                                    }
                                } else {
                                    ToastManager.success(data.message || 'Informação atualizada com sucesso!');
                                }
                            } else {
                                // Mostrar mensagem de erro
                                ToastManager.error(data && data.message ? data.message : 'Erro ao atualizar informação!');

                                // Reverter alteração visual no campo se necessário
                                if (typeof value === 'object' && value !== null) {
                                    const element = value;
                                    const originalValue = element.getAttribute('data-original-value') || "";
                                    element.value = originalValue;
                                    element.setAttribute('data-id', originalValue);
                                }
                            }
                        })
                        .catch(error => {
                            window.isUpdatingGateway = false;
                            console.error('Erro:', error);
                            ToastManager.error('Erro ao atualizar informação: ' + error.message);

                            // Se houver um elemento, reverter para o valor original
                            if (typeof value === 'object' && value !== null) {
                                const element = value;
                                const originalValue = element.getAttribute('data-original-value') || "";
                                element.value = originalValue;
                                element.setAttribute('data-id', originalValue);
                            }
                        });
                }

                // Função para esconder/mostrar campos
                function HideField(eyeId, fieldId) {
                    const eyeElement = document.getElementById(eyeId);
                    const fieldElement = document.getElementById(fieldId);

                    if (fieldElement.type === 'password') {
                        fieldElement.type = 'text';
                        eyeElement.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
                    } else {
                        fieldElement.type = 'password';
                        eyeElement.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
                    }
                }

                // Quando o documento estiver pronto
                document.addEventListener('DOMContentLoaded', function() {
                    // Inicializar flag global
                    window.isUpdatingGateway = false;

                    // Selecionar todos os campos de texto de entrada
                    const textInputs = document.querySelectorAll('input[type="text"]');

                    // Adicionar evento de tecla para cada campo
                    textInputs.forEach(input => {
                        // Salvar o valor original para recuperação em caso de erro
                        input.setAttribute('data-original-value', input.value);

                        // Quando pressionar Enter no campo
                        input.addEventListener('keypress', function(e) {
                            if (e.key === 'Enter') {
                                e.preventDefault(); // Prevenir o comportamento padrão do Enter

                                // Mapeamento de IDs para gateways e tipos de campo
                                const fieldMapping = {
                                    'cskedpay': { gateway: 'EdPay', field: 'secret_key' },
                                    'cpckedpay': { gateway: 'EdPay', field: 'public_clientid_key' }
                                };

                                // Verificar se o ID do campo está no mapeamento
                                const mapping = fieldMapping[this.id];
                                if (mapping) {
                                    AttGateway(mapping.gateway, mapping.field, this);
                                }

                                // Remover foco do campo
                                this.blur();
                            }
                        });
                    });
                });
            </script>
    @endpush
