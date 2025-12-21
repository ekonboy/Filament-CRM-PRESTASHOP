<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryLang extends Model
{
    protected $table;
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = env('DB_PREFIX', 'soft_') . 'category_lang';
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id_category',
        'id_lang',
        'name',
        'link_rewrite',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }
}
