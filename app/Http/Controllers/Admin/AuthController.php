<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->is_admin > 0) {
            return redirect()->route('admin.dash');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            if (in_array(Auth::user()->is_admin, [1, 2, 3])) {
                return response()->json(['status' => true]);
            }
        }

        throw ValidationException::withMessages([
            'email' => ['Credenciais inválidas ou você não tem permissão de administrador.'],
        ]);
    }

    public function checkAuth()
    {
        if (Auth::check() && Auth::user()->is_admin > 0) {
            return response()->json(['isAdmin' => true]);
        }

        return response()->json(['isAdmin' => false]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    /**
     * Exibe a página de perfil do administrador
     */
    public function profile()
    {
        $user = Auth::user();

        // Certifique-se de carregar a carteira do usuário
        $user->load('wallet');

        return view('admin.auth.profile', compact('user'));
    }

    /**
     * Atualiza os dados do perfil do administrador
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validar os dados recebidos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string',
            'nascimento' => 'nullable|date',
            'cpf' => 'nullable|string|max:14',
            'pix' => 'nullable|string',
        ]);

        // Atualizar os dados do usuário
        $user->update($validated);

        return redirect()->route('admin.profile')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Updates the user's password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validate form data
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Update the password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Senha atualizada com sucesso!');
    }
}
