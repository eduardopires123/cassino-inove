<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VipLevel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class VipLevelController extends Controller
{
    /**
     * Exibir uma lista dos níveis VIP.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $levels = VipLevel::orderBy('order', 'asc')->get();
        return view('admin.vip_levels.index', compact('levels'));
    }

    /**
     * Mostrar o formulário para criar um novo nível VIP.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vip_levels.create');
    }

    public function reset(Request $request)
    {
        \App\Models\Settings::where('id', 1)->update(['vip_level_lastreset' => now()]);

        return redirect()->route('admin.vip-levels.index')
            ->with('success', 'Nível VIP resetado com sucesso.');
    }

    /**
     * Armazenar um nível VIP recém-criado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1',
            'min_deposit' => 'required|numeric|min:0',
            'max_deposit' => 'nullable|numeric|gt:min_deposit',
            'benefits' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp|max:2048',
            'active' => 'nullable|boolean',
            'coins_reward' => 'required|integer|min:0',
            'balance_reward' => 'nullable|numeric|min:0',
            'balance_bonus_reward' => 'nullable|numeric|min:0',
            'free_spins_reward' => 'nullable|integer|min:0'
        ]);

        // Definir valor padrão para active
        $validatedData['active'] = $request->has('active') ? true : false;

        // Definir valores padrão para as recompensas se não forem fornecidos
        $validatedData['balance_reward'] = $validatedData['balance_reward'] ?? 0;
        $validatedData['balance_bonus_reward'] = $validatedData['balance_bonus_reward'] ?? 0;
        $validatedData['free_spins_reward'] = $validatedData['free_spins_reward'] ?? 0;

        // Processar e salvar imagem, se enviada
        if ($request->hasFile('image')) {
            $validatedData['image'] = $this->uploadImage($request->file('image'));
        }

        VipLevel::create($validatedData);

        return redirect()->route('admin.vip-levels.index')
            ->with('success', 'Nível VIP criado com sucesso.');
    }

    /**
     * Mostrar o formulário para editar o nível VIP especificado.
     *
     * @param  \App\Models\VipLevel  $vipLevel
     * @return \Illuminate\Http\Response
     */
    public function edit(VipLevel $vipLevel)
    {
        return view('admin.vip_levels.edit', compact('vipLevel'));
    }

    /**
     * Atualizar o nível VIP especificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VipLevel  $vipLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VipLevel $vipLevel)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1',
            'min_deposit' => 'required|numeric|min:0',
            'max_deposit' => 'nullable|numeric|gt:min_deposit',
            'benefits' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp|max:2048',
            'active' => 'nullable|boolean',
            'coins_reward' => 'required|integer|min:0',
            'balance_reward' => 'nullable|numeric|min:0',
            'balance_bonus_reward' => 'nullable|numeric|min:0',
            'free_spins_reward' => 'nullable|integer|min:0'
        ]);

        // Definir valor padrão para active
        $validatedData['active'] = $request->has('active') ? true : false;

        // Definir valores padrão para as recompensas se não forem fornecidos
        $validatedData['balance_reward'] = $request->input('balance_reward', 0);
        $validatedData['balance_bonus_reward'] = $request->input('balance_bonus_reward', 0);
        $validatedData['free_spins_reward'] = $request->input('free_spins_reward', 0);

        // Processar e salvar imagem, se enviada
        if ($request->hasFile('image')) {
            // Se já existe uma imagem, excluir a antiga
            if ($vipLevel->image) {
                $this->deleteImage($vipLevel->image);
            }

            $validatedData['image'] = $this->uploadImage($request->file('image'));
        }

        $vipLevel->update($validatedData);

        // Verificação adicional para o campo free_spins_reward
        if ($vipLevel->free_spins_reward != $validatedData['free_spins_reward']) {
            // Forçar atualização do campo
            $vipLevel->free_spins_reward = $validatedData['free_spins_reward'];
            $vipLevel->save();
        }

        return redirect()->route('admin.vip-levels.index')
            ->with('success', 'Nível VIP atualizado com sucesso.');
    }

    /**
     * Remover o nível VIP especificado.
     *
     * @param  \App\Models\VipLevel  $vipLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(VipLevel $vipLevel)
    {
        // Remover imagem se existir
        if ($vipLevel->image && File::exists(public_path($vipLevel->image))) {
            File::delete(public_path($vipLevel->image));
        }

        $vipLevel->delete();

        return redirect()->route('admin.vip-levels.index')
            ->with('success', 'Nível VIP excluído com sucesso.');
    }

    /**
     * Alterar a ordem dos níveis VIP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request)
    {
        $levels = $request->input('levels', []);

        foreach ($levels as $id => $order) {
            VipLevel::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Fazer upload de imagem e retornar o caminho.
     *
     * @param  \Illuminate\Http\UploadedFile  $image
     * @return string
     */
    private function uploadImage($image)
    {
        // Gerar nome aleatório com 9 dígitos
        $randomName = Str::random(9);
        $extension = $image->getClientOriginalExtension();

        // Nome do arquivo final
        $fileName = $randomName . '.' . $extension;

        // Caminho absoluto da pasta public/img/ranking
        $destinationPath = public_path('img/ranking');

        // Criar a pasta se não existir
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Mover o arquivo para a pasta public/img/ranking
        $image->move($destinationPath, $fileName);

        // Retornar o caminho relativo para salvar no banco de dados
        return 'img/ranking/' . $fileName;
    }

    private function deleteImage($imagePath)
    {
        // Remover imagem antiga se existir
        if (File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }

    /**
     * Exibir os resgates de recompensas do nível VIP.
     *
     * @param  \App\Models\VipLevel  $vipLevel
     * @return \Illuminate\Http\Response
     */
    public function redemptions(VipLevel $vipLevel)
    {
        // Definir os tipos de prêmios disponíveis para o filtro
        $prizeTypes = [
            'coins' => 'Coins',
            'balance' => 'Saldo Real',
            'bonus' => 'Saldo Bônus',
            'free_spins' => 'Rodadas Grátis'
        ];

        return view('admin.vip_levels.redemptions', compact('vipLevel', 'prizeTypes'));
    }

    /**
     * Obter dados de resgates para o datatable.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VipLevel  $vipLevel
     * @return \Illuminate\Http\JsonResponse
     */
    public function redemptionsData(Request $request, VipLevel $vipLevel)
    {
        $query = \App\Models\VipReward::with(['user'])
            ->where('vip_level_id', $vipLevel->id)
            ->where('is_claimed', true);

        // Filtrar por data inicial
        if ($request->filled('start_date')) {
            $query->whereDate('claimed_at', '>=', $request->start_date);
        }

        // Filtrar por data final
        if ($request->filled('end_date')) {
            $query->whereDate('claimed_at', '<=', $request->end_date);
        }

        // Filtrar por usuário
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filtrar por tipo de prêmio
        if ($request->filled('type')) {
            $type = $request->type;
            switch ($type) {
                case 'coins':
                    $query->where('coins_rewarded', '>', 0);
                    break;
                case 'balance':
                    $query->where('balance_rewarded', '>', 0);
                    break;
                case 'bonus':
                    $query->where('balance_bonus_rewarded', '>', 0);
                    break;
                case 'free_spins':
                    $query->where('free_spins_rewarded', '>', 0);
                    break;
            }
        }

        return datatables()->of($query)
            ->addColumn('usuario', function ($reward) {
                if ($reward->user) {
                    return '<a href="javascript:void(0);" onclick="LoadAgent(\'' . $reward->user_id . '\');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário" data-original-title="Visualizar Usuário">' .
                        $reward->user->name . ' (ID: ' . $reward->user->id . ')' .
                        '</a>';
                } else {
                    return 'Usuário não encontrado';
                }
            })
            ->addColumn('tipo_premio', function ($reward) {
                $badges = [];

                if ($reward->coins_rewarded > 0) {
                    $badges[] = '<span class="badge badge-light-primary mb-2 me-1">Coins</span>';
                }

                if ($reward->balance_rewarded > 0) {
                    $badges[] = '<span class="badge badge-light-success mb-2 me-1">Saldo Real</span>';
                }

                if ($reward->balance_bonus_rewarded > 0) {
                    $badges[] = '<span class="badge badge-light-info mb-2 me-1">Saldo Bônus</span>';
                }

                if ($reward->free_spins_rewarded > 0) {
                    $badges[] = '<span class="badge badge-light-warning mb-2 me-1">Rodadas Grátis</span>';
                }

                return implode(' ', $badges);
            })
            ->addColumn('coins', function ($reward) {
                if ($reward->coins_rewarded > 0) {
                    return number_format($reward->coins_rewarded);
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('free_spins', function ($reward) {
                if ($reward->free_spins_rewarded > 0) {
                    return number_format($reward->free_spins_rewarded);
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('balance', function ($reward) {
                if ($reward->balance_rewarded > 0) {
                    return 'R$ ' . number_format($reward->balance_rewarded, 2, ',', '.');
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('balance_bonus', function ($reward) {
                if ($reward->balance_bonus_rewarded > 0) {
                    return 'R$ ' . number_format($reward->balance_bonus_rewarded, 2, ',', '.');
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('old_value', function ($reward) use ($vipLevel) {
                // Buscar log único para obter valores anteriores
                $log = \App\Models\Admin\Logs::where('user_id', $reward->user_id)
                    ->where('updated_by', $vipLevel->id)
                    ->where('type', 10) // Tipo 10 para recompensas de nível VIP (todos os tipos)
                    ->where('created_at', '>=', $reward->claimed_at->subMinutes(5))
                    ->where('created_at', '<=', $reward->claimed_at->addMinutes(5))
                    ->first();

                if ($log) {
                    return str_replace(', ', '<br>', $log->old_value);
                }

                // Fallback para buscar logs separados (compatibilidade com registros antigos)
                $logs = [];

                if ($reward->coins_rewarded > 0) {
                    $coinLog = \App\Models\Admin\Logs::where('user_id', $reward->user_id)
                        ->where('updated_by', $vipLevel->id)
                        ->where('type', 6) // Tipo 6 para Coins VIP
                        ->where('created_at', '>=', $reward->claimed_at->subMinutes(5))
                        ->where('created_at', '<=', $reward->claimed_at->addMinutes(5))
                        ->first();

                    if ($coinLog) {
                        $logs[] = 'Coins: ' . number_format($coinLog->old_value);
                    }
                }

                if ($reward->balance_rewarded > 0) {
                    $balanceLog = \App\Models\Admin\Logs::where('user_id', $reward->user_id)
                        ->where('updated_by', $vipLevel->id)
                        ->where('type', 7) // Tipo 7 para Saldo Real VIP
                        ->where('created_at', '>=', $reward->claimed_at->subMinutes(5))
                        ->where('created_at', '<=', $reward->claimed_at->addMinutes(5))
                        ->first();

                    if ($balanceLog) {
                        $logs[] = 'Saldo: R$ ' . number_format($balanceLog->old_value, 2, ',', '.');
                    }
                }

                if ($reward->balance_bonus_rewarded > 0) {
                    $bonusLog = \App\Models\Admin\Logs::where('user_id', $reward->user_id)
                        ->where('updated_by', $vipLevel->id)
                        ->where('type', 8) // Tipo 8 para Bônus VIP
                        ->where('created_at', '>=', $reward->claimed_at->subMinutes(5))
                        ->where('created_at', '<=', $reward->claimed_at->addMinutes(5))
                        ->first();

                    if ($bonusLog) {
                        $logs[] = 'Bônus: R$ ' . number_format($bonusLog->old_value, 2, ',', '.');
                    }
                }

                if ($reward->free_spins_rewarded > 0) {
                    $spinsLog = \App\Models\Admin\Logs::where('user_id', $reward->user_id)
                        ->where('updated_by', $vipLevel->id)
                        ->where('type', 9) // Tipo 9 para Rodadas Grátis VIP
                        ->where('created_at', '>=', $reward->claimed_at->subMinutes(5))
                        ->where('created_at', '<=', $reward->claimed_at->addMinutes(5))
                        ->first();

                    if ($spinsLog) {
                        $logs[] = 'Rodadas: ' . number_format($spinsLog->old_value);
                    }
                }

                return !empty($logs) ? implode('<br>', $logs) : 'N/A';
            })
            ->addColumn('new_value', function ($reward) use ($vipLevel) {
                // Buscar log único para obter valores novos
                $log = \App\Models\Admin\Logs::where('user_id', $reward->user_id)
                    ->where('updated_by', $vipLevel->id)
                    ->where('type', 10) // Tipo 10 para recompensas de nível VIP (todos os tipos)
                    ->where('created_at', '>=', $reward->claimed_at->subMinutes(5))
                    ->where('created_at', '<=', $reward->claimed_at->addMinutes(5))
                    ->first();

                if ($log) {
                    return str_replace(', ', '<br>', $log->new_value);
                }

                // Fallback para buscar logs separados (compatibilidade com registros antigos)
                $logs = [];

                if ($reward->coins_rewarded > 0) {
                    $coinLog = \App\Models\Admin\Logs::where('user_id', $reward->user_id)
                        ->where('updated_by', $vipLevel->id)
                        ->where('type', 6) // Tipo 6 para Coins VIP
                        ->where('created_at', '>=', $reward->claimed_at->subMinutes(5))
                        ->where('created_at', '<=', $reward->claimed_at->addMinutes(5))
                        ->first();

                    if ($coinLog) {
                        $logs[] = 'Coins: ' . number_format($coinLog->new_value);
                    }
                }

                if ($reward->balance_rewarded > 0) {
                    $balanceLog = \App\Models\Admin\Logs::where('user_id', $reward->user_id)
                        ->where('updated_by', $vipLevel->id)
                        ->where('type', 7) // Tipo 7 para Saldo Real VIP
                        ->where('created_at', '>=', $reward->claimed_at->subMinutes(5))
                        ->where('created_at', '<=', $reward->claimed_at->addMinutes(5))
                        ->first();

                    if ($balanceLog) {
                        $logs[] = 'Saldo: R$ ' . number_format($balanceLog->new_value, 2, ',', '.');
                    }
                }

                if ($reward->balance_bonus_rewarded > 0) {
                    $bonusLog = \App\Models\Admin\Logs::where('user_id', $reward->user_id)
                        ->where('updated_by', $vipLevel->id)
                        ->where('type', 8) // Tipo 8 para Bônus VIP
                        ->where('created_at', '>=', $reward->claimed_at->subMinutes(5))
                        ->where('created_at', '<=', $reward->claimed_at->addMinutes(5))
                        ->first();

                    if ($bonusLog) {
                        $logs[] = 'Bônus: R$ ' . number_format($bonusLog->new_value, 2, ',', '.');
                    }
                }

                if ($reward->free_spins_rewarded > 0) {
                    $spinsLog = \App\Models\Admin\Logs::where('user_id', $reward->user_id)
                        ->where('updated_by', $vipLevel->id)
                        ->where('type', 9) // Tipo 9 para Rodadas Grátis VIP
                        ->where('created_at', '>=', $reward->claimed_at->subMinutes(5))
                        ->where('created_at', '<=', $reward->claimed_at->addMinutes(5))
                        ->first();

                    if ($spinsLog) {
                        $logs[] = 'Rodadas: ' . number_format($spinsLog->new_value);
                    }
                }

                return !empty($logs) ? implode('<br>', $logs) : 'N/A';
            })
            ->addColumn('data', function ($reward) {
                return $reward->claimed_at ? $reward->claimed_at->format('d/m/Y H:i:s') : 'N/A';
            })
            ->rawColumns(['old_value', 'new_value', 'coins', 'free_spins', 'balance', 'balance_bonus', 'tipo_premio', 'usuario'])
            ->toJson();
    }
}
