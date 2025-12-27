<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table;
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->table = env('DB_PREFIX', 'soft_') . 'tag';
        parent::__construct($attributes);
    }

    protected $fillable = [
        'id_lang',
        'name',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'id_lang', 'id_lang');
    }

    // Scope para obtener tags por idioma
    public function scopeForLanguage($query, $languageId)
    {
        return $query->where('id_lang', $languageId);
    }

    // Scope para buscar tags por nombre
    public function scopeByName($query, $name)
    {
        return $query->where('name', 'like', "%{$name}%");
    }
}
