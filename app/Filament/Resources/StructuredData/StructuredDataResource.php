<?php

namespace App\Filament\Resources\StructuredData;

use App\Filament\Resources\StructuredData\Pages\ListStructuredData;
use App\Models\Product;
use App\Models\StructuredData;
use App\Models\ProductLang;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StructuredDataResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-code-bracket';
    }
    
    protected static ?string $navigationLabel = 'Structured Data';
    
    protected static ?string $slug = 'structured-data-products';

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('active', 1))
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

                ImageColumn::make('image')
                    ->label('Imagen')
                    ->state(function (Product $record) {
                        try {
                            $idImage = \Illuminate\Support\Facades\DB::table('soft_image')
                                ->where('id_product', $record->id_product)
                                ->where('cover', 1)
                                ->value('id_image');

                            if ($idImage) {
                                $path = implode('/', str_split((string) $idImage));
                                return "https://sabi.vistarapida.es/img/p/{$path}/{$idImage}-small_default.jpg";
                            }
                        } catch (\Exception $e) {
                            // Si falla la query (tabla no existe, etc), devolver null
                        }
                        
                        return null; 
                    })
                    ->defaultImageUrl('https://sabi.vistarapida.es/img/404.jpg'),

                TextColumn::make('name')
                    ->label('Nombre')
                    ->state(function (Product $record, $livewire) {
                        $langId = $livewire->tableFilters['language']['value'] ?? 3; // Default Español
                        return $record->langs->firstWhere('id_lang', $langId)?->name ?? 'Sin nombre';
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        $query->whereHas('langs', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('reference')
                    ->label('Referencia')
                    ->searchable(),
                
                TextColumn::make('has_structured_data')
                    ->label('Estado')
                    ->badge()
                    ->color(function (Product $record, $livewire): string {
                        $langId = $livewire->tableFilters['language']['value'] ?? 3;
                        $exists = $record->structuredDataRecords()->where('lang_id', $langId)->exists();

                        return $exists ? 'success' : 'warning';
                    })
                    ->getStateUsing(function (Product $record, $livewire): string {
                        $langId = $livewire->tableFilters['language']['value'] ?? 3;
                        $exists = $record->structuredDataRecords()->where('lang_id', $langId)->exists();

                        return $exists ? 'Completado' : 'Pendiente';
                    }),
            ])
            ->filters([
                SelectFilter::make('language')
                    ->label('Idioma')
                    ->options([
                        1 => 'English (English)',
                        2 => 'Français (French)',
                        3 => 'Español (Spanish)',
                    ])
                    ->default(3)
                    ->selectablePlaceholder(false)
                    ->query(fn (Builder $query) => $query),
            ])
            ->recordActions([
                Action::make('structured_data')
                    ->label(fn (Product $record) => $record->structuredData ? 'Editar' : 'Data Structured')
                    ->icon('heroicon-o-code-bracket')
                    ->color(fn (Product $record) => $record->structuredData ? 'gray' : 'primary')
                    ->modalHeading('Generar Structured Data')
                    ->form([
                        Textarea::make('json_data')
                            ->label('JSON-LD')
                            ->rows(15)
                            ->required(),
                    ])

                    ->fillForm(function (Product $record, $livewire) {
                        $langId = $livewire->tableFilters['language']['value'] ?? 3;

                        $existing = $record->structuredDataRecords()
                            ->where('lang_id', $langId)
                            ->first();

                        if ($existing) {
                            return ['json_data' => $existing->json_ld];
                        }

                        // Generar nuevo
                        $lang = $record->langs->firstWhere('id_lang', $langId);
                        
                        $description = strip_tags($lang?->description ?? '');
                        
                        // Construir URL e Imagen
                        $url = url("/product/{$record->id_product}"); 
                        
                        // Generar URL de imagen correcta
                        $image = null;
                        try {
                            $idImage = \Illuminate\Support\Facades\DB::table('soft_image')
                                ->where('id_product', $record->id_product)
                                ->where('cover', 1)
                                ->value('id_image');
                            
                            if ($idImage) {
                                $path = implode('/', str_split((string) $idImage));
                                $image = "https://sabi.vistarapida.es/img/p/{$path}/{$idImage}-large_default.jpg"; // Usar large para JSON-LD
                            }
                        } catch (\Exception $e) {}

                        if (!$image) {
                             $image = "https://sabi.vistarapida.es/img/p/{$record->id_product}-home_default.jpg"; // Fallback simple
                        }

                        $data = [
                            "@context" => "https://schema.org",
                            "@type" => "Product",
                            "name" => $lang?->name ?? $record->reference,
                            "description" => $description,
                            "url" => $url,
                            "image" => $image,
                            "offers" => [
                                "@type" => "Offer",
                                "priceCurrency" => "EUR", // Asumir EUR o config
                                "price" => (string) round($record->price, 2),
                            ]
                        ];

                        return ['json_data' => json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)];
                    })
                    ->action(function (Product $record, array $data, $livewire) {
                        $langId = $livewire->tableFilters['language']['value'] ?? 3;

                        $lang = $record->langs->firstWhere('id_lang', $langId);
                        $idImage = null;

                        try {
                            $idImage = \Illuminate\Support\Facades\DB::table('soft_image')
                                ->where('id_product', $record->id_product)
                                ->where('cover', 1)
                                ->value('id_image');
                        } catch (\Exception $e) {
                            $idImage = null;
                        }

                        $image = null;
                        if ($idImage) {
                            $path = implode('/', str_split((string) $idImage));
                            $image = "https://sabi.vistarapida.es/img/p/{$path}/{$idImage}-large_default.jpg";
                        }

                        StructuredData::updateOrCreate(
                            [
                                'product_id' => $record->id_product,
                                'lang_id' => $langId,
                            ],
                            [
                                'json_ld' => $data['json_data'],
                                'reference' => $record->reference,
                                'name' => $lang?->name,
                                'image' => $image,
                                'price' => $record->price,
                            ],
                        );
                        
                        Notification::make()
                            ->title('Structured Data guardado')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStructuredData::route('/'),
        ];
    }
}
