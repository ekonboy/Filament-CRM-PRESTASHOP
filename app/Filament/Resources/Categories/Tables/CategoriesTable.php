<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_category')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('id_parent')
                    ->label('Padre')
                    ->sortable(),

                // Nombre desde category_lang (id_lang = 3)
                TextColumn::make('translations.name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),

                IconColumn::make('active')
                    ->boolean()
                    ->label('Activa'),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([]); // si quieres añadir luego delete
    }
}
