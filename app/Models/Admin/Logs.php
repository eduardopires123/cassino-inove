<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'field_name',
        'old_value',
        'new_value',
        'updated_by',
        'user_id',
        'type',
        'log'
    ];
}