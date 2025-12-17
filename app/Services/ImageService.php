<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class ImageService
{
    protected $manager;

    public function __construct()
    {
        // Tentar usar Imagick primeiro, depois GD como fallback
        try {
            if (extension_loaded('imagick') && class_exists('Imagick')) {
                $this->manager = new ImageManager(new ImagickDriver());
            } else {
                throw new \Exception('Imagick not available');
            }
        } catch (\Exception $e) {
            // Fallback para GD se Imagick não estiver disponível
            $this->manager = new ImageManager(new GdDriver());
        }
    }

    /**
     * Converte uma imagem enviada para AVIF e salva
     *
     * @param UploadedFile $file Arquivo de imagem enviado
     * @param string $path Caminho para salvar (sem extensão)
     * @param int $quality Qualidade da imagem AVIF (0-100)
     * @param int $width Largura máxima (null para manter original)
     * @param int $height Altura máxima (null para manter original)
     * @return string Caminho completo do arquivo salvo
     */
    public function convertToAvif(UploadedFile $file, string $path, int $quality = 80, ?int $width = null, ?int $height = null): string
    {
        // Gerar caminho de destino com extensão AVIF
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $directory = pathinfo($path, PATHINFO_DIRNAME);
        $avifPath = $directory . '/' . $filename . '.avif';
        
        // Carregar imagem com Intervention
        $image = $this->manager->read($file->getRealPath());
        
        // Redimensionar se necessário mantendo proporção
        if ($width || $height) {
            $image = $image->scale(width: $width, height: $height);
        }
        
        // Verificar se a imagem original tem transparência
        $hasTransparency = $this->hasTransparency($file);
        
        // Criar diretório se não existir
        $fullDirectory = public_path($directory);
        if (!file_exists($fullDirectory)) {
            mkdir($fullDirectory, 0755, true);
        }
        
        $fullPath = public_path($avifPath);
        
        // Configurar opções para AVIF
        $avifOptions = [
            'quality' => $quality,
        ];
        
        // Se a imagem tem transparência, tentar preservá-la
        if ($hasTransparency) {
            // Verificar se estamos usando ImageMagick
            if ($this->manager->driver() instanceof ImagickDriver) {
                // Para ImageMagick, usar configurações específicas para preservar alpha
                try {
                    // Obter o core nativo do ImageMagick
                    $imagick = $image->core()->native();
                    
                    // Configurar para preservar transparência
                    if (method_exists($imagick, 'setImageBackgroundColor')) {
                        $imagick->setImageBackgroundColor(new \ImagickPixel('transparent'));
                    }
                    
                    // Manter canal alpha ativo
                    if (method_exists($imagick, 'setImageAlphaChannel')) {
                        $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_ACTIVATE);
                    }
                    
                    // Codificar com configurações específicas para transparência
                    $encodedImage = $image->encode(new \Intervention\Image\Encoders\AvifEncoder(
                        quality: $quality
                    ));
                } catch (\Exception $e) {
                    // Fallback para método padrão se houver erro
                    // Error setting transparency, continue without logging
                }
            } else {
                // Para GD, usar método padrão (GD tem limitações com AVIF e transparência)
                $encodedImage = $image->encodeByExtension('avif', $avifOptions);
            }
        } else {
            // Para imagens sem transparência, usar método padrão
            $encodedImage = $image->encodeByExtension('avif', $avifOptions);
        }
        
        // Salvar diretamente no diretório público
        file_put_contents($fullPath, $encodedImage->toString());
        
        return $avifPath;
    }
    
    /**
     * Converte uma imagem para WebP (formato de fallback)
     */
    public function convertToWebp(UploadedFile $file, string $path, int $quality = 80, ?int $width = null, ?int $height = null): string
    {
        // Gerar caminho de destino com extensão WebP
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $directory = pathinfo($path, PATHINFO_DIRNAME);
        $webpPath = $directory . '/' . $filename . '.webp';
        
        // Carregar imagem com Intervention
        $image = $this->manager->read($file->getRealPath());
        
        // Redimensionar se necessário mantendo proporção
        if ($width || $height) {
            $image = $image->scale(width: $width, height: $height);
        }
        
        // Codificar como WebP preservando transparência
        $encodedImage = $image->encodeByExtension('webp', [
            'quality' => $quality,
            'lossless' => false, // Usar compressão com perdas para melhor qualidade/tamanho
            'near_lossless' => false,
            'smart_subsample' => true,
            'alpha_quality' => 100 // Máxima qualidade para o canal alpha
        ]);
        
        // Criar diretório se não existir
        $fullDirectory = public_path($directory);
        if (!file_exists($fullDirectory)) {
            mkdir($fullDirectory, 0755, true);
        }
        
        // Salvar diretamente no diretório público
        $fullPath = public_path($webpPath);
        file_put_contents($fullPath, $encodedImage->toString());
        
        return $webpPath;
    }
    
    /**
     * Verifica se a imagem tem transparência
     */
    private function hasTransparency(UploadedFile $file): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Apenas formatos que suportam transparência podem ter
        if (!in_array($extension, ['png', 'gif', 'webp'])) {
            return false;
        }
        
        try {
            // Para PNG, fazer uma verificação mais robusta
            if ($extension === 'png') {
                // Verificar usando getimagesize que é mais confiável
                $imageInfo = getimagesize($file->getRealPath());
                if ($imageInfo !== false) {
                    // PNG com canal alpha (tipo 6) ou PNG em escala de cinza com alpha (tipo 4)
                    $pngType = $imageInfo['channels'] ?? 0;
                    
                    // Se tem 4 canais (RGBA) ou é PNG com transparência
                    if ($pngType === 4 || (isset($imageInfo['bits']) && $imageInfo['bits'] === 32)) {
                        return true;
                    }
                    
                    // Verificação adicional: ler alguns bytes do arquivo para procurar por transparência
                    $fileContent = file_get_contents($file->getRealPath());
                    // Procurar por chunk tRNS (transparência) em PNG
                    if (strpos($fileContent, 'tRNS') !== false) {
                        return true;
                    }
                }
                
                // Para PNG, assumir que pode ter transparência se não conseguir determinar
                return true;
            }
            
            // Para GIF, verificar se tem transparência
            if ($extension === 'gif') {
                // GIF sempre pode ter transparência, assumir que sim
                return true;
            }
            
            // Para WebP, assumir que pode ter transparência
            if ($extension === 'webp') {
                return true;
            }
            
            return false;
            
        } catch (\Exception $e) {
            // Error detecting transparency, return false
            return false;
        }
    }
    
    /**
     * Salva uma imagem sempre em formato otimizado
     * - WebP para imagens com transparência (melhor suporte)
     * - AVIF para imagens sem transparência (melhor compressão)
     *
     * @param UploadedFile $file Arquivo de imagem enviado
     * @param string $path Caminho para salvar (sem extensão)
     * @param int $quality Qualidade da imagem (0-100)
     * @param int $width Largura máxima (null para manter original)
     * @param int $height Altura máxima (null para manter original)
     * @return string Caminho completo do arquivo salvo
     */
    public function saveOptimizedImage(UploadedFile $file, string $path, int $quality = 80, ?int $width = null, ?int $height = null): string
    {
        try {
            // Verificar se a imagem tem transparência
            $hasTransparency = $this->hasTransparency($file);
            
            if ($hasTransparency) {
                // Para imagens com transparência, usar WebP (melhor suporte)
                // Imagen com transparência detectada, convertendo para WebP: ...
                return $this->convertToWebp($file, $path, $quality, $width, $height);
            } else {
                // Para imagens sem transparência, usar AVIF (melhor compressão)
                // Imagen sem transparência detectada, convertendo para AVIF: ...
                return $this->convertToAvif($file, $path, $quality, $width, $height);
            }
        } catch (\Exception $e) {
            // Failed to convert image, throw exception
            throw $e;
        }
    }
} 