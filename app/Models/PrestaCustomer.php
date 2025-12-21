<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrestaCustomer extends Model
{
    protected $table = 'soft_customer';
    protected $primaryKey = 'id_customer';
    public $timestamps = false;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
    ];

    public function addresses()
    {
        return $this->hasMany(PrestaAddress::class, 'id_customer');
    }

    public function orders()
    {
        return $this->hasMany(PrestaOrder::class, 'id_customer');
    }
}
