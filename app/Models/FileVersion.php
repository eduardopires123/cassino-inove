<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileVersion extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'version',
        'file_path',
        'file_count',
        'checksum',
        'metadata',
    ];
    
    protected $casts = [
        'metadata' => 'array',
    ];
}