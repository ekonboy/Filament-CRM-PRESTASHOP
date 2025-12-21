<?php

namespace App\Filament\Resources\Groups\Pages;

use App\Filament\Resources\Groups\GroupResource;
use App\Models\Group;
use App\Models\GroupLang;
use App\Models\GroupReduction;
use Filament\Resources\Pages\CreateRecord;

class CreateGroup extends CreateRecord
{
    protected static string $resource = GroupResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['show_prices'] = $data['show_prices'] ?? 1;
        $data['price_display_method'] = $data['price_display_method'] ?? 0;
        $data['date_add'] = $data['date_add'] ?? now();
        $data['date_upd'] = $data['date_upd'] ?? now();

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Group $group */
        $group = $this->record;

        $state = $this->form->getState();
        $nameEs = (string) ($state['name_es'] ?? '');

        GroupLang::query()->updateOrCreate(
            ['id_group' => $group->id_group, 'id_lang' => 3],
            ['name' => $nameEs],
        );

        GroupReduction::query()->updateOrCreate(
            ['id_group' => $group->id_group, 'id_category' => 0],
            ['reduction' => round(((float) $group->reduction) / 100, 4)],
        );
    }
}
