<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ExtrasController extends Controller
{
    /**
     * Obtém a URL base do site sem o protocolo (sem http:// ou https://)
     * 
     * @return string
     */
    private function getSiteUrl()
    {
        $url = URL::to('/');
        return preg_replace('/^https?:\/\//', '', $url);
    }
    
    /**
     * Obtém a URL completa do site (incluindo protocolo)
     * 
     * @return string
     */
    private function getFullSiteUrl()
    {
        return URL::to('/');
    }
    
    /**
     * Obtém apenas o domínio do site sem o protocolo
     * 
     * @return string
     */
    private function getSiteDomain()
    {
        $url = URL::to('/');
        return parse_url($url, PHP_URL_HOST);
    }
    
    /**
     * Gera endereços de e-mail pré-formatados
     * 
     * @return array
     */
    private function getEmailAddresses()
    {
        $domain = $this->getSiteDomain();
        return [
            'support' => "suporte@{$domain}",
            'contact' => "contato@{$domain}",
            'atendimento' => "atendimento@{$domain}"
        ];
    }

    public function terms()
    {
        $emails = $this->getEmailAddresses();
        
        return view('extras.terms', [
            'siteUrl' => $this->getSiteUrl(),
            'fullSiteUrl' => $this->getFullSiteUrl(),
            'siteDomain' => $this->getSiteDomain(),
            'emails' => $emails
        ]);
    }

    public function privacy()
    {
        $emails = $this->getEmailAddresses();
        
        return view('extras.privacy', [
            'siteUrl' => $this->getSiteUrl(),
            'fullSiteUrl' => $this->getFullSiteUrl(),
            'siteDomain' => $this->getSiteDomain(),
            'emails' => $emails
        ]);
    }

    public function kyc()
    {
        $emails = $this->getEmailAddresses();
        
        return view('extras.aml-policy', [
            'siteUrl' => $this->getSiteUrl(),
            'fullSiteUrl' => $this->getFullSiteUrl(),
            'siteDomain' => $this->getSiteDomain(),
            'emails' => $emails
        ]);
    }

    public function betting()
    {
        $emails = $this->getEmailAddresses();
        
        return view('extras.betting-terms', [
            'siteUrl' => $this->getSiteUrl(),
            'fullSiteUrl' => $this->getFullSiteUrl(),
            'siteDomain' => $this->getSiteDomain(),
            'emails' => $emails
        ]);
    }
    
    public function lgpd()
    {
        $emails = $this->getEmailAddresses();
        
        return view('extras.lgpd', [
            'siteUrl' => $this->getSiteUrl(),
            'fullSiteUrl' => $this->getFullSiteUrl(),
            'siteDomain' => $this->getSiteDomain(),
            'emails' => $emails
        ]);
    }

    public function responsible()
    {
        $emails = $this->getEmailAddresses();
        
        return view('extras.responsible-gaming', [
            'siteUrl' => $this->getSiteUrl(),
            'fullSiteUrl' => $this->getFullSiteUrl(),
            'siteDomain' => $this->getSiteDomain(),
            'emails' => $emails
        ]);
    }
    public function footer()
    {
        $emails = $this->getEmailAddresses();
        
        return view('partials.footer', [
            'siteUrl' => $this->getSiteUrl(),
            'fullSiteUrl' => $this->getFullSiteUrl(),
            'siteDomain' => $this->getSiteDomain(),
            'emails' => $emails
        ]);
    }

    /**
     * Display the user's notifications.
     * 
     * @return \Illuminate\View\View
     */
    public function notifications()
    {
        // Redireciona para o controlador de notificações
        return redirect()->route('notifications.index');
    }
    
    /**
     * Create a notification for all users.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createGlobalNotification(Request $request)
    {
        // Verificar se é administrador
        if (!Auth::user() || !Auth::user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_pt_br' => 'required|string|max:255',
            'title_es' => 'required|string|max:255',
            'content_en' => 'required|string',
            'content_pt_br' => 'required|string',
            'content_es' => 'required|string',
            'link' => 'nullable|string|max:255',
        ]);
        
        // Obter todos os IDs de usuários
        $userIds = \App\Models\User::pluck('id')->toArray();
        $notifications = [];
        
        // Criar uma notificação para cada usuário
        foreach ($userIds as $userId) {
            $notificationData = array_merge($validated, ['user_id' => $userId]);
            $notifications[] = Notification::create($notificationData);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notificações globais criadas com sucesso!',
            'count' => count($notifications)
        ]);
    }
}