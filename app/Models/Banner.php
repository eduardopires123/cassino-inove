<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagem',
        'link',
        'ordem',
        'active',
        'tipo',
        'mobile'
        
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
} 