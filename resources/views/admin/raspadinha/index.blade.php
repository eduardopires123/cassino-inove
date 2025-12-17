@extends('admin.layouts.app')

@section('title', 'Gerenciar Raspadinhas')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="layout-px-spacing" id="contentaff">
    <div class="middle-content container-xxl p-0">
        <div class="page-meta" style="margin-top:45px;">
            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="estatde">Raspadinhas</li>
                </ol>
            </nav>
        </div>

        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div id="zero-config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-md-12 d-flex flex-wrap justify-content-end gap-2" style="padding-right: 36px; padding-top: 20px;">
                                    <a href="{{ route('admin.raspadinha.create') }}" 
                                       class="btn btn-success d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-plus me-1">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg> Nova Raspadinha
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="zero-config" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                <thead>
                                    <tr role="row">
                                        <th>ID</th>
                                        <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>Preço Normal</th>
                                        <th>Preço Turbo</th>
                                        <th>Qtd. Itens</th>
                                        <th>Status</th>
                                        <th>Criado em</th>
                                        <th style="width: 120px">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($raspadinhas as $raspadinha)
                                    <tr>
                                        <td>{{ $raspadinha->id }}</td>
                                        <td class="text-center">
                                            @if($raspadinha->image)
                                                <img src="{{ $raspadinha->image_url }}" alt="{{ $raspadinha->name }}" 
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 2px solid #e0e6ed;"
                                                     class="raspadinha-preview" data-bs-toggle="tooltip" title="Clique para ampliar">
                                            @else
                                                <div style="width: 60px; height: 60px; background-color: #f8f9fa; border-radius: 8px; border: 2px dashed #e0e6ed; display: flex; align-items: center; justify-content: center;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image text-muted">
                                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                        <polyline points="21,15 16,10 5,21"></polyline>
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $raspadinha->name }}</strong>
                                            @if($raspadinha->description)
                                                <br><small class="text-muted">{{ Str::limit($raspadinha->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>R$ {{ number_format($raspadinha->price, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($raspadinha->turbo_price, 2, ',', '.') }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $raspadinha->items->count() }}</span>
                                            <a href="{{ route('admin.raspadinha-item.index', $raspadinha) }}" class="btn btn-sm btn-outline-primary ms-1">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $raspadinha->is_active ? 'light-success' : 'light-danger' }} mb-2 me-4">
                                                {{ $raspadinha->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td>{{ $raspadinha->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.raspadinha.show', $raspadinha) }}" class="badge badge-light-info text-start me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </a>
                                            <a href="{{ route('admin.raspadinha.edit', $raspadinha) }}" class="badge badge-light-primary text-start me-2 action-edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                            </a>
                                            <button class="badge badge-light-danger text-start action-delete" data-id="{{ $raspadinha->id }}" data-name="{{ $raspadinha->name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Nenhuma raspadinha encontrada</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>Preço Normal</th>
                                        <th>Preço Turbo</th>
                                        <th>Qtd. Itens</th>
                                        <th>Status</th>
                                        <th>Criado em</th>
                                        <th style="width: 120px">Ações</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Mostrando {{ $raspadinhas->firstItem() }} até {{ $raspadinhas->lastItem() }} de {{ $raspadinhas->total() }} resultados
                            </div>
                            {{ $raspadinhas->links() }}
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

<!-- Modal para visualização de imagem -->
<div id="imageModal" class="image-modal">
    <span class="image-modal-close">&times;</span>
    <img class="image-modal-content" id="modalImage">
</div>

<style>
    body.dark .form-check-input:checked {
        background-color: #4361ee;
        border-color: #4361ee;
    }

    .raspadinha-preview {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .raspadinha-preview:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Modal para visualização da imagem */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
    }

    .image-modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 90%;
        max-height: 90%;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .image-modal-close {
        position: absolute;
        top: 15px;
        right: 25px;
        color: white;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
        z-index: 1001;
        background: rgba(0, 0, 0, 0.5);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease;
    }

    .image-modal-close:hover {
        background: rgba(0, 0, 0, 0.8);
    }
</style>
@endsection

@push('scripts')
<script>
    $(function() {
        // Lidar com cliques no botão de exclusão
        $('.action-delete').on('click', function() {
            const raspadinhaId = $(this).data('id');
            const raspadinhaName = $(this).data('name');

            // Mostrar modal de confirmação
            ModalManager.showConfirmation(
                'Excluir Raspadinha',
                `Tem certeza que deseja excluir a raspadinha "${raspadinhaName}"? Esta ação não pode ser desfeita.`,
                function() {
                    // Callback de confirmação
                    const deleteForm = $('#delete-form');
                    deleteForm.attr('action', `/admin/raspadinha/${raspadinhaId}`);

                    // Mostrar toast de processamento
                    const processingToast = ToastManager.info('Excluindo raspadinha, aguarde...');

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

        // Inicializar tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Modal de imagem
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const closeBtn = document.querySelector('.image-modal-close');

        // Abrir modal ao clicar na imagem
        $('.raspadinha-preview').on('click', function() {
            modal.style.display = 'block';
            modalImg.src = this.src;
            modalImg.alt = this.alt;
        });

        // Fechar modal
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        };

        // Fechar modal ao clicar fora da imagem
        modal.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };

        // Fechar modal com tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                modal.style.display = 'none';
            }
        });
    });
</script>
@endpush 