<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;
use Override;

enum StockReason: string implements HasLabel
{
    case PURCHASE = 'purchase';
    case SALE = 'sale';
    case CUSTOMERRETURN = 'customer_return';
    case SUPPLIERRETUREN = 'supplier_return';
    case HANDOVER = 'handover';
    case HANDOVERRETURN = 'handover_return';
    case TRANSFER = 'transfer';
    case ADJUSTMENT = 'adjustment';

    #[Override]
    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::PURCHASE => __("Purchase"),
            self::SALE => __("Sale"),
            self::CUSTOMERRETURN => __("Customer Return"),
            self::SUPPLIERRETUREN => __("Supplier Return"),
            self::HANDOVER => __("Handover"),
            self::HANDOVERRETURN => __("Handover Return"),
            self::TRANSFER => __("Transfer"),
            self::ADJUSTMENT => __("Adjustment"),
        };
    }
}
