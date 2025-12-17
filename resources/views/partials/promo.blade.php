@php
    // Verificar se o usuário já visualizou a promo hoje
    $promoShown = false;

    // Verificar cookie
    if (isset($_COOKIE['promo_shown'])) {
        $promoShown = true;
    }

    // Verificar se o usuário verificou a idade
    $ageVerified = isset($_COOKIE['age_verified']);

    // Usar dados do controller
    $promoBanner = $cachedData['promoBanner_cache'] ?? null;

    // Se o usuário ainda não visualizou a promo hoje, verificou a idade e existe um banner ativo, mostrar
    if (!$promoShown && $ageVerified && $promoBanner):
@endphp

<div id="promo-overlay" class="promo-overlay">
    <div class="promo-container">
        <div class="promo-close-button">
            <a href="javascript:void(0)" id="closePromoButton">
                <img width="22px" src="https://d146b4m7rkvjkw.cloudfront.net/01a9198719605f2b3d5b1f-X4.png" alt="Close">
            </a>
        </div>
        <div class="promo-content">
            <a href="{{ $promoBanner->link }}">
                <img class="promo-image" src="{{ asset($promoBanner->imagem) }}" alt="Promotional Banner">
            </a>
        </div>
    </div>
</div>

<style>
    .promo-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease;
    }

    .promo-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .promo-container {
        position: relative;
        max-width: 90%;
        background-color: transparent;
        border-radius: 8px;
        overflow: hidden;
    }

    .promo-close-button {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        padding: 5px;
    }

    .promo-content {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .promo-image {
        max-width: 100%;
        height: auto;
    }

    /* Desktop styles */
    @media (min-width: 768px) {
        .promo-container {
            max-width: 600px;
        }

        .promo-image {
            width: auto;
            max-height: 80vh;
        }
    }

    /* Mobile styles */
    @media (max-width: 767px) {
        .promo-container {
            max-width: 95%;
            margin: 0 10px;
        }

        .promo-image {
            width: 100%;
        }

        .promo-close-button {
            top: 5px;
            right: 5px;
        }
    }
</style>

<script>
    // Função para definir um cookie com data de expiração em dias
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + value + expires + "; path=/; SameSite=Lax";
    }

    // Função para verificar se o modal de +18 foi fechado
    function checkAgeVerification() {
        // Se o cookie de verificação de idade existe, mostrar a promo
        if (document.cookie.indexOf('age_verified=true') !== -1) {
            setTimeout(function() {
                document.getElementById('promo-overlay').classList.add('active');
            }, 1000); // Mostrar a promo 1 segundo após a verificação de idade
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const closeButton = document.getElementById('closePromoButton');
        const promoOverlay = document.getElementById('promo-overlay');

        // Mostrar promo após carregar a página, se a idade já foi verificada
        checkAgeVerification();

        // Escutar evento de mudança no cookie de verificação de idade
        window.addEventListener('storage', function(e) {
            if (e.key === 'age_verified' && e.newValue === 'true') {
                setTimeout(function() {
                    promoOverlay.classList.add('active');
                }, 1000);
            }
        });

        // Fechar o popup quando clicar no botão de fechar
        closeButton.addEventListener('click', function(e) {
            e.preventDefault();
            promoOverlay.classList.remove('active');
            // Salvar que a promo foi exibida com expiração de 1 dia
            setCookie('promo_shown', 'true', 1);
        });

        // Fechar o popup quando clicar no overlay de fundo
        promoOverlay.addEventListener('click', function(e) {
            if (e.target === promoOverlay) {
                promoOverlay.classList.remove('active');
                // Salvar que a promo foi exibida com expiração de 1 dia
                setCookie('promo_shown', 'true', 1);
            }
        });
    });
</script>

@php
    // Fim do bloco PHP condicional
    endif;
@endphp
