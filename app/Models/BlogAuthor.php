<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogAuthor extends Model
{
    protected $fillable = [
        'name',
        'role',
        'avatar',
    ];

    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'blog_author_id');
    }
}
