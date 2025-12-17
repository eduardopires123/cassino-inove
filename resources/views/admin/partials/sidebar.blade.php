<nav id="sidebar">
    <div class="navbar-nav theme-brand flex-row text-center">
        <div class="nav-logo">
            <div class="nav-item theme-logo">
                <a href="/">
                    <img src="{{ asset(\App\Models\Settings::first()->favicon) }}" alt="logo" />
                </a>
            </div>
            <div class="nav-item theme-text">
                <a href="{{ route('admin.dash') }}" class="nav-link"> Painel </a>
            </div>
        </div>
        <div class="nav-item sidebar-toggle">
            <div class="btn-toggle sidebarCollapse">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left">
                    <polyline points="11 17 6 12 11 7"></polyline>
                    <polyline points="18 17 13 12 18 7"></polyline>
                </svg>
            </div>
        </div>
    </div>

    @php
        use Illuminate\Support\Facades\Auth;
        $user = Auth::user();
        
        // Verificar qual provedor de API de sports está ativo
        $sportsApiProvider = App\Models\Settings::getSportsApiProvider();
        $isBetbyActive = $sportsApiProvider === 'betby';
        $isDigitainActive = $sportsApiProvider === 'digitain' || $sportsApiProvider === null;
    @endphp

    <div class="profile-info">
        <div class="user-info">
            <div class="profile-img">
                <img src="{{ asset($user->image ?? 'assets/img/profile-30.png') }}" alt="avatar">
            </div>
            <div class="profile-content">
                <h6>{{ $user->name }}</h6>
                <p>{{ $user->is_admin == 1 ? 'Administrador' : (($user->is_admin == 2) ? 'Supervisor' : 'Afiliado') }}</p>
            </div>
        </div>
    </div>

    <div class="shadow-bottom"></div>

    <ul class="list-unstyled menu-categories" id="accordionExample">
        <li class="menu {{ request()->routeIs('admin.dash') ? 'active' : '' }}">
            <a href="{{ route('admin.dash') }}" aria-expanded="false" class="dropdown-toggle">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    <span>Dashboard</span>
                </div>
            </a>
        </li>

        @if(ChecaPermissao(1) == 1)
            <li class="menu {{ request()->routeIs('admin.personalizacao.*') || request()->routeIs('admin.footer-settings.*') ? 'active' : '' }}">
                <a href="#costumizacao" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.personalizacao.*') || request()->routeIs('admin.footer-settings.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pen-tool"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>
                        <span>Personalização</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.personalizacao.*') || request()->routeIs('admin.footer-settings.*') ? 'show' : '' }}" id="costumizacao" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('admin.personalizacao.banners') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.personalizacao.banners') }}"> Banner's</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.personalizacao.menu') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.personalizacao.menu') }}"> Menu </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.personalizacao.css') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.personalizacao.css') }}"> CSS Avançado </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.personalizacao.home') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.personalizacao.home') }}">Página Inicial</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.footer-settings.edit') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.footer-settings.edit') }}">Rodapé</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.personalizacao.icones') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.personalizacao.icones') }}">Ícones</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.personalizacao.sections-order') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.personalizacao.sections-order') }}">Ordem das Seções</a>
                    </li>
                </ul>
            </li>
        @endif

        @if(ChecaPermissao(2) == 1)
            <li class="menu {{ request()->routeIs('admin.cassino.*') || request()->routeIs('admin.inove.*') ? 'active' : '' }}">
                <a href="#meusjogos" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.cassino.*') || request()->routeIs('admin.inove.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                        <span>Cassino</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.cassino.*') || request()->routeIs('admin.import.*') || request()->routeIs('admin.inove.*') ? 'show' : '' }}" id="meusjogos" data-bs-parent="#accordionExample">
                    <!-- <li>
                    <a href="{{ route('admin.cassino.categorias') }}"> Todas as Categorias</a>
                </li> -->
                    <li class="{{ request()->routeIs('admin.cassino.provedores') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.cassino.provedores') }}"> Todos os Provedores </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.cassino.todos') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.cassino.todos') }}"> Todos os Jogos </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.cassino.partidas') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.cassino.partidas') }}"> Histórico de Partidas </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.inove.index') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.inove.index') }}"> Importar Jogos Inove </a>
                    </li>
                </ul>
            </li>
        @endif

        @if(ChecaPermissao(2) == 1)
            <li class="menu {{ request()->routeIs('admin.raspadinha.*') ? 'active' : '' }}">
                <a href="#raspadinha" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.raspadinha.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        <span>Raspadinha</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.raspadinha.*') ? 'show' : '' }}" id="raspadinha" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('admin.raspadinha.index') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.raspadinha.index') }}"> Raspadinhas</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.raspadinha.create') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.raspadinha.create') }}"> Adicionar Raspadinha</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.raspadinha.history') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.raspadinha.history') }}"> Histórico de Jogadas</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.raspadinha.statistics') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.raspadinha.statistics') }}"> Estatísticas</a>
                    </li>
                </ul>
            </li>
        @endif
        
        @if(ChecaPermissao(3) == 1)
            @if($isDigitainActive)
                {{-- Menu Sportsbook Digitain (Tradicional) --}}
                <li class="menu {{ request()->routeIs('admin.sports.*') ? 'active' : '' }}">
                    <a href="#esportes" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.sports.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            <span>Sportsbook</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.sports.*') ? 'show' : '' }}" id="esportes" data-bs-parent="#accordionExample">
                        <li class="{{ request()->routeIs('admin.sports.sports_apostas') ? 'active-submenu' : '' }}">
                            <a href="{{ route('admin.sports.sports_apostas') }}"> Apostas</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.sports.sports_estatisticas') ? 'active-submenu' : '' }}">
                            <a href="{{ route('admin.sports.sports_estatisticas') }}"> Estatísticas</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.sports.sports_configuracoes') ? 'active-submenu' : '' }}">
                            <a href="{{ route('admin.sports.sports_configuracoes') }}"> Configurações</a>
                        </li>
                    </ul>
                </li>
            @endif

            @if($isBetbyActive)
                {{-- Menu Sportsbook Betby (Nova API) --}}
                <li class="menu {{ request()->routeIs('admin.betby-sports.*') ? 'active' : '' }}">
                    <a href="#betby-esportes" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.betby-sports.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <div class="">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                           <span>Sportsbook</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.betby-sports.*') ? 'show' : '' }}" id="betby-esportes" data-bs-parent="#accordionExample">
                        <li class="{{ request()->routeIs('admin.betby-sports.sports_apostas') ? 'active-submenu' : '' }}">
                            <a href="{{ route('admin.betby-sports.sports_apostas') }}"> Apostas</a>
                        </li>
                        <li class="{{ request()->routeIs('admin.betby-sports.sports_estatisticas') ? 'active-submenu' : '' }}">
                            <a href="{{ route('admin.betby-sports.sports_estatisticas') }}"> Estatísticas</a>
                        </li>
                    </ul>
                </li>
            @endif
        @endif

        @if(ChecaPermissao(4) == 1)
            <li class="menu {{ request()->routeIs('admin.pagamentos.*') ? 'active' : '' }}">
                <a href="#pagamentos" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.pagamentos.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        <span>Pagamentos</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.pagamentos.*') ? 'show' : '' }}" id="pagamentos" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('admin.pagamentos.depositos') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.pagamentos.depositos') }}"> Depósitos</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.pagamentos.saques') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.pagamentos.saques') }}"> Saques </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.pagamentos.saques_pendentes') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.pagamentos.saques_pendentes') }}"> Saques Pendentes </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.pagamentos.saques_afiliados') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.pagamentos.saques_afiliados') }}"> Saques de Afiliados </a>
                    </li>
                    <!--<li>
                    <a href="{{ route('admin.pagamentos.historico_pagamentos') }}"> Histórico de Pagamentos </a>
                </li>-->
                </ul>
            </li>
        @endif

        @if(ChecaPermissao(5) == 1)
            <li class="menu {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                <a href="#user" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.usuarios.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Usuários</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.usuarios.*') ? 'show' : '' }}" id="user" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('admin.usuarios.usuarios') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.usuarios.usuarios') }}"> Buscar Usuário</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.usuarios.carteiras') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.usuarios.carteiras') }}"> Carteiras </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.usuarios.blacklist') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.usuarios.blacklist') }}"> Blacklist </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.usuarios.user_news') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.usuarios.user_news') }}"> Novos Usuários </a>
                    </li>
                </ul>
            </li>
        @endif

        @if(ChecaPermissao(6) == 1)
            <li class="menu {{ request()->routeIs('admin.administracao.*') ? 'active' : '' }}">
                <a href="#admin" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.administracao.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <span>Administração</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.administracao.*') ? 'show' : '' }}" id="admin" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('admin.administracao.configuracoes_gerais') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.administracao.configuracoes_gerais') }}"> Configurações </a>
                    </li>
                    @php
                        $edPayActive = \App\Models\Gateways::where('nome', 'EdPay')->where('active', 1)->exists();
                    @endphp
                    @if($edPayActive)
                    <li class="{{ request()->routeIs('admin.administracao.banco') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.administracao.banco') }}"> Banco </a>
                    </li>
                    @endif
                    <li class="{{ request()->routeIs('admin.administracao.gateways') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.administracao.gateways') }}"> Gateways </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.administracao.apisgames') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.administracao.apisgames') }}"> APIS Games </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.administracao.funcoesepermissoes') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.administracao.funcoesepermissoes') }}"> Funções e Permissões </a>
                    </li>
                    <!-- <li class="{{ request()->routeIs('admin.email-templates.*') ? 'active-submenu' : '' }}">
                    <a href="{{ route('admin.email-templates.index') }}"> Templates de Email </a>
                </li>-->
                    <li>
                        <a href="#" id="clear-cache-link">Limpar Cache</a>
                        <form id="clear-cache-form" action="{{ route('admin.clear-cache') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                document.getElementById('clear-cache-link').addEventListener('click', function(e) {
                                    e.preventDefault();

                                    // Mostrar toast informando que o cache está sendo limpo
                                    const cacheToast = ToastManager.info('Limpando Cache...');

                                    // Desabilitar o link temporariamente
                                    const link = this;
                                    link.style.pointerEvents = 'none';
                                    link.style.opacity = '0.6';
                                    link.innerHTML = '⏳ Limpando cache...';

                                    // Realizar a chamada AJAX
                                    fetch('/admin/clear-cache', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                            'Content-Type': 'application/json'
                                        }
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            // Remover toast de processamento
                                            cacheToast.remove();

                                            // Reabilitar o link
                                            link.style.pointerEvents = 'auto';
                                            link.style.opacity = '1';
                                            link.innerHTML = 'Limpar Cache';

                                            if (data.success) {
                                                ToastManager.success('Cache Limpo com sucesso!');
                                            } else {
                                                console.error('Erro ao limpar cache:', data);
                                                ToastManager.error('Erro ao limpar cache: ' + data.message);
                                            }
                                        })
                                        .catch(error => {
                                            // Remover toast de processamento
                                            cacheToast.remove();

                                            // Reabilitar o link
                                            link.style.pointerEvents = 'auto';
                                            link.style.opacity = '1';
                                            link.innerHTML = 'Limpar Cache';

                                            console.error('Erro ao limpar cache:', error);
                                            ToastManager.error('Erro de conexão ao limpar cache. Tente novamente.');
                                        });
                                });
                            });
                        </script>
                    </li>
                </ul>
            </li>
        @endif


        @if(ChecaPermissao(8) == 1)
            <li class="menu {{ request()->routeIs('admin.cashback.*') ? 'active' : '' }}">
                <a href="#cashback" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.cashback.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        <span>Cashback</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.cashback.*') ? 'show' : '' }}" id="cashback" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('admin.cashback.index') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.cashback.index') }}"> Cashback Automático </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.cashback.report') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.cashback.report') }}"> Relatórios </a>
                    </li>
                </ul>
            </li>
        @endif

        @if(ChecaPermissao(9) == 1 && $user->is_admin == 1)
            <li class="menu {{ request()->routeIs('admin.afiliacao.*') ? 'active' : '' }}">
                <a href="#afiliados" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.afiliacao.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                        <span>Afiliação</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.afiliacao.*') ? 'show' : '' }}" id="afiliados" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('admin.afiliacao.afiliados') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.afiliacao.afiliados') }}"> Lista de Afiliados </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.afiliacao.estatisticas') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.afiliacao.estatisticas') }}"> Estatisticas </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.afiliacao.gerentes') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.afiliacao.gerentes') }}"> Gerentes de Afiliados </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.afiliacao.estatisticas.gerente') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.afiliacao.estatisticas.gerente') }}"> Estatisticas de Gerente </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.afiliacao.config') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.afiliacao.config') }}"> Configurações </a>
                    </li>
                </ul>
            </li>
        @elseif(ChecaPermissao(9) == 1 && $user->is_admin == 3)
            <li class="menu {{ request()->routeIs('admin.afiliacao.*') ? 'active' : '' }}">
                <a href="#afiliados" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.afiliacao.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                        <span>Afiliação</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.afiliacao.*') ? 'show' : '' }}" id="afiliados" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('admin.afiliacao.afiliados') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.afiliacao.afiliados') }}"> Lista de Afiliados </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.afiliacao.estatisticas.gerente') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.afiliacao.estatisticas.gerente') }}"> Estatisticas de Gerente </a>
                    </li>
                </ul>
            </li>
        @endif
        @if(ChecaPermissao(10) == 1)
            <li class="menu {{ request()->routeIs('admin.plugins.*') || request()->routeIs('admin.lucky-boxes.*') || request()->routeIs('admin.vip-levels.*') ||  request()->routeIs('admin.coupons.*') || request()->routeIs('admin.roulette.*') ? 'active' : '' }}">
                <a href="#plugins" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.plugins.*') || request()->routeIs('admin.lucky-boxes.*') || request()->routeIs('admin.vip-levels.*') || request()->routeIs('admin.coupons.*') || request()->routeIs('admin.roulette.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-codesandbox"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="7.5 4.21 12 6.81 16.5 4.21"></polyline><polyline points="7.5 19.79 7.5 14.6 3 12"></polyline><polyline points="21 12 16.5 14.6 16.5 19.79"></polyline><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <span>Plugins</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ request()->routeIs('admin.plugins.*') || request()->routeIs('admin.lucky-boxes.*') || request()->routeIs('admin.coupons.*') || request()->routeIs('admin.vip-levels.*') || request()->routeIs('admin.roulette.*') ? 'show' : '' }}" id="plugins" data-bs-parent="#accordionExample">
                    <li class="{{ request()->routeIs('admin.lucky-boxes.*') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.lucky-boxes.index') }}"> Lucky Box's </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.vip-levels.index') || request()->routeIs('admin.vip-levels.*') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.vip-levels.index') }}">Vip Ranking's </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.coupons.*') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.coupons.index') }}"> Cupons de Bônus </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.roulette.*') ? 'active-submenu' : '' }}">
                        <a href="{{ route('admin.roulette.config') }}"> Roleta da Sorte</a>
                    </li>
                </ul>
            </li>
        @endif

    </ul>
</nav>
<style>
    .active-submenu{
        background: #00000012 !important;
    }
    .active-submenu a{
        color: #4361ee !important;
    }
    .active-submenu a:before{
        background: #4361ee !important;
    }

    html[data-bs-theme="dark"] .dark-logo,
    body[data-bs-theme="dark"] .dark-logo,
    [data-bs-theme="dark"] .dark-logo,
    .sidebar-wrapper .profile-info{
        padding: 9px;
    }
    body.dark .sidebar-wrapper .profile-info{
        padding: 9px!important;
    }
    body.dark .sidebar-closed .sidebar-wrapper:not(:hover) .profile-info .user-info .profile-img img{
        margin-left: -7px;
    }
</style>
