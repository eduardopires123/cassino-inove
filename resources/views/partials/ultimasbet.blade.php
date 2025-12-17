<style>
.q8NfQ[data-v-66a27386] {
    border-radius: .5rem;
    background-color: #ffffff0d;
}
@media (min-width: 768px) {
    .q8NfQ .fjQCP[data-v-66a27386] {
        margin-bottom: 1.5rem;
    }
}
.q8NfQ .fjQCP[data-v-66a27386] {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
}
.q8NfQ .fxNLh[data-v-66a27386] {
    align-items: center;
    border-radius: .5rem;
    display: flex;
    height: 10rem;
    width: 100%;
}
.gnW1g[data-v-66a27386]:nth-child(odd) {
    background-color: transparent;
}
.gnW1g[data-v-66a27386] {
    align-items: center;
    background-color: #ffffff0d;
    border-radius: 0;
    cursor: pointer;
    display: flex;
    flex-direction: row;
    gap: .25rem;
    justify-content: space-between;
    -o-object-fit: cover;
    object-fit: cover;
    padding: .5rem;
    scroll-snap-align: end;
    width: 100%;
}
.gnW1g .Yrzsk[data-v-66a27386] {
    display: flex;
    flex-direction: row;
    gap: .75rem;
    margin-bottom: .25rem;
    width: 100%;
}
.gnW1g .Yrzsk ._14Dpm[data-v-66a27386] {
    aspect-ratio: 1 / 1;
    border-radius: .5rem;
    height: auto;
    max-width: 100%;
    width: 2.25rem;
}
.gnW1g .Yrzsk .ERJSU[data-v-66a27386] {
    align-items: center;
    display: flex;
    flex-direction: row;
    font-size: .75rem;
    gap: .25rem;
    justify-content: space-between;
    line-height: 1rem;
    overflow: hidden;
    width: 100%;
}
.gnW1g .Yrzsk .ERJSU .pMrm-[data-v-66a27386], .gnW1g .Yrzsk .ERJSU .pMrm- .kVci8[data-v-66a27386] {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.gnW1g .Yrzsk .ERJSU .pMrm-[data-v-66a27386] {
    color: #fffc;
    font-size: .875rem;
    font-weight: 600;
    line-height: 1.25rem;
}

.gnW1g .Yrzsk .ERJSU .pMrm- .kVci8[data-v-66a27386] {
    color: #ffffff80;
    display: block;
    font-size: .675rem;
    font-weight: 400;
    line-height: 1rem;
}

.gnW1g .Yrzsk .ERJSU .pMrm-[data-v-66a27386], .gnW1g .Yrzsk .ERJSU .pMrm- .kVci8[data-v-66a27386] {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
@media (min-width: 768px) {
    .gnW1g .Yrzsk .ERJSU .Bv8fH[data-v-66a27386] {
        font-size: .875rem;
        gap: .5rem;
        line-height: 1.25rem;
    }
}

.gnW1g .Yrzsk .ERJSU .Bv8fH[data-v-66a27386] {
    align-items: center;
    display: flex;
    flex-direction: row;
    font-size: .75rem;
    gap: .25rem;
    line-height: 1rem;
    margin-top: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.gnW1g .Yrzsk .ERJSU .Bv8fH ._9EVUU[data-v-66a27386] {
    color: #ffffff80;
    font-weight: 600;
}
.gnW1g .Yrzsk .ERJSU .Bv8fH .I9sXU[data-v-66a27386] {
    color: #ffffff4d;
    font-size: .75rem;
    font-weight: 500;
    line-height: 1rem;
    margin-bottom: -1px;
    margin-left: -1px;
}

.gnW1g .Yrzsk .ERJSU .Bv8fH .CpKm8[data-v-66a27386] {
    align-items: center;
    display: flex;
    font-size: .75rem;
    justify-content: center;
    line-height: 1rem;
    margin-left: .5rem;
    margin-right: .5rem;
    --tw-text-opacity: 1;
    color: rgb(255 255 255 / var(--tw-text-opacity, 1));
}

.gnW1g .Yrzsk .ERJSU .Bv8fH .kM-Vg[data-v-66a27386] {
    color: #fff;
    font-weight: 600;
}

.gnW1g .Yrzsk .ERJSU .Bv8fH .kM-Vg[data-v-66a27386] {
    font-weight: 700;
    --tw-text-opacity: 1;
    color: rgb(255 232 0 / var(--tw-text-opacity, 1));
}

.no-bets-message {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    text-align: center;
    color: #ffffff80;
}

.no-bets-message div {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.no-bets-message small {
    font-size: 0.875rem;
    opacity: 0.7;
}
</style>

@if($cachedData['show_last_bets_cache'] == 1)
<section class="recommended_title">
    <div class="SM-j1">
        <div class="h9HDs">
            <img alt="Trophy icon"  class="h-4" src="{{ asset('img/trophy.webp') }}"/>
            <h2 data-v-debf714a="" class="title flex" style="text-transform:uppercase;">{!! $homeSections->getSectionTitle('custom_title_last_bets', __('menu.last_bets')) !!}</h2>
        </div>
    </div>

    <div data-v-66a27386="" class="q8NfQ">
        <div data-v-66a27386="" class="fjQCP">
            <div data-v-66a27386="" class="swiper swiper-initialized swiper-vertical fxNLh" id="last-bets-container">
                <div class="swiper-wrapper" id="last-bets-wrapper" aria-live="off">
                    @forelse($cachedData['last_bets_cache'] as $bet)
                        <div data-v-66a27386="" class="swiper-slide gnW1g" role="group">
                            <a data-v-66a27386="" href="JavaScript: void(0);" onclick="{{ !empty($bet->game_id) ? "OpenGame('games', '" . $bet->game_id . "')" : '' }}" class="Yrzsk">
                                <img data-v-66a27386="" 
                                     alt="{{ $bet->game_name ?? 'Jogo' }}" 
                                     class="_14Dpm" 
                                     src="{{ getGameImageUrl($bet, 'game_image') }}"
                                />
                                <section data-v-66a27386="" class="ERJSU">
                                    <div data-v-66a27386="" class="pMrm-">
                                        {{ $bet->masked_user_name ?? 'Jogador' }}
                                        <span data-v-66a27386="" class="kVci8">{{ $bet->game_name ?? 'Jogo' }}</span>
                                    </div>
                                    <span data-v-66a27386="" class="Bv8fH">
                                        <span data-v-66a27386="" class="_9EVUU">R$&nbsp;{{ $bet->previous_amount_formatted }}</span>
                                        <span data-v-66a27386="" class="I9sXU">
                                            <span data-v-66a27386="" class="nuxt-icon nuxt-icon--fill">
                                                <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z" fill="currentColor"></path>
                                                </svg>
                                            </span>
                                        </span>
                                        <span data-v-66a27386="" class="kM-Vg">R$&nbsp;{{ $bet->amount_formatted }}</span>
                                        <span data-v-66a27386="" class="CpKm8">
                                            <span data-v-66a27386="" class="nuxt-icon nuxt-icon--fill">
                                                <svg height="1em" viewBox="0 0 320 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z" fill="currentColor"></path>
                                                </svg>
                                            </span>
                                        </span>
                                    </span>
                                </section>
                            </a>
                        </div>
                    @empty
                        <div class="no-bets-message">
                            <div>Nenhuma aposta recente encontrada</div>
                            <small>As apostas vencedoras aparecerão aqui</small>
                        </div>
                    @endforelse
                </div>
                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Função para iniciar o scroll vertical
    function startVerticalScroll() {
        const container = document.getElementById('last-bets-container');
        if (!container) return;
        
        const slides = container.querySelectorAll('.swiper-slide');
        if (slides.length <= 1) return;
        
        let currentIndex = 0;
        
        // Definir altura padrão para os slides
        slides.forEach(slide => {
            slide.style.height = '60px';
        });
        
        // Função para mover para o próximo slide
        function moveToNextSlide() {
            currentIndex = (currentIndex + 1) % slides.length;
            
            // Ocultar todos os slides
            slides.forEach(slide => {
                slide.style.display = 'none';
            });
            
            // Mostrar os próximos 5 slides (ou menos se não houver 5)
            for (let i = 0; i < 5; i++) {
                const index = (currentIndex + i) % slides.length;
                if (slides[index]) {
                    slides[index].style.display = 'block';
                    slides[index].style.order = i + 1;
                }
            }
        }
        
        // Iniciar com os primeiros 5 slides visíveis
        moveToNextSlide();
        
        // Definir intervalo para mover para o próximo slide
        setInterval(moveToNextSlide, 3000);
    }
    
    // Iniciar o scroll vertical com os dados iniciais
    startVerticalScroll();
});
</script>
@endif