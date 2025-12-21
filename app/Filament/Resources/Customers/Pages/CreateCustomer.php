<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\Customer;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function afterCreate(): void
    {
        /** @var Customer $customer */
        $customer = $this->record;
        $groupId = (int) ($customer->id_default_group ?? 0);

        $state = $this->form->getState();
        $groupIds = array_values(array_unique(array_map('intval', $state['group_ids'] ?? [])));

        if ($groupId > 0 && ! in_array($groupId, $groupIds, true)) {
            $groupIds[] = $groupId;
        }

        if (count($groupIds) > 0) {
            $customer->groups()->sync($groupIds);
        }
    }
}
