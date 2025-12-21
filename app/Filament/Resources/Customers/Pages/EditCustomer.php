<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\Customer;
use App\Models\Group;
use App\Models\GroupLang;
use App\Models\GroupReduction;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function afterSave(): void
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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_order')
                ->label('Ver Pedido')
                ->icon('heroicon-o-shopping-cart')
                ->color('primary')
                ->visible(function (): bool {
                    /** @var Customer $record */
                    $record = $this->record;
                    $orderCount = $record->orders()->count();
                    
                    // Mostrar solo si tiene exactamente 1 pedido
                    return $orderCount === 1;
                })
                ->url(function (): ?string {
                    /** @var Customer $record */
                    $record = $this->record;
                    $order = $record->orders()->first();
                    
                    if ($order) {
                        return '/admin/orders/' . $order->id_order . '/edit';
                    }
                    
                    return null;
                })
                ->openUrlInNewTab(),
                
            Action::make('view_latest_order')
                ->label('Ver Último Pedido')
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->visible(function (): bool {
                    /** @var Customer $record */
                    $record = $this->record;
                    $orderCount = $record->orders()->count();
                    
                    // Mostrar solo si tiene más de 1 pedido
                    return $orderCount > 1;
                })
                ->url(function (): ?string {
                    /** @var Customer $record */
                    $record = $this->record;
                    $latestOrder = $record->orders()->latest('date_add')->first();
                    
                    if ($latestOrder) {
                        return '/admin/orders/' . $latestOrder->id_order . '/edit';
                    }
                    
                    return null;
                })
                ->openUrlInNewTab(),
                
            Action::make('approve_btb')
                ->label('Aprobar BTB')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->form([
                    Select::make('btb_group_id')
                        ->label('Grupo BTB')
                        ->options(fn () => Group::query()
                            ->with(['langEs'])
                            ->orderBy('id_group')
                            ->get()
                            ->mapWithKeys(fn (Group $group) => [$group->id_group => ($group->langEs?->name ?? (string) $group->id_group)])
                            ->all())
                        ->default(fn (): ?int => Group::query()
                            ->whereHas('langEs', fn ($q) => $q->where('name', 'like', '%BTB%'))
                            ->value('id_group'))
                        ->searchable()
                        ->required(),

                    Toggle::make('create_discount_group')
                        ->label('Crear grupo con descuento para este cliente')
                        ->default(false),

                    TextInput::make('discount_percent')
                        ->label('% descuento')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(99)
                        ->visible(fn ($get): bool => (bool) $get('create_discount_group'))
                        ->required(fn ($get): bool => (bool) $get('create_discount_group')),
                ])
                ->action(function (array $data): void {
                    /** @var Customer $record */
                    $record = $this->record;

                    $btbGroupId = (int) $data['btb_group_id'];
                    $targetGroupId = $btbGroupId;

                    if (! empty($data['create_discount_group'])) {
                        $discountPercent = (float) ($data['discount_percent'] ?? 0);
                        $now = now();

                        $group = Group::query()->create([
                            'reduction' => $discountPercent,
                            'price_display_method' => 0,
                            'show_prices' => 1,
                            'date_add' => $now,
                            'date_upd' => $now,
                        ]);

                        $nameEs = trim('BTB - ' . ($record->firstname ?? '') . ' ' . ($record->lastname ?? '') . ' ' . rtrim(rtrim(number_format($discountPercent, 2, '.', ''), '0'), '.') . '%');

                        GroupLang::query()->updateOrCreate(
                            ['id_group' => $group->id_group, 'id_lang' => 3],
                            ['name' => $nameEs],
                        );

                        GroupReduction::query()->updateOrCreate(
                            ['id_group' => $group->id_group, 'id_category' => 0],
                            ['reduction' => round($discountPercent / 100, 4)],
                        );

                        $targetGroupId = (int) $group->id_group;
                    }

                    $record->update(['id_default_group' => $targetGroupId]);
                    $record->groups()->syncWithoutDetaching(array_values(array_unique([
                        $btbGroupId,
                        $targetGroupId,
                    ])));

                    Notification::make()
                        ->title('Cliente aprobado como BTB')
                        ->success()
                        ->send();

                    $this->refreshFormData([
                        'id_default_group',
                    ]);
                }),
            DeleteAction::make(),
        ];
    }
}
