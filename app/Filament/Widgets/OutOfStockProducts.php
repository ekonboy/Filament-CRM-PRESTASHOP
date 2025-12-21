<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\SoftProduct;

class OutOfStockProducts extends TableWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Productos sin stock';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SoftProduct::with(['lang', 'stockAvailable'])
                    ->whereHas('stockAvailable', function ($query) {
                        $query->where('quantity', '<=', 0);
                    })
            )
            ->striped(false)
            ->recordClasses(function ($record): string {
                return 'bg-red-50 border-l-4 border-red-500 hover:bg-red-100';
            })
            ->columns([
                TextColumn::make('id_product')
                    ->label('ID')
                    ->badge()
                    ->color(function ($state): string {
                        $colors = ['danger', 'gray', 'info', 'primary', 'warning', 'success'];
                        $index = ((int) $state) % count($colors);
                        return $colors[$index];
                    })
                    ->sortable(),
                TextColumn::make('reference')->label('Referencia')->searchable()->copyable(),
                TextColumn::make('lang.name')->label('Nombre')->searchable(),
                TextColumn::make('stockAvailable.quantity')->label('Stock')->sortable()->color('danger'),
            ]);
    }
}
