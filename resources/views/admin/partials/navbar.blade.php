<header class="header navbar navbar-expand-sm expand-header">
    @php
        use Illuminate\Support\Facades\Auth;
        $user = Auth::user();
    @endphp

    <a href="javascript: void(0);" class="sidebarCollapse">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
    </a>

    <ul class="navbar-item flex-row ms-lg-auto ms-0">
        @if($user && $user->is_admin == 1)
            <li class="nav-item dropdown notification-dropdown">
                <a href="javascript: void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success"></span>
                </a>

                <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                    <div class="drodpown-title message">
                        <h6 class="d-flex justify-content-between"><span class="align-self-center">Aviso</span> <span class="badge badge-primary">{{ $NPendencia ?? 0 }} Pendências</span></h6>
                    </div>
                    <div class="notification-scroll" style="height: auto;">
                        @foreach($pendingTransactions ?? [] as $registro)
                            <div class="dropdown-item">
                                <div class="media server-log">
                                    <img src="{{ asset(\App\Models\Settings::first()->favicon) }}" class="img-fluid me-2" alt="avatar">
                                    <div class="media-body">
                                        <div class="data-info">
                                            <h6 class="">{{$registro->user->name}}</h6>
                                            <p class="">R$ {{$registro->amount}}</p>
                                            <p class="">Aguardando aprovação</p>
                                        </div>

                                        <!--<div class="icon-status">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div style="line-height: 10px;">&nbsp;</div>
                        <div class="dropdown-item">
                            <div class="media ">
                                <div class="media-body">
                                    <div class="data-info">
                                        <a onclick="OpenURL('page/saques_pendentes', 'pagamentos');" style="color: #fff;
                                    background: #4361ee;
                                    padding: 8px;
                                    border-radius: 5px;
                                    margin-left: 20px;
                                    font-size: 0.9em;
                                ">Ver Todas Pendências</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="line-height: 10px;">&nbsp;</div>


                    </div>
                </div>
            </li>
        @endif

        <li class="nav-item dropdown user-profile-dropdown  order-lg-0 order-1">
            <a href="javascript: void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="avatar-container">
                    <div class="avatar avatar-sm avatar-indicators avatar-online">
                        <img src="{{ asset($user->image ?? 'assets/img/profile-30.png') }}" alt="avatar">
                    </div>
                </div>
            </a>

            <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                <div class="user-profile-section">
                    <div class="media mx-auto">
                        <div class="emoji me-2">
                            &#x1F44B;
                        </div>
                        <div class="media-body">
                            <h5>{{ $user->name ?? 'Usuário' }}</h5>
                            <p>{{ $userType ?? 'Usuário' }}</p>
                        </div>
                    </div>
                </div>

                <div class="dropdown-item">
                    <form id="logout-form" action="{{ url('admin/logout') }}" method="GET" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Sair</span>
                    </a>
                </div>
            </div>
        </li>
    </ul>
</header>
