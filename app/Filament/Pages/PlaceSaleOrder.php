<?php

namespace App\Filament\Pages;

use App\Enums\AmountTypeEnum;
use App\Enums\PartyTypeEnum;
use App\Models\Party;
use App\Models\Product;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Concerns\RestrictsFileUploadsToSchemaComponents;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PlaceSaleOrder extends Page implements HasSchemas

{
    use InteractsWithSchemas;
    use RestrictsFileUploadsToSchemaComponents;

    protected string $view = 'filament.pages.place-sale-order';
    protected static ?string $slug = 'sale-order/place';
    protected static bool $shouldRegisterNavigation = false;


    private const CURRENCY = 'EGP';

    private const ROOT = '../../';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function create(): void
    {
        dd($this->form->getState());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            self::clientField(),
            self::productsRepeater(),
            self::summaryGroup(),
        ]);
    }


    private static function clientField(): Select
    {
        return Select::make('party_id')
            ->label(__('Client'))
            ->required()
            ->options(
                fn(): array => Party::query()
                    ->where('type', PartyTypeEnum::CLIENT)
                    ->pluck('name', 'id')
                    ->toArray()
            );
    }

    private static function productsRepeater(): Repeater
    {
        return Repeater::make('distributionData')
            ->label(__('Products List'))
            ->required()
            ->columns(3)
            ->columnSpanFull()
            ->live(debounce: 500)
            ->afterStateUpdated(fn(Get $get, Set $set) => self::refreshTotal($get, $set))
            ->schema([
                Select::make('product_id')
                    ->label(__('Product'))
                    ->required()
                    ->columnSpan(2)
                    ->live()
                    ->options(fn(): array => Product::query()->pluck('name', 'id')->toArray())
                    ->afterStateUpdated(fn(Get $get, Set $set) => self::refreshItem($get, $set)),

                TextInput::make('quantity')
                    ->label(__('Quantity'))
                    ->required()
                    ->numeric()
                    ->integer()
                    ->minValue(1)
                    ->default(1)
                    ->live()
                    ->columnSpan(1)
                    ->afterStateUpdated(fn(Get $get, Set $set) => self::refreshItem($get, $set)),

                Fieldset::make(__('Price Per Unit'))
                    ->columns(4)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('buying_price')

                            ->label(__('Buying Price'))
                            ->readonly()
                            ->disabled()
                            ->suffix(__(self::CURRENCY)),
                        TextInput::make('wholesale_price')

                            ->label(__('Wholesale Price'))
                            ->readonly()
                            ->disabled()
                            ->suffix(__(self::CURRENCY)),
                        TextInput::make('selling_price')

                            ->label(__('Selling Price'))
                            ->readonly()
                            ->disabled()
                            ->suffix(__(self::CURRENCY)),
                        TextInput::make('quantity_in_stock')

                            ->label(__('Quantity in Stock'))->readonly()
                            ->disabled(),
                    ]),


                TextInput::make('total_selling_price')
                    ->label(__('Total Selling Price'))
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->suffix(__(self::CURRENCY))
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Get $get, Set $set) => self::refreshProfit($get, $set)),

                TextInput::make('profit')
                    ->label(__('Profit'))
                    ->suffix(__(self::CURRENCY))
                    ->readOnly(),
            ]);
    }

    private static function summaryGroup(): Group
    {
        return Group::make([
            FusedGroup::make([
                Select::make('discount_type')
                    ->label(__('Discount Type'))
                    ->placeholder(__('Discount Type'))
                    ->options(AmountTypeEnum::class)
                    ->live()
                    ->afterStateUpdated(fn(Get $get, Set $set) => self::refreshTotal($get, $set)),

                TextInput::make('discount_amount')
                    ->label(__('Discount Amount'))
                    ->placeholder(__('Discount Amount'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(fn(Get $get): ?int => $get('discount_type') === AmountTypeEnum::PERCENTAGE ? 100 : null)
                    ->suffix(fn(Get $get): string => $get('discount_type') === AmountTypeEnum::PERCENTAGE
                        ? '%'
                        : __(self::CURRENCY))
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Get $get, Set $set) => self::refreshTotal($get, $set)),
            ])->label(__("Discount")),

            FusedGroup::make([
                Select::make('tax_type')
                    ->label(__('Tax Type'))
                    ->placeholder(__('Tax Type'))
                    ->options(AmountTypeEnum::class)
                    ->live()
                    ->afterStateUpdated(fn(Get $get, Set $set) => self::refreshTotal($get, $set)),

                TextInput::make('tax_amount')
                    ->label(__('Tax Amount'))
                    ->placeholder(__('Tax Amount'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(fn(Get $get): ?int => $get('tax_type') === AmountTypeEnum::FIXED ? 100 : null)
                    ->suffix(fn(Get $get): string => $get('tax_type') === AmountTypeEnum::PERCENTAGE
                        ? '%'
                        : __(self::CURRENCY))
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Get $get, Set $set) => self::refreshTotal($get, $set)),
            ])->label(__("Tax")),

            TextEntry::make('total')
                ->label(__('Total'))
                ->default('0.00')
                ->money(self::CURRENCY)
                ->columnSpanFull(),
        ])->columns(2);
    }


    private static function refreshItem(Get $get, Set $set): void
    {
        $product = Product::query()->find($get('product_id'));

        if (! $product) {
            foreach (['buying_price', 'wholesale_price', 'selling_price', 'quantity_in_stock', 'total_selling_price', 'profit'] as $field) {
                $set($field, null);
            }

            self::refreshTotal($get, $set, self::ROOT);

            return;
        }

        $quantity = max(1, (int) $get('quantity'));
        $buyingPrice = (float) $product->buying_price;
        $sellingPrice = (float) $product->selling_price;
        $totalSellingPrice = $sellingPrice * $quantity;

        $set('buying_price', $buyingPrice);
        $set('wholesale_price', (float) $product->wholesale_price);
        $set('selling_price', $sellingPrice);
        $set('quantity_in_stock', $product->quantityInStock());
        $set('total_selling_price', $totalSellingPrice);
        $set('profit', self::calculateProfit($totalSellingPrice, $buyingPrice, $quantity));

        self::refreshTotal($get, $set, self::ROOT);
    }

    private static function refreshProfit(Get $get, Set $set): void
    {
        $set('profit', self::calculateProfit(
            (float) $get('total_selling_price'),
            (float) $get('buying_price'),
            max(1, (int) $get('quantity')),
        ));

        self::refreshTotal($get, $set, self::ROOT);
    }

    private static function refreshTotal(Get $get, Set $set, string $root = ''): void
    {
        $set($root . 'total', self::calculateTotal(
            $get($root . 'distributionData') ?? [],
            $get($root . 'discount_type'),
            $get($root . 'discount_amount'),
            $get($root . 'tax_type'),
            $get($root . 'tax_amount'),
        ));
    }

    private static function calculateProfit(float $totalSellingPrice, float $buyingPrice, int $quantity): float
    {
        return $totalSellingPrice - ($buyingPrice * $quantity);
    }

    private static function calculateTotal(array $items, ?AmountTypeEnum $discountType, null|int|float|string $discountAmount, ?AmountTypeEnum $taxType, null|int|float|string $taxAmount): float
    {
        $subtotal = collect($items)->sum(fn(array $item): float => (float) ($item['total_selling_price'] ?? 0));

        $discount = self::calculate($subtotal, $discountType, (float) $discountAmount);
        $tax = self::calculate($subtotal, $taxType, (float) $taxAmount);

        return max(0.0, $subtotal + $tax - $discount);
    }

    private static function calculate(float $subtotal, ?AmountTypeEnum $type, float $amount): float
    {
        return match ($type) {
            AmountTypeEnum::PERCENTAGE => AmountTypeEnum::PERCENTAGE->resolve($subtotal, $amount),
            AmountTypeEnum::FIXED => AmountTypeEnum::FIXED->resolve($subtotal, $amount),
            default => 0.0,
        };
    }

}
