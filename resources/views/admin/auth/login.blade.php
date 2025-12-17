<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Settings::first()->name ?? config('app.name') }} - {{ \App\Models\Settings::first()->subname ?? config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url(asset(\App\Models\Settings::first()->favicon)) }}" type="image/png">
    <link href="{{ asset('layouts/modern-light-menu/css/light/loader.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('layouts/modern-light-menu/css/dark/loader.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('layouts/modern-light-menu/loader.js') }}"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('src/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('layouts/modern-light-menu/css/light/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/light/authentication/auth-boxed.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('layouts/modern-light-menu/css/dark/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('src/assets/css/dark/authentication/auth-boxed.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <script disable-devtool-auto src="https://cdn.jsdelivr.net/npm/disable-devtool"></script>
</head>
<body class="form dark">

<!-- BEGIN LOADER -->
<div id="load_screen"> <div class="loader"> <div class="loader-content">
            <div class="spinner-grow align-self-center"></div>
        </div></div></div>
<!--  END LOADER -->

<div class="auth-container d-flex">

    <div class="container mx-auto align-self-center">

        <div class="row">

            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                <div class="card mt-3 mb-3">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <a aria-current="page" href="{{ route('home') }}" class="{{ request()->routeIs('home*') ? 'router-link-active router-link-exact-active bwSJI' : 'bwSJI' }}" aria-label="{{ \App\Models\Setting::first()->name ?? config('app.name') }}">
                                    <img width="200" height="auto" style="margin-bottom: 20px;" alt="{{ \App\Models\Setting::first()->name ?? config('app.name') }}" class="Ueilo" src="{{ asset(\App\Models\Setting::first()->logo ?? 'img/logo-inove.png') }}"/>
                                </a>
                                <h2>Painel Administrativo</h2>
                                <p>Digite seu email e senha para acessar</p>

                            </div>

                            <div id="error-messages" class="alert alert-danger d-none mb-4">
                                <ul class="mb-0 list-unstyled">
                                </ul>
                            </div>

                            <form id="login-form" method="POST" action="{{ route('admin.login.post') }}">
                                @csrf
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required autofocus>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label">Senha</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-check form-check-primary form-check-inline">
                                            <input class="form-check-input me-3" type="checkbox" id="remember" name="remember">
                                            <label class="form-check-label" for="remember">
                                                Lembrar-me
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-4">
                                        <button type="submit" class="btn btn-secondary w-100">ENTRAR</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="{{ asset('src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->

{{-- Floating Theme Toggle Button --}}
<div class="theme-toggle-float">
    <a href="javascript: void(0);" class="theme-toggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-mode"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-mode"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
    </a>
</div>

<style>
    .theme-toggle-float {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 999;
        background-color: var(--secondary-color, #4361ee);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .theme-toggle-float:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
    }

    .theme-toggle-float .theme-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #fff;
    }

    .theme-toggle-float .dark-mode,
    .theme-toggle-float .light-mode {
        width: 24px;
        height: 24px;
    }

    @media (max-width: 768px) {
        .theme-toggle-float {
            width: 45px;
            height: 45px;
            bottom: 15px;
            right: 15px;
        }
    }
</style>

<script>
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#login-form').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('admin.login.post') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.status) {
                    window.location.href = "{{ route('admin.dash') }}";
                } else {
                    showErrors(['Ocorreu um erro ao tentar fazer login.']);
                }
            },
            error: function(xhr) {
                let errors = [];
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    for (let key in xhr.responseJSON.errors) {
                        errors = errors.concat(xhr.responseJSON.errors[key]);
                    }
                } else {
                    errors.push('Ocorreu um erro ao tentar fazer login.');
                }
                showErrors(errors);
            }
        });
    });

    function showErrors(errors) {
        let errorContainer = $('#error-messages');
        let errorList = errorContainer.find('ul');

        errorList.empty();

        $.each(errors, function(index, error) {
            errorList.append('<li>' + error + '</li>');
        });

        errorContainer.removeClass('d-none');
    }

    // Theme toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.querySelector('.theme-toggle');

        // Inicializar os ícones baseado no tema atual
        updateThemeIcons(document.body.classList.contains('dark'));

        // Adicionar evento de clique ao botão de tema
        themeToggle.addEventListener('click', function() {
            // Verifica se o body tem a classe 'dark'
            if (document.body.classList.contains('dark')) {
                // Se tiver, remove a classe (muda para tema claro)
                document.body.classList.remove('dark');
                // Atualiza localStorage para manter o tema entre páginas
                localStorage.setItem('isDarkMode', 'false');
                // Atualiza ícones
                updateThemeIcons(false);
            } else {
                // Se não tiver, adiciona a classe (muda para tema escuro)
                document.body.classList.add('dark');
                // Atualiza localStorage para manter o tema entre páginas
                localStorage.setItem('isDarkMode', 'true');
                // Atualiza ícones
                updateThemeIcons(true);
            }
        });

        // Função auxiliar para mostrar o ícone correto
        function updateThemeIcons(isDarkMode) {
            const darkModeIcon = document.querySelector('.dark-mode');
            const lightModeIcon = document.querySelector('.light-mode');

            if (isDarkMode) {
                darkModeIcon.style.display = 'none';
                lightModeIcon.style.display = 'block';
            } else {
                darkModeIcon.style.display = 'block';
                lightModeIcon.style.display = 'none';
            }
        }

        // Set default theme to dark and save in localStorage
        localStorage.setItem('isDarkMode', 'true');

        // Verificar localStorage para aplicar tema salvo ao carregar a página
        const savedTheme = localStorage.getItem('isDarkMode');
        if (savedTheme === 'true' && !document.body.classList.contains('dark')) {
            document.body.classList.add('dark');
            updateThemeIcons(true);
        } else if (savedTheme === 'false' && document.body.classList.contains('dark')) {
            document.body.classList.remove('dark');
            updateThemeIcons(false);
        }
    });
</script>

</body>
</html>
