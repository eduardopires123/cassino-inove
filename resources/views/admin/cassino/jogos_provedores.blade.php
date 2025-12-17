@extends('admin.layouts.app')
@section('content')

    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Cassino</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Provedores</li>
                    </ol>
                </nav>
            </div>

            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <!-- FILTROS -->
                        <div class="row p-3">
                            <div class="col-md-3 mb-3">
                                <label for="filter-distribution" class="form-label">Distribuição</label>
                                <select id="filter-distribution" class="form-select filter-control">
                                    <option value="all">Todas</option>
                                    @foreach($availableDistributions as $distribution)
                                        <option value="{{ $distribution }}">{{ ucfirst($distribution) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="filter-status" class="form-label">Status</label>
                                <select id="filter-status" class="form-select filter-control">
                                    <option value="all">Todos</option>
                                    <option value="1">Ativos</option>
                                    <option value="0">Inativos</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="filter-provider" class="form-label">Provedor</label>
                                <select id="filter-provider" class="form-select filter-control">
                                    <option value="all">Todos</option>
                                    @foreach($ProvidersName as $provider)
                                        <option value="{{ $provider->name }}" data-distribution="{{ $provider->distribution }}" data-active="{{ $provider->active }}" data-wallets="{{ $provider->wallets ?? '' }}">{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3" id="filter-wallet-container" style="display: none;">
                                <label for="filter-wallet" class="form-label">Carteira</label>
                                <select id="filter-wallet" class="form-select filter-control">
                                    <option value="all">Todas</option>
                                    @foreach($availableWallets as $wallet)
                                        <option value="{{ $wallet }}">{{ $wallet }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3" id="filter-original-container" style="display: none;">
                                <label for="filter-original" class="form-label">Tipo</label>
                                <select id="filter-original" class="form-select filter-control">
                                    <option value="all">Todos</option>
                                    <option value="original">Oficial</option>
                                    <option value="clone">Clone</option>
                                </select>
                            </div>
                        </div>
                        <!-- /FILTROS -->
                        <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                            <div class="table-responsive">
                                <table id="provedores-datatable" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="provedores-datatable" rowspan="1" colspan="1" aria-label="ID: activate to sort column ascending">ID</th>
                                        <th class="sorting" tabindex="0" aria-controls="provedores-datatable" rowspan="1" colspan="1" aria-label="Provedor: activate to sort column descending">Provedor</th>
                                        <th class="sorting" tabindex="0" aria-controls="provedores-datatable" rowspan="1" colspan="1" aria-label="Nome na Home: activate to sort column ascending">Nome na Home</th>
                                        <th class="sorting" tabindex="0" aria-controls="provedores-datatable" rowspan="1" colspan="1" aria-label="Exibir na Home: activate to sort column ascending">Exibir na Home</th>
                                        <th class="sorting" tabindex="0" aria-controls="provedores-datatable" rowspan="1" colspan="1" aria-label="Ordem de Exibição: activate to sort column ascending" style="text-align: left;">Ordem de Exibição</th>
                                        <th class="sorting" tabindex="0" aria-controls="provedores-datatable" rowspan="1" colspan="1" aria-label="Ativo: activate to sort column ascending">Ativo</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ProvidersName as $registro)
                                        <tr role="row" data-id="{{$registro->id}}"
                                            data-distribution="{{$registro->distribution}}"
                                            data-active="{{$registro->active}}"
                                            data-name="{{$registro->name}}"
                                            data-wallets="{{$registro->wallets ?? ''}}"
                                            data-is-original="{{ str_contains(strtoupper($registro->name), 'ORIGINAL') ? 'original' : 'clone' }}">
                                            <td class="sorting_1">
                                                <strong>{{$registro->id}}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <!-- Preview da imagem do provedor -->
                                                    <div class="provider-image-container me-3" style="position: relative;">
                                                        <div id="providerImagePreview{{$registro->id}}" class="provider-image-preview"
                                                            style="width: 50px;height: 50px;border-radius: 8px;overflow: hidden; background-color: #4361ee; display: flex;align-items: center;justify-content: center;border: 1px solid #6984ff;cursor: pointer;"
                                                            onclick="openProviderImageModal('{{$registro->id}}', '{{$registro->name}}', '{{ $registro->img ? asset($registro->img) : '' }}')">
                                                            @if($registro->img)
                                                                <img src="{{ asset($registro->img) }}?t={{ time() }}"
                                                                    alt="{{$registro->name}}"
                                                                    class="img-fluid"
                                                                    style="width: 100%; height: 100%; object-fit: contain;"
                                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                                <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center;">
                                                                    <i class="fa fa-image text-muted" style="font-size: 20px;"></i>
                                                                </div>
                                                            @else
                                                                <i class="fa fa-image text-muted" style="font-size: 20px;color: #fff!important;"></i>
                                                            @endif
                                                        </div>

                                                        <!-- Botão para trocar imagem -->
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger provider-image-btn"
                                                            title="Trocar imagem do provedor"
                                                            onclick="document.getElementById('providerImageInput{{$registro->id}}').click();"
                                                            style="position: absolute; bottom: -5px; right: -5px; width: 20px; height: 20px; border-radius: 50%; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                            <i class="fa fa-camera" style="font-size: 10px;"></i>
                                                        </button>

                                                        <!-- Input file oculto -->
                                                        <input id="providerImageInput{{$registro->id}}"
                                                            type="file"
                                                            style="display: none;"
                                                            accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/avif"
                                                            data-provider-id="{{$registro->id}}"
                                                            data-provider-name="{{$registro->name}}"
                                                            class="provider-image-input">
                                                    </div>

                                                    <div>
                                                        <p class="align-self-center mb-0 admin-name">{{$registro->name}}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control editable-field"
                                                       data-provider-id="{{$registro->id}}"
                                                       data-field="name_home"
                                                       data-original-value="{{$registro->name_home ?? ''}}"
                                                       value="{{$registro->name_home ?? $registro->name}}"
                                                       placeholder="Nome na home"
                                                       onblur="saveEditableField(this)"
                                                       onkeypress="handleEditableFieldKeypress(event, this)">
                                            </td>
                                                                        <td>
                                <div class="form-check form-switch form-check-inline form-switch-primary">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchShowMain{{$registro->id}}" {{($registro->showmain == 1) ? 'checked=""' : ''}} onclick="AttProvider('{{$registro->id}}', 'showmain', Number(this.checked));">
                                </div>
                            </td>
                                            <td>
                                                <input id="OrderMain{{$registro->id}}" type="number" min="1" max="100" name="OrderMain{{$registro->id}}" placeholder="Ordem de exibição" class="form-control" required="" value="{{$registro->order_value}}" onchange="AttProvider('{{$registro->id}}', 'order_value', this.value);">
                                            </td>
                                                                        <td>
                                <div class="form-check form-switch form-check-inline form-switch-primary">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchActive{{$registro->id}}" {{($registro->active == 1) ? 'checked=""' : ''}} onclick="AttProvider('{{$registro->id}}', 'active', Number(this.checked));">
                                </div>
                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">ID</th>
                                        <th rowspan="1" colspan="1">Provedor</th>
                                        <th rowspan="1" colspan="1">Nome na Home</th>
                                        <th rowspan="1" colspan="1">Exibir na Home</th>
                                        <th rowspan="1" colspan="1" style="text-align: left;">Ordem de Exibição</th>
                                        <th rowspan="1" colspan="1">Ativo</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        body.dark .form-check-input:checked{
            background-color: #4361ee;
            border-color: #4361ee;
        }
        div.dataTables_wrapper div.dataTables_filter label {
            color: #647193!important;
        }
        
        /* Estilos para o preview da imagem do provedor */
        .provider-image-container {
            position: relative;
        }
        
        .provider-image-preview {
            transition: all 0.3s ease;
        }
        
        .provider-image-preview:hover {
            transform: scale(1.05);
        }
        
        .provider-image-btn {
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .provider-image-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .provider-image-preview img {
            transition: all 0.3s ease;
        }
        
        .provider-image-container:hover .provider-image-preview img {
            opacity: 0.8;
        }
        
        /* Estilos para os filtros */
        .filter-control {
            border-radius: 6px;
            border: 1px solid #e0e6ed;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }
        
        .filter-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 5px rgba(67, 97, 238, 0.3);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 6px;
        }
        
        /* Estilo para o contador de resultados */
        #provedores-datatable_info {
            margin-top: 15px;
            font-weight: 500;
        }
        
        /* Estilo para destacar filtros ativos */
        .filter-control.active {
            border-color: #4361ee;
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        /* Responsividade para filtros em telas menores */
        @media (max-width: 767px) {
            .filter-row {
                flex-direction: column;
            }

            .filter-control {
                margin-bottom: 10px;
            }
        }

        /* Estilos para campos editáveis */
        .editable-field {
            transition: all 0.3s ease;
        }

        .editable-field:hover {
            background-color: rgba(67, 97, 238, 0.05);
            border-color: #4361ee !important;
        }

        .editable-field:focus {
            background-color: rgba(67, 97, 238, 0.1);
            border-color: #4361ee !important;
            box-shadow: 0 0 5px rgba(67, 97, 238, 0.3);
            outline: none;
        }

        .editable-field.saving {
            background-color: rgba(255, 193, 7, 0.1);
            border-color: #ffc107 !important;
        }

        .editable-field.success {
            background-color: rgba(25, 135, 84, 0.1);
            border-color: #198754 !important;
        }

        .editable-field.error {
            background-color: rgba(220, 53, 69, 0.1);
            border-color: #dc3545 !important;
        }
    </style>

<!-- Modal para visualização da imagem do provedor -->
<div class="modal fade" id="providerImageModal" tabindex="-1" role="dialog" aria-labelledby="providerImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="providerImageModalLabel">Imagem do Provedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="providerImageContent">
                    <!-- Conteúdo será inserido dinamicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light-dark" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
    @push('scripts')
        <script>
            // Verificar se dependências estão carregadas
            function checkDependencies() {
                return (typeof $ !== 'undefined' && 
                        typeof $.fn.DataTable !== 'undefined' && 
                        typeof ModalManager !== 'undefined' && 
                        typeof ToastManager !== 'undefined');
            }


            // Variável para controlar toasts de processamento ativos
            let activeProcessingToasts = [];

            // Variáveis para controlar o upload de imagem do provedor
            let currentProviderImageData = {
                providerId: null,
                providerName: null,
                file: null,
                fileInput: null
            };

            // Variável para controlar o debounce do modal de upload de imagem
            let uploadImageModalTimeout = null;
            let isUploadImageModalOpen = false;

            // Função para limpar toasts de processamento ativos
            function clearActiveProcessingToasts() {
                activeProcessingToasts.forEach(toast => {
                    try {
                        if (toast && typeof toast.remove === 'function') {
                            toast.remove();
                        }
                    } catch (e) {
                        // Erro silencioso ao remover toast
                    }
                });
                activeProcessingToasts = [];
            }

            // Função para criar toast de processamento controlado
            function createProcessingToast(message) {
                // Limpar toasts de processamento anteriores
                clearActiveProcessingToasts();
                
                const toast = ToastManager.info(message);
                activeProcessingToasts.push(toast);
                return toast;
            }


            // Função para atualizar outros campos do provedor
            function AttProvider(id, field, value) {

                // Se estiver ativando o provedor, mostrar confirmação
                if (field === 'active' && value === 1) {
                    ModalManager.showConfirmation(
                        'Confirmar Ativação',
                        'Ao ativar este provedor, todos os jogos associados a ele e seus respectivos slugs serão ativados. Deseja continuar?',
                        function() {
                            // Callback de confirmação - Prosseguir com a ativação
                            processProviderUpdate(id, field, value);
                        },
                        function() {
                            // Callback de cancelamento - Reverter o toggle de ativação
                            const checkbox = document.querySelector(`tr[data-id="${id}"] input[type="checkbox"][onclick*="AttProvider"][onclick*="active"]`);
                            if (checkbox) {
                                checkbox.checked = false;
                            }
                        }
                    );
                } else if (field === 'active' && value === 0) {
                    // Se estiver desativando o provedor, mostrar confirmação
                    ModalManager.showConfirmation(
                        'Confirmar Desativação',
                        'Ao desativar este provedor, todos os jogos associados a ele e seus respectivos slugs serão desativados. Deseja continuar?',
                        function() {
                            // Callback de confirmação - Prosseguir com a desativação
                            processProviderUpdate(id, field, value);
                        },
                        function() {
                            // Callback de cancelamento - Reverter o toggle de desativação
                            const checkbox = document.querySelector(`tr[data-id="${id}"] input[type="checkbox"][onclick*="AttProvider"][onclick*="active"]`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        }
                    );
                } else {
                    // Para outros campos, atualizar diretamente
                    processProviderUpdate(id, field, value);
                }
            }


            // Função para processar a atualização do provedor
            function processProviderUpdate(id, field, value) {
                // Mostrar toast de processamento para ações que podem levar tempo
                let processingToast;
                if (field === 'active' && (value === 1 || value === 0)) {
                    processingToast = createProcessingToast('Processando, aguarde...');
                }

                $.ajax({
                    url: '/admin/cassino/atualizar-provider',
                    type: 'POST',
                    data: {
                        id: id,
                        field: field,
                        value: value,
                        activate_games: (field === 'active' && value === 1) ? 1 : 0, // Enviar flag para ativar jogos
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Limpar toasts de processamento se existir
                        if (processingToast) {
                            clearActiveProcessingToasts();
                        }

                        if (response.success) {
                            // Usar a mensagem da resposta, se disponível, ou usar uma mensagem padrão
                            const message = response.message || 'Provedor atualizado com sucesso!';
                            ToastManager.success(message);

                            // Se foi uma ativação e jogos foram ativados, mostrar informação adicional (se não já estiver na mensagem)
                            if (field === 'active' && value === 1 && response.gamesActivated && response.gamesActivated > 0 && !message.includes('jogos foram ativados')) {
                                ToastManager.info(`${response.gamesActivated} jogos foram ativados e marcados como originais.`);
                            }

                            // Se foi uma desativação e jogos foram desativados, mostrar informação adicional
                            if (field === 'active' && value === 0 && response.gamesDeactivated) {
                                ToastManager.info(`${response.gamesDeactivated} jogos e ${response.slugsDeactivated || 0} slugs foram desativados com sucesso.`);
                            }
                        } else {
                            ToastManager.error('Erro ao atualizar o provedor.');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Limpar toasts de processamento se existir
                        if (processingToast) {
                            clearActiveProcessingToasts();
                        }

                        ToastManager.error('Ocorreu um erro ao atualizar o provedor. Por favor, tente novamente.');
                    }
                });
            }

            // Função para processar upload de imagem do provedor
            function processProviderImageUpload(providerId, providerName, file, fileInput) {
                // Verificar se já existe um modal de upload aberto
                if (isUploadImageModalOpen) {
                    return;
                }
                
                // Limpar timeout anterior se existir
                if (uploadImageModalTimeout) {
                    clearTimeout(uploadImageModalTimeout);
                }
                
                // Validação de tipos de arquivo permitidos
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];
                if (!allowedTypes.includes(file.type)) {
                    ToastManager.error('Tipo de arquivo não permitido. Use apenas imagens JPG, PNG, GIF, WEBP ou AVIF.');
                    fileInput.value = '';
                    return;
                }
                
                // Validação de tamanho do arquivo (máximo 5MB)
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    ToastManager.error('Arquivo muito grande. O tamanho máximo permitido é 5MB.');
                    fileInput.value = '';
                    return;
                }
                
                // Armazenar dados do provedor atual
                currentProviderImageData = {
                    providerId: providerId,
                    providerName: providerName,
                    file: file,
                    fileInput: fileInput
                };
                
                // Definir timeout para evitar múltiplas chamadas
                uploadImageModalTimeout = setTimeout(() => {
                    // Marcar que o modal está aberto
                    isUploadImageModalOpen = true;
                    
                    // Mostrar modal de confirmação
                    ModalManager.showConfirmation(
                        'Confirmar Upload',
                        `Deseja realmente fazer upload desta imagem para o provedor "${providerName}"?`,
                        function() {
                            // Callback de confirmação
                            executeProviderImageUpload();
                            // Resetar variável de controle
                            isUploadImageModalOpen = false;
                        },
                        function() {
                            // Callback de cancelamento
                            fileInput.value = '';
                            // Resetar variável de controle
                            isUploadImageModalOpen = false;
                        }
                    );
                }, 100); // Debounce de 100ms
            }

            // Função para executar o upload da imagem do provedor após confirmação
            function executeProviderImageUpload() {
                // Extrair dados do objeto temporário
                const { providerId, providerName, file, fileInput } = currentProviderImageData;
                
                // Remover barra de progresso - não é mais necessária
                
                // Mostrar toast de "processando"
                const processingToast = createProcessingToast('Enviando imagem do provedor, aguarde...');
                
                // Criar FormData
                const formData = new FormData();
                formData.append('image', file);
                formData.append('provider_id', providerId);
                formData.append('provider_name', providerName);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                // Fazer o upload via AJAX
                fetch('/admin/cassino/update-provider-image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 500) {
                            throw new Error('Erro interno do servidor (500). Verifique se o diretório de upload existe e tem permissões adequadas.');
                        }
                        return response.text().then(text => {
                            throw new Error(`Erro ${response.status}: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Atualizar a imagem exibida
                        const imagePreview = document.getElementById(`providerImagePreview${providerId}`);
                        
                        // Usar a URL correta do banco de dados
                        const imageUrl = data.image_url || data.image_path;
                        
                        // Criar um timestamp para evitar cache da imagem
                        const timestamp = new Date().getTime();
                        
                        // Atualizar o conteúdo do preview mantendo a funcionalidade de clique
                        imagePreview.innerHTML = `
                            <img src="${imageUrl}?t=${timestamp}" 
                                alt="${providerName}" 
                                class="img-fluid" 
                                style="width: 100%; height: 100%; object-fit: contain;"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center;">
                                <i class="fa fa-image text-muted" style="font-size: 20px;"></i>
                            </div>
                        `;
                        
                        // Atualizar o onclick do preview para usar a nova imagem
                        imagePreview.setAttribute('onclick', `openProviderImageModal('${providerId}', '${providerName}', '${imageUrl}')`);
                        
                        // Mostrar mensagem de sucesso com informações sobre a conversão
                        let successMessage = 'Imagem do provedor enviada com sucesso!';
                        if (data.provider && data.provider.img && data.provider.img.endsWith('.webp')) {
                            successMessage += ' (Convertida para WebP com transparência)';
                        }
                        ToastManager.success(successMessage);
                        
                        // Recarregar a imagem do banco de dados após um pequeno delay
                        setTimeout(() => {
                            refreshProviderImageFromDatabase(providerId, providerName);
                        }, 500);
                    } else {
                        ToastManager.error('Erro ao enviar imagem: ' + (data.message || 'Erro desconhecido'));
                    }
                })
                .catch(error => {
                    ToastManager.error('Erro: ' + error.message);
                })
                .finally(() => {
                    // Limpar toasts de processamento
                    clearActiveProcessingToasts();
                    
                    // Resetar variáveis de controle
                    isUploadImageModalOpen = false;
                    
                    // Limpar o input file
                    fileInput.value = '';
                });
            }

            // Função para inicializar os inputs de upload de imagem do provedor
            function initializeProviderImageInputs() {
                document.querySelectorAll('.provider-image-input').forEach(input => {
                    // Verificar se já tem event listener para evitar duplicação
                    if (!input.hasAttribute('data-listener-added')) {
                        input.addEventListener('change', function(e) {
                            // Verificar se o modal já está aberto
                            if (isUploadImageModalOpen) {
                                return;
                            }
                            
                            const file = this.files[0];
                            if (!file) {
                                return;
                            }
                            
                            const providerId = this.getAttribute('data-provider-id');
                            const providerName = this.getAttribute('data-provider-name');
                            
                            processProviderImageUpload(providerId, providerName, file, this);
                        });
                        
                        input.setAttribute('data-listener-added', 'true');
                    }
                });
            }

            // Função para inicializar os toggles
            function initializeToggles() {
                // Apenas inicializar inputs que ainda não foram inicializados
                document.querySelectorAll('input[type="checkbox"]:not([data-initialized])').forEach(function(input) {
                    input.setAttribute('data-initialized', 'true');
                });
            }

            // Função para recarregar a imagem do provedor do banco de dados
            function refreshProviderImageFromDatabase(providerId, providerName) {
                
                // Fazer requisição para buscar dados atualizados do provedor
                fetch('/admin/cassino/get-provider-image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        provider_id: providerId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.provider && data.provider.img) {
                        const imagePreview = document.getElementById(`providerImagePreview${providerId}`);
                        const imageUrl = data.provider.img_url;
                        const timestamp = new Date().getTime();
                        
                        // Atualizar o preview com a URL do banco de dados
                        imagePreview.innerHTML = `
                            <img src="${imageUrl}?t=${timestamp}" 
                                alt="${providerName}" 
                                class="img-fluid" 
                                style="width: 100%; height: 100%; object-fit: contain;"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center;">
                                <i class="fa fa-image text-muted" style="font-size: 20px;"></i>
                            </div>
                        `;
                        
                        // Atualizar o onclick do preview
                        imagePreview.setAttribute('onclick', `openProviderImageModal('${providerId}', '${providerName}', '${imageUrl}')`);
                    }
                })
                .catch(error => {
                    // Erro silencioso
                });
            }

            // Função para abrir o modal de visualização da imagem do provedor
            function openProviderImageModal(providerId, providerName, imageUrl) {
                // Verificar se a URL da imagem está vazia
                if (!imageUrl || imageUrl.trim() === '') {
                    ToastManager.error('Este provedor não possui imagem para visualizar.');
                    return;
                }
                
                const modalContent = document.getElementById('providerImageContent');
                const modalLabel = document.getElementById('providerImageModalLabel');
                
                // Atualizar o título do modal
                modalLabel.textContent = `Imagem do Provedor: ${providerName}`;
                
                // Mostrar indicador de carregamento
                modalContent.innerHTML = `
                    <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                    </div>
                `;
                
                // Abrir o modal
                const providerImageModal = new bootstrap.Modal(document.getElementById('providerImageModal'));
                providerImageModal.show();
                
                // Criar elemento de imagem para verificar se carrega
                const img = new Image();
                
                img.onload = function() {
                    // Imagem carregada com sucesso
                    modalContent.innerHTML = `
                        <img src="${imageUrl}?t=${new Date().getTime()}" 
                            alt="Imagem do Provedor ${providerName}" 
                            class="img-fluid" 
                            style="max-height: 70vh; max-width: 100%; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    `;
                };
                
                img.onerror = function() {
                    // Erro ao carregar a imagem
                    modalContent.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            <strong>Erro:</strong> Não foi possível carregar a imagem.<br>
                            <small>URL: ${imageUrl}</small>
                        </div>
                    `;
                };
                
                // Tentar carregar a imagem
                img.src = imageUrl + '?t=' + new Date().getTime();
            }

            // Inicialização dos elementos quando o documento estiver pronto
            $(document).ready(function() {
                // Verificar dependências
                if (!checkDependencies()) {
                    return;
                }

                // Inicializar ModalManager
                try {
                    if (typeof ModalManager !== 'undefined' && typeof ModalManager.init === 'function') {
                        ModalManager.init();
                    }
                } catch (error) {
                    // Erro silencioso
                }

                // Inicializar os inputs de imagem
                try {
                    initializeProviderImageInputs();
                } catch (error) {
                    // Erro silencioso
                }

                // Toggles já são inicializados automaticamente pelo HTML

                // Inicializar o DataTable com callback para reinicializar os toggles após filtragem/paginação
                try {
                    // Verificar se a tabela existe antes de tentar inicializar o DataTable
                    if (!document.getElementById('provedores-datatable')) {
                        initializeToggles();
                        return;
                    }

                    dataTableInstance = $('#provedores-datatable').DataTable({
                        processing: false,
                        serverSide: false, // Usando DOM como fonte, não servidor
                        responsive: true,
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
                            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Processando...</span></div>',
                            paginate: {
                                first: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>',
                                previous: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                                next: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                                last: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>'
                            },
                            info: "Mostrando página _PAGE_ de _PAGES_",
                            search: "Buscar:",
                            lengthMenu: "Exibir _MENU_ registros por página",
                            emptyTable: "Nenhum registro encontrado",
                            zeroRecords: "Nenhum registro encontrado",
                            infoEmpty: "Mostrando 0 a 0 de 0 registros",
                            infoFiltered: "(filtrado de _MAX_ registros no total)"
                        },
                        order: [[0, 'asc']], // Ordenar pela primeira coluna
                        dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                            "<'table-responsive'tr>" +
                            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
                        lengthMenu: [10, 25, 50, 100],
                        pageLength: 20,
                        pagingType: 'full_numbers',
                        drawCallback: function(settings) {
                            // Adicionar classes ao paginador
                            $('#provedores-datatable_paginate').addClass('paging_simple_numbers');
                            $('#provedores-datatable_paginate ul.pagination li').addClass('paginate_button page-item');
                            $('#provedores-datatable_paginate ul.pagination li.previous').attr('id', 'provedores-datatable_previous');
                            $('#provedores-datatable_paginate ul.pagination li.next').attr('id', 'provedores-datatable_next');
                            $('#provedores-datatable_paginate ul.pagination li.first').attr('id', 'provedores-datatable_first');
                            $('#provedores-datatable_paginate ul.pagination li.last').attr('id', 'provedores-datatable_last');
                            $('#provedores-datatable_paginate ul.pagination li a').addClass('page-link');

                            // Substituir o texto dos botões de paginação por ícones SVG
                            $('#provedores-datatable_paginate ul.pagination li.first a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>');
                            $('#provedores-datatable_paginate ul.pagination li.previous a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                            $('#provedores-datatable_paginate ul.pagination li.next a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
                            $('#provedores-datatable_paginate ul.pagination li.last a').html('<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-right"><polyline points="13 17 18 12 13 7"></polyline><polyline points="6 17 11 12 6 7"></polyline></svg>');

                            // Apenas inicializar inputs para novos elementos (evitar duplicação)
                            setTimeout(() => {
                                initializeProviderImageInputs(); // Adicionar inicialização dos inputs de imagem
                                
                                // Atualizar filtros condicionais após desenhar a tabela
                                if (typeof updateCascadeFilters === 'function') {
                                    updateCascadeFilters();
                                }
                            }, 100);
                        }
                    });

                    // Inicializar filtros após criar o DataTable
                    initializeFilters();

                } catch (error) {
                    // Fallback: apenas continuar
                }
            });
            
            // Variável para armazenar a instância do DataTable
            let dataTableInstance = null;

            // Função para aplicar filtros na tabela de provedores usando DataTable
            function applyFilters() {
                if (!dataTableInstance) return;

                const distributionFilter = $('#filter-distribution').val();
                const providerFilter = $('#filter-provider').val();
                const statusFilter = $('#filter-status').val();
                const walletFilter = $('#filter-wallet').val();
                const originalFilter = $('#filter-original').val();

                // Atualizar filtros em cascata
                updateCascadeFilters();

                // Limpar filtros anteriores
                dataTableInstance.search('').columns().search('');

                // Remover filtros customizados anteriores
                clearCustomFilters();

                // Função de filtro customizada
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        if (settings.nTable.id !== 'provedores-datatable') {
                            return true;
                        }

                        const $row = $(dataTableInstance.row(dataIndex).node());
                        const rowDistribution = $row.data('distribution');
                        const rowName = $row.data('name');
                        const rowActive = $row.data('active').toString();
                        const rowWallets = $row.data('wallets') ? $row.data('wallets').toLowerCase() : '';
                        const rowIsOriginal = $row.data('is-original');

                        // Verificar filtro de distribuição
                        const matchesDistribution = distributionFilter === 'all' || rowDistribution === distributionFilter;

                        // Verificar filtro de provedor
                        const matchesProvider = providerFilter === 'all' || rowName === providerFilter;

                        // Verificar filtro de status
                        const matchesStatus = statusFilter === 'all' || rowActive === statusFilter;

                        // Verificar filtro de carteira
                        let matchesWallet = true;
                        if (walletFilter !== 'all' && $('#filter-wallet-container').is(':visible')) {
                            // Buscar o provedor no backend para verificar a wallet
                            const providerData = @json($ProvidersName->keyBy('name'));
                            const provider = providerData[rowName];
                            
                            if (provider && provider.wallets) {
                                try {
                                    let walletsArray;
                                    const walletsStr = provider.wallets.toString();
                                    
                                    // Tentar parse como JSON
                                    if (walletsStr.startsWith('[')) {
                                        walletsArray = JSON.parse(walletsStr);
                                    } else if (walletsStr.includes(',')) {
                                        // Fallback: split por vírgula
                                        walletsArray = walletsStr.split(',').map(w => w.trim());
                                    } else {
                                        // Valor único
                                        walletsArray = [walletsStr.trim()];
                                    }
                                    
                                    // Verificar se a wallet filtrada está no array
                                    matchesWallet = walletsArray.some(w => w.toLowerCase() === walletFilter.toLowerCase());
                                } catch (e) {
                                    matchesWallet = false;
                                }
                            } else {
                                matchesWallet = false;
                            }
                        }

                        // Verificar filtro de oficial/clone
                        let matchesOriginal = true;
                        if (originalFilter !== 'all' && $('#filter-original-container').is(':visible')) {
                            matchesOriginal = rowIsOriginal === originalFilter;
                        }

                        return matchesDistribution && matchesProvider && matchesStatus && matchesWallet && matchesOriginal;
                    }
                );

                // Redesenhar a tabela com os filtros aplicados
                dataTableInstance.draw();
            }

            // Função para atualizar filtros em cascata
            function updateCascadeFilters() {
                const distributionFilter = $('#filter-distribution').val();
                const providerFilter = $('#filter-provider').val();
                const statusFilter = $('#filter-status').val();

                // Atualizar select de provedores baseado na distribuição
                updateProviderOptions(distributionFilter);

                // Atualizar visibilidade do filtro de wallets
                updateWalletFilterVisibility(distributionFilter, providerFilter, statusFilter);

                // Atualizar visibilidade do filtro oficial/clone
                updateOriginalFilterVisibility(distributionFilter);
            }

            // Função para atualizar opções do select de provedores
            function updateProviderOptions(distributionFilter) {
                const providerSelect = $('#filter-provider');
                const currentValue = providerSelect.val();
                let validOptionFound = false;

                // Mostrar/esconder opções baseado na distribuição
                providerSelect.find('option').each(function() {
                    const $option = $(this);
                    const optionDistribution = $option.data('distribution');

                    if ($option.val() === 'all') {
                        $option.show();
                        if (currentValue === 'all') validOptionFound = true;
                    } else if (distributionFilter === 'all' || optionDistribution === distributionFilter) {
                        $option.show();
                        if ($option.val() === currentValue) validOptionFound = true;
                    } else {
                        $option.hide();
                    }
                });

                // Se a opção atual não é válida, resetar para "Todos"
                if (!validOptionFound) {
                    providerSelect.val('all');
                }
            }

            // Função para atualizar visibilidade do filtro de wallets
            function updateWalletFilterVisibility(distributionFilter, providerFilter, statusFilter) {
                const walletContainer = $('#filter-wallet-container');
                const walletSelect = $('#filter-wallet');
                const currentWallet = walletSelect.val();
                
                // SEMPRE mostrar as carteiras quando a distribuição for PlayFiver
                if (distributionFilter === 'PlayFiver') {
                    // Usar as wallets do backend que foram carregadas na página
                    const backendWallets = @json($availableWallets ?? []);
                    
                    if (backendWallets.length > 0) {
                        // Atualizar opções do select com as wallets do backend
                        walletSelect.empty();
                        walletSelect.append('<option value="all">Todas</option>');
                        
                        backendWallets.forEach(wallet => {
                            walletSelect.append(`<option value="${wallet}">${wallet}</option>`);
                        });
                        
                        // Manter valor selecionado se ainda estiver disponível
                        if (currentWallet !== 'all' && backendWallets.includes(currentWallet)) {
                            walletSelect.val(currentWallet);
                        } else {
                            walletSelect.val('all');
                        }
                        
                        walletContainer.show();
                    } else {
                        walletContainer.hide();
                    }
                } else {
                    // Para outras distribuições, esconder o filtro
                    walletContainer.hide();
                    walletSelect.val('all');
                }
            }

            // Função para atualizar visibilidade do filtro oficial/clone
            function updateOriginalFilterVisibility(distributionFilter) {
                const originalContainer = $('#filter-original-container');
                const originalSelect = $('#filter-original');

                // Mostrar filtro oficial/clone apenas quando distribuição for TBS
                if (distributionFilter === 'TBS') {
                    originalContainer.show();
                } else {
                    originalContainer.hide();
                    originalSelect.val('all');
                }
            }

            // Função para limpar filtros customizados
            function clearCustomFilters() {
                // Remover todos os filtros customizados relacionados a esta tabela
                const originalLength = $.fn.dataTable.ext.search.length;
                $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(fn) {
                    // Manter apenas filtros que não são da nossa tabela
                    try {
                        // Testar o filtro com dados dummy para ver se é nosso
                        const testResult = fn({nTable: {id: 'provedores-datatable'}}, [], 0);
                        return false; // Se chegou aqui, é nosso filtro, remover
                    } catch(e) {
                        return true; // Se deu erro, não é nosso filtro, manter
                    }
                });
            }
            
            // Inicializar os filtros
            function initializeFilters() {
                // Event listeners específicos para cada filtro com lógica de limpeza automática
                $('#filter-distribution').off('change').on('change', function() {
                    const value = $(this).val();
                    
                    if (value === 'all') {
                        // Limpar filtros dependentes quando selecionar "Todas" as distribuições
                        $('#filter-provider').val('all');
                        $('#filter-wallet').val('all');
                        $('#filter-original').val('all');
                        $('#filter-wallet-container').hide();
                        $('#filter-original-container').hide();
                    }
                    
                    updateCascadeFilters();
                    applyFilters();
                    highlightActiveFilters();
                });

                $('#filter-provider').off('change').on('change', function() {
                    const value = $(this).val();
                    
                    if (value === 'all') {
                        // Limpar filtros dependentes quando selecionar "Todos" os provedores
                        $('#filter-wallet').val('all');
                    }
                    
                    updateCascadeFilters();
                    applyFilters();
                    highlightActiveFilters();
                });

                $('#filter-status').off('change').on('change', function() {
                    const value = $(this).val();
                    
                    if (value === 'all') {
                        // Quando limpar status, recalcular visibilidade de wallets
                        updateCascadeFilters();
                    }
                    
                    applyFilters();
                    highlightActiveFilters();
                });

                $('#filter-wallet').off('change').on('change', function() {
                    applyFilters();
                    highlightActiveFilters();
                });

                $('#filter-original').off('change').on('change', function() {
                    applyFilters();
                    highlightActiveFilters();
                });

                // Inicializar estado dos filtros condicionais
                updateCascadeFilters();
                
                // Inicializar o estado dos filtros visuais
                highlightActiveFilters();
            }
            
            // Função para destacar filtros ativos
            function highlightActiveFilters() {
                // Remover classe active de todos os filtros
                $('.filter-control').removeClass('active');

                // Adicionar classe active aos filtros que têm valores diferentes do padrão
                if ($('#filter-distribution').val() !== 'all') {
                    $('#filter-distribution').addClass('active');
                }

                if ($('#filter-provider').val() !== 'all') {
                    $('#filter-provider').addClass('active');
                }

                if ($('#filter-status').val() !== 'all') {
                    $('#filter-status').addClass('active');
                }

                if ($('#filter-wallet').val() !== 'all' && $('#filter-wallet-container').is(':visible')) {
                    $('#filter-wallet').addClass('active');
                }

                if ($('#filter-original').val() !== 'all' && $('#filter-original-container').is(':visible')) {
                    $('#filter-original').addClass('active');
                }
            }



            // Função para salvar campo editável
            function saveEditableField(element) {
                const $element = $(element);
                const providerId = $element.data('provider-id');
                const field = $element.data('field');
                const newValue = $element.val().trim();
                const originalValue = String($element.data('original-value') || '').trim();

                // Se o valor não mudou, não fazer nada
                if (newValue === originalValue) {
                    return;
                }

                // Validar se não está vazio
                if (newValue === '') {
                    ToastManager.error('O nome na home não pode estar vazio.');
                    $element.val(originalValue || $element.closest('tr').find('.admin-name').text());
                    return;
                }

                // Adicionar classe de salvando
                $element.addClass('saving').removeClass('success error');

                // Fazer a requisição AJAX
                $.ajax({
                    url: '/admin/cassino/atualizar-provider',
                    type: 'POST',
                    data: {
                        id: providerId,
                        field: field,
                        value: newValue,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Atualizar o valor original
                            $element.data('original-value', newValue);

                            // Mostrar feedback visual de sucesso
                            $element.removeClass('saving').addClass('success');

                            // Mostrar toast de sucesso
                            ToastManager.success('Nome na home atualizado com sucesso!');

                            // Remover classe de sucesso após um tempo
                            setTimeout(() => {
                                $element.removeClass('success');
                            }, 2000);
                        } else {
                            // Reverter o valor
                            $element.val(originalValue);
                            $element.removeClass('saving').addClass('error');

                            ToastManager.error('Erro ao atualizar o nome na home.');

                            setTimeout(() => {
                                $element.removeClass('error');
                            }, 3000);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Reverter o valor
                        $element.val(originalValue || $element.closest('tr').find('.admin-name').text());
                        $element.removeClass('saving').addClass('error');

                        if (xhr.status === 500) {
                            ToastManager.error('Erro interno do servidor. Verifique os logs.');
                        } else {
                            ToastManager.error('Erro ao salvar. Tente novamente.');
                        }

                        setTimeout(() => {
                            $element.removeClass('error');
                        }, 3000);
                    }
                });
            }

            // Função para lidar com tecla Enter no campo editável
            function handleEditableFieldKeypress(event, element) {
                if (event.which === 13) { // Enter
                    event.preventDefault();
                    element.blur(); // Dispara o onblur que salva
                }
            }
            
        </script>
    @endpush
@endsection
