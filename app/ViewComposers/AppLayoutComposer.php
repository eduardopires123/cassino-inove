<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use App\Helpers\Core as Helper;
use App\Helpers\SeoHelper;
use App\Http\Controllers\Admin\CustomCSSController;
use App\Models\FooterSettings;
use App\Models\HomeSectionsSettings;
use App\Models\Transactions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AppLayoutComposer
{
    /**
     * Compartilha dados com o layout app.blade.php
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Get active theme from the controller
        $activeTheme = CustomCSSController::getActiveTheme();

        // Verificar se o topbar deve estar oculto baseado no cookie
        $topbarClosed = isset($_COOKIE['topbar_closed']) && $_COOKIE['topbar_closed'] === 'true';
        $topbarStyle = $topbarClosed ? 'display: none;' : '';
        $sidebarTopValue = $topbarClosed ? '65px' : '105px';

        $Infos = Helper::getSetting();
        $User = Auth::user() ?? null;

        // Obter configurações das seções da home
        $homeSections = HomeSectionsSettings::getSettings();

        $id = (string) Str::uuid();

        // Verificar se é primeiro depósito
        $isFirstDeposit = false;
        if (Auth()->check() && $User) {
            $Tran = Transactions::where('user_id', $User->id)
                ->where('type', 0)
                ->where('status', 1)
                ->count();
            $isFirstDeposit = $Tran == 0;
        }

        // Configurações de bônus
        $BonusMulti = 0;
        $BonusAllDeposits = false;
        if ($Infos) {
            $BonusMulti = $Infos->bonus_mult ?? 0;
            $BonusAllDeposits = $Infos->bonus_all_deposits ?? false;
        }

        if ($BonusAllDeposits) {
            $isFirstDeposit = true;
        }

        // Obter configurações do footer
        $footerSettings = FooterSettings::getSettings();

        // Gerar SEO Meta Tags
        $seoMetaTags = SeoHelper::isActive() ? SeoHelper::generateAllMetaTags() : '';
        $seoScripts = SeoHelper::isActive() ? SeoHelper::generateAllScripts() : '';
        $seoBodyScripts = SeoHelper::isActive() ? SeoHelper::generateBodyScripts() : '';
        $seoActive = SeoHelper::isActive();

        // Verificar se PWA está ativado (independente de SEO estar ativo)
        $pwaEnabled = false;
        $seoSettings = null;
        // Buscar configurações PWA independente de is_active
        $pwaSettings = \App\Models\SeoSettings::getPwaSettings();
        $pwaEnabled = $pwaSettings->pwa_enabled ?? false;
        
        // Buscar configurações SEO apenas se SEO estiver ativo
        if ($seoActive) {
            $seoSettings = \App\Models\SeoSettings::getSettings();
        }

        // Definir dados do site baseado no SEO ou configurações gerais
        // Se SEO está ativo, usar dados do SEO, senão usar dados das configurações gerais
        $siteName = $seoActive && $seoSettings && $seoSettings->site_title 
            ? $seoSettings->site_title 
            : ($Infos->name ?? config('app.name'));
        
        $siteSubtitle = $seoActive && $seoSettings && $seoSettings->site_subtitle 
            ? $seoSettings->site_subtitle 
            : null;
        
        $siteSubname = $seoActive && $seoSettings && $seoSettings->og_site_name 
            ? $seoSettings->og_site_name 
            : ($Infos->subname ?? config('app.name'));
        
        $siteDescription = $seoActive && $seoSettings && $seoSettings->site_description 
            ? $seoSettings->site_description 
            : ($Infos->name ?? config('app.name'));
        
        $siteFavicon = $seoActive && $seoSettings && $seoSettings->favicon 
            ? $seoSettings->favicon 
            : ($Infos->favicon ?? null);
        
        $siteAppleTouchIcon = $seoActive && $seoSettings && $seoSettings->apple_touch_icon 
            ? $seoSettings->apple_touch_icon 
            : null;

        // Compartilhar todas as variáveis com a view
        $view->with([
            'activeTheme' => $activeTheme,
            'topbarClosed' => $topbarClosed,
            'topbarStyle' => $topbarStyle,
            'sidebarTopValue' => $sidebarTopValue,
            'Infos' => $Infos,
            'User' => $User,
            'homeSections' => $homeSections,
            'id' => $id,
            'isFirstDeposit' => $isFirstDeposit,
            'BonusMulti' => $BonusMulti,
            'BonusAllDeposits' => $BonusAllDeposits,
            'footerSettings' => $footerSettings,
            'seoMetaTags' => $seoMetaTags,
            'seoScripts' => $seoScripts,
            'seoBodyScripts' => $seoBodyScripts,
            'seoActive' => $seoActive,
            'pwaEnabled' => $pwaEnabled,
            'siteName' => $siteName,
            'siteSubtitle' => $siteSubtitle,
            'siteSubname' => $siteSubname,
            'siteDescription' => $siteDescription,
            'siteFavicon' => $siteFavicon,
            'siteAppleTouchIcon' => $siteAppleTouchIcon,
        ]);
    }
}

