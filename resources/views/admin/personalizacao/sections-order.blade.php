@extends('admin.layouts.app')
@section('content')
    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.personalizacao.home') }}">Personalização</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ordem das Seções da Home</li>
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
                                        <button type="button" class="btn btn-warning d-flex align-items-center" onclick="resetOrder()">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-refresh-cw me-1">
                                                <polyline points="23 4 23 10 17 10"></polyline>
                                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                                            </svg> Resetar para Padrão
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="zero-config" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                    <thead>
                                    <tr role="row">
                                        <th>Seção</th>
                                        <th style="width: 80px">Posição</th>
                                        <th>Nome da Seção</th>
                                        <th>Chave</th>
                                        <th>Status</th>
                                        <th style="width: 120px">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody id="sortable-sections">
                                    @foreach($sections as $section)
                                        <tr data-section-key="{{ $section->section_key }}">
                                            <td class="handle">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-menu">
                                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                                    <line x1="3" y1="12" x2="21" y2="12"></line>
                                                    <line x1="3" y1="18" x2="21" y2="18"></line>
                                                </svg>
                                            </td>
                                            <td>{{ $section->position }}</td>
                                            <td>{{ $section->section_name }}</td>
                                            <td><code>{{ $section->section_key }}</code></td>
                                            <td>
                                                <span class="badge badge-{{ $section->is_active ? 'light-success' : 'light-danger' }} mb-2 me-4">
                                                    {{ $section->is_active ? 'Ativo' : 'Inativo' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input 
                                                        class="form-check-input section-toggle" 
                                                        type="checkbox" 
                                                        id="toggle-{{ $section->section_key }}"
                                                        data-section-key="{{ $section->section_key }}"
                                                        {{ $section->is_active ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label" for="toggle-{{ $section->section_key }}">
                                                        {{ $section->is_active ? 'Ativo' : 'Inativo' }}
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Seção</th>
                                        <th style="width: 80px">Posição</th>
                                        <th>Nome da Seção</th>
                                        <th>Chave</th>
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

    <style>
        body.dark .form-check-input:checked {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        .handle {
            cursor: grab;
        }

        .handle:active {
            cursor: grabbing;
        }

        code {
            font-size: 0.875em;
            color: #6c757d;
            background-color: #f8f9fa;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }

        body.dark code {
            color: #adb5bd;
            background-color: #495057;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        $(function() {
            // Inicializar ordenação
            $("#sortable-sections").sortable({
                handle: ".handle",
                update: function(event, ui) {
                    updatePositions();
                    
                    // Mostrar toast de processamento
                    const processingToast = ToastManager.info('Atualizando ordem, aguarde...');

                    // Preparar dados para envio
                    const sectionsOrder = [];
                    $('#sortable-sections tr').each(function() {
                        sectionsOrder.push($(this).data('section-key'));
                    });

                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.personalizacao.sections-order.update') }}",
                        data: {
                            sections: sectionsOrder,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Remover toast de processamento
                            processingToast.remove();

                            if (response && response.success) {
                                ToastManager.success('Ordem atualizada com sucesso!');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Remover toast de processamento
                            processingToast.remove();

                            ToastManager.error('Erro ao atualizar a ordem. Por favor, tente novamente.');
                            console.error('Erro:', error);
                        }
                    });
                }
            });

            // Event listeners para os switches
            $('.section-toggle').on('change', function() {
                const sectionKey = $(this).data('section-key');
                const isActive = $(this).is(':checked');
                toggleSection(sectionKey, isActive, $(this));
            });

            function updatePositions() {
                $('#sortable-sections tr').each(function(index) {
                    $(this).find('td:eq(1)').text(index + 1);
                });
            }

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

        function saveOrder() {
            const sectionsOrder = [];
            $('#sortable-sections tr').each(function() {
                sectionsOrder.push($(this).data('section-key'));
            });

            // Mostrar toast de processamento
            const processingToast = ToastManager.info('Salvando ordem, aguarde...');

            fetch('{{ route("admin.personalizacao.sections-order.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    sections: sectionsOrder
                })
            })
            .then(response => response.json())
            .then(data => {
                // Remover toast de processamento
                processingToast.remove();

                if (data.success) {
                    ToastManager.success(data.message);
                } else {
                    ToastManager.error(data.message);
                }
            })
            .catch(error => {
                // Remover toast de processamento
                processingToast.remove();
                
                console.error('Error:', error);
                ToastManager.error('Erro ao salvar ordem das seções');
            });
        }

        function toggleSection(sectionKey, isActive, toggleElement) {
            // Mostrar toast de processamento
            const processingToast = ToastManager.info('Atualizando status, aguarde...');

            fetch('{{ route("admin.personalizacao.sections-order.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    section_key: sectionKey,
                    is_active: isActive
                })
            })
            .then(response => response.json())
            .then(data => {
                // Remover toast de processamento
                processingToast.remove();

                if (data.success) {
                    // Atualizar visual da seção
                    const row = toggleElement.closest('tr');
                    const statusBadge = row.find('.badge');
                    const label = toggleElement.next('label');
                    
                    if (isActive) {
                        statusBadge.removeClass('badge-light-danger').addClass('badge-light-success').text('Ativo');
                        label.text('Ativo');
                    } else {
                        statusBadge.removeClass('badge-light-success').addClass('badge-light-danger').text('Inativo');
                        label.text('Inativo');
                    }
                    
                    ToastManager.success(data.message);
                } else {
                    ToastManager.error(data.message);
                    // Reverter o switch em caso de erro
                    toggleElement.prop('checked', !isActive);
                }
            })
            .catch(error => {
                // Remover toast de processamento
                processingToast.remove();
                
                console.error('Error:', error);
                ToastManager.error('Erro ao atualizar status da seção');
                // Reverter o switch em caso de erro
                toggleElement.prop('checked', !isActive);
            });
        }

        function resetOrder() {
            // Usar ModalManager para confirmação
            ModalManager.showConfirmation(
                'Resetar Ordem das Seções',
                'Tem certeza que deseja resetar a ordem das seções para o padrão? Esta ação não pode ser desfeita.',
                function() {
                    // Callback de confirmação
                    const processingToast = ToastManager.info('Resetando ordem, aguarde...');

                    fetch('{{ route("admin.personalizacao.sections-order.reset") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Remover toast de processamento
                        processingToast.remove();

                        if (data.success) {
                            ToastManager.success(data.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            ToastManager.error(data.message);
                        }
                    })
                    .catch(error => {
                        // Remover toast de processamento
                        processingToast.remove();
                        
                        console.error('Error:', error);
                        ToastManager.error('Erro ao resetar ordem das seções');
                    });
                }
            );
        }
    </script>
@endpush 