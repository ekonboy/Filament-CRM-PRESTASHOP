<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    protected $table = 'soft_order_detail';
    public $timestamps = false;

    protected $fillable = [
        'id_order',
        'product_name',
        'product_quantity',
        'product_price',
        'total_price',
        'reference',
        'supplier_reference',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }
}
