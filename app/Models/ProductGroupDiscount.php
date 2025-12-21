<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGroupDiscount extends Model
{
    protected $table = 'product_group_discounts';
    
    protected $fillable = [
        'id',
        'id_product',
        'id_group',
        'discount_percentage',
    ];
    
    protected $casts = [
        'discount_percentage' => 'decimal:2',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (ProductGroupDiscount::max('id') ?? 0) + 1;
            }
        });
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id_product');
    }
    
    public function group()
    {
        return $this->belongsTo(Group::class, 'id_group', 'id_group');
    }
}
