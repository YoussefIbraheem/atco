<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\Attributes\Sluggable;

#[Fillable(
    "sku",
    "name",
    "slug",
    "buying_price",
    "selling_price",
    "wholesale_price",
    "total_amount",
    "provider_id",
    "type_id"
)]
class Product extends Model
{

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        "buying_price" => "decimal:2",
        "selling_price" => "decimal:2",
        "wholesale_price" => "decimal:2",
    ];


    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }
}
