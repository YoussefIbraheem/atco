<?php

namespace App\Filament\Resources\Parties\Schemas;

use App\Enums\PartyTypeEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PartyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("name")
                ->label(__("Name"))
                ->required(),
                Select::make("type")
                ->label(__("Type"))
                ->required()
                ->options(PartyTypeEnum::class)
                ->enum(PartyTypeEnum::class)

            ]);
    }
}
