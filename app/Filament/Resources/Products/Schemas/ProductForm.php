<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\ProductType;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use PHPUnit\Metadata\Group;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__("Name"))
                    ->required(),
                TextInput::make('sku')
                    ->label(__('SKU')),
                Select::make('product_type_id')
                    ->label(__("Product Type"))
                    ->relationship('productType', 'name')
                    ->live(onBlur: true)
                    ->required(),

                TextInput::make('spec')
                    ->nullable()
                    ->numeric()
                    ->visible(fn($get) => $get("product_type_id") != null)
                    ->suffix(fn($get) => ProductType::query()->find($get("product_type_id"))->unit_measure)
                    ->label(__("Specification")),

                Grid::make()
                    ->columnSpanFull()
                    ->schema([
                        Section::make()
                            ->label(__("Prices"))
                            ->schema([
                                TextInput::make('buying_price')
                                    ->label(__("Price") . " " . __("Buying"))
                                    ->required()
                                    ->numeric()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn($get, $set) => self::calculatePrices($get, $set))
                                    ->prefix('EGP'),
                                TextInput::make('selling_price')
                                    ->label(__("Price") . " " . __("Selling"))
                                    ->required()
                                    ->numeric()

                                    ->prefix('EGP'),
                                TextInput::make('wholesale_price')
                                    ->label(__("Price") . " " . __("Wholesale"))
                                    ->required()
                                    ->numeric()
                                    ->prefix('EGP'),
                            ]),
                    ])

            ]);
    }
    private static function calculatePrices($get, $set)
    {
        $buyingPrice = $get("buying_price");
        $sellingPrice = $buyingPrice + ($buyingPrice * 0.105);
        $wholeSalePrice = $buyingPrice + ($buyingPrice * 0.35);
        $set("selling_price", $sellingPrice);
        $set("wholesale_price", $wholeSalePrice);
    }
}
