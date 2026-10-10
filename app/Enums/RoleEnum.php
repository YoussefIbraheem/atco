<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RoleEnum: string implements HasLabel

{
    case ADMIN = "admin";
    case USER = "user";

    public function getLabel(): string
    {
        return $this->name;
    }
}
