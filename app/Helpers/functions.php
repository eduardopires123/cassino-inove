<?php

// Arquivo de funções auxiliares
// Você pode adicionar funções globais úteis aqui

use Illuminate\Support\Facades\Auth;

/**
 * Exemplo de função auxiliar
 *
 * @param string $text
 * @return string
 */
// function exemplo($text) {
//     return $text;
// }

/**
 * Formata um valor numérico para o formato de moeda brasileira
 *
 * @param float $valor Valor a ser formatado
 * @return string Valor formatado (ex: 1.000,00)
 */
function FormataReal($valor) {
    if (empty($valor)) $valor = 0;
    return number_format($valor, 2, ',', '.');
}

/**
 * Verifica se o usuário logado tem permissão para um determinado módulo
 *
 * @param int $permissionId ID da permissão a ser verificada
 * @return int Retorna 1 se tem permissão, 0 se não tem
 */
function ChecaPermissao($permissionId) {
    $user = auth()->user();
    $User = App\Models\Admin\Permissions::where('user_id', $user->id)->first();
    $PermissoesData = json_decode($User->permission, true);

    if ($user->is_admin == 1) {
        return 1;
    }else {
        if ($user->is_admin == 2) {
            if (isset($PermissoesData[$permissionId]) && $PermissoesData[$permissionId] == 1) {
                return 1;
            } else {
                return 0;
            }
        }elseif ($user->is_admin == 3) {
            if ($permissionId == 9) {
                return 1;
            }
        }
    }

    return 0;
}

/**
 * Completa uma URL de imagem se ela não for uma URL completa
 *
 * @param string $imagePath Caminho da imagem
 * @return string URL completa da imagem
 */
function completeImageUrl($imagePath)
{
    // Verifica se a imagem já é uma URL completa
    if (empty($imagePath) || preg_match('/^https?:\/\//i', $imagePath)) {
        return $imagePath;
    }

    // Se o caminho não é uma URL completa, usar asset() para gerar a URL da pasta public
    return asset($imagePath);
}

/**
 * Completa URLs de imagens em objetos de jogos
 *
 * @param object|array $game Objeto ou coleção de objetos de jogo
 * @return object|array Objeto ou coleção com URLs completas
 */
function completeGameImageUrl($game)
{
    if (is_array($game) || $game instanceof \Illuminate\Support\Collection) {
        // Para coleções ou arrays, aplique a função a cada item
        return collect($game)->map(function($item) {
            return completeGameImageUrl($item);
        });
    }

    // Cria a propriedade image_url no objeto com a URL completa
    if (isset($game->image)) {
        $game->image_url = completeImageUrl($game->image);
    }

    return $game;
}

/**
 * Função otimizada para gerar URLs corretas de imagens de jogos
 * Funciona com diferentes tipos de campos de imagem
 *
 * @param object $item Objeto do jogo ou item que contém imagem
 * @param string $field Campo da imagem (image, game_image, img, etc)
 * @return string URL completa da imagem
 */
function getGameImageUrl($item, $field = 'image')
{
    // Se o item tem um campo image_url específico, usar primeiro
    if (isset($item->image_url) && !empty($item->image_url)) {
        return $item->image_url;
    }
    
    // Determinar o valor da imagem baseado no campo informado
    $imagePath = '';
    
    if (isset($item->$field) && !empty($item->$field)) {
        $imagePath = $item->$field;
    } elseif (isset($item->image) && !empty($item->image)) {
        $imagePath = $item->image;
    } elseif (isset($item->game_image) && !empty($item->game_image)) {
        $imagePath = $item->game_image;
    } elseif (isset($item->img) && !empty($item->img)) {
        $imagePath = $item->img;
    }
    
    // Se não encontrou nenhuma imagem, retornar string vazia
    if (empty($imagePath)) {
        return '';
    }
    
    // Verifica se a imagem já é uma URL completa
    if (preg_match('/^https?:\/\//i', $imagePath)) {
        return $imagePath;
    }
    
    // Se começar com 'storage/', usar url() para gerar a URL completa
    if (strpos($imagePath, 'storage/') === 0) {
        return url($imagePath);
    }
    
    // Se for apenas um caminho relativo, usar asset()
    return asset($imagePath);
}

/**
 * Retorna a URL correta para a página do cassino baseado na configuração de página inicial padrão
 *
 * @return string URL da página do cassino
 */
function getCassinoUrl()
{
    $settings = App\Helpers\Core::getSetting();
    $defaultHomePage = $settings->default_home_page ?? 'cassino';

    // Se cassino é a página inicial padrão, usar a rota home (/)
    // Caso contrário, usar a rota específica do cassino (/cassino)
    return $defaultHomePage === 'cassino' ? route('home') : route('cassino.index');
}

// Deixe este arquivo vazio se você não precisar de funções auxiliares por enquanto
