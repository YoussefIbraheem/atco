<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "sku" => fake()->isbn13(),
            "name" => fake()->word(),
            "slug" => fake()->slug(),
            "buying_price" => fake()->randomFloat(2),
            "selling_price" => fake()->randomFloat(2),
            "wholesale_price" => fake()->randomFloat(2),
            "total_amount" => fake()->randomNumber(),
            "provider_id" => fake()->randomNumber(),
            "type_id" => fake()->randomNumber(),
        ];
    }
}
