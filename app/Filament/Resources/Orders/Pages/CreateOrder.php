<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\OrderTracking;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
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
}
