<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Icon;
use App\Models\GamesApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IconsController extends Controller
{
    public function index()
    {
        $icons = Icon::orderBy('ordem', 'asc')->get();
        $games = GamesApi::where('status', 1)->orderBy('name', 'asc')->get();
        
        return view('admin.personalizacao.icones', compact('icons', 'games'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Recebido request para store icon', [
                'name' => $request->input('name'),
                'svg' => substr($request->input('svg'), 0, 50) . '...',  // Log only the first 50 chars
                'active' => $request->input('active'),
                'active_type' => gettype($request->input('active')),
                'has_active' => $request->has('active'),
                'all_data' => $request->all()
            ]);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'svg' => 'required|string',
                'link' => 'nullable|string|max:255',
                'game_id' => 'nullable|string|max:255',
                'active' => 'boolean',
                'type' => 'nullable|string',
                'hot' => 'integer|in:0,1,2'
            ]);
            
            // Get the highest order value and add 1
            $maxOrder = Icon::max('ordem') ?? 0;
            
            $icon = new Icon();
            $icon->name = $request->input('name');
            $icon->svg = $request->input('svg');
            $icon->link = $request->input('link');
            $icon->game_id = $request->input('game_id');
            $icon->ordem = $maxOrder + 1;
            $icon->type = $request->input('type', 'icon');
            
            // Handle the active field explicitly
            $active = $request->input('active');
            if ($active === '1' || $active === 1 || $active === true || $active === 'true') {
                $icon->active = true;
            } else {
                $icon->active = false;
            }
            
            // Handle the hot field
            $hot = $request->input('hot');
            $icon->hot = intval($hot);
            
            $icon->save();
            
            Log::info('Icon criado com sucesso', ['id' => $icon->id]);
            
            // Clear cache
            $this->clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Ícone adicionado com sucesso',
                'icon' => $icon
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao adicionar ícone: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar ícone: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $icon = Icon::findOrFail($id);

            $request->validate([
                'name' => 'nullable|string|max:255',
                'svg' => 'nullable|string',
                'link' => 'nullable|string|max:255',
                'game_id' => 'nullable|string|max:255',
                'active' => 'nullable|boolean',
                'type' => 'nullable|string',
                'hot' => 'nullable|integer|in:0,1,2'
            ]);

            // Update fields if provided
            if ($request->has('name')) $icon->name = $request->name;
            if ($request->has('svg')) $icon->svg = $request->svg;
            if ($request->has('link')) $icon->link = $request->link;
            if ($request->has('game_id')) $icon->game_id = $request->game_id;
            if ($request->has('ordem')) $icon->ordem = $request->ordem;
            if ($request->has('active')) $icon->active = $request->active;
            if ($request->has('type')) $icon->type = $request->type;
            if ($request->has('hot')) $icon->hot = $request->hot;

            $icon->save();
            
            // Clear cache
            $this->clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Ícone atualizado com sucesso',
                'icon' => $icon
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar ícone: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Solicitação para excluir ícone', ['id' => $id]);
            
            $icon = Icon::findOrFail($id);
            
            // Delete the icon
            $icon->delete();
            Log::info("Ícone excluído com sucesso", ['id' => $id]);
            
            // Clear cache
            $this->clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Ícone removido com sucesso'
            ]);

        } catch (\Exception $e) {
            Log::error("Erro ao remover ícone: {$e->getMessage()}", [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover ícone: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateOrder(Request $request)
    {
        try {
            $orders = $request->get('icons');
            
            foreach ($orders as $id => $ordem) {
                Icon::where('id', $id)->update(['ordem' => $ordem]);
            }
            
            // Clear cache
            $this->clearCache();

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

    /**
     * Get a single icon by ID
     */
    public function show($id)
    {
        try {
            $icon = Icon::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'icon' => $icon
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar ícone: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleActive(Request $request)
    {
        try {
            $id = $request->id;
            $active = $request->active;
            
            if (!$id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID do ícone não fornecido'
                ], 400);
            }
            
            $icon = Icon::find($id);
            
            if (!$icon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ícone não encontrado'
                ], 404);
            }
            
            $icon->active = $active;
            $icon->save();
            
            // Clear cache
            $this->clearCache();
            
            return response()->json([
                'success' => true,
                'message' => 'Status do ícone alterado com sucesso'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear the application cache to refresh icon data
     */
    private function clearCache()
    {
        try {
            Cache::forget('icons_menu');
            Artisan::call('cache:clear');
            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao limpar cache: ' . $e->getMessage());
            return false;
        }
    }
} 