<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ApiCpf extends Model
{
    protected $table = 'api_cpf';

    protected $fillable = [
        'id',
        'nome',
        'cpf',
        'nasc',
    ];
}