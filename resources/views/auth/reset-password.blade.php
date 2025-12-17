@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-gray-800 rounded-lg p-6">
        <h2 class="text-2xl font-bold text-white mb-6">Redefinir Senha aaaaa</h2>
        
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
                <label for="email" class="block text-gray-300 text-sm font-medium mb-2">E-mail</label>
                <input id="email" type="email" class="w-full bg-gray-700 text-white border border-gray-600 rounded py-2 px-3 @error('email') border-red-500 @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>
                
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-300 text-sm font-medium mb-2">Nova Senha</label>
                <input id="password" type="password" class="w-full bg-gray-700 text-white border border-gray-600 rounded py-2 px-3 @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">
                
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password-confirm" class="block text-gray-300 text-sm font-medium mb-2">Confirmar Nova Senha</label>
                <input id="password-confirm" type="password" class="w-full bg-gray-700 text-white border border-gray-600 rounded py-2 px-3" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                    Redefinir Senha
                </button>
                
                <a href="{{ route('login') }}" class="text-gray-400 hover:text-gray-300 text-sm">
                    Voltar para login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection