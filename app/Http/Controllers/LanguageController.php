<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    /**
     * Idiomas suportados pelo sistema
     */
    const SUPPORTED_LOCALES = ['pt_BR', 'en', 'es'];
    
    /**
     * Trocar idioma (método principal)
     * Usado tanto para AJAX quanto para redirect
     */
    public function switch(Request $request, $locale = null)
    {
        // Se for AJAX, pegar locale do request
        if ($request->isMethod('post') && $request->expectsJson()) {
            $locale = $request->input('language') ?? $request->input('locale');
        }
        
        // Validar idioma
        if (!$locale || !in_array($locale, self::SUPPORTED_LOCALES)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Idioma inválido'
                ], 400);
            }
            return redirect()->back()->with('error', 'Idioma inválido');
        }
        
        try {
            // 1. Limpar TODO o cache
            $this->clearAllCaches();
            
            // 2. Salvar idioma no usuário logado (se houver)
            if (Auth::check()) {
                $user = Auth::user();
                $user->language = $locale;
                $user->save();
            }
            
            // 3. Criar cookie com nova preferência
            $cookie = Cookie::make('user_locale', $locale, 525600); // 1 ano
            
            // 4. Log da operação
            Log::info("Idioma alterado para: {$locale}", [
                'user_id' => Auth::id() ?? 'guest',
                'ip' => $request->ip(),
                'method' => $request->isMethod('post') ? 'AJAX' : 'GET'
            ]);
            
            // 5. Retornar resposta adequada
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Idioma alterado com sucesso',
                    'locale' => $locale
                ])->withCookie($cookie);
            }
            
            return redirect('/')->withCookie($cookie)->with('success', 'Idioma alterado com sucesso');
            
        } catch (\Exception $e) {
            Log::error('Erro ao alterar idioma: ' . $e->getMessage(), [
                'locale' => $locale,
                'user_id' => Auth::id() ?? 'guest'
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao alterar idioma'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erro ao alterar idioma');
        }
    }
    
    /**
     * Limpar cookie de idioma (voltar para o padrão)
     */
    public function clear()
    {
        try {
            $this->clearAllCaches();
            
            Log::info('Cookie de idioma removido, voltando ao idioma padrão');
            
            return redirect('/')->withCookie(Cookie::forget('user_locale'))
                ->with('success', 'Idioma resetado para o padrão');
                
        } catch (\Exception $e) {
            Log::error('Erro ao limpar cookie de idioma: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao resetar idioma');
        }
    }
    
    /**
     * Limpar todos os caches do sistema
     */
    private function clearAllCaches()
    {
        try {
            // 1. Cache específico das partials (footer, sidebar, etc.)
            \App\Http\Controllers\PartialsController::clearPartialsCache();
            
            // 2. Cache do Laravel (Redis/File)
            Cache::flush();
            
            // 3. Limpar views compiladas
            Artisan::call('view:clear');
            
            // 4. Limpar cache de configuração
            Artisan::call('config:clear');
            
            // 5. Limpar cache de rotas
            Artisan::call('route:clear');
            
            // 6. Limpar cache de eventos (se existir)
            try {
                Artisan::call('event:clear');
            } catch (\Exception $e) {
                // Ignorar se não existir
            }
            
            Log::info('Todos os caches foram limpos com sucesso');
            
        } catch (\Exception $e) {
            Log::error('Erro ao limpar caches: ' . $e->getMessage());
            throw $e;
        }
    }
}