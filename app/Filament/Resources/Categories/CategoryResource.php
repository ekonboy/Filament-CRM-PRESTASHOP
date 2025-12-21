<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use App\Models\CategoryLang;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    // Filament mostrará el 'name' si tu modelo tiene el accessor getNameAttribute()
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-rectangle-group';
    }


    /**
     * Form configuration — signature compatible con Filament ^4.3
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_parent')
                    ->label('Categoría Padre')
                    ->numeric()
                    ->default(1),

                Toggle::make('active')
                    ->label('Activa')
                    ->default(true),

                Repeater::make('translations')
                    ->relationship('translations')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required(),
                        TextInput::make('link_rewrite')
                            ->label('URL amigable')
                            ->required(),
                        Textarea::make('description')
                            ->label('Descripción'),
                        TextInput::make('meta_title')
                            ->label('Meta título'),
                        TextInput::make('meta_description')
                            ->label('Meta descripción'),
                        TextInput::make('meta_keywords')
                            ->label('Meta keywords'),
                        TextInput::make('id_lang')
                            ->label('Idioma')
                            ->numeric()
                            ->default(3),
                    ]),
            ]);
    }

    /**
     * Table configuration — signature compatible con Filament ^4.3
     */
    public static function table(Table $table): Table
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

                // Usamos la relación 'translations' (idealmente filtrada por id_lang = 3 en el modelo)
                TextColumn::make('translations.name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),

                IconColumn::make('active')
                    ->label('Activa'),
            ])
            ->filters([]);
            // ->actions([
            //     EditAction::make(),
            // ])
            // ->bulkActions([
            //     DeleteBulkAction::make(),
            // ]);
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
            'index'  => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit'   => EditCategory::route('/{record}/edit'),
        ];
    }
}
