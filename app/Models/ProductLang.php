<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language;


class ProductLang extends Model
{
    protected $table;
    // Using id_product as primary key for Repeater compatibility
    // The actual composite key (id_product, id_shop, id_lang) is handled at DB level
    protected $primaryKey = 'id_product';
    public $incrementing = false;
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = env('DB_PREFIX', 'soft_') . 'product_lang';
        parent::__construct($attributes);
    }

    public function getKey()
    {
        $idProduct = $this->getAttribute('id_product');
        $idShop = $this->getAttribute('id_shop');
        $idLang = $this->getAttribute('id_lang');

        if ($idProduct !== null && $idShop !== null && $idLang !== null) {
            return $idProduct . '-' . $idShop . '-' . $idLang;
        }

        return parent::getKey();
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (is_string($value) && str_contains($value, '-')) {
            [$idProduct, $idShop, $idLang] = array_pad(explode('-', $value, 3), 3, null);

            if ($idProduct !== null && $idShop !== null && $idLang !== null) {
                return static::query()
                    ->where('id_product', (int) $idProduct)
                    ->where('id_shop', (int) $idShop)
                    ->where('id_lang', (int) $idLang)
                    ->firstOrFail();
            }
        }

        return parent::resolveRouteBinding($value, $field);
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
        'available_now',
        'available_later',
        'delivery_in_stock',
        'delivery_out_stock',
    ];

    // Relación con el idioma
    public function language()
    {
        return $this->belongsTo(Language::class, 'id_lang', 'id_lang');
    }
}
