<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class ProductsTable
{
    public static function configure(Table $table): Table
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
                    ->sortable()
                    ->searchable(),

                TextColumn::make('lang.name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                \Filament\Tables\Columns\ImageColumn::make('image')
                    ->label('Imagen')
                    ->state(function ($record) {
                        try {
                            $idImage = \Illuminate\Support\Facades\DB::table('soft_image')
                                ->where('id_product', $record->id_product)
                                ->where('cover', 1)
                                ->value('id_image');

                            if ($idImage) {
                                $path = implode('/', str_split((string) $idImage));
                                return "https://sabi.vistarapida.es/img/p/{$path}/{$idImage}-small_default.jpg";
                            }
                        } catch (\Exception $e) {}
                        
                        return null; 
                    })
                    ->defaultImageUrl('https://sabi.vistarapida.es/img/404.jpg')
                    ->toggleable(),
                // TextColumn::make('id_supplier')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('id_manufacturer')
                //     ->numeric()
                //     ->sortable(),

                // TextColumn::make('id_category_default')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('category_name')
                    ->label('Categoría')
                    ->getStateUsing(fn($record) => $record->categoryName())
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('id_shop_default')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                // TextColumn::make('id_tax_rules_group')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('on_sale')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('online_only')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('ean13')
                //     ->searchable(),
                // TextColumn::make('isbn')
                //     ->searchable(),
                // TextColumn::make('upc')
                //     ->searchable(),
                // TextColumn::make('mpn')
                //     ->searchable(),
                // TextColumn::make('ecotax')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->state(fn ($record) => (int) DB::table('soft_stock_available')
                        ->where('id_product', $record->id_product)
                        ->sum('quantity'))
                    ->formatStateUsing(fn ($state): string => (string) ((int) $state))
                    ->sortable()
                    ->toggleable(),
                // TextColumn::make('minimal_quantity')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('low_stock_threshold')
                //     ->numeric()
                //     ->sortable(),
                IconColumn::make('low_stock_alert')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('price')
                    ->money()
                    ->sortable()
                    ->toggleable(),
                // TextColumn::make('wholesale_price')
                //     ->money()
                //     ->sortable(),
                // TextColumn::make('unity')
                //     ->searchable(),
                // TextColumn::make('unit_price')
                //     ->money()
                //     ->sortable(),
                // TextColumn::make('unit_price_ratio')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('additional_shipping_cost')
                //     ->money()
                //     ->sortable(),
                TextColumn::make('reference')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),
                // TextColumn::make('supplier_reference')
                //     ->searchable(),
                // TextColumn::make('location')
                //     ->searchable(),
                // TextColumn::make('width')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('height')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('depth')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('weight')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('out_of_stock')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                // TextColumn::make('additional_delivery_times')
                //     ->numeric()
                //     ->sortable(),
                // IconColumn::make('quantity_discount')
                //     ->boolean(),
                // TextColumn::make('customizable')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('uploadable_files')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('text_fields')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('active')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                // TextColumn::make('redirect_type')
                //     ->badge(),
                // TextColumn::make('id_type_redirected')
                //     ->numeric()
                //     ->sortable(),
                // IconColumn::make('available_for_order')
                //     ->boolean(),
                // TextColumn::make('available_date')
                //     ->date()
                //     ->sortable(),
                IconColumn::make('show_condition')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('condition')
                    ->badge()
                    ->toggleable(),
                // IconColumn::make('show_price')
                //     ->boolean(),
                // IconColumn::make('indexed')
                //     ->boolean(),
                TextColumn::make('visibility')
                    ->badge()
                    ->toggleable(),
                // IconColumn::make('cache_is_pack')
                //     ->boolean(),
                // IconColumn::make('cache_has_attachments')
                //     ->boolean(),
                // IconColumn::make('is_virtual')
                //     ->boolean(),
                // TextColumn::make('cache_default_attribute')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('date_add')
                //     ->dateTime()
                //     ->sortable(),
                // TextColumn::make('date_upd')
                //     ->dateTime()
                //     ->sortable(),
                // IconColumn::make('advanced_stock_management')
                //     ->boolean(),
                // TextColumn::make('pack_stock_type')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('state')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('product_type')
                    ->badge()
                    ->toggleable(),
            ])
            ->reorderableColumns()
            ->deferColumnManager(false)
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
