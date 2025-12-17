<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RouletteItem;
use App\Models\RouletteSpin;
use App\Models\RouletteSettings;
use App\Models\HomeSectionsSettings;
use Illuminate\Support\Facades\Validator;

class RouletteController extends Controller
{
    /**
     * Configuração da roleta
     */
    public function config()
    {
        $rouletteItems = RouletteItem::orderBy('probability', 'desc')->get();
        $homeSections = HomeSectionsSettings::getSettings();
        $rouletteSettings = RouletteSettings::getSettings();
        
        return view('admin.personalizacao.roulette', compact('rouletteItems', 'homeSections', 'rouletteSettings'));
    }

    /**
     * Visualizar resgates da roleta
     */
    public function resgates(Request $request)
    {
        $query = RouletteSpin::with(['user', 'rouletteItem'])
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('user_name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_name . '%');
            });
        }

        if ($request->filled('item_name')) {
            $query->where('item_name', 'like', '%' . $request->item_name . '%');
        }

        if ($request->filled('prize_type')) {
            $query->where('prize_type', $request->prize_type);
        }

        if ($request->filled('is_free_spin')) {
            $query->where('is_free_spin', $request->is_free_spin);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $spins = $query->paginate(50);

        // Estatísticas
        $stats = [
            'total_spins' => RouletteSpin::count(),
            'total_free_spins' => RouletteSpin::where('is_free_spin', true)->count(),
            'total_paid_spins' => RouletteSpin::where('is_free_spin', false)->count(),
            'total_prizes_awarded' => RouletteSpin::sum('prize_awarded'),
            'spins_today' => RouletteSpin::whereDate('created_at', today())->count(),
            'unique_users' => RouletteSpin::distinct('user_id')->count('user_id')
        ];

        return view('admin.personalizacao.roulette-resgates', compact('spins', 'stats'));
    }

    /**
     * Atualizar configurações da roleta
     */
    public function updateSettings(Request $request)
    {
        try {
            // Configurações das seções home
            if ($request->has('show_roulette')) {
                $homeSections = HomeSectionsSettings::getSettings();
                $homeSections->show_roulette = $request->input('show_roulette');
                $homeSections->save();
            }

            // Configurações específicas da roleta
            $rouletteData = $request->only([
                'enable_free_daily_spin',
                'max_spins_per_day', 
                'guest_spins_enabled',
                'animation_duration',
                'show_confetti',
                'sound_enabled'
            ]);

            if (!empty($rouletteData)) {
                RouletteSettings::updateSettings($rouletteData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Configurações da roleta atualizadas com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar configurações da roleta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Criar novo item da roleta
     */
    public function createItem(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'free_spins' => 'required|integer|min:0',
                'game_name' => 'nullable|string|max:255',
                'color_code' => 'required|string|max:7',
                'coupon_code' => 'nullable|string|max:50',
                'probability' => 'required|numeric|min:0|max:100',
                'deposit_value' => 'required|numeric|min:0',
                'show_modal' => 'boolean',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }

            // Converter probabilidade de porcentagem para decimal
            $probability = $request->probability / 100;

            RouletteItem::create([
                'name' => $request->name,
                'free_spins' => $request->free_spins,
                'game_name' => $request->game_name,
                'color_code' => $request->color_code,
                'coupon_code' => $request->coupon_code,
                'probability' => $probability,
                'deposit_value' => $request->deposit_value,
                'show_modal' => $request->has('show_modal'),
                'is_active' => $request->has('is_active')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item da roleta criado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar item da roleta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar item da roleta
     */
    public function updateItem(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:roulette_items,id',
                'name' => 'required|string|max:255',
                'free_spins' => 'required|integer|min:0',
                'game_name' => 'nullable|string|max:255',
                'color_code' => 'required|string|max:7',
                'coupon_code' => 'nullable|string|max:50',
                'probability' => 'required|numeric|min:0|max:100',
                'deposit_value' => 'required|numeric|min:0',
                'show_modal' => 'boolean',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }

            $item = RouletteItem::findOrFail($request->id);
            
            // Converter probabilidade de porcentagem para decimal
            $probability = $request->probability / 100;
            
            $item->update([
                'name' => $request->name,
                'free_spins' => $request->free_spins,
                'game_name' => $request->game_name,
                'color_code' => $request->color_code,
                'coupon_code' => $request->coupon_code,
                'probability' => $probability,
                'deposit_value' => $request->deposit_value,
                'show_modal' => $request->has('show_modal'),
                'is_active' => $request->has('is_active')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item da roleta atualizado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar item da roleta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deletar item da roleta
     */
    public function deleteItem(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:roulette_items,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }

            $item = RouletteItem::findOrFail($request->id);
            $itemName = $item->name;
            $item->delete();

            return response()->json([
                'success' => true,
                'message' => "Item '{$itemName}' deletado com sucesso!"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar item da roleta: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status do item da roleta
     */
    public function toggleItemStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:roulette_items,id',
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }

            $item = RouletteItem::findOrFail($request->id);
            $item->is_active = $request->status;
            $item->save();

            $statusText = $request->status ? 'ativado' : 'desativado';

            return response()->json([
                'success' => true,
                'message' => "Item '{$item->name}' {$statusText} com sucesso!"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status do item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar dados de resgates em CSV
     */
    public function exportResgates(Request $request)
    {
        try {
            $query = RouletteSpin::with(['user', 'rouletteItem'])
                ->orderBy('created_at', 'desc');

            // Aplicar os mesmos filtros da página de resgates
            if ($request->filled('user_name')) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->user_name . '%');
                });
            }

            if ($request->filled('item_name')) {
                $query->where('item_name', 'like', '%' . $request->item_name . '%');
            }

            if ($request->filled('prize_type')) {
                $query->where('prize_type', $request->prize_type);
            }

            if ($request->filled('is_free_spin')) {
                $query->where('is_free_spin', $request->is_free_spin);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $spins = $query->get();

            $filename = 'resgates_roleta_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($spins) {
                $file = fopen('php://output', 'w');
                
                // Header do CSV
                fputcsv($file, [
                    'ID',
                    'Usuário',
                    'Item',
                    'Tipo de Prêmio',
                    'Valor Prêmio',
                    'Cupom',
                    'Giro Grátis',
                    'Data/Hora',
                    'IP'
                ]);

                // Dados
                foreach ($spins as $spin) {
                    fputcsv($file, [
                        $spin->id,
                        $spin->user ? $spin->user->name : 'Usuário Convidado',
                        $spin->item_name,
                        $spin->prize_type,
                        number_format($spin->prize_awarded, 2, ',', '.'),
                        $spin->coupon_code ?: '-',
                        $spin->is_free_spin ? 'Sim' : 'Não',
                        $spin->created_at->format('d/m/Y H:i:s'),
                        $spin->ip_address ?: '-'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao exportar dados: ' . $e->getMessage()
            ], 500);
        }
    }
} 