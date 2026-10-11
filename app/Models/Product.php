<?php

namespace App\Models;

use App\Traits\SluggableArabic;
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
    "product_type_id"
)]
class Product extends Model
{

    use SluggableArabic;

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

    protected function sluggable(): array
    {
        return [
            'source' => 'name',
            'target' => 'slug',
        ];
    }


    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }
}
