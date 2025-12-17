@extends('layouts.app')

@section('title', 'Raspadinhas')

@section('content')
<style>
    /* Animação para entrada de raspadinhas */
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

    .raspadinha-animation {
        opacity: 0;
        animation: fadeIn 0.2s ease-out forwards;
    }
</style>

<section data-v-0a4c896b="" class="spacing" id="content-raspadinha">
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
            <div class="f22UR">🎰 Raspadinhas</div>
            <div class="hJNny">
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
                <button id="refresh-balance">
                    <span class="nuxt-icon nuxt-icon--fill">
                        <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M463.5 224H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1c-87.5 87.5-87.5 229.3 0 316.8s229.3 87.5 316.8 0c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0c-62.5 62.5-163.8 62.5-226.3 0s-62.5-163.8 0-226.3c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5z" fill="currentColor"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
        <div data-v-959816fa="" class="casino-filters src7V">
            <div data-v-959816fa="" class="casino-search" style="padding: 4px;">
                <input data-v-959816fa="" class="_6z1L2" id="search-input" placeholder="Buscar raspadinha..." autocomplete="nope" onfocus="this.removeAttribute('readonly');" readonly aria-autocomplete="none">
            </div>
            <div data-v-959816fa="" class="casino-filters__buttons grid grid-cols-2 gap-4">
                <div data-v-959816fa="" class="listBox-wrapper">
                    <button data-v-959816fa="" id="headlessui-listbox-button-status" type="button" aria-haspopup="listbox" aria-expanded="false" data-headlessui-state="" class="select-btn status-select-btn">
                        Status: <strong data-v-959816fa="" id="selected-status">Todas</strong>
                    </button>
                    <ul data-v-959816fa="" id="headlessui-listbox-options-status" aria-multiselectable="true" aria-labelledby="headlessui-listbox-button-status" aria-orientation="vertical" role="listbox" tabindex="0" data-headlessui-state="" class="select-options no-scrollbar status-options" style="display: none;">
                        <li data-v-959816fa="" id="headlessui-listbox-option-all-status" role="option" tabindex="-1" aria-selected="true" data-headlessui-state="" class="select-opt">
                            <label data-v-959816fa="" for="all-status"><input data-v-959816fa="" id="all-status" name="status-opt" type="checkbox" value="all" checked /><span data-v-959816fa="" class="ml-2">Todas</span></label>
                        </li>
                        <li data-v-959816fa="" id="headlessui-listbox-option-active" role="option" tabindex="-1" aria-selected="false" data-headlessui-state="" class="select-opt">
                            <label data-v-959816fa="" for="active-status"><input data-v-959816fa="" id="active-status" name="status-opt" type="checkbox" value="active" /><span data-v-959816fa="" class="ml-2">Ativas</span></label>
                        </li>
                        <li data-v-959816fa="" id="headlessui-listbox-option-inactive" role="option" tabindex="-1" aria-selected="false" data-headlessui-state="" class="select-opt">
                            <label data-v-959816fa="" for="inactive-status"><input data-v-959816fa="" id="inactive-status" name="status-opt" type="checkbox" value="inactive" /><span data-v-959816fa="" class="ml-2">Inativas</span></label>
                        </li>
                    </ul>
                </div>
                <div data-v-959816fa="" class="listBox-wrapper">
                    <button data-v-959816fa="" id="headlessui-listbox-button-price" type="button" aria-haspopup="listbox" aria-expanded="false" data-headlessui-state="" class="select-btn price-select-btn">
                        Preço: <strong data-v-959816fa="" id="selected-price">Todos</strong>
                    </button>
                    <ul data-v-959816fa="" id="headlessui-listbox-options-price" aria-multiselectable="true" aria-labelledby="headlessui-listbox-button-price" aria-orientation="vertical" role="listbox" tabindex="0" data-headlessui-state="" class="select-options no-scrollbar price-options" style="display: none;">
                        <li data-v-959816fa="" id="headlessui-listbox-option-all-price" role="option" tabindex="-1" aria-selected="true" data-headlessui-state="" class="select-opt">
                            <label data-v-959816fa="" for="all-price"><input data-v-959816fa="" id="all-price" name="price-opt" type="checkbox" value="all" checked /><span data-v-959816fa="" class="ml-2">Todos</span></label>
                        </li>
                        <li data-v-959816fa="" id="headlessui-listbox-option-low" role="option" tabindex="-1" aria-selected="false" data-headlessui-state="" class="select-opt">
                            <label data-v-959816fa="" for="low-price"><input data-v-959816fa="" id="low-price" name="price-opt" type="checkbox" value="low" /><span data-v-959816fa="" class="ml-2">Até R$ 5,00</span></label>
                        </li>
                        <li data-v-959816fa="" id="headlessui-listbox-option-medium" role="option" tabindex="-1" aria-selected="false" data-headlessui-state="" class="select-opt">
                            <label data-v-959816fa="" for="medium-price"><input data-v-959816fa="" id="medium-price" name="price-opt" type="checkbox" value="medium" /><span data-v-959816fa="" class="ml-2">R$ 5,01 - R$ 20,00</span></label>
                        </li>
                        <li data-v-959816fa="" id="headlessui-listbox-option-high" role="option" tabindex="-1" aria-selected="false" data-headlessui-state="" class="select-opt">
                            <label data-v-959816fa="" for="high-price"><input data-v-959816fa="" id="high-price" name="price-opt" type="checkbox" value="high" /><span data-v-959816fa="" class="ml-2">Acima de R$ 20,00</span></label>
                        </li>
                    </ul>
                </div>
            </div>
        </div> 
    </header>

    <div data-v-0a4c896b="" class="games_content">
        <div data-v-0a4c896b="" class="nM44t" style="--d879e6ea: 12px; --45b10934: 6;">
            <div class="tXgOm" id="raspadinhas-container">
                @forelse($raspadinhas as $raspadinha)
                <a href="JavaScript: Void(0);" onclick="openRaspadinha({{ $raspadinha->id }})" class="hZm-w s3HXA raspadinha-animation" data-raspadinha-id="{{ $raspadinha->id }}" style="animation-delay: {{ $loop->iteration * 50 }}ms">
                    <div class="u3Qxq">
                        <div class="g-hw5">
                            <img alt="{{ $raspadinha->name }}" class="vTFYb" src="{{ asset('img/raspadinha/default.png') }}"/>
                            <div class="raspadinha-overlay">
                                <div class="price-badge normal">R$ {{ number_format($raspadinha->price, 2, ',', '.') }}</div>
                                <div class="price-badge turbo">⚡ R$ {{ number_format($raspadinha->turbo_price, 2, ',', '.') }}</div>
                                @php
                                    $stats = $raspadinha->getProbabilityStats();
                                @endphp
                                <div class="probability-badge">
                                    {{ number_format($stats['win_chance'], 1) }}% ganho
                                </div>
                            </div>
                        </div>
                        <div class="hzP6t">
                            <span class="phlJe">{{ $raspadinha->name }}</span>
                            <span class="liQBm">
                                {{ $raspadinha->description ?? 'Raspe e ganhe!' }}
                                <br><small class="prize-types">
                                    @php
                                        $prizeTypes = $raspadinha->items()->active()
                                            ->selectRaw('DISTINCT premio_type')
                                            ->pluck('premio_type')
                                            ->map(function($type) {
                                                return match($type ?? 'saldo_real') {
                                                    'saldo_real' => '💰',
                                                    'saldo_bonus' => '🎁',
                                                    'rodadas_gratis' => '🎰',
                                                    'produto' => '📱',
                                                    default => '💰'
                                                };
                                            })
                                            ->join(' ');
                                    @endphp
                                    Prêmios: {{ $prizeTypes }}
                                </small>
                            </span>
                        </div>
                        <section class="bBtlK">
                            <span class="Oe7Pi">
                                <span class="nuxt-icon nuxt-icon--fill">
                                    <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <span>Jogar</span>
                            </span>
                        </section>
                    </div>
                </a>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-ticket-alt fa-5x text-muted mb-4"></i>
                        <h3>Nenhuma raspadinha disponível</h3>
                        <p class="text-muted">As raspadinhas estão sendo preparadas. Volte em breve!</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Paginação -->
    <div data-v-14a18591="" data-v-0a4c896b="" class="pagination">
        <div data-v-14a18591="" class="w-[40%] lg:w-[16%] bg-texts/10 rounded-md h-1 mb-4">
            <div data-v-14a18591="" class="bg-primary h-1 rounded-md" id="progress-bar" style="width: {{ $total > 0 ? ($current / $total) * 100 : 0 }}%;"></div>
        </div>
        <span data-v-14a18591="" class="text-texts text-xs mb-4" id="showing-text">Mostrando {{ $current }} de {{ $total }} raspadinhas</span>
        <button data-v-14a18591="" class="btn-more" id="load-more-btn" data-page="2"><span data-v-14a18591="">Carregar mais</span></button>
    </div>

    <!-- Ganhadores Recentes -->
    @if($recentWinners->count() > 0)
    <div data-v-14a18591="" data-v-0a4c896b="" class="pagination">
        <div data-v-14a18591="" class="w-full bg-texts/10 rounded-md h-1 mb-4">
            <div data-v-14a18591="" class="bg-primary h-1 rounded-md" style="width: 100%;"></div>
        </div>
        <span data-v-14a18591="" class="text-texts text-xs mb-4">🏆 Ganhadores Recentes</span>
        <div class="winners-carousel">
            @foreach($recentWinners->take(5) as $winner)
            <div class="winner-item">
                <span class="winner-name">{{ Str::mask($winner->user->name, '*', 2, -2) }}</span>
                <span class="winner-prize">R$ {{ number_format($winner->amount_won, 2, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</section>

<!-- Estilos específicos da raspadinha -->
<style>
.raspadinha-overlay {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.price-badge {
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: bold;
}

.price-badge.normal {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.price-badge.turbo {
    background: linear-gradient(45deg, #ff9800, #e65100);
}

.probability-badge {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 9px;
    font-weight: bold;
    margin-top: 2px;
}

.prize-types {
    color: #666;
    font-size: 11px;
    margin-top: 4px;
    display: block;
}

.winners-carousel {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.winner-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(40, 167, 69, 0.1);
    padding: 8px 12px;
    border-radius: 6px;
    border-left: 3px solid #28a745;
}

.winner-name {
    font-size: 12px;
    color: #333;
}

.winner-prize {
    font-size: 11px;
    font-weight: bold;
    color: #28a745;
}

/* Ajuste responsivo para raspadinhas */
@media (max-width: 768px) {
    .nM44t {
        --45b10934: 3 !important;
    }
}

@media (min-width: 769px) and (max-width: 1100px) {
    .nM44t {
        --45b10934: 4 !important;
    }
}
</style>

<!-- Scripts da raspadinha -->
<script>
// TODAS AS RASPADINHAS
document.addEventListener('DOMContentLoaded', function() {
    const raspadinhasContainer = document.getElementById('raspadinhas-container');
    const loadMoreBtn = document.getElementById('load-more-btn');
    const progressBar = document.getElementById('progress-bar');
    const showingText = document.getElementById('showing-text');
    const gridContainer = document.querySelector('.nM44t');
    const statusSelectBtn = document.querySelector('.status-select-btn');
    const statusOptions = document.querySelector('.status-options');
    const selectedStatusText = document.getElementById('selected-status');
    const priceSelectBtn = document.querySelector('.price-select-btn');
    const priceOptions = document.querySelector('.price-options');
    const selectedPriceText = document.getElementById('selected-price');
    const searchInput = document.querySelector('.casino-search input');
    const totalRaspadinhas = {{ $total ?? 0 }};
    let currentShowing = {{ $current ?? 0 }};
    let selectedStatus = 'all';
    let selectedPrice = 'all';
    
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
    
    // Função para resetar e carregar raspadinhas com o filtro atual
    function resetAndLoadRaspadinhas() {
        // Limpar raspadinhas atuais
        raspadinhasContainer.innerHTML = '';
        
        // Resetar contadores
        currentShowing = 0;
        
        // Carregar raspadinhas com a página 1
        loadMoreBtn.setAttribute('data-page', '1');
        loadMoreBtn.click();
    }
    
    // Mostrar/esconder o dropdown de status
    statusSelectBtn?.addEventListener('click', function() {
        const isExpanded = statusOptions.style.display !== 'none';
        statusOptions.style.display = isExpanded ? 'none' : 'block';
        statusSelectBtn.setAttribute('aria-expanded', !isExpanded);
    });
    
    // Mostrar/esconder o dropdown de preço
    priceSelectBtn?.addEventListener('click', function() {
        const isExpanded = priceOptions.style.display !== 'none';
        priceOptions.style.display = isExpanded ? 'none' : 'block';
        priceSelectBtn.setAttribute('aria-expanded', !isExpanded);
    });
    
    // Fechar dropdowns quando clicar fora
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.listBox-wrapper')) {
            statusOptions.style.display = 'none';
            statusSelectBtn?.setAttribute('aria-expanded', 'false');
            priceOptions.style.display = 'none';
            priceSelectBtn?.setAttribute('aria-expanded', 'false');
        }
    });
    
    // Adicionar eventos aos checkboxes de status
    const statusCheckboxes = document.querySelectorAll('.status-options input[type="checkbox"]');
    statusCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.value === 'all') {
                // Se "Todos" for selecionado, desmarque os outros
                statusCheckboxes.forEach(cb => {
                    if (cb.value !== 'all') {
                        cb.checked = false;
                    }
                });
                selectedStatus = 'all';
                selectedStatusText.textContent = 'Todas';
            } else {
                // Se um status específico for selecionado, desmarque "Todos"
                document.getElementById('all-status').checked = false;
                
                // Verificar se há apenas um selecionado
                const checkedStatuses = [...statusCheckboxes].filter(cb => cb.checked && cb.value !== 'all');
                if (checkedStatuses.length === 1) {
                    selectedStatus = checkedStatuses[0].value;
                    selectedStatusText.textContent = checkedStatuses[0].value === 'active' ? 'Ativas' : 'Inativas';
                } else if (checkedStatuses.length > 1) {
                    selectedStatus = 'multiple';
                    selectedStatusText.textContent = `${checkedStatuses.length} selecionados`;
                } else {
                    // Se nenhum estiver selecionado, volte para "Todos"
                    document.getElementById('all-status').checked = true;
                    selectedStatus = 'all';
                    selectedStatusText.textContent = 'Todas';
                }
            }
            
            // Recarregar raspadinhas com o filtro
            resetAndLoadRaspadinhas();
        });
    });
    
    // Adicionar eventos aos checkboxes de preço
    const priceCheckboxes = document.querySelectorAll('.price-options input[type="checkbox"]');
    priceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.value === 'all') {
                // Se "Todos" for selecionado, desmarque os outros
                priceCheckboxes.forEach(cb => {
                    if (cb.value !== 'all') {
                        cb.checked = false;
                    }
                });
                selectedPrice = 'all';
                selectedPriceText.textContent = 'Todos';
            } else {
                // Se um preço específico for selecionado, desmarque "Todos"
                document.getElementById('all-price').checked = false;
                
                // Verificar se há apenas um selecionado
                const checkedPrices = [...priceCheckboxes].filter(cb => cb.checked && cb.value !== 'all');
                if (checkedPrices.length === 1) {
                    selectedPrice = checkedPrices[0].value;
                    const priceLabels = {
                        'low': 'Até R$ 5,00',
                        'medium': 'R$ 5,01 - R$ 20,00',
                        'high': 'Acima de R$ 20,00'
                    };
                    selectedPriceText.textContent = priceLabels[selectedPrice] || selectedPrice;
                } else if (checkedPrices.length > 1) {
                    selectedPrice = 'multiple';
                    selectedPriceText.textContent = `${checkedPrices.length} selecionados`;
                } else {
                    // Se nenhum estiver selecionado, volte para "Todos"
                    document.getElementById('all-price').checked = true;
                    selectedPrice = 'all';
                    selectedPriceText.textContent = 'Todos';
                }
            }
            
            // Recarregar raspadinhas com o filtro
            resetAndLoadRaspadinhas();
        });
    });
    
    // Função unificada para gerenciar a pesquisa
    function setupSearchFunctionality() {
        if (!searchInput) return;

        let searchTimer;

        // Função para processar a pesquisa
        function processSearch() {
            const searchTerm = searchInput.value.trim();
            
            // Limpar raspadinhas e recarregar com o termo atual
            raspadinhasContainer.innerHTML = '';
            currentShowing = 0;
            loadMoreBtn.setAttribute('data-page', '1');
            
            // Remover qualquer mensagem de erro anterior
            const errorMessage = raspadinhasContainer.querySelector('.error-message');
            if (errorMessage) {
                errorMessage.remove();
            }
            
            // Restaurar o botão "Carregar mais"
            loadMoreBtn.style.display = 'block';
            loadMoreBtn.disabled = false;
            loadMoreBtn.innerHTML = '<span>Carregar mais</span>';
            
            // Carregar raspadinhas com o termo de pesquisa atual
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
    
    // Função para carregar mais raspadinhas
    loadMoreBtn?.addEventListener('click', function() {
        const page = parseInt(this.getAttribute('data-page'));
        
        // Adicionar indicador de carregamento
        loadMoreBtn.disabled = true;
        loadMoreBtn.innerHTML = '<span>Carregando...</span>';
        
        // Usar o caminho relativo correto
        let url = `/raspadinha/carregar-mais?page=${page}&per_page=12`;
        
        // Adicionar filtro de status, se necessário
        if (selectedStatus !== 'all') {
            if (selectedStatus === 'multiple') {
                // Coletar todos os status selecionados
                const checkedStatuses = [...document.querySelectorAll('.status-options input[type="checkbox"]:checked')]
                    .filter(cb => cb.value !== 'all')
                    .map(cb => cb.value);
                
                url += `&statuses=${encodeURIComponent(JSON.stringify(checkedStatuses))}`;
            } else {
                url += `&status=${encodeURIComponent(selectedStatus)}`;
            }
        }
        
        // Adicionar filtro de preço, se necessário
        if (selectedPrice !== 'all') {
            if (selectedPrice === 'multiple') {
                // Coletar todos os preços selecionados
                const checkedPrices = [...document.querySelectorAll('.price-options input[type="checkbox"]:checked')]
                    .filter(cb => cb.value !== 'all')
                    .map(cb => cb.value);
                
                url += `&prices=${encodeURIComponent(JSON.stringify(checkedPrices))}`;
            } else {
                url += `&price=${encodeURIComponent(selectedPrice)}`;
            }
        }
        
        // Adicionar termo de pesquisa, se existir
        const searchTerm = searchInput ? searchInput.value.trim() : '';
        if (searchTerm) {
            url += `&search=${encodeURIComponent(searchTerm)}`;
        }

        // Fazer a requisição AJAX
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na requisição: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (!data.raspadinhas || data.raspadinhas.length === 0) {
                    const noRaspadinhasMessage = `
                        <div class="alert alert-warning"><span>Nenhuma raspadinha encontrada</span></div>
                    `;
                    // Inserir o alerta ANTES da div raspadinhas-container, não dentro dela
                    raspadinhasContainer.insertAdjacentHTML('beforebegin', noRaspadinhasMessage);
                    
                    loadMoreBtn.style.display = 'none';
                    return;
                }
                
                // Limpar qualquer mensagem de erro anterior
                const previousAlerts = document.querySelectorAll('.alert.alert-warning');
                previousAlerts.forEach(alert => alert.remove());
                
                // Adicionar novas raspadinhas ao container com animação
                data.raspadinhas.forEach((raspadinha, index) => {
                    const raspadinhaElement = `
                        <a href="JavaScript: Void(0);" onclick="openRaspadinha(${raspadinha.id})" class="hZm-w s3HXA raspadinha-animation" data-raspadinha-id="${raspadinha.id}" style="animation-delay: ${index * 50}ms">
                            <div class="u3Qxq">
                                <div class="g-hw5">
                                    <img alt="${raspadinha.name}" class="vTFYb" src="${raspadinha.image_url || '/img/raspadinha/default.png'}"/>
                                    <div class="raspadinha-overlay">
                                        <div class="price-badge normal">R$ ${parseFloat(raspadinha.price).toFixed(2).replace('.', ',')}</div>
                                        <div class="price-badge turbo">⚡ R$ ${parseFloat(raspadinha.turbo_price).toFixed(2).replace('.', ',')}</div>
                                        <div class="probability-badge">
                                            ${parseFloat(raspadinha.win_chance || 0).toFixed(1)}% ganho
                                        </div>
                                    </div>
                                </div>
                                <div class="hzP6t">
                                    <span class="phlJe">${raspadinha.name}</span>
                                    <span class="liQBm">
                                        ${raspadinha.description || 'Raspe e ganhe!'}
                                        <br><small class="prize-types">
                                            Prêmios: ${raspadinha.prize_types || '💰'}
                                        </small>
                                    </span>
                                </div>
                                <section class="bBtlK">
                                    <span class="Oe7Pi">
                                        <span class="nuxt-icon nuxt-icon--fill">
                                            <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <span>Jogar</span>
                                    </span>
                                </section>
                            </div>
                        </a>
                    `;
                    raspadinhasContainer.insertAdjacentHTML('beforeend', raspadinhaElement);
                });
                
                // Atualizar página atual
                loadMoreBtn.setAttribute('data-page', (data.page + 1).toString());
                
                // Atualizar contadores
                currentShowing += data.raspadinhas.length;
                showingText.textContent = `Mostrando ${currentShowing} de ${data.total} raspadinhas`;
                
                // Atualizar barra de progresso
                const progressPercentage = (currentShowing / data.total) * 100;
                progressBar.style.width = `${progressPercentage}%`;
                
                // Esconder o botão se todas as raspadinhas foram carregadas
                if (currentShowing >= data.total) {
                    loadMoreBtn.style.display = 'none';
                } else {
                    loadMoreBtn.style.display = 'block';
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.innerHTML = '<span>Carregar mais raspadinhas</span>';
                }
            })
            .catch(error => {
                // Recuperar de erros - reativar o botão
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerHTML = '<span>Tentar novamente</span>';
                
                // Mostrar mensagem de erro ao usuário
                if (!raspadinhasContainer.querySelector('.error-message')) {
                    const errorElement = `
                        <div class="error-message p-4 text-center">
                            <p>Erro ao carregar raspadinhas</p>
                        </div>
                    `;
                    raspadinhasContainer.insertAdjacentHTML('beforeend', errorElement);
                }
            });
    });
    
    // Event listener para refresh de saldo
    document.getElementById('refresh-balance')?.addEventListener('click', refreshBalance);
});

// Função para abrir raspadinha
function openRaspadinha(raspadinhaId) {
    // Redirecionar para a página de jogo
    window.location.href = `/raspadinha/${raspadinhaId}`;
}

// Função para atualizar saldo
function refreshBalance() {
    fetch('/raspadinha/user/balance')
        .then(response => response.json())
        .then(data => {
            // Atualizar saldo na interface se existir
            const balanceElement = document.querySelector('.user-balance');
            if (balanceElement) {
                balanceElement.textContent = data.formatted_balance;
            }
        })
        .catch(error => {});
}
</script>
@endsection 