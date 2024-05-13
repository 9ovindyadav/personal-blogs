<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Category;
use App\Models\Blog;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Govind Yadav',
            'username' => '9ovindyadav',
            'email' => 'govindsvyadav@gmail.com',
            'password' => 'asdfghjkl'
        ]);
        $category = Category::factory()->create([
            'name' => 'Computer Science',
            'slug' => 'computer-science'
        ]);

        $blog = Blog::factory(50)->create([
            'author_id' => $user->id,
            'category_id' => $category->id
        ]);

    }
}
