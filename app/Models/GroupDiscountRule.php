<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupDiscountRule extends Model
{
    protected $fillable = [
        'id_group',
        'group_name',
        'discount_percentage',
        'status',
        'user_id',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'status' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'id_group', 'id_group');
    }

    // Scope para obtener reglas activas
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Obtener nombre del grupo con cache
    public function getGroupNameAttribute(): string
    {
        if ($this->attributes['group_name'] ?? null) {
            return $this->attributes['group_name'];
        }

        // Buscar en soft_group_lang con id_lang = 3 (español)
        $groupLang = \DB::table('soft_group_lang')
            ->where('id_group', $this->id_group)
            ->where('id_lang', 3) // Idioma español
            ->first();

        // Si no encuentra en español, probar inglés
        if (!$groupLang) {
            $groupLang = \DB::table('soft_group_lang')
                ->where('id_group', $this->id_group)
                ->where('id_lang', 1) // Inglés
                ->first();
        }

        if ($groupLang && !empty($groupLang->name)) {
            // Actualizar cache
            $this->update(['group_name' => $groupLang->name]);
            return $groupLang->name;
        }

        return "Grupo #{$this->id_group}";
    }

    // Aplicar descuento a precio
    public function applyDiscount(float $originalPrice): float
    {
        if ($this->status !== 'active') {
            return $originalPrice;
        }

        return round($originalPrice * (1 - ($this->discount_percentage / 100)), 6);
    }
}
