@extends('admin.layouts.app')
@section('content')
    @php
        $userTable = App\Models\User::Where('is_admin', '>', 0)->get();
    @endphp

    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Administração</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Permissões</li>
                    </ol>
                </nav>
            </div>
            <div class="row" id="cancel-row">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-top-spacing layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <div id="invoice-list_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <div class="table-responsive">
                                <table id="permissoes" class="table table-striped dt-table-hover dataTable" style="width: 100%;" role="grid" aria-describedby="datatable-permissions_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="permissoes" rowspan="1" colspan="1">Usuário</th>
                                        <th class="sorting" tabindex="0" aria-controls="permissoes" rowspan="1" colspan="1">Permissões</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($userTable as $registro)
                                        @php
                                            $userPermissions = App\Models\Admin\Permissions::Where('user_id', $registro->id)->first();

                                            if ($userPermissions){
                                                $Permissoes = json_decode($userPermissions->permission, true);

                                                $mapaPermissoes = [
                                                '1' => 'Personalização',
                                                '2' => 'Cassino',
                                                '3' => 'SportsBook',
                                                '4' => 'Pagamentos',
                                                '5' => 'Usuários',
                                                '6' => 'Administração',
                                                '7' => 'Notificações',
                                                '8' => 'Cashback',
                                                '9' => 'Afiliação',
                                                '10' => 'Plugins'];

                                                $Valores = '';

                                                foreach ($mapaPermissoes as $key => $valor) {
                                                    if (!empty($Permissoes[$key]) && $Permissoes[$key] == 1) {
                                                        $Valores .= $valor . ',';
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr role="row" data-id="{{$registro->id}}">
                                            <td class="sorting_1">{{$registro->name}}</td>
                                            <td><input name='users-list-tags' value='{{$Valores}}' autofocus></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">Usuário</th>
                                        <th rowspan="1" colspan="1">Permissões</th>
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
    </style>

    @push("scripts")
        <script>
            function SetPermissions(id, value) {
                $.ajax({
                    url: '/admin/setPermissions',
                    type: 'POST',
                    data: {
                        id: id,
                        chave: value,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },

                    success: function(response) {
                        if (response.status) {
                            ToastManager.success(response.message);
                        }else{
                            ToastManager.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        ToastManager.error(xhr.message);
                    }
                });
            }

            function EnableTagify() {
                var inputElements = document.querySelectorAll('input[name=users-list-tags]');

                inputElements.forEach(inputElm => {
                    const row = inputElm.closest('tr');
                    const rowId = row.getAttribute('data-id');

                    function tagTemplate(tagData) {
                        return `<tag contenteditable='false'
                    spellcheck='false'
                    tabIndex="-1"
                    class="tagify__tag ${tagData.class ? tagData.class : ""}"
                    ${this.getAttributes(tagData)}>
                <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                <div>
                    <span class='tagify__tag-text'>${tagData.name}</span>
                </div>
            </tag>`;
                    }

                    function suggestionItemTemplate(tagData) {
                        return `<div ${this.getAttributes(tagData)}
                        class='tagify__dropdown__item ${tagData.class ? tagData.class : ""}'
                        tabindex="0"
                        role="option">
                        <strong>${tagData.name}</strong>
                    </div>`;
                    }

                    var usrList = new Tagify(inputElm, {
                        tagTextProp: 'name',
                        enforceWhitelist: true,
                        skipInvalid: true,
                        dropdown: {
                            closeOnSelect: false,
                            enabled: 0,
                            classname: 'users-list',
                            searchKeys: ['name']
                        },
                        templates: {
                            tag: tagTemplate,
                            dropdownItem: suggestionItemTemplate
                        },
                        whitelist: [
                            {"value": 1, "name": "Personalização"},
                            {"value": 2, "name": "Cassino"},
                            {"value": 3, "name": "SportsBook"},
                            {"value": 4, "name": "Pagamentos"},
                            {"value": 5, "name": "Usuários"},
                            {"value": 6, "name": "Administração"},
                            {"value": 7, "name": "Notificações"},
                            {"value": 8, "name": "Cashback"},
                            {"value": 9, "name": "Afiliação"},
                            {"value": 10, "name": "Plugins"},
                        ]
                    });

                    usrList.on('dropdown:show dropdown:updated', onDropdownShow);
                    usrList.on('dropdown:select', (e) => onSelectSuggestion(e, rowId));
                    usrList.on('remove', (e) => onRemoveTag(e, rowId));
                });

                function onDropdownShow(e) {}

                function onSelectSuggestion(e, rowId) {
                    const selectedTagData = e.detail.data;
                    const tagId = selectedTagData.value;

                    SetPermissions(rowId, tagId);
                }

                function onRemoveTag(e, rowId) {
                    const removedTagData = e.detail.data;
                    const removedTagId = removedTagData.value;

                    SetPermissions(rowId, removedTagId);
                }
            }

            $(document).ready(function() {
                $('#permissoes').DataTable({
                    "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                        "<'table-responsive'tr>" +
                        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                    "oLanguage": {
                        "oPaginate": {
                            "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                            "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                        },
                        "sInfo": "Mostrando página _PAGE_ de _PAGES_",
                        "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                        "sSearchPlaceholder": "Pesquisar...",
                        "sLengthMenu": "Resultados :  _MENU_",
                    },
                    "stripeClasses": [],
                    "lengthMenu": [7, 10, 20, 50],
                    "pageLength": 10
                });

            });

            EnableTagify();
        </script>
    @endpush
@endsection
