<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Relação com os posts
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag');
    }

    /**
     * Retorna apenas posts publicados com esta tag
     */
    public function publishedPosts()
    {
        return $this->posts()->where('status', 'published');
    }
}