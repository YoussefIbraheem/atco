<?php

namespace App\Filament\Resources\Users\Schemas;

use Dom\Text;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__("Name"))
                    ->required(),
                TextInput::make('email')
                    ->label(__("Email"))
                    ->email()
                    ->unique()
                    ->required(),
                TextInput::make('password')
                    ->label(__("Password"))
                    ->visibleOn('create')
                    ->password()
                    ->confirmed()
                    ->revealable()
                    ->required(),
                TextInput::make('password_confirmation')
                    ->label(__("Password Confirmation"))
                    ->visibleOn('create')
                    ->password()
                    ->revealable()
                    ->required(),
                Toggle::make('update_password')
                    ->label(__("Update Password"))
                    ->inlineLabel()
                    ->visibleOn('edit')
                    ->live(),

                Section::make()
                    ->label(__("Update Password"))
                    ->inlineLabel()
                    ->columns(1)
                    ->visible(fn(Get $get) => $get('update_password'))
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('password')
                            ->label(__("Password"))
                            ->password()
                            ->confirmed()
                            ->revealable()
                            ->required(),
                        TextInput::make('password_confirmation')
                            ->label(__("Password Confirmation"))
                            ->password()
                            ->revealable()
                            ->required(),
                    ]),
            ]);
    }
}
