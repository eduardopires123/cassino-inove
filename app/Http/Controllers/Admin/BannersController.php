<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Banners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class BannersController extends Controller
{
    // Tipos MIME de imagens permitidos
    private $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
        'image/avif'
    ];

    public function index()
    {
        return view('admin.personalizacao.banners');
    }

    public function store(Request $request)
    {
        try {
            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma imagem fornecida'
                ], 400);
            }

                $this->validateImage($request);

            $file = $request->file('image');
            $fileName = time() . '_banner_' . rand(1, 1000) . '.' . $file->getClientOriginalExtension();
            
            // Define o diretório
            $path = 'img/banners/';
            $fullPath = public_path($path);

            // Cria o diretório se não existir
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0777, true);
            }

            // Move o arquivo para o diretório
            $file->move($fullPath, $fileName);

                $banner = new Banners();
            $banner->imagem = $path . $fileName;
                $banner->link = $request->input('link', '');
                $banner->tipo = $request->input('tipo', 'slide');
                $banner->ordem = $request->input('ordem', 1);
                $banner->active = $request->input('active', true);
                $banner->mobile = $request->input('mobile', 'não');
                $banner->save();

            $this->clearCache();

                return response()->json([
                    'success' => true,
                    'message' => 'Banner adicionado com sucesso',
                    'banner' => $banner
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
        $banner = Banners::findOrFail($id);

        if ($request->hasFile('image')) {
            // Valida a imagem
            $this->validateImage($request);

                // Remove imagem antiga se existir e for local
                if (!empty($banner->imagem) && !str_starts_with($banner->imagem, 'http') && file_exists(public_path($banner->imagem))) {
                    @unlink(public_path($banner->imagem));
            }
            
                $file = $request->file('image');
                $fileName = time() . '_banner_' . rand(1, 1000) . '.' . $file->getClientOriginalExtension();
                
                // Define o diretório
                $path = 'img/banners/';
                $fullPath = public_path($path);

                // Cria o diretório se não existir
                if (!file_exists($fullPath)) {
                    mkdir($fullPath, 0777, true);
                }

                // Move o arquivo para o diretório
                $file->move($fullPath, $fileName);

                $banner->imagem = $path . $fileName;
        }

        // Atualiza outros campos se fornecidos
        if ($request->has('link')) $banner->link = $request->link;
        if ($request->has('ordem')) $banner->ordem = $request->ordem;
        if ($request->has('active')) $banner->active = $request->active;
        if ($request->has('mobile')) $banner->mobile = $request->mobile;

        $banner->save();
        $this->clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Banner atualizado com sucesso',
            'banner' => $banner
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar banner: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID do banner não fornecido'
            ], 400);
        }

        $banner = Banners::find($id);

        if (!$banner) {
            return response()->json([
                'success' => false,
                'message' => 'Banner não encontrado'
            ], 404);
        }

        // Remove a imagem local se existir
        if (!empty($banner->imagem) && !str_starts_with($banner->imagem, 'http') && file_exists(public_path($banner->imagem))) {
            @unlink(public_path($banner->imagem));
        }

        $banner->delete();
        $this->clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Banner removido com sucesso'
        ]);
    }

    public function updateOrder(Request $request)
    {
        try {
            $orders = $request->get('orders');

            foreach ($orders as $id => $ordem) {
                Banners::where('id', $id)->update(['ordem' => $ordem]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ordem atualizada com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar ordem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleActive(Request $request)
    {
        try {
            $id = $request->id;
            $banner = Banners::findOrFail($id);

            // Se a requisição fornecer um estado específico, use-o; caso contrário, alterne
            if ($request->has('active')) {
                $banner->active = $request->active;
            } else {
                $banner->active = !$banner->active;
            }

            $banner->save();

            // Limpar o cache do site após alterar o status do banner
            $this->clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Status do banner atualizado com sucesso',
                'active' => $banner->active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status do banner: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateMobile(Request $request)
    {
        try {
            $id = $request->id;
            $banner = Banners::findOrFail($id);

            // Atualiza o campo mobile
            if ($request->has('mobile')) {
                $banner->mobile = $request->mobile;
                $banner->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Tipo de dispositivo atualizado com sucesso',
                    'mobile' => $banner->mobile
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Parâmetro mobile não informado'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar tipo de dispositivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateImage(Request $request)
    {
        try {
            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma imagem fornecida'
                ], 400);
            }

            $this->validateImage($request);

            $id = $request->id;
            $banner = Banners::findOrFail($id);

            // Remove imagem antiga se existir e for local
            if (!empty($banner->imagem) && !str_starts_with($banner->imagem, 'http') && file_exists(public_path($banner->imagem))) {
                @unlink(public_path($banner->imagem));
            }

            $file = $request->file('image');
            $fileName = time() . '_banner_' . rand(1, 1000) . '.' . $file->getClientOriginalExtension();
            
            // Define o diretório
            $path = 'img/banners/';
            $fullPath = public_path($path);

            // Cria o diretório se não existir
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0777, true);
            }

            // Move o arquivo para o diretório
            $file->move($fullPath, $fileName);

            $banner->imagem = $path . $fileName;
                $banner->save();

                $this->clearCache();

                return response()->json([
                    'success' => true,
                    'message' => 'Imagem do banner atualizada com sucesso',
                    'banner' => $banner
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Valida o arquivo de imagem
     *
     * @param Request $request
     * @throws \Exception
     */
    private function validateImage(Request $request)
    {
        $image = $request->file('image');
        $mimeType = $image->getMimeType();

        if (!in_array($mimeType, $this->allowedMimes)) {
            $allowedTypesStr = implode(', ', array_map(function($mime) {
                return strtoupper(str_replace('image/', '', $mime));
            }, $this->allowedMimes));
            
            throw new \Exception("Tipo de arquivo não suportado. Formatos permitidos: {$allowedTypesStr}");
        }

        // Validar tamanho máximo
        $maxSize = 10 * 1024 * 1024; // 10MB
        if ($image->getSize() > $maxSize) {
            throw new \Exception('O arquivo excede o tamanho máximo permitido de 10MB');
        }
    }

    /**
     * Clear the application cache
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCache()
    {
        try {
            // 1. Limpar cache básico do Laravel
            Cache::flush();

            // 2. Comandos essenciais do Artisan
            $essentialCommands = [
                'cache:clear' => 'Limpeza do cache da aplicação',
                'view:clear' => 'Limpeza do cache de views',
                'config:clear' => 'Limpeza do cache de configuração',
                'route:clear' => 'Limpeza do cache de rotas',
                'event:clear' => 'Limpeza do cache de eventos',
                'optimize:clear' => 'Limpeza de otimizações',
            ];

            foreach ($essentialCommands as $command => $description) {
                try {
                    Artisan::call($command);
                } catch (\Exception $e) {
                    // Silenciosamente ignora erros de comandos opcionais
                }
            }

            // 3. Comandos adicionais (se disponíveis)
            $additionalCommands = [
                'queue:clear' => 'Limpeza da fila',
                'schedule:clear-cache' => 'Limpeza do cache de schedule',
                'auth:clear-resets' => 'Limpeza de tokens de reset',
                'telescope:clear' => 'Limpeza do Telescope',
                'horizon:clear' => 'Limpeza do Horizon',
                'debugbar:clear' => 'Limpeza do Debug Bar',
            ];

            foreach ($additionalCommands as $command => $description) {
                try {
                    Artisan::call($command);
                } catch (\Exception $e) {
                    // Silenciosamente ignora erros de comandos opcionais
                }
            }

            // 4. Limpar OPcache (se disponível)
            if (function_exists('opcache_reset')) {
                try {
                    opcache_reset();
                } catch (\Exception $e) {
                    // Silenciosamente ignora erros
                }
            }

            // 5. Limpar APCu cache (se disponível)
            if (function_exists('apcu_clear_cache')) {
                try {
                    apcu_clear_cache();
                } catch (\Exception $e) {
                    // Silenciosamente ignora erros
                }
            }

            // 6. Limpar arquivos de cache manualmente
            $this->clearCacheFiles();

            // 7. Limpar cache de sessões se usando file driver
            $this->clearSessionFiles();

            // 8. Limpar cache específico da home
            try {
                $homeController = new \App\Http\Controllers\HomeController();
                $homeController->clearHomeCache();
            } catch (\Exception $e) {
                // Silenciosamente ignora erros
            }

            // 9. Limpar logs antigos (opcional)
            $this->clearOldLogs();

            // 10. Forçar coleta de lixo do PHP
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }

            return response()->json([
                'success' => true,
                'message' => 'Cache limpo completamente com sucesso! Todos os caches foram removidos.'
            ])->header('Content-Type', 'application/json');

        } catch (\Exception $e) {
            \Log::error('Erro ao limpar cache: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar cache: ' . $e->getMessage()
            ], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Limpar arquivos de cache manualmente
     */
    private function clearCacheFiles()
    {
        $cachePaths = [
            storage_path('framework/cache/data') => 'Cache de dados',
            storage_path('framework/views') => 'Views compiladas',
            storage_path('framework/sessions') => 'Sessões em arquivo',
            storage_path('app/cache') => 'Cache da aplicação',
            storage_path('cache') => 'Cache geral',
            base_path('bootstrap/cache') => 'Bootstrap cache',
            storage_path('debugbar') => 'Debug bar cache',
        ];

        foreach ($cachePaths as $path => $description) {
            try {
                if (is_dir($path)) {
                    $files = glob($path . '/*');
                    $cleared = 0;

                    foreach ($files as $file) {
                        if (is_file($file) && basename($file) !== '.gitignore') {
                            if (unlink($file)) {
                                $cleared++;
                            }
                        } elseif (is_dir($file) && basename($file) !== '.' && basename($file) !== '..') {
                            // Limpar subdiretórios recursivamente
                            $this->clearDirectory($file);
                        }
                    }

                }
            } catch (\Exception $e) {
                // Silenciosamente ignora erros
            }
        }
    }

    /**
     * Limpar diretório recursivamente
     */
    private function clearDirectory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..', '.gitignore']);

        foreach ($files as $file) {
            $filePath = $dir . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                $this->clearDirectory($filePath);
                @rmdir($filePath);
            } else {
                @unlink($filePath);
            }
        }
    }

    /**
     * Limpar arquivos de sessão
     */
    private function clearSessionFiles()
    {
        try {
            $sessionPath = storage_path('framework/sessions');
            if (is_dir($sessionPath)) {
                $files = glob($sessionPath . '/*');
                $cleared = 0;

                foreach ($files as $file) {
                    if (is_file($file) && basename($file) !== '.gitignore') {
                        // Limpar sessões mais antigas que 24 horas
                        if (filemtime($file) < (time() - 86400)) {
                            if (unlink($file)) {
                                $cleared++;
                            }
                        }
                    }
                }

            }
        } catch (\Exception $e) {
            // Silenciosamente ignora erros
        }
    }

    /**
     * Limpar logs antigos (opcional)
     */
    private function clearOldLogs()
    {
        try {
            $logPath = storage_path('logs');
            if (is_dir($logPath)) {
                $files = glob($logPath . '/*.log');
                $cleared = 0;

                foreach ($files as $file) {
                    // Manter apenas logs dos últimos 7 dias
                    if (filemtime($file) < (time() - (7 * 86400))) {
                        if (unlink($file)) {
                            $cleared++;
                        }
                    }
                }

            }
        } catch (\Exception $e) {
            // Silenciosamente ignora erros
        }
    }

    /**
     * Obter ordens atuais dos banners do banco de dados
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrders()
    {
        try {
            $banners = Banners::select('id', 'ordem', 'tipo', 'mobile')
                ->orderBy('tipo')
                ->orderBy('mobile')
                ->orderBy('ordem')
                ->get();

            $orders = [];
            foreach ($banners as $banner) {
                $orders[$banner->id] = $banner->ordem;
            }

            return response()->json([
                'success' => true,
                'orders' => $orders,
                'message' => 'Ordens recuperadas com sucesso'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar ordens dos banners: ' . $e->getMessage()
            ], 500);
        }
    }
}
