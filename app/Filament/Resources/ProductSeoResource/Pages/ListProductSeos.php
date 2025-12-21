<?php

namespace App\Filament\Resources\ProductSeoResource\Pages;

use App\Filament\Resources\ProductSeoResource;
use Filament\Resources\Pages\ListRecords;

class ListProductSeos extends ListRecords
{
    protected static string $resource = ProductSeoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
