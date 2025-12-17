<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;

    protected $table = 'login_history';
    
    protected $fillable = [
        'user_id',
        'ip',
        'city',
        'state',
        'country',
        'lat',
        'lng',
        'user_agent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 