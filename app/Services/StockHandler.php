<?php

namespace App\Services;

use App\Enums\StockReasonEnum;
use App\Services\DTOs\StockEntry;
use Illuminate\Support\Facades\DB;

final class StockHandler
{

    public function __construct(private StockLedger $ledger) {}

    public function restock(int $productId, int $partyId, int $userId, array $distribution): void
    {
        DB::transaction(function () use ($productId, $partyId, $userId, $distribution) {
            foreach ($distribution as $row) {
                $entry = new StockEntry(
                    reason: StockReasonEnum::PURCHASE,
                    productId: $productId,
                    partyId: $partyId,
                    userId: $userId,
                    quantity: $row["quantity"],
                    storeId: $row["store_id"]
                );

                $this->ledger->record($entry);
            }
        });
    }

    public function processSale(int $partyId, int $userId, array $distribution)
    {
        DB::transaction(function () use ($partyId, $userId, $distribution) {
            foreach ($distribution as $row) {
                $entry = new StockEntry(
                    reason: StockReasonEnum::SALE,
                    productId: $row["stockDistribution"],
                    storeId: $row["store_id"],
                    partyId: $partyId,
                    userId: $userId,
                    quantity: $row["quantity"],
                );

                $this->ledger->record($entry);
            }
        });
    }
}
