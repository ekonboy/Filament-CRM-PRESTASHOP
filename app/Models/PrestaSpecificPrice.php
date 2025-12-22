<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PrestaSpecificPrice extends Model
{
    protected $table = 'soft_specific_price';
    protected $primaryKey = 'id_specific_price';
    public $timestamps = false; // PrestaShop usa su propio formato

    public $incrementing = true; // Asegúrate de que Laravel sepa que la PK sube sola 
    protected $keyType = 'int';

    protected $fillable = [
        'id_specific_price_rule',
        'id_cart',
        'id_group',
        'id_product',
        'id_shop',
        'id_shop_group',
        'id_currency',
        'id_country',
        'id_customer',
        'id_product_attribute',
        'price',
        'from_quantity',
        'reduction',
        'reduction_tax',
        'reduction_type',
        'from',
        'to'
    ];

    protected $casts = [
        'id_group' => 'integer',
        'id_product' => 'integer',
        'reduction' => 'float',
    ];

    public function customText(): HasOne
    {
        return $this->hasOne(CustomPromoText::class, 'id_specific_price', 'id_specific_price');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            // Forzamos que id_product sea 0 si no viene definido
            $model->id_product = 0;
            $model->id_shop = 1;
            $model->reduction_type = 'percentage';
            
            // Agregamos valores por defecto para campos requeridos
            $model->id_specific_price_rule = 0;
            $model->id_cart = 0;
            $model->id_shop_group = 0;
            $model->id_currency = 0;
            $model->id_country = 0;
            $model->id_customer = 0;
            $model->id_product_attribute = 0;
            $model->price = -1.000000;
            $model->from_quantity = 1;
            $model->reduction_tax = 1;
        });
    }
}
