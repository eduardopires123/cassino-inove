<?php
// Configuração básica para acessar o Laravel
require_once __DIR__ . '/../../vendor/autoload.php';

// Inicializar o Laravel
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    $request = Illuminate\Http\Request::capture()
);

// Definir o cabeçalho para CSS
header('Content-Type: text/css');

// Importar o controlador que contém o método para obter as variáveis CSS
use App\Http\Controllers\Admin\CustomCSSController;

// Obter o CSS inline gerado pela mesma função usada no app.blade.php
echo CustomCSSController::getAllInlineCss(); 