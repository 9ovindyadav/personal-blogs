<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = $this->faker->words(3, true);
        return [
            'name' => $category,
            'slug' => Str::of($category)->slug('-')
        ];
    }
}
