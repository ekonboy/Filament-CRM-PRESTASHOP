<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderState extends Model
{
    protected $table = 'soft_order_state_lang';
    protected $primaryKey = 'id_order_state';
    public $timestamps = false;

    protected $fillable = [
        'id_order_state',
        'id_lang',
        'name',
        'template',
    ];
}
