<?php

namespace Database\Seeders;

use App\Models\Post;
use Faker\Factory;
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
        Post::truncate();

        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $title = $faker->sentence(3);
            Post::create([
                'user_id' => rand(1, 10),
                'category_id' => rand(1, 4),
                'title' => $title,
                'slug' => str_replace(' ', '-', strtolower($title)),
                'description' => $faker->text(100)
            ]);
        }
    }
}
