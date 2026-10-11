<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\RoleEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name")
                    ->label(__("Name")),
                TextColumn::make("email")
                    ->label(__("Email")),
                TextColumn::make("roles.name")
                    ->label(__("Role"))
                    ->badge()
                    ->formatStateUsing(fn($state) => RoleEnum::getLabel($state))
                    ->color(fn($state) => RoleEnum::getColor($state))
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([]);
    }
}
