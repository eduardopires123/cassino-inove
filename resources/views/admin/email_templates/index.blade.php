@extends('admin.layouts.app')
@section('content')
@php
    use Illuminate\Support\Str;
@endphp
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Email</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Templates</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <div class="row" style="margin-bottom: -20px; padding:15px;">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <h6>Templates de Email</h6>
                                <div>
                                    <a href="{{ route('admin.email-templates.create') }}" class="btn btn-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Novo Template
                                    </a>
                                    <form method="POST" action="{{ route('admin.email-templates.run-migration') }}" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-info">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg> Criar Templates Padrão
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="zero-config" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th>Nome</th>
                                        <th>Slug</th>
                                        <th>Descrição</th>
                                        <th>Assunto</th>
                                        <th>ID Brevo</th>
                                        <th>Status</th>
                                        <th style="width: 120px">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($templates as $template)
                                        <tr>
                                            <td>{{ $template->name }}</td>
                                            <td><code>{{ $template->slug }}</code></td>
                                            <td>{{ Str::limit($template->description, 30) }}</td>
                                            <td>{{ Str::limit($template->subject, 30) }}</td>
                                            <td>{{ $template->brevo_template_id ?: 'Não vinculado' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $template->is_active ? 'light-success' : 'light-danger' }} mb-2 me-4">
                                                    {{ $template->is_active ? 'Ativo' : 'Inativo' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.email-templates.show', $template) }}" class="badge badge-light-info text-start me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </a>
                                                <a href="{{ route('admin.email-templates.edit', $template) }}" class="badge badge-light-primary text-start me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                                </a>
                                                <a href="{{ route('admin.email-templates.preview', $template) }}" class="badge badge-light-warning text-start me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                                                </a>
                                                <form method="POST" action="{{ route('admin.email-templates.destroy', $template) }}" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="badge badge-light-danger text-start action-delete" {{ in_array($template->slug, ['welcome', 'password-reset']) ? 'disabled' : '' }} data-id="{{ $template->id }}" data-name="{{ $template->name }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Nenhum template encontrado</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Slug</th>
                                        <th>Descrição</th>
                                        <th>Assunto</th>
                                        <th>ID Brevo</th>
                                        <th>Status</th>
                                        <th style="width: 120px">Ações</th>
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

<!-- Formulário oculto para exclusão -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')

<script>
    $(function() {
        // Inicializar DataTable
        if ($.fn.DataTable) {
            $('#zero-config').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
                }
            });
        }
        
        // Lidar com cliques no botão de exclusão
        $('.action-delete').on('click', function() {
            const templateId = $(this).data('id');
            const templateName = $(this).data('name');
            
            if ($(this).attr('disabled')) {
                return;
            }
            
            // Mostrar modal de confirmação
            ModalManager.showConfirmation(
                'Excluir Template de Email',
                `Tem certeza que deseja excluir o template "${templateName}"? Esta ação não pode ser desfeita.`,
                function() {
                    // Callback de confirmação
                    const deleteForm = $('#delete-form');
                    deleteForm.attr('action', `/admin/email-templates/${templateId}`);
                    
                    // Mostrar toast de processamento
                    const processingToast = ToastManager.info('Excluindo template, aguarde...');
                    
                    // Enviar o formulário
                    deleteForm.submit();
                }
            );
        });
        
        // Inicialização do gerenciador de modais quando o documento estiver pronto
        ModalManager.init();
        
        // Verificar se há mensagem de sucesso na sessão e exibir toast
        @if(session('success'))
            ToastManager.success("{{ session('success') }}");
        @endif
        
        // Verificar se há mensagem de erro na sessão e exibir toast
        @if(session('error'))
            ToastManager.error("{{ session('error') }}");
        @endif
    });
</script>
@endpush 