<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;
use Override;

enum PartyTypeEnum: string implements HasLabel, HasColor
{
    case CLIENT = "client";
    case PRODVIDER = "provider";
    case RESELLER = "reseller";

    public function getLabel(): string | Htmlable | null
    {
        return match($this){
            self::CLIENT => __("Client"),
            self::PRODVIDER => __("Provider"),
            self::RESELLER => __("Reseller")
        };

    }

    #[Override]
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::CLIENT => 'success',
            self::PRODVIDER => 'warning',
            self::RESELLER => 'info'
        };
    }
}
