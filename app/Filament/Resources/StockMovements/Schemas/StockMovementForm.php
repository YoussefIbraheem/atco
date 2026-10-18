<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use App\Enums\StockReasonEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make("product_id")
                    ->label(__("Product"))
                    ->relationship("product", "name")
                    ->preload()
                    ->searchable(),
                Select::make("store_id")
                    ->label(__("Store"))
                    ->relationship("store", "name")
                    ->preload()
                    ->searchable(),
                TextInput::make("quantity")
                    ->label(__("Quantity"))
                    ->numeric(),
                Select::make("reason")
                    ->enum(StockReasonEnum::class)
                    ->options(StockReasonEnum::class),
                Select::make("party_id")
                    ->label(__("Party"))
                    ->relationship("party", "name")
                    ->preload()
                    ->searchable()
            ]);
    }
}
