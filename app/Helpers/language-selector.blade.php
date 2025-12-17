<?php

if (!function_exists('localized_route')) {
    /**
     * Gera uma URL localizada para a rota especificada
     *
     * @param string $name Nome da rota
     * @param array $parameters Parâmetros da rota
     * @param string|null $locale Código do idioma (se null, usa o idioma atual)
     * @return string URL localizada
     */
    function localized_route($name, $parameters = [], $locale = null)
    {
        // Se nenhum idioma for especificado, use o idioma atual
        if ($locale === null) {
            $locale = app()->getLocale();
        }
        
        // Se for o idioma padrão (pt_BR), use as rotas com sufixo .pt para as rotas de tickets
        if ($locale === 'pt_BR') {
            // Verificar se é uma rota de tickets
            if (strpos($name, 'tickets.') === 0) {
                return route($name . '.pt', $parameters);
            }
            return route($name, $parameters);
        }
        
        // Para outros idiomas, use as rotas com sufixo .localized
        return route($name . '.localized', array_merge(['locale' => $locale], $parameters));
    }
}

if (!function_exists('language_switch_url')) {
    /**
     * Gera uma URL para troca de idioma
     *
     * @param string $locale Código do idioma desejado
     * @return string URL para troca de idioma
     */
    function language_switch_url($locale)
    {
        return route('language.switch', ['locale' => $locale]);
    }
}