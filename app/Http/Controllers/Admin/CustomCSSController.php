<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\CustomCSS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CustomCSSController extends Controller
{
    /**
     * Display the CSS configuration page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.customization.css');
    }

    /**
     * Update a single CSS variable
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateVariable(Request $request)
    {
        try {
            $variable = $request->input('variable');
            $value = $request->input('value');

            // Validate input
            if (!$variable || !$value) {
                return response()->json(['success' => false, 'message' => 'Missing variable or value'], 400);
            }

            // Convert CSS variable name to database column
            $columnName = 'css_' . str_replace('-', '_', $variable);

            // Update in database
            $cssModel = CustomCSS::findOrFail(1);
            $cssModel->$columnName = $value;
            $cssModel->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update active theme
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTheme(Request $request)
    {
        try {
            $themeId = $request->input('theme_id');

            // Validate input
            if (!$themeId || !is_numeric($themeId) || !in_array($themeId, [1, 2, 3, 4])) {
                return response()->json(['success' => false, 'message' => 'Invalid theme ID'], 400);
            }

            // Update in database
            $cssModel = CustomCSS::findOrFail(1);
            $cssModel->active_theme = $themeId;
            $cssModel->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update custom CSS
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCustom(Request $request)
    {
        try {
            $customCss = $request->input('custom_css');

            // Update in database
            $cssModel = CustomCSS::findOrFail(1);
            $cssModel->custom = urlencode($customCss);
            $cssModel->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update all CSS variables and custom CSS at once
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAll(Request $request)
    {
        try {
            $allVariables = json_decode($request->input('all_variables'), true);
            $customCss = $request->input('custom_css');

            // Update in database
            $cssModel = CustomCSS::findOrFail(1);

            foreach ($allVariables as $variable => $value) {
                $columnName = 'css_' . str_replace('-', '_', $variable);
                $cssModel->$columnName = $value;
            }

            if ($customCss) {
                $cssModel->custom = urlencode($customCss);
            }

            $cssModel->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Gera o código CSS para inclusão inline na página
     *
     * @return string
     */
    public static function getInlineCss()
    {
        // Busca diretamente do banco de dados
        $settings = CustomCSS::find(1);

        if (!$settings) {
            return ":root {\n    /* Valores padrão serão usados */\n}";
        }

        $cssVariables = $settings->getAttributes();

        // Gera o conteúdo CSS
        $cssContent = ":root {\n";

        foreach ($cssVariables as $key => $value) {
            // Pega apenas as colunas que começam com css_
            if (strpos($key, 'css_') === 0 && $key !== 'custom') {
                $cssVarName = str_replace('css_', '', $key);
                $cssVarName = str_replace('_', '-', $cssVarName);

                $cssContent .= "    --{$cssVarName}: {$value};\n";
            }
        }

        $cssContent .= "}\n";

        return $cssContent;
    }

    /**
     * Gera o CSS personalizado para inclusão inline
     *
     * @return string
     */
    public static function getCustomCss()
    {
        // Busca diretamente do banco de dados
        $settings = CustomCSS::find(1);

        if (!$settings || empty($settings->custom)) {
            return "/* Sem CSS personalizado */";
        }

        return urldecode($settings->custom);
    }

    /**
     * Gera todo o conteúdo CSS para inclusão inline
     *
     * @return string
     */
    public static function getAllInlineCss()
    {
        return Cache::remember('inline_css_cache', now()->addHours(6), function () {
            return self::getInlineCss() . "\n\n" . self::getCustomCss();
        });
    }

    /**
     * Retorna o tema ativo atual
     *
     * @return int
     */
    public static function getActiveTheme()
    {
        return Cache::remember('inline_theme_cache', now()->addHours(6), function () {
            $settings = CustomCSS::find(1);

            if (!$settings || !isset($settings->active_theme)) {
                return 1; // Default to theme 1 if not set
            }

            return (int) $settings->active_theme;
        });
    }
}
