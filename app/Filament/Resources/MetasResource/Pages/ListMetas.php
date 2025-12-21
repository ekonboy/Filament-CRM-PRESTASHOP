<?php

namespace App\Filament\Resources\MetasResource\Pages;

use App\Filament\Resources\MetasResource;
use Filament\Resources\Pages\ListRecords;

class ListMetas extends ListRecords
{
    protected static string $resource = MetasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
