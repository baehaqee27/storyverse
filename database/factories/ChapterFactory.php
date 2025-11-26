<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chapter>
 */
class ChapterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);
        return [
            'novel_id' => \App\Models\Novel::factory(),
            'title' => rtrim($title, '.'),
            'slug' => \Illuminate\Support\Str::slug($title),
            'content' => collect(fake()->paragraphs(5))->map(fn($p) => "<p>$p</p>")->implode(''),
            'order' => fake()->numberBetween(1, 100),
            'is_published' => true,
            'published_at' => now(),
        ];
    }
}
