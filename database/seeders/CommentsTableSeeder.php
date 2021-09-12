<?php

namespace Database\Seeders;

use App\Models\Comment;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::trunicate();

        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            Comment::create([
                'post_id' => rand(1,10),
                'user_id' => rand(1,10),
                'text' => $faker->sentence
            ]);
        }
    }
}
