<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupReduction extends Model
{
    protected $table = 'soft_group_reduction';
    protected $primaryKey = 'id_group_reduction';
    public $timestamps = false;

    protected $fillable = [
        'id_group',
        'id_category',
        'reduction',
    ];
}
