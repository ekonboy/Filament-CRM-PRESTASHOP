<?php

namespace App\Filament\Resources\BulkPrices\Pages;

use App\Filament\Resources\BulkPrices\BulkPricesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBulkPrices extends ListRecords
{
    protected static string $resource = BulkPricesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
