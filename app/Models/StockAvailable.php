<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAvailable extends Model
{
    protected $table;
    protected $primaryKey = 'id_product';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = env('DB_PREFIX', 'soft_') . 'stock_available';
        parent::__construct($attributes);
    }
}
