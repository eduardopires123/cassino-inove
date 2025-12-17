<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Mostra a página principal de suporte.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('support.index');
    }
    
    /**
     * Mostra a página de suporte ao vivo.
     *
     * @return \Illuminate\View\View
     */
    public function live()
    {
        return view('support.live');
    }
    
    /**
     * Mostra a página de chat com suporte.
     *
     * @return \Illuminate\View\View
     */
    public function chat()
    {
        return view('support.chat');
    }
    
    /**
     * Mostra a página de perguntas frequentes (FAQ).
     *
     * @return \Illuminate\View\View
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'Como faço para criar uma conta?',
                'answer' => 'Para criar uma conta, clique no botão "Registre-se" no canto superior direito da tela e preencha o formulário com suas informações.'
            ],
            [
                'question' => 'Quais são os métodos de pagamento disponíveis?',
                'answer' => 'Aceitamos PIX, transferência bancária, cartões de crédito/débito e boleto bancário.'
            ],
            [
                'question' => 'Quanto tempo leva para processar um saque?',
                'answer' => 'Saques via PIX são processados em até 30 minutos. Outros métodos podem levar até 24 horas úteis.'
            ],
            [
                'question' => 'Como funciona o bônus de boas-vindas?',
                'answer' => 'O bônus de boas-vindas oferece 100% do valor do seu primeiro depósito, até R$500. Para ativá-lo, faça um depósito mínimo de R$50.'
            ],
            [
                'question' => 'Posso jogar pelo celular?',
                'answer' => 'Sim, nosso site é totalmente responsivo e pode ser acessado por navegadores de celulares. Também temos um aplicativo disponível para download.'
            ],
        ];
        
        return view('support.faq', [
            'faqs' => $faqs
        ]);
    }
    
    /**
     * Mostra a central de ajuda.
     *
     * @return \Illuminate\View\View
     */
    public function helpCenter()
    {
        $articles = [
            [
                'id' => 1,
                'title' => 'Como criar uma conta',
                'category' => 'Conta',
                'short_description' => 'Aprenda a criar uma conta em nossa plataforma em poucos passos.'
            ],
            [
                'id' => 2,
                'title' => 'Métodos de pagamento',
                'category' => 'Pagamentos',
                'short_description' => 'Conheça todos os métodos de pagamento disponíveis em nossa plataforma.'
            ],
            [
                'id' => 3,
                'title' => 'Como fazer apostas esportivas',
                'category' => 'Apostas',
                'short_description' => 'Guia completo para fazer suas apostas esportivas com sucesso.'
            ],
        ];
        
        $categories = ['Conta', 'Pagamentos', 'Apostas', 'Cassino', 'Bônus', 'Segurança', 'Aplicativo'];
        
        return view('support.help-center', [
            'articles' => $articles,
            'categories' => $categories
        ]);
    }
    
    /**
     * Processa o formulário de contato.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Lógica para enviar o e-mail/mensagem
        // Mail::to('suporte@betbr.com')->send(new ContactMessage($request->all()));
        
        return redirect()->back()->with('success', 'Sua mensagem foi enviada com sucesso! Nossa equipe entrará em contato em breve.');
    }
}