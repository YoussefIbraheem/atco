<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\RoleEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("name"),
                TextColumn::make("email"),
                TextColumn::make("roles.name")
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {
                            RoleEnum::ADMIN->value => 'warning',
                            RoleEnum::USER->value => 'success',
                            default => 'gray',
                        },
                    )
                    ->label('Role'),
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
