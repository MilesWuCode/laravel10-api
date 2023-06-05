<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(rand(5, 200)),
            'content' => fake()->paragraph(200),
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Post $post) {
            // ...
        })->afterCreating(function (Post $post) {
            $url = fake()->imageUrl(width: 820, height: 320);

            $post->addMediaFromUrl($url)
                ->toMediaCollection('cover');
        });
    }
}
