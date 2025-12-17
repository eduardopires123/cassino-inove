<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\MaintenanceMode;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui é onde você pode registrar rotas de API para sua aplicação.
| Estas rotas são carregadas pelo RouteServiceProvider e todas elas
| serão atribuídas ao middleware "api". Faça algo incrível!
|
*/

// Adiciona o binding para MaintenanceMode
App::singleton(
    Illuminate\Contracts\Foundation\MaintenanceMode::class,
    Illuminate\Foundation\Http\MaintenanceMode::class
);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas para envio de emails via Brevo
Route::prefix('emails')->group(function () {
    Route::post('/enviar', [EmailController::class, 'enviarEmail']);
    Route::post('/enviar-template', [EmailController::class, 'enviarEmailTemplate']);
    Route::post('/boas-vindas', [EmailController::class, 'enviarEmailBoasVindas']);
});

