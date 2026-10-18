<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Override;

class PlaceSaleOrder extends Page
{
    protected string $view = 'filament.pages.place-sale-order';
    protected static ?string $slug= 'sale-order/place';

    #[Override]
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    #[Override]
    public function getTitle(): string|Htmlable
    {
        return __("Place Sale Order");
    }


}
