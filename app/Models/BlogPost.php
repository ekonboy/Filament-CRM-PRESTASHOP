<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'blog_author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'is_visible',
        'published_at',
    ];

    public function author()
    {
        return $this->belongsTo(BlogAuthor::class, 'blog_author_id');
    }

    public function getUrlAttribute()
    {
        return url("/media/{$this->slug}");
    }

    public function getPublicUrlAttribute()
    {
        return "/media/{$this->slug}";
    }
}
