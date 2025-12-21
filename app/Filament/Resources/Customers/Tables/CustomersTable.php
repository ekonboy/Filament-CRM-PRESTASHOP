<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\Customer;
use App\Models\Group;
use App\Models\GroupLang;
use App\Models\GroupReduction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->schema(fn (Schema $schema): ?Schema => CustomerResource::form($schema))
                    ->slideOver(),
            ])
            ->columns([
                // TextColumn::make('id_shop_group')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('id_shop')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('id_gender')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('id_default_group')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('id_customer')
                    ->label('ID Cliente')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('id_lang')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('id_risk')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('company')
                //     ->searchable(),
                // TextColumn::make('siret')
                //     ->searchable(),
                // TextColumn::make('ape')
                //     ->searchable(),
                TextColumn::make('firstname')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('lastname')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('group_name')
                    ->label('Grupo')
                    ->state(fn (Customer $record): string => $record->defaultGroup?->langEs?->name
                        ?? (string) ($record->id_default_group ?? ''))
                    ->toggleable(),
                // TextColumn::make('passwd')
                //     ->searchable(),
                // TextColumn::make('last_passwd_gen')
                //     ->dateTime()
                //     ->sortable(),
                TextColumn::make('birthday')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('newsletter')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('ip_registration_newsletter')
                //     ->searchable(),
                // TextColumn::make('newsletter_date_add')
                //     ->dateTime()
                //     ->sortable(),
                // TextColumn::make('optin')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('website')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('outstanding_allow_amount')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('show_public_prices')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('max_payment_days')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('secure_key')
                //     ->searchable(),
                TextColumn::make('active')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('is_guest')
                    ->boolean()
                    ->toggleable(),
                IconColumn::make('deleted')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('date_add')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('date_upd')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('orders.id_order')
                    ->label('Pedidos')
                    ->badge()
                    ->toggleable(),
            ])
            ->reorderableColumns()
            ->deferColumnManager(false)
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->schema(fn (Schema $schema): ?Schema => CustomerResource::form($schema))
                    ->slideOver(),
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
                    ->action(function (Customer $record, array $data): void {
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
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
