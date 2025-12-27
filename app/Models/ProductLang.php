<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Language;


class ProductLang extends Model
{
    protected $table = 'soft_product_lang';

    // Engañamos a Eloquent dándole una de las llaves como primaria
    protected $primaryKey = 'id_product';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    // Esta es la parte mágica para PrestaShop - clave compuesta para UPDATE
    protected function setKeysForSaveQuery($query)
    {
        $query->where('id_product', $this->getAttribute('id_product'))
              ->where('id_shop', $this->getAttribute('id_shop'))
              ->where('id_lang', $this->getAttribute('id_lang'));

        return $query;
    }

    public function getRouteKeyName()
    {
        return 'id_product';
    }

    public function getKey()
    {
        return "{$this->id_product}-{$this->id_shop}-{$this->id_lang}";
    }

    public function getKeyName()
    {
        return 'id_product';
    }

    public function __construct(array $attributes = [])
    {
        $this->table = env('DB_PREFIX', 'soft_') . 'product_lang';
        parent::__construct($attributes);
    }

    // Método personalizado para buscar por clave compuesta
    public static function findByCompositeKey($idProduct, $idShop, $idLang)
    {
        return static::where('id_product', $idProduct)
            ->where('id_shop', $idShop)
            ->where('id_lang', $idLang)
            ->first();
    }

    public static function find($id, $columns = ['*'])
    {
        if (str_contains($id, '-')) {
            $parts = explode('-', $id);
            if (count($parts) === 3) {
                return static::findByCompositeKey($parts[0], $parts[1], $parts[2]);
            }
        }
        return parent::find($id, $columns);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (is_string($value) && str_contains($value, '-')) {
            $parts = explode('-', $value);
            if (count($parts) === 3) {
                return static::findByCompositeKey((int) $parts[0], (int) $parts[1], (int) $parts[2]);
            }
        }
        return static::query()->where('id_product', (int) $value)->firstOrFail();
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
        'tags',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'id_lang', 'id_lang');
    }

    // Relación para tags de PrestaShop
    public function tags_rel()
    {
        return $this->belongsToMany(
            Tag::class, 
            'soft_product_tag', // tabla intermedia
            'id_product',       // llave en intermedia que apunta a producto
            'id_tag',           // llave en intermedia que apunta a tag
            'id_product',       // llave local
            'id_tag'            // llave en tabla tags
        );
    }
}
