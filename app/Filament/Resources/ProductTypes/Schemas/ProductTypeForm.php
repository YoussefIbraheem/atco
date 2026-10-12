<?php

namespace App\Filament\Resources\ProductTypes\Schemas;

use App\Helpers\ArabicSlugger;
use App\Models\ProductType;
use App\Rules\UniqueArabicSlug;
use App\SluggableArabic;
use Closure;
use Dom\Text;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__("Name"))
                    ->live(onBlur: true)
                    ->rules([fn($context, $record) => new UniqueArabicSlug($context, $record)])
                    ->required(),
                TextInput::make('unit_measure')
                    ->label(__("Unit Measure"))
            ]);
    }
}
