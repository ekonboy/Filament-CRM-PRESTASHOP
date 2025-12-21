<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductLang;
use App\Models\StockAvailable;
use App\Models\OrderDetail;


class Product extends Model
{
    protected $table;
    protected $primaryKey = 'id_product';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = env('DB_PREFIX', 'soft_') . 'product';
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id_supplier',
        'id_manufacturer',
        'id_category_default',
        'id_shop_default',
        'id_tax_rules_group',
        'on_sale',
        'online_only',
        'ean13',
        'isbn',
        'upc',
        'mpn',
        'ecotax',
        'quantity',
        'minimal_quantity',
        'low_stock_threshold',
        'low_stock_alert',
        'price',
        'wholesale_price',
        'unity',
        'unit_price',
        'unit_price_ratio',
        'additional_shipping_cost',
        'reference',
        'supplier_reference',
        'location',
        'width',
        'height',
        'depth',
        'weight',
        'out_of_stock',
        'additional_delivery_times',
        'quantity_discount',
        'customizable',
        'uploadable_files',
        'text_fields',
        'active',
        'redirect_type',
        'id_type_redirected',
        'available_for_order',
        'available_date',
        'show_condition',
        'condition',
        'show_price',
        'indexed',
        'visibility',
        'cache_is_pack',
        'cache_has_attachments',
        'is_virtual',
        'cache_default_attribute',
        'date_add',
        'date_upd',
        'advanced_stock_management',
        'pack_stock_type',
        'state',
        'product_type',
    ];




    // Relación con traducciones (product_lang) - múltiples idiomas
    public function langs()
    {
        return $this->hasMany(ProductLang::class, 'id_product', 'id_product');
    }

    // Relación con idioma específico (para retrocompatibilidad)
    public function lang()
    {
        return $this->hasOne(ProductLang::class, 'id_product', 'id_product')
            ->where('id_lang', 3); // Español por defecto
    }

    // Relación con stock
    public function stock()
    {
        return $this->hasOne(StockAvailable::class, 'id_product', 'id_product');
    }


    public function orderDetails()
    {
        return $this->hasMany(orderDetail::class, 'id_product', 'id_product');
    }



    // Excluir combinaciones
    public function scopeMainProducts($query)
    {
        return $query->whereNotIn('id_product', function ($q) {
            $q->select('id_product')->from('soft_product_attribute');
        });
    }


    // Product.php
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category_default', 'id_category');
    }

    public function categoryName(): string
    {
        // devuelve el nombre de la categoría para id_lang = 3
        return $this->category
            ? $this->category->translations->firstWhere('id_lang', 3)?->name ?? 'Sin categoría'
            : 'Sin categoría';
    }

    public function structuredData()
    {
        return $this->hasOne(StructuredData::class, 'product_id', 'id_product')
            ->where('lang_id', 3);
    }

    public function structuredDataRecords()
    {
        return $this->hasMany(StructuredData::class, 'product_id', 'id_product');
    }
}
