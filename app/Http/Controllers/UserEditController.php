<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class UserEditController extends Controller
{
    /**
     * Mostra o formulário para editar o número de celular
     */
    public function editPhone()
    {
        return view('user.edit_phone');
    }

    /**
     * Atualiza o número de celular do usuário
     */
    public function updatePhone(Request $request)
    {
        // Validação do telefone no formato brasileiro
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|min:8|max:20',
        ], [
            'phone.required' => 'O telefone é obrigatório.',
            'phone.min' => 'O telefone deve ter pelo menos 8 caracteres.',
            'phone.max' => 'O telefone não pode ter mais de 20 caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('phone')
            ]);
        }

        try {
            $user = Auth::user();
            $phone = $request->input('phone');
            
            // Limpar todos os caracteres não numéricos
            $phoneNumbers = preg_replace('/[^0-9]/', '', $phone);
            
            // Remover o 55 do início se estiver presente
            if (str_starts_with($phoneNumbers, '55')) {
                $phoneNumbers = substr($phoneNumbers, 2);
            }
            
            // Garantir que o número tenha pelo menos 10 dígitos (DDD + número)
            if (strlen($phoneNumbers) < 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'O número de telefone não está completo. Por favor, inclua o DDD.'
                ]);
            }
            
            // Salvar apenas o número sem o código do país
            $user->phone = $phoneNumbers;
            
            // Verificar se a coluna phone_verified_at existe antes de tentar atualizá-la
            if (Schema::hasColumn('users', 'phone_verified_at')) {
                $user->phone_verified_at = null;
            }
            
            $user->save();
            
            // Formatar para exibição com o 55 (país)
            $ddd = substr($phoneNumbers, 0, 2);
            $part1 = substr($phoneNumbers, 2, -4);
            $part2 = substr($phoneNumbers, -4);
            $formattedPhone = "+55 ({$ddd}) {$part1}-{$part2}";
            
            return response()->json([
                'success' => true,
                'formatted_phone' => $formattedPhone,
                'message' => 'Telefone atualizado com sucesso.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar o telefone: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Verifica o código enviado para validar o número de telefone
     */
    public function verifyPhone(Request $request)
    {
        // Implementar verificação do código aqui
        // ...

        return redirect()->route('user.account')
            ->with('success', 'Número de telefone verificado com sucesso.');
    }

    /**
     * Atualiza o email do usuário
     */
    public function updateEmail(Request $request)
    {
        // Validação do email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ], [
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Por favor, forneça um endereço de email válido.',
            'email.unique' => 'Este email já está em uso.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('email')
            ]);
        }

        try {
            $user = Auth::user();
            $oldEmail = $user->email;
            $newEmail = $request->input('email');
            
            // Se o email não mudou, retorna sucesso sem fazer alterações
            if ($oldEmail === $newEmail) {
                return response()->json([
                    'success' => true,
                    'email' => $newEmail,
                    'message' => 'Email atualizado com sucesso.'
                ]);
            }
            
            // Atualiza o email sem mexer no email_verified_at
            $user->email = $newEmail;
            
            // Verificar se a coluna email_verified_at existe antes de tentar atualizá-la
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $user->email_verified_at = null;
            }
            
            $user->save();
            
            return response()->json([
                'success' => true,
                'email' => $newEmail,
                'message' => 'Email atualizado com sucesso.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar o email: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Carrega a view de segurança para o usuário
     *
     * @return \Illuminate\Http\Response
     */
    public function security()
    {
        return view('user.security');
    }

    /**
     * Processa a alteração de senha
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/|confirmed',
        ], [
            'currentPassword.required' => 'A senha atual é obrigatória',
            'newPassword.required' => 'A nova senha é obrigatória',
            'newPassword.min' => 'A nova senha deve ter pelo menos 8 caracteres',
            'newPassword.regex' => 'A senha deve conter pelo menos uma letra, um número e um caractere especial',
            'newPassword.confirmed' => 'A confirmação da senha não corresponde à nova senha',
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->currentPassword, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'A senha atual está incorreta'
            ], 400);
        }
        
        $user->password = Hash::make($request->newPassword);
        $user->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Senha atualizada com sucesso'
            ]);
        }
        
        return redirect()->back()->with('success', 'Senha atualizada com sucesso');
    }

    /**
     * Atualiza o endereço do usuário
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cep' => 'required|string|min:8|max:9',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'estado' => 'required|string|size:2',
            'complemento' => 'nullable|string|max:100',
        ], [
            'cep.required' => 'O CEP é obrigatório',
            'logradouro.required' => 'O logradouro é obrigatório',
            'numero.required' => 'O número é obrigatório',
            'bairro.required' => 'O bairro é obrigatório',
            'cidade.required' => 'A cidade é obrigatória',
            'estado.required' => 'O estado é obrigatório',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        
        try {
            $user = Auth::user();
            
            // Verificar se o usuário já tem um endereço
            $address = $user->address;
            
            // Se não tiver, criar um novo
            if (!$address) {
                $address = new \App\Models\UserAddress();
                $address->user_id = $user->id;
            }
            
            // Atualizar os campos do endereço
            $address->cep = preg_replace('/[^0-9]/', '', $request->cep);
            $address->logradouro = $request->logradouro;
            $address->numero = $request->numero;
            $address->complemento = $request->complemento;
            $address->bairro = $request->bairro;
            $address->cidade = $request->cidade;
            $address->estado = $request->estado;
            $address->save();
            
            // Formatar o endereço para exibição
            $formattedAddress = $address->logradouro . ", " . $address->numero;
            if (!empty($address->complemento)) {
                $formattedAddress .= ", " . $address->complemento;
            }
            
            $formattedAddress .= " - " . $address->bairro . ", " . $address->cidade . "/" . $address->estado;
            $formattedAddress .= ", CEP: " . preg_replace('/(\d{5})(\d{3})/', '$1-$2', $address->cep);
            
            // Um endereço é considerado completo se tiver todos os campos obrigatórios,
            // independentemente de ter complemento ou não
            $address_complete = 
                !empty($address->cep) &&
                !empty($address->logradouro) &&
                !empty($address->numero) &&
                !empty($address->bairro) &&
                !empty($address->cidade) &&
                !empty($address->estado);
            
            return response()->json([
                'success' => true,
                'message' => 'Endereço atualizado com sucesso!',
                'formatted_address' => $formattedAddress,
                'address_complete' => $address_complete
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar o endereço: ' . $e->getMessage()
            ]);
        }
    }
} 