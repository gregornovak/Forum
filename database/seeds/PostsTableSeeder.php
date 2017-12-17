<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            'body' => str_random(250),
            'user_id' => 1,
            'thread_id' => 1,
        ]);
    
    }
}
