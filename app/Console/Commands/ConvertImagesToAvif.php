<?php

namespace App\Console\Commands;

use App\Services\ImageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ConvertImagesToAvif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-to-avif 
                            {--path=img : Caminho relativo dentro da pasta public para converter} 
                            {--quality=80 : Qualidade das imagens AVIF (0-100)}
                            {--width=1920 : Largura máxima das imagens}
                            {--dry-run : Apenas mostrar o que seria feito sem executar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converte imagens existentes para o formato AVIF para melhorar o desempenho';

    /**
     * Create a new command instance.
     */
    public function __construct(private ImageService $imageService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = $this->option('path');
        $quality = (int) $this->option('quality');
        $width = (int) $this->option('width');
        $dryRun = $this->option('dry-run');
        
        // Validar parâmetros
        if ($quality < 1 || $quality > 100) {
            $this->error('A qualidade deve estar entre 1 e 100');
            return 1;
        }
        
        // Mostrar configuração
        $this->info("Convertendo imagens para AVIF:");
        $this->info("- Caminho: {$path}");
        $this->info("- Qualidade: {$quality}");
        $this->info("- Largura máxima: {$width}px");
        if ($dryRun) {
            $this->warn("MODO SIMULAÇÃO: Nenhuma alteração será realizada.");
        }
        
        // Encontrar todas as imagens no diretório
        $baseDir = public_path($path);
        if (!is_dir($baseDir)) {
            $this->error("Diretório não encontrado: {$baseDir}");
            return 1;
        }
        
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $images = $this->findImageFiles($baseDir, $extensions);
        
        $totalImages = count($images);
        $this->info("Encontradas {$totalImages} imagens para converter.");
        
        // Barra de progresso
        $bar = $this->output->createProgressBar($totalImages);
        $bar->start();
        
        $converted = 0;
        $skipped = 0;
        $errors = 0;
        
        foreach ($images as $image) {
            $relativePath = str_replace(public_path() . '/', '', $image);
            $pathInfo = pathinfo($image);
            $newFilename = $pathInfo['dirname'] . '/' . $pathInfo['filename'];
            $avifPath = $newFilename . '.avif';
            
            // Verificar se o arquivo AVIF já existe
            if (file_exists(public_path($avifPath))) {
                $this->newLine();
                $this->line(" <fg=yellow>✓</> Pulando {$relativePath} (AVIF já existe)");
                $skipped++;
                $bar->advance();
                continue;
            }
            
            if (!$dryRun) {
                try {
                    // Criar objeto UploadedFile simulado para usar com o serviço
                    $tempFile = new UploadedFile(
                        $image,
                        basename($image),
                        mime_content_type($image),
                        null,
                        true
                    );
                    
                    // Converter para AVIF
                    $result = $this->imageService->convertToAvif(
                        $tempFile,
                        $pathInfo['dirname'] . '/' . $pathInfo['filename'],
                        $quality,
                        $width,
                        null
                    );
                    
                    $this->newLine();
                    $this->line(" <fg=green>✓</> Convertido: {$relativePath} -> {$result}");
                    $converted++;
                } catch (\Exception $e) {
                    $this->newLine();
                    $this->error(" <fg=red>✗</> Erro ao converter {$relativePath}: " . $e->getMessage());
                    $errors++;
                }
            } else {
                $this->newLine();
                $this->line(" <fg=blue>i</> Seria convertido: {$relativePath} -> {$avifPath}");
                $converted++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        // Mostrar resumo
        $this->info("Conversão concluída!");
        $this->line("- Total de imagens: {$totalImages}");
        $this->line("- Convertidas: {$converted}");
        $this->line("- Puladas (já existem): {$skipped}");
        $this->line("- Erros: {$errors}");
        
        if ($dryRun) {
            $this->warn("SIMULAÇÃO: Nenhuma alteração foi realizada.");
        }
        
        return 0;
    }
    
    /**
     * Encontra recursivamente todos os arquivos de imagem em um diretório
     *
     * @param string $dir Diretório para procurar
     * @param array $extensions Lista de extensões a procurar
     * @return array Lista de caminhos completos para as imagens
     */
    private function findImageFiles(string $dir, array $extensions): array
    {
        $images = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, $extensions)) {
                    $images[] = $file->getPathname();
                }
            }
        }
        
        return $images;
    }
} 