<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Override;

class RestockProducts extends Page
{
    protected string $view = 'filament.pages.restock-products';
    protected static ?string $slug = 'products/restock';

    #[Override]
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    #[Override]
    public function getTitle(): string|Htmlable
    {
        return __("Restock Products");
    }
}
