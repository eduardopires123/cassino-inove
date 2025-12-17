    // Verificar jQuery
    document.addEventListener('DOMContentLoaded', function() {
       
        // Verificar se o jQuery está carregado
        if (typeof jQuery === 'undefined') {
            console.error('jQuery não foi carregado!');
            alert('Erro: jQuery não está carregado. Por favor, atualize a página.');
            return;
        }
      

        // Verificar botões
        

        // Adicionar eventos de clique diretamente
        $('#save-notification').on('click', function(e) {
            e.preventDefault();
            saveEditNotification();
            return false;
        });

        // Inicializar DataTable
        if ($.fn.DataTable.isDataTable('#notificacoes')) {
            $('#notificacoes').DataTable().destroy();
        }

        $('#notificacoes').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
                "<'table-responsive'tr>" +
                "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Buscar...",
                "sLengthMenu": "Resultados :  _MENU_",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                "sInfoFiltered": "(filtrado de _MAX_ registros)",
                "sZeroRecords": "Nenhum registro encontrado",
                "sEmptyTable": "Nenhum registro disponível",
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                }
            },
            "stripeClasses": [],
            "lengthMenu": [10, 25, 50, 100],
            "pageLength": 10,
            "order": [[4, 'desc']],
            "columnDefs": [
                { "type": "date", "targets": 4 }
            ]
        });

        // Marcar uma notificação como lida
        $(document).on('click', '.mark-read', function() {
            const id = $(this).data('id');
            markAsRead(id);
        });
    });

    // Inicializar eventos para os modais
    function initModalEvents() {
        // Remover eventos antigos para evitar duplicações
        $(document).off('click', '#save-notification');

        // Adicionar novos eventos
        $(document).on('click', '#save-notification', function() {
           
            saveEditNotification();
        });
    }

    // Função para editar notificação
    function editNotification(id) {

        // Mostrar toast de "processando"
        const processingToast = ToastManager.info('Carregando notificação, aguarde...');

        // Carregar dados da notificação para o modal
        fetch(`/admin/notificacoes/info/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Resposta não OK: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // Remover toast de processamento
            processingToast.remove();

            if (data && data.success) {
                // Preencher o modal com os dados da notificação
                $('#edit-notification-id').val(data.notification.id);
                $('#edit-notification-title').val(data.notification.title_pt_br || '');
                $('#edit-notification-title-en').val(data.notification.title_en || '');
                $('#edit-notification-title-es').val(data.notification.title_es || '');
                $('#edit-notification-content').val(data.notification.content_pt_br || '');
                $('#edit-notification-content-en').val(data.notification.content_en || '');
                $('#edit-notification-content-es').val(data.notification.content_es || '');
                $('#edit-notification-link').val(data.notification.link || '');
                $('#edit-notification-is-read').prop('checked', data.notification.is_read);

                // Abrir o modal
                var editModal = new bootstrap.Modal(document.getElementById('editNotificationModal'));
                editModal.show();
            } else {
                console.error('Erro nos dados recebidos:', data);
                ToastManager.error('Ocorreu um erro ao carregar os dados da notificação. Por favor, tente novamente.');
            }
        })
        .catch(error => {
            // Remover toast de processamento
            processingToast.remove();

            console.error('Erro na requisição:', error);
            ToastManager.error('Ocorreu um erro ao carregar os dados da notificação. Por favor, tente novamente.');
        });
    }

    // Função para excluir notificação
    function deleteNotification(id, title) {

        // Usar o ModalManager para confirmação
        ModalManager.showConfirmation(
            'Confirmar Exclusão',
            `Tem certeza que deseja excluir a notificação "${title}"?`,
            function() {
                // Callback de confirmação - executado quando o usuário confirma
                // Mostrar toast de "processando"
                const processingToast = ToastManager.info('Processando exclusão, aguarde...');

                // Obtendo o token CSRF
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Usando jQuery AJAX que geralmente funciona melhor com aplicações Laravel
                $.ajax({
                    url: `/admin/notificacoes/destroy/${id}`,
                    type: 'POST',
                    data: {
                        '_token': csrfToken
                    },
                    dataType: 'json',
                    success: function(data) {
                        // Remover toast de processamento
                        processingToast.remove();


                        if (data && data.success) {
                            // Remover a linha da tabela
                            $(`.mark-read[data-id="${id}"]`).closest('tr').remove();

                            // Mostrar toast de sucesso
                            ToastManager.success('Notificação excluída com sucesso!');

                            // Recarregar a página após um tempo
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            console.error('Erro ao excluir (resposta do servidor):', data);
                            ToastManager.error('Ocorreu um erro ao excluir a notificação. Por favor, tente novamente.');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Remover toast de processamento
                        processingToast.remove();

                        console.error('Status da resposta (delete):', xhr.status);
                        console.error('Erro na requisição:', status, error);
                        ToastManager.error(`Erro ao excluir: ${xhr.status} - ${error}. Por favor, tente novamente.`);
                    }
                });
            },
            function() {
                // Callback de cancelamento - executado quando o usuário cancela
            }
        );
    }

    // Função para marcar uma notificação como lida
    function markAsRead(id) {

        // Mostrar toast de "processando"
        const processingToast = ToastManager.info('Processando, aguarde...');

        const formData = new FormData();

        fetch(`/admin/notificacoes/mark-read/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Resposta não OK: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // Remover toast de processamento
            processingToast.remove();

            if (data && data.success) {
                const row = $(`.mark-read[data-id="${id}"]`).closest('tr');
                const indicator = $(`.mark-read[data-id="${id}"]`);

                // Atualizar classes
                indicator.removeClass('unread').addClass('read');
                row.addClass('is-read');

                // Atualizar o badge
                const badgeCell = row.find('td:first-child');
                badgeCell.find('.badge').removeClass('badge-light-secondary').addClass('badge-light-success').text('Lida');

                // Mostrar toast de sucesso
                ToastManager.success('Notificação marcada como lida com sucesso!');
            } else {
                console.error('Erro ao marcar como lida:', data);
                ToastManager.error('Ocorreu um erro ao marcar a notificação como lida. Por favor, tente novamente.');
            }
        })
        .catch(error => {
            // Remover toast de processamento
            processingToast.remove();

            console.error('Erro ao marcar como lida:', error);
            ToastManager.error('Ocorreu um erro ao marcar a notificação como lida. Por favor, tente novamente.');
        });
    }

    // Salvar edição da notificação
    function saveEditNotification() {
        try {
            const id = $('#edit-notification-id').val();

            const title_pt_br = $('#edit-notification-title').val();
            const title_en = $('#edit-notification-title-en').val();
            const title_es = $('#edit-notification-title-es').val();
            const content_pt_br = $('#edit-notification-content').val();
            const content_en = $('#edit-notification-content-en').val();
            const content_es = $('#edit-notification-content-es').val();
            const link = $('#edit-notification-link').val();
            const is_read = $('#edit-notification-is-read').prop('checked');

            if (!title_pt_br || !content_pt_br || !title_en || !content_en || !title_es || !content_es) {
                ToastManager.error('Por favor, preencha os campos obrigatórios em todos os idiomas.');
                return;
            }

            // Mostrar toast de "processando"
            const processingToast = ToastManager.info('Salvando alterações, aguarde...');

            // Obtendo o token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // URL para atualização
            const updateUrl = `/admin/notificacoes/update/${id}`;

            // Usando XMLHttpRequest
            const xhr = new XMLHttpRequest();
            xhr.open('POST', updateUrl, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Montando os dados para envio
            const params = new URLSearchParams();
            params.append('title_pt_br', title_pt_br);
            params.append('title_en', title_en);
            params.append('title_es', title_es);
            params.append('content_pt_br', content_pt_br);
            params.append('content_en', content_en);
            params.append('content_es', content_es);
            params.append('link', link);
            params.append('is_read', is_read ? 1 : 0);

            const urlEncodedData = params.toString();

            xhr.onload = function() {
                // Remover toast de processamento
                processingToast.remove();

                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data && data.success) {
                            // Fechar o modal
                            var editModal = bootstrap.Modal.getInstance(document.getElementById('editNotificationModal'));
                            if (editModal) {
                                editModal.hide();
                            }

                            // Mostrar mensagem de sucesso
                            ToastManager.success('Notificação atualizada com sucesso!');

                            // Recarregar a página
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            console.error('Erro nos dados:', data);
                            ToastManager.error(data.message || 'Ocorreu um erro ao atualizar a notificação. Por favor, tente novamente.');
                        }
                    } catch (e) {
                        console.error('Erro ao processar resposta:', e);
                        ToastManager.error('Erro ao processar resposta do servidor. Por favor, tente novamente.');
                    }
                } else {
                    console.error('Erro na requisição:', xhr.status, xhr.statusText);
                    ToastManager.error('Ocorreu um erro na requisição: ' + xhr.status + ' ' + xhr.statusText);
                }
            };

            xhr.onerror = function() {
                // Remover toast de processamento
                processingToast.remove();

                console.error('Erro de rede na requisição');
                ToastManager.error('Ocorreu um erro de rede. Por favor, verifique sua conexão.');
            };

            xhr.send(urlEncodedData);
        } catch (error) {
            console.error('Erro na função saveEditNotification:', error);
            ToastManager.error('Ocorreu um erro ao processar sua solicitação: ' + error.message);
        }

        return false; // Prevenir comportamento padrão
    }

    // Função para abrir o modal de envio de notificações para usuários
    function openEnviarNotificacaoModal() {
        // Limpar formulário
        document.getElementById('enviar-notificacao-form').reset();

        // Forçar checkbox de "Todos os usuários" para desmarcado
        $('#todos-usuarios').prop('checked', false);

        // Habilitar os campos de pesquisa/seleção de usuários
        $('#search-users').prop('disabled', false);

        // Limpar a lista de usuários selecionados
        $('#usuarios-selecionados').empty();
        $('#user-ids-selected').val('');

        // Limpar o dropdown
        $('#dropdown-usuarios').removeClass('show').empty();

        // Mostrar a seção de usuários específicos
        $('#usuarios-especificos-container').show();

        // Carregar usuários via AJAX
        carregarUsuarios();

        // Configurar evento para o campo de pesquisa
        configurarFiltroUsuarios();

        // Abrir modal
        var enviarModal = new bootstrap.Modal(document.getElementById('enviarNotificacaoModal'));
        enviarModal.show();

    }

    // Função para configurar o filtro de usuários
    function configurarFiltroUsuarios() {
        // Armazenar os usuários selecionados
        window.usuariosSelecionados = [];

        // Remover evento anterior se existir
        $('#search-users').off('input focus blur keydown');
        $('#todos-usuarios').off('change');

        // Adicionar evento de foco para mostrar o dropdown
        $('#search-users').on('focus', function() {
            mostrarDropdownUsuarios();
        });

        // Adicionar evento para quando o usuário clica fora do dropdown
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('#dropdown-usuarios').removeClass('show');
            }
        });

        // Adicionar evento de tecla para navegação no dropdown
        $('#search-users').on('keydown', function(e) {
            const $items = $('#dropdown-usuarios .dropdown-item');
            const $highlighted = $('#dropdown-usuarios .dropdown-item.highlighted');

            // Seta para baixo
            if (e.keyCode === 40) {
                e.preventDefault();

                if ($highlighted.length) {
                    const $next = $highlighted.next('.dropdown-item');
                    if ($next.length) {
                        $highlighted.removeClass('highlighted');
                        $next.addClass('highlighted');
                    }
                } else if ($items.length) {
                    $items.first().addClass('highlighted');
                }
            }

            // Seta para cima
            else if (e.keyCode === 38) {
                e.preventDefault();

                if ($highlighted.length) {
                    const $prev = $highlighted.prev('.dropdown-item');
                    if ($prev.length) {
                        $highlighted.removeClass('highlighted');
                        $prev.addClass('highlighted');
                    }
                } else if ($items.length) {
                    $items.last().addClass('highlighted');
                }
            }

            // Enter - selecionar o item destacado
            else if (e.keyCode === 13 && $highlighted.length) {
                e.preventDefault();
                selecionarUsuario($highlighted.data('id'), $highlighted.text());
                $('#search-users').val('').focus();
                $('#dropdown-usuarios .dropdown-item').removeClass('highlighted');
            }
        });

        // Adicionar evento de input para filtrar usuários enquanto digita
        $('#search-users').on('input', function() {
            const searchTerm = $(this).val().toLowerCase().trim();

            // Mostrar o dropdown
            mostrarDropdownUsuarios();

            // Atualizar o dropdown com os resultados filtrados
            atualizarDropdownUsuarios(searchTerm);
        });

        // Adicionar evento para o checkbox "Todos os usuários"
        $('#todos-usuarios').on('change', function() {
            if ($(this).is(':checked')) {
                // Se marcar "Todos os usuários", desabilitar a pesquisa e a seleção
                $('#search-users').val('').prop('disabled', true);
                $('#dropdown-usuarios').removeClass('show').empty();
                $('#usuarios-selecionados').empty();

                // Limpar a lista de usuários selecionados e o campo hidden
                window.usuariosSelecionados = [];
                $('#user-ids-selected').val('');

                // Atualizar contador
                $('#usuarios-contador').text('Envio para todos os usuários ativado');
                $('#usuarios-contador').removeClass('text-danger').addClass('text-muted');

            } else {
                // Se desmarcar, habilitar novamente
                $('#search-users').prop('disabled', false);

                // Resetar o dropdown e mostrar todos usuários
                $('#search-users').val('');

                // Limpar a lista de usuários selecionados
                window.usuariosSelecionados = [];
                $('#usuarios-selecionados').empty();
                $('#user-ids-selected').val('');

                // Atualizar o dropdown
                atualizarDropdownUsuarios('');

                // Atualizar contador
                if (window.todosUsuarios) {
                    $('#usuarios-contador').text(`Mostrando todos os usuários cadastrados (${window.todosUsuarios.length})`);
                    $('#usuarios-contador').removeClass('text-danger').addClass('text-muted');
                }

            }
        });

        // Adicionar evento para clicks nos itens do dropdown
        $(document).on('click', '#dropdown-usuarios .dropdown-item', function() {
            const userId = $(this).data('id');
            const userName = $(this).text();
            selecionarUsuario(userId, userName);
            $('#search-users').val('').focus();
        });
    }

    // Função para mostrar o dropdown de usuários
    function mostrarDropdownUsuarios() {
        $('#dropdown-usuarios').addClass('show');
    }

    // Função para atualizar o dropdown com usuários filtrados
    function atualizarDropdownUsuarios(searchTerm) {
        // Limpar o dropdown
        $('#dropdown-usuarios').empty();

        if (!window.todosUsuarios || window.todosUsuarios.length === 0) {
            $('#dropdown-usuarios').append('<a class="dropdown-item">Nenhum usuário disponível</a>');
            return;
        }

        // Se não tiver termo de pesquisa, mostrar todos os usuários
        let usuariosParaMostrar = window.todosUsuarios;

        // Se tiver termo de pesquisa, filtrar os usuários
        if (searchTerm) {
            usuariosParaMostrar = window.todosUsuarios.filter(user =>
                user.name.toLowerCase().includes(searchTerm) ||
                user.email.toLowerCase().includes(searchTerm) ||
                user.id.toString().includes(searchTerm)
            );
        }

        // Remover usuários já selecionados da lista
        const idsJaSelecionados = window.usuariosSelecionados.map(u => u.id);
        usuariosParaMostrar = usuariosParaMostrar.filter(user => !idsJaSelecionados.includes(user.id.toString()));

        // Atualizar contador com estilo apropriado
        const totalFiltrados = usuariosParaMostrar.length;
        const totalGeral = window.todosUsuarios.length;

        if (searchTerm && totalFiltrados === 0) {
            $('#usuarios-contador').text(`Nenhum usuário encontrado para "${searchTerm}"`);
            $('#usuarios-contador').removeClass('text-muted').addClass('text-danger');
            $('#dropdown-usuarios').append(`<a class="dropdown-item">Não foi encontrado nenhum usuário com "${searchTerm}"</a>`);
        } else {
            if (searchTerm) {
                $('#usuarios-contador').text(`Mostrando ${totalFiltrados} de ${totalGeral} usuários`);
            } else {
                $('#usuarios-contador').text(`Mostrando todos os usuários cadastrados (${totalGeral})`);
            }
            $('#usuarios-contador').removeClass('text-danger').addClass('text-muted');

            // Adicionar usuários filtrados ao dropdown
            usuariosParaMostrar.forEach(user => {
                const userLabel = `${user.name} (${user.email})`;
                const $item = $(`<a class="dropdown-item" data-id="${user.id}">${userLabel}</a>`);

                // Destacar o termo pesquisado no texto
                if (searchTerm) {
                    const text = $item.text();
                    const index = text.toLowerCase().indexOf(searchTerm.toLowerCase());

                    if (index >= 0) {
                        const before = text.substring(0, index);
                        const match = text.substring(index, index + searchTerm.length);
                        const after = text.substring(index + searchTerm.length);

                        $item.html(before + '<strong style="background-color: #4361ee33;">' + match + '</strong>' + after);
                    }
                }

                $('#dropdown-usuarios').append($item);
            });
        }
    }

    // Função para selecionar um usuário
    function selecionarUsuario(userId, userName) {
        // Verificar se o usuário já está selecionado
        if (window.usuariosSelecionados.some(u => u.id === userId)) {
            return;
        }

        // Adicionar o usuário à lista de selecionados
        window.usuariosSelecionados.push({
            id: userId,
            name: userName
        });

        // Extrair apenas o nome do usuário (sem o email)
        const nomeSemEmail = userName.split('(')[0].trim();

        // Criar badge simples para o usuário
        const badge = $(`<span class="badge badge-light-success mb-2 me-4" data-id="${userId}">${nomeSemEmail}</span>`);

        // Adicionar evento de clique para remover o badge
        badge.on('click', function() {
            removerUsuario(userId);
        });

        // Adicionar o badge à lista de usuários selecionados
        $('#usuarios-selecionados').append(badge);

        // Atualizar o campo hidden com os IDs selecionados
        atualizarCampoSelecionados();

        // Atualizar o dropdown para remover o usuário selecionado
        const searchTerm = $('#search-users').val().toLowerCase().trim();
        atualizarDropdownUsuarios(searchTerm);
    }

    // Função para remover um usuário da seleção
    function removerUsuario(userId) {
        // Remover o usuário da lista de selecionados
        window.usuariosSelecionados = window.usuariosSelecionados.filter(u => u.id !== userId);

        // Remover o badge do usuário
        $(`.badge[data-id="${userId}"]`).remove();

        // Atualizar o campo hidden com os IDs selecionados
        atualizarCampoSelecionados();

        // Atualizar o dropdown para adicionar o usuário removido de volta
        const searchTerm = $('#search-users').val().toLowerCase().trim();
        atualizarDropdownUsuarios(searchTerm);
    }

    // Função para atualizar o campo hidden com os IDs selecionados
    function atualizarCampoSelecionados() {
        const ids = window.usuariosSelecionados.map(u => u.id).join(',');
        $('#user-ids-selected').val(ids);
    }

    // Função para carregar usuários via AJAX
    function carregarUsuarios() {

        // Mostrar mensagem de carregamento
        $('#usuarios-contador').text('Carregando usuários...');

        // Mostrar toast de "processando"
        const processingToast = ToastManager.info('Carregando lista de usuários, aguarde...');

        fetch('/admin/notificacoes/get-users', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Resposta não OK: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // Remover toast de processamento
            processingToast.remove();

            if (data && data.success) {
                // Armazenar todos os usuários em uma variável global para uso na pesquisa
                window.todosUsuarios = data.users;

                // Inicializar a lista de usuários selecionados
                window.usuariosSelecionados = [];

                // Atualizar contador
                $('#usuarios-contador').text(`Mostrando todos os usuários cadastrados (${data.users.length})`);

                // Preencher o dropdown inicial
                atualizarDropdownUsuarios('');
            } else {
                console.error('Erro nos dados recebidos:', data);
                $('#usuarios-contador').text('Erro ao carregar usuários');
                ToastManager.error('Ocorreu um erro ao carregar a lista de usuários. Por favor, tente novamente.');
            }
        })
        .catch(error => {
            // Remover toast de processamento
            processingToast.remove();

            console.error('Erro na requisição:', error);
            $('#usuarios-contador').text('Erro ao carregar usuários');
            ToastManager.error('Ocorreu um erro ao carregar a lista de usuários. Por favor, tente novamente.');
        });
    }

    // Função para enviar notificação para usuários
    function enviarNotificacaoUsuarios() {
        
        // Verificar se o formulário está válido
        const form = document.getElementById('enviar-notificacao-form');
        
        // Validação básica
        const titlePtBr = document.getElementById('enviar-notification-title').value.trim();
        const contentPtBr = document.getElementById('enviar-notification-content').value.trim();
        const titleEn = document.getElementById('enviar-notification-title-en').value.trim();
        const contentEn = document.getElementById('enviar-notification-content-en').value.trim();
        const titleEs = document.getElementById('enviar-notification-title-es').value.trim();
        const contentEs = document.getElementById('enviar-notification-content-es').value.trim();
        
        if (!titlePtBr || !contentPtBr || !titleEn || !contentEn || !titleEs || !contentEs) {
            console.error('Campos obrigatórios não preenchidos');
            ToastManager.error('Preencha todos os campos obrigatórios em todos os idiomas.');
            return;
        }
        
        // Verificar se é para todos os usuários ou específicos
        const todosUsuarios = document.getElementById('todos-usuarios').checked;
        
        if (!todosUsuarios) {
            // Verificar se existem usuários selecionados
            const userIds = document.getElementById('user-ids-selected').value;
            if (!userIds) {
                console.error('Nenhum usuário selecionado');
                ToastManager.error('Selecione pelo menos um usuário para enviar a notificação.');
                return;
            }
        }
        
        // Mostrar toast de "processando"
        const processingToast = ToastManager.info('Enviando notificação, aguarde...');
        
        // Preparar os dados do formulário
        const formData = new FormData(form);
        
        // Enviar requisição
        fetch('/admin/notificacoes/enviar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Resposta não OK: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            // Remover toast de processamento
            processingToast.remove();
            
            if (data && data.success) {
                // Fechar o modal
                var modal = bootstrap.Modal.getInstance(document.getElementById('enviarNotificacaoModal'));
                if (modal) {
                    modal.hide();
                }
                
                // Mostrar toast de sucesso
                ToastManager.success('Notificação enviada com sucesso!');
                
                // Criar a nova linha para adicionar à tabela
                let newRow = createNotificationTableRow(data.notification, data.users);
                
                // Adicionar a nova linha à tabela
                const table = document.querySelector('#zero-config tbody');
                // Verificar se existe a linha de "nenhum registro encontrado"
                const emptyRow = table.querySelector('tr td[colspan]');
                if (emptyRow) {
                    // Se existir, remover esta linha
                    emptyRow.closest('tr').remove();
                }
                
                // Adicionar a nova linha no início da tabela
                table.insertAdjacentHTML('afterbegin', newRow);
                
                // Inicializar tooltips para a nova linha
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
                
                // Limpar o formulário
                form.reset();
                document.getElementById('usuarios-selecionados').innerHTML = '';
                document.getElementById('user-ids-selected').value = '';
            } else {
                console.error('Erro ao enviar notificação:', data);
                ToastManager.error(data.message || 'Ocorreu um erro ao enviar a notificação. Por favor, tente novamente.');
            }
        })
        .catch(error => {
            // Remover toast de processamento
            processingToast.remove();
            
            console.error('Erro ao enviar notificação:', error);
            ToastManager.error('Ocorreu um erro ao enviar a notificação. Por favor, tente novamente.');
        });
    }

    // Função auxiliar para criar uma nova linha de notificação na tabela
    function createNotificationTableRow(notification, users) {
        const statusBadge = '<span class="badge badge-light-secondary mb-2 me-4">Não Lida</span>';
        const conteudoLimitado = notification.content_pt_br.length > 30 
            ? notification.content_pt_br.substring(0, 30) + '...' 
            : notification.content_pt_br;
        
        // Determinar o formato de exibição de usuários
        let usuariosHtml = '';
        
        if (notification.todos_usuarios) {
            usuariosHtml = '<span class="badge badge-light-primary">Todos os usuários</span>';
        } else if (users && users.length > 0) {
            if (users.length <= 3) {
                // Mostrar até 3 usuários diretamente
                const userBadges = users.map(user => 
                    `<span class="badge badge-light-info">${escapeHtml(user.name)}</span>`
                );
                usuariosHtml = userBadges.join(' ');
            } else {
                // Mostrar contagem para mais de 3 usuários
                const userNames = users.map(user => escapeHtml(user.name)).join(', ');
                usuariosHtml = `<span class="badge badge-light-warning usuarios-tooltip" 
                    data-bs-toggle="tooltip" data-bs-placement="top" 
                    title="${escapeHtml(userNames)}">${users.length} usuários</span>`;
            }
        } else if (notification.user && notification.user.name) {
            // Caso individual
            usuariosHtml = `<span class="badge badge-light-info">${escapeHtml(notification.user.name)}</span>`;
        } else if (notification.users_list && notification.users_list.length > 0) {
            const usersList = notification.users_list;
            if (usersList.length <= 3) {
                // Mostrar até 3 usuários diretamente
                const userBadges = usersList.map(user => 
                    `<span class="badge badge-light-info">${escapeHtml(user.name)}</span>`
                );
                usuariosHtml = userBadges.join(' ');
            } else {
                // Mostrar contagem para mais de 3 usuários
                const userNames = usersList.map(user => escapeHtml(user.name)).join(', ');
                usuariosHtml = `<span class="badge badge-light-warning usuarios-tooltip" 
                    data-bs-toggle="tooltip" data-bs-placement="top" 
                    title="${escapeHtml(userNames)}">${usersList.length} usuários</span>`;
            }
        }
        
        // Construir a linha da tabela
        return `
        <tr role="row">
            <td>
                <span class="notification-indicator unread mark-read" 
                    data-id="${notification.id}" title="Marcar como lida"></span>
                ${statusBadge}
            </td>
            <td>${escapeHtml(notification.title_pt_br || 'Notificação')}</td>
            <td>${escapeHtml(conteudoLimitado)}</td>
            <td>${usuariosHtml}</td>
            <td>${notification.created_at || new Date().toLocaleString('pt-BR')}</td>
            <td>
                <a class="badge badge-light-primary text-start me-2 action-edit" href="javascript:void(0);" onclick="editNotification('${notification.id}');">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                </a>
                <a class="badge badge-light-danger text-start action-delete" href="javascript:void(0);" onclick="deleteNotification('${notification.id}', '${escapeHtml(notification.title_pt_br)}');">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </a>
            </td>
        </tr>
        `;
    }

    // Função auxiliar para escapar HTML
    function escapeHtml(text) {
        if (!text) return '';
        
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        
        return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
    }