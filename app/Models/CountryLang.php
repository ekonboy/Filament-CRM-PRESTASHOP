<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryLang extends Model
{
    protected $table = 'soft_country_lang';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_country',
        'id_lang',
        'name',
    ];
}
