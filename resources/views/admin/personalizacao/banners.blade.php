@extends('admin.layouts.app')
@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp
    <div class="layout-px-spacing" id="contentaff">
        <div class="middle-content container-xxl p-0">
            <div class="page-meta" style="margin-top:45px;">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Personalização</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="estatde">Banner</li>
                    </ol>
                </nav>
            </div>

            <div class="row" style="margin-top: 20px;">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="statbox widget box box-shadow">
                        <div class="widget-content widget-content-area">
                            @php
                                // Definir todas as variáveis dos banners no início
                                $slideBannersTable = App\Models\Admin\Banners::where('tipo', 'slide')->orderBy('ordem', 'asc')->get();
                                $slideCountTable = App\Models\Admin\Banners::where('tipo', 'slide')->orderBy('ordem', 'asc')->count();
                                $slideActiveBanners = $slideBannersTable->where('active', true)->count();

                                $miniBannersTable = App\Models\Admin\Banners::where('tipo', 'mini')->orderBy('ordem', 'asc')->get();
                                $miniCountTable = App\Models\Admin\Banners::where('tipo', 'mini')->orderBy('ordem', 'asc')->count();
                                $miniActiveBanners = $miniBannersTable->where('active', true)->count();

                                $loginBannersTable = App\Models\Admin\Banners::where('tipo', 'login')->orderBy('ordem', 'asc')->get();
                                $loginCountTable = App\Models\Admin\Banners::where('tipo', 'login')->orderBy('ordem', 'asc')->count();
                                $loginActiveBanners = $loginBannersTable->where('active', true)->count();

                                $registerBannersTable = App\Models\Admin\Banners::where('tipo', 'register')->orderBy('ordem', 'asc')->get();
                                $registerCountTable = App\Models\Admin\Banners::where('tipo', 'register')->orderBy('ordem', 'asc')->count();
                                $registerActiveBanners = $registerBannersTable->where('active', true)->count();

                                $promoBannersTable = App\Models\Admin\Banners::where('tipo', 'promo')->orderBy('ordem', 'asc')->get();
                                $promoCountTable = App\Models\Admin\Banners::where('tipo', 'promo')->orderBy('ordem', 'asc')->count();
                                $promoActiveBanners = $promoBannersTable->where('active', true)->count();
                            @endphp

                            <div id="slideAccordion" class="accordion-icons accordion">
                                <!-- Banners Página Principal -->
                                <div class="card">
                                    <div class="card-header" id="headingOne3">
                                        <section class="mb-0 mt-0">
                                            <div role="menu" class="collapsed" data-bs-toggle="collapse" data-bs-target="#iconAccordionOne" aria-expanded="false" aria-controls="iconAccordionOne">
                                                <div class="accordion-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay">
                                                        <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                                                        <polygon points="12 15 17 21 7 21 12 15"></polygon>
                                                    </svg>
                                                </div>
                                                Banners (Página Principal)
                                                <div class="icons">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                    <div id="iconAccordionOne" class="collapse" aria-labelledby="headingOne3" data-bs-parent="#slideAccordion">
                                        <div class="card-body">
                                            <button id="add-slide-btn" class="btn btn-info btn-icon-split" style="width: 100%; margin-bottom: 20px;" type="button">
                                            <span class="icon text-white-50">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                                <span class="text">Adicionar novo Banner</span>
                                            </button>

                                            <div class="banner-stats mb-4">
                                                <div class="alert alert-info d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>Total de Banners:</strong> {{$slideCountTable}}
                                                        <span class="mx-3">|</span>
                                                        <strong>Banners Ativos:</strong> {{$slideActiveBanners}}
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="POST" id="slide-settings" name="slide-settings" action="">
                                                @csrf
                                                <div class="row" id="slide-form-container">
                                                    @foreach($slideBannersTable as $registro)
                                                        <div class="col-md-6 col-lg-4 mb-4">
                                                            <div class="banner-card" draggable="true" data-type="type-1" id="form-group-{{$registro->id}}">
                                                                <div class="banner-preview" style="position: relative;">
                                                                    <div class="banner-status {{$registro->active ? 'active' : 'inactive'}}" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                                                <span class="badge {{$registro->active ? 'bg-success' : 'bg-danger'}}">
                                                                    {{$registro->active ? 'Ativo' : 'Inativo'}}
                                                                </span>
                                                                    </div>

                                                                    <div id="bg{{$registro->id}}" class="banner-image d-flex align-items-center justify-content-center"
                                                                         style="height: 200px; border-radius: 8px; cursor: pointer; overflow: hidden; position: relative; background-color: #f8f9fa;"
                                                                         onclick="document.getElementById('ff{{$registro->id}}').click();">
                                                                        @if($registro->imagem == "")
                                                                            <div class="no-image-placeholder d-flex align-items-center justify-content-center h-100 w-100">
                                                                                <i class="fa fa-image fa-3x text-muted"></i>
                                                                            </div>
                                                                        @else
                                                                            <img src="{{ $registro->imagem }}" alt="Banner" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
                                                                        @endif
                                                                    </div>
                                                                    <input id="ff{{$registro->id}}" type="file" style="display: none;"
                                                                           class="form-control file-input-banner">
                                                                </div>

                                                                @if($registro->tipo == 'slide')
                                                                    <div class="banner-link-container mt-2">
                                                                        <div class="link-options-container mb-2">
                                                                            <label class="form-label"><strong>Link do Banner:</strong></label>
                                                                            <select id="banner_link_{{$registro->id}}" class="banner-select" placeholder="Digite URL ou selecione um jogo..." data-id="{{$registro->id}}">
                                                                                <option value="">Digite URL ou selecione um jogo...</option>
                                                                                @php
                                                                                    $games = App\Models\GamesApi::where('status', 1)->orderBy('name', 'asc')->get();
                                                                                    $selectedGameId = null;
                                                                                    $customUrl = null;

                                                                                    if(Str::startsWith($registro->link ?? '', 'OpenGame')) {
                                                                                        preg_match("/OpenGame\('games', '(.+?)'\);/", $registro->link, $matches);
                                                                                        $selectedGameId = $matches[1] ?? null;
                                                                                    } else {
                                                                                        $customUrl = $registro->link;
                                                                                    }
                                                                                @endphp

                                                                                    <!-- Opção para URL personalizada, se existir -->
                                                                                @if($customUrl)
                                                                                    <option value="{{$customUrl}}" selected>{{$customUrl}}</option>
                                                                                @endif

                                                                                <!-- Opções de jogos -->
                                                                                <optgroup label="Jogos">
                                                                                    @foreach($games as $game)
                                                                                        @if($game->name && $game->id)
                                                                                            <option value="OpenGame('games', '{{$game->id}}');" {{ $selectedGameId == $game->id ? 'selected' : '' }}>
                                                                                                {{$game->name}}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <div class="banner-order-controls mt-2 mb-2">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label mb-1"><strong>Ordem de Exibição:</strong></label>
                                                                            <div class="input-group input-group-sm">
                                                                                <input type="number" class="form-control banner-order-input" 
                                                                                       data-id="{{$registro->id}}" 
                                                                                       value="{{$registro->ordem}}" 
                                                                                       min="1" max="100" step="1">
                                                                                <button class="btn btn-outline-primary save-order-btn" 
                                                                                        type="button" 
                                                                                        data-id="{{$registro->id}}" 
                                                                                        title="Salvar ordem">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 text-end">
                                                                            <small class="text-muted">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-move me-1"><polyline points="5 9 2 12 5 15"></polyline><polyline points="9 5 12 2 15 5"></polyline><polyline points="15 19 12 22 9 19"></polyline><polyline points="19 9 22 12 19 15"></polyline><line x1="2" y1="12" x2="22" y2="12"></line><line x1="12" y1="2" x2="12" y2="22"></line></svg>
                                                                                Arraste para reordenar
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="banner-controls mt-3">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input banner-toggle" type="checkbox" data-id="{{$registro->id}}" {{ $registro->active ? 'checked' : '' }}>
                                                                            <label class="form-check-label">{{ $registro->active ? 'Ativo' : 'Desativado' }}</label>
                                                                        </div>
                                                                        <div class="banner-actions">
                                                                            <button type="button" class="btn btn-sm btn-info me-2 view-banner" title="Visualizar"
                                                                                    data-image="{{ $registro->imagem }}"
                                                                                    onclick="openImageModal('{{ $registro->imagem ? $registro->imagem : '' }}')"
                                                                                {{ $registro->imagem ? '' : 'disabled' }}>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                            </button>
                                                                            <button type="button" class="btn btn-sm btn-danger delete-banner" data-id="{{$registro->id}}">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div id="progressContainer{{$registro->id}}" class="progress mt-2"
                                                                     style="{!! ($registro->imagem == "") ? null : 'display: none;' !!}">
                                                                    <div id="progressBar{{$registro->id}}" class="progress-bar"
                                                                         role="progressbar" style="width: 0%;"
                                                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="miniAccordion" class="accordion-icons accordion">
                                <!-- Mini Banners -->
                                <div class="card">
                                    <div class="card-header" id="headingTwo3">
                                        <section class="mb-0 mt-0">
                                            <div role="menu" class="collapsed" data-bs-toggle="collapse" data-bs-target="#iconAccordionTwo" aria-expanded="false" aria-controls="iconAccordionTwo">
                                                <div class="accordion-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay">
                                                        <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                                                        <polygon points="12 15 17 21 7 21 12 15"></polygon>
                                                    </svg>
                                                </div>
                                                Mini Banners
                                                <div class="icons">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                    <div id="iconAccordionTwo" class="collapse" aria-labelledby="headingTwo3" data-bs-parent="#miniAccordion">
                                        <div class="card-body">
                                            <button id="add-minibanner-btn" class="btn btn-info btn-icon-split" style="width: 100%; margin-bottom: 20px;" type="button">
                                            <span class="icon text-white-50">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                                <span class="text">Adicionar novo Mini Banner</span>
                                            </button>

                                            <div class="banner-stats mb-4">
                                                <div class="alert alert-info alert-light-dark">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <div>
                                                            <strong>Total de Mini Banners:</strong> <span id="mini-total-count">{{$miniCountTable}}</span>
                                                            <span class="mx-3">|</span>
                                                            <strong>Mini Banners Ativos:</strong> <span id="mini-active-count">{{$miniActiveBanners}}</span>
                                                        </div>
                                                        <div class="ms-3">
                                                            <label class="form-label mb-1 me-2"><strong>Filtrar por:</strong></label>
                                                            <select id="mini-banner-filter" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                                                                <option value="todos">Todos</option>
                                                                <option value="desktop">Desktop</option>
                                                                <option value="mobile">Mobile</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div id="mini-filter-info" class="d-none">
                                                        <small class="text-muted">
                                                            <strong>Mostrando:</strong> <span id="mini-showing-count">0</span> mini banners
                                                            <span id="mini-filter-type"></span>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="POST" id="mini-settings" name="mini-settings" action="">
                                                @csrf
                                                <div class="row" id="mini-form-container">
                                                    @foreach($miniBannersTable as $registro)
                                                        <div class="col-md-6 col-lg-4 mb-4">
                                                            <div class="banner-card" draggable="true" data-type="type-1" id="form-group-{{$registro->id}}">
                                                                <div class="banner-preview" style="position: relative;">
                                                                    <div class="banner-status {{$registro->active ? 'active' : 'inactive'}}" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                                                <span class="badge {{$registro->active ? 'bg-success' : 'bg-danger'}}">
                                                                    {{$registro->active ? 'Ativo' : 'Inativo'}}
                                                                </span>
                                                                    </div>

                                                                    <div class="device-type-badge" style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                                                <span class="badge bg-primary">
                                                                    {{$registro->mobile == 'sim' ? 'Mobile' : 'Desktop'}}
                                                                </span>
                                                                    </div>

                                                                    <div id="bg{{$registro->id}}" class="banner-image d-flex align-items-center justify-content-center"
                                                                         style="height: 200px; border-radius: 8px; cursor: pointer; overflow: hidden; position: relative; background-color: #f8f9fa;"
                                                                         onclick="document.getElementById('ff{{$registro->id}}').click();">
                                                                        @if($registro->imagem == "")
                                                                            <div class="no-image-placeholder d-flex align-items-center justify-content-center h-100 w-100">
                                                                                <i class="fa fa-image fa-3x text-muted"></i>
                                                                            </div>
                                                                        @else
                                                                            <img src="{{ $registro->imagem }}" alt="Banner" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
                                                                        @endif
                                                                    </div>
                                                                    <input id="ff{{$registro->id}}" type="file" style="display: none;"
                                                                           class="form-control file-input-banner">
                                                                </div>

                                                                @if($registro->tipo == 'mini')
                                                                    <div class="banner-link-container mt-2">
                                                                        <div class="link-options-container mb-2">
                                                                            <label class="form-label"><strong>Link do Banner:</strong></label>
                                                                            <select id="banner_link_{{$registro->id}}" class="banner-select" placeholder="Digite URL ou selecione um jogo..." data-id="{{$registro->id}}">
                                                                                <option value="">Digite URL ou selecione um jogo...</option>
                                                                                @php
                                                                                    $games = App\Models\GamesApi::where('status', 1)->orderBy('name', 'asc')->get();
                                                                                    $selectedGameId = null;
                                                                                    $customUrl = null;

                                                                                    if(Str::startsWith($registro->link ?? '', 'OpenGame')) {
                                                                                        preg_match("/OpenGame\('games', '(.+?)'\);/", $registro->link, $matches);
                                                                                        $selectedGameId = $matches[1] ?? null;
                                                                                    } else {
                                                                                        $customUrl = $registro->link;
                                                                                    }
                                                                                @endphp

                                                                                    <!-- Opção para URL personalizada, se existir -->
                                                                                @if($customUrl)
                                                                                    <option value="{{$customUrl}}" selected>{{$customUrl}}</option>
                                                                                @endif

                                                                                <!-- Opções de jogos -->
                                                                                <optgroup label="Jogos">
                                                                                    @foreach($games as $game)
                                                                                        @if($game->name && $game->id)
                                                                                            <option value="OpenGame('games', '{{$game->id}}');" {{ $selectedGameId == $game->id ? 'selected' : '' }}>
                                                                                                {{$game->name}}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </optgroup>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                <div class="banner-order-controls mt-2 mb-2">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label mb-1"><strong>Ordem de Exibição:</strong></label>
                                                                            <div class="input-group input-group-sm">
                                                                                <input type="number" class="form-control banner-order-input" 
                                                                                       data-id="{{$registro->id}}" 
                                                                                       value="{{$registro->ordem}}" 
                                                                                       min="1" max="100" step="1">
                                                                                <button class="btn btn-outline-primary save-order-btn" 
                                                                                        type="button" 
                                                                                        data-id="{{$registro->id}}" 
                                                                                        title="Salvar ordem">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 text-end">
                                                                            <small class="text-muted">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-move me-1"><polyline points="5 9 2 12 5 15"></polyline><polyline points="9 5 12 2 15 5"></polyline><polyline points="15 19 12 22 9 19"></polyline><polyline points="19 9 22 12 19 15"></polyline><line x1="2" y1="12" x2="22" y2="12"></line><line x1="12" y1="2" x2="12" y2="22"></line></svg>
                                                                                Arraste para reordenar
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="banner-controls mt-3">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input banner-toggle" type="checkbox" data-id="{{$registro->id}}" {{ $registro->active ? 'checked' : '' }}>
                                                                            <label class="form-check-label">{{ $registro->active ? 'Ativo' : 'Desativado' }}</label>
                                                                        </div>
                                                                        <div class="banner-actions">
                                                                            <button type="button" class="btn btn-sm btn-info me-2 view-banner" title="Visualizar"
                                                                                    data-image="{{ $registro->imagem }}"
                                                                                    onclick="openImageModal('{{ $registro->imagem ? $registro->imagem : '' }}')"
                                                                                {{ $registro->imagem ? '' : 'disabled' }}>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                            </button>
                                                                            <button type="button" class="btn btn-sm btn-danger delete-banner" data-id="{{$registro->id}}">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div id="progressContainer{{$registro->id}}" class="progress mt-2"
                                                                     style="{!! ($registro->imagem == "") ? null : 'display: none;' !!}">
                                                                    <div id="progressBar{{$registro->id}}" class="progress-bar"
                                                                         role="progressbar" style="width: 0%;"
                                                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="loginAccordion" class="accordion-icons accordion">
                                <!-- Banners Login -->
                                <div class="card">
                                    <div class="card-header" id="headingThree3">
                                        <section class="mb-0 mt-0">
                                            <div role="menu" class="collapsed" data-bs-toggle="collapse" data-bs-target="#iconAccordionThree" aria-expanded="false" aria-controls="iconAccordionThree">
                                                <div class="accordion-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay">
                                                        <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                                                        <polygon points="12 15 17 21 7 21 12 15"></polygon>
                                                    </svg>
                                                </div>
                                                Banners (Login)
                                                <div class="icons">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                    <div id="iconAccordionThree" class="collapse" aria-labelledby="headingThree3" data-bs-parent="#loginAccordion">
                                        <div class="card-body">
                                            <button id="add-login-btn" class="btn btn-info btn-icon-split" style="width: 100%; margin-bottom: 20px;" type="button">
                                            <span class="icon text-white-50">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                                <span class="text">Adicionar novo Banner de Login</span>
                                            </button>

                                            <div class="banner-stats mb-4">
                                                <div class="alert alert-info d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>Total de Banners de Login:</strong> {{$loginCountTable}}
                                                        <span class="mx-3">|</span>
                                                        <strong>Banners Ativos:</strong> {{$loginActiveBanners}}
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="POST" id="login-settings" name="login-settings" action="">
                                                @csrf
                                                <div class="row" id="login-form-container">
                                                    @foreach($loginBannersTable as $registro)
                                                        <div class="col-md-6 col-lg-4 mb-4">
                                                            <div class="banner-card" draggable="true" data-type="type-1" id="form-group-{{$registro->id}}">
                                                                <div class="banner-preview" style="position: relative;">
                                                                    <div class="banner-status {{$registro->active ? 'active' : 'inactive'}}" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                                                <span class="badge {{$registro->active ? 'bg-success' : 'bg-danger'}}">
                                                                    {{$registro->active ? 'Ativo' : 'Inativo'}}
                                                                </span>
                                                                    </div>

                                                                    <div class="device-type-badge" style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                                                <span class="badge bg-primary">
                                                                    {{$registro->mobile == 'sim' ? 'Mobile' : 'Desktop'}}
                                                                </span>
                                                                    </div>

                                                                    <div id="bg{{$registro->id}}" class="banner-image d-flex align-items-center justify-content-center"
                                                                         style="height: 200px; border-radius: 8px; cursor: pointer; overflow: hidden; position: relative; background-color: #f8f9fa;"
                                                                         onclick="document.getElementById('ff{{$registro->id}}').click();">
                                                                        @if($registro->imagem == "")
                                                                            <div class="no-image-placeholder d-flex align-items-center justify-content-center h-100 w-100">
                                                                                <i class="fa fa-image fa-3x text-muted"></i>
                                                                            </div>
                                                                        @else
                                                                            <img src="{{ $registro->imagem }}" alt="Banner" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
                                                                        @endif
                                                                    </div>
                                                                    <input id="ff{{$registro->id}}" type="file" style="display: none;"
                                                                           class="form-control file-input-banner">
                                                                </div>

                                                                <div class="banner-controls mt-3">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input banner-toggle" type="checkbox" data-id="{{$registro->id}}" {{ $registro->active ? 'checked' : '' }}>
                                                                            <label class="form-check-label">{{ $registro->active ? 'Ativo' : 'Desativado' }}</label>
                                                                        </div>
                                                                        <div class="banner-actions">
                                                                            <button type="button" class="btn btn-sm btn-info me-2 view-banner" title="Visualizar"
                                                                                    data-image="{{ $registro->imagem }}"
                                                                                    onclick="openImageModal('{{ $registro->imagem ? $registro->imagem : '' }}')"
                                                                                {{ $registro->imagem ? '' : 'disabled' }}>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                            </button>
                                                                            <button type="button" class="btn btn-sm btn-danger delete-banner" data-id="{{$registro->id}}">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div id="progressContainer{{$registro->id}}" class="progress mt-2"
                                                                     style="{!! ($registro->imagem == "") ? null : 'display: none;' !!}">
                                                                    <div id="progressBar{{$registro->id}}" class="progress-bar"
                                                                         role="progressbar" style="width: 0%;"
                                                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="registerAccordion" class="accordion-icons accordion">
                                <!-- Banners Register -->
                                <div class="card">
                                    <div class="card-header" id="headingFour3">
                                        <section class="mb-0 mt-0">
                                            <div role="menu" class="collapsed" data-bs-toggle="collapse" data-bs-target="#iconAccordionFour" aria-expanded="false" aria-controls="iconAccordionFour">
                                                <div class="accordion-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay">
                                                        <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                                                        <polygon points="12 15 17 21 7 21 12 15"></polygon>
                                                    </svg>
                                                </div>
                                                Banners (Register)
                                                <div class="icons">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                    <div id="iconAccordionFour" class="collapse" aria-labelledby="headingFour3" data-bs-parent="#registerAccordion">
                                        <div class="card-body">
                                            <button id="add-register-btn" class="btn btn-info btn-icon-split" style="width: 100%; margin-bottom: 20px;" type="button">
                                            <span class="icon text-white-50">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                                <span class="text">Adicionar novo Banner de Registro</span>
                                            </button>

                                            <div class="banner-stats mb-4">
                                                <div class="alert alert-info d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>Total de Banners de Registro:</strong> {{$registerCountTable}}
                                                        <span class="mx-3">|</span>
                                                        <strong>Banners Ativos:</strong> {{$registerActiveBanners}}
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="POST" id="register-settings" name="register-settings" action="">
                                                @csrf
                                                <div class="row" id="register-form-container">
                                                    @foreach($registerBannersTable as $registro)
                                                        <div class="col-md-6 col-lg-4 mb-4">
                                                            <div class="banner-card" draggable="true" data-type="type-1" id="form-group-{{$registro->id}}">
                                                                <div class="banner-preview" style="position: relative;">
                                                                    <div class="banner-status {{$registro->active ? 'active' : 'inactive'}}" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                                                <span class="badge {{$registro->active ? 'bg-success' : 'bg-danger'}}">
                                                                    {{$registro->active ? 'Ativo' : 'Inativo'}}
                                                                </span>
                                                                    </div>

                                                                    <div class="device-type-badge" style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                                                <span class="badge bg-primary">
                                                                    {{$registro->mobile == 'sim' ? 'Mobile' : 'Desktop'}}
                                                                </span>
                                                                    </div>

                                                                    <div id="bg{{$registro->id}}" class="banner-image d-flex align-items-center justify-content-center"
                                                                         style="height: 200px; border-radius: 8px; cursor: pointer; overflow: hidden; position: relative; background-color: #f8f9fa;"
                                                                         onclick="document.getElementById('ff{{$registro->id}}').click();">
                                                                        @if($registro->imagem == "")
                                                                            <div class="no-image-placeholder d-flex align-items-center justify-content-center h-100 w-100">
                                                                                <i class="fa fa-image fa-3x text-muted"></i>
                                                                            </div>
                                                                        @else
                                                                            <img src="{{ $registro->imagem }}" alt="Banner" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
                                                                        @endif
                                                                    </div>
                                                                    <input id="ff{{$registro->id}}" type="file" style="display: none;"
                                                                           class="form-control file-input-banner">
                                                                </div>

                                                                <div class="banner-controls mt-3">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input banner-toggle" type="checkbox" data-id="{{$registro->id}}" {{ $registro->active ? 'checked' : '' }}>
                                                                            <label class="form-check-label">{{ $registro->active ? 'Ativo' : 'Desativado' }}</label>
                                                                        </div>
                                                                        <div class="banner-actions">
                                                                            <button type="button" class="btn btn-sm btn-info me-2 view-banner" title="Visualizar"
                                                                                    data-image="{{ $registro->imagem }}"
                                                                                    onclick="openImageModal('{{ $registro->imagem ? $registro->imagem : '' }}')"
                                                                                {{ $registro->imagem ? '' : 'disabled' }}>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                            </button>
                                                                            <button type="button" class="btn btn-sm btn-danger delete-banner" data-id="{{$registro->id}}">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div id="progressContainer{{$registro->id}}" class="progress mt-2"
                                                                     style="{!! ($registro->imagem == "") ? null : 'display: none;' !!}">
                                                                    <div id="progressBar{{$registro->id}}" class="progress-bar"
                                                                         role="progressbar" style="width: 0%;"
                                                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="promoAccordion" class="accordion-icons accordion">
                                <!-- Banner Promocional -->
                                <div class="card">
                                    <div class="card-header" id="headingFive3">
                                        <section class="mb-0 mt-0">
                                            <div role="menu" class="collapsed" data-bs-toggle="collapse" data-bs-target="#iconAccordionFive" aria-expanded="false" aria-controls="iconAccordionFive">
                                                <div class="accordion-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay">
                                                        <path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path>
                                                        <polygon points="12 15 17 21 7 21 12 15"></polygon>
                                                    </svg>
                                                </div>
                                                Banner Promocional
                                                <div class="icons">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                    <div id="iconAccordionFive" class="collapse" aria-labelledby="headingFive3" data-bs-parent="#promoAccordion">
                                        <div class="card-body">
                                            <button id="add-promo-btn" class="btn btn-info btn-icon-split" style="width: 100%; margin-bottom: 20px;" type="button" {{ $promoBannersTable->count() > 0 ? 'disabled' : '' }}>
                                            <span class="icon text-white-50">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                                <span class="text">Adicionar Banner Promocional</span>
                                            </button>

                                            <div class="banner-stats mb-4">
                                                <div class="alert {{ $promoCountTable > 0 ? 'alert-info' : 'alert-warning' }} d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>Banner Promocional:</strong> {{$promoCountTable > 0 ? 'Configurado' : 'Não configurado'}}
                                                        <span class="mx-3">|</span>
                                                        <strong>Status:</strong> {{$promoActiveBanners > 0 ? 'Ativo' : 'Inativo'}}
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-secondary">Máximo: 1</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="POST" id="promo-settings" name="promo-settings" action="">
                                                @csrf
                                                <div class="row" id="promo-form-container">
                                                    @foreach($promoBannersTable as $registro)
                                                        <div class="col-md-6 mb-4">
                                                            <div class="banner-card" draggable="false" data-type="type-1" id="form-group-{{$registro->id}}">
                                                                <div class="banner-preview" style="position: relative;">
                                                                    <div class="banner-status {{$registro->active ? 'active' : 'inactive'}}" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                                                <span class="badge {{$registro->active ? 'bg-success' : 'bg-danger'}}">
                                                                    {{$registro->active ? 'Ativo' : 'Inativo'}}
                                                                </span>
                                                                    </div>

                                                                    <div id="bg{{$registro->id}}" class="banner-image d-flex align-items-center justify-content-center"
                                                                         style="height: 200px; border-radius: 8px; cursor: pointer; overflow: hidden; position: relative; background-color: #f8f9fa;"
                                                                         onclick="document.getElementById('ff{{$registro->id}}').click();">
                                                                        @if($registro->imagem == "")
                                                                            <div class="no-image-placeholder d-flex align-items-center justify-content-center h-100 w-100">
                                                                                <i class="fa fa-image fa-3x text-muted"></i>
                                                                            </div>
                                                                        @else
                                                                            <img src="{{ $registro->imagem }}" alt="Banner Promocional" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
                                                                        @endif
                                                                    </div>
                                                                    <input id="ff{{$registro->id}}" type="file" style="display: none;"
                                                                           class="form-control file-input-banner">
                                                                </div>

                                                                <div class="banner-link-container mt-2">
                                                                    <div class="link-options-container mb-2">
                                                                        <label class="form-label"><strong>Link do Banner Promocional:</strong></label>
                                                                        <select id="banner_link_{{$registro->id}}" class="banner-select" placeholder="Digite URL ou selecione um jogo..." data-id="{{$registro->id}}">
                                                                            <option value="">Digite URL ou selecione um jogo...</option>
                                                                            @php
                                                                                $games = App\Models\GamesApi::where('status', 1)->orderBy('name', 'asc')->get();
                                                                                $selectedGameId = null;
                                                                                $customUrl = null;

                                                                                if(Str::startsWith($registro->link ?? '', 'OpenGame')) {
                                                                                    preg_match("/OpenGame\('games', '(.+?)'\);/", $registro->link, $matches);
                                                                                    $selectedGameId = $matches[1] ?? null;
                                                                                } else {
                                                                                    $customUrl = $registro->link;
                                                                                }
                                                                            @endphp

                                                                                <!-- Opção para URL personalizada, se existir -->
                                                                            @if($customUrl)
                                                                                <option value="{{$customUrl}}" selected>{{$customUrl}}</option>
                                                                            @endif

                                                                            <!-- Opções de jogos -->
                                                                            <optgroup label="Jogos">
                                                                                @foreach($games as $game)
                                                                                    @if($game->name && $game->id)
                                                                                        <option value="OpenGame('games', '{{$game->id}}');" {{ $selectedGameId == $game->id ? 'selected' : '' }}>
                                                                                            {{$game->name}}
                                                                                        </option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </optgroup>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="banner-order-controls mt-2 mb-2">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label mb-1"><strong>Ordem de Exibição:</strong></label>
                                                                            <div class="input-group input-group-sm">
                                                                                <input type="number" class="form-control banner-order-input" 
                                                                                       data-id="{{$registro->id}}" 
                                                                                       value="{{$registro->ordem}}" 
                                                                                       min="1" max="100" step="1">
                                                                                <button class="btn btn-outline-primary save-order-btn" 
                                                                                        type="button" 
                                                                                        data-id="{{$registro->id}}" 
                                                                                        title="Salvar ordem">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 text-end">
                                                                            <small class="text-muted">
                                                                                Banner único
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="banner-controls mt-3">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <div class="form-check form-switch">
                                                                            <input class="form-check-input banner-toggle" type="checkbox" data-id="{{$registro->id}}" {{ $registro->active ? 'checked' : '' }}>
                                                                            <label class="form-check-label">{{ $registro->active ? 'Ativo' : 'Desativado' }}</label>
                                                                        </div>
                                                                        <div class="banner-actions">
                                                                            <button type="button" class="btn btn-sm btn-info me-2 view-banner" title="Visualizar"
                                                                                    data-image="{{ $registro->imagem }}"
                                                                                    onclick="openImageModal('{{ $registro->imagem ? $registro->imagem : '' }}')"
                                                                                {{ $registro->imagem ? '' : 'disabled' }}>
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                                            </button>
                                                                            <button type="button" class="btn btn-sm btn-danger delete-banner" data-id="{{$registro->id}}">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div id="progressContainer{{$registro->id}}" class="progress mt-2"
                                                                     style="{!! ($registro->imagem == "") ? null : 'display: none;' !!}">
                                                                    <div id="progressBar{{$registro->id}}" class="progress-bar"
                                                                         role="progressbar" style="width: 0%;"
                                                                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualização de imagem -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Imagem Ampliada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img class="img-fluid" id="modalImage" style="max-height: 70vh;">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light-dark" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .banner-card {
            cursor: grab;
            transition: all 0.2s ease;
        }
        .banner-card:active {
            cursor: grabbing;
        }
        .banner-card:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .blue-background-class {
            background-color: rgba(0, 123, 255, 0.1) !important;
        }
        /* Estilo para o modal de visualização de imagem */
        .image-preview-content {
            margin: auto;
            display: block;
            max-width: 100%;
            max-height: 80vh;
        }
        #modalImage {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        /* Estilos para ícones de dispositivo */
        .device-toggle {
            position: relative;
            overflow: hidden;
        }
        .device-toggle .fa-mobile {
            font-size: 1.2em;
        }
        .device-toggle .fa-desktop {
            font-size: 1em;
        }
        .device-type-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }
        .device-type-badge .badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.6rem;
        }
        /* Destaque para banners novos */
        .highlight-new-banner {
            animation: pulse-border 2s infinite;
            border: 2px solid transparent;
            border-radius: 8px;
        }
        @keyframes pulse-border {
            0% { border-color: rgba(23, 162, 184, 0.2); box-shadow: 0 0 0 0 rgba(23, 162, 184, 0.2); }
            50% { border-color: rgba(23, 162, 184, 1); box-shadow: 0 0 0 10px rgba(23, 162, 184, 0); }
            100% { border-color: rgba(23, 162, 184, 0.2); box-shadow: 0 0 0 0 rgba(23, 162, 184, 0); }
        }
        
        /* Estilos para drag and drop */
        .sortable-ghost {
            opacity: 0.4;
            background: #f8f9fa;
            border: 2px dashed #6c757d;
        }
        
        .sortable-chosen {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3) !important;
            z-index: 1000;
        }
        
        .sortable-drag {
            opacity: 0.8;
            transform: rotate(5deg);
        }
        
        /* Cursor visual para indicar que é arrastável */
        .banner-card {
            cursor: grab;
            transition: all 0.2s ease;
            user-select: none;
        }
        
        .banner-card:active {
            cursor: grabbing;
        }
        
        .banner-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Estilos para controles de ordem */
        .banner-order-controls {
            background: rgba(0, 123, 255, 0.05);
            border-radius: 6px;
            padding: 8px;
            border: 1px solid rgba(0, 123, 255, 0.1);
        }
        
        .banner-order-input {
            text-align: center;
            font-weight: bold;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .banner-order-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .banner-order-input.saving {
            border-color: #28a745;
            background-color: #f8fff9;
        }
        
        .banner-order-input.error {
            border-color: #dc3545;
            background-color: #fff5f5;
        }
        
        /* Estilos para filtro de mini banners */
        #mini-banner-filter {
            min-width: 120px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        
        #mini-filter-info {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 8px;
            margin-top: 8px;
        }
        
        .banner-stats .alert {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Animação suave para mostrar/ocultar banners */
        .col-md-6.col-lg-4.mb-4 {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        .col-md-6.col-lg-4.mb-4[style*="display: none"] {
            opacity: 0;
            transform: scale(0.95);
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/light/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/css/dark/tomSelect/custom-tomSelect.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/src/tomSelect/tom-select.default.min.css') }}">
    @push('scripts')
        <script src="{{ asset('src/plugins/src/tomSelect/tom-select.base.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            // Variáveis para armazenar dados temporários do banner sendo processado
            // Verificar se já existe para evitar redeclaração
            if (typeof currentBannerData === 'undefined') {
                var currentBannerData = {
                    bannerId: null,
                    file: null,
                    tipo: null,
                    isMobile: null,
                    fileInput: null
                };
            }

            // Variáveis para controlar o debounce do cache
            if (typeof clearCacheTimeout === 'undefined') {
                var clearCacheTimeout = null;
            }
            if (typeof isCacheClearing === 'undefined') {
                var isCacheClearing = false;
            }

            // Variáveis para controlar o debounce do modal de upload
            if (typeof uploadModalTimeout === 'undefined') {
                var uploadModalTimeout = null;
            }
            if (typeof isUploadModalOpen === 'undefined') {
                var isUploadModalOpen = false;
            }

            // Função para limpar o cache do site com debounce
            function clearSiteCache() {
                // Se já está limpando cache, não faz nada
                if (isCacheClearing) {
                    return;
                }

                // Limpar timeout anterior se existir
                if (clearCacheTimeout) {
                    clearTimeout(clearCacheTimeout);
                }

                // Definir novo timeout para evitar múltiplas chamadas
                clearCacheTimeout = setTimeout(() => {
                    isCacheClearing = true;

                    // Mostrar toast informando que o cache está sendo limpo
                    const cacheToast = ToastManager.info('Limpando cache do site...');

                    // Chamada AJAX para limpar o cache
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

                            if (data.success) {
                                ToastManager.success('Cache do site limpo com sucesso!');
                            } else {
                                ToastManager.error('Erro ao limpar cache do site: ' + data.message);
                            }
                        })
                        .catch(error => {
                            // Remover toast de processamento
                            cacheToast.remove();

                            ToastManager.error('Erro ao limpar cache do site.');
                        })
                        .finally(() => {
                            isCacheClearing = false;
                        });
                }, 1000); // Debounce de 1 segundo
            }

            // Função para abrir o modal e definir a imagem
            function openImageModal(imageUrl) {
                // Verificar se a URL da imagem está vazia
                if (!imageUrl || imageUrl.trim() === '') {
                    console.error('URL da imagem vazia ou inválida');
                    ToastManager.error('Este banner não possui imagem para visualizar.');
                    return;
                }
                // Debug

                // Define a imagem no modal
                const modalImage = document.getElementById('modalImage');

                // Limpar a imagem anterior e mostrar indicador de carregamento
                modalImage.src = '';

                // Verificar se é um mini banner para mostrar o link
                const banner = document.querySelector(`[onclick="openImageModal('${imageUrl.split('?')[0]}')"]`) ||
                    document.querySelector(`[onclick*="${imageUrl.split('?')[0]}"]`);
                const bannerCard = banner ? banner.closest('.banner-card') : null;
                const isMini = bannerCard ? bannerCard.querySelector('.banner-link-input') !== null : false;

                // Remover qualquer linkInfo existente do modal anterior
                const existingLinkInfo = document.getElementById('modalLinkInfo');
                if (existingLinkInfo) {
                    existingLinkInfo.remove();
                }

                // Abrir o modal antes de carregar a imagem
                var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
                myModal.show();

                // Configurar handler para erros de carregamento
                modalImage.onerror = function() {
                    console.error('Erro ao carregar a imagem:', imageUrl);
                    modalImage.src = ''; // Limpar a imagem com erro
                    modalImage.alt = 'Erro ao carregar imagem';

                    // Adicionar mensagem de erro visível
                    const modalBody = document.querySelector('#imageModal .modal-body');
                    if (modalBody) {
                        modalBody.innerHTML = `
                    <div class="alert alert-danger">
                        <strong>Erro:</strong> Não foi possível carregar a imagem.<br>
                        URL: ${imageUrl}
                    </div>
                `;
                    }
                };

                // Configurar handler para carregamento bem-sucedido
                modalImage.onload = function() {

                    // Se for um mini banner, mostrar o link abaixo da imagem
                    if (isMini && bannerCard) {
                        const linkInput = bannerCard.querySelector('.banner-link-input');
                        if (linkInput) {
                            const link = linkInput.value;
                            const modalBody = document.querySelector('#imageModal .modal-body');

                            // Criar elemento para mostrar o link
                            const linkInfo = document.createElement('div');
                            linkInfo.id = 'modalLinkInfo';
                            linkInfo.className = 'mt-3 text-center';

                            // Exibir o link
                            if (link && link.trim() !== '') {
                                linkInfo.innerHTML = `
                            <div class="alert alert-info mb-0">
                                <strong>Link:</strong> <a href="${link}" target="_blank">${link}</a>
                            </div>
                        `;
                            } else {
                                linkInfo.innerHTML = `
                            <div class="alert alert-warning mb-0">
                                <strong>Atenção:</strong> Este banner não possui um link definido.
                            </div>
                        `;
                            }

                            if (modalBody) {
                                modalBody.appendChild(linkInfo);
                            }
                        }
                    }
                };

                // Adicionar timestamp para evitar cache
                const timestamp = new Date().getTime();
                const imageUrlWithTimestamp = imageUrl.includes('?') ? imageUrl : `${imageUrl}?t=${timestamp}`;

                // Tentar carregar a imagem
                modalImage.src = imageUrlWithTimestamp;
                modalImage.alt = 'Banner ampliado';
            }

            // Função para preparar upload de imagens
            function OnChangeInput(bannerId, inputId) {
                // Verificar se já existe um modal de upload aberto
                if (isUploadModalOpen) {
                    return;
                }

                // Limpar timeout anterior se existir
                if (uploadModalTimeout) {
                    clearTimeout(uploadModalTimeout);
                }

                const fileInput = document.getElementById(inputId);
                const file = fileInput.files[0];

                if (!file) {
                    ToastManager.error('Nenhum arquivo selecionado');
                    return;
                }

                // Validação de tipos de arquivo permitidos (Cloudflare Images não suporta AVIF)
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
                if (!allowedTypes.includes(file.type)) {
                    if (file.type === 'image/avif') {
                        ToastManager.error('O formato AVIF não é suportado pelo Cloudflare Images. Use JPG, PNG, GIF, WEBP ou SVG.');
                    } else {
                        ToastManager.error('Tipo de arquivo não permitido. Use apenas imagens JPG, PNG, GIF, WEBP ou SVG.');
                    }
                    fileInput.value = '';
                    return;
                }

                // Obter o tipo do banner
                const bannerCard = document.getElementById(`form-group-${bannerId}`);
                let tipo = 'slide'; // Valor padrão

                // Determinar o tipo com base no container pai
                if (bannerCard.closest('#slide-form-container')) {
                    tipo = 'slide';
                } else if (bannerCard.closest('#mini-form-container')) {
                    tipo = 'mini';
                } else if (bannerCard.closest('#login-form-container')) {
                    tipo = 'login';
                } else if (bannerCard.closest('#register-form-container')) {
                    tipo = 'register';
                } else if (bannerCard.closest('#promo-form-container')) {
                    tipo = 'promo';
                }

                // Verificar se é mobile ou desktop
                const deviceBadge = bannerCard.querySelector('.device-type-badge .badge');
                const isMobile = deviceBadge ? deviceBadge.textContent.trim() === 'Mobile' : false;

                // Armazenar dados do banner atual
                currentBannerData = {
                    bannerId: bannerId,
                    file: file,
                    tipo: tipo,
                    isMobile: isMobile,
                    fileInput: fileInput
                };

                // Definir timeout para evitar múltiplas chamadas
                uploadModalTimeout = setTimeout(() => {
                    // Marcar que o modal está aberto
                    isUploadModalOpen = true;

                    // Mostrar modal de confirmação
                    ModalManager.showConfirmation(
                        'Confirmar Upload',
                        'Deseja realmente fazer upload desta imagem para o banner?',
                        function() {
                            // Callback de confirmação
                            processBannerImageUpload();
                            // Resetar variável de controle
                            isUploadModalOpen = false;
                        },
                        function() {
                            // Callback de cancelamento
                            fileInput.value = '';
                            // Resetar variável de controle
                            isUploadModalOpen = false;
                        }
                    );
                }, 100); // Debounce de 100ms
            }

            // Processar o upload da imagem após confirmação
            function processBannerImageUpload() {
                // Extrair dados do objeto temporário
                const { bannerId, file, tipo, isMobile, fileInput } = currentBannerData;

                // Mostrar barra de progresso
                const progressContainer = document.getElementById(`progressContainer${bannerId}`);
                const progressBar = document.getElementById(`progressBar${bannerId}`);
                progressContainer.style.display = 'block';
                progressBar.style.width = '0%';

                // Mostrar toast de "processando"
                const processingToast = ToastManager.info('Enviando imagem, aguarde...');

                // Criar FormData
                const formData = new FormData();
                formData.append('image', file);
                formData.append('id', bannerId);
                formData.append('tipo', tipo);
                formData.append('mobile', isMobile ? 'sim' : 'não');
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                // Fazer o upload via AJAX
                fetch('/admin/banner/update-image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        progressBar.style.width = '100%';

                        if (!response.ok) {
                            if (response.status === 500) {
                                throw new Error('Erro interno do servidor (500). Verifique se o diretório de upload existe e tem permissões adequadas.');
                            }
                            return response.text().then(text => {
                                throw new Error(`Erro ${response.status}: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {

                            // Atualizar a imagem exibida
                            const imgContainer = document.getElementById(`bg${bannerId}`);
                            // Remover placeholder se existir
                            const placeholder = imgContainer.querySelector('.no-image-placeholder');
                            if (placeholder) {
                                placeholder.remove();
                            }

                            // Verificar se já existe uma imagem e atualizá-la ou criar uma nova
                            let imgElement = imgContainer.querySelector('img');
                            if (!imgElement) {
                                imgElement = document.createElement('img');
                                imgElement.className = 'img-fluid';
                                imgElement.alt = 'Banner';
                                imgElement.style.objectFit = 'cover';
                                imgElement.style.width = '100%';
                                imgElement.style.height = '100%';
                                imgElement.style.position = 'absolute';
                                imgElement.style.top = '0';
                                imgElement.style.left = '0';
                                imgContainer.appendChild(imgElement);
                            }

                            // Criar um timestamp para evitar cache da imagem
                            const timestamp = new Date().getTime();

                            // Atualizar o src da imagem com a nova URL e timestamp para evitar cache
                            const imagePath = data.banner.imagem;

                            imgElement.src = `${imagePath}?t=${timestamp}`;

                            // Ativar o botão de visualização
                            const bannerCard = document.getElementById(`form-group-${bannerId}`);
                            const viewBtn = bannerCard.querySelector('.view-banner');
                            if (viewBtn) {
                                viewBtn.removeAttribute('disabled');
                                viewBtn.setAttribute('data-image', imagePath);
                                viewBtn.setAttribute('onclick', `openImageModal('${imagePath}?t=${timestamp}')`);
                            }

                            // Limpar o cache do site após a atualização da imagem
                            clearSiteCache();

                            // Mostrar mensagem de sucesso
                            ToastManager.success('Imagem enviada com sucesso!');
                        } else {
                            console.error('Erro no upload:', data);
                            ToastManager.error('Erro ao enviar imagem: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erro na requisição:', error);
                        ToastManager.error('Erro: ' + error.message);
                    })
                    .finally(() => {
                        // Remover toast de processamento
                        processingToast.remove();

                        // Resetar variáveis de controle
                        isUploadModalOpen = false;

                        // Ocultar a barra de progresso após 1 segundo
                        setTimeout(() => {
                            progressContainer.style.display = 'none';
                        }, 1000);
                    });
            }

            // Função para adicionar novo banner com confirmação
            function addNewBanner(tipo, createMobile = false) {

                // Verificar se já existe um modal de criação aberto
                if (isUploadModalOpen) {
                    return;
                }

                // Verificar se já existe um modal de seleção aberto
                const existingModal = document.getElementById('deviceSelectionModal');
                if (existingModal) {
                    return;
                }

                // Remover inputs temporários existentes para evitar conflitos
                const existingTempInputs = document.querySelectorAll('input[data-temp-input="true"]');
                existingTempInputs.forEach(input => input.remove());

                const tempFileInput = document.createElement('input');
                tempFileInput.type = 'file';
                tempFileInput.accept = 'image/*';

                // Adicionar atributo para evitar eventos duplicados
                tempFileInput.setAttribute('data-temp-input', 'true');


                // Variável para controlar se o evento já foi processado
                let eventProcessed = false;

                tempFileInput.addEventListener('change', function(e) {
                    // Verificar se o evento já foi processado
                    if (eventProcessed) {
                        return;
                    }

                    // Verificar se já existe um modal aberto
                    if (isUploadModalOpen) {
                        return;
                    }

                    // Marcar que o evento foi processado
                    eventProcessed = true;

                    const file = this.files[0];
                    if (!file) {
                        ToastManager.error('Nenhuma imagem selecionada');
                        eventProcessed = false; // Resetar se não há arquivo
                        return;
                    }

                    // Validação de tipos de arquivo permitidos (Cloudflare Images não suporta AVIF)
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
                    if (!allowedTypes.includes(file.type)) {
                        if (file.type === 'image/avif') {
                            ToastManager.error('O formato AVIF não é suportado pelo Cloudflare Images. Use JPG, PNG, GIF, WEBP ou SVG.');
                        } else {
                            ToastManager.error('Tipo de arquivo não permitido. Use apenas imagens JPG, PNG, GIF, WEBP ou SVG.');
                        }
                        eventProcessed = false; // Resetar se arquivo inválido
                        return;
                    }

                    // Marcar que o modal está sendo aberto
                    isUploadModalOpen = true;

                    // Armazenar temporariamente os dados do novo banner
                    currentBannerData = {
                        file: file,
                        tipo: tipo,
                        isMobile: createMobile,
                        fileInput: tempFileInput
                    };

                    // Verificar se o tipo de banner suporta opção mobile/desktop
                    const supportsMobileOption = ['mini', 'login', 'register'].includes(tipo);

                    // Criar conteúdo do modal com ou sem opção de dispositivo
                    let modalContent = `
                <p>Deseja criar um novo banner do tipo ${tipo}?</p>
            `;

                    // Adicionar seletor de dispositivo se o tipo suportar
                    if (supportsMobileOption) {
                        modalContent += `
                    <div class="form-group mb-3">
                        <label class="form-label"><strong>Tipo de dispositivo:</strong></label>
                        <div class="mt-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="device_type" id="deviceDesktop" value="desktop" ${!createMobile ? 'checked' : ''}>
                                <label class="form-check-label" for="deviceDesktop">
                                    <i class="fa fa-desktop me-1"></i> Desktop
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="device_type" id="deviceMobile" value="mobile" ${createMobile ? 'checked' : ''}>
                                <label class="form-check-label" for="deviceMobile">
                                    <i class="fa fa-mobile me-1"></i> Mobile
                                </label>
                            </div>
                        </div>
                    </div>
                `;
                    }

                    // Remover qualquer modal existente antes de criar um novo
                    const existingModal = document.getElementById('deviceSelectionModal');
                    if (existingModal) {
                        try {
                            document.body.removeChild(existingModal);
                        } catch (error) {
                            console.error('Erro ao remover modal existente:', error);
                        }
                    }

                    // Mostrar modal customizado com opções
                    const modal = document.createElement('div');
                    modal.className = 'modal fade';
                    modal.id = 'deviceSelectionModal';
                    modal.setAttribute('tabindex', '-1');
                    modal.setAttribute('aria-hidden', 'true');
                    modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmar Novo Banner</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ${modalContent}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" id="confirmNewBanner">Confirmar</button>
                        </div>
                    </div>
                </div>
            `;

                    document.body.appendChild(modal);


                    // Inicializar e mostrar o modal
                    const modalElement = document.getElementById('deviceSelectionModal');
                    if (!modalElement) {
                        console.error('Modal deviceSelectionModal não encontrado');
                        isUploadModalOpen = false;
                        eventProcessed = false;
                        return;
                    }

                    const bsModal = new bootstrap.Modal(modalElement);
                    bsModal.show();


                    // Adicionar listener para o botão de cancelar
                    const cancelBtn = document.querySelector('#deviceSelectionModal .btn-light-dark');
                    if (cancelBtn) {
                        cancelBtn.addEventListener('click', function() {
                            // Resetar variáveis de controle ao cancelar
                            isUploadModalOpen = false;
                            eventProcessed = false;
                        });
                    }

                    // Adicionar listener para o botão de confirmação
                    const confirmBtn = document.getElementById('confirmNewBanner');
                    if (confirmBtn) {
                        confirmBtn.addEventListener('click', function() {
                            // Se houver opção de dispositivo, atualizar o valor
                            if (supportsMobileOption) {
                                const deviceMobileRadio = document.getElementById('deviceMobile');
                                if (deviceMobileRadio) {
                                    const isMobileSelected = deviceMobileRadio.checked;
                                    currentBannerData.isMobile = isMobileSelected;
                                }
                            }

                            // Fechar o modal
                            bsModal.hide();

                            // Remover o modal do DOM após fechado
                            const modalForRemoval = document.getElementById('deviceSelectionModal');
                            if (modalForRemoval) {
                                modalForRemoval.addEventListener('hidden.bs.modal', function() {
                                    try {
                                        // Verificar se o modal ainda existe no DOM antes de tentar remover
                                        const modalElement = document.getElementById('deviceSelectionModal');
                                        if (modalElement && document.body.contains(modalElement)) {
                                            document.body.removeChild(modalElement);
                                        }
                                    } catch (error) {
                                        console.error('Erro ao remover modal no confirmNewBanner:', error);
                                    }
                                });
                            }

                            // Processar a criação do banner
                            processNewBannerCreation();
                        });
                    }

                    // Limpar recursos quando o modal for fechado
                    const modalForCleanup = document.getElementById('deviceSelectionModal');
                    if (modalForCleanup) {
                        modalForCleanup.addEventListener('hidden.bs.modal', function(e) {

                            // Aguardar um pouco para garantir que o modal esteja totalmente fechado
                            setTimeout(() => {
                                try {
                                    // Verificar se o modal ainda existe no DOM antes de tentar remover
                                    const modalElement = document.getElementById('deviceSelectionModal');
                                    if (modalElement && document.body.contains(modalElement)) {
                                        document.body.removeChild(modalElement);
                                    }
                                } catch (error) {
                                    console.error('Erro ao remover modal:', error);
                                }
                            }, 100);

                            // Limpar input temporário
                            try {
                                if (tempFileInput && tempFileInput.value) {
                                    tempFileInput.value = '';
                                }
                            } catch (error) {
                                console.error('Erro ao limpar input temporário:', error);
                            }

                            // Resetar variáveis de controle
                            isUploadModalOpen = false;
                            eventProcessed = false;
                        });
                    }
                });

                // Simular o clique no input de arquivo com timeout para evitar problemas
                setTimeout(() => {
                    tempFileInput.click();
                }, 100);

                // Limpar automaticamente o input temporário após 5 minutos se não for usado
                setTimeout(() => {
                    if (tempFileInput && tempFileInput.parentNode) {
                        tempFileInput.remove();
                    }
                }, 300000); // 5 minutos
            }

            // Processar a criação de um novo banner após confirmação
            function processNewBannerCreation() {
                // Extrair dados do objeto temporário
                const { file, tipo, isMobile, fileInput } = currentBannerData;

                // Mostrar toast de "processando"
                const processingToast = ToastManager.info('Criando novo banner, aguarde...');

                // Determinar a próxima ordem disponível
                const container = getContainerByType(tipo);
                let nextOrder = 1;
                
                if (container) {
                    if (tipo === 'mini') {
                        // Para mini banners, calcular ordem baseada no tipo de dispositivo
                        const deviceType = isMobile ? 'Mobile' : 'Desktop';
                        const sameTypeBanners = Array.from(container.querySelectorAll('.banner-card')).filter(card => {
                            const deviceBadge = card.querySelector('.device-type-badge .badge');
                            return deviceBadge && deviceBadge.textContent.trim() === deviceType;
                        });
                        nextOrder = sameTypeBanners.length + 1;
                    } else {
                        // Para outros tipos, ordem normal
                        const existingBanners = container.querySelectorAll('.banner-card').length;
                        nextOrder = existingBanners + 1;
                    }
                }

                // Criar FormData com a imagem e dados do banner
                const formData = new FormData();
                formData.append('image', file);
                formData.append('tipo', tipo);
                formData.append('mobile', isMobile ? 'sim' : 'não');
                formData.append('ordem', nextOrder);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                // Enviar para criar o banner com a imagem
                fetch('/admin/personalizacao/banners', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 500) {
                                throw new Error('Erro interno do servidor. Por favor, tente novamente.');
                            }
                            return response.text().then(text => {
                                throw new Error(`Erro ${response.status}: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Remover toast de processamento
                        processingToast.remove();

                        if (data.success) {
                            // Mostrar toast de sucesso
                            ToastManager.success(`Banner de ${tipo} criado com sucesso!`);

                            // Limpar o cache do site
                            clearSiteCache();

                            // Adicionar o novo banner ao DOM em vez de recarregar a página
                            addBannerToDOM(data.banner);

                            // Recarregar ordens do banco de dados para garantir sincronização
                            setTimeout(() => {
                                refreshOrdersFromDatabase();
                            }, 1000);

                            // Resetar variáveis de controle
                            isUploadModalOpen = false;
                        } else {
                            console.error('Erro retornado pelo servidor:', data);
                            ToastManager.error('Erro ao adicionar banner: ' + data.message);

                            // Resetar variáveis de controle
                            isUploadModalOpen = false;
                        }
                    })
                    .catch(error => {
                        // Remover toast de processamento
                        processingToast.remove();

                        // Resetar variáveis de controle
                        isUploadModalOpen = false;

                        console.error('Erro:', error);
                        ToastManager.error(error.message || 'Erro ao adicionar banner. Verifique o console para mais detalhes.');
                    });
            }

            // Função helper para obter container por tipo
            function getContainerByType(tipo) {
                const containerMap = {
                    'slide': 'slide-form-container',
                    'mini': 'mini-form-container',
                    'login': 'login-form-container',
                    'register': 'register-form-container',
                    'promo': 'promo-form-container'
                };
                
                const containerId = containerMap[tipo];
                return containerId ? document.getElementById(containerId) : null;
            }

            // Função para adicionar um novo banner ao DOM
            function addBannerToDOM(banner) {
                // Determinar o container correto com base no tipo de banner
                let containerId;
                switch (banner.tipo) {
                    case 'slide':
                        containerId = 'slide-form-container';
                        break;
                    case 'mini':
                        containerId = 'mini-form-container';
                        break;
                    case 'login':
                        containerId = 'login-form-container';
                        break;
                    case 'register':
                        containerId = 'register-form-container';
                        break;
                    case 'promo':
                        containerId = 'promo-form-container';
                        break;
                    default:
                        console.error('Tipo de banner desconhecido:', banner.tipo);
                        return;
                }

                const container = document.getElementById(containerId);
                if (!container) {
                    console.error('Container não encontrado:', containerId);
                    return;
                }

                // Obter os jogos disponíveis para o select
                const gameOptions = [];
                // Buscar todos os selects existentes para obter as opções de jogos
                const existingSelects = document.querySelectorAll('.banner-select');
                if (existingSelects.length > 0) {
                    const existingSelect = existingSelects[0];
                    const gameOptgroup = existingSelect.querySelector('optgroup[label="Jogos"]');
                    if (gameOptgroup) {
                        const options = gameOptgroup.querySelectorAll('option');
                        options.forEach(option => {
                            gameOptions.push({
                                value: option.value,
                                text: option.textContent.trim()
                            });
                        });
                    }
                }

                // Criar elemento para o novo banner
                const bannerCol = document.createElement('div');
                bannerCol.className = 'col-md-6 col-lg-4 mb-4';
                bannerCol.innerHTML = `
            <div class="banner-card highlight-new-banner" draggable="true" data-type="type-1" id="form-group-${banner.id}">
                <div class="banner-preview" style="position: relative;">
                    <div class="banner-status ${banner.active ? 'active' : 'inactive'}" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                        <span class="badge ${banner.active ? 'bg-success' : 'bg-danger'}">
                            ${banner.active ? 'Ativo' : 'Inativo'}
                        </span>
                    </div>

                    ${banner.tipo !== 'slide' && banner.tipo !== 'promo' ? `
                    <div class="device-type-badge" style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                        <span class="badge bg-primary">
                            ${banner.mobile === 'sim' ? 'Mobile' : 'Desktop'}
                        </span>
                    </div>
                    ` : ''}

                    <div id="bg${banner.id}" class="banner-image d-flex align-items-center justify-content-center"
                        style="height: 200px; border-radius: 8px; cursor: pointer; overflow: hidden; position: relative; background-color: #f8f9fa;"
                        onclick="document.getElementById('ff${banner.id}').click();">
                        ${banner.imagem ? `
                            <img src="${banner.imagem}"
                                alt="Banner" class="img-fluid"
                                style="object-fit: cover; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
                        ` : `
                            <div class="no-image-placeholder d-flex align-items-center justify-content-center h-100 w-100">
                                <i class="fa fa-image fa-3x text-muted"></i>
                            </div>
                        `}
                    </div>
                                                        <input id="ff${banner.id}" type="file" style="display: none;"
                                        class="form-control file-input-banner">
                </div>

                ${(banner.tipo === 'slide' || banner.tipo === 'mini' || banner.tipo === 'promo') ? `
                <div class="banner-link-container mt-2">
                    <div class="link-options-container mb-2">
                        <label class="form-label"><strong>Link do Banner:</strong></label>
                        <select id="banner_link_${banner.id}" class="banner-select" placeholder="Digite URL ou selecione um jogo..." data-id="${banner.id}">
                            <option value="">Digite URL ou selecione um jogo...</option>
                            <optgroup label="Jogos">
                                ${gameOptions.map(game => `<option value="${game.value}">${game.text}</option>`).join('')}
                            </optgroup>
                        </select>
                    </div>
                </div>
                ` : ''}

                <div class="banner-order-controls mt-2 mb-2">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <label class="form-label mb-1"><strong>Ordem de Exibição:</strong></label>
                            <input type="number" class="form-control form-control-sm banner-order-input" 
                                   data-id="${banner.id}" 
                                   value="${banner.ordem || 1}" 
                                   min="1" max="999" step="1"
                                   placeholder="Ex: 1, 2, 3... (duplicatas OK)"
                                   title="Números duplicados são permitidos">
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                ${banner.tipo === 'promo' ? 'Banner único' : `
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-move me-1"><polyline points="5 9 2 12 5 15"></polyline><polyline points="9 5 12 2 15 5"></polyline><polyline points="15 19 12 22 9 19"></polyline><polyline points="19 9 22 12 19 15"></polyline><line x1="2" y1="12" x2="22" y2="12"></line><line x1="12" y1="2" x2="12" y2="22"></line></svg>
                                Auto-save ao sair do campo
                                `}
                            </small>
                        </div>
                    </div>
                </div>

                <div class="banner-controls mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input banner-toggle" type="checkbox" data-id="${banner.id}" ${banner.active ? 'checked' : ''}>
                            <label class="form-check-label">${banner.active ? 'Ativo' : 'Desativado'}</label>
                        </div>
                        <div class="banner-actions">
                            <button type="button" class="btn btn-sm btn-info me-2 view-banner" title="Visualizar"
                                data-image="${banner.imagem}"
                                onclick="openImageModal('${banner.imagem ? (banner.imagem.startsWith('/') ? banner.imagem : '/' + banner.imagem) : ''}')"
                                ${banner.imagem ? '' : 'disabled'}>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-banner" data-id="${banner.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="progressContainer${banner.id}" class="progress mt-2"
                    style="${banner.imagem ? 'display: none;' : ''}">
                    <div id="progressBar${banner.id}" class="progress-bar"
                        role="progressbar" style="width: 0%;"
                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
        `;

                // Adicionar ao container
                container.appendChild(bannerCol);

                // Atualizar contadores
                updateBannerCounters(banner.tipo);

                // Se for mini banner, garantir que apareça no filtro ativo
                if (banner.tipo === 'mini') {
                    // Pequeno delay para garantir que o DOM foi atualizado
                    setTimeout(() => {
                        updateMiniBannerCounts();
                    }, 100);
                }

                // Adicionar event listeners para o novo banner
                const newToggle = bannerCol.querySelector('.banner-toggle');
                if (newToggle) {
                    newToggle.addEventListener('change', function() {
                        const bannerId = this.getAttribute('data-id');
                        const isActive = this.checked;
                        toggleBannerStatus(bannerId, isActive);
                    });
                    // Marcar que o event listener foi adicionado
                    newToggle.setAttribute('data-listener-added', 'true');
                }

                const newDeleteBtn = bannerCol.querySelector('.delete-banner');
                if (newDeleteBtn) {
                    newDeleteBtn.addEventListener('click', function() {
                        const bannerId = this.getAttribute('data-id');
                        deleteBanner(bannerId);
                    });
                    // Marcar que o event listener foi adicionado
                    newDeleteBtn.setAttribute('data-listener-added', 'true');
                }

                // Adicionar event listener para o input de arquivo
                const newFileInput = bannerCol.querySelector('input[type="file"]');
                if (newFileInput) {
                    newFileInput.addEventListener('change', function(e) {
                        // Verificar se o modal já está aberto
                        if (isUploadModalOpen) {
                            return;
                        }

                        const inputId = this.id;
                        const bannerId = inputId.replace('ff', '');
                        OnChangeInput(bannerId, inputId);
                    });
                    // Marcar que o event listener foi adicionado
                    newFileInput.setAttribute('data-listener-added', 'true');
                }

                // Adicionar event listeners para o input de ordem (auto-save)
                const newOrderInput = bannerCol.querySelector('.banner-order-input');
                if (newOrderInput) {
                    // Auto-save ao sair do campo (blur)
                    newOrderInput.addEventListener('blur', function() {
                        const bannerId = this.getAttribute('data-id');
                        const ordem = parseInt(this.value);
                        
                        if (ordem && ordem >= 1) {
                            updateBannerOrderAutoSave(bannerId, ordem, this);
                        } else if (this.value.trim() !== '') {
                            showOrderError(this, 'Ordem deve ser um número válido (1 ou maior)');
                        }
                    });

                    // Auto-save ao pressionar Enter
                    newOrderInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            this.blur(); // Dispara o evento blur
                        }
                    });

                    // Limpar indicadores visuais ao começar a digitar
                    newOrderInput.addEventListener('input', function() {
                        this.classList.remove('saving', 'error');
                    });

                    newOrderInput.setAttribute('data-listener-added', 'true');
                }

                // Inicializar TomSelect para o novo banner se tiver select
                const newSelect = bannerCol.querySelector('.banner-select');
                if (newSelect) {
                    // Garantir que o TomSelect seja inicializado após o DOM estar totalmente carregado
                    setTimeout(() => {
                        if (!newSelect.tomselect) { // Verificar se já foi inicializado
                            new TomSelect(newSelect, {
                                create: true,
                                sortField: {
                                    field: "text",
                                    direction: "asc"
                                },
                                createFilter: function(input) {
                                    // Permitir criação apenas se parece uma URL
                                    return input.length > 0 && (input.startsWith('http://') || input.startsWith('https://') || input.startsWith('/'));
                                },
                                maxItems: 1,
                                valueField: 'value',
                                labelField: 'text',
                                searchField: ['text'],
                                allowEmptyOption: true,
                                placeholder: 'Digite URL ou selecione um jogo...',
                                persist: false,
                                createOnBlur: false,
                                highlight: true,
                                openOnFocus: true,
                                hideSelected: false,
                                closeAfterSelect: true,
                                searchConjunction: 'or',
                                copyClassesToDropdown: true,
                                dropdownParent: 'body',
                                render: {
                                    option: function(data, escape) {
                                        return '<div class="py-2 px-3">' + escape(data.text) + '</div>';
                                    },
                                    item: function(data, escape) {
                                        return '<div>' + escape(data.text) + '</div>';
                                    },
                                    option_create: function(data, escape) {
                                        return '<div class="create">Usar como URL: <strong>' + escape(data.input) + '</strong></div>';
                                    },
                                    no_results: function(data, escape) {
                                        return '<div class="no-results">Digite uma URL válida (http:// ou https://) ou selecione um jogo</div>';
                                    }
                                },
                                onChange: function(value) {
                                    if (value) {
                                        const bannerId = newSelect.getAttribute('data-id');
                                        updateBannerLink(bannerId, value);
                                    }
                                }
                            });
                        }
                    }, 100);
                }

                // Scroll para o novo banner
                setTimeout(() => {
                    bannerCol.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);

                // Remover classe de destaque após alguns segundos
                setTimeout(() => {
                    bannerCol.querySelector('.banner-card').classList.remove('highlight-new-banner');
                }, 5000);
            }

            // Função para atualizar os contadores de banners
            function updateBannerCounters(tipo) {
                let countElement, activeCountElement;

                switch (tipo) {
                    case 'slide':
                        const slideContainer = document.getElementById('slide-form-container');
                        const totalSlides = slideContainer.querySelectorAll('.banner-card').length;
                        const activeSlides = slideContainer.querySelectorAll('.banner-status.active').length;

                        document.querySelector('#iconAccordionOne .alert strong:first-child').nextSibling.nodeValue = ` ${totalSlides}`;
                        document.querySelector('#iconAccordionOne .alert strong:last-child').nextSibling.nodeValue = ` ${activeSlides}`;
                        break;

                    case 'mini':
                        const miniContainer = document.getElementById('mini-form-container');
                        const totalMinis = miniContainer.querySelectorAll('.banner-card').length;
                        const activeMinis = miniContainer.querySelectorAll('.banner-status.active').length;

                        // Atualizar contadores principais
                        const miniTotalCount = document.getElementById('mini-total-count');
                        const miniActiveCount = document.getElementById('mini-active-count');
                        if (miniTotalCount) miniTotalCount.textContent = totalMinis;
                        if (miniActiveCount) miniActiveCount.textContent = activeMinis;

                        // Atualizar filtro e contadores detalhados
                        updateMiniBannerCounts();
                        break;

                    case 'login':
                        const loginContainer = document.getElementById('login-form-container');
                        const totalLogins = loginContainer.querySelectorAll('.banner-card').length;
                        const activeLogins = loginContainer.querySelectorAll('.banner-status.active').length;

                        document.querySelector('#iconAccordionThree .alert strong:first-child').nextSibling.nodeValue = ` ${totalLogins}`;
                        document.querySelector('#iconAccordionThree .alert strong:last-child').nextSibling.nodeValue = ` ${activeLogins}`;
                        break;

                    case 'register':
                        const registerContainer = document.getElementById('register-form-container');
                        const totalRegisters = registerContainer.querySelectorAll('.banner-card').length;
                        const activeRegisters = registerContainer.querySelectorAll('.banner-status.active').length;

                        document.querySelector('#iconAccordionFour .alert strong:first-child').nextSibling.nodeValue = ` ${totalRegisters}`;
                        document.querySelector('#iconAccordionFour .alert strong:last-child').nextSibling.nodeValue = ` ${activeRegisters}`;
                        break;

                    case 'promo':
                        const promoContainer = document.getElementById('promo-form-container');
                        const totalPromos = promoContainer.querySelectorAll('.banner-card').length;
                        const activePromos = promoContainer.querySelectorAll('.banner-status.active').length;

                        // Atualizar texto e classe do alerta
                        const promoAlert = document.querySelector('#iconAccordionFive .alert');
                        if (promoAlert) {
                            if (totalPromos > 0) {
                                promoAlert.classList.remove('alert-warning');
                                promoAlert.classList.add('alert-info');
                                document.querySelector('#iconAccordionFive .alert strong:first-child').nextSibling.nodeValue = ' Configurado';
                            } else {
                                promoAlert.classList.remove('alert-info');
                                promoAlert.classList.add('alert-warning');
                                document.querySelector('#iconAccordionFive .alert strong:first-child').nextSibling.nodeValue = ' Não configurado';
                            }

                            document.querySelector('#iconAccordionFive .alert strong:last-child').nextSibling.nodeValue = activePromos > 0 ? ' Ativo' : ' Inativo';
                        }

                        // Desativar o botão de adicionar se já existir um banner promo
                        const addPromoBtn = document.getElementById('add-promo-btn');
                        if (addPromoBtn) {
                            addPromoBtn.disabled = totalPromos > 0;
                        }
                        break;
                }
            }

            // Função para excluir banner com confirmação
            function deleteBanner(bannerId) {
                ModalManager.showConfirmation(
                    'Confirmar Exclusão',
                    'Tem certeza que deseja excluir este banner?',
                    function() {
                        // Callback de confirmação
                        processDeleteBanner(bannerId);
                    }
                );
            }

            // Processar a exclusão de um banner após confirmação
            function processDeleteBanner(bannerId) {
                // Mostrar toast de "processando"
                const processingToast = ToastManager.info('Excluindo banner, aguarde...');

                // Chamada AJAX para excluir o banner
                fetch('/admin/banner/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id: bannerId
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 500) {
                                throw new Error('Erro interno do servidor. Por favor, tente novamente.');
                            }
                            return response.text().then(text => {
                                throw new Error(`Erro ${response.status}: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Remover toast de processamento
                        processingToast.remove();

                        if (data.success) {

                            // Verificar se é mini banner antes de remover
                            const bannerCard = document.querySelector(`#form-group-${bannerId}`);
                            const isMini = bannerCard && bannerCard.closest('#mini-form-container');

                            // Remove o elemento do DOM
                            const bannerCol = bannerCard.closest('.col-md-6');
                            bannerCol.remove();

                            // Limpar o cache do site
                            clearSiteCache();

                            // Se for mini banner, atualizar contadores
                            if (isMini) {
                                updateMiniBannerCounts();
                            }

                            // Recarregar ordens do banco de dados após exclusão
                            setTimeout(() => {
                                refreshOrdersFromDatabase();
                            }, 500);

                            // Exibe mensagem de sucesso
                            ToastManager.success('Banner excluído com sucesso');
                        } else {
                            console.error('Erro retornado pelo servidor:', data);
                            ToastManager.error('Erro ao excluir banner: ' + data.message);
                        }
                    })
                    .catch(error => {
                        // Remover toast de processamento
                        processingToast.remove();

                        console.error('Erro ao excluir banner:', error);
                        ToastManager.error(error.message || 'Erro ao excluir banner. Verifique o console para mais detalhes.');
                    });
            }

            // Função para alternar status do banner
            function toggleBannerStatus(bannerId, isActive) {
                // Chamada AJAX para atualizar o status
                fetch('/admin/banner/toggle-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        id: bannerId,
                        active: isActive
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Atualiza o indicador visual
                            const statusIndicator = document.querySelector(`#form-group-${bannerId} .banner-status`);
                            const statusBadge = statusIndicator.querySelector('.badge');
                            const label = document.querySelector(`#form-group-${bannerId} .form-check-label`);

                            if (isActive) {
                                statusIndicator.classList.remove('inactive');
                                statusIndicator.classList.add('active');
                                statusBadge.classList.remove('bg-danger');
                                statusBadge.classList.add('bg-success');
                                statusBadge.textContent = 'Ativo';
                                label.textContent = 'Ativo';
                                ToastManager.success('Banner ativado com sucesso!');
                            } else {
                                statusIndicator.classList.remove('active');
                                statusIndicator.classList.add('inactive');
                                statusBadge.classList.remove('bg-success');
                                statusBadge.classList.add('bg-danger');
                                statusBadge.textContent = 'Inativo';
                                label.textContent = 'Desativado';
                                ToastManager.success('Banner desativado com sucesso!');
                            }

                            // Limpar o cache do site após a alteração do status
                            clearSiteCache();

                            // Se for mini banner, atualizar contadores
                            const bannerCard = document.querySelector(`#form-group-${bannerId}`);
                            if (bannerCard && bannerCard.closest('#mini-form-container')) {
                                updateMiniBannerCounts();
                            }

                            // Sincronizar ordens após mudança de status
                            setTimeout(() => {
                                refreshOrdersFromDatabase();
                            }, 300);
                        } else {
                            ToastManager.error('Erro ao atualizar status do banner.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao atualizar status:', error);
                        ToastManager.error('Ocorreu um erro ao atualizar o status do banner.');
                    });
            }

            // Função para atualizar o link do banner via AJAX
            function updateBannerLink(bannerId, link) {
                // Mostrar toast de "processando"
                const processingToast = ToastManager.info('Atualizando link, aguarde...');

                // Chamada AJAX para atualizar o link
                fetch('/admin/personalizacao/banners/' + bannerId, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        link: link
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 500) {
                                throw new Error('Erro interno do servidor. Por favor, tente novamente.');
                            }
                            return response.text().then(text => {
                                throw new Error(`Erro ${response.status}: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Remover toast de processamento
                        processingToast.remove();

                        if (data.success) {

                            // Exibe mensagem de sucesso
                            ToastManager.success('Link atualizado com sucesso');

                            // Limpar o cache do site
                            clearSiteCache();
                        } else {
                            console.error('Erro retornado pelo servidor:', data);
                            ToastManager.error('Erro ao atualizar link: ' + data.message);
                        }
                    })
                    .catch(error => {
                        // Remover toast de processamento
                        processingToast.remove();

                        console.error('Erro ao atualizar link:', error);
                        ToastManager.error(error.message || 'Erro ao atualizar link. Verifique o console para mais detalhes.');
                    });
            }

            // Função para atualizar a ordem do banner via AJAX (auto-save)
            function updateBannerOrderAutoSave(bannerId, ordem, inputElement) {
                // Mostrar indicador visual no campo
                inputElement.classList.add('saving');

                // Chamada AJAX para atualizar a ordem
                fetch('/admin/personalizacao/banners/' + bannerId, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        ordem: ordem
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 500) {
                                throw new Error('Erro interno do servidor. Por favor, tente novamente.');
                            }
                            return response.text().then(text => {
                                throw new Error(`Erro ${response.status}: ${text}`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        inputElement.classList.remove('saving');

                        if (data.success) {
                            // Mostrar indicador de sucesso discreto
                            inputElement.classList.add('saving');
                            setTimeout(() => {
                                inputElement.classList.remove('saving');
                            }, 1000);

                            // Limpar o cache do site
                            clearSiteCache();

                            // Recarregar ordens do banco de dados
                            refreshOrdersFromDatabase();
                        } else {
                            showOrderError(inputElement, 'Erro ao salvar ordem');
                        }
                    })
                    .catch(error => {
                        inputElement.classList.remove('saving');
                        showOrderError(inputElement, 'Erro de conexão');
                        console.error('Erro ao atualizar ordem:', error);
                    });
            }

            // Função para mostrar erro no campo de ordem
            function showOrderError(inputElement, message) {
                inputElement.classList.add('error');
                inputElement.title = message + ' (duplicatas são permitidas)';
                
                // Remover indicador de erro após 3 segundos
                setTimeout(() => {
                    inputElement.classList.remove('error');
                    inputElement.title = '';
                }, 3000);
            }

            // Função DEPRECATED: substituída por reorderBannersFromDatabase
            // Mantida para compatibilidade, mas não deve ser usada
            function reorderBannersInInterface() {
                console.warn('reorderBannersInInterface() está deprecated. Use refreshOrdersFromDatabase() em vez disso.');
                refreshOrdersFromDatabase();
            }

            // Variável para verificar se já foi inicializado
            if (typeof bannerPageInitialized === 'undefined') {
                var bannerPageInitialized = false;
            }

                            // Inicialização quando o DOM estiver pronto
            document.addEventListener('DOMContentLoaded', function() {
                // Verificar se já foi inicializado
                if (bannerPageInitialized) {
                    return;
                }

                // Marcar como inicializado
                bannerPageInitialized = true;

                // Debug: verificar se os selects existem
                const bannerSelects = document.querySelectorAll('.banner-select');

                if (typeof ModalManager === 'undefined') {
                    window.ModalManager = {
                        showConfirmation: function(title, message, confirmCallback, cancelCallback) {
                            // Verificar se já existe um modal de confirmação aberto
                            if (document.querySelector('.modal.show')) {
                                return;
                            }

                            if (confirm(message)) {
                                if (confirmCallback) confirmCallback();
                            } else {
                                if (cancelCallback) cancelCallback();
                            }
                        }
                    };
                }

                // Botões para adicionar banners
                document.getElementById('add-slide-btn')?.addEventListener('click', function() {
                    // Verificar se já existe um processo de criação em andamento
                    if (isUploadModalOpen) {
                        return;
                    }

                    // Verificar limite de banners ativos (máximo 6)
                    const activeSlides = document.querySelectorAll('#slide-form-container .banner-status.active').length;
                    if (activeSlides >= 6) {
                        ToastManager.error('Atenção: Você já tem 6 banners ativos. Desative pelo menos um antes de adicionar um novo.');
                        return;
                    }

                    addNewBanner('slide');
                });

                document.getElementById('add-minibanner-btn')?.addEventListener('click', function() {
                    // Verificar se já existe um processo de criação em andamento
                    if (isUploadModalOpen) {
                        return;
                    }

                    // Verificar limite de mini banners ativos apenas para desktop
                    let activeMiniDesktop = 0;

                    const activeMiniBanners = document.querySelectorAll('#mini-form-container .banner-status.active');
                    activeMiniBanners.forEach(function(status) {
                        const card = status.closest('.banner-card');
                        const badge = card.querySelector('.device-type-badge .badge');
                        if (badge && badge.textContent.trim() === 'Desktop') {
                            activeMiniDesktop++;
                        }
                    });

                    const desktopLimit = 3;

                    // Verificar apenas limite de desktop
                    if (activeMiniDesktop >= desktopLimit) {
                        // Se desktop atingiu o limite, criar mobile
                        ToastManager.info(`Limite de mini banners desktop atingido (${desktopLimit}). Criando mini banner mobile.`);
                        addNewBanner('mini', true); // true = mobile
                    } else {
                        // Se desktop não atingiu o limite, criar desktop
                        addNewBanner('mini', false); // false = desktop
                    }
                });

                document.getElementById('add-login-btn')?.addEventListener('click', function() {
                    // Verificar se já existe um processo de criação em andamento
                    if (isUploadModalOpen) {
                        return;
                    }

                    // Verificar banners de login ativos
                    let activeLoginDesktop = 0;
                    let activeLoginMobile = 0;

                    const activeLoginBanners = document.querySelectorAll('#login-form-container .banner-status.active');
                    activeLoginBanners.forEach(function(status) {
                        const card = status.closest('.banner-card');
                        const badge = card.querySelector('.device-type-badge .badge');
                        if (badge && badge.textContent.trim() === 'Desktop') {
                            activeLoginDesktop++;
                        } else if (badge && badge.textContent.trim() === 'Mobile') {
                            activeLoginMobile++;
                        }
                    });

                    // Verificar se já existe um desktop e um mobile ativos
                    const hasActiveDesktop = activeLoginDesktop > 0;
                    const hasActiveMobile = activeLoginMobile > 0;

                    if (hasActiveDesktop && hasActiveMobile) {
                        ToastManager.error('Atenção: Você já tem um banner de login ativo para desktop e um para mobile.');
                        return;
                    }

                    // Determinar se deve ser criado mobile ou desktop
                    const createMobile = hasActiveDesktop && !hasActiveMobile;

                    addNewBanner('login', createMobile);
                });

                document.getElementById('add-register-btn')?.addEventListener('click', function() {
                    // Verificar se já existe um processo de criação em andamento
                    if (isUploadModalOpen) {
                        return;
                    }

                    // Verificar banners de registro ativos
                    let activeRegisterDesktop = 0;
                    let activeRegisterMobile = 0;

                    const activeRegisterBanners = document.querySelectorAll('#register-form-container .banner-status.active');
                    activeRegisterBanners.forEach(function(status) {
                        const card = status.closest('.banner-card');
                        const badge = card.querySelector('.device-type-badge .badge');
                        if (badge && badge.textContent.trim() === 'Desktop') {
                            activeRegisterDesktop++;
                        } else if (badge && badge.textContent.trim() === 'Mobile') {
                            activeRegisterMobile++;
                        }
                    });

                    // Verificar se já existe um desktop e um mobile ativos
                    const hasActiveDesktop = activeRegisterDesktop > 0;
                    const hasActiveMobile = activeRegisterMobile > 0;

                    if (hasActiveDesktop && hasActiveMobile) {
                        ToastManager.error('Atenção: Você já tem um banner de registro ativo para desktop e um para mobile.');
                        return;
                    }

                    // Determinar se deve ser criado mobile ou desktop
                    const createMobile = hasActiveDesktop && !hasActiveMobile;

                    addNewBanner('register', createMobile);
                });

                // Configurar listeners para os botões de exclusão
                document.querySelectorAll('.delete-banner').forEach(btn => {
                    // Verificar se já tem event listener para evitar duplicação
                    if (!btn.hasAttribute('data-listener-added')) {
                        btn.addEventListener('click', function() {
                            const bannerId = this.getAttribute('data-id');
                            deleteBanner(bannerId);
                        });
                        btn.setAttribute('data-listener-added', 'true');
                    }
                });

                // Configurar listeners para os toggles de status
                document.querySelectorAll('.banner-toggle').forEach(toggle => {
                    // Verificar se já tem event listener para evitar duplicação
                    if (!toggle.hasAttribute('data-listener-added')) {
                        toggle.addEventListener('change', function() {
                            const bannerId = this.getAttribute('data-id');
                            const isActive = this.checked;
                            toggleBannerStatus(bannerId, isActive);
                        });
                        toggle.setAttribute('data-listener-added', 'true');
                    }
                });

                // Adicionar event listener para botões de salvar link
                document.querySelectorAll('.save-link-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const bannerId = this.getAttribute('data-id');
                        const linkInput = document.querySelector(`.banner-link-input[data-id="${bannerId}"]`);
                        const link = linkInput.value;
                        updateBannerLink(bannerId, link);
                    });
                });

                // Adicionar event listeners para inputs de ordem (auto-save)
                document.querySelectorAll('.banner-order-input').forEach(input => {
                    // Auto-save ao sair do campo (blur)
                    input.addEventListener('blur', function() {
                        const bannerId = this.getAttribute('data-id');
                        const ordem = parseInt(this.value);
                        
                        if (ordem && ordem >= 1) {
                            updateBannerOrderAutoSave(bannerId, ordem, this);
                        } else if (this.value.trim() !== '') {
                            showOrderError(this, 'Ordem deve ser um número válido (1 ou maior)');
                        }
                    });

                    // Auto-save ao pressionar Enter
                    input.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            this.blur(); // Dispara o evento blur
                        }
                    });

                    // Limpar indicadores visuais ao começar a digitar
                    input.addEventListener('input', function() {
                        this.classList.remove('saving', 'error');
                    });
                });

                // Adicionar event listener para tecla Enter nos inputs de link
                document.querySelectorAll('.banner-link-input').forEach(input => {
                    input.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const bannerId = this.getAttribute('data-id');
                            updateBannerLink(bannerId, this.value);
                        }
                    });
                });

                document.getElementById('add-promo-btn')?.addEventListener('click', function() {
                    // Verificar se já existe um processo de criação em andamento
                    if (isUploadModalOpen) {
                        return;
                    }

                    // Verificar se já existe um banner promocional
                    const promoBanners = document.querySelectorAll('#promo-form-container .banner-card').length;
                    if (promoBanners > 0) {
                        ToastManager.error('Atenção: Só é permitido um banner promocional. Exclua o existente antes de adicionar um novo.');
                        return;
                    }

                    addNewBanner('promo');
                });

                // Adicionar event listeners para radio buttons de tipo de link
                document.querySelectorAll('.link-type-selector').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const bannerId = this.name.replace('link_type_', '');
                        const linkType = this.value;

                        // Obter os elementos de container
                        const urlContainer = document.getElementById(`link_url_container_${bannerId}`);
                        const gameContainer = document.getElementById(`game_select_container_${bannerId}`);

                        if (linkType === 'url') {
                            // Mostrar o container de URL e esconder o de jogo
                            urlContainer.style.display = 'block';
                            gameContainer.style.display = 'none';
                        } else if (linkType === 'game') {
                            // Mostrar o container de jogo e esconder o de URL
                            urlContainer.style.display = 'none';
                            gameContainer.style.display = 'block';
                        }
                    });
                });

                // Verificar estado atual dos radio buttons e aplicar a visibilidade correta
                document.querySelectorAll('.link-type-selector:checked').forEach(radio => {
                    const bannerId = radio.name.replace('link_type_', '');
                    const linkType = radio.value;

                    const urlContainer = document.getElementById(`link_url_container_${bannerId}`);
                    const gameContainer = document.getElementById(`game_select_container_${bannerId}`);

                    if (linkType === 'url') {
                        urlContainer.style.display = 'block';
                        gameContainer.style.display = 'none';
                    } else if (linkType === 'game') {
                        urlContainer.style.display = 'none';
                        gameContainer.style.display = 'block';
                    }
                });

                // Verificar se TomSelect está disponível
                if (typeof TomSelect === 'undefined') {
                    console.error('TomSelect não está carregado! Verifique se os scripts estão incluídos.');
                    return;
                }

                // Inicializar seletores de banner
                initializeBannerSelectors();

                // Inicializar funcionalidade de drag and drop
                initializeDragAndDrop();

                // Inicializar filtros de mini banners
                initializeMiniBannerFilter();

                // Converter controles de ordem existentes para auto-save
                convertExistingOrderControls();

                // Sincronizar ordens com banco de dados
                syncOrdersWithDatabase();

                // Verificar e prevenir event listeners duplicados nos inputs de arquivo
                document.querySelectorAll('input[type="file"]').forEach(input => {
                    // Verificar se já tem event listener para evitar duplicação
                    if (!input.hasAttribute('data-listener-added')) {
                        // Remover qualquer event listener inline existente
                        input.removeAttribute('onchange');

                        // Adicionar event listener via JavaScript com verificação adicional
                        input.addEventListener('change', function(e) {
                            // Verificar se o modal já está aberto
                            if (isUploadModalOpen) {
                                return;
                            }

                            const inputId = this.id;
                            const bannerId = inputId.replace('ff', '');
                            OnChangeInput(bannerId, inputId);
                        });

                        input.setAttribute('data-listener-added', 'true');
                    }
                });
            });

            // Função para inicializar os selectores TomSelect de banners
            function initializeBannerSelectors() {
                // Aguardar um pouco para garantir que o DOM esteja totalmente carregado
                setTimeout(() => {
                    document.querySelectorAll('.banner-select').forEach(select => {
                        try {
                            // Verificar se o elemento existe e não foi inicializado
                            if (!select || select.tomselect) {
                                return;
                            }

                            // Verificar se o elemento está no DOM
                            if (!document.contains(select)) {
                                return;
                            }

                            // Limpar qualquer instância anterior
                            if (select.tomselect) {
                                select.tomselect.destroy();
                            }

                            new TomSelect(select, {
                                create: true,
                                sortField: {
                                    field: "text",
                                    direction: "asc"
                                },
                                createFilter: function(input) {
                                    // Permitir criação apenas se parece uma URL
                                    return input && input.length > 0 && (input.startsWith('http://') || input.startsWith('https://') || input.startsWith('/'));
                                },
                                maxItems: 1,
                                valueField: 'value',
                                labelField: 'text',
                                searchField: ['text'],
                                allowEmptyOption: true,
                                placeholder: 'Digite URL ou selecione um jogo...',
                                persist: false,
                                createOnBlur: false,
                                highlight: true,
                                openOnFocus: true,
                                hideSelected: false,
                                closeAfterSelect: true,
                                searchConjunction: 'or',
                                copyClassesToDropdown: true,
                                dropdownParent: 'body',
                                render: {
                                    option: function(data, escape) {
                                        return '<div class="py-2 px-3">' + escape(data.text) + '</div>';
                                    },
                                    item: function(data, escape) {
                                        return '<div>' + escape(data.text) + '</div>';
                                    },
                                    option_create: function(data, escape) {
                                        if (!data || !data.input) return '';
                                        return '<div class="create">Usar como URL: <strong>' + escape(data.input) + '</strong></div>';
                                    },
                                    no_results: function(data, escape) {
                                        return '<div class="no-results">Digite uma URL válida (http:// ou https://) ou selecione um jogo</div>';
                                    }
                                },
                                onChange: function(value) {
                                    if (value && value.trim() !== '') {
                                        const bannerId = select.getAttribute('data-id');
                                        if (bannerId) {
                                            updateBannerLink(bannerId, value);
                                        }
                                    }
                                }
                            });

                        } catch (error) {
                            console.error('Erro ao inicializar TomSelect para:', select.id, error);
                        }
                    });
                }, 100);
            }

            // Função para inicializar drag and drop
            function initializeDragAndDrop() {
                const containers = [
                    { id: 'slide-form-container', tipo: 'slide' },
                    { id: 'mini-form-container', tipo: 'mini' },
                    { id: 'login-form-container', tipo: 'login' },
                    { id: 'register-form-container', tipo: 'register' }
                    // Note: Promo banners não têm drag and drop pois só há um
                ];

                containers.forEach(containerInfo => {
                    const container = document.getElementById(containerInfo.id);
                    if (!container) return;

                    // Configuração especial para mini banners
                    if (containerInfo.tipo === 'mini') {
                        // Para mini banners, permitir drag apenas entre banners do mesmo tipo de dispositivo
                        new Sortable(container, {
                            animation: 150,
                            ghostClass: 'sortable-ghost',
                            chosenClass: 'sortable-chosen',
                            dragClass: 'sortable-drag',
                            handle: '.banner-card',
                            onMove: function(evt) {
                                // Verificar se os banners são do mesmo tipo de dispositivo
                                const draggedCard = evt.dragged;
                                const relatedCard = evt.related;
                                
                                const draggedDeviceBadge = draggedCard.querySelector('.device-type-badge .badge');
                                const relatedDeviceBadge = relatedCard.querySelector('.device-type-badge .badge');
                                
                                if (!draggedDeviceBadge || !relatedDeviceBadge) return true;
                                
                                const draggedType = draggedDeviceBadge.textContent.trim();
                                const relatedType = relatedDeviceBadge.textContent.trim();
                                
                                // Permitir movimento apenas entre banners do mesmo tipo
                                return draggedType === relatedType;
                            },
                            onEnd: function(evt) {
                                updateBannerOrdersAfterDrag(containerInfo.tipo, container);
                            }
                        });
                    } else {
                        // Configuração normal para outros tipos
                        new Sortable(container, {
                            animation: 150,
                            ghostClass: 'sortable-ghost',
                            chosenClass: 'sortable-chosen',
                            dragClass: 'sortable-drag',
                            handle: '.banner-card',
                            onEnd: function(evt) {
                                updateBannerOrdersAfterDrag(containerInfo.tipo, container);
                            }
                        });
                    }
                });
            }

                        // Função para atualizar ordens após drag and drop
            function updateBannerOrdersAfterDrag(tipo, container) {
                const bannerCards = Array.from(container.children);
                const orders = {};

                if (tipo === 'mini') {
                    // Para mini banners, separar por tipo de dispositivo e ordenar cada grupo
                    const desktopBanners = [];
                    const mobileBanners = [];
                    
                    bannerCards.forEach(card => {
                        const deviceBadge = card.querySelector('.device-type-badge .badge');
                        const deviceType = deviceBadge ? deviceBadge.textContent.trim() : 'Desktop';
                        
                        if (deviceType === 'Mobile') {
                            mobileBanners.push(card);
                        } else {
                            desktopBanners.push(card);
                        }
                    });
                    
                    // Atualizar ordens para desktop (começando do 1)
                    desktopBanners.forEach((card, index) => {
                        const bannerId = card.querySelector('.banner-toggle')?.getAttribute('data-id');
                        if (bannerId) {
                            const newOrder = index + 1;
                            orders[bannerId] = newOrder;
                            
                            const orderInput = card.querySelector('.banner-order-input');
                            if (orderInput) {
                                orderInput.value = newOrder;
                            }
                        }
                    });
                    
                    // Atualizar ordens para mobile (começando do 1)
                    mobileBanners.forEach((card, index) => {
                        const bannerId = card.querySelector('.banner-toggle')?.getAttribute('data-id');
                        if (bannerId) {
                            const newOrder = index + 1;
                            orders[bannerId] = newOrder;
                            
                            const orderInput = card.querySelector('.banner-order-input');
                            if (orderInput) {
                                orderInput.value = newOrder;
                            }
                        }
                    });
                    
                } else {
                    // Para outros tipos de banner, ordenação normal
                    bannerCards.forEach((card, index) => {
                        const bannerId = card.querySelector('.banner-toggle')?.getAttribute('data-id');
                        if (bannerId) {
                            const newOrder = index + 1;
                            orders[bannerId] = newOrder;
                            
                            // Atualizar o input de ordem na interface
                            const orderInput = card.querySelector('.banner-order-input');
                            if (orderInput) {
                                orderInput.value = newOrder;
                            }
                        }
                    });
                }

                // Enviar as atualizações para o servidor
                if (Object.keys(orders).length > 0) {
                    const processingToast = ToastManager.info('Atualizando ordens dos banners...');
                    
                    fetch('/admin/banner/update-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            orders: orders
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        processingToast.remove();
                        
                        if (data.success) {
                            ToastManager.success('Ordens atualizadas! Duplicatas são permitidas.');
                            clearSiteCache();
                            
                            // Recarregar ordens do banco de dados para garantir sincronização
                            setTimeout(() => {
                                refreshOrdersFromDatabase();
                            }, 500);
                        } else {
                            ToastManager.error('Erro ao atualizar ordens: ' + data.message);
                        }
                    })
                    .catch(error => {
                        processingToast.remove();
                        ToastManager.error('Erro ao atualizar ordens dos banners.');
                        console.error('Erro:', error);
                    });
                }
            }

            // Função para inicializar filtro de mini banners
            function initializeMiniBannerFilter() {
                const filterSelect = document.getElementById('mini-banner-filter');
                const filterInfo = document.getElementById('mini-filter-info');
                const miniContainer = document.getElementById('mini-form-container');

                if (!filterSelect || !miniContainer) {
                    return;
                }

                // Event listener para mudança no filtro
                filterSelect.addEventListener('change', function() {
                    const filterValue = this.value;
                    filterMiniBanners(filterValue);
                });

                // Aplicar filtro inicial (todos)
                filterMiniBanners('todos');
            }

            // Função para filtrar mini banners
            function filterMiniBanners(filterType) {
                const miniContainer = document.getElementById('mini-form-container');
                const filterInfo = document.getElementById('mini-filter-info');
                const showingCount = document.getElementById('mini-showing-count');
                const filterTypeSpan = document.getElementById('mini-filter-type');
                
                if (!miniContainer) return;

                const allMiniBanners = miniContainer.querySelectorAll('.col-md-6.col-lg-4.mb-4');
                let visibleCount = 0;
                let totalDesktop = 0;
                let totalMobile = 0;
                let activeDesktop = 0;
                let activeMobile = 0;

                allMiniBanners.forEach(bannerCol => {
                    const bannerCard = bannerCol.querySelector('.banner-card');
                    const deviceBadge = bannerCard.querySelector('.device-type-badge .badge');
                    const statusBadge = bannerCard.querySelector('.banner-status .badge');
                    
                    if (!deviceBadge) return;

                    const deviceType = deviceBadge.textContent.trim();
                    const isActive = statusBadge && statusBadge.classList.contains('bg-success');
                    const isMobile = deviceType === 'Mobile';
                    const isDesktop = deviceType === 'Desktop';

                    // Contar totais
                    if (isMobile) {
                        totalMobile++;
                        if (isActive) activeMobile++;
                    } else if (isDesktop) {
                        totalDesktop++;
                        if (isActive) activeDesktop++;
                    }

                    // Aplicar filtro
                    let shouldShow = false;
                    switch (filterType) {
                        case 'todos':
                            shouldShow = true;
                            break;
                        case 'mobile':
                            shouldShow = isMobile;
                            break;
                        case 'desktop':
                            shouldShow = isDesktop;
                            break;
                    }

                    if (shouldShow) {
                        bannerCol.style.display = 'block';
                        visibleCount++;
                    } else {
                        bannerCol.style.display = 'none';
                    }
                });

                // Atualizar informações do filtro
                updateMiniBannerFilterInfo(filterType, visibleCount, {
                    totalDesktop,
                    totalMobile, 
                    activeDesktop,
                    activeMobile
                });
            }

            // Função para atualizar informações do filtro
            function updateMiniBannerFilterInfo(filterType, visibleCount, counts) {
                const filterInfo = document.getElementById('mini-filter-info');
                const showingCount = document.getElementById('mini-showing-count');
                const filterTypeSpan = document.getElementById('mini-filter-type');

                if (!filterInfo || !showingCount || !filterTypeSpan) return;

                showingCount.textContent = visibleCount;

                switch (filterType) {
                    case 'todos':
                        filterInfo.classList.add('d-none');
                        break;
                    case 'mobile':
                        filterInfo.classList.remove('d-none');
                        filterTypeSpan.innerHTML = `(Mobile - <strong>${counts.activeMobile}</strong> ativos de <strong>${counts.totalMobile}</strong> total)`;
                        break;
                    case 'desktop':
                        filterInfo.classList.remove('d-none');
                        filterTypeSpan.innerHTML = `(Desktop - <strong>${counts.activeDesktop}</strong> ativos de <strong>${counts.totalDesktop}</strong> total)`;
                        break;
                }
            }

                         // Função para atualizar contadores após mudanças nos banners
             function updateMiniBannerCounts() {
                 const currentFilter = document.getElementById('mini-banner-filter').value;
                 filterMiniBanners(currentFilter);
             }

            // Função para converter controles de ordem existentes para auto-save
            function convertExistingOrderControls() {
                // Remover todos os botões de salvar ordem existentes
                document.querySelectorAll('.save-order-btn').forEach(btn => {
                    btn.remove();
                });

                // Converter input-groups para inputs simples
                document.querySelectorAll('.banner-order-controls .input-group').forEach(inputGroup => {
                    const input = inputGroup.querySelector('.banner-order-input');
                    if (input) {
                        // Adicionar classes do form-control-sm
                        input.classList.add('form-control-sm');
                        input.setAttribute('placeholder', 'Ex: 1, 2, 3... (duplicatas OK)');
                        input.setAttribute('title', 'Números duplicados são permitidos');
                        input.setAttribute('max', '999');
                        
                        // Substituir o input-group pelo input simples
                        inputGroup.parentNode.replaceChild(input, inputGroup);
                    }
                });

                // Atualizar textos de ajuda
                document.querySelectorAll('.banner-order-controls .text-muted').forEach(helpText => {
                    if (helpText.textContent.includes('Arraste para reordenar')) {
                        helpText.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-move me-1"><polyline points="5 9 2 12 5 15"></polyline><polyline points="9 5 12 2 15 5"></polyline><polyline points="15 19 12 22 9 19"></polyline><polyline points="19 9 22 12 19 15"></polyline><line x1="2" y1="12" x2="22" y2="12"></line><line x1="12" y1="2" x2="12" y2="22"></line></svg>
                            Auto-save ao sair do campo
                        `;
                    } else if (helpText.textContent.includes('Banner único')) {
                        // Manter texto para banner promocional
                        helpText.textContent = 'Banner único';
                    }
                                 });
             }

            // Função para sincronizar ordens com banco de dados
            function syncOrdersWithDatabase() {
                
                // Buscar ordens atuais do banco de dados
                fetch('/admin/banner/get-orders?' + new Date().getTime(), { // Cache busting
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.orders) {
                        
                        // Atualizar ordens na interface
                        updateInterfaceOrders(data.orders);
                        
                        // Reordenar elementos baseado nas ordens do DB
                        reorderBannersFromDatabase(data.orders);
                    } else {
                        console.warn('⚠️ Resposta inválida ao sincronizar ordens:', data);
                    }
                })
                .catch(error => {
                    console.error('❌ Erro ao sincronizar ordens:', error);
                });
            }

            // Função para atualizar ordens na interface baseado no banco
            function updateInterfaceOrders(orders) {
                Object.keys(orders).forEach(bannerId => {
                    const orderInput = document.querySelector(`.banner-order-input[data-id="${bannerId}"]`);
                    if (orderInput) {
                        orderInput.value = orders[bannerId];
                    }
                });
            }

            // Função para reordenar elementos baseado nas ordens do banco
            function reorderBannersFromDatabase(orders) {
                const containers = ['slide-form-container', 'mini-form-container', 'login-form-container', 'register-form-container', 'promo-form-container'];
                
                containers.forEach(containerId => {
                    const container = document.getElementById(containerId);
                    if (!container) return;

                    const bannerCards = Array.from(container.children);
                    
                    if (containerId === 'mini-form-container') {
                        // Para mini banners, separar por tipo e ordenar cada grupo
                        const desktopBanners = [];
                        const mobileBanners = [];
                        
                        bannerCards.forEach(card => {
                            const bannerId = card.querySelector('.banner-toggle')?.getAttribute('data-id');
                            const deviceBadge = card.querySelector('.device-type-badge .badge');
                            const deviceType = deviceBadge ? deviceBadge.textContent.trim() : 'Desktop';
                            
                            if (bannerId && orders[bannerId] !== undefined) {
                                const orderFromDB = parseInt(orders[bannerId]);
                                
                                if (deviceType === 'Mobile') {
                                    mobileBanners.push({ card, order: orderFromDB });
                                } else {
                                    desktopBanners.push({ card, order: orderFromDB });
                                }
                            }
                        });
                        
                                                 // Ordenar cada grupo por ordem do DB (permitindo duplicatas)
                        desktopBanners.sort((a, b) => {
                            const orderDiff = a.order - b.order;
                            // Se ordens são iguais, manter ordem original (stable sort)
                            return orderDiff !== 0 ? orderDiff : 0;
                        });
                        mobileBanners.sort((a, b) => {
                            const orderDiff = a.order - b.order;
                            // Se ordens são iguais, manter ordem original (stable sort)
                            return orderDiff !== 0 ? orderDiff : 0;
                        });
                        
                        // Reorganizar no DOM
                        desktopBanners.forEach(item => container.appendChild(item.card));
                        mobileBanners.forEach(item => container.appendChild(item.card));
                        
                    } else {
                        // Para outros tipos, ordenação normal baseada no DB
                        const bannersWithOrders = bannerCards.map(card => {
                            const bannerId = card.querySelector('.banner-toggle')?.getAttribute('data-id');
                            const orderFromDB = bannerId && orders[bannerId] !== undefined ? parseInt(orders[bannerId]) : 999;
                            return { card, order: orderFromDB };
                        }).filter(item => item.order !== 999);
                        
                        // Ordenar por ordem do DB (permitindo duplicatas)
                        bannersWithOrders.sort((a, b) => {
                            const orderDiff = a.order - b.order;
                            // Se ordens são iguais, manter ordem original (stable sort)
                            return orderDiff !== 0 ? orderDiff : 0;
                        });
                        
                        // Reorganizar no DOM
                        bannersWithOrders.forEach(item => container.appendChild(item.card));
                    }
                });
            }

            // Função para recarregar ordens do banco após mudanças
            function refreshOrdersFromDatabase() {
                syncOrdersWithDatabase();
            }
        </script>
    @endpush

@endsection
