@extends('layouts.app')

@section('content')
<div data-v-bb268f68="" class="pageContainer">
    <div data-v-bb268f68="" class="boxesWrapper">
        <div data-v-bb268f68="" class="box">
            <h1 data-v-debf714a="" data-v-bb268f68="" class="title mb-3"><span data-v-bb268f68="">Termos de apostas</span></h1>
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
                    <!---->
                </div>
            </div>
            <div data-v-bb268f68="" class="pageSlotWrapper">
                <article>
                    <h2>Regulamento</h2>
                    <p>Estes Termos e Condições aplicam-se a você e são vinculativos se você participar no {{ \App\Models\Setting::first()->name ?? config('app.name') }}.</p>
                    <p>
                        Com o objetivo de um relacionamento harmonioso, duradouro e justo com nossos clientes e para evitar dúvidas quanto ao desenvolvimento de jogos esportivos, {{ \App\Models\Setting::first()->name ?? config('app.name') }} tem regras para qualquer cliente. Essas regras sempre
                        prevalecerão para qualquer e todas as inscrições no {{ \App\Models\Setting::first()->name ?? config('app.name') }}. Por isso, você deve aceitar nossos termos ao se registrar. Ao participar dos nossos serviços de apostas em <a href="{{ $siteUrl }}">{{ $siteUrl }}</a> ("o
                        Serviço"), você concorda que leu e compreendeu estes Termos e Condições e reconhece que estes Termos e Condições se aplicarão à sua conta. Você deve ler os Termos com atenção; se você não concordar com eles e/ou não
                        puder aceitá-los, por favor, não use, visite ou acesse o Site. Ao marcar a caixa "Aceito os termos e condições do {{ \App\Models\Setting::first()->name ?? config('app.name') }}" como parte do processo de registro, você concorda em estar vinculado a estes Termos e
                        Condições, que incluem, e estão inseparavelmente vinculados à nossa Política de Privacidade. Você está sujeito aos Termos e Condições em qualquer caso se usar o Serviço, o que inclui, mas não se limita a,
                        registrar-se para usar nosso Serviço, iniciar ou fazer um depósito através do Serviço ou jogar.
                    </p>
                    <h2>Definições</h2>
                    <p>Nestes Termos e Condições:</p>
                    <p>
                        "{{ \App\Models\Setting::first()->name ?? config('app.name') }}" refere-se à marca e todos os produtos oferecidos online (acesso via computador ou laptop) através do site <a href="{{ $siteUrl }}">{{ $siteUrl }}</a> e em "Mobile" (acesso via telefone celular ou tablet)
                        através de {{ $siteUrl }} ou via app. Isso inclui: esportes, Cassino, Cassino ao vivo e esportes virtuais.
                    </p>
                    <p>Sobre {{ \App\Models\Setting::first()->name ?? config('app.name') }} e a proteção dos fundos dos clientes</p>
                    <p>O "Acordo de Jogo" do cliente é celebrado com {{ \App\Models\Setting::first()->name ?? config('app.name') }}, e é formalizado com o sucesso do registro de uma conta no {{ \App\Models\Setting::first()->name ?? config('app.name') }}.</p>
                    <p>
                        Todos os fundos dos clientes mantidos pela plataforma são mantidos separados dos fundos da plataforma em contas separadas da sua conta de negociação. Isso significa que medidas foram tomadas para proteger os Fundos
                        do Cliente, mas que, em caso de insolvência, não há garantia absoluta de que todos os fundos serão reembolsados.
                    </p>
                    <h2>Sua Participação no {{ $siteUrl }}</h2>
                    <p>1.1. É expressamente proibido registrar e apostar para pessoas menores de 18 anos de idade.</p>
                    <p>1.2. O registro usando dados pertencentes a terceiros é expressamente proibido.</p>
                    <p>1.3. Aceitação</p>
                    <p>
                        Ao aceitar estes Termos e Condições, você está plenamente ciente de que há um risco de perder ou ganhar dinheiro no jogo e você é totalmente responsável por tal perda ou ganho. Você concorda que sua participação no
                        {{ \App\Models\Setting::first()->name ?? config('app.name') }} é por sua própria discrição e risco. Em relação às suas perdas, você não terá nenhuma reivindicação contra {{ \App\Models\Setting::first()->name ?? config('app.name') }} ou qualquer parceiro, ou seus respectivos diretores, executivos ou funcionários.
                    </p>
                    <p>1.4. Identificação e Documentação</p>
                    <p>
                        Para participar no {{ \App\Models\Setting::first()->name ?? config('app.name') }}, você deve inserir suas informações pessoais durante o processo de registro da conta. Ao fazer um pedido de saque, você também pode ser solicitado a enviar documentos de identificação
                        válidos que comprovem sua idade e endereço. Após esse pedido, o saque não será processado para pagamento até que {{ \App\Models\Setting::first()->name ?? config('app.name') }} tenha recebido todos os documentos de identificação solicitados. Documentação de identificação
                        aceitável inclui, mas não se limita a: uma cópia de um documento de identidade com foto válido, como passaporte ou carteira de motorista; cópia de uma conta recente que confirme a residência, como conta de luz, conta
                        de telefone, etc. (importante: a fatura não deve ter mais de 3 meses); e cópia de um extrato bancário recente (nota: o extrato deve se referir ao método financeiro usado e não deve ter mais de 3 meses).
                    </p>
                    <p>1.5. Verificações de ID</p>
                    <p>{{ \App\Models\Setting::first()->name ?? config('app.name') }} reserva-se o direito de receber e confirmar com sucesso seus documentos de identificação, internamente ou por meio de terceiros, antes de permitir o saque da sua conta.</p>
                    <p>1.6. Política de Verificação de Idade e Identificação</p>
                    <p>
                        O pagamento para um pedido de saque só será feito a um cliente registrado. Você autoriza-nos e nossos agentes designados a confirmar sua identidade e esclarecer seu direito de usar o dinheiro que você apostou no
                        {{ \App\Models\Setting::first()->name ?? config('app.name') }}. Podemos manter fundos na sua conta de cliente {{ \App\Models\Setting::first()->name ?? config('app.name') }} até que sua idade tenha sido verificada com sucesso. Se, após a conclusão do processo de verificação de idade, for determinado que você é de fato um menor
                        de idade, a conta será bloqueada permanentemente.
                    </p>
                    <p>1.7. Risco</p>
                    <p>
                        Você concorda que sua participação no {{ \App\Models\Setting::first()->name ?? config('app.name') }} é por sua própria discrição e risco. Além disso, você concorda que sua Participação no {{ \App\Models\Setting::first()->name ?? config('app.name') }} é para seu entretenimento pessoal e uso não profissional e que você está
                        agindo em seu próprio nome.
                    </p>
                    <p>1.8. Uso aceitável</p>
                    <p>
                        Você declara, garante e concorda que cumprirá todas as leis, estatutos e regulamentos aplicáveis ao seu uso do site e do Serviço. Não somos responsáveis por qualquer uso ilegal ou não autorizado do site ou do Serviço
                        por você. Ao aceitar estes Termos e Condições, você concorda em nos ajudar, na medida do possível, com o cumprimento das leis e regulamentos aplicáveis.
                    </p>
                    <h2>Sua conta</h2>
                    <p>2.1. Conta única</p>
                    <p>
                        Você pode registrar e operar pessoalmente apenas uma conta com o {{ \App\Models\Setting::first()->name ?? config('app.name') }}. Se você tiver mais de uma conta, reservamo-nos o direito de suspender ambas as contas permanentemente. {{ \App\Models\Setting::first()->name ?? config('app.name') }} pode, a seu exclusivo critério,
                        recusar-se a registrar uma conta de cliente ou fechar uma conta de cliente existente, mas todas as obrigações contratuais estabelecidas serão cumpridas.
                    </p>
                    <p>2.2. Confiabilidade das Informações</p>
                    <p>
                        Você deve manter suas informações de registro atualizadas em todos os momentos. Se você alterar seus detalhes de contato ou pessoais, entre em contato com o Atendimento ao Cliente para atualizar as informações da sua
                        conta. O nome fornecido no {{ \App\Models\Setting::first()->name ?? config('app.name') }} no momento do registro deve ser idêntico ao nome contido em seus documentos pessoais.
                    </p>
                    <p>2.3. Senha</p>
                    <p>
                        O processo de registro da conta do cliente exige que você escolha sua própria combinação de senha. Você deve manter essas informações em sigilo. Qualquer ação realizada pela sua conta será retida se seu nome de
                        usuário e senha forem inseridos corretamente. {{ \App\Models\Setting::first()->name ?? config('app.name') }} não pode assumir nenhuma responsabilidade pelo uso ou abuso de dados pessoais não autorizados.
                    </p>
                    <p>2.4. Verificação de Detalhes Bancários</p>
                    <p>Ao usar transações financeiras bancárias no {{ \App\Models\Setting::first()->name ?? config('app.name') }}, o nome do titular da conta deve ser o mesmo que o registrado ao criar uma conta no {{ \App\Models\Setting::first()->name ?? config('app.name') }}.</p>
                    <p>2.5. Qualquer Responsabilidade</p>
                    <p>
                        {{ \App\Models\Setting::first()->name ?? config('app.name') }} não aceitará qualquer responsabilidade por danos ou perdas considerados ou alegados como decorrentes de ou em conexão com sua participação; incluindo, sem limitação, atrasos ou interrupções na operação ou
                        transmissão, perda ou corrupção de dados, falhas de linha ou comunicação, uso inadequado da Oferta ou do site de uma pessoa, seu conteúdo ou quaisquer erros ou omissões no conteúdo do site.
                    </p>
                    <p>2.6. Transferências de Conta</p>
                    <p>Transferir fundos entre contas individuais é estritamente proibido. Os clientes estão proibidos de vender, transferir e/ou adquirir contas de/outros clientes.</p>
                    <p>2.7. Suspensão de Conta</p>
                    <p>
                        {{ \App\Models\Setting::first()->name ?? config('app.name') }} reserva-se o direito de suspender, fechar ou cancelar sua conta de cliente a seu exclusivo critério, no caso de suspeita de que: ganhos foram obtidos ilegalmente; violou estes Termos e Condições; durante todo
                        o período de suspensão, não será possível desbloquear a conta.
                    </p>
                    <p>2.8. Fechamento de Conta e Suspensão Temporária</p>
                    <p>Se você deseja fechar temporariamente sua conta de cliente, entre em contato com o Atendimento ao Cliente para obter assistência.</p>
                    <p>2.9. Alterações</p>
                    <p>
                        {{ \App\Models\Setting::first()->name ?? config('app.name') }} reserva-se o direito de suspender, modificar, excluir ou adicionar conteúdo ao site ou aos Serviços, a seu exclusivo critério, com efeito imediato e sem aviso prévio. Não seremos responsáveis por qualquer
                        perda sofrida como resultado de quaisquer alterações feitas ou por qualquer modificação ou suspensão ou interrupção do site ou dos Serviços, e você não terá nenhuma reivindicação contra {{ \App\Models\Setting::first()->name ?? config('app.name') }} a esse respeito.
                    </p>
                    <h2>3. Depósitos e Saques</h2>
                    <p>3.1. Registros</p>
                    <p>É responsabilidade do titular da conta bancária manter cópias dos registros de transações e destes Termos e Condições, conforme atualizado de tempos em tempos.</p>
                    <p>3.2. Saques</p>
                    <p>
                        O valor mínimo de saque é R$ 20,00 (vinte reais) e o valor máximo é R$ 4.999,00 (quatro mil novecentos e noventa e nove reais), e você só pode solicitar um único saque a cada 24 horas. {{ \App\Models\Setting::first()->name ?? config('app.name') }} reserva-se o direito de
                        alterar qualquer um desses valores sem aviso prévio.
                    </p>
                </article>
                <hr class="opacity-5 my-5" />
                <div class="pb-3 text-texts"><h5>Ao utilizar este site, você está de acordo com o regulamento acima descrito.</h5></div>
            </div>
        </div>
        @include('extras.partials.menu')
    </div>
</div>
@endsection
