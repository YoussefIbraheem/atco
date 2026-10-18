<?php

namespace App\Models;

use App\Enums\StockReasonEnum;
use App\Models\Party;
use App\Models\Product;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

#[Fillable(
    "product_id",
    "store_id",
    "quantity",
    "reason",
    "note",
    // "order_item_id",
    "party_id",
    "user_id",
)]
class StockMovement extends Model
{
    /** @use HasFactory<\Database\Factories\StockMovementFactory> */
    use HasFactory;


    #[Override]
    protected function casts()
    {
        return [
            "reason" => StockReasonEnum::class
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
