@extends('layouts.app')

@section('content')
<div data-v-bb268f68="" class="pageContainer">
    <div data-v-bb268f68="" class="boxesWrapper">
        <div data-v-bb268f68="" class="box">
            <h1 data-v-debf714a="" data-v-bb268f68="" class="title mb-3"><span data-v-bb268f68="">Jogo Responsável</span></h1>
            <div data-v-bb268f68="" class="boxSection">
                <div data-v-bb268f68="" class="boxSectionVersion">
                    <div data-v-bb268f68="" class="flex items-center gap-2">
                        <span data-v-bb268f68="" class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M256 128V0H48C21.49 0 0 21.49 0 48V464C0 490.51 21.49 512 48 512H336C362.51 512 384 490.51 384 464V128H256Z" fill="currentColor" opacity="0.4"></path>
                                <path d="M384 128H256V0L384 128Z" fill="currentColor"></path>
                            </svg>
                        </span>
                        <b data-v-bb268f68="">Versão:</b> 1.0
                    </div>
                    <div data-v-bb268f68="" class="flex items-center gap-2">
                        <span data-v-bb268f68="" class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 448 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 192V464C0 490.5 21.5 512 48 512H400C426.5 512 448 490.5 448 464V192H0Z" fill="currentColor" opacity="0.4"></path>
                                <path
                                    d="M400 64H352V32C352 14.327 337.673 0 320 0H320C302.327 0 288 14.327 288 32V64H160V32C160 14.327 145.673 0 128 0H128C110.327 0 96 14.327 96 32V64H48C21.49 64 0 85.49 0 112V192H448V112C448 85.49 426.51 64 400 64Z"
                                    fill="currentColor"
                                ></path>
                            </svg>
                        </span>
                        <b data-v-bb268f68="">Data:</b><span data-v-bb268f68="">{{ \Carbon\Carbon::now()->subDays(35)->locale('pt_BR')->isoFormat('DD MMM. YYYY') }}</span>
                    </div>
                </div>
            </div>
            <div data-v-bb268f68="" class="pageSlotWrapper">
                <article>
                    <p>Jogo Responsável</p>
                    <p><br /></p>
                    <p>
                    {{ \App\Models\Setting::first()->name ?? config('app.name') }} está aqui para fornecer uma experiência de jogo excelente e agradável e reconhecer a nossa responsabilidade na prevenção de atividades problemáticas. Aconselhamos todos os jogadores a seguir e não jogar
                        irresponsavelmente:
                    </p>
                    <p>• Jogar para entretenimento, não para ganhar dinheiro.</p>
                    <p>• Evite recuperar perdas.</p>
                    <p>• Estabeleça limites para si mesmo.</p>
                    <p>• Não deixe que o jogo interfira com as suas responsabilidades diárias.</p>
                    <p>• Nunca jogue se você não possa cobrir as perdas.</p>
                    <p>• Faça Pausas.</p>
                    <p><br /></p>
                    <p>Veja as perguntas abaixo. Se a sua resposta para a maioria deles é "SIM", aconselhamos que você tome medidas para evitar que o jogo tenha um impacto negativo na sua vida:</p>
                    <p>• O jogo afeta seu trabalho?</p>
                    <p>• O jogo causou discussões com a família/amigos?</p>
                    <p>• Você sempre volta para recuperar suas perdas?</p>
                    <p>• Você já pediu dinheiro emprestado para jogar?</p>
                    <p>• Você vê o jogo como uma fonte de renda?</p>
                    <p>• Você acha difícil limitar seu jogo?</p>
                    <p><br /></p>
                    <p><span style="color: rgb(146, 146, 159);">O que fazer?</span></p>
                    <p>Abaixo observe organizações de renome comprometidas em ajudar aqueles que lutam com problemas no jogo e pode ser contactadas a qualquer momento:</p>
                    <p><span style="color: rgb(0, 0, 0);">• </span>Gamblers Anonymous</p>
                    <p><span style="color: rgb(0, 0, 0);">• </span>Gambling Therapy</p>
                    <p><span style="color: rgb(0, 0, 0);">• </span>GamCare</p>
                    <p><br /></p>
                    <p>Como podemos ajudá-lo?</p>
                    <p>
                        Aconselhamos todos os jogadores que estão preocupados com o seu comportamento de jogo a fazer uma pausa excluindo sua conta de jogo. A autoexclusão bloqueará sua conta por um mínimo de 6 meses e nenhum material
                        promocional será enviado. Entre em contato com nossa equipe experiente de suporte ao cliente a qualquer momento para solicitar isso e eles vão gentilmente ajudá-lo. Um período de 7 dias de reflexão também está
                        disponível. Recomendamos que você entre em contato com todos os outros sites de jogos onde você tem uma conta e solicitar a autoexclusão lá também.
                    </p>
                    <p><br /></p>
                    <p>Jogo de menores</p>
                    <p>
                        Os jogadores devem ter idade legal para jogar em sua jurisdição (pelo menos 18 anos), a fim de jogar na {{ $siteUrl }}. É da sua responsabilidade estar ciente da restrição de idade onde reside e joga, e para confirmar a
                        sua legitimidade ao criar uma conta no {{ $siteUrl }}. Também aconselhamos os pais a fazer o seguinte:
                    </p>
                    <p>• Proteção por senha para computador, celular e/ou tablet.</p>
                    <p>• Não deixe o dispositivo sem supervisão quando estiver conectado à sua conta.</p>
                    <p>• Certifique-se de que todos os detalhes da conta e cartões de crédito estão inacessíveis para as crianças.</p>
                    <p>• Não salve senhas em seu computador, anote-as e mantenha-as em algum lugar fora de alcance.</p>
                    <p>• Faça o download de software ou aplicativos de filtragem (ex. controle dos pais,) para impedir que menores acessem sites inadequados.</p>
                </article>
                <!---->
            </div>
        </div>
        @include('extras.partials.menu')
    </div>
</div>
@endsection
