<?php

namespace App\Models;

use App\Enums\PartyTypeEnum;
use App\Traits\SluggableArabic;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

#[Fillable("name", "slug", "type")]
class Party extends Model
{
    /** @use HasFactory<\Database\Factories\PartyFactory> */
    use HasFactory, SluggableArabic;

    protected function sluggable(): array
    {
        return [
            'source' => 'name',
            'target' => 'slug',
        ];
    }

    #[Override]
    protected function casts()
    {
        return [
            "type" => PartyTypeEnum::class
        ];
    }
}
