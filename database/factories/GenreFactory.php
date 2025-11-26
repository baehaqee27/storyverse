<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Genre>
 */
class GenreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $genre = fake()->unique()->randomElement([
            'Fantasy', 'Romance', 'Sci-Fi', 'Mystery', 'Horror', 
            'Adventure', 'Historical', 'Comedy', 'Drama', 'Action'
        ]);

        return [
            'name' => $genre,
            'slug' => \Illuminate\Support\Str::slug($genre),
            'description' => fake()->sentence(),
            'is_visible' => true,
        ];
    }
}
