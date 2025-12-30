<?php

namespace App\Filament\Resources\BlogPosts;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Filament\Resources\BlogPosts\Pages\CreateBlogPost;
use App\Filament\Resources\BlogPosts\Pages\EditBlogPost;
use App\Filament\Resources\BlogPosts\Pages\ListBlogPosts;
use App\Filament\Resources\BlogPosts\Schemas\BlogPostForm;
use App\Filament\Resources\BlogPosts\Tables\BlogPostsTable;
use App\Models\BlogPost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Str;
use Filament\Actions\Action;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-document-text';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Templates';
    }

    protected static ?string $recordTitleAttribute = 'id';



    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Contenido')
                    ->schema([
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('URL Amigable')
                            ->required()
                            ->unique(BlogPost::class, 'slug', ignoreRecord: true),

                        RichEditor::make('content')
                            ->label('Contenido')
                            ->required()
                            ->extraInputAttributes(['style' => 'min-height: 500px; resize: vertical;']),
                    ]),

                Section::make('Publicación')
                    ->schema([
                        Select::make('blog_author_id')
                            ->label('Autor')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('published_at')
                            ->label('Fecha de Publicación')
                            ->default(now())
                            ->required(),

                        Toggle::make('is_visible')
                            ->label('¿Visible?')
                            ->default(true),
                    ]),

                Section::make('Imagen')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Imagen del Post')
                            ->image()
                            ->directory('blog/posts')
                            ->required(),

                        Textarea::make('excerpt')
                            ->label('Resumen Corto')
                            ->required(),
                    ]),
            ]);
    }

    protected static function getHeaderActions(): array
    {
        return [
            Action::make('view_post')
                ->label('Ver post')
                ->icon('heroicon-o-eye')
                ->color('success')
                ->url(fn ($record) => $record?->url)
                ->openUrlInNewTab(),
        ];
    }



    public static function table(Table $table): Table
    {
        return BlogPostsTable::configure($table);
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
            'index' => ListBlogPosts::route('/'),
            'create' => CreateBlogPost::route('/create'),
            'edit' => EditBlogPost::route('/{record}/edit'),
        ];
    }
}
