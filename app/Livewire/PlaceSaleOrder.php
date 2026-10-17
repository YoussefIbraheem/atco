<?php

namespace App\Livewire;

use App\Enums\PartyTypeEnum;
use App\Models\Party;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Concerns\RestrictsFileUploadsToSchemaComponents;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Component;

class PlaceSaleOrder extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use RestrictsFileUploadsToSchemaComponents;

    public function placeSaleOrder(): Action
    {
        return Action::make("placeSaleOrder")
            ->label(__("Place Sale Order"))
            ->schema([
                Select::make("party_id")
                    ->label(__("Client Type"))
                    ->required()
                    ->options(Party::query()->where("type", PartyTypeEnum::CLIENT)->pluck("name", "id")->toArray()),

                Repeater::make("distributionData")
                    ->label(__("Products List"))
                    ->columnSpanFull()
                    ->columns(3)
                    ->required()
                    ->live(debounce: 500)
                    ->afterStateUpdated(function ($set, $get) {
                        self::executeTotal($set, $get);
                    })
                    ->schema([
                        Select::make("product_id")
                            ->label(__("Product"))
                            ->required()
                            ->columnSpan(2)
                            ->live()
                            ->options(Product::query()->pluck("name", "id")->toArray())
                            ->afterStateUpdated(function ($set, $get) {
                                self::executeProductsDistribution($set, $get);
                            }),
                        TextInput::make("quantity")
                            ->label(__("Quantity"))
                            ->required()
                            ->live()
                            ->default(1)
                            ->gte(1)
                            ->afterStateUpdated(function ($set, $get) {
                                self::executeProductsDistribution($set, $get);
                            })
                            ->columnSpan(1),
                        Fieldset::make(__("Price Per Unit"))
                            ->schema([
                                TextInput::make("buying_price")
                                    ->label(__("Buying Price"))
                                    ->numeric()
                                    ->readOnly()
                                    ->disabled()
                                    ->minValue(1)
                                    ->columnOrder(1)
                                    ->suffix(__("EGP")),
                                TextInput::make("wholesale_price")
                                    ->label(__("Wholesale Price"))
                                    ->numeric()
                                    ->readOnly()
                                    ->disabled()
                                    ->minValue(1)
                                    ->columnOrder(2)
                                    ->suffix(__("EGP")),
                                TextInput::make("selling_price")
                                    ->label(__("Selling Price"))
                                    ->numeric()
                                    ->readOnly()
                                    ->disabled()
                                    ->minValue(1)
                                    ->columnOrder(3)
                                    ->suffix(__("EGP")),
                            ])->columnSpanFull()
                            ->columns(3),
                        TextInput::make("total_selling_price")
                            ->label(__("Total Selling Price"))
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->columnOrder(3)
                            ->live(onBlur: true)
                            ->gt("buying_price")
                            ->afterStateUpdated(function ($set, $get) {
                                self::executeProfit($set, $get);
                            })
                            ->suffix(__("EGP")),
                        TextInput::make("profit")
                            ->label(__("Profit"))
                            ->columnOrder(4)
                            ->readOnly()
                            ->disabled()
                            ->suffix(__("EGP"))
                    ])
                    ->columnSpanFull()
                    ->columns(3),

                TextEntry::make("total")
                    ->money("EGP")

            ])->action(function ($data) {
                dd($data);
            });
    }

    private static function executeProfit($set, $get)
    {

        $buyingPrice = (float) $get("buying_price");
        $sellingPrice = (float) $get("selling_price");
        $quantity = (int) $get("quantity");
        $profit = self::determineProfit($buyingPrice, $sellingPrice, $quantity);
        $set("profit", $profit);
    }

    private static function executeTotal($set, $get)
    {
        $data = $get("distributionData");
        $total = self::determineTotal($data);
        $set("total", $total);
    }



    private static function determineProfit($buyingPrice, $sellingPrice, $quantity): float
    {
        $totalSellingPrice = (float) ($sellingPrice * $quantity);
        $totalBuyingPrice = (float) ($buyingPrice * $quantity);
        $profit = (float) ($totalSellingPrice - $totalBuyingPrice);

        return $profit;
    }

    private static function executeProductsDistribution($set, $get): void
    {
        $productId = (int) $get("product_id");
        $quantity = (int) $get("quantity");
        $product = Product::query()->findOrFail($productId);
        $buyingPrice = (float) $product->buying_price;
        $wholesalePrice = (float) $product->wholesale_price;
        $sellingPrice = (float) ($product->selling_price);
        $profit = self::determineProfit($buyingPrice, $sellingPrice, $quantity);
        $totalSellingPrice = (float) ($sellingPrice * $quantity);

        $set("buying_price", $buyingPrice);
        $set("wholesale_price", $wholesalePrice);
        $set("selling_price", $sellingPrice);
        $set("total_selling_price", $totalSellingPrice);
        $set("profit", $profit);

        $distributionData = $get("../../distributionData");
        $total =  self::determineTotal($distributionData);
        $set("../../total", $total);
    }

    private static function determineTotal(array $distributionData): float
    {
        $total = 0;

        foreach ($distributionData as $data) {
            $price = (float) $data["total_selling_price"];

            if (!$price) {
                continue;
            }

            $total += $price;
        }

        return (float) $total;
    }

    public function render()
    {
        return view('livewire.place-sale-order');
    }
}
