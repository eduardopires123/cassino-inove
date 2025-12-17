@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-gray-800 rounded-lg p-6">
        <h2 class="text-2xl font-bold text-white mb-6">{{ __('auth.reset_password') }}</h2>
        
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('status') }}
            </div>
        @endif
        
        
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-300 text-sm font-medium mb-2">E-mail ou CPF</label>
                <input id="email" type="text" class="w-full bg-gray-700 text-white border border-gray-600 rounded py-2 px-3 @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                    {{ __('auth.send_reset_link') }}
                </button>
                
                <a href="{{ route('login') }}" class="text-gray-400 hover:text-gray-300 text-sm">
                    {{ __('auth.back_to_login') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection