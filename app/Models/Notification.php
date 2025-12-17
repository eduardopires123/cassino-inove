<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_ids',
        'title_en',
        'title_pt_br',
        'title_es',
        'content_en',
        'content_pt_br',
        'content_es',
        'link',
        'is_read'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'user_ids' => 'array',
    ];

    protected $appends = ['users_list'];

    /**
     * Get the title based on the current locale.
     *
     * @return string
     */
    public function getLocalizedTitleAttribute()
    {
        $locale = app()->getLocale();
        
        // Corrigir mapeamento para pt_BR
        if ($locale === 'pt_BR') {
            return $this->title_pt_br ?? $this->title_en;
        }
        
        return $this->{"title_{$locale}"} ?? $this->title_en;
    }

    /**
     * Get the content based on the current locale.
     *
     * @return string
     */
    public function getLocalizedContentAttribute()
    {
        $locale = app()->getLocale();
        
        // Corrigir mapeamento para pt_BR
        if ($locale === 'pt_BR') {
            return $this->content_pt_br ?? $this->content_en;
        }
        
        return $this->{"content_{$locale}"} ?? $this->content_en;
    }

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Retorna a lista de usuários a partir da string de IDs.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUsersListAttribute()
    {
        if (empty($this->user_ids)) {
            return collect([]);
        }
        
        // Converter a string de IDs para array
        $userIds = is_array($this->user_ids) ? $this->user_ids : explode(',', $this->user_ids);
        
        // Buscar os usuários
        return User::whereIn('id', $userIds)->get();
    }
    
    /**
     * Check if this notification was sent to all users.
     *
     * @return bool
     */
    public function isSentToAllUsers()
    {
        return $this->user_id === null && empty($this->user_ids);
    }
} 