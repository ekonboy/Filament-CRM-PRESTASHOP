<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceChangeLog extends Model
{
    protected $table = 'price_change_logs';

    protected $fillable = [
        'batch_id',
        'id_product',
        'old_price',
        'new_price',
        'discount_percent',
        'group_ids',
        'status',
        'user_id',
    ];

    protected $casts = [
        'group_ids' => 'array',
        'old_price' => 'decimal:6',
        'new_price' => 'decimal:6',
        'discount_percent' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
