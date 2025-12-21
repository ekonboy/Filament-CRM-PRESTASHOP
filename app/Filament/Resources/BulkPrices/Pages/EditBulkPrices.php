<?php

namespace App\Filament\Resources\BulkPrices\Pages;

use App\Filament\Resources\BulkPrices\BulkPricesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBulkPrices extends EditRecord
{
    protected static string $resource = BulkPricesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
