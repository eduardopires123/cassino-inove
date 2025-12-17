<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddAvifHeaders
{
    /**
     * Adiciona cabeçalhos apropriados para imagens AVIF
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Verifica se a resposta é um arquivo
        $path = $request->path();
        if (preg_match('/\.(avif|webp|jpg|jpeg|png|gif)$/i', $path, $matches)) {
            $extension = strtolower($matches[1]);
            
            // Definir o tipo MIME correto para AVIF
            if ($extension === 'avif') {
                $response->header('Content-Type', 'image/avif');
            } else if ($extension === 'webp') {
                $response->header('Content-Type', 'image/webp');
            }
            
            // Adicionar cabeçalhos de cache para todas as imagens
            $response->header('Cache-Control', 'public, max-age=31536000, immutable');
            $response->header('Vary', 'Accept');
        }
        
        return $response;
    }
} 