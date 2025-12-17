<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Relação com os posts
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Retorna apenas posts publicados desta categoria
     */
    public function publishedPosts()
    {
        return $this->posts()->where('status', 'published');
    }
}
