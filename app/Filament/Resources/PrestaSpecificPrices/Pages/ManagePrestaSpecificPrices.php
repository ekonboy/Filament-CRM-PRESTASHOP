<?php

namespace App\Filament\Resources\PrestaSpecificPrices\Pages;

use App\Filament\Resources\PrestaSpecificPrices\PrestaSpecificPriceResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ManageRecords;

class ManagePrestaSpecificPrices extends ManageRecords
{
    protected static string $resource = PrestaSpecificPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
