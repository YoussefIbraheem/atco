<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AmountTypeEnum: string implements HasLabel
{
    case FIXED = 'fixed';
    case PERCENTAGE = 'percentage';

    public function getLabel(): string
    {
        return match ($this) {
            self::FIXED => __('Fixed'),
            self::PERCENTAGE => __('Percentage'),
        };
    }

    /** Resolve the value of this amount against a base (e.g. the subtotal). */
    public function resolve(float $base, float $amount): float
    {
        return match ($this) {
            self::FIXED => $amount,
            self::PERCENTAGE => $base * ($amount / 100),
        };
    }
}
