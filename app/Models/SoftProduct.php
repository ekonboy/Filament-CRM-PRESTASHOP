<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SoftProductLang;
use App\Models\StockAvailable;

class SoftProduct extends Model
{
    protected $table;
    protected $primaryKey = 'id_product';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = env('DB_PREFIX', 'soft_') . 'product';
        parent::__construct($attributes);
    }

    // Relación con la tabla de idiomas
    public function lang()
    {
        return $this->hasOne(SoftProductLang::class, 'id_product', 'id_product')
                    ->where('id_lang', 1);
    }

    // Relación con la tabla de stock
    public function stockAvailable()
    {
        return $this->hasOne(StockAvailable::class, 'id_product', 'id_product');
    }
}
