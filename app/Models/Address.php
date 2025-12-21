<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $table = 'soft_address'; // tu tabla real
    protected $primaryKey = 'id_address';
    public $timestamps = false;

    protected $fillable = [
        'id_customer',
        'firstname',
        'lastname',
        'address1',
        'postcode',
        'city',
        'id_country',
        // añade los campos que necesites
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'id_country', 'id_country');
    }
}
