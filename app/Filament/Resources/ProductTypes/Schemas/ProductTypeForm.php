<?php

namespace App\Filament\Resources\ProductTypes\Schemas;

use App\Helpers\ArabicSlugger;
use App\Models\ProductType;
use App\SluggableArabic;
use Closure;
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
                    ->live(onBlur: true)
                    ->rules([
                        fn($context, $record): Closure => function (string $attribute, $value, Closure $fail) use ($context, $record) {
                            $slugged = ArabicSlugger::handle($value);
                            $exists = ProductType::query()->where("slug", $slugged)->first();
                            if ($exists) {
                                if ($context == "create") {
                                    $fail("نوع المنتج موجود بالفعل");
                                }

                                if ($context == "edit") {
                                    if ($record->id != $exists->id) {
                                        $fail("نوع المنتج موجود بالفعل");
                                    }
                                }
                            }
                        }
                    ])
                    ->required(),
            ]);
    }
}
