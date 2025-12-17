<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitLogs extends Model
{
    protected $table = 'visit_logs';

    protected $fillable = [
        'ip',
        'agent',
        'referer',
    ];
}