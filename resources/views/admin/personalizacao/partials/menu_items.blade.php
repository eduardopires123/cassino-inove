@php
    if(!isset($Categoria)) {
        $Categoria = request()->route('id');
    }

    try {
        $ItemsMenu = App\Models\MenuItems::where('categoria', $Categoria)->orderBy('ordem', 'asc')->get();
        $CategoriaInfo = App\Models\MenuCategoria::find($Categoria);
    } catch (\Exception $e) {
        $error = $e->getMessage();
        $ItemsMenu = collect();
        $CategoriaInfo = null;
    }
@endphp

@if(isset($error))
    <div class="alert alert-danger">
        <h5>Erro ao carregar dados</h5>
        <p>{{ $error }}</p>
    </div>
@endif

<div class="mb-3 d-flex justify-content-between align-items-center p-3">
    <h4 id="headerTitle"></h4>
    <button type="button" class="btn btn-primary" id="btnToggleView" onclick="toggleView()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
        Adicionar novo item
    </button>
</div>

<!-- Container principal para alternar entre tabela e formulário -->
<div id="content-container">
    <!-- Tabela de itens -->
    <div id="tableView">
        <div class="table-responsive">
            <table id="menu_items" class="table table-striped dt-table-hover dataTable" style="width: 100%;">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Ícone</th>
                    <th>Link</th>
                    <th style="width: 80px;">Ordem</th>
                    <th>Ativo</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                @if($ItemsMenu && $ItemsMenu->count() > 0)
                    @foreach($ItemsMenu as $registro)
                        <tr data-id="{{$registro->id}}">
                            <td>
                                <input type="text" class="form-control" value="{{$registro->nome}}" onchange="AttMenuItems('{{$registro->id}}', 'nome', this.value)">
                            </td>
                            <td>
                                <div class="input-group">
                                        <span class="input-group-text">
                                            <div id="svg{{$registro->id}}" style="width: 24px !important; height: 24px !important; overflow: hidden;">
                                                {!! $registro->icone !!}
                                            </div>
                                        </span>
                                    <input type="text" class="form-control" value="{{$registro->icone}}" onchange="AttMenuItems('{{$registro->id}}', 'icone', this.value)">
                                </div>
                            </td>
                            <td style="width: 200px;">
                                <div style="width: 100%;">
                                    @php
                                        $games = App\Models\GamesApi::where('status', 1)->orderBy('name')->get();
                                    @endphp
                                    <select id="game_id" name="game_id" onchange="AttMenuItems('{{$registro->id}}', 'link', this.value)" autocomplete="off">
                                        <option value="">{{ $registro->link != '' ? $registro->link : 'Informe o jogo ou URL' }}</option>
                                        @foreach($games as $game)
                                            @php
                                                $numeros = preg_replace('/\D/', '', $registro->link);
                                            @endphp
                                            <option value="{{ $game->id }}" {{ ($game->id == $numeros) ? "selected" : "" }}>
                                                {{ $game->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td>
                                <input type="number" class="form-control" value="{{$registro->ordem}}" onchange="AttMenuItems('{{$registro->id}}', 'ordem', this.value)">
                            </td>
                            <td>
                                <div class="form-check form-switch form-check-inline form-switch-primary">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                           {{($registro->active == 1) ? "checked" : ""}}
                                           onchange="AttMenuItems('{{$registro->id}}', 'active', Number(this.checked))">
                                </div>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="javascript:void(0);" class="action-btn btn-delete bs-tooltip me-2" data-item-id="{{$registro->id}}" onclick="confirmDelete('{{$registro->id}}')" data-toggle="bs-tooltip" aria-label="Excluir Item" data-bs-original-title="Excluir Item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr id="empty-row">
                        <td colspan="6" class="text-center">Nenhum item encontrado para esta categoria</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Formulário de adição de novo item -->
    <div class="modal-body" id="formView" style="display: none;">
        <form id="newItemForm">
            <input type="hidden" id="categoriaId" value="{{$Categoria}}">
            <div class="mb-3">
                <label for="itemNome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="itemNome" required>
            </div>
            <div class="mb-3">
                <label for="itemIcone" class="form-label">Ícone (SVG)</label>
                <textarea class="form-control" id="itemIcone" rows="3" required onInput="updateSvgPreview(this.value)"></textarea>
                <div class="mt-2">
                    <label>Pré-visualização:</label>
                    <div id="svgPreview" style="width: 24px; height: 24px; margin-top: 5px;"></div>
                </div>
            </div>
            <div class="mb-3">
                <label for="itemLink" class="form-label">Link</label>
                @php
                    $games = App\Models\GamesApi::where('status', 1)->orderBy('name')->get();
                @endphp
                <select id="itemLink" name="itemLink" autocomplete="off">
                    <option value="">Informe o jogo ou URL</option>
                    @foreach($games as $game)
                        @php
                            $numeros = preg_replace('/\D/', '', $registro->link);
                        @endphp
                        <option value="{{ $game->id }}" {{ ($game->id == $numeros) ? "selected" : "" }}>
                            {{ $game->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="itemOrdem" class="form-label">Ordem</label>
                <input type="number" class="form-control" id="itemOrdem" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="toggleView()">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="addNewItem()">Salvar</button>
            </div>
        </form>
    </div>
</div>

<style>
    .ts-control {
        height: 48px;
        margin-top: -3px;
    }
</style>

<script type="text/javascript">
    function Altera(id, qual) {
        if (qual == 0) {
            document.getElementById('tipo' + id).style.width = '100px';
            document.getElementById('jogo' + id).style.display = 'none';
            document.getElementById('link' + id).style.display = 'block';
        } else if (qual == 1) {
            document.getElementById('tipo' + id).style.width = '100px';
            document.getElementById('jogo' + id).style.display = 'block';
            document.getElementById('link' + id).style.display = 'none';
        } else if (qual == -1) {
            document.getElementById('tipo' + id).style.width = '100%';
            document.getElementById('jogo' + id).style.display = 'none';
            document.getElementById('link' + id).style.display = 'none';
        }
    }

    // Função para alternar entre as visualizações
    function toggleView() {
        var tableView = document.getElementById('tableView');
        var formView = document.getElementById('formView');
        var btnToggle = document.getElementById('btnToggleView');

        if (tableView.style.display !== 'none') {
            // Mostrar formulário
            tableView.style.display = 'none';
            formView.style.display = 'block';
            btnToggle.style.display = 'none';

            // Limpar campos do formulário
            document.getElementById('itemNome').value = '';
            document.getElementById('itemIcone').value = '';
            document.getElementById('itemLink').value = '';
            document.getElementById('itemOrdem').value = '';
            document.getElementById('svgPreview').innerHTML = '';

            // Focar no primeiro campo
            document.getElementById('itemNome').focus();
        } else {
            // Mostrar tabela
            formView.style.display = 'none';
            tableView.style.display = 'block';
            btnToggle.style.display = 'inline-flex';
        }
    }

    // Função para pré-visualização do ícone SVG
    function initSvgPreview() {
        // Adicionando evento diretamente no campo
        document.getElementById('itemIcone').addEventListener('input', function() {
            document.getElementById('svgPreview').innerHTML = this.value;
        });
    }

    // Função para confirmar exclusão
    function confirmDelete(itemId) {
        ModalManager.showConfirmation(
            'Confirmar Exclusão',
            'Tem certeza que deseja excluir este item do menu?',
            function() { deleteMenuItem(itemId); }
        );
    }

    // Função para adicionar um novo item na tabela sem recarregar a página
    function addRowToTable(item) {
        // Verificar se existe a linha "nenhum item encontrado"
        var emptyRow = document.getElementById('empty-row');
        if (emptyRow) {
            emptyRow.remove();
        }

        var tbody = document.querySelector('#menu_items tbody');
        var newRow = document.createElement('tr');
        newRow.setAttribute('data-id', item.id);

        newRow.innerHTML = `
        <td>
            <input type="text" class="form-control" value="${item.nome}" onchange="AttMenuItems('${item.id}', 'nome', this.value)">
        </td>
        <td>
            <div class="input-group">
                <span class="input-group-text">
                    <div id="svg${item.id}" style="width: 24px !important; height: 24px !important; overflow: hidden;">
                        ${item.icone}
                    </div>
                </span>
                <input type="text" class="form-control" value="${item.icone}" onchange="AttMenuItems('${item.id}', 'icone', this.value)">
            </div>
        </td>
        <td>
            <input type="text" class="form-control" value="${item.link}" onchange="AttMenuItems('${item.id}', 'link', this.value)">
        </td>
        <td>
            <input type="number" class="form-control" value="${item.ordem}" onchange="AttMenuItems('${item.id}', 'ordem', this.value)">
        </td>
        <td>
            <div class="form-check form-switch form-check-inline form-switch-primary">
                <input class="form-check-input" type="checkbox" role="switch"
                    ${(item.active == 1) ? "checked" : ""}
                    onchange="AttMenuItems('${item.id}', 'active', Number(this.checked))">
            </div>
        </td>
        <td>
            <div class="action-btns">
                <a href="javascript:void(0);" class="action-btn btn-delete bs-tooltip me-2" data-item-id="${item.id}" onclick="confirmDelete('${item.id}')" data-toggle="bs-tooltip" aria-label="Excluir Item" data-bs-original-title="Excluir Item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                </a>
            </div>
        </td>
    `;

        tbody.appendChild(newRow);
        return newRow;
    }

    // Função para adicionar novo item
    function addNewItem() {
        const nome = document.getElementById('itemNome').value;
        const icone = document.getElementById('itemIcone').value;
        const ordem = document.getElementById('itemOrdem').value;
        let link = document.getElementById('itemLink').value;

        if (!nome || !icone || !link || !ordem) {
            ToastManager.error('Preencha todos os campos obrigatórios');
            return;
        }

        if (Number.isInteger(Number(link))) {
            link = "javascript: OpenGame('games', '"+link+"')";
        }

        const data = {
            nome: nome,
            icone: icone,
            link: link,
            ordem: ordem,
            categoria: document.getElementById('categoriaId').value,
            active: 1,  // Definir como ativo por padrão
            _token: '{{ csrf_token() }}'
        };

        // Usar o jQuery aqui porque a função $.ajax é mais conveniente
        $.ajax({
            url: '/admin/menu/add-item',
            type: 'POST',
            data: data,
            success: function(response) {
                if(response.success) {
                    ToastManager.success('Item adicionado com sucesso!');

                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    ToastManager.error(response.message || 'Erro ao adicionar item!');
                }
            },
            error: function(xhr) {
                console.error('Erro ao adicionar item:', xhr.responseText);
                ToastManager.error('Erro ao adicionar item! Verifique o console para mais detalhes.');
            }
        });
    }

    function AttMenuItems(id, campo, valor) {
        // Only convert to game link format if the field is 'link' and the value is an integer
        if (campo === 'link' && Number.isInteger(Number(valor))) {
            valor = "javascript: OpenGame('games', '"+valor+"')";
        }

        $.ajax({
            url: '/admin/menu/update-item',
            type: 'POST',
            data: {
                id: id,
                campo: campo,
                valor: valor,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    ToastManager.success('Item atualizado com sucesso!');
                    if(campo === 'icone') {
                        document.getElementById(`svg${id}`).innerHTML = valor;
                    }
                } else {
                    ToastManager.error(response.message || 'Erro ao atualizar item!');
                }
            },
            error: function(xhr) {
                console.error('Erro ao atualizar item:', xhr.responseText);
                ToastManager.error('Erro ao atualizar item! Verifique o console para mais detalhes.');
            }
        });
    }

    function deleteMenuItem(id) {
        $.ajax({
            url: '/admin/menu/delete-item',
            type: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    ToastManager.success('Item excluído com sucesso!');
                    var row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        // Adicionar efeito de fade out
                        row.style.transition = 'opacity 0.5s';
                        row.style.opacity = '0';

                        setTimeout(function() {
                            row.remove();
                            // Verificar se a tabela está vazia
                            var rows = document.querySelectorAll('#menu_items tbody tr');
                            if(rows.length === 0) {
                                document.querySelector('#menu_items tbody').innerHTML =
                                    '<tr id="empty-row"><td colspan="6" class="text-center">Nenhum item encontrado para esta categoria</td></tr>';
                            }
                        }, 500);
                    }
                } else {
                    ToastManager.error(response.message || 'Erro ao excluir item!');
                }
            },
            error: function(xhr) {
                console.error('Erro ao excluir item:', xhr.responseText);
                ToastManager.error('Erro ao excluir item! Verifique o console para mais detalhes.');
            }
        });
    }

    // Função para atualizar a pré-visualização do SVG
    function updateSvgPreview(svgContent) {
        document.getElementById('svgPreview').innerHTML = svgContent;
    }

    // Inicializar tudo quando o documento estiver pronto
    document.addEventListener('DOMContentLoaded', function() {
        // Não é mais necessário chamar initSvgPreview() pois agora usamos o evento inline
    });

    // Backup para garantir que os eventos sejam registrados mesmo em carregamento tardio
    window.onload = function() {
        // Verificando se o evento inline está funcionando
        var iconInput = document.getElementById('itemIcone');
        if (iconInput) {
            // Garantir que o evento está registrado
            iconInput.addEventListener('input', function() {
                updateSvgPreview(this.value);
            });
        }
    };
</script>
