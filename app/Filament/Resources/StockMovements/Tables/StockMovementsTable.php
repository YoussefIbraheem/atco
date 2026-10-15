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
                  TextColumn::make("reason")
                    ->label(__("Reason"))
                    ->sortable()
                    ->searchable()
                    ->badge(),
                TextColumn::make("product.name")
                    ->label(__("Product"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("store.name")
                    ->label(__("Store"))
                    ->searchable()
                    ->sortable(),
                TextColumn::make("quantity")
                    ->label(__("Quantity"))
                    ->sortable(),
                TextColumn::make("user.name")
                    ->label(__("User")),
                TextColumn::make("created_at")
                    ->label(__("Issuing Date"))
                    ->date(),
                TextColumn::make("note")
                    ->label(__("Note")),

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
