<?php

namespace App\Filament\Resources\ProductSeoResource\Pages;

use App\Filament\Resources\ProductSeoResource;
use App\Models\ProductLang;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditProductSeo extends EditRecord
{
    protected static string $resource = ProductSeoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Cargar todas las traducciones del producto con la relación language
        $this->record->load(['langs.language']);
        
        // Convertir las traducciones a array para el Repeater
        // Solo incluir traducciones de idiomas activos
        $data['langs'] = $this->record->langs
            ->filter(function ($lang) {
                return $lang->language && $lang->language->active == 1;
            })
            ->map(function ($lang) {
                return $lang->toArray();
            })
            ->values()
            ->toArray();
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Extraer los datos de langs antes de guardar el producto
        $langsData = $data['langs'] ?? [];
        unset($data['langs']);

        $allowedLangColumns = (new ProductLang())->getFillable();
        $allowedLangColumns = array_fill_keys($allowedLangColumns, true);
        
        // Guardar o actualizar cada traducción
        foreach ($langsData as $langData) {
            // Saltar si no tiene id_lang (item eliminado)
            if (!isset($langData['id_lang'])) {
                continue;
            }

            $idShop = (int) ($langData['id_shop'] ?? 1);
            $idLang = (int) ($langData['id_lang'] ?? 0);

            if ($idLang <= 0) {
                continue;
            }

            $langData['id_product'] = (int) $this->record->id_product;
            $langData['id_shop'] = $idShop;
            $langData['id_lang'] = $idLang;

            $langData = array_intersect_key($langData, $allowedLangColumns);

            DB::table('soft_product_lang')->updateOrInsert(
                [
                    'id_product' => (int) $this->record->id_product,
                    'id_shop' => $idShop,
                    'id_lang' => $idLang,
                ],
                $langData,
            );
        }
        
        return $data;
    }
}
