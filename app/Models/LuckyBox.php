<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LuckyBox extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'level',
        'description',
        'price',
        'is_active',
        'is_mysterious',
        'daily_limit',
        'order',
        'max_prize',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'integer',
        'level' => 'integer',
        'is_active' => 'boolean',
        'is_mysterious' => 'boolean',
        'daily_limit' => 'integer',
        'order' => 'integer',
        'max_prize' => 'decimal:2',
    ];

    /**
     * Get the prize options for this lucky box.
     */
    public function prizeOptions()
    {
        return $this->hasMany(LuckyBoxPrizeOption::class);
    }

    /**
     * Get the purchases associated with this box.
     */
    public function purchases()
    {
        return $this->hasMany(LuckyBoxPurchase::class, 'level', 'level');
    }
} 