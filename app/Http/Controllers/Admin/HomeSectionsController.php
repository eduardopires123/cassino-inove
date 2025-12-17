<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSectionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeSectionsController extends Controller
{
    /**
     * Mostrar a página de configuração da ordem das seções
     */
    public function index()
    {
        // Excluir pwa_install_button da lista (é flutuante e não precisa estar na ordenação)
        $sections = HomeSectionOrder::where('section_key', '!=', 'pwa_install_button')
            ->orderBy('position')
            ->get();
        
        return view('admin.personalizacao.sections-order', compact('sections'));
    }

    /**
     * Atualizar a ordem das seções
     */
    public function updateOrder(Request $request)
    {
        try {
            $sectionsOrder = $request->input('sections', []);
            
            // Filtrar pwa_install_button (é flutuante e não precisa estar na ordenação)
            $sectionsOrder = array_filter($sectionsOrder, function($sectionKey) {
                return $sectionKey !== 'pwa_install_button';
            });
            
            DB::beginTransaction();
            
            foreach ($sectionsOrder as $index => $sectionKey) {
                HomeSectionOrder::where('section_key', $sectionKey)
                    ->update(['position' => $index + 1]);
            }
            
            DB::commit();
            
            // Limpar cache
            HomeSectionOrder::clearCache();
            
            return response()->json([
                'success' => true,
                'message' => 'Ordem das seções atualizada com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar ordem das seções: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ativar/Desativar seção
     */
    public function toggleSection(Request $request)
    {
        try {
            $sectionKey = $request->input('section_key');
            $isActive = $request->input('is_active');
            
            HomeSectionOrder::where('section_key', $sectionKey)
                ->update(['is_active' => $isActive]);
            
            // Limpar cache
            HomeSectionOrder::clearCache();
            
            return response()->json([
                'success' => true,
                'message' => 'Status da seção atualizado com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar status da seção: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Resetar para ordem padrão
     */
    public function resetOrder()
    {
        try {
            DB::beginTransaction();
            
            // Definir ordem padrão
            $defaultOrder = [
                'search_bar' => 1,
                'promo_banners' => 2,
                'menu_icons' => 3,
                'top_wins' => 4,
                'mini_banners' => 5,
                'modo_surpresa' => 6,
                'sports_icons' => 7,
                'providers_games' => 8,
                'live_casino' => 9,
                'top_matches' => 10,
                'new_games' => 11,
                'most_viewed_games' => 12,
                'raspadinhas' => 13,
                'providers_list' => 14,
                'recent_bets' => 15,
                'floating_roulette' => 16,
                'floating_whatsapp' => 17,
            ];
            
            foreach ($defaultOrder as $sectionKey => $position) {
                HomeSectionOrder::where('section_key', $sectionKey)
                    ->update([
                        'position' => $position,
                        'is_active' => 1
                    ]);
            }
            
            DB::commit();
            
            // Limpar cache
            HomeSectionOrder::clearCache();
            
            return response()->json([
                'success' => true,
                'message' => 'Ordem das seções resetada para o padrão!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao resetar ordem das seções: ' . $e->getMessage()
            ], 500);
        }
    }
} 