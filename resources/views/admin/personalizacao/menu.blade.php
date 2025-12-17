@extends('admin.layouts.app')
@section('content')
    @php
        use Carbon\Carbon;
        /*$ProvidersName = App\Models\GamesApi::select('provider_name', DB::raw('count(*) as total'), DB::raw('sum(views) as total_views'))
        ->groupBy('provider_name')
        ->orderBy('provider_name', 'asc')
        ->get();*/

        $ProvidersName = App\Models\MenuCategoria::orderBy('ordem', 'asc')->get();
    @endphp
    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Personalização</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Menu</li>
                    </ol>
                </nav>
            </div>

            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <div class="table-responsive">
                            <table id="menu_categorias" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid">
                                <thead>
                                <tr role="row">
                                    <th>Nome</th>
                                    <th>Editar</th>
                                    <th>Ordem</th>
                                    <th>Ativo</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($ProvidersName as $registro)
                                    <tr data-id="{{$registro->id}}">
                                        <td>{{$registro->nome}}</td>
                                        <td>
                                            <div class="action-btns">
                                                <a href="javascript:void(0);" class="action-btn btn-edit bs-tooltip me-2" data-category-id="{{$registro->id}}" data-toggle="bs-tooltip" aria-label="Editar Elementos Menu" data-bs-original-title="Editar Elementos Menu">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <input id="OrderMenuCat{{$registro->id}}" type="number" min="1" max="100" name="OrderMenuCat{{$registro->id}}" placeholder="Ordem de exibição" class="form-control" required="" value="{{$registro->ordem}}" onchange="AttMenuCat('{{$registro->id}}', 'ordem', this.value);">
                                        </td>
                                        <td>
                                            <div class="form-check form-switch form-check-inline form-switch-primary">
                                                <input class="form-check-input" type="checkbox" role="switch" id="form-switch-primary" {{($registro->active == 1) ? "checked" : ""}} onclick="AttMenuCat('{{$registro->id}}', 'active', Number(this.checked));">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Nome</th>
                                    <th>Editar</th>
                                    <th>Ordem</th>
                                    <th>Ativo</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Edição -->
    <div class="modal fade modal-xl" id="categoriesModal" tabindex="-1" role="dialog" aria-labelledby="tabsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tabsModalLabel">Editar Categoria <span id="categoria-nome"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="menuItemsContent">
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                            <p class="mt-2">Carregando itens...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function AttMenuCat(id, campo, valor) {
                $.ajax({
                    url: '/admin/menu/update-category',
                    type: 'POST',
                    data: {
                        id: id,
                        campo: campo,
                        valor: valor,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            ToastManager.success('Atualizado com sucesso!');
                        } else {
                            ToastManager.error('Erro ao atualizar!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro na requisição:', xhr.responseText);
                        ToastManager.error('Erro ao atualizar! Verifique o console para mais detalhes.');
                    }
                });
            }

            $(document).ready(function() {
                if (document.querySelector('#menu_items') && document.querySelector('#menu_items').tagName === 'TABLE') {
                    // Usa um seletor mais específico para garantir que estamos trabalhando com tabelas
                    $('table#menu_items').DataTable({
                        "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                            "<'table-responsive'tr>" +
                            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                        "oLanguage": {
                            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                            "sInfo": "Showing page _PAGE_ of _PAGES_",
                            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                            "sSearchPlaceholder": "Search...",
                            "sLengthMenu": "Results :  _MENU_",
                        },
                        "stripeClasses": [],
                        "lengthMenu": [7, 10, 20, 50],
                        "pageLength": 7
                    });
                }

                // Eventos do Modal
                $(document).off('click', '.btn-edit').on('click', '.btn-edit', function() {
                    const categoriaId = $(this).data('category-id');
                    const categoriaNome = $(this).closest('tr').find('td:first').text();

                    $('#categoria-nome').text(categoriaNome);
                    $('#categoriesModal').modal('show');
                    loadMenuItems(categoriaId);
                });

                $('#categoriesModal').on('hidden.bs.modal', function () {
                    $('#menuItemsContent').html(`
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-2">Carregando itens...</p>
                </div>
            `);
                });
            });

            function loadMenuItems(categoriaId) {
                $.ajax({
                    url: '/admin/menu/load-items/' + categoriaId,
                    type: 'GET',
                    success: function(response) {
                        $('#menuItemsContent').html(response);

                        document.querySelectorAll('[name="game_id"]').forEach(element => {
                            if (!element.tomselect) {
                                new TomSelect(element, { maxItems: 1,
                                    create: true,});
                            }
                        });

                        document.querySelectorAll('[name="itemLink"]').forEach(element => {
                            if (!element.tomselect) {
                                new TomSelect(element, { maxItems: 1,
                                    create: true,});
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro ao carregar itens:', xhr.responseText);
                        ToastManager.error('Erro ao carregar itens do menu');
                        $('#menuItemsContent').html(`
                    <div class="alert alert-danger">
                        <h5>Erro ao carregar itens</h5>
                        <p>Detalhes: ${error}</p>
                        <p>Resposta do servidor: ${xhr.responseText}</p>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary" onclick="loadMenuItems(${categoriaId})">Tentar novamente</button>
                    </div>
                `);
                    }
                });
            }
        </script>
    @endpush

@endsection
