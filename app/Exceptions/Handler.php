<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Lidar com erros de CSRF (token mismatch)
        $this->renderable(function (TokenMismatchException $e, $request) {
            // Se for uma requisição AJAX, retorne JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Sua sessão expirou. Por favor, atualize a página e tente novamente.'
                ], 419);
            }

            // Se for na página de login ou registro, redirecione para login com mensagem
            if ($request->is('login') || $request->is('registre-se')) {
                return redirect()->route('login')->with('error', 'Sua sessão expirou. Por favor, tente novamente.');
            }
            
            // Para qualquer outra página, volte para a página anterior com mensagem
            return redirect()->back()->with('error', 'Sua sessão expirou. Por favor, tente novamente.');
        });

        // Lidar com erros 404 (página não encontrada)
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('admin*')) {
                // Se a URL começar com 'admin', use o template administrativo
                return response()->view('admin.partials.error404', [], 404);
            } else {
                // Para todas as outras URLs, use o template padrão
                return response()->view('errors.404', [], 404);
            }
        });
    }
}