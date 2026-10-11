<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;
use Override;

enum RoleEnum: string

{
    case ADMIN = "admin";
    case USER = "user";

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => __("Admin"),
            self::USER  => __("User")
        };
    }


    public static function getLabel(string $role): string|null
    {
        return match ($role) {
            self::ADMIN->value => __("Admin"),
            self::USER->value  => __("User")
        };
    }


    public static function getColor(string $role): string|null
    {
        return match ($role) {
            self::ADMIN->value => 'warning',
            self::USER->value => 'success',
        };
    }
}
