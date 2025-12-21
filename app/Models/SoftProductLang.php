<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoftProductLang extends Model
{
    protected $table;
    protected $primaryKey = 'id_product';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = env('DB_PREFIX', 'soft_') . 'product_lang';
        parent::__construct($attributes);
    }
}
