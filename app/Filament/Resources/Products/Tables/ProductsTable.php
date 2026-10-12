<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                TextColumn::make('buying_price')
                    ->label(__("Price")." ".__("Buying"))
                    ->money("EGP")
                    ->sortable(),
                TextColumn::make('selling_price')
                    ->label(__("Price")." ".__("Selling"))
                    ->money("EGP")
                    ->sortable(),
                TextColumn::make('wholesale_price')
                    ->label(__("Price")." ".__("Wholesale"))
                    ->money("EGP")
                    ->sortable(),
                TextColumn::make('productType.name')
                    ->label(__("Product Type"))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
