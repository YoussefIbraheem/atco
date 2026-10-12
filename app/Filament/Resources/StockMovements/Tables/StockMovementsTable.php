<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("product.name")
                    ->label(__("Product")),
                TextColumn::make("store.name")
                    ->label(__("Store")),
                TextColumn::make("quantity")
                    ->label(__("Quantity")),
                TextColumn::make("reason")
                    ->label(__("Reason")),
                TextColumn::make("note")
                    ->label(__("Note")),
                TextColumn::make("user.name")
                    ->label(__("User")),

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
