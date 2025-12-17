<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Raspadinha;
use App\Models\RaspadinhaItem;
use App\Models\RaspadinhaHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RaspadinhaController extends Controller
{
    /**
     * Listar todas as raspadinhas
     */
    public function index()
    {
        $raspadinhas = Raspadinha::with('items')->paginate(10);
        return view('admin.raspadinha.index', compact('raspadinhas'));
    }

    /**
     * Mostrar formulário de criação
     */
    public function create()
    {
        return view('admin.raspadinha.create');
    }

    /**
     * Salvar nova raspadinha
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'turbo_price' => 'required|numeric|min:0.01|gt:price',
            'rtp_percentage' => 'required|numeric|min:50|max:95',
            'turbo_boost_percentage' => 'required|numeric|min:0|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ], [
            'name.required' => 'O nome é obrigatório',
            'price.required' => 'O preço é obrigatório',
            'price.min' => 'O preço deve ser maior que zero',
            'turbo_price.required' => 'O preço turbo é obrigatório',
            'turbo_price.gt' => 'O preço turbo deve ser maior que o preço normal',
            'rtp_percentage.required' => 'O RTP é obrigatório',
            'rtp_percentage.min' => 'O RTP deve ser no mínimo 50%',
            'rtp_percentage.max' => 'O RTP deve ser no máximo 95%',
            'turbo_boost_percentage.required' => 'O Boost Turbo é obrigatório',
            'turbo_boost_percentage.min' => 'O Boost Turbo deve ser no mínimo 0%',
            'turbo_boost_percentage.max' => 'O Boost Turbo deve ser no máximo 20%',
            'image.image' => 'O arquivo deve ser uma imagem',
            'image.mimes' => 'A imagem deve ser nos formatos: jpeg, png, jpg, gif, webp, avif',
            'image.max' => 'A imagem não pode ser maior que 2MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name', 'description', 'price', 'turbo_price', 'rtp_percentage', 'turbo_boost_percentage', 'is_active']);

        // Processar upload da imagem
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Criar diretório se não existir
            $uploadPath = public_path('raspadinha');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Mover arquivo para public/raspadinha
            $image->move($uploadPath, $imageName);
            $data['image'] = $imageName;
        }

        Raspadinha::create($data);

        return redirect()->route('admin.raspadinha.index')
            ->with('success', 'Raspadinha criada com sucesso!');
    }

    /**
     * Mostrar raspadinha específica
     */
    public function show(Raspadinha $raspadinha)
    {
        $raspadinha->load('items', 'history.user');
        return view('admin.raspadinha.show', compact('raspadinha'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(Raspadinha $raspadinha)
    {
        $raspadinha->load(['items' => function($query) {
            $query->orderBy('position', 'asc');
        }]);
        return view('admin.raspadinha.edit', compact('raspadinha'));
    }

    /**
     * Atualizar raspadinha
     */
    public function update(Request $request, Raspadinha $raspadinha)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
            'turbo_price' => 'required|numeric|min:0.01|gt:price',
            'rtp_percentage' => 'required|numeric|min:50|max:95',
            'turbo_boost_percentage' => 'required|numeric|min:0|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ], [
            'name.required' => 'O nome é obrigatório',
            'price.required' => 'O preço é obrigatório',
            'price.min' => 'O preço deve ser maior que zero',
            'turbo_price.required' => 'O preço turbo é obrigatório',
            'turbo_price.gt' => 'O preço turbo deve ser maior que o preço normal',
            'rtp_percentage.required' => 'O RTP é obrigatório',
            'rtp_percentage.min' => 'O RTP deve ser no mínimo 50%',
            'rtp_percentage.max' => 'O RTP deve ser no máximo 95%',
            'turbo_boost_percentage.required' => 'O Boost Turbo é obrigatório',
            'turbo_boost_percentage.min' => 'O Boost Turbo deve ser no mínimo 0%',
            'turbo_boost_percentage.max' => 'O Boost Turbo deve ser no máximo 20%',
            'image.image' => 'O arquivo deve ser uma imagem',
            'image.mimes' => 'A imagem deve ser nos formatos: jpeg, png, jpg, gif, webp, avif',
            'image.max' => 'A imagem não pode ser maior que 2MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name', 'description', 'price', 'turbo_price', 'rtp_percentage', 'turbo_boost_percentage', 'is_active']);

        // Processar upload da imagem
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Criar diretório se não existir
            $uploadPath = public_path('raspadinha');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Mover arquivo para public/raspadinha
            $image->move($uploadPath, $imageName);
            $data['image'] = $imageName;
        }

        $raspadinha->update($data);

        return redirect()->route('admin.raspadinha.index')
            ->with('success', 'Raspadinha atualizada com sucesso!');
    }

    /**
     * Deletar raspadinha
     */
    public function destroy(Raspadinha $raspadinha)
    {
        // Verificar se há histórico de jogadas
        if ($raspadinha->history()->exists()) {
            return back()->with('error', 'Não é possível deletar uma raspadinha que já possui histórico de jogadas.');
        }

        $raspadinha->delete();

        return redirect()->route('admin.raspadinha.index')
            ->with('success', 'Raspadinha deletada com sucesso!');
    }

    /**
     * Alterar status ativo/inativo
     */
    public function toggleStatus(Raspadinha $raspadinha)
    {
        $raspadinha->update(['is_active' => !$raspadinha->is_active]);

        $status = $raspadinha->is_active ? 'ativada' : 'desativada';
        
        return back()->with('success', "Raspadinha {$status} com sucesso!");
    }

    /**
     * Atualizar posições dos itens
     */
    public function updatePositions(Request $request, Raspadinha $raspadinha)
    {
        try {
            $items = $request->input('items', []);
            
            foreach ($items as $item) {
                $raspadinhaItem = $raspadinha->items()->find($item['id']);
                if ($raspadinhaItem) {
                    $raspadinhaItem->update(['position' => $item['position']]);
                }
            }
            
            return response()->json(['success' => true, 'message' => 'Posições atualizadas com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar posições: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Listar histórico de jogadas
     */
    public function history(Request $request)
    {
        $raspadinhas = Raspadinha::all();
        return view('admin.raspadinha.history', compact('raspadinhas'));
    }

    /**
     * Dados para DataTable do histórico
     */
    public function historyData(Request $request)
    {
        $query = RaspadinhaHistory::with(['user', 'raspadinha']);

        // Filtros
        if ($request->filled('raspadinha_id')) {
            $query->where('raspadinha_id', $request->raspadinha_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Busca por texto (se implementada no futuro)
        if ($request->filled('search') && $request->search['value']) {
            $searchValue = $request->search['value'];
            $query->where(function($q) use ($searchValue) {
                $q->whereHas('user', function($userQuery) use ($searchValue) {
                    $userQuery->where('name', 'like', "%{$searchValue}%")
                             ->orWhere('email', 'like', "%{$searchValue}%");
                })
                ->orWhereHas('raspadinha', function($raspQuery) use ($searchValue) {
                    $raspQuery->where('name', 'like', "%{$searchValue}%");
                });
            });
        }

        $totalRecords = $query->count();

        // Ordenação
        if ($request->filled('order')) {
            $orderColumn = $request->order[0]['column'];
            $orderDir = $request->order[0]['dir'];
            
            $columns = ['id', 'user_id', 'raspadinha_id', 'amount_paid', 'amount_won', 'type', 'status', 'created_at'];
            
            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDir);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Paginação
        if ($request->filled('length') && $request->length != -1) {
            $query->skip($request->start ?? 0)->take($request->length);
        }

        $records = $query->get();

        $data = [];
        foreach ($records as $record) {
            $data[] = [
                'id' => $record->id,
                'usuario' => $record->user ? 
                    '<strong>' . $record->user->name . '</strong><br><small class="text-muted">' . $record->user->email . '</small>' : 
                    '<span class="text-muted">Usuário removido</span>',
                'raspadinha' => $record->raspadinha ? 
                    '<strong>' . $record->raspadinha->name . '</strong>' : 
                    '<span class="text-muted">Raspadinha removida</span>',
                'valor_pago' => '<span class="badge badge-primary">R$ ' . number_format($record->amount_paid, 2, ',', '.') . '</span>',
                'valor_ganho' => $record->amount_won > 0 ? 
                    '<span class="badge badge-success">R$ ' . number_format($record->amount_won, 2, ',', '.') . '</span>' : 
                    '<span class="badge badge-secondary">R$ 0,00</span>',
                'tipo' => ($record->is_turbo ? '<span class="badge badge-warning">Turbo</span>' : '<span class="badge badge-info">Normal</span>') . 
                         ($record->is_auto ? '<br><span class="badge badge-light-primary">Auto (' . $record->auto_quantity . 'x)</span>' : ''),
                'status' => $record->status == 'completed' ? 
                    '<span class="badge badge-light-success">Completo</span>' : 
                    '<span class="badge badge-light-warning">' . ucfirst($record->status) . '</span>',
                'data' => '<span title="' . $record->created_at->format('d/m/Y H:i:s') . '">' . 
                         $record->created_at->format('d/m/Y') . '<br><small class="text-muted">' . 
                         $record->created_at->format('H:i') . '</small></span>'
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    /**
     * Relatório de estatísticas
     */
    public function statistics()
    {
        $stats = [
            'total_games' => RaspadinhaHistory::count(),
            'total_revenue' => RaspadinhaHistory::sum('amount_paid'),
            'total_prizes' => RaspadinhaHistory::sum('amount_won'),
            'profit' => RaspadinhaHistory::sum('amount_paid') - RaspadinhaHistory::sum('amount_won'),
            'today_games' => RaspadinhaHistory::whereDate('created_at', today())->count(),
            'today_revenue' => RaspadinhaHistory::whereDate('created_at', today())->sum('amount_paid'),
            'active_raspadinhas' => Raspadinha::active()->count(),
        ];

        $topWinners = RaspadinhaHistory::with('user')
            ->selectRaw('user_id, SUM(amount_won) as total_won')
            ->groupBy('user_id')
            ->orderBy('total_won', 'desc')
            ->limit(10)
            ->get();

        $popularRaspadinhas = RaspadinhaHistory::with('raspadinha')
            ->selectRaw('raspadinha_id, COUNT(*) as total_plays')
            ->groupBy('raspadinha_id')
            ->orderBy('total_plays', 'desc')
            ->limit(5)
            ->get();

        return view('admin.raspadinha.statistics', compact('stats', 'topWinners', 'popularRaspadinhas'));
    }
} 