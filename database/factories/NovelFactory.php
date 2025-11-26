<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Novel>
 */
class NovelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);
        return [
            'user_id' => \App\Models\User::factory(),
            'genre_id' => \App\Models\Genre::factory(),
            'title' => rtrim($title, '.'),
            'slug' => \Illuminate\Support\Str::slug($title),
            'synopsis' => fake()->paragraph(3),
            'cover_image' => null, // Or use a placeholder URL if we want external images, but local storage is safer to leave null or seed specifically
            'status' => fake()->randomElement(['ongoing', 'completed', 'hiatus']),
            'is_published' => true,
        ];
    }
}
