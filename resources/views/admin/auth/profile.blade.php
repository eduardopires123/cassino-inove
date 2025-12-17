@extends('admin.layouts.app')
@section('content')
<div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0">

                    <!-- BREADCRUMB -->
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Usuários</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Perfil</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- /BREADCRUMB -->
    
                    <div class="row layout-spacing ">
    
                        <!-- Content -->
                        <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12 layout-top-spacing">
                            <div class="user-profile">
                                <div class="widget-content widget-content-area" style="padding: 20px;">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="">Perfil</h3>
                                     </div>
                                    <div class="text-center user-info">
                                        <img src="{{ $user->image ? asset($user->image) : asset('src/assets/img/profile-3.jpeg') }}" alt="avatar" class="rounded-circle" width="100" height="100">
                                        <p class="">{{ $user->name }}</p>
                                    </div>
                                    <div class="user-info-list">
    
                                        <div class="">
                                            <ul class="contacts-block list-unstyled">
                                                <li class="contacts-block__item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user me-3"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> 
                                                    @if ($user->is_admin == 1)
                                                        Administrador
                                                    @elseif ($user->is_admin == 2)
                                                        Supervisor 
                                                    @elseif ($user->is_admin == 3)
                                                        Afiliado
                                                    @else
                                                        Usuário
                                                    @endif
                                                </li>
                                                @if ($user->nascimento)
                                                <li class="contacts-block__item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar me-3"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>{{ \Carbon\Carbon::parse($user->nascimento)->format('d/m/Y') }}
                                                </li>
                                                @endif
                                                <li class="contacts-block__item">
                                                    <a href="mailto:{{ $user->email }}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail me-3"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>{{ $user->email }}</a>
                                                </li>
                                                @if ($user->phone)
                                                <li class="contacts-block__item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone me-3"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> {{ $user->phone }}
                                                </li>
                                                @endif
                                                @if ($user->cpf)
                                                <li class="contacts-block__item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> CPF: {{ $user->cpf }}
                                                </li>
                                                @endif
                                            </ul>
                                        </div>                                    
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12 layout-top-spacing">

                            <div class="user-profile">
                                <div class="widget-content widget-content-area" style="padding: 20px;">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="">Editar Perfil</h3>
                                        <a href="#" class="mt-2 edit-profile"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></a>
                                    </div>
                                    <div class="form">
                                        <form action="{{ route('admin.profile.update') }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="name">Nome Completo:</label>
                                                        <input type="text" class="form-control mb-3" id="name" name="name" placeholder="Nome Completo" value="{{ $user->name }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="phone">Telefone:</label>
                                                        <input type="text" class="form-control mb-3" id="phone" name="phone" placeholder="Escreva seu telefone aqui" value="{{ $user->phone }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Email:</label>
                                                        <input type="email" class="form-control mb-3" id="email" name="email" placeholder="Escreva seu email aqui" value="{{ $user->email }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="nascimento">Data de Nascimento:</label>
                                                        <input type="date" class="form-control mb-3" id="nascimento" name="nascimento" value="{{ $user->nascimento ? \Carbon\Carbon::parse($user->nascimento)->format('Y-m-d') : '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cpf">CPF:</label>
                                                        <input type="text" class="form-control mb-3" id="cpf" name="cpf" placeholder="Digite seu CPF" value="{{ $user->cpf }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="pix">Chave PIX:</label>
                                                        <input type="text" class="form-control mb-3" id="pix" name="pix" placeholder="Digite sua chave PIX" value="{{ $user->pix }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mt-1">
                                                    <div class="form-group text-end">
                                                        <button type="submit" class="btn btn-primary _effect--ripple waves-effect waves-light">Salvar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>

                    <div class="row">

                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
                            <div class="summary layout-spacing ">
                                <div class="widget-content widget-content-area" style="padding: 20px;">
                                    <h3 class="">Resumo</h3>
                                    <div class="order-summary">

                                        <div class="summary-list summary-income">
    
                                            <div class="summery-info">
    
                                                <div class="w-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                                                </div>
    
                                                <div class="w-summary-details">
    
                                                    <div class="w-summary-info">
                                                        <h6>Carteira <span class="summary-count">R${{ number_format($user->wallet->balance ?? 0, 2, ',', '.') }}</span></h6>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
    
                                        <div class="summary-list summary-profit">
    
                                            <div class="summery-info">
    
                                                <div class="w-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                                </div>
                                                
                                                <div class="w-summary-details">
    
                                                    <div class="w-summary-info">
                                                        <h6>Lucro <span class="summary-count">R${{ number_format($user->wallet->total_won ?? 0, 2, ',', '.') }}</span></h6>
                                                        @if(($user->wallet->total_won ?? 0) > 0 && ($user->wallet->total_bet ?? 0) > 0)
                                                            <p class="summary-average">{{ round(($user->wallet->total_won / ($user->wallet->total_bet ?: 1)) * 100) }}%</p>
                                                        @else
                                                            <p class="summary-average">0%</p>
                                                        @endif
                                                    </div>
    
                                                </div>
    
                                            </div>
    
                                        </div>
    
                                        <div class="summary-list summary-expenses">
    
                                            <div class="summery-info">
                                                <div class="w-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                                                </div>
                                                <div class="w-summary-details">
    
                                                    <div class="w-summary-info">
                                                        <h6>GGR <span class="summary-count">R$ {{ number_format($user->wallet->total_lose ?? 0, 2, ',', '.') }}</span></h6>
                                                        @if(($user->wallet->total_lose ?? 0) > 0 && ($user->wallet->total_bet ?? 0) > 0)
                                                            <p class="summary-average">{{ round(($user->wallet->total_lose / ($user->wallet->total_bet ?: 1)) * 100) }}%</p>
                                                        @else
                                                            <p class="summary-average">0%</p>
                                                        @endif
                                                    </div>
    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">

                            <div class="pro-plan layout-spacing">
                                <div class="widget">
    
                                    <div class="widget-heading">
        
                                        <div class="task-info">
                                            <div class="w-title">
                                                <h5>Plano Mensal</h5>
                                                <span>R$500,00/mês</span>
                                            </div>
                                        </div>
        
                                        <div class="task-action">
                                            <button class="btn btn-secondary _effect--ripple waves-effect waves-light">Renovar Agora</button>
                                        </div>
                                    </div>
                                    
                                    <div class="widget-content">
        
                                        <ul class="p-2 ps-3 mb-4">
                                            <li class="mb-1"><strong>WebSite Dedicado</strong></li>
                                            <li class=""><strong>Cassino</strong></li>
                                            <li class=""><strong>SportsBook</strong></li>
                                            <li class="mb-1"><strong>Suporte 24hrs</strong></li>
                                        </ul>
                                        
                                        <div class="progress-data">
                                            <div class="progress-info">
                                                <div class="due-time">
                                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> 5 Dias Restantes</p>
                                                </div>
                                                <div class="progress-stats"><p class="text-info">R$500,00 / mês</p></div>
                                            </div>
                                            
                                            <div class="progress">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: 65%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
        
                                    </div>
        
                                </div>

                            </div>
                            
                        </div>
                    </div>
    
                </div>

            </div>
@endsection

@push('scripts')
<script>
    // CPF mask function
    document.addEventListener('DOMContentLoaded', function() {
        const cpfInput = document.getElementById('cpf');
        
        if (cpfInput) {
            cpfInput.addEventListener('input', function(e) {
                let value = e.target.value;
                
                // Remove non-digits
                value = value.replace(/\D/g, '');
                
                // Add formatting
                if (value.length > 0) {
                    value = value.replace(/^(\d{3})(\d)/, '$1.$2');
                }
                if (value.length > 3) {
                    value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
                }
                if (value.length > 7) {
                    value = value.replace(/^(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
                }
                
                // Limit to max length (14 chars with formatting: xxx.xxx.xxx-xx)
                if (value.length > 14) {
                    value = value.substring(0, 14);
                }
                
                e.target.value = value;
            });
        }
    });

    // Toast notifications
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            ToastManager.success("{{ session('success') }}");
        @endif
        
        @if(session('error'))
            ToastManager.error("{{ session('error') }}");
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                ToastManager.error("{{ $error }}");
            @endforeach
        @endif
    });
</script>
@endpush