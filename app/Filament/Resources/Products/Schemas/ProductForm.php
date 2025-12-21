<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use App\Models\Category;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_product')
                    ->label('ID')
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn ($record) => filled($record)),

                // Campos de la tabla product
                TextInput::make('id_supplier')
                    ->numeric(),
                TextInput::make('id_manufacturer')
                    ->numeric(),
                // TextInput::make('id_category_default')
                //     ->numeric(),

                Select::make('id_category_default')
                    ->label('Categoría')
                    ->options(function () {
                        return Category::with('translations')
                            ->get()
                            ->mapWithKeys(function ($category) {
                                $name = $category->translations->firstWhere('id_lang', 3)?->name ?? 'Sin categoría';
                                return [$category->id_category => $name];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->required(),

                TextInput::make('id_shop_default')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('id_tax_rules_group')
                    ->required()
                    ->numeric(),
                TextInput::make('on_sale')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('online_only')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('ean13'),
                TextInput::make('isbn'),
                TextInput::make('upc'),
                TextInput::make('mpn'),
                TextInput::make('ecotax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('minimal_quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('low_stock_threshold')
                    ->numeric(),
                Toggle::make('low_stock_alert')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('wholesale_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('unity'),
                TextInput::make('unit_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('unit_price_ratio')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('additional_shipping_cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('reference'),
                TextInput::make('supplier_reference'),
                TextInput::make('location')->required()->helperText('Ubicación física del producto en tu almacén'),
                TextInput::make('width')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('height')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('depth')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('weight')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('out_of_stock')
                    ->required()
                    ->numeric()
                    ->default(2),
                TextInput::make('additional_delivery_times')
                    ->required()
                    ->numeric()
                    ->default(1),
                Toggle::make('quantity_discount'),
                TextInput::make('customizable')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('uploadable_files')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('text_fields')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('active')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('redirect_type')
                    ->options([
            '' => '',
            404 => '404',
            410 => '410',
            '301-product' => '301 product',
            '302-product' => '302 product',
            '301-category' => '301 category',
            '302-category' => '302 category',
            '200-displayed' => '200 displayed',
            '404-displayed' => '404 displayed',
            '410-displayed' => '410 displayed',
            'default' => 'Default',
        ])
                    ->default('default')
                    ->required(),
                TextInput::make('id_type_redirected')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('available_for_order')
                    ->required(),
                DatePicker::make('available_date')->nullable(),
                Toggle::make('show_condition')
                    ->required(),
                Select::make('condition')
                    ->options(['new' => 'New', 'used' => 'Used', 'refurbished' => 'Refurbished'])
                    ->default('new')
                    ->required(),
                Toggle::make('show_price')
                    ->required(),
                Toggle::make('indexed')
                    ->required(),
                Select::make('visibility')
                    ->options(['both' => 'Both', 'catalog' => 'Catalog', 'search' => 'Search', 'none' => 'None'])
                    ->default('both')
                    ->required(),
                Toggle::make('cache_is_pack')
                    ->required(),
                Toggle::make('cache_has_attachments')
                    ->required(),
                Toggle::make('is_virtual')
                    ->required(),
                TextInput::make('cache_default_attribute')
                    ->numeric(),
                DateTimePicker::make('date_add')
                    ->nullable()
                    ->required(),
                DateTimePicker::make('date_upd')
                    ->nullable()
                    ->required(),
                Toggle::make('advanced_stock_management')
                    ->required(),
                TextInput::make('pack_stock_type')
                    ->required()
                    ->numeric()
                    ->default(3),
                TextInput::make('state')
                    ->required()
                    ->numeric()
                    ->default(1),
                Select::make('product_type')
                    ->options([
            'standard' => 'Standard',
            'pack' => 'Pack',
            'virtual' => 'Virtual',
            'combinations' => 'Combinations',
            '' => '',
        ])
                    ->required(),
            ])
            ->columns(2);
    }
}
