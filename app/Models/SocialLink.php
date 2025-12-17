<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'social_links';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'instagram',
        'facebook',
        'telegram',
        'whatsapp',
        'show_instagram',
        'show_facebook',
        'show_telegram',
        'show_whatsapp',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
        'show_instagram' => 'boolean',
        'show_facebook' => 'boolean',
        'show_telegram' => 'boolean',
        'show_whatsapp' => 'boolean',
    ];
}
