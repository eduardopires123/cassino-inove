@if (Route::has('password.request'))
    <div class="mt-4 text-center">
        <a href="{{ route('password.request') }}" class="text-gray-400 hover:text-gray-300">
            Esqueceu sua senha?
        </a>
    </div>
@endif 