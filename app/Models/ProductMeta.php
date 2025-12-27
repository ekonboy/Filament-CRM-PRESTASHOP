<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model
{
    protected $table;
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = env('DB_PREFIX', 'soft_') . 'product_lang';
        parent::__construct($attributes);
    }

    public function getKeyName()
    {
        return null; // No primary key
    }

    public function getKey()
    {
        return null; // No key value
    }

    protected $fillable = [
        'id_product',
        'id_shop',
        'id_lang',
        'name',
        'description',
        'description_short',
        'link_rewrite',
        'meta_description',
        'meta_keywords',
        'meta_title',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'id_lang', 'id_lang');
    }

    // Scope para obtener por idioma
    public function scopeForLanguage($query, $languageId)
    {
        return $query->where('id_lang', $languageId);
    }

    // Scope para obtener por producto
    public function scopeForProduct($query, $productId)
    {
        return $query->where('id_product', $productId);
    }
}
