<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Govind Yadav',
            'username' => '9ovindyadav',
            'email' => 'govind@gmail.com',
            'password' => 'asdfghjkl',
            'profession' => 'Software Developer',
            'is_admin' => true
        ]);

        User::factory()->count(4)->create();
    }
}
