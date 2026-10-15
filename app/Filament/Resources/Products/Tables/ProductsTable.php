<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->label(__("SKU"))
                    ->searchable(),
                TextColumn::make('name')
                    ->label(__("Name"))
                    ->searchable(),
                TextColumn::make('slug')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('productType.name')
                    ->label(__("Product Type"))
                    ->searchable(),
                ColumnGroup::make("Price")
                    ->columns([
                        TextColumn::make('buying_price')
                            ->label(__("Buying Price"))
                            ->money("EGP")
                            ->sortable(),
                        TextColumn::make('selling_price')
                            ->label(__("Selling Price"))
                            ->money("EGP")
                            ->sortable(),
                        TextColumn::make('wholesale_price')
                            ->label(__("Wholesale Price"))
                            ->money("EGP")
                            ->sortable(),
                    ])->alignCenter(),
                TextColumn::make("totalQuantity")
                    ->label(__("Total Quantity"))
                    ->state(fn (Model $record)=> $record->stores->sum("pivot.quantity"))
                //->formatStateUsing(fn($record)=>dd($record->stores))
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
