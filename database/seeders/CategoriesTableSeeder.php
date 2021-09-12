<?php

namespace Database\Seeders;

use App\Models\Category;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();

        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $title = $faker->title;
            Category::create([
                'name' => $title,
                'slug' => str_replace(' ', '-', $title)
            ]);
        }
    }
}
