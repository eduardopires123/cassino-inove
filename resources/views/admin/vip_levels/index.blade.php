@extends('admin.layouts.app')
@section('content')
    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Admin</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Níveis VIP</li>
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
                                        <a href="{{ route('admin.vip-levels.reset') }}"
                                           class="btn btn-danger d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-trash me-1">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6
                             m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                </path>
                                            </svg> Resetar Níveis Vip
                                        </a>

                                        <a href="{{ route('admin.vip-levels.create') }}"
                                           class="btn btn-success d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-upload me-1">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="17 8 12 3 7 8"></polyline>
                                                <line x1="12" y1="3" x2="12" y2="15"></line>
                                            </svg> Adicionar Novo Nível
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="zero-config" class="table table-striped dt-table-hover dataTable" id="niveis-table" style="width: 100%;" role="grid" aria-describedby="zero-config_info">
                                    <thead>
                                    <tr role="row">
                                        <th>Imagem</th>
                                        <th style="width: 80px">Nível</th>
                                        <th>Nome</th>
                                        <th>Min. Depósito</th>
                                        <th>Max. Depósito</th>
                                        <th>Coins</th>
                                        <th>Saldo Real</th>
                                        <th>Saldo Bônus</th>
                                        <th>Rodadas Grátis</th>
                                        <th>Status</th>
                                        <th style="width: 120px">Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody id="sortable-levels">
                                    @foreach($levels as $level)
                                        <tr data-id="{{ $level->id }}">
                                            <td class="handle">
                                                @if($level->image)
                                                    <img src="{{ asset($level->image) }}" alt="{{ $level->name }}" height="40">
                                                @else
                                                    <span class="text-muted">Sem imagem</span>
                                                @endif
                                            </td>
                                            <td>{{ $level->level }}</td>
                                            <td>{{ $level->name }}</td>
                                            <td>R$ {{ number_format($level->min_deposit, 2, ',', '.') }}</td>
                                            <td>
                                                @if($level->max_deposit)
                                                    R$ {{ number_format($level->max_deposit, 2, ',', '.') }}
                                                @else
                                                    <span class="text-muted">Sem limite</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($level->coins_reward) }}</td>
                                            <td>
                                                @if($level->balance_reward > 0)
                                                    R$ {{ number_format($level->balance_reward, 2, ',', '.') }}
                                                @else
                                                    <span class="text-muted">Sem recompensa</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($level->balance_bonus_reward > 0)
                                                    R$ {{ number_format($level->balance_bonus_reward, 2, ',', '.') }}
                                                @else
                                                    <span class="text-muted">Sem recompensa</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($level->free_spins_reward > 0)
                                                    {{ number_format($level->free_spins_reward) }}
                                                @else
                                                    <span class="text-muted">Sem recompensa</span>
                                                @endif
                                            </td>
                                            <td>
                                            <span class="badge badge-{{ $level->active ? 'light-success' : 'light-danger' }} mb-2 me-4">
                                                {{ $level->active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.vip-levels.edit', $level->id) }}" class="badge badge-light-primary text-start me-2 action-edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                                </a>
                                                <a href="{{ route('admin.vip-levels.redemptions', $level->id) }}" class="badge badge-light-info text-start me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                                </a>
                                                <button class="badge badge-light-danger text-start action-delete" data-id="{{ $level->id }}" data-name="{{ $level->name }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Imagem</th>
                                        <th style="width: 80px">Nível</th>
                                        <th>Nome</th>
                                        <th>Min. Depósito</th>
                                        <th>Max. Depósito</th>
                                        <th>Coins</th>
                                        <th>Saldo Real</th>
                                        <th>Saldo Bônus</th>
                                        <th>Rodadas Grátis</th>
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
    </style>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $(function() {
            // Inicializar ordenação
            $("#sortable-levels").sortable({
                handle: ".handle",
                update: function(event, ui) {
                    var levels = {};

                    $('#sortable-levels tr').each(function(index) {
                        levels[$(this).data('id')] = index + 1;
                    });

                    // Mostrar toast de processamento
                    const processingToast = ToastManager.info('Atualizando ordem, aguarde...');

                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.vip-levels.update-order') }}",
                        data: {
                            levels: levels,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Remover toast de processamento
                            processingToast.remove();

                            // Mostrar toast de sucesso apenas se a resposta não incluir redirecionamento
                            // Isso evita duplicação com as mensagens flash da sessão
                            if (response && response.success) {
                                ToastManager.success('Ordem atualizada com sucesso!');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Remover toast de processamento
                            processingToast.remove();

                            // Mostrar toast de erro
                            ToastManager.error('Erro ao atualizar a ordem. Por favor, tente novamente.');
                            console.error('Erro:', error);
                        }
                    });
                }
            });

            // Lidar com cliques no botão de exclusão
            $('.action-delete').on('click', function() {
                const levelId = $(this).data('id');
                const levelName = $(this).data('name');

                // Mostrar modal de confirmação
                ModalManager.showConfirmation(
                    'Excluir Nível VIP',
                    `Tem certeza que deseja excluir o nível "${levelName}"? Esta ação não pode ser desfeita.`,
                    function() {
                        // Callback de confirmação
                        const deleteForm = $('#delete-form');
                        deleteForm.attr('action', `/admin/vip-levels/${levelId}`);

                        // Mostrar toast de processamento
                        const processingToast = ToastManager.info('Excluindo nível, aguarde...');

                        // Enviar o formulário
                        deleteForm.submit();
                    }
                );
            });

            // Inicialização do gerenciador de modais quando o documento estiver pronto
            ModalManager.init();

            // Verificar se há mensagem de sucesso na sessão e exibir toast
            // Esta é a única notificação de sucesso que deve aparecer
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
