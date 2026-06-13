<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
use App\Models\Category;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 10, 500),
            'stock' => fake()->numberBetween(1, 100),

            // imagen placeholder
            'image' => 'https://via.placeholder.com/300',

            // relacion con categoría
            'category_id' => Category::inRandomOrder()->first()->id,

            // activar productos
            'is_active' => true,
        ];
    }
}
