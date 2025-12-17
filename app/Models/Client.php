<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'site_key',
        'domain',
        'current_version',
        'last_check',
        'last_update',
        'subscription_status',
        'subscription_expires_at',
        'current_schema',
    ];
    
    protected $casts = [
        'last_check' => 'datetime',
        'last_update' => 'datetime',
        'subscription_expires_at' => 'datetime',
        'current_schema' => 'array',
    ];
    
    public function subscriptionActive()
    {
        return $this->subscription_status === 'active' && 
               ($this->subscription_expires_at === null || $this->subscription_expires_at->isFuture());
    }
    
    public function updates()
    {
        return $this->hasMany(ClientUpdate::class);
    }
}