<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * As URIs que devem ser acessíveis enquanto o modo de manutenção está ativo.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
} 