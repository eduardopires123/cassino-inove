<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuckyBoxPrizeOption extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lucky_box_id',
        'prize_type',
        'min_amount',
        'max_amount',
        'min_spins',
        'max_spins',
        'chance_percentage',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'lucky_box_id' => 'integer',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'min_spins' => 'integer',
        'max_spins' => 'integer',
        'chance_percentage' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_active' => true
    ];

    /**
     * Get the lucky box that owns this prize option.
     */
    public function luckyBox()
    {
        return $this->belongsTo(LuckyBox::class);
    }
} 