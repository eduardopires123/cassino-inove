<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BingoGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'draw_interval',
        'prize_line1',
        'prize_line2',
        'prize_line3',
        'accumulated',
        'numbers_drawn',
        'active',
        'completed',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'prize_line1' => 'decimal:2',
        'prize_line2' => 'decimal:2',
        'prize_line3' => 'decimal:2',
        'accumulated' => 'decimal:2',
        'active' => 'boolean',
        'completed' => 'boolean',
    ];

    public function cards()
    {
        return $this->hasMany(BingoCard::class);
    }

    public function drawnNumbers()
    {
        return $this->hasMany(BingoDrawnNumber::class)->orderBy('order', 'asc');
    }

    public function getLastDrawnAttribute()
    {
        return $this->drawnNumbers()->latest('order')->first()?->number;
    }

    public function getAllDrawnNumbersAttribute()
    {
        return $this->drawnNumbers()->pluck('number')->toArray();
    }
} 