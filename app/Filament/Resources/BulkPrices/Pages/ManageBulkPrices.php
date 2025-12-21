<?php

namespace App\Filament\Resources\BulkPrices\Pages;

use App\Filament\Resources\BulkPrices\BulkPricesResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions;

class ManageBulkPrices extends ManageRecords
{
    protected static string $resource = BulkPricesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...BulkPricesResource::getHeaderActions(),
        ];
    }
}
