<?php

namespace App\Filament\Resources\OrderTrackings\Pages;

use App\Filament\Resources\OrderTrackings\OrderTrackingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrderTracking extends EditRecord
{
    protected static string $resource = OrderTrackingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
