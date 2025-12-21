<?php

namespace App\Filament\Resources\BulkPrices;

use App\Jobs\ApplyGroupDiscountJob;
use App\Models\Group;
use App\Models\GroupDiscountRule;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class BulkPricesResource extends Resource
{
    protected static ?string $model = GroupDiscountRule::class;

    // protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Bulk Prices';

    protected static ?string $title = 'Bulk Prices - Descuentos Masivos';

    protected static ?int $navigationSort = 100;


    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-m-fire';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_group')
                    ->label('ID Grupo')
                    ->sortable(),
                TextColumn::make('group_name')
                    ->label('Nombre del Grupo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('discount_percentage')
                    ->label('Descuento %')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
                    }),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Action::make('toggle_status')
                    ->label(fn (GroupDiscountRule $record): string => $record->status === 'active' ? 'Desactivar' : 'Activar')
                    ->icon(fn (GroupDiscountRule $record): string => $record->status === 'active' ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (GroupDiscountRule $record): string => $record->status === 'active' ? 'danger' : 'success')
                    ->action(function (GroupDiscountRule $record): void {
                        $newStatus = $record->status === 'active' ? 'inactive' : 'active';
                        $record->update(['status' => $newStatus]);
                        
                        Notification::make()
                            ->title('Estado actualizado')
                            ->body("Grupo {$record->group_name}: " . ($newStatus === 'active' ? 'Activado' : 'Desactivado'))
                            ->success()
                            ->send();
                    }),
                Action::make('edit_discount')
                    ->label('Editar Descuento')
                    ->icon('heroicon-o-pencil')
                    ->form([
                        TextInput::make('discount_percentage')
                            ->label('Porcentaje de Descuento')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(99)
                            ->suffix('%')
                            ->required()
                            ->default(fn (GroupDiscountRule $record): float => $record->discount_percentage),
                    ])
                    ->action(function (GroupDiscountRule $record, array $data): void {
                        $oldPercentage = $record->discount_percentage;
                        $newPercentage = $data['discount_percentage'];

                        $record->update([
                            'discount_percentage' => $newPercentage
                        ]);

                        Notification::make()
                            ->title('Descuento actualizado')
                            ->body("Grupo {$record->group_name}: {$oldPercentage}% → {$newPercentage}%")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                // Bulk actions si se necesitan
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\BulkPrices\Pages\ManageBulkPrices::route('/'),
        ];
    }

    public static function getHeaderActions(): array
    {
        return [
            Action::make('apply_bulk_discount')
                ->label('Aplicar Descuento Masivo')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->form([
                    Select::make('group_ids')
                        ->label('Grupos de Cliente')
                        ->options(function () {
                            return Group::query()
                                ->with('langEs')
                                ->orderBy('id_group')
                                ->get()
                                ->mapWithKeys(fn(Group $group) => [
                                    $group->id_group => $group->langEs?->name ?? "Grupo #{$group->id_group}"
                                ])
                                ->all();
                        })
                        ->multiple()
                        ->required()
                        ->searchable(),
                    TextInput::make('discount_percent')
                        ->label('Porcentaje de Descuento')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(99)
                        ->suffix('%')
                        ->required()
                        ->placeholder('Ej: 10 para 10%'),
                ])
                ->action(function (array $data): void {
                    $batchId = Str::uuid()->toString();

                    // Process immediately for better UX
                    $job = new ApplyGroupDiscountJob(
                        $batchId,
                        $data['group_ids'],
                        (float) $data['discount_percent'],
                        auth()->id(),
                    );

                    // Dispatch and process immediately
                    dispatch($job);

                    // Process the queue synchronously for immediate results
                    $job->handle();

                    Notification::make()
                        ->title('Descuento aplicado')
                        ->body("Se ha aplicado el descuento del {$data['discount_percent']}% para los grupos seleccionados")
                        ->success()
                        ->send();
                }),
            Action::make('panic_rollback')
                ->label('Botón de Pánico - Rollback')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('¿Estás seguro?')
                ->modalDescription('Esto eliminará TODOS los descuentos masivos. Esta acción no se puede deshacer.')
                ->action(function (): void {
                    GroupDiscountRule::query()->delete();

                    Notification::make()
                        ->title('Rollback completado')
                        ->body('Todas las reglas de descuento han sido eliminadas')
                        ->warning()
                        ->send();
                }),
        ];
    }
}
