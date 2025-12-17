<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuCategoria;
use App\Models\MenuItems;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Exibe a tela de gerenciamento de menus
     */
    public function index()
    {
        return view('admin.personalizacao.menu');
    }

    /**
     * Carrega os itens de uma categoria específica
     */
    public function loadItems($id)
    {
        try {
            // Verificar se a categoria existe
            $categoria = MenuCategoria::find($id);
            
            // Registrar o ID da categoria e informações para debug
            Log::info('Carregando itens para categoria ID: ' . $id);
            Log::info('Categoria existe: ' . ($categoria ? 'Sim' : 'Não'));
            
            // Mesmo se a categoria não existir, exibimos a interface para possível adição de itens
            return view('admin.personalizacao.partials.menu_items', [
                'Categoria' => $id,
                'CategoriaInfo' => $categoria
            ]);
            
        } catch (\Exception $e) {
            // Registrar o erro
            Log::error('Erro ao carregar itens do menu: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Retornar uma mensagem de erro
            return '<div class="alert alert-danger">Erro ao carregar itens do menu: ' . $e->getMessage() . '</div>';
        }
    }

    /**
     * Atualiza os dados de uma categoria
     */
    public function updateCategory(Request $request)
    {
        try {
            $categoria = MenuCategoria::findOrFail($request->id);
            
            // Adicionar id_cliente se o modelo requer
            if (empty($categoria->id_cliente) && Auth::check()) {
                $categoria->id_cliente = Auth::user()->id_cliente ?? 0;
            }
            
            // Validação dos campos
            if ($request->campo === 'ordem') {
                if (!is_numeric($request->valor) || $request->valor < 0) {
                    return response()->json(['success' => false, 'message' => 'Valor de ordem inválido']);
                }
            }
            
            // Atualizar o campo específico
            $categoria->{$request->campo} = $request->valor;
            
            // Se o campo for nome, atualizar slug
            if ($request->campo === 'nome') {
                $categoria->slug = Str::slug($request->valor);
            }
            
            $categoria->save();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar categoria: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Atualiza os dados de um item de menu
     */
    public function updateItem(Request $request)
    {
        try {
            $item = MenuItems::findOrFail($request->id);
            
            // Adicionar id_cliente se o modelo requer
            if (empty($item->id_cliente) && Auth::check()) {
                $item->id_cliente = Auth::user()->id_cliente ?? 0;
            }
            
            // Validação dos campos
            if ($request->campo === 'ordem') {
                if (!is_numeric($request->valor) || $request->valor < 0) {
                    return response()->json(['success' => false, 'message' => 'Valor de ordem inválido']);
                }
            }
            
            // Atualizar o campo específico
            $item->{$request->campo} = $request->valor;
            
            // Se o campo for nome, atualizar slug
            if ($request->campo === 'nome') {
                $item->slug = Str::slug($request->valor);
            }
            
            $item->save();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar item: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Adiciona um novo item de menu
     */
    public function addItem(Request $request)
    {
        try {
            // Validação dos dados
            $request->validate([
                'nome' => 'required|string|max:255',
                'icone' => 'required|string',
                'link' => 'required|string|max:255',
                'ordem' => 'required|integer|min:0',
                'categoria' => 'required'
            ]);
            
            // Criar novo item
            $item = new MenuItems();
            $item->nome = $request->nome;
            $item->slug = Str::slug($request->nome);
            $item->icone = $request->icone;
            $item->link = $request->link;
            $item->ordem = $request->ordem;
            $item->categoria = $request->categoria;
            $item->active = 1; // Ativo por padrão
            
            // Adicionar id_cliente se o modelo requer
            if (Auth::check()) {
                $item->id_cliente = Auth::user()->id_cliente ?? 0;
            }
            
            $item->save();
            
            return response()->json([
                'success' => true, 
                'message' => 'Item adicionado com sucesso',
                'item' => $item
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação ao adicionar item: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false, 
                'message' => 'Erro de validação: ' . implode(', ', array_map(function($item) {
                    return $item[0];
                }, $e->errors()))
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao adicionar item: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao adicionar item: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Exclui um item de menu
     */
    public function deleteItem(Request $request)
    {
        try {
            $item = MenuItems::findOrFail($request->id);
            $item->delete();
            
            return response()->json(['success' => true, 'message' => 'Item excluído com sucesso']);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir item: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao excluir item: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Adiciona uma nova categoria de menu
     */
    public function addCategory(Request $request)
    {
        try {
            // Validação dos dados
            $request->validate([
                'nome' => 'required|string|max:255',
                'ordem' => 'required|integer|min:0',
                'tipo' => 'sometimes|string'
            ]);
            
            // Criar nova categoria
            $categoria = new MenuCategoria();
            $categoria->nome = $request->nome;
            $categoria->slug = Str::slug($request->nome);
            $categoria->ordem = $request->ordem;
            $categoria->tipo = $request->tipo ?? 'principal';
            $categoria->active = 1; // Ativa por padrão
            
            // Adicionar id_cliente se o modelo requer
            if (Auth::check()) {
                $categoria->id_cliente = Auth::user()->id_cliente ?? 0;
            }
            
            $categoria->save();
            
            return response()->json([
                'success' => true, 
                'message' => 'Categoria adicionada com sucesso',
                'categoria' => $categoria
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação ao adicionar categoria: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false, 
                'message' => 'Erro de validação: ' . implode(', ', array_map(function($item) {
                    return $item[0];
                }, $e->errors()))
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao adicionar categoria: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao adicionar categoria: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Exclui uma categoria de menu e todos os seus itens
     */
    public function deleteCategory(Request $request)
    {
        try {
            $categoria = MenuCategoria::findOrFail($request->id);
            
            // Excluir todos os itens relacionados primeiro
            MenuItems::where('categoria', $categoria->id)->delete();
            
            // Excluir a categoria
            $categoria->delete();
            
            return response()->json(['success' => true, 'message' => 'Categoria e itens excluídos com sucesso']);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir categoria: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao excluir categoria: ' . $e->getMessage()]);
        }
    }
}