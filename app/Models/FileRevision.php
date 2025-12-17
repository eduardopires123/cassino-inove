<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileRevision extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'file_path',
        'version',
        'revision',
        'hash',
        'size',
        'modified_at',
    ];
    
    protected $casts = [
        'size' => 'integer',
        'revision' => 'integer',
        'modified_at' => 'datetime',
    ];
    
    public function fileVersion()
    {
        return $this->belongsTo(FileVersion::class, 'version', 'version');
    }
}