<?php

namespace App\Filament\Resources\MetasResource\Pages;

use App\Filament\Resources\MetasResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class EditMeta extends EditRecord
{
    protected static string $resource = MetasResource::class;

    protected function resolveRecord(int | string $key): Model
    {
        $parts = explode('-', $key);
        return static::getResource()::getModel()::where([
            'id_product' => $parts[0],
            'id_shop'    => $parts[1],
            'id_lang'    => $parts[2],
        ])->firstOrFail();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        // Cargar tags reales de PrestaShop
        $data['tags'] = DB::table('soft_product_tag')
            ->join('soft_tag', 'soft_product_tag.id_tag', '=', 'soft_tag.id_tag')
            ->where('soft_product_tag.id_product', $record->id_product)
            ->where('soft_tag.id_lang', $record->id_lang)
            ->pluck('soft_tag.name')
            ->toArray();

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // 1. Extraemos los tags
        $tags = $data['tags'] ?? [];

        // 2. IMPORTANTE: Quitamos los campos de la clave primaria del set de datos
        // Esto evita que SQL intente sobrescribirlos y cause el error de duplicado
        unset($data['tags']);
        unset($data['id_product']);
        unset($data['id_lang']);
        unset($data['id_shop']);

        // 3. Actualizamos la tabla usando los valores originales del $record
        DB::table('soft_product_lang')
            ->where('id_product', $record->id_product)
            ->where('id_shop', $record->id_shop)
            ->where('id_lang', $record->id_lang)
            ->update($data);

        // 4. Sincronizamos los Tags
        $this->syncPrestaShopTags($record->id_product, $record->id_lang, $tags);

        // Usamos fill para que el objeto en memoria sepa los cambios,
        // pero sin intentar guardar de nuevo
        $record->fill($data);

        return $record;
    }

    private function syncPrestaShopTags(int $productId, int $languageId, array $tags): void
    {
        // Borrar asociaciones actuales del producto en este idioma
        DB::table('soft_product_tag')
            ->where('id_product', $productId)
            ->where('id_lang', $languageId)
            ->delete();

        foreach ($tags as $tagName) {
            $tagName = substr(trim($tagName), 0, 32);
            if (empty($tagName)) continue;

            // Asegurar que el tag existe en soft_tag
            // Nota: En PrestaShop id_lang es parte de la identidad del tag
            $tag = DB::table('soft_tag')
                ->where('name', $tagName)
                ->where('id_lang', $languageId)
                ->first();

            if (!$tag) {
                $tagId = DB::table('soft_tag')->insertGetId([
                    'id_lang' => $languageId,
                    'name'    => $tagName,
                ]);
            } else {
                $tagId = $tag->id_tag;
            }

            // Insertar asociación
            DB::table('soft_product_tag')->insert([
                'id_product' => $productId,
                'id_tag'     => $tagId,
                'id_lang'    => $languageId,
            ]);
        }
    }
}
