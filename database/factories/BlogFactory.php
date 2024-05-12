<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Category;

class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->words(12, true);
        return [
            'title' => $title,
            'slug' => Str::of($title)->slug('-'),
            'extends' => $this->faker->paragraph(5),
            'body' => $this->faker->paragraph(50),
            'published_at' => now(),
            'author_id' => User::factory(),
            'category_id' => Category::factory()
        ];
    }
}
