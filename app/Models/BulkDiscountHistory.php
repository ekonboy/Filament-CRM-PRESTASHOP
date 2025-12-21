<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkDiscountHistory extends Model
{
    protected $table = 'bulk_discount_history';
    
    protected $fillable = [
        'batch_id',
        'group_ids',
        'discount_percent',
        'products_affected',
        'user_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'group_ids' => 'array',
        'discount_percent' => 'decimal:2',
        'products_affected' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getGroupsNamesAttribute(): string
    {
        if (empty($this->group_ids)) {
            return 'Ningún grupo';
        }

        $groups = Group::whereIn('id_group', $this->group_ids)
            ->with('langEs')
            ->get()
            ->map(fn (Group $group) => $group->langEs?->name ?? "Grupo #{$group->id_group}")
            ->implode(', ');

        return $groups ?: 'Grupos desconocidos';
    }
}
