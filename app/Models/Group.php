<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model
{
    protected $table = 'soft_group';
    protected $primaryKey = 'id_group';
    public $timestamps = false;

    protected $fillable = [
        'reduction',
        'price_display_method',
        'show_prices',
        'date_add',
        'date_upd',
    ];

    public function langs(): HasMany
    {
        return $this->hasMany(GroupLang::class, 'id_group', 'id_group');
    }

    public function langEs(): HasOne
    {
        return $this->hasOne(GroupLang::class, 'id_group', 'id_group')
            ->where('id_lang', 3);
    }

    public function reductions(): HasMany
    {
        return $this->hasMany(GroupReduction::class, 'id_group', 'id_group');
    }
}
