<?php

namespace App\Filament\Resources\BlogAuthors;

use App\Filament\Resources\BlogAuthors\Pages\CreateBlogAuthor;
use App\Filament\Resources\BlogAuthors\Pages\EditBlogAuthor;
use App\Filament\Resources\BlogAuthors\Pages\ListBlogAuthors;
use App\Filament\Resources\BlogAuthors\Schemas\BlogAuthorForm;
use App\Filament\Resources\BlogAuthors\Tables\BlogAuthorsTable;
use App\Models\BlogAuthor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;



class BlogAuthorResource extends Resource
{
    protected static ?string $model = BlogAuthor::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-users';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Templates';
    }

    protected static ?string $recordTitleAttribute = 'php artisan make:filament-resource BlogPost';




    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identidad del Escritor')
                    ->description('Configura los datos que se verán en la ficha de autor del blog.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre Completo')
                            ->placeholder('Ej: Gabi Fernández')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('role')
                            ->label('Cargo o Rol')
                            ->placeholder('Ej: Senior Fullstack Developer')
                            ->required(),

                        FileUpload::make('avatar')
                            ->label('Foto de Perfil')
                            ->image()
                            ->avatar() // Esto fuerza la previsualización circular
                            ->imageEditor() // Permite recortar la foto para que todas queden iguales
                            ->directory('blog/authors')
                            ->required(),
                    ])->columns(2),
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Foto')
                    ->circular(), // Muestra la miniatura redonda

                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Rol')
                    ->badge() // Lo pone dentro de una etiqueta moderna
                    ->color('info'),

                TextColumn::make('posts_count')
                    ->label('Artículos')
                    ->counts('posts'), // Muestra cuántos posts ha escrito cada uno
            ])
            ->filters([
                //
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
            'index' => ListBlogAuthors::route('/'),
            'create' => CreateBlogAuthor::route('/create'),
            'edit' => EditBlogAuthor::route('/{record}/edit'),
        ];
    }
}
