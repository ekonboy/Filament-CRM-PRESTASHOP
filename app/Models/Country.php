<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Country extends Model
{
    protected $table = 'soft_country';
    protected $primaryKey = 'id_country';
    public $timestamps = false;

    public function langs(): HasMany
    {
        return $this->hasMany(CountryLang::class, 'id_country', 'id_country');
    }

    public function langEs(): HasOne
    {
        return $this->hasOne(CountryLang::class, 'id_country', 'id_country')
            ->where('id_lang', 3);
    }
}
