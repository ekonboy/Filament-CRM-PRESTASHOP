<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductSeoResource\Pages;
use App\Models\Product;
use App\Models\Language;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;


class ProductSeoResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'SEO';

    protected static ?string $modelLabel = 'SEO del Producto';

    protected static ?string $pluralModelLabel = 'SEO de Productos';

    protected static ?int $navigationSort = 2;


    public static function getNavigationIcon(): ?string
    {
        // Devuelve la cadena de Blade Icons
        return 'heroicon-o-magnifying-glass';
    }


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Repeater para gestionar SEO en múltiples idiomas
                Repeater::make('langs')
                    ->label('Contenido y SEO por Idioma')
                    ->schema([
                        Select::make('id_lang')
                            ->label('Idioma')
                            ->options(Language::query()->where('active', 1)->pluck('name', 'id_lang'))
                            ->required()
                            ->disabled(fn($record) => $record !== null) // No permitir cambiar idioma en edición
                            ->dehydrated() // Asegurar que id_lang se envía aunque esté disabled
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('id_shop', 1);
                            })
                            ->columnSpan(2),
                        TextInput::make('id_shop')
                            ->default(1)
                            ->hidden(),
                        TextInput::make('id_product')
                            ->hidden(),
                        TextInput::make('name')
                            ->label('Nombre del Producto')
                            ->required()
                            ->maxLength(128)
                            ->icon('heroicon-o-link')
                            ->columnSpan(2),
                        Textarea::make('description_short')
                            ->label('Descripción Corta')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpan(2),
                        RichEditor::make('description')
                            ->label('Descripción Completa')
                            ->columnSpanFull(),
                        TextInput::make('link_rewrite')
                            ->label('URL Amigable')
                            ->maxLength(128)
                            ->helperText('URL amigable para SEO (ej: producto-ejemplo)'),
                        TextInput::make('meta_title')
                            ->label('Meta Título (SEO)')
                            ->maxLength(128),
                        Textarea::make('meta_description')
                            ->label('Meta Descripción (SEO)')
                            ->rows(2)
                            ->maxLength(512)
                            ->columnSpanFull(),
                        TextInput::make('meta_keywords')
                            ->label('Meta Keywords (SEO)')
                            ->maxLength(255)
                            ->columnSpan(2),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->itemLabel(
                        fn(array $state): ?string =>
                        Language::find($state['id_lang'] ?? null)?->name ?? 'Nueva traducción'
                    )
                    ->defaultItems(0)
                    ->columnSpanFull()
                    ->reorderable(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                TextColumn::make('lang.name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->url(function ($record): ?string {
                        // Get the product lang data for this specific language
                        $productLang = $record->langs()->where('id_lang', $record->lang->id_lang)->first();

                        if ($productLang && $productLang->link_rewrite) {
                            // Build the product URL with language support using SHOP_DOMAIN
                            $shopDomain = env('SHOP_DOMAIN', 'https://sabi.vistarapida.es');
                            $langCode = $record->lang->iso_code ?? 'es'; // Default to 'es' if no iso_code

                            // Get category link_rewrite for dynamic URL path
                            $categoryPath = 'home-accessories'; // Default fallback
                            if ($record->category) {
                                $categoryLang = $record->category->translations()
                                    ->where('id_lang', $record->lang->id_lang)
                                    ->first();
                                if ($categoryLang && $categoryLang->link_rewrite) {
                                    $categoryPath = $categoryLang->link_rewrite;
                                }
                            }

                            // Create correct language-specific product URL: domain/lang/category/product-id-product-name.html
                            return "{$shopDomain}/{$langCode}/{$categoryPath}/{$record->id_product}-{$productLang->link_rewrite}.html";
                        }

                        return null;
                    })
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->iconColor('primary'),
                TextColumn::make('reference')
                    ->label('Referencia')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-document')
                    ->iconColor('primary'),
                TextColumn::make('active')
                    ->label('Activo')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Sí' : 'No')
                    ->color(fn($state) => $state ? 'success' : 'danger'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
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
            'index' => Pages\ListProductSeos::route('/'),
            'edit' => Pages\EditProductSeo::route('/{record}/edit'),
        ];
    }
}
