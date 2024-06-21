<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Blog;
use App\Models\User;
use App\Models\Category;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Blog::factory()
            ->count(10)
            ->sequence(function($sequence) {
                return [
                    'author_id' => User::all()->random(),
                    'category_id' => Category::all()->random()
                ];
            })
            ->create();
    }
}
