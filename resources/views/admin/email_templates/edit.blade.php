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
                    <li class="breadcrumb-item active" aria-current="page">Editar Template</li>
                </ol>
            </nav>
        </div>
        <!-- /BREADCRUMB -->

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="d-flex justify-content-between">
                        <h5 class="mt-2">Editar Template: {{ $emailTemplate->name }}</h5>
                        <div>
                            <a href="{{ route('admin.email-templates.index') }}" class="btn btn-secondary btn-sm me-2">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                            <a href="{{ route('admin.email-templates.preview', $emailTemplate) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-desktop"></i> Pré-visualizar
                            </a>
                        </div>
                    </div>
                    <hr>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.email-templates.update', $emailTemplate) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name">Nome <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $emailTemplate->name) }}" required>
                                <small class="form-text text-muted">Nome descritivo do template</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="slug">Slug <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $emailTemplate->slug) }}" required
                                    {{ in_array($emailTemplate->slug, ['welcome', 'password-reset']) ? 'readonly' : '' }}>
                                <small class="form-text text-muted">
                                    Identificador único (apenas letras, números e traços)
                                    @if(in_array($emailTemplate->slug, ['welcome', 'password-reset']))
                                        <span class="text-danger">Este é um template padrão do sistema e seu slug não pode ser alterado.</span>
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="description">Descrição</label>
                                <textarea class="form-control" id="description" name="description" rows="2">{{ old('description', $emailTemplate->description) }}</textarea>
                                <small class="form-text text-muted">Breve descrição do propósito deste template</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-9 mb-3">
                                <label for="subject">Assunto do Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject', $emailTemplate->subject) }}" required>
                                <small class="form-text text-muted">Pode incluir variáveis como {{site_name}}</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="brevo_template_id">ID Template Brevo</label>
                                <input type="number" class="form-control" id="brevo_template_id" name="brevo_template_id" value="{{ old('brevo_template_id', $emailTemplate->brevo_template_id) }}">
                                <small class="form-text text-muted">Opcional - ID do template no Brevo</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="html_content">Conteúdo HTML <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="html_content" name="html_content" rows="15" required>{{ old('html_content', $emailTemplate->html_content) }}</textarea>
                                <small class="form-text text-muted">Conteúdo HTML do email com variáveis no formato {{nome_variavel}}</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="text_content">Conteúdo em Texto Plano</label>
                                <textarea class="form-control" id="text_content" name="text_content" rows="8">{{ old('text_content', $emailTemplate->text_content) }}</textarea>
                                <small class="form-text text-muted">Versão em texto plano do email (recomendado para clientes que não suportam HTML)</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Variáveis do Template</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="variables-table">
                                        <thead>
                                            <tr>
                                                <th>Nome da Variável</th>
                                                <th>Descrição</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(is_array($emailTemplate->variables) && count($emailTemplate->variables) > 0)
                                                @foreach($emailTemplate->variables as $varName => $varDescription)
                                                    <tr>
                                                        <td><input type="text" class="form-control" name="variables[{{ $loop->index }}][name]" value="{{ $varName }}" placeholder="Ex: nome_usuario"></td>
                                                        <td><input type="text" class="form-control" name="variables[{{ $loop->index }}][description]" value="{{ $varDescription }}" placeholder="Ex: Nome do usuário"></td>
                                                        <td><button type="button" class="btn btn-danger btn-sm remove-variable"><i class="fas fa-trash"></i></button></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td><input type="text" class="form-control" name="variables[0][name]" placeholder="Ex: nome_usuario"></td>
                                                    <td><input type="text" class="form-control" name="variables[0][description]" placeholder="Ex: Nome do usuário"></td>
                                                    <td><button type="button" class="btn btn-danger btn-sm remove-variable"><i class="fas fa-trash"></i></button></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3">
                                                    <button type="button" class="btn btn-info btn-sm" id="add-variable">
                                                        <i class="fas fa-plus"></i> Adicionar Variável
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ $emailTemplate->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Template Ativo</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Atualizar Template
                                </button>
                                <a href="{{ route('admin.email-templates.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    $(document).ready(function() {
        // Inicializar TinyMCE para o conteúdo HTML
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#html_content',
                height: 500,
                plugins: 'link image code table lists',
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
                menubar: false
            });
        }

        // Gerenciar variáveis do template
        let variableIndex = $('#variables-table tbody tr').length;

        $('#add-variable').click(function() {
            const newRow = `
                <tr>
                    <td><input type="text" class="form-control" name="variables[${variableIndex}][name]" placeholder="Ex: nome_usuario"></td>
                    <td><input type="text" class="form-control" name="variables[${variableIndex}][description]" placeholder="Ex: Nome do usuário"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-variable"><i class="fas fa-trash"></i></button></td>
                </tr>
            `;
            $('#variables-table tbody').append(newRow);
            variableIndex++;
        });

        $(document).on('click', '.remove-variable', function() {
            if ($('#variables-table tbody tr').length > 1) {
                $(this).closest('tr').remove();
            } else {
                alert('Pelo menos uma variável deve ser mantida.');
            }
        });
    });
</script>
@endsection 