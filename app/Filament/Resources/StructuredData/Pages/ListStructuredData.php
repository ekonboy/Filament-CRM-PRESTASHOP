<?php

namespace App\Filament\Resources\StructuredData\Pages;

use App\Filament\Resources\StructuredData\StructuredDataResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStructuredData extends ListRecords
{
    protected static string $resource = StructuredDataResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
