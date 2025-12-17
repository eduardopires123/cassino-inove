@extends('admin.layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="middle-content container-xxl p-0">
        <!-- BREADCRUMB -->
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Email</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.email-templates.index') }}">Templates</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.email-templates.show', $emailTemplate) }}">{{ $emailTemplate->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pré-visualização</li>
                </ol>
            </nav>
        </div>
        <!-- /BREADCRUMB -->

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="d-flex justify-content-between">
                        <h5 class="mt-2">Pré-visualização: {{ $emailTemplate->name }}</h5>
                        <div>
                            <a href="{{ route('admin.email-templates.edit', $emailTemplate) }}" class="btn btn-primary btn-sm me-2">
                                <i class="fas fa-pencil-alt"></i> Editar
                            </a>
                            <a href="{{ route('admin.email-templates.show', $emailTemplate) }}" class="btn btn-info btn-sm me-2">
                                <i class="fas fa-eye"></i> Detalhes
                            </a>
                            <a href="{{ route('admin.email-templates.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </div>
                    <hr>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Dados Utilizados na Renderização</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.email-templates.preview', $emailTemplate) }}" method="GET">
                                        <h6>Personalizar Dados</h6>
                                        @if(is_array($emailTemplate->variables) && count($emailTemplate->variables) > 0)
                                            @foreach($emailTemplate->variables as $varName => $varDescription)
                                                <div class="mb-3">
                                                    <label for="var_{{ $varName }}">{{ $varDescription }} ({{ $varName }})</label>
                                                    <input type="text" class="form-control" id="var_{{ $varName }}" 
                                                           name="test_data[{{ $varName }}]" 
                                                           value="{{ isset($data[$varName]) ? $data[$varName] : '' }}">
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="alert alert-info">
                                                Não há variáveis definidas para este template.
                                            </div>
                                        @endif

                                        <button type="submit" class="btn btn-primary">Atualizar Prévia</button>
                                    </form>

                                    <hr>
                                    
                                    <form method="POST" action="{{ route('admin.email-templates.send-test', $emailTemplate) }}">
                                        @csrf
                                        @if(is_array($data))
                                            @foreach($data as $key => $value)
                                                <input type="hidden" name="test_data[{{ $key }}]" value="{{ $value }}">
                                            @endforeach
                                        @endif
                                        
                                        <div class="mb-3">
                                            <label for="email">Enviar e-mail de teste para:</label>
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Seu email para teste" required>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-paper-plane"></i> Enviar Email de Teste
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Prévia do Email</h6>
                                        <div>
                                            <div class="badge badge-primary">Assunto: {{ $subject }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <!-- Abas para alternar entre HTML e Texto -->
                                    <ul class="nav nav-tabs" id="previewTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="html-tab" data-bs-toggle="tab" data-bs-target="#html-content" type="button" role="tab" aria-controls="html-content" aria-selected="true">HTML</button>
                                        </li>
                                        @if($textContent)
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="text-tab" data-bs-toggle="tab" data-bs-target="#text-content" type="button" role="tab" aria-controls="text-content" aria-selected="false">Texto Plano</button>
                                            </li>
                                        @endif
                                    </ul>
                                    
                                    <div class="tab-content" id="previewTabContent">
                                        <div class="tab-pane fade show active" id="html-content" role="tabpanel" aria-labelledby="html-tab">
                                            <!-- Visualização do email em HTML -->
                                            <div class="email-preview-frame">
                                                <iframe id="email-preview-iframe" style="width: 100%; height: 600px; border: 1px solid #ddd;"></iframe>
                                            </div>
                                        </div>
                                        @if($textContent)
                                            <div class="tab-pane fade" id="text-content" role="tabpanel" aria-labelledby="text-tab">
                                                <!-- Visualização do email em texto -->
                                                <div class="p-3">
                                                    <pre style="white-space: pre-wrap; font-family: monospace; background-color: #f8f9fa; padding: 15px; border-radius: 5px;">{{ $textContent }}</pre>
                                                </div>
                                            </div>
                                        @endif
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Injetar o conteúdo HTML no iframe
        const iframe = document.getElementById('email-preview-iframe');
        const iframeDocument = iframe.contentDocument || iframe.contentWindow.document;
        
        iframeDocument.open();
        iframeDocument.write(`{!! $htmlContent !!}`);
        iframeDocument.close();
        
        // Ajustar altura do iframe para conteúdo (opcional)
        iframe.onload = function() {
            iframe.style.height = (iframe.contentWindow.document.body.scrollHeight + 50) + 'px';
        };
    });
</script>
@endsection 