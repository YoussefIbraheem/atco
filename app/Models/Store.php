<?php

namespace App\Models;

use App\Traits\SluggableArabic;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable("name", "slug")]
class Store extends Model
{
    /** @use HasFactory<\Database\Factories\StoreFactory> */
    use HasFactory, SluggableArabic;

    protected function sluggable(): array
    {
        return [
            'source' => 'name',
            'target' => 'slug',
        ];
    }


    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot(['id','quantity']);
    }
}
