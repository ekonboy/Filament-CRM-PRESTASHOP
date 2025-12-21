<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructuredData extends Model
{
    protected $table = 'soft_structured_data'; // tu tabla con prefijo soft_
    protected $primaryKey = 'id'; // la clave primaria

    protected $fillable = [
        'product_id',
        'lang_id',
        'json_ld',
        'reference',
        'name',
        'image',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }
}
