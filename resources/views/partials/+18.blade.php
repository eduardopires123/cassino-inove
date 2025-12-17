@php
    // Verificar se o usuário já confirmou a idade
    $ageVerified = false;

    // Verificar cookie
    if (isset($_COOKIE['age_verified'])) {
        $ageVerified = true;
    }

    // Se o usuário ainda não verificou a idade, mostrar o modal
    if (!$ageVerified):
@endphp

<div id="ageVerificationOverlay" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 99999; display: flex; align-items: center; justify-content: center;">

    <!-- Modal de Confirmação -->
    <div class="yRRGs" id="confirmationModal" style="display: flex;">
        <div class="XAvk0">
            <div class="BuTfF">
                <div class="l6oz0 vTEeN">
                    <a aria-label="{{ $Infos->name ?? config('app.name') }}" class="bwSJI">
                        <img alt="{{ $Infos->name ?? config('app.name') }}" class="Ueilo" src="{{ completeImageUrl($Infos->logo ?? 'img/logo-inove.png') }}" />
                        <img alt="{{ $Infos->name ?? config('app.name') }}" class="j2x6J" src="{{ completeImageUrl($Infos->logo ?? 'img/logo-inove.png') }}" />
                    </a>
                </div>
            </div>
            <div class="_1xL4n">
                <div class="UQLTR"><span>Você tem mais de 18 anos?</span></div>
                <div class="UH5av">
                    <button class="pCZTM" type="button" id="btnNao">
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                        Não
                    </button>
                    <button class="OFi5b" type="button" id="btnSim">
                        <span class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                        Sim
                    </button>
                </div>
            </div>
        </div>
        <div class="_2RRhY"></div>
    </div>

    <!-- Modal de Erro -->
    <div class="yRRGs" id="errorModal" style="display: none;">
        <div class="XAvk0">
            <div class="BuTfF">
                <div class="l6oz0 vTEeN">
                    <a aria-label="{{ $Infos->name ?? config('app.name') }}" class="bwSJI">
                        <img alt="{{ $Infos->name ?? config('app.name') }}" class="Ueilo" src="{{ completeImageUrl($Infos->logo ?? 'img/logo-inove.png') }}" />
                        <img alt="{{ $Infos->name ?? config('app.name') }}" class="j2x6J" src="{{ completeImageUrl($Infos->logo ?? 'img/logo-inove.png') }}" />
                    </a>
                </div>
                <button class="i4ptq" type="button" id="btnVoltar">
                    <span class="nuxt-icon nuxt-icon--fill">
                        <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                                fill="currentColor"
                            ></path>
                        </svg>
                    </span>
                </button>
            </div>
            <div class="_1xL4n">
                <div class="UQLTR"><span>Desculpe. Você é muito jovem para consumir esse conteúdo.</span></div>
            </div>
        </div>
    </div>

</div>

<script>
    (function() {
        'use strict';

        // Verificar se já foi verificado
        function checkAgeVerification() {
            const cookie = document.cookie.split(';').find(row => row.trim().startsWith('age_verified='));
            const localStorage = window.localStorage.getItem('age_verified');
            return cookie || localStorage === 'true';
        }

        // Definir cookie
        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/; SameSite=Lax";
        }

        // Esconder overlay
        function hideOverlay() {
            const overlay = document.getElementById('ageVerificationOverlay');
            if (overlay) {
                overlay.style.display = 'none';
                overlay.remove();
            }
        }

        // Mostrar modal de erro
        function showErrorModal() {
            document.getElementById('confirmationModal').style.display = 'none';
            document.getElementById('errorModal').style.display = 'flex';
        }

        // Mostrar modal de confirmação
        function showConfirmationModal() {
            document.getElementById('errorModal').style.display = 'none';
            document.getElementById('confirmationModal').style.display = 'flex';
        }

        // Confirmar idade
        function confirmAge() {
            try {
                // Salvar cookie por 30 dias
                setCookie('age_verified', 'true', 10);

                // Salvar no localStorage
                localStorage.setItem('age_verified', 'true');

                // Esconder overlay
                hideOverlay();

            } catch (error) {
                console.error('Erro:', error);
                alert('Ocorreu um erro. Tente novamente.');
            }
        }

        // Event listeners
        function setupEventListeners() {
            const btnSim = document.getElementById('btnSim');
            const btnNao = document.getElementById('btnNao');
            const btnVoltar = document.getElementById('btnVoltar');

            if (btnSim) {
                btnSim.addEventListener('click', confirmAge);
            }

            if (btnNao) {
                btnNao.addEventListener('click', showErrorModal);
            }

            if (btnVoltar) {
                btnVoltar.addEventListener('click', showConfirmationModal);
            }
        }

        setupEventListeners();

        // Verificar se já foi verificado
        if (checkAgeVerification()) {
            hideOverlay();
        }

    })();
</script>

<style>
    #ageVerificationOverlay {
        font-family: inherit;
    }

    #ageVerificationOverlay button {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    #ageVerificationOverlay button:hover {
        opacity: 0.8;
        transform: scale(1.05);
    }

</style>

@php
    // Fim do bloco PHP condicional
    endif;
@endphp
