<?php

namespace App\Filament\Resources\StructuredData\Pages;

use App\Filament\Resources\StructuredData\StructuredDataResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStructuredData extends EditRecord
{
    protected static string $resource = StructuredDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
