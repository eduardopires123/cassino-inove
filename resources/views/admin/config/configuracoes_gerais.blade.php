@extends('admin.layouts.app')
@section('content')
    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Administração</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Configurações</li>
                    </ol>
                </nav>
            </div>

            <div class="row" style="margin-top: 20px;">
                <div id="flLoginForm" class="col-lg-12 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content widget-content-area">
                            <form method="POST" id="settings" name="settings" action="{{ route('admin.config.gerais.salvar') }}" style="padding:20px;" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Nome: <small style="color: darkorange;">Nome do cassino</small></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="" value="{{$Settings->getAttribute('name')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Subtitulo: <small style="color: darkorange;">Subtitulo do cassino</small></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="" value="{{$Settings->getAttribute('subname')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputPassword4" class="form-label">Logo</label>
                                            <div class="multiple-file-upload">
                                                <input type="file"
                                                       class="filepond file-upload-multiple"
                                                       id="logo-upload"
                                                       name="filepond"
                                                       data-allow-reorder="true"
                                                       data-max-file-size="2MB"
                                                       data-max-files="1">
                                            </div>
                                            <small style="color: darkorange;">Logotipo do cassino</small>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Logo Atual</label>
                                            <div class="favicon-preview mt-2" id="logo-preview-container">
                                                @if($Settings->logo)
                                                    <img src="{{ asset($Settings->logo) }}" alt="Logo atual" style="max-width: 160px;">
                                                @else
                                                    <span class="text-muted">Nenhuma logo definida</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="inputFavicon" class="form-label">Favicon</label>
                                            <div class="multiple-file-upload">
                                                <input type="file"
                                                       class="filepond file-upload-multiple"
                                                       id="favicon-upload"
                                                       name="filepond_favicon"
                                                       data-allow-reorder="true"
                                                       data-max-file-size="2MB"
                                                       data-max-files="1">
                                            </div>
                                            <small style="color: darkorange;">Ícone exibido na aba do navegador</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Favicon Atual</label>
                                            <div class="favicon-preview mt-2" id="favicon-preview-container">
                                                @if($Settings->favicon)
                                                    <img src="{{ asset($Settings->favicon) }}" alt="Favicon atual" style="max-width: 64px; max-height: 64px;">
                                                @else
                                                    <span class="text-muted">Nenhum favicon definido</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Mín Saque:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="min_saque_n" name="min_saque_n" placeholder="10" value="{{$Settings->getAttribute('min_saque_n')}}">
                                            </div>
                                            <small style="color: darkorange;">Saque mínimo</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Máx Saque: </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="max_saque_n" name="max_saque_n" placeholder="10" value="{{$Settings->getAttribute('max_saque_n')}}">
                                            </div>
                                            <small style="color: darkorange;">Saque máximo</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Máx Saque Automático:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="max_saque_aut" name="max_saque_aut" placeholder="10" value="{{$Settings->getAttribute('max_saque_aut')}}">
                                            </div>
                                            <small style="color: darkorange;">Saque acima do definido é feito manualmente pelo administrador</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Rollover Saque:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="rollover_saque" name="rollover_saque" placeholder="10" value="{{$Settings->getAttribute('rollover_saque')}}">
                                            </div>
                                            <small style="color: darkorange;">Percentual que o jogador deve jogar do valor depositado para conseguir sacar o restante</small>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Mín Dep:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="min_dep" name="min_dep" placeholder="10" value="{{$Settings->getAttribute('min_dep')}}">
                                            </div>
                                            <small style="color: darkorange;">Depósito mínimo</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Máx Dep:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="max_dep" name="max_dep" placeholder="10" value="{{$Settings->getAttribute('max_dep')}}">
                                            </div>
                                            <small style="color: darkorange;">Depósito máximo</small>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="bonus_all_deposits">Dar bônus em todos depósitos:</label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="bonus_all_deposits" name="bonus_all_deposits" value="1" {{ $Settings->getAttribute('bonus_all_deposits') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="bonus_all_deposits">{{ $Settings->getAttribute('bonus_all_deposits') ? 'Ativado' : 'Desativado' }}</label>
                                            </div>
                                            <small style="color: darkorange;">Ative e Desative bônus em todos depósitos (Padrão apenas no primeiro)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Mín Dep Bônus 1º Depósito:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="bonus_min_dep" name="bonus_min_dep" placeholder="10" value="{{$Settings->getAttribute('bonus_min_dep')}}">
                                            </div>
                                            <small style="color: darkorange;">Depósito mínimo para se enquadrar no bônus</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Máx Dep Bônus 1º Depósito:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="bonus_max_dep" name="bonus_max_dep" placeholder="10" value="{{$Settings->getAttribute('bonus_max_dep')}}">
                                            </div>
                                            <small style="color: darkorange;">Depósito máximo para se enquadrar no bônus</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Bônus 1º Depósito:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="bonus_mult" name="bonus_mult" placeholder="10" value="{{$Settings->getAttribute('bonus_mult')}}">
                                            </div>
                                            <small style="color: darkorange;">Percentual de bônus em cima do primeiro depósito</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Máx Saque Diário:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="max_saque_diario" name="max_saque_diario" placeholder="1000" value="{{$Settings->getAttribute('max_saque_diario')}}">
                                            </div>
                                            <small style="color: darkorange;">Limite máximo que o usuário pode sacar por dia</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Máx Quantidade Saques Diário:</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="max_quantidade_saques_diario" name="max_quantidade_saques_diario" placeholder="0" value="{{$Settings->getAttribute('max_quantidade_saques_diario') ?? 0}}" min="0">
                                            </div>
                                            <small style="color: darkorange;">Limite de quantidade de saques por dia por usuário (0 = ilimitado)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Máx Quantidade Saques Automáticos Diário:</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="max_quantidade_saques_automaticos_diario" name="max_quantidade_saques_automaticos_diario" placeholder="0" value="{{$Settings->getAttribute('max_quantidade_saques_automaticos_diario') ?? 0}}" min="0">
                                            </div>
                                            <small style="color: darkorange;">Limite de quantidade de saques automáticos por dia por usuário (0 = ilimitado)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Rollover de Bônus:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="bonus_rollover" name="bonus_rollover" placeholder="10" value="{{$Settings->getAttribute('bonus_rollover')}}">
                                            </div>
                                            <small style="color: darkorange;">Multiplicador em cima do bônus recebido que deve ser jogado para sacar o valor do bônus</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ccode">Expiração de Bônus:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="bonus_expire_days" name="bonus_expire_days" placeholder="10" value="{{$Settings->getAttribute('bonus_expire_days')}}">
                                            </div>
                                            <small style="color: darkorange;">Após receber o bônus o usuário tem X dias para atingir a meta</small>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="tawkto_src">Link do Chat Tawk.to:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="tawkto_src" name="tawkto_src" placeholder="https://embed.tawk.to/seu-codigo/seu-widget" value="{{$Settings->getAttribute('tawkto_src')}}">
                                            </div>
                                            <small style="color: darkorange;">Link do script do Tawk.to para chat de suporte - <a href="https://dashboard.tawk.to/signup" target="_blank" style="color: #4CAF50; font-size: 12px;"><i class="fa fa-external-link"></i> Crie sua conta clique aqui</a></small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="tawkto_active">Ativar Chat Tawk.to:</label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="tawkto_active" name="tawkto_active" value="1" {{ $Settings->getAttribute('tawkto_active') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tawkto_active">{{ $Settings->getAttribute('tawkto_active') ? 'Ativado' : 'Desativado' }}</label>
                                            </div>
                                            <small style="color: darkorange;">Ativar ou desativar o chat</small>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="jivochat_src">Código do JivoChat:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="jivochat_src" name="jivochat_src" placeholder="Digite o código do JivoChat" value="{{$Settings->getAttribute('jivochat_src')}}">
                                            </div>
                                            <small style="color: darkorange;"> Usar apenas o código dentro do src. Ex: //code.jivosite.com/script/widget/1234567890, usar apenas o 1234567890</small>
                                            <small style="color: darkorange;">Código do JivoChat para chat de suporte - <a href="https://www.jivochat.com/signup" target="_blank" style="color: #4CAF50; font-size: 12px;"><i class="fa fa-external-link"></i> Crie sua conta clique aqui</a></small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="jivochat_active">Ativar JivoChat:</label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="jivochat_active" name="jivochat_active" value="1" {{ $Settings->getAttribute('jivochat_active') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="jivochat_active">{{ $Settings->getAttribute('jivochat_active') ? 'Ativado' : 'Desativado' }}</label>
                                            </div>
                                            <small style="color: darkorange;">Ativar ou desativar o JivoChat</small>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="enable_cassino_bonus">Aposta com Bônus (Cassino):</label>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="enable_cassino_bonus" name="enable_cassino_bonus" value="1" {{ $Settings->getAttribute('enable_cassino_bonus') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="enable_cassino_bonus">{{ $Settings->getAttribute('enable_cassino_bonus') ? 'Ativado' : 'Desativado' }}</label>
                                            </div>
                                            <small style="color: darkorange;">Ative e Desative aposta com bônus</small>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="default_home_page">Página Inicial Padrão:</label>
                                            <div class="input-group">
                                                <select class="form-control" id="default_home_page" name="default_home_page">
                                                    <option value="cassino" {{ $Settings->getAttribute('default_home_page') === 'cassino' ? 'selected' : '' }}>Página de Cassino</option>
                                                    <option value="esportes" {{ $Settings->getAttribute('default_home_page') === 'esportes' ? 'selected' : '' }}>Página de Esportes</option>
                                                </select>
                                            </div>
                                            <small style="color: darkorange;">Escolha qual página será exibida quando o usuário acessar a raiz do site (/)</small>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('src/plugins/src/filepond/filepond.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/plugins/src/filepond/FilePondPluginImagePreview.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/plugins/css/light/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/plugins/css/dark/filepond/custom-filepond.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
    <script src="{{ asset('src/plugins/src/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('src/plugins/src/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
    <script src="{{ asset('src/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
    <script src="{{ asset('src/plugins/src/filepond/FilePondPluginImagePreview.min.js') }}"></script>
    <script src="{{ asset('src/plugins/src/filepond/FilePondPluginImageCrop.min.js') }}"></script>
    <script src="{{ asset('src/plugins/src/filepond/FilePondPluginImageResize.min.js') }}"></script>
    <script src="{{ asset('src/plugins/src/filepond/FilePondPluginImageTransform.min.js') }}"></script>
    <script src="{{ asset('src/plugins/src/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar se os listeners já foram anexados para evitar duplicação
            if (document.body.getAttribute('data-config-listeners-attached') === 'true') {
                return;
            }
            document.body.setAttribute('data-config-listeners-attached', 'true');

            // Registrar plugins do FilePond
            FilePond.registerPlugin(
                FilePondPluginFileValidateType,
                FilePondPluginImageExifOrientation,
                FilePondPluginImagePreview,
                FilePondPluginImageCrop,
                FilePondPluginImageResize,
                FilePondPluginImageTransform,
                FilePondPluginFileValidateSize
            );

            // Inicializar FilePond para o upload de logo
            const logoInput = document.querySelector('#logo-upload');
            const logoPond = FilePond.create(logoInput, {
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/gif', 'image/webp', 'image/svg+xml'],
                allowMultiple: false,
                maxFiles: 1,
                maxFileSize: '2MB',
                instantUpload: true,
                storeAsFile: false,
                allowImagePreview: true,
                imagePreviewHeight: 150,
                checkValidity: true,
                // Mensagens de erro personalizadas
                labelFileTypeNotAllowed: 'Tipo de arquivo inválido. Utilize apenas imagens (PNG, JPG, GIF, WEBP, SVG).',
                fileValidateTypeLabelExpectedTypes: 'Tipo de arquivo inválido. Utilize apenas imagens (PNG, JPG, GIF, WEBP, SVG).',
                labelMaxFileSizeExceeded: 'Arquivo muito grande!',
                labelMaxFileSize: 'O tamanho máximo permitido é {filesize}',
                // Manipulador de erros personalizado
                onwarning: (error) => {
                    if (error && error.body === 'Max file size exceeded') {
                        ToastManager.error('A imagem é muito grande. O tamanho máximo permitido é 2MB.');
                        return;
                    }

                    if (error && error.body && error.body.includes('is not of type')) {
                        ToastManager.error('Tipo de arquivo inválido. Utilize apenas imagens (PNG, JPG, GIF, WEBP, SVG).');
                        return;
                    }
                },
                server: {
                    process: {
                        url: '{{ route("admin.config.gerais.salvar") }}',
                        method: 'POST',
                        withCredentials: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        data: {
                            fileType: 'logo'
                        },
                        onload: (response) => {
                            try {
                                const data = JSON.parse(response);
                                if (data.success) {
                                    ToastManager.success('Logo atualizado com sucesso!');

                                    // Atualizar preview sem recarregar a página
                                    if (data.file_path) {
                                        const logoPreviewContainer = document.getElementById('logo-preview-container');
                                        if (logoPreviewContainer) {
                                            // Limpar conteúdo atual
                                            logoPreviewContainer.innerHTML = '';
                                            // Adicionar nova imagem
                                            const img = document.createElement('img');
                                            img.src = data.file_path;
                                            img.alt = 'Logo atual';
                                            img.style.maxWidth = '160px';
                                            logoPreviewContainer.appendChild(img);
                                        }
                                    }
                                } else {
                                    ToastManager.error(data.message || 'Erro ao atualizar logo.');
                                }
                            } catch (e) {
                                console.error('Erro ao processar resposta:', e);
                                ToastManager.error('Erro ao processar resposta do servidor.');
                            }
                            return response;
                        },
                        onerror: (response) => {
                            try {
                                const data = JSON.parse(response);
                                ToastManager.error(data.message || 'Erro ao atualizar logo.');
                            } catch (e) {
                                ToastManager.error('Erro ao atualizar logo.');
                            }
                            return response;
                        }
                    }
                }
            });

            // Inicializar FilePond para o upload de favicon
            const faviconInput = document.querySelector('#favicon-upload');
            const faviconPond = FilePond.create(faviconInput, {
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/ico', 'image/x-icon', 'image/vnd.microsoft.icon', 'image/gif', 'image/svg+xml'],
                allowMultiple: false,
                maxFiles: 1,
                maxFileSize: '2MB',
                instantUpload: true,
                storeAsFile: false,
                allowImagePreview: true,
                imagePreviewHeight: 64,
                checkValidity: true,
                // Definir nome do campo para "filepond_favicon"
                name: 'filepond_favicon',
                // Mensagens de erro personalizadas
                labelFileTypeNotAllowed: 'Tipo de arquivo inválido. Utilize apenas imagens (PNG, ICO, JPG, GIF, SVG).',
                fileValidateTypeLabelExpectedTypes: 'Tipo de arquivo inválido. Utilize apenas imagens (PNG, ICO, JPG, GIF, SVG).',
                labelMaxFileSizeExceeded: 'Arquivo muito grande!',
                labelMaxFileSize: 'O tamanho máximo permitido é {filesize}',
                // Manipulador de erros personalizado
                onwarning: (error) => {
                    if (error && error.body === 'Max file size exceeded') {
                        ToastManager.error('A imagem é muito grande. O tamanho máximo permitido é 2MB.');
                        return;
                    }

                    if (error && error.body && error.body.includes('is not of type')) {
                        ToastManager.error('Tipo de arquivo inválido. Utilize apenas imagens (PNG, ICO, JPG, GIF, SVG).');
                        return;
                    }
                },
                server: {
                    process: {
                        url: '{{ route("admin.config.gerais.salvar") }}',
                        method: 'POST',
                        withCredentials: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        data: {
                            fileType: 'favicon'
                        },
                        onload: (response) => {
                            try {
                                const data = JSON.parse(response);
                                if (data.success) {
                                    ToastManager.success('Favicon atualizado com sucesso!');

                                    // Atualizar preview sem recarregar a página
                                    if (data.file_path) {
                                        const faviconPreviewContainer = document.getElementById('favicon-preview-container');
                                        if (faviconPreviewContainer) {
                                            // Limpar conteúdo atual
                                            faviconPreviewContainer.innerHTML = '';
                                            // Adicionar nova imagem
                                            const img = document.createElement('img');
                                            img.src = data.file_path;
                                            img.alt = 'Favicon atual';
                                            img.style.maxWidth = '64px';
                                            img.style.maxHeight = '64px';
                                            faviconPreviewContainer.appendChild(img);
                                        }
                                    }
                                } else {
                                    ToastManager.error(data.message || 'Erro ao atualizar favicon.');
                                }
                            } catch (e) {
                                console.error('Erro ao processar resposta:', e);
                                ToastManager.error('Erro ao processar resposta do servidor.');
                            }
                            return response;
                        },
                        onerror: (response) => {
                            try {
                                const data = JSON.parse(response);
                                ToastManager.error(data.message || 'Erro ao atualizar favicon.');
                            } catch (e) {
                                ToastManager.error('Erro ao atualizar favicon.');
                            }
                            return response;
                        }
                    }
                }
            });

            // Lista de campos monetários para formatação especial
            const currencyFields = [];

            // Lista de campos numéricos simples
            const numericFields = ['min_saque_n', 'max_saque_n', 'max_saque_aut', 'max_saque_diario', 'min_dep', 'max_dep',
                'bonus_min_dep', 'bonus_max_dep', 'bonus_rollover', 'bonus_mult', 'bonus_expire_days', 'rollover_saque',
                'max_quantidade_saques_diario', 'max_quantidade_saques_automaticos_diario'];

            // Lista de campos de texto
            const textFields = ['name', 'subtitle', 'tawkto_src', 'jivochat_src', 'default_home_page'];

            // Lista de campos de checkbox
            const checkboxFields = ['tawkto_active', 'jivochat_active', 'enable_cassino_bonus', 'bonus_all_deposits'];

            // Token CSRF
            const csrfToken = document.querySelector('input[name="_token"]').value;
            const formAction = document.getElementById('settings').action;

            // Armazenar valores originais para comparação
            const originalValues = {};

            // Armazenar temporizadores para debounce
            const saveTimers = {};

            // Função de debounce - espera um tempo antes de executar para evitar múltiplas requisições
            function debounce(func, delay, fieldName) {
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(saveTimers[fieldName]);
                    saveTimers[fieldName] = setTimeout(() => {
                        func.apply(context, args);
                    }, delay);
                };
            }

            // Inicializar valores originais
            function initializeOriginalValues() {
                // Campos de texto e numéricos
                [...textFields, ...numericFields, ...currencyFields].forEach(field => {
                    const input = document.getElementById(field);
                    if (input) {
                        originalValues[field] = input.value;
                    }
                });

                // Campos de checkbox
                checkboxFields.forEach(field => {
                    const checkbox = document.getElementById(field);
                    if (checkbox) {
                        originalValues[field] = checkbox.checked ? 1 : 0;
                    }
                });
            }

            // Configurar campos monetários
            currencyFields.forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    // Salvar valor original não formatado
                    const rawValue = input.value.replace(/\./g, '').replace(',', '.');
                    originalValues[field] = rawValue;

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

                    // Salvar ao perder o foco com debounce
                    input.addEventListener('blur', debounce(function(e) {
                        let rawValue = e.target.value.replace(/\./g, '').replace(',', '.');
                        if (rawValue === '') {
                            rawValue = '0';
                        }

                        // Só salvar se o valor mudou
                        if (rawValue !== originalValues[field]) {
                            saveField(input.name, rawValue).then(() => {
                                originalValues[field] = rawValue;
                            });
                        }
                    }, 300, field));
                }
            });

            // Configurar campos numéricos
            numericFields.forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    // Garantir que o valor inicial não seja vazio
                    if (input.value === '' || input.value === null || input.value === undefined) {
                        input.value = '0';
                    }
                    originalValues[field] = input.value;

                    input.addEventListener('blur', debounce(function() {
                        // Garantir que valores vazios sejam convertidos para 0
                        let value = input.value.trim();
                        if (value === '' || value === null || value === undefined) {
                            value = '0';
                            input.value = '0';
                        }

                        // Converter para número e depois para string para normalizar
                        const numValue = parseInt(value) || 0;
                        const normalizedValue = numValue.toString();

                        // Só salvar se o valor mudou
                        if (normalizedValue !== originalValues[field]) {
                            saveField(input.name, normalizedValue).then(() => {
                                originalValues[field] = normalizedValue;
                            });
                        }
                    }, 300, field));
                }
            });

            // Configurar campos de texto
            textFields.forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    originalValues[field] = input.value;

                    // Para campos select, usar evento 'change' em vez de 'blur'
                    if (input.tagName === 'SELECT') {
                        input.addEventListener('change', debounce(function() {
                            // Só salvar se o valor mudou
                            if (input.value !== originalValues[field]) {
                                saveField(input.name, input.value).then(() => {
                                    originalValues[field] = input.value;
                                });
                            }
                        }, 300, field));
                    } else {
                        input.addEventListener('blur', debounce(function() {
                            // Só salvar se o valor mudou
                            if (input.value !== originalValues[field]) {
                                saveField(input.name, input.value).then(() => {
                                    originalValues[field] = input.value;
                                });
                            }
                        }, 300, field));
                    }
                }
            });

            // Configurar campos de checkbox
            checkboxFields.forEach(field => {
                const checkbox = document.getElementById(field);
                if (checkbox) {
                    originalValues[field] = checkbox.checked ? 1 : 0;

                    checkbox.addEventListener('change', function() {
                        // Atualizar texto do label dinamicamente
                        const label = document.querySelector(`label.form-check-label[for="${field}"]`);
                        if (label) {
                            label.textContent = this.checked ? 'Ativado' : 'Desativado';
                        }

                        // Salvar alteração imediatamente
                        const value = this.checked ? 1 : 0;
                        saveField(checkbox.name, value).then(() => {
                            originalValues[field] = value;
                        });
                    });
                }
            });

            // Função para salvar um campo individual
            function saveField(fieldName, fieldValue) {
                const processingToast = ToastManager.info(`Salvando alteração...`);

                // Tratar valores vazios para campos integer
                const integerFields = ['max_quantidade_saques_diario', 'max_quantidade_saques_automaticos_diario',
                    'rollover_saque', 'bonus_mult', 'bonus_rollover', 'bonus_expire_days',
                    'tawkto_active', 'jivochat_active', 'enable_cassino_bonus', 'bonus_all_deposits'];

                if (integerFields.includes(fieldName)) {
                    if (fieldValue === '' || fieldValue === null || fieldValue === undefined) {
                        fieldValue = '0';
                    }
                    // Garantir que seja um inteiro
                    fieldValue = parseInt(fieldValue) || 0;
                }

                // Preparar dados
                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append(fieldName, fieldValue);

                // Enviar via AJAX
                return fetch(formAction, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        processingToast.remove();
                        if (data.success) {
                            $.ajax({
                                url: "{{route('clearcacheafterpayment')}}",
                                type: "GET",
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (response) {
                                },
                                error: function (xhr) {
                                },
                            });

                            ToastManager.success('Configuração atualizada com sucesso!');
                            return true;
                        } else {
                            ToastManager.error('Erro ao atualizar configuração.');
                            return false;
                        }
                    })
                    .catch(error => {
                        processingToast.remove();
                        console.error('Erro:', error);
                        ToastManager.error('Ocorreu um erro ao atualizar configuração.');
                        return false;
                    });
            }

            // Remover botão de salvar ou configurar a ação para salvar todos os campos
            const saveButton = document.getElementById('btnSalvar');
            if (saveButton) {
                saveButton.addEventListener('click', function() {
                    document.getElementById('settings').submit();
                });
            }

            // Inicializar valores originais
            initializeOriginalValues();
        });
    </script>
@endpush
