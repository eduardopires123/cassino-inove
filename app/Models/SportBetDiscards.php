<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportBetDiscards extends Model
{
    protected $table = 'SportBetDiscards';

    protected $fillable = [
        'transaction_id',
        'reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}