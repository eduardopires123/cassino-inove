<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LuckyBox;
use App\Models\LuckyBoxPurchase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\LuckyBoxPrize;
use App\Models\LuckyBoxHistory;
use App\Models\User;
use App\Models\LuckyBoxPrizeOption;
use App\Services\ImageService;
use Yajra\DataTables\Facades\DataTables;

class LuckyBoxController extends Controller
{
    protected $imageService;
    
    /**
     * Construtor da classe
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of lucky boxes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $boxes = LuckyBox::with('prizeOptions')->orderBy('order', 'asc')->get();
        return view('admin.lucky-boxes.index', compact('boxes'));
    }

    /**
     * Show the form for creating a new lucky box.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.lucky-boxes.create');
    }

    /**
     * Store a newly created lucky box in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        \Log::info('Dados recebidos:', $request->all());

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'level' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'order' => 'nullable|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_mysterious' => 'boolean',
                'daily_limit' => 'integer|min:0',
                'is_active' => 'boolean',
                'prizes' => 'required|array'
            ]);

            // Check if there's an active box with the same level
            $existingActiveBox = LuckyBox::where('level', $request->level)->whereNull('deleted_at')->first();
            if ($existingActiveBox) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['level' => 'Já existe uma caixa ativa com este nível. Escolha outro nível ou delete a caixa existente primeiro.']);
            }

            DB::beginTransaction();

            // Criar a caixa da sorte
            $luckyBox = LuckyBox::create([
                'name' => $request->name,
                'description' => $request->description,
                'level' => $request->level,
                'price' => $request->price,
                'order' => $request->order ?? 0,
                'is_mysterious' => $request->boolean('is_mysterious'),
                'daily_limit' => $request->daily_limit ?? 0,
                'is_active' => $request->boolean('is_active', true),
                'max_prize' => 0 // Valor padrão inicial
            ]);

            // Processar imagem se fornecida
            if ($request->hasFile('image')) {
                // Definir o caminho de destino
                $basePath = 'images/lucky-boxes/box-' . $luckyBox->level;
                
                // Usar o ImageService para converter para AVIF e salvar
                $imagePath = $this->imageService->saveOptimizedImage(
                    $request->file('image'),
                    $basePath,
                    85, // qualidade
                    null, // largura máxima (null para manter o tamanho original)
                    null // altura (mantém proporção)
                );
                
                $luckyBox->image = $imagePath;
                $luckyBox->save();
            }

            // Processar as opções de prêmios
            $hasActivePrize = false;
            $maxPrizeValue = 0;
            foreach ($request->prizes as $type => $config) {
                if (!empty($config['active'])) {
                    $hasActivePrize = true;
                    
                    // Criar a opção de prêmio
                    $prizeData = [
                        'lucky_box_id' => $luckyBox->id,
                        'prize_type' => $type,
                        'chance_percentage' => $config['chance'],
                        'is_active' => true
                    ];

                    // Adicionar campos específicos baseado no tipo de prêmio
                    if ($type === 'free_spins') {
                        $prizeData['min_spins'] = $config['min_spins'];
                        $prizeData['max_spins'] = $config['max_spins'];
                        
                        // Atualizar max_prize se for maior que o atual
                        if (intval($config['max_spins']) > $maxPrizeValue) {
                            $maxPrizeValue = intval($config['max_spins']);
                        }
                    } else {
                        $prizeData['min_amount'] = $config['min_amount'];
                        $prizeData['max_amount'] = $config['max_amount'];
                        
                        // Atualizar max_prize se for maior que o atual
                        if (floatval($config['max_amount']) > $maxPrizeValue) {
                            $maxPrizeValue = floatval($config['max_amount']);
                        }
                    }

                    LuckyBoxPrizeOption::create($prizeData);
                }
            }

            // Atualizar o valor máximo de prêmio
            $luckyBox->max_prize = $maxPrizeValue;
            $luckyBox->save();

            if (!$hasActivePrize) {
                throw new \Exception('Pelo menos uma opção de prêmio deve estar ativa.');
            }

            DB::commit();
            return redirect()->route('admin.lucky-boxes.index')->with('success', 'Caixa da sorte criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao criar caixa da sorte: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar caixa da sorte: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified lucky box.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $box = LuckyBox::findOrFail($id);
        
        // Buscar as opções de prêmios e organizá-las para a view
        $prizeOptions = LuckyBoxPrizeOption::where('lucky_box_id', $box->id)->get();
        
        // Inicializar o objeto prizes
        $prizes = [];
        
        // Organizar as opções por tipo
        foreach ($prizeOptions as $option) {
            $prizes[$option->prize_type] = [
                'active' => $option->is_active,
                'chance' => $option->chance_percentage
            ];
            
            // Adicionar campos específicos com base no tipo de prêmio
            if ($option->prize_type === 'free_spins') {
                $prizes[$option->prize_type]['min_spins'] = $option->min_spins;
                $prizes[$option->prize_type]['max_spins'] = $option->max_spins;
            } else {
                $prizes[$option->prize_type]['min_amount'] = $option->min_amount;
                $prizes[$option->prize_type]['max_amount'] = $option->max_amount;
            }
        }
        
        // Adicionar o objeto prizes ao objeto box
        $box->prizes = $prizes;
        
        return view('admin.lucky-boxes.edit', compact('box'));
    }

    /**
     * Update the specified lucky box in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $box = LuckyBox::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'level' => 'required|integer|unique:lucky_boxes,level,' . $id . ',id,deleted_at,NULL',
                'description' => 'required|string',
                'price' => 'required|integer|min:1',
                'is_mysterious' => 'boolean',
                'daily_limit' => 'integer|min:0',
                'image' => 'nullable|image|max:2048',
                'prizes' => 'required|array',
                'max_prize' => 'nullable|numeric'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $request->all();
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it's not the default one
                if ($box->image && !str_contains($box->image, 'luckbox' . $box->level . '.png')) {
                    $oldPath = public_path($box->image);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                // Definir o caminho de destino
                $basePath = 'images/lucky-boxes/box-' . $request->level;
                
                // Usar o ImageService para converter para AVIF e salvar
                $imagePath = $this->imageService->saveOptimizedImage(
                    $request->file('image'),
                    $basePath,
                    85, // qualidade
                    null, // largura máxima (null para manter o tamanho original)
                    null // altura (mantém proporção)
                );
                
                $data['image'] = $imagePath;
            }
            
            // Set boolean values
            $data['is_active'] = $request->has('is_active');
            $data['is_mysterious'] = $request->has('is_mysterious');
            
            $box->update($data);
            
            // Processar as opções de prêmios
            if (isset($request->prizes) && is_array($request->prizes)) {
                $hasActivePrize = false;
                $maxPrizeValue = 0;
                
                // Remover as opções de prêmios existentes
                LuckyBoxPrizeOption::where('lucky_box_id', $box->id)->delete();
                
                foreach ($request->prizes as $type => $config) {
                    if (!empty($config['active'])) {
                        $hasActivePrize = true;
                        
                        // Criar a opção de prêmio
                        $prizeData = [
                            'lucky_box_id' => $box->id,
                            'prize_type' => $type,
                            'chance_percentage' => $config['chance'],
                            'is_active' => true
                        ];

                        // Adicionar campos específicos baseado no tipo de prêmio
                        if ($type === 'free_spins') {
                            $prizeData['min_spins'] = $config['min_spins'];
                            $prizeData['max_spins'] = $config['max_spins'];
                            
                            // Atualizar max_prize se for maior que o atual
                            if (intval($config['max_spins']) > $maxPrizeValue) {
                                $maxPrizeValue = intval($config['max_spins']);
                            }
                        } else {
                            $prizeData['min_amount'] = $config['min_amount'];
                            $prizeData['max_amount'] = $config['max_amount'];
                            
                            // Atualizar max_prize se for maior que o atual
                            if (floatval($config['max_amount']) > $maxPrizeValue) {
                                $maxPrizeValue = floatval($config['max_amount']);
                            }
                        }

                        LuckyBoxPrizeOption::create($prizeData);
                    }
                }
                
                // Atualizar o valor máximo de prêmio
                $box->max_prize = $maxPrizeValue;
                $box->save();

                if (!$hasActivePrize) {
                    throw new \Exception('Pelo menos uma opção de prêmio deve estar ativa.');
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.lucky-boxes.index')
                ->with('success', 'Caixa da Sorte atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Erro ao atualizar a caixa: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified lucky box from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $box = LuckyBox::findOrFail($id);
            
            // Desativar a caixa antes de excluir
            $box->is_active = false;
            $box->save();
            
            // Delete the image if it's not a default one
            if ($box->image && !str_contains($box->image, 'luckbox' . $box->level . '.png')) {
                $path = str_replace('/img/box/', '', $box->image);
                if (file_exists(public_path('img/box/' . $path))) {
                    unlink(public_path('img/box/' . $path));
                }
            }
            
            // Soft delete da caixa
            $box->delete();
            
            DB::commit();
            
            return redirect()->route('admin.lucky-boxes.index')
                ->with('success', 'Caixa excluída com sucesso! O histórico foi mantido.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.lucky-boxes.index')
                ->with('error', 'Erro ao excluir a caixa: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle the active status of a lucky box.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleActive($id)
    {
        $box = LuckyBox::findOrFail($id);
        $box->is_active = !$box->is_active;
        $box->save();
        
        return redirect()->route('admin.lucky-boxes.index')
            ->with('success', 'Lucky Box status updated successfully');
    }
    
    /**
     * Update the order of lucky boxes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrder(Request $request)
    {
        $boxes = $request->input('boxes', []);
        
        foreach ($boxes as $box) {
            $boxModel = LuckyBox::find($box['id']);
            if ($boxModel) {
                $boxModel->order = $box['order'];
                $boxModel->save();
            }
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Display redemptions of a specific lucky box.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function redemptions(Request $request, $id)
    {
        $box = LuckyBox::findOrFail($id);
        
        // Preparar os tipos de prêmios para o filtro
        $prizeTypes = [
            3 => 'Rodadas Grátis',
            4 => 'Saldo Real',
            5 => 'Bônus'
        ];
        
        // Tipos de prêmios para exibição na tabela
        $typeName = [
            3 => 'Rodadas Grátis',
            4 => 'Saldo Real',
            5 => 'Bônus'
        ];
        
        return view('admin.lucky-boxes.redemptions', compact('box', 'prizeTypes', 'typeName'));
    }
    
    /**
     * Fornece dados para a tabela de resgates das caixas da sorte
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function redemptionsData(Request $request, $id)
    {
        $box = LuckyBox::findOrFail($id);
        
        // Buscar logs relacionados a esta caixa da sorte
        $query = \App\Models\Admin\Logs::where('updated_by', $id)
            ->whereIn('type', [3, 4, 5]);
            
        // Aplicar filtros
        if ($request->input('type')) {
            $query->where('type', $request->input('type'));
        }
        
        if ($request->input('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        
        if ($request->input('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }
        
        if ($request->input('search')) {
            // Buscar usuários pelo nome
            $userIds = \App\Models\User::where('name', 'like', '%' . $request->input('search') . '%')
                ->pluck('id')
                ->toArray();
                
            // Filtrar pelos IDs de usuários encontrados
            if (!empty($userIds)) {
                $query->whereIn('user_id', $userIds);
            } else {
                $query->where('user_id', 0); // Força não encontrar resultados
            }
        }
        
        // Obter usuários relacionados
        $userIds = $query->pluck('user_id')->unique()->toArray();
        $users = \App\Models\User::whereIn('id', $userIds)->get()->keyBy('id');
        
        // Tipos de prêmios para exibição na tabela
        $typeName = [
            3 => 'Rodadas Grátis',
            4 => 'Saldo Real',
            5 => 'Bônus'
        ];
        
        // Configurar labels de tipo para exibição na tabela
        $typeLabels = [
            3 => '<span class="badge badge-light-success">Rodadas Grátis LuckBox</span>',
            4 => '<span class="badge badge-light-primary">Saldo Real LuckBox</span>',
            5 => '<span class="badge badge-light-warning">Bônus LuckBox</span>'
        ];
        
        return DataTables::of($query)
            ->addColumn('usuario', function ($log) use ($users) {
                if (isset($users[$log->user_id])) {
                    return '<a href="javascript:void(0);" onclick="LoadAgent(\'' . $log->user_id . '\');" data-bs-toggle="modal" data-bs-target="#tabsModal" class="bs-tooltip" title="Visualizar Usuário" data-original-title="Visualizar Usuário">' .
                        $users[$log->user_id]->name .
                        '</a>';
                } else {
                    return 'Usuário #' . $log->user_id;
                }
            })
            ->addColumn('tipo_premio', function ($log) use ($typeLabels) {
                return $typeLabels[$log->type] ?? '';
            })
            ->addColumn('valor_premio', function ($log) {
                if ($log->type == 3) {
                    return (int)($log->new_value - $log->old_value);
                } else {
                    return number_format($log->new_value - $log->old_value, 2, ',', '.');
                }
            })
            ->addColumn('data', function ($log) {
                return \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s');
            })
            ->rawColumns(['usuario', 'tipo_premio'])
            ->make(true);
    }

    public function processReward(User $user, LuckyBoxPrize $prize)
    {
        try {
            DB::beginTransaction();

            switch ($prize->prize_type) {
                case 'real_balance':
                    $user->balance += $prize->amount;
                    $user->save();
                    break;

                case 'bonus':
                    $user->bonus_balance += $prize->amount;
                    $user->save();
                    break;

                case 'free_spins':
                    // Aqui você implementa a lógica para adicionar rodadas grátis
                    $user->free_spins += $prize->spins_amount;
                    $user->save();
                    break;

                case 'coins':
                    $user->coins += $prize->amount;
                    $user->save();
                    break;
            }

            // Registrar o histórico do prêmio
            LuckyBoxHistory::create([
                'user_id' => $user->id,
                'lucky_box_id' => $prize->lucky_box_id,
                'prize_type' => $prize->prize_type,
                'amount' => $prize->amount,
                'spins_amount' => $prize->spins_amount
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao processar prêmio: ' . $e->getMessage());
            return false;
        }
    }

    private function generatePrize(LuckyBox $box)
    {
        try {
            // Buscar todas as opções de prêmios ativas para esta caixa
            $prizeOptions = LuckyBoxPrizeOption::where('lucky_box_id', $box->id)
                ->where('is_active', true)
                ->get();

            // Se não houver opções, criar um prêmio padrão
            if ($prizeOptions->isEmpty()) {
                \Log::warning('Caixa sem opções de prêmio: ' . $box->id);
                
                return [
                    'type' => 'coins',
                    'amount' => 10,
                    'spins_amount' => 0
                ];
            }

            // Se houver apenas 1 opção ativa, usar ela diretamente
            if ($prizeOptions->count() === 1) {
                \Log::info('Apenas uma opção de prêmio disponível, usando-a diretamente');
                return $this->generatePrizeValue($prizeOptions->first());
            }

            // Calcular o total de chances
            $totalChance = $prizeOptions->sum('chance_percentage');
            
            // Verificar se o total de chances é válido
            if ($totalChance <= 0) {
                \Log::warning('Configuração de chances inválida para caixa: ' . $box->id);
                
                // Selecionar uma opção aleatoriamente
                $randomIndex = mt_rand(0, $prizeOptions->count() - 1);
                return $this->generatePrizeValue($prizeOptions[$randomIndex]);
            }
            
            // Normalizar as chances se o total não for 100%
            $scaleFactor = 100 / $totalChance;
            
            // Gerar um número aleatório entre 0 e 100
            $random = mt_rand(0, 10000) / 100; // Para melhor precisão
            
            // Encontrar qual prêmio foi sorteado
            $currentSum = 0;
            foreach ($prizeOptions as $option) {
                // Usar a chance normalizada
                $normalizedChance = $option->chance_percentage * $scaleFactor;
                $currentSum += $normalizedChance;
                
                if ($random <= $currentSum) {
                    // Encontramos o prêmio!
                    $prize = $this->generatePrizeValue($option);
                    \Log::info('Prêmio sorteado com sucesso: ' . $option->prize_type);
                    return $prize;
                }
            }

            // Fallback para o último prêmio
            \Log::warning('Usando último prêmio como fallback para caixa: ' . $box->id);
            return $this->generatePrizeValue($prizeOptions->last());
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar prêmio: ' . $e->getMessage(), ['exception' => $e]);
            
            // Retornar um prêmio padrão em caso de erro
            return [
                'type' => 'coins',
                'amount' => 5,
                'spins_amount' => 0
            ];
        }
    }

    private function generatePrizeValue($prizeOption)
    {
        try {
            // Verificar se a opção de prêmio é válida
            if (!$prizeOption || !$prizeOption->prize_type) {
                \Log::warning('Opção de prêmio inválida');
                return [
                    'type' => 'coins',
                    'amount' => 5,
                    'spins_amount' => 0
                ];
            }
            
            // Log para debug
            \Log::info('Gerando prêmio com opção: ', [
                'id' => $prizeOption->id, 
                'type' => $prizeOption->prize_type,
                'min_amount' => $prizeOption->min_amount,
                'max_amount' => $prizeOption->max_amount,
                'min_spins' => $prizeOption->min_spins,
                'max_spins' => $prizeOption->max_spins
            ]);
            
            if ($prizeOption->prize_type === 'free_spins') {
                // Garantir que os valores min e max são válidos
                $minSpins = intval($prizeOption->min_spins);
                $maxSpins = intval($prizeOption->max_spins);
                
                // Garantir que min e max sejam pelo menos 1 e que max seja maior ou igual a min
                $minSpins = max(1, $minSpins);
                $maxSpins = max($minSpins, $maxSpins);
                
                // Gerar um valor aleatório entre min e max
                $value = mt_rand($minSpins, $maxSpins);
                
                \Log::info('Prêmio gerado (rodadas): ' . $value);
                
                return [
                    'type' => 'free_spins',
                    'spins_amount' => $value,
                    'amount' => 0
                ];
            } else {
                // Para saldo real, bônus e coins
                // Garantir que os valores min e max são válidos
                $minAmount = floatval($prizeOption->min_amount);
                $maxAmount = floatval($prizeOption->max_amount);
                
                // Garantir que min e max sejam pelo menos positivos e que max seja maior ou igual a min
                $minAmount = max(0, $minAmount);
                $maxAmount = max($minAmount, $maxAmount);
                
                // Se forem iguais, retornar o valor exato
                if ($minAmount === $maxAmount) {
                    $value = $minAmount;
                } else {
                    // Multiplicar por 100 para trabalhar com inteiros (evitar problemas de precisão)
                    $min = intval($minAmount * 100);
                    $max = intval($maxAmount * 100);
                    $value = mt_rand($min, $max) / 100; // Dividir por 100 para voltar ao valor decimal
                }
                
                \Log::info('Prêmio gerado (' . $prizeOption->prize_type . '): ' . $value);
                
                return [
                    'type' => $prizeOption->prize_type,
                    'amount' => $value,
                    'spins_amount' => 0
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar valor do prêmio: ' . $e->getMessage(), ['exception' => $e]);
            
            // Valor de fallback em caso de erro
            if ($prizeOption && $prizeOption->prize_type) {
                if ($prizeOption->prize_type === 'free_spins') {
                    return [
                        'type' => 'free_spins',
                        'amount' => 0,
                        'spins_amount' => 1
                    ];
                } else {
                    return [
                        'type' => $prizeOption->prize_type,
                        'amount' => 0,
                        'spins_amount' => 0
                    ];
                }
            } else {
                return [
                    'type' => 'coins',
                    'amount' => 1,
                    'spins_amount' => 0
                ];
            }
        }
    }

    public function open(Request $request)
    {
        try {
            DB::beginTransaction();

            \Log::info('Requisição de abertura de caixa recebida', $request->all());

            // Validar os dados de entrada
            $validator = Validator::make($request->all(), [
                'level' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                throw new \Exception('Caixa inválida ou não encontrada.');
            }

            $box = LuckyBox::where('level', $request->level)
                           ->where('is_active', true)
                           ->first();
                           
            if (!$box) {
                throw new \Exception('Caixa não encontrada ou inativa.');
            }
                           
            $user = auth()->user();

            if (!$user) {
                throw new \Exception('Usuário não autenticado.');
            }

            \Log::info('Usuário tentando abrir caixa', [
                'user_id' => $user->id,
                'box_id' => $box->id,
                'coins' => $user->coins,
                'price' => $box->price
            ]);

            // Verificar se o usuário tem coins suficientes
            if ($user->coins < $box->price) {
                throw new \Exception('Saldo insuficiente de coins.');
            }

            // Verificar limite diário se for caixa misteriosa
            if ($box->is_mysterious && $box->daily_limit > 0) {
                $todayOpenings = LuckyBoxHistory::where('user_id', $user->id)
                    ->where('lucky_box_id', $box->id)
                    ->whereDate('created_at', now())
                    ->count();

                if ($todayOpenings >= $box->daily_limit) {
                    throw new \Exception('Limite diário de aberturas atingido para esta caixa.');
                }
            }

            // Deduzir os coins
            $user->coins -= $box->price;
            $user->save();

            // Buscar diretamente uma opção de free_spins ativa
            $freeSpinsOption = LuckyBoxPrizeOption::where('lucky_box_id', $box->id)
                                               ->where('prize_type', 'free_spins')
                                               ->where('is_active', true)
                                               ->first();
            
            // Se existir uma opção de free_spins, vamos forçar essa premiação
            if ($freeSpinsOption) {
                \Log::info('Opção de free_spins encontrada, gerando premiação', [
                    'option_id' => $freeSpinsOption->id,
                    'min_spins' => $freeSpinsOption->min_spins,
                    'max_spins' => $freeSpinsOption->max_spins
                ]);
                
                // Garantir valores válidos para min e max spins
                $minSpins = intval($freeSpinsOption->min_spins);
                $maxSpins = intval($freeSpinsOption->max_spins);
                
                // Garantir que min <= max e ambos são positivos
                $minSpins = max(1, $minSpins);
                $maxSpins = max($minSpins, $maxSpins);
                
                // Gerar um valor aleatório entre min e max
                $spinsAmount = mt_rand($minSpins, $maxSpins);
                
                $prize = [
                    'type' => 'free_spins',
                    'amount' => 0,
                    'spins_amount' => $spinsAmount
                ];
                
                \Log::info('Prêmio de rodadas grátis gerado: ' . $spinsAmount);
            } else {
                // Fallback para outro tipo de prêmio se não houver free_spins
                \Log::warning('Nenhuma opção de free_spins encontrada, usando generatePrize');
                $prize = $this->generatePrize($box);
            }
            
            // Log detalhado do prêmio gerado
            \Log::info('Prêmio gerado para o usuário ' . $user->id, [
                'prize' => $prize,
                'box_id' => $box->id,
                'box_name' => $box->name
            ]);
            
            // Validar o prêmio gerado e aplicar correções se necessário
            if (!isset($prize['type']) || empty($prize['type'])) {
                \Log::warning('Tipo de prêmio inválido, usando fallback para free_spins');
                $prize['type'] = 'free_spins';
                $prize['amount'] = 0;
                $prize['spins_amount'] = 1;
            }
            
            // Garantir que os valores sejam definidos e não sejam zero ou negativos
            switch ($prize['type']) {
                case 'free_spins':
                    $prize['spins_amount'] = max(1, intval($prize['spins_amount'] ?? 0));
                    $prize['amount'] = 0;
                    break;
                case 'real_balance':
                case 'bonus':
                case 'coins':
                    $prize['amount'] = floatval($prize['amount'] ?? 0);
                    $prize['spins_amount'] = 0;
                    break;
                default:
                    // Caso o tipo seja desconhecido, mudar para free_spins
                    \Log::warning('Tipo de prêmio desconhecido: ' . $prize['type'] . ', convertendo para free_spins');
                    $prize['type'] = 'free_spins';
                    $prize['amount'] = 0;
                    $prize['spins_amount'] = 1;
                    break;
            }

            // Verificar se é um prêmio de valor baixo para ajustar o título
            $isLowPrize = false;
            if ($prize['type'] === 'real_balance' || $prize['type'] === 'bonus') {
                $isLowPrize = $prize['amount'] < 1;
            } elseif ($prize['type'] === 'free_spins') {
                $isLowPrize = $prize['spins_amount'] < 10;
            } elseif ($prize['type'] === 'coins') {
                $isLowPrize = $prize['amount'] < 5;
            }

            // Aplicar o prêmio ao usuário
            switch ($prize['type']) {
                case 'real_balance':
                    $oldValue = $user->balance;
                    $user->balance += $prize['amount'];
                    
                    // Registrar no log
                    \App\Models\Admin\Logs::create([
                        'field_name' => 'Saldo Real LuckBox',
                        'old_value' => $oldValue,
                        'new_value' => $user->balance,
                        'updated_by' => $box->id, // ID da caixa
                        'user_id' => $user->id,
                        'type' => 4, // Tipo 4 para Saldo Real
                        'log' => 'Usuário recebeu ' . $prize['amount'] . ' de saldo real da caixa ' . $box->name
                    ]);
                    break;
                case 'bonus':
                    $oldValue = $user->bonus_balance;
                    $user->bonus_balance += $prize['amount'];
                    
                    // Registrar no log
                    \App\Models\Admin\Logs::create([
                        'field_name' => 'Bonus LuckBox',
                        'old_value' => $oldValue,
                        'new_value' => $user->bonus_balance,
                        'updated_by' => $box->id, // ID da caixa
                        'user_id' => $user->id,
                        'type' => 5, // Tipo 5 para Bônus
                        'log' => 'Usuário recebeu ' . $prize['amount'] . ' de bônus da caixa ' . $box->name
                    ]);
                    break;
                case 'free_spins':
                    $oldValue = $user->free_spins;
                    $user->free_spins += $prize['spins_amount'];
                    
                    // Registrar no log
                    \App\Models\Admin\Logs::create([
                        'field_name' => 'Adicionar Rodadas Gratis LuckBox',
                        'old_value' => $oldValue,
                        'new_value' => $user->free_spins,
                        'updated_by' => $box->id, // ID da caixa
                        'user_id' => $user->id,
                        'type' => 3, // Tipo 3 para Rodadas Grátis
                        'log' => 'Usuário recebeu ' . $prize['spins_amount'] . ' rodadas grátis da caixa ' . $box->name
                    ]);
                    break;
                case 'coins':
                    $oldValue = $user->coins;
                    $user->coins += $prize['amount'];
                    
                    // Neste caso não registramos log pois não foi solicitado
                    break;
                default:
                    // Fallback para free_spins se o tipo for desconhecido (nunca deveria chegar aqui)
                    $oldValue = $user->free_spins;
                    $prize['type'] = 'free_spins';
                    $prize['amount'] = 0;
                    $prize['spins_amount'] = 1;
                    $user->free_spins += $prize['spins_amount'];
                    
                    // Registrar no log
                    \App\Models\Admin\Logs::create([
                        'field_name' => 'Adicionar Rodadas Gratis LuckBox',
                        'old_value' => $oldValue,
                        'new_value' => $user->free_spins,
                        'updated_by' => $box->id, // ID da caixa
                        'user_id' => $user->id,
                        'type' => 3, // Tipo 3 para Rodadas Grátis
                        'log' => 'Usuário recebeu ' . $prize['spins_amount'] . ' rodadas grátis da caixa ' . $box->name
                    ]);
                    
                    \Log::warning('Tipo de prêmio desconhecido, usando free_spins como fallback');
                    break;
            }
            $user->save();

            // Registrar no histórico
            LuckyBoxHistory::create([
                'user_id' => $user->id,
                'lucky_box_id' => $box->id,
                'prize_type' => $prize['type'],
                'amount' => $prize['amount'],
                'spins_amount' => $prize['spins_amount']
            ]);

            DB::commit();
            
            // Definir mensagem personalizada com base no tipo de prêmio
            $message = '';
            if ($prize['type'] === 'free_spins') {
                if ($isLowPrize) {
                    $message = "Você ganhou {$prize['spins_amount']} rodadas grátis. Aproveite e tente novamente!";
                } else {
                    $message = "Parabéns! Você ganhou {$prize['spins_amount']} rodadas grátis!";
                }
            } elseif ($prize['type'] === 'real_balance') {
                if ($isLowPrize) {
                    $message = "Você ganhou R$ " . number_format($prize['amount'], 2, ',', '.') . " em saldo real. Tente novamente!";
                } else {
                    $message = "Parabéns! Você ganhou R$ " . number_format($prize['amount'], 2, ',', '.') . " em saldo real!";
                }
            } elseif ($prize['type'] === 'bonus') {
                if ($isLowPrize) {
                    $message = "Você ganhou R$ " . number_format($prize['amount'], 2, ',', '.') . " em bônus. Tente novamente!";
                } else {
                    $message = "Parabéns! Você ganhou R$ " . number_format($prize['amount'], 2, ',', '.') . " em bônus!";
                }
            } else { // coins
                if ($isLowPrize) {
                    $message = "Você ganhou {$prize['amount']} coins. Tente novamente!";
                } else {
                    $message = "Parabéns! Você ganhou {$prize['amount']} coins!";
                }
            }
            
            // Construir a resposta com todos os dados necessários
            $response = [
                'success' => true,
                'title' => $isLowPrize ? 'Que pena!' : 'Parabéns!',
                'prize_type' => $prize['type'],
                'amount' => $prize['amount'],
                'spins_amount' => $prize['spins_amount'],
                'user_balance' => $user->balance,
                'user_bonus' => $user->bonus_balance,
                'user_free_spins' => $user->free_spins,
                'user_coins' => $user->coins,
                'box_name' => $box->name,
                'message' => $message
            ];
            
            \Log::info('Resposta enviada ao cliente:', $response);
            
            return response()->json($response);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao abrir caixa: ' . $e->getMessage(), ['exception' => $e]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
} 