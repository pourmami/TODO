<?php

namespace Database\Seeders;

use App\Models\Tag;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::truncate();

        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            Tag::create([
                'name' => $faker->word
            ]);
        }
    }
}
