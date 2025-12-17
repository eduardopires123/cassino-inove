@extends('layouts.app')
@section('content')
    <style>
        /* Animação para entrada de jogos */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .game-animation {
            opacity: 0;
            animation: fadeIn 0.2s ease-out forwards;
        }
    </style>
    <section data-v-0a4c896b="" class="spacing"  id="content-game">
        <header class="qMh00">
            <div class="Efcyy">
                <button class="_1ro3x" onclick="window.history.back()">
                <span class="nuxt-icon nuxt-icon--fill">
                    <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"
                            fill="currentColor"
                        ></path>
                    </svg>
                </span>
                </button>
                <div class="f22UR">{{ __('casino.all_casino_games') }}</div>
                <div class="hJNny">
                    <button id="filter-providers">
                    <span class="nuxt-icon nuxt-icon--fill">
                        <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                            <path d="M505 41L320 225.93V488c0 19.51-22 30.71-37.76 19.66l-80-56A24 24 0 0 1 192 432V226L7 41C-8 25.87 2.69 0 24 0h464c21.33 0 32 25.9 17 41z" fill="currentColor" opacity="0.4"></path>
                        </svg>
                    </span>
                    </button>
                    <button id="filter-search">
                    <span class="nuxt-icon nuxt-icon--fill">
                        <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M500.3 443.7l-119.7-119.7c-15.03 22.3-34.26 41.54-56.57 56.57l119.7 119.7c15.62 15.62 40.95 15.62 56.57 0C515.9 484.7 515.9 459.3 500.3 443.7z" fill="currentColor"></path>
                            <path
                                d="M207.1 0C93.12 0-.0002 93.13-.0002 208S93.12 416 207.1 416s208-93.13 208-208S322.9 0 207.1 0zM207.1 336c-70.58 0-128-57.42-128-128c0-70.58 57.42-128 128-128s128 57.42 128 128C335.1 278.6 278.6 336 207.1 336z"
                                fill="currentColor"
                                opacity="0.4"
                            ></path>
                        </svg>
                    </span>
                    </button>
                </div>
            </div>
            <div data-v-959816fa="" class="casino-filters src7V">
                <div data-v-959816fa="" class="casino-search" style="padding: 4px;">
                    <input data-v-959816fa="" class="_6z1L2" id="search-input" placeholder="{{ __('casino.search_game') }}" autocomplete="nope" onfocus="this.removeAttribute('readonly');" readonly aria-autocomplete="none">
                </div>
                <div data-v-959816fa="" class="casino-filters__buttons grid grid-cols-2 gap-4">
                    <div data-v-959816fa="" class="listBox-wrapper">
                        <button data-v-959816fa="" id="headlessui-listbox-button-nsiNM9WAguS_1" type="button" aria-haspopup="listbox" aria-expanded="false" data-headlessui-state="" class="select-btn provider-select-btn">
                            {{ __('casino.providers') }}: <strong data-v-959816fa="" id="selected-provider">{{ __('casino.all') }}</strong>
                        </button>
                        <ul data-v-959816fa="" id="headlessui-listbox-options-nsiNM9WAguS_2" aria-multiselectable="true" aria-labelledby="headlessui-listbox-button-nsiNM9WAguS_1" aria-orientation="vertical" role="listbox" tabindex="0" data-headlessui-state="" class="select-options no-scrollbar provider-options" style="display: none;">
                            <li data-v-959816fa="" id="headlessui-listbox-option-all" role="option" tabindex="-1" aria-selected="true" data-headlessui-state="" class="select-opt">
                                <label data-v-959816fa="" for="all-providers"><input data-v-959816fa="" id="all-providers" name="opt" type="checkbox" value="all" checked /><span data-v-959816fa="" class="ml-2">{{ __('casino.all') }}</span></label>
                            </li>
                        </ul>
                    </div>
                    <div data-v-959816fa="" class="listBox-wrapper">
                        <button data-v-959816fa="" id="headlessui-listbox-button-nsiNM9WAguS_3" type="button" aria-haspopup="listbox" aria-expanded="false" data-headlessui-state="" class="select-btn category-select-btn">
                            {{ __('casino.categories') }}: <strong data-v-959816fa="" id="selected-category">{{ __('casino.all') }}</strong>
                        </button>
                        <ul data-v-959816fa="" id="headlessui-listbox-options-nsiNM9WAguS_4" aria-multiselectable="true" aria-labelledby="headlessui-listbox-button-nsiNM9WAguS_3" aria-orientation="vertical" role="listbox" tabindex="0" data-headlessui-state="" class="select-options no-scrollbar category-options" style="display: none;">
                            <li data-v-959816fa="" id="headlessui-listbox-option-all-category" role="option" tabindex="-1" aria-selected="true" data-headlessui-state="" class="select-opt">
                                <label data-v-959816fa="" for="all-categories"><input data-v-959816fa="" id="all-categories" name="category-opt" type="checkbox" value="all" checked /><span data-v-959816fa="" class="ml-2">{{ __('casino.all') }}</span></label>
                            </li>
                            <!-- As categorias serão inseridas aqui via JavaScript -->
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <!---->
        <div data-v-0a4c896b="" class="games_content">
            <div data-v-0a4c896b="" class="nM44t" style="--d879e6ea: 12px; --45b10934: 6;">
                <!---->
                <div class="tXgOm" id="games-container">
                    @foreach($games as $game)
                        @if(isset($game->status) ? $game->status == 1 : true)
                            <a href="JavaScript: Void(0);" onclick="OpenGame('games', '{{ $game->id }}');" class="hZm-w s3HXA game-animation" data-game-id="{{ $game->id }}" style="animation-delay: {{ $loop->iteration * 50 }}ms">
                                <div class="u3Qxq">
                                    <div class="g-hw5">
                                        <img alt="{{ $game->name }}" class="vTFYb" src="{{ $game->image_url }}"/>
                                        <!---->
                                    </div>
                                    <div class="hzP6t"><span class="phlJe">{{ $game->name }}</span><span class="liQBm">{{ $game->provider }}</span></div>
                                    <section class="bBtlK">
                            <span class="Oe7Pi">
                                <span class="nuxt-icon nuxt-icon--fill">
                                    <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>{{ __('casino.play') }}</span>
                            </span>
                                    </section>
                                </div>
                            </a>
                        @endif
                    @endforeach
                    <!---->
                </div>
            </div>
        </div>
        <div data-v-14a18591="" data-v-0a4c896b="" class="pagination">
            <div data-v-14a18591="" class="w-[40%] lg:w-[16%] bg-texts/10 rounded-md h-1 mb-4">
                <div data-v-14a18591="" class="bg-primary h-1 rounded-md" id="progress-bar" style="width: {{ $total > 0 ? ($current / $total) * 100 : 0 }}%;"></div>
            </div>
            <span data-v-14a18591="" class="text-texts text-xs mb-4" id="showing-text">{{ __('casino.showing') }} {{ $current }} {{ __('casino.of') }} {{ $total }} {{ __('casino.games') }}</span>
            <button data-v-14a18591="" class="btn-more" id="load-more-btn" data-page="2"><span data-v-14a18591="">{{ __('casino.load_more') }}</span></button>
        </div>
    </section>
    <script>

        // TODOS OS JOGOS DE CASSINO
        document.addEventListener('DOMContentLoaded', function() {
            const gamesContainer = document.getElementById('games-container');
            const loadMoreBtn = document.getElementById('load-more-btn');
            const progressBar = document.getElementById('progress-bar');
            const showingText = document.getElementById('showing-text');
            const gridContainer = document.querySelector('.nM44t');
            const providerSelectBtn = document.querySelector('.provider-select-btn');
            const providerOptions = document.querySelector('.provider-options');
            const selectedProviderText = document.getElementById('selected-provider');
            const categorySelectBtn = document.querySelector('.category-select-btn');
            const categoryOptions = document.querySelector('.category-options');
            const selectedCategoryText = document.getElementById('selected-category');
            const searchInput = document.querySelector('.casino-search input');
            const totalGames = {{ $total }};
            let currentShowing = {{ $current }};
            let selectedProvider = 'all';
            let selectedCategory = 'all';

            // Função para ajustar o número de colunas com base no tamanho da tela
            function adjustGridColumns() {
                if (window.innerWidth < 768) {
                    // Mobile: 3 colunas
                    gridContainer.style.setProperty('--45b10934', '3');
                } else if (window.innerWidth < 1100) {
                    // Tablet: 4 colunas
                    gridContainer.style.setProperty('--45b10934', '4');
                } else {
                    // Desktop: 6 colunas
                    gridContainer.style.setProperty('--45b10934', '6');
                }
            }

            // Ajustar colunas na inicialização
            adjustGridColumns();

            // Ajustar colunas quando a janela for redimensionada
            window.addEventListener('resize', adjustGridColumns);

            // Função para registrar visualização ao clicar em um jogo
            function registrarVisualizacao(gameId, clickEvent) {
                // Se estiver clicando no botão de jogar, não impedir a navegação
                if (clickEvent.target.closest('.Oe7Pi')) {
                    return;
                }

                // Prevenir a navegação imediata para a página do jogo
                clickEvent.preventDefault();

                // Enviar solicitação AJAX para incrementar a visualização
                fetch(`/jogos/incrementar-visualizacao/${gameId}`)
                    .then(response => response.json())
                    .then(data => {

                        // Após registrar, redirecionar para a página do jogo
                        window.location.href = clickEvent.currentTarget.href;
                    })
                    .catch(error => {
                        console.error('Erro ao registrar visualização:', error);

                        // Em caso de erro, ainda redireciona para não afetar a experiência do usuário
                        window.location.href = clickEvent.currentTarget.href;
                    });
            }

            // Função para carregar os provedores
            function loadProviders() {
                // Usar o caminho relativo correto sem domínio/porta
                fetch('/provedores/listar')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Erro na requisição: ${response.status} ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Verificar se existem provedores
                        if (!data.providers || data.providers.length === 0) {
                            throw new Error('Nenhum provedor encontrado');
                        }

                        // Verificar quais provedores estão ativos
                        fetch('/provedores/verificar-ativos', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                providers: data.providers.map(provider => provider.name)
                            })
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`Erro ao verificar provedores: ${response.status} ${response.statusText}`);
                                }
                                return response.json();
                            })
                            .then(activeData => {
                                // Verificar se temos dados válidos na resposta
                                if (!activeData.active_providers) {
                                    console.error('Resposta inválida do servidor:', activeData);
                                    selectedProviderText.textContent = '{{ __("casino.data_error") }}';
                                    return;
                                }

                                // Filtrar apenas provedores ativos
                                const activeProviders = data.providers.filter(provider =>
                                    activeData.active_providers.includes(provider.name)
                                );

                                // Se não houver provedores ativos, exiba uma mensagem
                                if (activeProviders.length === 0) {
                                    selectedProviderText.textContent = '{{ __("casino.none_active") }}';
                                    return;
                                }

                                // Adicionar provedores ativos ao dropdown
                                activeProviders.forEach(provider => {
                                    const providerElement = `
                            <li data-v-959816fa="" id="headlessui-listbox-option-${provider.id}" role="option" tabindex="-1" aria-selected="false" data-headlessui-state="" class="select-opt">
                                <label data-v-959816fa="" for="${provider.id}">
                                    <input data-v-959816fa="" id="${provider.id}" name="opt" type="checkbox" value="${provider.name}" />
                                    <span data-v-959816fa="" class="ml-2">${provider.name}</span>
                                    <span data-v-959816fa="" class="badge">${provider.games_count}</span>
                                </label>
                            </li>
                        `;
                                    providerOptions.insertAdjacentHTML('beforeend', providerElement);
                                });

                                // Atualizar texto do botão
                                selectedProviderText.textContent = '{{ __("casino.all") }}';

                                // Adicionar eventos aos checkboxes dos provedores
                                const providerCheckboxes = document.querySelectorAll('.provider-options input[type="checkbox"]');
                                providerCheckboxes.forEach(checkbox => {
                                    checkbox.addEventListener('change', function() {
                                        if (this.value === 'all') {
                                            // Se "Todos" for selecionado, desmarque os outros
                                            providerCheckboxes.forEach(cb => {
                                                if (cb.value !== 'all') {
                                                    cb.checked = false;
                                                }
                                            });
                                            selectedProvider = 'all';
                                            selectedProviderText.textContent = '{{ __("casino.all") }}';
                                        } else {
                                            // Se um provedor específico for selecionado, desmarque "Todos"
                                            document.getElementById('all-providers').checked = false;

                                            // Verificar se há apenas um selecionado
                                            const checkedProviders = [...providerCheckboxes].filter(cb => cb.checked && cb.value !== 'all');
                                            if (checkedProviders.length === 1) {
                                                selectedProvider = checkedProviders[0].value;
                                                selectedProviderText.textContent = selectedProvider;
                                            } else if (checkedProviders.length > 1) {
                                                selectedProvider = 'multiple';
                                                selectedProviderText.textContent = `${checkedProviders.length} {{ __("casino.selected") }}`;
                                            } else {
                                                // Se nenhum estiver selecionado, volte para "Todos"
                                                document.getElementById('all-providers').checked = true;
                                                selectedProvider = 'all';
                                                selectedProviderText.textContent = '{{ __("casino.all") }}';
                                            }
                                        }

                                        // Recarregar jogos com o filtro
                                        resetAndLoadGames();
                                    });
                                });
                            })
                            .catch(error => {
                                console.error('Erro ao verificar provedores ativos:', error);
                                selectedProviderText.textContent = '{{ __("casino.error") }}';
                                // Tentar carregar todos os provedores como fallback
                                loadAllProviders(data.providers);
                            });
                    })
                    .catch(error => {
                        console.error('Erro ao carregar provedores:', error);
                        // Exibir mensagem de erro ao usuário
                        selectedProviderText.textContent = '{{ __("casino.unavailable") }}';
                    });
            }

            // Função de fallback para carregar todos os provedores em caso de erro
            function loadAllProviders(providers) {
                if (!providers || providers.length === 0) {
                    return;
                }

                providers.forEach(provider => {
                    const providerElement = `
                <li data-v-959816fa="" id="headlessui-listbox-option-${provider.id}" role="option" tabindex="-1" aria-selected="false" data-headlessui-state="" class="select-opt">
                    <label data-v-959816fa="" for="${provider.id}">
                        <input data-v-959816fa="" id="${provider.id}" name="opt" type="checkbox" value="${provider.name}" />
                        <span data-v-959816fa="" class="ml-2">${provider.name}</span>
                        <span data-v-959816fa="" class="badge">${provider.games_count}</span>
                    </label>
                </li>
            `;
                    providerOptions.insertAdjacentHTML('beforeend', providerElement);
                });

                // Adicionar eventos aos checkboxes dos provedores
                const providerCheckboxes = document.querySelectorAll('.provider-options input[type="checkbox"]');
                providerCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        // Código existente para lidar com mudanças nos checkboxes
                        // ...
                    });
                });
            }

            // Função para carregar categorias
            function loadCategories() {
                fetch('/categorias/listar')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Erro na requisição: ${response.status} ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Adicionar categorias ao dropdown
                        data.categories.forEach(category => {
                            const categoryElement = `
                        <li data-v-959816fa="" id="headlessui-listbox-option-${category.id}" role="option" tabindex="-1" aria-selected="false" data-headlessui-state="" class="select-opt">
                            <label data-v-959816fa="" for="${category.id}">
                                <input data-v-959816fa="" id="${category.id}" name="category-opt" type="checkbox" value="${category.name}" />
                                <span data-v-959816fa="" class="ml-2">${category.name}</span>
                                <span data-v-959816fa="" class="badge">${category.games_count}</span>
                            </label>
                        </li>
                    `;
                            categoryOptions.insertAdjacentHTML('beforeend', categoryElement);
                        });

                        // Adicionar eventos aos checkboxes das categorias
                        const categoryCheckboxes = document.querySelectorAll('.category-options input[type="checkbox"]');
                        categoryCheckboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', function() {
                                if (this.value === 'all') {
                                    // Se "Todos" for selecionado, desmarque os outros
                                    categoryCheckboxes.forEach(cb => {
                                        if (cb.value !== 'all') {
                                            cb.checked = false;
                                        }
                                    });
                                    selectedCategory = 'all';
                                    selectedCategoryText.textContent = '{{ __("casino.all") }}';
                                } else {
                                    // Se uma categoria específica for selecionada, desmarque "Todos"
                                    document.getElementById('all-categories').checked = false;

                                    // Verificar se há apenas uma selecionada
                                    const checkedCategories = [...categoryCheckboxes].filter(cb => cb.checked && cb.value !== 'all');
                                    if (checkedCategories.length === 1) {
                                        selectedCategory = checkedCategories[0].value;
                                        selectedCategoryText.textContent = selectedCategory;
                                    } else if (checkedCategories.length > 1) {
                                        selectedCategory = 'multiple';
                                        selectedCategoryText.textContent = `${checkedCategories.length} {{ __("casino.selected") }}`;
                                    } else {
                                        // Se nenhuma estiver selecionada, volte para "Todos"
                                        document.getElementById('all-categories').checked = true;
                                        selectedCategory = 'all';
                                        selectedCategoryText.textContent = '{{ __("casino.all") }}';
                                    }
                                }

                                // Recarregar jogos com o filtro
                                resetAndLoadGames();
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Erro ao carregar categorias:', error);
                        // Exibir mensagem de erro ao usuário
                        selectedCategoryText.textContent = '{{ __("casino.unavailable") }}';
                    });
            }

            // Função para resetar e carregar jogos com o filtro atual
            function resetAndLoadGames() {
                // Limpar jogos atuais
                gamesContainer.innerHTML = '';

                // Resetar contadores
                currentShowing = 0;

                // Carregar jogos com a página 1
                loadMoreBtn.setAttribute('data-page', '1');
                loadMoreBtn.click();
            }

            // Mostrar/esconder o dropdown de provedores
            providerSelectBtn.addEventListener('click', function() {
                const isExpanded = providerOptions.style.display !== 'none';
                providerOptions.style.display = isExpanded ? 'none' : 'block';
                providerSelectBtn.setAttribute('aria-expanded', !isExpanded);
            });

            // Fechar dropdown quando clicar fora
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.listBox-wrapper')) {
                    providerOptions.style.display = 'none';
                    providerSelectBtn.setAttribute('aria-expanded', 'false');
                }
            });

            // Mostrar/esconder o dropdown de categorias
            categorySelectBtn.addEventListener('click', function() {
                const isExpanded = categoryOptions.style.display !== 'none';
                categoryOptions.style.display = isExpanded ? 'none' : 'block';
                categorySelectBtn.setAttribute('aria-expanded', !isExpanded);
            });

            // Fechar dropdown quando clicar fora
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.listBox-wrapper')) {
                    categoryOptions.style.display = 'none';
                    categorySelectBtn.setAttribute('aria-expanded', 'false');
                }
            });

            // Função unificada para gerenciar a pesquisa
            function setupSearchFunctionality() {
                if (!searchInput) return;

                let searchTimer;

                // Função para processar a pesquisa
                function processSearch() {
                    const searchTerm = searchInput.value.trim();

                    // Limpar jogos e recarregar com o termo atual
                    gamesContainer.innerHTML = '';
                    currentShowing = 0;
                    loadMoreBtn.setAttribute('data-page', '1');

                    // Remover qualquer mensagem de erro anterior
                    const errorMessage = gamesContainer.querySelector('.error-message');
                    if (errorMessage) {
                        errorMessage.remove();
                    }

                    // Restaurar o botão "Carregar mais"
                    loadMoreBtn.style.display = 'block';
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.innerHTML = '<span>{{ __("casino.load_more") }}</span>';

                    // Carregar jogos com o termo de pesquisa atual
                    loadMoreBtn.click();
                }

                // Manipulador para eventos de entrada de texto (typing e apagando)
                searchInput.addEventListener('input', function() {
                    // Limpar o timer anterior
                    if (searchTimer) {
                        clearTimeout(searchTimer);
                    }

                    // Definir novo timer
                    searchTimer = setTimeout(processSearch, 500);
                });
            }

            // Inicializar funcionalidade de pesquisa
            setupSearchFunctionality();

            // Função para carregar mais jogos (melhorada)
            loadMoreBtn.addEventListener('click', function() {
                const page = parseInt(this.getAttribute('data-page'));

                // Adicionar indicador de carregamento
                loadMoreBtn.disabled = true;
                loadMoreBtn.innerHTML = '<span>{{ __("casino.loading") }}</span>';

                // Usar o caminho relativo correto sem domínio/porta
                let url = `/jogos/carregar-mais?page=${page}&per_page=24`;

                // Adicionar filtro de provedor, se necessário
                if (selectedProvider !== 'all') {
                    if (selectedProvider === 'multiple') {
                        // Coletar todos os provedores selecionados
                        const checkedProviders = [...document.querySelectorAll('.provider-options input[type="checkbox"]:checked')]
                            .filter(cb => cb.value !== 'all')
                            .map(cb => cb.value);

                        url += `&providers=${encodeURIComponent(JSON.stringify(checkedProviders))}`;
                    } else {
                        url += `&provider=${encodeURIComponent(selectedProvider)}`;
                    }
                }

                // Adicionar filtro de categoria, se necessário
                if (selectedCategory !== 'all') {
                    if (selectedCategory === 'multiple') {
                        // Coletar todas as categorias selecionadas
                        const checkedCategories = [...document.querySelectorAll('.category-options input[type="checkbox"]:checked')]
                            .filter(cb => cb.value !== 'all')
                            .map(cb => cb.value);

                        url += `&categories=${encodeURIComponent(JSON.stringify(checkedCategories))}`;
                    } else {
                        url += `&category=${encodeURIComponent(selectedCategory)}`;
                    }
                }

                // Adicionar termo de pesquisa, se existir
                const searchTerm = searchInput ? searchInput.value.trim() : '';
                if (searchTerm) {
                    url += `&search=${encodeURIComponent(searchTerm)}`;
                }

                // Adicionar parâmetro de ordenação
                url += '&sort=views';

                // Fazer a requisição AJAX
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Erro na requisição: ${response.status} ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(data => {

                        if (!data.games || data.games.length === 0) {
                            const noGamesMessage = `
                        <div class="alert alert-warning"><span>{{ __("casino.no_results") }}</span></div>
                    `;
                            // Inserir o alerta ANTES da div games-container, não dentro dela
                            gamesContainer.insertAdjacentHTML('beforebegin', noGamesMessage);

                            loadMoreBtn.style.display = 'none';
                            return;
                        }

                        // Limpar qualquer mensagem de erro anterior
                        const previousAlerts = document.querySelectorAll('.alert.alert-warning');
                        previousAlerts.forEach(alert => alert.remove());

                        // Adicionar novos jogos ao container com animação
                        data.games.forEach((game, index) => {
                            if (game.status === 1 || game.status === undefined) {
                                const gameElement = `
                            <a href="JavaScript: Void(0);" onclick="OpenGame('games', '${game.id}');" class="hZm-w s3HXA game-animation" data-game-id="${game.id}" style="animation-delay: ${index * 50}ms">
                                <div class="u3Qxq">
                                    <div class="g-hw5">
                                        <img
                                            alt="${game.name}"
                                            class="vTFYb"
                                            src="${game.image_url}"
                                        />
                                    </div>
                                    <div class="hzP6t"><span class="phlJe">${game.name}</span><span class="liQBm">${game.provider}</span></div>
                                    <section class="bBtlK">
                                        <span class="Oe7Pi">
                                            <span class="nuxt-icon nuxt-icon--fill">
                                                <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" fill="currentColor"></path>
                                                </svg>
                                            </span>
                                            <span>{{ __("casino.play") }}</span>
                                        </span>
                                    </section>
                                </div>
                            </a>
                        `;
                                gamesContainer.insertAdjacentHTML('beforeend', gameElement);
                            }
                        });

                        // Adicionar eventos para incrementar visualizações
                        document.querySelectorAll('#games-container .hZm-w.s3HXA[data-game-id]').forEach(gameLink => {
                            if (!gameLink.hasEventListener) {
                                gameLink.addEventListener('click', function(e) {
                                    const gameId = this.getAttribute('data-game-id');
                                    registrarVisualizacao(gameId, e);
                                });
                                gameLink.hasEventListener = true;
                            }
                        });

                        // Atualizar página atual
                        loadMoreBtn.setAttribute('data-page', (data.page + 1).toString());

                        // Atualizar contadores
                        currentShowing += data.games.length;

                        let teste = 0;
                        if (currentShowing > data.total) {
                            teste = currentShowing;
                        }else{
                            teste = data.total;
                        }

                        showingText.textContent = `{{ __("casino.showing") }} ${currentShowing} {{ __("casino.of") }} ${teste} {{ __("casino.games") }}`;

                        // Atualizar barra de progresso
                        const progressPercentage = (currentShowing / data.total) * 100;
                        progressBar.style.width = `${progressPercentage}%`;

                        // Esconder o botão se todos os jogos foram carregados
                        if (currentShowing >= data.total) {
                            loadMoreBtn.style.display = 'none';
                        } else {
                            loadMoreBtn.style.display = 'block';
                            loadMoreBtn.disabled = false;
                            loadMoreBtn.innerHTML = '<span>{{ __("casino.load_more_games") }}</span>';
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao carregar mais jogos:', error);

                        // Recuperar de erros - reativar o botão
                        loadMoreBtn.disabled = false;
                        loadMoreBtn.innerHTML = '<span>{{ __("casino.try_again") }}</span>';

                        // Mostrar mensagem de erro ao usuário
                        if (!gamesContainer.querySelector('.error-message')) {
                            const errorElement = `
                        <div class="error-message p-4 text-center">
                            <p>{{ __("casino.error_loading") }}</p>
                        </div>
                    `;
                            gamesContainer.insertAdjacentHTML('beforeend', errorElement);
                        }
                    });
            });

            // Função para resetar todos os filtros e carregar jogos novamente
            function resetarFiltros() {
                // Redefinir filtro de provedor para "Todos"
                document.getElementById('all-providers').checked = true;
                const providerCheckboxes = document.querySelectorAll('.provider-options input[type="checkbox"]');
                providerCheckboxes.forEach(cb => {
                    if (cb.value !== 'all') {
                        cb.checked = false;
                    }
                });
                selectedProvider = 'all';
                selectedProviderText.textContent = '{{ __("casino.all") }}';

                // Redefinir filtro de categoria para "Todos"
                document.getElementById('all-categories').checked = true;
                const categoryCheckboxes = document.querySelectorAll('.category-options input[type="checkbox"]');
                categoryCheckboxes.forEach(cb => {
                    if (cb.value !== 'all') {
                        cb.checked = false;
                    }
                });
                selectedCategory = 'all';
                selectedCategoryText.textContent = '{{ __("casino.all") }}';

                // Limpar campo de busca
                if (searchInput) searchInput.value = '';

                // Resetar e carregar jogos
                resetAndLoadGames();
            }

            // Carregar provedores e categorias na inicialização
            loadProviders();
            loadCategories();
        });
    </script>
@endsection
