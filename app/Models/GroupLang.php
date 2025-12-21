<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupLang extends Model
{
    protected $table = 'soft_group_lang';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_group',
        'id_lang',
        'name',
    ];
}
