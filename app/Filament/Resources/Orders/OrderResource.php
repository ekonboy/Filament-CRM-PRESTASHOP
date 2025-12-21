<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Models\OrderTracking;
use App\Models\Order;
// use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
// use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;


class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id_order';



    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-credit-card';
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_order')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('reference')
                    ->label('Referencia')
                    ->searchable()
                    ->copyable()
                    ->toggleable()
                    ->icon('heroicon-o-document')
                    ->iconColor('primary'),
                TextColumn::make('invoice_number')
                    ->label('Factura')
                    ->searchable()
                    ->copyable()
                    ->copyableState(fn ($record) => $record->invoice_number)
                    ->toggleable()
                    ->icon('heroicon-o-document')
                    ->iconColor('primary'),

                TextColumn::make('id_customer')
                    ->label('ID Cliente')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->state(fn (Order $record): string => trim(
                        ($record->customer?->firstname ?? $record->firstname ?? '')
                        . ' ' .
                        ($record->customer?->lastname ?? $record->lastname ?? '')
                    ))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('customer', function (Builder $customerQuery) use ($search): void {
                                $customerQuery
                                    ->where('firstname', 'like', "%{$search}%")
                                    ->orWhere('lastname', 'like', "%{$search}%");
                            })
                            ->orWhere('firstname', 'like', "%{$search}%")
                            ->orWhere('lastname', 'like', "%{$search}%");
                    })
                    ->toggleable(),

                TextColumn::make('total_paid')
                    ->label('Total')
                    ->money('EUR')
                    ->toggleable(),
                TextColumn::make('date_add')
                    ->label('Fecha')
                    ->date('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('shipping_country')
                    ->label('País')
                    ->state(fn (Order $record): string => $record->deliveryAddress?->country?->langEs?->name
                        ?: ($record->country_delivery ?? ''))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('deliveryAddress', function (Builder $addressQuery) use ($search): void {
                                $addressQuery->whereHas('country.langEs', function (Builder $countryLangQuery) use ($search): void {
                                    $countryLangQuery->where('name', 'like', "%{$search}%");
                                });
                            })
                            ->orWhere('country_delivery', 'like', "%{$search}%");
                    })
                    ->toggleable(),
            ])
            ->reorderableColumns()
            ->deferColumnManager(false)
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ]);
        // ->bulkActions([
        //     Tables\Actions\DeleteBulkAction::make(),
        // ]);
    }



    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Grid::make(['lg' => 2])
                    ->columnSpan('full')
                    ->schema([
                        Section::make('Información del Cliente')
                            ->compact()
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('customer_name')
                                            ->label('Nombre del Cliente')
                                            ->disabled()
                                            ->formatStateUsing(function (?Order $record): string {
                                                if (!$record) return '';
                                                
                                                $firstname = $record->customer?->firstname ?? $record->firstname ?? '';
                                                $lastname = $record->customer?->lastname ?? $record->lastname ?? '';
                                                
                                                return trim($firstname . ' ' . $lastname);
                                            }),
                                        TextInput::make('customer_email')
                                            ->label('Email del Cliente')
                                            ->disabled()
                                            ->formatStateUsing(function (?Order $record): string {
                                                return $record->customer?->email ?? $record->email ?? '';
                                            }),
                                    ]),
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('invoice_number')
                                            ->label('Factura')
                                            ->disabled(),
                                        TextInput::make('tracking_code')
                                            ->label('Código de Tracking')
                                            ->disabled(fn (?Order $record): bool => filled($record))
                                            ->dehydrated(false)
                                            ->afterStateHydrated(function (TextInput $component, ?Order $record): void {
                                                if (! $record) {
                                                    return;
                                                }

                                                $component->state(
                                                    OrderTracking::query()
                                                        ->where('id_order', $record->getKey())
                                                        ->value('tracking_code')
                                                );
                                            }),
                                    ]),
                                Textarea::make('note')
                                    ->label('Nota del Pedido')
                                    ->placeholder('Escribe aquí notas específicas de este pedido...')
                                    ->rows(4),
                                Textarea::make('customer.note')
                                    ->label('Nota del Cliente Privada')
                                    ->placeholder('Notas generales del cliente (se aplican a todos sus pedidos)...')
                                    ->dehydrated(false)
                                    ->rows(4)
                                    ->afterStateHydrated(function (Textarea $component, ?Order $record): void {
                                        if (! $record) {
                                            return;
                                        }
                                        $component->state($record->customer?->note);
                                    }),
                            ])
                            ->columnSpan(['lg' => 1]),

                        Section::make()
                            ->compact()
                            ->schema([
                                Repeater::make('details')
                                    ->relationship('details')
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->dehydrated(false)
                                    ->schema([
                                        TextInput::make('product_name')->label('Producto')->disabled(),
                                        TextInput::make('product_quantity')->label('Cantidad')->disabled(),
                                        TextInput::make('product_price')->label('Precio unitario')->disabled(),
                                        TextInput::make('total_price')->label('Total')->disabled(),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
