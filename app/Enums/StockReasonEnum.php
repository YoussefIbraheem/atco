<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;
use Override;

enum StockReasonEnumEnum: string implements HasLabel
{
    case PURCHASE = 'purchase';
    case SALE = 'sale';
    case CUSTOMER_RETURN = 'customer_return';
    case SUPLLIER_RETURN = 'supplier_return';
    case HANDOVER = 'handover';
    case HANDOVER_RETURN = 'handover_return';
    case TRANSFER = 'transfer';
    case ADJUSTMENT = 'adjustment';

    #[Override]
    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::PURCHASE => __("Purchase"),
            self::SALE => __("Sale"),
            self::CUSTOMER_RETURN => __("Customer Return"),
            self::SUPLLIER_RETURN => __("Supplier Return"),
            self::HANDOVER => __("Handover"),
            self::HANDOVER_RETURN => __("Handover Return"),
            self::TRANSFER => __("Transfer"),
            self::ADJUSTMENT => __("Adjustment"),
        };
    }
}
