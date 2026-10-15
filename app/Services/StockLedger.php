<?php

namespace App\Services;

use App\Models\StockMovement;
use App\Services\DTOs\StockEntry;
use Illuminate\Support\Facades\DB;

final class StockLedger
{
    public function record(StockEntry $e)
    {
        $movement = StockMovement::create([
            'product_id' => $e->productId,
            'store_id' => $e->storeId,
            'quantity' => $e->quantity,
            'reason' => $e->reason,
            'user_id' => $e->userId,
            'party_id' => $e->partyId,
            'order_item_id' => $e->orderItemId,
            'note' => $e->note,
        ]);

        DB::table('product_store')->insertOrIgnore([
            'product_id' => $e->productId,
            'store_id' => $e->storeId,
            'quantity' => 0,
        ]);

        DB::table('product_store')->where('product_id', $e->productId)->where('store_id', $e->storeId)
            ->increment('quantity', $e->quantity);


        return $movement;
    }
}
