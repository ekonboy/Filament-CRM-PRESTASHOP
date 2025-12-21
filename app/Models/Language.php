<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'soft_lang';
    protected $primaryKey = 'id_lang';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'active',
        'iso_code',
        'language_code',
        'locale',
        'date_format_lite',
        'date_format_full',
        'is_rtl',
    ];

    // Relación con product_lang
    public function productLangs()
    {
        return $this->hasMany(ProductLang::class, 'id_lang', 'id_lang');
    }
}
