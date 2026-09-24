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
            'name' => fake()->randomElement(['Cola', 'Orange', 'Lemon Lime', 'Ginger Ale', 'Root Beer']),
            'brand' => fake()->randomElement(['Coca-Cola', 'Pepsi', 'Fanta', 'Sprite']),
            'size' => fake()->randomElement(['330ml', '500ml', '1L', '2L']),
            'stock_quantity' => fake()->numberBetween(20, 100),
            'price' => fake()->randomFloat(2, 0.5, 5),
        ];
    }
}
