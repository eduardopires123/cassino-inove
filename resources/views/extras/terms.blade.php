@extends('layouts.app')

@section('content')
<div data-v-bb268f68="" class="pageContainer">
    <div data-v-bb268f68="" class="boxesWrapper">
        <div data-v-bb268f68="" class="box">
            <h1 data-v-debf714a="" data-v-bb268f68="" class="title mb-3"><span data-v-bb268f68="">Termos e condições</span></h1>
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
                    <p class="ql-align-justify"><span style="background-color: transparent;">Última atualização: {{ \Carbon\Carbon::now()->subDays(15)->locale('pt_BR')->isoFormat('DD MMM. YYYY') }}</span></p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">Introdução:</span></p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            Os presentes termos e condições e os documentos referidos abaixo ("Termos") aplicam-se à utilização do presente endereço Web ({{ $siteUrl }}).
                        </span>
                    </p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            O utilizador deve analisar cuidadosamente estes Termos, uma vez que contêm informações importantes relativas aos seus direitos e obrigações relativos à utilização do site e constituem um acordo jurídico
                            vinculativo entre o utilizador, o nosso cliente (o "apostador"), e nós. Ao utilizar este site e/ou acessar o Serviço, o utilizador, quer seja um convidado ou um utilizador registado com uma conta ("{{ \App\Models\Setting::first()->name ?? config('app.name') }}"),
                            concorda em ficar vinculado a estes Termos, juntamente com quaisquer alterações que possam ser publicadas periodicamente. Se não aceitar estes Termos, deve abster-se de acessar ao Serviço e utilizar o site.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">2. Termos e Condições Gerais.</span></p>
                    <p><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            Reservamo-nos o direito de rever e alterar os Termos e Condições Gerais (incluindo quaisquer documentos referidos e ligados abaixo) em qualquer tempo. O utilizador deve visitar esta página periodicamente para
                            rever os Termos e Condições. As alterações serão vinculativas e efetivas imediatamente após a sua publicação neste site. Se o utilizador se opuser a tais alterações, deve deixar imediatamente de utilizar o
                            serviço prestado pelo o site. A utilização contínua do site após essa publicação indicará que o utilizador concorda em ser vinculado aos Termos e Condições conforme alterados. Quaisquer apostas não liquidadas
                            antes da entrada em vigor dos Termos alterados estarão sujeitas aos Termos pré-existentes.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">3. As Suas Obrigações.</span></p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">O utilizador reconhece que, sempre que acessar ao site e utiliza o Serviço:</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            3.1. O utilizador tem mais de 18 anos ou a idade legal em que o jogo ou as atividades de jogo são permitidos sob a lei ou jurisdição que se aplica ao utilizador. Reservamo-nos o direito de solicitar uma prova de
                            idade do utilizador em qualquer tempo.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            3.2. O utilizador tem capacidade legal e pode celebrar um acordo legal vinculativo conosco. O usuário não deve acessar o site ou utilizar o serviço se não tiver capacidade legal.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            3.3. O utilizador é residente numa jurisdição que permite jogos. Não é um residente de qualquer país no qual o acesso a jogos de azar online para os seus residentes ou para qualquer pessoa dentro desse país seja
                            proibido. É da sua exclusiva responsabilidade garantir que a sua utilização do serviço é legal.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;"> 3.4. O utilizador não pode utilizar uma VPN, um proxy ou serviços ou dispositivos semelhantes que mascarem ou manipulem a identificação da sua localização real.</span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">3.5. O jogador é o utilizador autorizado do método de pagamento que utiliza.</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            3.6. O utilizador deve efetuar todos os pagamentos a nós ou aos nossos provedores de pagamentos de boa fé e não tentar reverter um pagamento efectuado ou tomar qualquer medida que possa fazer com que esse
                            pagamento seja anulado por terceiros.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            3.7. Ao fazer apostas, o utilizador pode perder parte ou a totalidade do dinheiro depositado no Serviço em de acordo com estes Termos e o utilizador será totalmente responsável por essa perda.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            3.8. Ao fazer apostas, você não deve usar nenhuma informação obtida em violação de qualquer legislação em força no país em que você estava quando a aposta foi colocada.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            3.9. Você não está agindo em nome de outra parte ou para quaisquer fins comerciais, mas unicamente em seu próprio nome como um indivíduo privado em uma capacidade pessoal.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            3.10. Você não deve tentar manipular qualquer mercado ou elemento dentro do Serviço de má-fé ou de uma maneira que afete negativamente a integridade do Serviço ou a nós.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">3.11. Você geralmente deve agir de boa fé em relação a nós do Serviço em todos os momentos e para todas as apostas feitas usando o serviço.</span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">3.12. Você, ou, se aplicável, seus funcionários, empregadores, agentes ou membros da família, não estão registrados como afiliados em nosso programa de afiliados.</span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">4. Utilização Restrita.</span></p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> 4.1. Não deve utilizar o Serviço:</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            4.1.1. Se você tiver menos de 18 anos (ou abaixo da maioridade, conforme estipulado nas leis da jurisdição aplicável a você) ou se você não for legalmente capaz de entrar em um acordo legal vinculativo conosco ou
                            você agindo como um agente para, ou de outra forma em nome, de uma pessoa com menos de 18 anos (ou abaixo da idade de maioridade, conforme estipulado nas leis da jurisdição aplicável para você);
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;"> 4.1.2. Se você reside em um país no qual o acesso a jogos de casas de apostas on-line para seus residentes ou para qualquer pessoa dentro desse país é proibido.</span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">4.1.3. Se você é residente de um dos seguintes países, ou acessa o site de um dos seguintes países:</span></p>
                    <ul>
                        <li class="ql-align-justify"><span style="background-color: transparent;">Países do continente Europeu entre eles,</span></li>
                        <li class="ql-align-justify"><span style="background-color: transparent;">Áustria</span></li>
                        <li class="ql-align-justify"><span style="background-color: transparent;">França e seus territórios</span></li>
                        <li class="ql-align-justify"><span style="background-color: transparent;">Alemanha</span></li>
                        <li class="ql-align-justify"><span style="background-color: transparent;">Países-Baixos e seus territórios</span></li>
                        <li class="ql-align-justify"><span style="background-color: transparent;">Espanha</span></li>
                        <li class="ql-align-justify"><span style="background-color: transparent;">União das Comores</span></li>
                        <li class="ql-align-justify"><span style="background-color: transparent;">Reino Unido</span></li>
                        <li class="ql-align-justify"><span style="background-color: transparent;">Países da américa do Norte entre eles,</span></li>
                        <li class="ql-align-justify"><span style="background-color: transparent;">EUA e seus territórios</span></li>
                        <li class="ql-align-justify"><span style="background-color: transparent;">Países da América do Sul com exceção do Brasil</span></li>
                        <li class="ql-align-justify"><span style="background-color: transparent;">Países do continente Africano</span></li>
                        <li class="ql-align-justify"><span style="background-color: transparent;">Países do continente Asiático</span></li>
                    </ul>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> Todos os países da lista negra do FATF, quaisquer outras jurisdições consideradas proibidas pela Anjouan Offshore Financial Authority.</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            4.1.4. Para coletar apelidos, endereços de e-mail e/ou outras informações de outros clientes por qualquer meio (por exemplo, enviando spam, outros tipos de e-mails não solicitados ou o enquadramento não
                            autorizado ou vinculação ao Serviço);
                        </span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> 4.1.5. Para interromper, afetar ou influenciar indevidamente as atividades de outros Clientes ou a operação do Serviço em geral;</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;"> 4.1.6. Para promover anúncios comerciais não solicitados, links de afiliados e outras formas de solicitação que podem ser removidas do serviço sem aviso prévio;</span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            4.1.7. De qualquer forma que, em nossa opinião razoável, possa ser considerada como uma tentativa de: (i) enganar o Serviço ou outro Cliente usando o Serviço; ou (ii) conluio com qualquer outro Cliente que
                            utilize o Serviço para obter uma vantagem desonesta;
                        </span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> 4.1.8. Para violar nossas probabilidades ou violar qualquer um de nossos Direitos de Propriedade Intelectual; ou </span></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> 4.1.9. Por qualquer atividade ilícita.</span></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> 4.2. Você não pode vender ou transferir sua conta para terceiros, nem pode adquirir uma conta de jogador de terceiros.</span></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> 4.3. Você não pode, de forma alguma, transferir fundos entre contas de jogadores.</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            4.4. Podemos encerrar imediatamente sua conta mediante notificação por escrito se você usar o serviço para fins não autorizados. Também podemos tomar medidas legais contra você por fazê-lo em determinadas
                            circunstâncias.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            4.5. Os funcionários da Empresa, seus licenciados, distribuidores, atacadistas, subsidiárias, agências de publicidade, promocionais ou outras, parceiros de mídia, contratados, varejistas e membros das famílias
                            imediatas de cada um NÃO estão autorizados a usar o Serviço por dinheiro real sem o consentimento prévio do Diretor ou CEO da Empresa. Se tal atividade for descoberta, a(s) conta(s) será(ão) imediatamente
                            encerrada(s) e todos os bônus/ganhos serão perdidos.
                        </span>
                    </p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">5. Registro. </span></p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">Você concorda que, em todos os momentos ao usar o Serviço:</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            5.1. Reservamo-nos o direito de recusar a aceitação de um pedido de registo de qualquer candidato, a nosso exclusivo critério e sem qualquer obrigação de comunicar um motivo específico.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            5.2. Antes de usar o serviço, você deve preencher pessoalmente o formulário de registro e ler e aceitar estes Termos. Para começar a apostar no serviço ou retirar seus ganhos, podemos exigir que você se torne um
                            cliente verificado, o que inclui passar por certas comprovações. Você pode ser solicitado a fornecer uma prova válida de identificação e qualquer outro documento que possa ser considerado necessário. Isso inclui,
                            mas não está limitado a, um documento de identificação com foto (passaporte, carteira de motorista ou carteira de identidade nacional) e uma conta de serviços públicos recente listando seu nome e endereço como
                            comprovante de residência. Reservamo-nos o direito de suspender as apostas ou restringir as opções da conta em qualquer conta até que as informações necessárias sejam recebidas. Este procedimento é feito de
                            acordo com o regulamento de jogo aplicável e os requisitos legais de combate à lavagem de dinheiro. Além disso, você precisará depositar fundos em sua conta usando os métodos de pagamento definidos na seção de
                            pagamento do nosso site.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            5.3. Você deve fornecer informações de contato precisas, incluindo um endereço de e-mail válido ("e-mail registrado") e atualizar essas informações no futuro para mantê-las precisas. É da sua responsabilidade
                            manter os seus dados de contacto atualizados na sua conta. Não fazer isso pode resultar em você não receber notificações e informações importantes relacionadas à conta, incluindo alterações feitas a estes Termos
                            e Condições. Identificamos e comunicamos com os nossos clientes através do seu endereço de e-mail registado. É da responsabilidade do cliente manter uma conta de e-mail ativa e exclusiva, nos fornecer o endereço
                            de e-mail correto e aconselhar a empresa sobre quaisquer alterações em seu endereço de e-mail. Cada cliente é totalmente responsável por manter a segurança de seu endereço de e-mail registrado para impedir o uso
                            de seu endereço de e-mail registrado por qualquer terceiro. A Empresa não será responsável por quaisquer danos ou perdas considerados ou alegadamente resultantes de comunicações entre a empresa e o cliente usando
                            o endereço de e-mail registrado. Qualquer cliente que não tenha um endereço de e-mail acessível pela empresa poderá ter sua Conta suspensa até que tal endereço seja fornecido. Podemos suspender imediatamente sua
                            conta mediante notificação por escrito à você para esse efeito, se você fornecer intencionalmente informações pessoais falsas ou imprecisas. Também podemos tomar medidas legais contra você por fazê-lo em
                            determinadas circunstâncias e/ ou entrar em contato com as autoridades relevantes que também podem tomar medidas contra você.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            5.4. Só é permitido registar uma conta no site {{ \App\Models\Setting::first()->name ?? config('app.name') }}. As contas estão sujeitas a encerramento imediato se for constatado que você tem várias contas registradas conosco. Isso inclui o uso de representantes,
                            parentes, associados, afiliados, partes relacionadas, pessoas conectadas e/ou terceiros que operam em seu nome.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            5.5. A fim de garantir a sua idoneidade financeira e confirmar a sua identidade, podemos pedir-lhe que nos forneça informações pessoais adicionais, como o seu nome completo e login, ou que utilize quaisquer
                            fornecedores de informações de terceiros que consideremos necessários. Caso qualquer informação pessoal adicional seja obtida através de fontes de terceiros, iremos informá-lo sobre os dados obtidos.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            5.6. Você deve manter confidencial a sua senha no site. Desde que as informações da conta solicitadas tenham sido fornecidas corretamente, temos o direito de assumir que as apostas, depósitos e retiradas foram
                            feitas por você. Aconselhamos que você altere sua senha regularmente e nunca a divulgue a terceiros. É sua responsabilidade proteger sua senha e qualquer falha em fazê-lo será por sua conta e risco. Você pode
                            sair do serviço no final de cada sessão. Se você acredita que qualquer informação da sua conta está sendo mal utilizada por terceiros, ou sua conta foi invadida ou sua senha foi descoberta por terceiros, você
                            deve nos notificar imediatamente. Você deve nos notificar se o seu endereço de e-mail registrado foi invadido, podemos, no entanto, exigir que você forneça informações/ documentação adicionais para que possamos
                            verificar sua identidade. Suspenderemos imediatamente a sua conta assim que tivermos conhecimento de tal incidente. Enquanto isso, você é responsável por todas as atividades em sua Conta, incluindo o acesso de
                            terceiros, independentemente de seu acesso ter ou não sido autorizado por você.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            5.7. Você não deve, em nenhum momento, transmitir qualquer conteúdo ou outra informação sobre o serviço a outro cliente ou qualquer outra parte por meio de uma captura de tela (ou outro método semelhante), nem
                            exibir tais informações ou conteúdo em um quadro ou de qualquer outra maneira que seja diferente de como apareceria se tal cliente ou terceiro tivesse digitado o URL do site na linha do navegador.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            5.8. Ao se registrar, você terá a possibilidade de usar todas as moedas disponíveis no site. Essas serão as moedas de seus depósitos, retiradas e apostas feitas e correspondidas no serviço, conforme estabelecido
                            nestes Termos. Alguns métodos de pagamento não processam todas as moedas. Nesses casos, uma moeda de processamento será exibida, juntamente com uma calculadora de conversão disponível na página.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            5.9. Não temos qualquer obrigação de abrir uma conta para você e a página de inscrição no nosso site é apenas um convite para tratar. Fica inteiramente dentro de nosso exclusivo critério se deve ou não prosseguir
                            com a abertura de uma conta para você e, caso nos recusemos a abrir uma conta para você, não temos obrigação de fornecer um motivo pela recusa.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            5.10. Após o recebimento de sua inscrição, podemos entrar em contato para solicitar mais informações e/ou documentação para que possamos cumprir nossas obrigações regulatórias e legais.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">6. Sua conta.</span></p>
                    <p><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">6.1. As contas podem utilizar várias moedas, neste caso todos os saldos e transações da conta aparecem na moeda utilizada para a transação.</span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> 6.2. Não damos crédito pela utilização do nosso site.</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            6.3. Podemos fechar ou suspender a sua conta se acreditarmos razoavelmente que você não está cumprindo ou se você não estiver cumprindo estes Termos, ou para garantir a integridade ou justiça do serviço ou se
                            tivermos outros motivos razoáveis para fazê-lo. Podemos nem sempre ser capazes de lhe dar um aviso prévio. Se encerrarmos ou suspendermos sua conta devido a você não cumprir com estes Termos, poderemos cancelar
                            e/ou anular qualquer uma de suas apostas e reter qualquer dinheiro em sua conta (incluindo o depósito).
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            6.4. Reservamo-nos o direito de encerrar ou suspender qualquer conta sem aviso prévio e optar por devolver todos os fundos. As obrigações contratuais já vencidas serão, no entanto, honradas.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            6.5. Reservamo-nos o direito de recusar, restringir, cancelar ou limitar qualquer aposta a qualquer momento por qualquer motivo, incluindo qualquer aposta percebida como sendo feita de forma fraudulenta, a fim de
                            contornar nossos limites de apostas e/ ou nossos regulamentos do sistema.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            6.6. Se qualquer quantia for erroneamente creditada em sua conta, ela permanecerá em nossa propriedade e quando tomarmos conhecimento de qualquer erro, notificaremos você e o valor será retirado de sua Conta.
                        </span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">6.7. Se, por qualquer motivo, a sua conta ficar com saldo negativo, você estará em dívida conosco pelo valor de saldo negativo.</span></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">6.8. Você deve nos informar assim que tomar conhecimento de quaisquer erros em relação à sua conta.</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            6.9. Por favor, lembre-se que as apostas são puramente para entretenimento e prazer e você deve parar assim que deixar de ser divertido. Absolutamente não aposte nada que você não pode perder. Se você sentir que
                            pode ter perdido o controle do seu jogo, oferecemos uma opção de auto-exclusão. Basta enviar uma mensagem para o nosso Departamento de Suporte ao Cliente usando seu endereço de e-mail registrado que deseja se
                            auto-excluir e essa solicitação entrará em vigor dentro de 24 horas a partir do momento do recebimento. Nesse caso, sua conta será desabilitada até que você seja notificado, e você não poderá fazer login nela.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            6.10. Não pode transferir, vender ou penhorar a sua conta a outra pessoa. Esta proibição inclui a transferência de quaisquer ativos de valor de qualquer tipo, incluindo mas não limitado à propriedade de contas,
                            ganhos, depósitos, apostas, direitos e/ou reivindicações em conexão com esses ativos, legais, comerciais ou de outra forma. A proibição de tais transferências também inclui, no entanto, não se limita à oneração,
                            penhora, cessão, usufruto, negociação, corretagem, hipótese e/ou doação em cooperação com um fiduciário ou qualquer outro terceiro, empresa, indivíduo natural ou legal, fundação e/ou associação de qualquer forma
                            ou forma
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">6.11. Caso pretenda encerrar a sua conta conosco, envie um e-mail do seu endereço de e-mail registado para o nosso suporte via e-mail: </span>
                        <a href="mailto:{{ $emails['support'] }}" rel="noopener noreferrer" target="_blank" style="background-color: transparent; color: rgb(255, 255, 255);">{{ $emails['support'] }}</a>
                        <span style="background-color: transparent;"> ou através dos links presentes no site.</span>
                    </p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify">7. Depósito de fundos.</p>
                    <p><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            7.1. Todos os depósitos devem ser feitos a partir de uma conta ou sistema de pagamento que está registado em seu próprio nome, e quaisquer depósitos feitos em qualquer outra moeda será convertido usando a taxa de
                            câmbio diária obtida de (Oanda.com), ou à taxa de câmbio vigente do nosso próprio banco ou do nosso processador de pagamentos, após a qual sua conta será depositada em conformidade. Observe que alguns sistemas de
                            pagamento podem aplicar taxas de câmbio adicionais que serão deduzidas da soma do seu depósito.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            7.2. Podem aplicar-se taxas e encargos aos depósitos e levantamentos de clientes, que podem ser encontrados no site. Na maioria dos casos, absorvemos as taxas de transação para depósitos na sua conta {{ $siteUrl }}.
                            Você é responsável por seus próprios encargos bancários que pode incorrer devido ao depósito de fundos conosco.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            7.3. A empresa não é uma instituição financeira e pode utilizar processadores de pagamentos eletrônicos de terceiros para processar depósitos; não são processados diretamente por nós. Se depositar fundos através
                            de um cartão de crédito ou de um cartão de débito seu banco será responsável pela a transferência, o valor só será creditado em sua conta se recebermos um código de aprovação e autorização da instituição emissora
                            do pagamento. Se o emissor do seu cartão não conceder tal autorização, a sua conta não será creditada com esses fundos.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            7.4. Você concorda em pagar integralmente todos e quaisquer pagamentos e encargos devidos a nós ou a provedores de pagamento em conexão com o seu uso do serviço. Você também concorda em não fazer qualquer
                            cobrança de volta ou renunciar ou cancelar ou de outra forma reverter qualquer um dos seus depósitos, e em qualquer caso, você irá reembolsar e compensar-nos por tais depósitos não pagos, incluindo quaisquer
                            despesas incorridas por nós no processo de recolher o seu depósito, e você concorda que quaisquer ganhos de apostas utilizando os fundos de volta cobrados serão perdidos. Você reconhece e concorda que sua conta
                            de jogador não é uma conta bancária e, portanto, não é garantida, segurada ou protegida por qualquer depósito ou sistema de seguro bancário ou por qualquer outro sistema de seguro semelhante de qualquer outra
                            jurisdição, incluindo mas não limitado à sua jurisdição local. Além disso, a conta do jogador não suporta juros sobre qualquer um dos fundos detidos na mesma.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            7.5. Se decidir aceitar qualquer uma das nossas ofertas promocionais ou de bónus introduzindo um código de bónus durante o depósito, concorda com os Termos de Bónus e os termos de cada bónus específico.
                        </span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> 7.6. Os fundos provenientes de atividades criminosas e/ou ilegais e/ou não autorizadas não devem ser depositados conosco.</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">7.7. Se você depositar usando seu cartão de crédito, é recomendável que você mantenha uma cópia dos registros de transações e uma cópia destes Termos.</span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            7.8. O Jogo na Internet pode ser ilegal na jurisdição em que você está localizado; em caso afirmativo, você não está autorizado a usar seu cartão de pagamento para depositar neste site. É da sua responsabilidade
                            conhecer as leis relativas ao jogo online no seu país de residência.
                        </span>
                    </p>
                    <p class="ql-align-justify"></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">8. Retirada de Fundos.</span></p>
                    <p><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            8.1. Você pode retirar quaisquer fundos não utilizados e liberados mantidos em sua conta de jogador, enviando um pedido de retirada de acordo com nossas condições de retirada. O valor mínimo de retirada por
                            transação é de R$30,00 (ou equivalente em outra moeda).
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            8.2. Não há comissões de retirada se você apostar o depósito pelo menos 1 vez. Caso contrário, temos o direito de deduzir uma taxa de 8% com uma soma mínima de 4 euros (ou equivalente na moeda da sua conta) para
                            combater a lavagem de dinheiro.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            8.3. Reservamo-nos o direito de solicitar identificação com foto, confirmação de endereço ou realizar procedimentos de verificação adicionais (solicitar sua selfie com documento, organizar uma chamada de
                            verificação etc.) para fins de verificação de identidade antes de conceder quaisquer retiradas de sua conta. Também nos reservamos o direito de realizar a verificação de identidade a qualquer momento durante a
                            vigência do seu relacionamento conosco.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            8.4. Todas as retiradas devem ser feitas para o débito original, cartão de crédito, conta bancária, método de pagamento usado para fazer o pagamento em sua conta. Podemos, e sempre a nosso critério, permitir que
                            você retire para um método de pagamento do qual o seu depósito original não se originou. Isso sempre estará sujeito a verificações de segurança adicionais.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">8.5. Se pretender realizar saques, mas a sua conta estiver inacessível, inativa, bloqueada ou fechada, contacte o nosso suporte ao cliente.</span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            8.6. Nos casos em que o seu saldo é pelo menos 10 vezes maior do que a soma total dos seus depósitos, você poderá ser limitado a R$25,000 (ou equivalente em moeda) para retirada por mês. Em outros casos, o valor
                            máximo de retirada por mês é de R$50.000.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            8.7. Por favor, note que não podemos garantir o processamento bem-sucedido de retiradas ou reembolsos no caso de você violar a política de uso restrito estabelecida nas Cláusulas 3.3 e 4.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            8.8. Reservamo-nos o direito de suspender sua conta sem aviso prévio e você deve resgatar seus fundos em até 30 dias após o conhecimento de suspensão de conta via chat online, e-mail ou outros meios de
                            comunicação. Também podemos devolver todos os fundos para a conta de origem do último depósito no nome do cliente.
                        </span>
                    </p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify"></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">9. Operações de Pagamento e Processadores.</span></p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            9.1. Você é totalmente responsável pelo pagamento de todas as quantias devidas a plataforma. Você deve fazer todos os pagamentos para nós de boa fé e não tentar reverter um pagamento feito ou tomar qualquer ação
                            que fará com que tal pagamento seja revertido por terceiros, a fim de evitar uma responsabilidade legitimamente incorrida. Você nos reembolsará por quaisquer cobranças, negação ou reversão de pagamento que fizer
                            e qualquer perda sofrida por nós como consequência disso. Reservamo-nos o direito de também impor uma taxa de administração de R$250,00, ou equivalente em moeda por cobrança de volta, negação ou reversão do
                            pagamento que você fizer.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            9.2. Reservamo-nos o direito de usar processadores de pagamento eletrônicos de terceiros e ou bancos comerciais para processar pagamentos feitos por você e você concorda em ficar vinculado aos seus termos e
                            condições, desde que sejam informados a você e esses termos não entrem em conflito com estes Termos.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            9.3. Todas as transacções efetuadas no nosso site poderão ser verificadas para evitar atividades de branqueamento de capitais ou de financiamento do terrorismo. Transações suspeitas serão relatadas à autoridade
                            relevante.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">10. Erros.</span></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> </span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            10.1. Em caso de erro ou mau funcionamento do nosso sistema ou processos, todas as apostas serão anuladas. Você tem a obrigação de nos informar imediatamente assim que tomar conhecimento de qualquer erro com o
                            Serviço. No caso de comunicação ou erros de sistema ou bugs ou vírus que ocorram em conexão com o serviço e/ou pagamentos feitos a você como resultado de um defeito ou erro no serviço, não seremos responsáveis
                            por você ou a terceiros por quaisquer custos diretos ou indiretos, despesas, perdas ou reclamações decorrentes ou resultantes de tais erros, e nos reservamos o direito de anular todos os jogos/apostas em questão
                            e tomar qualquer outra ação para corrigir tais erros.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            10.2. Envidamos todos os esforços para garantir que não cometemos erros na publicação de linhas de apostas. No entanto, se, como resultado de erro humano ou problemas de sistema, uma aposta for aceite com uma
                            odd, ou seja: materialmente diferente das disponíveis no mercado geral no momento em que a aposta foi feita; ou claramente incorreto, dada a chance do evento ocorrer no momento em que a aposta foi feita, então
                            nos reservamos o direito de cancelar ou anular essa aposta, ou cancelar ou anular uma aposta feita após um evento ter começado.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            10.3. Temos o direito de recuperar de você qualquer montante pago em excesso e de ajustar a sua conta para corrigir qualquer erro. Um exemplo de tal erro pode ser quando um preço está incorreto ou quando
                            inserimos um resultado de um evento incorretamente. Se houver fundos insuficientes em sua conta, podemos exigir que você nos pague o valor relevante em aberto relativo a quaisquer apostas ou apostas erradas.
                            Assim, reservamo-nos o direito de cancelar, reduzir ou excluir quaisquer jogadas pendentes, sejam colocadas com fundos resultantes do erro ou não.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">11. Regras de jogo, Reembolsos e Cancelamentos.</span></p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;"> 11.1. O vencedor de um evento será determinado na data de liquidação do evento, e não reconheceremos decisões contestadas ou anuladas para fins de apostas.</span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            11.2. Todos os resultados publicados serão definitivos após 72 horas e nenhuma consulta será realizada após esse período de tempo. Dentro de 72 horas após a publicação dos resultados, só
                            reiniciaremos/corrigiremos os resultados devido a erro humano, erro do sistema ou erros cometidos pela fonte de resultados de referência.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            11.3. Se o resultado de uma partida for anulado por qualquer motivo pelo órgão governante da partida dentro do período de pagamento, todo o dinheiro será reembolsado.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            11.4. Se ocorrer um empate num jogo em que seja oferecida uma opção de empate, todas as apostas na vitória ou derrota da equipe serão perdidas. Se uma opção de sorteio não for oferecida, todos receberão um
                            reembolso no resultado de um empate na partida.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            11.5. Se um resultado não puder ser validado por nós, por exemplo, se o feed que transmite o evento for interrompido (e não puder ser verificado por outra fonte), então, no nosso critério, as apostas nesse evento
                            serão consideradas inválidas e as apostas reembolsadas.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            11.6. Os valores mínimos e máximos de apostas em todos os eventos serão determinados por nós e estão sujeitos a alterações sem aviso prévio por escrito. Também nos reservamos o direito de ajustar os limites das
                            contas individuais.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            11.7. Os clientes são os únicos responsáveis pelas transações da sua conta. Uma vez concluída, a transação não poderá ser alterada. Não nos responsabilizamos por apostas em falta ou duplicadas feitas pelo cliente
                            e não aceitaremos pedidos de discrepância porque um jogo está em falta ou duplicado. Os clientes podem revisar suas transações na seção "Carteira" do site após cada sessão para garantir que todas as apostas
                            solicitadas foram aceitas.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">11.8. Um confronto terá ação desde que as duas equipes estejam corretas e independentemente do cabeçalho da Liga em que seja colocado em nosso site.</span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            11.9. As datas e horas de início apresentadas no site para jogos eSport são apenas uma indicação e não são garantidas como corretas. Se uma partida for suspensa ou adiada e não for retomada dentro de 72 horas a
                            partir do horário de início programado, a partida não terá ação e as apostas serão reembolsadas. A exceção é qualquer aposta sobre se um time/jogador avança no torneio, ou ganha o torneio, terá ação
                            independentemente de uma partida suspensa ou adiada.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">11.10. Se um evento for publicado por nós com uma data incorreta, todas as apostas têm ação com base na data anunciada pelo órgão governante e/ou oficial.</span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">11.11. Se uma equipe&nbsp; usar substitutos, o resultado continua a ser válido, uma vez que foi escolha da equipa usar os substitutos.</span></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">11.12. A Empresa reserva-se o direito de remover eventos, mercados e quaisquer outros produtos do site.</span></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> 11.13. A explicação aprofundada das nossas regras de apostas esportivas encontra-se em página separada: Central de Ajuda, ESPORTE.</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;"> 11.14 Rollover. O que vai determinar o roll-over vai ser a modalidade em que você apostou primeiro com o saldo de bônus, seja ela Cassino ou Apostas Esportivas:</span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">- Roll-over Cassino;</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            O usuário deverá usar 150x o valor do bônus recebido, além de fazer 750 jogadas em jogos de SLOT da PG ou PRAGMATIC. O usuário terá 24 horas para cumprimento do rollover.
                        </span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">- Roll-over Apostas esportivas;</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">O usuário deverá apostar 75x o valor do bônus recebido além das 188 apostas que devem ser feitas. O usuário terá 24 horas para cumprimento do rollover.</span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            Atenção: Após escolher a categoria desejada para seu roll-over, não é possível trocá-la depois.Para ver em que modalidade você apostou primeiro, basta ir no seu histórico de apostas.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">REGRAS DE APOSTAS</span></p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">12. Comunicações e Avisos.</span></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> </span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;"> 12.1. Todas as comunicações e avisos a serem fornecidos sob estes Termos por você para nós serão enviados usando um de nossos canais de suporte ao cliente no site.</span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            12.2. Todas as comunicações e notificações a serem dadas sob estes Termos por nós para você devem, a menos que especificado de outra forma nestes Termos, ser postadas no site e/ou enviadas para o endereço de
                            e-mail registrado que mantemos em nosso sistema para o cliente relevante. O método de tal comunicação será a nosso e exclusivo critério.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            12.3. Todas as comunicações e notificações a serem dadas sob estes Termos por você ou por nós serão por escrito no idioma português-BR e devem ser dadas de e para o endereço de e-mail registrado em sua conta.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            12.4. De tempos em tempos, podemos entrar em contato com você por e-mail com a finalidade de oferecer informações sobre apostas, ofertas promocionais exclusivas e outras informações da {{ $siteUrl }}. Você concorda
                            em receber esses e-mails quando concorda com estes Termos ao se registrar no site. Você pode optar por não receber essas ofertas promocionais de nós a qualquer momento, enviando uma solicitação ao suporte ao
                            cliente.
                        </span>
                    </p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">13. Assuntos além do nosso controle.</span></p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            13.1 Não podemos ser responsabilizados por qualquer falha ou atraso na prestação do serviço devido a um evento de Força Maior que possa razoavelmente ser considerado fora do nosso controle, apesar da nossa
                            execução de medidas preventivas razoáveis, tais como: um ato de Deus; disputa comercial ou trabalhista; corte de energia; ação, falha ou omissão de qualquer governo ou autoridade; obstrução ou falha de serviços
                            de telecomunicações; ou qualquer outro atraso ou falha causada por terceiros, e não seremos responsáveis por qualquer perda ou dano resultante que você possa sofrer. Nesse caso, reservamo-nos o direito de
                            cancelar ou suspender o serviço sem incorrer em qualquer responsabilidade.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">14. Responsabilidade.</span></p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            14.1. Na medida permitida pela lei aplicável, não compensaremos você por qualquer perda ou dano razoavelmente previsível (direto ou indireto) que você possa sofrer se não cumprirmos nossas obrigações sob estes
                            termos a menos menos que violemos quaisquer obrigações impostas pela lei brasileira (incluindo se causarmos morte ou danos pessoais por nossa negligência) em que o caso não será responsável por você se essa falha
                            for atribuída a: (i) sua própria culpa; (ii) um terceiro não conectado com a nossa execução destes termos (por exemplo, problemas devido ao desempenho da rede de comunicações, congestionamento e conectividade ou
                            ao desempenho do seu equipamento de computador); ou (iii) quaisquer outros eventos que nem nós nem nossos fornecedores poderíamos ter previsto ou previsto mesmo se tivéssemos tomado cuidado razoável. Como este
                            serviço é apenas para uso do consumidor, não seremos responsáveis por quaisquer perdas comerciais de qualquer tipo.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            14.2. No caso de sermos responsabilizados por qualquer evento sob estes termos, nossa responsabilidade total agregada com você sob ou em conexão com estes termos não deve exceder (a) o valor das apostas e ou
                            apostas que você colocou através da sua conta em relação à aposta relevante ou produto que deu origem à responsabilidade relevante, ou (b) R$2.500,00 no agregado, o que for menor.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            14.3. Recomendamos vivamente que você (i) tome o cuidado de verificar a adequação e compatibilidade do serviço com o seu próprio equipamento informático antes da utilização; e (ii) tome precauções razoáveis para
                            se proteger contra programas ou dispositivos prejudiciais, inclusive através da instalação de software antivírus.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">15. Jogos de apostas por Menores de Idade</span></p>
                    <p><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            15.1. Se suspeitarmos que você é atualmente menor de 18 anos ou era menor de 18 anos (ou abaixo da idade da maioridade, conforme estipulado nas leis da jurisdição aplicável a você) quando você fez qualquer aposta
                            através do serviço do nosso site, sua conta será suspensa (bloqueada) para impedir que você faça mais apostas ou faça quaisquer retiradas de sua conta. Em seguida, investigaremos o assunto, incluindo se você está
                            apostando como agente ou em nome de uma pessoa com menos de 18 anos (ou abaixo da idade da maioridade, conforme estipulado nas leis da jurisdição aplicável a você). Se tiver constatado que: (a) são atualmente;
                            (b) eram menores de 18 anos ou abaixo da idade maioritária que se aplica a você no momento relevante; ou (c) têm apostado como agente para ou a mando de uma pessoa com menos de 18 anos ou abaixo da idade
                            maioritária que se aplica:
                        </span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">• todos os ganhos atualmente ou devidos a serem creditados na sua Conta serão retidos;</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            • todos os ganhos obtidos com apostas através do Serviço enquanto menores de idade devem ser pagos a nós sob demanda (se você não cumprir esta disposição, vamos procurar recuperar todos os custos associados à
                            recuperação de tais somas); e/ou
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            • quaisquer valores depositados na sua Conta que não sejam ganhos serão devolvidos a você ou retidos a nosso exclusivo critério. Reservamo-nos o direito de deduzir taxas de transação de pagamento do valor caso
                            seja devolvido, incluindo taxas de transação para depósitos na sua conta {{ $siteUrl }} que cobrimos.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            15.2. Esta condição também se aplica a você se tiver mais de 18 anos, mas estiver fazendo as suas apostas numa jurisdição que especifica uma idade superior a 18 anos para apostas legais e se estiver abaixo dessa
                            idade mínima legal nessa jurisdição.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            15.3. No caso de suspeitarmos que você está violando as disposições desta cláusula ou está tentando confiar neles para um propósito fraudulento, nos reservamos o direito de tomar qualquer ação necessária para
                            investigar o assunto, incluindo informar as agências de aplicação da lei relevantes.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">16. Fraude.</span></p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            16.1 Buscaremos sanções criminais e contratuais contra qualquer cliente envolvido em fraude, desonestidade ou atos criminosos. Vamos reter o pagamento a qualquer cliente em que qualquer um destes seja suspeito. O
                            cliente indenizará e será responsável por nos pagar sob demanda todos os custos, encargos ou perdas sofridos ou incorridos por nós (incluindo quaisquer perdas diretas, indiretas ou consequentes, perda de lucro,
                            perda de negócios e perda de reputação) decorrentes direta ou indiretamente de fraude, desonestidade ou ato criminoso do cliente.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">17. Propriedade Intelectual.</span></p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;"> 17.1. Qualquer uso não autorizado do nosso nome e logotipo pode resultar em ações legais contra você.</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            17.2. Entre nós e você, somos os únicos proprietários dos direitos sobre o serviço, nossa tecnologia, software e sistemas de negócios (os "Sistemas"), bem como nossas chances. você não deve usar seu perfil
                            pessoal para seu próprio ganho comercial (como vender sua atualização de status para um anunciante); e ao selecionar um login para sua conta, nos reservamos o direito de removê-lo ou recuperá-lo, se acreditarmos
                            ser apropriado.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            17.3. Você não pode usar nosso URL, marcas registradas, nomes comerciais e/ou imagem comercial, logotipos ("Marcas") e/ou nossas chances em relação a qualquer produto ou serviço que não seja nosso, que de
                            qualquer maneira possa causar confusão entre os clientes ou público..
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            17.4. Exceto conforme expressamente previsto nestes Termos, nós e nossos licenciadores não concedemos a você quaisquer direitos, licenças, títulos ou interesses expressos ou implícitos nos sistemas ou nas marcas
                            e todos esses direitos, licenças, títulos e interesses especificamente retidos por nós e nossos licenciadores. Você concorda em não usar qualquer dispositivo automático ou manual para monitorar ou copiar páginas
                            da web ou conteúdo dentro do site. Qualquer uso ou reprodução não autorizada pode resultar em ações legais contra você.
                        </span>
                    </p>
                    <p class="ql-align-justify"><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">18. Sua Licença.</span></p>
                    <p><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            18.1. Sujeito a estes Termos e à sua conformidade com eles, concedemos a você uma licença não exclusiva, limitada, intransferível e não sublicenciável para acessar e usar o Serviço apenas para fins pessoais não
                            comerciais. Nossa licença para você termina se nosso contrato com você sob estes Termos terminar.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            18.2. Salvo em relação ao seu próprio conteúdo, você não pode, em hipótese alguma, modificar, publicar, transmitir, transferir, vender, reproduzir, fazer upload, publicar, distribuir, executar, exibir, criar
                            trabalhos derivados ou de qualquer outra forma explorar, o Serviço e/ou qualquer conteúdo nele contido ou o software nele contido, exceto conforme expressamente permitido nestes Termos ou de outra forma no site.
                            Nenhuma informação ou conteúdo no Serviço ou disponibilizado a você em conexão com o serviço pode ser modificado ou alterado, mesclado com outros dados ou publicado em qualquer forma, incluindo, por exemplo,
                            clonagem de tela ou banco de dados e qualquer outra atividade destinada a coletar, armazenar, reorganizar ou manipular tais informações ou conteúdos.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            18.3. Qualquer incumprimento por você com esta cláusula também pode ser uma violação da nossa propriedade intelectual ou de terceiros e outros direitos de propriedade que podem sujeitá-lo a responsabilidade civil
                            e/ou processo criminal.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">19. Sua Conduta e Segurança.</span></p>
                    <p class="ql-align-justify"></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            19.1. Para sua proteção e proteção de todos os nossos clientes, a publicação de qualquer conteúdo no serviço, bem como a conduta em relação a ele e/ou o serviço, que é de qualquer forma ilegal, inadequada ou
                            indesejável é estritamente proibida ("Comportamento Proibido").
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            19.2. Se você se envolver em comportamento proibido, ou determinarmos a nosso exclusivo critério que você está se engajando em comportamento proibido, sua conta e/ou seu acesso ou uso do site podem ser
                            rescindidos imediatamente sem aviso prévio a você. Ações legais podem ser tomadas contra você por outro cliente, terceiros, autoridades policiais e/ou nós em relação a você ter se envolvido em comportamento
                            proibido.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            19.3. O comportamento proibido inclui, mas não se limita a, acessar ou usar o site para: Promover ou compartilhar informações que você sabe que são falsas, enganosas ou ilegais; conduzir qualquer atividade
                            ilegal, como, mas não limitado a, qualquer atividade que promova qualquer atividade criminosa ou empresarial, viole a privacidade de outro cliente ou de qualquer terceiro ou outros direitos ou que crie ou espalhe
                            vírus de computador;
                        </span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">Prejudique menores de qualquer forma; </span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            Transmitir ou disponibilizar qualquer conteúdo que seja ilegal, prejudicial, ameaçador, abusivo, tortuoso, difamatório, vulgar, obsceno, lascivo, violento, odioso, racial ou etnicamente ou de outra forma
                            censurável;
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            Transmitir ou disponibilizar qualquer conteúdo que o usuário não tenha o direito de disponibilizar sob qualquer lei ou relação contratual ou fiduciária, incluindo, sem limitação, qualquer conteúdo que infrinja os
                            direitos autorais de terceiros, Marca ou outra propriedade intelectual e direitos de propriedade;
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            Transmitir ou disponibilizar qualquer conteúdo ou material que contenha vírus de software ou outro código informático ou de programação (incluindo HTML) concebido para interromper, destruir ou alterar a
                            funcionalidade do site, a sua apresentação ou qualquer outro site, software informático ou hardware;
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            Interferir, interromper ou fazer engenharia reversa do serviço de qualquer maneira, incluindo, sem limitação, interceptar, emular ou redirecionar os protocolos de comunicação usados por nós, criando ou usando
                            fraudes, mods ou hacks ou qualquer outro software projetado para modificar o site, ou utilizar qualquer software que intercepte ou recolha informações a partir ou através do site;
                        </span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">Recuperar ou indexar qualquer informação do site utilizando qualquer robô, aranha ou outro mecanismo automatizado;</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">Participar em qualquer atividade ou ação que, a nosso critério exclusivo e sem restrições, resulte ou possa resultar na fraude ou fraude de outro cliente;</span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            Transmitir ou disponibilizar qualquer publicidade não solicitada ou não autorizada ou correio em massa, tais como, mas não limitado a, lixo eletrônico, mensagens instantâneas, "spim", "spam", correntes, esquemas
                            de pirâmide ou outras formas de solicitações;
                        </span>
                    </p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">Criar contas por meios automatizados ou sob pretextos falsos ou fraudulentos;</span></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            Personificar outro cliente ou qualquer outro terceiro, ou qualquer outro ato ou coisa feita que consideremos razoavelmente contrária aos nossos princípios comerciais. A lista acima de comportamento proibido não é
                            exaustiva e pode ser modificada por nós a qualquer momento ou de tempos em tempos. Reservamo-nos o direito de investigar e tomar todas as ações que, a nosso exclusivo critério, considerarmos apropriadas ou
                            necessárias nas circunstâncias, incluindo, sem limitação, a exclusão da postagem(s) do cliente do site e/ou o encerramento de sua conta, e tomar qualquer ação contra qualquer cliente ou terceiro que, direta ou
                            indiretamente, permita que qualquer terceiro se envolva direta ou indiretamente no comportamento proibido, com ou sem aviso prévio a tal cliente ou terceiro.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">20. Links para outros Sites.</span></p>
                    <p><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            20.1 O Serviço pode conter links para sites de terceiros que não são mantidos por nós ou relacionados a nós e sobre os quais não temos controle. Os links para esses sites são fornecidos apenas como uma
                            conveniência para os clientes e não são de forma alguma investigados, monitorados ou verificados quanto à precisão ou integridade por nós. Links para tais sites não implicam qualquer endosso por nós e/ou qualquer
                            afiliação com os sites vinculados ou seu conteúdo ou seu proprietário(s). Não temos controle ou responsabilidade sobre a disponibilidade nem sua precisão, integridade, acessibilidade e utilidade. Assim, ao
                            acessar esses sites, recomendamos que você tome as precauções habituais ao visitar um novo site, incluindo a revisão de sua política de privacidade e termos de uso.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">21. Compliance.</span></p>
                    <p><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            21.1. Se você tiver quaisquer preocupações ou perguntas sobre estes Termos, você deve entrar em contato com o nosso suporte ao cliente através do link do chat 24 horas ou usar seu endereço de e-mail e nos enviar
                            um e-mail para:
                        </span>
                        <a href="mailto:{{ $emails['support'] }}" rel="noopener noreferrer" target="_blank" style="background-color: transparent; color: rgb(255, 255, 255);">{{ $emails['support'] }}</a>
                        <span style="background-color: transparent;">.</span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            21.2. Não obstante o acima exposto, não assumimos qualquer responsabilidade para você ou para qualquer terceiro ao responder a qualquer reclamação que recebemos ou tomamos medidas em relação a ela.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            21.3. Se um cliente não estiver satisfeito com a forma como uma aposta foi resolvida, o cliente deve fornecer detalhes de sua reclamação ao nosso suporte ao cliente. Envidaremos todos os esforços razoáveis para
                            responder a consultas desta natureza dentro de alguns dias (e, em qualquer caso, pretendemos responder a todas essas consultas no prazo de 28 dias após o recebimento).
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            21.4. Os litígios devem ser apresentados no prazo de três (3) dias a contar da data em que a aposta em questão foi decidida. Nenhuma reivindicação será honrada após este período. O cliente é o único responsável
                            por suas transações de conta.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            21.5. Em caso de litígio entre o utilizador e nós, o nosso suporte tentará chegar a uma solução acordada. Caso nosso suporte não consiga chegar a uma solução acordada com você, o assunto será encaminhado para
                            nossa gestão.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            21.6. Se todos os esforços para resolver uma disputa para a satisfação do cliente falharem, o cliente tem o direito de ter a disputa resolvida através de arbitragem.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">22. Atribuição.</span></p>
                    <p><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            22.1 Nem estes Termos nem qualquer um dos direitos ou obrigações aqui mencionados podem ser cedidos por você sem o consentimento prévio por escrito de nós, que o consentimento não será desmentido. Podemos, sem o
                            seu consentimento, atribuir todos ou qualquer parte dos nossos direitos e obrigações a qualquer terceiro, desde que tal terceiro seja capaz de fornecer um serviço de qualidade substancialmente semelhante ao
                            serviço, publicando uma notificação por escrito para este efeito no serviço.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">23. Divisibilidade.</span></p>
                    <p><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            23.1 No caso de qualquer disposição destes Termos e Condições ser considerada inaplicável ou inválida por qualquer autoridade competente, a disposição relevante será modificada para permitir que ela seja
                            executada de acordo com a intenção do texto original na medida máxima permitida pela lei aplicável. A validade e aplicabilidade das restantes disposições destes Termos não serão afetadas.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">24. Violação dos presentes Termos e Condições.</span></p>
                    <p><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">
                            24.1 Sem limitar nossos outros recursos, podemos suspender ou encerrar sua conta e nos recusar a continuar a fornecer o serviço a você, em qualquer caso, sem aviso prévio, se, em nossa opinião razoável, você
                            violar qualquer termo material destes Termos. O aviso de qualquer ação tomada será, no entanto, prontamente fornecido a você.
                        </span>
                    </p>
                    <p><br /></p>
                    <p class="ql-align-justify"><span style="background-color: transparent;">25. Disposições Gerais.</span></p>
                    <p><br /></p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">25.1. </span><strong style="background-color: transparent;">Duração do contrato.</strong>
                        <span style="background-color: transparent;">
                            Estes Termos e Condições permanecerão em pleno vigor e efeito enquanto você acessar ou usar o serviço ou for um cliente ou visitante do site. Estes Termos sobreviverão ao término de sua conta por qualquer motivo.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">25.2. </span><strong style="background-color: transparent;">Gênero.</strong>
                        <span style="background-color: transparent;">
                            As palavras que importam o número singular devem incluir o plural e vice-versa, as palavras que importam o género masculino devem incluir os géneros feminino e neutro e vice-versa e as palavras que importam
                            pessoas devem incluir indivíduos, parcerias, associações, trustes, organizações não incorporadas e corporações.
                        </span>
                    </p> 
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">25.3. </span><strong style="background-color: transparent;">Isenção de responsabilidade</strong>
                        <span style="background-color: transparent;">
                            . Nenhuma renúncia por nós, seja por conduta ou de outra forma, de uma violação ou ameaça de violação por você de qualquer termo ou condição destes Termos será efetiva ou vinculativa contra nós, a menos que feita
                            por escrito e devidamente assinada por nós, e, salvo disposição em contrário na renúncia por escrito, limitar-se-á à violação específica renunciada. A falha de nós em fazer cumprir a qualquer momento qualquer
                            termo ou condição destes Termos não deve ser interpretada como uma renúncia a tal disposição ou do direito de nós de fazer cumprir tal disposição em qualquer outro momento.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">25.4.</span><strong style="background-color: transparent;"> Aviso de recepção.</strong>
                        <span style="background-color: transparent;">
                            Ao acessar ou usar o serviço, você reconhece ter lido, entendido e concordado com cada parágrafo destes Termos e Condições. Como resultado, você renuncia irrevogavelmente a qualquer argumento futuro,
                            reivindicação, demanda ou procedimento contrário de qualquer coisa contida nestes Termos.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">25.5. </span><strong style="background-color: transparent;">Idioma.</strong>
                        <span style="background-color: transparent;">
                            No caso de haver uma discrepância entre a versão em português dessas regras e qualquer outra versão em inglês dessas regras, a versão em inglês será considerada correta.
                        </span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">25.6. </span><strong style="background-color: transparent;">Legislação aplicável.</strong>
                        <span style="background-color: transparent;"> Estes Termos são regidos exclusivamente pela lei em vigor no estado de Anjouan na União das Comores.</span>
                    </p>
                    <p class="ql-align-justify">
                        <span style="background-color: transparent;">25.7. </span><strong style="background-color: transparent;">Acordo integral.</strong>
                        <span style="background-color: transparent;">
                            Estes Termos constituem o acordo integral entre você e nós com relação ao seu acesso e uso do site e serviço, e substitui todos os outros acordos e comunicações anteriores, sejam verbais ou escritas com relação
                            ao assunto aqui tratado.
                        </span>
                    </p>
                    <p><br /></p>
                    <p><br /></p>
                </article>
                <!---->
            </div>
        </div>
       @include('extras.partials.menu')
    </div>
</div>
@endsection