@extends('layouts.app')

@section('content')
<div data-v-bb268f68="" class="pageContainer">
    <div data-v-bb268f68="" class="boxesWrapper">
        <div data-v-bb268f68="" class="box">
            <h1 data-v-debf714a="" data-v-bb268f68="" class="title mb-3"><span data-v-bb268f68="">{{ __('kyc-policy.title') }}</span></h1>
            <div data-v-bb268f68="" class="boxSection">
                <div data-v-bb268f68="" class="boxSectionVersion">
                    <div data-v-bb268f68="" class="flex items-center gap-2">
                        <span data-v-bb268f68="" class="nuxt-icon nuxt-icon--fill">
                            <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg">
                                <path d="M256 128V0H48C21.49 0 0 21.49 0 48V464C0 490.51 21.49 512 48 512H336C362.51 512 384 490.51 384 464V128H256Z" fill="currentColor" opacity="0.4"></path>
                                <path d="M384 128H256V0L384 128Z" fill="currentColor"></path>
                            </svg>
                        </span>
                        <b data-v-bb268f68="">{{ __('kyc-policy.version') }}</b> 1.0
                    </div>
                    <!---->
                </div>
            </div>
            <div data-v-bb268f68="" class="pageSlotWrapper">
                <div>
                    <h3><b>{{ __('kyc-policy.title') }}</b></h3>
                    <h3><b>{{ __('kyc-policy.introduction') }}</b></h3>
                    <p>
                        A Política de Conheça seu Cliente (KYC) estabelece os princípios, normas e diretrizes que a <b>{{ \App\Models\Setting::first()->name ?? config('app.name') }}</b> adota para identificar e verificar a identidade dos jogadores e usuários da plataforma visando a conformidade
                        com as legislações aplicáveis e prevenção de atividades ilícitas, como lavagem de dinheiro e financiamento ao terrorismo.
                    </p>
                    <h3><b>2. Objetivo</b></h3>
                    <p>
                        O principal objetivo desta política é prevenir que atividades ilícitas ocorram em nosso ambiente de jogos de apostas online, estando em conformidade com as exigências legais, setoriais e éticas, sendo orientadas
                        pelos seguintes princípios:
                    </p>
                    <p>
                        <b>● Conformidade Regulatória</b> : Assegurar que todas as comunicações publicitárias estejam de acordo com as regulamentações brasileiras de jogos e apostas, especificamente a
                        <b> Lei de Prevenção a Lavagem de Dinheiro e Financiamento do Terrorismo (PLD/FT - Lei nº 9.613/1998) </b> , as <b>Leis nº 13.756/2018 e 14.790/2023</b> , que dispõem sobre a modalidade lotérica denominada apostas de
                        quota fixa, as <b>portarias regulamentadoras emitidas pelo Ministério da Fazenda</b> e a <b>Instrução Normativa nº 1/2020 do COAF.</b>
                    </p>
                    <h3><b>3. Escopo</b></h3>
                    <p>
                        Esta política aplica-se a todos os colaboradores e departamentos da <b>{{ \App\Models\Setting::first()->name ?? config('app.name') }}</b> que se envolvam na interação com os jogadores, incluindo, mas não se limitando a cadastro, atendimento, áreas operacionais e de
                        monitoramento de jogadores e transações.
                    </p>
                    <h3><b>4. Definições</b></h3>
                    <p>
                        <b>· Know Your Client (KYC):</b> Em português “Conheça o seu Cliente”, é um processo utilizado para identificar e verificar a identidade de seus clientes com o objetivo de prevenir fraudes, lavagem de dinheiro e
                        outras atividades ilícitas.
                    </p>
                    <h3><b>5. Diretrizes do Processo de KYC</b></h3>
                    <h4><b>5.1 Cadastro do Jogador</b></h4>
                    <p>Ao se registrar, o jogador ou usuário deverá fornecer as seguintes informações:</p>
                    <p>5.1.1 Informações Básicas Requeridas</p>
                    <p>· Nome completo;</p>
                    <p>· Data de nascimento;</p>
                    <p>· Nacionalidade;</p>
                    <p>· Endereço residencial completo;</p>
                    <p>· Número de identificação (CPF, RG, passaporte ou documento equivalente); e</p>
                    <p>· Informações de contato (telefone e e-mail);</p>
                    <p>· Geolocalização.</p>
                    <p>5.1.2 Informações Bancárias:</p>
                    <p>· Pix cadastrado.</p>
                    <p>5.1.3 Documentos necessários:</p>
                    <p>· Cópia de um documento de identidade com foto; e</p>
                    <p>· Comprovante de endereço recente (conta de luz ou água).</p>
                    <p><b>5.2 Verificação do Jogador ou Usuário e Validação dos Documentos</b></p>
                    <p>
                        Todas as informações disponibilizadas são verificadas através dos documentos previamente disponibilizados, que por sua vez são verificados quanto à sua autenticidade através de ferramentas específicas. A
                        <b>{{ \App\Models\Setting::first()->name ?? config('app.name') }}</b> se reserva no direito de solicitar documentos adicionais, se necessário.
                    </p>
                    <p><b>5.3 Cruzamento dos dados</b></p>
                    <p>A <b>{{ \App\Models\Setting::first()->name ?? config('app.name') }}</b> realiza o cruzamento dos dados disponibilizados pelos jogadores e usuários com bases de dados terceiras para identificar as seguintes informações:</p>
                    <p>· Pessoas expostas politicamente (PEPs) ou relacionamento com PEPs (até 2º grau de relacionamento);</p>
                    <p>· Pessoas expostas desportivamente (PEDs) ou relacionamento com PEDs (até 2º grau de relacionamento);</p>
                    <p>· Existência de vínculo societário ou empregatício com outros agentes operadores;</p>
                    <p>· Presença em listas restritivas nacionais e internacionais (FBI, Interpol, OFAC etc.);</p>
                    <p>· Histórico de vínculo empregatício com órgãos reguladores (MF, SPA, COAF etc.);</p>
                    <p>· Histórico de ações judiciais;</p>
                    <p>· Histórico de antecedentes criminais;</p>
                    <p>· Histórico de Ludopatia;</p>
                    <p>· Exposição e perfil na mídia;</p>
                    <p>· Dentre outros.</p>
                    <p>
                        A partir dos resultados do cruzamento, a <b>{{ \App\Models\Setting::first()->name ?? config('app.name') }}</b> deverá adotar critérios para restringir a aceitação de jogadores de alto risco ou que estejam localizados em determinadas jurisdições onde o Risco de Lavagem de
                        Dinheiro e do Financiamento do Terrorismo e Proliferação de Armas de Destruição em Massa é maior.
                    </p>
                    <p>Os perfis de jogadores que não poderão ser aceitos:</p>
                    <p>· Menores de 18 anos;</p>
                    <p>· Proprietário, administrador, diretor, pessoa com influência significativa, gerente ou funcionário do agente operador;</p>
                    <p>· Agente público com atribuições diretamente relacionadas à regulação, ao controle, e à fiscalização da atividade no âmbito do ente federativo em cujo quadro de pessoal exerça suas competências;</p>
                    <p>· Pessoa que tenha ou possa ter acesso aos sistemas informatizados de loteria de apostas de quota fixa;</p>
                    <p><u>· Pessoa exposta desportivamente</u> - Pessoa que tenha ou possa ter qualquer influência no resultado de evento real de temática esportiva objeto de loteria de apostas de quota fixa, incluídos:</p>
                    <p>o Pessoa que exerça cargo de dirigente desportivo, técnico desportivo, treinador e integrante de comissão técnica;</p>
                    <p>o Árbitro de modalidade desportiva, assistente de árbitro de modalidade desportiva, ou equivalente, empresário desportivo, agente ou procurador de atletas e de técnicos, técnico ou membro de comissão técnica;</p>
                    <p>o Membro de órgão de administração ou de fiscalização de entidade de administração de organizadora de competição ou de prova desportiva;</p>
                    <p>o Atleta participante de competições organizadas pelas entidades integrantes do Sistema Nacional do Esporte.</p>
                    <p>· Pessoa diagnosticada com ludopatia, por laudo de profissional de saúde mental habilitado; e</p>
                    <p>· Pessoas que estejam localizadas ou que tenham contas bancárias localizadas em jurisdições com alto risco de lavagem de dinheiro;</p>
                    <p>· Outras pessoas previstas na regulamentação do Ministério da Fazenda,</p>
                    <p><b>5.4 Classificação do Risco</b></p>
                    <p>
                        A partir das informações prestadas pelo jogador ou usuário, estes são classificados com base no nível do risco que representam, de modo que a <b>{{ \App\Models\Setting::first()->name ?? config('app.name') }}</b> possa aplicar medidas de controle adequadas para cada nível
                        de risco. Os principais perfis de risco de jogadores e usuários estão detalhados na política de PLD-FTP.
                    </p>
                    <p><b>5.5 Monitoramento Contínuo</b></p>
                    <p>A <b>{{ \App\Models\Setting::first()->name ?? config('app.name') }}</b> realizará monitoramento contínuo das contas para identificar atividades suspeitas. Isso inclui:</p>
                    <p>· Revisão regular de transações;</p>
                    <p>· Análise de padrões de apostas;</p>
                    <p>· Verificação de possíveis sinais de fraude ou atividades ilícitas;</p>
                    <p>· Verificação dos cadastros para identificar potenciais jogadores ou usuários impedidos de jogar;</p>
                    <p>· Checagem de potenciais alterações dos níveis de risco de jogadores ou usuários.</p>
                    <p><b>5.6 Armazenamento de Dados</b></p>
                    <p>A <b>{{ \App\Models\Setting::first()->name ?? config('app.name') }}</b> deve manter registros e documentos relacionados ao cumprimento do disposto nesta Portaria por no mínimo 5 (cinco) anos, sem prejuízo de outros deveres previstos na legislação.</p>
                    <p>Todas as informações coletadas serão armazenadas de maneira segura e em conformidade com as leis de proteção de dados aplicáveis. O acesso às informações é restrito aos funcionários autorizados.</p>
                    <h3><b>6. Responsabilidades</b></h3>
                    <p><b>● Área de Cadastro:</b> Responsável pela gestão dos cadastros dos jogadores ou usuários;</p>
                    <p><b>● Área de Governança e Compliance:</b> Responsável pela implementação, revisão e atualização desta política, conforme necessário, assegurando o cumprimento das exigências regulatórias do setor.</p>
                    <p><b>● Área de Risco:</b> Responsável por gerenciar esta política, garantindo a conformidade com regulamentações locais e internacionais;</p>
                    <h3><b>7. Auditoria e Revisão da Política</b></h3>
                    <p>
                        A <b>{{ \App\Models\Setting::first()->name ?? config('app.name') }}</b> conduzirá auditorias internas periódicas para garantir a conformidade com esta Política de KYC e as regulamentações aplicáveis que estabelecem as normas de prevenção de atividades ilícitas, como
                        lavagem de dinheiro e financiamento ao terrorismo
                    </p>
                    <h3><b>8. Treinamento e Capacitação</b></h3>
                    <p>
                        A empresa realiza treinamentos regulares para todos os colaboradores, principalmente aqueles envolvidos diretamente nos processos de cadastro, verificação, armazenamento e monitoramento dos dados do jogador ou
                        usuário. Estes recebem treinamento regular sobre a política de KYC e as melhores práticas de conformidade.
                    </p>
                    <p>
                        · Os treinamentos auxiliam os funcionários envolvidos nos processos em questão a identificar quaisquer divergências e inconsistências nas informações registradas e documentos disponibilizados pelos jogadores ou
                        usuários no momento do cadastro;
                    </p>
                    <p>· O treinamento incluirá orientações e análise de situações e exemplos de tentativas de lavagem de dinheiro em cassinos e jogos de apostas online.</p>
                    <h3><b>9. Penalidades por Descumprimento</b></h3>
                    <p>Qualquer violação desta Política de KYC será tratada com a devida seriedade pela <b>{{ \App\Models\Setting::first()->name ?? config('app.name') }}.</b> As penalidades podem incluir:</p>
                    <p>· Advertências formais;</p>
                    <p>· Treinamentos corretivos obrigatórios;</p>
                    <p>· Ações disciplinares, que podem variar de suspensão até desligamento, dependendo da gravidade da infração.</p>
                    <h3><b>10. Contato</b></h3>
                    <p>Para qualquer dúvida sobre esta política, entre em contato com a <b>área de Governança e Compliance.</b></p>
                    <p>Aprovação:</p>
                    <p>Diretor(a) de Compliance</p>
                </div>
            </div>
        </div>
        @include('extras.partials.menu')
    </div>
</div>
@endsection
