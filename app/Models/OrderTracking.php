<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    protected $table = 'soft_order_trackings'; // si quieres tu prefijo soft_

    protected $fillable = [
        'id_order',
        'reference',
        'customer_name',
        'country',
        'tracking_code',
        'status', // enviado / pendiente
        'sent_at',
    ];
}
