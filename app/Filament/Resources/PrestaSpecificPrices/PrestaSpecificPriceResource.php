<?php

namespace App\Filament\Resources\PrestaSpecificPrices;

use App\Filament\Resources\PrestaSpecificPrices\Pages\ManagePrestaSpecificPrices;
use App\Models\PrestaSpecificPrice;
use App\Models\Group;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;

use Illuminate\Database\Eloquent\Builder;

class PrestaSpecificPriceResource extends Resource
{
    protected static ?string $model = PrestaSpecificPrice::class;

    // Tipado compatible con PHP 8.3 y Filament 4
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';
    
    protected static ?int $navigationSort = 101;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id_product', 0);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Configuración de Descuento')
                    ->schema([
                        Select::make('id_group')
                            ->label('Grupo de Clientes')
                            ->options(function () {
                                return Group::with('langEs')
                                    ->get()
                                    ->mapWithKeys(function ($group) {
                                        $name = $group->langEs?->name ?? 'Grupo ' . $group->id_group;
                                        return [$group->id_group => $name];
                                    })
                                    ->toArray();
                            })
                            ->required()
                            ->default(3),

                        TextInput::make('reduction')
                            ->label('Descuento (ej: 0.15)')
                            ->numeric()
                            ->required(),

                        Section::make('Mensaje Promocional')
                            ->relationship('customText')
                            ->schema([
                                TextInput::make('promo_text')
                                    ->label('Texto en PrestaShop')
                                    ->required(),
                            ]),

                        Hidden::make('id_product')->default(0),
                        Hidden::make('id_shop')->default(1),
                        Hidden::make('reduction_type')->default('percentage'),
                        Hidden::make('from')->default(now()),
                        Hidden::make('to')->default(now()->addYears(1)),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_group')
                    ->label('Grupo')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        $group = Group::with('langEs')->find($state);
                        return $group ? $group->langEs?->name ?? 'Grupo ' . $state : 'ID: ' . $state;
                    }),

                TextColumn::make('reduction')
                    ->label('Descuento')
                    ->formatStateUsing(fn($state) => round($state * 1, 0) . '%'),

                TextColumn::make('customText.promo_text')
                    ->label('Mensaje')
                    ->wrap(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePrestaSpecificPrices::route('/'),
        ];
    }
}
