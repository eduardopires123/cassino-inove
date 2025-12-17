
@extends('layouts.app')

@section('content')
<div data-v-bb268f68="" class="pageContainer">
    <div data-v-bb268f68="" class="boxesWrapper">
        <div data-v-bb268f68="" class="box">
            <h1 data-v-debf714a="" data-v-bb268f68="" class="title mb-3"><span data-v-bb268f68="">LGPD</span></h1>
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
                    <h1>Política de Privacidade - LGPD</h1>
                    <ol>
                        <li>
                            <p>Âmbito e Introdução</p>
                            <br />
                            <p>
                                A <strong>{{ \App\Models\Setting::first()->name ?? config('app.name') }}</strong> valoriza a proteção dos dados pessoais de seus usuários e está comprometida em cumprir as disposições da Lei Geral de Proteção de Dados (LGPD - Lei n.º 13.709/2018). Esta Política de
                                Privacidade detalha como coletamos, utilizamos, armazenamos e compartilhamos seus dados pessoais ao acessar nossos serviços, seja por meio de um computador, dispositivo móvel ou qualquer outra tecnologia.
                            </p>
                            <br />
                        </li>
                        <li>
                            <p>Coleta de Dados Pessoais</p>
                            <br />
                            <p>Coletamos seus dados pessoais de diversas formas, incluindo:</p>
                            <br />
                            <p>Diretamente de você: Ao registrar-se, preencher formulários, ou entrar em contato conosco.</p>
                            <br />
                            <p>Indiretamente: Através de tecnologias de rastreamento, como cookies, e análise de seu comportamento em nossa plataforma.</p>
                            <p>Os dados coletados podem incluir:</p>
                            <br />
                            <p>Dados cadastrais (nome, CPF, endereço, data de nascimento). Dados de contato (telefone, e-mail).</p>
                            <p>Dados financeiros (informações bancárias).</p>
                            <p>Dados de navegação (IP, tipo de dispositivo, localização geográfica).</p>
                            <br />
                        </li>
                        <li>
                            <p>Finalidades do Tratamento de Dados</p>
                            <br />
                            <p>Tratamos seus dados pessoais para diversas finalidades, tais como:</p>
                            <br />
                            <p>Execução de Contrato: Processamento de transações, autenticação de usuários e verificação de identidade.</p>
                            <p>Cumprimento de Obrigações Legais: Reporte a autoridades reguladoras, prevenção à lavagem de dinheiro e combate ao financiamento do terrorismo.</p>
                            <p>Interesse Legítimo: Melhoria dos nossos serviços, personalização da experiência do usuário, e prevenção a fraudes.</p>
                            <p>Consentimento: Envio de comunicações de marketing, caso tenha sido autorizado por você.</p>
                            <br />
                        </li>
                        <li>
                            <p>Compartilhamento de Dados Pessoais</p>
                            <br />
                            <p>A <strong>{{ \App\Models\Setting::first()->name ?? config('app.name') }}</strong> pode compartilhar seus dados pessoais com terceiros nas seguintes circunstâncias:</p>
                            <br />
                            <p>Provedores de Serviços: Para a operacionalização de nossos serviços, como processadores de pagamento e fornecedores de tecnologia.</p>
                            <p>Autoridades Governamentais: Para o cumprimento de obrigações legais ou regulatórias. Empresas do Grupo: Para fins administrativos e de suporte interno.</p>
                        </li>
                        <li>
                            <p>Segurança e Armazenamento de Dados</p>
                            <br />
                            <p>
                                Seus dados pessoais são armazenados em ambientes seguros e protegidos por medidas técnicas e organizacionais, alinhadas às melhores práticas do setor, para evitar acessos não autorizados, perda, alteração ou
                                destruição dos dados.
                            </p>
                            <br />
                        </li>
                        <li>
                            <p>Direitos dos Titulares</p>
                            <br />
                            <p>Conforme a LGPD, você tem o direito de:</p>
                            <br />
                            <p>Acessar seus dados: Confirmar a existência de tratamento de dados e acessar as informações. Corrigir dados: Retificar dados pessoais incompletos, inexatos ou desatualizados.</p>
                            <p>Excluir dados: Solicitar a anonimização, bloqueio ou eliminação de dados desnecessários ou em desconformidade.</p>
                            <p>Portabilidade: Transferir seus dados a outro fornecedor de serviço.</p>
                            <br />
                            <p>Revogar consentimento: Retirar seu consentimento para o tratamento de dados a qualquer momento.</p>
                            <p>Para exercer seus direitos, entre em contato conosco pelo e-mail: <a href="mailto:{{ $emails['support'] }}">{{ $emails['support'] }}.</a></p>
                            <br />
                        </li>
                        <li>
                            <p>Transferência Internacional de Dados</p>
                            <br />
                            <p>A <strong>{{ \App\Models\Setting::first()->name ?? config('app.name') }}</strong> pode transferir seus dados pessoais para fora do Brasil, garantindo que tais transferências sejam feitas de acordo com a LGPD e com as devidas salvaguardas de proteção.</p>
                        </li>
                        <li>
                            <p>Duração do Tratamento e Retenção de Dados</p>
                            <br />
                            <p>Manteremos seus dados pessoais apenas pelo tempo necessário para cumprir as finalidades descritas nesta política, ou conforme exigido por lei. Após esse período, os dados serão excluídos ou anonimizados.</p>
                            <br />
                        </li>
                        <li>
                            <p>Alterações na Política de Privacidade</p>
                            <br />
                            <p>
                                Reservamo-nos o direito de modificar esta Política de Privacidade a qualquer momento. Notificaremos você sobre alterações significativas e, ao continuar a usar nossos serviços após essas alterações, você
                                estará de acordo com a nova política.
                            </p>
                            <br />
                        </li>
                        <li>
                            <p>Contato</p>
                            <br />
                            <p>Para dúvidas ou solicitações sobre esta Política de Privacidade, entre em contato pelo e-mail: <a href="mailto:{{ $emails['support'] }}">{{ $emails['support'] }}.</a></p>
                        </li>
                    </ol>
                </article>
            </div>
        </div>
        @include('extras.partials.menu')
    </div>
</div>
@endsection
