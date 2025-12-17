<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'description',
        'type',
        'amount',
        'rollover_multiplier',
        'valid_from',
        'valid_until',
        'max_usages',
        'used_count',
        'is_active',
        'is_deleted'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'rollover_multiplier' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'max_usages' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the redemptions for the coupon.
     */
    public function redemptions()
    {
        return $this->hasMany(CouponRedemption::class);
    }

    /**
     * Check if the coupon is valid.
     */
    public function isValid()
    {
        $now = now();
        
        if (!$this->is_active || $this->is_deleted) {
            return false;
        }

        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        if ($this->max_usages > 0 && $this->used_count >= $this->max_usages) {
            return false;
        }

        return true;
    }

    /**
     * Check if a user has already redeemed this coupon.
     */
    public function hasBeenRedeemedByUser($userId)
    {
        return $this->redemptions()->where('user_id', $userId)->exists();
    }
}
