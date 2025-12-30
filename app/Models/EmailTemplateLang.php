<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplateLang extends Model
{
    protected $table = 'soft_email_template_lang';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_template',
        'id_lang',
        'subject',
        'html_content',
    ];
}
