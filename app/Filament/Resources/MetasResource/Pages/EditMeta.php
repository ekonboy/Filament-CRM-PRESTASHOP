<?php

namespace App\Filament\Resources\MetasResource\Pages;

use App\Filament\Resources\MetasResource;
use Filament\Resources\Pages\EditRecord;

class EditMeta extends EditRecord
{
    protected static string $resource = MetasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
