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
                    <li class="breadcrumb-item active" aria-current="page">{{ $emailTemplate->name }}</li>
                </ol>
            </nav>
        </div>
        <!-- /BREADCRUMB -->

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="d-flex justify-content-between">
                        <h5 class="mt-2">Template: {{ $emailTemplate->name }}</h5>
                        <div>
                            <a href="{{ route('admin.email-templates.edit', $emailTemplate) }}" class="btn btn-primary btn-sm me-2">
                                <i class="fas fa-pencil-alt"></i> Editar
                            </a>
                            <a href="{{ route('admin.email-templates.preview', $emailTemplate) }}" class="btn btn-info btn-sm me-2">
                                <i class="fas fa-desktop"></i> Pré-visualizar
                            </a>
                            <a href="{{ route('admin.email-templates.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </div>
                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Informações Básicas</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 30%">Nome</th>
                                        <td>{{ $emailTemplate->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Slug</th>
                                        <td><code>{{ $emailTemplate->slug }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Descrição</th>
                                        <td>{{ $emailTemplate->description ?: 'Não informada' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Assunto</th>
                                        <td>{{ $emailTemplate->subject }}</td>
                                    </tr>
                                    <tr>
                                        <th>ID Brevo</th>
                                        <td>{{ $emailTemplate->brevo_template_id ?: 'Não vinculado' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge {{ $emailTemplate->is_active ? 'badge-success' : 'badge-danger' }}">
                                                {{ $emailTemplate->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Criado em</th>
                                        <td>{{ $emailTemplate->created_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Atualizado em</th>
                                        <td>{{ $emailTemplate->updated_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Variáveis do Template</h6>
                            @if(is_array($emailTemplate->variables) && count($emailTemplate->variables) > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Descrição</th>
                                                <th>Exemplo de Uso</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($emailTemplate->variables as $varName => $varDescription)
                                                <tr>
                                                    <td><code>{{ $varName }}</code></td>
                                                    <td>{{ $varDescription }}</td>
                                                    <td><code>@{{ $varName }}</code></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Não há variáveis definidas para este template.
                                </div>
                            @endif
                        </div>
                    </div>

                    <h6>Conteúdo HTML</h6>
                    <div class="card mb-4">
                        <div class="card-body">
                            <pre style="max-height: 300px; overflow-y: auto;"><code class="language-html">{{ htmlspecialchars($emailTemplate->html_content) }}</code></pre>
                        </div>
                    </div>

                    @if($emailTemplate->text_content)
                        <h6>Conteúdo em Texto Plano</h6>
                        <div class="card mb-4">
                            <div class="card-body">
                                <pre style="max-height: 200px; overflow-y: auto;"><code>{{ $emailTemplate->text_content }}</code></pre>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <form method="POST" action="{{ route('admin.email-templates.send-test', $emailTemplate) }}" class="mb-4">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label for="email">Enviar e-mail de teste para:</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Seu email para teste" required>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-paper-plane"></i> Enviar Email de Teste
                                    </button>
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

@section('scripts')
<script>
    // Adicionar highlighter de código se necessário
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof hljs !== 'undefined') {
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightBlock(block);
            });
        }
    });
</script>
@endsection 