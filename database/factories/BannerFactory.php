<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
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
            'link' => fake()->url(),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Banner $banner) {
            // ...
        })->afterCreating(function (Banner $banner) {
            $url = fake()->imageUrl(width: 600, height: 300);

            $banner->addMediaFromUrl($url)
                ->toMediaCollection('cover');
        });
    }
}
