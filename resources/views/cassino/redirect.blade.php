<section data-v-393cf599="" id="casino-auth" data-v-owner="361" style="--effbcec4: 56.09375px;">
    <button data-v-393cf599="" class="close">
        <span data-v-393cf599="" class="nuxt-icon nuxt-icon--fill">
            <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"
                    fill="currentColor"
                ></path>
            </svg>
        </span>
    </button>
    <div data-v-393cf599="" class="casino-auth__content">
        <div data-v-393cf599="" class="casino-auth__body">
            <div data-v-393cf599="" class="casino-auth__game">
                <div data-v-393cf599="" class="casino-auth__image">
                    <img
                        data-v-393cf599=""
                        id="game-image"
                        width="100"
                        height="100"
                        alt="{{ __('casino-auth.game_image_alt') }}"
                        data-nuxt-img=""
                        sizes="50vw"
                        src="https://imagedelivery.net/BgH9d8bzsn4n0yijn4h7IQ/9845a083-c105-48d2-ec4a-14571e09d100/public"
                    />
                </div>
                <div data-v-393cf599="" class="casino-auth__info">
                    <h3 data-v-393cf599=""><span data-v-393cf599="" id="game-name">{{ __('casino-auth.game_name_default') }}</span></h3>
                    <h4 data-v-393cf599=""><span data-v-393cf599="" id="game-provider">{{ __('casino-auth.provider_default') }}</span></h4>
                </div>
            </div>
            <button data-v-393cf599="" class="btn btn-primary" id="login-register-btn">
                <span data-v-393cf599="" class="nuxt-icon nuxt-icon--fill">
                    <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M344.7 273.5l-144.1 136c-6.975 6.578-17.2 8.375-26 4.594C165.8 410.3 160.1 401.6 160.1 392V320H32.02C14.33 320 0 305.7 0 288V224c0-17.67 14.33-32 32.02-32h128.1V120c0-9.578 5.707-18.25 14.51-22.05c8.803-3.781 19.03-1.984 26 4.594l144.1 136C354.3 247.6 354.3 264.4 344.7 273.5z"
                            fill="currentColor"
                        ></path>
                        <path
                            d="M416 32h-64c-17.67 0-32 14.33-32 32s14.33 32 32 32h64c17.67 0 32 14.33 32 32v256c0 17.67-14.33 32-32 32h-64c-17.67 0-32 14.33-32 32s14.33 32 32 32h64c53.02 0 96-42.98 96-96V128C512 74.98 469 32 416 32z"
                            fill="currentColor"
                            opacity="0.4"
                        ></path>
                    </svg>
                </span>
                {{ __('casino-auth.login_register_button') }}
            </button>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Função para mostrar o modal com os dados do jogo
    window.showCasinoAuthModal = function(gameData) {
        const modal = document.getElementById('casino-auth');
        const gameName = document.getElementById('game-name');
        const gameProvider = document.getElementById('game-provider');
        const gameImage = document.getElementById('game-image');
        
        // Atualizar os dados do jogo no modal
        if (gameData) {
            gameName.textContent = gameData.name || '{{ __("casino-auth.game_name_default") }}';
            gameProvider.textContent = gameData.provider || '{{ __("casino-auth.provider_default") }}';
            if (gameData.image) {
                gameImage.src = gameData.image;
                gameImage.alt = gameData.name || '{{ __("casino-auth.game_image_alt") }}';
            }
        }
        
        // Adicionar a classe "opened" para mostrar o modal
        modal.classList.add('opened');
    };
    
    // Fechar o modal quando clicar no botão de fechar
    const closeButton = document.querySelector('#casino-auth .close');
    closeButton.addEventListener('click', function() {
        document.getElementById('casino-auth').classList.remove('opened');
    });
    
    // Abrir o modal de login quando clicar em "Entre ou registre-se"
    const loginButton = document.getElementById('login-register-btn');
    loginButton.addEventListener('click', function() {
        // Fechar o modal atual
        document.getElementById('casino-auth').classList.remove('opened');
        
        // Abrir o modal de login (substitua por sua própria lógica para abrir o modal de login)
        if (typeof openLoginModal === 'function') {
            openLoginModal();
        } 
    });
});
</script>