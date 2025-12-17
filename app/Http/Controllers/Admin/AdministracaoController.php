<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;

class AdministracaoController extends Controller
{
    /**
     * Exibe a página de configurações gerais
     */
    public function configuracoesGerais()
    {
        $settings = Setting::first();
        return view('admin.config.gerais', compact('settings'));
    }
    
    /**
     * Exibe a página de configurações de banco
     */
    public function banco()
    {
        $settings = Setting::first();
        return view('admin.config.banco', compact('settings'));
    }
    
    /**
     * Exibe a página de configurações de gateways
     */
    public function gateways()
    {
        return view('admin.config.gateways');
    }
    
    /**
     * Exibe a página de configurações de APIs de jogos
     */
    public function apisGames()
    {
        return view('admin.config.apisgames');
    }
    
    /**
     * Exibe a página de funções e permissões
     */
    public function funcoesEPermissoes()
    {
        $users = User::where('is_admin', 1)->get();
        return view('admin.config.permissoes', compact('users'));
    }
    
    /**
     * Salvar configurações gerais
     */
    public function salvarConfiguracoesGerais(Request $request)
    {
        // Implementação de salvamento de configurações
        return redirect()->back()->with('success', 'Configurações salvas com sucesso!');
    }
    
    /**
     * Salvar configurações de banco
     */
    public function salvarBanco(Request $request)
    {
        // Implementação de salvamento de configurações de banco
        return redirect()->back()->with('success', 'Configurações de banco salvas com sucesso!');
    }
} 