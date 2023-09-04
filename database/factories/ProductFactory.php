<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
            'name' => fake()->text(rand(5, 100)),
            'price' => fake()->randomFloat(2, 0.1, 10000),
            'content' => fake()->randomHtml(),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Product $product) {
            // ...
        })->afterCreating(function (Product $product) {
            $url = fake()->imageUrl(width: 300, height: 300);

            $product->addMediaFromUrl($url)
                ->toMediaCollection('cover');
        });
    }
}
