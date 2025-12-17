<?php
// app/Models/Post.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'image',
        'status',
        'featured',
        'views',
        'category_id',
        'user_id',
    ];

    /**
     * Relação com a categoria
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relação com o autor
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relação com os comentários
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relação com as tags
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    /**
     * Escopo para posts publicados
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Escopo para posts em destaque
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Retorna a URL da imagem
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Se não é uma URL completa, usar asset() para gerar a URL da pasta public
            if (!str_starts_with($this->image, 'http')) {
                return asset($this->image);
            }
            return $this->image;
        }
        
        return asset('images/blog/default-post.jpg');
    }

    /**
     * Retorna a data formatada
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    /**
     * Retorna o tempo de leitura estimado
     */
    public function getReadTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200); // 200 palavras por minuto
        
        return $minutes;
    }
}