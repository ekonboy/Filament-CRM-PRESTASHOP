<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'soft_email_templates';
    protected $primaryKey = 'id_email_template';
    public $timestamps = false; // or true if you add dates

    protected $fillable = [
        'name',
        'active',
    ];

    public function langs()
    {
        return $this->hasMany(EmailTemplateLang::class, 'id_template');
    }
}
