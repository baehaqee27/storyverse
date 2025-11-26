<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin
        User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@storyverse.test',
            'password' => bcrypt('password'),
        ]);

        // 2. Create Genres
        $genres = \App\Models\Genre::factory()->count(8)->create();

        // 3. Create Authors
        $authors = User::factory(5)->create();

        // 4. Create Novels for each Author
        foreach ($authors as $author) {
            $novels = \App\Models\Novel::factory(3)->create([
                'user_id' => $author->id,
                'genre_id' => $genres->random()->id,
            ]);

            // 5. Create Chapters for each Novel
            foreach ($novels as $novel) {
                for ($i = 1; $i <= 5; $i++) {
                    \App\Models\Chapter::factory()->create([
                        'novel_id' => $novel->id,
                        'title' => "Chapter $i: " . fake()->words(3, true),
                        'order' => $i,
                    ]);
                }
            }
        }
    }
}
