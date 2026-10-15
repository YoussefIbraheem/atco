<?php

namespace App\Services\DTOs;

use App\Enums\StockReason;

readonly class StockEntry
{

    public function __construct(
        public StockReason $reason,
        public int $productId,
        public int $storeId,
        public int $quantity,
        public int $userId,
        public ?int $partyId = null,
        public ?int $orderItemId = null,
        public ?string $note = null,
    ) {}
}
