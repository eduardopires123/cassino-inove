<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebugLogs extends Model
{
    protected $table = 'debug';

    protected $fillable = [
        'user_id',
        'api',
        'text',
    ];
}
