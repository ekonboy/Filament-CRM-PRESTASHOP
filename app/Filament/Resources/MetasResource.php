<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetasResource\Pages\EditMeta;
use App\Filament\Resources\MetasResource\Pages\ListMetas;
use App\Models\Language;
use App\Models\ProductLang;
use App\Models\Tag;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MetasResource extends Resource
{
    protected static ?string $model = ProductLang::class;

    protected static ?string $navigationLabel = 'Metas';

    protected static ?string $modelLabel = 'Meta';

    protected static ?string $pluralModelLabel = 'Metas';

    protected static ?int $navigationSort = 3;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-language';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('id_product')
                    ->label('ID Producto')
                    ->disabled()
                    ->dehydrated(false),
                Select::make('id_lang')
                    ->label('Idioma')
                    ->options(Language::query()->pluck('name', 'id_lang'))
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(128)
                    ->columnSpan(2),

                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->rows(3)
                    ->maxLength(512)
                    ->columnSpan(2),

                Textarea::make('description_short')
                    ->label('Descripción Corta')
                    ->rows(5)
                    ->maxLength(65535)
                    ->columnSpanFull(),

                RichEditor::make('description')
                    ->label('Descripción')
                    ->columnSpanFull(),

                TextInput::make('link_rewrite')
                    ->label('URL (link_rewrite)')
                    ->maxLength(128)
                    ->columnSpan(2),

                TextInput::make('meta_title')
                    ->label('Meta title')
                    ->maxLength(128)
                    ->columnSpan(2),

                TextInput::make('meta_keywords')
                    ->label('Meta Keywords')
                    ->maxLength(255)
                    ->placeholder('palabra1, palabra2, palabra3')
                    ->columnSpan(2),

                TagsInput::make('tags')
                    ->label('Tags')
                    ->placeholder('Añadir tags...')
                    ->columnSpan(2),
            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Forzamos el orden por id_product explícitamente
            ->defaultSort('id_product', 'desc')
            ->modifyQueryUsing(function (Builder $query): Builder {
                return $query->with(['language'])
                    ->where(function (Builder $q): void {
                        $q->whereRaw("LENGTH(TRIM(COALESCE(description, ''))) = 0")
                            ->orWhereRaw("LENGTH(TRIM(COALESCE(description_short, ''))) = 0")
                            ->orWhereRaw("LENGTH(TRIM(COALESCE(link_rewrite, ''))) = 0")
                            ->orWhereRaw("LENGTH(TRIM(COALESCE(meta_title, ''))) = 0");
                    });
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

                TextColumn::make('language.name')
                    ->label('Idioma')
                    ->sortable(),

                TextColumn::make('id_lang')
                    ->label('ID Lang')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),

                IconColumn::make('tiene_desc')
                    ->label('Desc')
                    ->boolean()
                    ->state(function (ProductLang $record): bool {
                        $value = trim(strip_tags((string) ($record->description ?? '')));
                        return $value !== '';
                    })
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                IconColumn::make('tiene_desc_corta')
                    ->label('Desc corta')
                    ->boolean()
                    ->state(function (ProductLang $record): bool {
                        $value = trim(strip_tags((string) ($record->description_short ?? '')));
                        return $value !== '';
                    })
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                IconColumn::make('tiene_url')
                    ->label('URL')
                    ->boolean()
                    ->state(function (ProductLang $record): bool {
                        $value = trim((string) ($record->link_rewrite ?? ''));
                        return $value !== '';
                    })
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                IconColumn::make('tiene_seo')
                    ->label('SEO')
                    ->boolean()
                    ->state(function (ProductLang $record): bool {
                        $metaTitle = trim((string) ($record->meta_title ?? ''));
                        $metaDescription = trim((string) ($record->meta_description ?? ''));
                        $metaKeywords = trim((string) ($record->meta_keywords ?? ''));

                        return $metaTitle !== ''
                            && $metaDescription !== ''
                            && $metaKeywords !== '';
                    })
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('link_rewrite')
                    ->label('link_rewrite')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap(),

                TextColumn::make('meta_title')
                    ->label('meta_title')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap(),
            ])
            ->filters([
                SelectFilter::make('id_lang')
                    ->label('Idioma')
                    ->options(Language::query()->pluck('name', 'id_lang'))
                    ->placeholder('Todos los idiomas')
                    ->default(null),

                Filter::make('con_campos_vacios')
                    ->label('Con campos vacíos')
                    ->query(function (Builder $query): Builder {
                        return $query->where(function (Builder $q): void {
                            $q->whereRaw("LENGTH(TRIM(COALESCE(description, ''))) = 0")
                                ->orWhereRaw("LENGTH(TRIM(COALESCE(description_short, ''))) = 0")
                                ->orWhereRaw("LENGTH(TRIM(COALESCE(link_rewrite, ''))) = 0")
                                ->orWhereRaw("LENGTH(TRIM(COALESCE(meta_title, ''))) = 0");
                        });
                    }),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->url(fn($record) => static::getUrl('edit', [
                        'record' => "{$record->id_product}-{$record->id_shop}-{$record->id_lang}"
                    ])),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMetas::route('/'),
            'edit' => EditMeta::route('/{record}/edit'),
        ];
    }
}
