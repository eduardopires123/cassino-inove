<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'version',
        'release_notes',
        'file_path',
        'file_size',
        'update_type',
        'base_version',
        'metadata',
        'status',
    ];
    
    protected $casts = [
        'metadata' => 'array',
    ];
}