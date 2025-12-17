<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportBetParents extends Model
{
    protected $table = 'SportBetParents';

    protected $fillable = [
        'tId',
        'transaction_id',
        'parent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}