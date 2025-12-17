@extends('admin.layouts.app')
@section('content')
@php
    $Settings = App\Helpers\Core::getSetting();
@endphp
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Administração</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">APIS Games</li>
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
                                    Sports e Cassino (Inove)  <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                                </div>
                            </section>
                        </div>
                        <div id="iconAccordionOne" class="collapse show" aria-labelledby="..." data-bs-parent="#iconsAccordion">
                            <div class="card-body" style="color: #888ea8;">
                                <div class="form-group">
                                    <form class="row g-3">
                                        <div class="col">
                                            <label for="inputEmail4" class="form-label">Partner Name</label>

                                            <div class="input-group">
                                                <div class="input-group-text" id="hidesportpartnername" style="cursor: pointer;" onclick="HideField('hidesportpartnername', 'sportpartnername');"><i class="fa fa-eye" aria-hidden="true"></i></div>
                                                <input type="text" class="form-control" id="sportpartnername" value="{{$Settings->sportpartnername}}" data-id="{{$Settings->sportpartnername}}" onblur="AttApi('Sports', 'sportpartnername', this);">
                                            </div>
                                        </div>
                                    </form>

                                    <form class="row g-3 mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Provedor de API Sports</label>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="radio" name="sports_api_provider" id="digitain_provider" value="digitain"
                                                       {{ ($Settings->sports_api_provider ?? 'digitain') == 'digitain' ? 'checked' : '' }}
                                                       onchange="updateSportsProvider('digitain')">
                                                <label class="form-check-label" for="digitain_provider">
                                                    <strong>DIGITAIN</strong>
                                                    <small class="text-muted d-block">API tradicional de esportes</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="radio" name="sports_api_provider" id="betby_provider" value="betby"
                                                       {{ ($Settings->sports_api_provider ?? 'digitain') == 'betby' ? 'checked' : '' }}
                                                       onchange="updateSportsProvider('betby')">
                                                <label class="form-check-label" for="betby_provider">
                                                    <strong>BETBY</strong>
                                                    <small class="text-muted d-block">Nova API de esportes integrada</small>
                                                </label>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="alert alert-info mt-3 mb-0" role="alert">
                                        <i class="fa fa-info-circle me-2"></i>
                                        <strong>Partner Name:</strong> O Partner Name é disponibilizado na hora de fazer o cadastro no site da API.
                                        <br>
                                        <a href="https://api.inoveigaming.com" target="_blank" class="alert-link">
                                            <i class="fa fa-external-link me-1"></i> Acesse: api.inoveigaming.com
                                        </a>
                                    </div>
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

@push('scripts')
<script>
// Função para atualizar o provedor de Sports API
function updateSportsProvider(provider) {
    // Prevenir múltiplas chamadas simultâneas
    if (window.isUpdatingApi) {
        ToastManager.info('Outra atualização está em andamento. Aguarde...');
        return;
    }

    window.isUpdatingApi = true;

    // Mostrar indicador de carregamento
    const loadingToast = ToastManager.info('Atualizando provedor de API Sports...', 0);

    // Enviar solicitação para atualizar o provedor
    fetch('/admin/config/atualizar-api', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            nome: 'Sports',
            field: 'sports_api_provider',
            value: provider
        })
    })
    .then(response => {
        // Remover toast de carregamento
        if (loadingToast) {
            loadingToast.remove();
        }

        if (!response.ok) {
            throw new Error('Erro na resposta do servidor: ' + response.status);
        }

        return response.json();
    })
    .then(data => {
        window.isUpdatingApi = false;

        if (data && data.success) {
            ToastManager.success('Provedor de API Sports atualizado para: ' + provider.toUpperCase());

            // Limpar o cache após atualização bem-sucedida
            fetch('/admin/clear-cache', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(cacheResponse => {
                // Verificar se a resposta é JSON válida
                const contentType = cacheResponse.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Resposta não é JSON válida. Tipo de conteúdo: ' + contentType);
                }
                return cacheResponse.json();
            })
            .then(cacheData => {
                if (cacheData && cacheData.success) {
                    ToastManager.success('Cache limpo com sucesso');
                } else {
                    console.error('Erro ao limpar cache:', cacheData.message);
                }
            })
            .catch(cacheError => {
                console.error('Erro ao limpar cache:', cacheError);
                ToastManager.warning('Cache pode não ter sido limpo completamente. Verifique manualmente se necessário.');
            });
        } else {
            ToastManager.error(data && data.message ? data.message : 'Erro ao atualizar provedor!');

            // Reverter seleção
            const currentProvider = provider === 'betby' ? 'digitain' : 'betby';
            document.getElementById(currentProvider + '_provider').checked = true;
        }
    })
    .catch(error => {
        window.isUpdatingApi = false;
        console.error('Erro:', error);
        ToastManager.error('Erro ao atualizar provedor: ' + error.message);

        // Reverter seleção
        const currentProvider = provider === 'betby' ? 'digitain' : 'betby';
        document.getElementById(currentProvider + '_provider').checked = true;
    });
}

// Função para atualizar campos da API de jogos
function AttApi(nome, field, value) {
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
    if (window.isUpdatingApi) {
        ToastManager.info('Outra atualização está em andamento. Aguarde...');
        return;
    }

    window.isUpdatingApi = true;

    // Mostrar indicador de carregamento
    const loadingToast = ToastManager.info('Salvando alterações...', 0);

    // Enviar solicitação para atualizar a API
    fetch('/admin/config/atualizar-api', {
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
                throw new Error('API não encontrada.');
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
        window.isUpdatingApi = false;

        if (data && data.success) {
            // Mostrar mensagem de sucesso
            ToastManager.success(data.message || 'Informação atualizada com sucesso!');

            // Limpar o cache após atualização bem-sucedida
            fetch('/admin/clear-cache', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(cacheResponse => {
                // Verificar se a resposta é JSON válida
                const contentType = cacheResponse.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Resposta não é JSON válida. Tipo de conteúdo: ' + contentType);
                }
                return cacheResponse.json();
            })
            .then(cacheData => {
                if (cacheData && cacheData.success) {
                    ToastManager.success('Cache limpo com sucesso');
                } else {
                    console.error('Erro ao limpar cache:', cacheData.message);
                }
            })
            .catch(cacheError => {
                console.error('Erro ao limpar cache:', cacheError);
                ToastManager.warning('Cache pode não ter sido limpo completamente. Verifique manualmente se necessário.');
            });
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
        window.isUpdatingApi = false;
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
    // Verificar se os listeners já foram anexados para evitar duplicação
    if (document.body.getAttribute('data-api-listeners-attached') === 'true') {
        return;
    }
    document.body.setAttribute('data-api-listeners-attached', 'true');

    // Inicializar flag global
    window.isUpdatingApi = false;

    // Selecionar todos os campos de texto de entrada
    const textInputs = document.querySelectorAll('input[type="text"], input[type="password"]');

    // Adicionar evento de tecla para cada campo
    textInputs.forEach(input => {
        // Salvar o valor original para recuperação em caso de erro
        input.setAttribute('data-original-value', input.value);

        // Quando pressionar Enter no campo
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir o comportamento padrão do Enter
                this.blur(); // Remover foco do campo para acionar o evento onblur
            }
        });

        // Adicionar ou sobrescrever o evento onblur para todos os campos
        input.addEventListener('blur', function() {
            // Obter os parâmetros de API com base no ID do campo
            const params = getApiParams(this.id);

            // Se conseguimos determinar a API e o campo, atualizamos
            if (params) {
                AttApi(params.apiName, params.fieldName, this);
            }
        });
    });

    // Função auxiliar para obter os parâmetros da API com base no ID do campo
    function getApiParams(fieldId) {
        switch(fieldId) {
            case 'sportpartnername':
                return { apiName: 'Sports', fieldName: 'sportpartnername' };
            case 'sports_api_provider':
                return { apiName: 'Sports', fieldName: 'sports_api_provider' };
            default:
                return null;
        }
    }
});
</script>
@endpush
