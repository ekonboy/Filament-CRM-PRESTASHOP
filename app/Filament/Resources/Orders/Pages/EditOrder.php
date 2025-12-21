<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\OrderTracking;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterSave(): void
    {
        $state = $this->form->getState();
        $trackingCode = $state['tracking_code'] ?? null;

        if (blank($trackingCode)) {
            return;
        }

        OrderTracking::query()->updateOrCreate(
            ['id_order' => $this->record->getKey()],
            [
                'tracking_code' => $trackingCode,
                'reference' => $this->record->reference,
                'customer_name' => trim(($this->record->customer?->firstname ?? $this->record->firstname ?? '') . ' ' . ($this->record->customer?->lastname ?? $this->record->lastname ?? '')),
                'country' => $this->record->deliveryAddress?->country?->langEs?->name ?: ($this->record->country_delivery ?? ''),
            ],
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
