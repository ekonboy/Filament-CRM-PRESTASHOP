<?php

namespace App\Filament\Resources\OrderTrackings\Pages;

use App\Filament\Resources\OrderTrackings\OrderTrackingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrderTrackings extends ListRecords
{
    protected static string $resource = OrderTrackingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
