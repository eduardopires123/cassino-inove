<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SqlScript extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'filename',
        'description',
        'min_version',
        'execution_order',
        'content',
        'is_mandatory',
    ];
    
    protected $casts = [
        'is_mandatory' => 'boolean',
        'execution_order' => 'integer',
    ];
}