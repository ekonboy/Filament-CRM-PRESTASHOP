<?php

namespace App\Jobs;

use App\Models\BulkDiscountHistory;
use App\Models\GroupDiscountRule;
use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ApplyGroupDiscountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $batchId,
        public array $groupIds,
        public float $discountPercent,
        public ?int $userId = null,
    ) {
        $this->onQueue('default');
    }

    public function handle(): void
    {
        // Create history record
        $history = BulkDiscountHistory::create([
            'batch_id' => $this->batchId,
            'group_ids' => $this->groupIds,
            'discount_percent' => $this->discountPercent,
            'user_id' => $this->userId,
            'status' => 'processing',
            'notes' => 'Aplicando reglas de descuento por grupo',
        ]);

        $rulesCreated = 0;

        foreach ($this->groupIds as $groupId) {
            // Obtener nombre del grupo para cache
            $groupLang = DB::table('soft_group_lang')
                ->where('id_group', $groupId)
                ->where('id_lang', 1) // Idioma principal
                ->first();

            $groupName = $groupLang ? $groupLang->name : "Grupo #{$groupId}";

            // Crear o actualizar regla de descuento (SOLO UNA FILA POR GRUPO)
            GroupDiscountRule::updateOrCreate(
                ['id_group' => $groupId],
                [
                    'group_name' => $groupName,
                    'discount_percentage' => $this->discountPercent,
                    'status' => 'active',
                    'user_id' => $this->userId,
                ]
            );

            $rulesCreated++;
        }

        // Update history record with final status
        $history->update([
            'products_affected' => $rulesCreated, // Ahora es número de reglas
            'status' => 'completed',
            'notes' => "Reglas de descuento aplicadas a {$rulesCreated} grupos",
        ]);
    }
}
