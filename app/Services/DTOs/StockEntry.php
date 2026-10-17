<?php

namespace App\Services\DTOs;

use App\Enums\StockReasonEnum;

readonly class StockEntry
{

    public function __construct(
        public StockReasonEnum $reason,
        public int $productId,
        public int $storeId,
        public int $quantity,
        public int $userId,
        public ?int $partyId = null,
        public ?int $orderItemId = null,
        public ?string $note = null,
    ) {}
}
