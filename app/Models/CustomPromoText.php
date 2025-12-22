<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPromoText extends Model
{
    protected $table = 'soft_custom_promo_text';
    protected $primaryKey = 'id_specific_price';
    public $timestamps = false;
    public $incrementing = false; // El ID lo hereda de specific_price

    protected $fillable = ['id_specific_price', 'promo_text'];

    protected static function booted()
    {
        static::creating(function ($model) {
            // Si no hay promo_text, establecer un valor por defecto
            if (empty($model->promo_text)) {
                $model->promo_text = 'Descuento especial';
            }
        });
    }

    public function specificPrice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PrestaSpecificPrice::class, 'id_specific_price', 'id_specific_price');
    }
}
