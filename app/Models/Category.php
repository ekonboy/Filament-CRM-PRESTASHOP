<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table;
    protected $primaryKey = 'id_category';
    public $timestamps = false; // PrestaShop no usa created_at/updated_at

    public function __construct(array $attributes = [])
    {
        $this->table = env('DB_PREFIX', 'soft_') . 'category';
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id_parent',
        'active',
        'position',
        'is_root_category',
        'id_shop_default'
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryLang::class, 'id_category', 'id_category')
            ->where('id_lang', 3);
    }

    public function getNameAttribute()
    {
        return $this->translations()->first()?->name ?? 'Sin nombre';
    }
}
