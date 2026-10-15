<?php

namespace App\Services;

use App\Enums\StockReason;
use Illuminate\Support\Facades\DB;

class StockMovementHandler
{

    public static function processRestock(int $productId, int $partyId, array $stockData, int $userId)
    {

        $movementData = [];
        $distributionInfo=[];

        foreach ($stockData as $data) {
            $movementData[] = [
                "product_id" => $productId,
                "party_id" => $partyId,
                "store_id" => $data["store_id"],
                "quantity" => $data["quantity"],
                "reason" => StockReason::PURCHASE->value,
                "user_id" => $userId,
                "created_at" => now()
            ];
            $distributionInfo[] = [
                "product_id" => $productId,
                "store_id" => $data["store_id"],
                "quantity" => $data["quantity"]
            ];
        }

        DB::transaction(function () use ($distributionInfo, $movementData) {
            DB::table("product_store")->insert($distributionInfo);
            DB::table("stock_movements")->insert($movementData);
        });
    }
}
