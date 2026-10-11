<?php

namespace App\Filament\Resources\Stores\Schemas;

use App\Rules\UniqueArabicSlug;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                  TextInput::make("name")
                  ->label(__("Name"))
                  ->rules([fn($context, $record) => new UniqueArabicSlug($context, $record)])
            ]);
    }
}
