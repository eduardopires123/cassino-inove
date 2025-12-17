<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Banner;

class BannerController extends Controller
{
    /**
     * Obter todos os banners ativos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBanners()
    {
        try {
            // Usar o driver de arquivo para cache
            $banners = Cache::driver('file')->remember('site_banners', 3600, function () {
                $banners = Banner::where('active', 1)
                    ->orderBy('ordem', 'asc')
                    ->get();

                // Garantir que as URLs das imagens estejam corretas
                $banners->each(function ($banner) {
                    // Remover barras iniciais extras se existirem
                    $banner->imagem = ltrim($banner->imagem, '/');
                });

                return $banners;
            });

            return response()->json($banners);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar banners'], 500);
        }
    }

    /**
     * Obter banners por tipo específico
     *
     * @param string $tipo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBannersByType($tipo)
    {
        try {
            // Cache específico para cada tipo de banner
            $cacheKey = 'site_banners_' . $tipo;

            $banners = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($tipo) {
                $banners = Banner::where('active', 1)
                    ->where('tipo', $tipo)
                    ->orderBy('ordem', 'asc')
                    ->get();

                return $banners;
            });

            return response()->json($banners);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar banners do tipo ' . $tipo . $e->getMessage()], 500);
        }
    }

    /**
     * Método de conveniência para obter banners de slide
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSlides()
    {
        return $this->getBannersByType('slide');
    }

    /**
     * Método de conveniência para obter banners de registro
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRegisterBanners()
    {
        return $this->getBannersByType('register');
    }

    /**
     * Método de conveniência para obter banners de login
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLoginBanners()
    {
        return $this->getBannersByType('login');
    }
}
