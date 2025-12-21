<?php

namespace App\Filament\Resources\Groups\Pages;

use App\Filament\Resources\Groups\GroupResource;
use App\Models\Group;
use App\Models\GroupLang;
use App\Models\GroupReduction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGroup extends EditRecord
{
    protected static string $resource = GroupResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['date_upd'] = now();

        return $data;
    }

    protected function afterSave(): void
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

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
