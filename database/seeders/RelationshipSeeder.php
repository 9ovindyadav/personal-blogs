<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $relations = [
            [
                'parent' => 'user',
                'child' => 'contact',
                'type' => '1:M',
                'pivot_table' => 'user_contact'
            ],
            [
                'parent' => 'user',
                'child' => 'project',
                'type' => '1:1',
                'pivot_table' => 'user_project'
            ],
            [
                'parent' => 'user',
                'child' => 'message',
                'type' => '1:1',
                'pivot_table' => ''
            ],
            [
                'parent' => 'project',
                'child' => 'task',
                'type' => '1:M',
                'pivot_table' => 'project_task'
            ]
        ];
        foreach($relations as $relation){
            DB::table('relationships')->insert($relation);
        }

        
    }
}
