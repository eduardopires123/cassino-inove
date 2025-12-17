<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionCompletion extends Model
{
    use HasFactory;

    protected $table = 'mission_completions';
    
    protected $fillable = [
        'user_id',
        'mission_id',
        'completed_at',
        'reward_claimed',
        'claimed_at',
    ];

    protected $dates = [
        'completed_at',
        'claimed_at',
    ];

    protected $casts = [
        'reward_claimed' => 'boolean',
    ];

    // Relação com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relação com a missão
    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }
} 