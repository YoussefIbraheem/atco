<?php

namespace App\Models;

use App\Models\Product;
use App\Traits\SluggableArabic;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable("name", "slug")]
class ProductType extends Model
{
    /** @use HasFactory<\Database\Factories\ProductTypeFactory> */
    use HasFactory, SluggableArabic;

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    protected function sluggable(): array
    {
        return [
            'source' => 'name',
            'target' => 'slug',
        ];
    }
}
