<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BingoCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bingo_game_id',
        'price',
        'numbers',
        'line1_completed',
        'line2_completed',
        'line3_completed',
        'prize_won',
        'prize_claimed',
    ];

    protected $casts = [
        'numbers' => 'array',
        'price' => 'decimal:2',
        'prize_won' => 'decimal:2',
        'line1_completed' => 'boolean',
        'line2_completed' => 'boolean',
        'line3_completed' => 'boolean',
        'prize_claimed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(BingoGame::class, 'bingo_game_id');
    }

    // Retorna os números da primeira linha
    public function getLine1Attribute()
    {
        return array_slice($this->numbers, 0, 5);
    }

    // Retorna os números da segunda linha
    public function getLine2Attribute()
    {
        return array_slice($this->numbers, 5, 5);
    }

    // Retorna os números da terceira linha
    public function getLine3Attribute()
    {
        return array_slice($this->numbers, 10, 5);
    }

    // Verifica se uma linha está completa baseada nos números sorteados
    public function checkLineCompletion($lineNumbers, $drawnNumbers)
    {
        return empty(array_diff($lineNumbers, $drawnNumbers));
    }

    // Verifica o status de vitória baseado nos números sorteados
    public function checkWinStatus($drawnNumbers)
    {
        $line1Complete = $this->checkLineCompletion($this->line1, $drawnNumbers);
        $line2Complete = $this->checkLineCompletion($this->line2, $drawnNumbers);
        $line3Complete = $this->checkLineCompletion($this->line3, $drawnNumbers);
        
        $this->line1_completed = $line1Complete;
        $this->line2_completed = $line2Complete;
        $this->line3_completed = $line3Complete;
        
        $this->save();
        
        return [
            'line1' => $line1Complete,
            'line2' => $line2Complete,
            'line3' => $line3Complete,
            'all_completed' => $line1Complete && $line2Complete && $line3Complete,
        ];
    }
} 