<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Address;


class Order extends Model
{
    protected $table = 'soft_orders';
    protected $primaryKey = 'id_order';
    public $incrementing = true; // si aplica
    protected $keyType = 'int';  // si es integer
    public $timestamps = false;

    protected $fillable = [
        'id_customer',
        'reference',
        'total_paid',
        'payment',
        'date_add',
        'current_state',
        'country_delivery',
        'firstname',
        'lastname',
        'email',
        'note',
        // otros campos según PrestaShop
    ];

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'id_order', 'id_order');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id_customer');

    }

    public function deliveryAddress()
    {
        return $this->belongsTo(Address::class, 'id_address_delivery', 'id_address');
    }

    public function state()
    {
        return $this->hasOne(OrderState::class, 'id_order_state', 'current_state')
            ->where('id_lang', 3); // Por defecto español, o usar lógica dinámica si es necesario
    }
}
