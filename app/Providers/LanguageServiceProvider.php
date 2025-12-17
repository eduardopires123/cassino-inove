<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar o Carbon (para datas) de acordo com o idioma atual
        $this->configureCarbon();
        
        // Compartilhar variáveis relacionadas ao idioma com todas as views
        $this->shareLanguageVariables();
    }
    
    /**
     * Configura o Carbon para usar o idioma atual
     */
    protected function configureCarbon(): void
    {
        // Mapear os locales do Laravel para os locales do Carbon
        $carbonLocaleMap = [
            'pt_BR' => 'pt_BR',
            'en' => 'en',
            'es' => 'es',
        ];
        
        $locale = App::getLocale();
        $carbonLocale = $carbonLocaleMap[$locale] ?? 'en';
        
        // Configurar locale do Carbon
        Carbon::setLocale($carbonLocale);
        
        // Carregar traduções de datetime.php
        if (file_exists(lang_path($locale . '/datetime.php'))) {
            $customizations = require lang_path($locale . '/datetime.php');
            
            foreach ($customizations as $key => $value) {
                if (is_array($value)) {
                    continue;
                }
                
                // Usar o método correto para adicionar traduções ao Carbon
                // O Laravel's Carbon usa uma versão diferente do método de tradução
                $this->addCarbonTranslation($locale, $key, $value);
            }
        }
    }
    
    /**
     * Método auxiliar para adicionar traduções ao Carbon de forma compatível
     */
    protected function addCarbonTranslation(string $locale, string $key, string $value): void
    {
        $translator = Carbon::getTranslator();
        
        // Verifica quais métodos estão disponíveis e usa o adequado
        if (method_exists($translator, 'addResource')) {
            // Symfony Translator (mais recente)
            $translator->addResource('array', [$key => $value], $locale);
        } elseif (method_exists($translator, 'addTranslation')) {
            // Método legado
            $translator->addTranslation($locale, $key, $value);
        } elseif (property_exists($translator, 'messages')) {
            // Acesso direto à propriedade
            $translator->messages[$locale][$key] = $value;
        } else {
            // Fallback - tentar definir a tradução
            Carbon::setTranslation($key, $value, $locale);
        }
    }
    
    /**
     * Compartilha variáveis relacionadas ao idioma com todas as views
     */
    protected function shareLanguageVariables(): void
    {
        // Lista de idiomas disponíveis
        $availableLocales = [
            'pt_BR' => [
                'name' => 'Português',
                'flag' => 'pt-br.png',
                'code' => 'pt_BR',
            ],
            'en' => [
                'name' => 'English',
                'flag' => 'en.png',
                'code' => 'en',
            ],
            'es' => [
                'name' => 'Español',
                'flag' => 'es.png',
                'code' => 'es',
            ],
        ];
        
        // Compartilhar variáveis com todas as views
        View::share('availableLocales', $availableLocales);
        View::share('currentLocale', App::getLocale());
    }
}