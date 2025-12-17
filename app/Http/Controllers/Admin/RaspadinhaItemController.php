<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Raspadinha;
use App\Models\RaspadinhaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RaspadinhaItemController extends Controller
{
    /**
     * Listar itens de uma raspadinha
     */
    public function index(Raspadinha $raspadinha)
    {
        $items = $raspadinha->items()->orderBy('position', 'asc')->paginate(15);
        return view('admin.raspadinha-item.index', compact('raspadinha', 'items'));
    }

    /**
     * Mostrar formulário de criação
     */
    public function create(Raspadinha $raspadinha)
    {
        return view('admin.raspadinha-item.create', compact('raspadinha'));
    }

    /**
     * Salvar novo item
     */
    public function store(Request $request, Raspadinha $raspadinha)
    {
        // Validações básicas
        $rules = [
            'name' => 'required|string|max:255',
            'premio_type' => 'required|in:saldo_real,saldo_bonus,rodadas_gratis,produto',
            'probability' => 'required|numeric|min:0.01|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ];

        $messages = [
            'name.required' => 'O nome é obrigatório',
            'premio_type.required' => 'O tipo de prêmio é obrigatório',
            'premio_type.in' => 'Tipo de prêmio inválido',
            'probability.required' => 'A probabilidade é obrigatória',
            'probability.min' => 'A probabilidade deve ser maior que zero',
            'probability.max' => 'A probabilidade não pode ser maior que 100%',
            'image.image' => 'O arquivo deve ser uma imagem',
            'image.max' => 'A imagem não pode ser maior que 2MB',
        ];

        // Validações condicionais baseadas no tipo de prêmio
        if ($request->premio_type === 'produto') {
            $rules['product_description'] = 'required|string|max:1000';
            $messages['product_description.required'] = 'A descrição do produto é obrigatória';
            $messages['product_description.max'] = 'A descrição do produto não pode ter mais de 1000 caracteres';
        } else {
            $rules['value'] = 'required|numeric|min:0';
            $messages['value.required'] = 'O valor é obrigatório';
            $messages['value.min'] = 'O valor deve ser maior ou igual a zero';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verificar se a soma das probabilidades não excede 100%
        $currentSum = $raspadinha->items()->sum('probability');
        if ($currentSum + $request->probability > 100) {
            return back()->withErrors(['probability' => 'A soma das probabilidades não pode exceder 100%. Disponível: ' . (100 - $currentSum) . '%']);
        }

        $data = $request->all();
        $data['raspadinha_id'] = $raspadinha->id;
        
        // Para produtos, definir valor como 0
        if ($request->premio_type === 'produto') {
            $data['value'] = 0;
        }
        
        // Definir posição automaticamente como a próxima disponível
        $maxPosition = $raspadinha->items()->max('position') ?? 0;
        $data['position'] = $maxPosition + 1;

        // Upload da imagem
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

        RaspadinhaItem::create($data);

        return redirect()->route('admin.raspadinha-item.index', $raspadinha)
            ->with('success', 'Item criado com sucesso!');
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit(Raspadinha $raspadinha, RaspadinhaItem $item)
    {
        return view('admin.raspadinha-item.edit', compact('raspadinha', 'item'));
    }

    /**
     * Atualizar item
     */
    public function update(Request $request, Raspadinha $raspadinha, RaspadinhaItem $item)
    {
        // Validações básicas
        $rules = [
            'name' => 'required|string|max:255',
            'premio_type' => 'required|in:saldo_real,saldo_bonus,rodadas_gratis,produto',
            'probability' => 'required|numeric|min:0.01|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:2048',
        ];

        $messages = [
            'name.required' => 'O nome é obrigatório',
            'premio_type.required' => 'O tipo de prêmio é obrigatório',
            'premio_type.in' => 'Tipo de prêmio inválido',
            'probability.required' => 'A probabilidade é obrigatória',
            'probability.min' => 'A probabilidade deve ser maior que zero',
            'probability.max' => 'A probabilidade não pode ser maior que 100%',
            'image.image' => 'O arquivo deve ser uma imagem',
            'image.max' => 'A imagem não pode ser maior que 2MB',
        ];

        // Validações condicionais baseadas no tipo de prêmio
        if ($request->premio_type === 'produto') {
            $rules['product_description'] = 'required|string|max:1000';
            $messages['product_description.required'] = 'A descrição do produto é obrigatória';
            $messages['product_description.max'] = 'A descrição do produto não pode ter mais de 1000 caracteres';
        } else {
            $rules['value'] = 'required|numeric|min:0';
            $messages['value.required'] = 'O valor é obrigatório';
            $messages['value.min'] = 'O valor deve ser maior ou igual a zero';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verificar se a soma das probabilidades não excede 100% (excluindo o item atual)
        $currentSum = $raspadinha->items()->where('id', '!=', $item->id)->sum('probability');
        if ($currentSum + $request->probability > 100) {
            return back()->withErrors(['probability' => 'A soma das probabilidades não pode exceder 100%. Disponível: ' . (100 - $currentSum) . '%']);
        }

        $data = $request->all();

        // Para produtos, definir valor como 0
        if ($request->premio_type === 'produto') {
            $data['value'] = 0;
        }

        // Upload da nova imagem
        if ($request->hasFile('image')) {
            // Deletar imagem antiga
            if ($item->image && file_exists(public_path('raspadinha/' . $item->image))) {
                unlink(public_path('raspadinha/' . $item->image));
            }

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

        $item->update($data);

        return redirect()->route('admin.raspadinha-item.index', $raspadinha)
            ->with('success', 'Item atualizado com sucesso!');
    }

    /**
     * Deletar item
     */
    public function destroy(Raspadinha $raspadinha, RaspadinhaItem $item)
    {
        // Verificar se há histórico de jogadas com este item
        if ($item->history()->exists()) {
            return back()->with('error', 'Não é possível deletar um item que já foi premiado.');
        }

        // Deletar imagem se existir
        if ($item->image && file_exists(public_path('raspadinha/' . $item->image))) {
            unlink(public_path('raspadinha/' . $item->image));
        }

        $item->delete();

        return redirect()->route('admin.raspadinha-item.index', $raspadinha)
            ->with('success', 'Item deletado com sucesso!');
    }

    /**
     * Alterar status ativo/inativo
     */
    public function toggleStatus(Raspadinha $raspadinha, RaspadinhaItem $item)
    {
        $item->update(['is_active' => !$item->is_active]);

        $status = $item->is_active ? 'ativado' : 'desativado';
        
        return back()->with('success', "Item {$status} com sucesso!");
    }

    /**
     * Verificar soma das probabilidades
     */
    public function checkProbabilities(Raspadinha $raspadinha)
    {
        $totalProbability = $raspadinha->items()->sum('probability');
        
        return response()->json([
            'total' => $totalProbability,
            'remaining' => 100 - $totalProbability,
            'valid' => $totalProbability <= 100
        ]);
    }
} 