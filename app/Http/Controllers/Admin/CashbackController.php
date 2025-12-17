<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashbackSetting;
use App\Models\UserCashback;
use App\Services\CashbackService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\GameHistory;

class CashbackController extends Controller
{
    protected $cashbackService;

    public function __construct(CashbackService $cashbackService)
    {
        $this->cashbackService = $cashbackService;
    }

    /**
     * Exibe a lista de configurações de cashback
     */
    public function index()
    {
        return view('admin.cashback.settings');
    }

    /**
     * Fornece dados para a tabela de configurações de cashback
     */
    public function settingsData(Request $request)
    {
        $query = CashbackSetting::query();
        
        // Filtros
        if ($request->filled('tipoFiltro')) {
            $query->where('type', $request->tipoFiltro);
        }
        
        if ($request->filled('statusAtivo')) {
            $query->where('active', $request->statusAtivo);
        }
        
        if ($request->filled('nivelVIP')) {
            if ($request->nivelVIP === 'global') {
                $query->where('is_global', true);
            } else {
                $query->where('vip_level', $request->nivelVIP);
            }
        }
        
        $settings = $query->get();
        
        return datatables()->of($settings)
            ->addColumn('type', function ($setting) {
                $tipos = [
                    'sports' => 'Apostas Esportivas',
                    'virtual' => 'Jogos Virtuais',
                    'all' => 'Todos os Jogos'
                ];
                return $tipos[$setting->type] ?? $setting->type;
            })
            ->addColumn('status', function ($setting) {
                $badge = $setting->active 
                    ? '<span class="badge bg-success">Ativo</span>' 
                    : '<span class="badge bg-danger">Inativo</span>';
                return $badge;
            })
            ->addColumn('vip_level', function ($setting) {
                if ($setting->is_global) {
                    return '<span class="badge bg-info">Global</span>';
                } elseif ($setting->vip_level) {
                    $niveis = [
                        1 => 'Bronze',
                        2 => 'Prata',
                        3 => 'Ouro',
                        4 => 'Diamante',
                        5 => 'Platina'
                    ];
                    return '<span class="badge bg-warning">' . ($niveis[$setting->vip_level] ?? 'Nível ' . $setting->vip_level) . '</span>';
                }
                return '-';
            })
            ->addColumn('schedule', function ($setting) {
                if (!$setting->schedule_active) {
                    return '<span class="badge bg-secondary">Desativado</span>';
                }
                
                $dias = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
                $frequencia = [
                    'daily' => 'Diária',
                    'weekly' => 'Semanal',
                    'biweekly' => 'Quinzenal',
                    'monthly' => 'Mensal',
                    'once' => 'Uma vez'
                ];
                
                $texto = $frequencia[$setting->scheduled_frequency] ?? $setting->scheduled_frequency;
                
                if ($setting->scheduled_frequency === 'weekly' && $setting->scheduled_day !== null) {
                    $texto .= ' (' . ($dias[$setting->scheduled_day] ?? 'Dia ' . $setting->scheduled_day) . ')';
                } else if ($setting->scheduled_frequency === 'biweekly' && $setting->scheduled_day !== null) {
                    if ($setting->scheduled_day == 1) {
                        $texto .= ' (Dia 1)';
                    } else if ($setting->scheduled_day == 16) {
                        $texto .= ' (Dia 16)';
                    }
                } else if ($setting->scheduled_frequency === 'once' && $setting->scheduled_at) {
                    $scheduledAt = \Carbon\Carbon::parse($setting->scheduled_at);
                    $texto .= ' (' . $scheduledAt->format('d/m/Y') . ')';
                }
                
                if ($setting->scheduled_hour !== null && $setting->scheduled_minute !== null) {
                    $texto .= ' às ' . sprintf('%02d:%02d', $setting->scheduled_hour, $setting->scheduled_minute);
                }
                
                return '<span class="badge bg-primary">' . $texto . '</span>';
            })
            ->addColumn('actions', function ($setting) {
                $editBtn = '<button class="btn btn-sm btn-primary me-2 btn-edit-cashback" data-id="' . $setting->id . '" title="Editar"><i class="fas fa-edit"></i></button>';
                $deleteBtn = '<button class="btn btn-sm btn-danger delete-cashback-btn" data-id="' . $setting->id . '" data-name="' . $setting->name . '" title="Excluir"><i class="fas fa-trash"></i></button>';
                
                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['status', 'vip_level', 'schedule', 'actions'])
            ->make(true);
    }

    /**
     * Fornece dados para a tabela de cashbacks dos usuários
     */
    public function userCashbacksData(Request $request)
    {
        $query = UserCashback::with(['user', 'setting']);
        
        // Filtros
        if ($request->filled('statusFiltro')) {
            $query->where('status', $request->statusFiltro);
        }
        
        if ($request->filled('tipoFiltro')) {
            $query->where('type', $request->tipoFiltro);
        }
        
        if ($request->filled('usuarioFiltro')) {
            $userFilter = $request->usuarioFiltro;
            $query->whereHas('user', function ($q) use ($userFilter) {
                $q->where('name', 'like', "%{$userFilter}%")
                  ->orWhere('email', 'like', "%{$userFilter}%")
                  ->orWhere('id', $userFilter);
            });
        }
        
        if ($request->filled('nivelVipFiltro')) {
            if ($request->nivelVipFiltro === 'global') {
                $settingIds = CashbackSetting::where('is_global', true)->pluck('id');
                $query->whereIn('cashback_setting_id', $settingIds);
            } else {
                $settingIds = CashbackSetting::where('vip_level', $request->nivelVipFiltro)->pluck('id');
                $query->whereIn('cashback_setting_id', $settingIds);
            }
        }
        
        if ($request->filled('globalFiltro') && $request->globalFiltro === '1') {
            $settingIds = CashbackSetting::where('is_global', true)->pluck('id');
            $query->whereIn('cashback_setting_id', $settingIds);
        }
        
        $cashbacks = $query->get();
        
        return datatables()->of($cashbacks)
            ->addColumn('user', function ($cashback) {
                if ($cashback->user) {
                    return '<a href="' . route('admin.cashback.user.losses', $cashback->user_id) . '">' . 
                        $cashback->user->name . ' (ID: ' . $cashback->user_id . ')</a>';
                }
                return 'Usuário #' . $cashback->user_id;
            })
            ->addColumn('vip_level', function ($cashback) {
                $niveis = [
                    1 => 'Bronze',
                    2 => 'Prata',
                    3 => 'Ouro',
                    4 => 'Diamante',
                    5 => 'Platina'
                ];
                
                if ($cashback->setting && $cashback->setting->is_global) {
                    return '<span class="badge bg-info">Global</span>';
                } elseif ($cashback->setting && $cashback->setting->vip_level) {
                    return '<span class="badge bg-warning">' . ($niveis[$cashback->setting->vip_level] ?? 'Nível ' . $cashback->setting->vip_level) . '</span>';
                }
                return '-';
            })
            ->addColumn('total_loss', function ($cashback) {
                return 'R$ ' . number_format($cashback->total_loss, 2, ',', '.');
            })
            ->addColumn('cashback_amount', function ($cashback) {
                return 'R$ ' . number_format($cashback->cashback_amount, 2, ',', '.');
            })
            ->addColumn('percentage_applied', function ($cashback) {
                return $cashback->percentage_applied . '%';
            })
            ->addColumn('type', function ($cashback) {
                $tipos = [
                    'sports' => 'Apostas Esportivas',
                    'virtual' => 'Jogos Virtuais',
                    'all' => 'Todos os Jogos'
                ];
                return $tipos[$cashback->type] ?? $cashback->type;
            })
            ->addColumn('status', function ($cashback) {
                $badges = [
                    'pending' => '<span class="badge bg-warning">Pendente</span>',
                    'credited' => '<span class="badge bg-success">Creditado</span>',
                    'expired' => '<span class="badge bg-danger">Expirado</span>'
                ];
                return $badges[$cashback->status] ?? $cashback->status;
            })
            ->addColumn('expires_at', function ($cashback) {
                if ($cashback->expires_at) {
                    $expiresAt = Carbon::parse($cashback->expires_at);
                    $now = Carbon::now();
                    
                    // Se expirado
                    if ($cashback->status === 'expired') {
                        return '<span class="text-danger">Expirado em ' . $expiresAt->format('d/m/Y') . '</span>';
                    }
                    
                    // Se creditado
                    if ($cashback->status === 'credited') {
                        if ($cashback->credited_at) {
                            return '<span class="text-success">Creditado em ' . Carbon::parse($cashback->credited_at)->format('d/m/Y') . '</span>';
                        }
                        return '<span class="text-success">Creditado</span>';
                    }
                    
                    // Se pendente
                    $diff = $now->diffInDays($expiresAt, false);
                    if ($diff < 0) {
                        return '<span class="text-danger">Expirado</span>';
                    } elseif ($diff <= 3) {
                        return '<span class="text-warning">Expira em ' . $diff . ' dias</span>';
                    } else {
                        return $expiresAt->format('d/m/Y');
                    }
                }
                return '-';
            })
            ->addColumn('actions', function ($cashback) {
                $actions = '';
                
                // Botão para detalhes das perdas
                $actions .= '<a href="javascript:void(0)" class="btn btn-sm btn-info me-2 btn-view-losses" title="Ver Detalhes das Perdas" data-user-id="' . $cashback->user_id . '"><i class="fas fa-chart-bar"></i></a>';
                
                // Botão para aplicar cashback (somente se estiver pendente)
                if ($cashback->status === 'pending') {
                    $actions .= '<button class="btn btn-sm btn-success me-2 btn-apply-cashback" title="Aplicar Cashback" data-id="' . $cashback->id . '" data-user="' . ($cashback->user->name ?? 'Usuário #' . $cashback->user_id) . '" data-amount="' . number_format($cashback->cashback_amount, 2, ',', '.') . '"><i class="fas fa-check"></i></button>';
                }
                
                // Botão para excluir cashback
                $actions .= '<button class="btn btn-sm btn-danger btn-delete-cashback" title="Excluir Cashback" data-id="' . $cashback->id . '" data-user="' . ($cashback->user->name ?? 'Usuário #' . $cashback->user_id) . '" data-amount="' . number_format($cashback->cashback_amount, 2, ',', '.') . '"><i class="fas fa-trash"></i></button>';
                
                return $actions;
            })
            ->rawColumns(['user', 'vip_level', 'status', 'expires_at', 'actions'])
            ->make(true);
    }

    /**
     * Exibe formulário para criar uma nova configuração
     */
    public function create()
    {
        // Buscar todos os níveis VIP disponíveis
        $vipLevels = \App\Models\VipLevel::where('active', true)->orderBy('level', 'asc')->get();
        
        return view('admin.cashback.create', compact('vipLevels'));
    }

    /**
     * Armazena nova configuração de cashback
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0.01|max:100',
            'type' => 'required|in:sports,virtual,all',
            'min_loss' => 'required|numeric|min:0',
            'max_cashback' => 'nullable|numeric|min:0',
            'auto_apply' => 'boolean',
            'expiry_days' => 'required|integer|min:1',
            'active' => 'boolean',
            'is_global' => 'boolean',
            'vip_level' => 'nullable|integer',
            'schedule_active' => 'boolean',
            'scheduled_frequency' => 'required_if:schedule_active,1|in:daily,weekly,biweekly,monthly,once',
            'scheduled_day' => 'nullable|integer|min:0|max:31',
            'scheduled_hour' => 'required_if:schedule_active,1|nullable|integer|min:0|max:23',
            'scheduled_minute' => 'required_if:schedule_active,1|nullable|integer|min:0|max:59'
        ]);

        // Se for global, garantir que vip_level seja null
        if (isset($validated['is_global']) && $validated['is_global']) {
            $validated['vip_level'] = null;
        }
        
        // Se não for global nem tiver nível VIP, configurar como global
        if (!isset($validated['is_global']) && !isset($validated['vip_level'])) {
            $validated['is_global'] = true;
        }
        
        // Verificar se já existe configuração global ou para o mesmo nível VIP
        if (isset($validated['is_global']) && $validated['is_global']) {
            $exists = CashbackSetting::where('is_global', true)
                ->where('type', $validated['type'])
                ->exists();
                
            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Já existe uma configuração global para este tipo de jogo. Edite a existente ou desative-a antes de criar uma nova.');
            }
        } elseif (isset($validated['vip_level'])) {
            $exists = CashbackSetting::where('vip_level', $validated['vip_level'])
                ->where('type', $validated['type'])
                ->exists();
                
            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Já existe uma configuração para este nível VIP e tipo de jogo. Edite a existente ou desative-a antes de criar uma nova.');
            }
        }

        $setting = CashbackSetting::create($validated);
        
        // Se o agendamento estiver ativo, calcular próxima execução
        if ($setting->schedule_active) {
            $setting->updateNextRun();
        }
        
        return redirect()->route('admin.cashback.index')
            ->with('success', 'Configuração de cashback criada com sucesso!');
    }

    /**
     * Exibe formulário para editar configuração
     */
    public function edit($id)
    {
        $setting = CashbackSetting::findOrFail($id);
        $vipLevels = \App\Models\VipLevel::where('active', true)->orderBy('level', 'asc')->get();
        
        return view('admin.cashback.edit', compact('setting', 'vipLevels'));
    }

    /**
     * Atualiza configuração de cashback
     */
    public function update(Request $request, $id)
    {
        $setting = CashbackSetting::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0.01|max:100',
            'type' => 'required|in:sports,virtual,all',
            'min_loss' => 'required|numeric|min:0',
            'max_cashback' => 'nullable|numeric|min:0',
            'auto_apply' => 'boolean',
            'expiry_days' => 'required|integer|min:1',
            'active' => 'boolean',
            'is_global' => 'boolean',
            'vip_level' => 'nullable|integer',
            'schedule_active' => 'boolean',
            'scheduled_frequency' => 'required_if:schedule_active,1|in:daily,weekly,biweekly,monthly,once',
            'scheduled_day' => 'nullable|integer|min:0|max:31',
            'scheduled_hour' => 'required_if:schedule_active,1|nullable|integer|min:0|max:23',
            'scheduled_minute' => 'required_if:schedule_active,1|nullable|integer|min:0|max:59'
        ]);
        
        // Ajustar o scheduled_day conforme a frequência selecionada
        if (isset($validated['schedule_active']) && $validated['schedule_active']) {
            if (isset($validated['scheduled_frequency'])) {
                if ($validated['scheduled_frequency'] === 'weekly') {
                    // Para frequência semanal, garantir que o dia está no intervalo de 0-6
                    $validated['scheduled_day'] = isset($validated['scheduled_day']) ? min(max(intval($validated['scheduled_day']), 0), 6) : 1;
                } else if ($validated['scheduled_frequency'] === 'biweekly') {
                    // Para frequência quinzenal, usar dia 1 ou 16
                    $validated['scheduled_day'] = $validated['scheduled_day'] > 10 ? 16 : 1;
                } else if ($validated['scheduled_frequency'] === 'monthly') {
                    // Para frequência mensal, garantir que o dia está no intervalo de 1-28 
                    $validated['scheduled_day'] = isset($validated['scheduled_day']) ? min(max(intval($validated['scheduled_day']), 1), 28) : 1;
                }
            }
        }
        
        // Se for global, garantir que vip_level seja null
        if (isset($validated['is_global']) && $validated['is_global']) {
            $validated['vip_level'] = null;
        }
        
        // Se não for global nem tiver nível VIP, configurar como global
        if (!isset($validated['is_global']) && !isset($validated['vip_level'])) {
            $validated['is_global'] = true;
        }
        
        // Verificar se já existe outra configuração global ou para o mesmo nível VIP
        if (isset($validated['is_global']) && $validated['is_global']) {
            $exists = CashbackSetting::where('is_global', true)
                ->where('type', $validated['type'])
                ->where('id', '!=', $id)
                ->exists();
                
            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Já existe uma configuração global para este tipo de jogo. Edite a existente ou desative-a antes de atualizar esta.');
            }
        } elseif (isset($validated['vip_level'])) {
            $exists = CashbackSetting::where('vip_level', $validated['vip_level'])
                ->where('type', $validated['type'])
                ->where('id', '!=', $id)
                ->exists();
                
            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Já existe uma configuração para este nível VIP e tipo de jogo. Edite a existente ou desative-a antes de atualizar esta.');
            }
        }
        
        $setting->update($validated);
        
        // Se o agendamento estiver ativo, calcular próxima execução
        if ($setting->schedule_active) {
            $setting->updateNextRun();
        } else {
            $setting->next_run_at = null;
            $setting->save();
        }
        
        return redirect()->route('admin.cashback.index')
            ->with('success', 'Configuração de cashback atualizada com sucesso!');
    }

    /**
     * Exibe lista de cashbacks dos usuários
     */
    public function userCashbacks(Request $request)
    {
        return view('admin.cashback.user-cashbacks');
    }

    /**
     * Aplicar manualmente um cashback pendente
     */
    public function applyCashback($id)
    {
        try {
            $result = $this->cashbackService->applyCashback($id);
            
            if ($result) {
                return redirect()->back()->with('success', 'Cashback aplicado com sucesso!');
            } else {
                return redirect()->back()->with('error', 'Não foi possível aplicar o cashback.');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao aplicar cashback: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao aplicar cashback: ' . $e->getMessage());
        }
    }

    /**
     * Processar cashbacks para todos os usuários
     */
    public function processAll(Request $request)
    {
        $type = $request->input('type', 'all');
        
        try {
            $results = $this->cashbackService->processAutomaticCashbacks($type);
            
            $message = "Processamento concluído! {$results['processed']} cashbacks criados, totalizando R$ " . number_format($results['amount'], 2, ',', '.');
            
            if ($results['processed'] > 0) {
                $message .= " ({$results['global']} globais, {$results['vip']} VIP)";
            }
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erro ao processar cashbacks: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao processar cashbacks: ' . $e->getMessage());
        }
    }

    /**
     * Exibe relatório de cashbacks
     */
    public function report(Request $request)
    {
        $startDate = $request->input('start_date') 
            ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()
            : Carbon::now()->subMonth()->startOfDay();
            
        $endDate = $request->input('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();
            
        // Usar relatório detalhado que inclui informações sobre a origem das perdas
        $report = $this->cashbackService->generateDetailedCashbackReport($startDate, $endDate);
        
        return view('admin.cashback.report', compact('report', 'startDate', 'endDate'));
    }

    /**
     * Exibe detalhes de perdas de um usuário específico
     */
    public function userLossDetails($userId, Request $request)
    {
        $user = User::findOrFail($userId);
        
        $startDate = $request->input('start_date') 
            ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()
            : Carbon::now()->subMonth()->startOfDay();
            
        $endDate = $request->input('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();
            
        $details = $this->cashbackService->getDetailedLossBreakdown($userId, $startDate, $endDate);
        
        // Buscar histórico de apostas esportivas perdidas
        $sportsLosses = $this->cashbackService->getSportsLostBets($userId, $startDate, $endDate);
        
        // Buscar histórico de jogos virtuais perdidos com join para obter o nome do jogo
        $virtualLosses = GameHistory::select(
                'games_history.*',
                'games_api.name as game_name',
                'providers.name as provider_name'
            )
            ->leftJoin('games_api', 'games_history.game', '=', 'games_api.slug')
            ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
            ->where('games_history.user_id', $userId)
            ->whereBetween('games_history.created_at', [$startDate, $endDate])
            ->where('games_history.action', 'loss')
            ->where('games_history.provider', '!=', 'sports')
            ->get();
            
        return view('admin.cashback.user-loss-details', compact(
            'user', 
            'details', 
            'sportsLosses', 
            'virtualLosses', 
            'startDate', 
            'endDate'
        ));
    }

    /**
     * Processar cashback manualmente para um usuário específico
     */
    public function processForUser(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $type = $request->input('type', 'all');
        
        $startDate = $request->input('start_date') 
            ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()
            : Carbon::now()->subWeek()->startOfWeek();
            
        $endDate = $request->input('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()
            : Carbon::now()->subWeek()->endOfWeek();
            
        try {
            $cashback = $this->cashbackService->processCashback($user, $type, $startDate, $endDate);
            
            if ($cashback) {
                return redirect()->back()->with('success', 'Cashback processado com sucesso! Valor: R$ ' . number_format($cashback->cashback_amount, 2, ',', '.'));
            } else {
                return redirect()->back()->with('warning', 'Não foi possível gerar cashback. Verifique se o usuário teve perdas suficientes no período.');
            }
        } catch (\Exception $e) {
            Log::error('Erro ao processar cashback para usuário: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao processar cashback: ' . $e->getMessage());
        }
    }

    /**
     * Processa cashbacks agendados manualmente
     */
    public function processScheduled()
    {
        try {
            $results = $this->cashbackService->processScheduledCashbacks();
            
            $message = "Processamento de cashbacks agendados concluído! {$results['processed']} cashbacks criados, totalizando R$ " . number_format($results['amount'], 2, ',', '.');
            
            if ($results['processed'] > 0) {
                $message .= " ({$results['global']} globais, {$results['vip']} VIP)";
            }
            
            if (!empty($results['settings_processed'])) {
                $processedDetails = [];
                foreach ($results['settings_processed'] as $setting) {
                    $processedDetails[] = "{$setting['name']}: {$setting['processed']} cashbacks";
                }
                $message .= "<br>Configurações processadas: " . implode(', ', $processedDetails);
            }
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erro ao processar cashbacks agendados: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao processar cashbacks agendados: ' . $e->getMessage());
        }
    }

    /**
     * Adiciona cashback manual para um usuário específico
     */
    public function addManualCashback(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:sports,virtual,all',
            'percentage' => 'required|numeric|min:0.1|max:100',
            'amount' => 'required|numeric|min:0.01',
            'observation' => 'nullable|string|max:255',
        ]);

        try {
            $user = User::findOrFail($validated['user_id']);
            
            // Obter o nível VIP do usuário
            $userRanking = $user->getRanking();
            $userVipLevel = $userRanking ? $userRanking['level'] : 1;
            
            // Buscar a configuração de cashback específica para o nível VIP do usuário
            $cashbackSetting = CashbackSetting::where('active', true)
                ->where('type', $validated['type'])
                ->where(function($query) use ($userVipLevel) {
                    $query->where('vip_level', $userVipLevel)
                          ->orWhere('is_global', true);
                })
                ->orderBy('vip_level', 'desc') // Prioriza configuração específica do nível VIP
                ->first();
            
            // Se não encontrar, busca uma configuração global para o tipo
            if (!$cashbackSetting) {
                $cashbackSetting = CashbackSetting::where('active', true)
                    ->where('is_global', true)
                    ->where('type', $validated['type'])
                    ->first();
            }
            
            // Se ainda não encontrar, busca qualquer configuração global
            if (!$cashbackSetting) {
                $cashbackSetting = CashbackSetting::where('active', true)
                    ->where('is_global', true)
                    ->first();
            }
            
            // Se não encontrar nenhuma configuração ativa, cria uma temporária
            if (!$cashbackSetting) {
                $cashbackSetting = CashbackSetting::create([
                    'name' => 'Configuração para Cashback Manual',
                    'percentage' => $validated['percentage'],
                    'type' => $validated['type'],
                    'min_loss' => 0,
                    'max_cashback' => $validated['amount'], // Usa o valor informado como máximo
                    'auto_apply' => false, // Não aplicar automaticamente
                    'expiry_days' => 30,
                    'active' => true,
                    'is_global' => true,
                    'schedule_active' => false
                ]);
            }
            
            // Verificar se o agendamento está ativo
            $useScheduling = $cashbackSetting->schedule_active;
            $expiryDays = $cashbackSetting->expiry_days ?? 30;
            
            // Sempre configurar como pendente, independente da configuração auto_apply
            $statusInicial = 'pending';
            
            // Determinar a data de expiração
            $expiresAt = Carbon::now()->addDays($expiryDays);
            
            // Criar um novo cashback manual - usar a porcentagem definida pelo usuário
            $userCashback = UserCashback::create([
                'user_id' => $user->id,
                'cashback_setting_id' => $cashbackSetting->id,
                'total_loss' => 0, // Não há perda calculada, é manual
                'cashback_amount' => $validated['amount'],
                'percentage_applied' => $validated['percentage'], // Usa a porcentagem que o usuário definiu
                'type' => $validated['type'],
                'status' => $statusInicial,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now(),
                'expires_at' => $expiresAt,
                'notes' => 'Cashback Manual: ' . ($validated['observation'] ?? 'Adicionado pelo administrador')
            ]);
            
            // NÃO aplica o cashback imediatamente - deixamos como pendente
            
            // Se o agendamento estiver ativo, atualiza a próxima execução na configuração
            if ($useScheduling) {
                $cashbackSetting->updateNextRun();
            }
            
            return redirect()->route('admin.cashback.users')->with('success', 'Cashback manual adicionado com sucesso para ' . $user->name . '. Status: Pendente');
        } catch (\Exception $e) {
            Log::error('Erro ao adicionar cashback manual: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao adicionar cashback manual: ' . $e->getMessage());
        }
    }

    /**
     * Armazena uma nova configuração de cashback específica para um usuário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUserSpecific(Request $request)
    {
        try {
            // Validar os dados do formulário
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'name' => 'required|string|max:255',
                'type' => 'required|in:all,sports,virtual',
                'percentage' => 'required|numeric|min:0.01|max:100',
                'min_loss' => 'required|numeric|min:0',
                'max_cashback' => 'nullable|numeric|min:0',
                'expiry_days' => 'required|integer|min:1',
                'auto_apply' => 'nullable|in:1',
                'schedule_active' => 'nullable|in:1',
                'schedule_frequency' => 'nullable|in:once,daily,weekly,biweekly,monthly',
                'scheduled_date' => 'required_if:schedule_active,1|date|after_or_equal:today',
                'scheduled_time' => 'required_if:schedule_active,1',
            ]);

            // Obter o usuário
            $user = \App\Models\User::findOrFail($request->user_id);

            // Criar a configuração de cashback
            $cashback = new CashbackSetting();
            $cashback->name = $request->name;
            $cashback->type = $request->type;
            $cashback->percentage = $request->percentage;
            $cashback->min_loss = $request->min_loss;
            $cashback->max_cashback = $request->max_cashback;
            $cashback->expiry_days = $request->expiry_days;
            $cashback->auto_apply = $request->has('auto_apply') ? 1 : 0;
            $cashback->is_global = 0; // Não é global, é específico para um usuário
            $cashback->vip_level = null; // Não está associado a nível VIP
            $cashback->user_id = $user->id; // Associar ao usuário específico
            $cashback->active = 1; // Ativo por padrão

            // Configurar agendamento, se habilitado
            if ($request->has('schedule_active')) {
                $cashback->schedule_active = 1;
                
                // Frequência de agendamento (padrão: única vez)
                $frequency = $request->schedule_frequency ?? 'once';
                $cashback->scheduled_frequency = $frequency;
                
                // Para frequências recorrentes, configurar dia da semana ou do mês
                if ($frequency === 'weekly') {
                    // Se for semanal, usar o dia da semana da data selecionada
                    $scheduledDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->scheduled_date);
                    $cashback->scheduled_day = $scheduledDate->dayOfWeek;
                } elseif ($frequency === 'biweekly') {
                    // Se for quinzenal, configurar para rodar a cada 15 dias
                    $scheduledDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->scheduled_date);
                    $cashback->scheduled_day = $scheduledDate->day <= 15 ? 1 : 16; // 1 para primeira quinzena, 16 para segunda
                    $cashback->scheduled_frequency = 'biweekly';
                } elseif ($frequency === 'monthly') {
                    // Se for mensal, usar o dia do mês
                    $scheduledDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->scheduled_date);
                    $cashback->scheduled_day = $scheduledDate->day;
                }
                
                // Configurar hora e minuto
                $time = explode(':', $request->scheduled_time);
                $cashback->scheduled_hour = $time[0] ?? 0;
                $cashback->scheduled_minute = $time[1] ?? 0;
                
                // Armazenar a data completa para execução única
                if ($frequency === 'once') {
                    $scheduledDateTime = \Carbon\Carbon::createFromFormat(
                        'Y-m-d H:i', 
                        $request->scheduled_date . ' ' . $request->scheduled_time
                    );
                    $cashback->scheduled_at = $scheduledDateTime;
                }
                
                // Calcular a próxima execução
                $cashback->updateNextRun();
            } else {
                $cashback->schedule_active = 0;
            }

            // Salvar a configuração
            $cashback->save();

            return redirect()->route('admin.cashback.index')
                ->with('success', "Cashback específico para o usuário {$user->name} configurado com sucesso!");
        } catch (\Exception $e) {
            return redirect()->route('admin.cashback.index')
                ->with('error', 'Erro ao configurar cashback: ' . $e->getMessage());
        }
    }

    /**
     * Remove uma configuração de cashback.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Verificar se o cashback existe antes de tentar excluir
            if (!$id || !is_numeric($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID de configuração inválido.'
                ], 400);
            }

            // Usar find em vez de findOrFail para verificar manualmente se existe
            $setting = CashbackSetting::find($id);
            
            if (!$setting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuração de cashback não encontrada.'
                ], 404);
            }
            
            // Deletar o registro
            $setting->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Configuração de cashback excluída com sucesso!'
            ]);
        } catch (\Exception $e) {
            // Log do erro para ajudar na depuração
            \Log::error('Erro ao excluir configuração de cashback: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir configuração: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna os dados de uma configuração de cashback em formato JSON
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSettingJson($id)
    {
        try {
            $setting = CashbackSetting::findOrFail($id);
            return response()->json($setting);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Configuração não encontrada'], 404);
        }
    }

    /**
     * Excluir um cashback de usuário
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCashback($id)
    {
        try {
            $cashback = UserCashback::findOrFail($id);
            
            // Não permitir excluir cashbacks já creditados
            if ($cashback->status === 'credited') {
                return redirect()->back()
                    ->with('error', 'Não é possível excluir um cashback que já foi creditado.');
            }
            
            $cashback->delete();
            
            return redirect()->route('admin.cashback.users')
                ->with('success', 'Cashback excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao excluir cashback: ' . $e->getMessage());
        }
    }

    /**
     * Retorna detalhes de perdas de um usuário em formato para modal Ajax
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function userLossDetailsAjax(Request $request)
    {
        $userId = $request->input('user_id');
        if (!$userId) {
            return response()->json(['error' => 'ID do usuário não fornecido'], 400);
        }
        
        try {
            $user = User::findOrFail($userId);
            
            $startDate = $request->input('start_date') 
                ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()
                : Carbon::now()->subMonth()->startOfDay();
                
            $endDate = $request->input('end_date')
                ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()
                : Carbon::now()->endOfDay();
                
            $details = $this->cashbackService->getDetailedLossBreakdown($userId, $startDate, $endDate);
            
            // Buscar histórico de apostas esportivas perdidas
            $sportsLosses = $this->cashbackService->getSportsLostBets($userId, $startDate, $endDate);
            
            // Buscar histórico de jogos virtuais perdidos com join para obter o nome do jogo
            $virtualLosses = GameHistory::select(
                    'games_history.*',
                    'games_api.name as game_name',
                    'providers.name as provider_name'
                )
                ->leftJoin('games_api', 'games_history.game', '=', 'games_api.slug')
                ->leftJoin('providers', 'games_api.provider_id', '=', 'providers.id')
                ->where('games_history.user_id', $userId)
                ->whereBetween('games_history.created_at', [$startDate, $endDate])
                ->where('games_history.action', 'loss')
                ->where('games_history.provider', '!=', 'sports')
                ->get();
                
            return view('admin.cashback.user-loss-details-ajax', compact(
                'user', 
                'details', 
                'sportsLosses', 
                'virtualLosses', 
                'startDate', 
                'endDate'
            ));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar detalhes: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Envia notificações para usuários sobre cashback
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendNotifications(Request $request)
    {
        // Validar parâmetros
        $validated = $request->validate([
            'cashback_setting_id' => 'nullable|exists:cashback_settings,id',
            'vip_level' => 'nullable|integer|min:1',
            'type' => 'nullable|in:sports,virtual,all',
            'status' => 'nullable|in:pending,credited,expired',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        try {
            // Preparar parâmetros
            $settingId = $request->input('cashback_setting_id');
            $vipLevel = $request->input('vip_level');
            $type = $request->input('type');
            $status = $request->input('status', 'credited');
            
            // Converter datas se fornecidas
            $startDate = $request->filled('start_date') 
                ? Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay()
                : null;
                
            $endDate = $request->filled('end_date')
                ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay()
                : null;
                
            // Chamar o serviço de cashback para enviar notificações
            $stats = $this->cashbackService->sendCashbackNotifications(
                $settingId, 
                $vipLevel, 
                $type, 
                $status, 
                $startDate, 
                $endDate
            );
            
            $message = "Notificações de cashback enviadas: {$stats['success']} de {$stats['total_notifications']} com sucesso.";
            
            if ($stats['error'] > 0) {
                $message .= " ({$stats['error']} falhas)";
            }
            
            if ($stats['success'] == 0) {
                return redirect()->back()->with('warning', $message . ' Verifique os filtros aplicados.');
            }
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificações de cashback: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao enviar notificações: ' . $e->getMessage());
        }
    }
} 