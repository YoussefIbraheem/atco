<?php

namespace App\Filament\Resources\Parties\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PartiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->label(__("Name"))
                    ->sortable(),
                TextColumn::make("slug")
                    ->searchable()
                    ->hidden(),
                TextColumn::make("type")
                    ->label(__("Type"))
                    ->badge()
                    ->sortable()

            ])
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
