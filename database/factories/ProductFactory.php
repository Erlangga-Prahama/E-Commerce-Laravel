<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\Category;
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
        $name = fake()->unique()->words(3, true);
        return [
            'category_id' => Category::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(10000, 500000), // Rp10.000 - Rp500.000
            'stock' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
