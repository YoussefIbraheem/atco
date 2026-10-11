<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\RoleEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
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
                TextColumn::make("name")
                    ->label(__("Name")),
                TextColumn::make("email")
                    ->label(__("Email")),
                TextColumn::make("roles.name")
                    ->label(__("Role"))
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {
                            RoleEnum::ADMIN->value => 'warning',
                            RoleEnum::USER->value => 'success',
                            default => 'gray',
                        },
                    )
                    ->label(__('Role')),
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
