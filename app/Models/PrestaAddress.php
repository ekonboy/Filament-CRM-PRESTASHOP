<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrestaAddress extends Model
{
    protected $table = 'soft_address';
    protected $primaryKey = 'id_address';
    public $timestamps = false;
}
